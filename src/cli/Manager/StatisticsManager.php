<?php

namespace Jalle19\StatusManager\Manager;

use Jalle19\StatusManager\Message\AbstractMessage;
use Jalle19\StatusManager\Message\Handler\HandlerInterface;
use Jalle19\StatusManager\Message\Request\PopularChannelsRequest;
use Jalle19\StatusManager\Message\Response\PopularChannelsResponse;

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
		switch ($message->getType())
		{
			case AbstractMessage::TYPE_POPULAR_CHANNELS_REQUEST:
				/* @var PopularChannelsRequest $message */
				return $this->getPopularChannels($message->getInstanceName(), $message->getLimit());
		}

		return false;
	}


	/**
	 * @param string $instanceName
	 * @param int    $limit
	 *
	 * @return PopularChannelsResponse
	 */
	private function getPopularChannels($instanceName, $limit)
	{
		return new PopularChannelsResponse([
			'foo'   => $instanceName,
			'bar'   => [1, 2, 3],
			'limit' => $limit,
		]);
	}

}
