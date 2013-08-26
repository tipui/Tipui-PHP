<?php
/** DateBirth.php
 *
 *   company: JapanCase - Digital Business
 *   autor: Daniel Omine
 *   email: danielomine@gmail.com
 *   website: www.japancase.com
 *   updated: 2010-05-13 20:09:00 - Daniel Omine
 *
 *   Methods
        ValidDays
        ValidYears
*/

class DateBirth
{

	const MAX_AGE = 130;

	// for Date Birth
	function ValidDays()
    {
		$rs = range( 0, 31 );
		unset( $rs[0] );
		return $rs;
    }
	// for Date Birth
    function ValidYears()
    {
		$y   = DateAndTime::GET( true, 'Y' );
		$ini = $y - DATE_BIRTH_MIN_AGE;
		$end = $y - DATE_BIRTH_MAX_AGE;
		while( $end <= $ini )
		{
			$rs[$end] = $end;
			$end++;
		}
		arsort( $rs );
        return $rs;
    }

	// compare age min and age max to validate.
	// age min must be less then max, basically
    function ValidAgeRange( $min, $max )
    {
		$rs['Error'] = false;
		if( $min < 0 )
		{
			$rs['Error'] = 'min<0';			
		}
		if( $min > $max )
		{
			$rs['Error'] = 'min>max';
		}
		if( $max > self::MAX_AGE )
		{
			$rs['Error'] = 'max>limit';
		}
		return $rs;
    }

}
?>