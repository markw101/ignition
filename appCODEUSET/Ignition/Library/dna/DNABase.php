<?php
/*********************************************************************
    AUTHOR:      Mark E. Williamson
    FILE:        DNABase.php
    VERSION:     See Robotica.php
    DESCRIPTION: Base DNA related helper functions.  Perhaps suitable 
                 for other Ignition applications
    COPYRIGHT:   2023
    FIRST REV:   July 2023
    LICENSE:     MIT

**********************************************************************/

// ----- given binary string, converts each col in order to dna40
if (! function_exists('bin2dna40'))
{

	function bin2dna40($convertData) {

		// ------ init
		$convertData = ($convertData === null ? "" : $convertData);
		$numLen = strlen($convertData);
		$rex32Vals = $GLOBALS['SYMBOL_SETS']['REX32'];
		$rex8Vals  = $GLOBALS['SYMBOL_SETS']['REX8'];
		$retVal = '';

		// ----- set up loop to convert
		for($count = 0; $count < $numLen; $count ++) {

			// ----- calculate next pair (using bin2hex intermediary, issues with bindec())
			$nextVal = substr($convertData, $count, 1);
			$nextVal = hexdec(bin2hex($nextVal));
			$next32 = substr($rex32Vals, floor($nextVal / 8), 1);
			$next8 = substr($rex8Vals, $nextVal % 8, 1);

			// ----- concatinate together
			$retVal = $retVal . $next32 . $next8;
		}

		return $retVal;
	}

}

// ----- given dna40, takes each pair and converts to a binary value in order 
if (! function_exists('dna402bin'))
{

	function dna402bin($convertText) {

		// ------ init
		$convertText = ($convertText === null ? "" : $convertText);
		$numLen = strlen($convertText);
		$rex32Vals = $GLOBALS['SYMBOL_SETS']['REX32'];
		$rex8Vals  = $GLOBALS['SYMBOL_SETS']['REX8'];
		$retVal = '';

		// ----- set up loop to convert
		for($count = 0; $count < $numLen; $count += 2) {

			// ----- get next pair
			$next32 = substr($convertText, $count, 1);
			$next8 = substr($convertText, $count + 1, 1);

			// ----- convert to binary value 
			$tally = strpos($rex32Vals, $next32) * 8;
			$tally += strpos($rex8Vals, $next8);

			// ----- store and increment
			if ($count == 0)
				$retVal = chr($tally);
			else 
				$retVal = $retVal . chr($tally);

		} 

		return $retVal;
	}

}

if (! function_exists('bin2dec'))
{
    function bin2dec($val)
    {
        return hexdec(bin2hex($val));
    }
}

// ----- given varbinary string return base64url
if (! function_exists('bin2base64'))
{

	function bin2base64($binVal) {

		return sodium_bin2base64($binVal, SODIUM_BASE64_VARIANT_ORIGINAL);

	}

}

// ----- given varbinary string return base64url
if (! function_exists('base642bin'))
{

	function base642bin($base64Val) {

		return sodium_base642bin($base64Val, SODIUM_BASE64_VARIANT_ORIGINAL);

	}

}

// ----- given plain text, generate sha512 hash value in dna40 encoding, 4096 key strech
if (! function_exists('dnaHashsha512'))
{

	function dnaHashsha512($text2Hash, $saltVal) {

		return bin2dna40(hash_pbkdf2('sha3-512', $text2Hash, $saltVal, 4096, 0, TRUE));

	}

}

// ----- wrapper for sha512, 4096 key strech
if (! function_exists('DNAPasswordHash'))
{

	function DNAPasswordHash($password, $saltVal) {

		return dnaHashsha512($password, $saltVal);

	}

}

// ----- verify password with salt utilizing sha512, 4096 key strech
if (! function_exists('DNAPasswordVerify'))
{

	function DNAPasswordVerify($password, $passwordHash, $passwordSalt) {

		return $passwordHash == DNAPasswordHash($password, $passwordSalt);

	}

}

// ----- given text and salt, sha256 hash and return 40 bytes 
if (! function_exists('dnaHashsha256'))
{

	function dnaHashsha256($text2Hash, $saltVal) {

		return bin2dna40(hash_pbkdf2('sha256', $text2Hash, $saltVal, 4096, 0, TRUE));
	}

}

// ----- given plain text, generate sha512 hash value in dna40 encoding, 4096 key strech
if (! function_exists('dnaHashHaval160'))
{

	function dnaHashHaval160($text2Hash, $saltVal) {

		return bin2dna40(hash_pbkdf2('haval160', $text2Hash, $saltVal, 4096, 0, TRUE));
	}

}

