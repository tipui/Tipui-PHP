<?php

/**
* @class  GroupingOptionProperty
* @file   GroupingOptionProperty.php
* @brief  GroupingOptionProperty HTML Helper Form Elements functions.
* @date   2013-12-05 20:13:00
* @license http://opensource.org/licenses/GPL-3.0 GNU Public License
* @company: Tipui Co. Ltda.
* @author: Daniel Omine <omine@tipui.com>
* @updated: 2014-01-21 17:44:00
*/

namespace Tipui\Builtin\Helpers\HTML\Form;

use Tipui\Builtin\Libs\DataRules as DataRules;

class GroupingOptionProperty extends \Tipui\Builtin\Helpers\HTML\Form
{

	/**
	* Grouping properties of elements with multiple values and options
	* Returns as array.
	*/
	public function Exec( $name, $property )
	{

		/**
		* Builds the name as array
		* @see \Builtin\Helpers\HTML\Form::SetNameAsArray()
		*/
		$name_add = '';
		if( !empty( self::$name_as_array ) )
		{

			if( !is_array( self::$name_as_array  ) )
			{
				$name_add .= '[' . self::$name_as_array . ']';
			}else{
				foreach( self::$name_as_array as $k => $v )
				{
					$name_add .= '[' . $v . ']';
				}
			}

			/**
			* Debug purposes
			*/
			//print_r( $v ); exit;
			//print_r( self::$name_as_array); exit;
			//print_r( $data['key'][self::$name_as_array] ); exit;

		}

        $check = false;

		if( !empty( $property[DataRules::VALUE] ) )
		{
			if( !is_array( $property[DataRules::VALUE] ) )
			{
				$check = $property[DataRules::VALUE];
			}else{
				//print_r( array_values( $property[DataRules::VALUE] ) ); exit;
				$check = array_combine( $property[DataRules::VALUE], $property[DataRules::VALUE] );
				//print_r( $check ); exit;
			}
		}else{
			if( isset( $property[DataRules::DEFAULTS] ) and !empty( $property[DataRules::DEFAULTS] ) )
			{
				if( !is_array( $property[DataRules::DEFAULTS] ) )
				{
					$check = $property[DataRules::DEFAULTS];
					settype( $check, 'string' );
				}else{
					$check = array_combine( $property[DataRules::DEFAULTS], $property[DataRules::DEFAULTS] );
				}
			}
		}

		/**
		* Debug purposes
		*/
		//print_r( $data[DataRules::OPTIONS] ); exit;

        foreach( $property[DataRules::OPTIONS] as $k => $v )
        {

			$rs[$k] = '<input type="' . $property[DataRules::TYPE] . '"' . self::ParametersAdd() . 'name="' . $name . $name_add;

			/**
			* @see \Builtin\Helpers\HTML\Form::SetNameAsArray()
			*/
			if( !empty( self::$name_as_array ) )
			{
				$rs[$k] .= '[]';
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

					/**
					* @see \Builtin\Helpers\HTML\Form::SetNameAsArray()
					*/
					if( isset( $check[$k] ) )
					{
						$rs[$k] .= ' checked';
					}

				}

			}
    
			/**
			* class (css stylesheet)
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