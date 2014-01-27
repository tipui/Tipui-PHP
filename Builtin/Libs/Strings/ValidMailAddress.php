<?php

/**
* @class  ValidMailAddress
* @file   ValidMailAddress.php
* @brief  ValidMailAddress strings functions.
* @date   2013-09-15 02:54:00
* @license http://opensource.org/licenses/GPL-3.0 GNU Public License
* @company: Tipui Co. Ltda.
* @author: Daniel Omine <omine@tipui.com>
* @updated: 2014-01-05 03:17:00
*/

namespace Tipui\Builtin\Libs\Strings;

class ValidMailAddress
{

	/**
	* (boolean) Check if string have e-mail address format.
	*/
	public function Exec( $str )
	{
        $rule  = '/^([0-9,a-z,A-Z,_,-,.]+)([.,_,-]([0-9,a-z,A-Z,_,-,.]+))';
        $rule .= '*[@]([0-9,a-z,A-Z]+)([.,-]([0-9,a-z,A-Z]+))';
        $rule .= '*[.]([0-9,a-z,A-Z]){2}([0-9,a-z,A-Z])?$/';

        if( preg_match( $rule, $str ) ){
            return $str;
        }else{
            return false;
        }
    }

}