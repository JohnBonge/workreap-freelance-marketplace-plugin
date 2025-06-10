<?php
global $workreap_settings;

$proposal_id    = !empty($args['proposal_id']) ? intval($args['proposal_id']) : 0;
$project_id     = !empty($args['project_id']) ? intval($args['project_id']) : 0;
$freelancer_id      = !empty($args['freelancer_id']) ? intval($args['freelancer_id']) : 0;
$proposal_status= !empty($args['proposal_status']) ? esc_attr($args['proposal_status']) : 0;
$profile_id     = workreap_get_linked_profile_id($freelancer_id, '','freelancers');
$user_name      = workreap_get_username($profile_id);
$avatar         = apply_filters( 'workreap_avatar_fallback', workreap_get_user_avatar(array('width' => 50, 'height' => 50), $profile_id), array('width' => 50, 'height' => 50));
$proposal_price = isset($args['proposal_meta']['price']) ? $args['proposal_meta']['price'] : 0;
$args   = array(
    'post_id'       => $proposal_id,
    'orderby'       => 'date',
    'order'         => 'DESC',
    'hierarchical'  => 'threaded',
    'type'          => 'activity_detail '
);
$comments = get_comments( $args );
$enable_ai      = !empty($workreap_settings['enable_ai']) && !empty($workreap_settings['enable_ai_project_hiring']) ? true : false;
$ai_classs      = !empty($enable_ai) ? 'wr-input-ai' : '';
?>

<div class=" tab-pane fade show active" id="project-activities" role="tabpanel" aria-labelledby="project-activities-tab">
    <?php if( !empty($comments) ){?>
        <div class="wr-proactivity">
            <ul class="wr-proactivity_list mCustomScrollbar">
                <?php
                    foreach ($comments as $key => $value) { 
                        do_action( 'workreap_project_comments_history', $value );
                    }
                ?>
            </ul>
        </div>
    <?php } else { ?>
        <div class="wr-proactivity">
            <?php do_action( 'workreap_empty_listing', esc_html__('No project activities found', 'workreap')); ?>
        </div>
    <?php } ?>
    <?php if( !empty($proposal_status) && $proposal_status === 'hired' ){?>
        <form class="wr-themeform wr-uploadfile-doc wr-project-comment-form">
            <fieldset>
               
                <div class="form-group <?php echo esc_attr($ai_classs);?>">
                    <label class="wr-label"><?php esc_html_e('Add detail','workreap');?></label>
                    <?php 
                        if(!empty($enable_ai)){
                            do_action( 'workreapAIContent', 'hired_project-'.$proposal_id,'hired_project' );
                        }
                    ?>
                    <textarea name="details" data-ai_content_id="hired_project-<?php echo esc_attr($proposal_id);?>" class="form-control wr-themeinput" name="detail" placeholder="<?php esc_attr_e('Enter your comments here','workreap');?>"></textarea>
                </div>
                <div class="wr-freelanerinfo form-group">
                    <h6><?php esc_html_e('Upload documents / files','workreap');?></h6>
                    <div class="wr-upload-resume" id="workreap-upload-attachment">
                        <ul class="wr-upload-list" id="workreap-fileprocessing"></ul>
                        <div class="wr-uploadphoto workreap-dragdroparea" id="workreap-droparea" >
                            <p><?php echo wp_sprintf( esc_html__( 'You can upload jpg,jpeg,gif,png,zip,rar,mp3 mp4 and pdf only. Make sure your file does not exceed %s mb.', 'workreap'), $workreap_settings['upload_file_size'] );?></p>
                            <span id="workreap-attachment-btn-clicked">
                                <input id="workreap-attachment-btn-clicked" type="file" name="file">
                                <?php esc_html_e('Click here to upload', 'workreap');?>
                            </span>
                        </div>
                    </div>
                </div>
                <div class="form-group wr-form-btn">
                    <span><?php esc_html_e('Click Send now button to send your uploaded file(s)','workreap');?></span>
                    <span class="wr-btn-solid wr-submit-project-commetns" data-id="<?php echo intval($proposal_id);?>"><?php esc_html_e('Send now','workreap');?></span>
                </div>
            </fieldset>
        </form>
    <?php } ?>
</div>
<script type="text/template" id="tmpl-load-chat-media-attachments">
    <li id="thumb-{{data.id}}" class="wr-uploading">
        <span>{{data.name}}</span>
        <input type="hidden" class="attachment_url" name="attachments[{{data.attachment_id}}]" value="{{data.url}}">
        <em class="wr-remove"><a href="javascript:void(0)" class="workreap-remove-attachment wr-remove-attachment"><i class="wr-icon-trash-2"></i></a></em>
    </li>
</script>
