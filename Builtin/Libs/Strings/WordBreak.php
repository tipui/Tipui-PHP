<?php

/**
* @class  WordBreak
* @file   WordBreak.php
* @brief  WordBreak strings functions.
* @date   2013-09-15 02:54:00
* @license http://opensource.org/licenses/GPL-3.0 GNU Public License
* @company: Tipui Co. Ltda.
* @author: Daniel Omine <omine@tipui.com>
* @updated: 2013-09-15 02:54:00
*/

namespace Tipui\Builtin\Libs\Strings;

use Tipui\Builtin\Libs as Libs;

class WordBreak
{

	/**
	* Break's string chars
	* looooooooooooooooooooooooooooong strinnnnnnnnnnnnnnnnnnnnnng
	* returns looooo oooooo oooooo oooooo oooooo ng strinn nnnnnn nnnnnn nnnnnn nng
	*/
	public function Exec( $str, $limit = 15, $escape = false, $break = ' ' )
	{
		$str = mb_ereg_replace('#(\S{' . $limit . ',})#e', "chunk_split('$1', " . $limit . ", '" . $break . "')", $str );
		if( $escape )
		{
			return Libs\Strings::Method( 'Escape' ) -> Exec( $str, false );
		}
		return $str;
    }

}