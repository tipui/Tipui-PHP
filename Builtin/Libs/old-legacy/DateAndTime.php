<?php
/** DateAndTime.php
 *
 *   company: JapanCase - Digital Business
 *   autor: Daniel Omine
 *   email: danielomine@gmail.com
 *   website: www.japancase.com
 *   updated: 2013-03-03 21:08:00 - Daniel Omine
 *
 *   Methods
        Init
        Result
        CheckDate
        Months
		IncDec
		GMT
		LeapYear
		Get
		TimeStampDiff
		TimeStampToDate
		Age
		Format
*/

class DateAndTime
{

    static $rs           = array();

    function Init()
    {
        self::$rs = array( 'error' => false );
    }

    function Result( $error, $error_msg )
    {
        self::$rs = array(
                'error'     => $error,
                'error_msg' => $error_msg,
        );
        return null;
    }

    function CheckDate( $str ){

        self::Init();

        if( !is_array( $str ) )
        {
            if( strpos( $str , '-' ) )
            {
                $arr = explode( '-', $str );
            }
        }else{
            $arr = $str;
        }

        empty( $arr['Y'] ) ? $arr['Y'] = false : '' ;
        empty( $arr['m'] ) ? $arr['m'] = false : '' ;
        empty( $arr['d'] ) ? $arr['d'] = false : '' ;

        isset( $arr[0] ) ? $arr['Y'] = $arr[0] : '' ;
        isset( $arr[1] ) ? $arr['m'] = $arr[1] : '' ;
        isset( $arr[2] ) ? $arr['d'] = $arr[2] : '' ;

        $arr['Y'] = sprintf( '%04d', substr( Strings::NumbersOnly( $arr['Y'] ), 0, 4 ) );
        $arr['m'] = sprintf( '%02d', substr( Strings::NumbersOnly( $arr['m'] ), 0, 2 ) );
        $arr['d'] = sprintf( '%02d', substr( Strings::NumbersOnly( $arr['d'] ), 0, 2 ) );

        if( $arr['Y'] and $arr['m'] and $arr['d'] and strlen( $arr['Y'] ) == 4 and strlen( $arr['m'] ) == 2 and strlen( $arr['d'] ) == 2 and $arr['Y'] > 0 and $arr['m'] > 0 and $arr['d'] > 0 )
        {

            if( !$rs = checkdate( sprintf( '%02d', $arr['m'] ), sprintf( '%02d', $arr['d'] ), sprintf( '%04d', $arr['Y'] ) ) )
            {
                self::Result( 102, $rs );
            }

        }else{

            self::Result( 101, 'invalid' );

        }

        /** usage
        http://php.net/checkdate

            sample 1 

                DateAndTime::CheckDate( '2008-06-23' );
                if( !DateAndTime::$rs['error'] ){
                    echo 'valid';
                }else{
                    echo 'invalid';
                }

            sample 2 

                $arr['Y'] = 2008;
                $arr['m'] = 06;
                $arr['d'] = 23;
                DateAndTime::CheckDate( $arr );
                if( !DateAndTime::$rs['error'] ){
                    echo 'valid';
                }else{
                    echo 'invalid';
                }

            sample 3

                $arr[0] = 2008;
                $arr[1] = 06;
                $arr[2] = 23;

                DateAndTime::CheckDate( $arr );
                if( !DateAndTime::$rs['error'] ){
                    $rs = 'valid';
                }else{
                    $rs = 'invalid';
                }
        */
    }

    function Months( $id = false )
    {
        return DateAndTimeLabels::Months( $id );
    }


