<?php

/*********************************************************************
    AUTHOR:      Mark E. Williamson
    FILE:        Blog.php
    VERSION:     See BaseController.php
    DESCRIPTION: 
    COPYRIGHT:   2022
    FIRST REV:   16 Mar 2022
    LICENSE:     MIT

*********************************************************************/

namespace Ignition\Blog\MC;

class Blog extends \Ignition\Base\BaseController
{

    protected $viewPath = 'Ignition\Blog\Views';

	// ----- admin function, shows all blogs 
    public function index()
    {

        $model = new \Ignition\Blog\Forms\Index;
        $this->moduleMethod = 'index';             // sometimes called without index in uri

		// ----- paginate
		$elements = $model->orderBy('id','DESC')->paginate($this->IConfig->perPage);

		// ----- set child record values 
		//       setup loop to replace title and slug with language of user 
		$elem = [];
		$count = 0;
//		$count = count($elements) - 1;		// going in reverse order
		foreach ($elements as $element) {

			// ----- sefety
			if ($count < 0)
				break;

			$elem[$count] = $element;

			// ----- search matching
			$model->parentIDValue = $element['id'];
			$lang = $model->GetChildrenReturn(['bloglang_lang' => $GLOBALS['LANGCODE']]);

			// ----- check for empty
			if ($lang == []) {

				// ----- fall back to default locale (may be different than url)
				$lang = $model->GetChildrenReturn(['bloglang_lang' => DEFAULTLOCALE]);

				// ----- if true, then assumed no usable child records to derive a title and url slug
				if ($lang == []) {
					$elem[$count]['blog_name'] = $element['blog_name'];
					$elem[$count]['created_at'] = $element['created_at'];
					$elem[$count]['blog_title'] = '***************';
					$elem[$count]['blog_slug'] = "***************";
					$count ++;
					continue;
				}
			}

			// ----- set values
			$lang = $lang[0];
			$elem[$count]['blog_title'] = $lang['bloglang_title'];
			$elem[$count]['blog_slug'] = $lang['bloglang_slug'];
			$elem[$count]['created_at'] = $lang['bloglang_refreshdate'];

			// ----- decrement counter
			$count ++;

		}

        return $this->RenderTheme('index', [
			'elements' => $elem,
			'pager' => true,
            'model' => $model,
            'AppLayout' => TRUE,
            'errors' => $model->validationErrors
        ]);

    }

	// ----- add new record
    public function new()
    {

        $model = new \Ignition\Blog\Forms\Form('TEMP' . RandomName(4));

		// ----- get input fields from url object
        $model->data = $this->request->getPost();

        // ----- call data validation
    	if ($model->data)
        {

            // ----- data already set, validate
            if ($model->IValidateChild())
            {
                // ----- save dropzone key from id 
                $dropzoneNewKey = $model->data['id'];

                if ($model->IInsertChild()) {

                    // ----- rename temp files, create gave files only temp id number
                    $model->RenameUploaded($dropzoneNewKey);

                    // ----- add corresponding bloglangs record to taz
                    $modelTaz = new \Ignition\Base\BaseModel('bloglangs', ['active'], IGDBPREFIX . 'taz');
                    $modelTaz->SetTimeStamps(FALSE);
                    $modelTaz->insert(['active' => TRUE]);

                    // ----- set success message
                    $this->SetFlashMessage(lang('base.record_successfully_created'));

	                $this->redirect('blog' . $GLOBALS['KEYCODE'] . '/index');
	                return;

                } else
                    $this->SetFlashMessage(lang('base.failure'));

            }
        }
        else {

            $model->data = $model->AllowedBlank();
            $model->data['id'] = $model->parentIDValue;
	        $model->data['active'] = '1';
			$model->data['blog_languages'] = "'" . DEFAULTLOCALE . "'";

			// ----- get language child records and store to data within model
			$languagesArticle = $model->GetChildrenComplete([DEFAULTLOCALE], FALSE, TRUE);
			$model->data = array_merge($model->data, $languagesArticle);

        }

        return $this->RenderTheme('form', [
            'model' => $model,
            'formType' => 'new',
            'AppLayout' => true,
            'errors' => $model->validationErrors
        ]);
    }

	// ----- edit one record
    public function edit($id = 1)
    {

        $model = new \Ignition\Blog\Forms\Form($id);

		// ----- get input fields from url object
        $model->data = $this->request->getPost();

        // ----- call data validation
    	if ($model->data)
        {

            // ----- data already set, validate
            if ($model->IValidateChild())
            {

                if ($model->IUpdateChild($id)) {

	                // ----- set success message
	                $this->SetFlashMessage(lang('base.record_successfully_updated'));

	                if (isset($_SESSION['ReturnToForm']) && $_SESSION['ReturnToForm'])
	                    unset($_SESSION['ReturnToForm']);
	                else
	                    $this->redirect('blog' . $GLOBALS['KEYCODE'] . '/index');
				}
            }

        } else {
    		$model->data = $model->find($id);

            if ($model->data == '') {
                $this->redirect('blog' . $GLOBALS['KEYCODE'] . '/index');
                return;
            }

			// ----- determine if multiple languages are being utilzied for this article
			if ($model->data['blog_languages'] == '')
				$langs = [DEFAULTLOCALE];
			else
				$langs = quotetoarray($model->data['blog_languages']);

			// ----- get language child records and store to data within model
			$languagesArticle = $model->GetChildrenComplete($langs, $id, TRUE);
			$model->data = array_merge($model->data, $languagesArticle);
        }

        return $this->RenderTheme('form', [
            'model' => $model,
            'adminLayout' => true,
            'formType' => 'edit',
            'breadCrumbs' => $this->AutoCrumble('edit', $id),
            'errors' => $model->validationErrors
        ]);
    }

	// ----- delete record, uses soft deletes
    public function delete($id)
    {

        // ----- init
        $model = new \Ignition\Blog\Forms\Form($id);
        $model->data = $this->request->getPost();

        // ----- no validation
    	if ($model->data)
        {

            $model->delete($id);
			$model->DeleteChildRecords($id);

            // ----- create session update message
            $session = service('session');
            $session->SetFlashdata('success', lang('base.record_successfully_deleted'));

            $this->redirect('blog' . $GLOBALS['KEYCODE'] . '/index');
            return;
        }
        else {
    		$model->data = $model->find($id);

            if (!$model->data) {
                $this->redirect('blog' . $GLOBALS['KEYCODE'] . '/index');
                return;
            }

			// ----- determine if multiple languages are being utilzied for this article
			if ($model->data['blog_languages'] == '')
				$langs = [DEFAULTLOCALE];
			else
				$langs = quotetoarray($model->data['blog_languages']);

			// ----- get language child records and store to data within model
			$languagesArticle = $model->GetChildrenComplete($langs);
			$model->data = array_merge($model->data, $languagesArticle);
        }

        return $this->RenderTheme('form', [
            'model' => $model,
            'adminLayout' => true,
            'formType' => 'delete',
            'breadCrumbs' => $this->AutoCrumble('delete', $id),
            'errors' => $model->validationErrors
        ]);
    }

	// ----- OPTION TO CREATE A HOME PAGE FOR BLOG ORIENTED WEBSITES. SET AS DEFAULT 
	//       ROUTE IN app/config/routes.php
    public function blogHome() {

        $model = new \Ignition\Asset\MC\Model;

        return $this->RenderTheme('blogHome', [
            'model' => $model
        ]);

    }
}
