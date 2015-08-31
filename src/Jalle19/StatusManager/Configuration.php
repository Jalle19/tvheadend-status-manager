<?php

namespace Jalle19\StatusManager;

/**
 * Class Configuration
 * @package Jalle19\StatusManager
 * @copyright Copyright &copy; Sam Stenvall 2015-
 * @license https://www.gnu.org/licenses/gpl.html The GNU General Public License v2.0
 */
class Configuration
{

	const DEFAULT_UPDATE_INTERVAL = 1;

	/**
	 * @var Instance[] the instances
	 */
	private $_instances;

	/**
	 * @var float the status update interval (in seconds)
	 */
	private $_updateInterval = self::DEFAULT_UPDATE_INTERVAL;


	/**
	 * Configuration constructor.
	 *
	 * @param Instance[] $_instances
	 */
	public function __construct(array $_instances)
	{
		$this->_instances = $_instances;
	}


	/**
	 * @return Instance[]
	 */
	public function getInstances()
	{
		return $this->_instances;
	}


	/**
	 * @return float
	 */
	public function getUpdateInterval()
	{
		return $this->_updateInterval;
	}


	/**
	 * @param int $updateInterval
	 */
	public function setUpdateInterval($updateInterval)
	{
		$this->_updateInterval = $updateInterval;
	}

}
