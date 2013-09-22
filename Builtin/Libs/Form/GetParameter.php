<?php

/**
* @class  GetParameter
* @file   GetParameter.php
* @brief  GetParameter Header functions.
* @date   2013-09-23 03:07:00
* @license http://opensource.org/licenses/GPL-3.0 GNU Public License
* @company: Tipui Co. Ltda.
* @author: Daniel Omine <omine@tipui.com>
* @updated: 2013-09-23 03:07:00
*/

namespace Tipui\Builtin\Libs\Form;

class GetParameter extends \Tipui\Builtin\Libs\Form
{

	/**
	* Returns self::$parameters property
	*/
	public function Exec( $name = false )
	{
		if( !$name )
		{
			return self::$parameters;
		}else{
			if( isset( self::$parameters[$name] ) )
			{
				return self::$parameters[$name];
			}else{
				throw new \Exception('Parameter name "' . $name . '" not found.');
			}
		}
    }

}