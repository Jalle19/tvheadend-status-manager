<?php

namespace Jalle19\StatusManager\Test\Exception;

use Jalle19\StatusManager\Exception\UnknownRequestException;

/**
 * Class UnknownRequestExceptionTest
 * @package   Jalle19\StatusManager\Test\Exception
 * @copyright Copyright &copy; Sam Stenvall 2016-
 * @license   https://www.gnu.org/licenses/gpl.html The GNU General Public License v2.0
 */
class UnknownRequestExceptionTest extends \PHPUnit_Framework_TestCase
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
