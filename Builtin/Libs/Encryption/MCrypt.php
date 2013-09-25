<?php

/**
* @class  
* @file   MCrypt.php
* @brief  MCrypt Encryption functions.
* @date   2013-09-25 23:41:00
* @license http://opensource.org/licenses/GPL-3.0 GNU Public License
* @company: Tipui Co. Ltda.
* @author: Daniel Omine <omine@tipui.com>
* @updated: 2013-09-26 03:59:00
*
* http://stackoverflow.com/questions/2448256/php-mcrypt-encrypting-decrypting-file/2448441#2448441
*/

namespace Tipui\Builtin\Libs\Encryption;

class MCrypt
{

	/**
	* MCrypt default constants
	*/
    const CYPHER = MCRYPT_RIJNDAEL_256;
    const MODE   = MCRYPT_MODE_CBC;
    const KEY    = 'F$0`sk]#sc4Z=';

	private $running_cypher;
	private $running_mode;
	private $running_key;

	/**
	* Encrypt/Encode an string
	*/
	public function Exec( $key = false, $cypher = false, $mode = false )
	{

		$this -> running_cypher = $cypher ? $cypher : false;
		$this -> running_mode   = $mode   ? $mode   : false;
		$this -> running_key    = $key    ? $key    : false;

		return $this;
	}

	/**
	* Encrypt/Encode an string
	*/
	public function Encode( $str, $key = false, $cypher = false, $mode = false )
	{

		/**
		* Serializes if is array or boolean
		*/
		if( is_array( $str ) or is_bool( $str ) )
		{
			$str = serialize( $str );
		}

		/**
		* Debug purposes
		*/
		//echo $str; exit;

		!$cypher ? $cypher = ( $this -> running_cypher ? $this -> running_cypher : self::CYPHER ) : null;
		!$mode   ? $mode   = ( $this -> running_mode   ? $this -> running_mode   : self::MODE )   : null;
		!$key    ? $key    = ( $this -> running_key    ? $this -> running_key    : self::KEY )    : null;

        $td = mcrypt_module_open( $cypher, '', $mode, '' );
        $iv = mcrypt_create_iv( mcrypt_enc_get_iv_size( $td ), MCRYPT_RAND );
        mcrypt_generic_init( $td, $key, $iv );
        $crypttext = mcrypt_generic( $td, $str );
        mcrypt_generic_deinit( $td );
        return base64_encode( $iv . $crypttext );
    }

	/**
	* Decrypt/Decode an string
	*/
	public function Decode( $str, $key = false, $cypher = false, $mode = false )
	{
		!$cypher ? $cypher = ( $this -> running_cypher ? $this -> running_cypher : self::CYPHER ) : null;
		!$mode   ? $mode   = ( $this -> running_mode   ? $this -> running_mode   : self::MODE )   : null;
		!$key    ? $key    = ( $this -> running_key    ? $this -> running_key    : self::KEY )    : null;

        $str       = base64_decode( $str );
        $plaintext = '';
        $td        = mcrypt_module_open( $cypher, '', $mode, '' );
        $ivsize    = mcrypt_enc_get_iv_size( $td );
        $iv        = substr( $str, 0, $ivsize );
        $str       = substr( $str, $ivsize );
        if( $iv )
        {
			mcrypt_generic_init( $td, $key, $iv );
			$plaintext = trim( mdecrypt_generic( $td, $str ) );
        }

		/**
		* Debug purposes
		*/
		//var_dump($plaintext);

		/**
		* Workaround... check if is serialized or not.
		*/
		$data = @unserialize( $plaintext );

		if( $data !== false || $data === 'b:0;')
		{
			//echo $data; //exit;
			return ( $data === 'b:0;' ) ? false : ( $data === 'b:1;' ? true : $data );
		}

		//echo $plaintext; //exit;
		return ( $plaintext === 'b:0;' ) ? false : ( $plaintext === 'b:1;' ? true : $plaintext );
    }

}