<?php

/*********************************************************************
    AUTHOR:      ME Williamson
    FILE:        IBlog.php
    VERSION:     See BaseController.php
    DESCRIPTION: Blog oriented sites that require custom home page
    COPYRIGHT:   2022
    FIRST REV:   15 Aug 2022
    LICENSE:     MIT

*********************************************************************/

// ----- blog object consists of a home page index, all index, index by category, view blog article 
class IBlog
{
    protected $model;
    protected $IConfig;

    // ----- constructor
    public function __construct($model, $iconfig)
    {
        $this->model = $model;
        $this->IConfig = $iconfig;

    }

    // ----- create section dispaly categories with featured flag true
    public function FeaturedItems($resultsObj = '')
    {
        echo '<section class="top-category-widget-area"><div class="container"><div class="row" style="margin-top: 20px; margin-bottom: 40px">';

        // ----- retrieve all category featured records (should be 3)
		if (!is_array($resultsObj)) {
            $db = SetDB(DEFAULTDBGROUP);
	        $builder = $db->table('category');
	        $resultsObj = $builder->getWhere(['category_featured' => 1, 'category_lang' => $GLOBALS['LANGCODE']])->getResult('array');
			$isCat = true;

		} else
			$isCat = false;

        // ----- setup loop to dispaly the featured blog categories
        foreach($resultsObj as $category) {

            // ----- match id and find images in cat directory
			if ($isCat) {
	            if ($fileData = $this->model->GetFilesByPrefix('cat', $category['id']))
	                $category['imageFile'] = BaseURL('/assets/cat/' . $fileData[0]['fileName']);
	            else 
	                $category['imageFile'] = 'NotFound';

                // ----- if user included link in category table
                if ($category['category_url'] == '') 
    				$category['urlLink'] = BaseURL('/post/category/' . $category['category_code']);
                else
    				$category['urlLink'] = $category['category_url'];
			} else {
				$category['imageFile'] = BaseURL('/assets/page/' . $category['imageFile']);
				$category['urlLink'] = BaseURL('/' . $category['urlLink']);
			}

            echo '<div class="col-lg-4"><div class="single-cat-widget"><div class="content relative"><div class="overlay overlay-bg"></div>';
    	    echo '<a href="' . $category['urlLink'] . '"><div class="thumb">';
            echo '<img class="content-image img-fluid d-block mx-auto" src="' . $category['imageFile'] . '" alt="">';
            echo '</div><div class="content-details">';
            echo '<h4 class="content-title mx-auto text-uppercase">' . $category['category_name'] . '</h4><span></span>';
            echo '<p>' . $category['category_description'] . '</p>';
            echo '</div></a></div></div></div>';
        }

        echo '</div></div></section>';

    }

