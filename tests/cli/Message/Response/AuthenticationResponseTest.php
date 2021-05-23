<?php

namespace Jalle19\StatusManager\Test\Message\Response;

use Jalle19\StatusManager\Message\Response\AuthenticationResponse;
use PHPUnit\Framework\TestCase;

/**
 * Class AuthenticationResponseTest
 * @package   Jalle19\StatusManager\Test\Message\Response
 * @copyright Copyright &copy; Sam Stenvall 2016-
 * @license   https://www.gnu.org/licenses/gpl.html The GNU General Public License v2.0
 */
class AuthenticationResponseTest extends TestCase
{

	/**
	 * Not much to test here really
	 */
	public function testAuthenticationResponse()
	{
		$response = new AuthenticationResponse(AuthenticationResponse::STATUS_SUCCESS);
		$this->assertEquals(AuthenticationResponse::STATUS_SUCCESS, $response->getStatus());
	}

}
