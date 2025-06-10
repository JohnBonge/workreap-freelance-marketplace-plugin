<?php
/**
 * Dashboard Notifications
 *
 * @package     Workreap
 * @subpackage  Workreap/templates/dashboard
 * @author      Amentotech <info@amentotech.com>
 * @link        https://codecanyon.net/user/amentotech/portfolio
 * @version     1.0
 * @since       1.0
*/
global $current_user;
$user_id 	 				= intval($current_user->ID);
$user_verification			= get_user_meta( $user_id, 'user_verification', true );
$identity_verified			= !empty($user_verification) ? $user_verification : '';

$verification_attachments  	= get_user_meta($user_id, 'verification_attachments', true);
$verification_attachments	= !empty($verification_attachments) ? $verification_attachments : array();

$identity_verified  	= get_user_meta($user_id, 'identity_verified', true);
$identity_verified		= !empty($identity_verified) ? $identity_verified : 0;
?>
<div class="wr-dhb-profile-settings">
	<div class="wr-dhb-mainheading">
		<h2><?php esc_html_e('Upload Identity Information', 'workreap'); ?></h2>
	</div>
	
	<?php if(empty($identity_verified) && !empty($verification_attachments) ){?>
		<div class="wr-refunddetailswrap wr-alert-information">
			<div class="wr-orderrequest">
				<div class="wr-ordertitle">
					<h5><?php esc_html_e('Woohoo!', 'workreap'); ?></h5>
					<p><?php esc_html_e('You have successfully submitted your documents. buckle up, we will verify and respond to your request very soon', 'workreap'); ?></p>
				</div>
				<div class="wr-orderbtn">
					<a class="wr-btn btn-orange wr-cancel-identity" href="javascript:;"><?php esc_html_e("Cancel & Re-Upload", 'workreap'); ?></a>
				</div>
			</div>
		</div>
	<?php }else if(!empty($identity_verified) && $identity_verified === '1'){?>
		<div class="wr-orderrequest wr-alert-success">
			<div class="wr-ordertitle">
				<h5><?php esc_html_e('Hurray!', 'workreap'); ?></h5>
				<p><?php esc_html_e('We have successfully completed your identity verification. you’re now ready to use site features', 'workreap');?></p>
			</div>
		</div>
	<?php }else{?>
		<div class="wr-dhb-box-wrapper">
			<form class="wr-themeform wr-profileform" id="wr_identity_settings">
				<fieldset>
					<div class="wr-profileform__holder">
						<div class="wr-profileform__detail wr-billinginfo">
							<div class="form-group-half form-group_vertical">
								<label class="form-group-title"><?php esc_html_e('Your name:', 'workreap'); ?></label>
								<input type="text" value="" name="name" class="form-control" placeholder="<?php esc_attr_e('Your name', 'workreap'); ?>">
							</div>
							<div class="form-group-half form-group_vertical">
								<label class="form-group-title"><?php esc_html_e('Contact number:', 'workreap'); ?></label>
								<input type="text" value="" name="contact_number" class="form-control" placeholder="<?php esc_attr_e('Contact number', 'workreap'); ?>">
							</div>
							<div class="form-group form-group_vertical">
								<label class="form-group-title"><?php esc_html_e('National identity card, passport or driving license number:', 'workreap'); ?></label>
								<input type="text" value="" name="verification_number" class="form-control" placeholder="<?php esc_attr_e('National identity card, passport or driving license number', 'workreap'); ?>">
							</div>
							<div class="form-group form-group_vertical">
								<label class="form-group-title"><?php esc_html_e('Add address:', 'workreap'); ?></label>
								<textarea class="form-control" name="address" placeholder="<?php esc_attr_e('Add address', 'workreap'); ?>"></textarea>
							</div>
							<div class="form-group">
								<div id="workreap-upload-verification" class="workreap-fileuploader wr-uploadarea">
									<div class="wr-uploadbox workreap-dragdroparea" id="workreap-verification-droparea">
										<svg>
											<rect width="100%" height="100%"/>
										</svg>
										<i class="wr-icon-upload"></i>
										<em>
											<?php echo wp_sprintf( '%1$s <br/> %2$s', esc_html__( 'You can upload media file format only.', 'workreap'), esc_html__( 'make sure your file size does not exceed 15mb.', 'workreap') );?>
											<label for="file1">
												<span id="workreap-verification-btn">
													<input id="file1" type="file" name="file">
													<?php esc_html_e('Click here to upload', 'workreap');?>
												</span>
											</label>
										</em>
									</div>
									<ul class="wr-uploadbar wr-bars workreap-fileprocessing" id="workreap-fileprocessing"></ul>
								</div>
							</div>
						</div>
					</div>
					<div class="wr-profileform__holder">
						<div class="wr-dhbbtnarea wr-dhbbtnareav2">
							<em><?php esc_html_e('Click “Save & Update” to update the latest changes', 'workreap'); ?></em>
							<a href="javascript:void(0);" data-id="<?php echo intval($user_id); ?>" class="wr-btn wr_profile_verification"><?php esc_html_e('Save & Update', 'workreap'); ?></a>
						</div>
					</div>
				</fieldset>
			</form>
		</div>
	<?php } ?>
	
</div>
<script type="text/template" id="tmpl-load-verification-attachments">
	<li id="thumb-{{data.id}}" class="workreap-list wr-uploading">
		<div class="wr-filedesciption">
			<span>{{data.name}}</span>
			<input type="hidden" class="attachment_url" name="attachments[{{data.attachment_id}}]" value="{{data.url}}">
			<em class="wr-remove"><a href="javascript:void(0)" class="workreap-remove-attachment wr-remove-attachment"><?php esc_html_e('remove', 'workreap');?></a></em>
		</div>
		<div class="progress">
			<div class="progress-bar uploadprogressbar" style="width:0%"></div>
		</div>
	</li>
</script>
