<?php
/**
*  Project basic
*
* @package     Workreap
* @author      Amentotech <info@amentotech.com>
* @link        https://codecanyon.net/user/amentotech/portfolio
* @version     1.0
* @since       1.0
*/
global $workreap_settings,$current_user;
if ( !class_exists('WooCommerce') ) {
	return;
}

$post_id            = !empty($post_id) ? intval($post_id) : "";
$step_id            = !empty($step) ? intval($step) : "";
$project_types      = workreap_project_type();
$hide_product_cat   = !empty($workreap_settings['hide_product_cat']) ? $workreap_settings['hide_product_cat'] : array();
$enable_zipcode     = !empty($workreap_settings['enable_zipcode']) ? $workreap_settings['enable_zipcode'] : "";
$enable_milestone_feature       = !empty($workreap_settings['enable_milestone_feature']) ? $workreap_settings['enable_milestone_feature'] : 'yes';
$project_multilevel_cat         = !empty($workreap_settings['project_multilevel_cat']) ? $workreap_settings['project_multilevel_cat'] : 'disable';

$project_meta       = !empty($product) ? get_post_meta( $product->get_id(), 'wr_project_meta', true ) : array();
$project_meta       = !empty($project_meta) ? $project_meta : array();

$selected_job_type  = !empty($project_meta['project_type']) ? $project_meta['project_type'] : "fixed";
$product_cat        = !empty($product) ? get_the_terms( $product->get_id(), 'product_cat' ) : array();
$selected_category  = !empty($product_cat[0]->term_id) ? intval($product_cat[0]->term_id) : 0;
$downloadable_files = get_post_meta($post_id, '_downloadable_files', true);
$videourl           = !empty($project_meta['video_url']) ? $project_meta['video_url'] : "";

$project_upload_area   = '';
if(!empty($downloadable_files) && is_array($downloadable_files) && count($downloadable_files) >= 3 ){
    $project_upload_area   = 'style="display: none;"';
}
$min_price          = "";
$max_price          = "";
if( !empty($selected_job_type) && $selected_job_type === 'fixed' ){
    $min_price  = !empty($project_meta['min_price']) ? $project_meta['min_price'] : "";
    $max_price  = !empty($project_meta['max_price']) ? $project_meta['max_price'] : "";
} else {
    $min_price  = !empty($project_meta['min_price']) ? $project_meta['min_price'] : "";
    $max_price  = !empty($project_meta['max_price']) ? $project_meta['max_price'] : "";
}
$duration           = !empty($product) ? get_the_terms( $product->get_id(), 'duration' ) : array();
$selected_duration  = !empty($duration[0]->term_id) ? intval($duration[0]->term_id) : 0;
$selected_country   = !empty($project_meta['country']) ? $project_meta['country'] : '';
$zipcode            = !empty($project_meta['zipcode']) ? $project_meta['zipcode'] : '';
$description        = !empty($product) ? $product->get_description() : "";
$hourly_class       = !empty($selected_job_type) && $selected_job_type === 'hourly' ? "wr-hourly-type" : "wr-hourly-type d-none";
$fixed_class        = !empty($selected_job_type) && $selected_job_type === 'fixed' ? "wr-fixed-type" : "wr-fixed-type d-none";
$milestone_checked  = "";
if( !empty($project_meta['project_type']) && $project_meta['project_type'] === 'fixed' ){
    if( !empty($project_meta['is_milestone']) && $project_meta['is_milestone'] === 'yes'){
        $milestone_checked  = "checked";
    }
}

$title                      = !empty($product) ? $product->get_name() : '';
$project_location_types     = workreap_project_location_type();
$selected_location          = !empty($product) ? get_post_meta( $product->get_id(), '_project_location',true ) : '';
$selected_location          = !empty($selected_location) ? $selected_location : '';
$location_class             = 'wr-loaction-type d-none';

