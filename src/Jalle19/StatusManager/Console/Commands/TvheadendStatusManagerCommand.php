<?php

namespace Jalle19\StatusManager\Console\Commands;

use Jalle19\StatusManager\Configuration;
use Jalle19\StatusManager\Instance;
use Jalle19\StatusManager\StatusManager;
use Monolog\Formatter\LineFormatter;
use Monolog\Logger;
use Monolog\Processor\PsrLogMessageProcessor;
use Symfony\Bridge\Monolog\Handler\ConsoleHandler;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class TvheadendStatusManagerCommand
 * @package   Jalle19\StatusManager\Console\Command
 * @copyright Copyright &copy; Sam Stenvall 2015-
 * @license   https://www.gnu.org/licenses/gpl.html The GNU General Public License v2.0
 */
class TvheadendStatusManagerCommand extends Command
{

	const COMMAND_NAME = 'tvheadend-status-manager';


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
			Configuration::DEFAULT_UPDATE_INTERVAL);

		$this->addOption('listenAddress', 'l', InputOption::VALUE_REQUIRED,
			'The address the Websocket server should be listening on',
			Configuration::DEFAULT_LISTEN_ADDRESS);

		$this->addOption('listenPort', 'p', InputOption::VALUE_REQUIRED,
			'The port the Websocket server should be listening on', Configuration::DEFAULT_LISTEN_PORT);
	}


	/**
	 * @inheritdoc
	 */
	protected function execute(InputInterface $input, OutputInterface $output)
	{
		// Configure the logger
		$handler = new ConsoleHandler($output);
		$handler->setFormatter(new LineFormatter("[%datetime%] %level_name%: %message%\n"));

		$logger = new Logger(self::COMMAND_NAME);
		$logger->pushHandler($handler);
		$logger->pushProcessor(new PsrLogMessageProcessor());

		$statusManager = new StatusManager($this->parseConfiguration($input), $logger);
		$statusManager->run();
	}


	/**
	 * Parses the application configuration
	 *
	 * @param InputInterface $input
	 *
	 * @return Configuration the parsed configuration
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
		$updateInterval = floatval($input->getOption(Configuration::OPTION_UPDATE_INTERVAL));
		$config->setUpdateInterval($updateInterval);

		$listenAddress = $input->getOption(Configuration::OPTION_LISTEN_ADDRESS);
		$config->setListenAddress($listenAddress);

		$listenPort = $input->getOption(Configuration::OPTION_LISTEN_PORT);
		$config->setListenPort($listenPort);

		return $config;
	}

}
