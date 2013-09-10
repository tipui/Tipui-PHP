<?php

/**
* @class  Strings
* @file   Strings.php
* @brief  Strings functions.
* @date   2013-03-18 18:50:00
* @license http://opensource.org/licenses/GPL-3.0 GNU Public License
* @company: Tipui Co. Ltda.
* @author: Daniel Omine <omine@tipui.com>
* @updated: 2013-09-09 19:28:00
*/

namespace Tipui\Builtin\Libs;

class Strings
{


    public static function StrLen( $str, $charset = 'UTF-8' )
	{

        return mb_strlen( $str, $charset );

    }

    public static function Trim( $str )
	{

        //return trim( $str );
		return preg_replace( '/(^\s+)|(\s+$)/us', '', $str );
    
    }

    public static function ChunkSpaces( $str )
	{

		return preg_replace('!\s+!', ' ', $str);
    
    }

    public static function NumbersOnly( $str, $float = false )
    {
        if( !is_array( $str ) )
        {
			$r = '';
			if( $float )
			{
				$r   = '.';
				$str = str_replace( ',', $r, $str );
			}
            return preg_replace( '#[^0-9' . $r . ']#', '', mb_convert_kana( $str, 'n' ) );
        }
        return '';
    }

    public static function ValidMailAddress( $str )
	{

		//$str = trim( $str );

        $rule  = '/^([0-9,a-z,A-Z,_,-,.]+)([.,_,-]([0-9,a-z,A-Z,_,-,.]+))';
        $rule .= '*[@]([0-9,a-z,A-Z]+)([.,-]([0-9,a-z,A-Z]+))';
        $rule .= '*[.]([0-9,a-z,A-Z]){2}([0-9,a-z,A-Z])?$/';

        //ereg
        if( preg_match( $rule, $str ) ){
            return true;
        }else{
            return false;
        }

    }

	public static function LimitStr( $str = '', $limit = 10, $dots = '...' )
	{
		if( mb_strlen( $str ) <= $limit )
		{
			return $str;
		}

		return mb_substr( $str, 0, $limit ) . $dots;
	}

    public static function RomanAlphabet( $mode = 'auto' )
    {

        return self::ParseRange( 'A', 'Z', $mode );

    }

    public static function ParseRange( $start = false, $end = false, $mode = 'auto' )
    {

        if( !$start or !$end )
        {
            return false;
        }else{

            $rs = range( $start, $end );

            // retorna o "range" original, com índices numéricos iniciados por 0
            if( $mode == 'auto' )
            {
                return $rs;
            }

            // atribui valores iguais aos índices, se $mode for diferente de "auto"
            foreach( $rs as $k => $v )
            {
                $arr[$v] = $v;
            }
            unset( $rs );
    
            return $arr;

        }


    }
	
	public static function MoneyFormat( $n = false, $d = false )
	{
        $r = Strings::NumbersOnly( $n );
		//echo Strings::NumbersOnly( $n ); exit;
        if( $r != '' ){
            $d ? $c = 2 : $c = 0;
            $r = number_format( $r, $c, ', ', '.' );
        }else{
            unset( $r );
            $r = false;
        }
        return $r;

        /**
        http://php.net/number_format

        SAMPLE
            return array of results
            0 => result
            1 => original string

            $d parameter is boolean
            -true: return decimal value
            -false: return integer value
            ex: $n = 200, $d = true, return 200,00
            ex: $n = 200, $d = false, return 200
            usage: 
            echo Strings::MoneyFormat( 1000, true );
            echo Strings::MoneyFormat( 1000 );
        */
    }

	public static function isJapaneseUTF8( $str, $kanji = true, $hiragana = true, $katakana = true, $japaneseAlphaNum = true, $alphaNum = false, $forceUTF8 = false )
	{

		// google: php 正規表現 漢字
		//http://pentan.info/php/reg/is_kanji.html
		//http://www.se-land.com/chapter.php?cha_id=cha0000000488
		//http://phpspot.org/blog/archives/2005/11/php_17.html

		$patterns['kanji']    = ( $kanji ) ? '[一-龠]' : '';
		$patterns['hiragana'] = ( $hiragana ) ? '+|[ぁ-ん]' : '';
		$patterns['katakana'] = ( $katakana ) ? '+|[ァ-ヴー]' : '';
		$patterns['alphaNum'] = ( $alphaNum ) ? '+|[a-zA-Z0-9]' : '';
		$patterns['japaneseAlphaNum'] = ( $japaneseAlphaNum ) ? '+|[ａ-ｚＡ-Ｚ０-９]' : '';

		$forceUTF8 = ( $forceUTF8 ) ? 'U' : '';


		if( preg_match( '{' . $patterns['kanji'] . $patterns['hiragana'] . $patterns['katakana'] . $patterns['alphaNum'] . $patterns['japaneseAlphaNum'] . '+}' . $forceUTF8, $str, $matches ) )
		{
			//print_r( $matches ); exit;
			return $matches;
		}else{
			return false;
		}
	}
	
