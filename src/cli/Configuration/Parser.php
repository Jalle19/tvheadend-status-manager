<?php

namespace Jalle19\StatusManager\Configuration;

use Jalle19\StatusManager\Exception\InvalidConfigurationException;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Yaml\Exception\ParseException;
use Symfony\Component\Yaml\Yaml;

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
	 * @param InputInterface $input
	 *
	 * @return Configuration the parsed configuration
	 * @throws InvalidConfigurationException if the configuration contains unrecoverable errors
	 */
	public static function parseConfiguration(InputInterface $input)
	{
		self::validateArguments($input);

		$configFile   = $input->getArgument('configFile');
		$databaseFile = $input->getArgument('databaseFile');
		$logFile      = $input->getArgument('logFile');

		// Parse the configuration file
		try
		{
			$configuration = Yaml::parse(file_get_contents($configFile));
		}
		catch (ParseException $e)
		{
			throw new InvalidConfigurationException('Failed to parse the specified configuration file: ' . $e->getMessage());
		}

		// Validate the configuration. We need at least one instance.
		if (!isset($configuration['instances']) || empty($configuration['instances']))
			throw new InvalidConfigurationException('No instances defined, you need to specify at least one instance');

		$instances = [];

		// Parse instances
		foreach ($configuration['instances'] as $name => $options)
			$instances[] = self::parseInstance($name, $options);

		// Create the configuration object
		$config = new Configuration($databaseFile, $instances);

		// Parse options
		$updateInterval = floatval($input->getOption(Configuration::OPTION_UPDATE_INTERVAL));
		$config->setUpdateInterval($updateInterval);

		$listenAddress = $input->getOption(Configuration::OPTION_LISTEN_ADDRESS);
		$config->setListenAddress($listenAddress);

		$listenPort = $input->getOption(Configuration::OPTION_LISTEN_PORT);
		$config->setListenPort($listenPort);

		$config->setLogPath($logFile);

		return $config;
	}


	/**
	 * @param InputInterface $input
	 *
	 * @throws InvalidConfigurationException if the arguments are invalid
	 */
	private static function validateArguments(InputInterface $input)
	{
		$configFile   = $input->getArgument('configFile');
		$databasePath = $input->getArgument('databaseFile');
		$logFile      = $input->getArgument('logFile');

		// Check that the configuration file exists
		if (!file_exists($configFile))
			throw new InvalidConfigurationException('The specified configuration file does not exist');

		// Check that the database exists and is writable
		if (!file_exists($databasePath))
			throw new InvalidConfigurationException('The specified database path does not exist');
		else if (!is_writable($databasePath))
			throw new InvalidConfigurationException('The specified database path is not writable');

		// Check that the directory of the log file path is writable
		if ($logFile !== null && !is_writable(dirname($logFile)))
			throw new InvalidConfigurationException('The specified log file path is not writable');
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
