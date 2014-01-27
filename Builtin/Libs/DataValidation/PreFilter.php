<?php

/**
* @class  PreFilter
* @file   PreFilter.php
* @brief  PreFilter Builtin DataValidation functions.
* @date   2014-01-03 05:30:00
* @license http://opensource.org/licenses/GPL-3.0 GNU Public License
* @company: Tipui Co. Ltda.
* @author: Daniel Omine <omine@tipui.com>
* @updated: 2014-01-24 18:17:00
*/

namespace Tipui\Builtin\Libs\DataValidation;

use \Tipui\Builtin\Libs\Factory as Factory;

class PreFilter
{

	/**
	* Call the filtering functions through Factory (Reflection)
	* @see: Start.php file
	*/
	public function Exec( $filter, $value )
	{
		if( is_array( $filter ) )
		{
			return Factory::Exec( $filter[0][0], $filter[0][1], ( isset( $filter[1] ) ? array_pad( $filter[1], -( count( $filter[1] ) + 1 ), $value ) : array( $value ) ) );
		}else if( is_string( $filter ) ){

			/**
			* Debug purposes
			*/
			//echo __FILE__ . PHP_EOL;
			//print_r( Factory::Exec( '\Tipui\Builtin\Libs\DataValidation', 'Filters\\' . $filter, array( $value ) ) ); exit;

			/**
			* The rule parameter, pre_filter, was defined as string.
			* For this case, the filter is called from pre defined filters.
			*
			* This is useful for simplify wich filter will be applied.
			* For example, instead of
			* 'pre_filter' => array( array( '\Tipui\Builtin\Libs\Strings', 'NumbersOnly' ) )
			* Use
			* 'pre_filter' => 'Number'
			* The result will be the same. The difference is only for friendly notation.
			*
			* @see \Tipui\Builtin\Helpers\DataRules, \Tipui\Builtin\Libs\DataValidation
			*
			*/

			/**
			* Returns stdClass Object
			* The "error" property  is ignored here because the purpose is only filter and not validate.
			*/
			return Factory::Exec( '\Tipui\Builtin\Libs\DataValidation', 'Filters\\' . $filter, array( $value ) ) -> str; exit;
		}
    }

}