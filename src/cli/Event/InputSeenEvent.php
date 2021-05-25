<?php

namespace Jalle19\StatusManager\Event;

use Jalle19\StatusManager\Instance\InstanceStatus;
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
	 * @var InstanceStatus
	 */
	private InstanceStatus $_instanceStatus;


	/**
	 * @param string         $instanceName
	 * @param InstanceStatus $instanceStatus
	 * @param InputStatus    $inputStatus
	 */
	public function __construct(
		$instanceName,
		InstanceStatus $instanceStatus,
		InputStatus $inputStatus
	) {
		parent::__construct($instanceName);

		$this->_instanceStatus = $instanceStatus;
		$this->_input          = $inputStatus;
	}


	/**
	 * @return InputStatus
	 */
	public function getInputStatus()
	{
		return $this->_input;
	}


	/**
	 * @return InstanceStatus
	 */
	public function getInstanceStatus(): InstanceStatus
	{
		return $this->_instanceStatus;
	}
}
