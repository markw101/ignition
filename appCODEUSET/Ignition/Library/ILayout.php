<?php

/*********************************************************************
    AUTHOR:      ME Williamson
    FILE:        ILayout.php
    VERSION:     See BaseController.php
    DESCRIPTION: Class for page layout of website
    COPYRIGHT:   2022
    FIRST REV:   16 Mar 2022
    LICENSE:     MIT

*********************************************************************/

// ----- Used to build pages for Ignition website.
class ILayout
{
    protected $layoutConfig;
    protected $IConfig;
	protected $addDiv = FALSE;
	protected $backEnd;
    public $params;
    public $menu;
    public $header;
    public $responsive;

    // ----- constructor
    public function __construct($iconfig, $params = '', $layoutConfig = '', $backEnd = FALSE)
    {
		$this->backEnd = $backEnd;
        $this->params = $params;
        $this->layoutConfig  = $layoutConfig;
        $this->IConfig = $iconfig;
        $this->menu = !ValSet($this->layoutConfig, "NOMENU");
        $this->header = !ValSet($this->layoutConfig, "NOHEADER");
        $this->responsive = !ValSet($this->layoutConfig, "NORESPONSIVE");

    }

    // ----- create head tag
    public function HeadStart($title = '', $language = '')
    {
        $language = $this->IConfig->userLocale;

        echo '<!DOCTYPE html>';
        echo '<html lang="' . $language . '" class="no-js">';
        echo '<head>';
        echo '<meta charset="utf-8">';
        echo '<meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">';
        echo '<link rel="shortcut icon" href="' . $this->IConfig->favIcon . '">';
        echo '<meta name="author" content="Mark Williamson" >';
        echo '<meta name="description" content="' . $this->IConfig->siteDescription . '">';
        echo '<meta name="keywords" content="' . $this->IConfig->keyWords . '">';

		// ----- if multilang site, set up loop to create hreflang tags, one for each language
		if ($this->IConfig->multiLanguage) {
			foreach ($this->IConfig->languages as $key => $langCode) {
				$url = ($this->IConfig->SSL ? "https://" : "http://") . $key . "." . SERVERBASENAME . $this->IConfig->URLPages;
				echo '<link rel="alternate" hreflang="' . $key . '" href="' . $url . '" />';
			}

		}

        if ($title)
            echo '<title>' . $title . '</title>';

		// ----- output header css/js according to site type
		switch (($this->backEnd ? $this->IConfig->backendTheme : $this->IConfig->frontendTheme)) {
		    case 'default':
				include_once('default/default_header.php');
		        break;
		    case 'admindark':
				include_once('admindark/dark_header.php');
		        break;
		    case 'blogvista':
				include_once('blogvista/vista_header.php');
		        break;
		    case 'busyworker':
				include_once('busyworker/worker_header.php');
				break;
			default:
				echo "Please set backend/frontendTheme variables in SiteConfig.php";
				halt();
		}

    }

    // ----- create open tag
    public function BodyStart($style = '')
    {
        echo '</head>';

        if ($style)
            echo '<body style="' . $style . '">';
        else
            echo '<body>';

    }

    // ----- create open tag
    public function Content($addDiv = TRUE)
    {
		if ($addDiv) {
	        if ($this->responsive)
	            echo '<div class="table-responsive">';
	        else 
	            echo '<div>';

			$this->addDiv = TRUE;
		}

        echo $this->params['content'];

    }

    // ----- end main content div
    public function EndContent()
    {
		// ----- close the main div if opened
		if ($this->addDiv)
			echo '</div>';

		// ----- output header css/js according to site type
		switch (($this->backEnd ? $this->IConfig->backendTheme : $this->IConfig->frontendTheme)) {
		    case 'default':
				// ----- color lib js (must have for admin menu functions, especially mobile version 
				echo '<script src="' . BaseURL('/themes/admin/colorlib-main.js') . '"></script>';
		        break;
		    case 'admindark':
				echo '<script src="' . BaseURL('/themes/admin/colorlib-main.js') . '"></script>';
		        break;
		    case 'blogvista':
				echo '<script src="/blogvista/jquery.min.js"></script>';
				echo '<script src="/blogvista/bootstrap.min.js" ></script>';
				echo '<script src="/blogvista/clean-blog.min.js"></script>';
		        break;
			case 'busyworker':
				include_once 'busyworker/worker_footer.php';
		        break;
			default:
				echo "Please set backend/frontendTheme variables in SiteConfig.php";
				halt();
		}

    }

}

?>
