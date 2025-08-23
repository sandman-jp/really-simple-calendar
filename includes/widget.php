<?php

namespace sandmanjp\ReallySimpleCalendar;

if ( ! defined('ABSPATH') ) {
	exit; // Exit if accessed directly
}



if(!class_exists('sandmanjp\ReallySimpleCalendar\widget')):
	
	
class widget extends \WP_Widget{
	
	private $_fields;
	private $_locked;
	private $_locked_fields;
	
	function __construct(){
		
		parent::__construct(
			SMJP_RS_CALENDAR,
			'Really Simple Calendar',
			array( 'description' => esc_html__('Very Simple Single Calendar', 'really-simple-calendar')),
		);
		
		register_widget($this);
		
		$panel = new \sandmanjp\ReallySimpleCalendar\Admin\Panel\view();
		$this->_fields = $panel->get_fields();
		$lock_fields = $panel->get_lock_fields();
		$this->_locked = array();
		$this->_locked_fields = array();
		
		foreach($lock_fields as $key){
			$this->_locked[$key] = get_option($key);
			if($this->_locked[$key]){
				$this->_locked_fields[] = str_replace('_lock', '', $key);
			}
		}
	}
	
	function widget( $args, $instance ) {
		$title = apply_filters( 'widget_title', $instance['title'] );
		
		echo wp_kses_post($args['before_widget']);
		
		if ( ! empty( $title ) ) {
				echo wp_kses_post($args['before_title'] . $title . $args['after_title']);
		}
		
		smjp_rsc_get_calendar($instance);
		
		echo wp_kses_post($args['after_widget']);
	}
	
	function form( $instance ) {
			if ( isset( $instance['title'] ) ) {
					$title = $instance['title'];
			} else {
					$title = esc_html__('New title', 'really-simple-calendar');
			}
			?>
			<div class="components-placeholder__label">
				<label for="<?php echo esc_attr($this->get_field_id('title')); ?>"><?php esc_html_e( 'Title:', 'really-simple-calendar'); ?></label>
			</div>
			<div class="components-placeholder__fieldset">
				<input class="widefat" id="<?php echo esc_attr($this->get_field_id('title')); ?>" name="<?php echo esc_attr($this->get_field_name('title')); ?>" type="text" value="<?php echo esc_attr($title); ?>" />
			</div>
			
			<?php
			$this->_render_calendar_settings($instance);
		}
		
