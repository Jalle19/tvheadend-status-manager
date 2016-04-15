<?php

namespace Jalle19\StatusManager\Message\Request;

use Jalle19\StatusManager\Message\AbstractMessage;

/**
 * Class AuthenticationRequest
 * @package   Jalle19\StatusManager\Message\Request
 * @copyright Copyright &copy; Sam Stenvall 2016-
 * @license   https://www.gnu.org/licenses/gpl.html The GNU General Public License v2.0
 */
class AuthenticationRequest extends AbstractMessage
{

	/**
	 * @var string
	 */
	private $_accessToken;


	/**
	 * AuthenticationRequest constructor.
	 *
	 * @param string $accessToken
	 */
	public function __construct($accessToken)
	{
		parent::__construct(self::TYPE_AUTHENTICATION_REQUEST, $accessToken);

		$this->_accessToken = $accessToken;
	}


	/**
	 * @return string
	 */
	public function getAccessToken()
	{
		return $this->_accessToken;
	}
	
}
