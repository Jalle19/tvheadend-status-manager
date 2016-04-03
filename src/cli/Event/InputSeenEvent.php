<?php

namespace Jalle19\StatusManager\Event;

use Jalle19\tvheadend\model\InputStatus;

/**
 * Input seen event
 *
 * @package   Jalle19\StatusManager\Event
 * @copyright Copyright &copy; Sam Stenvall 2016-
 * @license   https://www.gnu.org/licenses/gpl.html The GNU General Public License v2.0
 */
class InputSeenEvent extends AbstractInstanceEvent
{

	/**
	 * @var InputStatus
	 */
	private $_input;


	/**
	 * @param string      $instanceName
	 * @param InputStatus $inputStatus
	 */
	public function __construct($instanceName, InputStatus $inputStatus)
	{
		parent::__construct($instanceName);

		$this->_input = $inputStatus;
	}


	/**
	 * @return InputStatus
	 */
	public function getInputStatus()
	{
		return $this->_input;
	}

}
