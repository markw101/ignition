<?php
/*********************************************************************
    AUTHOR:      ME Williamson
    FILE:        BaseModel.php
    VERSION:     See BaseController.php
    DESCRIPTION: Extension of CI Model.php
    COPYRIGHT:   2022
    FIRST REV:   15 Aug 2022
    LICENSE:     MIT

*********************************************************************/

namespace Ignition\Base;

//       base class model for Ignition
// 
//       File management system acts similar to CI pagination system in that files 
//       listed are "paginated" and displayed.  They may then be accessed and modified
//       according to speicific application implementation.
//
//       A security feature added to the file management system is the files are
//       are not accessed directly.  They are stored on the server, but utilzing a
//       random name string.  This string must then be refereed in a table which 
//       returns the true file name.  In this manner a hacker will have trouble 
//       arbitrarily accessing files on the server for execution as the file will
//       not be directly accessible by the server.  The file must be referenced utiliing
//       the table name look up system 
//
//       Another security feature is that, if set up correctly, image files which are 
//       to be accessed directly by the server, may only be accessed after the containing
//       directory is mounted read only.
//
//       A better client server design would be to return a page in total as a
//       singular file wich is a dynamically created conglomeration of elements into a
//       singular entity.  That way the browser would not have a list of individual page
//       elements which may be directly accessed by, essentially, reaching into the
//       sever and accessing that element.  This would mitigate a great many attacks.
//
//       For a list of CI validation rules and parameters see */

use Exception;
use CodeIgniter\Model;
use MySQLi;
use CodeIgniter\Database\BaseBuilder;

// ----- base class model for Ignition
class BaseModel extends Model
{
    // ----- primary table for model, applies to buit-in crud methods
    protected $table;
	protected $table2 = FALSE;						     // name of child (secondary) table 
	public $table3 = FALSE;						     // name of child (tertiary) table 
    // ----- db types: STD (standard singular table);
    //       REL (standard relational tables, ex: invoices, blog langs);
    //       AMD (DNA amendable relation with record changes history);
    //       TRN (DNA simple transactional relational, seconary database contains tables of singular type);
    //       COM (DNA complex/collections), a collection of assets where transactions are expressed as 
    //                                      amendable records.  Each asset type is contained within an account
    //                                      secondary database which contains tables of various types (erobot 
    //                                      records within parent).  The child of this amendable child contains
    //                                      the transactions.
    public $dbType = "STD";
    public $dbType2 = FALSE;          // COM has AMD as secondary database
    // ----- column that uniquely identifies the records in this table 
    //       ex uses: find()
    protected $primaryKey         = 'id';
    // ----- specifies if the table uses an auto-increment feature for 
    //       $primaryKey.  if set false, make sure primary key set to unique
    protected $useAutoIncrement   = true;
    // ----- valid values are array (the default) and object
    protected $returnType         = 'array';
    // ----- delete() method calls will set deleted_at in the database, instead of 
    //       actually deleting the row.  requires deleted_at col as datetime
    protected $useSoftDeletes     = false;
    // ----- set to fields that can be set during save() insert() update() methods
    protected $allowedFields      = [];
    // ----- determines whether the current date is automatically added to 
    //       all inserts and updates.  requires that the table have columns 
    //       created_at and updated_at as datetime
    protected $useTimestamps      = true;
    // ----- use for data record create timestamp
    //protected $createdField       = '';
    // ----- use for data record updated timestamp
    protected $updatedField       = 'updated_at';
    // ----- use for data record deleted timestamp
    //protected $deletedField       = '';
    // ----- valid options are: 'datetime' 'date' or 'int'
    protected $dateFormat         = 'datetime';
    // ----- either an array of validation rules or a string containing 
    //       the name of a validation group
    protected $validationRules    = [];
    // ----- array of custom error messages used during validation
    protected $validationMessages = [];
	protected $validationErrors = [];
    // ----- skip validation during all inserts and updates
    protected $skipValidation     = false;
	public $DBGroup = 'default';
	public $data;									// primary (parent) current record data storage
    public $showOnly = [];                          // these are fields allows to be shown, but restricted by ci for edit
    public $showOnly2 = [];

    // ----- file management related variables 
    protected $filePath = UPLOADS_FOLDER; // base path in local server directory for file storage
    protected $flags = '';         // return flags from operations such as FilePaginate (see ignition FILE MANAGER/PAGINATOR documentation)
    protected $addRandom = true;   // add a random number of 6 chars to all uploaded file name
    protected $IDField = 'id';     // column in table to use num in file name as prefix to link to a record
    protected $butttonText = "Add Files"; // button text in file drop zone (use static call or __construct() in model to set language)
    protected $subDirectory = false;      // the subdirectory beneath $filePath where to store files for this model (extends).  also acts as flag in autoform

