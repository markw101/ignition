<?php

/*********************************************************************
    AUTHOR:      Mark E. Williamson
    FILE:        BaseModelRelate.php
    VERSION:     See BaseController.php
    DESCRIPTION: Extends CI and BaseModel to include secondary 
                 relational table
    COPYRIGHT:   2023
    FIRST REV:   25 Sep 2023
    LICENSE:     MIT

*********************************************************************/

namespace Ignition\Base;

//     base class model for Ignition for two or more tables in a typical relational db
//
//       PARENT TABLE: The parent table is the primary table in the two (or more) 
//       tables relationship.
// 
//       CHILD TABLE:  The secondary table in the multi table relational 
//       model.  The child table may have a one to many or many to one relationship with 
//       parent table.
//
//       This class extends the basemodel to introduce a relational database system
//       typically this would be a one to many, two table design, where the child (secondary)
//       table is a storage for a muliplicity of items stored for the parent.  Ex: customers
//       is a parent table that stores customer records linked by a central id number 
//       or a customer number.  invoices is a secondary table that stores invoices for one 
//       or more customers and relates to it using the customer number.  Therefore, 
//       customers->invoices is a one to many relationship and a relational table system
//       whereby: customers.table = parent and invoices.table = child
//
//       This parent (=1) -> child (>1) relationship may quite frequently be reversed.  A
//       database that stores records of employees clocking-in to work would have a table 
//       timeclock with records in and out.  The timeclock table would store entries for 
//       every work day.  It would therefore be the many and the employee would be the one.
//       timeclock (>1) -> employee (=1), ergo parent (>1) -> child (=1).  Therefore, the parent 
//       is always the primary table *assigned to the class implementation* (ergo, set in model)
//       and the secondary is always called the child, regardless of some other standards of 
//       data importance or value.  This provides flexibility to the programmer
//
//       This system is very easy to implement in Ignition, with as few as 6 variables, making
//       child table and the calling of just a few functions.  For example, in blog, we have a
//       one to many relationship between blog and bloglangs, blog being the blog article
//       records (one) with bloglangs being the text for the articles in several languages (many), ergo, 
//       bloglangs.bloglang_title, bloglangs.bloglang_desc, bloglangs.bloglang_text, 
//       bloglangs.bloglang_slug.  In this relation we have blog being the primary parent
//       and bloglangs being the child with a multiplicity of language translations for the 
//       same blog article.  Implementation in this example, we require setting the following 
//       variables in the model:
//      	(1) protected $table2 = 'bloglangs';
//      	(2) protected $checkFieldsBlankNoSave = ['bloglang_title', 'bloglang_description', 'bloglang_text', 'bloglang_slug', 'bloglang_cateogory', 'bloglang_tags'];            // if this contains field names of child record, will only save a record if at least one of these fields contain values.  Otherwise, record dropped in iValidate
//      	(3) protected $keyField2 = 'bloglang_lang';
//
//		and then for labels, validation and title text in Form.php (extends model.php)
//			(4) protected $fieldLabels2
//			(5) protected $validationRules2
//			(6) protected $allowedFields2
//
//		 The following functions are then required to fully implement automated record
//       processing for the child records: $model->GetChildrenComplete(), 
//       $model->DeleteChildRecords($id) (see Ignition\Blog\MC\Blog.php) and the IForm 
//       extensions for children fields: ITextChild, ITextAreaChild, IHiddenChild
//       (in Ignition\Blog\Views\form.php).  With this implementation, the relational system may
//       be quickly set up and then the child record processing automated through the above
//       functions and then records saved and created using $model->IInsertChild()
//       and $model->IUpdateChild($id), both also in Blog.php.  This relieves the programmer
//       of having to deal with the writing of a great many record processing functions 
//       normally required in developing a parent/child record system.  Please see Blog 
//       controller, model and form for detailed example.  This could be implemented within just
//       a few hours versus a few weeks!
//
//       INTERNAL DETAILS
//
//       Child records are auto added to $model->data by prepending a tablename string and 
//       appending the id to the end of the field name.  Therefore, parent and child 
//       records may be sent out from the server to the browser, encoded into a singular 
//       array with temporary field name markers and child table record id numbers. 
//       Ex: $model->data['_>CHILDTABLENAME<_CHILDFIELDNAME_>>CHILDID']
//       would return the data contained in CHILDTABLENAME->CHILDFIELDNAME
//
//       If an additional child table is required (table3), must externally manipulate some
//       below variables and call iValidate() and SaveChildrenData() data with a table name 
//       (table3) and allowedfields (allowedFields3) variables
//
//       Study the below variables and then make a model in the MVC system base on
//       BaseModelRelate, setting applicable variables in your model
//       Ex (Ignition\Blog\MC\Model.php):
//       	protected $table = 'blog';                   // <<< PARENT TABLE 
//          protected $subDirectory = 'blog';            // enables iamage uploads
//      	protected $table2 = 'bloglangs';             // <<< CHILD TABLE
//      	protected $checkFieldsBlankNoSave = ['bloglang_title', 'bloglang_description', 'bloglang_text', 'bloglang_slug', 'bloglang_cateogory', 'bloglang_tags'];            // if this contains field names of child record, will only save a record if these fields contain values.  Otherwise, record will not be saved
//      	protected $keyField2 = 'bloglang_lang';
//       
//       Build display forms using IForm to display and modify parent/child records
//       Example in Ignition\Blog\Views\form.php */

