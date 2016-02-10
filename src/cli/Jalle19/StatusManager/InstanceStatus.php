<?php

namespace Jalle19\StatusManager;

use Jalle19\StatusManager\Subscription\StateChange;
use jalle19\tvheadend\model\ConnectionStatus;
use jalle19\tvheadend\model\InputStatus;
use jalle19\tvheadend\model\SubscriptionStatus;

/**
 * Class InstanceStatus
 * @package   Jalle19\StatusManager
 * @copyright Copyright &copy; Sam Stenvall 2015-
 * @license   https://www.gnu.org/licenses/gpl.html The GNU General Public License v2.0
 */
class InstanceStatus implements \JsonSerializable
{

	/**
	 * @var string the name of the instance
	 */
	private $_instanceName;

	/**
	 * @var InputStatus[]
	 */
	private $_inputs;

	/**
	 * @var SubscriptionStatus[]
	 */
	private $_subscriptions;

	/**
	 * @var ConnectionStatus[]
	 */
	private $_connections;

	/**
	 * @var StateChange[]
	 */
	private $_subscriptionStateChanges;


	/**
	 * BroadcastMessage constructor.
	 *
	 * @param string               $instanceName
	 * @param InputStatus[]        $inputs
	 * @param SubscriptionStatus[] $subscriptions
	 * @param ConnectionStatus[]   $connections
	 * @param StateChange[]        $subscriptionStateChanges
	 *
	 */
	public function __construct(
		$instanceName,
		array $inputs,
		array $subscriptions,
		array $connections,
		array $subscriptionStateChanges
	) {
		$this->_instanceName             = $instanceName;
		$this->_inputs                   = $inputs;
		$this->_subscriptions            = $subscriptions;
		$this->_connections              = $connections;
		$this->_subscriptionStateChanges = $subscriptionStateChanges;
	}


	/**
	 * @return string
	 */
	public function getInstanceName()
	{
		return $this->_instanceName;
	}


	/**
	 * @return ConnectionStatus[]
	 */
	public function getConnections()
	{
		return $this->_connections;
	}


	/**
	 * @return SubscriptionStatus[]
	 */
	public function getSubscriptions()
	{
		return $this->_subscriptions;
	}


	/**
	 * @return Subscription\StateChange[]
	 */
	public function getSubscriptionStateChanges()
	{
		return $this->_subscriptionStateChanges;
	}


	/**
	 * @inheritdoc
	 */
	public function jsonSerialize()
	{
		return [
			'instanceName'             => $this->_instanceName,
			'inputs'                   => $this->_inputs,
			'subscriptions'            => $this->_subscriptions,
			'connections'              => $this->_connections,
			'subscriptionStateChanges' => $this->_subscriptionStateChanges,
		];
	}

}
