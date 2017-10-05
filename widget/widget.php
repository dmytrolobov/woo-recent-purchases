<?php if ( ! defined( 'ABSPATH' ) ) exit;
	/**
		* Widget
		*
		* @package     
		* @subpackage  
		* @copyright   Copyright (c) 2017, Dmytro Lobov
		* @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
		* @since       1.0
	*/
	class WOW_WOO_RECENT_PURCHASES_WIDGET extends WP_Widget {	
		
		
		/**
			* Register widget with WordPress.
		*/
		function __construct() {			
			parent::__construct(
			'wow_woo_recent_purchases', // Base ID
			'Woocommerce Recent Purchases', // Name
			array( 'description' => 'Display Woocommerce recent purchases', ) // Args
			);
			
			
		}
		
		/**
			* Front-end display of widget.
			*
			* @see WP_Widget::widget()
			*
			* @param array $args     Widget arguments.
			* @param array $instance Saved values from database.
		*/
		public function widget( $args, $instance ) {	
			$args['id']        = ( isset( $args['id'] ) ) ? $args['id'] : 'wow_edd_purchases_notifications';
			$instance['title'] = ( isset( $instance['title'] ) ) ? $instance['title'] : '';
			$title = apply_filters( 'widget_title', $instance['title'], $instance, $args['id'] );
			
			$param = get_option('woo_recent_purchases');
			
			$quantity = isset( $param['widget_quntity'] ) ? $param['widget_quntity'] : '5';
			
			$notification = isset( $param['widget'] ) ? $param['widget'] : "{fname} {lname} purchased {product} for {price} {time} ago";
			
			$img = isset( $param['widget_img'] ) ? $param['widget_img'] : 'none'; 
			$img_size = isset( $param['widget_img_size'] ) ? $param['widget_img_size'] : '32';
			
			
			$articles = array(
			'numberposts'      => $quantity,
			'post_status'      => 'wc-completed',			
			'post_type'        => 'shop_order',
			'suppress_filters' => true, 
			);						
			$payments = get_posts( $articles );
			
						
			$out = null;
			if($payments){
				$out .= '<ul class="wow_woo_recent_purchases_widget">';			
				foreach ( $payments as $payment ) { 
					setup_postdata($payment);
					$order = new WC_Order($payment->ID);
					$fname = $order->get_billing_first_name();
					$lname = $order->get_billing_last_name();							
					$date = $order->get_date_completed();
					$time = human_time_diff( strtotime($date), current_time('timestamp') );
					$user_id = $order->get_user_id();
					$products = $order->get_items();
					$product_ids = array();					
					foreach($products as $product){
						$product_ids[] = $product['product_id'];
					}					
					$product_ids = array_unique($product_ids);
					$item_id = $product_ids[0];
					$product = wc_get_product( $item_id );					 
					$price = $product->get_price_html();
					$url = get_permalink($item_id);				
					$download = '<a href="'.$url.'">'.$product->get_title().'</a>';					
					$message = $notification;
					$message = str_replace( '{fname}', $fname, $message );
					$message = str_replace( '{lname}', $lname, $message );
					$message = str_replace( '{product}', $download, $message );
					$message = str_replace( '{price}', $price, $message );
					$message = str_replace( '{time}', $time, $message );										
					if($img == 'download'){
						
						$image = get_the_post_thumbnail( $item_id, array($img_size,$img_size), array('class' => 'alignleft') );
						$image = '<a href="'.$url.'">'.$image.'</a>';
					}
					elseif($img == 'avatar'){
						$url = get_avatar_url( $user_id, array('size' => $img_size,'default'=>'monsterid',) );
						$image = '<img src="'. $url .'" class="alignleft" width="'.$img_size.'">';														
					}
					else {
						$image = null;
					}
					
					$out .=  '<li>'.$image.' '.$message.'</li>';
				}
				wp_reset_postdata();
				$out .= '</ul>';
				
				
			}
			
			echo $args['before_widget'];
			if ( $title ) {
				echo $args['before_title'] . $title . $args['after_title'];
			}
			echo $out;
			echo $args['after_widget'];
			
		}
		
		/**
			* Back-end widget form.
			*
			* @see WP_Widget::form()
			*
			* @param array $instance Previously saved values from database.
		*/
		public function form( $instance ) {		
		?>		
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php _e( 'Title:', 'edd-notifications' ); ?></label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php if(!empty($instance['title']))echo $instance['title']; ?>"/>
			
		</p>
		<?php 
		}
		
		/**
			* Sanitize widget form values as they are saved.
			*
			* @see WP_Widget::update()
			*
			* @param array $new_instance Values just sent to be saved.
			* @param array $old_instance Previously saved values from database.
			*
			* @return array Updated safe values to be saved.
		*/
		public function update( $new_instance, $old_instance ) {
			$instance = array();
			$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
			
			
			return $instance;
		}
		
	} // class Foo_Widget					
	
	function woo_recent_purchases_pro_widget() {
		register_widget( 'WOW_WOO_RECENT_PURCHASES_WIDGET' );	
	}
add_action( 'widgets_init', 'woo_recent_purchases_pro_widget' );