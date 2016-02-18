<?php

namespace Jalle19\StatusManager;

use Jalle19\StatusManager\Event\InstanceStatusUpdatesEvent;
use Psr\Log\LoggerInterface;
use Ratchet\ConnectionInterface;
use Ratchet\Http\HttpServer;
use Ratchet\MessageComponentInterface;
use Ratchet\Server\IoServer;
use Ratchet\WebSocket\WsServer;
use React\EventLoop\LoopInterface;
use React\Socket\Server as ServerSocket;

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
	 * @var Configuration
	 */
	private $_configuration;

	/**
	 * @var IoServer the Websocket server
	 */
	private $_websocket;

	/**
	 * @var \SplObjectStorage the connected clients
	 */
	private $_connectedClients;


	/**
	 * WebSocketEventHandler constructor.
	 *
	 * @param LoggerInterface $logger
	 * @param Configuration   $configuration
	 * @param LoopInterface   $loop
	 */
	public function __construct(LoggerInterface $logger, Configuration $configuration, LoopInterface $loop)
	{
		$this->_logger           = $logger;
		$this->_configuration    = $configuration;
		$this->_connectedClients = new \SplObjectStorage();

		// Create the socket to listen on
		$socket = new ServerSocket($loop);
		$socket->listen($this->_configuration->getListenPort(), $this->_configuration->getListenAddress());

		// Create the WebSocket server
		$this->_websocket = new IoServer(new HttpServer(new WsServer($this)), $socket, $loop);
	}


	/**
	 * Called right before the main loop is started
	 */
	public function onMainLoopStarted()
	{
		$this->_logger->info('Starting the Websocket server on {address}:{port}', [
			'address' => $this->_configuration->getListenAddress(),
			'port'    => $this->_configuration->getListenPort(),
		]);
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