class BaseModelRelate extends BaseModel
{
    // ----- init
	protected $fieldNameChildRelated = 'id_parent';	// field name in child table that stores parent record identifier
	protected $fieldNameParentRelated = 'id';		// field name in parent table that is used as parent record identifier
	protected $fieldNameChildID = 'id';             // field name in child table that stores chil id
	protected $order = 'ASC';
	protected $orderOn = 'id';
	protected $allowedFields2;						// Ignition for child table2
	protected $fieldLabels2;						// Ignition for child table2
	protected $validationRules2;					// Ignition for child table2
	protected $checkFieldsBlankNoSave = [];         // if set, contains field names of child record.  If set will only save a record if any of these fields contain values.  Otherwise, record will be considered blank and superfluous and discarded
	protected $autoRemoveBlanks;                    // if set to true, any record who's $checkFieldsBlankNoSave fields are blank will be automatically deleted from the child table
	protected $keyField2 = 'id';				    // used in identifying record in returned error form messages in ivalidate
	protected $childFetchType = 'all';				// preferred record record retrieval type for child table records: none, one, all 
	public $dataChildren;							// storage of child records, provisional during validation and save
	public $parentIDValue = NOTSET;                 // value in field name parent related
    protected $butttonText = "Add Files";           // button text in file drop zone (use static call or __construct() in model to set language)
    public $rawRecords = [];                        // if elect, save the raw record data when collecting children records
    public $totalChildren;                          // if elect to save records, also save record count
    public $currentChildRecord = NOTSET;            // currently selected child record.  user invoked pointer.  is offset val and counts from zero
    public $database2;                              // in the case of complex assets, a secondary database may be required
    public $parentAllowed = FALSE;                  // Ignition allowed parent fields.  Used occasionally in AMD to save allowed
	public $allowedFields3;						    // Ignition for child table3 for COM type databases (see BaseModel->dbType)

    // ----- constructor for configuration
    public function __construct($pid = NOTSET, $setTable = "", $allowField = "", $selectDB = '')
    {
        PARENT::__construct();
        $this->parentIDValue = $pid;
		$this->autoRemoveBlanks = $this->checkFieldsBlankNoSave != [];
        $this->table2 = ($this->table2 ? $this->table2 : FALSE);           // flag signifying this is a relational db

		// ----- select database 
		if ($selectDB == '')
			$this->DBGroup = $GLOBALS['DBGROUP'];
		else 
			$this->DBGroup = $selectDB;

        $this->butttonText = lang('base.add_photos');

		if ($setTable !== "")
			$this->table = $setTable;
		if ($allowField !== "")
			$this->allowedFields = $allowField;

    }

