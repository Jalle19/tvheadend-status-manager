<?php

namespace Jalle19\StatusManager\Test\Message\Request;

use Jalle19\StatusManager\Message\Request\UsersRequest;

/**
 * Class UserRequestTest
 * @package   Jalle19\StatusManager\Test\Message\Request
 * @copyright Copyright &copy; Sam Stenvall 2016-
 * @license   https://www.gnu.org/licenses/gpl.html The GNU General Public License v2.0
 */
class UserRequestTest extends \PHPUnit_Framework_TestCase
{

	/**
	 * Tests that the constructor validation is working
	 *
	 * @expectedException \Jalle19\StatusManager\Exception\MalformedRequestException
	 */
	public function testConstructor()
	{
		new UsersRequest(null);
	}

}
