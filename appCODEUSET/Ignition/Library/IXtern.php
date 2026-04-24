<?php
/*********************************************************************
    AUTHOR:      ME Williamson
    FILE:        IXtern.php
    VERSION:     See BaseController.php
    DESCRIPTION: Acquire external table data for display ignition forms
    COPYRIGHT:   2023
    FIRST REV:   15 Aug 2022
    LICENSE:     MIT

*********************************************************************/

//       reference external tables from ignition autoform
// 
//       Ex:
//       Design idea is placing within links paradigm
//
// 'ecompany_name' => [
//	'label' => strtoupper(lang('dna.ecompany')),
//	'fieldType' => 'text',
//	'dataType' => 'text',
//	'externalTable' => [
//		'link1Tables' => ['dna_ebonds', 'dna_ebond_issue'],
//		'link1Fields' => ['worldwide_issue_record', 'id'],
//		'link1FieldTypes' => ['binified', 'text'],
//		'link2Tables' => ['dna_ebonds_issue', 'dna_ecompanies'],
//		'link2Fields' => ['ebond_authority_entity', 'entity_number'],
//		'link2FieldTypes' => ['text', text']
//	 ]
// ]
//         LINK1            LINK2
// primary      secondary        tertiary
// table1    -> table2        -> table3
// dna_ebonds   dna_ebond_issue  dna_ecompanies
//
// LINKS PARADIGM: LINK1 AND LINK2
// link1 = table1->table2
// link2 = table2->table3
//
// Whenever the second/target table in a link is an amendable style table, must set this flag in externalTable db type:
//            'linkXdbTypes' => ['AMD', 'AMD'], << second important because must seek correct amendment


namespace Ignition\Library;

class IXtern
{

	protected $data;

    // ----- constructor for configuration
    public function __construct($data = '')
    {
		$this->data = $data;

	}

    // ----- set data as needed
    public function set($data)
    {
		$this->data = $data;

	}

