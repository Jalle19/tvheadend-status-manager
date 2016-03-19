<?php

namespace Jalle19\StatusManager\Console\Commands;

use Auryn\Injector;
use Bramus\Monolog\Formatter\ColoredLineFormatter;
use Jalle19\StatusManager\Configuration\Configuration;
use Jalle19\StatusManager\Configuration\Parser as ConfigurationParser;
use Jalle19\StatusManager\Event\Events;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Monolog\Processor\PsrLogMessageProcessor;
use Propel\Runtime\Connection\ConnectionManagerSingle;
use Propel\Runtime\Propel;
use Propel\Runtime\ServiceContainer\StandardServiceContainer;
use Psr\Log\LoggerInterface;
use React\EventLoop\Factory as EventLoopFactory;
use Symfony\Bridge\Monolog\Handler\ConsoleHandler;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\EventDispatcher\EventDispatcher;

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
		$this->addArgument('logFile', InputArgument::OPTIONAL, 'The path to the log file');

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
		// Configure Propel and the logger
		$configuration = ConfigurationParser::parseConfiguration($input);
		$logger        = $this->configureLogger($output, $configuration);
		$this->configurePropel($configuration, $logger);

		$injector = new Injector();

		// Configure shared instances
		$eventLoop       = EventLoopFactory::create();
		$eventDispatcher = new EventDispatcher();
		$aliases         = [':logger' => $logger, ':loop' => $eventLoop];

		$injector->share($configuration)
		         ->share($logger)
		         ->share($eventDispatcher)
		         ->share($eventLoop);

		// Create managers
		$statusManager        = $injector->make('Jalle19\StatusManager\Manager\StatusManager', $aliases);
		$instanceStateManager = $injector->make('Jalle19\StatusManager\Manager\InstanceStateManager', $aliases);
		$webSocketManager     = $injector->make('Jalle19\StatusManager\Manager\WebSocketManager', $aliases);
		$persistenceManager   = $injector->make('Jalle19\StatusManager\Manager\PersistenceManager', $aliases);
		$statisticsManager    = $injector->make('Jalle19\StatusManager\Manager\StatisticsManager', $aliases);

		// Wire the event dispatcher
		$webSocketManager->registerMessageHandler($statisticsManager);
		$eventDispatcher->addSubscriber($statusManager);
		$eventDispatcher->addSubscriber($instanceStateManager);
		$eventDispatcher->addSubscriber($webSocketManager);
		$eventDispatcher->addSubscriber($persistenceManager);

		// Configure the event loop and start the application
		$eventLoop->addPeriodicTimer($configuration->getUpdateInterval(), function () use ($eventDispatcher)
		{
			// Emit an event on each tick
			$eventDispatcher->dispatch(Events::MAIN_LOOP_TICK);
		});

		$eventDispatcher->dispatch(Events::MAIN_LOOP_STARTING);
		$eventLoop->run();
	}


	/**
	 * Configures and returns the logger instance
	 *
	 * @param OutputInterface $output
	 * @param Configuration   $configuration
	 *
	 * @return Logger
	 */
	private function configureLogger(OutputInterface $output, Configuration $configuration)
	{
		$consoleHandler = new ConsoleHandler($output);
		$consoleHandler->setFormatter(new ColoredLineFormatter(null, "[%datetime%] %level_name%: %message%\n"));

		$logger = new Logger(self::COMMAND_NAME);
		$logger->pushHandler($consoleHandler);
		$logger->pushProcessor(new PsrLogMessageProcessor());

		if ($configuration->getLogPath() !== null)
		{
			$fileHandler = new StreamHandler($configuration->getLogPath());
			$logger->pushHandler($fileHandler);
		}

		return $logger;
	}


	/**
	 * Configures the database
	 *
	 * @param Configuration   $configuration
	 * @param LoggerInterface $logger
	 */
	private function configurePropel(Configuration $configuration, LoggerInterface $logger)
	{
		/* @var StandardServiceContainer $serviceContainer */
		$serviceContainer = Propel::getServiceContainer();
		$serviceContainer->checkVersion('2.0.0-dev');
		$serviceContainer->setAdapterClass('tvheadend_status_manager', 'sqlite');
		$manager = new ConnectionManagerSingle();
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

}
