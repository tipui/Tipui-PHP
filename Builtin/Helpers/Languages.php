<?php

/**
* @class  Languages
* @file   Languages.php
* @brief  Languages Helper functions.
* @date   2014-02-28 02:02:00
* @license http://opensource.org/licenses/GPL-3.0 GNU Public License
* @company: Tipui Co. Ltda.
* @author: Daniel Omine <omine@tipui.com>
* @updated: 2014-02-28 02:02:00
*/

namespace Tipui\Builtin\Helpers;
use Tipui\Builtin\Libs\Factory as Factory;

class Languages
{

	/**
	* Handle the array of labels
	* @see: self::Label()
	*/
	protected static $labels = null;

	/**
	* Handle the labels file path to load
	* @see: self::Label()
	*/
	protected static $file_path = null;

	/**
	* Handle the language code for current instance
	* @see: self::Lang()
	* @see: self::Label()
	*/
	protected static $lang_code = null;

	/**
	* Handle the base path of translation file
	* @see: self::Base()
	* @see: self::Label()
	*/
	protected static $base_path = null;



 	/**
	* Instance.
	*
	* sample
	* [code]
	* $c = new ClassName;
	* $c -> MethodName();
	* [/code]
	*/
    public function __call( $name, $arguments )
    {
		return Factory::Exec( __CLASS__, $name, $arguments );
    }



	/**
	* Statically.
	*
	* sample
	* [code]ClassName::MethodName();[/code]
	*/
    public static function __callStatic( $name, $arguments )
    {
		return Factory::Exec( __CLASS__, $name, $arguments );
    }

}