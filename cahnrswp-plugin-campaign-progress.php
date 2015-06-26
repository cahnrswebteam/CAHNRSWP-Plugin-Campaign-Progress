<?php 
/*
Plugin Name: CAHNRS Campaign Progress Meter
Description: Registers a widget which displays an animated campaign progress meter
Author: CAHNRS, philcable
Version: 0.1.0
*/

class CAHNRSWP_Campaign_Progress_Widget extends WP_Widget {

	/**
	 * Register widget with WordPress.
	 */
	function __construct() {
		parent::__construct(
			'cahnrs_campaign_progress_meter', // Base ID
			'Campaign Progress Meter', // Name
			array( 'description' => 'An animated campaign progress meter', ) // Args
		);
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ) );
	}

	/**
	 * Enqueue the scripts and styles used in the admin interface.
	 */
	public function admin_enqueue_scripts( $hook ) {
		if ( 'post-new.php' == $hook || 'post.php' == $hook || 'widgets.php' == $hook ) {
			wp_enqueue_style( 'cahnrswp-campaign-progress-meter-widget', plugins_url( 'css/campaign-progress-edit.css', __FILE__ ) );
			wp_enqueue_script( 'cahnrswp-campaign-progress-meter-widget', plugins_url( 'js/campaign-progress-edit.js', __FILE__ ), array( 'jquery-ui-tabs' ) );
		}
	}

	/**
	 * Front-end display of widget.
	 */
	public function widget( $args, $instance ) {
		wp_enqueue_style( 'cahnrswp-campaign-progress-meter-style', plugins_url( 'css/campaign-progress-widget.css', __FILE__ ) );
		wp_enqueue_script( 'cahnrswp-campaign-progress-meter-script', plugins_url( 'js/campaign-progress-widget.js', __FILE__ ), array( 'jquery' ) );
		
		$background_image = wp_get_attachment_image_src( $instance['background_image'], 'full' );
		$overlay_image = wp_get_attachment_image_src( $instance['overlay_image'], 'full' );

		if ( ! empty( $instance['progress_background'] ) ) {
			if ( strpos( $instance['progress_background'], '#' ) ) {
				$progress_background = $instance['progress_background'];
			} else {
				$image = wp_get_attachment_image_src( $instance['progress_background'], 'full' );
				$progress_background = 'url(' . esc_url( $image[0] ) . ') no-repeat bottom left';
			}
		}
		
		
		echo $args['before_widget'];
		?>
		<h2><?php echo esc_html( $instance['title'] ); ?></h2>
		<div class="campaign-progress-container">
    	<?php if ( $background_image ) : ?>
				<img class="campaign-progress-background" src="<?php echo esc_url( $background_image[0] ); ?>" width="<?php echo esc_attr( $background_image[1] ); ?>" height="<?php echo esc_attr( $background_image[2] ); ?>">
			<?php endif; ?>
			<div class="campaign-progress-indicator" style="background: <?php echo ( $progress_background ) ? $progress_background : '#981e32'; ?>; width: <?php echo esc_attr( $overlay_image[1] ); ?>;"></div>
			<?php if ( $overlay_image ) : ?>
				<img class="campaign-progress-overlay" src="<?php echo esc_url( $overlay_image[0] ); ?>" width="<?php echo esc_attr( $overlay_image[1] ); ?>" height="<?php echo esc_attr( $overlay_image[2] ); ?>">
			<?php endif; ?>
			<p class="campaign-total">$<?php echo esc_html( $instance['total_display'] ); ?></p>
			<div class="campaign-progress-indicator" style="padding-bottom: 28px; width: <?php echo esc_attr( $overlay_image[1] ); ?>;">
				<p class="campaign-progress-amount-wrapper">
					$<span class="campaign-progress-amount" data-total="<?php echo esc_attr( $instance['total'] ); ?>" data-progress="<?php echo esc_attr( $instance['progress'] ); ?>">0</span>
				</p>
			</div>
		</div>
		<?php
		echo $args['after_widget'];
	}

	/**
	 * Back-end widget form.
	 */
	public function form( $instance ) {
		$title = ! empty( $instance['title'] ) ? $instance['title'] : '';
		$total = ! empty( $instance['total'] ) ? $instance['total'] : '';
		$progress = ! empty( $instance['progress'] ) ? $instance['progress'] : '';
		$total_display = ! empty( $instance['total_display'] ) ? $instance['total_display'] : '';
		$overlay_image = ! empty( $instance['overlay_image'] ) ? $instance['overlay_image'] : '';
		$progress_background = ! empty( $instance['progress_background'] ) ? $instance['progress_background'] : '#981e32';
		$background_image = ! empty( $instance['background_image'] ) ? $instance['background_image'] : '';
		?>
		<p><label for="<?php echo $this->get_field_id( 'title' ); ?>">Title</label>
		<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>"></p>
		<p><label for="<?php echo $this->get_field_id( 'total' ); ?>">Campaign Total (<span class="description">full number without commas</span>)</label>
		<input class="widefat" id="<?php echo $this->get_field_id( 'total' ); ?>" name="<?php echo $this->get_field_name( 'total' ); ?>" type="text" value="<?php echo esc_attr( $total ); ?>"></p>
    <p><label for="<?php echo $this->get_field_id( 'progress' ); ?>">Campaign Progress (<span class="description">full number without commas</span>)</label>
		<input class="widefat" id="<?php echo $this->get_field_id( 'progress' ); ?>" name="<?php echo $this->get_field_name( 'progress' ); ?>" type="text" value="<?php echo esc_attr( $progress ); ?>"></p>

		<p>Display</p>

		<ul class="campaign-progress-display-options">
			<li>
				<label for="<?php echo $this->get_field_id( 'total_display' ); ?>">Campaign Total (<span class="description">example: X Million</span>)</label>
				<input class="widefat" id="<?php echo $this->get_field_id( 'total_display' ); ?>" name="<?php echo $this->get_field_name( 'total_display' ); ?>" type="text" value="<?php echo esc_attr( $total_display ); ?>">
			</li>
			<li class="upload-set-wrapper">
				<input type="hidden" class="campaign-progress-upload" name="<?php echo $this->get_field_name( 'overlay_image' ); ?>" id="<?php echo $this->get_field_id( 'overlay_image' ); ?>" value="<?php echo esc_attr( $overlay_image ); ?>" />
      	Overlay Image: <span class="hide-if-no-js campaign-progress-set-link"><a class="campaign-progress-overlay" href="#">Set</a></span>
        <span class="hide-if-no-js campaign-progress-remove-link"<?php if ( ! $overlay_image ) { echo ' style="display: none;"'; } ?>><a class="campaign-progress-overlay" title="Remove" href="#">Remove</a></span>
			</li>
			<li class="upload-set-wrapper">
				<input type="hidden" class="campaign-progress-upload" name="<?php echo $this->get_field_name( 'progress_background' ); ?>" id="<?php echo $this->get_field_id( 'progress_background' ); ?>" value="<?php echo esc_attr( $progress_background ); ?>" />
				Progress Image: <span class="hide-if-no-js campaign-progress-set-link"><a class="campaign-progress-indicator" href="#">Set</a></span>
        <span class="hide-if-no-js campaign-progress-remove-link"<?php if ( ! $progress_background ) { echo ' style="display: none;"'; } ?>><a class="campaign-progress-indicator" title="Remove" href="#">Remove</a></span>
			</li>
			<li class="upload-set-wrapper">
				<input type="hidden" class="campaign-progress-upload" name="<?php echo $this->get_field_name( 'background_image' ); ?>" id="<?php echo $this->get_field_id( 'background_image' ); ?>" value="<?php echo esc_attr( $background_image ); ?>" />
				Background Image: <span class="hide-if-no-js campaign-progress-set-link"><a class="campaign-progress-background" href="#">Set</a></span>
        <span class="hide-if-no-js campaign-progress-remove-link"<?php if ( ! $background_image ) { echo ' style="display: none;"'; } ?>><a class="campaign-progress-background" title="Remove" href="#">Remove</a></span>
			</li>
		</ul>

		<p>Preview (<span class="description">at 50% progress</span>)</p>
		<div class="campaign-progress-preview-container">

			<?php if ( $background_image ) : ?>
				<?php $image = wp_get_attachment_image_src( $background_image, 'full' ); ?>
				<img src="<?php echo esc_url( $image[0] ); ?>" class="campaign-progress-background-preview" />
			<?php else : ?>
				<span class="campaign-progress-background-placeholder"></span>
			<?php endif; ?>

			<?php
      	if ( $progress_background ) {
					if ( strpos( $progress_background, '#' ) === false ) {
						$image = wp_get_attachment_image_src( $progress_background, 'full' );
						$background = 'url(' . esc_url( $image[0] ) . ') no-repeat';
					} else { 
						$background = $progress_background;
					}
				}
			?>
			<div class="campaign-progress-indicator" style="background: <?php echo ( $background ) ? $background : '#981e32'; ?>;"><span class="campaign-progress-indicator-placeholder"></span></div>

			<?php if ( $overlay_image ) : ?>
				<?php $image = wp_get_attachment_image_src( $overlay_image, 'full' ); ?>
				<img src="<?php echo esc_url( $image[0] ); ?>" class="campaign-progress-overlay-preview" />
			<?php else : ?>
				<span class="campaign-progress-overlay-placeholder"></span>
			<?php endif; ?>

			<p class="campaign-total">$<?php echo ( $instance['total_display'] ) ? esc_html( $instance['total_display'] ) : 'XXXXXX'; ?></p>

			<div class="campaign-progress-indicator" style="padding-bottom: 28px; width: <?php echo esc_attr( $overlay_image[1] ); ?>;">
				<p class="campaign-progress-amount-wrapper">
					$<span class="campaign-progress-amount"><?php echo ( $instance['total'] ) ? esc_html( $instance['total'] / 2 ) : 'XXXXXX'; ?></span>
				</p>
			</div>

		</div>

		<?php
	}

	/**
	 * Sanitize widget form values as they are saved.
	 */
	public function update( $new_instance, $old_instance ) {
		$instance = array();
		$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
		$instance['total'] = ( ! empty( $new_instance['total'] ) ) ? strip_tags( $new_instance['total'] ) : '';
		$instance['progress'] = ( ! empty( $new_instance['progress'] ) ) ? strip_tags( $new_instance['progress'] ) : '';
		$instance['total_display'] = ( ! empty( $new_instance['total_display'] ) ) ? strip_tags( $new_instance['total_display'] ) : '';
		$instance['overlay_image'] = ( ! empty( $instance['overlay_image'] ) ) ? strip_tags( $new_instance['overlay_image'] ) : '';
		$instance['progress_background'] = ( ! empty( $instance['progress_background'] ) ) ? strip_tags( $new_instance['progress_background'] ) : '#981e32';
		$instance['background_image'] = ( ! empty( $instance['background_image'] ) ) ? strip_tags( $new_instance['background_image'] ) : '';
		return $instance;
	}

}

add_action( 'widgets_init', function(){
	register_widget( 'CAHNRSWP_Campaign_Progress_Widget' );
});