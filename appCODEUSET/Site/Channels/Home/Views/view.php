<?php
/*********************************************************************
    AUTHOR:      Mark E. Williamson
    FILE:        view.php
    VERSION:     See BaseController.php
    DESCRIPTION: 
    COPYRIGHT:   2022
    FIRST REV:   16 Mar 2022
    LICENSE:     MIT

*********************************************************************/

// ----- create blog class object
$blog = new IBlog($model, $iconfig);

?>

<section class="post-content-area">
	<div class="container">
		<div class="row">

            <div class="col-lg-8 posts-list">

            <?php
            $blog->BlogIndex(); 
			echo '<center><a href="/post/category" class="btn&#x20;btn-primary">' . lang('base.all') . '</a></center><br>';
            ?>

			</div>

            <!-- right sidebar -->
			<div class="col-lg-4 sidebar-widgets">
				<div class="widget-wrap">

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

