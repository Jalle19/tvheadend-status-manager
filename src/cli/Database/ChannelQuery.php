<?php

namespace Jalle19\StatusManager\Database;

use Jalle19\StatusManager\Database\Base\ChannelQuery as BaseChannelQuery;

/**
 * @package   Jalle19\StatusManager\Database
 * @copyright Copyright &copy; Sam Stenvall 2015-
 * @license   https://www.gnu.org/licenses/gpl.html The GNU General Public License v2.0
 */
class ChannelQuery extends BaseChannelQuery
{

	/**
	 * @param string $instanceName
	 * @param string $channelName
	 *
	 * @return bool
	 */
	public function hasChannel($instanceName, $channelName)
	{
		return $this->filterByInstanceName($instanceName)->filterByName($channelName)
		            ->findOne() !== null;
	}

}
