<?php

namespace Jalle19\StatusManager;

use jalle19\tvheadend\model\ConnectionStatus;
use jalle19\tvheadend\model\InputStatus;
use jalle19\tvheadend\model\SubscriptionStatus;

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
	 * (PHP 5 &gt;= 5.4.0)<br/>
	 * Specify data which should be serialized to JSON
	 * @link http://php.net/manual/en/jsonserializable.jsonserialize.php
	 * @return mixed data which can be serialized by <b>json_encode</b>,
	 * which is a value of any type other than a resource.
	 */
	function jsonSerialize()
	{
		return [
			'instanceName'  => $this->_instanceName,
			'inputs'        => $this->_inputs,
			'subscriptions' => $this->_subscriptions,
			'connections'   => $this->_connections,
		];
	}

}
