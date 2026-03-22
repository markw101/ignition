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

namespace Ignition\Blog\MC;

class Model extends \Ignition\Base\BaseModelRelate
{

	protected $table = 'blog';
    protected $subDirectory = 'blog';		// enables iamage uploads
	protected $table2 = 'bloglangs';		// child table name (see Ignition\Blog\MC\BaseModelRelate.php)
    // ----- if this contains field names of child record, will only save a record if at least one 
    //       of these fields contain values.  Otherwise, record dropped in iValidate
	protected $checkFieldsBlankNoSave = ['bloglang_title', 'bloglang_description', 'bloglang_text', 'bloglang_slug', 'bloglang_cateogory', 'bloglang_tags'];
	protected $keyField2 = 'bloglang_lang';    // primary categorization field for child table 

}
