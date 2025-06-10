<?php

/**
 * Add/Edit Portfolio
 *
 * @package     Workreap
 * @subpackage  Workreap/templates/dashboard
 * @author      Amentotech <info@amentotech.com>
 * @link        https://codecanyon.net/user/amentotech/portfolio
 * @version     1.0
 * @since       1.0
 */
global $current_user, $workreap_settings, $userdata, $post;
$post_id		= !empty($_GET['id']) ? intval($_GET['id']) : 0;
$enable_ai      = !empty($workreap_settings['enable_ai']) && !empty($workreap_settings['enable_ai_portfolio_content']) ? true : false;
$ai_classs      = !empty($enable_ai) ? 'wr-input-ai' : '';
$product_cat_data       = workreap_get_term_dropdown('product_cat', false, 0, false);
$selected_type			= array();
if(function_exists('get_field_object')){
	$field = get_field_object('field_668e242339c78');
	$selected_type	= !empty($field['choices']) ? $field['choices'] : array();
	if($selected_type['Select type']){
		unset($selected_type['Select type']);
	}
}

$title	= !empty($post_id) ? get_the_title($post_id) : '';
$type	= !empty($post_id) ? get_post_meta($post_id, 'type',true) : '';
$link_class		= 'd-none';
$video_class	= 'd-none';
$gallery_class	= 'd-none';
$document_class	= 'd-none';
$image_class	= 'd-none';
$link			= '';
$video_url		= '';
$image			= 0;
$document		= 0;
$main_title		= esc_html__('Add Portfolio', 'workreap');
if(!empty($post_id)){
	$main_title		= esc_html__('Edit Portfolio', 'workreap');
	if(!empty($type) && $type === 'link'){
		$link_class		= '';
		$link			= get_post_meta($post_id, 'url',true);
	} elseif(!empty($type) && $type === 'document'){
		$document_class		= '';
		$document			= get_post_meta($post_id, 'document',true);
	}else if(!empty($type) && $type === 'video'){
		$video_class	= '';
		$video_url			= get_post_meta($post_id, 'video_url',true);
	}else if(!empty($type) && $type === 'gallery'){
		$gallery_class	= '';
		$product_gallery			= get_post_meta($post_id, '_portfolio_gallery',true);
		$product_gallery			= !empty($product_gallery) ? explode(',',$product_gallery) : array();
	}
}
$task_max_images        =  !empty($workreap_settings['portf_max_images']) ? intval($workreap_settings['portf_max_images']) : 3;
$services_upload_area   = '';
if(!empty($product_gallery) && is_array($product_gallery) && count($product_gallery) >= $task_max_images ){
    $services_upload_area   = 'style="display: none;"';
}
$rand		= 11;
$randv1		= 1122;
$image_url	= array();
?>
<div class="wr-dhb-profile-settings">
	<div class="wr-dhb-mainheading">
		<h2><?php echo esc_html($main_title); ?></h2>
	</div>
	<div class="wr-dhb-box-wrapper">
		<form class="wr-themeform wr-profileform" id="wr_save_port-form">
            <fieldset>
                <div class="wr-profileform__holder">
                    <div class="wr-profileform__detail wr-portfolio-info">
                        <div class="form-group">
                            <div class="wr-profilephotocontent">
                                <div class="wr-formtheme wr-formprojectinfo wr-formcategory wr-featured_img" id="wr-img-<?php echo esc_attr( $rand ); ?>">
                                    <div class="uploaded-placeholder">
                                        <?php if( has_post_thumbnail( $post_id ) ){
                                            $img_id		= get_post_thumbnail_id( $post_id );
                                            $image_url	= wp_get_attachment_image_src( $img_id, 'thumbnail' );
                                            $image_url	= !empty($image_url[0]) ? $image_url[0] : '';
                                            ?>
                                            <ul class="wr-attachfile wr-attachfilevtwo">
                                                <div class="wr-uploadingholder wr-companyimg-user">
                                                    <div class="wr-uploadingbox">
                                                        <figure><img class="img-thumb" src="<?php echo esc_url( $image_url ); ?>" alt="<?php echo esc_attr( get_the_title( $post_id ) ); ?>"></figure>
                                                        <div class="wr-uploadingbar wr-avatar_option">
                                                            <span class="uploadprogressbar"></span>
                                                            <em><a href="javascript:void(0);" class="wr-remove-image lnr lnr-cross wr-icon-x"></a></em>
                                                        </div>
                                                        <input type="hidden" name="image_id" value="<?php echo esc_attr( $img_id ); ?>">
                                                    </div>
                                                </div>
                                            </ul>
                                        <?php } ?>
                                    </div>
                                    <div class="wr-container-img" id="wr-image-container-<?php echo esc_attr( $rand ); ?>">
                                        <div class="wr-labelgroup"  id="image-drag-<?php echo esc_attr( $rand ); ?>">
											<h4><?php echo esc_html__('Upload featured image', 'workreap') ?></h4>
											<span><?php esc_html_e('Image should have jpg, jpeg, gif, png extension and size should not be more than 5MB', 'workreap'); ?></span>
                                            <label for="file" class="wr-image-file">
                                                <span class="wr-btn" id="image-btn-<?php echo esc_attr( $rand ); ?>"><?php esc_html_e('Select File', 'workreap'); ?></span>
                                            </label>
                                            <em class="wr-fileuploading d-none"><?php esc_html_e('Uploading', 'workreap'); ?><i class="fa fa-spinner fa-spin"></i></em>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group-half form-group_vertical">
                            <label for="wr-portfolio-title" class="form-group-title"><?php esc_html_e('Title', 'workreap'); ?></label>
                            <input id="wr-portfolio-title" type="text" class="form-control" name="title" placeholder="<?php esc_attr_e('Enter title', 'workreap'); ?>" autocomplete="off" value="<?php echo esc_attr($title); ?>">
                        </div>
                        <div class="form-group-half form-group_vertical">
                            <label for="wr-selected_type" class="form-group-title"><?php esc_html_e('Type', 'workreap'); ?></label>
                            <span class="wr-select wr-select-selected_type">
                                    <select id="wr-selected_type" class="wr-selected_type" name="type" data-placeholderinput="<?php esc_attr_e('Search type', 'workreap'); ?>" data-placeholder="<?php esc_attr_e('Choose type', 'workreap'); ?>">
                                        <option selected hidden disabled value=""><?php esc_html_e('Type', 'workreap'); ?></option>
                                        <?php if (!empty($selected_type)) {
                                            foreach ($selected_type as $key => $item) {
                                                $selected = '';
                                                if(!empty($type) && $type == $key){
                                                    $selected = 'selected';
                                                }
                                                ?>
                                                <option <?php echo esc_attr($selected); ?> value="<?php echo esc_attr($key); ?>"><?php echo esc_html($item); ?></option>
                                            <?php }
                                        } ?>
                                    </select>
                                </span>
                        </div>
                        <div class="form-group wr-port_option <?php echo esc_attr($video_class);?> wr-port_video form-group_vertical">
                            <label class="form-group-title"><?php esc_html_e('Video URL', 'workreap'); ?></label>
                            <input type="text" class="form-control" name="video_url" placeholder="<?php esc_attr_e('Enter video URL', 'workreap'); ?>" autocomplete="off" value="<?php echo esc_url($video_url); ?>">
                        </div>
                        <div class="form-group wr-port_option <?php echo esc_attr($link_class);?> wr-port_link form-group_vertical">
                            <label class="form-group-title"><?php esc_html_e('URL', 'workreap'); ?></label>
                            <input type="text" class="form-control" name="url" placeholder="<?php esc_attr_e('Enter URL', 'workreap'); ?>" autocomplete="off" value="<?php echo esc_url($link); ?>">
                        </div>
                        <div class="form-group form-vertical  wr-port_option wr-port_document <?php echo esc_attr($document_class);?>">
                            <div class="wr-profilephotocontent">
                                <div class="wr-formtheme wr-formprojectinfo wr-formcategory wr-featured_img" id="wr-img-<?php echo esc_attr( $randv1 ); ?>">
								<div class="uploaded-placeholder">
                                        <?php if( !empty( $document ) ){
                                            $image_url	= wp_get_attachment_url($document);
                                            ?>
                                            <ul class="wr-attachfile wr-attachfilevtwo">
                                                <li class="wr-uploadingholder wr-companyimg-user">
                                                    <div class="wr-uploadingbox">
                                                        <figure><img class="img-thumb" src="<?php echo esc_url(  WORKREAP_DIRECTORY_URI . 'public/images/attachment.png');?>" alt="<?php echo esc_attr( get_the_title( $post_id ) ); ?>"></figure>
                                                        <div class="wr-uploadingbar wr-avatar_option">
                                                            <span class="uploadprogressbar"></span>
                                                            <em>
                                                                <a href="javascript:void(0);" class="wr-remove-image lnr lnr-cross wr-icon-x"></a>
                                                            </em>
                                                        </div>
                                                        <input type="hidden" name="document_id" value="<?php echo esc_attr( $document ); ?>">
                                                    </div>
                                                </li>
                                            </ul>
                                        <?php } ?>
                                    </div>
                                    <div class="form-group-label wr-container-img" id="wr-image-container-<?php echo esc_attr( $randv1 ); ?>">
                                        <div class="wr-labelgroup"  id="image-drag-<?php echo esc_attr( $randv1 ); ?>">
											<h4>Upload your PDF here</h4>
											<span><?php esc_html_e('Upload PDF here', 'workreap'); ?></span>
                                            <label for="file" class="wr-image-file">
                                                <span class="wr-btn" id="image-btn-<?php echo esc_attr( $randv1 ); ?>"><?php esc_html_e('Select File', 'workreap'); ?></span>
                                            </label>
                                            <em class="wr-fileuploading d-none"><?php esc_html_e('Uploading', 'workreap'); ?><i class="fa fa-spinner fa-spin"></i></em>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group wr-port_option wr-port_gallery <?php echo esc_attr($gallery_class);?>">
                            <div class="wr-postserviceholder">
                                <div class="wr-postservicetitle">
                                    <h4><?php esc_html_e('Gallery', 'workreap');?> <span>(<?php echo sprintf(esc_html__('Add up to %s', 'workreap'),$task_max_images);?>)</span></h4>
                                </div>
                                <div id="workreap-upload-attachment-port" class="workreap-fileuploader wr-uploadarea">
                                    <div class="wr-uploadbox workreap-dragdroparea" id="workreap-droparea-port" <?php echo do_shortcode($services_upload_area);?>>
                                        <svg>
                                            <rect width="100%" height="100%"/>
                                        </svg>
                                        <i class="wr-icon-upload"></i>
                                        <em>
                                            <?php echo wp_sprintf( '%1$s <br/> %2$s', esc_html__( 'You can upload media file format only.', 'workreap'), esc_html__( 'make sure your file size does not exceed 15mb.', 'workreap') );?>
                                            <label for="file1">
                                                    <span id="workreap-attachment-btn-port">
                                                        <input id="file1" type="file" name="file">
                                                        <?php esc_html_e('Click here to upload', 'workreap');?>
                                                    </span>
                                            </label>
                                        </em>
                                    </div>
                                    <ul class="wr-uploadbar wr-bars workreap-fileprocessing" id="workreap-fileprocessing-port">
                                        <?php if(!empty($product_gallery) ){
                                            foreach( $product_gallery as $attachment_id ){
                                                if(!empty($attachment_id)){
                                                    $url = $name = '';
                                                    $name	= get_the_title($attachment_id);

                                                    $url            = wp_get_attachment_url( $attachment_id );
                                                    $file_size      = !empty($gallery_image['size']) ? $gallery_image['size'] : '';

                                                    if(empty($name)){
                                                        $name   = get_the_title($attachment_id);
                                                    }
                                                    ?>
                                                    <li class="workreap-file-uploaded">
                                                        <div class="wr-filedesciption">
                                                            <span><a href="#" data-href="<?php echo esc_url($url);?>" class="venobox-gallery"><?php echo esc_html($name);?></a></span>
                                                            <input type="hidden" class="attachment_url" name="attachments[<?php echo intval($attachment_id);?>][url]" value="<?php echo esc_url($url);?>">
                                                            <input type="hidden" name="attachments[<?php echo intval($attachment_id);?>][attachment_id]" value="<?php echo intval($attachment_id);?>">
                                                            <input type="hidden" name="attachments[<?php echo intval($attachment_id);?>][name]" value="<?php echo esc_attr($name);?>">
                                                            <span class="wr-preview">
                                                                <a href="#" data-href="<?php echo esc_url($url);?>" class="venobox-gallery"><?php esc_html_e('View', 'workreap');?></a>
                                                            </span>
                                                            <em class="wr-remove"><a href="javascript:void(0)"  class="workreap-remove-attachment wr-gallery-attachment-port" data-attachment_id="<?php echo intval($attachment_id);?>"><?php esc_html_e('Remove', 'workreap');?></a></em>
                                                        </div>
                                                    </li>
                                                <?php }}}?>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="wr-profileform__holder">
                    <div class="wr-dhbbtnarea wr-dhbbtnareav2">
                        <em><?php esc_html_e('Click “Save & Update” to update the latest changes', 'workreap'); ?></em>
                        <a href="javascript:void(0);" data-id="<?php echo intval($post_id); ?>" class="wr-btn wr_update_portfolio"><?php esc_html_e('Save & Update', 'workreap'); ?></a>
                    </div>
                </div>
            </fieldset>
		</form>		
	</div>
