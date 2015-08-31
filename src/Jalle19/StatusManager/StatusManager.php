<?php

namespace Jalle19\StatusManager;

use Psr\Log\LoggerInterface;
use Ratchet\ConnectionInterface;
use Ratchet\Http\HttpServer;
use Ratchet\MessageComponentInterface;
use Ratchet\Server\IoServer;
use Ratchet\WebSocket\WsServer;

class StatusManager implements MessageComponentInterface
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
	}


	/**
	 * Runs the application
	 */
	public function run()
	{
		// Configure the WebSocket server
		$this->_websocket = IoServer::factory(
			new HttpServer(new WsServer($this)),
			8080,
			'0.0.0.0'
		);

		// Add the status polling mechanism to the event loop
		$this->_websocket->loop->addPeriodicTimer($this->_configuration->getUpdateInterval(),
			[$this, 'broadcastTimer']);

		// Start the main loop
		$this->_logger->info('Starting the main loop');
		$this->_websocket->run();
	}


	/**
	 * Broadcasts all status messages to all clients
	 */
	public function broadcastTimer()
	{
		$this->broadcastMessages($this->getStatusMessages());
	}


	/**
	 * Retrives and returns all status messages for the configured
	 * instances
	 * @return InstanceStatusCollection
	 */
	private function getStatusMessages()
	{
		$collection = new InstanceStatusCollection();

		foreach ($this->_configuration->getInstances() as $instance)
		{
			$tvheadend    = $instance->getInstance();
			$instanceName = $instance->getName();

			// Collect statuses
			$collection->add(new InstanceStatus(
				$instanceName,
				$tvheadend->getInputStatus(),
				$tvheadend->getSubscriptionStatus(),
				$tvheadend->getConnectionStatus()));

			$this->_logger->debug('Got status updates from {instanceName}', [
				'instanceName' => $instanceName,
			]);
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
