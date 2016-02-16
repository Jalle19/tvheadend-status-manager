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
	 * @param InputStatus $input
	 */
	public function __construct($instanceName, InputStatus $input)
	{
		parent::__construct($instanceName);

		$this->_input = $input;
	}


	/**
	 * @return InputStatus
	 */
	public function getInput()
	{
		return $this->_input;
	}

}
