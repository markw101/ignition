<?php
/*********************************************************************
    AUTHOR:      Mark E. Williamson
    FILE:        Page.php
    VERSION:     See BaseController.php
    DESCRIPTION: 
    COPYRIGHT:   2022
    FIRST REV:   16 Mar 2022
    LICENSE:     MIT

*********************************************************************/

namespace Ignition\Page\MC;

class Page extends \Ignition\Base\BaseController
{

    protected $viewPath = 'Ignition\Page\Views';

	// ----- admin function, shows all pages 
    public function index()
    {
        // ----- check for access allowed
        if (!SITEADMIN)
            $this->redirect('/');

        $model = new \Ignition\Page\Forms\Index;
        $this->moduleMethod = 'index';             // sometimes called without index in uri

		// ----- paginate
		$elements = $model->paginate($this->IConfig->perPage);
		
		// ----- set child record values 
		//       setup loop to replace title and slug with language of user 
		$elem = [];
		$count = 0;
		foreach ($elements as $element) {

			$elem[$count] = $element;

			// ----- search matching
			$model->parentIDValue = $element['id'];
			$lang = $model->GetChildrenReturn(['pagelang_lang' => $GLOBALS['LANGCODE']]);

			// ----- check for empty
			if ($lang == []) {

				// ----- fall back to default locale (may be different than url)
				$lang = $model->GetChildrenReturn(['pagelang_lang' => DEFAULTLOCALE]);

				// ----- if true, then assumed no usable child records to derive a title and url slug
				if ($lang == []) {
					$elem[$count]['page_slug'] = "***************";
					$count ++;
					continue;
				}
			}

			// ----- set values
			$lang = $lang[0];
			$elem[$count]['page_slug'] = $lang['pagelang_slug'];

			// ----- increment counter
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

	// ----- add new site page
    public function new()
    {
        // ----- check for access allowd
        if (!SITEADMIN)
            $this->redirect('/');

        $model = new \Ignition\Page\Forms\Form('TEMP' . RandomName(4));

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

                    // ----- set success message
                    $this->SetFlashMessage(lang('base.record_successfully_created'));

	                $this->redirect('page' . $GLOBALS['KEYCODE'] . '/index');
	                return;

                } else
                    $this->SetFlashMessage(lang('base.failure'));

            }
        }
        else {

            // ----- make blank record data variables
            $model->data = $model->AllowedBlank();
	        $model->data['active'] = '1';
            $model->data['id'] = $model->parentIDValue;
			$model->data['page_languages'] = "'" . DEFAULTLOCALE . "'";

			// ----- get language child records and store to data within model
			$languagesArticle = $model->GetChildrenComplete([DEFAULTLOCALE], '', TRUE);
			$model->data = array_merge($model->data, $languagesArticle);

        }

        return $this->RenderTheme('form', [
            'model' => $model,
            'formType' => 'new',
            'adminLayout' => true,
            'errors' => $model->validationErrors
        ]);

    }

	// ----- edit one record
    public function edit($id = -1)
    {
        // ----- check for access allowd
        if (!SITEADMIN || $id == -1)
            $this->redirect('/');

        $model = new \Ignition\Page\Forms\Form($id);

		// ----- get input fields from url object
        $model->data = $this->request->getPost();

        // ----- call data validation
    	if ($model->data)
        {
            // ----- data already set, validate and save
            if ($model->IValidateChild($model->data))
            {

                if ($model->IUpdateChild($id)) {

	                // ----- set success message
	                $this->SetFlashMessage(lang('base.record_successfully_updated'));

                    $this->redirect('page' . $GLOBALS['KEYCODE'] . '/index');
				}

            }

        } else {

    		$model->data = $model->find($id);

            if ($model->data == '') {
                $this->redirect('page' . $GLOBALS['KEYCODE'] . '/index');
                return;
            }

			// ----- determine if multiple languages are being utilzied for this article
			if ($model->data['page_languages'] == '')
				$langs = [DEFAULTLOCALE];
			else
				$langs = quotetoarray($model->data['page_languages']);

			// ----- get language child records and store to data within model
			$languagesArticle = $model->GetChildrenComplete($langs, '', TRUE);
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
    public function delete($id = -1)
    {
        // ----- check for access allowd
        if (!SITEADMIN || $id == -1)
            $this->redirect('/');

        // ----- init
        $model = new \Ignition\Page\Forms\Form($id);
        $model->data = $this->request->getPost();

        // ----- no validation
    	if ($model->data)
        {

            $model->delete($id);
			$model->DeleteChildRecords($id);

            // ----- create session update message
            $session = service('session');
            $session->SetFlashdata('success', lang('base.record_successfully_deleted'));

			$this->redirect('page' . $GLOBALS['KEYCODE'] . '/index');
            return;
        }
        else {
    		$model->data = $model->find($id);

            if ($model->data == '') {
                $this->redirect('page' . $GLOBALS['KEYCODE'] . '/index');
                return;
            }

			// ----- determine if multiple languages are being utilzied for this article
			$langs = ($model->data['page_languages'] == '' ? [DEFAULTLOCALE] : quotetoarray($model->data['page_languages']) );

			// ----- get language child records and store to data within model
			$model->data = array_merge($model->data, $model->GetChildrenComplete($langs, '', TRUE));
        }

        return $this->RenderTheme('form', [
            'model' => $model,
            'data' => $model->data,
            'adminLayout' => true,
            'errors' => $model->errors()
        ]);
    }

}