	// ----- given record identifier and dataset, save the data to the child table record
	//		 should probably return a value 
	public function UpdateChild($childID, $childData) {

		// ----- init
		$childModel = new \Ignition\Base\BaseModel($this->table2, $this->allowedFields2);
		$childModel->validationRules = $this->validationRules2;
		$childModel->update($childID, $childData);

	}

	// ----- retrieve and/or set all children records according to a list of key values.
    //       $this->keyField2 must be set to the field matching column in the  
	//       child table which shall act as central differentiating data point 
	//       per record.  If keyField2List entry is not located within child table, 
	//       then a blank child record shall be added to returnList[] with id set to temp
	//       value, keyField2List entry added to $this->keyField2 (ex: lang in blog) and 
	//       $this->fieldNameChildRelated (default 'id_parent') set to 
	//       $this->parentIDValue
	//       returns returnList[]
	public function GetChildrenComplete($keyField2List, $idVal = FALSE, $processFromList = FALSE, $saveRawRecords = FALSE, $reverseOrder = FALSE, $maxReturn = FALSE, $pageNum = 1) {

        // ----- check for sent id for parent
        if ($idVal)
            $this->parentIDValue = $idVal;

	 	// ----- check to see if id of parent record set 
		if ($this->parentIDValue == NOTSET || $this->parentIDValue == 0)
			halt("parentIDValue not set in GetChildrenComplete() as required.  Normally, set in BaseModelRelate constructor.  Ignition halted");

		$returnList = [];
        $filter = [];

        // ----- check for trans type and max return which is a limit to total return records
        if ($maxReturn && $this->dbType == 'TRN') {

            // ----- obtain a tot record count (mysql maintains record count in its data)
    		$db      = SetDB($GLOBALS['DBGROUP']);
            $recCount = $db->query('SELECT COUNT(*) FROM `' . $this->table2 . '`;')->getResult('array');
            $recCount = $recCount[0]['COUNT(*)'];

            // ----- create filter based on max return value IF table record count is greater than maximum return quantity
            if ($recCount > $maxReturn) {
                // ----- check for reverse order
                if ($reverseOrder)
                    $filter = ['__id_range__' => TRUE, 'offset' => $recCount - ($maxReturn * $pageNum), 'limit' => $maxReturn];
                else
                    $filter = ['__id_range__' => TRUE, 'offset' => ($maxReturn * ($pageNum - 1)), 'limit' => $maxReturn];
            }
        }

		$childRecs = $this->GetChildrenReturn($filter);            // obtain all existing child records

        $this->totalChildren = count($childRecs);

		$preFix = '_>' . $this->table2 . '<_';

        // ----- check for process from list.  this is needed when processing a pre destined set of children records
        //       a function of keyField2.  used in blog langs and page langs, where a list of language records 
        //       is required by the parent, ergo: 'en' 'es' 'zh'
        if ($processFromList) {

            $count = 0;
            foreach ($childRecs as $oneChildren) {
                $keyField2List[$count] = $oneChildren[$this->keyField2];
                $count ++;
            }

    		// ----- setup loop to iterate through key fields list.  first look for matching
    		//       record with key value.  if not found create a new temporary record with this key
    		//       if found, copy the values contained within $allowedFields2
    		foreach ($keyField2List as $recordKey) {
    
    			// ----- this acts as a flag.  if set, found.  otherwise, make a blank child record
    			$childID = '';

    			// ----- set up loop to search for matching record in child table for this particular $this->keyField2 = $recordKey
    			foreach ($childRecs as $oneChild) {
    
    				// ----- check for matching child and extract the child table record values
    				if ($oneChild[$this->keyField2] == $recordKey) {
    					$childID = $oneChild[$this->fieldNameChildID];
    					$suFix = '_>>'  . $childID;
    
    					// ----- set up loop to append entries to the return array
    					foreach ($this->allowedFields2 as $field) {
    						$fieldName = $preFix . $field . $suFix;
    						$returnList[$fieldName] = $oneChild[$field];
    					}
    
    					$returnList[$preFix . $this->fieldNameChildRelated . $suFix] = $this->parentIDValue;
    					$returnList[$preFix . $this->fieldNameChildID . $suFix] = $childID;
    
    					break;
    
    				}
    			}
    
    			// ----- if $recordKey record found, ergo record already exists in child, loop to continue 
    			if ($childID != '')
    				continue;
    
    			// ----- create a new, blank, record
    			$childID = 'TEMP' . RandomName(4);
    
    			$suFix = '_>>'  . $childID;
    
    			$f1 = $preFix . $this->keyField2 . $suFix;
    			$returnList[$f1] = $recordKey;
    
    			$f2 = $preFix . $this->fieldNameChildRelated . $suFix;
    			$returnList[$f2] = $this->parentIDValue;
    
    			$f3 = $preFix . $this->fieldNameChildID . $suFix;
    			$returnList[$f3] = $childID;
    
    			// ----- set up loop to append entries to the returned array
    			foreach ($this->allowedFields2 as $field) {
    				//       skip entries for keyField2, fieldNameChildRelated and fieldNameChildID (set above)
    				if (!(strpos($f1 . $f2 . $f3, $preFix . $field . $suFix) === FALSE))
    					continue;
    				$fieldName = $preFix . $field . $suFix;
    				$returnList[$fieldName] = '';
    			}
    
    		}

        } else {

            // ----- process child records in an ORDINARY fashion
            //       copy child records into data array using standard relation suffix/prefix design
    		foreach ($childRecs as $oneChild) {
    
    			$childID = $oneChild[$this->fieldNameChildID];
    			$suFix = '_>>'  . $childID;
    
    			// ----- set up loop to append entries to the return array
    			foreach ($this->allowedFields2 as $field) {
    				$fieldName = $preFix . $field . $suFix;
    				$returnList[$fieldName] = $oneChild[$field];
    			}
    
    			$returnList[$preFix . $this->fieldNameChildRelated . $suFix] = $this->parentIDValue;
    			$returnList[$preFix . $this->fieldNameChildID . $suFix] = $childID;
    		}
        }

        if ($saveRawRecords)
          $this->rawRecords = $childRecs;

        return $returnList;

	}

