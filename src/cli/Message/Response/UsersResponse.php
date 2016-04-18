<?php

namespace Jalle19\StatusManager\Message\Response;

use Jalle19\StatusManager\Database\User;
use Jalle19\StatusManager\Message\AbstractMessage;

/**
 * Class UsersResponse
 * @package   Jalle19\StatusManager\Message\Response
 *          copyright Copyright &copy; Sam Stenvall 2016-
 * @license   https://www.gnu.org/licenses/gpl.html The GNU General Public License v2.0
 */
class UsersResponse extends AbstractMessage
{

	/**
	 * UsersResponse constructor.
	 *
	 * @param User[] $users
	 */
	public function __construct(array $users)
	{
		parent::__construct(self::TYPE_USERS_RESPONSE, $users);
	}

}
