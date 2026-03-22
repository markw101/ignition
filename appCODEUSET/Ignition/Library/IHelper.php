<?php

/*********************************************************************
    AUTHOR:      Mark E. Williamson
    FILE:        IHelper.php
    VERSION:     See BaseController.php
    DESCRIPTION: General application helper functions
    COPYRIGHT:   2022
    FIRST REV:   16 Mar 2022
    LICENSE:     MIT

*********************************************************************/

// ----- match $needle in $haystack1 and return corresponding column element in $haystack2.  Use | postfix delimeter
if (! function_exists('ColMatch'))
{
    function ColMatch($needle, $hayStack1, $hayStack2)
    {
        // ----- init, remove leading separator
        $hayStack1 = (substr($hayStack1, 0, 1) == '|' ? substr($hayStack1, 1) : $hayStack1);
        $hayStack2 = (substr($hayStack2, 0, 1) == '|' ? substr($hayStack2, 1) : $hayStack2);

        // ----- find index in 1
        $match = strpos($hayStack1, $needle . '|');
        $match = substr_count(substr($hayStack1, 0, $match + 1), '|');

        // ----- return column
        return ($match === FALSE ? FALSE : GetField($hayStack2, $match + 1, '|'));

    }
}

// ----- simple to use exit screen in case of error.  allows to include a message
if (! function_exists('ExitFlashGuest'))
{
    function ExitFlashGuest($exitMessage, $url = FALSE, $warnLevel = FALSE)
    {

        $redirectString  = json_encode(['title' => lang('base.program') . ' ' . ($warnLevel ? lang('base.warning') : lang('base.error')),
            'body' => $exitMessage,
            'button' => lang('base.continue'),
            'action' => ($url ? BaseURL('/' . $url) : BaseURL())]);

        // ----- check for buffer overflow
        if (strlen($redirectString) > 500)
            $redirectString = substr($redirectString, 0, 500);

        IRedirect('system/result/?body=' . rawurlencode($redirectString));

    }
}

// ----- simple to use exit screen in case of error.  allows to include a message
if (! function_exists('ExitFlash'))
{
    function ExitFlash($exitMessage, $url = FALSE, $warnLevel = FALSE)
    {

        SetFlash(['title' => lang('base.program') . ' ' . ($warnLevel ? lang('base.warning') : lang('base.error')),
            'body' => $exitMessage,
            'button' => lang('base.continue'),
            'action' => ($url ? BaseURL('/' . $url) : BaseURL('/' . HOMEURL))]);

        IRedirect(APPURL . '/result');

    }
}

if (! function_exists('e'))
{
    function e($echoLabel, $echoVal = '')
    {

        if (is_array($echoLabel))
            echo print_r($echoLabel);
        else
            echo $echoLabel;

        if (is_array($echoVal)) {
            br();
            echo print_r($echoVal);
            br();
        } else 
            echo ': ' . $echoVal . _br();

	}
}

if (! function_exists('BaseURL'))
{
    function BaseURL($urlString = '', $langCode = '')
    {
		$langCode = ($langCode == '' ? $GLOBALS['LANGCODE'] : $langCode);
		return HTTPPROT . "://" . (AUTOLANG ? $langCode . '.' : '') . SERVERBASENAME . $urlString;

	}
}

// ----- check and see if one value exists within a list of values dilimited by |
//
if (! function_exists('InList'))
{

    function InList($haystack, $needle)
    {

		if ($needle == '')
			return false;

        if ((strpos($haystack, $needle . "|") === false))
            return false;
        else
            return true;

    }
}

// ----- check and see if up to 7 values (needles) exists within a list of values (haystack), 
//       no delimiter required.  does not work with boolean values and needle1 must not be blank
//
if (! function_exists('ValSet'))
{

    function ValSet($haystack, $needle1, $needle2 = FALSE, $needle3 = FALSE, $needle4 = FALSE, $needle5 = FALSE, $needle6 = FALSE, $needle7 = FALSE, $needle8 = FALSE)
    {

		if ($needle1 == '' || !$haystack)
			return FALSE;

        // ------ set up loop to check all needles
        for ($count = 1; $count < 9; $count ++) {

            $needles = 'needle' . $count;

            if ($$needles === FALSE)
                return FALSE;

            if ((strpos($haystack, $$needles) === FALSE))
                continue;
            else
                return TRUE;

        }

        return FALSE;
    }
}

/// ----- check and see if one key VALUE exists (isset) within a list of array key+values
//
if (! function_exists('ValSetArray'))
{
    function ValSetArray($list, $value)
    {

		foreach($list as $fieldName => $fieldValue) {

			if ($fieldValue === $value)
				return true;
		}

		return false;

    }
}

