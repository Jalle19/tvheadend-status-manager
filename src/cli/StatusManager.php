<?php

namespace Jalle19\StatusManager;

use Jalle19\StatusManager\Database;
use Jalle19\StatusManager\Event\ConnectionSeenEvent;
use Jalle19\StatusManager\Event\Events;
use Jalle19\StatusManager\Event\InputSeenEvent;
use Jalle19\StatusManager\Event\InstanceSeenEvent;
use Jalle19\StatusManager\Event\InstanceStatusUpdatesEvent;
use Jalle19\StatusManager\Event\SubscriptionSeenEvent;
use Jalle19\StatusManager\Event\SubscriptionStateChangeEvent;
use Jalle19\StatusManager\Subscription\StateChangeParser;
use Psr\Log\LoggerInterface;
use Ratchet\Http\HttpServer;
use Ratchet\Server\IoServer;
use Ratchet\WebSocket\WsServer;
use Symfony\Component\EventDispatcher\EventDispatcher;

/**
 * Class StatusManager
 * @package   Jalle19\StatusManager
 * @copyright Copyright &copy; Sam Stenvall 2015-
 * @license   https://www.gnu.org/licenses/gpl.html The GNU General Public License v2.0
 */
class StatusManager
{

	/**
	 * The number of cycles to wait until retrying an unreachable instance
	 */
	const UNREACHABLE_CYCLES_UNTIL_RETRY = 10;

	/**
	 * @var Configuration the configuration
	 */
	private $_configuration;

	/**
	 * @var \SplObjectStorage the instances to connect to and their individual state
	 */
	private $_instances;

	/**
	 * @var LoggerInterface the logger
	 */
	private $_logger;

	/**
	 * @var IoServer the Websocket server
	 */
	private $_websocket;

	/**
	 * @var WebSocketManager
	 */
	private $_webSocketManager;

	/**
	 * @var EventDispatcher
	 */
	private $_eventDispatcher;

	/**
	 * @var PersistenceManager the persistence manager
	 */
	private $_persistenceManager;


	/**
	 * StatusManager constructor.
	 *
	 * @param Configuration   $configuration
	 * @param LoggerInterface $logger
	 */
	public function __construct(Configuration $configuration, LoggerInterface $logger)
	{
		$this->_configuration = $configuration;
		$this->_logger        = $logger;
		$this->_instances     = new \SplObjectStorage();

		// Attach a state to each instance
		foreach ($this->_configuration->getInstances() as $instance)
			$this->_instances->attach($instance, new InstanceState());

		// Create manager instances
		$this->_persistenceManager = new PersistenceManager($logger);
		$this->_webSocketManager   = new WebSocketManager($logger);

		// Configure the event dispatcher
		$this->configureEventDispatcher();
	}


	/**
	 * Configures the event dispatcher and attaches event listeners to it
	 */
	private function configureEventDispatcher()
	{
		$this->_eventDispatcher = new EventDispatcher();

		$eventDefinitions = [
			[Events::MAIN_LOOP_STARTING, $this->_persistenceManager, 'onMainLoopStarted'],
			[Events::INSTANCE_STATUS_UPDATES, $this->_webSocketManager, 'onInstanceStatusUpdates'],
			[Events::INSTANCE_SEEN, $this->_persistenceManager, 'onInstanceSeen'],
			[Events::CONNECTION_SEEN, $this->_persistenceManager, 'onConnectionSeen'],
			[Events::INPUT_SEEN, $this->_persistenceManager, 'onInputSeen'],
			[Events::SUBSCRIPTION_SEEN, $this->_persistenceManager, 'onSubscriptionSeen'],
			[Events::SUBSCRIPTION_STATE_CHANGE, $this->_persistenceManager, 'onSubscriptionStateChange'],
		];

		foreach ($eventDefinitions as $eventDefinition)
			$this->_eventDispatcher->addListener($eventDefinition[0], [$eventDefinition[1], $eventDefinition[2]]);
	}


