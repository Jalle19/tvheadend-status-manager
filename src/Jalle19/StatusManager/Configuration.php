<?php
/**
 * Created by PhpStorm.
 * User: negge
 * Date: 2015-08-30
 * Time: 10:44
 */

namespace Jalle19\StatusManager;

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
	 * @return int
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
