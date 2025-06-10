<?php
/**
 * Task detail activity history
 *
 * @link       https://codecanyon.net/user/amentotech/portfolio
 * @since      1.0.0
 *
 * @package    Workreap
 * @subpackage Workreap_/public
 */
global  $current_user, $workreap_settings;

if ( !class_exists('WooCommerce') ) {
    return;
}

$order_id           = !empty($_GET['id']) ? intval($_GET['id']) : 0;
$current_user_type  = apply_filters('workreap_get_user_type', $current_user->ID);
$post_type          = !empty($order_id) ? get_post_type($order_id) : '';
$disputes_order_id  = !empty($post_type) && $post_type === 'disputes' ? get_post_meta( $order_id, '_dispute_order',true ) : 0;
$disputes_order_type= !empty($disputes_order_id) ? get_post_type( $disputes_order_id ) : '';
if(!empty($post_type) && $post_type === 'disputes' && !empty($disputes_order_id) && !empty($current_user_type) && $current_user_type ==='administrator' ){
    $order_id   = $disputes_order_id;
}
$task_id            = get_post_meta( $order_id, 'task_product_id', true);
$task_id            = !empty($task_id) ? $task_id : 0;
$freelancer_id      = get_post_meta( $order_id, 'freelancer_id', true);
$freelancer_id      = !empty($freelancer_id) ? intval($freelancer_id) : 0;
$task_status        = get_post_meta( $order_id, '_task_status', true);
$task_status        = !empty($task_status) ? $task_status : '';
$gmt_time           = current_time( 'mysql', 1 );
$order_type         = $task_status;
$task_title         = get_the_title($task_id);
$enable_ai          = !empty($workreap_settings['enable_ai']) && !empty($workreap_settings['enable_ai_service_hiring']) ? true : false;
$ai_classs          = !empty($enable_ai) ? 'wr-input-ai' : '';
$args               = array(
    'post_id'       => $order_id,
    'orderby'       => 'date',
    'order'         => 'ASC',
    'hierarchical'  => 'threaded',
);
$comments = get_comments( $args );
?>

<!-- comments -->
<?php if (isset($comments) && !empty($comments)) { ?>
    <div class="wr-additonolservices wr-taskhistory">
        <div class="wr-additonoltitle">
            <h5>
                <?php 
                    if( !empty($disputes_order_type) && $disputes_order_type === 'proposals' ){
                        esc_html_e('Project history','workreap');
                    } else {
                        esc_html_e('Task history','workreap');
                    }
                ?>
            </h5>
        </div>
        <div class="wr-additionolinfo">
            <div class="wr-blogcommentsholder wr-blogcommentsholdervone">
                <?php
                    foreach ($comments as $key => $value) {
                        do_action('workreap_activity_chat_history',$value,'parent',$current_user->ID);

                        // check if comment's children
                        $comment_children = array();
                        $comment_children = $value->get_children();

                        if (!empty($comment_children)){
                            foreach ($comment_children as $comment_child){
                                do_action('workreap_activity_chat_history',$comment_child,'child',$current_user->ID);
                            }
                        }
                    }
                ?>

                <?php if (!empty($task_status) && $task_status == 'completed'){ ?>
                    <div class="wr-addcomment ">
                        <div class="wr-description">
                            <div class="wr-statustag">
                                <span class="wr-approved"><i class="far fa-check-circle"></i><?php esc_html_e('Approved','workreap'); ?></span>
                            </div>
                        </div>
                    </div>
                <?php } ?>
            </div>
        </div>
    </div>
<?php } ?>
<!-- comments end -->

