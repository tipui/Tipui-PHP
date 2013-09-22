<?php

/**
* @class  Select
* @file   Select.php
* @brief  HTML Form Element input type Checkbox Helper functions.
* @date   2013-09-14 02:56:00
* @license http://opensource.org/licenses/GPL-3.0 GNU Public License
* @company: Tipui Co. Ltda.
* @author: Daniel Omine <omine@tipui.com>
* @updated: 2013-09-23 02:13:00
*/

namespace Tipui\Builtin\Helpers\HTML\Form\Elements;

use Tipui\Builtin\Libs as Libs;

class Select extends \Tipui\Builtin\Helpers\HTML\Form
{

	public static function Add( $name, $property )
    {
		$rs  = '<select' . self::ParametersAdd() . 'name="' . $name;

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

		$rs .= '"';

		/**
		* Class property
		*/
		if( self::$css_name != null )
		{
			$rs .= ' class="' . self::$css_name . '"';
		}

		$rs .= '>';

		$check = false;

		if( $property['value'] != '' )
		{

			$check = (string)$property['value'];
			//echo gettype($check); exit;
		}else{

			if( $property['default'] )
			{
				$check = (string)$property['default'];
			}

		}

		/**
		* [review]
		* Multiple selected options not available
		*/
		if( isset( $property['options'] ) and is_array($property['options']) and count($property['options']) > 0 )
		{
			//print_r($data['options']);
			foreach( $property['options'] as $k => $v )
			{
				if( !is_array( $v ) )
				{
					$rs .= '<option';
					$rs .= ' value="' . $k . '"';
				}else{
					if( isset( $v['optgroup'] ) )
					{
						$rs .= '<optgroup label="' . $v['optgroup']  . '">';
					}
				}

				if( !is_array( $v ) and gettype($check) != 'boolean' and $check == $k )
				{
					$rs .= ' selected';
				}

				if( !is_array( $v ) )
				{
					$rs .= '>';
					$rs .= $v;
					$rs .= '</option>';
				}else{
					if( isset( $v['optgroup'] ) )
					{
						if( isset( $v['options'] ) and is_array( $v['options'] ) and count( $v['options'] ) > 0 )
						{
							foreach( $v['options'] as $k1 => $v1 )
							{
								$rs .= '<option';
								$rs .= ' value="' . $k1 . '"';
								if( gettype($check) != 'boolean' and $check == $k1 )
								{
									$rs .= ' selected';
								}
								$rs .= '>';
								$rs .= $v1;
								$rs .= '</option>';
							}
						}
						$rs .= '</optgroup>';
					}
				}
			}
		}

		$rs .= '</select>';

        return $rs;
	}

}