<?php
/** Bench Class
 *
 *   company: JapanCase - Digital Business
 *   autor: Daniel Omine
 *   email: danielomine@gmail.com
 *   website: www.japancase.com
 *   updated: 2013-02-07 01:38:00 - Daniel Omine
 *
 *   Methods
		Calc
        Write
*/

class Bench extends Config
{

	function Calc( )
	{

		//$rs  = self::$bench['ini'];
		//$rs .= ' - ' . microtime();
		//echo $rs; exit;

		$starttime  = explode( ' ', self::$bench['ini'] );
		$endtime    = explode( ' ', microtime() );
		$total_time = $endtime[0] + $endtime[1] - ( $starttime[1] + $starttime[0] );
		$total_time = round( $total_time * 1000 );

		return $total_time;
		
		/*
			// usage sample
			echo Bench::Calc( );
		*/
	}

	function Write( $exit = false )
	{
		if( ( BENCHMARK_VIEW and Controller::$mod['template'] ) or BENCHMARK_VIEW_FORCE )
		{
			echo '<!-- ' . Bench::Calc() . ' -->';
			if( $exit ){ exit;}
		}
		return null;
		/*
			// usage sample
			Bench::Write( );
		*/
	}

}
?>