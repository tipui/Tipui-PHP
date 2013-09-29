<?php

/**
* @class  Elements
* @file   Elements.php
* @brief  HTML Elements Helper functions.
* @date   2013-09-13 00:38:00
* @license http://opensource.org/licenses/GPL-3.0 GNU Public License
* @company: Tipui Co. Ltda.
* @author: Daniel Omine <omine@tipui.com>
* @updated: 2013-09-29 21:36:00
*/

namespace Tipui\Builtin\Helpers\HTML;

class Elements
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
	* Base folder
	* Holds the env/BOOTSTRAP PUBLIC_FOLDER
	*/
	protected static $base_folder = null;

	/**
	* Base folder of CSS (stylesheet) files
	* Holds the env/BOOTSTRAP PUBLIC_FOLDER_CSS
	*/
	protected static $base_folder_css = null;

	/**
	* Base folder of JavaScript files
	* Holds the env/BOOTSTRAP PUBLIC_FOLDER_JS
	*/
	protected static $base_folder_js = null;

	/**
	* Base folder of images
	* Holds the env/BOOTSTRAP PUBLIC_FOLDER_IMAGES
	*/
	protected static $base_folder_images = null;


	/**
	* Statically.
	*
	* sample
	* [code]Elements::AddTitle();[/code]
	*/
    public static function __callStatic( $name, $arguments )
    {
		return Factory::Exec( 'Elements', $name, $arguments );
    }

}