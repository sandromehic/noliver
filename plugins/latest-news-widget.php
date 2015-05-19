
<?php
/*
Plugin Name: Latest isf news Widget
Description: Widget personalizzato, fa un loop sulle ultime notizie e le sputa fuori in un widget con un po' di stile!
Author: Sandro Mehic
Version: 1.20
*/


class LatestNewsWidget extends WP_Widget
{
  function LatestNewsWidget()
  {
    $widget_ops = array('classname' => 'LatestNewsWidget', 'description' => 'Widget personalizzato, fa un loop sulle ultime notizie e le sputa fuori in un widget con un po\' di stile!' );
    $this->WP_Widget('LatestNewsWidget', 'Latest News', $widget_ops);
  }

  function form($instance)
  {
    $instance = wp_parse_args( (array) $instance, array( 'title' => '' ) );
    $title = $instance['title'];
?>
  <p><label for="<?php echo $this->get_field_id('title'); ?>">Title: <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo attribute_escape($title); ?>" /></label></p>
<?php
  }

  function update($new_instance, $old_instance)
  {
    $instance = $old_instance;
    $instance['title'] = $new_instance['title'];
    return $instance;
  }

  function widget($args, $instance)
  {
    extract($args, EXTR_SKIP);

    echo $before_widget;
    $title = empty($instance['title']) ? ' ' : apply_filters('widget_title', $instance['title']);

    if (!empty($title))
      echo $before_title . $title . $after_title;;

    // WIDGET CODE GOES HERE
		$timestamp = strtotime('today midnight');
    $args = array(
			'post_type' => 'post',
			'posts_per_page' => 5,
		);
		$loop = new WP_Query( $args );
	?> <ul> <?php
		while ( $loop->have_posts() ) : $loop->the_post();
			?>
				<li>
				<a class="post-title nextevents" href="<?php the_permalink() ?>">
				<?php the_title(); ?>
				</a></li>
				<p ><?php the_date(); ?></p>

			<?php
		endwhile;
		?> </ul> <?php

    echo $after_widget;
  }

}
add_action( 'widgets_init', create_function('', 'return register_widget("LatestNewsWidget");') );?>
