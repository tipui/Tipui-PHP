<?php
/** DAOURL Class
 *
 *   company: JapanCase - Digital Business
 *   autor: Daniel Omine
 *   email: danielomine@gmail.com
 *   website: www.japancase.com
 *   updated: 2012-09-12 02:19:00 - Daniel Omine
 *
 *   Methods
		__construct
		Set
        Request
		Reset
*/

class DAOURL
{

    static $Result;
    static $Error;
    static $ErrorMsg;
    static $fields  = false;
	static $conf    = false;
	static $extr    = false;

	function __construct( $a )
	{
		self::Set( $a );
		return null;
	}

	function Set( $a )
	{
		$p = 'PORT';           self::$conf[CURLOPT_PORT]           = isset( $a[$p] ) ? $a[$p] : 80;
		$p = 'METHOD';         self::$extr[$p]                     = isset( $a[$p] ) ? $a[$p] : 'GET';
		$p = 'URL';            self::$conf[CURLOPT_URL]            = isset( $a[$p] ) ? $a[$p] : '127.0.0.1';
		$p = 'URL_APPEND';     self::$extr[$p]                     = isset( $a[$p] ) ? $a[$p] : false;
		$p = 'ENCODE_DATA';    self::$extr[$p]                     = isset( $a[$p] ) ? $a[$p] : 1;
		$p = 'TIMEOUT';        self::$conf[CURLOPT_TIMEOUT]        = isset( $a[$p] ) ? $a[$p] : 5;
		$p = 'HEADER';         self::$conf[CURLOPT_HEADER]         = isset( $a[$p] ) ? $a[$p] : false;
		$p = 'MAXREDIRS';      self::$conf[CURLOPT_MAXREDIRS]      = isset( $a[$p] ) ? $a[$p] : 1;
		$p = 'REFERER';        self::$conf[CURLOPT_REFERER]        = isset( $a[$p] ) ? $a[$p] : 'http://' . $_SERVER['SERVER_NAME'];
		$p = 'USER_AGENT';     self::$conf[CURLOPT_USERAGENT]      = isset( $a[$p] ) ? $a[$p] : 'Mozilla/5.0 (Windows NT 6.2; WOW64) AppleWebKit/537.1 (KHTML, like Gecko) Chrome/21.0.1180.89 Safari/537.1';
		$p = 'HTTPAUTH';       self::$conf[CURLOPT_HTTPAUTH]       = isset( $a[$p] ) ? $a[$p] : false;  // CURLAUTH_NTLM, CURLAUTH_BASIC, CURLAUTH_DIGEST
		$p = 'USERPWD';        self::$conf[CURLOPT_USERPWD]        = isset( $a[$p] ) ? $a[$p] : false;  // user pass.. if HTTPAUTH is true, must unset()
		$p = 'CONNECTTIMEOUT'; self::$conf[CURLOPT_CONNECTTIMEOUT] = isset( $a[$p] ) ? $a[$p] : 5;
		//$p = 'CONNECTTIMEOUT_MS'; self::$conf[CURLOPT_CONNECTTIMEOUT_MS] = isset( $a[$p] ) ? $a[$p] : ( self::$conf[CURLOPT_CONNECTTIMEOUT] * 1000 );
		$p = 'RETURNTRANSFER'; self::$conf[CURLOPT_RETURNTRANSFER] = isset( $a[$p] ) ? $a[$p] : true;   // return web page 
		$p = 'FOLLOWLOCATION'; self::$conf[CURLOPT_FOLLOWLOCATION] = isset( $a[$p] ) ? $a[$p] : false;  // follow redirects
		$p = 'ENCODING';       self::$conf[CURLOPT_ENCODING]       = isset( $a[$p] ) ? $a[$p] : '';     // if empty, handle all encodings 
		$p = 'AUTOREFERER';    self::$conf[CURLOPT_AUTOREFERER]    = isset( $a[$p] ) ? $a[$p] : true;   // set referer on redirect 
		$p = 'SSL_VERIFYHOST'; self::$conf[CURLOPT_SSL_VERIFYHOST] = isset( $a[$p] ) ? $a[$p] : 0;      // don't verify ssl
		$p = 'SSL_VERIFYPEER'; self::$conf[CURLOPT_SSL_VERIFYPEER] = isset( $a[$p] ) ? $a[$p] : false;  // 
		$p = 'VERBOSE';        self::$conf[CURLOPT_VERBOSE]        = isset( $a[$p] ) ? $a[$p] : 1;      // 

		$p = 'CAINFO';         self::$conf[CURLOPT_CAINFO]         = isset( $a[$p] ) ? $a[$p] : false;  //  path to ssl certificate


		return null;
	}

