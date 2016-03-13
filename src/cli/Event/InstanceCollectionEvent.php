<?php

namespace Jalle19\StatusManager\Event;

use Symfony\Component\EventDispatcher\Event;

/**
 * Class InstanceCollectionEvent
 * @package   Jalle19\StatusManager\Event
 * @copyright Copyright &copy; Sam Stenvall 2016-
 * @license   https://www.gnu.org/licenses/gpl.html The GNU General Public License v2.0
 */
class InstanceCollectionEvent extends Event
{

	/**
	 * @var \SplObjectStorage
	 */
	private $_instances;


	/**
	 * InstanceCollectionEvent constructor.
	 *
	 * @param \SplObjectStorage $instances
	 */
	public function __construct($instances)
	{
		$this->_instances = $instances;
	}


	/**
	 * @return \SplObjectStorage
	 */
	public function getInstances()
	{
		return $this->_instances;
	}

}
