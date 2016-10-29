<?php

namespace Jalle19\StatusManager\Database;

use Jalle19\StatusManager\Configuration\Instance as ConfiguredInstance;
use Jalle19\StatusManager\Database\Base\InstanceQuery as BaseInstanceQuery;

/**
 * @package   Jalle19\StatusManager\Database
 * @copyright Copyright &copy; Sam Stenvall 2015-
 * @license   https://www.gnu.org/licenses/gpl.html The GNU General Public License v2.0
 */
class InstanceQuery extends BaseInstanceQuery
{

	/**
	 * @param ConfiguredInstance $instance
	 *
	 * @return bool whether the instance exists in the database
	 */
	public function hasInstance(ConfiguredInstance $instance)
	{
		return $this->findPk($instance->getName()) !== null;
	}

}
