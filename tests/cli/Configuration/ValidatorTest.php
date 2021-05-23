<?php

namespace Jalle19\StatusManager\Test\Configuration;

use Jalle19\StatusManager\Configuration\Validator;
use Jalle19\StatusManager\Exception\InvalidConfigurationException;
use PHPUnit\Framework\TestCase;

/**
 * Class ValidatorTest
 * @package   Jalle19\StatusManager\Test\Configuration
 * @copyright Copyright &copy; Sam Stenvall 2016-
 * @license   https://www.gnu.org/licenses/gpl.html The GNU General Public License v2.0
 */
class ValidatorTest extends TestCase
{

	use BasicConfigurationTrait;

	public function testMandatoryValues()
	{
		$this->expectExceptionMessageMatches("*Mandatory*");
		$this->expectException(InvalidConfigurationException::class);
		$configuration = [

		];

		$validator = new Validator($configuration);
		$validator->validate();
	}


	public function testDatabasePath()
	{
		$this->expectExceptionMessageMatches("*The database path*");
		$this->expectException(InvalidConfigurationException::class);
		$configuration                  = $this->getBaseConfiguration();
		$configuration['database_path'] = '/tmp/does/not/exist';

		$validator = new Validator($configuration);
		$validator->validate();
	}


	public function testLogPath()
	{
		$this->expectExceptionMessageMatches("*The log path*");
		$this->expectException(InvalidConfigurationException::class);
		$configuration             = $this->getBaseConfiguration();
		$configuration['log_path'] = '/some/other/path';

		$validator = new Validator($configuration);
		$validator->validate();
	}


	/**
	 * @dataProvider                   updateIntervalProvider
	 *
	 * @param mixed $updateInterval
	 *
	 *
	 *
	 */
	public function testUpdateInterval($updateInterval)
	{
		$this->expectExceptionMessageMatches("*Update interval cannot*");
		$this->expectException(InvalidConfigurationException::class);
		$configuration                    = $this->getBaseConfiguration();
		$configuration['update_interval'] = $updateInterval;

		$validator = new Validator($configuration);
		$validator->validate();
	}


	public function testListenPorts()
	{
		$this->expectExceptionMessage("listen_port and http_listen_port cannot be equal");
		$this->expectException(InvalidConfigurationException::class);
		$configuration                     = $this->getBaseConfiguration();
		$configuration['http_listen_port'] = $configuration['listen_port'];

		$validator = new Validator($configuration);
		$validator->validate();
	}


	/**
	 * @dataProvider                   listenPortProvider
	 *
	 * @param int $listenPort
	 *
	 *
	 *
	 */
	public function testListenPort($listenPort)
	{
		$this->expectExceptionMessageMatches("*Listen port must be between*");
		$this->expectException(InvalidConfigurationException::class);
		$configuration                = $this->getBaseConfiguration();
		$configuration['listen_port'] = $listenPort;

		$validator = new Validator($configuration);
		$validator->validate();
	}


	/**
	 * @return array
	 */
	public function updateIntervalProvider()
	{
		return [
			[0],
			[0.5],
			[0.99],
		];
	}


	/**
	 * @return array
	 */
	public function listenPortProvider()
	{
		return [
			[-1],
			[0],
			[65536],
			[100000],
		];
	}

}
