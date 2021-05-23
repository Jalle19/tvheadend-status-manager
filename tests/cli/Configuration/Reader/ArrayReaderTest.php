<?php

namespace Jalle19\StatusManager\Test\Configuration\Reader;

use Jalle19\StatusManager\Configuration\Reader\ArrayReader;
use PHPUnit\Framework\TestCase;

/**
 * Class ArrayReaderTest
 * @package   Jalle19\StatusManager\Test\Configuration\Reader
 * @copyright Copyright &copy; Sam Stenvall 2016-
 * @license   https://www.gnu.org/licenses/gpl.html The GNU General Public License v2.0
 */
class ArrayReaderTest extends TestCase
{

	/**
	 * 
	 */
	public function testReader()
	{
		$configuration = [
			'foo' => 'bar',
			'baz',
		];

		$reader = new ArrayReader($configuration);
		$this->assertEquals($configuration, $reader->readConfiguration());
	}

}
