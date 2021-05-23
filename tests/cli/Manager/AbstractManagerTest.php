<?php

namespace Jalle19\StatusManager\Test\Manager;

use Jalle19\StatusManager\Configuration\Configuration;
use Jalle19\StatusManager\Configuration\Instance;
use Jalle19\StatusManager\Configuration\Parser as ConfigurationParser;
use Jalle19\StatusManager\Configuration\Reader\ArrayReader;
use Jalle19\StatusManager\Test\Configuration\BasicConfigurationTrait;
use Monolog\Handler\NullHandler;
use Monolog\Logger;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventDispatcher;

/**
 * Class AbstractManagerTest
 * @package   Jalle19\StatusManager\Test\Manager
 * @copyright Copyright &copy; Sam Stenvall 2016-
 * @license   https://www.gnu.org/licenses/gpl.html The GNU General Public License v2.0
 */
abstract class AbstractManagerTest extends TestCase
{

	use BasicConfigurationTrait;

	/**
	 * @var Configuration
	 */
	protected $configuration;

	/**
	 * @var LoggerInterface
	 */
	protected $logger;

	/**
	 * @var EventDispatcher
	 */
	protected $eventDispatcher;


	/**
	 * AbstractManagerTest constructor.
	 */
	public function __construct()
	{
		parent::__construct();

		$this->eventDispatcher = new EventDispatcher();

		// Configure a null logger to suppress unwanted output
		$this->logger = new Logger('TestLogger');
		$this->logger->setHandlers([new NullHandler()]);

		// Configure a single test instance
		$this->configuration = ConfigurationParser::parseConfiguration(new ArrayReader($this->getBaseConfiguration()));
	}


	/**
	 * @return Instance
	 */
	protected function getTestInstance()
	{
		$instances = $this->configuration->getInstances();

		return $instances[0];
	}

}
