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
use Ratchet\MessageComponentInterface;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Keeps track of connected clients and handles events related to them.
 *
 * @package   Jalle19\StatusManager
 * @copyright Copyright &copy; Sam Stenvall 2016-
 * @license   https://www.gnu.org/licenses/gpl.html The GNU General Public License v2.0
 */
abstract class AbstractClientManager extends AbstractManager implements
	MessageComponentInterface,
	EventSubscriberInterface,
	HandlerInterface
{

	use DelegatesMessagesTrait;

	/**
	 * @var \SplObjectStorage the connected clients
	 */
	protected $_connectedClients;

	/**
	 * @var \SplObjectStorage the authenticated clients
	 */
	protected $_authenticatedClients;


	/**
	 * WebSocketManager constructor.
	 *
	 * @param Configuration   $configuration
	 * @param LoggerInterface $logger
	 * @param EventDispatcher $eventDispatcher
	 */
	public function __construct(
		Configuration $configuration,
		LoggerInterface $logger,
		EventDispatcher $eventDispatcher
	) {
		parent::__construct($configuration, $logger, $eventDispatcher);

		// Keep track of clients
		$this->_connectedClients     = new \SplObjectStorage();
		$this->_authenticatedClients = new \SplObjectStorage();
	}


	/**
	 * @inheritdoc
	 */
	public static function getSubscribedEvents()
	{
		return [
			Events::INSTANCE_STATUS_UPDATES => 'onInstanceStatusUpdates',
		];
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
		$this->logger->info('Got client connection');
		$this->_connectedClients->attach($conn);
	}


	/**
	 * @inheritdoc
	 */
	public function onClose(ConnectionInterface $conn)
	{
		$this->logger->info('Got client disconnect');

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

			$this->logger->notice('Client authenticated successfully');
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
				$this->logger->error('The request failed: ' . $e->getMessage());
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
