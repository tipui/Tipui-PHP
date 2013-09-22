<?php

/**
* @class  Text
* @file   Text.php
* @brief  HTML Form input type Text Helper functions.
* @date   2013-09-14 02:56:00
* @license http://opensource.org/licenses/GPL-3.0 GNU Public License
* @company: Tipui Co. Ltda.
* @author: Daniel Omine <omine@tipui.com>
* @updated: 2013-09-14 02:56:00
*/

namespace Tipui\Builtin\Helpers\HTML\Form;

use Tipui\Builtin\Libs as Libs;

class Text extends \Tipui\Builtin\Helpers\HTML\Form
{

	protected static function Add( $name, $property )
    {
		$rs  = '<input type="' . $property['type'] . '"' . self::ParametersAdd() . 'name="' . $name;

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
		
		/**
		* value property
		*/
		$rs .= '" value="';

		if( !is_array( $property['value'] ) )
		{
			/**
			* If value is empty, check for default property.
			* Value must be filtered to avoid HTML injections.
			*/
			if( !empty( $property['value'] ) )
			{
				$rs .= Libs\Strings::Escape( $property['value'], 'quotes' );
			}else{
				$rs .= $property['default'];
			}
		}else{
			/**
			* value is array
			*/
			throw new \Exception('Input text value as array not implemented');
		}

		/**
		* size, maxLength, ExactLength properties
		*/
		$rs .= '" size="' . $property['size'] . '" maxLength="' . ( ( isset( $property['MaxLength'] ) ) ? $property['MaxLength'] : $property['ExactLength'] ) . '"';

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
			$rs .= ' readonly';
		}

		$rs .= ' />';

		return $rs;
	}

}