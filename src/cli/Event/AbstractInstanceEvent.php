<?php

namespace Jalle19\StatusManager\Event;

use Symfony\Component\EventDispatcher\Event;

/**
 * Base class for all events that are tied to a specific instance
 *
 * @package   Jalle19\StatusManager\Event
 * @copyright Copyright &copy; Sam Stenvall 2016-
 * @license   https://www.gnu.org/licenses/gpl.html The GNU General Public License v2.0
 */
abstract class AbstractInstanceEvent extends Event
{

	/**
	 * @var string
	 */
	protected $_instance;


	/**
	 * @param string $_instance
	 */
	public function __construct($_instance)
	{
		$this->_instance = $_instance;
	}


	/**
	 * @return string
	 */
	public function getInstance()
	{
		return $this->_instance;
	}

}
