<?php

namespace Jalle19\StatusManager\Event;

use Symfony\Component\EventDispatcher\Event;

/**
 * Class InstanceStatusCollectionRequestEvent
 * @package   Jalle19\StatusManager\Event
 * @copyright Copyright &copy; Sam Stenvall 2016-
 * @license   https://www.gnu.org/licenses/gpl.html The GNU General Public License v2.0
 */
class InstanceStatusCollectionRequestEvent extends Event
{

	/**
	 * @var \SplObjectStorage
	 */
	private $_instanceCollection;


	/**
	 * @return \SplObjectStorage
	 */
	public function getInstanceStatusCollection()
	{
		return $this->_instanceCollection;
	}


	/**
	 * @param \SplObjectStorage $instanceCollection
	 */
	public function setInstanceCollection($instanceCollection)
	{
		$this->_instanceCollection = $instanceCollection;
	}

}
