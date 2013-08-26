<?php
/** URLWrite Parameters Class
 *
 *   company: JapanCase - Digital Business
 *   autor: Daniel Omine
 *   email: danielomine@gmail.com
 *   website: www.japancase.com
 *   updated: 2013-02-07 15:39:00 - Daniel Omine
 *
 *   Methods
        Make
		Compile
		HeaderLocation
*/

class URLWrite
{

    function Make( $data )
    {

        $r = false;

		// switch url mode between 'normal' and 'mod_rewrite' manually
		// if not set, default is applied instead
		// default value can be set on conf files
        if( !isset( $data['t'] ) )
        {
            $data['t'] = URL_MODE;
        }

		// protocol
        if( isset( $data['p'] ) )
        {
            $data['p'] .= SUBDOMAIN . DOMAIN;
        }else{
			$data['p'] = '';
		}
		
		// base of url
        if( !isset( $data['b'] ) )
        {
            $data['b'] = URL_HREF_BASE;
			if( URL_HREF_BASE != URL_PFS )
			{
				//$data['b'] .= URL_PFS;
			}
        }
		$data['b'] = $data['p'] . $data['b'];

		// url arguments (parameters)
		// k represents parameters names
		// v represents respective values of parameters
        if( !isset( $data['k'] ) or !isset( $data['v'] ) )
        {
            return false;
        }

        switch( $data['t'] )
        {
            default:
            case 'normal':
			    if( !isset( $data['prepend'] ) )
				{
					$r = $data['b'] . PARAM_ARGUMENTOR;
				}else{
					$r = PARAM_SEPARATOR;
				}
				if( $data['k'] and is_array( $data['k'] ) )
				{
					foreach( $data['k'] as $k => $v )
					{

						settype( $data['v'][$k], 'string' ); // prevent 0 value as boolean, null or empty

						if( $k > 0 )
						{
							$r .= PARAM_SEPARATOR;
						}
						if( !empty( $v ) )
						{
							$r .= $v . '=';
						}
						$r .= $data['v'][$k];
					}
				}
				if( ini_get( 'session.use_trans_sid' ) == 1 )
				{
					if( !$data['k'] or !is_array( $data['k'] ) or isset( $data['SID'] ) )
					{
						$r .= PARAM_SEPARATOR;
						$r .= ini_get('session.name') . '=' . session_id();
					}
				}

            break;

            case 'mod_rewrite':

                $r = $data['b'];
				if( $data['k'] and is_array( $data['k'] ) )
				{
					foreach( $data['v'] as $k => $v )
					{

						settype( $v, 'string' ); // prevent 0 value as boolean, null or empty

						if( $k > 0 )
						{
							$r .= URL_PFS;
						}

						$r .= $v;

					}
				}
				if( ini_get( 'session.use_trans_sid' ) == 1 )
				{
					if( $data['k'] and is_array( $data['k'] ) )
					{
						//$r .= URL_PFS;
						//$r .= ini_get('session.name') . '=' . session_id();
						//$r .= URL_PFS;
						$r .= URL_PFS;
						$r .= PARAM_ARGUMENTOR;
						//$r .= 'x';
					}else{
						$r .= PARAM_ARGUMENTOR;
						$r .= 'x';
						$r .= ini_get('session.name') . '=' . session_id();
					}
				}
					
				
            break;
        }
		//echo $r; exit;
        return $r;

        /*
			Usage samples

            URLWrite::Make( array( 't' => 'normal', 'k' => array( PARAM_NAME, 'ID', 'Page' ), 'v' => array( 1, 2, 3 ) ) );

            URLWrite::Make( array( 'k' => array( PARAM_NAME, 'ID', 'Page' ), 'v' => array( 1, 2, 3 ) ) );

        */

    }
	
	function Compile( $k, $v, $add = false )
    {

		if( $add and is_array( $add ) )
		{
			$arr = $add;
		}
		$arr['k'] = $k;
		$arr['v'] = $v;
		if( ini_get( 'session.use_trans_sid' ) == 1 )
		{
			$arr['SID'] = true;
		}

		return URLWrite::Make( $arr );

	}
	
	function HeaderLocation( $k, $v, $add = false )
    {
		//echo 'URL: ' . self::Compile( $k, $v, $add ); exit;
		//var_dump(headers_sent()); exit;
 
		header( 'Location: ' . self::Compile( $k, $v, $add ), true ); exit;
		//URLWrite::HeaderLocation( array( 'k' => array( PARAM_NAME ), 'v' => array( 'adm/Access' ) ) );
	}

}


?>