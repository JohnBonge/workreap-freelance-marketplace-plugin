<?php
/**
 * Provide basic profile inofrmation
 *
 * @link       https://codecanyon.net/user/amentotech/portfolio
 * @since      1.0.0
 *
 * @package    Workreap
 * @subpackage Workreap_/public/partials
 */
global  $post,$current_user,$workreap_settings;
$hide_languages       = !empty($workreap_settings['hide_languages']) ? $workreap_settings['hide_languages'] : 'no';
$currentuser_id  = !empty($current_user->ID) ? intval($current_user->ID) : 0;
$user_type      = !empty($currentuser_id) ? apply_filters('workreap_get_user_type', $currentuser_id ) : '';
$post_id        = !empty($args['post_id']) ? intval($args['post_id']) : $post->ID;
$post_author    = get_post_field( 'post_author', $post_id );
$user_name      = workreap_get_username($post_id);
$wr_post_meta   = get_post_meta( $post_id,'wr_post_meta',true );
$wr_post_meta   = !empty($wr_post_meta) ? $wr_post_meta : array();
$tagline        = !empty($wr_post_meta['tagline']) ? $wr_post_meta['tagline'] : '';

$wr_location    = get_post_meta( $post_id,'location',true );
$wr_location    = !empty($wr_location) ? $wr_location : array();
$profile_views  = get_post_meta( $post_id,'workreap_profile_views',true );
$profile_views  = !empty($profile_views) ? intval($profile_views) : 0;
$address        = apply_filters( 'workreap_user_address', $post_id );
$user_rating    = get_post_meta( $post_id, 'wr_total_rating', true );
$user_rating    = !empty($user_rating) ? $user_rating : 0;
$review_users   = get_post_meta( $post_id, 'wr_review_users', true );
$review_users   = !empty($review_users) ? intval($review_users) : 0;
$user_id        = workreap_get_linked_profile_id($post_id,'post');
$description	= !empty($wr_post_meta['description']) ? $wr_post_meta['description'] : '';

$completed_rate         = workreap_complete_task_count($user_id);

$login_user_class   = 'wr_btn_checkout';
$wr_msgform         = 'data-type="task" data-url="'.get_the_permalink( $post ).'"';
if(!empty($currentuser_id)){
    $login_user_class   = '';
    $wr_msgform         = 'data-bs-toggle="modal" data-bs-target="#wr_msgform"';
}
?>
<div class="wr-asideholder wr-freelancer-profile-two">
    <div class="wr-asidebox">
        <?php do_action( 'workreap_profile_image', $post_id,true,array('width' => 600, 'height' => 600));?>
        <div class="wr-icondetails">
            <?php if( !empty($tagline) ){?>
                <h5><?php echo esc_html($tagline);?></h5>
            <?php } ?>
            <ul class="wr-rateviews">
            <?php do_action('workreap_get_freelancer_rating_count', $post_id); ?>
                <?php do_action('workreap_get_freelancer_views', $post_id); ?>
                <?php do_action('workreap_save_freelancer_html', $currentuser_id, $post_id, '_saved_freelancers', '', 'freelancers'); ?>
            </ul>
            <?php if( !empty($description) ){?>
                <div class="wr-description-area description-with-more"><p><?php echo do_shortcode(nl2br($description));?></p></div>
            <?php } ?>
            <?php do_action( 'workreap_freelancer_hourly_rate_html', $post_id );?>
            <?php if( !empty($address) ){?>
                <div class="wr-sidebarcontent">
                    <div class="wr-sidebarinnertitle">
                        <h6><?php esc_html_e('Location:','workreap');?></h6>
                        <h5><?php echo esc_html($address);?></h5>
                    </div>
                </div>
            <?php } ?>
            <?php do_action( 'workreap_texnomies_static_html', $post_id,'freelancer_type',esc_html__('Freelancer type','workreap') );?>
            <?php do_action( 'workreap_texnomies_static_html', $post_id,'languages',esc_html__('Languages','workreap') );?>
            <?php  if(!empty($hide_languages ) && $hide_languages == 'no'){do_action( 'workreap_texnomies_static_html', $post_id,'english_level',esc_html__('English level','workreap') );}?>
            <?php if( (!empty($user_type) && $user_type === 'employers' || !is_user_logged_in()) && !empty($post_author) && $post_author != $currentuser_id && (in_array('wp-guppy/wp-guppy.php', apply_filters('active_plugins', get_option('active_plugins'))) || in_array('wpguppy-lite/wpguppy-lite.php', apply_filters('active_plugins', get_option('active_plugins'))))){?>
                <div class="wr-sidebarcontent">
                    <div class="wr-sidebarinnertitle">
                    <a href="javascript:;" class="wr-btn <?php echo esc_attr($login_user_class);?>" <?php echo do_shortcode( $wr_msgform );?>><i class="wr-icon-message-square"></i><?php esc_html_e('Contact to this freelancer','workreap');?></a>
                    </div>
                </div>
            <?php } ?>
        </div>
    </div>
</div>
<div class="modal fade wr-startchat" id="wr_msgform" role="dialog">
    <div class="modal-dialog wr-modaldialog" role="document">
        <div class="modal-content">
            <div class="wr-popuptitle">
                <h4 id="wr_ratingtitle"><?php echo sprintf(esc_html__('Send a message to “%s“','workreap'),$user_name);?></h4>
                <a href="javascript:void(0);" class="close"><i class="wr-icon-x" data-bs-dismiss="modal"></i></a>
            </div>
            <div class="modal-body" id="wr_startcaht_form">
                <div class="wr-startchat-field">
                    <textarea class="form-control" id="wr_message" name="message" placeholder="<?php esc_attr_e('Type your message','workreap');?>"></textarea>
                    <a href="javascript:void(0);" data-post_id="<?php echo intval($post_id);?>"  class="wr-btn wr_sentmsg_task"><?php esc_html_e('Send message','workreap');?></a>
                </div>
            </div>
        </div>
    </div>
</div>
<?php 
$script = "WorkreapShowMore();";
wp_add_inline_script( 'workreap', $script, 'after' );
