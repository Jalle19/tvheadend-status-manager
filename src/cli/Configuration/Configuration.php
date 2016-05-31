<?php

namespace Jalle19\StatusManager\Configuration;

/**
 * Class Configuration
 * @package   Jalle19\StatusManager\Configuration
 * @copyright Copyright &copy; Sam Stenvall 2015-
 * @license   https://www.gnu.org/licenses/gpl.html The GNU General Public License v2.0
 */
class Configuration
{

	/**
	 * @var string the database path
	 */
	private $_databasePath;

	/**
	 * @var string the log file path
	 */
	private $_logPath;

	/**
	 * @var Instance[] the instances
	 */
	private $_instances;

	/**
	 * @var string the access token used by clients
	 */
	private $_accessToken;

	/**
	 * @var float the status update interval (in seconds)
	 */
	private $_updateInterval;

	/**
	 * @var string the address to listen on
	 */
	private $_listenAddress;

	/**
	 * @var int the port for the WebSocket server to listen on
	 */
	private $_listenPort;

	/**
	 * @var int the port for the HTTP server to listen on
	 */
	private $_httpListenPort;

	/**
	 * @var string
	 */
	private $_httpUsername;

	/**
	 * @var string
	 */
	private $_httpPassword;


	/**
	 * @return string
	 */
	public function getDatabasePath()
	{
		return $this->_databasePath;
	}


	/**
	 * @param string $databasePath
	 *
	 * @return Configuration
	 */
	public function setDatabasePath($databasePath)
	{
		$this->_databasePath = $databasePath;

		return $this;
	}


	/**
	 * @return string
	 */
	public function getLogPath()
	{
		return $this->_logPath;
	}


	/**
	 * @param string $logPath
	 *
	 * @return Configuration
	 */
	public function setLogPath($logPath)
	{
		$this->_logPath = $logPath;

		return $this;
	}


	/**
	 * @return Instance[]
	 */
	public function getInstances()
	{
		return $this->_instances;
	}


	/**
	 * @param Instance[] $instances
	 *
	 * @return Configuration
	 */
	public function setInstances(array $instances)
	{
		$this->_instances = $instances;

		return $this;
	}


	/**
	 * @return string
	 */
	public function getAccessToken()
	{
		return $this->_accessToken;
	}


	/**
	 * @param string $accessToken
	 *
	 * @return Configuration
	 */
	public function setAccessToken($accessToken)
	{
		$this->_accessToken = $accessToken;

		return $this;
	}


	/**
	 * @param string $name
	 *
	 * @return Instance|null
	 */
	public function getInstanceByName($name)
	{
		foreach ($this->_instances as $instance)
			if ($instance->getName() === $name)
				return $instance;

		return null;
	}


	/**
	 * @return float
	 */
	public function getUpdateInterval()
	{
		return $this->_updateInterval;
	}


	/**
	 * @param $updateInterval
	 *
	 * @return Configuration
	 */
	public function setUpdateInterval($updateInterval)
	{
		$this->_updateInterval = $updateInterval;

		return $this;
	}


	/**
	 * @return string
	 */
	public function getListenAddress()
	{
		return $this->_listenAddress;
	}


	/**
	 * @param string $listenAddress
	 *
	 * @return Configuration
	 */
	public function setListenAddress($listenAddress)
	{
		$this->_listenAddress = $listenAddress;

		return $this;
	}


	/**
	 * @return int
	 */
	public function getListenPort()
	{
		return $this->_listenPort;
	}


	/**
	 * @param int $listenPort
	 *
	 * @return Configuration
	 */
	public function setListenPort($listenPort)
	{
		$this->_listenPort = $listenPort;

		return $this;
	}


	/**
	 * @return int
	 */
	public function getHttpListenPort()
	{
		return $this->_httpListenPort;
	}


	/**
	 * @param int $httpListenPort
	 *
	 * @return Configuration
	 */
	public function setHttpListenPort($httpListenPort)
	{
		$this->_httpListenPort = $httpListenPort;

		return $this;
	}


	/**
	 * @return string
	 */
	public function getHttpUsername()
	{
		return $this->_httpUsername;
	}


	/**
	 * @param string $httpUsername
	 *
	 * @return Configuration
	 */
	public function setHttpUsername($httpUsername)
	{
		$this->_httpUsername = $httpUsername;

		return $this;
	}


	/**
	 * @return string
	 */
	public function getHttpPassword()
	{
		return $this->_httpPassword;
	}


	/**
	 * @param string $httpPassword
	 *
	 * @return Configuration
	 */
	public function setHttpPassword($httpPassword)
	{
		$this->_httpPassword = $httpPassword;

		return $this;
	}

}
