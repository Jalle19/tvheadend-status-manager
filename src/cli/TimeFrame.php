<?php

namespace Jalle19\StatusManager;

/**
 * Represents a time frame, can be used to limit
 * @package   Jalle19\StatusManager
 * @copyright Copyright &copy; Sam Stenvall 2016-
 * @license   https://www.gnu.org/licenses/gpl.html The GNU General Public License v2.0
 */
class TimeFrame implements \JsonSerializable
{

	const TIME_FRAME_ALL_TIME   = 'allTime';
	const TIME_FRAME_LAST_MONTH = 'lastMonth';
	const TIME_FRAME_LAST_WEEK  = 'lastWeek';
	const TIME_FRAME_LAST_DAY   = 'lastDay';

	/**
	 * @var array
	 */
	private static $_timeFrames = [
		self::TIME_FRAME_ALL_TIME,
		self::TIME_FRAME_LAST_MONTH,
		self::TIME_FRAME_LAST_WEEK,
		self::TIME_FRAME_LAST_DAY,
	];

	/**
	 * @var string
	 */
	private $_timeFrame;


	/**
	 * TimeFrame constructor.
	 *
	 * @param string $timeFrame
	 *
	 * @throws \InvalidArgumentException when an invalid time frame is specified
	 */
	public function __construct($timeFrame)
	{
		if (!in_array($timeFrame, self::$_timeFrames))
			throw new \InvalidArgumentException('Invalid time frame specified');

		$this->_timeFrame = $timeFrame;
	}


	/**
	 * @return string
	 */
	public function getType()
	{
		return $this->_timeFrame;
	}


	/**
	 * @return int
	 */
	public function getTimestamp()
	{
		$dateTime = new \DateTime();

		switch ($this->_timeFrame)
		{
			case self::TIME_FRAME_LAST_MONTH:
				$dateTime = $dateTime->modify('-1 month');
				break;
			case self::TIME_FRAME_LAST_WEEK:
				$dateTime = $dateTime->modify('-1 week');
				break;
			case self::TIME_FRAME_LAST_DAY:
				$dateTime = $dateTime->modify('-1 day');
				break;
		}

		return $dateTime->getTimestamp();
	}


	/**
	 * @inheritdoc
	 */
	public function jsonSerialize()
	{
		return $this->_timeFrame;
	}

}
