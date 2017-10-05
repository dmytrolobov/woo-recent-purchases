<?php if ( ! defined( 'ABSPATH' ) ) exit;
	/**
		* Public Class
		*
		* @package     WOW_WOO_RECENT_PURCHASES_SHORTCODE
		* @subpackage  
		* @copyright   Copyright (c) 2017, Dmytro Lobov
		* @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
		* @since       1.0
	*/
	class WOW_WOO_RECENT_PURCHASES_SHORTCODE {
		
		private $arg;
		
		public function __construct( $arg ) {
			$this->plugin_name      = $arg['plugin_name'];
			$this->plugin_menu      = $arg['plugin_menu'];
			$this->version          = $arg['version'];
			$this->pref             = $arg['pref'];			
			$this->slug             = $arg['slug'];
			$this->plugin_dir       = $arg['plugin_dir'];
			$this->plugin_url       = $arg['plugin_url'];
			$this->plugin_home_url  = $arg['plugin_home_url'];
			$this->shortcode        = $arg['shortcode'];			
			// admin pages
			add_shortcode($this->shortcode, array($this, 'shortcode') );			
		}
		
		// Show on Front end
		function shortcode($atts) {	
			$param = get_option($this->pref);			
			$quantity = isset( $param['quntity'] ) ? $param['quntity'] : '5';
			$notification = isset( $param['content'] ) ? $param['content'] : "{fname} {lname} purchased {product} for {price} {time} ago";
			
			$img = isset( $param['shortcode_img'] ) ? $param['shortcode_img'] : 'none'; 
			$img_size = isset( $param['shortcode_img_size'] ) ? $param['shortcode_img_size'] : '32';
			
			$args = array(
			'numberposts'      => $quantity,
			'post_status'      => 'wc-completed',			
			'post_type'        => 'shop_order',
			'suppress_filters' => true, 
			);						
			$payments = get_posts( $args );
			
						
			$out = null;
			if($payments){
				$out .= '<ul class="wow_woo_recent_purchases">';			
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
			return $out;
		}
		
	}					