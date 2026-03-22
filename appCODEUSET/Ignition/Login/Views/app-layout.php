<?php
/*********************************************************************
    AUTHOR:      Mark E. Williamson
    FILE:        app-layout.php
    VERSION:     See BaseController.php
    DESCRIPTION: 
    COPYRIGHT:   2022
    FIRST REV:   16 Mar 2022
    LICENSE:     MIT

*********************************************************************/

// ----- login window main screen
include_once APPPATH . "Site/MainMenu.php";
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

<?php include_once(APPPATH . 'Ignition/Library/admindark/dark_header.php'); ?>

<style>

	div.ibreadcrumbs {
		height: 50px;
		background-color: #112138;
	    position: relative;
		padding: 5px;
		margin-bottom: 5px;
	    z-index: 0;
		font-family: Chandas;
		font-weight: 500;
		font-size: 20px;
	}

	.bgcolor {
		background-color: #131925;
	}

	table.breadcrumb {
		padding: 0px;
		width: 1%;
		white-space: nowrap;
		background-color: #112138;
		height: 30px;
		margin: 0px;
	}

</style>

</head>
<body class=bgcolor>
<!-- begin content-->
<div class="page-wrapper">

<?php

	// ----- if navigation, build desktop and mobile menus and output 
	if (!ValSet($this->layoutConfig, "NOMENU"))
        $this->RenderMenu($menus, TRUE);
?>

<!-- PAGE CONTENT-->
<div class="page-content--bgf7 bgcolor">
    <section class="p-t-20">
        <div class="container">
            <div class="row">
                <div class="col-md-12">

<!-- BREADCRUMB-->
<?php if ($breadCrumbs) { ?>
<div class=ibreadcrumbs>
<ul class="list-unstyled list-inline au-breadcrumb__list">
<table class=breadcrumb>
<tbody>
<tr>
	<td>

<?php
// ----- setup loop to print breadcrumbs, highlight location tags
$arraySize = count($breadCrumbs);
$counter = 1;

foreach ($breadCrumbs as $crumb) {

    echo '<li class="list-inline-item active">/<a href="' . $crumb['url'] . '" style="font-size: 17px">';

    if ($counter == $arraySize)
        echo '<u><font color="white" style="font-size: 22px">' . rtrim($crumb['name']) . '</font></u></a></li>';
    else
        echo rtrim($crumb['name']) . '</a></li>';

    $counter += 1;
}

echo '</td><td>&nbsp;&nbsp;&nbsp;&nbsp;</td>';

?>

</tr>
</tbody>
</table>
                </ul>               

</div>
<!-- END BREADCRUMB-->
<?php } ?>

<?php

    // ----- check for responsive requested
    if(!ValSet($this->layoutConfig, "NORESPONSIVE") && $this->moduleName != 'dna')
        echo '<div class="table-responsive">';
    else 
        echo '<div>';

    // ----- render dna content  
    echo $params['content'];

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
                        <p style="font-family: Roboto, Arial, sans-serif; font-size: 18px; color: whitesmoke;">
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
