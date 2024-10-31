<?php
/**
 * Plugin Name: Product Widget Glopart
 * Plugin URI: https://ru.wordpress.org/plugins/product-widget-glopart/
 * Description: Теперь вы можете активно зарабатывать на продаже топовых товаров из каталога партнерских программ Glopart.ru. Просто укажите свой Glopart ID рекламного блока, количество отображаемых на сайте товаров.
 * Version: 1.0.3
 * Author: WpMoney
 * Author URI: https://wpmoney.ru
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

define('PWG_URL', plugin_dir_url( __FILE__ ));

function pwg_admin_scripts( $hook ){
    if( $hook == 'widgets.php' ){
        wp_enqueue_style( 'pwg_admin_css', PWG_URL . 'assets/css/style.css' );
    }
}
add_action( 'admin_enqueue_scripts', 'pwg_admin_scripts' );

add_action( 'widgets_init', 'pwg_product_glopart' );

function pwg_product_glopart() {
	register_widget( 'PWG_Glopart_Widget' );
}

class PWG_Glopart_Widget extends WP_Widget {

	function PWG_Glopart_Widget() {
		$widget_ops = array( 'classname' => 'example', 'description' => __('Зарабатывайте на продаже топовых товаров из каталога партнерских продаж Glopart.ru', 'example') );
				
		parent::__construct( 'pwg_glopart', __('Витрина товаров Glopart', 'example'), $widget_ops );
	}
	
	function widget( $args, $instance ) {
		extract( $args );

		//Наши переменные из настроек виджета.
		$title = apply_filters('widget_title', $instance['title'] );
		$glopart_id = $instance['glopart_id'];
		$glopart_count = $instance['glopart_count'];
		$glopart_img_width = $instance['glopart_img_width'];
		$glopart_img_align = $instance['glopart_img_align'];
		$glopart_img_border_radius = $instance['glopart_img_border_radius'];
		$selected_url = $instance['selected_url'];

		echo $before_widget;

		// Отображение заголовка виджета на сайте
		echo $before_title . $title . $after_title;

		// Отображаем контент и имя на сайте 
		$body = array(
            'user_id' => $glopart_id,
            'count_featured' => $glopart_count,
            'img_width' => $glopart_img_width,
            'img_align' => $glopart_img_align,
            'border_radius' => $glopart_img_border_radius,
            'url' => $_SERVER['SERVER_NAME']
    	);
        $args = array(
            'body' => $body
        );

	$response = wp_remote_post( $selected_url, $args );
        $body = wp_remote_retrieve_body( $response );
	
		echo $body;
		
		echo $after_widget;
	}

	// Обновление виджета 
	 
	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;

	//Удаляем HTML-теги из заголовка и имени 
		$instance['title'] = strip_tags( $new_instance['title'] );
		$instance['glopart_id'] = strip_tags( $new_instance['glopart_id'] );
		$instance['glopart_count'] = strip_tags( $new_instance['glopart_count'] );
		$instance['glopart_img_width'] = strip_tags( $new_instance['glopart_img_width'] );
		$instance['glopart_img_align'] = strip_tags( $new_instance['glopart_img_align'] );
		$instance['glopart_img_border_radius'] = strip_tags( $new_instance['glopart_img_border_radius'] );
		$instance['selected_url'] = strip_tags( $new_instance['selected_url'] );

		return $instance;
	}
	
	function form( $instance ) {

	// Настройка параметров виджета и данные по умолчанию.
		$defaults = array( 'title' => __('Витрина товаров Glopart.ru', 'example'), 'glopart_id' => __('11690', 'example'), 'glopart_count' => __('5', 'example'), 'glopart_img_width' => __('250', 'example'), 'glopart_img_align' => __('center', 'example'), 'glopart_img_border_radius' => __('0', 'example'), 'selected_url' => __('https://wpmoney.ru/engine/widget_free.php', 'example') );
		$instance = wp_parse_args( (array) $instance, $defaults );
		$free_url = 'https://wpmoney.ru/engine/widget_free.php';
		$pay_url = 'https://wpmoney.ru/engine/widget.php';
		$left_selected = 'left';
		$center_selected = 'center'; 
		$right_selected = 'right'; ?>

		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e('Название блока (отображается на сайте):', 'example'); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo $instance['title']; ?>" style="width:100%;" />
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'glopart_id' ); ?>"><?php _e('Ваш Glopart ID:', 'example'); ?></label>
			<input id="<?php echo $this->get_field_id( 'glopart_id' ); ?>" name="<?php echo $this->get_field_name( 'glopart_id' ); ?>" type="number" value="<?php echo $instance['glopart_id']; ?>" style="width:100%;" />
		</p>
		
		<p>
			<label for="<?php echo $this->get_field_id( 'glopart_count' ); ?>"><?php _e('Количество объявлений в блоке:', 'example'); ?></label>
			<input id="<?php echo $this->get_field_id( 'glopart_count' ); ?>" name="<?php echo $this->get_field_name( 'glopart_count' ); ?>" type="number" value="<?php echo $instance['glopart_count']; ?>" style="width:100%;" />
		</p>
		
		<p>
			<label for="<?php echo $this->get_field_id( 'glopart_img_width' ); ?>"><?php _e('Максимальная ширина картинки в px:', 'example'); ?></label>
			<input id="<?php echo $this->get_field_id( 'glopart_img_width' ); ?>" name="<?php echo $this->get_field_name( 'glopart_img_width' ); ?>" type="number" value="<?php echo $instance['glopart_img_width']; ?>" style="width:100%;" />
		</p>
		
		<p>
			<label for="<?php echo $this->get_field_id( 'glopart_img_align' ); ?>"><?php _e('Выравнивание картинки (слева | над текстом | справа)', 'example'); ?></label><br>
                         <select class="form-control" id="<?php echo $this->get_field_id( 'glopart_img_align' ); ?>" name="<?php echo $this->get_field_name( 'glopart_img_align' ); ?>" value="<?php echo $instance['glopart_img_align']; ?>">
                         <?php
                         $selected_left = '';
                         $selected_center = '';
                         $selected_right = '';
                         if( $instance['glopart_img_align'] == $left_selected ) {
                             $selected_left = 'selected';
                         } elseif( $instance['glopart_img_align'] == $center_selected ) {
                             $selected_center = 'selected';
                         } elseif( $instance['glopart_img_align'] == $right_selected ) {
                             $selected_right = 'selected';
                         }
                         ?>
                         <option style="display: none" value="<?php echo $instance['glopart_img_align']; ?>"><?php echo $instance['glopart_img_align']; ?></option>
                         <option value="<?php echo $left_selected; ?>" <?php echo $selected_left; ?>>Слева</option>
                         <option value="<?php echo $center_selected; ?>" <?php echo $selected_center; ?>>Над текстом</option>
                         <option value="<?php echo $right_selected; ?>" <?php echo $selected_right; ?>>Справа</option>
                         </select>
		</p>
		
		<p>
			<label for="<?php echo $this->get_field_id( 'glopart_img_border_radius' ); ?>"><?php _e('Скругление картинки в процентах', 'example'); ?></label><br>
                         <select class="form-control" id="<?php echo $this->get_field_id( 'glopart_img_border_radius' ); ?>" name="<?php echo $this->get_field_name( 'glopart_img_border_radius' ); ?>" value="<?php echo $instance['glopart_img_border_radius']; ?>">
                         <?php
                         $border_radius_zero = '0';
                         $border_radius_twenty = '20';
                         $border_radius_thirty_five = '35';
                         $border_radius_fifty = '50';
                         if( $instance['glopart_img_border_radius'] == $border_radius_zero ) {
							 echo $border_radius_zero;
                         } elseif( $instance['glopart_img_border_radius'] == $border_radius_thirty_twenty ) {
							 echo $border_radius_thirty_twenty;
                         } elseif( $instance['glopart_img_border_radius'] == $border_radius_thirty_five ) {
							 echo $border_radius_thirty_five;
                         } elseif( $instance['glopart_img_border_radius'] == $border_radius_fifty ) {
							 echo $border_radius_fifty;
                         }
                         ?>
                         <option style="display: none" value="<?php echo $instance['glopart_img_border_radius']; ?>"><?php echo $instance['glopart_img_border_radius']; ?></option>
                         <option value="<?php echo $border_radius_zero; ?>" <?php echo $border_radius_zero; ?>>0</option>
                         <option value="<?php echo $border_radius_twenty; ?>" <?php echo $border_radius_twenty; ?>>20</option>
                         <option value="<?php echo $border_radius_thirty_five; ?>" <?php echo $border_radius_thirty_five; ?>>35</option>
                         <option value="<?php echo $border_radius_fifty; ?>" <?php echo $border_radius_fifty; ?>>50</option>
                         </select>
		</p>
		
		<p>
			<label for="<?php echo $this->get_field_id( 'selected_url' ); ?>"><?php _e('У вас PRO версия плагина?', 'example'); ?></label><br>
                         <select class="form-control" id="<?php echo $this->get_field_id( 'selected_url' ); ?>" name="<?php echo $this->get_field_name( 'selected_url' ); ?>" value="<?php echo $instance['selected_url']; ?>">
                         <?php
                         $pro_selected = '';
                         $free_selected = '';
                         if( $instance['selected_url'] == $pay_url ) {
                             $pro_selected = 'selected';
                         } elseif( $instance['selected_url'] == $free_url ) {
                             $free_selected = 'selected';
                         }
                         ?>
                         <option style="display: none" value="<?php echo $instance['selected_url']; ?>"><?php echo $instance['selected_url']; ?></option>
                         <option value="<?php echo $pay_url; ?>" <?php echo $pro_selected; ?>>Да, у меня PRO версия</option>
                         <option value="<?php echo $free_url; ?>" <?php echo $free_selected; ?>>Нет, еще думаю</option>
                         </select>
		</p>
        
                         <?php
                         $pro_message = '<div class="pwg_coffee"> <p>У вас активирована PRO версия!</p> <div class="pwg_coffee_wrap"> <a href="mailto:info@wpmoney.ru" target="_blank" class="button button-primary">Есть идеи? Ждем!</a> </div> </div>';
                         $free_message = '<div class="pwg_coffee"> <p>Доступна PRO версия плагина!</p> <div class="pwg_coffee_wrap"> <a class="button button-primary" href="https://wpmoney.ru" target="_blank">Подробнее</a> </div> </div>';
                         if( $instance['selected_url'] == $pay_url ) {
							 echo $pro_message;
                         } elseif( $instance['selected_url'] == $free_url ) {
                             echo $free_message;
                         }
                         ?>
		
	<?php
	}
}
?>