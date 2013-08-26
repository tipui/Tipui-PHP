<?php
/** UserNavigation Class
 *
 *   company: JapanCase - Digital Business
 *   autor: Daniel Omine
 *   email: danielomine@gmail.com
 *   website: www.japancase.com
 *   updated: 2012-04-06 14:00:00 - Daniel Omine
 *
 *   Methods
        Set
		Get
		PageNext
		SetPageNext
		PageNextClear
        GetRemoteInfo
		URLModuleSet
		URLModuleGet
		URLModuleSetParameter
		Browser
*/

class UserNavigation
{

	static $URLModule      = false;
	static $URLModuleName  = false;
	const  SID_NAME        = USER_NAVIGATION_SESSION_NAME;

    function Set( $p = false )
    {
		//echo get_class( $this );
		//echo 'SIDName: ' . self::SID_NAME; exit;
		//echo 'session_id: ' . session_id(); exit;
		//print_r( $_SERVER ); exit;

		$session = new Session;
		$session -> Init( );
		$session -> Get( self::SID_NAME );
		//print_r( $_SESSION ); //exit;
		//echo 'PHPSESSID: ' . PHPSESSID; exit;
		//echo 'session_id: ' . session_id(); exit;
		
		if( !$session -> exists )
		{

			$rnifo = self::GetRemoteInfo();

			$data['SessionID']             = session_id();
			$data['TimeIni']               = time();
			$data['TimeEnd']               = false;
			
			$data['REQUEST_URI']           = urldecode( $_SERVER['REQUEST_URI'] );
			
			$data['PageStart']             = $data['REQUEST_URI'];
			$data['PageCurrent']           = $data['REQUEST_URI'];
			$data['PageNext']              = false;
			$data['PagePrevius']           = false;

			$data['REMOTE_ADDR']           = $rnifo['IP']; //$_SERVER['REMOTE_ADDR'];
			$data['REMOTE_HOST_BY_ADDR']   = $rnifo['REMOTE_HOST_BY_ADDR'];
			$data['REMOTE_PROXY']          = $rnifo['PROXY'];
			$data['REMOTE_PORT']           = $_SERVER['REMOTE_PORT'];

			$data['HTTP_REFERER']          = isset( $_SERVER['HTTP_REFERER'] ) ? $_SERVER['HTTP_REFERER'] : '';
			$data['REQUEST_METHOD']        = $_SERVER['REQUEST_METHOD'];

			$data['HTTP_CONNECTION']       = isset( $_SERVER['HTTP_CONNECTION'] )      ? $_SERVER['HTTP_CONNECTION'] : '';
			$data['HTTP_USER_AGENT']       = isset( $_SERVER['HTTP_USER_AGENT'] )      ? $_SERVER['HTTP_USER_AGENT'] : '';
			$data['HTTP_ACCEPT_LANGUAGE']  = isset( $_SERVER['HTTP_ACCEPT_LANGUAGE'] ) ? $_SERVER['HTTP_ACCEPT_LANGUAGE'] : '';
			$data['HTTP_ACCEPT_ENCODING']  = isset( $_SERVER['HTTP_ACCEPT_ENCODING'] ) ? $_SERVER['HTTP_ACCEPT_ENCODING'] : '';		
			$data['HTTP_ACCEPT_CHARSET']   = isset( $_SERVER['HTTP_ACCEPT_CHARSET'] )  ? $_SERVER['HTTP_ACCEPT_CHARSET'] : '';
			$data['HTTP_ACCEPT']           = isset( $_SERVER['HTTP_ACCEPT'] )          ? $_SERVER['HTTP_ACCEPT'] : '';
			
			//print_r( $data ); exit;

			$session -> Set( self::SID_NAME, $data );			
			unset( $data );

		}else{
		
			//print_r( $session -> data ); exit;
			
			$session -> data['REQUEST_URI']           = urldecode( $_SERVER['REQUEST_URI'] );
			
			$session -> data['TimeEnd']               = time();
			$session -> data['PagePrevius']           = $session -> data['PageCurrent'];
			$session -> data['PageCurrent']           = $session -> data['REQUEST_URI'];
			$session -> data['HTTP_REFERER']          = isset( $_SERVER['HTTP_REFERER'] ) ? $_SERVER['HTTP_REFERER'] : '';
			$session -> data['REQUEST_METHOD']        = $_SERVER['REQUEST_METHOD'];
			
			$session -> data['HTTP_CONNECTION']       = isset( $_SERVER['HTTP_CONNECTION'] )      ? $_SERVER['HTTP_CONNECTION'] : '';
			$session -> data['HTTP_ACCEPT_LANGUAGE']  = isset( $_SERVER['HTTP_ACCEPT_LANGUAGE'] ) ? $_SERVER['HTTP_ACCEPT_LANGUAGE'] : '';
			$session -> data['HTTP_ACCEPT_ENCODING']  = isset( $_SERVER['HTTP_ACCEPT_ENCODING'] ) ? $_SERVER['HTTP_ACCEPT_ENCODING'] : '';	
			$session -> data['HTTP_ACCEPT_CHARSET']   = isset( $_SERVER['HTTP_ACCEPT_CHARSET'] )  ? $_SERVER['HTTP_ACCEPT_CHARSET'] : '';
			$session -> data['HTTP_ACCEPT']           = isset( $_SERVER['HTTP_ACCEPT'] )          ? $_SERVER['HTTP_ACCEPT'] : '';
			
			if( $p and is_array( $p ) )
			{
				foreach( $p as $k => $v )
				{
					switch( $k )
					{
						case 'PageCurrent':
						case 'PageNext':
						case 'PagePrevius':
							$session -> data[ $k ] = $v;
							//echo $v; exit;
						break;
						case 'URL':
							$session -> data[ $k ] = $v;
							/*
								$v is array
								'ModuleName' = 'ModuleAdminPersonList'
								'Parameters' = array( 'Page' => 1, 'ID' => 10 )

								UserNavigation::Set( array( 'URL' => array( 'ModuleName' => 'ModuleAdminPersonList', 'Parameters' => array( 'Page' => 1, 'ID' => 10  ) ) )
								UserNavigation::Get( 'URL' )
							*/
						break;
					}
				}
			}
						
			$session -> Set( self::SID_NAME, $session -> data );

		}
	
        return null;
    }
	
