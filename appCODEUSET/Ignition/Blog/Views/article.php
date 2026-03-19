<?php

/*********************************************************************
    AUTHOR:      Mark E. Williamson
    FILE:        article.php
    VERSION:     See BaseController.php
    DESCRIPTION: 
    COPYRIGHT:   2022
    FIRST REV:   16 Mar 2022
    LICENSE:     MIT

*********************************************************************/

// ----- ouput story
echo '<div class="container"><div class="row"> <div class="mx-auto" style="margin:25px">';

// ----- match id and find images in cat directory
if ($fileData = $model->GetFilesByPrefix('blog', $data['id'])) {

    $imageFile = 'NotFound' . $data['id'];

    // ----- setup loop to find blog main graphic, -main.
    foreach ($fileData as $file) {
        if (stripos($file['fileName'], '-main.') !== false) {
            $imageFile = '/assets/blog/' . $file['fileName'];
            break;
        }
	}

	// ----- check for blog delivery type, switch article image file for masthead
	if ($iconfig->frontendTheme == 'blogvista')
		$iconfig->mastheadImage = $imageFile;
	else {
		echo '<div class="feature-img">';
		echo '<center><img class="img-fluid" src="' . BaseURL($imageFile) . '" alt=""></center>';
		echo '</div><br>';
	}

}

echo '<center><h2>';

//foreach (quotetoarray($data['blog_languages']) as $key => $value) {

// ----- open blog languages and locate get the slug for this language
$db = SetDB(DEFAULTDBGROUP);
$builder = $db->table('bloglangs');
$resultsObj = $builder->where(['id_parent' => $data['id']])->get()->getResult('array');

foreach ($resultsObj as $blogLang) {
	echo '<a href="/post/' . $blogLang['bloglang_slug'] . '"' . ' class="btn&#x20;btn-primary">';
	echo '<font style="color: white; text-shadow: -1px -1px 0 #000, 1px -1px 0 #000, -1px 1px 0 #000, 1px 1px 0 #000;">' . lang('languages.' . $blogLang['bloglang_lang'])  . ' </font>';
	echo '</a>&nbsp;&nbsp;&nbsp;';
}

echo '</h2></center>';
echo '<br><h2><b><a href="' . site_url('/post/' . $data['blog_slug']) . '"><font style="color: ' . ACCENTCOLOR . '">';
echo '<center>' . $data['blog_title'] . '</center>';
echo '</h2><br><h3></font><font style="color: #3d3a37">';
echo '<center>' . $data['blog_description'] . '</center>';
echo '</b></a><br><br>';
echo lang('blog.author') . ': ' . $data['blog_author'] . _br();

// ----- get cat name
$db      = \Config\Database::connect();
$builder = $db->table('category');
$categoryName = $builder->getWhere(['category_code' => $data['blog_category']])->getResult('array');
if (isset($categoryName[0]['category_name'])) {
	echo lang('blog.blog_category') . ': ' . $categoryName[0]['category_name'];
}

$dateTime = new DateTime($data['blog_refresh_date']);
$month = $dateTime->format('d') . ' ' . lang('base.' . strtolower($dateTime->format('F'))) . ' ' . ' ' . $dateTime->format('Y');

echo '<br>' . 'Posted: ' . $month . '</h3></font><br><br><font style="font-size: 18px">';

if (SPARKS)
    echo '<br><center><h1>>>>>THIS IS THE SPARK BLOCK<<<<</h1></center><br>';

// ----- call markup function to include carriage returns
echo MarkUp($data['blog_text'], $data['blog_justify']);

echo '</font><hr>';
echo '<center><a href="' . BaseURL('/post/category') . '"><font style="font-size: 17px" color="' . ACCENTCOLOR . '"><u>' . lang('blog.blog_back') . '</u></font></a></center>';

// ----- give option for this cateogry
if (isset($categoryName[0]['category_name'])) {
	echo '<center><a href="' . BaseURL('/post/category/' . $categoryName[0]['category_name']) . '"><font style="font-size: 17px" color="' . ACCENTCOLOR . '"><u>' . lang('blog.more_category') . $categoryName[0]['category_name'] . '</u></font></a></center>';
}

// ----- close out page content
echo '<br><br></div></div></div>';

