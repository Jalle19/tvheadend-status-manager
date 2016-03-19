<?php

namespace Jalle19\StatusManager\Message\Response;

use Jalle19\StatusManager\Message\Request\MostActiveWatchersRequest;

/**
 * Class MostActiveWatchersResponse
 * @package   Jalle19\StatusManager\Message\Response
 * @copyright Copyright &copy; Sam Stenvall 2016-
 * @license   https://www.gnu.org/licenses/gpl.html The GNU General Public License v2.0
 */
class MostActiveWatchersResponse extends StatisticsResponse
{

	/**
	 * MostActiveWatchersResponse constructor.
	 *
	 * @param MostActiveWatchersRequest $request
	 * @param array                     $mostActiveWatchers
	 */
	public function __construct(MostActiveWatchersRequest $request, array $mostActiveWatchers)
	{
		parent::__construct(self::TYPE_MOST_ACTIVE_WATCHERS_RESPONSE, $request, $mostActiveWatchers);
	}

}
