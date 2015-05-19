
<?php
/*
Plugin Name: Next Reunions Widget
Description: Widget personalizzato, fa un loop sulle ultime riunioni e le sputa fuori in un widget con un po' di stile!
Author: Sandro Mehic
Version: 1
*/


class NextReunionWidget extends WP_Widget
{
  function NextReunionWidget()
  {
    $widget_ops = array('classname' => 'NextReunionWidget', 'description' => 'Widget personalizzato, fa un loop sulle ultime riunioni e le sputa fuori in un widget con un po\' di stile!' );
    $this->WP_Widget('NextReunionWidget', 'Next Reunion', $widget_ops);
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
			'post_type' => 'isf_event',
			'cat' => '2',
			'posts_per_page' => 5,
			'meta_key' => '_event_datestamp_meta_value_key',
      'orderby' => 'meta_value_num',
      'order' => 'ASC',
			'meta_query' => array(
				array(
					'key'     => '_event_datestamp_meta_value_key',
					'value'   => $timestamp,
					'compare' => '>=',
				),
			),
		);
		$loop = new WP_Query( $args );
	?> <ul> <?php
		while ( $loop->have_posts() ) : $loop->the_post();
			?>
				<li>
				<a class="post-title nextevents" href="<?php the_permalink() ?>">
			<?php
				$event_date = get_post_meta( get_the_ID(), '_event_date_meta_value_key', true );
				// check if the custom field has a value
				if( ! empty( $event_date ) ) {
					print "<span style=\"font-weight:bold\">" . $event_date . "</span> - ";
				}

				the_title();

			?>
				</a></li><br/>

			<?php
		endwhile;
		?> </ul> <?php

    echo $after_widget;
  }

}
add_action( 'widgets_init', create_function('', 'return register_widget("NextReunionWidget");') );?>
