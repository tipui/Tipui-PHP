<?php

/**
* @class  MoneyFormat
* @file   MoneyFormat.php
* @brief  MoneyFormat strings functions.
* @date   2013-09-15 02:54:00
* @license http://opensource.org/licenses/GPL-3.0 GNU Public License
* @company: Tipui Co. Ltda.
* @author: Daniel Omine <omine@tipui.com>
* @updated: 2013-09-15 02:54:00
*/

namespace Tipui\Builtin\Libs\Strings;

use Tipui\Builtin\Libs as Libs;

class MoneyFormat
{

	/**
	* Format number to a currency.
	*/
	public function Exec( $str, $decimal_places = false )
	{

		$str = Libs\Strings::NumbersOnly( $str );

        if( !empty( $str ) )
		{
            !$decimal_places ? $decimal_places = 0 : null;
            return number_format( $str, $decimal_places, ', ', '.' );
        }

		return null;

		/**
		*@see http://php.net/number_format

		* [usage]
		* echo Libs\Strings::MoneyFormat( 1000, true );
		* //returns 1000,00
		* echo Libs\Strings::MoneyFormat( 1000 );
		* //returns 1000
		*/

    }

}