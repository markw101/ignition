<?php
/*********************************************************************
    AUTHOR:      Mark E. Williamson
    FILE:        AutoForm.php
    VERSION:     See BaseController.php
    DESCRIPTION: Key file in the ignition forms system
    COPYRIGHT:   2022
    FIRST REV:   16 Mar 2022
    LICENSE:     MIT

*********************************************************************/

namespace Ignition\AutoForm\MC;

class Controller extends \Ignition\Base\BaseController
{

    protected $viewPath = 'Ignition\AutoForm\Views';
	protected $breadCrumbs = '';
    protected $modName = '';
    protected $autoFormFile;

    // ----- constructor for configuration
    public function __construct()
    {
        PARENT::__construct();

        // ----- merge user built channels with ignition base channels
        $this->baseIChannels = array_merge($this->baseIChannels, $this->IConfig->siteIChannels);

		// ----- strip out keycode from mod name 
		if ($keyPos = strpos($this->moduleName, $GLOBALS['KEYCODE']))
			$this->modName = substr($this->moduleName, 0, $keyPos);
        else 
            $this->modName = $this->moduleName;

        // ----- check for array type and added settings
        if (isset($this->baseIChannels[$this->modName]) && is_array($this->baseIChannels[$this->modName])) {

            // ----- shift segments based upon having module be a submodule of base module (moduleName)
            $this->subModule = $this->moduleMethod;
            $this->moduleMethod = $this->moduleID;
            if ($this->uri->getTotalSegments() > 3 && $this->moduleMethod != 'index')
                $this->moduleID = $this->uri->getSegment(4);

            // ----- set autoform file
            $this->autoFormFile = $this->baseIChannels[$this->modName][$this->subModule]['include'];

            // ----- set options
            if (isset($this->baseIChannels[$this->modName][$this->subModule]['appLayout']))
                $this->appLayoutFile = $this->baseIChannels[$this->modName][$this->subModule]['appLayout'];
            if (isset($this->baseIChannels[$this->modName][$this->subModule]['adminColor']))
                $GLOBALS['ADMINMENUCOLOR'] = $this->baseIChannels[$this->modName][$this->subModule]['adminColor'];
        } elseif (isset($this->baseIChannels[$this->modName]))
            $this->autoFormFile = $this->baseIChannels[$this->modName];

    }

	// ----- shows all records as index listing
    public function index($securityCode = FALSE)
    {

        // ----- init
        $filter = '';

        // ----- if security code enabled
        if ($securityCode) {
            $flashData = TrGetFlash(FALSE);
            if ((gettype($flashData) != 'array') || empty($flashData) || ($flashData['securityCode'] != $securityCode))
    		  halt(lang('base.access') . ' ' . lang('base.denied'));
            $this->securityCode = $securityCode;
        }

        // ----- include the generic config file 
        include_once APPPATH . $this->autoFormFile;

        // ----- if require security code, but failed to provide
        if (isset($requireSecurityCode) && $requireSecurityCode && $securityCode == '')
            halt(lang('base.access') . ' ' . lang('base.denied') . ' AutoForm');

        // ----- check for filter field
        if (isset($filterField))
            $filter = ['filterField' => $filterField, 'filterValue' => $filterValue];
        else 
            $filter = '';

    	// ----- check for custom get database 
		if (function_exists('getDatabase'))
			$databaseName = getDatabase($dataTable);

		// ----- check for non default database 
		if (isset($databaseName))
			$GLOBALS['DBGROUP'] = $databaseName; // this must be a valid group in .env AND /Config/Database.php 

        // ----- check authenticiation IF SET, redirect if not correct
		if ($this->allowedUsersEdit !== '' && !ValSet($this->allowedUsersEdit, UT))
            $this->redirect("/");

		// ----- check for custom language library
		if (isset($customLanguage))
			$this->customLanguage = $customLanguage;
        else if (CUSTOMLANG)
            $this->customLanguage = CUSTOMLANG;

        // ----- check for custom data model 
        if (isset($modelClass)) {
            $model = new $modelClass;
        } else {
            $model = new \Ignition\AutoForm\MC\Model;
            $model->table = $dataTable;
        }

        $this->moduleMethod = 'index';             // sometimes called without index in uri

        // ----- set field labels
        $model->fieldLabels = $iIndex['fieldLabels'];

		// ----- check for special text 
		$indexText = (isset($indexText) ? $indexText : '');

		// ----- set any auxilliary buttons
		$deleteButton = (isset($deleteButton) ? $deleteButton : FALSE);
		$editButton = (isset($editButton) ? $editButton : FALSE);
		$viewButton = (isset($viewButton) ? $viewButton : FALSE);
		$auxButtons = ($editButton ? ':edit' : '') . ($deleteButton ? ':delete' : '') . ($viewButton ? ':view' : '');

        return $this->RenderTheme('index', [
			'auxButtons' => $auxButtons,
            'controller' => $this->moduleName,
			'pager' => TRUE,
            'model' => $model,
            'AppLayout' => TRUE,
			'indexText' => $indexText,
			'breadCrumbs' => $this->breadCrumbs,
			'filter' => $filter,
            'securityCode' => $securityCode,
            'subModule' => $this->subModule,
            'errors' => $model->errors()
        ]);
    }

