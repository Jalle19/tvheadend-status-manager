<?php

namespace Jalle19\StatusManager\Instance;

use Jalle19\tvheadend\model\InputStatus;

/**
 * Class InputErrorCumulative
 * @package   Jalle19\StatusManager\Instance
 * @copyright Copyright &copy; Sam Stenvall 2016-
 * @license   https://www.gnu.org/licenses/gpl.html The GNU General Public License v2.0
 */
class InputErrorCumulative
{

	const VALUE_TYPE_BER = 'ber';
	const VALUE_TYPE_UNC = 'unc';
	const VALUE_TYPE_TE  = 'te';
	const VALUE_TYPE_CC  = 'cc';

	/**
	 * @var \DateTime
	 */
	private $_created;

	/**
	 * @var array
	 */
	private $_counters;


	/**
	 * Initializes all value counters
	 */
	public function __construct()
	{
		$this->_created = new \DateTime();

		foreach ([self::VALUE_TYPE_BER, self::VALUE_TYPE_UNC, self::VALUE_TYPE_TE, self::VALUE_TYPE_CC] as $type)
			$this->_counters[$type] = [];
	}


	/**
	 * @return \DateTime
	 */
	public function getCreated()
	{
		return $this->_created;
	}


	/**
	 * @param InputStatus $inputStatus
	 */
	public function accumulate(InputStatus $inputStatus)
	{
		$this->addValue(InputErrorCumulative::VALUE_TYPE_BER, $inputStatus->ber);
		$this->addValue(InputErrorCumulative::VALUE_TYPE_UNC, $inputStatus->unc);
		$this->addValue(InputErrorCumulative::VALUE_TYPE_TE, $inputStatus->te);
		$this->addValue(InputErrorCumulative::VALUE_TYPE_CC, $inputStatus->cc);
	}


	/**
	 * @param string $type
	 *
	 * @return float
	 */
	public function getAverageValue($type)
	{
		$count = count($this->_counters[$type]);

		if ($count === 0)
			return 0;

		return array_sum($this->_counters[$type]) / $count;
	}


	/**
	 * @param string $type
	 *
	 * @return mixed
	 */
	public function getLastValue($type)
	{
		return end($this->_counters[$type]);
	}


	/**
	 * @param string $type
	 * @param int    $value
	 */
	private function addValue($type, $value)
	{
		$this->_counters[$type][] = $value;
	}

}
