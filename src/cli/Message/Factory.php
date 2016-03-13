<?php

namespace Jalle19\StatusManager\Message;

use Jalle19\StatusManager\Exception\MalformedRequestException;
use Jalle19\StatusManager\Exception\UnknownRequestException;

/**
 * Factory for turning raw client messages into respective message objects.
 *
 * @package   Jalle19\StatusManager\Message
 * @copyright Copyright &copy; Sam Stenvall 2016-
 * @license   https://www.gnu.org/licenses/gpl.html The GNU General Public License v2.0
 */
class Factory
{

	/**
	 * @param string $json
	 *
	 * @return AbstractMessage
	 * @throws UnknownRequestException
	 * @throws MalformedRequestException
	 */
	public static function factory($json)
	{
		$deserialized = json_decode($json);

		if (!isset($deserialized->type) || !isset($deserialized->payload))
			throw new MalformedRequestException('Missing "type" or "payload"');

		$type       = $deserialized->type;
		$parameters = $deserialized->payload;

		switch ($type)
		{
			case AbstractMessage::TYPE_STATISTICS_POPULAR_CHANNELS:
				return new StatisticsPopularChannelsMessage($type, $parameters);
			default:
				throw new UnknownRequestException('Unknown message "' . $type . '"');
		}
	}

}
