<?php

/*********************************************************************
    AUTHOR:      Mark E. Williamson
    FILE:        Post.php
    VERSION:     See BaseController.php
    DESCRIPTION: 
    COPYRIGHT:   2022
    FIRST REV:   16 Mar 2022
    LICENSE:     MIT

*********************************************************************/

namespace Ignition\Blog\MC;

class Post extends \Ignition\Base\BaseController
{

    protected $viewPath = 'Ignition\Blog\Views';

	// ----- list tagcloud blog stories
    public function tagcloud($tag = 'all')
    {

        $model = new \Ignition\Blog\MC\Model;

        // ----- get blog record from pages (would be more efficient to make static all here)
        $pageModel = new \Ignition\Page\MC\Model;
        $pageBlog = $pageModel->getPage('blog', TRUE);
		if (!isset($pageBlog['pagelang_narrow_text']))
			$pageBlog['pagelang_narrow_text'] = '';
		if (!isset($pageBlog['page_text']))
			$pageBlog['page_text'] = '';

        return $this->RenderTheme('tagcloud', [
            'tag' => $tag,
            'pageBlog' => $pageBlog ?? false,
            'page_narrow_text' => $pageBlog['page_narrow_text'] ?? false,
            'model' => $model,
            'narrowBanner' => true
        ]);

    }

	// ----- list all stories in category  or all stories
    public function category($SelCat = 'all')
    {
		// ----- fix all for display
		$SelCat = ($SelCat == 'all' && $GLOBALS['LANGCODE'] != 'en' ? lang("blog.all") : $SelCat);

        $model = new \Ignition\Blog\MC\Model;
        $elements = $model->paginate($this->IConfig->blogPerPage);
        $this->menuSelected = "blog";

        // ----- get blog record from pages (would be more efficient to make static all here)
        $pageModel = new \Ignition\Page\MC\Model;
        $pageBlog = $pageModel->getPage('blog', TRUE);
		if (!isset($pageBlog['pagelang_narrow_text']))
			$pageBlog['pagelang_narrow_text'] = '';
		if (!isset($pageBlog['page_text']))
			$pageBlog['page_text'] = '';

		// ----- get user request category
        $data = $this->request->getPost();

		// ----- set page title
		$this->browserTitle = " - " . lang('blog.blog_category');

        // ----- call data validation
    	if ($data)
        	$SelCat = $data['SelectCategory'];

        return $this->RenderTheme('category', [
            'SelCat' => $SelCat,
            'pageBlog' => $pageBlog ?? false,
            'page_narrow_text' => $pageBlog['pagelang_narrow_text'] ?? false,
            'model' => $model,
            'narrowBanner' => true
        ]);

    }

	// ----- show one blog story
    public function article($blogURL = 'index')
    {

        $this->menuSelected = "blog";            // set manually, override constructor (post uri)
		$this->layoutConfig .= "ARTICLE";        // this is a variable to indicate for MainLayout blogvista
        $model = new \Ignition\Blog\Forms\Form;
		$langRecord = $model->GetChildrenReturn(['bloglang_slug' => $blogURL], '__UNKNOWNPARENT__');

		// ----- verify record found
		if ($langRecord == [])
			$this->redirect('post/category');

		// ----- get corresponding blog record
		$langRecord = $langRecord[0];
		$model->data['parentIDValue'] = $langRecord['id_parent'];
        $blogRecord = $model->where('id', $langRecord['id_parent']);
        $blogRecord = $blogRecord->get(1)->getResult('array');

		// ----- verify record found
		if ($blogRecord == [])
			halt("Missing blog record for child language record id: " . $langRecord['id']);

		$model->data = $blogRecord[0];

        // ----- check to see if blog is published
        if (!isset($model->data['active']) || $model->data['active'] != 1){
            $this->redirect('blog');
            return;
        }

		// ------ increment views for story by 1
        $viewsModel = new \Ignition\Base\BaseModel('bloglangs', ['bloglang_views'], IGDBPREFIX . 'taz');
        $viewsModel->SetTimeStamps(FALSE);
        $viewRecord = $viewsModel->find($langRecord['id']);
        $viewsModel->update($langRecord['id'], ['bloglang_views' => $viewRecord['bloglang_views'] + 1]);

		// ----- set story variables 
		$model->data['blog_slug'] = $langRecord['bloglang_slug'];
		$model->data['blog_title'] = $langRecord['bloglang_title'];
		$model->data['blog_description'] = $langRecord['bloglang_description'];
		$model->data['blog_category'] = $langRecord['bloglang_category'];
		$model->data['blog_tags'] = $langRecord['bloglang_tags'];
		$model->data['blog_author'] = $langRecord['bloglang_author'];
		$model->data['blog_refresh_date'] = $langRecord['bloglang_refreshdate'];
		$model->data['updated_at'] = $langRecord['updated_at'];
		$externalDomain = $langRecord['bloglang_external_domain'];

		// ----- check for using external article
		if ($externalDomain != '') {

			// ----- using stored external domain, access article
			$url = HTTPPROT . "://" . $externalDomain . '/post/extern/' . $model->data['blog_slug'] . '/' . $langRecord['bloglang_external_code'];

			// ----- set up curl session to fetch article body text
			//       title, view count, pub date, desc are all language specific to local site
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, $url);

			// ----- finally execute curl command and retrieve body text, close session
			$model->data['blog_text'] = curl_exec($ch);
			$resultInfo = curl_getinfo($ch);
			curl_close($ch);

		} else {
			$model->data['blog_text'] = $langRecord['bloglang_text'];

		}

		// ----- set page title browser
		$this->browserTitle = ' - ' . $model->data['blog_title'];

        return $this->RenderTheme('article', [
			'externalDomain' => FALSE,
            'model' => $model,
            'data' => $model->data,
			'iconfig' => $this->IConfig,
            'narrowBanner' => true
        ]);

    }

	// ----- show one blog story
    public function externalarticle($blogURL = 'storynotfound', $accessCode = '')
    {

		if ($accessCode != REMOTEPIN) {
			echo "ACCESS DENIED!";
			halt();
		}

        $model = new \Ignition\Blog\Forms\Form;
		$langRecord = $model->GetChildrenReturn(['bloglang_slug' => $blogURL], '__UNKNOWNPARENT__');

		// ----- verify record found
		if ($langRecord == [])
			halt("External request language child record not found");

		// ----- get corresponding blog record
		$langRecord = $langRecord[0];
		$model->data['parentIDValue'] = $langRecord['id_parent'];
        $blogRecord = $model->where('id', $langRecord['id_parent']);
        $blogRecord = $blogRecord->get(1)->getResult('array');

		// ----- verify record found
		if ($blogRecord == [])
			halt("Missing blog record for child language record id: " . $langRecord['id']);

		$model->data = $blogRecord[0];

		// ------ increment views for story by 1
		$langRecord['bloglang_views'] = $langRecord['bloglang_views'] + 1;
		$model->UpdateChild($langRecord['id'],  $langRecord);

        header("HTTP/1.1 200 OK");
        header("Access-Control-Allow-Origin: *");
        header("Content-Type: application/json; charset=ISO-8859-1");

		echo $langRecord['bloglang_text'];

    }

	// ----- show custom home page
    public function blogHome() {

        $model = new \Ignition\Asset\MC\Model;

        return $this->RenderTheme('blogHome', [
            'model' => $model
        ]);

    }
}