	public static function Escape( $str, $escape = false, $exceptions = false )
	{
		//print_r( $escape ); exit;
		//echo time(); exit;

		//$str = urldecode( $str );

		if( is_array( $escape ) or !$escape )
		{
			if( !$escape or in_array( 'strip_tags', $escape ) )
			{
				//echo 'strip_tags: ' . time(); exit;
				$str = self::StripTags( $str, $exceptions );
			}


			if( !$escape or in_array( 'trim', $escape ) )
			{

				//echo 'php: ' . time(); exit;
				$str = self::Trim( $str );
			}

			if( !$escape or in_array( 'php', $escape ) )
			{

				//echo 'php: ' . time(); exit;
				$str = str_ireplace( '<?php ', '&#60;?php ', $str );
			}


			if( !$escape or in_array( 'form', $escape ) )
			{
				$f = array( '<textarea',     '</textarea',     '<form',     '</form',     '<input' );
				$t = array( '&#60;textarea', '&#60;/textarea', '&#60;form', '&#60;/form', '&#60;input' );
				$str = str_ireplace( $f, $t, $str );
			}


			if( !$escape or in_array( 'script', $escape ) )
			{
				$f = array( '<script',     '</script' );
				$t = array( '&#60;script', '&#60;/script' );
				$str = str_ireplace( $f, $t, $str );
			}
			
			if( !$escape or in_array( 'headers', $escape ) )
			{
				$f = array( '<html',     '</html',     '<head',     '</head',     '<body',     '</body' );
				$t = array( '&#60;html', '&#60;/html', '&#60;head', '&#60;/head', '&#60;body', '&#60;/body' );
				$str = str_ireplace( $f, $t, $str );
			}

			if( $escape and in_array( 'htmlspecialchars', $escape ) )
			{
				$str = htmlspecialchars( $str, ENT_QUOTES );
			}

		
		}else{
			if( $escape == 'quotes' )
			{
				$str = str_replace( '"', '&quot;', $str );
				//echo $str; //exit;
			}
			if( $escape == 'url_slash' )
			{
				//echo 'php: ' . time(); exit;
				$str = str_ireplace( '_-_', '/', $str );
				//echo $str; exit;
			}
			if( $escape == 'slash_url' )
			{
				//echo 'php: ' . time(); exit;
				$str = str_ireplace( '/', '_-_', $str );
				//echo $str; exit;
			}


		}


		return $str;
		
		/*
			Strings::Escape( 'foo bar', array( 'php', 'form' ) );
			Strings::Escape( 'foo bar', array( 'php' ) );
			Strings::Escape( 'foo bar', array( 'php', 'form', 'strip_tags' ) );
			Strings::Escape( 'foo bar', false ); // apply all filters
		*/
	}

	
    public static function StripTags( $v, $allow = '' )
    {
        return strip_tags( $v, $allow );
    }


    // add an element at the beginning of an array
    public static function ArrayRpush( $arr, $item )
    {
      return array_pad( $arr, -( count( $arr ) + 1 ), $item );
    }

	public static function RandomChar( )
	{
		$c = range( 'A', 'Z' );
		$n = range( '0', '9' );
		$l = count( $c )-1;
		$i = count( $n )-1;
		$p  = $c[rand( 1, $l )] . $n[rand( 1, $i )] . $c[rand( 1, $l )];
		$p .= $c[rand( 1, $l )] . $n[rand( 1, $i )] . $c[rand( 1, $l )];
		$p .= $n[rand( 1, $i )];
		return $p;
	}
	
	public static function Highlight( $str, $s, $n = array( '<b>', '</b>' ) )
	{
		//echo 'aaa: ' . $s; exit;
		return str_ireplace( $s, $n[0] . $s . $n[1], $str );
		//return preg_replace("/($s)/i", $n[0] . '$1' . $n[1], $str);
	}