    // ----- constructor for configuration
    public function __construct($setTable = "", $allowField = "", $selectDB = '')
    {
		// ----- select database 
		if ($selectDB == '')
			$this->DBGroup = $GLOBALS['DBGROUP'];
		else 
			$this->DBGroup = $selectDB;

		// ----- check for dna, tell ci do not need these (can be overridden by changing in implemented object)
		if ($GLOBALS['DNAFORM']) {
		    $this->createdField       = '';
		    $this->updatedField       = '';
		    $this->deletedField       = '';
		}

        PARENT::__construct();

        $this->butttonText = lang('base.add_photos');

		if ($setTable !== "")
			$this->table = $setTable;
		if ($allowField !== "")
			$this->allowedFields = $allowField;

    }

    function SetTable2 ($tableName) {

        $this->table2 = $tableName;
    }

    function SetTimeStamps ($setVal) {

        $this->useTimestamps = $setVal;
    }

    function SetAllowed ($allowed) {

        $this->allowedFields = $allowed;
    }

	function SetValidation ($errors) {

		$this->validationErrors = $errors;
	}

	function SetFieldLables ($fl) {
		$this->fieldLabels = $fl;
	}

	function escape($str = "")
	{
		$retVal = mysqli_real_escape_string($str, $this->dbLink);
		if (len($retVal) > 99)
			$retVal = substr($retVal, 0, 100);

		// ----- return 
		return $retVal;
	}

	// ------ basic select.  select records for a given table
	function sqlSelect($retField, $table, $criteria = [], $groupby = "", $orderby = "", $limit = "ALL" )
	{
		// ----- unify limit
		IF ($limit == "ALL")
			$limit = 0;
		else
			$limit = max($limit, 1);

		$db      = \Config\Database::connect();
		$builder = $db->table($table);

		// ----- check for wildcard return fields
		if ($retField[0] == "*") {
			$result = $builder->orderBy($orderby)->groupBy($groupby)->getWhere($criteria, (int) $limit)->getResult('object');
		} else {
			// ----- init
			$fields = implode(",", $retField);
			$result = $builder->select($fields)->orderBy($orderby)->groupBy($groupby)->getWhere($criteria, (int) $limit)->getResult('object');
		}

		return $result;

	}

	// ----- return 1 record with ci find command
    //       found error in CI: if call find with a complete record, can return an array within in array
	public function IFind($id)
	{

        $data = $this->find($id);

        if (isset($data[0]['id']))
            return $data[0];
        else 
            return $data;

    }

	// ----- update model table
	public function IUpdate($id,  $data, $autoForm = FALSE)
	{

		// ----- assumes using iform(), this fixes checkboxes.  see w3 issue williamson.software website
		$data = $this->FixCheckBoxes($data);

		// ----- remove external fields
		$data = $this->RemoveShowOnly($data);

		// ----- remove any remaining virtual fields (some times show only binary fields get their name changed)
		$data = $this->RemoveVirtual($data);

        // ----- remove amendables
		$data = $this->RemoveAmend($data);

        // ----- remove com db type fields
        //       these fields are set to include __TRANS__ string in field name
        if ($this->dbType == "TRN") {
            // ----- unset all __TRANS__ prefixed vars names
            foreach ($data as $key => $vale) {
                if (strlen($key) > 9 && substr($key, 0, 9) == '__TRANS__')
                    unset($data[$key]);
            }
        }
		// ----- convert binary data fields for storage
		$data = $this->ConvertBinaryFields($data, $autoForm);
		if (!empty($this->validationErrors)) {

			if (isset($data['submit']))
				unset($data['submit']);

			$this->data = $data;

			return FALSE;
		}

        // ----- if utilizing saved allowed fields, restore before update (used in AMD)
        if ($this->parentAllowed)
            $this->allowedFields = $this->parentAllowed;

        // ----- check for AMD type tables used in DNA (this is a dual table record system where
        //       child records store the updates made to the parent table.  Requires BaseModelRelate)
        if ($this->table2 && $this->dbType == "AMD") {

            // ----- save the form fields corresponding to the child table to the current child record 
            $data = $this->ProcessAMD($data, $autoForm);

            // ----- possibly restore database
            if (isset($saveDBGroup))
                $GLOBALS['DBGROUP'] = $saveDBGroup;

			if (isset($data['submit']))
				unset($data['submit']);

    		if (!empty($this->validationErrors)) {
    			return FALSE;
    		} else {

                // ----- if no savable fields in allowedFields in parent record, we are finished
                if ($this->NoAllowedSaveAny($data))
                    return TRUE;

                // ----- save amd parent record
        		$return_val = $this->update($id,  $data);
        		$this->validationErrors = $this->errors();
        		if (empty($this->validationErrors))
        			return $return_val;
        		else 
                    return FALSE;
            }

        }

		// ----- call ci update
		$return_val = $this->update($id,  $data);
		$this->validationErrors = $this->errors();

		if (!empty($this->validationErrors)) {

			if (isset($data['submit']))
				unset($data['submit']);

			$this->data = $data;

			return FALSE;
		}

        // ------ check for relational table system
  		return $return_val;

	}

