<?php

namespace Jalle19\StatusManager\Message\Request;

use Jalle19\StatusManager\Exception\MalformedRequestException;
use Jalle19\StatusManager\Message\AbstractMessage;
use Jalle19\StatusManager\TimeFrame;

/**
 * Base class for all requests for statistics
 *
 * @package   Jalle19\StatusManager\Message\Request
 * @copyright Copyright &copy; Sam Stenvall 2016-
 * @license   https://www.gnu.org/licenses/gpl.html The GNU General Public License v2.0
 */
abstract class StatisticsRequest extends AbstractMessage
{

	/**
	 * @var string
	 */
	private $_instanceName;

	/**
	 * @var TimeFrame
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
			$this->_timeFrame = new TimeFrame($parameters->timeFrame);
		else
			$this->_timeFrame = new TimeFrame(TimeFrame::TIME_FRAME_ALL_TIME);
	}


	/**
	 * @return string
	 */
	public function getInstanceName()
	{
		return $this->_instanceName;
	}


	/**
	 * @return TimeFrame
	 */
	public function getTimeFrame()
	{
		return $this->_timeFrame;
	}

}
