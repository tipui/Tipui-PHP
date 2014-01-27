<?php

/**
* @class  Checkbox
* @file   Checkbox.php
* @brief  HTML Form Element input type Checkbox Helper functions.
* @date   2013-09-14 02:56:00
* @license http://opensource.org/licenses/GPL-3.0 GNU Public License
* @company: Tipui Co. Ltda.
* @author: Daniel Omine <omine@tipui.com>
* @updated: 2014-01-05 18:30:00
*/

namespace Tipui\Builtin\Helpers\HTML\Form\Elements;

use Tipui\Builtin\Libs\Strings as Strings;
use Tipui\Builtin\Libs\DataRules as DataRules;

class Checkbox extends \Tipui\Builtin\Helpers\HTML\Form
{

	public static function Add( $name, $property )
    {
		if( isset( $property[DataRules::OPTIONS] ) )
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
		if( !is_array( $property[DataRules::VALUE] ) )
		{
			/**
			* If value is empty, check for default property and exact_value.
			* For value, must be filtered to avoid HTML injections.
			*/
			if( !empty( $property[DataRules::VALUE] ) )
			{
				$rs .= Strings::Escape( $property[DataRules::VALUE], 'quotes' );
			}else if( isset( $property[DataRules::DEFAULTS] ) and !empty( $property[DataRules::DEFAULTS] ) ){
				$rs .= $property[DataRules::DEFAULTS];
			}else{
				$rs .= $property[DataRules::EXACT_VALUE];
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

		if( !empty( $property[DataRules::VALUE] ) )
		{
			$rs .= ' checked';
		}else if( isset( $property[DataRules::DEFAULTS] ) && !empty( $property[DataRules::DEFAULTS] ) )
		{
			$rs .= ' checked';
		}

		$rs .= ' />';

        return $rs;
	}

}