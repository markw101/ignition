<div class="container"><div class="row"><div class="mx-auto" style="margin-top:0px; margin-left: 25px; margin-right: 25px; margin-bottom:40px">
<?php
/*********************************************************************
    AUTHOR:      Mark E. Williamson
    FILE:        profile.php
    VERSION:     See BaseController.php
    DESCRIPTION: 
    COPYRIGHT:   2022
    FIRST REV:   16 Mar 2022
    LICENSE:     MIT

*********************************************************************/

// ----- match id and find images in cat directory
if ($fileData = $model->GetFilesByPrefix('author', $model->data['id'])) {

    $imageFile = '/assets/author/' . $fileData[0]['fileName'];

} else
	$imageFile = '/images/profile-icon.png';

// ----- diaplay author image
echo '<div class="feature-img">';
echo '<img class="img-fluid" src="' . BaseURL($imageFile) . '" alt="">';
echo '</div><br>';

?>
<h1><?php echo lang('blog.author') . ': ' . $model->data['author_name']?></h1>
<br>
<h2><?php echo lang('blog.first_publication') . ': ' . $model->data['author_pub_date']?></h2>
<br>
<?= $model->data['author_about'] ?>
</div></div></div>