    // ------ check allowedFields list against fields in data.  if no matches return affirm, nothing to save
    public function NoAllowedSaveAny($data) {

        // ------ set up loop
        foreach ($this->allowedFields as $key => $value ) {

            if (is_array($value))
                continue;

            if (isset($data[$value]))
                return FALSE;
        }

       return TRUE;
    }

	// ----- append to model table
	public function IInsert($data, $autoForm = FALSE)
	{

		if (isset($data['submit']))
			unset($data['submit']);

		// ----- assumes using iform(), this fixes checkboxes.  see w3 issue williamson.software website
		$data = $this->FixCheckBoxes($data);

		// ----- remove external fields
		$data = $this->RemoveShowOnly($data);

		// ----- remove virtual fields
		$data = $this->RemoveVirtual($data);

        // ----- remove amendables
		$data = $this->RemoveAmend($data);

        // ----- remove com db type fields
        //       these fields are set to include __TRANS__ string in field name
        if ($this->dbType == "TRN") {
            // ----- unset all __TRANS__ prefixed vars names
            foreach ($data as $key => $vale) {
                if (strlen($key) > 9 && substr($key, 0, 9) == '__TRANS__')
                    unset($data[$key]);
            }
        }

		// ----- convert binary data fields for storage
		$data = $this->ConvertBinaryFields($data, $autoForm);	
		if (!empty($this->validationErrors)) {

			$this->data = $data;
			return FALSE;
		}

        // ----- if utilizing saved allowed fields, restore before insert (used in AMD)
        if ($this->parentAllowed)
            $this->allowedFields = $this->parentAllowed;

        // ----- check for AMD type tables (this is a dual table record system where
        //       child records store the updates made to the parent table)
        if ($this->table2 && $this->dbType == "AMD") {

            // ----- save the form fields corresponding to the child table to the current child record 
            $data = $this->ProcessAMD($data, $autoForm, TRUE);

    		if (!empty($this->validationErrors))
                return FALSE;

            // ----- if this is a DNAForm, set the semaphore
            if ($GLOBALS['DNAFORM'])
                $data['dna_semaphore'] = 1;

    		// ----- call ci insert
    		$data['id'] = 0;			// set zero because temp ID non numeric some times 
    		$return_val = $this->insert($data);
    		$this->validationErrors = $this->errors();

    		if (!empty($this->validationErrors))
    			return FALSE;

            // ----- now, return to new child record and set the parent id
    		$childModel = new \Ignition\Base\BaseModel($this->table2, [$this->fieldNameChildRelated]);
            $childModel->update($this->currentChildRecord, [$this->fieldNameChildRelated => $this->getInsertID()]);
    		$this->validationErrors = $childModel->errors();

            // ----- return true if no errors
    		return empty($this->validationErrors);

        }

		// ----- call ci insert
		$data['id'] = 0;			// set zero because temp ID non numeric some times 
		$return_val = $this->insert($data);
		$this->validationErrors = $this->errors();

		return $return_val;
	}

	// ----- unset all remaining virtual fields
    function RemoveVirtual($data)
    {
		// ----- init
		$dataKeys = array_keys($data);

		// ----- set up loop to find
		foreach($dataKeys as $dataKey) {

			// ----- check to see if virtual
			if (ValSet($dataKey, "__VIRTUAL__")) {

				// ----- unset virtual field
				unset($data[$dataKey]);
			}

		}

		return $data;
	}

	// ----- unset all showOnly fields from data
    function RemoveShowOnly($data)
    {
		// ----- set up loop to unset showOnly from data
		foreach($this->showOnly as $dataKey) {

			// ----- check to see if set
			if (isset($data[$dataKey]))
				unset($data[$dataKey]);

		}

		return $data;
	}