    function Get( $idx = false )
    {
	
		$session = new Session;
		$session -> Get( self::SID_NAME );
		if( $session -> exists )
		{
			if( $idx and isset( $session -> data[$idx] ) )
			{
				return $session -> data[$idx];
			}
			return $session -> data;
		}else{
			return false;
		}
		
	}

	function PageNext()
	{
		$navi = self::Get();
		//print_r( $navi['PageNext'] ); exit;
		if( $navi['PageNext'] )
		{
			self::PageNextClear();
			//$navi = self::Get();
			//print_r( $navi['PageNext'] ); exit;
			if( !is_array( $navi['PageNext'] ) )
			{
				URLWrite::HeaderLocation( array( PARAM_NAME ), array( $navi['PageNext'] ) );
			}else{
				URLWrite::HeaderLocation( $navi['PageNext']['parameters'], $navi['PageNext']['values'] );
			}
		}
		return null;
	}
	
	static public function SetPageNext( $p, $v )
	{
		self::Set( array( 'PageNext' => array( 'parameters' => $p, 'values' => $v ) ) );
	}
	
	function PageNextClear()
	{
		self::Set( array( 'PageNext' => false ) );
		return null;
	}

    function GetRemoteInfo()
	{

		$proxy = '';
		$IP    = '';
		if( isset( $_SERVER ) )
		{
			if ( isset( $_SERVER['HTTP_X_FORWARDED_FOR'] ) )
			{
               $IP     = $_SERVER['HTTP_X_FORWARDED_FOR'];
               $proxy  = $_SERVER['REMOTE_ADDR'];
			}elseif( isset( $_SERVER['HTTP_CLIENT_IP'] ) ){
               $IP     = $_SERVER['HTTP_CLIENT_IP'];
			}else{
               $IP     = $_SERVER['REMOTE_ADDR'];
			}
		}else{
			if ( getenv( 'HTTP_X_FORWARDED_FOR' ) )
			{
               $IP     = getenv( 'HTTP_X_FORWARDED_FOR' );
               $proxy  = getenv( 'REMOTE_ADDR' );
			}elseif ( getenv( 'HTTP_CLIENT_IP' ) ) {
               $IP     = getenv( 'HTTP_CLIENT_IP' );
			}else{
               $IP     = getenv( 'REMOTE_ADDR' );
			}
		}
		if( strstr( $IP, ',' ) )
		{
			$ips  = explode( ',', $IP );
			$IP   = $ips[0];
		}
		
		return array(
					'IP'                   => $IP,
					'REMOTE_HOST_BY_ADDR'  => @gethostbyaddr( $IP ),
					'PROXY'                => $proxy
					);
    }

	

	function URLModuleSet( $data )
	{
	
		( !self::$URLModuleName ) ? self::$URLModuleName = Form::$sindex : '';

		UserNavigation::Set( 
			array( 
					'URL' => array( 
									self::$URLModuleName => $data
								) 
				) 
		);
	}

	function URLModuleGet( $p = false )
	{
		( !self::$URLModuleName ) ? self::$URLModuleName = Form::$sindex : '';
		$r = self::Get( 'URL' );
		
		if( isset( $r[self::$URLModuleName] ) )
		{
			if( $p )
			{
				if( isset( $r[self::$URLModuleName][$p] ) )
				{
					return $r[self::$URLModuleName][$p];
				}else{
					return false;
				}
			}else{
				return $r[self::$URLModuleName];
			}
		}else{
			return false;
		}
		
		return $r;
	}
	/*
	function URLModuleGetStored( $p )
	{

			UserNavigation::$URLModuleName  = 'ModuleAdminSymposiumsList';
			$this -> reg[$p]                = UserNavigation::URLModuleGet( 'ID' );
			UserNavigation::$URLModuleName  = false;
			if( !$this -> reg[$p] or $this -> reg[$p] == '' )
			{
				$this -> Error = $p;
			}
	}
	*/
	
	function URLModuleGetParameter( $p )
	{
	
		if( !self::$URLModule )
		{
			self::$URLModule = UserNavigation::URLModuleGet( );
		}

		//echo FormValidation::$Results[$p]; exit;
		
		//echo gettype(FormValidation::$Results[$p]); exit;
		if( isset( FormValidation::$Results[$p] ) )
		{
			$v = FormValidation::$Results[$p];
		}else{
			$v = false;
		}

		if( !$v and $v != '0' )
		{
			if( self::$URLModule and isset( self::$URLModule[$p] ) )
			{
				$v = self::$URLModule[$p];
			}else{
				
			}
		}else{
			//self::URLModuleDel( $data );
		}

		return $v;

	}
	
	
	function Browser( $p )
	{
	
		if( !isset( $_SERVER[ $p ] ) )
		{
			return false;
		}
	
		switch( $p )
		{
			default:
				return $_SERVER[ $p ];
			break;
			case 'HTTP_ACCEPT_LANGUAGE':
				return strtolower( substr( $_SERVER[ $p ], 0, 2) );
			break;
		}

		// UserNavigation::Browser( 'HTTP_ACCEPT_LANGUAGE' );
		
	}

}
?>