	/**
	 * Runs the application
	 */
	public function run()
	{
		// Configure the WebSocket server
		$address = $this->_configuration->getListenAddress();
		$port    = $this->_configuration->getListenPort();

		$this->_websocket = IoServer::factory(
			new HttpServer(new WsServer($this->_webSocketManager)),
			$port,
			$address
		);

		// Add the instance polling mechanism to the event loop
		$this->_websocket->loop->addPeriodicTimer($this->_configuration->getUpdateInterval(),
			[$this, 'handleInstanceUpdates']);

		// Log information about the database
		$this->_logger->debug('Using database at {databasePath}', [
			'databasePath' => $this->_configuration->getDatabasePath(),
		]);

		// Log information about the configured instances
		$instances = $this->_configuration->getInstances();

		$this->_logger->info('Managing {instances} instances:', [
			'instances' => count($instances),
		]);

		foreach ($instances as $configuredInstance)
		{
			$instance = $configuredInstance->getInstance();

			$this->_logger->info('  {address}:{port}', [
				'address' => $instance->getHostname(),
				'port'    => $instance->getPort(),
			]);

			$this->_eventDispatcher->dispatch(Events::INSTANCE_SEEN, new InstanceSeenEvent($instance));
		}

		$this->_eventDispatcher->dispatch(Events::MAIN_LOOP_STARTING);

		// Start the main loop
		$this->_logger->info('Starting the Websocket server on {address}:{port}', [
			'address' => $address,
			'port'    => $port,
		]);

		$this->_websocket->run();
	}


	/**
	 * Handles the updates polled from the instances
	 */
	public function handleInstanceUpdates()
	{
		$statusCollection = $this->getStatusMessages();

		foreach ($statusCollection->getInstanceStatuses() as $instanceStatus)
		{
			$instanceName = $instanceStatus->getInstanceName();

			$this->_logger->debug('Got status updates from {instanceName}', [
				'instanceName' => $instanceName,
			]);

			// Persist connections
			foreach ($instanceStatus->getConnections() as $connection)
			{
				$this->_eventDispatcher->dispatch(Events::CONNECTION_SEEN,
					new ConnectionSeenEvent($instanceName, $connection));
			}

			// Persist inputs
			foreach ($instanceStatus->getInputs() as $input)
				$this->_eventDispatcher->dispatch(Events::INPUT_SEEN, new InputSeenEvent($instanceName, $input));

			// Persist running subscriptions
			foreach ($instanceStatus->getSubscriptions() as $subscription)
			{
				$this->_eventDispatcher->dispatch(Events::SUBSCRIPTION_SEEN,
					new SubscriptionSeenEvent($instanceName, $subscription));
			}

			// Handle subscription state changes
			foreach ($instanceStatus->getSubscriptionStateChanges() as $subscriptionStateChange)
			{
				$this->_eventDispatcher->dispatch(Events::SUBSCRIPTION_STATE_CHANGE,
					new SubscriptionStateChangeEvent($instanceName, $subscriptionStateChange));
			}
		}

		$this->_eventDispatcher->dispatch(Events::INSTANCE_STATUS_UPDATES,
			new InstanceStatusUpdatesEvent($statusCollection));
	}


	/**
	 * Retrieves and returns all status messages for the configured
	 * instances
	 * @return InstanceStatusCollection
	 */
	private function getStatusMessages()
	{
		$collection = new InstanceStatusCollection();

		foreach ($this->_instances as $instance)
		{
			/* @var Instance $instance */
			$tvheadend    = $instance->getInstance();
			$instanceName = $instance->getName();

			/* @var InstanceState $instanceState */
			$instanceState = $this->_instances[$instance];

			// Collect statuses from currently reachable instances
			if ($instanceState->isReachable())
			{
				try
				{
					$collection->add(new InstanceStatus(
						$instanceName,
						$tvheadend->getInputStatus(),
						$tvheadend->getSubscriptionStatus(),
						$tvheadend->getConnectionStatus(),
						StateChangeParser::parseStateChanges($tvheadend->getLogMessages())));

					// Update reachability state now that we know the instance is reachable
					if ($instanceState->getReachability() === InstanceState::MAYBE_REACHABLE)
					{
						$this->_logger->info('Instance {instanceName} is now reachable, will start polling for updates',
							[
								'instanceName' => $instanceName,
							]);

						$instanceState->setReachability(InstanceState::REACHABLE);
					}
				}
				catch (\Exception $e)
				{
					// Mark the instance as unreachable
					$message = 'Instance {instanceName} not reachable, will wait for {cycles} cycles before retrying.
								The exception was: {exception}';

					$this->_logger->alert($message, [
						'instanceName' => $instanceName,
						'cycles'       => self::UNREACHABLE_CYCLES_UNTIL_RETRY,
						'exception'    => $e->getMessage(),
					]);

					$instanceState->setReachability(InstanceState::UNREACHABLE);
				}
			}
			else
			{
				// Wait for some cycles and then mark unreachable instances as maybe reachable
				if ($instanceState->getRetryCount() === self::UNREACHABLE_CYCLES_UNTIL_RETRY - 1)
				{
					$instanceState->setReachability(InstanceState::MAYBE_REACHABLE);
					$instanceState->resetRetryCount();

					$this->_logger->info('Retrying instance {instanceName} during next cycle', [
						'instanceName' => $instanceName,
					]);
				}
				else
					$instanceState->incrementRetryCount();
			}
		}

		return $collection;
	}

}