$states				    = array();
$state				    = !empty($project_meta['state']) ? $project_meta['state'] : '';
$enable_state		    = !empty($workreap_settings['enable_state']) ? $workreap_settings['enable_state'] : false;
$state_country_class	= !empty($enable_state) && empty($selected_country) ? 'd-none' : '';
if (class_exists('WooCommerce')) {
	$countries_obj   	= new WC_Countries();
	$countries   		= $countries_obj->get_allowed_countries('countries');
    $country            = !empty($selected_country) ? $selected_country : '';
    if( empty($country) && is_array($countries) && count($countries) === 1 ){
        $country                = array_key_first($countries);
        $selected_country       = $country;
        $state_country_class    = '';
    }
	$states			 	= $countries_obj->get_states( $country );
}
if( !empty($selected_location) && $selected_location === 'location'){
    $location_class         = 'wr-loaction-type';
}

$sub_cat            = '';
$sub_cat2           = '';

$cat_class          = 'wr-select-cat';
if( !empty($project_multilevel_cat) && $project_multilevel_cat === 'enable' ){
    $cat_class          = 'wr-select-cat wr-top-service';
    $selected_category	= get_post_meta($post_id, '_cat', true);
    $sub_cat            = get_post_meta($post_id, '_sub_cat', true);
    $sub_cat2           = get_post_meta($post_id, '_cat_type', true);
    $sub_cat            = !empty($sub_cat) ? $sub_cat : '';
    $sub_cat2           = !empty($sub_cat2) ? $sub_cat2 : '';
}
$enable_ai      = !empty($workreap_settings['enable_ai']) && !empty($workreap_settings['enable_ai_job']) ? true : false;
$ai_classs      = !empty($enable_ai) ? 'wr-input-ai' : '';
?>

