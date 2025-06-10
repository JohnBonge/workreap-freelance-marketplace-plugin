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

$wr_location    = get_post_meta( $post_id,'location',true );
$wr_location    = !empty($wr_location) ? $wr_location : array();
$profile_views  = get_post_meta( $post_id,'workreap_profile_views',true );
$profile_views  = !empty($profile_views) ? intval($profile_views) : 0;
$address        = apply_filters( 'workreap_user_address', $post_id );

$user_id        = workreap_get_linked_profile_id($post_id,'post');
$description	= !empty($wr_post_meta['description']) ? $wr_post_meta['description'] : get_the_content($post_id);


$completed_rate         = workreap_complete_task_count($user_id);
$freelancer_type        = wp_get_post_terms($post_id, 'freelancer_type');
$freelancer_type        = !empty($freelancer_type[0]) ? $freelancer_type[0]->name : '';

$languages              = wp_get_post_terms($post_id, 'languages');
$languages              = !empty($languages[0]) ? $languages[0]->name : '';

$english_level              = wp_get_post_terms($post_id, 'english_level');
$english_level              = !empty($english_level[0]) ? $english_level[0]->name : '';

$login_user_class   = 'wr_btn_checkout';
$wr_msgform         = 'data-type="task" data-url="'.get_the_permalink( $post ).'"';
if(!empty($currentuser_id)){
    $login_user_class   = '';
    $wr_msgform         = 'data-bs-toggle="modal" data-bs-target="#wr_msgform"';
}
$user_name      = workreap_get_username($post_id);
$user_name      = !empty($user_name) ? $user_name : '';
$user_id        = get_post_meta($post_id, '_linked_profile', true);

