<?php

namespace Jalle19\StatusManager\Message\Request;

use Jalle19\StatusManager\Exception\MalformedRequestException;
use Jalle19\StatusManager\Message\AbstractMessage;

/**
 * Class UsersRequest
 * @package   Jalle19\StatusManager\Message\Request
 * @copyright Copyright &copy; Sam Stenvall 2016-
 * @license   https://www.gnu.org/licenses/gpl.html The GNU General Public License v2.0
 */
class UsersRequest extends AbstractMessage
{

	/**
	 * UsersRequest constructor.
	 *
	 * @param string $instanceName
	 *
	 * @throws MalformedRequestException
	 */
	public function __construct($instanceName)
	{
		if (!is_string($instanceName))
			throw new MalformedRequestException('Missing required parameter "instanceName"');

		parent::__construct(self::TYPE_USERS_REQUEST, $instanceName);
	}


	/**
	 * @return string
	 */
	public function getInstanceName()
	{
		return $this->getPayload();
	}

}
