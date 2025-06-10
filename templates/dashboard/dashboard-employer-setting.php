<?php

/**
 * Profile settings
 *
 * @package     Workreap
 * @subpackage  Workreap/templates/dashboard
 * @author      Amentotech <info@amentotech.com>
 * @link        https://codecanyon.net/user/amentotech/portfolio
 * @version     1.0
 * @since       1.0
 */

global $current_user, $workreap_settings, $userdata, $post;

$reference 		 = !empty($_GET['ref']) ? $_GET['ref'] : '';
$mode 			 = !empty($_GET['mode']) ? $_GET['mode'] : '';
$user_identity 	 = intval($current_user->ID);
$user_data_set   = get_userdata($user_identity);
$id 			 = !empty($args['id']) ? $args['id'] : '';
$user_type		 = apply_filters('workreap_get_user_type', $current_user->ID);
$linked_profile	= workreap_get_linked_profile_id($user_identity,'',$user_type);
$user_name		= workreap_get_username($linked_profile);
$profile_id      = workreap_get_linked_profile_id($user_identity, '', $user_type);
$wr_post_meta   = get_post_meta($profile_id, 'wr_post_meta', true);
$wr_post_meta   = !empty($wr_post_meta) ? $wr_post_meta : array();
$country		= get_post_meta($profile_id, 'country', true);
$zipcode		= get_post_meta($profile_id, 'zipcode', true);
$country		= !empty($country) ? $country : '';
$zipcode		= !empty($zipcode) ? $zipcode : '';
$tag_line		= !empty($wr_post_meta['tagline']) ? $wr_post_meta['tagline'] : '';
$first_name		= !empty($wr_post_meta['first_name']) ? $wr_post_meta['first_name'] : '';
$last_name		= !empty($wr_post_meta['last_name']) ? $wr_post_meta['last_name'] : '';
$description	= !empty($wr_post_meta['description']) ? $wr_post_meta['description'] : '';

$first_name			= !empty($first_name) ? $first_name : $user_data_set->first_name;
$last_name			= !empty($last_name) ? $last_name : $user_data_set->last_name;
$countries			= array();

$states					= array();
$state					= get_post_meta($profile_id, 'state', true);
$state					= !empty($state) ? $state : '';
$enable_state			= !empty($workreap_settings['enable_state']) ? $workreap_settings['enable_state'] : false;
$state_country_class	= !empty($enable_state) && empty($country) ? 'd-none' : '';
if (class_exists('WooCommerce')) {
	$countries_obj   = new WC_Countries();
	$countries  	 = $countries_obj->get_allowed_countries('countries');
	if( empty($country) && is_array($countries) && count($countries) == 1 ){
        $country                = array_key_first($countries);
		$state_country_class	= '';
    }
	$states			 = $countries_obj->get_states( $country );
}

$country_class = "form-group";
if(!empty($workreap_settings['enable_zipcode']) ){
	$country_class = "form-group-half";
}