</div>

<script type="text/template" id="tmpl-load-chat-media-attachments-doc">
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
<script type="text/template" id="tmpl-load-service-media-attachments">
	<li id="thumb-{{data.id}}" class="workreap-list">
		<div class="wr-filedesciption">
			<span><a href="#" data-href="{{data.url}}" class="venobox-gallery">{{data.name}}</a></span>
			<input type="hidden" class="attachment_url" name="attachments[{{data.attachment_id}}]" value="{{data.url}}">
			<span class="wr-preview">
				<a href="#" data-href="{{data.url}}" class="venobox-gallery"><?php esc_html_e('View', 'workreap');?></a>
			</span>
			<em class="wr-remove"><a href="javascript:void(0)" class="workreap-remove-attachment wr-gallery-attachment-port"><?php esc_html_e('Remove', 'workreap');?></a></em>
		</div>
		<div class="progress">
			<div class="progress-bar uploadprogressbar" style="width:0%"></div>
		</div>
	</li>
</script>

<script type="text/template" id="tmpl-load-default-image">
	<ul class="wr-attachfile wr-attachfilevtwo">
		<li class="award-new-item wr-uploadingholder wr-doc-parent" id="thumb-{{data.id}}">
			<div class="wr-uploadingbox">
				<div class="wr-uploadingbar wr-uploading">
					<span class="uploadprogressbar" style="width:{{data.percentage}}%"></span>
					<em><a href="#" class="wr-remove-image lnr lnr-cross"></a></em>
				</div>	
			</div>
		</li>
	</ul>	
