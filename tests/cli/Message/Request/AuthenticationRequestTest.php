<?php

namespace Jalle19\StatusManager\Test\Message\Request;

use Jalle19\StatusManager\Exception\MalformedRequestException;
use Jalle19\StatusManager\Message\Request\AuthenticationRequest;
use PHPUnit\Framework\TestCase;

/**
 * Class AuthenticationRequestTest
 * @package   Jalle19\StatusManager\Test\Message\Request
 * @copyright Copyright &copy; Sam Stenvall 2016-
 * @license   https://www.gnu.org/licenses/gpl.html The GNU General Public License v2.0
 */
class AuthenticationRequestTest extends TestCase
{

	/**
	 * Tests that the constructor validation is working
	 *
	 *
	 */
	public function testConstructor()
	{
		$this->expectException(MalformedRequestException::class);
		new AuthenticationRequest(['very invalid']);
	}


	/**
	 *
	 */
	public function testPayload()
	{
		$request = new AuthenticationRequest('token');
		$this->assertEquals('token', $request->getAccessToken());
	}

}