$width			= 315;
$height			= 300;
$avatar	= apply_filters(
    'workreap_avatar_fallback', workreap_get_user_avatar(array('width' => $width, 'height' => $height), $linked_profile), array('width' => $width, 'height' => $height)
);
$enable_ai      = !empty($workreap_settings['enable_ai']) && !empty($workreap_settings['enable_ai_user']) ? true : false;
$ai_classs      = !empty($enable_ai) ? 'wr-input-ai' : '';
?>
<div class="wr-dhb-profile-settings">
	<div class="wr-dhb-mainheading">
		<h2><?php esc_html_e('Profile settings', 'workreap'); ?></h2>
	</div>
	<div class="wr-dhb-box-wrapper">
        <!--Profile Image-->
        <div class="wr-asidebox wr-profile-area-wrapper" id="workreap-droparea">
            <div id="wr-asideprostatusv2" class="wr-asideprostatusv2">
                <?php if( !empty($avatar) ){?>
                    <a id="profile-avatar" href="javascript:void(0);" data-target="#cropimgpopup" data-toggle="modal">
                        <figure>
                            <img id="user_profile_avatar" src="<?php echo esc_url($avatar);?>" alt="<?php echo esc_attr($user_name);?>">
                        </figure>
                    </a>
                <?php } ?>
                <div class="wr-profile-content-area">
                    <h4 class="wr-profile-content-title"><?php esc_html_e('Upload profile photo','workreap'); ?></h4>
                    <p class="wr-profile-content-desc"><?php esc_html_e('Profile image should have jpg, jpeg, gif, png extension and size should not be more than 5MB','workreap'); ?></p>
                    <div class="wr-profilebtnarea-wrapper">
                        <a id="profile-avatar-btn" class="wr-btn" href="javascript:void(0);"><?php esc_html_e('Upload Photo','workreap');?></a>
                    </div>
                </div>
            </div>
        </div>
		<form class="wr-themeform wr-profileform" id="wr_save_settings">
			<fieldset>
				<div class="wr-profileform__holder">
					<div class="wr-profileform__detail wr-billinginfo">
						<div class="form-group-half form-group_vertical">
							<label class="form-group-title"><?php esc_html_e('First name:', 'workreap'); ?><sup class="asterik_red">*</sup></label>
							<input type="text" class="form-control" name="first_name" placeholder="<?php esc_attr_e('Enter first name', 'workreap'); ?>" autocomplete="off" value="<?php echo esc_attr($first_name); ?>">
						</div>
						<div class="form-group-half form-group_vertical">
							<label class="form-group-title"><?php esc_html_e('Last name:', 'workreap'); ?><sup class="asterik_red">*</sup></label>
							<input type="text" class="form-control" name="last_name" placeholder="<?php esc_attr_e('Enter last name', 'workreap'); ?>" autocomplete="off" value="<?php echo esc_attr($last_name); ?>">
						</div>
						
						
						<div class="<?php echo esc_attr($country_class);?> form-group_vertical">
							<label class="form-group-title"><?php esc_html_e('Country', 'workreap'); ?></label>
							<span class="wr-select wr-select-country">
								<select id="wr-category" name="country" data-placeholderinput="<?php esc_attr_e('Search country', 'workreap'); ?>" data-placeholder="<?php esc_attr_e('Choose country', 'workreap'); ?>">
									<option selected hidden disabled value=""><?php esc_html_e('Country', 'workreap'); ?></option>
									<?php if (!empty($countries)) {
										foreach ($countries as $key => $item) {
											$selected = '';
											if (!empty($country) && $country === $key) {
												$selected = 'selected';
											} ?>
											<option <?php echo esc_attr($selected); ?> value="<?php echo esc_attr($key); ?>"><?php echo esc_html($item); ?></option>
									<?php }
									} ?>
								</select>
							</span>
						</div>
						<?php if( !empty($enable_state) ){?>
							<div class="form-group-half form-group_vertical wr-state-parent <?php echo esc_attr($state_country_class);?>">
								<label class="form-group-title"><?php esc_html_e('States', 'workreap'); ?><sup class="asterik_red">*</sup></label>
								<span class="wr-select wr-select-country">
									<select class="wr-country-state" name="state" data-placeholderinput="<?php esc_attr_e('Search states', 'workreap'); ?>" data-placeholder="<?php esc_attr_e('Choose states', 'workreap'); ?>">
										<option selected hidden disabled value=""><?php esc_html_e('States', 'workreap'); ?></option>
										<?php if (!empty($states)) {
											foreach ($states as $key => $item) {
												$selected = '';
												if (!empty($state) && $state === $key) {
													$selected = 'selected';
												} ?>
												<option <?php echo esc_attr($selected); ?> value="<?php echo esc_attr($key); ?>"><?php echo esc_html($item); ?></option>
										<?php }
										} ?>
									</select>
								</span>
							</div>
						<?php } ?>
						<?php if(!empty($workreap_settings['enable_zipcode']) ){?>
							<div class="form-group-half form-group_vertical">
								<label class="form-group-title"><?php esc_html_e('Zip code:', 'workreap'); ?><sup class="asterik_red">*</sup></label>
								<input type="text" class="form-control" name="zipcode" placeholder="<?php esc_attr_e('Add zip code', 'workreap'); ?>" autocomplete="off" value="<?php echo esc_attr($zipcode); ?>">
							</div>
						<?php } ?>

					</div>
				</div>
				<div class="wr-profileform__holder">
					<div class="wr-dhbbtnarea wr-dhbbtnareav2">
						<em><?php esc_html_e('Click “Save & Update” to update the latest changes', 'workreap'); ?></em>
						<a href="javascript:void(0);" data-id="<?php echo intval($user_identity); ?>" class="wr-btn wr_employer_settings"><?php esc_html_e('Save & Update', 'workreap'); ?></a>
					</div>
				</div>
			</fieldset>
		</form>
	</div>
</div>
<?php workreap_get_template_part('profile', 'avatar-popup');