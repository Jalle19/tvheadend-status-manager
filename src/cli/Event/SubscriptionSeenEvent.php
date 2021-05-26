<?php

namespace Jalle19\StatusManager\Event;

use Jalle19\StatusManager\Instance\InstanceStatus;
use Jalle19\tvheadend\model\SubscriptionStatus;

/**
 * Subscription seen event
 *
 * @package   Jalle19\StatusManager\Event
 * @copyright Copyright &copy; Sam Stenvall 2016-
 * @license   https://www.gnu.org/licenses/gpl.html The GNU General Public License v2.0
 */
class SubscriptionSeenEvent extends AbstractInstanceEvent
{

	/**
	 * @var SubscriptionStatus
	 */
	private $_subscription;

	/**
	 * @var InstanceStatus
	 */
	private InstanceStatus $_instanceStatus;


	/**
	 * @param string             $instanceName
	 * @param InstanceStatus     $instanceStatus
	 * @param SubscriptionStatus $subscription
	 */
	public function __construct(
		$instanceName,
		InstanceStatus $instanceStatus,
		SubscriptionStatus $subscription
	) {
		parent::__construct($instanceName);

		$this->_instanceStatus = $instanceStatus;
		$this->_subscription   = $subscription;
	}


	/**
	 * @return SubscriptionStatus
	 */
	public function getSubscription()
	{
		return $this->_subscription;
	}


	/**
	 * @return InstanceStatus
	 */
	public function getInstanceStatus(): InstanceStatus
	{
		return $this->_instanceStatus;
	}
}
