<?php

/**
* @class  Form
* @file   Form.php
* @brief  HTML Form Helper functions.
* @date   2013-09-12 03:03:00
* @license http://opensource.org/licenses/GPL-3.0 GNU Public License
* @company: Tipui Co. Ltda.
* @author: Daniel Omine <omine@tipui.com>
* @updated: 2014-01-22 11:40:00
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
	* [code]HTML\Form::$name_as_array = 'a';[/code]
	* i.e. [code]<input name="foo[a]"...[/code]
	*/
	protected static $name_as_array;

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
	* Holds the URL PARAM_NAME
	* @see self::SetParam, self::AddParam, Core::GetConf()
	*/
	protected static $url_param = null;



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