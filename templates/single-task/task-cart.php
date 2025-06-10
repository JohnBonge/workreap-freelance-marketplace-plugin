<?php
/**
 * Single task author details
 *
 * @link       https://codecanyon.net/user/amentotech/portfolio
 * @since      1.0.0
 *
 * @package    Workreap
 * @subpackage Workreap_/public
 */
$plans			= !empty($workreap_plans_values) ? $workreap_plans_values : array();
$total_price	= 0;
$select_options	= '';

if( !empty($plans) ){
	$counter	= 0;
	foreach($plans as $key => $plan ){
		$title			= !empty($plan['title']) ? $plan['title'] : '';
		$price			= !empty($plan['price']) ? $plan['price'] : '';
		$selected		= '';
		
		if( empty($counter) ){
			$total_price	= $price;
			$selected		= 'selected';
		}

		if( !empty($title)){
			$select_options .= '<option id="wr-op-'.esc_attr($key).'" '.esc_attr($selected).' value="'.esc_attr($key).'" data-price="'.esc_attr($price).'">'.esc_html($title).'</option>';
		}

		$counter++;
	}
}
?>
<div class="wr-fixsidebar" id="wr-fixsidebar" style="display:none;">
	<div class="wr-messageuser">
		<div class="wr-tasktotalbudget">
			<div class="wr-tasktotal">
				<h5><?php esc_html_e('Add task details','workreap');?></h5>
			</div>
			<a href="javascript:void(0);" class="close"><i class="wr-icon-x" data-bs-dismiss="modal"></i></a>
		</div>
	</div>
	<div class="wr-addformtask">
		<div class="wr-packages__plan">
			<h6><em data-tippy-content="<?php esc_attr_e('Attachments','workreap');?>" class="tippy wr-icon-info"></em> <?php esc_html_e('Total task budget','workreap');?></h6>
			<h4 id="wr_total_price"><?php workreap_price_format($total_price);?></h4>
		</div>
		<form class="wr-themeform wr-sidebarform" id="wr_cart_form">
			<fieldset>
				<div class="form-group-wrap">
					<div class="form-group form-vertical">
						<label class="wr-titleinput"><?php esc_html_e('Selected service:','workreap');?></label>
						<input type="text" class="form-control disable" placeholder="<?php echo esc_attr($product->get_title());?>">
					</div>
					<div class="form-group form-vertical">
						<label class="wr-titleinput"><?php esc_html_e('Choose package plan:','workreap');?></label>
						<div class="wr-select">
							<select class="form-control wr_project_task" id="wr_task_cart" data-task_id="<?php echo intval($task_id);?>" name="product_task">
								<?php echo do_shortcode( $select_options );?>
							</select>
						</div>
					</div>
					<?php if(!empty($workreap_subtask)){?>
						<div class="form-group form-vertical">
							<label class="wr-titleinput"><?php esc_html_e('Choose addtional service:','workreap');?></label>
							<ul class="wr-additionalchecklist mCustomScrollbar">
							<?php 
								foreach($workreap_subtask as $key => $workreap_subtask_id){
									$subtask_price 	= wc_get_product( $workreap_subtask_id );
									$subtask_price	= !empty($subtask_price) ? $subtask_price->get_regular_price() : ''; ?>
									<li>
										<div class="wr-additionalservices__content">
											<div class="wr-checkbox">
												<input name="subtasks[]" class="wr_subtask_check" data-id="<?php echo intval($workreap_subtask_id);?>" id="additionalservice-<?php echo intval($workreap_subtask_id);?>" type="checkbox" data-price="<?php echo esc_attr($task_id);?>" value="<?php echo intval($workreap_subtask_id);?>">
												<label for="additionalservice-<?php echo intval($workreap_subtask_id);?>">
													<span><?php echo get_the_title($workreap_subtask_id);?></span>
													<?php if( !empty($subtask_price) ){?>
														<em> ( +<?php workreap_price_format($subtask_price);?> )</em>
													<?php } ?>
											</label>
											</div>
											<p><?php echo apply_filters( 'the_content', get_the_content(null, false, $workreap_subtask_id));?></p>
										</div>
									</li>
								<?php  }?>
							</ul>    
						</div>
					<?php } ?>
					<div class="form-group m-0">
						<div class="wr-popupbtnarea">
							<a href="javascript:void(0);" data-id="<?php echo intval($task_id);?>" class="wr-btn" id="wr_btn_cart"><?php esc_html_e('Hire now','workreap');?><span class="rippleholder wr-jsripple"><em class="ripplecircle"></em></span></a>
						</div>
					</div>
				</div>
			</fieldset>
		</form>
	</div>
</div>