// ----- given a single row (record), multi column string, and delimiter return the
//       requested col numbered field.  Col counts from 1
if (! function_exists('GetField'))
{

    function GetField($row, $col, $sep)
    {
        // ----- init
        $start = 0;
        $os = strpos($row, $sep);

        if ($os === FALSE || !$col)
            return FALSE;

        // ----- check for first record
        if ($col == 1)
            return substr($row, 0, $os);

        // ----- setup loop to set markers around requested col field
        for ($step = 2; $step <= $col; $step ++)
        {	$os ++;
            if ($step == $col)
                $start = $os;
            $os = strpos($row, $sep, $os);
        }
    
        // ----- return index string of record
        return substr($row, $start, $os - $start);
    }

}

// ----- given a single row, return a count of fields delimted by sep
//       returns real col count, starting with 1
//       if accidentally begin list with delimiter, corrects
//
if (! function_exists('FieldCount'))
{

    function FieldCount($row, $sep)
    {
    
        // ----- check for empty list
        if (strlen($row) == 0)
            return FALSE;

        // ----- check for begins with delimiter, assumes every field ends with delimiter
        if (substr($row, 0, 1) == $sep)
            $subtractOne = 1;
        else 
            $subtractOne = 0;

        // ----- get field count
        return substr_count($row, $sep) - $subtractOne;
    }
}

if (! function_exists('SpecialBaseURL'))
{
    function SpecialBaseURL($urlString = '', $langCode = '', $baseName = '')
    {
		$langCode = ($langCode == '' ? $GLOBALS['LANGCODE'] : $langCode);
		return HTTPPROT . "://" . (AUTOLANG ? $langCode . '.' : '') . $baseName . $urlString;

	}
}

if (! function_exists('_br'))
{
    function _br($count = 1) 
    {
        $retVal = '';

        for ($counter = 1; $counter <= $count; $counter++)
            $retVal .= "<br>";

        return $retVal;
    }

}

if (! function_exists('br'))
{
    function br($count = 1) 
    {
        $retVal = '';

        for ($counter = 1; $counter <= $count; $counter ++)
            $retVal .= "<br>";

        echo $retVal;
    }

}

if (! function_exists('_sp'))
{
    function _sp($count = 1) 
    {
        $retVal = '';

        for ($counter = 1; $counter <= $count; $counter++)
            $retVal .= "&nbsp;";

        return $retVal;
    }

}

if (! function_exists('sp'))
{
    function sp($count = 1) 
    {
        $retVal = '';

        for ($counter = 1; $counter <= $count; $counter ++)
            $retVal .= "&nbsp;";

        echo $retVal;
    }

}

if (! function_exists('IRedirect'))
{
    function IRedirect(string $url, $includeBase = true, $sendReferrer = false)
    {

        if ($includeBase)
            $url = BaseURL('/' . $url);

        if ($sendReferrer) {
            // ----- send the page requested 
            $uri = new \CodeIgniter\HTTP\URI(fixed_url());
			$sysError = new \Ignition\System\MC\Controller;
			$sysError->error404($uri);
			return;

        }

		header('Location: ' . $url);
		die();

    }

}

if (! function_exists('GetUserEN'))
{
    // ----- return session user id (from user record)
    function GetUserEN() {
        // ----- get EN from session
        $session = service('session');
        if ($session->has('session_data'))
            return ($_SESSION['session_data']['user_peernum'] == 'ANONYMOUS' ? $_SESSION['session_data']['user_accountnum'] : $_SESSION['session_data']['user_peernum']);
        else
            return false;
    }
}

if (! function_exists('GetUserID'))
{
    // ----- return session user id (from user record)
    function GetUserID() {
        // ----- get userid from session
        $session = service('session');
        if ($session->has('session_data'))
            return $_SESSION['session_data']['user_id'];
        else
            return false;
    }
}

if (! function_exists('FlipSlashes'))
{
    function FlipSlashes($pathName)
    {

        // ----- setup loop to flip slashes
        while(true) {
            // ----- find next slash
            $pos = strpos($pathName, "/");

            if ($pos === false)
                break;

            $pathName = substr_replace($pathName, '\\', $pos, 1);

        }

        return $pathName;

    }
}

if (! function_exists('RandomName'))
{
    function RandomName($digits = 10)
    {
        // ----- init
        $retVal = '';
        $symbolSet = "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
        for ($count = 0; $count < $digits; $count ++)
            $retVal .=  substr($symbolSet, random_int(0, 35), 1);

        return $retVal;

    }
}

