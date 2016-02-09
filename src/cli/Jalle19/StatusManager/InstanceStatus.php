<?php

namespace Jalle19\StatusManager;

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
	 * BroadcastMessage constructor.
	 *
	 * @param string                                        $instanceName
	 * @param \jalle19\tvheadend\model\InputStatus[]        $inputs
	 * @param \jalle19\tvheadend\model\SubscriptionStatus[] $subscriptions
	 * @param \jalle19\tvheadend\model\ConnectionStatus[]   $connections
	 */
	public function __construct($instanceName, array $inputs, array $subscriptions, array $connections)
	{
		$this->_instanceName  = $instanceName;
		$this->_inputs        = $inputs;
		$this->_subscriptions = $subscriptions;
		$this->_connections   = $connections;
	}

	/**
	 * @return string
	 */
	public function getInstanceName()
	{
		return $this->_instanceName;
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
		];
	}

}