	// ----- unset all remaining virtual fields
    function RemoveAmend($data)
    {
		// ----- init
		$dataKeys = array_keys($data);

		// ----- set up loop to find
		foreach($dataKeys as $dataKey) {

			// ----- check to see if virtual
			if (ValSet($dataKey, "_amend<_")) {

				// ----- unset virtual field
				unset($data[$dataKey]);
			}

		}

		return $data;
	}

	// ----- all binary fields conversion for storage: dna40, base64, hex
    function ConvertBinaryFields($data, $autoForm)
    {
		// ----- init
		$dataKeys = array_keys($data);

		// ----- base10: set up loop to find all base10 fields.  if exist in post data, convert to binary storage
		foreach($dataKeys as $dataKey) {

			// ----- reset with every itteration
			$errorTrip = FALSE;

			// ----- check to see if base10 hidden
			if (substr($dataKey, 0, 10) == "__BASE10__") {

				// ----- check to see if the form returned in post data
				if (isset($data[substr($dataKey, 10, strlen($dataKey) - 9)])) {
					$fieldName = substr($dataKey, 10, strlen($dataKey) - 9);
					$value = strtoupper($data[$fieldName]);

					// ----- if not blank validate binified 
                    if ($value != '') {
    					// ----- validate base 
    					$base10Val = new \Ignition\Library\dna\EBase10($value);
    
    					if ($base10Val->set($value))
    						$data[$fieldName] = base102bin($base10Val->value);
    					else 
    						$errorTrip = TRUE;
                    }

				}

				// ----- check for found and with errors 
				if ($errorTrip) {
					$this->validationErrors += ["base10" => "  (" . count($this->validationErrors) + 1 . ") " . $base10Val->message . lang('base.in_field') . IsSetElse($this->fieldLabels[$fieldName], $fieldName)];
					$data[$fieldName] = '';
				}

				// ----- remove temp hidden field
				unset($data[$dataKey]);
			}
		}

		// ----- DNA40: set up loop to find all dna40 fields.  if exist in post data, convert to binary storage
		foreach($dataKeys as $dataKey) {

			// ----- reset with every itteration
			$errorTrip = FALSE;

			// ----- check to see if dna40 hidden
			if (DNABASEENABLE && substr($dataKey, 0, 10) == "__EFLOAT__") {

				// ----- check to see if the form returned in post data
				if (isset($data[substr($dataKey, 10, strlen($dataKey) - 9)])) {
					$fieldName = substr($dataKey, 10, strlen($dataKey) - 9);
					$value = strtoupper($data[$fieldName]);

					// ----- if not blank validate binified 
                    if ($value != '') {
    					// ----- validate dna40 
                        $eFloatVal = new \dna\Library\EFloat($value, $GLOBALS['DECLIMIT']);

    					if ($eFloatVal->displayValue != "__INVALID__")
    						$data[$fieldName] = $eFloatVal->Binify();
    					else 
    						$errorTrip = TRUE;
                    }

				}

				// ----- check for found and with errors 
				if ($errorTrip) {
					$this->validationErrors += ["dna40" => "  (" . count($this->validationErrors) + 1 . ") " . $dna40Val->message . lang('base.in_field') . IsSetElse($this->fieldLabels[$fieldName], $fieldName)];
					$data[$fieldName] = '';
				}

				// ----- remove temp hidden field
				unset($data[$dataKey]);
			}
		}

		// ----- DNA40: set up loop to find all dna40 fields.  if exist in post data, convert to binary storage
		foreach($dataKeys as $dataKey) {

			// ----- reset with every itteration
			$errorTrip = FALSE;

			// ----- check to see if dna40 hidden
			if (DNABASEENABLE && substr($dataKey, 0, 9) == "__DNA40__") {

				// ----- check to see if the form returned in post data
				if (isset($data[substr($dataKey, 9, strlen($dataKey) - 8)])) {
					$fieldName = substr($dataKey, 9, strlen($dataKey) - 8);
					$value = strtoupper($data[$fieldName]);

					// ----- if not blank validate binified 
                    if ($value != '') {
    					// ----- validate dna40 
    					$dna40Val = new \Ignition\Library\dna\Edna40($value);
    
    					if ($dna40Val->set($value))
    						$data[$fieldName] = dna402bin($dna40Val->value);
    					else 
    						$errorTrip = TRUE;
                    }

				}

				// ----- check for found and with errors 
				if ($errorTrip) {
					$this->validationErrors += ["dna40" => "  (" . count($this->validationErrors) + 1 . ") " . $dna40Val->message . lang('base.in_field') . IsSetElse($this->fieldLabels[$fieldName], $fieldName)];
					$data[$fieldName] = '';
				}

				// ----- remove temp hidden field
				unset($data[$dataKey]);
			}
		}

		// ----- BASE64: set up loop to find all base64 fields.  if exist in post data, convert to binary storage
		foreach($dataKeys as $dataKey) {

			// ----- reset with every itteration
			$errorTrip = FALSE;

			// ----- check to see if base64 hidden
			if (substr($dataKey, 0, 10) == "__BASE64__") {

				// ----- check to see if the form returned in post data
				if (isset($data[substr($dataKey, 10, strlen($dataKey) - 9)])) {

                    // ----- check for hacker honeypot
                    /// NEED FIELDS
/// using $autoForm verify that this field was set

					$fieldName = substr($dataKey, 10, strlen($dataKey) - 9);
					$value = $data[$fieldName];

					// ----- if not blank validate binified 
                    if ($value != '') {
    					// ----- validate base64 
    					$base64Val = new \Ignition\Library\dna\EBase64($value);
    
    					if ($base64Val->set($value))
    						$data[$fieldName] = sodium_base642bin($base64Val->value, SODIUM_BASE64_VARIANT_URLSAFE_NO_PADDING);
    					else
    						$errorTrip = TRUE;
                    }

				}

				// ----- check for found and with errors 
				if ($errorTrip) {
					$this->validationErrors += ["base64" => "  (" . count($this->validationErrors) + 1 . ") " . $base64Val->message . lang('base.in_field') . IsSetElse($this->fieldLabels[$fieldName], $fieldName)];
					$data[$fieldName] = '';
				}

				// ----- remove temp hidden field
				unset($data[$dataKey]);
			}
		}

		// ----- HEX: set up loop to find all hex fields.  if exist in post data, convert to binary storage
		foreach($dataKeys as $dataKey) {

			// ----- reset with every itteration
			$errorTrip = FALSE;

			// ----- check to see if hex hidden
			if (substr($dataKey, 0, 7) == "__HEX__") {

				// ----- check to see if the form returned in post data
				if (isset($data[substr($dataKey, 7, strlen($dataKey) - 6)])) {
					$fieldName = substr($dataKey, 7, strlen($dataKey) - 6);
					$value = $data[$fieldName];

					// ----- if not blank validate binified 
                    if ($value != '') {
    					// ----- validate base64 
    					$hexVal = new \Ignition\Library\dna\EHex($value);
    
    					if ($hexVal->set($value))
    						$data[$fieldName] = hex2bin($hexVal->value);
    					else
    						$errorTrip = TRUE;
                    }

				}

				// ----- check for found and with errors 
				if ($errorTrip) {
					$this->validationErrors += ["hex" => "  (" . count($this->validationErrors) + 1 . ") " . $hexVal->message . lang('base.in_field') . IsSetElse($this->fieldLabels[$fieldName], $fieldName)];
					$data[$fieldName] = '';
				}

				// ----- remove temp hidden field
				unset($data[$dataKey]);
			}
		}

		// ----- FAN (binified): set up loop to find all binified fields, ergo fq assets that must be converted.  if exist in post data, 
        //       convert for binary storage
		foreach($dataKeys as $dataKey) {

			// ----- reset with every itteration
			$errorTrip = FALSE;

			// ----- check to see if FAN (binified) hidden
			if (substr($dataKey, 0, 7) == "__FAN__") {

				// ----- check to see if the form returned in post data
				if (isset($data[substr($dataKey, 7, strlen($dataKey) - 6)])) {
					$fieldName = substr($dataKey, 7, strlen($dataKey) - 6);
					$value = $data[$fieldName];

					// ----- if not blank validate binified 
                    if ($value != '') {
    					$EFanVal = new \Ignition\Library\dna\EFan($value);

    					if ($EFanVal->set($value))
    						$data[$fieldName] = $EFanVal->value;
    					else
    						$errorTrip = TRUE;
                    }

				}

				// ----- check for found and with errors 
				if ($errorTrip) {

					$this->validationErrors += ["fan" => "  (" . count($this->validationErrors) + 1 . ") " . $EFanVal->message . lang('base.in_field') . (isset($this->fieldLabels[$fieldName]) ? $this->fieldLabels[$fieldName] : $fieldName)];

					$data[$fieldName] = '';
				}

				// ----- remove temp hidden field
				unset($data[$dataKey]);
			}
		}

		return $data;
	}

