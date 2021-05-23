<?php

namespace Jalle19\StatusManager\Test\Message;

use Jalle19\StatusManager\Exception\MalformedRequestException;
use Jalle19\StatusManager\Exception\UnknownRequestException;
use Jalle19\StatusManager\Message\Factory;
use PHPUnit\Framework\TestCase;

/**
 * Class FactoryTest
 * @package   Jalle19\StatusManager\Test\Message
 * @copyright Copyright &copy; Sam Stenvall 2016-
 * @license   https://www.gnu.org/licenses/gpl.html The GNU General Public License v2.0
 */
class FactoryTest extends TestCase
{

	public function testMalformedRequest()
	{
		$this->expectException(MalformedRequestException::class);
		Factory::factory(json_encode([
			'payload' => 'foo',
		]));
	}


	public function testUnknownRequest()
	{
		$this->expectException(UnknownRequestException::class);
		Factory::factory(json_encode([
			'type' => 'totally invalid',
		]));
	}

}