// ----- good for password generation.  adds 3 non alphanum chars to end
if (! function_exists('RandomNamePlus'))
{
    function RandomNamePlus($digits = 10)
    {
        // ----- init
        $retVal = '';
        $symbolSet = "ABCDEFGHIJKLMNPQRTUVWXYZ123456789";
        for ($count = 0; $count < $digits - 3; $count ++)
            $retVal .=  substr($symbolSet, random_int(0, 32), 1);

        $symbolSet = ".,;:+-_=^%$";
        for ($count = 0; $count < 3; $count ++)
            $retVal .=  substr($symbolSet, random_int(0, 10), 1);

        return $retVal;

    }
}

if (! function_exists('DebugStore'))
{
    function DebugStore($storeSession, $varName = 'defaultnamerequested')
    {

        // ----- check for default name requested
        if ($varName == 'defaultnamerequested')
            $varName = RandomName();

        // ----- init
        $_SESSION[$varName] = $storeSession;

    }
}

if (! function_exists('MarkUp'))
{
    function MarkUp($textData, $justifyText = false, $noCR = false)
    {
        // ----- init
        $startOS = 0;
        $dataLen = strlen($textData);

        // ----- check for justify
        if ($justifyText)
            echo '<div style="text-align: justify; text-justify: inter-word; color:' . BLOGFONTCOLOR . '; line-height:150%;">';

		// ----- if no need to insert CR, then just dump text 
		if ($noCR)
			echo print_r($textData);
		else {

	        for($count = 0; $count < $dataLen; $count ++)
	        {
	            // ----- check for found a return 
//	            if (substr($textData, $count, 2) == "\r\n") { << would be more efficient
	            if (substr($textData, $count, 1) == "\r" && substr($textData, $count + 1, 1) == "\n") {

                    // ----- check for programming content and echo
	                echoLine(substr($textData, $startOS, $count - $startOS + 1) . ($noCR ? "" : "<br><br>"));
	                //echo substr($textData, $startOS, $count - $startOS + 1) . ($noCR ? "" : "<br><br>");
	                $count += 2;
	                $startOS = $count;
	            }

	        }

			// ----- check for never found a cr 
			if ($startOS == 0)
				echo $textData;
			else {
				// ----- check to see if did not end on cr, must flush final paragraph
				if (substr($textData, $dataLen, 1)  != "\n")
					echo substr($textData, $startOS, $dataLen - $startOS);
			}
		}

        // ----- check for justify
        if ($justifyText)
            echo '</div>';

    }
}

// ----- given a line of text to output, seek programming input and replace values as needed and output
//
if (! function_exists('echoLine'))
{
    function echoLine($textData)
    {

        // ----- explode string by ignition command delimeter
        $textDataExp = explode('___COMMAND___', $textData);

        if (count($textDataExp) == 1) {
            echo $textData;
            return;
        }

        // -----  if search string is found in explode, then will return 2 or more array elements.
        echo $textDataExp[0];
        $arraySize = count($textDataExp);

        for ($count = 1; $count < $arraySize; $count ++) {

            $element = $textDataExp[$count];

            // ----- locate command + params
            $extractStart = strpos($element, '___COMMAND___');
            $extractStop = strpos($element, ');');

            // ----- do look up of command
            $paramsOS = strpos($element, '(') + 1;
            $params = quotetoarray(substr($element, $paramsOS, $extractStop));
            $command = substr($element, 0, $paramsOS - 1);

            switch($command) {

                case ('lang') : 
                    echo lang($params[0]);
                    break;

                default : 
                    halt('Error in ___COMMAND___ entry in text. Command not found: ' . $command);

            }

            echo substr($element, $extractStop + 2);
        }

        return;

    }

}

// ----- given multi line string, return lineNumber (cr/lf delimited)
//       taylored specifically for the wallet controller as this is faster than exploding()
//       because only need the first item in a long string
if (! function_exists('GetLine'))
{
    function GetLine($textData, $lineNumber, $delimiter)
    {
        // ----- init
        $dataLen = strlen($textData);
        $lineCount = 1;
        $startOS = 0;
        $lenDelimit = strlen($delimiter);

        for ($count = 0; $count < $dataLen; $count ++)
        {

            // ----- check for found a return 
            if (substr($textData, $count, $lenDelimit) == $delimiter) {

                if ($lineCount == $lineNumber)
                    return substr($textData, $startOS, $count);

                $count += $lenDelimit;
                $lineCount += 1;
                $startOS = $count;
            }

        }

        // ----- did not hit the line
        return FALSE;

    }
}

