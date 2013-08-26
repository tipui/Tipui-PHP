<?php
/** Functions Class
 *
 *   company: JapanCase - Digital Business
 *   autor: Daniel Omine
 *   email: danielomine@gmail.com
 *   website: www.japancase.com
 *   updated: 2010-09-15 14:16:00 - Daniel Omine
 *
 *   Methods
        Call
*/

class Functions
{

	function Call( $f )
	{


		return call_user_func_array( $f[0], $f[1] );
		
		/*
			// calling function
			echo Functions::Call( array( 'strip_tags', array( '<br>foo<br>bar</b>', '<br><b>' ) ) ); exit;
			
			// calling object
			Functions::Call( array( array( 'Strings', 'StripTags' ), array( '<br>foo<br>bar</b>', '<br><b>' ) ) );
		*/
	}

}
?>