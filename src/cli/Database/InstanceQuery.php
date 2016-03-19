<?php

namespace Jalle19\StatusManager\Database;

use Jalle19\StatusManager\Database\Base\InstanceQuery as BaseInstanceQuery;
use Jalle19\tvheadend\Tvheadend;

/**
 * @package   Jalle19\StatusManager\Database
 * @copyright Copyright &copy; Sam Stenvall 2015-
 * @license   https://www.gnu.org/licenses/gpl.html The GNU General Public License v2.0
 */
class InstanceQuery extends BaseInstanceQuery
{

	/**
	 * @param Tvheadend $instance
	 *
	 * @return bool whether the instance exists in the database
	 */
	public function hasInstance(Tvheadend $instance)
	{
		return $this->findPk($instance->getHostname()) !== null;
	}

}
