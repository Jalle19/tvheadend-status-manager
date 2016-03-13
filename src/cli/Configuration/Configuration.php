<?php

namespace Jalle19\StatusManager\Configuration;

use Jalle19\StatusManager\Exception\InvalidConfigurationException;

/**
 * Class Configuration
 * @package   Jalle19\StatusManager\Configuration
 * @copyright Copyright &copy; Sam Stenvall 2015-
 * @license   https://www.gnu.org/licenses/gpl.html The GNU General Public License v2.0
 */
class Configuration
{

	const SECTION_TYPE_INSTANCE = 'instance';

	const OPTION_UPDATE_INTERVAL = 'updateInterval';
	const OPTION_LISTEN_ADDRESS  = 'listenAddress';
	const OPTION_LISTEN_PORT     = 'listenPort';

	const DEFAULT_UPDATE_INTERVAL = 2;
	const DEFAULT_LISTEN_ADDRESS  = '0.0.0.0';
	const DEFAULT_LISTEN_PORT     = 9333;

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
	 * @var float the status update interval (in seconds)
	 */
	private $_updateInterval = self::DEFAULT_UPDATE_INTERVAL;

	/**
	 * @var string the address to listen on
	 */
	private $_listenAddress = self::DEFAULT_LISTEN_ADDRESS;

	/**
	 * @var int the port to listen on
	 */
	private $_listenPort = self::DEFAULT_LISTEN_PORT;


	/**
	 * @param string     $databasePath
	 * @param Instance[] $_instances
	 */
	public function __construct($databasePath, array $_instances)
	{
		$this->_databasePath = $databasePath;
		$this->_instances    = $_instances;
	}


	/**
	 * @return string
	 */
	public function getDatabasePath()
	{
		return $this->_databasePath;
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
	 */
	public function setLogPath($logPath)
	{
		$this->_logPath = $logPath;
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
	 * @param $updateInterval
	 *
	 * @throws \RuntimeException
	 */
	public function setUpdateInterval($updateInterval)
	{
		if ($updateInterval <= 0)
			throw new \RuntimeException('Invalid update interval specified');

		$this->_updateInterval = $updateInterval;
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
	 */
	public function setListenAddress($listenAddress)
	{
		$this->_listenAddress = $listenAddress;
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
	 * @throws \RuntimeException
	 */
	public function setListenPort($listenPort)
	{
		if ($listenPort < 1 || $listenPort > 65535)
			throw new \RuntimeException('Invalid port specified');

		$this->_listenPort = $listenPort;
	}


	/**
	 * @param string $section
	 * @param array  $values
	 *
	 * @return Instance
	 */
	public static function parseInstance($section, $values)
	{
		$name    = substr($section, 9);
		$address = $values['address'];
		$port    = intval($values['port']);

		$instance = new Instance($name, $address, $port);

		// Optionally set ignored users
		if (isset($values['ignoredUsers']))
			$instance->setIgnoredUsers($values['ignoredUsers']);

		// Optionally set credentials
		if (isset($values['username']) && isset($values['password']))
			$instance->setCredentials($values['username'], $values['password']);

		return $instance;
	}


	/**
	 * Returns the determined section type based on the specified section name
	 *
	 * @param string $section
	 *
	 * @return string
	 * @throws InvalidConfigurationException if the section type could not be determined
	 */
	public static function getSectionType($section)
	{
		if (substr($section, 0, 8) === 'instance')
			return Configuration::SECTION_TYPE_INSTANCE;

		throw new InvalidConfigurationException('Unknown section "' . $section . '"');
	}

}
