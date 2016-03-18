<?php

namespace Jalle19\StatusManager\Configuration;

use Jalle19\StatusManager\Exception\InvalidConfigurationException;
use Symfony\Component\Console\Input\InputInterface;

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
		$configuration = parse_ini_file($configFile, true);

		// Check that the file was parsed
		if ($configuration === false)
			throw new InvalidConfigurationException('Failed to parse the specified configuration file');

		$instances = [];

		// Parse sections
		foreach ($configuration as $section => $values)
		{
			switch (self::getSectionType($section))
			{
				case Configuration::SECTION_TYPE_INSTANCE:
					$instances[] = self::parseInstance($section, $values);
					break;
			}
		}

		// Validate the configuration. We need at least one instance.
		if (empty($instances))
			throw new InvalidConfigurationException('No instances defined, you need to specify at least one instance');

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
	 * @param string $section
	 * @param array  $values
	 *
	 * @return Instance
	 */
	private static function parseInstance($section, $values)
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
	private static function getSectionType($section)
	{
		if (substr($section, 0, 8) === 'instance')
			return Configuration::SECTION_TYPE_INSTANCE;

		throw new InvalidConfigurationException('Unknown section "' . $section . '"');
	}

}
