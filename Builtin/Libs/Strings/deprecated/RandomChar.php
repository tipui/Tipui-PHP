<?php

/**
* @class  RandomChar
* @file   RandomChar.php
* @brief  RandomChar strings functions.
* @date   2013-09-15 02:54:00
* @license http://opensource.org/licenses/GPL-3.0 GNU Public License
* @company: Tipui Co. Ltda.
* @author: Daniel Omine <omine@tipui.com>
* @updated: 2013-09-15 02:54:00
*/

namespace Tipui\Builtin\Libs\Strings;

class RandomChar
{

	/**
	* (boolean) Check if string have e-mail address format.
	*/
	public function Exec()
	{
		$c = range( 'A', 'Z' );
		$n = range( '0', '9' );
		$l = count( $c )-1;
		$i = count( $n )-1;
		$p  = $c[rand( 1, $l )] . $n[rand( 1, $i )] . $c[rand( 1, $l )];
		$p .= $c[rand( 1, $l )] . $n[rand( 1, $i )] . $c[rand( 1, $l )];
		$p .= $n[rand( 1, $i )];
		return $p;
    }

}