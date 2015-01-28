<?php

function adm_sitemap($atts, $content = null, $code) {
	if(isset($atts['type'])){
		switch($atts['type']){
			case 'page': case 'pages':
				return adm_sitemap_pages($atts);
			case 'post': default:
				return adm_sitemap_posts($atts);	
		}
	}
	return '';
}
add_shortcode('sitemap', 'adm_sitemap');

///////////////////////////////////////////////////////////////////////////////

function adm_sitemap_pages($atts){
	extract(shortcode_atts(array(
		'number' => '0',
		'depth' => '0',
	), $atts));
	
	return '<div class="sc-sitemap sitemap-pages"><ul>'.wp_list_pages('depth=0&sort_column=menu_order&echo=0&title_li=&depth='.$depth.'&number='.$number ).'</ul></div>';
}

///////////////////////////////////////////////////////////////////////////////

function adm_sitemap_posts($atts){
	extract(shortcode_atts(array(
		'comments_number' => true,
		'number' => '0',
		'cat' => '',
		'posts' => '',
		'author' => '',
    'type' => 'post',
    'comments' => 'true'
	), $atts));
	
	if($number == 0){
		$number = 1000;
	}
	if($comments_number === 'false'){
		$comments_number = false;
	}
	
	$query = array(
		'showposts' => (int)$number,
		'post_type'=>$type//'post',
	);
	if($cat){
		$query['cat'] = $cat;
	}
	if($posts){
		$query['post__in'] = explode(',',$posts);
	}
	if($author){
		$query['author'] = $author;
	}
	$archive_query = new WP_Query( $query );
	
	$output = '';
	while ($archive_query->have_posts()) : $archive_query->the_post();
		$output .= '<li><a href="'.get_permalink().'" rel="bookmark" title="'.sprintf( __("Permanent Link to %s", 'striking_front'), get_the_title() ).'">'. get_the_title().'</a>';
		if( $comments == 'true' ) {
			$output .= ($comments_number?' ('.get_comments_number().')':'');
		}
		$output .= '</li>';
	endwhile;
	
	wp_reset_query();
	
	return '<div class="sc-sitemap sitemap-posts"><ul>'.$output.'</ul></div>';
}