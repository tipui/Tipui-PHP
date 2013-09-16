<?php

/**
* @class  Strings
* @file   Strings.php
* @brief  Strings functions.
* @date   2013-03-18 18:50:00
* @license http://opensource.org/licenses/GPL-3.0 GNU Public License
* @company: Tipui Co. Ltda.
* @author: Daniel Omine <omine@tipui.com>
* @updated: 2013-09-16 03:56:00
*/

namespace Tipui\Builtin\Libs;

/**
* Strings manipulation classes
*/
class Strings
{

	/**
	* Instance.
	*
	* sample
	* [code]
	* $c = new Strings;
	* $c -> Trim( ' foo ' );
	* [/code]
	*/
    public function __call( $name, $arguments )
    {
		return Factory::Exec( 'Strings', $name, $arguments );
    }

	/**
	* Statically.
	*
	* sample
	* [code]Strings::Trim( ' foo ' );[/code]
	*/
    public static function __callStatic( $name, $arguments )
    {
		return Factory::Exec( 'Strings', $name, $arguments );
    }

}