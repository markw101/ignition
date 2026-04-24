<?php

/*********************************************************************
    AUTHOR:      Mark E. Williamson
    FILE:        tagcloud.php
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

?>

<table width="100%">
<tr>
<td colspan="2">
	<?= $pageBlog['page_text'] ?>
</td>	
</tr>
<tr><td>
	<h2 class="text-black">
		<?= lang('blog.tag_cloud') . ': ' . ucfirst($tag)?>
	</h2>	

</td>
<td align="right">

</td>
</tr>
<tr>
<td>
	<p class="text-black link-nav"><h3>
		<a href="/"><?= lang('base.home') ?></a> /
		<a href="/post/tagcloud/<?=$tag?>"><?= ucwords($tag) ?></a>
	</h3></p>
</td>
<td align="right">

</td>
</tr>
</table>
<br><br>
<?php

// ----- list all matching
$blog->BlogIndex($tag, 4);

if ($pager) echo '<center>' . $pager->links('default', 'iPager') . '</center>';

// ----- close page
echo '<br><br><br></div></div></div>';
