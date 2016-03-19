<?php

namespace Jalle19\StatusManager\Database;

use Jalle19\StatusManager\Database\Base\UserQuery as BaseUserQuery;

/**
 * @package   Jalle19\StatusManager\Database
 * @copyright Copyright &copy; Sam Stenvall 2015-
 * @license   https://www.gnu.org/licenses/gpl.html The GNU General Public License v2.0
 */
class UserQuery extends BaseUserQuery
{

	/**
	 * @param string $instanceName
	 * @param string $userName
	 *
	 * @return bool
	 */
	public function hasUser($instanceName, $userName)
	{
		return $this->filterByInstanceName($instanceName)->filterByName($userName)->findOne() !== null;
	}

}
