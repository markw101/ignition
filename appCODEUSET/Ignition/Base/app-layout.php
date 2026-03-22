<?php
/*********************************************************************
    AUTHOR:      ME Williamson
    FILE:        app-layout.php
    VERSION:     See BaseController.php
    DESCRIPTION: build admin menu
    COPYRIGHT:   2021, 2022, 2023, 2024
    FIRST REV:   2023
    LICENSE:     MIT

*********************************************************************/

// ----- Use this array to build admin menu.  Rendering done automatically by renderAdminMenu
//       build menu array.  This is where to build the menus for all authenticated
//       users accessing internal site functions that require a login account
//       RenderAdminMenu() builds two menus: desktop and mobile version
//       See colorlib.all.css or ignition.css for icons.  There are many!
//
// ----- include menu (set in SiteConstants)
include_once APPPATH . "Site/". $GLOBALS["APPMENU"] . ".php";

?>

<!DOCTYPE html>
<html lang="<?= $this->IConfig->defaultLocale;?>">
<head>
<!-- Required meta tags-->
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<!-- Title Page-->
<title><?= $this->IConfig->title ?></title>
<!-- Favicon begin -->
<link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png">
<link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
<link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">
<link rel="manifest" href="/site.webmanifest">
<link rel="mask-icon" href="/safari-pinned-tab.svg" color="#5bbad5">
<meta name="msapplication-TileColor" content="#da532c">
<meta name="theme-color" content="#ffffff">

<?php
	include_once(APPPATH . 'Ignition/Library/admindark/dark_header.php');
?>

</head>
<body>
<!-- begin content-->
<div class="page-wrapper">

<?php
	// ----- check for no navigation
	if (!ValSet($this->layoutConfig, "NOMENU"))
	{

        // ----- build desktop and mobile menus and output 
        $this->RenderMenu($menus);

    } ?>

<!-- PAGE CONTENT-->
<div class="page-content--bgf7">

    <section class="p-t-20">
        <div class="container">
            <div class="row">
                <div class="col-md-12">

<!-- BREADCRUMB-->
<?php if ($breadCrumbs) { ?>
<section class="au-breadcrumb m-b-20">
    <div class="section__content">                  
        <div class="au-breadcrumb-content">
            <div class="au-breadcrumb-left">
               <ul class="list-unstyled list-inline au-breadcrumb__list">
<table>
<tbody>
<tr>
	<td style="width: 1%; white-space: nowrap">

<?php
// ----- setup loop to print breadcrumbs, highlight location tags
$arraySize = count($breadCrumbs);
$counter = 1;

foreach ($breadCrumbs as $crumb) {

    echo '<li class="list-inline-item active">/<a href="' . $crumb['url'] . '" style="font-size: 17px">';

    if ($counter == $arraySize || ($counter + 1 == $arraySize && ($this->moduleMethod == 'edit' || $this->moduleMethod == 'delete' || $this->moduleMethod == 'new')))
        echo '<u><font color="green" style="font-size: 30px">' . rtrim($crumb['name']) . '</font></u></a></li>';
    else
        echo '<u>' . rtrim($crumb['name']) . '</u></a></li>';

    $counter += 1;
} ?>
	</td>
	<td style="width: 1%; white-space: nowrap">&nbsp;&nbsp;&nbsp;&nbsp;</td>
<?php
// ----- if index controller, show new button and pager
if ($this->moduleMethod == 'index' && !ValSet($this->layoutConfig, "NONEW")) { ?>
    <td style="width: 1%; white-space: nowrap">
		<li class="list-inline-item active" style="padding-left: 30px">
		<a href="<?=BaseURL() . '/' . $this->moduleName?>/new" class="btn&#x20;btn-primary" style="color: white"><?= lang('base.new') ?></a>
		</li>
	</td>
	<td style="white-space: nowrap">
		<li class="list-inline-item active" style="padding-left: 60px">
		<?php 
    if ($params['pager']) echo $pager->links('default', 'iPager');

//if ($params['pager']) echo $pager->links('default', 'iPagerIndex'); ?>
		</li>
	</td>
<?php } ?>
</tr>
</tbody>
</table>
                </ul>               
             </div>
        </div>                   
    </div>
</section>
<!-- END BREADCRUMB-->

<?php
}
    // ----- check for responsive requested
    if(!ValSet($this->layoutConfig, "NORESPONSIVE") && ($this->moduleMethod == 'index'))
        echo '<div class="table-responsive">';
    else 
        echo '<div>';

    // ----- display rendered content created by channel or module  
    echo $params['content'];

    if ($params['pager']) echo '<center>' . $pager->links('default', 'iPager') . '</center>';

?>

                </div>
            </div>
        </div>
    </section>
    	<!-- COPYRIGHT-->
	<section class="p-t-60 p-b-20">
	    <div class="container">
	        <div class="row">
	            <div class="col-md-12">
	                <div class="copyright">
                        <p style="font-family: Roboto, Arial, sans-serif; font-size: 18px; color: black;">
                        	<?= lang('base.ignitionpower') . " v" . $this->IgnitionVersion ?>
                        	<br>
<?php
if (isset($this->DNAVersion))
    echo 'DNA ' . lang('base.version') . " v" . $this->DNAVersion . '<br>';
?>
							<?= lang('base.by') ?>
                        	<a target="_blank" href="https://williamsonsoftware.com">Williamson Software</a>
                        </p>
                    </div>
	            </div>
	        </div>
	    </div>
	</section>
	<!-- END COPYRIGHT-->


</div>
</div>
<!-- END PAGE CONTENT-->

<?php
// ----- color lib js (must have for admin menu functions, especially mobile version 
echo '<script src="' . BaseURL('/themes/admin/colorlib-main.js') . '"></script>';
?>

</body>
</html>
<!-- end document-->
