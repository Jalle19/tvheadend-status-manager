<?php

namespace Jalle19\StatusManager\Test\Message;

use Jalle19\StatusManager\Message\Factory;

/**
 * Class FactoryTest
 * @package   Jalle19\StatusManager\Test\Message
 * @copyright Copyright &copy; Sam Stenvall 2016-
 * @license   https://www.gnu.org/licenses/gpl.html The GNU General Public License v2.0
 */
class FactoryTest extends \PHPUnit_Framework_TestCase
{

	/**
	 * @expectedException \Jalle19\StatusManager\Exception\MalformedRequestException
	 */
	public function testMalformedRequest()
	{
		Factory::factory(json_encode([
			'payload' => 'foo',
		]));
	}


	/**
	 * @expectedException \Jalle19\StatusManager\Exception\UnknownRequestException
	 */
	public function testUnknownRequest()
	{
		Factory::factory(json_encode([
			'type' => 'totally invalid',
		]));
	}

}
