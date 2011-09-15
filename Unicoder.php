<?php
if (!class_exists('Unicoder')): 

class Unicoder {
	
	/**
	 * Removes accents from Unicode string (case sensitive).
	 * This function supports both precomposed characters and composite characters.
	 * The string should be a valid utf-8 string (no html stuff or whatever)
	**/
	public static function removeAccent($text) {
		static $map = array(
			'a' => array('à','á','ạ','ả','ã','â','ầ','ấ','ậ','ẩ','ẫ','ă','ằ','ắ','ặ','ẳ','ẵ'),
			'e' => array('è','é','ẹ','ẻ','ẽ','ê','ề','ế','ệ','ể','ễ'),
			'i' => array('ì','í','ị','ỉ','ĩ'),
			'o' => array('ò','ó','ọ','ỏ','õ','ô','ồ','ố','ộ','ổ','ỗ','ơ','ờ','ớ','ợ','ở','ỡ'),
			'u' => array('ù','ú','ụ','ủ','ũ','ư','ừ','ứ','ự','ử','ữ'),
			'y' => array('ỳ','ý','ỵ','ỷ','ỹ'),
			'd' => 'đ',
			'A' => array('À','Á','Ạ','Ả','Ã','Â','Ầ','Ấ','Ậ','Ẩ','Ẫ','Ă','Ằ','Ắ','Ặ','Ẳ','Ẵ'),
			'E' => array('È','É','Ẹ','Ẻ','Ẽ','Ê','Ề','Ế','Ệ','Ể','Ễ'),
			'I' => array('Ì','Í','Ị','Ỉ','Ĩ'),
			'O' => array('Ò','Ó','Ọ','Ỏ','Õ','Ô','Ồ','Ố','Ộ','Ổ','Ỗ','Ơ','Ờ','Ớ','Ợ','Ở','Ỡ'),
			'U' => array('Ù','Ú','Ụ','Ủ','Ũ','Ư','Ừ','Ứ','Ự','Ử','Ữ'),
			'Y' => array('Ỳ','Ý','Ỵ','Ỷ','Ỹ'),
			'D' => 'Đ',
			'' => array('̉','̣','̃','̀','́'),
		);
		$result = $text;
		foreach ($map as $char => $chars) {
			$result = str_replace($chars, $char, $result);
		}
		return $result;
	}
	
	/**
	 * Translates unicode characters to a specialized ansii presentation (case insensitive).
	 * Please note that this function was designed works with lower case characters, 
	 * so if your string is not in lower case, make sure to set $inLowerCase to true.
	 * However, the result will always be in lower case. This function supports both
	 * precomposed and composite characters.
	 *
	 * Example of translated presentation:
	 * "Hồ Chí Minh" -> "ho^` chi' minh"
	 * "Cộng hoà xã hội chủ nghĩa Việt Nam" -> "cong^. hoa` xa~ hoi^. chu? nghia~ viet^. nam"
	**/
	public static function asciiAccent($text, $inLowerCase = false) {
		static $map = array(
			'à' => 'a`', 'á' => "a'", 'ạ' => 'a.', 'ả' => 'a?', 'ã' => 'a~', 
			'â' => 'a^', 'ầ' => 'a^`', 'ấ' => "a^'", 'ậ' => 'a^.', 'ẩ' => 'a^?', 'ẫ' => 'a^~',
			'ă' => 'a(', 'ằ' => 'a(`', 'ắ' => "a('", 'ặ' => 'a(.', 'ẳ' => 'a(?', 'ẵ' => 'a(~',
			'è' => 'e`', 'é' => "e'", 'ẹ' => 'e.', 'ẻ' => 'e?', 'ẽ' => 'e~',
			'ê' => 'e^', 'ề' => 'e^`', 'ế' => "e^'", 'ệ' => 'e^.', 'ể' => 'e^?', 'ễ' => 'e^~',
			'ì' => 'i`', 'í' => "i'", 'ị' => 'i.', 'ỉ' => 'i?', 'ĩ' => 'i~',
			'ò' => 'o`', 'ó' => "o'", 'ọ' => 'o.', 'ỏ' => 'o?', 'õ' => 'o~',
			'ô' => 'o^', 'ồ' => 'o^`', 'ố' => "o^'", 'ộ' => 'o^.', 'ổ' => 'o^?', 'ỗ' => 'o^~',
			'ơ' => 'o*', 'ờ' => 'o*`', 'ớ' => "o*'", 'ợ' => 'o*.', 'ở' => 'o*?', 'ỡ' => 'o*~',
			'ù' => 'u`', 'ú' => "u'", 'ụ' => 'u.', 'ủ' => 'u?', 'ũ' => 'u~',
			'ư' => 'u*', 'ừ' => 'u*`', 'ứ' => "u*'", 'ự' => 'u*.', 'ử' => 'u*?', 'ữ' => 'u*~',
			'ỳ' => 'y`', 'ý' => "y'", 'ỵ' => 'y.', 'ỷ' => 'y?', 'ỹ' => 'y~',
			'đ' => 'd-',
			'̉' => '?', '̣' => '.','̃' => '~','̀' => '`','́' => "'",
		);
		static $keys = array();
		if (empty($keys)) $keys = array_keys($map);
		static $marks = array('`',"'",'.','?','~');

		// convert characters
		$text = str_replace($keys, $map, ($inLowerCase ? $text : self::strtolower($text)));
		
		$words = explode(' ',$text);
		$count = count($words);
		for ($i = 0; $i < $count; $i++) {
			foreach ($marks as $mark) {
				$pos = strpos($words[$i], $mark);
				/*
				Note: It's safe to use strpos here because the whole string
				is now ascii characters. We don't need to hurt performance using
				the heavier version functions like mb_strpos, mb_strlen or mb_substr
				*/
				if ($pos !== false AND $pos < strlen($words[$i]) - 1) {
					// this word have a mark somewhere, move it so it will be the last character
					$words[$i] = substr($words[$i], 0, $pos) // the portion before the mark
						. substr($words[$i], $pos + 1) // the portion after the mark
						. $mark; // the mark itself
				}
			}
		}
		
		return implode(' ',$words);
	}
	