    public function new () {
        $this->edit();
    }

    public function delete () {
        $this->edit();
    }

    public function view () {
        $this->edit();
    }

	// ----- edit a single record
    public function edit($record = FALSE, $securityCode = '')
    {
		// ----- init
		$showOnly = [];

        // ----- if security code enabled
        if ($securityCode != '') {
            $flashData = TrGetFlash(FALSE);
            if ((gettype($flashData) != 'array') || empty($flashData) || ($flashData['securityCode'] != $securityCode)) {
    		  haltLog(lang('base.access') . ' ' . lang('base.denied') . ' AutoForm1');
            }
            $this->securityCode = $securityCode;
        }

        // ----- include the generic config file 
        $channelConfig = APPPATH . $this->autoFormFile;
        include_once $channelConfig;

        // ----- if require security code, but failed to provide
        if (isset($requireSecurityCode) && $requireSecurityCode && $securityCode == '')
            haltLog(lang('base.access') . ' ' . lang('base.denied') . ' AutoForm2');

        // ----- check authenticiation IF SET, redirect if not correct
		if ($this->allowedUsersEdit !== '' && !ValSet($this->allowedUsersEdit, UT))
            $this->redirect("/");

		// ----- check for custom get database 
		if (function_exists('getDatabase'))
			$databaseName = getDatabase($dataTable);

		// ----- check for non default database 
		if (isset($databaseName))
			$GLOBALS['DBGROUP'] = $databaseName; // this must be a valid group in .env AND /Config/Database.php 

		// ----- check for customer language library
		if (isset($customLanguage))
			$this->customLanguage = $customLanguage;
        else if (CUSTOMLANG)
            $this->customLanguage = CUSTOMLANG;

        // ----- check for custom data model 
        if (isset($modelClass)) {
            $model = new $modelClass;
        } else {
            $model = new \Ignition\AutoForm\MC\Model;
            $model->table = $dataTable;
        }

        $iFormKeys = array_keys($iForm);
        $keyCount = sizeof($iForm);

        // ----- set field labels
        include_once APPPATH . 'Ignition/Library/iform-set-fields.php';

		// ----- get input fields from url object
        $model->data = $this->request->getPost();

        // ----- check for need to set allowedfields
        if (is_string($allowedFields) && $allowedFields == 'ALL') {

            // ----- igniton autoform requested all fields 
            $db = SetDB(isset($databaseName) ? $databaseName : DEFAULTDBGROUP);
            $model->allowedFields = $db->getFieldNames($dataTable);

        } else 
            $model->allowedFields = $allowedFields;

        // ----- call data validation
    	if ($model->data && $this->moduleMethod != 'view') {

            if ($this->moduleMethod === 'delete') {

                $model->delete($this->moduleID);

                // ----- create session update message
                $this->SetFlashMessage(lang('base.record_successfully_deleted'));
                return $this->redirect($this->moduleName);
            }

			// ----- determine if valid 
			if (isset($model->validationRules) && $model->validationRules != '')
				$valid = $model->validate($model->data);
			else {
				$valid = TRUE;
				$model->validationRules = [];
			}

            // ----- edit or insert record
            if ($valid)
            {
                if ($this->moduleMethod === 'new') {

                    // ----- save dropzone key from id (if file uploads)
                    if ($model->subDirectory)
                        $dropzoneNewKey = $model->data['id'];

                    if ($model->IInsert($model->data)) {

                        // ----- rename temp files, create gave files only temp id number
                        if ($model->subDirectory)
                            $model->RenameUploaded($dropzoneNewKey);

                        // ----- set success message
                        $this->SetFlashMessage(lang('base.record_successfully_created'));
						return $this->redirect($this->moduleName);

                    } else {
                        $this->SetFlashMessage(lang('base.failure'));
						$returnVal = FALSE;
					}

                } else {
					$model->data['id'] = GetEditID(); // prevents session jumping

					// ----- check for modified
					if ($this->moduleID != $model->data['id'])
						haltLog('Record ID mismatch in call to autoform edit');

                    $returnVal = $model->IUpdate($model->data['id'],  $model->data);
				}

				// ----- if no errors
				if ($returnVal) {

	                // ----- set success message
	                $this->SetFlashMessage(lang('base.record_successfully_updated'));

	                return $this->redirect($this->moduleName);
				}
            }

        } else {
    	    // ----- CHECK FOR CHILD RECORDS, REQUIRES USING BASEMODELRELATE
			if (isset($dataTable2)) {

                // ----- check for requested all fields2
                if (!($this->moduleMethod == 'view') && is_string($allowedFields2) && $allowedFields2 == 'ALL') {
                    $allowedFields2 = GetFieldNames($dataTable2, $GLOBALS['DBGROUP']);
                }

                // ----- update model with parent/child table relation fields
                if ($this->moduleMethod != 'view')
                    $model->allowedFields2 = $allowedFields2;

                $model->fieldNameChildRelated = $fieldNameChildRelate;
                $model->table2 = $dataTable2;
                $model->checkFieldsBlankNoSave = $checkBlankNoSave;
                $model->keyField2 = $keyFieldLink2;
            }

			// ----- check for new 
			if ($this->moduleMethod == 'new') {

				// ----- check for custom create 
				if (function_exists('createNew'))
					$model->data = createNew($model);
				else
    		    	$model->data = $model->AllowedBlank(array_merge($model->allowedFields, $showOnly));

				// ----- check for file uploads requested (needed for uploads to new records without id)
				if (isset($baseDirectory))
					$model->data['id'] = 'TEMP' . RandomName(4);

			} else {

				$model->data = $model->IFind(($record ? $record : $this->moduleID));

				// ----- if not found
				if (!$model->data) {

                    if ($this->moduleMethod == 'view')
        		    	$model->data = $model->AllowedBlank(GetFieldNames($dataTable, $databaseName));
                    else
    				    $this->redirect((isset($redirect) ? $redirect : $this->moduleName));

                }

                // ----- check for security
                if ($this->securityCode && $model->data[$filterField] != $filterValue)
                    haltLog(lang('base.access') . ' ' . lang('base.denied') . ' AutoForm3 ' . GetUserID());

    			// ----- CHECK FOR CHILD RECORDS, REQUIRES USING BASEMODELRELATE
                /// this was copied from dna autoform but never implemented or tested
    			if (isset($dataTable2)) {

        			// ----- get child records and store to data within model
        			$childRecords = $model->GetChildrenComplete([], $model->parentIDValue, TRUE, TRUE);
        			$model->data = array_merge($model->data, $childRecords);

                }

			}
		}

        // ----- check for override form 
        if (isset($editForm)) {

            // ----- make sure exists
            $lastSlash = strrpos($channelConfig, "/");

            // ----- check to see if file exists
            if (file_exists(substr($channelConfig, 0, $lastSlash + 1) . 'Views/' . $editForm)) {
                $lastSlash = strrpos($this->baseIChannels[$this->modName], "/");
                $this->viewPath = FlipSlashes(substr($this->baseIChannels[$this->modName], 0, $lastSlash) . '/Views');
                $viewFile = $editForm;
            } else {
                // ----- warn user 
                echo '<script language="javascript">alert("Form file not found!");</script>';
                $viewFile = 'form';
            }
        } else 
            $viewFile = 'form';

		// ----- check for special text 
		$formText = (isset($formText) ? $formText : '');

        // ----- call fix up, everything that requires doing after variables are processed and before form is rendered
        if (function_exists('PostDataSet'))
            $model = PostDataSet($model);

		// ----- set the edit session id 
		SetEditID($model->data['id']);

        return $this->RenderTheme($viewFile, [
            'controller' => $this->moduleName,
            'method' => $this->moduleMethod,
            'iForm' => $iForm,
            'model' => $model,
            'AppLayout' => TRUE,
			'breadCrumbs' => $this->breadCrumbs,
			'formText' => $formText,
            'errors' => $model->errors()
        ]);

    }
}

