<?php

namespace Jalle19\StatusManager\Message\Response;

use Jalle19\StatusManager\Configuration\Instance;
use Jalle19\StatusManager\Message\AbstractMessage;

/**
 * Class InstancesResponse
 * @package   Jalle19\StatusManager\Message\Response
 * @copyright Copyright &copy; Sam Stenvall 2016-
 * @license   https://www.gnu.org/licenses/gpl.html The GNU General Public License v2.0
 */
class InstancesResponse extends AbstractMessage
{

	/**
	 * InstancesResponse constructor.
	 *
	 * @param Instance[] $instances
	 */
	public function __construct(array $instances)
	{
		parent::__construct(self::TYPE_INSTANCES_RESPONSE, $instances);
	}

}
