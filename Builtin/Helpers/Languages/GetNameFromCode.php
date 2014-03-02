<?php

/**
* @class  GetNameFromCode
* @file   GetNameFromCode.php
* @brief  GetNameFromCode Helper/Languages functions.
* @date   2014-03-02 19:27:00
* @license http://opensource.org/licenses/GPL-3.0 GNU Public License
* @company: Tipui Co. Ltda.
* @author: Daniel Omine <omine@tipui.com>
* @updated: 2014-03-02 19:27:00
*/

namespace Tipui\Builtin\Helpers\Languages;

class GetNameFromCode extends \Tipui\Builtin\Helpers\Languages
{

	/**
	* Get the language name from language code
	*/
	public function Exec( $lang_code = null )
	{

		/**
		* Setting the property [code]$lang_code[/code], if empty.
		*/
		if( empty( self::$lang_code ) )
		{

			self::Lang();

		}else{

			if( !empty( $lang_code ) )
			{
				self::$lang_code = $lang_code;
			}

		}

		/**
		* Checking if language code is empty
		*/
		if( empty( self::$lang_code ) )
		{
			throw new \Exception('Property "self::$lang_code" is null.');
		}

		/**
		* Debug purposes
		*/
		//var_dump( property_exists( \Tipui\Core::GetConf() -> LANGUAGES, 'en' ) ); exit;
		//$c = \Tipui\Core::GetConf() -> LANGUAGES -> ch;
		//echo $c; exit;
		//var_dump( \Tipui\Core::GetConf() -> LANGUAGES -> ch ); exit;
		//var_dump( \Tipui\Core::GetConf() -> LANGUAGES -> {self::$lang_code} ); exit;
		//echo \Tipui\Core::GetConf() -> LANGUAGES -> {self::$lang_code}; exit;

		/**
		* Checking if language code is valid
		*/
		if( !\Tipui\Core::GetConf() -> LANGUAGES -> {self::$lang_code} )
		{
			throw new \Exception('Language code "' . self::$lang_code . '" is not valid.');
		}

		return \Tipui\Core::GetConf() -> LANGUAGES -> {self::$lang_code};

	}

}