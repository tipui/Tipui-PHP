<?php
/** Pager Class
 *
 *   company: JapanCase - Digital Business
 *   autor: Daniel Omine
 *   email: danielomine@gmail.com
 *   website: www.japancase.com
 *   updated: 2010-12-25 01:58:00 - Daniel Omine
 *
 *   Methods
        Calc
        GrepURL
        Init

*/

class Pager
{

    static $Result;
    static $Data;
    static $url = false;
    static $uri = false;
	static $param;

    function Calc( $total_regs, $reg_pag, $reg_limit, $pag )
    {

        /**
            $total_regs -> total rows found
            $reg_pag    -> limit rows per page
            $reg_limit  -> limit for show pages number
            $pag        -> current page

            self::$url
        */

        //$reg_limit -= 2;


        if( $total_regs < 1 )
        {
            return false;
        }

    	$pages       = ceil( intval( $total_regs / $reg_pag ) );

		// bug fix
		// quando reg_limit e igual a pages, a paginacao nao avanca para a pagina seguinte, por isso, o adiciona 1, mas nao deve incrementar o valor de $page
    	$total_pages = ( $pages == $reg_limit and $pag != $pages ) ? ($pages + 1) : $pages;

    	$arr['total_pages'] = $total_pages;
    	$arr['total']       = $total_regs;


    	// multi pagination
    	$mult_ini    = 0;
    	$pages_type  = 'multi';

       	if( $total_pages > $reg_limit ){

    		$mult_ini = $pag;
    		if( ( $pag - $reg_limit ) >= 0 ){

                $calc = ( $pag - $reg_limit );

    			$arr[$pages_type]['back']['arrow'] = 5; // << active
    			$arr[$pages_type]['back']['page']  = $calc;

                $arr[$pages_type]['back']['link']  = self::GrepURL( $calc );


    		}else{
    			$arr[$pages_type]['back']['arrow'] = 6; // << inactive
    			$arr[$pages_type]['back']['page']  = false;
    		}

    		if( ( $pag + $reg_limit ) < $pages ){

                $calc = ( $pag + $reg_limit );

    			$arr[$pages_type]['foward']['arrow'] = 7; // >> active
    			$arr[$pages_type]['foward']['page']  = ( $pag + $reg_limit );

                $arr[$pages_type]['foward']['link']  = self::GrepURL( $calc );

    		}else{
    			$arr[$pages_type]['foward']['arrow'] = 8; // >> inactive
    			$arr[$pages_type]['foward']['page']  = false;
    		}

    	}

    	// pages
    	$pages_type = 'pages';

    	if( ( $pag - 1 ) >= 0 )
        {

            $calc = ( $pag - 1 );

    		$arr[$pages_type]['back']['arrow'] = 1; // < active
    		$arr[$pages_type]['back']['page'] = $calc;

            $arr[$pages_type]['back']['link']  = self::GrepURL( $calc );

    	}else{

    		$arr[$pages_type]['back']['arrow'] = 2; // < inactive
    		$arr[$pages_type]['back']['page']  = false;

    	}
/*
echo 'pag: ' . $pag; 
echo '<br>reg_limit: ' . $reg_limit;
echo '<br>mult_ini: ' . $mult_ini;
echo '<br>pages: ' . $pages;
//exit;
*/
		$j = 0;
		$m = round($reg_limit/2);
		if( $pag >= $m and $reg_limit < $pages+1 )
		{
			//echo time(); exit;
			$mult_ini = ( $pag - $m + 1 );
			$d = ($mult_ini + $reg_limit);
			if( $d >= $pages )
			{
				$mult_ini -= ($d - $pages - 1);
			}
		}else{
			$mult_ini = 0;
		}
/*
echo '<hr>pag: ' . $pag; 
echo '<br>reg_limit: ' . $reg_limit;
echo '<br>mult_ini: ' . $mult_ini;
echo '<br>pages: ' . $pages;
*/
		for( $i = $mult_ini; $i <= $pages; $i++ )
        {

			if( $j < $reg_limit ){

				$arr[$pages_type][$j]['page'] = $i + 1;
                $arr[$pages_type][$j]['link'] = self::GrepURL( $i );

				$j++;

			}

		}
//echo ($total_pages); exit;
    	if( ( $pag + 1 ) < $total_pages )
        {

            $calc = ( $pag + 1 );

    		$arr[$pages_type]['foward']['arrow'] = 3; // > active
    		$arr[$pages_type]['foward']['page']  = $calc;

            $arr[$pages_type]['foward']['link']  = self::GrepURL( $calc );

    	}else{

    		$arr[$pages_type]['foward']['arrow'] = 4; // > inactive
    		$arr[$pages_type]['foward']['page']  = false;

    	}
        //print_r( $arr ); exit;

	    //echo $j; exit;
		$arr['page_rows'] = $j; // quantidade de rows na pagina corrent
		
        self::$Result = $arr;
    	return null;
    }

    function GrepURL( $page, $param = false )
    {

	    if( !$param )
		{
			$param = self::$param;
		}

        self::$url['v'][ $param ] = $page;
        $rs = URLWrite::Make( self::$url );

		//print_r( self::$url['prefix']['k'] );  exit;
		//echo self::$url['prefix']['k'][ $param ]; exit;
		//echo $rs; exit;
        return $rs;

    }

    function Init( $param, $page = 0, $page_limit, $page_rows, $url = false )
    {

        define( 'PAGE_LIMIT', $page_limit );
        define( 'PAGE_ROWS', $page_rows );

        $pag_start   = $page * PAGE_ROWS;
		self::$param = $param - 1;

        if( $url )
        {
            self::$url  = $url;
            self::GrepURL( $page );

        }

        define( 'PAGE_NUM', $page ); // current page
        define( 'PAGE_START', $pag_start );

        return null;
    }


    function HTMLData( $PagesCSS = 'PageNumbers' )
    {

        //print_r( self::$Result['pages'] ); exit;

        $v  = self::$Result['pages'];
        unset( self::$Result['pages']['back'] );
        unset( self::$Result['pages']['foward'] );

        $rs  = '';
        $rs .= '<a href="';
        if( isset( $v['back']['link'] ) )
        {
            $rs .= $v['back']['link'];
        }else{
            $rs .= 'javascript:;';
        }
        $rs .= '" class="null">';
        //$rs .= '<img src="' . URL_HREF_BASE . 'images/Pager/arrow' . $v['back']['arrow'] . '.gif" border="0"></a>';
		$rs .= ' <div id="back_arrow' . $v['back']['arrow'] . '"></div></a>';

            foreach( self::$Result['pages'] as $k => $vl )
            {
                $rs .= '<a href="';
                $rs .= $vl['link'];
                $rs .= '"';

                if( $PagesCSS )
                {
                    $rs .= ' class="' . $PagesCSS;
					if( $vl['page'] - 1 == PAGE_NUM )
					{
						$rs .= 'Current';
					}
					$rs .= '"';
                }

                $rs .= '>';
                $rs .= $vl['page'];
                $rs .= '</a>';
            }

        $rs .= '<a href="';
        if( isset( $v['foward']['link'] ) )
        {
            $rs .= $v['foward']['link'];
        }else{
            $rs .= 'javascript:;';
        }
        $rs .= '" class="null">';
		$rs .= '<div id="foward_arrow' . $v['foward']['arrow'] . '"></div></a>';
        //$rs .= '<img src="' . URL_HREF_BASE . 'images/Pager/arrow' . $v['foward']['arrow'] . '.gif" border="0"></a>';

        self::$Data = $rs;
        unset( $rs );
        unset( $v );

        return null;

    }

}


?>