// ----- given an array of article tag fields, return array with tallies for every unique tag
if (! function_exists('TotalTags'))
{
    function TotalTags($blogArticles)
    {
        // ----- init
        $returnTags = [];

		// ----- check for no data 
		if (empty($blogArticles)) {
			return $returnTags;
		}

        // ----- setup loop to count matching tags
        foreach($blogArticles as $tagField) {

            $tagField = ($tagField['bloglang_tags'] ?? "");   // if tags set to null get error if do not do this

			$tagField = quotetoarray($tagField);

			// ----- setup loop to tally every tag
			foreach ($tagField as $tag) {

				if (isset($returnTags[$tag]))
					$returnTags[$tag] += 1;
				else 
					$returnTags[$tag] = 1;
			}

        }

        return $returnTags;

    }
}

// ----- given a string with single quoted entries, converts to array, one arra entry per quoted
//
if (! function_exists('array2string'))
{
    function array2string($array, $delimiter = '|', $showKeys = FALSE)
    {
        // ----- init
        $returnString = $delimiter;

        foreach($array as $key => $value) {

            if ($showKeys)
                $returnString .= $key . ": ";

            // ----- check for nested arrays
            if (is_array($value)) {
                $returnString .= array2string($value, $delimiter, $showKeys);
                continue;
            }

            $returnString .= $value . $delimiter;

        }

        return $returnString;
    }

}

// ----- given a string with single quoted entries, converts to array, one arra entry per quoted
//
if (! function_exists('quotetoarray'))
{
    function quotetoarray($quotedText)
    {
        // ----- init
		$textLen = strlen($quotedText);
		$entryStart = 0;
        $returnArray = [];

        // ----- setup loop to find quoted entries and add to array
        do {

	        // ----- set start/stop markers
	        $entryStart = strpos($quotedText, "'", $entryStart);
	        if ($entryStart === false)
	            break;
	        $entryStart += 1;

			if ($entryStart >= $textLen)
				break;

	        $entryStop = strpos($quotedText, "'", $entryStart + 1);

	        if ($entryStop === false || !($entryStop > $entryStart))
	            break;

	        // ----- extract tag
	        $returnArray[] = strtolower(substr($quotedText, $entryStart, $entryStop - $entryStart));

	        $entryStart = $entryStop + 1;

        } while(true);

		return $returnArray;

	}
}

// ----- given a text file with line feeds, convert each line to an array
//       turning text into array appears to remove all leading/trailing spaces ea line
//
if (! function_exists('texttoarray'))
{
    function texttoarray($textData)
    {
        // ----- init
        $startOS = 0;
        $dataLen = strlen($textData);
        $returnArray = [];

		// ----- loop thru lines of text 
        for($count = 0; $count < $dataLen; $count ++)
        {
            // ----- check for found a return 
            if (substr($textData, $count, 1) == "\r" || substr($textData, $count, 1) == "\n") {
                if (substr($textData, $count + 1, 1) == "\r" || substr($textData, $count + 1, 1) == "\n")
                    $count += 1;
                $returnArray[] = substr($textData, $startOS, $count - $startOS + 1);
                $count += 1;
                $startOS = $count;
            }

        }

		// ----- check for never found a cr 
		if ($startOS == 0)
			$returnArray[] =  $textData;
		else {
			// ----- check to see if did not end on cr, must flush final paragraph
			if (substr($textData, $dataLen, 1)  != "\n")
				$returnArray[] = substr($textData, $startOS, $dataLen - $startOS);
		}

		return $returnArray;

	}
}

// ----- given URL string, return the geo/lang locale contained therein
if (! function_exists('GetLocalURL'))
{
    function GetLocalURL($URL, $setDefault = TRUE)
    {

		// ----- load array of every language
		include APPPATH . "Ignition/Library/EarthicaLanguages.php";

		// ----- check for no language prefix in url 
		$URL = strtolower($URL);

		if ($URL == SERVERBASENAME) {

			if ($setDefault)
				return DEFAULTLOCALE;
			else
				return FALSE;
		}

		// ----- check for malformed url, no language code followed by dot 
		$langPos = strpos($URL, ".");
		if ($langPos === false) {

			return false;
		}

		// ----- search array for language code 
		$langCode = substr($URL, 0, $langPos);

		// ----- return the code or false 
		return (isset($systemLanguages[$langCode]) ? $langCode : false);

	}

}

// ----- for non critical errors that do not require stopping the program in production
//       use this one if the error is not very serious where our logs would show it and
//       does not require the user to stop with his operation
if (! function_exists('haltLog'))
{
    function haltLog($debugMessage = '')
    {
        $flashData = GetFlash();
        if (gettype($flashData) == 'array')
            $flashData = array2string($flashData);

        echo _br() . $flashData;

		if ($debugMessage != '' && getenv('CI_ENVIRONMENT') == 'development') {
            br(2);
			echo (gettype($debugMessage) == 'array' ? print_r($debugMessage) : $debugMessage);
            $storeMessage = (gettype($debugMessage) == 'array' ? array2string($debugMessage) : $debugMessage);
		} else {
            $storeMessage = $debugMessage;
            echo _br(2) . lang('base.error') . ' ' . lang('base.access_denied');
        }

//        if (getenv('CI_ENVIRONMENT') != 'development')
            logEvent('haltLog', $storeMessage);

  		die();

	}
}

