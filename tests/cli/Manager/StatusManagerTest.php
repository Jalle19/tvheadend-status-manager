<?php

namespace Jalle19\StatusManager\Test\Manager;

use Jalle19\StatusManager\Event\Events;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Class StatusManagerTest
 * @package   Jalle19\StatusManager\Test\Manager
 * @copyright Copyright &copy; Sam Stenvall 2016-
 * @license   https://www.gnu.org/licenses/gpl.html The GNU General Public License v2.0
 */
class StatusManagerTest extends AbstractManagerTest
{

	/**
	 *
	 */
	public function testEventHandling()
	{
		/* @var EventSubscriberInterface|\PHPUnit_Framework_MockObject_MockObject $statusManager */
		$statusManager = $this->getMockBuilder('\Jalle19\StatusManager\Manager\StatusManager')
		                      ->setConstructorArgs([$this->configuration, $this->logger, $this->eventDispatcher])
		                      ->setMethods(['onMainLoopStarted', 'onMainLoopTick'])
		                      ->getMock();

		$statusManager->expects($this->once())
		              ->method('onMainLoopStarted');
		$statusManager->expects($this->once())
		              ->method('onMainLoopTick');

		$this->eventDispatcher->addSubscriber($statusManager);
		$this->eventDispatcher->dispatch(Events::MAIN_LOOP_STARTING);
		$this->eventDispatcher->dispatch(Events::MAIN_LOOP_TICK);
	}

}
