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

namespace Ignition\Author\MC;

class Model extends \Ignition\Base\BaseModel
{

	protected $table = 'authors';
    protected $subDirectory = 'author';		// enables iamage uploads

}
