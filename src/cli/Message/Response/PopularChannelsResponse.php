<?php

namespace Jalle19\StatusManager\Message\Response;

use Jalle19\StatusManager\Message\Request\PopularChannelsRequest;

/**
 * Class PopularChannelsResponse
 * @package   Jalle19\StatusManager\Message\Response
 * @copyright Copyright &copy; Sam Stenvall 2016-
 * @license   https://www.gnu.org/licenses/gpl.html The GNU General Public License v2.0
 */
class PopularChannelsResponse extends StatisticsResponse
{

	/**
	 * PopularChannelsResponse constructor.
	 *
	 * @param PopularChannelsRequest $request
	 * @param array                  $popularChannels
	 */
	public function __construct(PopularChannelsRequest $request, array $popularChannels)
	{
		parent::__construct(self::TYPE_POPULAR_CHANNELS_RESPONSE, $request, $popularChannels);
	}

}
