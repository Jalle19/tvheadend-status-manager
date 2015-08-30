<?php

namespace Jalle19\StatusManager\Console\Commands;

use Jalle19\StatusManager\Configuration;
use Jalle19\StatusManager\Instance;
use jalle19\tvheadend\Tvheadend;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputDefinition;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class TvheadendStatusManagerCommand extends Command
{

	const COMMAND_NAME = 'tvheadend-status-manager';

	/**
	 * @var Configuration the configuration
	 */
	private $_configuration;

	/**
	 * @var Instance[] the tvheadend instances we're listening to
	 */
	private $_instances = [];


	/**
	 * @inheritdoc
	 */
	protected function configure()
	{
		$this->setName(self::COMMAND_NAME);
		$this->setDescription('Aggregating status manager for tvheadend instances');

		// Add arguments
		$this->addArgument('configFile', InputArgument::REQUIRED, 'The path to the configuration file');

		// Add options
		$this->addOption('updateInterval', 'i', InputOption::VALUE_REQUIRED, 'The status update interval (in seconds)',
			Configuration::DEFAULT_UPDATER_INTERVAL);
	}


	protected function execute(InputInterface $input, OutputInterface $output)
	{
		// Parse the configuration
		$this->_configuration = $this->parseConfiguration($input);

		// Start the main loop
		while (true)
		{
			foreach($this->_configuration->getInstances() as $instance)
			{
				$tvheadend = $instance->getInstance();
				$channels = $tvheadend->getNetworks();
				var_dump(count($channels));
			}

			usleep($this->_configuration->getUpdateInterval() * 1000000);
		}
	}


	/**
	 * Parses the application configuration
	 *
	 * @param InputInterface $input
	 */
	private function parseConfiguration(InputInterface $input)
	{
		// Check that the configuration file exists
		if (!file_exists($input->getArgument('configFile')))
			throw new \InvalidArgumentException('The specified configuration file does not exist');

		// Parse the configuration file
		$configuration = parse_ini_file($input->getArgument('configFile'), true);

		// Check that the file was parsed
		if ($configuration === false)
			throw new \RuntimeException('Failed to parse the specified configuration file');

		// Check that we have at least one instance
		$hasInstances = false;

		foreach (array_keys($configuration) as $section)
			if (substr($section, 0, 8) === 'instance')
				$hasInstances = true;

		if (!$hasInstances)
			throw new \RuntimeException('No instances defined, you need to specify at least one instance');

		// Parse instances
		$instances = [];

		foreach ($configuration as $section => $values)
		{
			$name    = substr($section, 9);
			$address = $values['address'];
			$port    = intval($values['port']);

			$instance = new Instance($name, $address, $port);

			// Optionally set credentials
			if (isset($values['username']) && isset($values['password']))
				$instance->setCredentials($values['username'], $values['password']);

			$instances[] = $instance;
		}

		// Create the configuration object
		$config = new Configuration($instances);

		// Parse options
		$updateInterval = floatval($input->getOption('updateInterval'));

		if ($updateInterval <= 0)
			throw new \RuntimeException('Invalid update interval specified');

		$config->setUpdateInterval($updateInterval);

		return $config;
	}

}
