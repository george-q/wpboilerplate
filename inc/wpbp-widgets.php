<?php

class wpbp_vcard extends WP_Widget {

	function wpbp_vcard() {
		$widget_ops = array('description' => 'Display a vCard');
		parent::WP_Widget(false, __('WPBP: vCard', 'wpbp'), $widget_ops);
	}
   
	function widget($args, $instance) {  
		extract($args);
		extract($instance);

		echo $before_widget;
		if ( isset($title) && strlen($title) > 0 ) {
			echo $before_title . $title . $after_title;
		}
		?>
		<p class="vcard">
			<a class="fn org url" href="<?php echo home_url('/'); ?>"><?php bloginfo('name'); ?></a><br>
			<span class="adr">
			<span class="street-address"><?php echo $street_address; ?></span><br>
			<span class="locality"><?php echo $locality; ?></span>,
			<span class="region"><?php echo $region; ?></span>
			<span class="postal-code"><?php echo $postal_code; ?></span><br>
			</span>
			<span class="tel"><span class="value"><span class="hidden">+1-</span><?php echo $tel; ?></span></span><br>
			<a class="email" href="mailto:<?php echo $email; ?>"><?php echo $email; ?></a>
		</p>        
    <?php echo $after_widget;
        
	}
	
	function update($new_instance, $old_instance) {                
		return $new_instance;
	}

	function form($instance) {
		$fields = array(
			'title' => array(
				'id' => $this->get_field_id('title'),
				'name' => $this->get_field_name('title'),
				'title' => 'Title:',
				'type' => 'text',
				'required' => false
			),
			'street_address' => array(
				'id' => $this->get_field_id('street_address'),
				'name' => $this->get_field_name('street_address'),
				'title' => 'Street Address:',
				'type' => 'text',
				'required' => false
			),
			'locality' => array(
				'id' => $this->get_field_id('locality'),
				'name' => $this->get_field_name('locality'),
				'title' => 'City/Locality:',
				'type' => 'text',
				'required' => false
			),
			'postal_code' => array(
				'id' => $this->get_field_id('postal_code'),
				'name' => $this->get_field_name('postal_code'),
				'title' => 'Zipcode/Postal Code:',
				'type' => 'text',
				'required' => false
			),
			'tel' => array(
				'id' => $this->get_field_id('tel'),
				'name' => $this->get_field_name('tel'),
				'title' => 'Telephone:',
				'type' => 'text',
				'required' => false
			),
			'email' => array(
				'id' => $this->get_field_id('email'),
				'name' => $this->get_field_name('email'),
				'title' => 'Email:',
				'type' => 'text',
				'required' => false
			)
		);
		wpbp_build_form($fields, $instance);
	}
} 

register_widget('wpbp_vcard');


class wpbp_cat_nav extends WP_Widget {

	function wpbp_cat_nav() {
		$widget_ops = array('description' => 'Display a navigation menu based on categories and posts');
		parent::WP_Widget(false, __('WPBP: Category Navigation', 'wpbp'), $widget_ops);
	}

	function widget($args, $instance) {
		extract($args);
		extract($instance);

		echo $before_widget;
		if ( isset($title) && strlen($title) > 0 ) {
			echo $before_title . $title . $after_title;
		}

		echo "<ul class=\"cat-list\">";

		$cats = get_categories();
		foreach($cats as $cat) {

			$current_menu_item = ( is_category() && ( $cat->ID == get_query_var('cat') ) ) ? " current-menu-item" : "";
			echo "<li class=\"cat-name" . $current_menu_item . "\"><a href=\"" . get_category_link( $cat->cat_ID ) . "\">" . $cat->name . "</a>";
			echo "<ul class=\"cat-posts\">";

			$cat_posts = get_posts( array(
				'numberposts' => -1,
				'category' => $cat->cat_ID
			) );
			foreach( $cat_posts as $post ) {
				setup_postdata($post);
				$current_menu_item = ( is_single() && ( get_the_ID() == get_query_var('page_id') ) ) ? " current-menu-item" : "";
				echo "<li class=\"post-link" . $current_menu_item . "\"><a href=\"" . get_permalink() . "\">" . get_the_title() . "</a></li>";
			}
			echo "</ul></li>";
		}
		echo "</ul>";

		echo $after_widget;

	}

	function update($new_instance, $old_instance) {
		return $new_instance;
	}

	function form($instance) {}
}

register_widget('wpbp_cat_nav');


class wpbp_most_popular extends WP_Widget {

	function wpbp_most_popular() {
		$widget_ops = array('description' => 'Displays the most popular posts based on number of views in the last \'x\' days.');
		parent::WP_Widget(false, __('WPBP: Most Popular', 'wpbp'), $widget_ops);
	}

	function widget($args, $instance) {
		extract($args);
		extract($instance);

		echo $before_widget;
		if ( isset($title) && strlen($title) > 0 ) {
			echo $before_title . $title . $after_title;
		}

		$category = get_query_var('cat');

		echo $after_widget;

	}

	function update($new_instance, $old_instance) {
		return $new_instance;
	}

	function form($instance) {
		$fields = array(
			'title' => array(
				'id' => $this->get_field_id('title'),
				'name' => $this->get_field_name('title'),
				'title' => 'Title:',
				'type' => 'text',
				'defval' => '',
				'required' => false
			),
			'time_range' => array(
				'id' => $this->get_field_id('time_range'),
				'name' => $this->get_field_name('time_range'),
				'title' => 'Time range:',
				'type' => 'dropdown',
				'required' => true,
				'options' => array(
					'today' => 'Today',
					'3-days' => 'Last 3 days',
					'7-days' => 'Last week',
					'all-time' => 'All-time'
				),
				'defval' => 'today'
			),
			'display' => array(
				'id' => $this->get_field_id('display'),
				'name' => $this->get_field_name('display'),
				'title' => 'Display:',
				'type' => 'multi-checkbox',
				'required' => false,
				'options' => array(
					'post_title' => 'Post title',
					'featured_image' => 'Featured image',
					'post_time' => 'Post time',
					'post_author' => 'Post author',
					'post_excerpt' => 'Post excerpt',
					'comment_count' => 'Comment count',
					'view_count' => 'View count'
				),
				'defval' => array('post_title', 'post_time')
			)
		);
		wpbp_build_form($fields, $instance);
	}
}

register_widget('wpbp_most_popular');

?>