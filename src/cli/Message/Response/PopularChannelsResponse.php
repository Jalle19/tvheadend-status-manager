<?php

namespace Jalle19\StatusManager\Message\Response;

use Jalle19\StatusManager\Message\AbstractMessage;

/**
 * Class PopularChannelsResponse
 * @package Jalle19\StatusManager\Message\Response
 * @copyright Copyright &copy; Sam Stenvall 2016-
 * @license   https://www.gnu.org/licenses/gpl.html The GNU General Public License v2.0
 */
class PopularChannelsResponse extends AbstractMessage
{

	/**
	 * PopularChannelsResponse constructor.
	 *
	 * @param mixed $payload
	 */
	public function __construct($payload)
	{
		parent::__construct(self::TYPE_POPULAR_CHANNELS_RESPONSE, $payload);
	}

}
