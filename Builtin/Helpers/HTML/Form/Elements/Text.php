<?php

/**
* @class  Text
* @file   Text.php
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

class Text extends \Tipui\Builtin\Helpers\HTML\Form
{

	public static function Add( $name, $property )
    {
		$rs  = '<input type="text"' . self::ParametersAdd() . 'name="' . $name;

		/**
		* Holds the $name_as_array value as formated string
		* @see \Tipui\Builtin\Libs\DataValidation\Sanitize
		*/
		$name_array_as_string = null;

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

				/**
				* Formating for ArrayBaseTree
				* @see \Tipui\Builtin\Libs\DataValidation\Sanitize
				*/
				$name_array_as_string = implode( '/', self::$name_as_array );
			}
		}
		
		/**
		* value property
		*/
		$rs .= '" value="';

		if( !empty( $property[DataRules::VALUE] ) )
		{
			/**
			* [review]
			* Must review if is realy necessary to escape string from html injections.
			* Mostly of all parameters have the optional pre-filter that already applies the String::Escape()
			*/
			if( !is_array( $property[DataRules::VALUE] ) )
			{
				$rs .= Strings::Escape( $property[DataRules::VALUE], 'quotes' );
			}else{

				/**
				* Debug purposes
				*/
				//echo implode( '/', self::$name_as_array ) . PHP_EOL;
				//echo __FILE__ . PHP_EOL; print_r( $property[DataRules::VALUE] ); exit;

				/**
				* 
				* @see \Tipui\Builtin\Libs\DataValidation\Sanitize
				*/
				if( !empty( $name_array_as_string ) )
				{
					if( isset( $property[DataRules::VALUE][$name_array_as_string] ) )
					{
						$rs .= Strings::Escape( $property[DataRules::VALUE][$name_array_as_string], 'quotes' );
					}
				}else{
					/**
					* [review] Must test user input for securitie reasons.
					*/
					throw new \Exception('Value is array, but $name_array_as_string is empty.');
				}

			}

		}else{

			if( !is_array( $property[DataRules::DEFAULTS] ) )
			{
				$rs .= Strings::Escape( $property[DataRules::DEFAULTS], 'quotes' );
			}else{

				throw new \Exception('Default as array, not supported');

			}

		}

		/**
		* size
		*/
		$rs .= '" size="' . $property[DataRules::SIZE] . '"';

		/**
		* maxLength
		*/
		$v = ( ( isset( $property[DataRules::MAX_LENGTH] ) ) ? $property[DataRules::MAX_LENGTH] : ( isset( $property[DataRules::EXACT_LENGTH] ) ? $property[DataRules::EXACT_LENGTH] : null ) );
		if( !empty( $v ) )
		{
			$rs .= ' maxLength="' . $v . '"';
		}
		unset( $v );

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