<?php

namespace Jalle19\StatusManager\Test\Configuration\Reader;

use Jalle19\StatusManager\Configuration\Reader\YamlReader;
use Jalle19\StatusManager\Exception\InvalidConfigurationException;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Yaml\Yaml;

/**
 * Class YamlReaderTest
 * @package   Jalle19\StatusManager\Test\Configuration\Reader
 * @copyright Copyright &copy; Sam Stenvall 2016-
 * @license   https://www.gnu.org/licenses/gpl.html The GNU General Public License v2.0
 */
class YamlReaderTest extends TestCase
{

	public function testReader()
	{
		$configuration = [
			'foo' => 'bar',
			'baz',
		];

		$tmpFile = $this->getTemporaryFilePath();
		file_put_contents($tmpFile, Yaml::dump($configuration));

		$reader = new YamlReader($tmpFile);
		$this->assertEquals($configuration, $reader->readConfiguration());
	}


	public function testMissingFile()
	{
		$this->expectExceptionMessageMatches("*The configuration file does not*");
		$this->expectException(InvalidConfigurationException::class);
		$reader = new YamlReader('/tmp/does/not/exist');
		$reader->readConfiguration();
	}


	public function testUnparsableConfiguration()
	{
		$this->expectExceptionMessageMatches("*Failed to parse*");
		$this->expectException(InvalidConfigurationException::class);
		$tmpFile = $this->getTemporaryFilePath();
		file_put_contents($tmpFile, "\t\tfail");

		$reader = new YamlReader($tmpFile);
		$reader->readConfiguration();
	}


	/**
	 * @return string
	 */
	private function getTemporaryFilePath()
	{
		return tempnam(sys_get_temp_dir(), 'yaml');
	}

}