	// ----- validate data child and parent records, usually during save
	//
	public function IValidateChild($TableName = "__NOTSET__", $AllowedFields = "__NOTSET__", $ValidationRules = "__NOTSET__") {

		// ----- init
		$TableName = ($TableName == "__NOTSET__" ? $this->table2 : $TableName);
		$AllowedFields = ($AllowedFields == "__NOTSET__" ? $this->allowedFields2 : $AllowedFields);
		$ValidationRules = ($ValidationRules == "__NOTSET__" ? $this->validationRules2 : $ValidationRules);
		$childModel = new \Ignition\Base\BaseModel($TableName, $AllowedFields);
		$childModel->validationRules = $ValidationRules;

		// ----- assumes using iform(), this fixes checkboxes.  see w3 issue williamson.software website
		$this->FixCheckBoxesChild();	 

		// ----- freshen data
		$this->ExtractDataChildren();

		// ----- set up loop to validate every child record
		foreach ($this->dataChildren as $child) {

			// ----- extract record ID and set relation field value to parent
			$childID = $child[$this->fieldNameChildID];
			$child[$this->fieldNameChildRelated] = $this->parentIDValue;

			// ----- if record is blank based upon all checkFieldsBlankNoSave fields being blank
			//       then ignore and do not validate.  this allows for the deletion of blank child recs
			if ($this->autoRemoveBlanks && $this->RecordBlank($child))
				continue;

			// ----- validate and pass back the errors to model
			if (!$childModel->validate($child)) {
				$this->validationErrors = $childModel->errors();

				// ------ if errors, include the record number 
				if ($this->validationErrors != [])
					$this->validationErrors += ["child_record" => " In child record: " . $child[$this->keyField2]];

				return false;
			}
		
		}

		return $this->validate($this->data);
	}

