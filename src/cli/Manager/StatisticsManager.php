<?php

namespace Jalle19\StatusManager\Manager;

use Jalle19\StatusManager\Database\InstanceQuery;
use Jalle19\StatusManager\Database\SubscriptionQuery;
use Jalle19\StatusManager\Database\UserQuery;
use Jalle19\StatusManager\Message\AbstractMessage;
use Jalle19\StatusManager\Message\Handler\HandlerInterface;
use Jalle19\StatusManager\Message\Request\MostActiveWatchersRequest;
use Jalle19\StatusManager\Message\Request\PopularChannelsRequest;
use Jalle19\StatusManager\Message\Request\StatisticsRequest;
use Jalle19\StatusManager\Message\Response\MostActiveWatchersResponse;
use Jalle19\StatusManager\Message\Response\PopularChannelsResponse;
use Jalle19\tvheadend\exception\RequestFailedException;
use Propel\Runtime\Exception\PropelException;

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
	public function handleMessage(AbstractMessage $message)
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
						$message->getTimeInterval()));
				case AbstractMessage::TYPE_MOST_ACTIVE_WATCHERS_REQUEST:
					/* @var MostActiveWatchersRequest $message */
					return new MostActiveWatchersResponse($message, $this->getMostActiveWatchers(
						$message->getInstanceName(),
						$message->getLimit(),
						$message->getTimeInterval()));
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
	 * @param string      $timeInterval
	 *
	 * @return array the popular channels
	 */
	private function getPopularChannels($instanceName, $userName, $limit, $timeInterval)
	{
		// Find the instance and the user
		$instance = InstanceQuery::create()->findOneByName($instanceName);
		$user     = UserQuery::create()->findOneByName($userName);

		// Find the subscriptions
		$query = SubscriptionQuery::create()->getPopularChannelsQuery($instance, $user);

		// Apply additional filters not done by the query
		if ($limit !== null)
			$query->limit($limit);

		if ($timeInterval !== StatisticsRequest::TIME_INTERVAL_ALL_TIME)
		{
			$query->filterByStopped([
				'min' => $this->getTimeIntervalTimestamp($timeInterval),
			]);
		}

		return $query->find()->getData();
	}


	/**
	 * @param string $instanceName
	 * @param int    $limit
	 * @param string $timeInterval
	 *
	 * @return array
	 */
	private function getMostActiveWatchers($instanceName, $limit, $timeInterval)
	{
		$instance = InstanceQuery::create()->findOneByName($instanceName);
		$query    = UserQuery::create()->getMostActiveWatchersQuery($instance);

		// Apply additional filters not done by the query
		if ($limit !== null)
			$query->limit($limit);

		if ($timeInterval !== StatisticsRequest::TIME_INTERVAL_ALL_TIME)
		{
			$query->useSubscriptionQuery()->filterByStopped([
				'min' => $this->getTimeIntervalTimestamp($timeInterval),
			])->endUse();
		}

		return $query->find()->getData();
	}


	/**
	 * @param string $timeInterval
	 *
	 * @return int
	 */
	private function getTimeIntervalTimestamp($timeInterval)
	{
		$dateTime = new \DateTime();

		switch ($timeInterval)
		{
			case StatisticsRequest::TIME_INTERVAL_LAST_MONTH:
				$dateTime = $dateTime->modify('-1 month');
		}

		return $dateTime->getTimestamp();
	}

}
