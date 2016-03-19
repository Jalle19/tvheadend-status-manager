<?php

namespace Jalle19\StatusManager\Database;

use Jalle19\StatusManager\Database\Base\ConnectionQuery as BaseConnectionQuery;
use Jalle19\tvheadend\model\ConnectionStatus;

/**
 * @package   Jalle19\StatusManager\Database
 * @copyright Copyright &copy; Sam Stenvall 2015-
 * @license   https://www.gnu.org/licenses/gpl.html The GNU General Public License v2.0
 */
class ConnectionQuery extends BaseConnectionQuery
{

	/**
	 * @param string           $instanceName
	 * @param ConnectionStatus $connectionStatus
	 *
	 * @return bool whether the connection exists in the database
	 */
	public function hasConnection($instanceName, ConnectionStatus $connectionStatus)
	{
		return $this->filterByInstanceName($instanceName)
		            ->filterByPeer($connectionStatus->peer)
		            ->filterByStarted($connectionStatus->started)
		            ->findOne() !== null;
	}

}
