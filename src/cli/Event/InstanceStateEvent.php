<?php

namespace Jalle19\StatusManager\Event;

use Jalle19\StatusManager\Configuration\Instance;
use Symfony\Component\EventDispatcher\Event;

/**
 * Represents an instance state event
 *
 * @package   Jalle19\StatusManager\Event
 * @copyright Copyright &copy; Sam Stenvall 2016-
 * @license   https://www.gnu.org/licenses/gpl.html The GNU General Public License v2.0
 */
class InstanceStateEvent extends Event
{

	/**
	 * @var Instance
	 */
	private $_instance;


	/**
	 * @param Instance $_instance
	 */
	public function __construct(Instance $_instance)
	{
		$this->_instance = $_instance;
	}


	/**
	 * @return Instance
	 */
	public function getInstance()
	{
		return $this->_instance;
	}

}
