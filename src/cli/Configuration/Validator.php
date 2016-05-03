<?php

namespace Jalle19\StatusManager\Configuration;

use Jalle19\StatusManager\Exception\InvalidConfigurationException;

/**
 * Class Validator
 * @package   Jalle19\StatusManager\Configuration
 * @copyright Copyright &copy; Sam Stenvall 2016-
 * @license   https://www.gnu.org/licenses/gpl.html The GNU General Public License v2.0
 */
class Validator
{

	/**
	 * @var array
	 */
	private $_configuration;

	/**
	 * @var array
	 */
	private static $mandatoryValues = [
		'database_path',
		'log_path',
		'access_token',
		'instances',
		'update_interval',
		'listen_address',
		'listen_port',
	];


	/**
	 * Validator constructor.
	 *
	 * @param array $configuration
	 */
	public function __construct(array $configuration)
	{
		$this->_configuration = $configuration;
	}


	/**
	 * @throws InvalidConfigurationException
	 */
	public function validate()
	{
		// Check that all mandatory values are defined
		foreach (self::$mandatoryValues as $mandatoryValue)
			if (!isset($this->_configuration[$mandatoryValue]) || ($this->_configuration[$mandatoryValue] != '0' && empty($this->_configuration[$mandatoryValue])))
				throw new InvalidConfigurationException('Mandatory configuration value "' . $mandatoryValue . '" is missing');

		if (!is_readable($this->_configuration['database_path']))
			throw new InvalidConfigurationException('The database path does not exist or is not writable');

		// Attempt to create the log path if it doesn't exist, fail if it is not writable
		if (!file_exists($this->_configuration['log_path']))
			touch($this->_configuration['log_path']);

		if (!is_writable($this->_configuration['log_path']))
			throw new InvalidConfigurationException('The log path does not exist or is not writable');

		if (intval($this->_configuration['update_interval']) < 1)
			throw new InvalidConfigurationException('Update interval cannot be lower than 1 second');

		$listenPort = intval($this->_configuration['listen_port']);
		if ($listenPort < 1 || $listenPort > 65535)
			throw new InvalidConfigurationException('Listen port must be between 1 and 65535');
	}

}
