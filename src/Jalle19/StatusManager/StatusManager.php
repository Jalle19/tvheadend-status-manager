<?php
/**
 * Created by PhpStorm.
 * User: negge
 * Date: 2015-08-30
 * Time: 10:09
 */

namespace Jalle19\StatusManager;

class StatusManager
{

	/**
	 * @var Configuration the configuration
	 */
	private $_configuration;


	/**
	 * StatusManager constructor.
	 *
	 * @param Configuration $_configuration
	 */
	public function __construct(Configuration $_configuration)
	{
		$this->_configuration = $_configuration;
	}

}
