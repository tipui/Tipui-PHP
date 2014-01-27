<?php

/**
* @class  Select
* @file   Select.php
* @brief  HTML Form Element input type Checkbox Helper functions.
* @date   2013-09-14 02:56:00
* @license http://opensource.org/licenses/GPL-3.0 GNU Public License
* @company: Tipui Co. Ltda.
* @author: Daniel Omine <omine@tipui.com>
* @updated: 2014-01-19 12:23:00
*/

namespace Tipui\Builtin\Helpers\HTML\Form\Elements;

use Tipui\Builtin\Libs\Strings as Strings;
use Tipui\Builtin\Libs\DataRules as DataRules;

class Select extends \Tipui\Builtin\Helpers\HTML\Form
{

	public static function Add( $name, $property )
    {
		$rs  = '<select' . self::ParametersAdd() . 'name="' . $name;

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

		$rs .= '"';

		/**
		* Class property
		*/
		if( self::$css_name != null )
		{
			$rs .= ' class="' . self::$css_name . '"';
		}

		$rs .= '>';

		$check = null;

		if( !empty( $property[DataRules::VALUE] ) )
		{
			/**
			* [notice] 
			* The old framework was casting to string (string)..
			*/
			$check = (string)$property[DataRules::VALUE];
			//echo $check . ':' . gettype($check); exit;
		}else{

			if( isset( $property[DataRules::DEFAULTS] ) )
			{
				/**
				* [notice] 
				* The old framework was casting to string (string)..
				*/
				$check = (string)$property[DataRules::DEFAULTS];
			}

		}

		/**
		* [review]
		* Multiple selected options not available
		*/
		if( isset( $property[DataRules::OPTIONS] ) && is_array( $property[DataRules::OPTIONS] ) && !empty( $property[DataRules::OPTIONS] ) )
		{
			//print_r($data['options']);
			foreach( $property[DataRules::OPTIONS] as $k => $v )
			{
				if( !is_array( $v ) )
				{
					$rs .= '<option';
					$rs .= ' value="' . $k . '"';

					if( !empty( $check ) && $check == $k )
					{
						$rs .= ' selected';
					}

					$rs .= '>';
					$rs .= $v;
					$rs .= '</option>';
				}else{
					if( !empty( $v ) )
					{
						foreach( $v as $optgroup => $options )
						{

							$rs .= '<optgroup label="' . $optgroup  . '">';

							foreach( $options as $value => $label )
							{

								$rs .= '<option';
								$rs .= ' value="' . $value . '"';
								if( !empty( $check ) && $check == $value )
								{
									$rs .= ' selected';
								}
								$rs .= '>';
								$rs .= $label;
								$rs .= '</option>';

							}

							$rs .= '</optgroup>';

						}
					}
				}
			}
		}

		$rs .= '</select>';

        return $rs;
	}

}