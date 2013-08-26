<?php
/** DB MySQL Class
 *
 *   company: JapanCase - Digital Business
 *   autor: Daniel Omine
 *   email: danielomine@gmail.com
 *   website: www.japancase.com
 *   updated: 2010-11-29 15:28:00 - Daniel Omine
 *
 *   Methods
		Open
		Reset
*/

class DB
{

	static  $error            = false;
	private static $conn      = false;

	static function Open( $set = false )
	{
		//print_r( $rs ); exit;
		if( !self::$conn )
		{
			// load the settings defined on config file
			$rs = Config::SetDB();
			//print_r( $rs ); exit;

			// change settings "on the run"
			if( $set )
			{
				$o = 'system';
				if( isset( $set[$o] ) and is_array( $set[$o] ) )
				{
					foreach( $set[$o] as $k => $v )
					{
						$rs[$o][$k] = $v;
					}
				}
				$o = 'dns';
				if( isset( $set[$o] ) and is_array( $set[$o] ) )
				{
					foreach( $set[$o] as $k => $v )
					{
						$rs[$o][$k] = $v;
					}
				}
				$o = 'options';
				if( isset( $set[$o] ) and is_array( $set[$o] ) )
				{
					foreach( $set[$o] as $k => $v )
					{
						$rs[$o][$k] = $v;
					}
				}
			}

			//print_r( $rs ); exit;
			if( $rs['system']['pear'] )
			{
				// load PEAR Library
				require_once 'MDB2.php';

				self::$conn = MDB2::factory( $rs['dns'], $rs['options'] );
				if( PEAR::isError( self::$conn ) )
				{
					self::$error = self::$conn -> getMessage();
				}else{
					if( $rs['dns']['charset'] )
					{
						self::$conn -> setCharset( $rs['dns']['charset'] );
					}
				}
			}else{
				$o = $rs['system']['engine'];
				$o = new $o();
				$o -> dbInfo = $rs;
				self::$conn  = $o;
			}
		}
		return self::$conn;
	}
	static function Reset( )
	{
		self::$error = false;
		self::$conn  = false;
	}
}
?>