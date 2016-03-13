<?php

namespace Jalle19\StatusManager\Message;

use Jalle19\StatusManager\Instance\InstanceStatusCollection;

/**
 * Class StatusUpdatesMessage
 * @package Jalle19\StatusManager\Message
 */
class StatusUpdatesMessage extends AbstractMessage
{

	/**
	 * StatusUpdatesMessage constructor.
	 *
	 * @param InstanceStatusCollection $collection
	 */
	public function __construct(InstanceStatusCollection $collection)
	{
		parent::__construct(self::TYPE_STATUS_UPDATES, $collection);
	}

}
