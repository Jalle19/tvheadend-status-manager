<?php

namespace Jalle19\StatusManager\Manager;

use Jalle19\StatusManager\Configuration\Configuration;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventDispatcher;

/**
 * Base class for all managers
 *
 * @package   Jalle19\StatusManager\Manager
 * @copyright Copyright &copy; Sam Stenvall 2016-
 * @license   https://www.gnu.org/licenses/gpl.html The GNU General Public License v2.0
 */
abstract class AbstractManager
{

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
	 * AbstractManager constructor.
	 *
	 * @param Configuration   $configuration
	 * @param LoggerInterface $logger
	 * @param EventDispatcher $eventDispatcher
	 */
	public function __construct(Configuration $configuration, LoggerInterface $logger, EventDispatcher $eventDispatcher)
	{
		$this->configuration   = $configuration;
		$this->logger          = $logger;
		$this->eventDispatcher = $eventDispatcher;
	}

}
