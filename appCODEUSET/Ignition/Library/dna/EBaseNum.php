<?php

/*********************************************************************
    AUTHOR:      Mark E. Williamson
    FILE:        EBaseNum.php
    VERSION:     Ignition (see BaseController.php)
    DESCRIPTION: Ignition special numeric data types required for DNA
                 network storage and display
    COPYRIGHT:   2021
    FIRST REV:   31 Aug 2021
    LICENSE:     MIT

    In the blockchain 2 design there are two classes of numbers:

    Class 1: These are binary numbers based on CharBit

    Class 2: These are specialized numbers based on BaseNum

    * Blockchain 2 data types:
    *   introduces "emini", a 5 bit word that allows for implementation of Rex32 which
    *   is used: extensively to build the DNA40 throughout Roboto and DNA, in
    *   conjunction with "enano", a 3 bit word paired with the emini.  Total 8 bits.
    *   This builds a profanity proof, Rex32 + Rex8 alternating pair number, that
    *   may be easily displayed, transcribed and transmitted/stored in a single byte.
    *   See Rex32, Rex8 and DNA40 encoding Standards document for more information
    *   https:// . EARTHICA . /standards/numericencoding.pdf
    *   EARTHICA NUMBERS DOMAINS (begins with a 5 bit designator letter):
        DNA40 FIRST COLUMN CHARACTER SET (REX32)
        0 0      9 9    18 I    27 S
        1 1     10 A    19 J    28 T
        2 2     11 B    20 K    29 U
        3 3     12 C    21 L    30 V
        4 4     13 D    22 M    31 W
        5 5     14 E    23 N
        6 6     15 F    24 P
        7 7     16 G    25 Q
        8 8     17 H    26 R
        DNA40 SECOND COLUMN CHARACTER SET (REX8)
        0 0
        1 1
        2 2
        3 3
        4 4
        5 5
        6 6
        7 7
    VARIOUS DOMAINS OF DNA40 ASSETS/NUMERS, FIRST COLUMN (Rex32) ORDINAL ASSIGNMENTS:
        Ordinary Assets:
            0, 1, 2, 3, 4, 5, 6, 7, 8, 9, A, B, D, F, H, J, K, L, M, N, P, Q, S, *T, U, V, W
        "REGIC" numbers (these assigned numbers are unique throughout the entire DNA network):
           ES (Earthica Society restricted):
               R
           Enterprises (chartered entities operating as companies on DNA):
               E
           Governments (recognized states on Earth):
               G
           Individuals (citizen internal identification, permanent):
               I
           Commercial (citizen, shared publically, replaceable):
               C

			PROPOSAL CONSIDERATON:
				Temporary number applied to assets where user is without a number
					*T

    ENs AND ADDRESSING:
       DNA40: The basic paired number with rex32+rex8, making up 1 byte
	   EN: DNA objects, DNA40(10 written digits, 5 stored bytes) used to identify 
          assets/people/companies/governments within the DNA system 
          inclusive of all EN assets/numbers including REGI reserved first character numbers
       ECompany: EN assigned to companies (E ordinal), and governments (G) with (R) limited to Earthica entities.
       INumber: EN assigned to individuals, always begins with I - number keep private to the individual
       Earthizen: EN assigned to individuals for individual commercial activities, always begins with C -
          share for work and network activities including asset ownership
       ERobot: EN assigned to company robots
	   eStripe: DNA40 of only two bytes (4 written columns) 
	   eZone: All assets/persons/companies require country+stripe to make the fqal (fully qualified address location)
        Country + Stripe: @ET.A001.  Although REGI numbers are unique worldwide.
       eAsset: Non REGI assets which require an eZone in order to be located as they are not ww unique
          Country + Stripe.  Ex: I000000001@earthtopia.1
       eBag: EN, however the first two bytes are presumed to be stored elsewhere within processing.  The
          final 3 bytes only, that will be used for specialized internal processing where it is already
          established the two byte file name extension which is the EN prefix
    

*********************************************************************/


namespace Ignition\Library\dna;

class EBaseNum
{
	public $value = FALSE;
	public $message = '';
    public $displayValue = '';
	protected $wordSize = 8;
	protected $base = 10;
	protected $caseSensative = FALSE;
	protected $sybolSet;

	function add($val = 1)
	{

	}

	function out()
	{
		return $this->value;
	}

	// ----- convert native string value to binary bit values for storage and transport
	function streamOut()
	{
		return dna402bin($this->value);
	}

