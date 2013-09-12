<?php

/**
* @class  HTML
* @file   HTML.php
* @brief  HTML Helper functions.
* @date   2013-09-13 00:38:00
* @license http://opensource.org/licenses/GPL-3.0 GNU Public License
* @company: Tipui Co. Ltda.
* @author: Daniel Omine <omine@tipui.com>
* @updated: 2013-09-13 00:38:00
*/

namespace Tipui\Builtin\Helpers\HTML;

class HTML
{

	/**
	* Holds title tag
	*/
	protected static $title;

	/**
	* Holds metatags
	*/
	protected static $meta;


	/**
	* Add HTML DOCTYPE DTD
	*/
	public static function AddDocType( $dtd = 'strict' )
	{
		return '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">';
	}

	/**
	* Add HTML opener tag <html>
	*/
	public static function AddOpenTag( $lang = 'english' )
	{
		return '<html lang="' . $lang . '">';
	}

	/**
	* Open head
	*/
	public static function AddHead()
	{
		return '<head>';
	}

	/**
	* Close head
	*/
	public static function CloseHead()
	{
		return '</head>';
	}

	/**
	* Sets HTML title tag
	*/
	public static function SetTitle( $str )
	{
		self::$title = $str;
		unset( $str );
	}

	/**
	* Add HTML title tag
	*/
	public static function AddTitle()
	{
		return '<title>' . self::$title . '</title>';
	}


	/**
	* Sets HTML metatags
	*/
	public static function SetMetatag( $type, $index, $value )
	{
		self::$meta[$type][$index] = $value;
		unset( $index, $value );
	}

	/**
	* Add HTML metatag
	*/
	public static function AddMetatag( $type, $index, $value = false )
	{
		!$value ? $value = self::$meta[$type][$index] : null;
		return '<meta ' . $type . '="' . $index .'" content="' . $value .'" />';
	}

}