<!-- message area -->
<?php if (!empty($task_status) && $task_status == 'hired' && isset($current_user_type) && $current_user_type != 'administrator') { ?>
    <div class="wr-additonolservices wr-addtaskfile <?php echo esc_attr($ai_classs); ?>" id="chat_box_section">
        <div class="wr-additonoltitle">
            <h5><?php esc_html_e('Add task documents / files','workreap'); ?></h5>
        </div>
        <div class="wr-additionolinfo">
            <form class="wr-themeform wr-project-chat-form">
                <fieldset>
                    <?php if (isset($current_user_type) && $current_user_type == 'freelancers') { ?>
                        <ul class="wr-resvsionbtn-holder">
                            <li class="form-group-half">
                                <div class="wr-radio">
                                    <input type="radio" id="x-option" name="message_type" value="revision" checked="">
                                    <label for="x-option">
                                        <span><?php esc_html_e('Send as a revision','workreap'); ?></span>
                                    </label>
                                </div>
                            </li>
                            <li class="form-group-half">
                                <div class="wr-radio">
                                    <input type="radio" id="y-option" name="message_type" value="final">
                                    <label for="y-option">
                                        <span><?php esc_html_e('Send as a final attempt','workreap'); ?></span>
                                    </label>
                                </div>
                            </li>
                        </ul>
                    <?php } ?>
                    <div class="wr-profileform__holder">
                        <?php 
                            if(!empty($enable_ai)){
                                do_action( 'workreapAIContent', 'service_hiring-'.$order_id,'service_hiring' );
                            }
                        ?>
                        <div class="wr-profileform__content">
                            <textarea name="activity_detail" data-ai_content_id="service_hiring-<?php echo esc_attr($order_id);?>" class="form-control form-controltwo" placeholder="<?php esc_attr_e('Enter description','workreap');?>"></textarea>
                        </div>
                        <div class="wr-workreaploadtitle">
                            <h6><?php esc_html_e('Upload task documents / files:', 'workreap');?> <span>(<?php esc_html_e('Add up to 3', 'workreap');?>)</span></h6>
                        </div>
                        <div class="wr-profileform__content">
                            <div class="wr-uploadarea" id="workreap-upload-attachment">
                                <ul class="wr-uploadbar wr-bars workreap-fileprocessing workreap-infouploading" id="workreap-fileprocessing"></ul>
                                <div class="wr-uploadbox workreap-dragdroparea" id="workreap-droparea">
                                    <em>
                                        <?php echo wp_sprintf( esc_html__( 'You can upload jpg,jpeg,gif,png,zip,rar,mp3,mp4 and pdf only. Make sure your file does not exceed %s MB.', 'workreap'), $workreap_settings['upload_file_size'] );?>
                                        <label for="file1">
                                            <span id="workreap-attachment-btn-clicked">
                                                <input id="file1" type="file" name="file">
                                                <?php esc_html_e('Click here to upload', 'workreap');?>
                                            </span>
                                        </label>
                                    </em>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Video Call Button -->
                    <div class="wr-video-call-btn" style="margin-top: 20px; margin-bottom: 20px;">
                        <a href="javascript:void(0)" class="wr-btn wr-video-call-btn" id="video-call-btn" style="background-color: #007bff; color: white; padding: 10px 20px; border-radius: 5px; text-align: center; display: inline-block;">
                            <?php esc_html_e('Start Meeting', 'workreap'); ?>
                        </a>
                    </div>
                    
                    <div class="wr-taskbtn">
                        <a href="javascript:void(0)"  class="wr-btn wr-submit-project-chat" data-id="<?php echo esc_attr( $order_id ); ?>"><?php esc_html_e('Submit', 'workreap'); ?></a>
                    </div>
                </fieldset>
                <script type="text/template" id="tmpl-load-chat-media-attachments">
                    <li id="thumb-{{data.id}}" class="workreap-list">
                        <div class="wr-filedesciption">
                            <span>{{data.name}}</span>
                            <input type="hidden" class="attachment_url" name="attachments[{{data.attachment_id}}]" value="{{data.url}}">
                            <em class="wr-remove"><a href="javascript:void(0)" class="workreap-remove-attachment wr-remove-attachment"><?php esc_html_e('Remove', 'workreap'); ?></a></em>
                        </div>
                    </li>
                </script>
            </form>
        </div>
    </div>
<?php } ?>

<!-- Add Video Call Script -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const videoCallBtn = document.getElementById('video-call-btn');
    videoCallBtn.addEventListener('click', function() {
        const orderId = <?php echo esc_js($order_id); ?>;
        const freelancerId = <?php echo esc_js($freelancer_id); ?>;
        
        // Create a dynamic room ID based on the order ID
        const roomId = `meeting-${orderId}`; 
        
        // Jitsi URL structure
        const videoCallUrl = `https://meet.jit.si/${roomId}`; // For Jitsi
        
        // Open the video call in a new window for both users
        const newWindow = window.open(videoCallUrl, '_blank');
        
        // Auto-close the meeting after 2 minutes for both users
        setTimeout(() => {
            if (newWindow) {
                newWindow.close(); // Close the current window
            }
            
            // Redirect both users to the dynamic URL after meeting ends
            const redirectUrl = `https://nzuricoach.com/user-dashboard/?ref=tasks-orders&mode=detail&id=${orderId}&identity=${freelancerId}`;
            window.location.href = redirectUrl; // Redirect current window
        }, 2 * 60 * 1000); // 2 minutes
        
        // Ensure the meeting ends for both users
        newWindow.onbeforeunload = function() {
            const redirectUrl = `https://nzuricoach.com/user-dashboard/?ref=tasks-orders&mode=detail&id=${orderId}&identity=${freelancerId}`;
            window.location.href = redirectUrl; // Redirect current window when the Jitsi window closes
        };
    });
});
</script>