// ----- universal function for debugging and error trapping
if (! function_exists('halt'))
{
    function halt($debugMessage = '')
    {

		if (getenv('CI_ENVIRONMENT') == 'development') {
            if ($debugMessage == '')
                br();
            else
    			echo (gettype($debugMessage) == 'array' ? print_r($debugMessage) : $debugMessage . _br());
		}

        echo "Program halted";

		die();

	}
}

// ----- fixes php bug in current_url() which never returns https
if (! function_exists('fixed_url'))
{
    function fixed_url()
    {

		return BaseURL() . $_SERVER['REQUEST_URI'];

	}
}

// ----- grant access
if (! function_exists('GrantAccess'))
{
	// ----- given a list of allowed users, compare logged in user type, UT
    function GrantAccess($list)
    {

		if (UT == '0' || UT == '')
			return FALSE;

		if ($list == "ALL")
			RETURN TRUE;

		$list = bar2array($list, TRUE);

		// ----- set up loop to verify access
		foreach($list as $entry) {

			if ($entry == UT)
				return TRUE;
		}

		RETURN FALSE;

	}
}

// ----- log events 
if (! function_exists('LogEvent'))
{
    function LogEvent($code, $desc)
    {
		// ------ store event to logs
		$model = new \Ignition\Base\BaseModel('logs', ['id', 'created_at', 'logs_code', 'logs_desc', 'logs_username', 'logs_host', 'active'], DEFAULTDBGROUP);
		$data = [
			'logs_code' => $code,
			'logs_desc' => $desc,
			'logs_username' => USERNAME,
			'logs_host' => $_SERVER['SERVER_NAME'],
			'logs_ip' => $_SERVER['REMOTE_ADDR']];
		$model->insert($data);

	}
}

// ----- takes string with one or more words, makes all lower case and strips out spaces
//       useful in menu where lang version can be converted directly to url
if (! function_exists('URLize'))
{
    function URLize($makeURL)
    {

		$makeURL = str_replace(' ', '', $makeURL);
		return strtolower($makeURL);

	}
}

// ----- takes string with one or more words, makes all lower case and strips out spaces
if (! function_exists('basic_sort'))
{

	function basic_sort($a, $b) {

	  if ($a == $b)
		return 0;

	  return ($a < $b) ? -1 : 1;
	}

}

// ----- takes bar delineated string and converts to array
if (! function_exists('bar2array'))
{

	function bar2array($barText, $makeLower = FALSE) {

		if ($barText == '')
			return [];

        // ----- init
		$textLen = strlen($barText);
		$entryStart = 0;
        $returnArray = [];

        // ----- setup loop to find quoted entries and add to array
        do {

	        $entryStop = strpos($barText, "|", $entryStart);

	        if ($entryStop === false) {

				// ----- check for final entry 
				if ($entryStart < $textLen) {
                    if ($makeLower)
    					$returnArray[] = strtolower(substr($barText, $entryStart, $textLen - $entryStart));
                    else
    					$returnArray[] = substr($barText, $entryStart, $textLen - $entryStart);
                }

	            break;

			}

	        // ----- extract tag
            if ($makeLower)
    	        $returnArray[] = strtolower(substr($barText, $entryStart, $entryStop - $entryStart));
            else
    	        $returnArray[] = substr($barText, $entryStart, $entryStop - $entryStart);

	        $entryStart = $entryStop + 1;

			if ($entryStart >= $textLen)
				break;

        } while(true);

		return $returnArray;

	}

}

// ----- takes string with one or more words, makes all lower case and strips out spaces
if (! function_exists('IsSetElse'))
{

	function IsSetElse($setThis, $orElse) {
		if (isset($setThis))
			return $setThis;
		else 
			return $orElse;

	}
}

// ----- set a temporary message if user has session
if (! function_exists('SetFlash'))
{

	function SetFlash($message) {

		if (isset($_SESSION['session_data']))
			$_SESSION['session_data']['user_flash'] = $message;

	}

}

// ----- get the temporary message stored in session
if (! function_exists('GetFlash'))
{

	function GetFlash() {

		if (isset($_SESSION['session_data'])) {
			$retval = $_SESSION['session_data']['user_flash'];
			SetFlash('');
			return $retval;
		} else 
            return FALSE;
	}

}

// ----- set a temporary message if user has session
if (! function_exists('SetFlashPro'))
{

	function SetFlashPro($message) {

		if (isset($_SESSION['session_data']))
			$_SESSION['session_data']['user_flash_pro'] = $message;

	}

}

