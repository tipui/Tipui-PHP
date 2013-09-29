<?php

/**
* @class  URL
* @file   URL.php
* @brief  HTML URL Helper functions.
* @date   2013-09-30 01:39:00
* @license http://opensource.org/licenses/GPL-3.0 GNU Public License
* @company: Tipui Co. Ltda.
* @author: Daniel Omine <omine@tipui.com>
* @updated: 2013-09-30 01:39:00
*/

namespace Tipui\Builtin\Helpers\HTML;

class URL
{
 	/**
	* Instance.
	*
	* sample
	* [code]
	* $c = new URL;
	* $c -> Make();
	* [/code]
	*/
    public function __call( $name, $arguments )
    {
		return Factory::Exec( 'URL', $name, $arguments );
    }



	/**
	* Statically.
	*
	* sample
	* [code]URL::Make();[/code]
	*/
    public static function __callStatic( $name, $arguments )
    {
		return Factory::Exec( 'URL', $name, $arguments );
    }

}