    // ----- display a listing of blog articles with title, description (exerpt) and main image
	//       modes of operation: all, featured, category, tag
	//       4 modes are divided by root sorting either by parent or child tables 
	//       Parent table = blog, child table = bloglangs
    public function BlogIndex($criteria = '', $mode = 2)
    {

		// ----- check for parent/child mode.  modes 3, 4 are tabulated
		//       using child records first, ergo bloglangs, and parent second, blog
		if ($mode == 1 || $mode == 2) {
	        // ----- retrieve articles based on parent table
	        $db      = \Config\Database::connect();
	        $builder = $db->table('blog');
			$parentMode = true;
		} else {
			// ------ if specific category, then must uses child table as this is langauge specific
			// ------ retrieve articles based on child table, bloglangs
	        $db      = \Config\Database::connect();
	        $builder = $db->table('bloglangs');
			$parentMode = false;
		}

		// ----- modes of operation: all, featured, category, tag
		if ($mode == 1) {
			$resultsObj = $builder->getWhere(['active' => 1])->getResult('array');
		} elseif ($mode == 2) {
	        $resultsObj = $builder->getWhere(['blog_featured' => 1, 'active' => 1])->getResult('array');
		} elseif ($mode == 3) {
			if ($criteria == 'all')
				$resultsObj = $builder->getWhere(['bloglang_lang' => $GLOBALS['LANGCODE'], 'active' => 1])->getResult('array');
			else
				$resultsObj = $builder->getWhere(['bloglang_category' => $criteria, 'bloglang_lang' => $GLOBALS['LANGCODE'], 'active' => 1])->getResult('array');
		} elseif ($mode == 4) {
			$resultsObj = $builder->getWhere(['bloglang_lang' => $GLOBALS['LANGCODE'], 'active' => 1])->getResult('array');
		}

		$recordCounter = 0;

        // ----- setup loop to dispaly articles based on all/featured (parentMode) or category
        foreach($resultsObj as $posting) {

			// ----- runs in parent or child record mode depending on above
			if ($parentMode) {
				// ----- retrieve child language record (guesses using language of visitor)
				$langRecord = $this->GetChildRecordLocale($posting['id']);

				// ----- if not found, skip 
				if ($langRecord == [])
					continue;

			} else {
				// ----- store langrecord (flip/flop the records)
				$langRecord = $posting;

				// ----- get parent record
				$posting = $this->model->find($langRecord['id_parent']);
			}

			// ----- check for tagcloud, mode 4
			if ($mode == 4) {
				// ----- scan bloglan_tags field for the tag
				$returnTags = quotetoarray($langRecord['bloglang_tags']);
				$foundTag = FALSE;

				foreach ($returnTags as $tag) {
					if (lcfirst($tag) == lcfirst($criteria)) {
						$foundTag = TRUE;
						break;
					}
				}

				// ----- if tag not found in tags field, skip record
				if (!$foundTag)
					continue;

			}

            $viewsModel = new \Ignition\Base\BaseModel('bloglangs', ['bloglang_views'], IGDBPREFIX . 'taz');
            $viewRecord = $viewsModel->find($langRecord['id']);
			$posting['blog_views'] = $viewRecord['bloglang_views'];

			// ----- set blog variables using specific language record
			$posting['blog_slug'] = $langRecord['bloglang_slug'];
			$posting['blog_title'] = $langRecord['bloglang_title'];
			$posting['blog_description'] = $langRecord['bloglang_description'];
			$posting['blog_authorid'] = 'rex'; // $langRecord['bloglang_authorid'];
			$posting['blog_category'] = $langRecord['bloglang_category'];
			$posting['blog_tags'] = $langRecord['bloglang_tags'];
			$posting['blog_author'] = $langRecord['bloglang_author'];
			$posting['blog_refreshdate'] = $langRecord['bloglang_refreshdate'];

			// ----- store posting to results table 
			$ordered[$recordCounter] = $posting;
			$recordCounter ++;

		}

		// ----- check for zero blog articles in system 
		if (!isset($ordered))
			return;

		// ----- sort array by refresh date
		foreach ($ordered as $key => $row) {
		    $refreshdate[$key] = $row['blog_refreshdate'];
		}

		// ----- magic php, reorder the ordered array according to the refreshdate order (child rec)
		array_multisort($refreshdate, SORT_DESC, $ordered);

		// ----- setup loop to display the records
		foreach ($ordered as $posting)
		{

    	    echo '<div class="single-post row">';
    		echo '<div class="col-lg-3  col-md-3 meta-details">';
            $dateTime = new DateTime($posting['blog_refreshdate']);
            $month = $dateTime->format('m') . '/' . $dateTime->format('Y');

            // ----- setup loop to show all tags for article
            //

            // ----- init
            $marker1 = '-'; $marker2 = '-';
            $allTags = ($posting['blog_tags'] ?? "");
            $dataLen = strlen($allTags);

            echo '<ul class="tags">';

            for($count = 0; $count < $dataLen; $count ++)
            {
                // ----- check for found next delimiter 
                if (substr($allTags, $count, 1) == "'") {
                    if ($marker1 === '-') {
                        $marker1 = $count + 1;
                    } else 
                        $marker2 = $count - 1;
                }

                // ----- output the tag
                if ($marker1 !== '-' && $marker2 !== '-') {
					$tag = substr($allTags, $marker1, $marker2 - $marker1 + 1);
                    echo '<li><u><a href="/post/tagcloud/' . $tag . '">' . $tag;
                    echo ($count + 1 == $dataLen ? '' : ',') . '</a></u></li>';
                    $marker1 = '-'; $marker2 = '-';
                }

            }
            echo '</ul>';

            // ----- match id and find images in cat directory
            if ($fileData = $this->model->GetFilesByPrefix('blog', $posting['id'])) {

                $imageFile = 'NotFound' . $posting['id'];

                // ----- setup loop to find blog main graphic, -main.
                foreach ($fileData as $file) {
                    if (stripos($file['fileName'], '-main.') !== false) {
                        $imageFile = $file['fileName'];
                        break;
                    }

                }
            } else 
                $imageFile = 'NotFound' . $posting['id'];
            ?><br>
    						<div class="user-details row">
    							<div class="user-name col-lg-12 col-md-12 col-6" style="margin-bottom: 6px"><u><a href="/author/profile/<?= $posting['blog_authorid']?>"><?= $posting['blog_author'] ?></a></u> <span class="lnr lnr-user"></span></div>
    							<div class="date col-lg-12 col-md-12 col-6" style="margin-bottom: 6px"><a href="#"><?= $month ?></a><span class="lnr lnr-calendar-full"></span></div>
    							<div class="view col-lg-12 col-md-12 col-6" style="margin-bottom: 6px"><a href="#"><?= $posting['blog_views'] . ' ' . lang('blog.views')?></a><span class="lnr lnr-eye"></span></div>
								<?php if ($this->IConfig->blogComments) {
    							echo '<div class="comments col-lg-12 col-md-12 col-6" style="margin-bottom: 6px"><u><a href="#">' . $posting['blog_comments'] . ' ' . lang('blog.comments') . '</a></u> <span class="lnr lnr-bubble"></span></div>';
								} ?>
    						</u></div>
    					</div>
    					<div class="col-lg-9 col-md-9 ">
    						<a class="posts-title" href="<?= BaseURL('/post/' . $posting['blog_slug']) ?>"><h3><?= $posting['blog_title'] ?></h3></a>
    						<div class="feature-img">
    							<img class="img-fluid" src="<?= BaseURL('/assets/blog/' . $imageFile)?>" alt="">
    						</div><br>
    						<p class="excert">
                            <?= $posting['blog_description'] ?>
    						<br><br></p>
    						<a href="<?= BaseURL('/post/' . $posting['blog_slug']) ?>" class="primary-btn" style="background-color: #ff0000ff"><?= lang('base.view') ?></a>
							<hr>
							<br>
    					</div>


    				</div>
            <?php }

    }

