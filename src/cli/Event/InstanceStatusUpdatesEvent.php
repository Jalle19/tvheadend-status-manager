<?php

namespace Jalle19\StatusManager\Event;

use Jalle19\StatusManager\InstanceStatusCollection;
use Symfony\Component\EventDispatcher\Event;

/**
 * Represents an instance status update event
 * @package   Jalle19\StatusManager\Event
 *
 * @copyright Copyright &copy; Sam Stenvall 2016-
 * @license   https://www.gnu.org/licenses/gpl.html The GNU General Public License v2.0
 */
class InstanceStatusUpdatesEvent extends Event
{

	/**
	 * @var InstanceStatusCollection
	 */
	private $_instanceStatusCollection;


	/**
	 * @param InstanceStatusCollection $_instanceStatusCollection
	 */
	public function __construct(InstanceStatusCollection $_instanceStatusCollection)
	{
		$this->_instanceStatusCollection = $_instanceStatusCollection;
	}


	/**
	 * @return InstanceStatusCollection
	 */
	public function getInstanceStatusCollection()
	{
		return $this->_instanceStatusCollection;
	}

}
