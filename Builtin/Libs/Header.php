<?php

/**
* @class  Header
* @file   Header.php
* @brief  Header functions.
* @date   2013-07-08 02:18:00
* @license http://opensource.org/licenses/GPL-3.0 GNU Public License
* @company: Tipui Co. Ltda.
* @author: Daniel Omine <omine@tipui.com>
* @updated: 2013-09-09 19:28:00
*/

namespace Tipui\Builtin\Libs;

/**
* Header library.
*/
class Header
{

	/**
	* Instance.
	*
	* sample
	* [code]
	* $c = new Header;
	* $c -> HTTPStatus( '404' );
	* [/code]
	*/
    public function __call( $name, $arguments )
    {
		return Factory::Exec( 'Header', $name, $arguments );
    }

	/**
	* Statically.
	*
	* sample
	* [code]Header::HTTPStatus( '404' );[/code]
	*/
    public static function __callStatic( $name, $arguments )
    {
		return Factory::Exec( 'Header', $name, $arguments );
    }

}