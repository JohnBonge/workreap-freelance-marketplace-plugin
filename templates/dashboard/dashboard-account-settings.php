<?php
/**
 *  Account settings
 *
 * @package     Workreap
 * @subpackage  Workreap/templates/dashboard
 * @author      Amentotech <info@amentotech.com>
 * @link        https://codecanyon.net/user/amentotech/portfolio
 * @version     1.0
 * @since       1.0
*/

global $current_user, $workreap_settings, $userdata, $post;

$reference 		  	= !empty($_GET['ref'] ) ? esc_html($_GET['ref']) : '';
$mode 			    = !empty($_GET['mode']) ? esc_html($_GET['mode']) : '';
$user_identity 		= intval($current_user->ID);
$id 			    = !empty($args['id']) ? intval($args['id']) : '';
$user_type		  	= apply_filters('workreap_get_user_type', $user_identity );
$linked_profile 	= '';
$user_type		  	= apply_filters('workreap_get_user_type', $current_user->ID );
$login_type     	= get_user_meta( $user_identity, 'login_type', true );
$login_type     	= !empty($login_type) ? $login_type : '';
if( function_exists('workreap_get_account_settings') ){
	$linked_profile	= workreap_get_linked_profile_id($user_identity,'',$user_type);
}

$settings		 = array();

if( function_exists('workreap_get_account_settings') ){
	$settings		 = workreap_get_account_settings($user_type);
}

$remove_account_reasons	= !empty($workreap_settings['remove_account_reasons']) ? $workreap_settings['remove_account_reasons'] : array();
?>
<div class="wr-dhb-account-settings">
	<div class="wr-dhb-mainheading">
		<h2><?php esc_html_e('Account Settings','workreap');?></h2>
	</div>
	<?php if( empty($login_type) ){ ?>
	<div class="wr-profile-settings-box wr-chnage-password-wrapper">
		<div class="wr-tabtasktitle">
			<h5><?php esc_html_e('Change password','workreap');?></h5>
		</div>
		<div class="wr-dhb-box-wrapper">
			<div class="wr-themeform wr-profileform">
				<form id="wr_cp_form">
					<div class="wr-profileform__holder">
						<div class="wr-profileform__detail">
							<div class="wr-profileform__content">
								<label class="wr-titleinput"><?php esc_html_e('Current password:','workreap');?></label>
								<input type="password" name="password" class="form-control" placeholder="<?php esc_attr_e('Enter password*','workreap');?>">
							</div>
							<div class="wr-profileform__content">
								<label class="wr-titleinput"><?php esc_html_e('New password:','workreap');?></label>
								<input type="password" name="new_password" class="form-control" placeholder="<?php esc_attr_e('Enter new password*','workreap');?>">
							</div>
							<div class="wr-profileform__content">
								<label class="wr-titleinput"><?php esc_html_e('Retype password:','workreap');?></label>
								<input type="password" name="retype_password" class="form-control" placeholder="<?php esc_attr_e('Retype Password:','workreap');?>">
							</div>
						</div>
					</div>
					<div class="wr-profileform__holder">
						<div class="wr-dhbbtnarea wr-dhbbtnareav2">
							<em><?php esc_html_e('Click “update now” to update latest changes made by you','workreap');?></em>
							<a href="javascript:void(0);" data-id="<?php echo intval($user_identity);?>" id="wr_change_password" class="wr-btn"><?php esc_html_e('Update now ','workreap');?></a>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
	<?php } ?>
</div>
<div class="wr-profile-settings-box wr-privacy-wrapper">
	<?php if( !empty($settings) ){?>
		<div class="wr-tabtasktitle">
			<h5><?php esc_html_e('Privacy &amp; notification','workreap');?></h5>
		</div>
		<div class="wr-dhb-box-wrapper">
			<form id="wr_privacy_form">
				<div class="wr-profileform__holder">
					<div class="wr-profileform__detail">
						<?php foreach( $settings as $key => $value ){
							$db_val 	= get_post_meta($linked_profile, $key, true);
							$db_val 	= !empty( $db_val ) ?  $db_val : 'off';
							?>
							<div class="wr-profileform__content wr-formcheckbox">
								<label class="wr-titleinput"><?php echo esc_html( $value );?></label>
								<div class="wr-onoff">
									<input type="hidden" name="settings[<?php echo esc_attr($key); ?>]" value="off">
									<input type="checkbox" <?php checked( $db_val, 'on' ); ?>  value="on" id="<?php echo esc_attr( $key );?>" name="settings[<?php echo esc_attr( $key );?>]">
									<label for="<?php echo esc_attr( $key );?>"><em><i></i></em><span class="wr-enable"><?php esc_html_e('Enabled','workreap');?></span><span class="wr-disable"><?php esc_html_e('Disabled','workreap');?></span></label>
								</div>
							</div>
						<?php }?>
					</div>
				</div>
				<div class="wr-profileform__holder">
					<div class="wr-dhbbtnarea wr-dhbbtnareav2">
						<em><?php esc_html_e('Click “update now” to update latest changes made by you','workreap');?></em>
						<a href="javascript:void(0);" data-id="<?php echo intval($user_identity);?>" id="wr_update_profile" class="wr-btn"><?php esc_html_e('Update now','workreap');?> </a>
					</div>
				</div>
			</form>
		</div>
	<?php } ?>
</div>
<div class="wr-profile-settings-box wr-deactivate-wrapper">
	<?php if( !empty($remove_account_reasons) ){?>
		<div class="wr-tabtasktitle">
			<h5><?php esc_html_e('Deactivate account','workreap');?></h5>
		</div>
		<div class="wr-dhb-box-wrapper">
			<form id="wr_deactive_form">
				<div class="wr-profileform__holder">
					<div class="wr-profileform__detail">
						<div class="wr-profileform__content">
							<label class="wr-titleinput"><?php esc_html_e('Choose reason:','workreap');?></label>
							<div class="wr-select">
								<select name="reason" id="wr-selection2" class="form-control">
									<option value="select_option"><?php esc_html_e('Why you want to leave?','workreap');?></option>
									<?php foreach($remove_account_reasons as $remove_account_reasons){
										$key =  !empty($remove_account_reasons) ? sanitize_title($remove_account_reasons) : '';?>
										<option value="<?php echo esc_attr($remove_account_reasons);?> "><?php echo esc_html($remove_account_reasons);?></option>
									<?php } ?>
								</select>
							</div>
						</div>
						<div class="wr-profileform__content">
							<label class="wr-titleinput"><?php esc_html_e('Add description:','workreap');?></label>
							<textarea class="form-control" name="details" placeholder="<?php esc_attr_e('Description','workreap');?>"></textarea>
						</div>
					</div>
				</div>
				<div class="wr-profileform__holder">
					<div class="wr-dhbbtnarea wr-dhbbtnareav2">
						<em><?php esc_html_e('Click “deactivate now” to disable your account permanently','workreap');?></em>
						<a href="javascript:void(0);" data-id="<?php echo intval($user_identity);?>" id="wr_deactive_profile" class="wr-btn wr-deactivate"><?php esc_html_e('Deactivate now','workreap');?></a>
					</div>
				</div>
			</form>
		</div>
	<?php } ?>
</div>
	