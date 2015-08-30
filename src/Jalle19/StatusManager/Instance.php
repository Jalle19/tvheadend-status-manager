<?php

namespace Jalle19\StatusManager;

use jalle19\tvheadend\Tvheadend;

class Instance
{

	/**
	 * @var string the name of the instance
	 */
	private $_name;

	/**
	 * @var Tvheadend the actual tvheadend instance
	 */
	private $_instance;


	/**
	 * Instance constructor.
	 *
	 * @param string $name
	 * @param string $address
	 * @param int    $port
	 */
	public function __construct($name, $address, $port)
	{
		$this->_name    = $name;

		// Create the actual instance
		$this->_instance = new Tvheadend($address, $port);
	}


	/**
	 * @return string
	 */
	public function getName()
	{
		return $this->_name;
	}


	/**
	 * @return bool
	 */
	public function requiresCredentials()
	{
		return $this->_username !== null && $this->_password !== null;
	}


	/**
	 * Sets the credentials to use
	 *
	 * @param $username
	 * @param $password
	 */
	public function setCredentials($username, $password)
	{
		$this->_username = $username;
		$this->_password = $password;

		$this->_instance->setCredentials($username, $password);
	}


	/**
	 * @return Tvheadend
	 */
	public function getInstance()
	{
		return $this->_instance;
	}

}
