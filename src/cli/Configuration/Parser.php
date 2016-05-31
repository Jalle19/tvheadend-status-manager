<?php

namespace Jalle19\StatusManager\Configuration;

use Jalle19\StatusManager\Configuration\Reader\ReaderInterface;
use Jalle19\StatusManager\Exception\InvalidConfigurationException;

/**
 * Class Parser
 * @package   Jalle19\StatusManager\Configuration
 * @copyright Copyright &copy; Sam Stenvall 2016-
 * @license   https://www.gnu.org/licenses/gpl.html The GNU General Public License v2.0
 */
class Parser
{

	/**
	 * Parses the application configuration
	 *
	 * @param ReaderInterface $reader
	 *
	 * @return Configuration the parsed configuration
	 * @throws InvalidConfigurationException if the configuration couldn't be parsed and validated
	 */
	public static function parseConfiguration(ReaderInterface $reader)
	{
		$configuration = $reader->readConfiguration();

		// Validate the configuration
		$validator = new Validator($configuration);
		$validator->validate();

		// Create the configuration object
		$config = new Configuration();
		$config->setDatabasePath($configuration['database_path'])
		       ->setLogPath($configuration['log_path'])
		       ->setInstances(self::parseInstances($configuration))
		       ->setAccessToken($configuration['access_token'])
		       ->setUpdateInterval($configuration['update_interval'])
		       ->setListenAddress($configuration['listen_address'])
		       ->setListenPort($configuration['listen_port'])
		       ->setHttpListenPort($configuration['http_listen_port'])
		       ->setHttpUsername($configuration['http_username'])
		       ->setHttpPassword($configuration['http_password']);

		return $config;
	}


	/**
	 * @param array $configuration
	 *
	 * @return Instance[] the instances
	 */
	private static function parseInstances($configuration)
	{
		$instances = [];

		foreach ($configuration['instances'] as $name => $options)
			$instances[] = self::parseInstance($name, $options);

		return $instances;
	}


	/**
	 * @param string $name the name of the instance
	 * @param array  $options
	 *
	 * @return Instance
	 */
	private static function parseInstance($name, $options)
	{
		$address = $options['address'];
		$port    = intval($options['port']);

		$instance = new Instance($name, $address, $port);

		// Optionally set ignored users
		if (isset($options['ignoredUsers']))
			$instance->setIgnoredUsers($options['ignoredUsers']);

		// Optionally set credentials
		if (isset($options['username']) && isset($options['password']))
			$instance->setCredentials($options['username'], $options['password']);

		return $instance;
	}

}
