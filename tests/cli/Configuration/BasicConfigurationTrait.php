<?php

namespace Jalle19\StatusManager\Test\Configuration;

/**
 * Class BasicConfigurationTrait
 * @package   Jalle19\StatusManager\Test\Configuration
 * @copyright Copyright &copy; Sam Stenvall 2016-
 * @license   https://www.gnu.org/licenses/gpl.html The GNU General Public License v2.0
 */
trait BasicConfigurationTrait
{

	/**
	 * @return array
	 */
	private function getBaseConfiguration()
	{
		return [
			'database_path'    => '/tmp',
			'log_path'         => '/tmp',
			'access_token'     => 'access',
			'instances'        => [
				[
					'address' => 'address',
					'port'    => 9981,
				],
			],
			'update_interval'  => 1,
			'listen_address'   => '0.0.0.0',
			'listen_port'      => 9333,
			'http_listen_port' => 8080,
			'http_username'    => 'admin',
			'http_password'    => 'admin',
		];
	}

}
