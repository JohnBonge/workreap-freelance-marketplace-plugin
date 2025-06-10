<?php
/**
 *  Add task media attachments
 *
 * @package     Workreap
 * @subpackage  Workreap/templates/post_services
 * @author      Amentotech <info@amentotech.com>
 * @link        https://codecanyon.net/user/amentotech/portfolio
 * @version     1.0
 * @since       1.0
*/
global $workreap_settings;
$task_downloadable      =  !empty($workreap_settings['task_downloadable']) ? $workreap_settings['task_downloadable'] : '';
$task_max_images        =  !empty($workreap_settings['task_max_images']) ? intval($workreap_settings['task_max_images']) : 3;
if(isset($_GET['postid']) && !empty($_GET['postid'])){
    $post_id = intval($_GET['postid']);
}

if ( !class_exists('WooCommerce') ) {
	return;
}

$current_page_url   = get_the_permalink();
if(isset($_GET['post']) && !empty($_GET['post'])){
    $post_id = intval($_GET['post']);
    $current_page_url   = add_query_arg('post', $post_id, $current_page_url);
}

if(isset($_GET['step']) && !empty($_GET['step'])){
    $step = intval($_GET['step']);
    $current_page_url   = add_query_arg('step', $step, $current_page_url);
}

$product_gallery        = get_post_meta($post_id, '_product_attachments', true);
$services_upload_area   = '';
if(!empty($product_gallery) && is_array($product_gallery) && count($product_gallery) >= $task_max_images ){
    $services_upload_area   = 'style="display: none;"';
}

$_product_video 		= get_post_meta($post_id, '_product_video', true);
$video_attachment_id 	= get_post_meta($post_id, '_product_video_attachment_id', true);
$videourl   = '';
$video_name   = '';

if(!empty($_product_video) ){
    $videourl   = $_product_video;
}
$services_download_area   = 'style="display: none;"';

