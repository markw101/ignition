<?php
/*********************************************************************
    AUTHOR:      Mark E. Williamson
    FILE:        Model.php
    VERSION:     See BaseController.php
    DESCRIPTION: 
    COPYRIGHT:   2022
    FIRST REV:   16 Mar 2022
    LICENSE:     MIT

*********************************************************************/

namespace Ignition\AutoForm\MC;

// ----- assets model
class Model extends \Ignition\Base\BaseModel
{
    public $table, $iForm, $subDirectory;
    public $allowedFields = '';
    public $fieldLabels = '';
    public $formFields = '';
    public $validationRules = '';

}

?>