    // ----- display blog pager
    public function BlogPager()
    {
        ?>
            <nav class="blog-pagination justify-content-center d-flex">
                <ul class="pagination">
                    <li class="page-item">
                        <a href="#" class="page-link" aria-label="Previous">
                            <span aria-hidden="true">
                                <span class="lnr lnr-chevron-left"></span>
                            </span>
                        </a>
                    </li>
                    <li class="page-item"><a href="#" class="page-link">01</a></li>
                    <li class="page-item active"><a href="#" class="page-link">02</a></li>
                    <li class="page-item"><a href="#" class="page-link">03</a></li>
                    <li class="page-item"><a href="#" class="page-link">04</a></li>
                    <li class="page-item"><a href="#" class="page-link">09</a></li>
                    <li class="page-item">
                        <a href="#" class="page-link" aria-label="Next">
                            <span aria-hidden="true">
                                <span class="lnr lnr-chevron-right"></span>
                            </span>
                        </a>
                    </li>
                </ul>
            </nav>
        <?php

    }

    // ----- display central author block
    public function ChiefEditor()
    {
        ?>
		<div class="single-sidebar-widget user-info-widget">
			<img src="<?= $this->IConfig->authorImg ?>" alt=""><br>
			<a href="#"><h4><?= $this->IConfig->author ?></h4></a>
			<p><?= $this->IConfig->authorTitle ?></p>
<?php /*
			<ul class="social-links">
				<li><a href="#"><i class="fa fa-twitter"></i></a></li>
				<li><a href="#"><i class="fa fa-github"></i></a></li>
				<li><a href="#"><i class="fa fa-behance"></i></a></li>
			</ul>
*/ ?>
			<p><?= $this->IConfig->personalTagPhrase ?></p>
		</div>
        <?php

    }

    // ----- display 4 most popular posts block.  based on tabulated bloglang_views
    public function PopularPosts()
    {
        echo '<div class="single-sidebar-widget post-category-widget"><h4 class="category-title">' . lang('blog.popular_posts') . '</h4><div class="popular-post-list">';

        // ----- retrieve top four most read blog articles
        $db      = \Config\Database::connect();
        $builder = $db->table('bloglangs');
		$resultsObj = $builder->orderBy('bloglang_views','DESC')->getWhere(['bloglang_lang' => $GLOBALS['LANGCODE'], 'active' => 1], 4)->getResult('array');

        // ----- setup loop to dispaly snippits for top 4 articles
        foreach($resultsObj as $posting) {

			// ----- store langrecord (flip/flop the records)
			$langRecord = $posting;

			// ----- get parent record
			$posting = $this->model->find($langRecord['id_parent']);

            $viewsModel = new \Ignition\Base\BaseModel('bloglangs', ['bloglang_views'], IGDBPREFIX . 'taz');
            $viewRecord = $viewsModel->find($langRecord['id']);
			$posting['blog_views'] = $viewRecord['bloglang_views'];

			// ----- set blog variables using specific language record
			$posting['blog_slug'] = $langRecord['bloglang_slug'];
			$posting['blog_title'] = $langRecord['bloglang_title'];
			$posting['blog_refreshdate'] = $langRecord['bloglang_refreshdate'];

            $dateTime = new DateTime($posting['blog_refreshdate']);
            $month = $dateTime->format('m') . '/' . $dateTime->format('Y');

            // ----- match id and find images in cat directory
            if ($fileData = $this->model->GetFilesByPrefix('blog', $posting['id'])) {

                $imageFile = 'NotFound';

                // ----- setup loop to find blog main graphic, -main.
                foreach ($fileData as $file) {
                    if (stripos($file['fileName'], '-main.') !== false) {
                        $imageFile = $file['fileName'];
                        break;
                    }

                }
            } else 
                $imageFile = 'NotFound';

            echo '<div class="single-post-list d-flex flex-row align-items-center">';
            echo '<div class="thumb"><img class="img-fluid" src="' . BaseURL('/assets/blog/' . $imageFile) . '" alt=""></div>';
            echo '<div class="details"><a href="' . BaseURL('/post/' . $posting['blog_slug']) . '"><h6>' . $posting['blog_title'] . '</h6></a>';
            echo '<p>' . $posting['blog_views'] . ' ' . lang('blog.views') . '</p></div></div>';
        }
		echo '<center><a href="/post/category" class="btn&#x20;btn-primary">' . lang('base.all') . '</a></center><br>';
        echo '</div></div>';

    }

