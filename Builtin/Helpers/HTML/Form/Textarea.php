<?php

/**
* @class  Textarea
* @file   Textarea.php
* @brief  HTML Form input type Textarea Helper functions.
* @date   2013-09-14 02:56:00
* @license http://opensource.org/licenses/GPL-3.0 GNU Public License
* @company: Tipui Co. Ltda.
* @author: Daniel Omine <omine@tipui.com>
* @updated: 2013-09-14 02:56:00
*/

namespace Tipui\Builtin\Helpers\HTML\Form;

use Tipui\Builtin\Libs as Libs;

class Textarea extends \Tipui\Builtin\Helpers\HTML\Form
{

	protected static function Add( $name, $property )
    {
		$rs  = '<textarea' . self::ParametersAdd() . 'name="' . $name;

		/**
		* name property
		*/
		if( self::$key_add )
		{
			if( !is_array( self::$key_add  ) )
			{
				$rs .= '[' . self::$key_add . ']';
			}else{
				foreach( self::$key_add as $k => $v )
				{
					$rs .= '[' . $v . ']';
				}
			}
		}

		$rs .= '" cols="' . $property['cols'] . '" rows="' . $property['rows'] . '"';

		/**
		* class property
		*/
		if( self::$css_name != null )
		{
			$rs .= ' class="' . self::$css_name . '"';
		}

		/**
		* readonly attribute
		*/
		if( self::$readonly )
		{
			$rs .= ' readonly="readonly"';
		}

		$rs .= '>';

		/**
		* value field
		*/
		if( !is_array( $property['value'] ) )
		{
			/**
			* If value is empty, check for default property.
			*/
			if( !empty( $property['value'] ) )
			{
				$rs .= $property['value'];

			}else{
				$rs .= $property['default'];

			}
		}else{

			/**
			* Value is array
			*/

			if( self::$key_add )
			{

				self::$ArrayVal = '';
				self::ArrayVal( $property['value'] );
				
				if( self::$ArrayVal == '' )
				{
					if( isset( $property['default'] ) )
					{
						$rs .= $property['default'];
					}
				}else{					
					$rs .= Strings::Escape( self::$ArrayVal, 'quotes' );
				}

			}else{
				$rs .= Strings::Escape( $property['default'], 'quotes' );

			}

		}

        $rs .= '</textarea>';

        return $rs;
	}

}