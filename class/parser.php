<?php
class parser {

	public static $l_delim = '{';
	public static $r_delim = '}';
	public static $object;

	/**
	 *  Parse a template
	 *
	 * Parses pseudo-variables contained in the specified template view,
	 * replacing them with the data in the second param
	 *
	 * @access	public
	 * @param	string
	 * @param	array
	 * @param	bool
	 * @return	string
	 */
	public static function parse($template, $data, $return = FALSE)
	{
		return self::_parse($template, $data, $return);
	}

	// --------------------------------------------------------------------

	/**
	 *  Parse a String
	 *
	 * Parses pseudo-variables contained in the specified string,
	 * replacing them with the data in the second param
	 *
	 * @access	public
	 * @param	string
	 * @param	array
	 * @param	bool
	 * @return	string
	 */
	public static function parse_string($template, $data, $return = FALSE)
	{
		return self::_parse($template, $data, $return);
	}

	// --------------------------------------------------------------------

	/**
	 *  Parse a template
	 *
	 * Parses pseudo-variables contained in the specified template,
	 * replacing them with the data in the second param
	 *
	 * @access	public
	 * @param	string
	 * @param	array
	 * @param	bool
	 * @return	string
	 */
	public static function _parse($template, $data, $return = FALSE)
	{
		if ($template == '')
		{
			return FALSE;
		}

		foreach ($data as $key => $val)
		{
			if (is_array($val))
			{
				$template = self::_parse_pair($key, $val, $template);
			}
			else
			{
				$template = self::_parse_single($key, (string)$val, $template);
			}
		}

		return $template;
	}

	// --------------------------------------------------------------------

	/**
	 *  Set the left/right variable delimiters
	 *
	 * @access	public
	 * @param	string
	 * @param	string
	 * @return	void
	 */
	public static function set_delimiters($l = '{', $r = '}')
	{
		self::$l_delim = $l;
		self::$r_delim = $r;
	}

	// --------------------------------------------------------------------

	/**
	 *  Parse a single key/value
	 *
	 * @access	private
	 * @param	string
	 * @param	string
	 * @param	string
	 * @return	string
	 */
	public static function _parse_single($key, $val, $string)
	{
		return str_replace(self::$l_delim.$key.self::$r_delim, $val, $string);
	}

	// --------------------------------------------------------------------

	/**
	 *  Parse a tag pair
	 *
	 * Parses tag pairs:  {some_tag} string... {/some_tag}
	 *
	 * @access	private
	 * @param	string
	 * @param	array
	 * @param	string
	 * @return	string
	 */
	public static function _parse_pair($variable, $data, $string)
	{
		if (FALSE === ($match = self::_match_pair($string, $variable)))
		{
			return $string;
		}

		$str = '';
		foreach ($data as $row)
		{
			$temp = $match['1'];
			foreach ($row as $key => $val)
			{
				if ( ! is_array($val))
				{
					$temp = self::_parse_single($key, $val, $temp);
				}
				else
				{
					$temp = self::_parse_pair($key, $val, $temp);
				}
			}

			$str .= $temp;
		}

		return str_replace($match['0'], $str, $string);
	}

	// --------------------------------------------------------------------

	/**
	 *  Matches a variable pair
	 *
	 * @access	private
	 * @param	string
	 * @param	string
	 * @return	mixed
	 */
	public static function _match_pair($string, $variable)
	{
		if ( ! preg_match("|" . preg_quote(self::$l_delim) . $variable . preg_quote(self::$r_delim) . "(.+?)". preg_quote(self::$l_delim) . '/' . $variable . preg_quote(self::$r_delim) . "|s", $string, $match))
		{
			return FALSE;
		}

		return $match;
	}

}
