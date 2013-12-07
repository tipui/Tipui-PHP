<?php

/**
* @class  Checkbox
* @file   Checkbox.php
* @brief  HTML Form Element input type Checkbox Helper functions.
* @date   2013-09-14 02:56:00
* @license http://opensource.org/licenses/GPL-3.0 GNU Public License
* @company: Tipui Co. Ltda.
* @author: Daniel Omine <omine@tipui.com>
* @updated: 2013-09-23 02:13:00
*/

namespace Tipui\Builtin\Helpers\HTML\Form\Elements;

use Tipui\Builtin\Libs as Libs;

class Checkbox extends \Tipui\Builtin\Helpers\HTML\Form
{

	public static function Add( $name, $property )
    {
		if( isset( $property['options'] ) )
		{
			return self::GroupingOptionProperty( $name, $property );
		}

		$rs  = '<input type="checkbox"' . self::ParametersAdd() . 'name="' . $name;

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

		$rs .= '" value="';

		/**
		* value field
		*/
		if( !is_array( $property['value'] ) )
		{
			/**
			* If value is empty, check for default property and ExactValue.
			* For value, must be filtered to avoid HTML injections.
			*/
			if( !empty( $property['value'] ) )
			{
				$rs .= Libs\Strings::Escape( $property['value'], 'quotes' );
			}else if( !empty( $property['default'] ) ){
				$rs .= $property['default'];
			}else{
				$rs .= $property['ExactValue'];
			}
		}else{

			/**
			* Value is array
			*/
			throw new \Exception('Checkbox value as array not implemented');

		}

		$rs .= '"';

		/**
		* Class property
		*/
		if( self::$css_name != null )
		{
			$rs .= ' class="' . self::$css_name . '"';
		}

		/**
		* Checked state
		*/
        $check = false;

		if( !empty( $property['value'] ) )
		{
			$rs .= ' checked';
		}else if( !empty( $property['default'] ) )
		{
			$rs .= ' checked';
		}

		$rs .= ' />';

        return $rs;
	}

}