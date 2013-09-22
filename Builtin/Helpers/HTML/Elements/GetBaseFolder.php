<?php

/**
* @class  GetBaseFolder
* @file   GetBaseFolder.php
* @brief  GetBaseFolder HTML Elements functions.
* @date   2013-09-22 14:44:00
* @license http://opensource.org/licenses/GPL-3.0 GNU Public License
* @company: Tipui Co. Ltda.
* @author: Daniel Omine <omine@tipui.com>
* @updated: 2013-09-22 14:44:00
*/

namespace Tipui\Builtin\Helpers\HTML\Elements;

class GetBaseFolder extends \Tipui\Builtin\Helpers\HTML\Elements
{

	/**
	* Gets the base folder (public_folder, css, js, images)
	*/
	public function Exec( $target = false )
	{
		if( self::$base_folder == null )
		{
			self::SetBaseFolder();
		}

		switch( $target )
		{
			default:
			case false:
				return self::$base_folder;
			break;
			case 'css':
				return self::$base_folder_css;
			break;
			case 'js':
				return self::$base_folder_js;
			break;
			case 'images':
				return self::$base_folder_images;
			break;
		}
	}

}