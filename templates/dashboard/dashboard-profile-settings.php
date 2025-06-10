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

$reference 		 = !empty($_GET['ref']) ? esc_html($_GET['ref']) : '';
$mode 			 = !empty($_GET['mode']) ? esc_html($_GET['mode']) : '';
$user_identity 	 = intval($current_user->ID);
$user_data_set   = get_userdata($user_identity);
$id 			 = !empty($args['id']) ? intval($args['id']) : '';
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
$hide_english_level       = !empty($workreap_settings['hide_english_level']) ? $workreap_settings['hide_english_level'] : 'no';
$hide_skills       	  = !empty($workreap_settings['hide_skills']) ? $workreap_settings['hide_skills'] : 'no';
$hide_languages       = !empty($workreap_settings['hide_languages']) ? $workreap_settings['hide_languages'] : 'no';

$freelancer_type_term	= wp_get_object_terms($profile_id, 'freelancer_type');
$freelancer_type		= !empty($freelancer_type_term) ? $freelancer_type_term : array();
$english_level_term	= wp_get_object_terms($profile_id, 'english_level');
$english_level		= !empty($english_level_term) ? $english_level_term : array();
$first_name			= !empty($first_name) ? $first_name : $user_data_set->first_name;
$last_name			= !empty($last_name) ? $last_name : $user_data_set->last_name;
$hourly_rate		= get_post_meta($profile_id, 'wr_hourly_rate', true);
$hourly_rate		= !empty($hourly_rate) ? $hourly_rate : '';
$countries			= array();
$selected_skills	= !empty($profile_id) ? wp_get_post_terms( $profile_id, 'skills', array('fields' =>'ids') ) : array();
$selected_languages = !empty($profile_id) ? wp_get_post_terms( $profile_id, 'languages', array('fields' =>'ids') ) : array();

