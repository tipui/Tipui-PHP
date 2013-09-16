<?php

/**
* @class  Browse
* @file   Browse.php
* @brief  Browse functions.
* @date   2013-07-11 03:09:00
* @license http://opensource.org/licenses/GPL-3.0 GNU Public License
* @company: Tipui Co. Ltda.
* @author: Daniel Omine <omine@tipui.com>
* @updated: 2013-09-16 15:24:00
*/

namespace Tipui\Builtin\Libs;

/**
* Browse headers and general info (REFERER, IP, Browser name, version, language, etc)
*/
class Browse
{

	/**
	* Instance.
	*
	* sample
	* [code]
	* $c = new Browse;
	* $c -> GetHTTPInfo();
	* [/code]
	*/
    public function __call( $name, $arguments )
    {
		return Factory::Exec( 'Browse', $name, $arguments );
    }

	/**
	* Statically.
	*
	* sample
	* [code]Browse::GetHTTPInfo();[/code]
	*/
    public static function __callStatic( $name, $arguments )
    {
		return Factory::Exec( 'Browse', $name, $arguments );
    }

}