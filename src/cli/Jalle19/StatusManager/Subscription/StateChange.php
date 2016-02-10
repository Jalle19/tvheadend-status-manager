<?php

namespace Jalle19\StatusManager\Subscription;

/**
 * Represents a subscription state change
 *
 * @package   Jalle19\StatusManager\Subscription
 * @copyright Copyright &copy; Sam Stenvall 2016-
 * @license   https://www.gnu.org/licenses/gpl.html The GNU General Public License v2.0
 */
class StateChange implements \JsonSerializable
{

	const STATE_SUBSCRIPTION_STARTED = 'started';
	const STATE_SUBSCRIPTION_STOPPED = 'stopped';

	/**
	 * @var int
	 */
	private $_subscriptionId;

	/**
	 * @var string
	 */
	private $_state;


	/**
	 * StateChange constructor
	 *
	 * @param int $subscriptionId
	 */
	public function __construct($subscriptionId)
	{
		$this->_subscriptionId = $subscriptionId;
	}


	/**
	 * @return int
	 */
	public function getSubscriptionId()
	{
		return $this->_subscriptionId;
	}


	/**
	 * @return string
	 */
	public function getState()
	{
		return $this->_state;
	}


	/**
	 * @param string $state
	 */
	public function setState($state)
	{
		$this->_state = $state;
	}


	/**
	 * @inheritdoc
	 */
	public function jsonSerialize()
	{
		return [
			'subscriptionId' => $this->_subscriptionId,
			'state'          => $this->_state,
		];
	}

}
