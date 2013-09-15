<?php

/**
* @class  IsJapaneseUTF8
* @file   IsJapaneseUTF8.php
* @brief  IsJapaneseUTF8 strings functions.
* @date   2013-09-15 02:54:00
* @license http://opensource.org/licenses/GPL-3.0 GNU Public License
* @company: Tipui Co. Ltda.
* @author: Daniel Omine <omine@tipui.com>
* @updated: 2013-09-15 02:54:00
*/

namespace Tipui\Builtin\Libs\Strings;

class IsJapaneseUTF8
{

	/**
	* (boolean) Detect japanese chars.
	*/
	public function Exec( $str, $kanji = true, $hiragana = true, $katakana = true, $japaneseAlphaNum = true, $alphaNum = false, $forceUTF8 = false )
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

}