$skills                         = get_the_terms($post->ID, 'skills');
$app_task_base      		    = workreap_application_access('task');
$skills_base                    = 'project';
if( !empty($app_task_base) ){
    $skills_base    = 'service';
}
$is_activeMeeting   = in_array('workreap-meetings/workreap-meetings.php', apply_filters('active_plugins', get_option('active_plugins'))) ? true : false;
?>
<div class="wr-asideholder wr-freelancer-profile-three">
    <div class="wr-asidebox">
        
        <?php do_action('workreap_get_freelancer_profile_basic', $post_id); ?>
        <div class="wr-rating-profile">
            <ul class="wr-profile-rating">
                <?php do_action('workreap_get_freelancer_rating_count', $post_id); ?>
                <?php do_action('workreap_get_freelancer_views', $post_id); ?>
            </ul>
            <?php do_action('workreap_show_badges', $post_id);?>
        </div>
        <div class="wr-tags-list">
            <ul class="wr-profile-options">
                <?php if( !empty($address) ){?>
                    <li class="wr-profile-location">
                        <em>
                            <svg width="12" height="14" viewBox="0 0 12 14" fill="none">
                                <path d="M0.75 6.12499C0.75 3.20832 3.08333 1.16666 6 1.16666C8.91667 1.16666 11.25 3.20832 11.25 6.12499C11.25 10.7052 7.16667 12.8332 6 12.8332C4.83333 12.8332 0.75 10.7052 0.75 6.12499Z" stroke="#7A50EC" stroke-width="1.5"/>
                                <path d="M7.75 6.41666C7.75 7.38316 6.9665 8.16666 6 8.16666C5.0335 8.16666 4.25 7.38316 4.25 6.41666C4.25 5.45016 5.0335 4.66666 6 4.66666C6.9665 4.66666 7.75 5.45016 7.75 6.41666Z" stroke="#7A50EC" stroke-width="1.5"/>
                            </svg>
                            <?php esc_html_e('Location','workreap');?>
                        </em>
                        <span>
                            <?php echo esc_html($address);?>
                        </span>
                    </li>
                <?php } ?>
                <?php if(!empty($freelancer_type)){?>
                    <li class="wr-profile-freelancer_type">
                        <em>
                            <svg width="12" height="14" viewBox="0 0 12 14" fill="none">
                                <path d="M1.33334 10.9667C1.33334 9.42026 2.58695 8.16666 4.13334 8.16666H7.86668C9.41307 8.16666 10.6667 9.42026 10.6667 10.9667V10.9667C10.6667 11.9976 9.83094 12.8333 8.80001 12.8333H3.20001C2.16908 12.8333 1.33334 11.9976 1.33334 10.9667V10.9667Z" stroke="#F79009" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                <path d="M8.33334 3.49999C8.33334 4.78865 7.28868 5.83332 6.00001 5.83332C4.71135 5.83332 3.66668 4.78865 3.66668 3.49999C3.66668 2.21133 4.71135 1.16666 6.00001 1.16666C7.28868 1.16666 8.33334 2.21133 8.33334 3.49999Z" stroke="#F79009" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                            <?php esc_html_e('Freelancer type','workreap');?>
                        </em>
                        <span>
                            <?php echo esc_html($freelancer_type);?>
                        </span>
                    </li>
                <?php } ?>
                <?php if(!empty($languages)){?>
                    <li class="wr-profile-languages">
                        <em>
                            <svg width="14" height="12" viewBox="0 0 14 12" fill="none">
                                <path d="M12.1393 4.92252C13.0055 3.93612 13.3809 3.05214 13.0621 2.5C12.7433 1.94768 11.7895 1.83092 10.5015 2.08819M12.1393 4.92252C11.2547 5.92988 9.85826 7.04407 8.16664 8.02073C4.81859 9.95373 1.58213 10.616 0.937794 9.5C0.619012 8.94786 0.994445 8.06387 1.86066 7.07745M12.1393 4.92252C12.2119 5.27029 12.25 5.63069 12.25 6C12.25 8.89949 9.89948 11.25 6.99999 11.25C4.46979 11.25 2.35764 9.46011 1.86066 7.07745M12.1393 4.92252C11.9066 3.80678 11.3197 2.82103 10.5015 2.08819M10.5015 2.08819C9.57252 1.25605 8.34535 0.749999 6.99999 0.749999C4.10049 0.749999 1.74999 3.1005 1.74999 6C1.74999 6.3693 1.78812 6.72969 1.86066 7.07745" stroke="#079455" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                            <?php esc_html_e('Languages','workreap');?>
                        </em>
                        <span>
                            <?php echo esc_html($languages);?>
                        </span>
                    </li>
                <?php } ?>
                <?php if(!empty($english_level)){?>
                    <li class="wr-profile-english_level">
                        <em>
                            <svg width="11" height="14" viewBox="0 0 11 14" fill="none">
                                <path d="M0.916656 2.33335V8.75002M0.916656 2.33335V1.16669M0.916656 2.33335L1.68637 2.14093C2.68754 1.89063 3.74713 2.0483 4.63205 2.57925V2.57925C5.60608 3.16367 6.78685 3.29329 7.86447 2.93408L9.66666 2.33335V8.75002L7.86447 9.35075C6.78685 9.70996 5.60608 9.58034 4.63205 8.99592V8.99592C3.74713 8.46497 2.68754 8.3073 1.68637 8.55759L0.916656 8.75002M0.916656 12.8334V8.75002" stroke="#D92D20" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                            <?php esc_html_e('English level','workreap');?>
                        </em>
                        <span>
                            <?php echo esc_html($english_level);?>
                        </span>
                    </li>
                <?php } ?>
            </ul>
        </div>
        <?php if( (!empty($user_type) && $user_type === 'employers' || !is_user_logged_in()) 
            && !empty($post_author) && $post_author != $currentuser_id
            && (in_array('wp-guppy/wp-guppy.php', apply_filters('active_plugins', get_option('active_plugins')))  || !empty($is_activeMeeting) || in_array('wpguppy-lite/wpguppy-lite.php', apply_filters('active_plugins', get_option('active_plugins'))))){?>
            <div class="wr-sidebarcontent">
                <div class="wr-sidebarinnertitle <?php echo !empty($is_activeMeeting) ? 'wr-active-meeting' : '';?>">
                    <?php if((in_array('wp-guppy/wp-guppy.php', apply_filters('active_plugins', get_option('active_plugins'))) || in_array('wpguppy-lite/wpguppy-lite.php', apply_filters('active_plugins', get_option('active_plugins'))))){?>
                        <a href="javascript:;" class="wr-btn <?php echo esc_attr($login_user_class);?>" style="background-color:#99876e; color:white; padding:0 20px; height:48px;cursor:pointer; border-radius:10px; font: 500 14px / 26px Inter, sans-serif;" <?php echo do_shortcode( $wr_msgform );?>><?php esc_html_e('Send Message','workreap');?>&nbsp;<i class="wr-icon-message-square" style="vertical-align:middle;"></i></a>
                        <!--<a href="javascript:;" class="wr-btn '.esc_attr($checkout_class).'" style="background-color:#99876e; color:white; padding:0 20px; height:48px;cursor:pointer; border-radius:10px; min-width:100% !important; font: 500 14px / 26px Inter, sans-serif;" <?php echo do_shortcode( $wr_msgform );?>><?php esc_html_e('Send Message','workreap');?>&nbsp;<i class="wr-icon-message-square" style="vertical-align:middle;"></i></a>-->
                    <?php } ?>
                    <?php do_action('workreap_show_bookings', $post_id,$post_author);?>
                </div>
            </div>
        <?php } ?>
    </div>
    <?php if( !empty($skills) || !empty($description) ){ ?>
        <div class="wr-asidebox-content">
            <?php  if( !empty($description) ){?>
                <div class="wt-title"><?php esc_html_e("About","workreap");?></div>
                <div class="wr-description-area description-with-more wr-description-container">
	                <?php
//	                echo workreapReadMoreDescription( do_shortcode( nl2br( $description ) ), 560 );
	                echo do_shortcode(nl2br($description));
	                ?>
                </div>
            <?php } ?>
            <?php if( !empty($skills) ){ ?>
                <div class="wr-asidebox wr-freelancerinfo wr-freelancer-skills">
                    <div class="wr-freesingletitle">
                        <h4><?php esc_html_e('Skills','workreap');?></h4>
                    </div>
                    <?php do_action( 'workreap_term_tags', $post->ID,'skills','',5,$skills_base );?>
                </div>
            <?php } ?>
        </div>
    <?php } ?>
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
// $script = "WorkreapShowMore();";
// wp_add_inline_script( 'workreap', $script, 'after' );