	// ----- write child records to child table (table2)
	//       this function will automatically delete all "blank" child records that have a 
	//       table record.  determined by variables $this->autoRemoveBlanks (bool) and
	//       $this->checkFieldsBlankNoSave (field name list)
	public function SaveChildrenData($TableName = "__NOTSET__", $AllowedFields = "__NOTSET__") {

		// ----- init
		$TableName = ($TableName == "__NOTSET__" ? $this->table2 : $TableName);
		$AllowedFields = ($AllowedFields == "__NOTSET__" ? $this->allowedFields2 : $AllowedFields);
		$childModel = new \Ignition\Base\BaseModel($TableName, $AllowedFields);
        $tazModel = new \Ignition\Base\BaseModel('bloglangs', GetFieldNames('bloglangs', IGDBPREFIX . 'taz'), IGDBPREFIX . 'taz');
        $tazModel->SetTimeStamps(FALSE);

		// ----- freshen child data if necessary
		if ($this->dataChildren == [])
			$this->ExtractDataChildren();

		// ----- set up loop to save every dataChildren records in child table 
		foreach ($this->dataChildren as $child) {

			// ----- set values
			$childID = $child[$this->fieldNameChildID];
			$child[$this->fieldNameChildRelated] = $this->parentIDValue;

			// ----- check to see if the record is considered blank based on checkFieldsBlankNoSave
			if ($this->autoRemoveBlanks && $this->RecordBlank($child)) {

				// ----- check to see if this record has already been saved, given an ID
				//       must therefore remove from table
				if (!(strpos($childID, "TEMP") === 0))
					$childModel->delete($child[$this->fieldNameChildID]);

				continue;

			}

			// ----- if value begins with TEMP, then assume is appended record
			if (strpos($childID, "TEMP") === 0) {

				// ----- insert new record, check for possible errors
				if (!$childModel->insert($child)) {
					$this->validationErrors = $childModel->errors();
					$this->validationErrors += ["child_record" => " In child record: " . $child[$this->keyField2]];
					return false;
				}

                // ----- insert a corresponding record in taz db
                $tazModel->insert(['active' => TRUE]);
			}
			else {
				$child[$this->fieldNameChildID] = $childID;

				// ----- update, check for possible errors
				if (!$childModel->update($childID, $child)) {
					$this->validationErrors = $childModel->errors();
					$this->validationErrors += ["child_record" => " In child record: " . $child[$this->keyField2]];
					return false;
				}

			}

		}

		// ----- if made it here, success
		return true;

	}

	// ----- check to see if record is blank based on list contained in 
	//       checkFieldsBlankNoSave containing only blank fields
	public function RecordBlank($record)
	{

		// ----- setup loop to check record for blankness
		foreach ($record as $field => $value) {

			if (ValSetArray($this->checkFieldsBlankNoSave, $field) && !empty($value))
				return false;

		}

		return true;
	}

