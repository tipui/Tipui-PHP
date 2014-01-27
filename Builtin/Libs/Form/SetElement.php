<?php

/**
* @class  SetElement
* @file   SetElement.php
* @brief  SetElement Builtin Form Lib functions.
* @date   2013-09-23 03:07:00
* @license http://opensource.org/licenses/GPL-3.0 GNU Public License
* @company: Tipui Co. Ltda.
* @author: Daniel Omine <omine@tipui.com>
* @updated: 2014-01-02 19:20:00
*/

namespace Tipui\Builtin\Libs\Form;

use \Tipui\Builtin\Libs\DataRules as DataRules;

class SetElement extends \Tipui\Builtin\Libs\Form
{

	/**
	* Sets form fields rules
	*/
	public function Exec( $name = null, $rule = null, $required = true, $rule_index = false )
	{

		if( !empty( $name ) )
		{
			/**
			* Creates parameter rules
			*/
			self::$parameters[$name] = DataRules::Get( $rule, $required );

			/**
			* Set parameter's especific rule index, if $rule_index is not false.
			*/
			if( is_array( $rule_index ) )
			{
				/**
				* Iterates the array to set the index for each rule parameter.
				*/
				foreach( $rule_index as $k => $v )
				{

					/**
					* Debug purposes
					*/
					//print_r( ( $rule_index ) ); exit;
					self::$parameters[$name][$k] = $v;

				}
			}
		}

		return new self;

		/**
		* Debug purposes
		*/
		//print_r( self::$parameters[$name] ); exit;
    }

}