<?php

namespace Jalle19\StatusManager\Message\Request;

use Jalle19\StatusManager\Message\AbstractMessage;

/**
 * Class InstancesRequest
 * @package   Jalle19\StatusManager\Message\Request
 * @copyright Copyright &copy; Sam Stenvall 2016-
 * @license   https://www.gnu.org/licenses/gpl.html The GNU General Public License v2.0
 */
class InstancesRequest extends AbstractMessage
{

	/**
	 * @inheritdoc
	 */
	public function __construct()
	{
		parent::__construct(self::TYPE_INSTANCES_REQUEST, null);
	}

}
