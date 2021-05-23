<?php

namespace Jalle19\StatusManager\Test\Exception;

use Jalle19\StatusManager\Exception\UnknownRequestException;
use PHPUnit\Framework\TestCase;

/**
 * Class UnknownRequestExceptionTest
 * @package   Jalle19\StatusManager\Test\Exception
 * @copyright Copyright &copy; Sam Stenvall 2016-
 * @license   https://www.gnu.org/licenses/gpl.html The GNU General Public License v2.0
 */
class UnknownRequestExceptionTest extends TestCase
{

	/**
	 *
	 */
	public function testGetType()
	{
		$exception = new UnknownRequestException('foo');
		$this->assertEquals('foo', $exception->getType());
	}

}
