<?php

namespace Jalle19\StatusManager\Event;

use Jalle19\tvheadend\Tvheadend;
use Symfony\Component\EventDispatcher\Event;

/**
 * Instance seen event
 *
 * @package   Jalle19\StatusManager\Event
 * @copyright Copyright &copy; Sam Stenvall 2016-
 * @license   https://www.gnu.org/licenses/gpl.html The GNU General Public License v2.0
 */
class InstanceSeenEvent extends Event
{

	/**
	 * @var Tvheadend
	 */
	private $_instance;


	/**
	 * @param Tvheadend $instance
	 */
	public function __construct(Tvheadend $instance)
	{
		$this->_instance = $instance;
	}


	/**
	 * @return Tvheadend
	 */
	public function getInstance()
	{
		return $this->_instance;
	}

}
