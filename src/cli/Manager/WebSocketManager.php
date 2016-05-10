<?php

namespace Jalle19\StatusManager\Manager;

use Jalle19\StatusManager\Configuration\Configuration;
use Jalle19\StatusManager\Event\Events;
use Psr\Log\LoggerInterface;
use Ratchet\Http\HttpServer;
use Ratchet\Server\IoServer;
use Ratchet\WebSocket\WsServer;
use React\EventLoop\LoopInterface;
use React\Socket\Server as ServerSocket;
use Symfony\Component\EventDispatcher\EventDispatcher;

/**
 * Class WebSocketManager
 * @package Jalle19\StatusManager\Manager
 */
class WebSocketManager extends AbstractClientManager
{

	/**
	 * @var IoServer the Websocket server
	 */
	private $_websocket;


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
		return array_merge(parent::getSubscribedEvents(), [
			Events::MAIN_LOOP_STARTING => 'onMainLoopStarted',
		]);
	}


	/**
	 * Called right before the main loop is started
	 */
	public function onMainLoopStarted()
	{
		$this->logger->notice('Starting the Websocket server on {address}:{port}', [
			'address' => $this->configuration->getListenAddress(),
			'port'    => $this->configuration->getListenPort(),
		]);
	}

}
