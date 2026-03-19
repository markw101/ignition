<?php

/*********************************************************************
    AUTHOR:      Mark E. Williamson
    FILE:        vista_menu.php
    VERSION:     See BaseController.php
    DESCRIPTION: 
    COPYRIGHT:   2022
    FIRST REV:   16 Mar 2022
    LICENSE:     MIT

*********************************************************************/

// ----- set parameter names based on array
$menus = $renderParameters[0];
$logoPic = $renderParameters[1] ?? "";
$fontColor = $renderParameters[2] ?? "";
$languagePicker = $renderParameters[3] ?? FALSE;
$userProfile = $renderParameters[4] ?? FALSE;

// ----- initialize the menu using blog vista 
?>
<nav class="navbar navbar-default navbar-custom navbar-fixed-top">
<div class="container-fluid">

    <div class="navbar-header page-scroll" style="margin-top: 0px; padding: 0px">
        <a class="navbar-brand" href="/" style="padding: 0px">
			<picture><source media="(min-width: 850px)" srcset="<?=$logoPic?>"><source media="(min-width: 650px)" srcset="<?=$logoPic?>"><img src="<?=$logoPic?>" style="margin-top: 0px;"></picture>
        </a>
        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
        <span class="sr-only">Toggle navigation</span>
        Menu <i class="fa fa-bars"></i>
        </button>
    </div>

    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
        <ul class="nav navbar-nav navbar-left" style="width: 90%;">
<?php

if ($languagePicker) {
	echo '<li style="margin-left: 10px"><div class="dropdown">';
	echo '<button class="btn btn-default dropdown-toggle" type="button" data-toggle="dropdown" style="padding: 10px; margin-top: 10px; background: ' . $fontColor . '"><font style="font-size: 10px; color: ' . MENUFONT . '">' . lang('base.language') . '</font><span class="caret"></span></button>';
	echo '<ul class="dropdown-menu" style="font-size: 10px; background: ' . $fontColor . '">';
	// ----- set up loop to make li for every lang
	foreach ($this->IConfig->languages as $key => $label) {
		echo '<li><a href="' . BaseURL("/", $key) . '" style="font-size: 10px; color: ' . MENUFONT . '; text-shadow: 1px 1px ' . THEMECOLOR . '">' . $label . '</a></li>';
	}
	echo '</ul></div></li>';
}

// ----- setup loop to render menus as part of main menu group, second of three groups
foreach ($menus as $menuItems) {

    // ----- check security constraint
    if ($menuItems['users'] == 'ALL' || ValSet($menuItems['users'], '|' . UT . '|'))
    {
		echo '<li style="margin-left: 10px; margin-right: 0px">';
		echo '<a href="' . (isset($menuItems['url']) ? $menuItems['url'] : '') . '" style="padding-left: 5px; padding-right: 0px; margin-left: 0px; margin-right: 0px"><font color=' . $fontColor . ' style="text-shadow: 1px 1px ' . SHADOWCOLOR . ';">';
        echo '<i class="fa ' . (isset($menuItems['icon']) ? $menuItems['icon'] : '') . '"></i>&nbsp;' . (isset($menuItems['name']) ? $menuItems['name'] : '') . '&nbsp;</font></a></li>';
	}
}

// ----- render account, third of 3 groups
if ($userProfile && UT > 0 && $GLOBALS['KEYCODE'] != "__INVALID__") {
    echo '<li style="margin-left: 10px; margin-right: 0px"><a href="' . BaseURL('/userprofile' . $GLOBALS['KEYCODE']) . '" style="margin-left: 0px; margin-right: 0px"><font color=' . $fontColor . ' style="text-shadow: 1px 1px ' . SHADOWCOLOR . ';">';
    echo '<i class="fa fa-star"></i>&nbsp;&nbsp;' . lang('base.my_profile') . '</font></a></li>';
}

echo '</ul></div></div></nav>';
