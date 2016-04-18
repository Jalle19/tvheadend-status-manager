<?php

namespace Jalle19\StatusManager\Message\Request;

use Jalle19\StatusManager\Exception\MalformedRequestException;
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
	 * AuthenticationRequest constructor.
	 *
	 * @param string $accessToken
	 */
	public function __construct($accessToken)
	{
		if (!is_string($accessToken))
			throw new MalformedRequestException('Malformed access token');

		parent::__construct(self::TYPE_AUTHENTICATION_REQUEST, $accessToken);
	}


	/**
	 * @return string
	 */
	public function getAccessToken()
	{
		return $this->getPayload();
	}

}
