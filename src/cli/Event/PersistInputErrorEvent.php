<?php

namespace Jalle19\StatusManager\Event;

use Jalle19\StatusManager\Database\Input;
use Jalle19\StatusManager\Instance\InputErrorCumulative;
use Symfony\Component\EventDispatcher\Event;

/**
 * Class PersistInputErrorEvent
 * @package   Jalle19\StatusManager\Event
 * @copyright Copyright &copy; Sam Stenvall 2016-
 * @license   https://www.gnu.org/licenses/gpl.html The GNU General Public License v2.0
 */
class PersistInputErrorEvent extends Event
{

	/**
	 * @var Input
	 */
	private $_input;

	/**
	 * @var InputErrorCumulative
	 */
	private $_inputErrors;


	/**
	 * InputErrorEvent constructor.
	 *
	 * @param Input                $input
	 * @param InputErrorCumulative $inputErrors
	 */
	public function __construct(Input $input, InputErrorCumulative $inputErrors)
	{
		$this->_input       = $input;
		$this->_inputErrors = $inputErrors;
	}


	/**
	 * @return Input
	 */
	public function getInput()
	{
		return $this->_input;
	}


	/**
	 * @return InputErrorCumulative
	 */
	public function getCumulativeErrors()
	{
		return $this->_inputErrors;
	}

}
