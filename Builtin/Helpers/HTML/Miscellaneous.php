<?php

/**
* @class  Miscellaneous
* @file   Miscellaneous.php
* @brief  HTML Miscellaneous Helper functions.
* @date   2013-09-29 21:36:00
* @license http://opensource.org/licenses/GPL-3.0 GNU Public License
* @company: Tipui Co. Ltda.
* @author: Daniel Omine <omine@tipui.com>
* @updated: 2013-09-29 21:36:00
*/

namespace Tipui\Builtin\Helpers\HTML;

class Miscellaneous
{

	/**
	* Statically.
	*
	* sample
	* [code]Miscellaneous::Append() -> Set( '<script>alert( 'hello world' )</script>' );[/code]
	*/
    public static function __callStatic( $name, $arguments )
    {
		return Factory::Exec( 'Miscellaneous', $name, $arguments );
    }

}