<?php

namespace Jalle19\StatusManager;

/**
 * Represents a message that can be serialized and sent to clients
 *
 * @package   Jalle19\StatusManager
 * @copyright Copyright &copy; Sam Stenvall 2016-
 * @license   https://www.gnu.org/licenses/gpl.html The GNU General Public License v2.0
 */
class Message implements \JsonSerializable
{

	const TYPE_STATUS_UPDATES = 'statusUpdates';

	/**
	 * @var string
	 */
	private $_message;

	/**
	 * @var \JsonSerializable
	 */
	private $_payload;

	/**
	 * @var array
	 */
	private static $availableTypes = [
		self::TYPE_STATUS_UPDATES,
	];


	/**
	 * @param string            $message
	 * @param \JsonSerializable $payload
	 */
	public function __construct($message, \JsonSerializable $payload)
	{
		if (!in_array($message, self::$availableTypes))
			throw new \InvalidArgumentException('Invalid message type ' . $message);

		$this->_message = $message;
		$this->_payload = $payload;
	}


	/**
	 * @inheritdoc
	 */
	public function jsonSerialize()
	{
		return [
			'message' => $this->_message,
			'payload' => $this->_payload,
		];
	}

}
