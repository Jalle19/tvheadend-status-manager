<?php

namespace Jalle19\StatusManager\Event;

use Jalle19\StatusManager\Subscription\StateChange;

/**
 * Subscription state change event
 *
 * @package   Jalle19\StatusManager\Event
 * @copyright Copyright &copy; Sam Stenvall 2016-
 * @license   https://www.gnu.org/licenses/gpl.html The GNU General Public License v2.0
 */
class SubscriptionStateChangeEvent extends AbstractInstanceEvent
{

	/**
	 * @var StateChange
	 */
	private $_stateChange;


	/**
	 * SubscriptionStateChangeEvent constructor.
	 *
	 * @param string      $instanceName
	 * @param StateChange $stateChange
	 */
	public function __construct($instanceName, StateChange $stateChange)
	{
		parent::__construct($instanceName);

		$this->_stateChange = $stateChange;
	}


	/**
	 * @return StateChange
	 */
	public function getStateChange()
	{
		return $this->_stateChange;
	}

}