	// ----- fixes w3 issue with checkboxes, not returned in post data if not checked
	//       uses hidden field in iform prefixed by __CHECKBOX__ to locate and rectify w3
    function FixCheckBoxes($data)
    {
		// ----- store keys
		$dataKeys = array_keys($data);

		// ----- set up loop to find all check boxes.  if exist in post data, set true or set false
		foreach($dataKeys as $dataKey) {

			// ----- check to see if check box hidden
			if (substr($dataKey, 0, 12) == "__CHECKBOX__") {

				// ----- check to see if the checkbox returned in post data
				if (isset($data[substr($dataKey, 12, strlen($dataKey) - 11)]))
					$data[substr($dataKey, 12, strlen($dataKey) - 11)] = 1;
				else
					$data[substr($dataKey, 12, strlen($dataKey) - 11)] = 0;

				// ----- remove temp hidden field
				unset($data[$dataKey]);
			}
		}

		return $data;
	}

    // ------ create and return an array of all allowed fields in blank 
    //        set id to zero and active to true
    public function AllowedBlank($blankList = FALSE, $isDNADB = FALSE) {

        // ----- init
        $returnVal = [];

		// ----- check for called with list 
		if (!is_array($blankList))
			$blankList = $this->allowedFields;

        foreach ($blankList as $fieldName) {
            if (is_array($fieldName))
                continue;

            $returnVal[$fieldName] = '';
        }

		if (!$isDNADB)
        	$returnVal['active'] = 1;
		else
			$returnVal['dna_active'] = 1;

        $returnVal['id'] = 0;

        return $returnVal;

    }

/// THESE FILES NEED TO GO INTO A TABLE SO BECOME BENIGN, OUTSIDE OF FS
    // ----- pager based on directory file system, returns directory listing
    //       based on the $this->filePath being the base storage location for files and directory 
    //       information, access this directory + any subDirectory and retrieve a list 
    //       of perPage items to display.  sets this->flags
    public function FilePaginate($subDirectory = '', $includeSubs = true, $page = 1, $perPage = 25)
    {
        // ----- init 
        $filesFound = 0;
        $totalBytes = 0;

        // ----- if adding subDirectory 
        $fullPath = $this->filePath . $subDirectory ?? '';

        $result = [];

        if (!file_exists($fullPath)) {
            $this->flags = "|NOEXIST";
            return false;
        }

        // ----- check to see if is a directory 
        if (!is_dir($fullPath)) {
            $this->flags = "|ISFILE";
            return false;
        }

        $files = scandir($fullPath);

        if ($files !== false) {

            // ----- add first entry in list as root dir or up one directory 
            if ($subDirectory) {

                // ----- get minus 1 directory to step up by one
                $upDirLink = substr($subDirectory, 0, strlen($subDirectory) - 1);
                $upDirLink = substr($upDirLink, 0, strrpos($upDirLink, '/'));

// THE ABOVE COULD RESULT IN AN ERROR IF DID NOT FIND

                $obj['fileName'] = '../' . substr($upDirLink, strrpos($upDirLink, '/') + 1, strlen($upDirLink));
                $obj['fileType'] = "DIR";
                $obj['fileSize'] = number_format(filesize($fullPath));
                $obj['fileLink'] = $upDirLink;
                $obj['isDir'] = true;
                $obj['created'] = date(DATE_W3C, filemtime($fullPath));
                $result[] = $obj;
            } else {
                $obj['fileName'] = "/";
                $obj['fileType'] = "ROOT";
                $obj['fileSize'] = 0;
                $obj['fileLink'] = '';
                $obj['isDir'] = true;
                $obj['created'] = date(DATE_W3C, filemtime($fullPath));
                $result[] = $obj;
            }

            foreach ($files as $file) {
                if (in_array($file, array(".", ".."))) {
                    continue;
                }

                // ----- check for max paginate
                if ($filesFound == $perPage)
                    break;

                // ----- increment files/directories count 
                $filesFound += 1;

                $isDir = is_dir($fullPath . '/' . $file);

                // ----- extract file extension if any
                if ($isDir)
                    $ext = 'DIR';
                else {
                    $ext = strrpos($file, '.');
                    $ext = ($ext === false ? 'UNK' : substr($file, $ext + 1, strlen($file) - $ext + 1));
                    $totalBytes += filesize($fullPath . '/' . $file);
                }

                if (substr(realpath($fullPath), realpath($file) == 0)) {
                    $obj['fileName'] = $file . ($isDir ? '/' : '');
                    $obj['fileType'] = strtoupper($ext);
                    $obj['fileSize'] = number_format(filesize($fullPath . '/' . $file));
                    $obj['fileLink'] = $subDirectory . '/' . $file;
                    $obj['isDir'] = $isDir;
                    $obj['created'] = date(DATE_W3C, filemtime($fullPath . '/' . $file));
                    $result[] = $obj;
                }
            }

        }

        // ----- set flag
        $this->flags = $this->flags .= "|" . ($filesFound > 0 ? 'FILEFOUND' . $totalBytes : 'EMPTYDIR');

        return $result;
    }