</script>
<script type="text/template" id="tmpl-load-profile-image">
	<div class="wr-uploadingbox">
		<figure><img class="img-thumb" src="{{data.url}}" alt="<?php echo esc_attr( get_the_title( $post_id ) ); ?>"></figure>
		<div class="wr-uploadingbar wr-avatar_option">
			<span class="uploadprogressbar"></span>
			<em><a href="#" class="wr-remove-image lnr lnr-cross wr-icon-x"></a></em>
			<input type="hidden" name="image_url" value="{{data.url}}">	
		</div>	
	</div>	
</script>
<script type="text/template" id="tmpl-load-document-image">
	<div class="wr-uploadingbox">
		<figure><img class="img-thumb"  src="<?php echo esc_url(  WORKREAP_DIRECTORY_URI . 'public/images/attachment.png');?>" alt="<?php echo esc_attr( get_the_title( $post_id ) ); ?>"></figure>
		<div class="wr-uploadingbar wr-avatar_option">
			<span class="uploadprogressbar"></span>
			<em>
				<a href="#" class="wr-remove-image lnr lnr-cross wr-icon-x"></a>
			</em>
			<input type="hidden" name="document_url" value="{{data.url}}">	
		</div>	
	</div>	
</script>
<?php
	$inline_script = 'jQuery(document).on("ready", function() { init_image_uploader_v2("' . esc_js( $rand ). '", "profile"); });';
	//wp_add_inline_script( 'workreap-dashboard', $inline_script, 'after' );
?>
<?php

$scripts	= "
jQuery(document).ready(function($){
    'use strict';

    $('#wr-selected_type').on('change', function() {
		let selected_val	= $(this).val();
		jQuery('.wr-port_option').addClass('d-none'); 
		jQuery('.wr-port_'+selected_val).removeClass('d-none'); 
	});

    });";
    wp_add_inline_script('workreap', $scripts, 'after');

	$script_video = '
jQuery(document).ready(function () {
    let venobox = document.querySelector(".venobox-gallery");
    if (venobox !== null) {
        jQuery(".venobox-gallery").venobox({
            spinner : "cube-grid",
        });
    }
})
';
wp_add_inline_script('venobox', $script_video, 'after');