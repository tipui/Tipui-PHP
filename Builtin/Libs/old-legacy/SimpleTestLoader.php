<?php
/** SimpleTestLoader Class
 *
 *   company: JapanCase - Digital Business
 *   autor: Daniel Omine
 *   email: danielomine@gmail.com
 *   website: www.japancase.com
 *   updated: 2012-09-07 16:56:00 - Daniel Omine
 *
 *   Methods
		__construct
		GetObject
*/

class SimpleTestLoader
{
	function __construct(){
		require_once(LIB_DIR . 'simpletest' . DS . 'browser.php');
	}

	public static function GetObject($clss){
		return new $clss();
	}
}
?>