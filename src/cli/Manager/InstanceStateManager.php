<?php

namespace Jalle19\StatusManager\Manager;

use Jalle19\StatusManager\Configuration\Configuration;
use Jalle19\StatusManager\Configuration\Instance;
use Jalle19\StatusManager\Event\Events;
use Jalle19\StatusManager\Event\InstanceStateEvent;
use Jalle19\StatusManager\Event\InstanceStatusCollectionRequestEvent;
use Jalle19\StatusManager\Instance\InstanceState;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Keeps track of the reachability for all configures instances.
 *
 * @package   Jalle19\StatusManager\Manager
 * @copyright Copyright &copy; Sam Stenvall 2016-
 * @license   https://www.gnu.org/licenses/gpl.html The GNU General Public License v2.0
 */
class InstanceStateManager extends AbstractManager implements EventSubscriberInterface
{

	/**
	 * The number of cycles to wait until retrying an unreachable instance
	 */
	const UNREACHABLE_CYCLES_UNTIL_RETRY = 10;

	/**
	 * @var \SplObjectStorage the instances to connect to and their individual state
	 */
	private $_instances;


	/**
	 * InstanceStateManager constructor.
	 *
	 * @param Configuration   $configuration
	 * @param LoggerInterface $logger
	 * @param EventDispatcher $eventDispatcher
	 */
	public function __construct(Configuration $configuration, LoggerInterface $logger, EventDispatcher $eventDispatcher)
	{
		parent::__construct($configuration, $logger, $eventDispatcher);

		$this->_instances = new \SplObjectStorage();

		// Attach a state to each instance
		foreach ($configuration->getInstances() as $instance)
			$this->_instances->attach($instance, new InstanceState());
	}


	/**
	 * @inheritdoc
	 */
	public static function getSubscribedEvents()
	{
		return [
			Events::INSTANCE_STATUS_COLLECTION_REQUEST => 'onInstanceStatusCollectionRequest',
			Events::INSTANCE_STATE_REACHABLE           => 'onInstanceReachable',
			Events::INSTANCE_STATE_UNREACHABLE         => 'onInstanceUnreachable',
			Events::INSTANCE_STATE_MAYBE_REACHABLE     => 'onInstanceMaybeReachable',
		];
	}


	/**
	 * Handler for the INSTANCE_STATUS_COLLECTION_EVENT event
	 *
	 * @param InstanceStatusCollectionRequestEvent $event
	 */
	public function onInstanceStatusCollectionRequest($event)
	{
		$event->setInstanceCollection($this->_instances);
	}


	/**
	 * Called when an instance was tried and reachable
	 *
	 * @param InstanceStateEvent $event
	 */
	public function onInstanceReachable(InstanceStateEvent $event)
	{
		$instance      = $event->getInstance();
		$instanceState = $this->getInstanceState($instance);

		// Update reachability state now that we know the instance is reachable
		if ($instanceState->getReachability() === InstanceState::MAYBE_REACHABLE)
		{
			$this->logger
				->info('Instance {instanceName} is now reachable, will start polling for updates', [
					'instanceName' => $instance->getName(),
				]);

			$instanceState->setReachability(InstanceState::REACHABLE);
		}
	}


	/**
	 * Called when an instance was tried and wasn't reachable
	 *
	 * @param InstanceStateEvent $event
	 */
	public function onInstanceUnreachable(InstanceStateEvent $event)
	{
		$instance      = $event->getInstance();
		$instanceState = $this->getInstanceState($instance);

		// Mark the instance as unreachable
		$message = 'Instance {instanceName} not reachable, will wait for {cycles} cycles before retrying';

		$this->logger->alert($message, [
			'instanceName' => $instance->getName(),
			'cycles'       => self::UNREACHABLE_CYCLES_UNTIL_RETRY,
		]);

		$instanceState->setReachability(InstanceState::UNREACHABLE);
	}


	/**
	 * Called when a previously unreachable instance was left untried for the current tick
	 *
	 * @param InstanceStateEvent $event
	 */
	public function onInstanceMaybeReachable(InstanceStateEvent $event)
	{
		$instance      = $event->getInstance();
		$instanceState = $this->getInstanceState($instance);

		// Wait for some cycles and then mark unreachable instances as maybe reachable
		if ($instanceState->getRetryCount() === self::UNREACHABLE_CYCLES_UNTIL_RETRY - 1)
		{
			$instanceState->setReachability(InstanceState::MAYBE_REACHABLE);
			$instanceState->resetRetryCount();

			$this->logger->info('Retrying instance {instanceName} during next cycle', [
				'instanceName' => $instance->getName(),
			]);
		}
		else
			$instanceState->incrementRetryCount();
	}


	/**
	 * @param Instance $instance
	 *
	 * @return InstanceState
	 */
	private function getInstanceState(Instance $instance)
	{
		return $this->_instances[$instance];
	}

}
