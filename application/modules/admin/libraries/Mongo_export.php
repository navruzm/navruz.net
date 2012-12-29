<?php

/**
 * Mongo_export library
 *
 * Taken from rockmongo
 * http://code.google.com/p/rock-php/wiki/rock_mongo
 */

class Mongo_export
{
    private $_jsonParams = array();
    private $_paramIndex = 0;


    public function export($var)
    {
        $var = $this->_formatVarAsJSON($var);
        $string = json_encode($var);
        foreach ($this->_jsonParams as $index => $value)
        {
            $string = str_replace("\"" . $this->_param($index) . "\"", $value, $string);
        }
        return json_unicode_to_utf8(json_format($string));
    }

    private function _param($index)
    {
        return "%{MONGO_PARAM_{$index}}";
    }


    private function _formatVarAsJSON($var)
    {
        if (is_scalar($var) || is_null($var))
        {
            return $var;
        }
        if (is_array($var))
        {
            foreach ($var as $index => $value)
            {
                $var[$index] = $this->_formatVarAsJSON($value);
            }
            return $var;
        }
        if (is_object($var))
        {
            $this->_paramIndex++;
            switch (get_class($var))
            {
                case "MongoId":
                    $this->_jsonParams[$this->_paramIndex] = 'ObjectId("' . $var->__toString() . '")';
                    return $this->_param($this->_paramIndex);
                case "MongoDate":
                    $timezone = date_default_timezone_get();
                    date_default_timezone_set("UTC");
                    $this->_jsonParams[$this->_paramIndex] = "ISODate(\"" . date("Y-m-d", $var->sec) . "T" . date("H:i:s.", $var->sec) . ($var->usec / 1000) . "Z\")";
                    date_default_timezone_set($timezone);
                    return $this->_param($this->_paramIndex);
                default:
                    if (method_exists($var, "__toString"))
                    {
                        return $var->__toString();
                    }
                    return '<unknown type>';
            }
        }
    }
}


/**
 * convert unicode in json to utf-8
 *
 * @param string $json string to convert
 * @return string utf-8 string
 */
function json_unicode_to_utf8($json)
{
    $json = preg_replace_callback("/\\\u([0-9a-f]{4})/", create_function('$match', '
		$val = intval($match[1], 16);
		$c = "";
		if($val < 0x7F){        // 0000-007F
			$c .= chr($val);
		} elseif ($val < 0x800) { // 0080-0800
			$c .= chr(0xC0 | ($val / 64));
			$c .= chr(0x80 | ($val % 64));
		} else {                // 0800-FFFF
			$c .= chr(0xE0 | (($val / 64) / 64));
			$c .= chr(0x80 | (($val / 64) % 64));
			$c .= chr(0x80 | ($val % 64));
		}
		return $c;
	'), $json);
    return $json;
}


function json_format($json)
{
    $tab = "  ";
    $new_json = "";
    $indent_level = 0;
    $in_string = false;

    /*
     commented out by monk.e.boy 22nd May '08
     because my web server is PHP4, and
     json_* are PHP5 functions...

        $json_obj = json_decode($json);

        if($json_obj === false)
            return false;

        $json = json_encode($json_obj);
    */
    $len = strlen($json);

    for ($c = 0; $c < $len; $c++)
    {
        $char = $json[$c];
        switch ($char)
        {
            case '{':
            case '[':
                if (!$in_string)
                {
                    $new_json .= $char . "\n" . str_repeat($tab, $indent_level + 1);
                    $indent_level++;
                }
                else
                {
                    $new_json .= $char;
                }
                break;
            case '}':
            case ']':
                if (!$in_string)
                {
                    $indent_level--;
                    $new_json .= "\n" . str_repeat($tab, $indent_level) . $char;
                }
                else
                {
                    $new_json .= $char;
                }
                break;
            case ',':
                if (!$in_string)
                {
                    $new_json .= ",\n" . str_repeat($tab, $indent_level);
                }
                else
                {
                    $new_json .= $char;
                }
                break;
            case ':':
                if (!$in_string)
                {
                    $new_json .= ": ";
                }
                else
                {
                    $new_json .= $char;
                }
                break;
            case '"':
                if ($c > 0 && $json[$c - 1] != '\\')
                {
                    $in_string = !$in_string;
                }
            default:
                $new_json .= $char;
                break;
        }
    }

    return $new_json;
}