    // ----- given a subdirectory and file prefix string (plus dash symbol dilimeter)
    //       returns an array of files with matching prefix 
    public function GetFilesByPrefix($subDirectory, $filePrefix, $useFilePath = TRUE)
    {
        // ----- set target path
		if ($useFilePath)
 	       $fullPath = $this->filePath . $subDirectory ?? '';
		else 
 	       $fullPath = FCPATH . $subDirectory ?? '';

		$count = 0;
		$result = [];
		$filePrefix = $filePrefix . "-";
		$files = scandir($fullPath);

        if ($files !== false) {

            foreach ($files as $file) {
                if (in_array($file, array(".", ".."))) {
                    continue;
                }
                if (strpos($file, $filePrefix) !== 0) {
                    continue;
                }

                // ----- verify 
                if (substr(realpath($fullPath), realpath($file) == 0)) {

                    // ----- check for matching prefix
                    $extractId = substr($file, 0, strlen($filePrefix));

                    if ($filePrefix === $extractId) {

                        // ----- check to see if file is directory 
                        $isDir = is_dir($fullPath . '/' . $file);

                        // ----- extract file extension if any
                        if ($isDir)
                            $ext = 'DIR';
                        else {
                            $ext = strrpos($file, '.');
                            $ext = ($ext === false ? 'UNK' : substr($file, $ext + 1, strlen($file) - $ext + 1));
                        }

                        $obj['fileName'] = $file . ($isDir ? '/' : '');
                        $obj['fileType'] = strtoupper($ext);
                        $obj['fileSize'] = number_format(filesize($fullPath . '/' . $file));
                        $obj['fileLink'] = $subDirectory . '/' . $file;
                        $obj['isDir'] = $isDir;
                        $obj['created'] = date(DATE_W3C, filemtime($fullPath . '/' . $file));
                        $result[] = $obj;
                    }
                }
            }

        }

        return $result;

    }