	private function _render_calendar_settings($instance){
		
		foreach($this->_fields as $f){
			if(!isset($instance[$f])){
				$instance[$f] = '';
			}
		}
		?>
		<div class="rsc-widget">
				<?php $this->_render_calendar_type($instance); ?>
				<?php $this->_render_start_from($instance); ?>
				<?php $this->_render_display_period($instance); ?>
				<?php $this->_render_start_of_week($instance); ?>
		</div>
		<?php
	}
	private function _render_calendar_type($instance){
		$is_locked_type = $this->_locked[SMJP_RS_CALENDAR.'_type_lock']; 
	?>
		<div class="components-placeholder__label rsc-widget-label">
			<label for="rsc-widget-type"><?php esc_html_e('Calendar Type', 'really-simple-calendar'); ?> <?php smjp_rsc_get_lock($is_locked_type); ?></label>
		</div>
		<div class="components-placeholder__fieldset rsc-widget-input">
			<select id="rsc-calendar-type" name="<?php echo esc_attr($this->get_field_name(SMJP_RS_CALENDAR.'_type')); ?>" class="rsc-widget-item" <?php disabled(wp_unslash($is_locked_type)); ?> style="background-image:" >
				<option value=""><?php esc_html_e('Default', 'really-simple-calendar'); ?></option>
				<?php if(!$is_locked_type): ?>
				<option value="month" <?php selected( wp_unslash($instance[SMJP_RS_CALENDAR.'_type']), 'month'); ?>><?php esc_html_e('Monthly', 'really-simple-calendar'); ?></option>
				<option value="week" <?php selected( wp_unslash($instance[SMJP_RS_CALENDAR.'_type']), 'week'); ?>><?php esc_html_e('Weekly', 'really-simple-calendar'); ?></option>
				<option value="day" <?php selected( wp_unslash($instance[SMJP_RS_CALENDAR.'_type']), 'day'); ?>><?php esc_html_e('Daily', 'really-simple-calendar'); ?></option>
				<?php endif; ?>
			</select>
		<?php if(!$is_locked_type): ?>
			<select id="rsc-calendar-type-align" name="<?php echo esc_attr($this->get_field_name(SMJP_RS_CALENDAR.'_align')); ?>" class="rsc-widget-item" required>
				<option value=""><?php esc_html_e('Default', 'really-simple-calendar'); ?></option>
				<option value="0" <?php selected( wp_unslash($instance[SMJP_RS_CALENDAR.'_align']), '0'); ?>><?php esc_html_e('Horizontal', 'really-simple-calendar'); ?></option>
				<option value="1" <?php selected( wp_unslash($instance[SMJP_RS_CALENDAR.'_align']), '1'); ?>><?php esc_html_e('Vertical', 'really-simple-calendar'); ?></option>
			</select>
			<span class="rsc-unit-month rsc-unit" style="display:none"><?php echo esc_html_x('Month(s)', 'unit', 'really-simple-calendar'); ?></span><span class="rsc-unit-week rsc-unit" style="display:none"><?php echo esc_html_x('Week(s)', 'unit', 'really-simple-calendar'); ?></span><span class="rsc-unit-day rsc-unit"  style="display:none"><?php echo esc_html_x('Day(s)', 'unit', 'really-simple-calendar'); ?></span>
		<?php endif; ?>
		</div>
	<?php
	}
		
