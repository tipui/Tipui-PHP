<?php

/**
* @class  ParametersAdd
* @file   ParametersAdd.php
* @brief  ParametersAdd HTML Helper Form Elements functions.
* @date   2013-09-22 14:44:00
* @license http://opensource.org/licenses/GPL-3.0 GNU Public License
* @company: Tipui Co. Ltda.
* @author: Daniel Omine <omine@tipui.com>
* @updated: 2013-09-22 14:44:00
*/

namespace Tipui\Builtin\Helpers\HTML\Form;

class ParametersAdd extends \Tipui\Builtin\Helpers\HTML\Form
{

	/**
	* Add extra parameters to a element.
	*/
	public function Exec( $id = false, $action = false, $name = false )
	{
		$rs = ' ';

		if( self::$tag_params )
		{
			if( is_array( self::$tag_params ) )
			{
				foreach( self::$tag_params as $k => $v )
				{
					$rs .= ' ' . $k . '="' . $v . '"';
				}
				$rs .= ' ';
			}else{
				$rs .= self::$tag_params . ' ';
			}
		}

		return $rs;
	}

}