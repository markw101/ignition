<?php 
/*********************************************************************
    AUTHOR:      Mark E. Williamson
    FILE:        Controller.php
    VERSION:     See BaseController.php
    DESCRIPTION: Displays a form using Ignition class
    COPYRIGHT:   2022
    FIRST REV:   16 Mar 2022
    LICENSE:     MIT

*********************************************************************/

namespace Ignition\Asset\MC;

use Ignition\Asset\MC\Model;

// ----- introduce asset functionality
class Controller extends \Ignition\Base\BaseController
{
    protected $viewPath = 'Ignition\Asset\Views';

    // ----- constructor for configuration
    public function __construct()
    {
        $this->layoutConfig = "NONEW";

        PARENT::__construct();
    }

	// ----- show files and directories in directory indicated by $targetPath and $cat 
    public function index($dir1 = '', $dir2 = '', $dir3 = '', $dir4 = '', $dir5 = '', $dir6 = '', $dir7 = '', $dir8 = '')
    {
        // ----- include the generic config file 
        include_once APPPATH . 'Ignition/Asset/Asset.autoform.php';

        // ----- set model 
        $model = new \Ignition\Asset\MC\Model;

        $this->moduleMethod = 'index';             // sometimes called without index in uri

        // ----- set field labels
        $model->fieldLabels = $iIndex['fieldLabels'];

        // ----- compile subdirectories into single string
        $subDirs = ($dir1 ? '/' . $dir1 . '/' : '') . ($dir2 ? $dir2 . "/" : '') . ($dir3 ? $dir3 . "/" : '') . ($dir4 ? $dir4 . "/" : '') . ($dir5 ? $dir5 . "/" : '') . ($dir6 ? $dir6 . "/" : '') . ($dir7 ? $dir7 . "/" : '') . ($dir8 ? $dir8 . "/" : '');

        // ----- run pager to return per page # of elements
        $elements = $model->FilePaginate($subDirs);

        // ----- must check return flags whenever call FilePaginate
        if (ValSet($model->flags, '|NOEXIST') || ValSet($model->flags, '|ISFILE'))
            $this->Go404();

        $breadCrumbs = [
	        ['name' => lang('base.site'),
	        'url' => BaseURL('/')],
	        ['name' => lang('base.mydashboard'),
	        'url' => BaseURL('/controlpanel' . $GLOBALS['KEYCODE'])],
	        ['name' => lang('base.asset'),
	        'url' => BaseURL('/asset' . $GLOBALS['KEYCODE'])]
		];

        // ----- add sub directories to breadcrumbs
        if ($dir1)
            $breadCrumbs[] = ['name' => $dir1, 'url' => BaseURL('/asset' . $GLOBALS['KEYCODE'] . '/index/' . $dir1)];
        if ($dir2)
            $breadCrumbs[] = ['name' => $dir2, 'url' => BaseURL('/asset' . $GLOBALS['KEYCODE'] . '/index/' . $dir1 . '/' . $dir2)];
        if ($dir3)
            $breadCrumbs[] = ['name' => $dir3, 'url' => BaseURL('/asset' . $GLOBALS['KEYCODE'] . '/index/' . $dir1 . '/' . $dir2 . '/' . $dir3)];
        if ($dir4)
            $breadCrumbs[] = ['name' => $dir4, 'url' => BaseURL('/asset' . $GLOBALS['KEYCODE'] . '/index/' . $dir1 . '/' . $dir2 . '/' . $dir3 . '/' . $dir4)];
        if ($dir5)
            $breadCrumbs[] = ['name' => $dir5, 'url' => BaseURL('/asset' . $GLOBALS['KEYCODE'] . '/index/' . $dir1 . '/' . $dir2 . '/' . $dir3 . '/' . $dir4 . '/' . $dir5)];
        if ($dir6)
            $breadCrumbs[] = ['name' => $dir6, 'url' => BaseURL('/asset' . $GLOBALS['KEYCODE'] . '/index/' . $dir1 . '/' . $dir2 . '/' . $dir3 . '/' . $dir4 . '/' . $dir5 . '/' . $dir6)];
        if ($dir7)
            $breadCrumbs[] = ['name' => $dir7, 'url' => BaseURL('/asset' . $GLOBALS['KEYCODE'] . '/index/' . $dir1 . '/' . $dir2 . '/' . $dir3 . '/' . $dir4 . '/' . $dir5 . '/' . $dir6 . '/' . $dir7)];
        if ($dir8)
            $breadCrumbs[] = ['name' => $dir8, 'url' => BaseURL('/asset' . $GLOBALS['KEYCODE'] . '/index/' . $dir1 . '/' . $dir2 . '/' . $dir3 . '/' . $dir4 . '/' . $dir5 . '/' . $dir6 . '/' . $dir7 . '/' . $dir8)];

        return $this->RenderTheme('index', [
            'totalBytes' => substr($model->flags, strpos($model->flags, 'FILEFOUND') + 9, strlen($model->flags)),
            'fileQuotaSize' => $this->IConfig->fileQuotaSize,
            'baseDirectory' => $model->filePath,
            'breadCrumbs' => $breadCrumbs,
            'controller' => $this->moduleName,
			'elements' => $elements,
            'model' => $model,
            'adminLayout' => TRUE,
            'errors' => $model->errors()
        ]);

    }

