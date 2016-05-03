<?php

namespace Jalle19\StatusManager\Configuration\Reader;

use Jalle19\StatusManager\Exception\InvalidConfigurationException;
use Symfony\Component\Yaml\Exception\ParseException;
use Symfony\Component\Yaml\Yaml;

/**
 * Class YamlReader
 * @package   Jalle19\StatusManager\Configuration\Reader
 * @copyright Copyright &copy; Sam Stenvall 2016-
 * @license   https://www.gnu.org/licenses/gpl.html The GNU General Public License v2.0
 */
class YamlReader implements ReaderInterface
{

	/**
	 * @var string
	 */
	private $_file;


	/**
	 * YamlReader constructor.
	 *
	 * @param string $file
	 */
	public function __construct($file)
	{
		$this->_file = $file;
	}


	/**
	 * @inheritdoc
	 */
	public function readConfiguration()
	{
		// Check that the configuration file is readable
		if (!is_readable($this->_file))
			throw new InvalidConfigurationException('The configuration file does not exist or is not readable');

		// Parse the configuration file
		try
		{
			return Yaml::parse(file_get_contents($this->_file));
		}
		catch (ParseException $e)
		{
			throw new InvalidConfigurationException('Failed to parse the specified configuration file: ' . $e->getMessage());
		}
	}

}