// ----- given asset prefix and table record OS, return asset number with prefix 
//       combines the prefix with a properly formatted dna40 asset chain height
if (! function_exists('height2asset'))
{

	function height2asset($assetPrefix, $recordOS) {

		$dataVal = dec2dna40($recordOS);

        if ($assetPrefix == 'P' || $assetPrefix == 'C' || $assetPrefix == 'R')
            $rootEntity = TRUE;
        else 
            $rootEntity = FALSE;

        // ----- as the prefix is built into the entity number, subtract 1 column from len of height
        //       entity numbers are limited to the domain of dna40 with start val of P, C or R
        //       in the case of assets, the OS chain number is 1 column bigger because the 
        //       prefix is a separate part of the ID
		return $assetPrefix . str_repeat('1', HEIGHTFILL - strlen($dataVal) - ($rootEntity ? 1 : 0)) . $dataVal;

	}

}

// ----- given dec value, returns dna40 
if (! function_exists('dec2dna40'))
{

	function dec2dna40($convertVal) {

		// ----- make sure number is in
		//if (!is_integer($convertVal))
		//	halt("Invalid integer value: " . $convertVal);

		// ----- check for proper col count to use hex conversion
		$convertVal = dechex($convertVal);
		if (strlen($convertVal) % 2 > 0)
			$convertVal = "0" . $convertVal;

		return bin2dna40(hex2bin($convertVal));

	}
}

// ----- given dna40, takes each pair and converts to a decimal (base10) number 
if (! function_exists('dna402dec'))
{

	function dna402dec($convertVal) {

		// ----- verify dna40 
		$convertVal = strtoupper($convertVal);
		$dna40Val = new \Ignition\Library\dna\Edna40($convertVal);

		if (!$dna40Val->set($convertVal)) {
			SetFlash("Invalid DNA40 number in call to convert to decimal: " . $dna40Val->message);
            return FALSE;
        }

		return hexdec(bin2hex(dna402bin($convertVal)));
	}
}


// ----- given binary string, converts number to base10 display value
if (! function_exists('bin2base10'))
{

	function bin2base10($convertData) {

		return hexdec(bin2hex($convertData));

	}
}

// ----- given base10 display value convert to binary
if (! function_exists('base102bin'))
{

	function base102bin($convertData) {

		$convertData = dechex($convertData);

		// ----- check for proper col count to use hex conversion
		if (strlen($convertData) % 2 > 0)
			$convertData = "0" . $convertData;

		return hex2bin($convertData);

	}
}

// ----- incrementally step a dna40 number by the value of 1
if (! function_exists('DNA40plus1'))
{

	function DNA40plus1($addOne) {
		return dec2dna40(dna402dec($addOne) + 1);
	}

}

// ----- convert dna viewable content to storage depending on type 
if (! function_exists('dnaView2Store'))
{

    function dnaView2Store($data, $ftype, $dtype) {

	    if ($ftype == "text") {

		    switch ($dtype) {
		        case "binified" :
		            return BinifyAsset($data);
		            break;
		        case "dna40" :
		            return dna402bin($data);
		            break;
		        case "base64" :
		            return sodium_base642bin($data, SODIUM_BASE64_VARIANT_URLSAFE_NO_PADDING);
		            break;
		        case "hex" :
		            return hex2bin($data);
		            break;
				case "base10" :
		            return base102bin($data);
	    	        break;
				case "text" :
					return $data;
	    	        break;
				default :
	            	echo "Form Error: IForm incorrect dataType attribute in field defintion in AutoForm for field: " . $field['name'] . ".  Must be set to either text, dna40, base64, hex<br>";
					return false;
					break;
			}
		} else 
			return $data;
	}
}

// ----- convert dna viewable content to storage depending on type 
if (! function_exists('dnaStore2View'))
{

    function dnaStore2View($data, $ftype, $dtype) {

	    if ($ftype == "text") {

		    switch ($dtype) {
		        case "dna_date" :
		            return \dna\Library\CryptoUtil::dnadate2rfc($data);
		            break;
		        case "efloat" :
		            return UnbinifyEFloat($data);
		            break;
		        case "binified" :
		            return UnbinifyAsset($data);
		            break;
		        case "dna40" :
		            return bin2dna40($data);
		            break;
		        case "base64" :
		            return sodium_bin2base64($data, SODIUM_BASE64_VARIANT_URLSAFE_NO_PADDING);
		            break;
		        case "hex" :
		            return bin2hex($data);
		            break;
				case "base10" :
		            return bin2base10($data);
	    	        break;
				case "text" :
					return $data;
	    	        break;
				default :
	            	echo "Form Error: IForm incorrect dataType attribute in field defintion in AutoForm for field type: " . $dtype . ".  Must be set to either text, dna40, base64, hex, efloat<br>";
					return false;
					break;
			}
		} else 
			return $data;
	}
}