    // ----- return files in subDirectory matching prefix ($IDField)
    //       return options: array or send jason encoded list to 
    //       browser (default).  If no prefix, return all files
    public function GetFiles($subDirectory, $prefix = '', $retArray = false)
    {
        // ----- set target path
        $fullPath = $this->filePath . $subDirectory . '/';
        $result = [];

        // ----- check for record id 
        if ($prefix !== '')
            $fileKey = $prefix . "-";

        $files = scandir($fullPath);

        if ($files !== false) {

            foreach ($files as $file) {
                if (in_array($file, array(".", ".."))) {
                    continue;
                }
                if (strpos($file, $fileKey) !== 0) {
                    continue;
                }
                if (substr(realpath($fullPath), realpath($file) == 0)) {
                    $obj['name'] = substr($file, strlen($fileKey) + ($this->addRandom ? 7 : 0));
                    $obj['fullname'] = $file;
                    $obj['size'] = filesize($fullPath . '/' . $file);
                    $obj['fullpath'] = $fullPath . '/' . $file;
                    $result[] = $obj;
                }
            }

        } else {
            return;
        }

        if ($retArray)
            return $result;
        else
            echo json_encode($result);
    }

    // ----- upload files 
    public function UploadFiles($files, $subDirectory = '', $prefix = '')
    {
        // ----- init
        $fullPath = $this->filePath . $subDirectory . '/';

        // ----- add any prefixes
        $prefixes = ($prefix !== '' ? $prefix . '-' : '');
        $prefixes .= ($this->addRandom ? RandomName(6) . '-'  : '');

        // ----- ??
        //$this->create_dir($fullPath . '/');

        // ----- check for php $_FILE
        $tempFile = $files['file']['tmp_name'];
        $fileName = preg_replace('/\s+/', '_', $files['file']['name']);
        $targetFile = $fullPath . $prefixes . $fileName;

//DebugStore($tempFile, 'tempfile');
//DebugStore($fileName, 'fileName');

        // ----- before upload, make sure file does not exist
        if (!file_exists($targetFile)) {

            move_uploaded_file($tempFile, $targetFile);
//$this->moduleName
            echo json_encode([
                'success' => true
            ]);

        } else {
            // ----- If file exists then echo the error and set a http error response
            echo json_encode([
                'success' => false,
                'message' => trans('error_duplicate_file')
            ]);
            http_response_code(404);
        }

    }

