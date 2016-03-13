<?php

namespace Jalle19\StatusManager\Message;

use Jalle19\StatusManager\Exception\MalformedRequestException;

/**
 * Class StatisticsPopularChannelsMessage
 * @package   Jalle19\StatusManager\Message
 * @copyright Copyright &copy; Sam Stenvall 2016-
 * @license   https://www.gnu.org/licenses/gpl.html The GNU General Public License v2.0
 */
class StatisticsPopularChannelsMessage extends AbstractMessage
{

	/**
	 * @var string
	 */
	private $_instanceName;

	/**
	 * @var int
	 */
	private $_limit;


	/**
	 * @inheritdoc
	 */
	public function __construct($type, $parameters)
	{
		parent::__construct($type, $parameters);

		/* @var \stdClass $parameters */
		if (!isset($parameters->instanceName) || empty($parameters->instanceName))
			throw new MalformedRequestException('Missing mandatory "instanceName" parameter');

		$this->_instanceName = $parameters->instanceName;

		if (isset($parameters->limit))
			$this->_limit = $parameters->limit;
	}

}