// ----- given text present of FAN, compact into binary form for storage and transport
//       break asset ID into parts and then strategically reduce to smallest binary size
//       FAN ID: B-1111111114@1111.ET.  Converted to binary bytes 1 + 5 + 2 + 1 = 9
//       FEN ID: P111111112@1111.ET.  Converted to binary bytes 5 + 2 + 1 = 8 (normally, not required because entity # unique worldwide)
//       returns compacted, binary form of asset number
if (! function_exists('BinifyAsset'))
{

    function BinifyAsset($assetID, $eRobot = FALSE) {

        // ----- check for erobot symbol, remove
		$prefixOS = strpos($assetID, '-');
        if ($prefixOS > 2) {
            $assetID = ($prefixOS === FALSE ? $assetID : substr($assetID, $prefixOS + 1));
            $prefixOS = FALSE;
        }

        // ----- verify asset contains ezone
        if (!strstr($assetID, '@') && !strstr($assetID, '.')) {
            SetFlash("Invalid asset in call to BinifyAsset");
            return FALSE;
        }

		// ----- check if ordinal (non entity)
		$prefixOS = strpos($assetID, '-');
		if ($prefixOS === FALSE) {
			$assetPrefix = substr($assetID, 0, 1);
            $prefixOS = 1;
		} else
			$assetPrefix = substr($assetID, 0, $prefixOS + 1);

        $classInfo = PrefixClassInfo($assetPrefix);

        // ----- check for not found
        if (empty($classInfo)) {
            $message = "Invalid class prefix: " . $assetPrefix . ".  Not found in call to BinifyAsset";
            e($message);
            e('classinfo', $classInfo);
            e('assetID', $assetID);
            SetFlash($message);
            return FALSE;
        }

        $binValPrefix = $classInfo['binVal'];

        // ----- check for fungibles and entities, no need to strip away the prefix
        if (InList('|M|T|K|B|C|P|R|A|', $classInfo['assetPrefix'])) {
            $height = substr($assetID, 0, strpos($assetID, '@'));
        } else {

            $height = substr($assetID, $prefixOS + 1);
            $height = substr($height, 0, strpos($height, '@'));
    
            if (strlen($height) > HEIGHTFILL) {
                SetFlash(lang('ecoin.invalid') . ' ' . lang('dna.asset') . ' ' . lang('base.number'));
                return FALSE;
            }
            $height = str_repeat(chr("0"), HEIGHTFILL - strlen($height)) . $height;
        }

        $eStripe = substr($assetID, strpos($assetID, '@') + 1, strlen($assetID) - strpos($assetID, '.') + 1); 
        $eCountry = substr($assetID, strpos($assetID, '.') + 1);

        return chr($binValPrefix) . dna402bin($height) . dna402bin($eStripe) . ecountrytobin($eCountry);

    }

}

// ----- given fully qualified asset in binary form, FAN, return asset in text form
//       fqAssetID: B1111111114@1111.ET (Bytes 1, 5, 2, 1 = 9)
//       in case of root entities and fung accounts, there is no dash and the prefix part of first rextet
if (! function_exists('UnbinifyAsset'))
{

    function UnbinifyAsset($assetID) {

        if (empty($assetID)) {
            SetFlash("Empty asset ID in call to UnbinifyAsset"); 
            return '';
        }

        // ----- get prefix and height
        $prefix = binval2prefix(hexdec(bin2hex(mb_substr($assetID, 0, 1, '8bit'))));

        $height = bin2dna40(mb_substr($assetID, 1, 5, '8bit'));

        // ----- check for fungible/entites: the number contains the prefix
        if (InList('|C|P|R|B|M|K|T|A|', $prefix)) {
            return $height . '@' . bin2dna40(mb_substr($assetID, 6, 2, '8bit')) . '.' . bintoecountry(mb_substr($assetID, 8, 2, '8bit'));
        } else {
            return $prefix . $height . '@' . bin2dna40(mb_substr($assetID, 6, 2, '8bit')) . '.' . bintoecountry(mb_substr($assetID, 8, 2, '8bit'));
        }
    }

}

