<?php

/**
* @class  JSFiles
* @file   JSFiles.php
* @brief  JSFiles HTML Elements functions.
* @date   2013-09-22 14:44:00
* @license http://opensource.org/licenses/GPL-3.0 GNU Public License
* @company: Tipui Co. Ltda.
* @author: Daniel Omine <omine@tipui.com>
* @updated: 2013-09-22 14:44:00
*/

namespace Tipui\Builtin\Helpers\HTML\Elements;

class JSFiles extends \Tipui\Builtin\Helpers\HTML\Elements
{

	/**
	* Handles array of JavaScript file.
	* Alternative way to add more than one file.
	*/
	protected static $js_array = null;

	/**
	* Returns self object
	*/
	public function Exec()
	{
		return $this;
	}

	/**
	* Set array of JavaScript files to add at once method call.
	* Alternative way to add more than one file.
	* @see self::Add
	*/
	public static function SetArray( $array )
	{
		self::$js_array = $array;
		unset( $array );
	}

	/**
	* Returns array of JavaScript files adding all at once.
	* Alternative way to add more than one file.
	* @see self::SetArray
	* @see self::Add
	*
	* Sample 1
	* Setting array method
	* [code]
	* Helper\HTML::JSFiles()->SetArray( array( 
	*	array( 'file' => 'foo.js' ), 
	*	array( 'file' => 'bar.js' ), 
	*	array( 'file' => 'test.js', 'base_path' => '/js_sample/' ) 
	* ) );
	*
	* // Writes the results
	* Helper\HTML::JSFiles()->AddArray();
	* [/code]
	*
	* Sample 2
	* Adding directly
	* [code]
	* Helper\HTML::JSFiles()->AddArray( array( 
	*	array( 'file' => 'foo.js' ), 
	*	array( 'file' => 'bar.js' ), 
	*	array( 'file' => 'test.js', 'base_path' => '/js_sample/' ) 
	* ) );
	* [/code]
	*
	*/
	public static function AddArray( $array = false )
	{
		$array ? self::$js_array = $array : null;

		if( !empty( self::$js_array ) )
		{
			foreach( self::$js_array as $v )
			{
				!isset( $v['base_path'] ) ? $v['base_path'] = false : null;
				!isset( $v['add'] )       ? $v['add']       = false : null;
				echo PHP_EOL . self::Add( $v['file'], $v['base_path'], $v['add'] );
			}
			self::$js_array = null;
		}
	}

	/**
	* Add JavaScript file (js)
	* @see self::SetArray
	* @see self::AddArray
	*/
	public static function Add( $file, $base_path = false, $add = false )
	{
		!$base_path ? $base_path = self::GetBaseFolder() . self::$base_folder_js: null;
		$add ? $add = ' ' . $add : '';
		return '<script type="text/javascript" href="' . $base_path . $file . '"' . $add . '></script>';
	}

}