<?php

/*********************************************************************
    AUTHOR:      Mark E. Williamson
    FILE:        MainLayout.php
    VERSION:     See BaseController.php
    DESCRIPTION: Displays a form using Ignition class
    COPYRIGHT:   2022
    FIRST REV:   16 Mar 2022
    LICENSE:     MIT

*********************************************************************/

// ----- create layout class object
$layout = new ILayout($this->IConfig, $params, $this->layoutConfig);
$layout->HeadStart($this->IConfig->title . $this->browserTitle);
$layout->BodyStart("background-color: #f9f9ff");

// ----- include menu (set in SiteConstants)
include_once APPPATH . "Site/MainMenu.php";

// ----- build menu buen vista
$this->RenderCustomMenu([$menus, '', '#000000', TRUE], "Site/menu-fragment.php");

?>

<header class="intro-header" style="background-image: url('<?= $this->IConfig->mastheadImage?>') ; background-color: #101010">
<div class="container">
    <div class="row">
        <div class="col-lg-8 col-lg-offset-2 col-md-10 col-md-offset-1">
            <div class="site-heading">
                <br><br><br>
            </div>
        </div>
    </div>
</div>
</header>

<?php

$layout->content();

?>

<!-- start footer Area -->
<footer class="footer-area section-gap">
    <div class="container">
        <div class="row">
            <div class="mx-auto">
				<p class="copyright text-muted">
                    <?= lang('base.ignitionpower') . " v" . $this->IgnitionVersion ?>
                    <br>Williamson Software © <?= date("Y") ?>
                    <br>On the Internet for 30 years
                </p>
            </div>
		</div>
    </div>
</footer>
<!-- End footer Area -->		

<?php

$layout->EndContent(); 

?>

</body>
</html>
