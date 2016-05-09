<?php

namespace Jalle19\StatusManager\Test;

use Jalle19\StatusManager\TimeFrame;

/**
 * Class TimeFrameTest
 * @package   Jalle19\StatusManager\Test
 * @copyright Copyright &copy; Sam Stenvall 2016-
 * @license   https://www.gnu.org/licenses/gpl.html The GNU General Public License v2.0
 */
class TimeFrameTest extends \PHPUnit_Framework_TestCase
{

	/**
	 * @expectedException \InvalidArgumentException
	 */
	public function testConstructor()
	{
		new TimeFrame('invalid');
	}


	/**
	 * A data provider can't be used since it's evaluated long before the test method is run, scewing the timestamps
	 */
	public function testGetTimestamp()
	{
		foreach ($this->timestampProvider() as $data)
		{
			$timeFrame = new TimeFrame($data[0]);
			$this->assertEquals($data[1], $timeFrame->getTimestamp());
		}
	}


	public function testJsonSerialize()
	{
		$timeFrame = new TimeFrame(TimeFrame::TIME_FRAME_ALL_TIME);
		$this->assertEquals(TimeFrame::TIME_FRAME_ALL_TIME, $timeFrame->jsonSerialize());
	}


	/**
	 * @return array
	 */
	public function timestampProvider()
	{
		return [
			[TimeFrame::TIME_FRAME_ALL_TIME, (new \DateTime())->getTimestamp()],
			[TimeFrame::TIME_FRAME_LAST_MONTH, (new \DateTime('-1 month'))->getTimestamp()],
			[TimeFrame::TIME_FRAME_LAST_WEEK, (new \DateTime('-1 week'))->getTimestamp()],
			[TimeFrame::TIME_FRAME_LAST_DAY, (new \DateTime('-1 day'))->getTimestamp()],
		];
	}

}
