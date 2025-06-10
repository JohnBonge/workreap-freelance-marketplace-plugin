<?php
/**
 * List notifications
 *
 * @package     Workreap
 * @subpackage  Workreap/templates
 * @author      Amentotech <info@amentotech.com>
 * @link        https://codecanyon.net/user/amentotech/portfolio
 * @version     1.0
 * @since       1.0
*/
global $workreap_notification;
$linked_profile     = !empty($args['linked_profile']) ? $args['linked_profile'] : 0;
$current_use_id     = !empty($linked_profile) ? workreap_get_linked_profile_id($linked_profile,'post') : 0; 
$post_limit         =  !empty($workreap_notification['notification_limit']) ? intval($workreap_notification['notification_limit']) : 3;
$query_args = array(
	'post_type'			=> 'notification',
	'post_status'		=> 'publish',
	'posts_per_page'	=> intval($post_limit),
	'orderby'			=> array('meta_value_num' => 'ASC','ID' => 'DESC'),
    'meta_key'          => 'status',
	'meta_query'        => array(
        array(
            'key'     => 'linked_profile',
            'value'   => $linked_profile,
            'compare' => '=',
        ),
        array(
            'key'     => 'status',
            'value'   => 0,
            'compare' => '=',
        )
    ),
);
$notify_query   = new WP_Query($query_args);
$count_post     = $notify_query->found_posts;
$meata_keys     = array( 'linked_profile'=>$linked_profile,'status'=>0);
$unread_message = workreap_post_count('notification','publish',$meata_keys);
?>

<div class="wr-notidropdowns">
    <a class="wr-nav-icons wr-notifyheader" data-url="<?php Workreap_Profile_Menu::workreap_profile_menu_link('notifications', $current_use_id, false, 'listing');?>" href="javascript:void(0);">
        <i class="wr-icon-bell"></i>
        <?php if(!empty($unread_message) ){?><em class="wr-remaining-notification wr-notfy-counter"><?php echo intval($unread_message);?></em><?php } ?>
        <span><?php esc_html_e('Notifications','workreap');?></span>
    </a>
    <div class="wr-noti_wrap">
        <div class="wr-notiwrap_title">
            <h5><?php esc_html_e('Notifications','workreap');?></h5>
            <?php if( !empty($unread_message) ){?>
                <span class="wr-noti-counter"><?php echo sprintf(_n('%s New','%s New',$unread_message,'workreap'),$unread_message); ?></span>
            <?php } ?>
        </div>
        <ul class="wr-notify-list" id="wr-notify-list" data-post_id="<?php echo intval($linked_profile);?>">
            <?php 
            if ($notify_query->have_posts()) { 
                while ($notify_query->have_posts()) {
                $notify_query->the_post();
                global $post;
                $msg_read		= get_post_meta( $post->ID, 'status', true );
                $msg_read		= !empty($msg_read) ? $msg_read : 0;
                $msg_class		= !empty($msg_read) ? '' : 'wr-noti-unread';
                ?>
                    <li class="<?php echo esc_attr( $msg_class );?> wr_notify_<?php echo intval($post->ID);?>"><?php do_action( 'workreap_single_message', $post->ID );?></li>
                <?php
                }
            } else { ?>
                <li class="wr-noti_empty">
                    <span>
                        <i class="wr-icon-bell-off"></i>
                    </span>
                    <em><?php esc_html_e('No notification available','workreap');?></em>
                </li>
            <?php } ?>
        </ul>
        <div class="wr-noti_showall" >
            <a href="<?php Workreap_Profile_Menu::workreap_profile_menu_link('notifications', $current_use_id, false, 'listing');?>"><?php esc_html_e('Show all','workreap');?></a>
        </div>
    </div>
</div>