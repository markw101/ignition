<?php
/*********************************************************************
    AUTHOR:      Mark E. Williamson
    FILE:        dark_header.php
    VERSION:     See BaseController.php
    DESCRIPTION: 
    COPYRIGHT:   2022
    FIRST REV:   16 Mar 2022
    LICENSE:     MIT

*********************************************************************/
// ----- admin website common css and js elements (MUST REMAIN IN THIS ORDER)
echo '<script src="/themes/admin/dependencies.js?v=1.5.9"></script>';    // MUST REMAIN ON TOP necessary for file uploads
echo '<link href="/themes/admin/fontawesome.all.min.css" rel="stylesheet" type="text/css">'; 
echo '<link href="/themes/clean-blog.min.css" rel="stylesheet">';
echo '<link href="/themes/admin/bootstrap.min.css" rel="stylesheet" media="all">';
echo '<script src="/themes/admin/jquery-3.2.1.min.js"></script>';
echo '<link href="/themes/ignition.css' . '" rel="stylesheet">';
echo '<link href="/themes/admin/font-awesome.min.css" rel="stylesheet" media="all">';
echo '<link href="/themes/admin/hamburgers.min.css" rel="stylesheet" media="all">';
echo '<link href="/themes/admin/colorlib-theme.css" rel="stylesheet" media="all">';
//echo '<link href="/themes/full/colorlib.all.css" rel="stylesheet" media="all">';
echo '<script src="/themes/admin/animsition.min.js"></script>';


?>