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

namespace Ignition\Blog\Forms;

use Ignition\Blog\MC\Model;

// ----- specialized form input
class Form extends Model
{

	protected $allowedFields = [
        'id',
		'updated_at',
		'blog_name',
		'created_at',
        'blog_justify',
        'blog_featured',
        'blog_comments',
        'blog_views',
        'blog_languages',
		'blog_notes',
		'active'
	];

	protected $allowedFields2 = [
        'id',
		'updated_at',
		'id_parent',
		'bloglang_lang',
		'bloglang_title',
		'bloglang_slug',
		'bloglang_text',
		'bloglang_description',
		'bloglang_category',
        'bloglang_tags',
        'bloglang_author',
		'bloglang_authorid',
        'bloglang_views',
        'bloglang_refreshdate',
		'bloglang_external_domain',
		'bloglang_external_code',
		'active'
	];

    protected $validationRules = [];

    protected $fieldLabels = [];

    // ----- constructor for configuration
    public function __construct($pid = NOTSET)
    {
        PARENT::__construct($pid);

        $this->fieldLabels = [
    		'id' => 'ID',
    		'blog_name' => 'ID' . " " . lang('base.name'),
    		'active' => lang('base.active'),
            'created_at' => lang('base.created') . " " . lang('base.date'),
            'blog_justify' => lang('base.justify') . ' ' . lang('base.text'),
            'blog_featured' => lang('base.featured'),
            'blog_comments' => lang('blog.comments'),
            'blog_views' => lang('blog.views'),
            'blog_notes' => 'Private Author Notes: does not appear in public data',
    		'blog_languages' => lang('base.language') . "s.  Each locale code (language) must be singled quoted, example: 'en'.  Your DEFAULTLOCALE, as set in SiteConstants.php, is as follows: " . DEFAULTLOCALE
        ];

        $this->fieldLabels2 = [
    		'bloglang_refreshdate' => lang('admin.refresh_date'),
            'bloglang_author' => lang('blog.author'),
            'bloglang_authorid' => lang('blog.author') . 'ID',
    		'bloglang_title' => lang('base.title'),
    		'bloglang_description' => lang('base.description') . " " . lang('base.or') . " URL",
    		'bloglang_slug' => lang('blog.slug'),
            'bloglang_category' => lang('base.category'),
            'bloglang_tags' => lang('blog.tags'),
    		'bloglang_text' => lang('blog.body_text'),
            'bloglang_views' => lang('blog.views'),
            'bloglang_external_domain' => lang('blog.external_domain'),
            'bloglang_external_code' => lang('blog.external_code'),
    		'active' => lang('base.active')
        ];

        $this->validationRules = [
    		'blog_featured' => 'in_list[0,1]',
    		'blog_justify' => 'in_list[0,1]',
    		'active' => 'in_list[0,1]'
        ];

        $this->validationRules2 = [
    		'bloglang_title' => 'max_length[255]',
		    'bloglang_slug' => [
		        'label'  => 'blog.slug',
		        'rules'  => 'required|max_length[255]',
		        //'rules'  => 'is_unique[bloglangs.bloglang_slug]|required|max_length[255]',
		        'errors' => [
		            'required' => 'Validation.required',
		        ],
		    ],
    		'active' => 'in_list[0,1]'
        ];

/*
		$this->validation->setRules([
		    'username' => [
		        'label'  => 'Rules.username',
		        'rules'  => 'required|max_length[30]|is_unique[users.username]',
		        'errors' => [
		            'required' => 'Rules.username.required',
		        ],
		    ],
		    'password' => [
		        'label'  => 'Rules.password',
		        'rules'  => 'required|max_length[255]|min_length[10]',
		        'errors' => [
		            'min_length' => 'Rules.password.min_length',
		        ],
		    ],
		]);
*/

   }

}