    function Request( )
    {

        self::$Error    = false;
        self::$ErrorMsg = false;
		if( !self::$conf ){self::Set( array() );}
        if( !self::$conf[CURLOPT_SSL_VERIFYPEER] )
        {
			unset( self::$conf[CURLOPT_CAINFO] );
		}
        if( !self::$conf[CURLOPT_USERPWD] )
        {
			if( isset( self::$conf[CURLOPT_HTTPAUTH] ) )
			{
            unset( self::$conf[CURLOPT_HTTPAUTH] );
			}
        }
		//print_r( self::$conf ); exit;


        if( self::$fields and is_array( self::$fields ) )
        {
            $s = 0;
            switch( self::$extr['METHOD'] )
            {
                default:
                case 'GET':
                    self::$conf[CURLOPT_HTTPGET] = true;
                    self::$conf[CURLOPT_URL]    .= '?';
					$i = false;
                    foreach( self::$fields as $k => $v )
                    {
						$p  = $i ? '&':'';
                        $p .= $k . '=' . urlencode( $v );
                        $s += strlen( $p );
                        self::$conf[CURLOPT_URL] .= $p;
						$i = true;
                    }
                    $s = strlen( $p );
					//echo self::$conf[CURLOPT_URL]; exit;
                break;
                case 'POST':
                    self::$conf[CURLOPT_POST]    = 1;
                    //print_r( self::$fields ); exit;
                    if( self::$extr['ENCODE_DATA'] == 2 )
                    {
                        self::$conf[CURLOPT_POSTFIELDS]  = self::$fields;
                    }else{
                        self::$conf[CURLOPT_POSTFIELDS] = '';
                        foreach( self::$fields as $k => $v )
                        {
                            self::$conf[CURLOPT_POSTFIELDS] .= $k . '=' . urlencode( $v ) . '&';
                        }
                        $s = strlen( self::$conf[CURLOPT_POSTFIELDS] );
                    }
                break;
            }
            if( $s > 0 )
            {
				// infinite loop on db.bomprecoserver... 2012-09-12
                //self::$conf[CURLOPT_HTTPHEADER] = array( 'Content-Type: text/xml; charset=ISO-8859-1', 'Content-length: ' . $s );
            }

        }
        
		if( self::$extr['URL_APPEND'] )
		{
			self::$conf[CURLOPT_URL] .= self::$extr['URL_APPEND'];
		}

		//echo file_get_contents( self::$conf[CURLOPT_URL] ); exit;
        //print_r( self::$conf ); exit;


        $ch = curl_init(); 
        curl_setopt_array( $ch, self::$conf );

		/*
		// debug purposes only
				foreach (self::$conf as $k => $v) {
					if (!curl_setopt($ch, $k, $v)) {
						//echo $k . ': ' . time(); exit;
					}
				}
		*/

        try {
            $content = curl_exec($ch);
            $err     = curl_errno($ch); 
            $errmsg  = curl_error($ch) ; 
            $header  = curl_getinfo($ch); 
            curl_close($ch); 
            //echo time(); exit;
            $header['errno']   = $err; 
            $header['errmsg']  = $errmsg; 
            $header['content'] = $content; 

            if ( $header['errno'] == 0 ){

                if ( $header['http_code'] == 200 ){
                    //echo time(); exit;
                    self::$Result = $header['content'];
                }else{
                    self::$Error = $header;
                }

            }else{
                self::$Error = $header;
            }

        } catch (HttpException $ex) {
            self::$Error['exception'] = $ex;
            self::$ErrorMsg           = self::$Error['exception'];
        }

        return null;
    }

    function Reset( $all = false )
    {
        self::$Result    = false;
        self::$Error     = false;
        self::$ErrorMsg  = false;
        
        self::$fields    = false;
		if( $all )
		{
			self::$conf      = false;
		}
    }

/*
// usage sample

	include('DAOURL.php');

	$arr['PORT']            = '7788';
	$arr['TIMEOUT']         = 7;
	$arr['HEADER']          = true;
	$arr['MAXREDIRS']       = 1;
	$arr['REFERER']         = 'http://batata.jp';
	$arr['USER_AGENT']      = 'Mozilla/4.0 (compatible; MSIE 5.01; Windows NT 5.0)';
	$arr['USERPWD']         = false;
	$arr['HTTPAUTH']        = false; // HTTP_AUTH_NTLM, CURLAUTH_BASIC
	$arr['METHOD']          = 'GET';
	$arr['CONNECTTIMEOUT']  = 7;
	$arr['ENCODE_DATA']     = 1;

	$c = new DAOURL( $arr );
	//$c::$conf = $arr; 
	$c -> url_append = '';
	$c -> url        = '69.64.56.138';
	$c -> Request();

	if( !$c -> Error )
	{
		echo $c -> Result; exit;
	}else{
		//print_r( $c -> Error ); exit;
		self::$error = $c -> ErrorMsg; 
		//exit;
	}

*/

} // end DAOURL class
 
?>