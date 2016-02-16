<?php

namespace Jalle19\StatusManager\Event;

use Jalle19\tvheadend\model\ConnectionStatus;

/**
 * Connection seen event
 *
 * @package   Jalle19\StatusManager\Event
 * @copyright Copyright &copy; Sam Stenvall 2016-
 * @license   https://www.gnu.org/licenses/gpl.html The GNU General Public License v2.0
 */
class ConnectionSeenEvent extends AbstractInstanceEvent
{

	/**
	 * @var ConnectionStatus
	 */
	private $_connection;


	/**
	 * @param string           $instanceName
	 * @param ConnectionStatus $connection
	 */
	public function __construct($instanceName, ConnectionStatus $connection)
	{
		parent::__construct($instanceName);

		$this->_connection = $connection;
	}


	/**
	 * @return ConnectionStatus
	 */
	public function getConnection()
	{
		return $this->_connection;
	}

}