// ----- given two char representation of ecountry, return binary token
//       ecountries must never be inserted inot this array, only appended
if (! function_exists('ecountrytobin'))
{

    function ecountrytobin($eCountry) {
        $count = 0;
        foreach ($GLOBALS['COUNTRY'] as $keyVal => $val) {
            if ($keyVal == strtoupper($eCountry))
                return chr($count);

            $count ++;
        }
        return FALSE;
    }

}

// ----- given binary token/OS of country in std global array return two char name of ecountry 
if (! function_exists('bintoecountry'))
{

    function bintoecountry($eCountry) {
        $count = 0;
        $eCountry = hexdec(bin2hex(mb_substr($eCountry, 0, 1)));

        foreach ($GLOBALS['COUNTRY'] as $keyVal => $val) {
            if ($count == $eCountry)
                return $keyVal;

            $count ++;
        }
        return FALSE;
    }

}

// ----- FillAsset.  given possibly truncated assetID, convert to HEIGHTFILL asset width
if (! function_exists('FillAsset'))
{

    function FillAsset($assetID) {

		// ----- check if ordinal (non entity)
		$prefixOS = strpos($assetID, '-');
		if ($prefixOS === FALSE) {
            // ----- check for entity asset type
            $assetPrefix = substr($assetID, 0, 1);
            if ($assetPrefix == 'P' || $assetPrefix == 'C' || $assetPrefix == 'R') {
                $height = substr($assetID, 1);
                return $assetPrefix . str_repeat("1", HEIGHTFILL - strlen($height) - 1) . $height;
            } else {
                SetFlash("Invalid asset prefix in call to assetID");
    			return FALSE;
            }
		} else
			$assetPrefix = substr($assetID, 0, $prefixOS + 1);
        // ----- fill asset core value to 10 columns with zeros, ergo 1s
        $height = substr($assetID, $prefixOS + 1);

        // ----- check range
        if (strlen($height) > HEIGHTFILL)
            halt("Error in height value in call to FillAsset:");

        return $assetPrefix . str_repeat("1", HEIGHTFILL - strlen($height)) . $height;
    }

}

// ----- Given binified asset FAN, return just the id record number in the asset table (table offset)
if (! function_exists('ExtractBinifiedHeight'))
{
    function ExtractBinifiedHeight($BinifiedAsset) {

            $BinifiedAsset = UnbinifyAsset($BinifiedAsset);
            $prefixOS = strpos($BinifiedAsset, '-') + 1;
            $endDNA40 = strpos(substr($BinifiedAsset, $prefixOS), '@');
            if ($endDNA40)
                return dna402dec(substr($BinifiedAsset, $prefixOS, $endDNA40));
            else 
                return FALSE;
    }
}

// ----- import efloat and return string value.  very different then unbinify fan
if (! function_exists('UnbinifyEFloat'))
{
    function UnbinifyEFloat($BinifiedEFloat) {

        $len = mb_strlen($BinifiedEFloat, '8bit');
        $valGMP = gmp_import(mb_substr($BinifiedEFloat, 1, $len - 1, '8bit'));

        // ---- first byte, set number of decimal places (notset becomes 0)
        $place = hexdec(bin2hex(mb_substr($BinifiedEFloat, 0, 1, '8bit')));

        // ----- negative values are indicated in storage by adding 100 to the decimal value
        if ($place > 99) {
            $place -= 100;
            $isNegative = TRUE;
        } else 
            $isNegative = FALSE;

        $place = ($place > 0 ? $place : NOTSET);

        $setVal = gmp_strval($valGMP);
        $len = strlen($setVal);

        // ----- check if needs to front pad with zeros
        if ($len < $place) {
            $setVal = str_repeat('0', $place - $len) . $setVal;
            $len = strlen($setVal);
        }

        // ----- if decimal point, insert
        if ($place != NOTSET)
            $setVal = substr($setVal, 0, $len - $place) . '.' . substr($setVal, $len - $place);

        // ----- if negative add symbol
        if ($isNegative)
            $setVal = '-' . $setVal;

        return $setVal;

    }
}

