<?php

/**
* @class  SetBaseFolder
* @file   SetBaseFolder.php
* @brief  SetBaseFolder HTML Elements functions.
* @date   2013-09-22 14:44:00
* @license http://opensource.org/licenses/GPL-3.0 GNU Public License
* @company: Tipui Co. Ltda.
* @author: Daniel Omine <omine@tipui.com>
* @updated: 2013-09-22 14:44:00
*/

namespace Tipui\Builtin\Helpers\HTML\Elements;

class SetBaseFolder extends \Tipui\Builtin\Helpers\HTML\Elements
{

	/**
	* Sets the base folder (public_folder, css, js, images)
	* [review] $bootstrap redundant usage
	*/
	public function Exec( $custom = false, $target = false )
	{
		if( $custom )
		{
			switch( $target )
			{
				default:
				case false:
					self::$base_folder = $custom;
				break;
				case 'css':
					self::$base_folder_css = $custom;
				break;
				case 'js':
					self::$base_folder_js = $custom;
				break;
				case 'images':
					self::$base_folder_images = $custom;
				break;
			}
		}else{

			if( self::$base_folder == null )
			{
				$bootstrap = \Tipui\Core::GetConf() -> BOOTSTRAP;
				self::$base_folder        = $bootstrap -> PUBLIC_FOLDER;
				self::$base_folder_css    = $bootstrap -> PUBLIC_FOLDER_CSS . '/';
				self::$base_folder_js     = $bootstrap -> PUBLIC_FOLDER_JS . '/';
				self::$base_folder_images = $bootstrap -> PUBLIC_FOLDER_IMAGES . '/';
				unset( $bootstrap );
			}

		}
	}

}