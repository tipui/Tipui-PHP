<?php

/**
* @class  SetElementRule
* @file   SetElementRule.php
* @brief  SetElementRule Builtin Form Lib functions.
* @date   2014-01-05 02:35:00
* @license http://opensource.org/licenses/GPL-3.0 GNU Public License
* @company: Tipui Co. Ltda.
* @author: Daniel Omine <omine@tipui.com>
* @updated: 2014-01-05 02:35:00
*/

namespace Tipui\Builtin\Libs\Form;

use \Tipui\Builtin\Libs\DataRules as DataRules;

class SetElementRule extends \Tipui\Builtin\Libs\Form
{

	/**
	* Editing form field rules
	* @param $name: The parameter name
	* @param $rule_index:
	*  For multiple rules, set as array(). 
	*  ie: array( 'value'=>'foo', 'default'=>'bar' )
	*  For single rule $rule_index must the the rule name and $rule_value the value of rule
	*/
	public function Exec( $name, $rule_index, $rule_value = null )
	{

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
				self::$parameters[$name][$k] = $v;
			}
		}else{
			if( !is_string( $rule_index ) )
			{
				throw new \Exception('The rule index "' . $rule_index . '" must be string.');
			}
			self::$parameters[$name][$rule_index] = $rule_value;
		}

		return new self;

    }

}