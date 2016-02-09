<?php

namespace Jalle19\StatusManager;

use Jalle19\StatusManager\Subscription\StateChangeParser;
use Psr\Log\LoggerInterface;
use Ratchet\ConnectionInterface;
use Ratchet\Http\HttpServer;
use Ratchet\MessageComponentInterface;
use Ratchet\Server\IoServer;
use Ratchet\WebSocket\WsServer;

/**
 * Class StatusManager
 * @package   Jalle19\StatusManager
 * @copyright Copyright &copy; Sam Stenvall 2015-
 * @license   https://www.gnu.org/licenses/gpl.html The GNU General Public License v2.0
 */
class StatusManager implements MessageComponentInterface
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
	 * @var \SplObjectStorage the connected clients
	 */
	private $_connectedClients;


	/**
	 * StatusManager constructor.
	 *
	 * @param Configuration   $configuration
	 * @param LoggerInterface $logger
	 */
	public function __construct(Configuration $configuration, LoggerInterface $logger)
	{
		$this->_configuration    = $configuration;
		$this->_logger           = $logger;
		$this->_connectedClients = new \SplObjectStorage();
		$this->_instances        = new \SplObjectStorage();

		// Attach a state to each instance
		foreach ($this->_configuration->getInstances() as $instance)
			$this->_instances->attach($instance, new InstanceState());
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
			new HttpServer(new WsServer($this)),
			$port,
			$address
		);

		// Add the instance polling mechanism to the event loop
		$this->_websocket->loop->addPeriodicTimer($this->_configuration->getUpdateInterval(),
			[$this, 'handleInstanceUpdates']);

		// Log information about the configured instances
		$instances = $this->_configuration->getInstances();

		$this->_logger->info('Managing {instances} instances:', [
			'instances' => count($instances),
		]);

		foreach ($instances as $instance)
		{
			$this->_logger->info('  {address}:{port}', [
				'address' => $instance->getInstance()->getHostname(),
				'port'    => $instance->getInstance()->getPort(),
			]);
		}

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

		// Log subscription state changes
		foreach ($statusCollection->getInstanceStatuses() as $instanceStatus)
		{
			$instanceName = $instanceStatus->getInstanceName();

			$this->_logger->debug('Got status updates from {instanceName}', [
				'instanceName' => $instanceName,
			]);
		}

		// Broadcast the status messages to all connected clients
		$this->broadcastMessages($statusCollection);
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


	/**
	 * Broadcasts the specified messages to all connected clients
	 *
	 * @param InstanceStatusCollection $messages
	 */
	private function broadcastMessages(InstanceStatusCollection $messages)
	{
		$this->_logger->debug('Broadcasting statuses to all clients');

		foreach ($this->_connectedClients as $client)
		{
			/* @var ConnectionInterface $client */
			$client->send(json_encode($messages));
		}
	}


	/**
	 * @inheritdoc
	 */
	public function onOpen(ConnectionInterface $conn)
	{
		$this->_logger->info('Got client connection');
		$this->_connectedClients->attach($conn);
	}


	/**
	 * @inheritdoc
	 */
	public function onClose(ConnectionInterface $conn)
	{
		$this->_logger->info('Got client disconnect');
		$this->_connectedClients->detach($conn);
	}


	/**
	 * @inheritdoc
	 */
	public function onError(ConnectionInterface $conn, \Exception $e)
	{
		// TODO: Implement onError() method.
	}


	/**
	 * @inheritdoc
	 */
	public function onMessage(ConnectionInterface $from, $msg)
	{
		// TODO: Implement onMessage() method.
	}

}