	private function _render_start_from($instance){
		$is_locked_from = $this->_locked[SMJP_RS_CALENDAR.'_from_lock'];
	?>
		<div class="components-placeholder__label rsc-widget-label">
			<label for="rsc-widget-from"><?php esc_html_e('Start From', 'really-simple-calendar'); ?> <?php smjp_rsc_get_lock($is_locked_from); ?></label>
		</div>
		<div class="components-placeholder__fieldset rsc-widget-input">
			<select 
				id="rsc-calendar-from" 
				name="<?php echo esc_attr($this->get_field_name(SMJP_RS_CALENDAR.'_from')); ?>" 
				class="rsc-widget-item" <?php disabled(wp_unslash($is_locked_from)); ?>
				>
				<option value=""><?php esc_html_e('Default', 'really-simple-calendar'); ?></option>
				<?php if(!$is_locked_from): ?>
				<option value="previous" <?php selected( wp_unslash($instance[SMJP_RS_CALENDAR.'_from']), 'previous'); ?>><?php esc_html_e('Previous', 'really-simple-calendar'); ?></option>
				<option value="current" <?php selected( wp_unslash($instance[SMJP_RS_CALENDAR.'_from']), 'current'); ?>><?php esc_html_e('Current', 'really-simple-calendar'); ?></option>
				<option value="next" <?php selected( wp_unslash($instance[SMJP_RS_CALENDAR.'_from']), 'next'); ?>><?php esc_html_e('Next', 'really-simple-calendar'); ?></option>
				<option value="today" <?php selected( wp_unslash($instance[SMJP_RS_CALENDAR.'_from']), 'today'); ?>><?php esc_html_e('Today', 'really-simple-calendar'); ?></option>
				<option value="date" <?php selected( wp_unslash($instance[SMJP_RS_CALENDAR.'_from']), 'date'); ?>><?php esc_html_e('Date', 'really-simple-calendar'); ?></option>
				<?php endif; ?>
			</select>
			
		<?php if(!$is_locked_from): ?>
			<label id="rsc-calendar-from-previous" class="rsc-widget-item">
				<input 
					name="<?php echo esc_attr($this->get_field_name(SMJP_RS_CALENDAR.'_previous_from')); ?>" 
					type="number" 
					max="-1" 
					value="<?php echo (int)esc_attr($instance[SMJP_RS_CALENDAR.'_previous_from']); ?>" 
					class="small-text" 
					required
					>
				<span class="rsc-unit-month rsc-unit"><?php echo esc_html_x('Month(s)', 'unit', 'really-simple-calendar'); ?></span><span class="rsc-unit-week rsc-unit"><?php echo esc_html_x('Week(s)', 'unit', 'really-simple-calendar'); ?></span><span class="rsc-unit-day rsc-unit"><?php echo esc_html_x('Day(s)', 'unit', 'really-simple-calendar'); ?></span>
				<span class="dashicons dashicons-info" title="<?php esc_html_e('Should be less than 0', 'really-simple-calendar'); ?>"></span>
			</label>
			
			<label id="rsc-calendar-from-date" class="rsc-widget-item">
				<input 
					type="date" 
					name="<?php echo esc_attr($this->get_field_name(SMJP_RS_CALENDAR.'_from_date')); ?>" 
					value="<?php echo esc_attr($instance[SMJP_RS_CALENDAR.'_from_date']); ?>" 
					required
					>
			</label>
		<?php endif; ?>
		</div>
	<?php
	}
		
		
	private function _render_display_period($instance){
		$is_locked_period = $this->_locked[SMJP_RS_CALENDAR.'_period_lock']; 
	?>
		<div class="components-placeholder__label rsc-widget-label">
			<label for="rsc-widget-period"><?php esc_html_e('Display Period', 'really-simple-calendar'); ?> <?php smjp_rsc_get_lock($is_locked_period); ?></label>
		</div>
		<div class="components-placeholder__fieldset rsc-widget-input">
			<select 
				id="rsc-calendar-period" 
				name="<?php echo esc_attr($this->get_field_name(SMJP_RS_CALENDAR.'_period')); ?>" 
				class="rsc-widget-item" 
				<?php disabled(wp_unslash($is_locked_period)); ?>
				>
				<option value=""><?php esc_html_e('Default', 'really-simple-calendar'); ?></option>
				<?php if(!$is_locked_period): ?>
				<option value="last" <?php selected( wp_unslash($instance[SMJP_RS_CALENDAR.'_period']), 'last'); ?>><?php esc_html_e('Priod', 'really-simple-calendar'); ?></option>
				<option value="date" <?php selected( wp_unslash($instance[SMJP_RS_CALENDAR.'_period']), 'date'); ?>><?php esc_html_e('Date', 'really-simple-calendar'); ?></option>
				<?php endif; ?>
			</select>
			
		<?php if(!$is_locked_period): ?>
			<label id="rsc-calendar-period-last" class="rsc-widget-item">
				<input 
					name="<?php echo esc_attr($this->get_field_name(SMJP_RS_CALENDAR.'_period_last')); ?>" 
					type="number" 
					min="0" 
					value="<?php echo esc_attr($instance[SMJP_RS_CALENDAR.'_period_last']); ?>" 
					class="small-text" 
					required
					><span class="rsc-unit-month rsc-unit"><?php echo esc_html_x('Month(s)', 'unit', 'really-simple-calendar'); ?></span><span class="rsc-unit-week rsc-unit"><?php echo esc_html_x('Week(s)', 'unit', 'really-simple-calendar'); ?></span><span class="rsc-unit-day rsc-unit"><?php echo esc_html_x('Day(s)', 'unit', 'really-simple-calendar'); ?></span>
				<span class="dashicons dashicons-info" title="<?php esc_html_e('Should be 0 or more.', 'really-simple-calendar'); ?>"></span>
			</label>
			<label id="rsc-calendar-period-date" class="rsc-widget-item">
				<input 
					name="<?php echo esc_attr($this->get_field_name(SMJP_RS_CALENDAR.'_period_date')); ?>" 
					type="date" 
					value="<?php echo esc_attr($instance[SMJP_RS_CALENDAR.'_period_date']); ?>" 
					required
					>
			</label>
		<?php endif; ?>
		</div>
	<?php
	}
	
