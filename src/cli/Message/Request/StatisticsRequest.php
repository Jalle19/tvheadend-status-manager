<?php

namespace Jalle19\StatusManager\Message\Request;

use Jalle19\StatusManager\Exception\MalformedRequestException;
use Jalle19\StatusManager\Message\AbstractMessage;

/**
 * Base class for all requests for statistics
 *
 * @package   Jalle19\StatusManager\Message\Request
 * @copyright Copyright &copy; Sam Stenvall 2016-
 * @license   https://www.gnu.org/licenses/gpl.html The GNU General Public License v2.0
 */
abstract class StatisticsRequest extends AbstractMessage
{

	const TIME_INTERVAL_ALL_TIME   = 'allTime';
	const TIME_INTERVAL_LAST_MONTH = 'lastMonth';

	/**
	 * @var string
	 */
	private $_instanceName;

	/**
	 * @var string
	 */
	private $_timeInterval;


	/**
	 * StatisticsRequest constructor.
	 *
	 * @param string $type
	 * @param mixed  $parameters
	 * 
	 * @throws MalformedRequestException
	 */
	public function __construct($type, $parameters)
	{
		parent::__construct($type, $parameters);

		/* @var \stdClass $parameters */
		if (!isset($parameters->instanceName) || empty($parameters->instanceName))
			throw new MalformedRequestException('Missing mandatory "instanceName" parameter');

		$this->_instanceName = $parameters->instanceName;

		if (isset($parameters->timeInterval))
			$this->_timeInterval = $parameters->timeInterval;
		else
			$this->_timeInterval = self::TIME_INTERVAL_ALL_TIME;
	}


	/**
	 * @return string
	 */
	public function getInstanceName()
	{
		return $this->_instanceName;
	}


	/**
	 * @return string
	 */
	public function getTimeInterval()
	{
		return $this->_timeInterval;
	}

}