	public static function StrBr( $str, $force = false )
	{
		if( !$force )
		{
			return nl2br( $str );
		}else{
			$str = nl2br( $str );
			$str = str_replace( array("
","\r\n","\r","\n"), '<br />', $str );
			return $str;
		}
	}

	public static function SEOFilter( $str )
	{
		$f = array( '%', '　', ' ', '/', '~', 'á', 'é', 'í', 'ó', 'ú', 'â', 'ê', 'î', 'ô', 'û', 'à', 'è', 'ì', 'ò', 'ù', 'ã', 'õ', 'ç', 'ñ', 'Á', 'É', 'Í', 'Ó', 'Ú', 'Â', 'Ê', 'Î', 'Ô', 'Û', 'À', 'È', 'Ì', 'Ò', 'Ù', 'Ã', 'Õ', 'Ç', 'Ñ' );
		$t = array( '', '_', '_', '-', '_', 'a', 'e', 'i', 'o', 'u', 'a', 'e', 'i', 'o', 'u', 'a', 'e', 'i', 'o', 'u', 'a', 'o', 'c', 'n', 'A', 'E', 'I', 'O', 'U', 'A', 'E', 'I', 'O', 'U', 'A', 'E', 'I', 'O', 'U', 'A', 'O', 'C', 'N' );
		$str = str_replace( $f, $t, $str );
		return $str;
	}

	public static function SEO_strip( $str )
    {
		return self::SEOFilter( trim( substr( $str, 0, Register_Strings::SEO_STR_MAX_LENGTH ) ) );
    }

	public static function WordBreak( $str, $limit = 15, $escape = false, $break = ' ' )
    {
		$str = mb_ereg_replace('#(\S{' . $limit . ',})#e', "chunk_split('$1', " . $limit . ", '" . $break . "')", $str );
		if( $escape ){
		return self::Escape( $str, false );
		}
		return $str;
    }

	public static function alphaID($in, $to_num = false, $pad_up = false, $passKey = null)
	{
		$index = "abcdefghijklmnopqrstuvwxyz0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ";
		if ($passKey !== null) {
			// Although this function's purpose is to just make the
			// ID short - and not so much secure,
			// with this patch by Simon Franz (http://blog.snaky.org/)
			// you can optionally supply a password to make it harder
			// to calculate the corresponding numeric ID

			for ($n = 0; $n<strlen($index); $n++) {
				$i[] = substr( $index,$n ,1);
			}

			$passhash = hash('sha256',$passKey);
			$passhash = (strlen($passhash) < strlen($index))
				? hash('sha512',$passKey)
				: $passhash;

			for ($n=0; $n < strlen($index); $n++) {
				$p[] =  substr($passhash, $n ,1);
			}

			array_multisort($p,  SORT_DESC, $i);
			$index = implode($i);
		}

		$base  = strlen($index);

		if ($to_num) {
			// Digital number  <<--  alphabet letter code
			$in  = strrev($in);
			$out = 0;
			$len = strlen($in) - 1;
			for ($t = 0; $t <= $len; $t++) {
				$bcpow = bcpow($base, $len - $t);
				$out   = $out + strpos($index, substr($in, $t, 1)) * $bcpow;
			}

			if (is_numeric($pad_up)) {
				$pad_up--;
				if ($pad_up > 0) {
					$out -= pow($base, $pad_up);
				}
			}
			$out = sprintf('%F', $out);
			$out = substr($out, 0, strpos($out, '.'));
		} else {
			// Digital number  -->>  alphabet letter code
			if (is_numeric($pad_up)) {
				$pad_up--;
				if ($pad_up > 0) {
					$in += pow($base, $pad_up);
				}
			}

			$out = "";
			for ($t = floor(log($in, $base)); $t >= 0; $t--) {
				$bcp = bcpow($base, $t);
				$a   = floor($in / $bcp) % $base;
				$out = $out . substr($index, $a, 1);
				$in  = $in - ($a * $bcp);
			}
			$out = strrev($out); // reverse
		}

		return $out;
	}

	public static function EscapeJS( $str )
	{
		//return str_replace( '"', '&quot;', str_replace( "'", "\'", $str ) );
		return str_replace( '"', '&#34;', str_replace( "'", "&#39;", $str ) );
	}
	
	public static function IDRewrite( $str )
	{
		// catch the id parameter url like "25-www.amazon.co.jp" and outputs "25"
		$str = explode( '-', $str . '-' );
		return $str[0];
	}
}