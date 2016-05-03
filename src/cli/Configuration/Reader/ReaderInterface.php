<?php

namespace Jalle19\StatusManager\Configuration\Reader;

use Jalle19\StatusManager\Exception\InvalidConfigurationException;

/**
 * Interface for configuration readers
 *
 * @package   Jalle19\StatusManager\Configuration\Reader
 * @copyright Copyright &copy; Sam Stenvall 2016-
 * @license   https://www.gnu.org/licenses/gpl.html The GNU General Public License v2.0
 */
interface ReaderInterface
{

	/**
	 * @return array the raw configuration as an array
	 *
	 * @throws InvalidConfigurationException if the configuration cannot be read at all
	 */
	public function readConfiguration();

}
