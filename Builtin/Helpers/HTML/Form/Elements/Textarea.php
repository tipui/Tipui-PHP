<?php

/**
* @class  Textarea
* @file   Textarea.php
* @brief  HTML Form Element input type Checkbox Helper functions.
* @date   2013-09-14 02:56:00
* @license http://opensource.org/licenses/GPL-3.0 GNU Public License
* @company: Tipui Co. Ltda.
* @author: Daniel Omine <omine@tipui.com>
* @updated: 2013-09-23 02:13:00
*/

namespace Tipui\Builtin\Helpers\HTML\Form\Elements;

use Tipui\Builtin\Libs as Libs;

class Textarea extends \Tipui\Builtin\Helpers\HTML\Form
{

	public static function Add( $name, $property )
    {
		$rs  = '<textarea' . self::ParametersAdd() . 'name="' . $name;

		/**
		* name property
		*/
		if( self::$name_as_array )
		{
			if( !is_array( self::$name_as_array  ) )
			{
				$rs .= '[' . self::$name_as_array . ']';
			}else{
				foreach( self::$name_as_array as $k => $v )
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

			if( self::$name_as_array )
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
					$rs .= Libs\Strings::Escape( self::$ArrayVal, 'quotes' );
				}

			}else{
				$rs .= Libs\Strings::Escape( $property['default'], 'quotes' );

			}

		}

        $rs .= '</textarea>';

        return $rs;
	}

}