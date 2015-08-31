<?php

namespace Jalle19\StatusManager;

class InstanceStatusCollection implements \JsonSerializable
{

	/**
	 * @var InstanceStatus[] the collected status messages
	 */
	private $_messages = [];


	/**
	 * Adds an instance status to the collection
	 *
	 * @param InstanceStatus $message
	 */
	public function add(InstanceStatus $message)
	{
		$this->_messages[] = $message;
	}


	/**
	 * @inheritdoc
	 */
	public function jsonSerialize()
	{
		return [
			'instances' => $this->_messages,
		];
	}

}
