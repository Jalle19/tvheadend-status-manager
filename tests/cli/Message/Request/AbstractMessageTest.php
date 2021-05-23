<?php

namespace Jalle19\StatusManager\Test\Message\Request;

use PHPUnit\Framework\TestCase;

/**
 * Class AbstractMessageTest
 * @package   Jalle19\StatusManager\Test\Message\Request
 * @copyright Copyright &copy; Sam Stenvall 2016-
 * @license   https://www.gnu.org/licenses/gpl.html The GNU General Public License v2.0
 */
class AbstractMessageTest extends TestCase
{

	/**
	 * Tests that the serialization of messages works as expected
	 */
	public function testSerialization()
	{
		$request = new DummyRequest('type', 'payload');

		$expected = json_encode([
			'type'    => 'type',
			'payload' => 'payload',
		]);

		$actual = json_encode($request);

		$this->assertEquals($expected, $actual);
	}

}
