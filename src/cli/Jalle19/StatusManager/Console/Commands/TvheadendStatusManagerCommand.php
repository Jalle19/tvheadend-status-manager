<?php

namespace Jalle19\StatusManager\Console\Commands;

use Bramus\Monolog\Formatter\ColoredLineFormatter;
use Jalle19\StatusManager\Configuration;
use Jalle19\StatusManager\Exception\InvalidConfigurationException;
use Jalle19\StatusManager\Instance;
use Jalle19\StatusManager\StatusManager;
use Monolog\Logger;
use Monolog\Processor\PsrLogMessageProcessor;
use Propel\Runtime\ServiceContainer\StandardServiceContainer;
use Psr\Log\LoggerInterface;
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
		$this->addArgument('databaseFile', InputArgument::REQUIRED, 'The path to the database');

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
		$handler->setFormatter(new ColoredLineFormatter(null, "[%datetime%] %level_name%: %message%\n"));

		$logger = new Logger(self::COMMAND_NAME);
		$logger->pushHandler($handler);
		$logger->pushProcessor(new PsrLogMessageProcessor());

		// Parse the configuration
		$configuration = $this->parseConfiguration($input);

		// Configure Propel
		$this->configurePropel($configuration, $logger);

		// Start the application
		$statusManager = new StatusManager($configuration, $logger);
		$statusManager->run();
	}


	/**
	 * Configurs the database
	 *
	 * @param Configuration   $configuration
	 * @param LoggerInterface $logger
	 */
	private function configurePropel(Configuration $configuration, LoggerInterface $logger)
	{
		/* @var StandardServiceContainer $serviceContainer */
		$serviceContainer = \Propel\Runtime\Propel::getServiceContainer();
		$serviceContainer->checkVersion('2.0.0-dev');
		$serviceContainer->setAdapterClass('tvheadend_status_manager', 'sqlite');
		$manager = new \Propel\Runtime\Connection\ConnectionManagerSingle();
		$manager->setConfiguration([
			'classname'  => 'Propel\\Runtime\\Connection\\ConnectionWrapper',
			'dsn'        => 'sqlite:' . $configuration->getDatabasePath(),
			'user'       => null,
			'password'   => '',
			'attributes' => [
				'ATTR_EMULATE_PREPARES' => false,
			],
			'settings'   => [
				'charset' => 'utf8',
				'queries' => [],
			],
		]);
		$manager->setName('tvheadend_status_manager');
		$serviceContainer->setConnectionManager('tvheadend_status_manager', $manager);
		$serviceContainer->setDefaultDatasource('tvheadend_status_manager');

		$serviceContainer->setLogger(self::COMMAND_NAME, $logger);
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
			throw new InvalidConfigurationException('The specified configuration file does not exist');

		// Check that the database exists and is writable
		$databasePath = $input->getArgument('databaseFile');

		if (!file_exists($databasePath))
			throw new InvalidConfigurationException('The specified database path does not exist');
		else if (!is_writable($databasePath))
			throw new InvalidConfigurationException('The specified database path is not writable');

		// Parse the configuration file
		$configuration = parse_ini_file($input->getArgument('configFile'), true);

		// Check that the file was parsed
		if ($configuration === false)
			throw new InvalidConfigurationException('Failed to parse the specified configuration file');

		$instances    = [];

		// Parse sections
		foreach ($configuration as $section => $values)
		{
			switch (self::getSectionType($section))
			{
				case Configuration::SECTION_TYPE_INSTANCE:
					$name    = substr($section, 9);
					$address = $values['address'];
					$port    = intval($values['port']);

					$instance = new Instance($name, $address, $port);

					// Optionally set credentials
					if (isset($values['username']) && isset($values['password']))
						$instance->setCredentials($values['username'], $values['password']);

					$instances[] = $instance;
					break;
			}
		}

		// Validate the configuration. We need at least one instance.
		if (empty($instances))
			throw new InvalidConfigurationException('No instances defined, you need to specify at least one instance');

		// Create the configuration object
		$config = new Configuration($databasePath, $instances);

		// Parse options
		$updateInterval = floatval($input->getOption(Configuration::OPTION_UPDATE_INTERVAL));
		$config->setUpdateInterval($updateInterval);

		$listenAddress = $input->getOption(Configuration::OPTION_LISTEN_ADDRESS);
		$config->setListenAddress($listenAddress);

		$listenPort = $input->getOption(Configuration::OPTION_LISTEN_PORT);
		$config->setListenPort($listenPort);

		return $config;
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
