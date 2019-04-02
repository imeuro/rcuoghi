<?php

/**
 * Extend WordPress search to include custom fields
 *
 * http://adambalee.com
 */

/**
 * Join posts and postmeta tables
 *
 * http://codex.wordpress.org/Plugin_API/Filter_Reference/posts_join
 */
function cf_search_join( $join ) {
    global $wpdb;
    if ( (is_search() && is_admin()) || is_page_template('searchresults-catalogo.php') ) {
        $join .=' LEFT JOIN '.$wpdb->postmeta. ' p  ON '. $wpdb->posts . '.ID = p.post_id ';
    }
    return $join;
}
add_filter('posts_join', 'cf_search_join' );

/**
 * Modify the search query with posts_where
 *
 * http://codex.wordpress.org/Plugin_API/Filter_Reference/posts_where
 */
function cf_search_where( $where ) {
    global $pagenow, $wpdb;
		if ( (is_search() && is_admin()) || is_page_template('searchresults-catalogo.php') || is_page('catalogo-ragionato-options') ) {
        $where = preg_replace(
            "/\(\s*".$wpdb->posts.".post_title\s+LIKE\s*(\'[^\']+\')\s*\)/",
            "(".$wpdb->posts.".post_title LIKE $1) OR (p.meta_value LIKE $1)", $where );
    }
    return $where;
}
add_filter( 'posts_where', 'cf_search_where' );

/**
 * Prevent duplicates
 *
 * http://codex.wordpress.org/Plugin_API/Filter_Reference/posts_distinct
 */
function cf_search_distinct( $where ) {
    global $wpdb;
		if ( (is_search() && is_admin()) || is_page_template('searchresults-catalogo.php') ) {
        return "DISTINCT";
    }
    return $where;
}
add_filter( 'posts_distinct', 'cf_search_distinct' );

?>
