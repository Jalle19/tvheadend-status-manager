<?php

namespace Jalle19\StatusManager\Message\Request;

use Jalle19\StatusManager\Exception\MalformedRequestException;
use Jalle19\StatusManager\Message\AbstractMessage;

/**
 * Class PopularChannelsRequest
 * @package   Jalle19\StatusManager\Message\Request
 * @copyright Copyright &copy; Sam Stenvall 2016-
 * @license   https://www.gnu.org/licenses/gpl.html The GNU General Public License v2.0
 */
class PopularChannelsRequest extends StatisticsRequest
{

	/**
	 * @var int
	 */
	private $_limit;


	/**
	 * PopularChannelsRequest constructor.
	 *
	 * @param string $parameters
	 */
	public function __construct($parameters)
	{
		parent::__construct(self::TYPE_POPULAR_CHANNELS_REQUEST, $parameters);

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
