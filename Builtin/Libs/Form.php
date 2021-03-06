<?php

/**
* @class  Form
* @file   Form.php
* @brief  Form functions.
* @date   2013-08-31 23:37:00
* @license http://opensource.org/licenses/GPL-3.0 GNU Public License
* @company: Tipui Co. Ltda.
* @author: Daniel Omine <omine@tipui.com>
* @updated: 2014-01-03 04:09:00
*/

namespace Tipui\Builtin\Libs;

use \Tipui\Builtin\Libs\DataRules as DataRules;

/**
* Form properties and settings library.
* @see \Tipui\Builtin\Helpers\HTML\Form
*/
class Form
{

	/**
	* Handles form action
	*/
	protected static $action     = null;

	/**
	* Handles form method
	*/
	protected static $method     = null;

	/**
	* Handles form parameters
	*/
	protected static $parameters = null;


	/**
	* Instance.
	*
	* sample
	* [code]
	* $c = new Form;
	* $c -> SetAction( 'path' );
	* [/code]
	*/
    public function __call( $name, $arguments )
    {
		return Factory::Exec( 'Form', $name, $arguments );
    }

	/**
	* Statically.
	*
	* sample
	* [code]Form::SetAction( 'path' );[/code]
	*/
    public static function __callStatic( $name, $arguments )
    {
		return Factory::Exec( 'Form', $name, $arguments );
    }

}