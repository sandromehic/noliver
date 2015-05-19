<?php get_header(); ?>

<?php do_action( 'bp_before_content' ) ?>
<!-- CONTENT START -->
<div id="single-content" class="content">
<div class="content-inner">

<?php do_action( 'bp_before_blog_home' ) ?>

<!-- POST ENTRY START -->
<div id="post-entry">
<section class="post-entry-inner">

<?php if (have_posts()) : while (have_posts()) : the_post(); ?>

<?php $full_img = get_theme_option('full_feat_img'); if($full_img == 'Enable') {
if( get_theme_option('full_feat_img_only_show') == 'Enable') {
$get_featon = get_post_meta($post->ID, 'full_feat_img_on', true); if($get_featon == 'yes') {
echo get_featured_post_image("<div class='post-thumb in-single'>", "</div>", 1000, 300, "alignleft", "full", get_singular_cat('false') ,the_title_attribute('echo=0'), false); }
} else {
echo get_featured_post_image("<div class='post-thumb in-single'>", "</div>", 1000, 300, "alignleft", "full", get_singular_cat('false') ,the_title_attribute('echo=0'), false);
}
}
?>

<!-- POST START -->
<article <?php post_class('post-single'); ?> id="post-<?php the_ID(); ?>"<?php do_action('bp_article_start'); ?>>

<h1 class="post-title entry-title"<?php do_action('bp_article_post_title'); ?>><?php the_title(); ?></h1>
<?php
// uncomment the next line to have standard meta of the event post
//get_template_part( 'lib/templates/post-meta' );
?>

<!-- Show the date, time and place of the event if they exist -->
<?php

$event_date = get_post_meta( get_the_ID(), '_event_date_meta_value_key', true );
// check if the custom field has a value
if( ! empty( $event_date ) ) {
  print "<h2 class=\"customDateMeta\">In data: $event_date</h2>";
}

$event_time = get_post_meta( get_the_ID(), '_event_time_meta_value_key', true );
// check if the custom field has a value
if( ! empty( $event_time ) ) {
  print "<h2 class=\"customDateMeta\">Orario: $event_time</h2>";
}

$event_place = get_post_meta( get_the_ID(), '_event_place_meta_value_key', true );
// check if the custom field has a value
if( ! empty( $event_place ) ) {
  print "<h2 class=\"customDateMeta\">Luogo: $event_place</h2>";
}
?>

<div class="post-content entry-content"<?php do_action('bp_article_post_content'); ?>>
<?php the_content( __('...more &raquo;',TEMPLATE_DOMAIN) ); ?>

</div>

<?php get_template_part( 'lib/templates/post-meta-bottom' ); ?>

</article>
<!-- POST END -->

<?php get_template_part( 'lib/templates/author-bio' ); ?>

<div class="sharebox-wrap">
<?php get_template_part( 'lib/templates/share-box' ); ?>
</div>

<?php get_template_part( 'lib/templates/related' ); ?>

<?php set_wp_post_view( get_the_ID() ); ?>

<?php endwhile; ?>

<?php comments_template( '', true );  ?>

<?php else : ?>

<?php get_template_part( 'lib/templates/result' ); ?>

<?php endif; ?>

<?php get_template_part( 'lib/templates/paginate' ); ?>

</section>
</div>
<!-- POST ENTRY END -->

<?php do_action( 'bp_after_blog_home' ) ?>

</div><!-- CONTENT INNER END -->
</div><!-- CONTENT END -->

<?php do_action( 'bp_after_content' ) ?>

<?php get_sidebar(); ?>

<?php get_footer(); ?>
