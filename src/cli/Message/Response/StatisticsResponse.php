<?php

namespace Jalle19\StatusManager\Message\Response;

use Jalle19\StatusManager\Message\AbstractMessage;
use Jalle19\StatusManager\Message\Request\StatisticsRequest;

/**
 * Class StatisticsResponse
 * @package   Jalle19\StatusManager\Message\Response
 * @copyright Copyright &copy; Sam Stenvall 2016-
 * @license   https://www.gnu.org/licenses/gpl.html The GNU General Public License v2.0
 */
abstract class StatisticsResponse extends AbstractMessage
{

	/**
	 * StatisticsResponse constructor.
	 *
	 * @param string            $type
	 * @param StatisticsRequest $request
	 * @param mixed             $response
	 */
	public function __construct($type, $request, $response)
	{
		$payload           = new \stdClass();
		$payload->request  = $request;
		$payload->response = $response;

		parent::__construct($type, $payload);
	}

}
