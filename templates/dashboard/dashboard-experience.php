<?php
/**
 * User education
 *
 * @package     Workreap
 * @subpackage  Workreap/templates/dashboard
 * @author      Amentotech <info@amentotech.com>
 * @link        https://codecanyon.net/user/amentotech/portfolio
 * @version     1.0
 * @since       1.0
*/

global $current_user, $workreap_settings, $userdata, $post;

$reference 		 = !empty($_GET['ref'] ) ? esc_html($_GET['ref']) : '';
$mode 			 = !empty($_GET['mode']) ? esc_html($_GET['mode']) : '';
$user_identity 	 = intval($current_user->ID);
$id 			 = !empty($args['id']) ? intval($args['id']) : '';
$user_type		 = apply_filters('workreap_get_user_type', $current_user->ID );
$profile_id      = workreap_get_linked_profile_id($user_identity,'',$user_type);
$user_type		 = apply_filters('workreap_get_user_type', $user_identity );
$date_format	 = get_option( 'date_format' );
$wr_post_meta   = get_post_meta( $profile_id,'wr_post_meta',true );
$wr_post_meta   = !empty($wr_post_meta) ? $wr_post_meta : array();
$experiences     	= !empty($wr_post_meta['experience']) ? $wr_post_meta['experience'] : array();
$experience_array	= array();
$countries  = workreap_get_countries();
?>
<div class="wr-dhb-profile-settings wr-education-wrapper">
	<div class="wr-tabtasktitle">
		<h5><?php esc_html_e('Experience details','workreap');?></h5>
		<div class="wr-profileform__title--rightarea">
			<a href="javascript:void(0);" data-type="add" class="wr_show_experience"><?php esc_html_e('Add new','workreap');?></a>
		</div>
	</div>
	<div class="wr-dhb-box-wrapper">
		<div class="wr-themeform wr-profileform">
			<fieldset>
				<div class="wr-profileform__holder">
					<?php if( !empty($experiences) ){?>
						<ul class="wr-detail wr-educationdetail">
							<?php 
							foreach($experiences as $key => $value ){
								$job_title		= !empty($value['job_title']) ? $value['job_title'] : '';
								$company		= !empty($value['company']) ? $value['company'] : '';
								$location		= !empty($value['location']) ? $value['location'] : '';
								$startdate 		= !empty( $value['start_date'] ) ? $value['start_date'] : '';
								$enddate 		= !empty( $value['end_date'] ) ? $value['end_date'] : '';
								$description 	= !empty( $value['description'] ) ? wp_kses_post( stripslashes( $value['description'] ) ) : '';
								$start_date 	= !empty( $startdate ) ? date_i18n($date_format, strtotime(apply_filters('workreap_date_format_fix',$startdate ))) : '';
								$end_date 		= !empty( $enddate ) ? date_i18n($date_format, strtotime(apply_filters('workreap_date_format_fix',$enddate ))) : '';
								$location_val	= !empty($location) && !empty($countries[$location]) ? $countries[$location] : '';
								if( empty( $end_date ) ){
									$end_date = '';
								} else {
									$end_date	= ' - '.$end_date;
								}

								if( !empty( $start_date ) ){
									$period = $start_date.$end_date;
								}
								if(!empty($location_val)){
									$company	= $company.' ('.$location_val.')';
								}

								if( !empty($period) ){
									$company	= $company.' - '.$period;
								}

								$experience_array[$key]	= $value;
								?>
								<li>
									<div class="wr-detail__content">
										<div class="wr-detail__title">
											<?php if( !empty($company) ){?>
												<span><?php echo esc_html($company);?></span>
											<?php } ?>
											<?php if( !empty($job_title) ){ ?>
												<h6><a href="javascript:void(0);"><?php echo esc_html($job_title);?></a></h6>
											<?php } ?>
										</div>
										<div class="wr-detail__icon">
											<a href="javascript:void(0);" data-id="<?php echo intval($user_identity);?>" data-type="edit" data-key="<?php echo intval($key);?>" class="wr-edit wr_show_experience"><i class="wr-icon-edit-2"></i></a>
											<a href="javascript:void(0);" data-id="<?php echo intval($user_identity);?>" data-key="<?php echo intval($key);?>" class="wr-delete wr_remove_exp"><i class="wr-icon-trash-2"></i></a>
										</div>
									</div>
								</li>
							<?php } ?>
						</ul>
					<?php } ?>
				</div>
			</fieldset>
		</div>
	</div>
