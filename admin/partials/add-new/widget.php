<?php if ( ! defined( 'ABSPATH' ) ) exit; 
	/**
		* Widget
		*
		* @package     
		* @subpackage  Settings
		* @copyright   Copyright (c) 2017, Dmytro Lobov
		* @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
		* @since       1.0
	*/
	
	include ('settings/widget.php');	
?>
<div class="itembox">
	<div class="item-title">
		<h3>Widget Settings</h3>		
		<div class="wow-admin-col">
			<div class="wow-admin-col-4">
				Quantity of Notifications<br/>
				<?php echo self::create_option($widget_quntity);?>				
			</div>
			<div class="wow-admin-col-4"></div>
			<div class="wow-admin-col-4"></div>
		</div>
		
		<div class="wow-admin-col">
			<div class="wow-admin-col-12">
				<?php echo self::create_option($widget);?>
			</div>
		</div>
		<div class="wow-admin-col">
			<div class="wow-admin-col-12">
				<i>{fname}</i> - The buyer's first name<br/>
			<i>{lname}</i> - The buyer's last name<br/>
			<i>{product}</i> - The buyer's product<br/>
			<i>{price}</i> - The price of product<br/>
			<i>{time}</i> - Time ago when product was purchase	
			</div>
		</div>
	</div>	
</div>

<div class="itembox">
	<div class="item-title">
		<h3>Image</h3>
		
		<div class="wow-admin-col">
			<div class="wow-admin-col-4">
				Download Image<br/>
				<?php echo self::create_option($widget_img);?>				
			</div>
			<div class="wow-admin-col-4">
				Image size<br/>
				<?php echo self::create_option($widget_img_size);?>
			</div>
			<div class="wow-admin-col-4"></div>
		</div>		
	</div>	
	</div>