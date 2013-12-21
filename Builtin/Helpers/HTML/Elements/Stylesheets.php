<?php

/**
* @class  Stylesheets
* @file   Stylesheets.php
* @brief  Stylesheets HTML Elements functions.
* @date   2013-09-22 14:44:00
* @license http://opensource.org/licenses/GPL-3.0 GNU Public License
* @company: Tipui Co. Ltda.
* @author: Daniel Omine <omine@tipui.com>
* @updated: 2013-12-19 02:48:00
*/

namespace Tipui\Builtin\Helpers\HTML\Elements;

class Stylesheets extends \Tipui\Builtin\Helpers\HTML\Elements
{

	/**
	* Handles array of stylesheets.
	* Alternative way to add more than one file.
	*/
	protected static $stylesheets_array = null;

	/**
	* Returns self object
	*/
	public function Exec()
	{
		return $this;
	}

	/**
	* Set array of stylesheets to add at once method call.
	* Alternative way to add more than one file.
	* @see self::Add
	*/
	public static function SetArray( $array )
	{
		self::$stylesheets_array = $array;
		unset( $array );
	}

	/**
	* Returns array of stylesheets adding all at once.
	* Alternative way to add more than one file.
	* @see self::SetArray
	* @see self::Add
	*
	* Sample 1
	* Setting array method
	* [code]
	* Helper\HTML::Stylesheets()->SetArray( array( 
	*	array( 'file' => 'foo.css' ), 
	*	array( 'file' => 'bar.css' ), 
	*	array( 'file' => 'test.css', 'base_path' => '/other_base_folder/' ) 
	* ) );
	*
	* // Writes the results
	* Helper\HTML::Stylesheets()->AddArray();
	* [/code]
	*
	* Sample 2
	* Adding directly
	* [code]
	* Helper\HTML::Stylesheets()->AddArray( array( 
	*	array( 'file' => 'foo.css' ), 
	*	array( 'file' => 'bar.css' ), 
	*	array( 'file' => 'test.css', 'base_path' => '/other_base_folder/' ) 
	* ) );
	* [/code]
	*
	*/
	public static function AddArray( $array = false )
	{
		$array ? self::$stylesheets_array = $array : null;

		if( !empty( self::$stylesheets_array ) )
		{
			foreach( self::$stylesheets_array as $v )
			{
				!isset( $v['base_path'] ) ? $v['base_path'] = false : null;
				!isset( $v['add'] )       ? $v['add']       = false : null;
				echo PHP_EOL . self::Add( $v['file'], $v['base_path'], $v['add'] );
			}
			self::$stylesheets_array = null;
		}
	}

	/**
	* Add Stylesheet (css)
	* @see self::SetArray
	* @see self::AddArray
	*/
	public static function Add( $file, $base_path = false, $add = false )
	{
		!$base_path ? $base_path = self::GetBaseFolder() . self::$base_folder_css: null;
		$add ? $add = ' ' . $add : '';
		return '<link rel="stylesheet" type="text/css" href="' . $base_path . $file . '"' . $add . ' />';
	}

}