// ----- get the temporary message stored in session
if (! function_exists('GetFlashPro'))
{

	function GetFlashPro() {

		if (isset($_SESSION['session_data'])) {
    		$retval = $_SESSION['session_data']['user_flash_pro'];
    		$_SESSION['session_data']['user_flash_pro'] = '';
			return $retval;
		} else 
            return FALSE;

	}

}

// ----- store the session id to edit so will only modify that id
if (! function_exists('SetEditID'))
{

	function SetEditID($id) {

		if (isset($_SESSION['session_data']))
			$_SESSION['session_data']['edit_id'] = $id;

	}

}

// ----- restore edit id
if (! function_exists('GetEditID'))
{

	function GetEditID() {

		if (isset($_SESSION['session_data']))
			return $_SESSION['session_data']['edit_id'];

	}

}

// ----- return the first array key that matches the provided value field
if (! function_exists('GetKey'))
{

    function GetKey($array, $value) {

		foreach ($array as $key => $field) {
			if ($value == $field)
				return $key;
		} 
		return FALSE;
	}

}

// ----- return a comma formated number
if (! function_exists('NumberDisplay'))
{

    function NumberDisplay($number, $decimals = 0) {

		switch (strtolower($GLOBALS['LANGCODE'])) {
			case 'en' : return number_format($number, $decimals, '.', ',');
			case 'es' : return number_format($number, $decimals, ',', '.');
			default : return number_format($number);
		}
	}

}

// ----- return t/f email
if (! function_exists('isEmail'))
{

    function isEmail($possibleEmail) {
        return (strstr($possibleEmail, '@') && strstr($possibleEmail, '.'));
	}

}

// ----- given a directory, return all file names without directories
if (! function_exists('GetFiles'))
{

    function GetFiles($path) {

        $returnArray = [];

        // ----- scan for files
        if (is_dir($path))
            $files = scandir($path);
        else {
            setFlash("Error: pahaltth not found in call to GetFiles. " . $path);
            return '';
        }

        foreach ($files as $file) {
            if (in_array($file, array(".", "..")))
                continue;

            if (is_dir($path .  '/' . $file))
                continue;

            $returnArray[] = $file;
        }

        return (count($returnArray) == 0 ? '' : $returnArray);
	}

}

// ----- trigger active intrusion management
if (! function_exists('ActiveIntrusion'))
{

    function ActiveIntrusion($path, $options = []) {
        return;
    }

}

// ----- given array and prefix, prepend the prefix upon every array key
if (! function_exists('PrependKeys'))
{

    function PrependKeys($data, $prefix) {

        // ----- init
        $dataRet = [];

        foreach($data as $key => $value) {
            $dataRet[$prefix . $key] = $value;
        }

        return $dataRet;
    }

}

