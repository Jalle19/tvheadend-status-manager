<?php

namespace Jalle19\StatusManager\Message\Request;

/**
 * Class MostActiveWatchersRequest
 * @package   Jalle19\StatusManager\Message\Request
 * @copyright Copyright &copy; Sam Stenvall 2016-
 * @license   https://www.gnu.org/licenses/gpl.html The GNU General Public License v2.0
 */
class MostActiveWatchersRequest extends StatisticsRequest
{

	/**
	 * @var int
	 */
	private $_limit;


	/**
	 * PopularChannelsRequest constructor.
	 *
	 * @param \stdClass $parameters
	 */
	public function __construct($parameters)
	{
		parent::__construct(self::TYPE_MOST_ACTIVE_WATCHERS_REQUEST, $parameters);

		/* @var \stdClass $parameters */
		if (isset($parameters->limit))
			$this->_limit = $parameters->limit;
	}


	/**
	 * @return int
	 */
	public function getLimit()
	{
		return $this->_limit;
	}

}
