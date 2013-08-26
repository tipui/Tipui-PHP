<?php
/** DataRules Class
 *
 *   company: JapanCase - Digital Business
 *   autor: Daniel Omine
 *   email: danielomine@gmail.com
 *   website: www.japancase.com
 *   updated: 2010-10-30 13:14:00 - Daniel Omine
 *
 *   Methods
        Get
*/

class DataRules
{

	static protected $p = false;

    function Get( $p = false )
    {
		//print_r( Controller::Properties() ); exit;

		$rs = false;

		// save on memory in case of reuse in the same module by different or equal rules that have same name
		if( !isset( self::$p[$p] ) )
		{
			require_once( REGISTER_DIR . 'DataRules' . DS . strtolower( $p ) . FILE_EXTENSION );

			self::$p[$p] = $rs;
		}else{
			//print_r( self::$p ); exit;
			$rs = self::$p[$p]; // get from memory
		}

		return $rs;

    }

} // end DataRules class
?>