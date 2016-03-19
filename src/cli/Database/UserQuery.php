<?php

namespace Jalle19\StatusManager\Database;

use Jalle19\StatusManager\Database\Base\UserQuery as BaseUserQuery;
use Propel\Runtime\ActiveQuery\Criteria;

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


	/**
	 * @param Instance $instance
	 *
	 * @return UserQuery
	 * @throws \Propel\Runtime\Exception\PropelException
	 */
	public function getMostActiveWatchersQuery($instance)
	{
		$this->withColumn('user.name', 'userName');
		$this->withColumn('SUM((julianday(subscription.stopped) - julianday(subscription.started)) * 86400)',
			'totalTimeSeconds');

		$this->select(['userName', 'totalTimeSeconds']);

		$this->useSubscriptionQuery()->filterByStopped(null, Criteria::NOT_EQUAL)->endUse();
		$this->filterByInstance($instance);

		$this->groupBy('userName');
		$this->orderBy('totalTimeSeconds', Criteria::DESC);

		return $this;
	}

}
