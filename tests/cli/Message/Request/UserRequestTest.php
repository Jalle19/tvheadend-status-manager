<?php

namespace Jalle19\StatusManager\Test\Message\Request;

use Jalle19\StatusManager\Exception\MalformedRequestException;
use Jalle19\StatusManager\Message\Request\UsersRequest;
use PHPUnit\Framework\TestCase;

/**
 * Class UserRequestTest
 * @package   Jalle19\StatusManager\Test\Message\Request
 * @copyright Copyright &copy; Sam Stenvall 2016-
 * @license   https://www.gnu.org/licenses/gpl.html The GNU General Public License v2.0
 */
class UserRequestTest extends TestCase
{

	/**
	 * Tests that the constructor validation is working
	 *
	 *
	 */
	public function testConstructor()
	{
		$this->expectException(MalformedRequestException::class);
		new UsersRequest(null);
	}


	/**
	 * 
	 */
	public function testGetInstance()
	{
		$request = new UsersRequest('foo');
		$this->assertEquals('foo', $request->getInstanceName());
	}

}
