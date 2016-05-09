<?php

namespace Jalle19\StatusManager\Database;

use Jalle19\StatusManager\Database\Base\SubscriptionQuery as BaseSubscriptionQuery;
use Jalle19\tvheadend\model\SubscriptionStatus;
use Propel\Runtime\ActiveQuery\Criteria;

/**
 * @package   Jalle19\StatusManager\Database
 * @copyright Copyright &copy; Sam Stenvall 2015-
 * @license   https://www.gnu.org/licenses/gpl.html The GNU General Public License v2.0
 */
class SubscriptionQuery extends BaseSubscriptionQuery
{
	
	use LimitTrait;

	/**
	 * @param Instance           $instance
	 * @param User|null          $user
	 * @param Channel            $channel
	 * @param SubscriptionStatus $subscription
	 *
	 * @return bool
	 * @throws \Propel\Runtime\Exception\PropelException
	 */
	public function hasSubscription(
		Instance $instance,
		$user,
		Channel $channel,
		SubscriptionStatus $subscription
	) {
		// Not all subscriptions are tied to a user
		$userId = $user !== null ? $user->getId() : null;

		return $this->filterByInstance($instance)->filterByUserId($userId)
		            ->filterByChannel($channel)
		            ->filterBySubscriptionId($subscription->id)->filterByStarted($subscription->start)
		            ->findOne() !== null;
	}


	/**
	 * @param string $instanceName
	 * @param int    $subscriptionId
	 *
	 * @return Subscription
	 */
	public function getNewestMatching($instanceName, $subscriptionId)
	{
		return $this->filterByInstanceName($instanceName)
		            ->filterBySubscriptionId($subscriptionId)
		            ->addDescendingOrderByColumn('started')->findOne();
	}


	/**
	 * @param Instance $instance
	 * @param User     $user
	 *
	 * @return SubscriptionQuery
	 * @throws \Propel\Runtime\Exception\PropelException
	 */
	public function getPopularChannelsQuery(Instance $instance, User $user)
	{
		$this->withColumn('channel.name', 'channelName');
		$this->withColumn('user.name', 'userName');
		$this->withColumn('SUM((julianday(subscription.stopped) - julianday(subscription.started)) * 86400)',
			'totalTimeSeconds');
		$this->select(['channelName', 'userName', 'totalTimeSeconds']);

		// Join the user table with useUserQuery() so the same method can be used later
		$this->joinChannel('channel');
		$this->useUserQuery()->endUse();
		$this->groupBy('channelName');
		$this->orderBy('totalTimeSeconds', Criteria::DESC);

		// Apply filtering
		$this->filterByStopped(null, Criteria::NOT_EQUAL);
		$this->filterByInstance($instance);

		if ($user !== null)
			$this->filterByUser($user);

		return $this;
	}

}
