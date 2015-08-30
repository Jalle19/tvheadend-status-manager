<?php

namespace Jalle19\StatusManager;

use Psr\Log\LoggerInterface;

class StatusManager
{

	/**
	 * @var Configuration the configuration
	 */
	private $_configuration;

	/**
	 * @var LoggerInterface the logger
	 */
	private $_logger;


	/**
	 * StatusManager constructor.
	 *
	 * @param Configuration   $configuration
	 * @param LoggerInterface $logger
	 */
	public function __construct(Configuration $configuration, LoggerInterface $logger)
	{
		$this->_configuration = $configuration;
		$this->_logger        = $logger;
	}


	/**
	 * Runs the application
	 */
	public function run()
	{
		// Start the main loop
		while (true)
		{
			foreach ($this->_configuration->getInstances() as $instance)
			{
				$tvheadend = $instance->getInstance();

				$this->_logger->info('{name} has {channels} channels', [
					'name'     => $instance->getName(),
					'channels' => count($tvheadend->getChannels()),
				]);
			}

			usleep($this->_configuration->getUpdateInterval() * 1000000);
		}
	}

}
