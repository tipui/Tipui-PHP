<?php

/**
* @class  Form
* @file   Form.php
* @brief  HTML Form Helper functions.
* @date   2013-09-12 03:03:00
* @license http://opensource.org/licenses/GPL-3.0 GNU Public License
* @company: Tipui Co. Ltda.
* @author: Daniel Omine <omine@tipui.com>
* @updated: 2013-09-23 02:18:00
*/

namespace Tipui\Builtin\Helpers\HTML;

class Form
{

	/**
	* Handles Libs\Form::$parameters
	*/
	protected static $parameter = null;

	/**
	* Optional form object name as array
	* [code]HTML\Form::$key_add = 'a';[/code]
	* i.e. [code]<input name="foo[a]"...[/code]
	*/
	protected static $key_add;

	/**
	* [code]<input class=""[/code]
	*/
	protected static $css_name = null;

	/**
	* [code]<input readonly[/code]
	*/
	protected static $readonly = false;

	/**
	* Additional parameters or inline scripts like css or js.
	* [code]HTML\Form::$tag_params = 'id=foo';[/code]
	* i.e. [code]<input id="foo"...[/code]
	* [code]HTML\Form::$tag_params = array('id'=>'foo');[/code]
	* i.e. [code]<input id="foo"...[/code]
	*/
	protected static $tag_params = false;



 	/**
	* Instance.
	*
	* sample
	* [code]
	* $c = new Form;
	* $c -> AddForm();
	* [/code]
	*/
    public function __call( $name, $arguments )
    {
		return Factory::Exec( 'Form', $name, $arguments );
    }

	/**
	* Statically.
	*
	* Form::AddForm();
	*/
    public static function __callStatic( $name, $arguments )
    {
		return Factory::Exec( 'Form', $name, $arguments );
    }

}