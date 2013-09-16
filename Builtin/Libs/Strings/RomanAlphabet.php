<?php

/**
* @class  RomanAlphabet
* @file   RomanAlphabet.php
* @brief  RomanAlphabet strings functions.
* @date   2013-09-15 02:54:00
* @license http://opensource.org/licenses/GPL-3.0 GNU Public License
* @company: Tipui Co. Ltda.
* @author: Daniel Omine <omine@tipui.com>
* @updated: 2013-09-15 02:54:00
*/

namespace Tipui\Builtin\Libs\Strings;

use Tipui\Builtin\Libs as Libs;

class RomanAlphabet
{

	/**
	* (array) alphabet letters
	* Dependency: Libs/Strings/ParseRange
	*/
	public function Exec( $mode = 'auto' )
	{
		return Libs\Strings::ParseRange( 'A', 'Z', $mode );
    }

}