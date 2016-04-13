<?php

namespace Jalle19\StatusManager\Exception;

/**
 * Class UnknownRequestException
 * @package   Jalle19\StatusManager\Exception
 * @copyright Copyright &copy; Sam Stenvall 2016-
 * @license   https://www.gnu.org/licenses/gpl.html The GNU General Public License v2.0
 */
class UnknownRequestException extends BaseException
{

	/**
	 * @var string
	 */
	private $_type;


	/**
	 * UnknownRequestException constructor.
	 *
	 * @param string $type
	 */
	public function __construct($type)
	{
		parent::__construct();

		$this->_type = $type;
	}


	/**
	 * @return string
	 */
	public function getType()
	{
		return $this->_type;
	}
	
}
