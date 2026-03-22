<?php

/*********************************************************************
    AUTHOR:      Mark E. Williamson
    FILE:        category.php
    VERSION:     See BaseController.php
    DESCRIPTION: 
    COPYRIGHT:   2022
    FIRST REV:   16 Mar 2022
    LICENSE:     MIT

*********************************************************************/

$pager = $model->pager;
$blog = new IBlog($model, $iconfig);

// ----- create page header
echo '<br><div class="container"><div class="row"> <div class="col-lg-8 col-md-10 mx-auto">';

// ----- create lang select
$SelectModel = new \Ignition\Base\BaseModel("NONE", ['SelectCategory']);
$SelectModel->data = ['SelectCategory' => $SelCat];
$form = new IForm($SelectModel);

?>

<table width="100%">
<tr>
<td colspan="2">
	<?= $pageBlog['page_text'] ?>
</td>	
</tr>
<tr><td>
	<h2 class="text-black">
		<?= lang('blog.blog_category') . ': ' . ucfirst($SelCat)?>
	</h2>	

</td>
<td align="right">
	<h2 class="text-black">
		<?= ucfirst(lang('blog.select')) . ' ' . ucfirst(lang('base.category')) ?>
	</h2>
</td>
</tr>
<tr>
<td>
	<p class="text-black link-nav"><h3>
		<a href="/"><?= lang('base.home') ?></a> /
		<a href="/post/category/all">Blog</a> /
        <a href="/post/category/<?= $SelCat . '">' . ucfirst($SelCat) ?></a>
	</h3></p>
</td>
<td align="right">
<?php

	$categories = [lang("blog.all") => lang("blog.all")];

    // ----- build list of categories
    $db      = \Config\Database::connect();
    $builder = $db->table('category');
	$resultsObj = $builder->getWhere(['category_lang' => $GLOBALS['LANGCODE']])->getResult('array');

    // ----- setup loop to store categories for current language
    foreach($resultsObj as $catRecord) {
		// ----- store category name using code as key
		$categories[$catRecord['category_code']] = $catRecord['category_name'];
	}

	$form->IFormOpen();
	$form->ISelect('SelectCategory', $categories, "", ['style' => 'margin:5px;margin-top:11px; background: blue; color: white; border-radius: 5px;'], "SelectCategory");
	$form->IFormSubmit(lang('base.switch'));
	$form->IFormClose();

?>
</td>
</tr>
</table>
<br><br>
<?php

// ----- convert all to visitor language
$SelCat = ($SelCat == lang('blog.all') ? 'all' : $SelCat);	
$blog->BlogIndex($SelCat, 3);

if ($pager) echo '<center>' . $pager->links('default', 'iPager') . '</center>';

// ----- close page
echo '<br><br><br></div></div></div>';
