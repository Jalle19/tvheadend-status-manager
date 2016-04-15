<?php

namespace Jalle19\StatusManager\Manager;

use Jalle19\StatusManager\Configuration\Configuration;
use Jalle19\StatusManager\Event\Events;
use Jalle19\StatusManager\Event\InstanceStatusUpdatesEvent;
use Jalle19\StatusManager\Exception\MalformedRequestException;
use Jalle19\StatusManager\Exception\UnhandledMessageException;
use Jalle19\StatusManager\Exception\UnknownRequestException;
use Jalle19\StatusManager\Message\AbstractMessage;
use Jalle19\StatusManager\Message\Factory as MessageFactory;
use Jalle19\StatusManager\Message\Handler\DelegatesMessagesTrait;
use Jalle19\StatusManager\Message\Handler\HandlerInterface;
use Jalle19\StatusManager\Message\Request\AuthenticationRequest;
use Jalle19\StatusManager\Message\Response\AuthenticationResponse;
use Jalle19\StatusManager\Message\StatusUpdatesMessage;
use Jalle19\tvheadend\exception\RequestFailedException;
use Psr\Log\LoggerInterface;
use Ratchet\ConnectionInterface;
use Ratchet\Http\HttpServer;
use Ratchet\MessageComponentInterface;
use Ratchet\Server\IoServer;
use Ratchet\WebSocket\WsServer;
use React\EventLoop\LoopInterface;
use React\Socket\Server as ServerSocket;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Handles events related to the WebSocket. Events are either triggered from Ratchet (onOpen etc.)
 * or, the event dispatcher or client requests.
 *
 * @package   Jalle19\StatusManager
 * @copyright Copyright &copy; Sam Stenvall 2016-
 * @license   https://www.gnu.org/licenses/gpl.html The GNU General Public License v2.0
 */
class WebSocketManager extends AbstractManager implements
	MessageComponentInterface,
	EventSubscriberInterface,
	HandlerInterface
{

	use DelegatesMessagesTrait;

	/**
	 * @var IoServer the Websocket server
	 */
	private $_websocket;

	/**
	 * @var \SplObjectStorage the connected clients
	 */
	private $_connectedClients;

	/**
	 * @var \SplObjectStorage the authenticated clients
	 */
	private $_authenticatedClients;


	/**
	 * WebSocketManager constructor.
	 *
	 * @param Configuration   $configuration
	 * @param LoggerInterface $logger
	 * @param EventDispatcher $eventDispatcher
	 * @param LoopInterface   $loop
	 */
	public function __construct(
		Configuration $configuration,
		LoggerInterface $logger,
		EventDispatcher $eventDispatcher,
		LoopInterface $loop
	) {
		parent::__construct($configuration, $logger, $eventDispatcher);

		// Keep track of clients
		$this->_connectedClients     = new \SplObjectStorage();
		$this->_authenticatedClients = new \SplObjectStorage();

		// Create the socket to listen on
		$socket = new ServerSocket($loop);
		$socket->listen($configuration->getListenPort(), $configuration->getListenAddress());

		// Create the WebSocket server
		$this->_websocket = new IoServer(new HttpServer(new WsServer($this)), $socket, $loop);
	}


	/**
	 * @inheritdoc
	 */
	public static function getSubscribedEvents()
	{
		return [
			Events::MAIN_LOOP_STARTING      => 'onMainLoopStarted',
			Events::INSTANCE_STATUS_UPDATES => 'onInstanceStatusUpdates',
		];
	}


	/**
	 * Called right before the main loop is started
	 */
	public function onMainLoopStarted()
	{
		$this->logger->info('Starting the Websocket server on {address}:{port}', [
			'address' => $this->configuration->getListenAddress(),
			'port'    => $this->configuration->getListenPort(),
		]);
	}


	/**
	 * @param InstanceStatusUpdatesEvent $event
	 */
	public function onInstanceStatusUpdates(InstanceStatusUpdatesEvent $event)
	{
		$this->logger->debug('Broadcasting statuses to all clients');
		$message = new StatusUpdatesMessage($event->getInstanceStatusCollection());

		foreach ($this->_authenticatedClients as $client)
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
		$this->logger->debug('Got client connection');
		$this->_connectedClients->attach($conn);
	}


	/**
	 * @inheritdoc
	 */
	public function onClose(ConnectionInterface $conn)
	{
		$this->logger->debug('Got client disconnect');

		$this->_connectedClients->detach($conn);
		$this->_authenticatedClients->detach($conn);
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
	public function handleMessage(AbstractMessage $message, ConnectionInterface $sender)
	{
		if ($message->getType() !== AbstractMessage::TYPE_AUTHENTICATION_REQUEST)
			return false;

		/* @var AuthenticationRequest $message */
		$status = AuthenticationResponse::STATUS_FAILURE;

		// Add the sender to the list of authenticated clients
		if ($message->getAccessToken() === $this->configuration->getAccessToken())
		{
			$status = AuthenticationResponse::STATUS_SUCCESS;
			$this->_authenticatedClients->attach($sender);

			$this->logger->info('Client authenticated successfully');
		}
		else
			$this->logger->warning('Got invalid authentication request from client');

		return new AuthenticationResponse($status);
	}


	/**
	 * Dispatches incoming client messages to the appropriate handlers
	 *
	 * @param ConnectionInterface $from
	 * @param string              $msg
	 */
	public function onMessage(ConnectionInterface $from, $msg)
	{
		try
		{
			$message = MessageFactory::factory($msg);

			$this->logger->debug('Got message from client (type: {messageType})', [
				'messageType' => $message->getType(),
			]);

			// Attempt to delegate the message
			try
			{
				$response = $this->tryDelegateMessage($message, $from);
				$this->sendMessage($response, $from);
			}
			catch (RequestFailedException $e)
			{
				$this->logger->critical('The request failed: ' . $e->getMessage());
			}
			catch (UnhandledMessageException $e)
			{
				$this->logger->error('Unhandled message (type: {messageType})', [
					'messageType' => $message->getType(),
				]);
			}
		}
		catch (MalformedRequestException $e)
		{
			$this->logger->error('Got malformed message from client (reason: {reason})', [
				'reason' => $e->getMessage(),
			]);
		}
		catch (UnknownRequestException $e)
		{
			// The server itself sometimes sends out messages that are received here, hence debug
			$this->logger->debug('Got unknown message from client (type: {messageType})', [
				'messageType' => $e->getType(),
			]);
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
