<?php

namespace Jalle19\StatusManager\Manager;

use Jalle19\StatusManager\Application;
use Jalle19\StatusManager\Event\InstanceStatusUpdatesEvent;
use Jalle19\StatusManager\Exception\MalformedRequestException;
use Jalle19\StatusManager\Exception\UnknownRequestException;
use Jalle19\StatusManager\Message\Factory as MessageFactory;
use Jalle19\StatusManager\Message\StatusUpdatesMessage;
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
class WebSocketManager extends AbstractManager implements MessageComponentInterface
{

	/**
	 * @var IoServer the Websocket server
	 */
	private $_websocket;

	/**
	 * @var \SplObjectStorage the connected clients
	 */
	private $_connectedClients;


	/**
	 * WebSocketManager constructor.
	 *
	 * @param Application   $application
	 * @param LoopInterface $loop
	 */
	public function __construct(Application $application, LoopInterface $loop)
	{
		parent::__construct($application);

		$this->_connectedClients = new \SplObjectStorage();
		$configuration           = $application->getConfiguration();

		// Create the socket to listen on
		$socket = new ServerSocket($loop);
		$socket->listen($configuration->getListenPort(), $configuration->getListenAddress());

		// Create the WebSocket server
		$this->_websocket = new IoServer(new HttpServer(new WsServer($this)), $socket, $loop);
	}


	/**
	 * Called right before the main loop is started
	 */
	public function onMainLoopStarted()
	{
		$configuration = $this->getApplication()->getConfiguration();

		$this->getApplication()->getLogger()->info('Starting the Websocket server on {address}:{port}', [
			'address' => $configuration->getListenAddress(),
			'port'    => $configuration->getListenPort(),
		]);
	}


	/**
	 * @param InstanceStatusUpdatesEvent $event
	 */
	public function onInstanceStatusUpdates(InstanceStatusUpdatesEvent $event)
	{
		$this->getApplication()->getLogger()->debug('Broadcasting statuses to all clients');
		$message = new StatusUpdatesMessage($event->getInstanceStatusCollection());

		foreach ($this->_connectedClients as $client)
		{
			/* @var ConnectionInterface $client */
			$this->sendMessage($message, $client);
		}
	}


	/**
	 * @inheritdoc
	 */
	public function onOpen(ConnectionInterface $conn)
	{
		$this->getApplication()->getLogger()->debug('Got client connection');
		$this->_connectedClients->attach($conn);
	}


	/**
	 * @inheritdoc
	 */
	public function onClose(ConnectionInterface $conn)
	{
		$this->getApplication()->getLogger()->debug('Got client disconnect');
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
	 * Dispatches incoming client messages to the appropriate handlers
	 *
	 * @param ConnectionInterface $from
	 * @param string              $msg
	 */
	public function onMessage(ConnectionInterface $from, $msg)
	{
		$logger = $this->getApplication()->getLogger();

		try
		{
			$message = MessageFactory::factory($msg);

			$logger->debug('Got message from client (type: {messageType})', [
				'messageType' => $message->getType(),
			]);
		}
		catch (MalformedRequestException $e)
		{
			$logger->error('Got malformed message from client (reason: {reason})', [
				'reason' => $e->getMessage(),
			]);
		}
		catch (UnknownRequestException $e)
		{
			// The server itself sometimes sends out messages that are received here, hence debug
			$logger->debug('Got unknown message from client');
		}
	}


	/**
	 * @param AbstractMessage     $message
	 * @param ConnectionInterface $target
	 */
	private function sendMessage(AbstractMessage $message, ConnectionInterface $target)
	{
		$target->send(json_encode($message));
	}

}