    function IncDec( $src1 = false, $src2 = '', $output = '' ){
    
        $i['Y'] = 'years';
        $i['m'] = 'months';
        $i['d'] = 'days';
        $i['H'] = 'hours';
        $i['i'] = 'minutes';
        $i['s'] = 'seconds';

        if( !is_array( $src1 ) or !$src1 ){
            $src1 = DateAndTime::GET( true );
        }
        if( !is_array( $src2 ) or $src2 == '' ){
            $src2 = array(
                    'Y' => 0
                   ,'m' => 0
                   ,'d' => 0
                   ,'H' => 0
                   ,'i' => 0
                   ,'s' => 0
                );
        }else{

            !isset( $src2['Y'] ) ? $src2['Y'] = 0 : false;
            !isset( $src2['m'] ) ? $src2['m'] = 0 : false;
            !isset( $src2['d'] ) ? $src2['d'] = 0 : false;
            !isset( $src2['H'] ) ? $src2['H'] = 0 : false;
            !isset( $src2['i'] ) ? $src2['i'] = 0 : false;
            !isset( $src2['s'] ) ? $src2['s'] = 0 : false;

        }
    
        foreach( $src2 as $k => $v )
        {
            $factor[] = '+' . $v . ' ' . $i[$k]; 
        }
    
        $output == '' ? $output = 'Y-m-d H:i:s' : '';
    
        $date1 = mktime( $src1['H'], $src1['i'], $src1['s'], $src1['m'], $src1['d'], $src1['Y'] );
        $rs    = strtotime( implode( ' ', $factor ), $date1 );

        return date( $output, $rs );
    

        /** usage
        http://php.net/mktime
        http://php.net/implode
        http://php.net/date
        http://php.net/strtotime
        http://php.net/is_array

        sample
            $src1 = array(
                    'Y' => date('Y')
                   ,'m' => date('m')
                   ,'d' => date('d')
                   ,'H' => date('H')
                   ,'i' => date('i')
                   ,'s' => date('s')
                );
            
            $src2 = array(
                    'Y' => 0
                   ,'m' => 0
                   ,'d' => 0
                   ,'H' => 2 // for decrease 2 hours put -2
                   ,'i' => 0
                   ,'s' => 0
                );
            
            print_r( DateAndTime::IncDec( $src1, $src2, 'H' ) );
        */
    }


    function GMT(){

        $src1 = array(
                'Y' => date('Y')
               ,'m' => date('m')
               ,'d' => date('d')
               ,'H' => date('H')
               ,'i' => date('i')
               ,'s' => date('s')
            );

        $src2 = array(
                'Y' => GMT_year
               ,'m' => GMT_month
               ,'d' => GMT_days
               ,'H' => GMT_hours
               ,'i' => GMT_minute
               ,'s' => 0
            );

        return self::IncDec( $src1 , $src2 );

        /**
            usage sample

            echo DateAndTime::GMT();
            return 'yyyy-mm-dd hh:ii:ss'
        */
    }


    function LeapYear( $n = '' ){
        // "ano bissexto"

        $n = strings::OnlyNumber( $n );
        $n == '' or strlen( $n ) <> 4  ? $n = self::GMT() : '';
        $n = substr( $n, 0, 4 );
        return ( $n % 4 == 0 &&( $n % 100  !=  0 || $n % 400 == 0 ) );

        /** usage

            if( DateAndTime::LeapYear() ){
                echo 'yes';
            }else{
                echo 'no';
            }

            if( DateAndTime::LeapYear(1981) ){
                echo 'yes';
            }else{
                echo 'no';
            }

            output boolean
            true:  leap year 366 days
            false: normal    365 days
        */
    }


    function GET( $arr = true, $part = false ){

        $r  = self::GMT();
        $ra = array(
                'Y' => substr( $r, 0, 4 )
               ,'m' => substr( $r, 5, 2 )
               ,'d' => substr( $r, 8, 2 )
               ,'H' => substr( $r, 11, 2 )
               ,'i' => substr( $r, 14, 2 )
               ,'s' => substr( $r, 17, 2 )
            );

        if( $arr ){
            if( $part && isset( $ra[$part] ) ){
                return $ra[$part];
            }else{
                return $ra;
            }
        }else{
            return $r; // return same that DateAndTime::GMT()
        }


        // GET CURRENT DATE
        /** usage
            samples

            DateAndTime::GET( true );      // return array( YmdHis )
            DateAndTime::GET( true, 'Y' ); // return year 0000. options: "Y,m,d,H,i,s"
            DateAndTime::GET( false );     // return yyyy-mm-dd hh:ii:ss
        */

    }
	
