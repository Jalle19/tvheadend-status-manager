<?php

namespace Jalle19\StatusManager\Message\Response;

use Jalle19\StatusManager\Message\AbstractMessage;

/**
 * Class AuthenticationResponse
 * @package   Jalle19\StatusManager\Message\Response
 * @copyright Copyright &copy; Sam Stenvall 2016-
 * @license   https://www.gnu.org/licenses/gpl.html The GNU General Public License v2.0
 */
class AuthenticationResponse extends AbstractMessage
{

	const STATUS_SUCCESS = 'success';
	const STATUS_FAILURE = 'failure';


	/**
	 * AuthenticationResponse constructor.
	 *
	 * @param string $status
	 */
	public function __construct($status)
	{
		parent::__construct(AbstractMessage::TYPE_AUTHENTICATION_RESPONSE, $status);
	}

}
