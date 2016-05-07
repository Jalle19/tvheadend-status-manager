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

	const TIME_FRAME_ALL_TIME   = 'allTime';
	const TIME_FRAME_LAST_MONTH = 'lastMonth';
	const TIME_FRAME_LAST_WEEK  = 'lastWeek';
	const TIME_FRAME_LAST_DAY   = 'lastDay';

	/**
	 * @var string
	 */
	private $_instanceName;

	/**
	 * @var string
	 */
	private $_timeFrame;


	/**
	 * StatisticsRequest constructor.
	 *
	 * @param string    $type
	 * @param \stdClass $parameters
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

		if (isset($parameters->timeFrame))
			$this->_timeFrame = $parameters->timeFrame;
		else
			$this->_timeFrame = self::TIME_FRAME_ALL_TIME;
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
	public function getTimeFrame()
	{
		return $this->_timeFrame;
	}

}
