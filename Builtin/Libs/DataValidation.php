<?php

/**
* @class  DataValidation
* @file   DataValidation.php
* @brief  DataValidation functions.
* @date   2014-01-03 05:30:00
* @license http://opensource.org/licenses/GPL-3.0 GNU Public License
* @company: Tipui Co. Ltda.
* @author: Daniel Omine <omine@tipui.com>
* @updated: 2014-01-03 05:30:00
*/

namespace Tipui\Builtin\Libs;

use \Tipui\Builtin\Libs as Libs;

class DataValidation
{

	/**
	* Instance.
	*
	* sample
	* [code]
	* $c = new DataValidation;
	* $c -> MethodName();
	* [/code]
	*/
    public function __call( $name, $arguments )
    {
		return Factory::Exec( 'DataValidation', $name, $arguments );
    }

	/**
	* Statically.
	*
	* sample
	* [code]DataValidation::MethodName();[/code]
	*/
    public static function __callStatic( $name, $arguments )
    {
		return Factory::Exec( 'DataValidation', $name, $arguments );
    }

}