	/**
	 * Converts case of characters. 
	 * This function supports two way conversion. To make the string in lower case,
	 * leave the $reverse as false, otherwise, make it true. In theory, this function
	 * should work with both precomposed and composite characters (but I didn't test, sorry).
	**/
	public static function strtolower($text, $reverse = false) {
		static $map = array(
			// lower case (67 chars)
			'à','á','ạ','ả','ã','â','ầ','ấ','ậ','ẩ','ẫ','ă','ằ','ắ','ặ','ẳ','ẵ',
			'è','é','ẹ','ẻ','ẽ','ê','ề','ế','ệ','ể','ễ',
			'ì','í','ị','ỉ','ĩ',
			'ò','ó','ọ','ỏ','õ','ô','ồ','ố','ộ','ổ','ỗ','ơ','ờ','ớ','ợ','ở','ỡ',
			'ù','ú','ụ','ủ','ũ','ư','ừ','ứ','ự','ử','ữ',
			'ỳ','ý','ỵ','ỷ','ỹ',
			'đ',
			// upper case (67 chars)
			'À','Á','Ạ','Ả','Ã','Â','Ầ','Ấ','Ậ','Ẩ','Ẫ','Ă','Ằ','Ắ','Ặ','Ẳ','Ẵ',
			'È','É','Ẹ','Ẻ','Ẽ','Ê','Ề','Ế','Ệ','Ể','Ễ',
			'Ì','Í','Ị','Ỉ','Ĩ',
			'Ò','Ó','Ọ','Ỏ','Õ','Ô','Ồ','Ố','Ộ','Ổ','Ỗ','Ơ','Ờ','Ớ','Ợ','Ở','Ỡ',
			'Ù','Ú','Ụ','Ủ','Ũ','Ư','Ừ','Ứ','Ự','Ử','Ữ',
			'Ỳ','Ý','Ỵ','Ỷ','Ỹ',
			'Đ'
		);
		static $lower = array();
		static $upper = array();
		static $regex = null;
		
		if (empty($lower)) {
			// build the conversion array first time this function runs
			// simply merge ascii characters with unicode characters
			$lower = array_slice($map,0,67);
			$upper = array_slice($map,67);
			for ($i = 65; $i <= 90; $i++) {
				// A = 65, a = 97, Z = 90
				$lower[] = chr($i + 32);
				$upper[] = chr($i);
			}
		}
		
		if (empty($reverse)) {
			// strtolower
			$text = str_replace($upper, $lower, $text);
		} else {
			// strtoupper
			$text = str_replace($lower, $upper, $text);
		}
		
		return $text;
	}
	
	/**
	 * Converts case of characters from lower case to upper case. 
	 * In theory, this function should work with both precomposed and 
	 * composite characters (but I didn't test, sorry).
	**/
	public static function strtoupper($text) {
		return self::strtolower($text,true);
	}
	
	/**
	 * Returns a string with the first character of each word in str capitalized,
	 * if that character is alphabetic.
	 * The definition of a word is any string of characters that is immediately after a space (ascii #32).
	 * This function works with both precomposed and composite characters.
	**/
	public static function ucwords($text) {
		$words = explode(' ', self::strtolower($text));
		$count = count($words);
		
		for ($i = 0; $i < $count; $i++) {
			$sample = mb_substr($words[$i], 0, 1);
			$changed = self::strtoupper($sample);
			if ($changed != $sample) {
				$words[$i] = $changed . mb_substr($words[$i], 1);
			}
		}
		
		return implode(' ',$words);
	}
}

endif;