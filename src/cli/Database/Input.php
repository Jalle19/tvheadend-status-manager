<?php

namespace Jalle19\StatusManager\Database;

use Jalle19\StatusManager\Database\Base\Input as BaseInput;
use Jalle19\tvheadend\model\InputStatus;

/**
 * @package   Jalle19\StatusManager\Database
 * @copyright Copyright &copy; Sam Stenvall 2015-
 * @license   https://www.gnu.org/licenses/gpl.html The GNU General Public License v2.0
 */
class Input extends BaseInput
{

	/**
	 * @param InputStatus $inputStatus
	 *
	 * @return string
	 */
	public static function parseMux(InputStatus $inputStatus)
	{
		$parts = self::getStreamParts($inputStatus);

		return $parts[0];
	}


	/**
	 * @param InputStatus $inputStatus
	 *
	 * @return string
	 */
	public static function parseNetwork(InputStatus $inputStatus)
	{
		$parts = self::getStreamParts($inputStatus);

		return $parts[1];
	}


	/**
	 * @param InputStatus $inputStatus
	 *
	 * @return array
	 */
	private static function getStreamParts(InputStatus $inputStatus)
	{
		return explode(' in ', $inputStatus->stream);
	}

}