	// ----- iterate through $this->data, locating child records and saving them
	//       to $this->dataChildren
	public function ExtractDataChildren($TableName = "__NOTSET__")
	{

		// ----- init
		$TableName = ($TableName == "__NOTSET__" ? $this->table2 : $TableName);
		$this->dataChildren = [];
		$foundChildID = "NULL";
		$foundNewChild = FALSE;
		$setChildData =  FALSE;
		$arrayCount = 0;
		$childData = [];

		// ----- setup loop to iterate through each data array fields and locate 
		//       child records to save
		foreach ($this->data as $fieldName => $fieldData) {

			// ----- check for a new child record 
			if (strpos($fieldName, "_>" . $TableName . "<_") === 0) {

				// ----- extract unique child record ID  and create first entry
				$startPos = strrpos($fieldName, "_>>") + 3;
				$childID = substr($fieldName, $startPos, strlen($fieldName) - $startPos);

				// ----- check to see if already accumulatting fields for a record
				if ($foundChildID == $childID) {

					$childData[$this->ExtactBaseName($fieldName)] = $fieldData;
					continue;

				} else {

					// ----- check for found stray data field (happens with FixCheckBoxes)
					$foundStrayField = false;
					$tcount = 0;
					foreach ($this->dataChildren as $child) {

						if (isset($child['id']) && $child['id'] == $childID) {

							// ----- extract child data, add field, reinsert child with stray field
							$childDataTemp = $this->dataChildren[$tcount];
							$childDataTemp[$this->ExtactBaseName($fieldName)] = $fieldData;
							unset($this->dataChildren[$tcount]);
							$this->dataChildren[$tcount] = $childDataTemp;

							$foundStrayField = true;
							break;
						}

						$tcount ++;

					} 

					if ($foundStrayField)
						continue;

					// ----- check if need to save previous record already being collected
					if ($foundNewChild && !$setChildData) {
						// ----- add total child to the children array 
						$this->dataChildren[$arrayCount] = $childData;
						$arrayCount ++;
						$setChildData =  TRUE;
					}

					// ----- found new record, store to found and set flags
					$foundChildID = $childID;
					$foundNewChild = TRUE;
					$setChildData =  FALSE;

					// ----- initialize a new record as array type 
					$childData = [$this->fieldNameChildID => $childID];

					// ----- set first field of record, name and data
					$childData[$this->ExtactBaseName($fieldName)] = $fieldData;
					
					continue;
				}

			}

		}

		// ----- check for final unsaved child record
		if ($foundNewChild && !$setChildData)
			$this->dataChildren[$arrayCount] = $childData;

		return $this->dataChildren;
	}

	// ----- add one child record and return the new temporary id 
	//
	public function AddNewChildData() {

		// ----- create temp id 
		$newID = 'TEMP' . RandomName(4);

		// ----- set up loop to append entries to the data 
		foreach ($this->allowedFields2 as $field) {
			$fieldName = '_>' . $this->table2 . '<_' . $field . '_>>'  . $newID;
			$this->data[$fieldName] = '';
		}

		return $newID;

	}

	// ----- retrieve all children records based on model settings and fuse into data array
	//       return true if successful
	public function GetChildrenData($cid = 0) {

		if (ValSet('one|all', $this->childFetchType))
			halt("Call to GetChildrenData in BaseModelRelate without setting childFetchType variable in model.  Possible setting values are one or all.");

		// ----- connect to child table 
		$db      = \Config\Database::connect();
		$builder = $db->table($this->table2);

		// ----- if specified, get one child record matching cid
		if ($cid != 0) {
			$addRecords =  $builder->getWhere([$this->fieldNameChildID => $cid], 1)->getResult('array');
		} else {

			// ----- get child records based on the linking $parentIDValue
			switch ($this->childFetchType) {

				case 'one' :
					// ----- get and prepend to data
					$addRecords =  $builder->getWhere([$this->fieldNameChildRelated => $this->parentIDValue], 1)->getResult('array');
					break;

				case 'all' :
					$addRecords =  $builder->getWhere([$this->fieldNameChildRelated => $this->parentIDValue])->getResult('array');
					break;

				default :
					return false;

			}

		}

		// ----- set up loop to add records to data for every child record found
		foreach ($addRecords as $record) {

			// ----- set up loop to append entries to the data 
			foreach ($this->allowedFields2 as $field) {
				$fieldName = '_>' . $this->table2 . '<_' . $field . '_>>'  . $record['id'];
				$this->data[$fieldName] = $record[$field];

			}
		}

		return true;
		
	}

	// ----- extract field base name of child table from data 
	public function ExtactBaseName($fieldName) {

		$fieldName = substr($fieldName, 4 + strlen($this->table2));
		$fieldName = substr($fieldName, 0, strrpos($fieldName, "_>>"));

		return $fieldName;

	}

