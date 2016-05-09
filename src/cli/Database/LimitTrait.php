<?php

namespace Jalle19\StatusManager\Database;

/**
 * Class LimitTrait
 * @package   Jalle19\StatusManager\Database
 * @copyright Copyright &copy; Sam Stenvall 2016-
 * @license   https://www.gnu.org/licenses/gpl.html The GNU General Public License v2.0
 */
trait LimitTrait
{

	/**
	 * @param int|null $limit
	 *
	 * @return $this
	 */
	public function filterByLimit($limit)
	{
		if ($limit !== null)
			$this->limit($limit);

		return $this;
	}


	public abstract function limit($limit);

}
