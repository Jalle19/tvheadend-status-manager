<?php

namespace Jalle19\StatusManager\Configuration\Reader;

/**
 * Class ArrayReader
 * @package   Jalle19\StatusManager\Configuration\Reader
 * @copyright Copyright &copy; Sam Stenvall 2016-
 * @license   https://www.gnu.org/licenses/gpl.html The GNU General Public License v2.0
 */
class ArrayReader implements ReaderInterface
{

	/**
	 * @var array
	 */
	private $_configuration;


	/**
	 * ArrayReader constructor.
	 *
	 * @param array $configuration
	 */
	public function __construct(array $configuration)
	{
		$this->_configuration = $configuration;
	}


	/**
	 * @inheritdoc
	 */
	public function readConfiguration()
	{
		return $this->_configuration;
	}

}