	// ----- get children records and return, optionally according to custom filter 
	public function GetChildrenReturn($filter = [], $pid = NOTSET)
	{

		if ($pid == NOTSET)
			$pid = $this->parentIDValue;

        // ----- if looking to retrieve a range of ids, used in pager function
        if (!empty($filter) && array_key_exists('__id_range__', $filter)) {
    		$db      = SetDB($GLOBALS['DBGROUP']);
    		$builder = $db->table($this->table2);
            return $builder->get($filter['limit'], $filter['offset'])->getResult('array');

        }

		// ----- check for unknown parent, otherwise limit scope to pid 
		//       this allows for the retrival of child records where a specific column is known
		//       but the parent id is unknown
		if ($pid != '__UNKNOWNPARENT__' && $this->fieldNameChildRelated != 'ALL')
			$filter = array_merge([$this->fieldNameChildRelated => $pid], $filter);

		// ----- connect to secondary table 
		$db      = SetDB($GLOBALS['DBGROUP']);
		$builder = $db->table($this->table2);
        $childRecords = $builder->getWhere($filter)->getResult('array');

		// ----- get records in secondary
		return $childRecords;

	}

	// ----- remove child records from regular data 
	public function RemoveChildRecords()
	{
		// ----- init
		$cursor = [];

		// ----- set up loop to remove child records from regular data 
		foreach ($this->data as $fieldName => $fieldData) {

			// ----- check if child record
			if (strpos($fieldName, "_>" . $this->table2 . "<_") === 0)
				continue;

			$cursor[$fieldName] = $this->data[$fieldName];

		}

		$this->data = $cursor;

	}

	// ----- remove child records child record table
	public function DeleteChildRecords($id = "__NOTSET__")
	{

		if ($id == "__NOTSET__")
			$id = $this->parentIDValue;

		// ----- delete child records
		$childModel = new \Ignition\Base\BaseModel($this->table2);

        // ----- for taz (read count tracking) need all child bloglangs records because must correlate them to the id in taz 
        $modelTaz = new \Ignition\Base\BaseModel('bloglangs', ['id'], IGDBPREFIX . 'taz');
        $childRecords = $childModel->where([$this->fieldNameChildRelated => $id])->get()->getResult('array');

        // ----- delete correlate records from taz
        foreach($childRecords as $record) {
            $modelTaz->delete($record['id']);
        }

		$childModel->where($this->fieldNameChildRelated, $id)->delete();

	}