	function Diff( $dt1, $dt2 )
	{
		$datetime1 = new DateTime( $dt1 );
		$datetime2 = new DateTime( $dt2 );
		return $datetime1 -> diff( $datetime2 );
	}

	function TimeStampDiff( $dt1, $dt2 )
	{
		return strtotime( $dt1 ) - strtotime( $dt2 ); 
	}
	
	function TimeStampToDate( $str, $mask = 'Y-m-d H:i:s' )
	{
		return date( $mask, $str );
	}

	function Age( $DOBArray )
	{
        !is_array( $DOBArray ) ? $DOBArray = explode( '-', $DOBArray ) : '' ;

        !isset( $DOBArray[0] ) ? $DOBArray[0] = $DOBArray['Y'] : $DOBArray['Y'] = $DOBArray[0] ;
        !isset( $DOBArray[1] ) ? $DOBArray[1] = $DOBArray['m'] : $DOBArray['m'] = $DOBArray[1] ;
        !isset( $DOBArray[2] ) ? $DOBArray[2] = $DOBArray['d'] : $DOBArray['d'] = $DOBArray[2] ;

        // Get current year, month and day
        $TodayDate   = self::GET( true );
        $TodayDay    = $TodayDate['d'];
        $TodayMonth  = $TodayDate['m'];
        $TodayYear   = $TodayDate['Y'];
        
        // Work out Age in Years
        if (($TodayMonth > $DOBArray[1]) || (($TodayMonth == $DOBArray[1]) && ($TodayDay >= $DOBArray[2]))) {
           $AgeYear = $TodayYear - $DOBArray[0];
        } else {
           $AgeYear = $TodayYear - $DOBArray[0] - 1;
        }

        return $AgeYear;

        /** usage

            sample 1
            echo DateAndTime::Age('1981-08-10');

            sample 2
            echo DateAndTime::Age( array( 'Y' => 1981, 'm' => 08, 'd' => 10 ) );

        */
	}
	static function Format( $str, $o )
	{
		switch( $o )
		{
			case 'remove_gmt':
				return str_replace( 'T', ' ', substr( $str, 0, 19 ) );
			break;
			case 'datetime_string_to_array':
				if( !is_array( $str ) )
				{
					if( strpos( $str, '-' ) )
					{
						$d = explode( '-', substr( $str, 0, 10 ) );
					}
					if( strpos( $str, ':' ) )
					{
						$t = explode( ':', substr( $str, 11, 8 ) );
						$d[3] = $t[0];
						$d[4] = $t[1];
						$d[5] = $t[2];
					}else{
						$d[3] = '00';
						$d[4] = '00';
						$d[5] = '00';
					}
					return $d;
				}
			break;
			case 'datetime_array_to_string':
				if( !is_array( $str ) )
				{
					$d = self::Format( $str, 'datetime_string_to_array' );
				}else{
					if( isset( $str['Y'] ) )
					{
						$d[0] = $str['Y'];
						$d[1] = $str['m'];
						$d[2] = $str['d'];
						$d[3] = $str['H'];
						$d[4] = $str['i'];
						$d[5] = $str['s'];
					}else{
						$i = 0;
						foreach( $str as $v )
						{
							$d[$i] = $v;
							$i++;
						}
						!isset( $d[3] ) ? $d[3] = '00' : '';
						!isset( $d[4] ) ? $d[4] = '00' : '';
						!isset( $d[5] ) ? $d[5] = '00' : '';
					}
				}

				return sprintf( '%04d', $d[0] ) . '-' . sprintf( '%02d', $d[1] ) . '-' . sprintf( '%02d', $d[2] ) . ' ' . sprintf( '%02d', $d[3] ) . ':' . sprintf( '%02d', $d[4] ) . ':' . sprintf( '%02d', $d[5] );
			break;
		}
		return $str;
	}
}
?>