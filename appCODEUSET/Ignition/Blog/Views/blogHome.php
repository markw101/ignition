<?php

/*********************************************************************
    AUTHOR:      Mark E. Williamson
    FILE:        blogHome.php
    VERSION:     See BaseController.php
    DESCRIPTION: 
    COPYRIGHT:   2022
    FIRST REV:   16 Mar 2022
    LICENSE:     MIT

*********************************************************************/

// ----- create blog class object
$blog = new IBlog($model, $iconfig);

$blog->FeaturedItems();
?>

<section class="post-content-area">
	<div class="container">
		<div class="row">

            <div class="col-lg-8 posts-list">

            <?php
            $blog->FeaturedItems(); 
            $blog->BlogPager();
            ?>

			</div>

            <!-- right sidebar -->
			<div class="col-lg-4 sidebar-widgets">
				<div class="widget-wrap">

					<div class="single-sidebar-widget search-widget">
						<form class="search-form" action="#">
                            <input placeholder="Search Posts" name="search" type="text" onfocus="this.placeholder = ''" onblur="this.placeholder = 'Search Posts'" >
                            <button type="submit"><i class="fa fa-search"></i></button>
                        </form>
					</div>

                    <?php
                    $blog->ChiefEditor(); 
                    $blog->PopularPosts();
                    $blog->BlogCategories();
                    $blog->TagCloud();
                    ?>

				</div>
			</div>
            <!-- END right sidebar -->

		</div>
	</div>	
</section>


