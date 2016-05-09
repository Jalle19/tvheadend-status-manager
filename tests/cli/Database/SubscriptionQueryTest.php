<?php

namespace Jalle19\StatusManager\Test\Database;

use Jalle19\StatusManager\Database\SubscriptionQuery;
use Jalle19\StatusManager\TimeFrame;

/**
 * Class SubscriptionQueryTest
 * @package   Jalle19\StatusManager\Test\Database
 * @copyright Copyright &copy; Sam Stenvall 2016-
 * @license   https://www.gnu.org/licenses/gpl.html The GNU General Public License v2.0
 */
class SubscriptionQueryTest extends \PHPUnit_Framework_TestCase
{

	/**
	 * Checks that filtering is applied appropriately depending on the specified time frame
	 * @dataProvider provider
	 */
	public function testFilterByTimeFrame($expects, $timeFrame)
	{
		/* @var \PHPUnit_Framework_MockObject_MockObject|SubscriptionQuery $mock */
		$mock = $this->getMockBuilder(SubscriptionQuery::class)
		             ->setMethods(['filterByStopped'])
		             ->getMock();

		$mock->expects($expects)
		     ->method('filterByStopped');

		$mock->filterByTimeFrame(new TimeFrame($timeFrame));
	}


	/**
	 * @return array
	 */
	public function provider()
	{
		return [
			[$this->never(), TimeFrame::TIME_FRAME_ALL_TIME],
			[$this->once(), TimeFrame::TIME_FRAME_LAST_MONTH],
			[$this->once(), TimeFrame::TIME_FRAME_LAST_WEEK],
			[$this->once(), TimeFrame::TIME_FRAME_LAST_DAY],
		];
	}

}
