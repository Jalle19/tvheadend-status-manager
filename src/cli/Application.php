<?php

namespace Jalle19\StatusManager;

use Jalle19\StatusManager\Configuration\Configuration;
use Jalle19\StatusManager\Event\Events;
use Psr\Log\LoggerInterface;
use React\EventLoop\Factory as EventLoopFactory;
use Symfony\Component\EventDispatcher\EventDispatcher;

/**
 * Main application object. The main purpose of this class is to hold the configuration, the logger and the dispatcher.
 * Events are dispatched to the various managers so they can ultimately work together without contacting each other
 * directly.
 *
 * @package   Jalle19\StatusManager
 * @copyright Copyright &copy; Sam Stenvall 2016-
 * @license   https://www.gnu.org/licenses/gpl.html The GNU General Public License v2.0
 */
class Application
{

	/**
	 * @var Configuration
	 */
	private $_configuration;

	/**
	 * @var LoggerInterface
	 */
	private $_logger;

	/**
	 * @var EventDispatcher
	 */
	private $_eventDispatcher;

	/**
	 * @var StatusManager
	 */
	private $_statusManager;

	/**
	 * @var InstanceStateManager
	 */
	private $_instanceStateManager;

	/**
	 * @var WebSocketManager
	 */
	private $_webSocketManager;

	/**
	 * @var PersistenceManager the persistence manager
	 */
	private $_persistenceManager;


	/**
	 * Application constructor.
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
		$eventLoop = EventLoopFactory::create();

		// Configure managers
		$this->_statusManager        = new StatusManager($this);
		$this->_instanceStateManager = new InstanceStateManager($this);
		$this->_webSocketManager     = new WebSocketManager($this, $eventLoop);
		$this->_persistenceManager   = new PersistenceManager($this);

		$this->configureEventDispatcher();

		// Configure the event loop and start the application
		$eventLoop->addPeriodicTimer($this->_configuration->getUpdateInterval(),
			[$this->_statusManager, 'handleInstanceUpdates']);

		$this->_eventDispatcher->dispatch(Events::MAIN_LOOP_STARTING);
		$eventLoop->run();
	}


	/**
	 * @return Configuration
	 */
	public function getConfiguration()
	{
		return $this->_configuration;
	}


	/**
	 * @return LoggerInterface
	 */
	public function getLogger()
	{
		return $this->_logger;
	}


	/**
	 * @return EventDispatcher
	 */
	public function getEventDispatcher()
	{
		return $this->_eventDispatcher;
	}


	/**
	 * @return StatusManager
	 */
	public function getStatusManager()
	{
		return $this->_statusManager;
	}


	/**
	 * @return InstanceStateManager
	 */
	public function getInstanceStateManager()
	{
		return $this->_instanceStateManager;
	}


	/**
	 * @return WebSocketManager
	 */
	public function getWebSocketManager()
	{
		return $this->_webSocketManager;
	}


	/**
	 * @return PersistenceManager
	 */
	public function getPersistenceManager()
	{
		return $this->_persistenceManager;
	}


	/**
	 * Configures the event dispatcher and attaches event listeners to it
	 */
	private function configureEventDispatcher()
	{
		$this->_eventDispatcher = new EventDispatcher();

		$eventDefinitions = [
			[Events::MAIN_LOOP_STARTING, $this->_statusManager, 'onMainLoopStarted'],
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

}