// ------ Pass fieldList, list of fields to set; url, location of json file, standard dna ignition iform model 
//        Returns model modified with new values set, adding any missing values from meta file
//        Optional prependkey allows for differentiation from the other data in the model
if (! function_exists('FetchMetaData'))
{
    function FetchMetaData($fieldList, $url, $model = FALSE, $prependKey = '', $json = TRUE) {

        // ----- init
        $altText = lang('dna.not_set');
        $showOnly = [];
        $errorCode = FALSE;

        // ----- check field list for missing end bar
        if (substr($fieldList, strlen($fieldList) - 1, 1) != '|')
            $fieldList .= '|';

        // ----- if received url
        if ($url != '' && !empty($url)) {

            // ----- check for auto set proto
            if (substr($url, 0, 4) != 'http')
                $url = HTTPPROT . "://" . $url;

        	// ----- set up curl session to fetch article body text
        	$ch = curl_init();
        	curl_setopt($ch, CURLOPT_URL, $url);
        	curl_setopt($ch, CURLOPT_RETURNTRANSFER, $url);
        
        	// ----- finally execute curl command and retrieve body text, close session
        	$metaData = curl_exec($ch);
        	$resultInfo = curl_getinfo($ch);
        	curl_close($ch);
    
            // ----- check for url not found or other error
            if ($resultInfo['http_code'] == 404) {
                $altText = "Meta file URL not found";
                $metaData = FALSE;
                $errorCode = 1;
            } else {
                if ($resultInfo['http_code'] != 200) {
                    $altText = "Meta file download, other error";
                    $metaData = FALSE;
                    $errorCode = 2;
                }
            }

        } else {
            $metaData = FALSE;  // will return array of fieldslist set to blank
            $errorCode = 3;
        }

        // ----- store, check keys and sanetize meta data
        //
        if ($metaData) {

            // ----- if json decode, convert metadata to array
            if ($json) {
                $metaData = json_decode($metaData, TRUE);

                if (!is_array($metaData)) {
                    $altText = 'Meta file malformed error.  Unable to decifer.';
                    $metaData = [];
                    $errorCode = 4;
                }

                // ----- check for select all automatically
                if (!$fieldList) {
 
                    // ----- check for error
                    if (empty($metaData)) {
                        $altText = 'Meta file malformed error.  Unable to decifer.';
                        $metaData = [];
                        $errorCode = 5;
                    }

                    // ----- if no model, no model data integration required, simply return the array of metadata
                    if (!$model) {
                        setFlash($errorCode);
                        if ($errorCode)
                            $metaData = $altText;
                        return $metaData;
                    }

                }

            } else {
                $showOnly[] = $prependKey;
                $model->data[$prependKey] = ($metaData ? $metaData : '');
            }


        } else {
            $metaData = [];
            if (!$json)
                $model->data[$prependKey] = '';
        }
    
        // ----- if json decode, ergo, multi field, set fields in model data
        if ($json) {

            // ----- set up loop to verify/set fields
            $fieldTally = 0; $fieldCount = FieldCount($fieldList, "|");

            $metaKeys = array_keys($metaData);
            foreach ($metaKeys as $metaKey) {
        
                // ----- scan for element
                if (strstr($fieldList, $metaKey . "|")) {
                    $showOnly[] = $metaKey;
                    $model->data[$prependKey . $metaKey] = $metaData[$metaKey]; // CISANATIZE($metaData[$metaKey]);
                    $fieldTally ++;
                }
            }
        
            // ----- if any misssing fields, add them with blank or not set data
            if ($fieldTally < $fieldCount) {
                // ----- set up loop to seek and fill in any missing data elements
                for ($count = 1; $count <= $fieldCount; $count ++) {
        
                    // ----- get next field in list
                    $nextVal = GetField($fieldList, $count, "|");
        
                    // ----- scan showOnly for set value
                    if (!ValSetArray($showOnly, $nextVal)) {
                        $showOnly[] = $nextVal;
                        $model->data[$prependKey . $nextVal] = $altText;
                    }
    
                }
            }
        }

        if (!empty($model->showOnly))
            $model->showOnly = array_merge($model->showOnly, $showOnly);

        // ----- save error code and return
        setFlash($errorCode);
        return $model;

    }

}

// ----- fail safe set db group.  if does not exist, return false
if (!function_exists('SetDB'))
{
    function SetDB($dbGroup) {

        // ----- check exits
        $myDB = new \Config\Database;

        if (isset($myDB->$dbGroup)) {
            return \Config\Database::connect($dbGroup);
        } else {
            SetFlash("Unable to set database in call to SetDB");
            return FALSE;
        }

    }

}

// ----- check to see if database exists
if (! function_exists('DBExists'))
{
    function DBExists($dbName)
    {
        $db = Config::connect();

        $query = $db->query("SELECT SCHEMA_NAME FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME = ?", $dbName);

        if ($query->getNumRows() == 0)
            return FALSE;
        else 
            return $db;
    
    }
}

// ----- get user profile record.  Use id by default
if (!function_exists('GetUserProfile'))
{
    function GetUserProfile($uid, $byEN = FALSE) {

        if (!$db = SetDB(DEFAULTDBGROUP)) {
            SetFlash("Failed to connect to default database");
            return [];
        }
        $builder = $db->table('user');

        if ($byEN) {
            if ($_SESSION['session_data']['user_peernum'] == 'ANONYMOUS' || substr($uid, 0, 1) == 'W')
    		  $resultsObj = $builder->getWhere(['user_accountnum' => dna402bin($uid)])->getResult('array');
            else 
    		  $resultsObj = $builder->getWhere(['user_peernum' => dna402bin($uid)])->getResult('array');
        } else
    		$resultsObj = $builder->getWhere(['id' => $uid])->getResult('array');

   		return isset($resultsObj[0]) ? $resultsObj[0] : [];

    }

}

// ----- trim trailing zeros from a number
if (!function_exists('TrimDecZeros'))
{
    function TrimDecZeros($value) {

        // ----- check for decimal, required
        if (strstr($value, $GLOBALS['DECSEP']) === FALSE)
            return $value;

        $len = strlen($value);
        for ($countDown = $len; $countDown > 1; $countDown--) {
            // ----- if reached decimal, or non zero val
            if (substr($value, $countDown - 1, 1) != '0')
                break;

            // ----- subtract one trailing zero
            $value = substr($value, 0, $countDown - 1);

        }

        // ----- if end up with trailing decimal, remove
        if (substr($value, strlen($value) - 1, 1) == $GLOBALS['DECSEP'])
            $value = substr($value, 0, strlen($value) - 1);

        return $value;

    }

}