    // ----- display all blog categories
    public function BlogCategories()
    {

		// ------ init
		$articleCounter = 0;
		$articleTally = 0;

		// ------ open categories box
        echo '<div class="single-sidebar-widget post-category-widget"><h4 class="category-title">' . lang('blog.blog_category') . '</h4><ul class="cat-list">';

        // ----- select all category fields from all blog posts
        $db      = \Config\Database::connect();
        $builder = $db->table('bloglangs')->select('bloglang_category');
		$blogPosts = $builder->orderBy('bloglang_category','DESC')->getWhere(['bloglang_lang' => $GLOBALS['LANGCODE'], 'active' => 1])->getResult('array');

		// ----- initialize category
		$arraySize = count($blogPosts);

        // ----- setup loop to tally entries for a category and display the category with total
		do {

			// ------ check for none
			if ($blogPosts == [])
				break;

			$currentCategory = $blogPosts[$articleCounter]['bloglang_category'];

			// ----- loop until one past all articles within curret category
			do {

				$articleCounter ++;

				// ----- check for reached the end of the array 
				if ($articleCounter >= $arraySize)
					break;

				// ----- add to category count
				if ($currentCategory == $blogPosts[$articleCounter]['bloglang_category'])
					$articleTally ++;
				else 
					break;

			} while (TRUE);

            echo '<li><a href="/post/category/' . $currentCategory . '">' . ucwords($currentCategory) . ' (' . $articleTally + 1 . ')</a></li>';

			$articleTally = 0;

        } while ($articleCounter < $arraySize);

        echo '</ul></div>';

    } 

    // ----- display all tag clouds
    public function TagCloud()
    {

		// ----- guess locale appropriate bloglangs record based on visitor language
		$db1      = \Config\Database::connect();
		$langRecord = $db1->table('bloglangs')->select('bloglang_tags');
		$filter = ['bloglang_lang' => $GLOBALS['LANGCODE'], 'active' => 1];
		$langRecord = $langRecord->getWhere($filter)->getResult('array');

		// ----- build an array with tag as key with tally for each
        $tagsArray = TotalTags($langRecord);
		echo '<div class="single-sidebar-widget post-category-widget"><h4 class="category-title">' . lang('blog.tag_cloud') . '</h4><ul>';

        foreach ($tagsArray as $tag => $tally) {
            echo '<li><a href="/post/tagcloud/' . $tag . '">' . ucwords($tag) . ' (' . $tally . ')</a></li>';
        }

        echo '</ul></div>';

    }

	// ----- get blog recrod best match for child record based on locale variables
	public function GetChildRecordLocale($pid = "ALL")
	{

		// ----- guess locale appropriate bloglangs record based on visitor language
		$db1      = \Config\Database::connect();
		$langRecord = $db1->table('bloglangs');
		$filter = [ 'id_parent' => $pid, 'bloglang_lang' => $GLOBALS['LANGCODE']];
		$langRecord = $langRecord->getWhere($filter)->getResult('array');

		// ----- check for record found, based on visitor language
		//       if not, utilize default locale.  otherwise, return empty
		if ($langRecord == []) {
			$filter = [ 'id_parent' => $pid, 'bloglang_lang' => DEFAULTLOCALE];
			$langRecord = $db1->table('bloglangs');
			$langRecord = $langRecord->getWhere($filter)->getResult('array');

		}

		// ----- if not found
		if ($langRecord == [])
			return $langRecord;
		else
			return $langRecord[0];

	}

}

?>
