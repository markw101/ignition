<?php

/*********************************************************************
    AUTHOR:      Mark E. Williamson
    FILE:        blog.php
    VERSION:     See BaseController.php
    DESCRIPTION: 
    COPYRIGHT:   2022
    FIRST REV:   16 Mar 2022
    LICENSE:     MIT

*********************************************************************/

return [
    'author'                                       => 'Author',
    'authoradmin'                                  => 'Author',
    'comments'                                     => 'Comments',
	'allow_comments'							   => 'Allow Comments',
    'select'                                       => 'Select',
	'tag_cloud'								   	   => 'Tag Cloud',
    'popular_posts'                                => 'Popular Posts',
	'blog'                                         => 'Blog',
    'blog_back'                                    => 'Back to Blog Index',
    'blog_category'                                => 'Blog Category',
    'body_text'                                    => 'Body Text',
	'views'                                        => 'Views',
	'all'                                          => 'All',
    'slug'                                         => 'URL Slug',
	'tags'                                         => "Tags.  Each tag must be single quoted: 'example'.  Tags are case insensative.",
	'more_category'                                => "More articles from this category: ",
	'external_domain'							   => "Use external Ignition site base domain to retrieve Body Text for article (excluding protocol, etc).",
	'external_code'			                       => "External Code (must be set in external site as REMOTEPIN in SiteConstants.php)",
	'featured'									   => "Featured",
	'first_publication'							   => "First Published",
    'how_cat_ref'                                  => " (how this category is referenced in blog stories)"
];
?>