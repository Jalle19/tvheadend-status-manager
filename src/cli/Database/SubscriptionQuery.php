<?php

namespace Jalle19\StatusManager\Database;

use Jalle19\StatusManager\Database\Base\SubscriptionQuery as BaseSubscriptionQuery;
use Propel\Runtime\ActiveQuery\Criteria;

/**
 * @package   Jalle19\StatusManager\Database
 * @copyright Copyright &copy; Sam Stenvall 2015-
 * @license   https://www.gnu.org/licenses/gpl.html The GNU General Public License v2.0
 */
class SubscriptionQuery extends BaseSubscriptionQuery
{

	/**
	 * @param Instance $instance
	 * @param User     $user
	 *
	 * @return SubscriptionQuery
	 * @throws \Propel\Runtime\Exception\PropelException
	 */
	public static function createPopularChannelsQuery(Instance $instance, User $user)
	{
		$query = SubscriptionQuery::create();

		$query->withColumn('channel.name', 'channelName');
		$query->withColumn('user.name', 'userName');
		$query->withColumn('SUM((julianday(subscription.stopped) - julianday(subscription.started)) * 86400)',
			'totalTimeSeconds');
		$query->select(['channelName', 'userName', 'totalTimeSeconds']);
		$query->joinChannel('channel');
		$query->joinUser('user');
		$query->groupBy('channelName');
		$query->orderBy('totalTimeSeconds', Criteria::DESC);

		// Apply filtering
		$query->filterByStopped(null, Criteria::NOT_EQUAL);
		$query->filterByInstance($instance);

		if ($user !== null)
			$query->filterByUser($user);

		return $query;
	}

}
