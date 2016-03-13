<?php

namespace Jalle19\StatusManager\Message\Handler;

use Jalle19\StatusManager\Message\AbstractMessage;

/**
 * Interface HandlerInterface
 * @package   Jalle19\StatusManager\Message\Handler
 * @copyright Copyright &copy; Sam Stenvall 2016-
 * @license   https://www.gnu.org/licenses/gpl.html The GNU General Public License v2.0
 */
interface HandlerInterface
{

	/**
	 * @param AbstractMessage $message
	 *
	 * @return AbstractMessage|false
	 */
	public function handleMessage(AbstractMessage $message);

}
