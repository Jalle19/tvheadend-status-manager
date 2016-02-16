<?php

namespace Jalle19\StatusManager\Event;

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
	 * @param string             $instanceName
	 * @param SubscriptionStatus $subscription
	 */
	public function __construct($instanceName, SubscriptionStatus $subscription)
	{
		parent::__construct($instanceName);

		$this->_subscription = $subscription;
	}


	/**
	 * @return SubscriptionStatus
	 */
	public function getSubscription()
	{
		return $this->_subscription;
	}

}
