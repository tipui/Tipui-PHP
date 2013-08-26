<?php

/** CurrencyConverter Class
 *
 *   company: JapanCase - Digital Business
 *   autor: Daniel Omine
 *   email: danielomine@gmail.com
 *   website: www.japancase.com
 *   updated: 2013-02-26 20:10:00 - Daniel Omine
 *
 *   Methods
        __construct
		GetRate
		GetError
		Google
		CalcDown
		CalcUp
*/

class CurrencyConverter
{

	private $rate;
	private $error;
	
	function __construct()
	{
		$this -> rate  = false;
		$this -> error = false;
	}

	public function GetRate( )
	{
		return $this -> rate;
	}
	public function GetError( )
	{
		return $this -> error;
	}

	public function Google( $amount = 1, $from_currency = 'USD', $to_currency = 'JPY' )
	{
		$rs['result'] = false;
		$rs['error']  = false;

		// http://www.xe.com/iso4217.php
		// BRL, CNY, JPY, USD
		$query = 'hl=en&q=' . urlencode( $amount ) . urlencode( $from_currency ) . '%3D%3F' . urlencode( $to_currency );
		$source = file_get_contents( 'http://google.com/ig/calculator?' . $query );

		$currency_data = explode('"', $source);
		if( isset( $currency_data['3'] ) )
		{
			$currency_data = explode(' ', $currency_data['3']);
		}else{
			$this -> error = 1;
		}
		if( !$this -> error )
		{
			if( isset( $currency_data['0'] ) )
			{
				$this -> rate = $currency_data['0'];
			}else{
				$this -> error = 2;
			}
		}

		return $this -> rate;
	}

	public function CalcDown( $val )
	{
		if( $this -> rate )
		{
			return ceil( $val * $this -> rate );
		}else{
			$this -> error = 10;
		}
		return null;
	}

	public function CalcUp( $val )
	{
		if( $this -> rate )
		{
			return round( $val * $this -> rate );
		}else{
			$this -> error = 11;
		}
		return null;
	}
}
?>