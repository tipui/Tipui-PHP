<?php
/** Languages Class
 *
 *   company: JapanCase - Digital Business
 *   autor: Daniel Omine
 *   email: danielomine@gmail.com
 *   website: www.japancase.com
 *   updated: 2010-09-21 12:39:00 - Daniel Omine
 *
 *   Methods
		Get
        Check
*/

class Languages
{

    function Get( $str = false )
    {

		$rs = LanguagesLabels::Get();

		
		if( $str )
		{
			if( isset( $rs[$str] ) )
			{
				return $rs[$str];
			}
		}else{
			return $rs;
		}

		return false;
	}

    function Check( $str )
    {

		$rs = false;

        // registered languages into config		
		$langs  = Config::Languages();

		if( !is_array( $str ) )
		{
			if( in_array( $str, $langs ) )
			{
				$rs = true;
			}
		}

		return $rs;
    }

}
?>