// ----- prep number for math.  sanatize base10 number, extract any non base10 column.   
//       remove non b10 symbols, spaces, commas, extra decimals.  make sure negative 
//       symbol is in front
//       negative sign can be anywhere in number.  remove any leading zeros
if (! function_exists('CleanBase10'))
{
    function CleanBase10($value) {

        $retVal = '';
        $len = strlen($value);
        $isNegative = FALSE;
        $hasDecimal = FALSE;

        for ($count = 0; $count < $len; $count ++) {

            if (!ValSet('1023456789.-', substr($value, $count, 1))) {
                SetFlash("Invalid symbol in call to function CleanBase10: " . substr($value, $count, 1));
                continue;
            }

            // ----- check for negative symbol, remove
            if (substr($value, $count, 1) == '-') {
                $isNegative = TRUE;
                continue;
            }

            // ----- check for decimal symbol
            if (substr($value, $count, 1) == '.') {
                if ($hasDecimal)
                    continue;
                $hasDecimal = TRUE;
            }

            $retVal = $retVal . substr($value, $count, 1);

        }

        // ----- check for and remove leading zeros
        if (substr($retVal, 0, 1) == '0') {
            $len = strlen($retVal);
            $value = $retVal;
            $retVal = '';
            for ($count = 0; $count < $len; $count ++) {
                if (substr($value, $count, 1) == '0')
                    continue;
                $retVal = substr($value, $count);
                break;
            }

            if (strlen($retVal) == 0)
                $retVal = '0';

        }

        if ($isNegative && $retVal != '0')
            $retVal = '-' . $retVal;

        return $retVal;

    }

}

// ----- Given asset FAN, return just the id record number in the asset table (table offset)
if (! function_exists('ExtractFANHeight'))
{
    function ExtractFANHeight($assetFAN) {

            $prefixOS = strpos($assetFAN, '-') + 1;
            $endDNA40 = strpos(substr($assetFAN, $prefixOS), '@');
            if ($endDNA40)
                return dna402dec(substr($assetFAN, $prefixOS, $endDNA40));
            else 
                return FALSE;
    }
}

// ------ Pass amend entity number in text form.  strips away entity prefix designator, calc id
//        Returns int, the entity table RECORD ID NUMBER (not an offset)
if (! function_exists('EntityRecordHeight'))
{
    function EntityRecordHeight($entityNumber) {
        $offSet = "1" . substr($entityNumber, 1);
        return dna402dec($offSet);
    }

}

// ------ Pass record number + entity type
//        Returns dna40 version of the entity number
if (! function_exists('EntityNumber'))
{
    function EntityNumber($recordOS, $entityPrefix) {

        $entityNumber = dec2dna40($recordOS);
        return FillAsset($entityPrefix . $entityNumber);
    }

}

// ------ given a FAN, return ezone in database name format
if (! function_exists('ExtractEzoneFAN'))
{
    function ExtractEzoneFAN($eFan) {

        return strtoupper(substr($eFan, strpos($eFan, '@') + 1, 4)) . strtolower(substr($eFan, strpos($eFan, '.') + 1));
    }

}

// ------ hash or verify a value using the QuickSum algo.  second param is either salt of set to false which means verify
if (! function_exists('QuickSum'))
{
    function QuickSum($plainText, $isSalt = FALSE) {

        // ------ check for verify mode
        if (!$isSalt) {
            return;
        } else {

            // ----- hash the data.  this does a pbkdf2, 4096 rounds, sha512 hash of the plain text
          	$hashVal = dnaHashsha512($plainText, $isSalt);

// ----- return a dna40 24 byte hash (48 dna40 chars)
return substr($hashVal, 0, 48) . '00';

        }

    }

}

// ----- get time int expressed as DNA epoch time, unix epoch subtract until 12/12/12
if (! function_exists('GetDNATime'))
{
    function GetDNATime() {

        return date('U') - UNIX2DNA;

    }

}

// ----- for all internal and transmission purposes, the prefix for fungible asset account numbers
//       should be removed (not required for processing and gums up the works)
if (! function_exists('CleanPrefix'))
{

	function CleanPrefix($eAsset) {

        if(($pos = strpos($eAsset, '-')) === FALSE)
            return $eAsset;
        else
            return substr($eAsset, $pos + 1);

    }
}

// ----- for wallet business, set a temporary message if user has session
if (! function_exists('TrSetFlash'))
{

	function TrSetFlash($message) {

		if (isset($_SESSION['session_data']))
			$_SESSION['session_data']['tr_user_flash'] = $message;

	}

}

// ----- for wallet business, get the temporary message stored in session
if (! function_exists('TrGetFlash'))
{

	function TrGetFlash($reset = TRUE) {

		if (isset($_SESSION['session_data'])) {
			$retval = $_SESSION['session_data']['tr_user_flash'];
            if ($reset)
    			$_SESSION['session_data']['tr_user_flash'] = '';
			return $retval;
		} else 
            return FALSE;
	}

}

?>
