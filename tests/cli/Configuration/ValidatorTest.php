<?php

namespace Jalle19\StatusManager\Test\Configuration;

use Jalle19\StatusManager\Configuration\Validator;

/**
 * Class ValidatorTest
 * @package   Jalle19\StatusManager\Test\Configuration
 * @copyright Copyright &copy; Sam Stenvall 2016-
 * @license   https://www.gnu.org/licenses/gpl.html The GNU General Public License v2.0
 */
class ValidatorTest extends \PHPUnit_Framework_TestCase
{
	
	use BasicConfigurationTrait;

	/**
	 * @expectedException \Jalle19\StatusManager\Exception\InvalidConfigurationException
	 * @expectedExceptionMessageRegExp *Mandatory*
	 */
	public function testMandatoryValues()
	{
		$configuration = [

		];

		$validator = new Validator($configuration);
		$validator->validate();
	}


	/**
	 * @expectedException \Jalle19\StatusManager\Exception\InvalidConfigurationException
	 * @expectedExceptionMessageRegExp *The database path*
	 */
	public function testDatabasePath()
	{
		$configuration                  = $this->getBaseConfiguration();
		$configuration['database_path'] = '/tmp/does/not/exist';

		$validator = new Validator($configuration);
		$validator->validate();
	}


	/**
	 * @expectedException \Jalle19\StatusManager\Exception\InvalidConfigurationException
	 * @expectedExceptionMessageRegExp *The log path*
	 */
	public function testLogPath()
	{
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
	 * @expectedException \Jalle19\StatusManager\Exception\InvalidConfigurationException
	 * @expectedExceptionMessageRegExp *Update interval cannot*
	 */
	public function testUpdateInterval($updateInterval)
	{
		$configuration                    = $this->getBaseConfiguration();
		$configuration['update_interval'] = $updateInterval;

		$validator = new Validator($configuration);
		$validator->validate();
	}


	/**
	 * @dataProvider                   listenPortProvider
	 *
	 * @param int $listenPort
	 *
	 * @expectedException \Jalle19\StatusManager\Exception\InvalidConfigurationException
	 * @expectedExceptionMessageRegExp *Listen port must be between*
	 */
	public function testListenPort($listenPort)
	{
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
