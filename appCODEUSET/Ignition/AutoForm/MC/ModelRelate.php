<?php
/*********************************************************************
    AUTHOR:      Mark E. Williamson
    FILE:        ModelRelate.php
    VERSION:     See BaseController.php
    DESCRIPTION: 
    COPYRIGHT:   2022
    FIRST REV:   16 Mar 2022
    LICENSE:     MIT

*********************************************************************/

namespace Ignition\AutoForm\MC;

// ----- assets model
class ModelRelate extends \Ignition\Base\BaseModelRelate
{
    public $table, $iForm, $subDirectory;
    public $allowedFields = '';
    public $fieldLabels = '';
    public $formFields = '';
    public $validationRules = '';
    public $fieldNameChildRelated;

    // ----- relate model
    public $table2;
    public $checkFieldsBlankNoSave = [];            // if this contains field names of child record, will only save a record if at least one of these fields contain values. Otherwise, record dropped in iValidate
    public $keyField2;
    public $allowedFields2;

}

?>