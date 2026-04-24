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

namespace Ignition\Page\MC;

class Model extends \Ignition\Base\BaseModelRelate
{

	protected $table = 'pages';
    protected $subDirectory = 'page';
	protected $table2 = 'pagelangs';
	protected $checkFieldsBlankNoSave = ['pagelang_slug', 'pagelang_text', 'pagelang_tags', 'pagelang_narrow_text'];            // if this contains field names of child record, will only save a record if at least one of these fields contain values. Otherwise, record dropped in iValidate
	protected $keyField2 = 'pagelang_lang';

    // ----- Given page url/slug, seek matching record.  Dual mode of function
	//       MODE 1: search for page by child record first, whereby flag, $selectByParent = false
	//       MODE 2: search for page by parent record first, flag $selectByParent = false, 
	//       Mode 2 uses page_name as record identifier, like a master code, inclusive of any session language
	//       After locating matching child and parent records, automatically merges the child and 
	//       parent records fields into one set of variables in returned result[]
    public function getPage($pageURL, $selectByParent = FALSE) {

		// ----- check mode of operation
		if ($selectByParent) {

	        // ------ locate record
	        $query = $this->where('page_name', $pageURL);
	        $parentResult = $query->findAll();

			// ------ if no result, return empty aray 
			if ($parentResult == [])
				return [];

			// ----- locate language record in child based on configured lang code 
			$parentResult = $parentResult[0];
            $db1 = SetDB(DEFAULTDBGROUP);
			$langRecord = $db1->table('pagelangs');
			$childResult = $langRecord->getWhere(['pagelang_lang' => $GLOBALS['LANGCODE'], 'active' => 1, 'id_parent' => $parentResult['id']])->getResult('array');

			// ------ if no result, return empty aray 
			if ($childResult == [])
				return [];

		} else {

			// ----- retieve unique url within pagelanguage table
			$db1      = \Config\Database::connect();
			$langRecord = $db1->table('pagelangs');

	        // ------ locate record
		    $childResult = $langRecord->getWhere(['pagelang_slug' => $pageURL, 'active' => 1])->getResult('array');

			// ----- check for pagelang slug not found
			if ($childResult == []) {
				header('Location: ' . BaseURL("/system/error404/" . $pageURL));
				halt();
			}

			// ----- must also find parent record
			$db1      = \Config\Database::connect();
			$pageRecord = $db1->table('pages');
			$parentResult = $pageRecord->getWhere(['id' => $childResult[0]['id_parent']])->getResult('array');

			// ----- check for orphaned child record
			if ($parentResult == []) {
				// ----- error page
				header('Location: ' . BaseURL("/system/error404/" . lang('base.invalid')));
				halt();
			}

			$parentResult = $parentResult[0];

		}

		// ----- set all fields
		$childResult = $childResult[0];
		$result = $parentResult;
		$result['page_url'] = $childResult['pagelang_slug'];
		$result['page_text'] = $childResult['pagelang_text'];
		$result['page_narrow_text'] = $childResult['pagelang_narrow_text'];
		$result['active'] = $childResult['active'];
		$result['page_tags'] = $childResult['pagelang_tags'];
		$result['page_title'] = $childResult['pagelang_title'];

		return $result;

    }

}
