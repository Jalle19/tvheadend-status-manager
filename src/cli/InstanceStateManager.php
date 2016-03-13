<?php

namespace Jalle19\StatusManager;

use Jalle19\StatusManager\Configuration\Instance;
use Jalle19\StatusManager\Event\Events;
use Jalle19\StatusManager\Event\InstanceCollectionEvent;
use Jalle19\StatusManager\Event\InstanceStateEvent;
use Jalle19\StatusManager\Instance\InstanceState;

/**
 * Keeps track of the reachability for all configures instances.
 *
 * @package   Jalle19\StatusManager
 * @copyright Copyright &copy; Sam Stenvall 2016-
 * @license   https://www.gnu.org/licenses/gpl.html The GNU General Public License v2.0
 */
class InstanceStateManager extends AbstractManager
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
	 * @inheritdoc
	 */
	public function __construct(Application $application)
	{
		parent::__construct($application);

		$this->_instances = new \SplObjectStorage();

		// Attach a state to each instance
		foreach ($application->getConfiguration()->getInstances() as $instance)
			$this->_instances->attach($instance, new InstanceState());
	}


	/**
	 * Handler for the INSTANCE_COLLECTION_REQUEST event
	 */
	public function onInstanceCollectionRequest()
	{
		// Respond with the instances and their current state
		$this->getApplication()->getEventDispatcher()
		     ->dispatch(Events::INSTANCE_COLLECTION, new InstanceCollectionEvent($this->_instances));
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
			$this->getApplication()->getLogger()
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

		$this->getApplication()->getLogger()->alert($message, [
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

			$this->getApplication()->getLogger()->info('Retrying instance {instanceName} during next cycle', [
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