	private function _render_start_of_week($instance){
		$is_locked_start_of_week = $this->_locked[SMJP_RS_CALENDAR.'_start_of_week_lock']; 
	?>
		<div class="components-placeholder__label rsc-widget-label">
			<label for="rsc-widget-start_of_week"><?php esc_html_e('Start day of week', 'really-simple-calendar'); ?> <?php smjp_rsc_get_lock($is_locked_start_of_week); ?></label>
		</div>
		<div class="components-placeholder__fieldset rsc-widget-input">
			<select 
				id="rsc-calendar-start_of_week" 
				name="<?php echo esc_attr($this->get_field_name(SMJP_RS_CALENDAR.'_start_of_week')); ?>" 
				class="rsc-widget-item" 
				<?php disabled(wp_unslash($is_locked_start_of_week)); ?>
				>
				<option value=""><?php esc_html_e('Default', 'really-simple-calendar'); ?></option>
				<?php if(!$is_locked_start_of_week): ?>
				<option value="today" <?php selected( wp_unslash($instance[SMJP_RS_CALENDAR.'_start_of_week']), 'today'); ?>><?php esc_html_e('Today', 'really-simple-calendar'); ?></option>
				<option value="0" <?php selected( wp_unslash($instance[SMJP_RS_CALENDAR.'_start_of_week']), '0'); ?>><?php esc_html_e('Sunday', 'really-simple-calendar'); ?></option>
				<option value="1" <?php selected( wp_unslash($instance[SMJP_RS_CALENDAR.'_start_of_week']), '1'); ?>><?php esc_html_e('Monday', 'really-simple-calendar'); ?></option>
				<option value="2" <?php selected( wp_unslash($instance[SMJP_RS_CALENDAR.'_start_of_week']), '2'); ?>><?php esc_html_e('Tuesday', 'really-simple-calendar'); ?></option>
				<option value="3" <?php selected( wp_unslash($instance[SMJP_RS_CALENDAR.'_start_of_week']), '3'); ?>><?php esc_html_e('Wednesday', 'really-simple-calendar'); ?></option>
				<option value="4" <?php selected( wp_unslash($instance[SMJP_RS_CALENDAR.'_start_of_week']), '4'); ?>><?php esc_html_e('Thursday', 'really-simple-calendar'); ?></option>
				<option value="5" <?php selected( wp_unslash($instance[SMJP_RS_CALENDAR.'_start_of_week']), '5'); ?>><?php esc_html_e('Friday', 'really-simple-calendar'); ?></option>
				<option value="6" <?php selected( wp_unslash($instance[SMJP_RS_CALENDAR.'_start_of_week']), '6'); ?>><?php esc_html_e('Saturday', 'really-simple-calendar'); ?></option>
				<?php endif; ?>
			</select>
		</div>
	<?php
	}
	
	
	// Updating widget replacing old instances with new
	function update( $new_instance, $old_instance ) {
		
		// $this->_locked
		
		$instance = array();
		
		$fields = array(
			'title',
			SMJP_RS_CALENDAR.'_type', 
			SMJP_RS_CALENDAR.'_from',
			SMJP_RS_CALENDAR.'_from_date', 
			SMJP_RS_CALENDAR.'_previous_from',
			SMJP_RS_CALENDAR.'_period', 
			SMJP_RS_CALENDAR.'_period_last', 
			SMJP_RS_CALENDAR.'_period_date', 
			SMJP_RS_CALENDAR.'_start_of_week',
			SMJP_RS_CALENDAR.'_align',
		);
		
		foreach($fields as $f){
			
			if(!empty($new_instance[$f])){
				$locked = false;
				//check if locked.
				foreach($this->_locked_fields as $lf){
					if(strpos($f, $lf) !== false){
						$locked = true;
					}
				}
				if(!$locked){
					$instance[$f] = wp_strip_all_tags($new_instance[$f]);
				}
			}
			
		}
		
		return $instance;
	}
}

endif;