    // ----- count all files in subDirectory directory matching recordid
    public function FileCount($subDirectory = '', $recordId = '')
    {
        // ----- set target path
        $fullPath = $this->filePath . ($subDirectory !== '' ? $subDirectory . '/' : '');
        $count = 0;
        $fileKey = $recordId . ($recordId == '' ? '' : "-");
        $files = scandir($fullPath);

        if ($files !== false) {

            foreach ($files as $file) {
                if (in_array($file, array(".", "..")))
                    continue;

                if (strpos($file, $fileKey) !== 0)
                    continue;

                if (substr(realpath($fullPath), realpath($file) == 0))
                    $count ++;

            }

        }

        return $count;
    }

    // ---- delete a file in the targetPath for the recordi
    public function DeleteFile($fileName, $subDirectory = '')
    {
        // ----- set target path
        $fullPath = $this->filePath . ($subDirectory !== '' ? $subDirectory . '/' : '');

        // ----- assemble path + file name 
        $filePath = $fullPath . $fileName;

        // ----- make sure hackers have not jiggered the path, avoid tree traversal
        if (ValSet(realpath($filePath), realpath($fullPath))) {

            // ----- check to see if file exists
            if (file_exists($filePath))
                unlink($filePath);
            else
                IRedirect('system/error404/' . $filePath);
        }
        else
            IRedirect('system/error404/' . $filePath);

    }

    // ----- return a file as a download to client and prevent its execution
    public function ReturnFile($subDirectory, $fileName)
    {
        // ----- init
        $ctype_default = "application/octet-stream";
        $content_types = array(
            'gif' => 'image/gif',
            'jpg' => 'image/jpeg',
            'jpeg' => 'image/jpeg',
            'pdf' => 'application/pdf',
            'png' => 'image/png',
            'txt' => 'text/plain',
            'xml' => 'application/xml',
        );

        // ----- set target path
        $targetPath = $this->filePath . $subDirectory . '/';

        $file_path = $targetPath . $fileName;

        if (strpos(realpath($targetPath), realpath($file_path)) != 0) {
            IRedirect('system/error404/' . $file_path);
            exit;
        }

        $path_parts = pathinfo($file_path);
        $file_ext = $path_parts['extension'];

        if (file_exists($file_path)) {
            $file_size = filesize($file_path);

            $save_ctype = isset($content_types[$file_ext]);
            $ctype = $save_ctype ? $content_types[$file_ext] : $ctype_default;

            header("Expires: -1");
            header("Cache-Control: public, must-revalidate, post-check=0, pre-check=0");
            header("Content-Disposition: attachment; filename=\"$fileName\"");
            header("Content-Type: " . $ctype);
            header("Content-Length: " . $file_size);

            echo file_get_contents($file_path);
            exit;
        }

        IRedirect('system/error404/' . $file_path);
        exit;
    }

    // ----- create a directory in the system assets directory
    public function CreateDir($path, $chmod = '0777')
    {
        if (!(is_dir($path) || is_link($path))) {
            return mkdir($path, $chmod);
        } else {
            return false;
        }
    }

    // ----- rename temp files, create() names files with temp id number
    public function RenameUploaded($dropzoneKey, $newID = "__NOTASSIGNED__") {

        // ----- get most recent record id to use in naming files
        if ($newID == "__NOTASSIGNED__")
            $newID = $this->getInsertID();

        // ----- set target path
        $path = UPLOADS_FOLDER . $this->subDirectory . '/';
        $fileKey = $dropzoneKey . "-";
        $files = scandir($path);

        if ($files !== false) {

            foreach ($files as $file) {
                if (in_array($file, array(".", ".."))) {
                    continue;
                }
                if (strpos($file, $fileKey) !== 0) {
                    continue;
                }
                if (substr(realpath($path), realpath($file) == 0)) {
                    $fileName = substr($file, strlen($fileKey));
                    rename($path . $file, $path . $newID . "-" . $fileName);
                }
            }

        }

    }

}