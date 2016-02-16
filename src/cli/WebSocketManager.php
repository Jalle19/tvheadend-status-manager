<?php

namespace Jalle19\StatusManager;

use Jalle19\StatusManager\Event\InstanceStatusUpdatesEvent;
use Psr\Log\LoggerInterface;
use Ratchet\ConnectionInterface;
use Ratchet\MessageComponentInterface;

/**
 * Handles events related to the WebSocket. Events are either triggered from Ratchet (onOpen etc.)
 * or from the event dispatcher in StatusManager.
 *
 * @package   Jalle19\StatusManager
 * @copyright Copyright &copy; Sam Stenvall 2016-
 * @license   https://www.gnu.org/licenses/gpl.html The GNU General Public License v2.0
 */
class WebSocketManager implements MessageComponentInterface
{

	/**
	 * @var LoggerInterface the logger
	 */
	private $_logger;

	/**
	 * @var \SplObjectStorage the connected clients
	 */
	private $_connectedClients;


	/**
	 * WebSocketEventHandler constructor.
	 *
	 * @param LoggerInterface $logger
	 */
	public function __construct(LoggerInterface $logger)
	{
		$this->_logger           = $logger;
		$this->_connectedClients = new \SplObjectStorage();
	}


	/**
	 * @param InstanceStatusUpdatesEvent $event
	 */
	public function onInstanceStatusUpdates(InstanceStatusUpdatesEvent $event)
	{
		$this->_logger->debug('Broadcasting statuses to all clients');
		$messages = $event->getInstanceStatusCollection();

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
