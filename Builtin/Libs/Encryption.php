<?php

/**
* @class  Encryption
* @file   Encryption.php
* @brief  Encryption functions.
* @date   2013-09-25 23:41:00
* @license http://opensource.org/licenses/GPL-3.0 GNU Public License
* @company: Tipui Co. Ltda.
* @author: Daniel Omine <omine@tipui.com>
* @updated: 2013-09-26 03:59:00
*/

namespace Tipui\Builtin\Libs;

/**
* Encryption library.
*/
class Encryption
{

	/**
	*
	* Sample
	*
	* [code]
	* // New Encryption instance
	* $encryption  = new Libs\Encryption;
	* // Retrieves the encryption from app settings
	* $encryption_conf = \Tipui\Core::GetConf() -> BOOTSTRAP -> ENCRYPTION_LIBRARY;
	* // Encryption library name
	* $encryption_lib  = $encryption_conf['LIB'];
	* // Call the instance dynamically
	* $encryption      = $encryption -> $encryption_lib();
	*
	* $encrypted = $encryption -> Encode( 'foo', $encryption_conf['KEY'], $encryption_conf['CYPHER'], $encryption_conf['MODE'] );
	* echo $encrypted . PHP_EOL;
	* $decrypted = $encryption -> Decode( $encrypted, $encryption_conf['KEY'], $encryption_conf['CYPHER'], $encryption_conf['MODE'] );
	* echo $decrypted . PHP_EOL;
	* [/code]
	*
	*/

	/**
	* Instance.
	*
	* sample
	* [code]
	* $c = new Encryption;
	* $c -> MCrypt() -> Encode( 'string' );
	*
	*
	* If call Auto(), will load automatically from the BOOTSTRAP -> ENCRYPTION_LIBRARY settings
	* $c = new Encryption;
	* $c -> Auto() -> Encode( 'string' );
	*
	* Auto in runtime mode. The parameters of Auto() method is the parameters of library, except for the string parameter to be encrypted
	* $c -> Auto( 'custom_key' ) -> Encode( 'string' );
	* [/code]
	*/
    public function __call( $name, $arguments )
    {
		$encryption_conf = \Tipui\Core::GetConf() -> BOOTSTRAP -> ENCRYPTION_LIBRARY;
		$name == 'Auto' ? $name = $encryption_conf['LIB'] : null;
		return Factory::Exec( 'Encryption', $name, $arguments );
    }

	/**
	* Statically.
	*
	* sample
	* [code]Encryption::MCrypt() -> Encode( 'string' );[/code]
	*/
    public static function __callStatic( $name, $arguments )
    {
		$encryption_conf = \Tipui\Core::GetConf() -> BOOTSTRAP -> ENCRYPTION_LIBRARY;
		$name == 'Auto' ? $name = $encryption_conf['LIB'] : null;
		return Factory::Exec( 'Encryption', $name, $arguments );
    }

}