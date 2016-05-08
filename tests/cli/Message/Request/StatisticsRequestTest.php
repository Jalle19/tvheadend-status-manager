<?php

namespace Jalle19\StatusManager\Test\Message\Request;

use Jalle19\StatusManager\Message\Request\PopularChannelsRequest;
use Jalle19\StatusManager\TimeFrame;

/**
 * Class StatisticsRequestTest
 * @package   Jalle19\StatusManager\Test\Message\Request
 * @copyright Copyright &copy; Sam Stenvall 2016-
 * @license   https://www.gnu.org/licenses/gpl.html The GNU General Public License v2.0
 */
class StatisticsRequestTest extends \PHPUnit_Framework_TestCase
{

	/**
	 * Tests that the constructor validation is working (instance name must be defined)
	 *
	 * @expectedException \Jalle19\StatusManager\Exception\MalformedRequestException
	 * @expectedExceptionMessageRegExp *instanceName*
	 */
	public function testConstructor()
	{
		$parameters            = new \stdClass();
		$parameters->timeFrame = TimeFrame::TIME_FRAME_ALL_TIME;

		new PopularChannelsRequest($parameters);
	}


	/**
	 *
	 */
	public function testTimeFrame()
	{
		$parameters               = new \stdClass();
		$parameters->instanceName = 'foo';
		$parameters->timeFrame    = TimeFrame::TIME_FRAME_ALL_TIME;

		$request = new PopularChannelsRequest($parameters);

		$this->assertEquals(TimeFrame::TIME_FRAME_ALL_TIME, $request->getTimeFrame()->getType());
	}

}