// ----- shortcut to iform input field
if (! function_exists('IFormInput'))
{

	function IFormInput($value, $label, $disabled = TRUE, $options = []) {

        if (!empty($options)) {

            if (isset($options['numberFormat']))
                $value = NumberFormat($value, $options['numberFormat']);
        }

        echo '<div class="form-group">';
        echo '<label class="form-control-label">' . $label . '</label>' . _br();
        echo '<input type="text" name="fan" value="' . $value . '"  class="form-control" disabled="' . ($disabled ? '1' : '') . '">';
        echo '</div>';
    }
}

// ----- shortcut to iform input field
if (! function_exists('GetFieldNames'))
{

	function GetFieldNames($tableName, $dbName) {

        $db = SetDB($dbName);

		// ----- check debug error
		if (!$tableName || !$db || !$dbName) {
			if (getenv('CI_ENVIRONMENT') == 'development') {
				e('Tablename', $tableName);
				e('Database name', $dbName);
                e('Database is set', (!$db ? 'FALSE' : 'TRUE'));
                e('GetFlash', GetFlash());
				halt('Call to GetFieldNames() with bad parameter(s).');
			} else 
				haltLog('Internal program error while accessing database: ' . $dbName . '.' . $tableName);
		}

        $builder = $db->table($tableName);

        $query = $builder->limit(1)->get()->getResult('array');

        // ----- check for new result, table must have zero records
        if (!$query) {

            // ----- add record, get result, delete record
            $model = new \Ignition\Base\BaseModel($tableName, ['id'], $dbName);
            $model->SetTimeStamps(FALSE);
            $model->insert(['id' => 1]);
            if (!($query = $builder->limit(1)->get()->getResult('array'))) {
                SetFlash('Failed to get field names in call to GetFieldNames, table & database: ' . $tableName . ' ' . $dbName);
                return FALSE;
            }
            // ----- now delete the record and restore the auto increment to default 1
            $model->delete(1);
            $db = SetDB($dbName);
            $queryDB = $db->query('ALTER TABLE `' . $tableName . '` AUTO_INCREMENT = 1;');
        }

        return array_keys($query[0]);

    }
}

// ----- randomly generate a new account number and check for already in use/collission before accept
if (! function_exists('NewAccountNum'))
{

    // ----- generate new private id number 
    //       Gen random number 0-34359738368 billion (35 bits)
    //       Check to see if already in use
    //       If not in use assign, otherwise try and find another
    function NewAccountNum($dbGroup = FALSE) {

        // ----- open earthizen root database
        $db = SetDB($dbGroup ? $dbGroup : DEFAULTDBGROUP);
        $builder = $db->table('user');

        // ----- initialize seed
        srand(101);
        $recRange = 34359738368;
        $WBase = dna402dec('W111111111');

        // ----- set up loop to verify INumber not already taken
    	do {

            $nextRandom = rand(1, $recRange);
            $nextRandomINumber = dec2dna40($WBase + $nextRandom);

            $resultsObj1 = $builder->getWhere(['user_accountnum' => dna402bin($nextRandomINumber)])->getResult('array');

            if (empty($resultsObj1))
                break;

    	} while (TRUE);

        return $nextRandomINumber;
    }

}

// ----- set number format and return printable value
//       useful for number formatting.  dec and sep compatible with application.  see globals
if (! function_exists('NumberFormat'))
{

    // ----- number format, base10 only.  Uses globals for decimal point and separator
    function NumberFormat($value, $format) {

        $attrib2 = NULL;
        $attrib3 = NULL;
        $decPos = 0;

        // ----- sanitize number, php chokes if non numerics in string
        if (!ctype_digit($value))
            $value = CleanBase10($value);

        if (isset($format['set'])) {
            if ($format['set'] == 'app') {
                $attrib2 = $GLOBALS['DECSEP'];
                $attrib3 = $GLOBALS['NUMSEP'];
                if (!isset($format['decimal']))
                    $format['decimal'] = 'auto';
            }
        }

        if (isset($format['decimal'])) {
            if ($format['decimal'] == 'auto') {
                if (!(($pos = strpos($value, '.')) === NULL));
                    $decPos = strlen($value) - $pos - 1;
            } else 
                $decPos = $format['decimal'];
        }

        $value = number_format((int) $value, $decPos, $attrib2, $attrib3);

        // ----- check for trim zeros
        $trim = isset($attributes['numberFormat'][3]) ? $attributes['numberFormat'][3] : AUTOTRIM0;
        if ($trim)
            $value = TrimDecZeros($value);

        return $value;

   }
}

?>