	// ----- step through valid list of object type values in valuesObject and validate
	//       each entry stored in value as being on symbol set list of valid characters
	function validateVal($valCheck, $symSet = '__NOTSET__')
	{

		if ($symSet == '__NOTSET__')
			$symSet = $this->sybolSet; 

		$valuesObject = $GLOBALS['SYMBOL_SETS'][$symSet];

	    // ----- setup loop to validate every digit
	    for ($count = 0; $count < strlen($valCheck); $count ++) {
			$checkVal = substr($valCheck, $count, 1);
	        if(str_contains($valuesObject, $checkVal) === FALSE)
	            return $count + 1;
		}

	    return 0;

	}


/*
	// ----- given qstring value in object base, going from right to left, convert to base 10 number
	//       (does not modify core settings of object)
	//       PENDING DO FRACTIONS FOR DIVISION
	public function toBase10()
	{
		// ----- init 
	    double powerBase, base10Val, tot10Val = 0, decimalVal = 0;
	    short numLen = n.length(), decimalLoc = n.indexOf(".", 0);
	    QString f = "";
	    QString valuesObject = SYMBOL_SETS[symbolSet][1];

	    // ----- divide the number into 2 pars, integer and fraction
	    //if (decimalLoc > 0) {
	    //    f = n.mid(decimalLoc + 1, numLen - decimalLoc - 1);
	    //    n = n.mid(0, decimalLoc);
	    //    numLen = decimalLoc;
	    //}

	    // ----- setup loop to add up each of the columns, converting them to base10
	    for (short count = 0; count < numLen; count ++)
	    {

	        powerBase = pow(base, count);

	        // ----- using current col in ascii, obtain numeric value and convert to base 10
	        base10Val = valuesObject.indexOf(n.at(numLen + (0 - count - 1)), 0);

	        // ----- check for error
	        if (base10Val == NOTFOUND)
	            return (double) NOTFOUND;

	        // ----- use conversion algorithm to calc and add this col b10 value
	        tot10Val += base10Val * powerBase;

	    }

	        // ----- check for decimal
	        //if (decimalLoc > 0) {

	            //decimalVal = toBase10(f, false);


	    //cout << "\n       ***** toBase10() vals:" << endl;
	    //cout << "         n: " << n.toStdString() << endl;
	    //cout << "         f: " << f.toStdString() << endl;
	    //cout << "decimalVal: " << decimalVal << endl;
	    //cout << "    ***** END" << endl;
	    //    }

	    return tot10Val;
	}

}
*/

/*
// ----- DNA40 number consisting: 2 x DNA40S, 4 columns number as a part of the 
//       DNA network and eZone.  Designates the stripe within the country.
class eStripe : public DNA40
{
    public :
        QString value;
        DNA40 d1;
        DNA40 d2;

        eStripe(QString sn = "[NULL]") {
            setStripe(sn);
        }
        bool setStripe(QString sn);
        bool plusOne();
        string number(void);
        QString getSymbols(void) {
        }
            return QObject::tr("DNA composite Number, eStripe\nBuilt as follows, Rex32: 0123456789ABCDEFGHIJKLMNPQRTUVWX\nRex8: 01234567\nStripe is composite: Rex32:Rex8:Rex32:Rex8:Rex32:Rex8\nEx: A2C4G5\nStripe number assigned by DNA root authority, Earthica Society, and is part of the DNA network.  This number is only required for DNA apps.  In many cases it can be left blank unless DNA main net access is required.\nThis code may be obtained for no charge under many cicumstances.\nVist the website " . EARTHICA . "/incorporate to obtain and lean more about a Stripe.");
};

// ----- stripe address location for entire DNA network
//       complex, composit number consisting of 2 composit numbers
class eZone : DNA40
{
    public :
        Country country;
        eStripe stripe;
        QString val = "";

        eZone(QString countryCode = "[NULL]", QString stripeNum = "[NULL]") {
            setZone(countryCode, stripeNum);
        }
        bool setZone(QString countryCode, QString stripeNum) {
 
            FLASH_MESSAGE = "";
 
            if (!country.set(countryCode)) {
                val = "INVALID";
                setWarn("Country code invalid.\n" + countryCode + "\nFor a list of valid country codes type show countries in Robot Builder or --show-country at command line.");
                return false;
            }

            if (!stripe.setStripe(stripeNum)) {
                val= "INVALID";
                return false;
            }

            val = country.getName() + ":" + stripe.value;
            return true;
        }
        QString getSymbols(void) {
            return "DNA composite Number, eZone: Country + Stripe\n.  Country must be in a two letter code and stripe must be 4 digits of DNA40 x 2 numbers (ex: A0R5).  Zone is required to positively locate most assets on the DNA network.\n" + dna40Explained;
        }

};

// ----- 40 bit, 1T domain.
// IDEALLY CONTAINS DISTINCTIONS, such as the REGI class logic
// this could distinguish the numbers
class EN : public DNA40
{
    public :
        QString value;
        DNA40 d1;
        DNA40 d2;
        DNA40 d3;
        DNA40 d4;
        DNA40 d5;

        EN(QString en = "[NULL]") {
            if (en == "[NULL]")
                return;
            setEN(en.toUpper());
        }
        bool setEN (QString en) {
            if (!d1.set(en.mid(0,2)))
                return false;
            if (!d2.set(en.mid(2,2)))
                return false;
            if (!d3.set(en.mid(4,2)))
                return false;
            if (!d4.set(en.mid(6,2)))
                return false;
            if (!d5.set(en.mid(8,2)))
                return false;

            value = d1.value + d2.value + d3.value + d4.value + d5.value;

            return true;
        }
        string number(void);
};

// ----- 5 byte number of type DNA40, must begin with R, E, or G
class eCompany : public EN
{
public :
    eCompany(QString ec = "[NULL]") {
        if (ec == "[NULL]") {
            value = ec;
            return;
        }
        Set(ec.toUpper());
    }
    bool Set(QString ec) {
        
        if (ec == "[NULL]") {
            value = "NONE";
            return false;
        }

        FLASH_MESSAGE = "";
                    
        // ----- verify beginning letter
        if ((QString) ec.at(0) != "R" && (QString) ec.at(0) != "E" && (QString) ec.at(0) != "G") {
            value = "INVALID";
            setWarn("eCompany value set with non REG asset ordinal.  Example correct: E100000001\nRestricted to group domain beginning with R (restricted), E (enterprise), G (government).\n" + dna40Explained);
            return false;
        };
        return setEN(ec);
    }

};

// ----- always begin with I (r32)
class INumber : public EN
{
public :
    INumber(QString ec = "[NULL]") {
        if (ec == "[NULL]") {
            value = ec;
            return;
        }
        Set(ec.toUpper());
    }
    bool Set(QString ec) {
    
        FLASH_MESSAGE = "";
                    
        // ----- verify beginning letter
        if ((QString) ec.at(0) != "I") {
            value = "INVALID";
            setWarn("INumber value set with non I asset ordinal.  Example correct: I100000001\nRestricted to group domain beginning with I (individual).\n" + dna40Explained);
            return false;
        };
        return setEN(ec);
    }
};

// ----- always begin with I (r32)
class cNumber : public EN
{
public :
    cNumber(QString ec = "[NULL]") {
        if (ec == "[NULL]") {
            value = ec;
            return;
        }
        Set(ec.toUpper());
    }
    bool Set(QString ec) {

        FLASH_MESSAGE = "";

        // ----- verify beginning letter
        if ((QString) ec.at(0) != "C") {
            value = "INVALID";
            setWarn("cNumber value set with non C asset ordinal.  Example correct: C100000001\nRestricted to group domain beginning with C (commercial belonging to individual).\n" + dna40Explained);
            return false;
        };
        return setEN(ec);
    }
};

// ----- DNA40 asset number consisting: 5 x DNA40S, 10 columns number
//       !REGIC
class eAsset : public EN
{
    public :
        QString value;
        EN asset;

        eAsset(QString en = "[NULL]") {
            if (en == "[NULL]") {
                value = en;
                return;
            }
            Set(en);
        }

        bool Set(QString en) {

            FLASH_MESSAGE = "";

            // ----- verify beginning letter en asset !REGIC
            if ((QString) en.at(0) == "R" || (QString) en.at(0) == "E" || (QString) en.at(0) == "G" || (QString) en.at(0) == "I" || (QString) en.at(0) == "C") {
                value = "INVALID";
                setWarn("eAsset value begins with one of the DNA reserved REGIC ordinal values.  Correct example: A100000001");
                return false;
            }
            
            // ----- set en
            if (!asset.setEN(en)) {
                FLASH_MESSAGE += " Set eAsset EN failed.  Check DNA40 values.";
                return false;
            }

            value = asset.value;
            return true;
        }

};

// ----- DNA40 number consisting: 3 x DNA40S, 6 columns number
//       32 class asset number by stripe !REGIC
class eAddress : public EN
{
    public :
        QString value;
        EN en;
        eZone zone;

        eAddress() {
            value = "NOTSET";
        }

        bool Set(QString easset = "NOTSET") {
        
            // ----- check for no set
            if (easset == "NOTSET")
                return false;
        
            FLASH_MESSAGE = "";
            
            if (!zone.setZone(easset.mid(11, 2), easset.mid(14, 4)))
                return false;

            if (!en.setEN(easset.mid(0, 10)))
                return false;

            value = en.value + "@" + zone.country.code + "." + zone.stripe.value;

            return true;
        }

        bool Set(QString eno, QString ct, QString st) {

            FLASH_MESSAGE = "";
            
            if (!zone.setZone(ct, st))
                return false;

            if (!en.setEN(eno))
                return false;

            value = en.value + "@" + zone.country.getName() + "." + zone.stripe.value;

            return true;

        }

};

*/

}

?>