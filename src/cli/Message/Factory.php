<?php

namespace Jalle19\StatusManager\Message;

use Jalle19\StatusManager\Exception\MalformedRequestException;
use Jalle19\StatusManager\Exception\UnknownRequestException;
use Jalle19\StatusManager\Message\Request\AuthenticationRequest;
use Jalle19\StatusManager\Message\Request\InstancesRequest;
use Jalle19\StatusManager\Message\Request\MostActiveWatchersRequest;
use Jalle19\StatusManager\Message\Request\PopularChannelsRequest;
use Jalle19\StatusManager\Message\Request\UsersRequest;

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

		if (!isset($deserialized->type))
			throw new MalformedRequestException('Missing required parameter "type"');

		$type = $deserialized->type;

		// Not all requests have a payload
		if (isset($deserialized->payload))
			$payload = $deserialized->payload;
		else
			$payload = null;

		switch ($type)
		{
			case AbstractMessage::TYPE_POPULAR_CHANNELS_REQUEST:
				return new PopularChannelsRequest($payload);
			case AbstractMessage::TYPE_MOST_ACTIVE_WATCHERS_REQUEST:
				return new MostActiveWatchersRequest($payload);
			case AbstractMessage::TYPE_AUTHENTICATION_REQUEST:
				return new AuthenticationRequest($payload);
			case AbstractMessage::TYPE_INSTANCES_REQUEST:
				return new InstancesRequest();
			case AbstractMessage::TYPE_USERS_REQUEST:
				return new UsersRequest($payload);
			default:
				throw new UnknownRequestException($type);
		}
	}


	/**
	 * Don't allow instantiation
	 */
	private function __construct()
	{

	}

}
