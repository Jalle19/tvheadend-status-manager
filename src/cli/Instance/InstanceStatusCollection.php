<?php

namespace Jalle19\StatusManager\Instance;

/**
 * Class InstanceStatusCollection\Instance
 * @package   Jalle19\StatusManager
 * @copyright Copyright &copy; Sam Stenvall 2015-
 * @license   https://www.gnu.org/licenses/gpl.html The GNU General Public License v2.0
 */
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
	 * @return InstanceStatus[]
	 */
	public function getInstanceStatuses()
	{
		return $this->_messages;
	}


	/**
	 * @inheritdoc
	 */
	public function jsonSerialize()
	{
		return $this->_messages;
	}

}
