<?php

namespace Jalle19\StatusManager\Test\Configuration;

use Jalle19\StatusManager\Configuration\Parser;
use Jalle19\StatusManager\Configuration\Reader\ArrayReader;

/**
 * Class ParserTest
 * @package   Jalle19\StatusManager\Test\Configuration
 * @copyright Copyright &copy; Sam Stenvall 2016-
 * @license   https://www.gnu.org/licenses/gpl.html The GNU General Public License v2.0
 */
class ParserTest extends \PHPUnit_Framework_TestCase
{

	use BasicConfigurationTrait;


	public function testParser()
	{
		$rawConfiguration = $this->getBaseConfiguration();

		// Configure two instances
		$rawConfiguration['instances'] = [
			'example.com'     => [
				'address' => 'example.com',
				'port'    => 9981,
			],
			'foo.example.com' => [
				'address' => 'foo.example.com',
				'port'    => 9981,
			],
		];

		$configuration = Parser::parseConfiguration(new ArrayReader($rawConfiguration));

		$this->assertEquals($rawConfiguration['database_path'], $configuration->getDatabasePath());
		$this->assertEquals($rawConfiguration['log_path'], $configuration->getLogPath());
		$this->assertEquals($rawConfiguration['access_token'], $configuration->getAccessToken());
		$this->assertCount(2, $configuration->getInstances());
		$this->assertEquals('example.com', $configuration->getInstanceByName('example.com')->getName());
		$this->assertEquals('foo.example.com', $configuration->getInstanceByName('foo.example.com')->getName());
		$this->assertEquals($rawConfiguration['update_interval'], $configuration->getUpdateInterval());
		$this->assertEquals($rawConfiguration['listen_address'], $configuration->getListenAddress());
		$this->assertEquals($rawConfiguration['listen_port'], $configuration->getListenPort());
		$this->assertEquals([], $configuration->getInstanceByName('example.com')->getIgnoredUsers());
	}

}
