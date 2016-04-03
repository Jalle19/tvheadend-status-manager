<?php

namespace Jalle19\StatusManager\Test\Manager;

use Jalle19\StatusManager\Event\Events;
use Jalle19\StatusManager\Event\InstanceStateEvent;
use Jalle19\StatusManager\Event\InstanceStatusCollectionRequestEvent;
use Jalle19\StatusManager\Instance\InstanceState;
use Jalle19\StatusManager\Manager\InstanceStateManager;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Class InstanceStateManagerTest
 * @package   Jalle19\StatusManager\Test\Manager
 * @copyright Copyright &copy; Sam Stenvall 2016-
 * @license   https://www.gnu.org/licenses/gpl.html The GNU General Public License v2.0
 */
class InstanceStateManagerTest extends AbstractManagerTest
{

	/**
	 * Mocks the instance state manager to test that events are actually handled
	 */
	public function testEventHandling()
	{
		/* @var EventSubscriberInterface|\PHPUnit_Framework_MockObject_MockObject|InstanceStateManager $instanceStateManager */
		$instanceStateManager = $this->getMockBuilder('\Jalle19\StatusManager\Manager\InstanceStateManager')
		                             ->setConstructorArgs([$this->configuration, $this->logger, $this->eventDispatcher])
		                             ->setMethods([
			                             'onInstanceStatusCollectionRequest',
			                             'onInstanceReachable',
			                             'onInstanceUnreachable',
			                             'onInstanceMaybeReachable',
		                             ])
		                             ->getMock();

		$instanceStateManager->expects($this->once())
		                     ->method('onInstanceStatusCollectionRequest');
		$instanceStateManager->expects($this->once())
		                     ->method('onInstanceReachable');
		$instanceStateManager->expects($this->once())
		                     ->method('onInstanceUnreachable');
		$instanceStateManager->expects($this->once())
		                     ->method('onInstanceMaybeReachable');

		// Start triggering events and assert that the instance state is as expected
		$this->eventDispatcher->addSubscriber($instanceStateManager);

		$this->eventDispatcher->dispatch(Events::INSTANCE_STATUS_COLLECTION_REQUEST,
			new InstanceStatusCollectionRequestEvent());

		$this->eventDispatcher->dispatch(Events::INSTANCE_STATE_REACHABLE,
			new InstanceStateEvent($this->getTestInstance()));

		$this->eventDispatcher->dispatch(Events::INSTANCE_STATE_UNREACHABLE,
			new InstanceStateEvent($this->getTestInstance()));

		$this->eventDispatcher->dispatch(Events::INSTANCE_STATE_MAYBE_REACHABLE,
			new InstanceStateEvent($this->getTestInstance()));
	}


	/**
	 * Tests that instance state events are reflected properly in the actual instance stateF
	 */
	public function testInstanceStateHandling()
	{
		$instanceStateManager = new InstanceStateManager($this->configuration, $this->logger, $this->eventDispatcher);
		$this->eventDispatcher->addSubscriber($instanceStateManager);

		// Assert that triggered events actually trigger a change in state
		$this->eventDispatcher->dispatch(Events::INSTANCE_STATE_REACHABLE,
			new InstanceStateEvent($this->getTestInstance()));

		// Assert that the instance is now reachable
		$this->assertEquals(InstanceState::REACHABLE,
			$instanceStateManager->getInstanceState($this->getTestInstance())->getReachability());

		$this->eventDispatcher->dispatch(Events::INSTANCE_STATE_UNREACHABLE,
			new InstanceStateEvent($this->getTestInstance()));

		// Assert that the instance is now unreachable
		$this->assertEquals(InstanceState::UNREACHABLE,
			$instanceStateManager->getInstanceState($this->getTestInstance())->getReachability());

		// The instance should be maybe reachable after a full retry cycle
		for ($i = 0; $i < InstanceStateManager::UNREACHABLE_CYCLES_UNTIL_RETRY; $i++)
		{
			$this->eventDispatcher->dispatch(Events::INSTANCE_STATE_MAYBE_REACHABLE,
				new InstanceStateEvent($this->getTestInstance()));
		}

		$this->assertEquals(InstanceState::MAYBE_REACHABLE,
			$instanceStateManager->getInstanceState($this->getTestInstance())->getReachability());

		// And now if we mark it reachable it should be that
		$this->eventDispatcher->dispatch(Events::INSTANCE_STATE_REACHABLE,
			new InstanceStateEvent($this->getTestInstance()));

		$this->assertEquals(InstanceState::REACHABLE,
			$instanceStateManager->getInstanceState($this->getTestInstance())->getReachability());
	}

}