if(isset($_downloadable) && $_downloadable == 'yes'){
    $services_download_area   = '';
}?>
<div id="service-media-attachments-wrapper">
    <form id="service-media-attachments-form" name="service-media-attachments-form" class="wr-themeform service-media-attachments-form" action="<?php echo esc_url($current_page_url);?>" method="post" novalidate enctype="multipart/form-data">
        <fieldset>
            <div class="form-group">
                <div class="wr-postserviceholder">
                    <div class="wr-postservicetitle">
                        <h4><?php esc_html_e('Gallery', 'workreap');?> <span>(<?php echo sprintf(esc_html__('Add up to %s', 'workreap'),$task_max_images);?>)</span><sup class="asterik_red">*</sup></h4>
                    </div>
                    <div id="workreap-upload-attachment" class="workreap-fileuploader wr-uploadarea">
                        <div class="wr-uploadbox workreap-dragdroparea" id="workreap-droparea" <?php echo do_shortcode($services_upload_area);?>>
                            <svg>
                                <rect width="100%" height="100%"/>
                            </svg>
                            <i class="wr-icon-upload"></i>
                            <em>
                                <?php echo wp_sprintf( '%1$s <br/> %2$s', esc_html__( 'You can upload media file format only.', 'workreap'), esc_html__( 'make sure your file size does not exceed 15mb.', 'workreap') );?>
                                <label for="file1">
                                    <span id="workreap-attachment-btn">
                                        <input id="file1" type="file" name="file">
                                        <?php esc_html_e('Click here to upload', 'workreap');?>
                                    </span>
                                </label>
                            </em>
                        </div>
                        <ul class="wr-uploadbar wr-bars workreap-fileprocessing" id="workreap-fileprocessing">
						  <?php if(!empty($product_gallery) ){
							foreach( $product_gallery as $gallery_image ){

								if(!empty($gallery_image)){
									$url = $name = '';

									if(!empty($gallery_image['url'])) {
										$file_detail         = Workreap_file_permission::getDecrpytFile($gallery_image);
										$name                = $file_detail['filename'];
										$url                 = $file_detail['dirname'].'/'.$name;
									}

									$attachment_id  = !empty($gallery_image['attachment_id']) ? $gallery_image['attachment_id'] : '';
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
											<em class="wr-remove"><a href="javascript:void(0)"  class="workreap-remove-attachment wr-gallery-attachment" data-attachment_id="<?php echo intval($attachment_id);?>"><?php esc_html_e('remove', 'workreap');?></a></em>
										</div>
									</li>
						   <?php }}}?>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <div class="wr-postserviceholder">
                    <div class="wr-postservicetitle">
                        <h4><?php esc_html_e('Video', 'workreap');?><sup class="asterik_red">*</sup></h4>
                    </div>
                    <div class="wr-videolink">
                        <input id="videourl" name="video_url" type="url" autocomplete="off" class="form-control" placeholder="<?php esc_attr_e('Enter video link here', 'workreap');?>" value="<?php echo esc_url($videourl);?>">
                        <input type="hidden" id="custom_video_upload" name="custom_video_upload" value="">
                        <em>
                            <?php esc_html_e('or', 'workreap');?>
                            <label for="videofile">
                                <input id="videofile" type="file" name="videofile">
                                <?php esc_html_e('upload a video', 'workreap');?>
                            </label>
                        </em>
                    </div>
                </div>
            </div>
            <?php if( !empty($task_downloadable) ){?>
                <div class="form-group wr-addtolist-wrap">
                    <div class="wr-addtolist-head">
                        <div class="wr-checkbox">
                            <input type="checkbox" name="downloadable"  value="no">
                            <input id="downloadables" name="downloadable" type="checkbox" value="yes" <?php if(isset($_downloadable) && $_downloadable == 'yes'){echo do_shortcode('checked="checked"');}?>>
                            <label for="downloadables"><span><?php esc_html_e('Downloadables', 'workreap');?> <?php do_action('workreap_render_tippy','downloads_title');?></span></label>
                        </div>
                        <div id="service-downloads" class="download-service" <?php echo do_shortcode($services_download_area);?>>
                            <div class="wr-uploadbar">
                                <div class="wr-uploadbar__group">
                                    <input type="text" name="download_title" id="download_title" class="form-control downloadsurlbtn-title" placeholder="<?php esc_attr_e('Enter title here', 'workreap');?>">
                                </div>
                                <div class="wr-uploadbar__url">
                                    <label for="downloadsurlbtn">
                                        <?php esc_html_e('Upload file', 'workreap');?>
                                        <input type="file" id="downloadsurlbtn" class="downloadfile uploadbtn" value="<?php esc_attr_e('Upload file', 'workreap');?>">
                                    </label>
                                    <input type="url" name="download_file_url" class="form-control uploadfileurl download_file_url downloadsurlbtn" placeholder="<?php esc_attr_e('Enter URL here', 'workreap');?>">
                                </div>
                                <div class="wr-uploadbar__group wr-addbtn">
                                    <a href="javascript:void(0);" class="wr-btn add-downloads-btn"><?php esc_html_e('Add to list', 'workreap');?></a>
                                </div>
                            </div>
                            <ul id="tbslothandle" class="wr-uploadbar-list">
                                <?php if(!empty($_downloadable_files)){
                                    foreach($_downloadable_files as $download){?>
                                        <li>
                                            <div class="wr-uploadbar-content">
                                                <a href="javascript:void(0);" class="media-title-text"><?php echo esc_html($download['name']);?></a>
                                                <div class="wr-actionbtn">
                                                    <a href="javascript:void(0);" class="wr-addlink" ><i class="wr-icon-edit-2"></i></a>
                                                    <a href="javascript:void(0);" class="wr-trashlink"><i class="wr-icon-trash-2"></i></a>
                                                </div>
                                            </div>
                                            <div class="wr-uploadfile-list" v-show="isShowing">

                                                <div class="wr-uploadbar wr-uploadbarappend">
                                                    <div class="wr-uploadbar__group">
                                                        <input type="text" class="form-control media-title-input" value="<?php echo esc_attr($download['name']);?>" name="downloads[<?php echo intval($download['id']);?>][title]" placeholder="<?php esc_attr_e('Enter title here', 'workreap');?>">
                                                    </div>
                                                    <div class="wr-uploadbar__url">
                                                    
                                                        <input type="hidden" value="<?php echo esc_attr($download['id']);?>" name="downloads[<?php echo intval($download['id']);?>][id]">
                                                        <input type="url" value="<?php echo esc_attr($download['file']);?>" name="downloads[<?php echo intval($download['id']);?>][file]" class="form-control" placeholder="<?php esc_attr_e('Add URL here', 'workreap');?>">
                                                    </div>
                                                </div>
                                                <div class="wr-actionbtn">
                                                    <a href="javascript:void(0);" class="wr-checked"><i class="fa fa-check"></i></a>
                                                    <a href="javascript:void(0);" class="wr-trashlink"><i class="wr-icon-trash-2"></i></a>
                                                </div>
                                            </div>
                                        </li>
                                        <?php
                                    }
                                }?>
                            </ul>
                        </div>                    

                    </div>
                </div>
            <?php } ?>
            <?php do_action('workreap_media_fields_after', $args);?>
            <div class="form-group wr-postserviceformbtn">
                <div class="wr-savebtn">
                    <span><?php esc_html_e('Click “Save &amp; Update” to update your media/attachments.', 'workreap');?></span>
                    <button type="submit" class="wr-btn wr-upload-media-attachments"><?php esc_html_e('Save &amp; Update', 'workreap');?></button>
                    <input type="hidden" name="post_id" value="<?php echo (int)$post_id;?>">
                </div>
            </div>
        </fieldset>
        <?php if( !empty($task_downloadable)){?>
            <script type="text/template" id="tmpl-load-dwonloads">
                <li>
                    <div class="wr-uploadbar-content">

                        <a href="javascript:void(0);" class="media-title-text">{{{data.title}}}</a>
                        <div class="wr-actionbtn">
                            <a href="javascript:void(0);" class="wr-addlink" ><i class="wr-icon-edit-2"></i></a>
                            <a href="javascript:void(0);" class="wr-trashlink"><i class="wr-icon-trash-2"></i></a>
                        </div>
                    </div>
                    <div class="wr-uploadfile-list" v-show="isShowing">

                        <div class="wr-uploadbar wr-uploadbarappend">
                            <div class="wr-uploadbar__group">
                                <input type="text" class="form-control media-title-input" value="{{{data.title}}}" name="downloads[{{{data.id}}}][title]" placeholder="<?php esc_attr_e('Enter title here', 'workreap');?>">
                            </div>
                            <div class="wr-uploadbar__url">
                                
                                <input type="url" value="{{{data.url}}}" name="downloads[{{{data.id}}}][file]" class="form-control" placeholder="<?php esc_attr_e('Add URL here', 'workreap');?>">
                            </div>
                        </div>
                        <div class="wr-actionbtn">
                            <a href="javascript:void(0);" class="wr-checked"><i class="fa fa-check"></i></a>
                            <a href="javascript:void(0);" class="wr-trashlink"><i class="wr-icon-trash-2"></i></a>
                        </div>
                    </div>
                </li>
            </script>
        <?php } ?>
        <script type="text/template" id="tmpl-load-service-media-attachments">
            <li id="thumb-{{data.id}}" class="workreap-list">
                <div class="wr-filedesciption">
                    <span><a href="#" data-href="{{data.url}}" class="venobox-gallery">{{data.name}}</a></span>
                    <input type="hidden" class="attachment_url" name="attachments[{{data.attachment_id}}]" value="{{data.url}}">
                    <span class="wr-preview">
                        <a href="#" data-href="{{data.url}}" class="venobox-gallery"><?php esc_html_e('View', 'workreap');?></a>
                    </span>
                    <em class="wr-remove"><a href="javascript:void(0)" class="workreap-remove-attachment wr-gallery-attachment"><?php esc_html_e('remove', 'workreap');?></a></em>
                </div>
                <div class="progress">
                    <div class="progress-bar uploadprogressbar" style="width:0%"></div>
                </div>
            </li>
        </script>
    </form>
</div>
<?php
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