<?php

namespace Jalle19\StatusManager\Manager;

use Jalle19\StatusManager\Database\InstanceQuery;
use Jalle19\StatusManager\Database\SubscriptionQuery;
use Jalle19\StatusManager\Database\User;
use Jalle19\StatusManager\Database\UserQuery;
use Jalle19\StatusManager\Message\AbstractMessage;
use Jalle19\StatusManager\Message\Handler\HandlerInterface;
use Jalle19\StatusManager\Message\Request\InstancesRequest;
use Jalle19\StatusManager\Message\Request\MostActiveWatchersRequest;
use Jalle19\StatusManager\Message\Request\PopularChannelsRequest;
use Jalle19\StatusManager\Message\Request\UsersRequest;
use Jalle19\StatusManager\Message\Response\InstancesResponse;
use Jalle19\StatusManager\Message\Response\MostActiveWatchersResponse;
use Jalle19\StatusManager\Message\Response\PopularChannelsResponse;
use Jalle19\StatusManager\Message\Response\UsersResponse;
use Jalle19\StatusManager\TimeFrame;
use Jalle19\tvheadend\exception\RequestFailedException;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\Exception\PropelException;
use Ratchet\ConnectionInterface;

/**
 * Class StatisticsManager
 * @package   Jalle19\StatusManager\Manager
 * @copyright Copyright &copy; Sam Stenvall 2016-
 * @license   https://www.gnu.org/licenses/gpl.html The GNU General Public License v2.0
 */
class StatisticsManager extends AbstractManager implements HandlerInterface
{

	/**
	 * @inheritdoc
	 */
	public function handleMessage(AbstractMessage $message, ConnectionInterface $sender)
	{
		// Wrap database exceptions in the more generic RequestFailedException
		try
		{
			switch ($message->getType())
			{
				case AbstractMessage::TYPE_POPULAR_CHANNELS_REQUEST:
					/* @var PopularChannelsRequest $message */
					return new PopularChannelsResponse($message, $this->getPopularChannels(
						$message->getInstanceName(),
						$message->getUserName(),
						$message->getLimit(),
						$message->getTimeFrame()));
				case AbstractMessage::TYPE_MOST_ACTIVE_WATCHERS_REQUEST:
					/* @var MostActiveWatchersRequest $message */
					return new MostActiveWatchersResponse($message, $this->getMostActiveWatchers(
						$message->getInstanceName(),
						$message->getLimit(),
						$message->getTimeFrame()));
				case AbstractMessage::TYPE_INSTANCES_REQUEST:
					/* @var InstancesRequest $message */
					return new InstancesResponse($this->configuration->getInstances());
				case AbstractMessage::TYPE_USERS_REQUEST:
					/* @var UsersRequest $message */
					return new UsersResponse($this->getUsers($message->getInstanceName()));
			}
		}
		catch (PropelException $e)
		{
			throw new RequestFailedException('A database error occured: ' . $e->getMessage());
		}

		return false;
	}


	/**
	 * @param string      $instanceName
	 * @param string|null $userName
	 * @param int|null    $limit
	 * @param TimeFrame   $timeFrame
	 *
	 * @return array the popular channels
	 */
	private function getPopularChannels($instanceName, $userName, $limit, $timeFrame)
	{
		// Find the instance and the user
		$instance = InstanceQuery::create()->findOneByName($instanceName);
		$user     = UserQuery::create()->findOneByName($userName);
		$query    = SubscriptionQuery::create()->getPopularChannelsQuery($instance, $user);

		$query = $this->filterByLimit($limit, $query);
		$query = $this->filterBySubscriptionStopped($timeFrame, $query);
		$query = $this->filterIgnoredUsers($instanceName, $query->useUserQuery())->endUse();

		return $query->find()->getData();
	}


	/**
	 * @param string    $instanceName
	 * @param int       $limit
	 * @param TimeFrame $timeFrame
	 *
	 * @return array
	 */
	private function getMostActiveWatchers($instanceName, $limit, $timeFrame)
	{
		$instance = InstanceQuery::create()->findOneByName($instanceName);
		$query    = UserQuery::create()->getMostActiveWatchersQuery($instance);

		$query = $this->filterByLimit($limit, $query);
		$query = $this->filterBySubscriptionStopped($timeFrame, $query->useSubscriptionQuery())->endUse();
		$query = $this->filterIgnoredUsers($instanceName, $query);

		return $query->find()->getData();
	}


	/**
	 * Returns all users found so far for the specified instance name
	 *
	 * @param string $instanceName
	 *
	 * @return User[]
	 */
	private function getUsers($instanceName)
	{
		$query = UserQuery::create();
		$query = $this->filterIgnoredUsers($instanceName, $query);

		return $query->findByInstanceName($instanceName)->getArrayCopy();
	}


	/**
	 * @param int|null $limit
	 * @param mixed    $query a query instance
	 *
	 * @return mixed
	 */
	private function filterByLimit($limit, $query)
	{
		if ($limit !== null)
			$query->limit($limit);

		return $query;
	}


	/**
	 * @param TimeFrame         $timeFrame
	 * @param SubscriptionQuery $query
	 *
	 * @return SubscriptionQuery
	 */
	private function filterBySubscriptionStopped($timeFrame, SubscriptionQuery $query)
	{
		if ($timeFrame->getType() !== TimeFrame::TIME_FRAME_ALL_TIME)
		{
			$query = $query->filterByStopped([
				'min' => $timeFrame->getTimestamp(),
			]);
		}

		return $query;
	}


	/**
	 * @param string    $instanceName
	 * @param UserQuery $query
	 *
	 * @return UserQuery
	 */
	private function filterIgnoredUsers($instanceName, UserQuery $query)
	{
		$instance     = $this->configuration->getInstanceByName($instanceName);
		$ignoredUsers = $instance->getIgnoredUsers();

		// Always ignore system users
		$ignoredUsers[] = User::NAME_DVR;

		foreach ($ignoredUsers as $ignoredUser)
			$query = $query->filterByName($ignoredUser, Criteria::NOT_EQUAL);

		return $query;
	}

}
