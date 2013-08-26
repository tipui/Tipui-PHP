<?php
/** Pass Class
 *
 *   company: JapanCase - Digital Business
 *   autor: Daniel Omine
 *   email: danielomine@gmail.com
 *   website: www.japancase.com
 *   updated: 2010-10-14 16:12:00 - Daniel Omine
 *
 *   Methods
        Encrypt
		ClientSideEncryption
*/

class Pass
{

    function Encrypt( $str, $force_bypass = false )
	{
		// $force_bypass jump all process
		if( $force_bypass )
		{
			return $str;
		}

		/*
			check the lenght of string, then, auto-bypass according to the rules of switch
			must registry new lengths for each type of encryption.
		*/
		$bypass = false;
		switch( TRUE )
		{
			// md5 case
			case ( strlen( $str ) == 32 ):
				$bypass = true;
			break;
		}
		
		if( !$bypass )
		{
			if( PASS_ENCRYPT_CLIENT_SIDE )
			{
				switch( PASS_ENCRYPT_CLIENT_HASH )
				{
					case 'md5':
						$str = md5( $str );
					break;
				}
			}else{
				if( PASS_ENCRYPT_SERVER_SIDE )
				{
					switch( PASS_ENCRYPT_SERVER_HASH )
					{
						case 'md5':
							$str = md5( $str );
						break;
					}
				}
			}
		}
		return $str;

    }

	function ClientSideEncryption()
	{
		if( PASS_ENCRYPT_CLIENT_SIDE )
		{
			return PASS_ENCRYPT_CLIENT_HASH;
		}
		return false;
	}

	// deprecated
	function EncryptByPass()
	{
		return Pass::ClientSideEncryption() ? true : false;
	}
}
?>