    // ----- process amendable style databases.  when not called from an Ignition autoform will need to manually
    //       create the $formFields array before calling this function.  Study an autoform and the stub file in 
    //       dna/Library/iform-set-fields.php.  $formFields is the descriptor file that maps and describes the 
    //       required fields for an amend type database
    public function ProcessAMD($data, $formFields, $new = FALSE) {

        // ----- check for new
        if (!$new) {
            // ----- get entire child table record
            $currentAMDChild = $data['__SESSIONID2428934__'];
    		$db      = \Config\Database::connect($GLOBALS['DBGROUP']);
    		$builder = $db->table($this->table2);
            $childRecord = $builder->getWhere(['id' => $currentAMDChild])->getResult('array');
            $childRecord = (empty($childRecord) ? FALSE : $childRecord[0]);
        } else 
            $childRecord = FALSE;

        // ----- if zero records, create one
        if (!$childRecord) {
            // ----- now, creating child record
            $new = TRUE;
            $childRecord = [];

        } else {

            // ----- verify that this record belongs to the parent (defends against switching in browser form, hacking)
            if ($childRecord[$this->fieldNameChildRelated] != $data['id'])
                halt('ID invalid fieldNameChildRelated in BaseModel or data id value for AMD form');
        }

        // ----- fill in any missing data elements
        foreach ($this->allowedFields2 as $dataKey => $dataValue) {
            if (!isset($data[$dataValue]))
                $data[$dataValue] = '';
        }

        // ----- set up loop to save fields to child record
        foreach ($formFields as $fieldKey => $fieldValue) {

            // ----- check for amend field flag
            if ($fieldValue['dbType'] == 'AMD') {

                if ($fieldValue['options']['enabled'])
                    $childRecord[$fieldValue['name']] = $data[$fieldValue['name']];

                unset($data[$fieldValue['name']]);

            }

        }

        // ----- save updated fields to amend record
		$childModel = new \Ignition\Base\BaseModel($this->table2, $this->allowedFields2);

        // ----- check for new
        if ($new) {
            $returnVal = $childModel->insert($childRecord);
            $this->currentChildRecord = $childModel->getInsertID();
        } else {

    		$returnVal = $childModel->update($currentAMDChild, $childRecord);
        }

		if (!$returnVal) {
    		$this->validationErrors = $childModel->errors();
    		$this->validationErrors += ["child_record" => " In child record: " . $child[$this->keyField2]];
    		return FALSE;
    	}

        // ----- unset child records from data array
        unset($data['__SESSIONID2428934__']);
        foreach ($data as $key => $dataField) {

            // ----- check for child record indicators
            if (!(strpos($key, "_>>") === FALSE)) {
                if (!(strpos($key, "_>") === FALSE)) {
                    unset($data[$key]);
                }
            }

        }

        // ----- delete from data any table2 fields
        foreach ($this->allowedFields2 as $dataKey => $dataValue) {
            if (isset($data[$dataValue]) && $dataValue != 'id')
                unset($data[$dataValue]);
        }

        return $data;

    }

	// ----- primary update function for parent record containing child table records
	//
	public function IUpdateChild($id)
	{

		// ----- assumes using iform(), this fixes checkboxes.  see w3 issue williamson.software website
		$this->FixCheckBoxesChild();	

		// ----- call ci update and set errors
		$return_val = $this->update($id,  $this->data);
		$this->validationErrors = $this->errors();

		// ----- return if had error saving
		if (!$return_val)
			return false;

		// ----- save all children records, check for validation errors
		if (!$this->SaveChildrenData())
			return false;

		$this->validationErrors = $this->errors();
		return $return_val;

	}

	// ----- primary insert function for parent record containing child table records
	//
	public function IInsertChild()
	{
		// ----- assumes using iform(), this fixes checkboxes.  see w3 issue williamson.software website
		$this->FixCheckBoxesChild();

		// ----- call ci update
		$this->data['id'] = 0;			// set zero because temp ID non numeric some times 
		$return_val = $this->insert($this->data);
		$this->validationErrors = $this->errors();

		// ----- check for errors
		if (!$return_val)
			return false;

		// ----- set new parent id 
		$this->parentIDValue = $this->getInsertID();

		// ----- save all children records
		if (!$this->SaveChildrenData())
			return false;

		$this->validationErrors = $this->errors();
		return $return_val;
	}

	// ----- fixes w3 issue with checkboxes, not returned in post data if not checked
	//       uses hidden field in iform prefixed by __CHECKBOX__ to locate and rectify w3
    function FixCheckBoxesChild()
    {
		// ----- store keys
		$dataKeys = array_keys($this->data);

		// ----- set up loop to find all check boxes.  if exist in post data, set true or set false
		foreach($dataKeys as $dataKey) {

			// ----- check to see if check box hidden
			if (substr($dataKey, 0, 12) == "__CHECKBOX__") {

				// ----- check to see if the checkbox returned in post data
				if (isset($this->data[substr($dataKey, 12, strlen($dataKey) - 11)]))
					$this->data[substr($dataKey, 12, strlen($dataKey) - 11)] = 1;
				else
					$this->data[substr($dataKey, 12, strlen($dataKey) - 11)] = 0;

				// ----- remove temp hidden field
				unset($this->data[$dataKey]);
			}
		}

		return;

	}

}