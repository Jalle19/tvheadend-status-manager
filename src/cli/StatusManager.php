<?php

namespace Jalle19\StatusManager;

use Jalle19\StatusManager\Event\ConnectionSeenEvent;
use Jalle19\StatusManager\Event\Events;
use Jalle19\StatusManager\Event\InputSeenEvent;
use Jalle19\StatusManager\Event\InstanceSeenEvent;
use Jalle19\StatusManager\Event\InstanceStateEvent;
use Jalle19\StatusManager\Event\InstanceStatusUpdatesEvent;
use Jalle19\StatusManager\Event\SubscriptionSeenEvent;
use Jalle19\StatusManager\Event\SubscriptionStateChangeEvent;
use Jalle19\StatusManager\Subscription\StateChangeParser;
use Psr\Log\LoggerInterface;
use React\EventLoop\Factory;
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
	 * @var Configuration the configuration
	 */
	private $_configuration;

	/**
	 * @var LoggerInterface the logger
	 */
	private $_logger;

	/**
	 * @var InstanceStateManager
	 */
	private $_instanceStateManager;

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
	}


	/**
	 * Runs the application
	 */
	public function run()
	{
		// Configure the main event loop
		$eventLoop = Factory::create();
		$eventLoop->addPeriodicTimer($this->_configuration->getUpdateInterval(),
			[$this, 'handleInstanceUpdates']);

		// Configure managers
		$this->_instanceStateManager = new InstanceStateManager($this->_logger, $this->_configuration->getInstances());
		$this->_webSocketManager     = new WebSocketManager($this->_logger, $this->_configuration, $eventLoop);
		$this->_persistenceManager   = new PersistenceManager($this->_logger);

		$this->configureEventDispatcher();

		$this->_eventDispatcher->dispatch(Events::MAIN_LOOP_STARTING);
		$eventLoop->run();
	}


	/**
	 * Configures the event dispatcher and attaches event listeners to it
	 */
	private function configureEventDispatcher()
	{
		$this->_eventDispatcher = new EventDispatcher();

		$eventDefinitions = [
			[Events::MAIN_LOOP_STARTING, $this, 'onMainLoopStarted'],
			[Events::MAIN_LOOP_STARTING, $this->_persistenceManager, 'onMainLoopStarted'],
			[Events::MAIN_LOOP_STARTING, $this->_webSocketManager, 'onMainLoopStarted'],
			[Events::INSTANCE_STATUS_UPDATES, $this->_webSocketManager, 'onInstanceStatusUpdates'],
			[Events::INSTANCE_STATE_REACHABLE, $this->_instanceStateManager, 'onInstanceReachable'],
			[Events::INSTANCE_STATE_UNREACHABLE, $this->_instanceStateManager, 'onInstanceUnreachable'],
			[Events::INSTANCE_STATE_MAYBE_REACHABLE, $this->_instanceStateManager, 'onInstanceMaybeReachable'],
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
	 * Called right before the main loop is started
	 */
	public function onMainLoopStarted()
	{
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
		$instances  = $this->_instanceStateManager->getInstances();
		$collection = new InstanceStatusCollection();

		foreach ($instances as $instance)
		{
			/* @var Instance $instance */
			$tvheadend    = $instance->getInstance();
			$instanceName = $instance->getName();

			// Collect statuses from currently reachable instances
			if ($this->_instanceStateManager->isReachable($instance))
			{
				try
				{
					$collection->add(new InstanceStatus(
						$instanceName,
						$tvheadend->getInputStatus(),
						$tvheadend->getSubscriptionStatus(),
						$tvheadend->getConnectionStatus(),
						StateChangeParser::parseStateChanges($tvheadend->getLogMessages())));

					$this->_eventDispatcher->dispatch(Events::INSTANCE_STATE_REACHABLE,
						new InstanceStateEvent($instance));
				}
				catch (\Exception $e)
				{
					$this->_eventDispatcher->dispatch(Events::INSTANCE_STATE_UNREACHABLE,
						new InstanceStateEvent($instance));
				}
			}
			else
			{
				$this->_eventDispatcher->dispatch(Events::INSTANCE_STATE_MAYBE_REACHABLE,
					new InstanceStateEvent($instance));
			}
		}

		return $collection;
	}

}
