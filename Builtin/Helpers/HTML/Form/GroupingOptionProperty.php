<?php

/**
* @class  GroupingOptionProperty
* @file   GroupingOptionProperty.php
* @brief  GroupingOptionProperty HTML Helper Form Elements functions.
* @date   2013-09-22 14:44:00
* @license http://opensource.org/licenses/GPL-3.0 GNU Public License
* @company: Tipui Co. Ltda.
* @author: Daniel Omine <omine@tipui.com>
* @updated: 2013-09-22 14:44:00
*/

namespace Tipui\Builtin\Helpers\HTML\Form;

class GroupingOptionProperty extends \Tipui\Builtin\Helpers\HTML\Form
{

	/**
	* Grouping properties of elements with multiple values and options
	* Returns as array.
	*/
	public function Exec( $name, $property )
	{
		/**
		* name property
		*/
		$name_add = '';
		if( self::$key_add )
		{

			if( !is_array( self::$key_add  ) )
			{
				$name_add .= '[' . self::$key_add . ']';
			}else{
				foreach( self::$key_add as $k => $v )
				{
					$name_add .= '[' . $v . ']';
				}
			}

			/**
			* Debug purposes
			*/
			//print_r( $v ); exit;
			//print_r( self::$key_add); exit;
			//print_r( $data['key'][self::$key_add] ); exit;

			if( isset( $property['value'][self::$key_add] ) )
			{
				$property['value'] = $property['value'][self::$key_add];
			}

		}

		/**
		* Debug purposes
		*/
		//print_r( $property ); exit;
		//print_r( $property['key'][self::$key_add] ); exit;
		//print_r( $property['value'] ); exit;

        $check = false;

		if( $property['value'] != '' )
		{
			if( !is_array( $property['default'] ) )
			{
				$check = $property['value'];
			}else{
				$check = array_combine( $property['value'], $property['value'] );
			}
		}else{
			if( $property['default'] != false )
			{
				if( !is_array( $property['default'] ) )
				{
					$check = $property['default'];
					settype( $check, 'string' );
				}else{
					$check = array_combine( $property['default'], $property['default'] );
				}
			}
		}

		/**
		* Debug purposes
		*/
		//print_r( $data['options'] ); exit;

        foreach( $property['options'] as $k => $v )
        {

			$rs[$k] = '<input type="' . $property['type'] . '"' . self::ParametersAdd() . 'name="' . $name . $name_add;

			if( isset( $property['multiple'] ) )
			{
				$rs[$k] .= '[' . $k . ']';
			}

			$rs[$k] .= '" value="' . $k . '"';
    
			if( $check )
			{

				settype( $k, 'string' );

				/**
				* Debug purposes
				*/
				//echo self::$key . ':' . gettype( $check ); exit;

				if( !is_array( $check ) and $check == $k )
				{
					$rs[$k] .= ' checked';
				}else{

					// For mutidimensional array choices
					if( isset( $property['multiple'] ) )
					{
						if( isset( $check[$k] ) )
						{
							$rs[$k] .= ' checked';
						}
					}

				}

			}
    
			/**
			* class property
			*/
			if( self::$css_name != null )
			{
				$rs[$k] .= ' class="' . self::$css_name . '"';
			}

			$rs[$k] .= ' />';

		}

		/**
		* Debug purposes
		*/
		//print_r( $rs ); exit;

        return $rs;
	}

}