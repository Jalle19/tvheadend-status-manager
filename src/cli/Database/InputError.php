<?php

namespace Jalle19\StatusManager\Database;

use Jalle19\StatusManager\Database\Base\InputError as BaseInputError;
use Jalle19\StatusManager\Instance\InputErrorCumulative;

/**
 * Class InputError
 * @package   Jalle19\StatusManager\Database
 * @copyright Copyright &copy; Sam Stenvall 2016-
 * @license   https://www.gnu.org/licenses/gpl.html The GNU General Public License v2.0
 */
class InputError extends BaseInputError
{

	/**
	 * @param InputErrorCumulative $cumulative
	 *
	 * @return InputError
	 */
	public function setFromInputErrorCumulative(InputErrorCumulative $cumulative)
	{
		$this->setBerAverage($cumulative->getAverageValue(InputErrorCumulative::VALUE_TYPE_BER));
		$this->setUncAverage($cumulative->getAverageValue(InputErrorCumulative::VALUE_TYPE_UNC));
		$this->setCumulativeTe($cumulative->getLastValue(InputErrorCumulative::VALUE_TYPE_TE));
		$this->setCumulativeCc($cumulative->getLastValue(InputErrorCumulative::VALUE_TYPE_CC));

		return $this;
	}

}