    public function delete () {
        $this->edit();
    }

	// ----- edit a single record
    public function edit()
    {

        // ----- include the generic config file 
        include_once APPPATH . 'Ignition/Asset/Asset.ignition.php';

        // ----- set model 
        $model = new \Ignition\Asset\MC\Model;

        $iFormKeys = array_keys($iForm);
        $keyCount = sizeof($iForm);

        // ----- set field labels
        for ($count = 0; $count < $keyCount; $count ++) {

            // ----- store sub array name (field name)
            $fieldName = $iFormKeys[$count];
            $fieldValues = $iForm[$fieldName];

            // ----- check for label and set
            if (isset($fieldValues['label']))
                $fieldLabels[$fieldName] = $fieldValues['label'];

            // ----- add to allowed
            $allowedFields[$count] = $fieldName;

            // ----- set formFields values
            $options = (isset($fieldValues['viewOnly']) && $fieldValues['viewOnly'] == 'true' ? ['enabled' => '0'] : []);
            /// ADD DEFAULT AS TEXT FIELD TYPE
            $formFields[$count] =
                ['name' => $fieldName,
                'options' => $options,
                'fieldType' => $fieldValues['fieldType']];

            // ----- check for validation and set
            if (isset($fieldValues['validation']))
                $validationRules[$fieldName] = $fieldValues['validation'];

        }

        // ----- assign values to model
        $model->fieldLabels = $fieldLabels;
        $model->allowedFields = $allowedFields;
        $model->validationRules = $validationRules;
        $model->formFields = $formFields;
        $model->iForm = $iForm;

		// ----- get input fields from url object
        $data = $this->request->getPost();

        // ----- call data validation
    	if ($data)
        {

            if ($this->moduleMethod === 'delete') {

                $model->delete($this->moduleID);

                // ----- create session update message
                $this->SetFlashMessage(lang('base.record_successfully_deleted'));
                return $this->redirect($this->moduleName);
            }

            // ----- otherwise edit or insert record
            if ($model->validate($data))
            {
                $returnVal = $model->IUpdate($this->moduleID,  $data);

                // ----- set success message
                $this->SetFlashMessage(lang('base.record_successfully_updated'));

                return $this->redirect($this->moduleName);
            }

        }
        else {

            $data = $model->find($this->moduleID);

            // ----- if not found
            if (!$data)
                $this->redirect($this->moduleName);
        }

        return $this->RenderTheme('form', [
            'controller' => $this->moduleName,
            'iForm' => $iForm,
            'model' => $model,
            'data' => $data,
            'adminLayout' => TRUE,
            'errors' => $model->errors()
        ]);

    }

    // ------ either upload files contained in $_FILES or, if empty,
    //        download all files with matching recordid. This ties
    //        into the JS code contained in dropzone
    public function getfiles($subDirectory, $recordId)
    {
        // ----- set target path
        $model = new \Ignition\Asset\MC\Model;

        // ----- check to see if _FILES post exists, otherwise return 
        //       list of files to browser through json
        if (!empty($_FILES))
            $model->UploadFiles($_FILES, $subDirectory, $recordId);
        else
            $model->GetFiles($subDirectory, $recordId);
    }

    // ---- delete a file in the targetPath for the recordi
    public function deletefile($subDirectory, $fileName)
    {
        // ----- call delete 
        $model = new \Ignition\Asset\MC\Model;
        $model->DeleteFile($fileName, $subDirectory);
    }

    // ----- return a file as a download to client
    public function getfile($subDirectory, $fileName)
    {
        // ------ call ReturnFile in model
        $model = new \Ignition\Asset\MC\Model;
        $model->ReturnFile($subDirectory, $fileName);
    }

    public function copycliptext($subDirectory, $fileName)
    {
        // ------ call ReturnFile in model
        //$model = new \Ignition\Asset\MC\Model;
        //$model->ReturnFile($subDirectory, $fileName);

        //echo '<script>';
  //var copyText = document.getElementById("uploadName1");
  //copyText.select();
  //copyText.setSelectionRange(0, 99999); /* For mobile devices */
        return '<script>navigator.clipboard.writeText("' . $subDirectory . $fileName . '");</script>';
  //alert("Copied the text: " + copyText.value);
  //} 
        //echo '</script>';


    }
}
