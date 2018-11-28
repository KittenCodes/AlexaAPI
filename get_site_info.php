<?php

ob_clean();
ob_start();

require "wp-config.php";

include("alexaapi.php");

// Show the post offset
echo " Post offset = " . $_GET['my_page_number'] * 100;

?><br /><?php

$offset = $_GET["my_page_number"] * 100;


$args = array (
	"post_type"			=> "post",
	"post_status"		=> "publish",
	"posts_per_page"	=> 100,
	"orderby" 			=> "ID",
	"order"   			=> "ASC",
	"offset" 			=> $offset,
	);

// Custom query
$query = new WP_Query( $args );
 
// Check that we have query results.
if ( $query->have_posts() ) {
 
    // Start looping over the query results.
    while ( $query->have_posts() ) {
 
        $query->the_post();
 
        // Get the post title
        $url = get_the_title();
        // Get the post ID
        $post_id = get_the_id();

        if (metadata_exists("post", $post_id, "_site_alexa_rank") == false) {
        
	        //Run URL Info function
	        $url_info = my_check_content ( $url );
	        //Update post meta
	        update_post_meta ($post_id, "_site_wordpress", $url_info);

	        // Run Alexa function
	        $alexa_api_info = my_get_awis_info ( $url );
	        
	        echo " Site " . $alexa_api_info . " ";

	        // Update post meta
	        $alexa_api_info = str_replace("Rank: ", "", $alexa_api_info);
	        update_post_meta ($post_id, "_site_alexa_rank", $alexa_api_info);        

	        echo $url . " processed... ";
	 
	        ?><br /><?php

	    }

    }
 
} else {
	echo "No posts found for selected range."
	?><br /><?php
}
 

$offset = (($offset / 100) + 1);
// Set redirect
wp_redirect("http://example.com/get_site_info.php?my_page_number=" . $offset);
exit();
	

function my_check_content ( $url ) {
	$ctx = stream_context_create ( array ( 'http'=>array ( 'timeout' => 15, ) ) );
	$content = file_get_contents ( "http://" . $url, false, $ctx );
	$content = strtolower ( $content );
	if ( strpos ( $content, "wordpress" ) == true ) {
		// Has WordPress
		return "TRUE";
	} elseif ( strpos( $content, "wordpress" ) == false ) {
		// Doesn't have WordPress
		return "FALSE";
	} else {
			return "";
	}
}


?>