</div>
<script>
	var profile_experience = [];
	window.profile_experience	= <?php echo json_encode($experience_array); ?>
</script>
<script type="text/template" id="tmpl-load-experience">
	<form class="wr-themeform wr-formlogin" id="wr_update_experience">
		<fieldset>
			<div class="form-group">
				<label class="form-group-title"><?php esc_html_e('Add job title :','workreap');?></label>
				<input type="text" name="experience[{{data.counter}}][job_title]" value="{{data.job_title}}" class="form-control" placeholder="<?php esc_attr_e('Add job title','workreap');?>" autocomplete="off">
			</div>
			<div class="form-group">
				<label class="form-group-title"><?php esc_html_e('Add company name :','workreap');?></label>
				<input type="text" name="experience[{{data.counter}}][company]" value="{{data.company}}" class="form-control" placeholder="<?php esc_attr_e('Add company name','workreap');?>" autocomplete="off">
			</div>
			<div class="form-group">
				<?php 
					if (is_array($countries) && !empty($countries)) { ?>
						<span class="wr-select">
							<select id="experience_location" name="experience[{{data.counter}}][location]" data-placeholderinput="<?php esc_attr_e('Search location', 'workreap'); ?>" data-placeholder="<?php esc_attr_e('Select location', 'workreap'); ?>" class="form-control wr-company-select-single">
								<option value=""><?php esc_html_e('Search Company location','workreap');?></option>
								<?php foreach ($countries as $key => $item) { ?>
									<option value="<?php echo esc_attr($key); ?>"><?php echo esc_html($item); ?></option>
								<?php } ?>
							</select>
						</span>
				<?php } ?>
			</div>
			<div class="form-group">
				<label class="form-group-title"><?php esc_html_e('Choose date','workreap');?></label>
				<div class="wr-themeform__wrap">
					<div class="form-group wr-combine-group">
						<div class="wr-calendar">
							<input id="edu_start_date" value="{{data.start_date}}" name="experience[{{data.counter}}][start_date]" type="text" class="form-control dateinit-{{data.counter}}wr-start-pick" placeholder="<?php esc_attr_e('Date from','workreap');?>">
						</div>
						<div class="wr-calendar">
							<input id="edu_end_date" value="{{data.end_date}}" name="experience[{{data.counter}}][end_date]" type="text" class="form-control dateinit-{{data.counter}}wr-end-pick" placeholder="<?php esc_attr_e('Date to','workreap');?>">
						</div>
					</div>
				</div>
			</div>
			<div class="form-group">
				<label class="form-group-title"><?php esc_html_e('Add description:','workreap');?></label>
				<textarea class="form-control"  name="experience[{{data.counter}}][description]" placeholder="<?php esc_attr_e('Description','workreap');?>">{{{data.description}}}</textarea>
			</div>
			<div class="form-group wr-form-btn">
				<div class="wr-savebtn">
					<em><?php esc_html_e('Click “Save & Update” to update your experience details','workreap');?></em>
					<a href="javascript:void(0);" data-mode="{{data.mode}}" data-key="{{data.key}}" data-id="<?php echo intval($user_identity);?>" id="wr_add_experience" class="wr-btn"><?php esc_html_e('Save & Update','workreap');?></a>
				</div>
			</div>
		</fieldset>
	</form>
</script>
<div class="modal fade wr-educationpopup" id="wr_experiencedetail" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog wr-modaldialog" role="document">
		<div class="modal-content">
			<div class="wr-popuptitle">
				<h4><?php esc_html_e('Add/edit experience details','workreap');?></h4>
				<a href="javascript:void(0);" class="close"><i class="wr-icon-x" data-bs-dismiss="modal"></i></a>
			</div>
			<div class="modal-body" id="wr_add_experience_frm"></div>
		</div>
	</div>
</div>
<?php
$counter = 0;