<div class="row">
    <?php do_action( 'workreap_project_sidebar', $step_id,$post_id );?>
    <div class="col-lg-8 col-xl-9">
        <div class="wr-project-wrapper wr-aboutprojectstep">
            <div class="wr-project-box">
                <div class="wr-maintitle">
                    <h4><?php esc_html_e('Tell us about your project','workreap');?></h4>
                </div>
                <form class="wr-themeform wr-project-form" id="service-media-attachments-form">
                    <fieldset>
                        <div class="wr-themeform__wrap">
                            <div class="form-group <?php echo esc_attr($ai_classs);?>">
                                <label class="wr-label"><?php esc_html_e('Add your project title','workreap');?></label>
                                <?php
                                    if(!empty($enable_ai)){
                                        do_action( 'workreapAIContent', 'job_title-'.$post_id,'job_title' );
                                    }
                                ?>
                                <div class="wr-placeholderholder">
                                    <input type="text" data-ai_content_id="job_title-<?php echo esc_attr($post_id);?>" name="title" class="form-control wr-themeinput" value="<?php echo esc_attr($title);?>" placeholder="<?php esc_attr_e('Enter your project title','workreap');?>">
                                </div>
                            </div>
                            <div class="form-group">
                               
                                <ul class="nav nav-tabs wr-nav-tabs">
                                    <?php foreach($project_types as $key => $project_type){
                                        $title          = !empty($project_type['title']) ? $project_type['title'] : "";
                                        $details        = !empty($project_type['details']) ? $project_type['details'] : "";
                                        $icon           = !empty($project_type['icon']) ? $project_type['icon'] : "";

                                        $checked        = "";
                                        $active_class   = "";
                                        if( !empty($selected_job_type) && $selected_job_type === $key ){
                                            $checked        = "checked";
                                            $active_class   = "wr-active-option";
                                        }
                                    ?>
                                    <li class="<?php echo do_shortcode($active_class);?> wr-li-<?php echo esc_attr($key);?>">
                                        <input <?php echo esc_attr($checked);?> type="radio" id="<?php echo esc_attr($key);?>" name="project_type" value="<?php echo esc_attr($key);?>">
                                        <label class="wr-project-type" for="<?php echo esc_attr($key);?>">
                                            <i class="<?php echo esc_attr( $icon );?>"></i>
                                            <div>
                                                <?php if( !empty($title) ){?>
                                                    <h6><?php echo esc_html( $title );?></h6>
                                                <?php } ?>
                                                <?php if( !empty($details) ){?>
                                                    <p><?php echo esc_html( $details );?></p>
                                                <?php } ?>
                                            </div>
                                        </label>
                                    </li>
                                    <?php } ?>
                                </ul>
                            </div>
                            <?php do_action( 'workreap_project_creation_step2',$post_id );?>
                            <?php if(!empty($enable_milestone_feature) && $enable_milestone_feature == 'yes'){?>
                                <div class="form-group <?php echo esc_attr($fixed_class);?>">
                                    <div class="wr-betaversion-wrap">
                                        <figure>
                                            <img src="<?php echo esc_url(WORKREAP_DIRECTORY_URI.'/public/images/milestone.jpg');?>" alt="image">
                                        </figure>
                                        <div class="wr-betaversion-info">
                                            <h5>
                                                <?php esc_html_e('Split your project into milestones','workreap');?>
                                            </h5>
                                            <p><?php esc_html_e('You can also let your freelancer to bid on this project based on their milestones and then you pay them on each completed milestone separately. For more information read','workreap');?>
                                            </p>
                                        </div>
                                        <div class="form-check form-switch wr-switch">
                                            <input class="form-check-input" name="is_milestone" value="yes" type="checkbox" role="switch" <?php echo esc_attr($milestone_checked);?>>
                                        </div>
                                    </div>
                                </div>
                            <?php }?>
                            <div class="form-group form-group-half <?php echo esc_attr($fixed_class);?>">
                                <label class="wr-label"><?php esc_html_e('Add minimum fixed budget','workreap');?></label>
                                <div class="wr-placeholderholder">
                                    <input type="text" name="min_price" value="<?php echo esc_attr($min_price);?>" class="form-control wr-themeinput" placeholder="<?php esc_attr_e('Enter min price','workreap');?>">
                                </div>
                            </div>
                            <div class="form-group form-group-half <?php echo esc_attr($fixed_class);?>">
                                <label class="wr-label"><?php esc_html_e('Add maximum fixed budget','workreap');?></label>
                                <div class="wr-placeholderholder">
                                    <input type="text" name="max_price" value="<?php echo esc_attr($max_price);?>" class="form-control wr-themeinput" placeholder="<?php esc_attr_e('Enter max price','workreap');?>">
                                </div>
                            </div>
                            <div class="form-group form-group-half">
                                <label class="wr-label"><?php esc_html_e('Project duration','workreap');?></label>
                                <div class="wr-select wr-project-select">
                                    <?php
                                        $duration_args = array(
                                            'show_option_none'  => esc_html__('Choose duration', 'workreap'),
                                            'option_none_value' => '',
                                            'show_count'    => false,
                                            'hide_empty'    => false,
                                            'name'          => 'duration',
                                            'class'         => 'wr-select-cat',
                                            'taxonomy'      => 'duration',
                                            'value_field'   => 'term_id',
                                            'orderby'       => 'name',
                                            'hide_if_empty' => false,
                                            'echo'          => true,
                                            'required'      => false,
                                            'selected'      => $selected_duration,
                                        );
                                        do_action('workreap_taxonomy_dropdown', $duration_args);
                                    ?>
                                </div>
                            </div>

                            <div class="form-group form-group-half">
                                <label class="wr-label"><?php esc_html_e('Project category','workreap');?></label>
                                <div class="wr-select wr-project-select">
                                    <?php
                                        $category_args = array(
                                            'show_option_none'  => esc_html__('Choose category', 'workreap'),
                                            'option_none_value' => '',
                                            'show_count'    => false,
                                            'hide_empty'    => false,
                                            'name'          => 'categories',
                                            'class'         => $cat_class,
                                            'taxonomy'      => 'product_cat',
                                            'value_field'   => 'term_id',
                                            'orderby'       => 'name',
                                            'hide_if_empty' => false,
                                            'echo'          => true,
                                            'required'      => false,
                                            'parent'        => 0,
                                            'selected'      => $selected_category,
                                        );
                                        if( !empty($hide_product_cat) ){
                                            $category_args['exclude']    = $hide_product_cat;
                                        }
                                        do_action('workreap_taxonomy_dropdown', $category_args);
                                    ?>
                                </div>
                            </div>
                            <?php if( !empty($project_multilevel_cat) && $project_multilevel_cat === 'enable' ){?>
                                <div class="form-group form-group-half form-group_vertical" id="wr_sub_category">
                                    <?php if (!empty($sub_cat)) { do_action('workreap_get_terms', $selected_category, $sub_cat); } ?>
                                </div>
                                <div class="form-group form-group-half form-group_vertical" id="wr_category_level3" data-type="project">
                                    <?php if (!empty($sub_cat2)) {do_action('workreap_get_terms_subcategories', $sub_cat, $sub_cat2,'project');} ?>
                                </div>
                            <?php } ?>
                            <div class="form-group form-group-half">
                                <label class="wr-label"><?php esc_html_e('Add location','workreap');?></label>
                                <div class="wr-select">
                                    <?php do_action( 'workreap_custom_dropdown_html', $project_location_types,'location','wr-location-type',$selected_location);?>
                                </div>
                            </div>
                            <div class="form-group form-group-half <?php echo esc_attr($location_class);?>">
                                <label class="wr-label"><?php esc_html_e('Country','workreap');?></label>
                                <div class="wr-select wr-project-select">
                                    <?php do_action('workreap_country_dropdown', $selected_country);?>
                                </div>
                            </div>
                            <?php if( !empty($enable_state) ){?>
                                <div class="form-group-half form-group_vertical wr-state-parent <?php echo esc_attr($location_class);?> <?php echo esc_attr($state_country_class);?>">
                                    <label class="form-group-title"><?php esc_html_e('States', 'workreap'); ?></label>
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
                            <?php if( !empty($enable_zipcode) ){ ?>
                                <div class="form-group form-group-half <?php echo esc_attr($location_class);?>">
                                    <label class="wr-label"><?php esc_html_e('Zipcode','workreap');?></label>
                                    <div class="wr-placeholderholder">
                                        <input type="text" name="zipcode"  value="<?php echo esc_attr($zipcode);?>" class="form-control wr-themeinput" placeholder="<?php esc_attr_e('Zipcode','workreap');?>">
                                    </div>
                                </div>
                            <?php } ?>
                            <?php do_action( 'workreap_project_step1_extra_fields', $step_id,$post_id );?>
                            <div class="form-group <?php echo esc_attr($ai_classs);?>">
                                <label class="wr-label"><?php esc_html_e('Project description','workreap');?></label>
                                <?php
                                    if(!empty($enable_ai)){
                                        do_action( 'workreapAIContent', 'job_content-'.$post_id,'job_content' );
                                    }
                                ?>
                                <div class="wr-placeholderholder">
                                    <?php
                                    $editor_content   = do_shortcode($description);
                                    $editor_id = 'job_content-' . $post_id;
                                    $editor_settings = array(
	                                    'media_buttons' => false,
	                                    'textarea_name' => 'details',
	                                    'textarea_rows' => get_option('default_post_edit_rows', 10),
	                                    'quicktags' => false,
                                    );
                                    wp_editor( $editor_content, $editor_id, $editor_settings );
                                    ?>
                                </div>
                            </div>
                            <div class="accordion wr-attachments-hodler" id="attechmentacordian">
                                <div class="wr-attechment-wrapper">
                                    <div class="wr-attechment-tittle">
                                        <h6 data-bs-toggle="collapse" data-bs-target="#flush-collapseTwo" aria-expanded="false" aria-controls="flush-collapseTwo"><?php esc_html_e('Add media / attachments (optional)','workreap');?></h6>
                                        <i class="wr-icon-plus" role="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseTwo" aria-expanded="false" aria-controls="flush-collapseTwo"></i>
                                    </div>
                                    <div id="flush-collapseTwo" class=" collapse" data-bs-parent="#attechmentacordian">
                                        <div class="wr-attechment-content">
                                            <div class="form-group">
                                                <div class="wr-postserviceholder">
                                                    <div class="wr-postservicetitle">
                                                        <h6><?php esc_html_e('Add project descriptive video', 'workreap');?></h6>
                                                    </div>
                                                    <div class="wr-videolink">
                                                        <input id="videourl" name="video_url" type="url" autocomplete="off" class="form-control" placeholder="<?php esc_attr_e('Enter video link here', 'workreap');?>" value="<?php echo esc_url($videourl);?>">
                                                        <!-- <input type="hidden" id="custom_video_upload" name="custom_video_upload" value="">
                                                        <em>
                                                            <?php esc_html_e('or', 'workreap');?>
                                                            <label for="videofile">
                                                                <input id="videofile" type="file" name="videofile">
                                                                <?php esc_html_e('upload a video', 'workreap');?>
                                                            </label>
                                                        </em> -->
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="wr-postserviceholder">
                                                    <div class="wr-postservicetitle">
                                                        <h6><?php esc_html_e('Upload project attachments', 'workreap');?></h6>
                                                    </div>
                                                    <div id="workreap-upload-documents" class="workreap-fileuploader wr-uploadarea">
                                                        <ul class="wr-uploadbar wr-bars workreap-fileprocessing" id="workreap-fileprocessing">
                                                            <?php
                                                                if(!empty($downloadable_files) ){
                                                                    foreach( $downloadable_files as $downloadable_file ){

                                                                        if(!empty($downloadable_file)){
                                                                            $url = $name = '';

                                                                            if(!empty($downloadable_file['url'])) {
                                                                                $file_detail         = Workreap_file_permission::getDecrpytFile($downloadable_file);
                                                                                $name                = $file_detail['filename'];
                                                                                $url                 = $file_detail['dirname'].'/'.$name;
                                                                            }

                                                                            $attachment_id  = !empty($downloadable_file['id']) ? $downloadable_file['id'] : '';
                                                                            $url            = wp_get_attachment_url( $attachment_id );
                                                                            $file_size      = !empty($downloadable_file['size']) ? $downloadable_file['size'] : '';

                                                                            if(empty($name)){
                                                                                $name   = get_the_title($attachment_id);
                                                                            }
                                                                            ?>
                                                                            <li class="workreap-file-uploaded">
                                                                                <div class="wr-filedesciption">
                                                                                    <span><a href="#" data-href="<?php echo esc_url($url);?>"><?php echo esc_html($name);?></a></span>
                                                                                    <input type="hidden" class="attachment_url" name="attachments[<?php echo intval($attachment_id);?>][file]" value="<?php echo esc_url($url);?>">
                                                                                    <input type="hidden" name="attachments[<?php echo intval($attachment_id);?>][id]" value="<?php echo intval($attachment_id);?>">
                                                                                    <input type="hidden" name="attachments[<?php echo intval($attachment_id);?>][name]" value="<?php echo esc_attr($name);?>">
                                                                                    <em class="wr-remove"><a href="javascript:void(0)"  class="wr-remove-document" data-attachment_id="<?php echo intval($attachment_id);?>"><i class="wr-icon-trash-2"></i></a></em>
                                                                                </div>
                                                                            </li>
                                                                <?php }}}?>
                                                            </ul>
                                                        <div class="wr-uploadbox workreap-dragdroparea" id="workreap-documents-droparea" <?php echo do_shortcode($project_upload_area);?>>
                                                            <em>
                                                                <?php echo wp_sprintf( '%1$s %2$s', esc_html__( 'You can upload media file format only.', 'workreap'), esc_html__( 'make sure your file size does not exceed 15mb.', 'workreap') );?>
                                                                <label for="file1">
                                                                    <span id="workreap-documents-btn">
                                                                        <input id="file1" type="file" name="file">
                                                                        <?php esc_html_e('Click here to upload', 'workreap');?>
                                                                    </span>
                                                                </label>
                                                            </em>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </fieldset>
                </form>
            </div>
            <div class="wr-project-box">
                <div class="wr-projectbtns">
                    <a href="javascript:void(0)" class="wr-btn-solid-lg-lefticon wr-save-project" data-step_id="2" data-project_id="<?php echo intval($post_id);?>">
                        <?php esc_html_e('Save & continue','workreap');?>
                        <i class="wr-icon-chevron-right"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/template" id="tmpl-load-documents-attachments">
    <li id="thumb-{{data.id}}" class="workreap-list">
        <div class="wr-filedesciption">
            <span><a href="#" data-href="{{data.url}}" class="venobox-gallery">{{data.name}}</a></span>
            <input type="hidden" class="attachment_url" name="attachments[{{data.attachment_id}}]" value="{{data.url}}">
            <em class="wr-remove"><a href="javascript:void(0)" class="wr-remove-document"><i class="wr-icon-trash-2"></i></a></em>
        </div>
        <div class="progress">
            <div class="progress-bar uploadprogressbar" style="width:0%"></div>
        </div>
    </li>
</script>

<?php
$scripts	= "
    jQuery(document).ready(function($){
        'use strict';
        removeMilestone();
        jQuery('.wr-select-cat').select2({
            theme: 'default wr-select2-dropdown',
            allowClear: true,
            language: {
				noResults: function(){
                    return scripts_vars.select_not_found
                },
                searching: function(){
                    return scripts_vars.select_searching
                },
            }
        });
        jQuery('input[type=radio][name=project_type]').change(function() {
            let job_type    = this.value;
            if(job_type == 'fixed'){
                jQuery('.wr-fixed-type').removeClass('d-none');
                jQuery('.wr-hourly-type').addClass('d-none');
                jQuery('.wr-li-fixed').addClass('wr-active-option');
                jQuery('.wr-li-hourly').removeClass('wr-active-option');
            } else if(job_type == 'hourly'){
                jQuery('.wr-hourly-type').removeClass('d-none');
                jQuery('.wr-fixed-type').addClass('d-none');
                jQuery('.wr-li-fixed').removeClass('wr-active-option');
                jQuery('.wr-li-hourly').addClass('wr-active-option');
            }
            
        });
        
        jQuery('.wr-location-type').select2({
            theme: 'default wr-select2-dropdown',
            allowClear: true,
            placeholder: scripts_vars.select_location,
            language: {
				noResults: function(){
                    return scripts_vars.select_not_found
                },
                searching: function(){
                    return scripts_vars.select_searching
                },
            }
        });

        jQuery('[name=location]').on('select2:select', function (e) {
            let selected_val    = jQuery(this).val();
            if( selected_val == 'location'){
                jQuery('.wr-loaction-type').removeClass('d-none');
            } else {
                jQuery('.wr-loaction-type').addClass('d-none');
            }
        });

        jQuery('#wr-add-milestone').on('click', function (e) {
            let counter 	            = Math.floor((Math.random() * 999999) + 999);
            var load_milestone_temp 	= wp.template('load-project-milestone');
            var data 		            = {id: counter};
            load_milestone_temp	        = load_milestone_temp(data);
            jQuery('#wr-list-milestone').append(load_milestone_temp);
            removeMilestone();
        });
    
        function removeMilestone(){
            jQuery('.wr-remove-milestone').on('click', function (e) {
                jQuery(this).closest('.wr-milestone-list').remove();
            });
        }
    });";
wp_add_inline_script('workreap', $scripts, 'after');