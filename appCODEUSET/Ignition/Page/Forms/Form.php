<?php
/*********************************************************************
    AUTHOR:      Mark E. Williamson
    FILE:        Form.php
    VERSION:     See BaseController.php
    DESCRIPTION: 
    COPYRIGHT:   2022
    FIRST REV:   16 Mar 2022
    LICENSE:     MIT

*********************************************************************/

namespace Ignition\Page\Forms;

use Ignition\Page\MC\Model;

// ----- specialized form input
class Form extends Model
{

	protected $allowedFields = [
		'updated_at',
		'page_name',
        'page_narrow_banner',
		'page_languages',
		'page_justify',
		'page_views',
		'page_no_carriage_returns',
		'active'
	];

    protected $validationRules = [];
    protected $fieldLabels = [];
    protected $validationRules2 = [];
    protected $fieldLabels2 = [];

	protected $allowedFields2 = [
        'id',
		'updated_at',
		'id_parent',
		'pagelang_title',
		'pagelang_lang',
		'pagelang_slug',
		'pagelang_text',
        'pagelang_tags',
		'pagelang_narrow_text',
		'active'
	];

    // ----- constructor for configuration
    public function __construct($pid = NOTSET)
    {
        PARENT::__construct($pid);

        $this->fieldLabels = [
			'page_justify' => lang('base.justify') . ' ' . lang('base.text'),
    		'id' => 'ID',
    		'updated_at' => lang('base.last') . ' ' . lang('base.update'),
    		'page_name' => 'ID' . " " . lang('base.name') . " - " . lang("base.no") . " " . lang("base.spaces"),
			'page_views' => lang('base.total') . ' ' . lang('base.view') . 's',
            'page_narrow_banner' => lang('base.narrow_banner'),
			'page_languages' => lang('base.language') . "s.  Each locale code (language) must be singled quoted, example: 'en'.  Your DEFAULTLOCALE, as set in SiteConstants.php, is as follows: " . DEFAULTLOCALE,
			'page_no_carriage_returns' => lang('admin.html_override'),
    		'active' => lang('base.active')
        ];

        $this->validationRules = [
    		'page_name' => 'max_length[255]',
            'page_narrow_banner' => 'in_list[0,1]',
        ];

        $this->fieldLabels2 = [
    		'pagelang_slug' => lang('base.site') . ' URL',
			'pagelang_title' => lang('base.title'),
			'pagelang_text' => lang('base.page') . ' ' . lang('base.text'),
            'pagelang_narrow_text' => lang('base.display') . ' ' . lang('base.text') . lang('base.for') . lang('base.narrow_banner'),
            'pagelang_tags' => lang('blog.tags'),
    		'active' => lang('base.active')
        ];

        $this->validationRules2 = [
    		'pagelang_name' => 'max_length[255]',
		    'pagelang_slug' => [
		        'label'  => 'blog.slug',
		        'rules'  => 'required|max_length[255]',
		        //'rules'  => 'is_unique[pagelangs.pagelang_slug]|required|max_length[255]',
		    ],
    		'active' => 'in_list[0,1]',
            'page_narrow_banner' => 'in_list[0,1]',
//		    'page_url' => 'max_length[255]|is_unique[pages.page_url,page_id,{page_id}]|required',
        ];

    }

}