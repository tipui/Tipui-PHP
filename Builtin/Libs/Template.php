<?php

/**
* @class  Template
* @file   Template.php
* @brief  Template functions.
* @date   2011-01-18 13:49:00
* @license http://opensource.org/licenses/GPL-3.0 GNU Public License
* @company: Tipui Co. Ltda.
* @author: Daniel Omine <omine@tipui.com>
* @updated: 2013-09-16 20:11:00
*/

namespace Tipui\Builtin\Libs;

class Template
{
	/**
	* Tag for template files
	*/
    protected static $tag;

	/**
	* Type of output (hold or print)
	*/
    protected static $output;

	/**
	* Base of path
	*/
    protected static $base_dir;

	/**
	* Full path of template file
	*/
    protected static $path;

	/**
	* Init settings
	*/
	public function Init( $base_dir = false, $tag = 'T', $output = 'print' )
	{
		self::$tag      = $tag;
		self::$output   = $output;
		self::$base_dir = $base_dir;
	}

	/**
	* Instance.
	*
	* sample
	* [code]
	* $t = new Libs\Template;
	* $t -> Init( base_path, tag, output );
	* $t -> Compile( dataArray, template_dir, template_file );
	* [/code]
	*/
    public function __call( $name, $arguments )
    {
		return Factory::Exec( 'Template', $name, $arguments );
    }

	/**
	* Statically.
	*
	* sample
	* [code]
	* Libs\Template::Init( base_path, tag, output );
	* Libs\Template::Compile( dataArray, template_dir, template_file );
	* [/code]
	*/
    public static function __callStatic( $name, $arguments )
    {
		return Factory::Exec( 'Template', $name, $arguments );
    }

}