	// ----- used in autoform to fetch external table columns
	public function FetchExternalData($field)
	{
        // ----- init
        $takeHighest1 = FALSE;

        // ----- link1
		$link1Table1 = $field['externalTable']['link1Tables'][0];
		$link1Table2 = $field['externalTable']['link1Tables'][1];
		$link1Field1 = $field['externalTable']['link1Fields'][0];
		$link1Field2 = $field['externalTable']['link1Fields'][1];

        if (isset($field['externalTable']['link1FieldTypes'])) {
    		$link1FieldType1 = $field['externalTable']['link1FieldTypes'][0];
	       	$link1FieldType2 = $field['externalTable']['link1FieldTypes'][1];
        } else {
    		$link1FieldType1 = 'text';
	       	$link1FieldType2 = 'text';
        }

        $externalSearchDataLink1 = $this->data[$link1Field1];

        // ----- check for blank search field
        if ($externalSearchDataLink1 == '')
            return lang('base.link') . '1 ' . strtolower(lang('dna.record_no_data'));

        // ----- link2
        if (isset($field['externalTable']['link2Tables'])) {
    		$link2Table1 = $field['externalTable']['link2Tables'][0];
    		$link2Table2 = $field['externalTable']['link2Tables'][1];
    		$link2Field1 = $field['externalTable']['link2Fields'][0];
    		$link2Field2 = $field['externalTable']['link2Fields'][1];

            if (isset($field['externalTable']['link2FieldTypes'])) {
        		$link2FieldType1 = $field['externalTable']['link2FieldTypes'][0];
    	       	$link2FieldType2 = $field['externalTable']['link2FieldTypes'][1];
            } else {
        		$link2FieldType1 = 'text';
    	       	$link2FieldType2 = 'text';
            }

            $isDoubleLink = TRUE;
        } else 
            $isDoubleLink = FALSE;

        // ----- if field is a virtual, then remove this tag
        if (!(($os = strrpos($field['name'], '__VIRTUAL__')) === FALSE))
            $field['name'] = substr($field['name'], 0, $os);

        // ----- check for specify amendment record
        if (isset($field['externalTable']['link1AMDField']) && $field['externalTable']['link1AMDField'] > 0) {
            $takeAMDRecordNum1 = $this->data[$field['externalTable']['link1AMDField']];
            $takeAMDRecordNum1 = ($takeAMDRecordNum1 < 1 ? 1 : $takeAMDRecordNum1);
            $takeHighest1 = FALSE;
        } else 
            $takeHighest1 = TRUE;

        // ----- on dna server, must incorporate ezone for binified (compacted FAN) asset numbers and determine stripe database
		if (DNASERVERENABLE) {

            // ----- get info about tables

            // ----- table 1
            //       skipping because irrelevent, not the external target, link1Table1

            // ----- table 2
            $link1Prefix2 = PrefixOrTable($link1Table2);
            if (!$link1Prefix2) {
                echo GetFlash() . _br();
                echo "Field name: " . $field['name'] . _br();
                halt('Call in IXtern.  Link1, Table2 table name, class prefix not found in prefix database.  Bad table name? ' . $link1Table2);
            }
            $link1ClassInfo2 = PrefixClassInfo($link1Prefix2);

            $countryID = FALSE;
            $link1dbType2 = (ValSet($link1ClassInfo2['flags'], 'AMD') ? 'AMD' : (ValSet($link1ClassInfo2['flags'], 'TRN') ? 'TRN' : "STD"));
            $eSector = $GLOBALS['ESECTOR'];

            // ----- for binary fields, transform to text
            if ($link1FieldType1 == 'binified') {
                $esdl1 = UnbinifyAsset($externalSearchDataLink1);
                $countryID = substr($esdl1, substr($esdl1, '.') + 1);
                $eSector = substr($esdl1, strpos($esdl1, '@') + 1, 4);
                if (!$externalSearchDataLink1 = ExtractFANHeight($esdl1))
                    halt("Improperly formated binified asset in field1: " . $link1Field1);
    
            } else if ($link1FieldType1 == 'dna40') {
                $externalSearchDataLink1 = bin2dna40($externalSearchDataLink1);

                // ----- Remove the leading prefix from domain (parent offset is less these 5 bits).  If target field is parent
                //       offset of an entity, then, convert the number into an integer less the 5 bit entity prefix for parent ID (CPR)
                if ($link1Field2 == 'dna_parent_height' && ValSet($link1ClassInfo2['flags'], 'ENTITY')) // changed from entity_number for link1Field1 April 3 25
                    $externalSearchDataLink1 = '1' . substr($externalSearchDataLink1, 1);

                $externalSearchDataLink1 = dna402dec($externalSearchDataLink1);

            }

           // ----- set flag.  matters because of offset issue between entities in root vs home stripe
           if (ValSet($link1ClassInfo2['flags'], 'PEER')) {

                // ----- must only search on 'dna_parent_height' for peers in the amendable table
                if ($link1Field2 != 'dna_parent_height')
                    halt("Externally referenced variable " . $field['name'] . " must reference dna_parent_height as the value in Link1Fields second value");

                if ($link1FieldType1 != 'dna40')  // remove prefix if not already done above
                    $externalSearchDataLink1 = '1' . substr($externalSearchDataLink1, 1);

                $isPeer = TRUE;
                $countryID = 'ES';

            } else
                $isPeer = FALSE;

            // ----- get dna table name for currect ecountry/stripe database, must include the field name BECAUSE
            //       if this is a 'worldwide' field will then need to get the correct db, not just a local db call
            $dnaDB = Table2dbName($link1Table2, $eSector, $countryID, $link1ClassInfo2);

            // ----- set up for direct search of amendable table
            if ($link1dbType2 == 'AMD')
                $link1Table2 = $link1Table2 . '_amend';

	        $db = \Config\Database::connect($dnaDB);

		} else {
	        $db = \Config\Database::connect();
        }

        $builder = $db->table($link1Table2);
        $resultsObj = $builder->getWhere([$link1Field2 => $externalSearchDataLink1])->getResult('array');

        // ----- if no result, return
        if (empty($resultsObj))
            return lang('base.link') . '1 ' . strtolower(lang('dna.record_not_found'));

        // ----- in the case of peers and their roots, must check to see if need to seek the data from the home entity record.  This is 
        //       only necessary if field is missing from primary record search above (ergo, data is contained in home record).  Requires
        //       that we go to root record first, then locate the home record using the binified home asset id number.  Added 
        //       $isDoubleLink conditional as this was catching double links where the ultimate field was not in home/root peer records
        //       This means that in case of a peer link1 field, it must be in the peer root db, otherwise to auto skip to double
        if ($isPeer && !$isDoubleLink && $link1Field2 == 'dna_parent_height' && !isset($resultsObj[0][$field['name']])) {

            // ----- locate the entity home record 
            $assetFAN = UnbinifyAsset($resultsObj[count($resultsObj) - 1]['entity_home_record']);
            $countryID1 = substr($assetFAN, strpos($assetFAN, '.') + 1);
            $eSector1 = substr($assetFAN, strpos($assetFAN, '@') + 1, 4);
            if (!$parentHeight1 = ExtractFANHeight($assetFAN))
               halt("Improperly formated binified asset in field2: " . $link1Field2);

            $classInfo1 = PrefixClassInfo(substr($assetFAN, 0, strpos($assetFAN, '-') + 1));
            $table1 = $classInfo1['table'] . '_amend';

            $dnaDB1 = Table2dbName($table1, $eSector1, $countryID1, $classInfo1);
	        $db1 = \Config\Database::connect($dnaDB1);
            $builder1 = $db1->table($table1);

            $resultsObj = $builder1->getWhere(['dna_parent_height' => $parentHeight1])->getResult('array');

        }

        // ----- in the case of amend (dna), take highest record, unless designated by takeAMDRecordNum
        if (!empty($resultsObj)) {
            if ($takeHighest1)
        		$externalRecord = (!empty($resultsObj) ? $resultsObj[count($resultsObj) - 1] : []);
            else {
                if ($takeAMDRecordNum1 < 1)
                    $externalRecord = [];
                else
                	$externalRecord = (!empty($resultsObj) ? $resultsObj[$takeAMDRecordNum1 - 1] : []); // convert num to offset
            }
        } else 
            $externalRecord = [];

        // **********************************************************************
        // ------ if doing a double link
		if ($isDoubleLink && !empty($externalRecord)) {

            // ----- verify received data for link
			$externalSearchDataLink2 = (isset($externalRecord[$link2Field1]) ? $externalRecord[$link2Field1] : '');

            // ----- check for blank search field
            if ($externalSearchDataLink2 == '')
                return lang('base.link') . '2 ' . strtolower(lang('dna.record_no_data'));

            // ----- check for specify amendment record
            if (isset($field['externalTable']['link2AMDField']) && $field['externalTable']['link2AMDField'] > 0) {
                $takeAMDRecordNum2 = $this->data[$field['externalTable']['link2AMDField']];
                $takeAMDRecordNum2 = ($takeAMDRecordNum2 < 1 ? 1 : $takeAMDRecordNum2);
                $takeHighest2 = FALSE;
            } else 
                $takeHighest2 = TRUE;

    		if (DNASERVERENABLE) {

                // ----- get info about tables
                // ----- table 1
                $link2Prefix1 = PrefixOrTable($link2Table1);
                if (!$link2Prefix1) {
                    echo GetFlash() . _br();
                    echo "Field name: " . $field['name'] . _br();
                    halt('Call in IXtern.  Link2, Table1 table name, class prefix not found in prefix database.  Bad table name? ' . $link2Table1);
                }
                $link2ClassInfo1 = PrefixClassInfo($link2Prefix1);

                // ----- table 2
                $link2Prefix2 = PrefixOrTable($link2Table2);
                if (!$link2Prefix2) {
                    echo GetFlash() . _br();
                    echo "Field name: " . $field['name'] . _br();
                    halt('Call in IXtern.  Link2, Table2 table name, class prefix not found in prefix database.  Bad table name? ' . $link2Table2);
                }
                $link2ClassInfo2 = PrefixClassInfo($link2Prefix2);

                $countryID = FALSE;
                $link2dbType2 = (ValSet($link2ClassInfo2['flags'], 'AMD') ? 'AMD' : (ValSet($link2ClassInfo2['flags'], 'TRN') ? 'TRN' : "STD"));
                $eSector = $GLOBALS['ESECTOR'];

                // ----- for binary fields, transform to text
                if ($link2FieldType1 == 'binified') {
                    $esdl2 = UnbinifyAsset($externalSearchDataLink2);
                    $countryID = substr($esdl2, strpos($esdl2, '.') + 1);
                    $eSector = substr($esdl2, strpos($esdl2, '@') + 1, 4);
                    if (!$externalSearchDataLink2 = ExtractFANHeight($esdl2))
                        halt("Improperly formated binified asset in field1: " . $link2Field1);

                } else if ($link2FieldType1 == 'dna40') {
                    $externalSearchDataLink2 = bin2dna40($externalSearchDataLink2);

                    // ----- Remove the leading prefix from domain (parent offset is less these 5 bits).  If target field is parent
                    //       offset of an entity, then, convert the number into an integer less the 5 bit entity prefix for parent ID (CPR)
                    if ($link2Field2 == 'dna_parent_height' && ValSet($link2ClassInfo2['flags'], 'ENTITY')) // changed from entity_number for link1Field1 April 3 25
                        $externalSearchDataLink2 = '1' . substr($externalSearchDataLink2, 1);

                    $externalSearchDataLink2 = dna402dec($externalSearchDataLink2);

                }
    
               // ----- set flag. matters because of offset issue between entities in root vs home stripe
               if (ValSet($link2ClassInfo2['flags'], 'PEER')) {
    
                    // ----- must only search on 'dna_parent_height' for peers in the amendable table
                    if ($link2Field2 != 'dna_parent_height')
                        halt("Externally referenced variable " . $field['name'] . " must reference dna_parent_height as the value in Link1Fields second value");
    
                    if ($link2FieldType1 != 'dna40')  // remove prefix if not already done above
                        $externalSearchDataLink2 = '1' . substr($externalSearchDataLink2, 1);
    
                    $isPeer = TRUE;
                    $countryID = 'ES';
    
                } else
                    $isPeer = FALSE;
    
                // ----- get dna table name for currect ecountry/stripe database, must include the field name BECAUSE
                //       if this is a 'worldwide' field will then need to get the correct db, not just a local db call
                $dnaDB = Table2dbName($link2Table2, $eSector, $countryID, $link2ClassInfo2);
    
                // ----- set up for direct search of amendable table
                if ($link2dbType2 == 'AMD')
                    $link2Table2 = $link2Table2 . '_amend';
    
    	        $db = \Config\Database::connect($dnaDB);
    
    		} else {
    	        $db = \Config\Database::connect();
            }

            $builder = $db->table($link2Table2);
            $resultsObj = $builder->getWhere([$link2Field2 => $externalSearchDataLink2])->getResult('array');

            // ----- if no result, return
            if (empty($resultsObj))
                return lang('base.link') . '2 ' . strtolower(lang('dna.record_not_found'));
    
            // ----- in the case of peers and their roots, must check to see if need to seek 
            //       the data from the home entity record.  This is only necessary if field 
            //       is missing from primary record search above (ergo, data is contained in 
            //       home record).  Requires that we go to root record first, then locate the 
            //       home record using the binified home asset id number.
            if ($isPeer && $link2Field2 == 'dna_parent_height' && !isset($resultsObj[0][$field['name']])) {
    
                // ----- locate the entity home record 
                $assetFAN = UnbinifyAsset($resultsObj[count($resultsObj) - 1]['entity_home_record']);
                $countryID1 = substr($assetFAN, strpos($assetFAN, '.') + 1);
                $eSector1 = substr($assetFAN, strpos($assetFAN, '@') + 1, 4);
                if (!($parentHeight1 = ExtractFANHeight($assetFAN)))
                   halt("Improperly formated binified asset in field4: " . $link2Field2);

                $classInfo1 = PrefixClassInfo(substr($assetFAN, 0, strpos($assetFAN, '-') + 1));
                $table1 = $classInfo1['table'] . '_amend';
    
                $dnaDB1 = Table2dbName($table1, $eSector1, $countryID1, $classInfo1);

    	        $db1 = \Config\Database::connect($dnaDB1);
                $builder1 = $db1->table($table1);
    
                $resultsObj = $builder1->getWhere(['dna_parent_height' => $parentHeight1])->getResult('array');
    
            }
    
            // ----- in the case of amend (dna), take highest record, unless designated by takeAMDRecordNum
            if (!empty($resultsObj)) {
                if ($takeHighest2)
            		$externalRecord = (!empty($resultsObj) ? $resultsObj[count($resultsObj) - 1] : []);
                else {
                    if ($takeAMDRecordNum2 < 1)
                        $externalRecord = [];
                    else {
                        if (!isset($resultsObj[$takeAMDRecordNum2 - 1]))
                            halt('Error IXtern L372');
                        $externalRecord = (!empty($resultsObj) ? $resultsObj[$takeAMDRecordNum2 - 1] : []);
                    }
                }
            } else 
                $externalRecord = [];

    	} // DOUBLE

		// ----- return data element
		if (isset($externalRecord[$field['name']])) {

			// ----- check for must modify ultimate display type 
			if (isset($field['externalTable']['linkFinalType']))
				$externalRecord[$field['name']] = dnaStore2View($externalRecord[$field['name']], 'text', $field['externalTable']['linkFinalType']);

			return $externalRecord[$field['name']];
		} else {
			return lang('base.external_not_found');
        }
	}

}

?>