$states					= array();
$state					= get_post_meta($profile_id, 'state', true);
$state					= !empty($state) ? $state : '';
$enable_state			= !empty($workreap_settings['enable_state']) ? $workreap_settings['enable_state'] : false;
$state_country_class	= !empty($enable_state) && empty($country) ? 'd-none' : '';
if (class_exists('WooCommerce')) {
	$countries_obj   	= new WC_Countries();
	$countries   		= $countries_obj->get_allowed_countries('countries');
	if( empty($country) && is_array($countries) && count($countries) == 1 ){
        $country                = array_key_first($countries);
		$state_country_class	= '';
    }
	$states			 	= $countries_obj->get_states( $country );
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

/* getting freelancer types */
$freelancer_type_data         = workreap_get_term_dropdown('freelancer_type', false, 0, false);
/* getting freelancer types */
$english_level_data       = workreap_get_term_dropdown('english_level', false, 0, false);

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
                    <h4 class="wr-profile-content-title"><?php esc_html_e('Upload profile photo','workreap'); ?><sup class="asterik_red">*</sup></h4>
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
						<div class="form-group form-group_vertical">
							<label class="form-group-title"><?php esc_html_e('Your tagline', 'workreap'); ?></label>
							<input type="text" class="form-control" name="tagline" placeholder="<?php esc_attr_e('Add tagline', 'workreap'); ?>" autocomplete="off" value="<?php echo esc_attr($tag_line); ?>">
						</div>
						<div class="form-group form-group_vertical <?php echo esc_attr($ai_classs);?>">
							<label class="form-group-title"><?php esc_html_e('Description', 'workreap'); ?></label>
							<?php
								if(!empty($enable_ai)){
									do_action( 'workreapAIContent', 'profile_content-'.$profile_id,'profile_content' );
								}
                                $editor_content   	= do_shortcode($description);
                                $editor_id 			= 'profile_content-' . $profile_id;
                                $editor_settings 	= array(
                                    'media_buttons' => false,
                                    'textarea_name' => 'description',
                                    'textarea_rows' => get_option('default_post_edit_rows', 10),
                                    'quicktags' 	=> false,
                                );
                                wp_editor( $editor_content, $editor_id, $editor_settings );
							?>
						</div>
						<div class="<?php echo esc_attr($country_class);?> form-group_vertical">
							<label class="form-group-title"><?php esc_html_e('Country', 'workreap'); ?><sup class="asterik_red">*</sup></label>
							<span class="wr-select wr-select-country">
								<select id="wr-country" class="wr-country" name="country" data-placeholderinput="<?php esc_attr_e('Search country', 'workreap'); ?>" data-placeholder="<?php esc_attr_e('Choose country', 'workreap'); ?>">
									<option selected hidden disabled value=""><?php esc_html_e('Country', 'workreap'); ?></option>
									<?php if (!empty($countries)) {
										foreach ($countries as $key => $item) {
											$selected = '';

											if (!empty($country) && $country === $key) {
												$selected = 'selected';
											}?>
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
												<option class="wr-state-option" <?php echo esc_attr($selected); ?> value="<?php echo esc_attr($key); ?>"><?php echo esc_html($item); ?></option>
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
						<div class="form-group-half form-group_vertical">
							<label class="form-group-title"><?php esc_html_e('Freelancer type', 'workreap'); ?></label>
							<span class="wr-select">
								<select id="freelancer_type" name="freelancer_type" data-placeholderinput="<?php esc_attr_e('Search freelancer type', 'workreap'); ?>" data-placeholder="<?php esc_attr_e('Choose freelancer type', 'workreap'); ?>">
									<option selected hidden disabled value=""><?php esc_html_e('Freelancer type', 'workreap'); ?></option>
									<?php if (is_array($freelancer_type_data) && !empty($freelancer_type_data)) {
										foreach ($freelancer_type_data as $item) {
											$selected = (isset($item->term_id) && ($item->term_id == $freelancer_type[0]->term_id)) ? 'selected' : '';
											?>
											<option value="<?php echo esc_attr($item->term_id); ?>" <?php echo esc_attr($selected); ?>><?php echo esc_html($item->name); ?></option>
									<?php }
									} ?>
								</select>
							</span>
						</div>
						<?php if(!empty($hide_english_level ) && $hide_english_level == 'no'){?>
							<div class="form-group-half form-group_vertical">
								<label class="form-group-title"><?php esc_html_e('English level', 'workreap'); ?></label>
								<span class="wr-select">
									<select id="english_level" name="english_level" data-placeholderinput="<?php esc_attr_e('Choose English level', 'workreap'); ?>"  data-placeholder="<?php esc_attr_e('Choose English level', 'workreap'); ?>">
										<option selected hidden disabled value=""><?php esc_html_e('English level', 'workreap'); ?></option>
										<?php if (is_array($english_level_data) && !empty($english_level_data)) {
										$eng_level_term_id = isset($english_level[0]->term_id) ? $english_level[0]->term_id : '';
											foreach ($english_level_data as $item) {
												$selected = (isset($item->term_id) && ($item->term_id == $eng_level_term_id)) ? 'selected' : '';
												?>
												<option value="<?php echo esc_attr($item->term_id); ?>" <?php echo esc_attr($selected); ?>><?php echo esc_html($item->name); ?></option>
										<?php }
										} ?>
									</select>
								</span>
							</div>
						<?php } ?>
						<?php if(!empty($hide_skills ) && $hide_skills == 'no'){?>
							<div class="form-group form-group_vertical">
								<label class="wr-label"><?php esc_html_e('Skills','workreap');?></label>
								<div class="wr-select">
									<?php
										$skills_args = array(
											'class'         => 'wr-select2-skills',
											'taxonomy'      => 'skills',
											'value_field'   => 'term_id',
											'orderby'       => 'name',
											'name'          => 'skills[]',
											'selected'      => $selected_skills,
										);
										do_action('workreap_custom_taxonomy_dropdown', $skills_args);
									?>
								</div>
							</div>
						<?php } ?>
						<?php if(!empty($hide_languages ) && $hide_languages == 'no'){?>
							<div class="form-group form-group_vertical">
								<label class="wr-label"><?php esc_html_e('Languages, I can speak','workreap');?></label>
								<div class="wr-select">
									<?php
										$languages_args = array(
											'class'         => 'wr-select2-languages',
											'taxonomy'      => 'languages',
											'value_field'   => 'term_id',
											'orderby'       => 'name',
											'name'          => 'languages[]',
											'selected'      => $selected_languages,
										);
										do_action('workreap_custom_taxonomy_dropdown', $languages_args);
									?>
								</div>
							</div>
						<?php } ?>
						<div class="form-group-half form-group_vertical">
							<label class="form-group-title"><?php esc_html_e('Hourly rate', 'workreap'); ?></label>
							<input type="number" class="form-control" name="hourly_rate" placeholder="<?php esc_attr_e('Enter hourly rate', 'workreap'); ?>" autocomplete="off" value="<?php echo esc_attr($hourly_rate); ?>">
						</div>
						<?php do_action('workreap_add_freelancer_profile_fields', $profile_id); ?>
					</div>
				</div>
				<div class="wr-profileform__holder">
					<div class="wr-dhbbtnarea wr-dhbbtnareav2">
						<em><?php esc_html_e('Click “Save & Update” to update the latest changes', 'workreap'); ?></em>
						<a href="javascript:void(0);" data-id="<?php echo intval($user_identity); ?>" class="wr-btn wr_profile_settings"><?php esc_html_e('Save & Update', 'workreap'); ?></a>
					</div>
				</div>
			</fieldset>
		</form>
	</div>
</div>
<?php

workreap_get_template_part('profile', 'avatar-popup');

$scripts	= "
jQuery(document).ready(function($){
    'use strict';

	// Make category drop-down select2 
    if ( $.isFunction($.fn.select2) ) {
        jQuery('.wr-select2-languages').select2({
            theme: 'default wr-select2-dropdown',
            multiple: true,
            placeholder: scripts_vars.languages_option,
            language: {
				noResults: function(){
                    return scripts_vars.select_not_found
                },
                searching: function(){
                    return scripts_vars.select_searching
                },
            }
        });
    }

    if ( $.isFunction($.fn.select2) ) {
        jQuery('.wr-select2-skills').select2({
            theme: 'default wr-select2-dropdown',
            multiple: true,
            placeholder: scripts_vars.skills_option,
            language: {
				noResults: function(){
                    return scripts_vars.select_not_found
                },
                searching: function(){
                    return scripts_vars.select_searching
                },
            }
        });
    }

    });";
    wp_add_inline_script('workreap', $scripts, 'after');