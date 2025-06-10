<?php
/**
 * Project listing
 *
 * @package     Workreap
 * @subpackage  Workreap/templates/dashboard
 * @author      Amentotech <info@amentotech.com>
 * @link        https://codecanyon.net/user/amentotech/portfolio
 * @version     1.0
 * @since       1.0
 */
global $current_user;
$show_posts		= get_option('posts_per_page') ? get_option('posts_per_page') : 10;
$paged 			= ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;
$ref		    = !empty($_GET['ref']) ? esc_html($_GET['ref']) : '';
$mode			= !empty($_GET['mode']) ? esc_html($_GET['mode']) : '';
$user_identity	= intval($current_user->ID);
$proposal_id	= !empty($_GET['id']) ? intval($_GET['id']) : 0;
$project_id     = !empty($proposal_id) ? get_post_meta($proposal_id,'project_id',true) : 0;
$project_id     = !empty($project_id) ? $project_id : 0;
$user_type		= apply_filters('workreap_get_user_type', $user_identity);
$linked_profile	= workreap_get_linked_profile_id($user_identity,'',$user_type);
$project_status = get_post_status( $project_id );
$product 	    = wc_get_product( $project_id );
$project_price  = !empty($project_id) ? workreap_get_project_price($project_id) : '';
$proposal_status= !empty($proposal_id) ? get_post_status( $proposal_id ) : '';
$activity_url   = Workreap_Profile_Menu::workreap_profile_menu_link('projects', $user_identity, true, 'activity',$proposal_id);

$product_author_id  = get_post_field ('post_author', $proposal_id);
$linked_profile_id  = workreap_get_linked_profile_id($product_author_id, '','freelancers');
$user_name          = workreap_get_username($linked_profile_id);
$avatar             = apply_filters( 'workreap_avatar_fallback', workreap_get_user_avatar(array('width' => 100, 'height' => 100), $linked_profile_id), array('width' => 100, 'height' => 100));

$wr_total_rating    = get_post_meta( $linked_profile_id, 'wr_total_rating', true );
$wr_total_rating	= !empty($wr_total_rating) ? $wr_total_rating : 0;
$wr_review_users	= get_post_meta( $linked_profile_id, 'wr_review_users', true );
$wr_review_users	= !empty($wr_review_users) ? $wr_review_users : 0;
$proposal_meta      = get_post_meta( $proposal_id, 'proposal_meta',true );
$proposal_meta      = !empty($proposal_meta) ? $proposal_meta : array();
$proposal_type      = !empty($proposal_meta['proposal_type']) ? $proposal_meta['proposal_type'] : '';
$project_type       = get_post_meta( $proposal_id, 'proposal_type',true );
$project_type       = !empty($project_type) ? $project_type : '';
$milestone          = !empty($proposal_meta['milestone']) ? $proposal_meta['milestone'] : array();
$project_meta	    = get_post_meta( $project_id, 'wr_project_meta',true);
$is_milestone	    = !empty($project_meta['is_milestone']) ? $project_meta['is_milestone'] : '';
$user_balance       = get_user_meta( $user_identity, '_employer_balance', true );
$user_balance       = !empty($user_balance) ? $user_balance : 0;
if( empty($project_type) ||$project_type === 'fixed') {
    if( !empty($user_balance) ){
        $checkout_class         = 'wr_proposal_hiring';
    } else {
        $checkout_class     = 'wr_hire_proposal';
    }
} else {
    $checkout_class     = 'wr_hire_job_proposal';
}
$enable_offline         = false;
$offline_order          = false;
$is_offline_enabled     = apply_filters('workreap_check_offline_payment_methods_enabled', false);
if(!empty($is_offline_enabled)){
    $offline_order_meta     = array();
    $shop_order_args = array(
        'post_type' 	    => 'shop_order',
        'post_status'       => array('wc-on-hold'),
        'posts_per_page'    => 1,
        'meta_query'        => array(
            array(
                'key'       => 'proposal_id',
                'value'     => intval($proposal_id),
                'compare'   => '=',
                'type'      => 'NUMERIC'
            )
        )
    );
    $offline_orders         = get_posts( $shop_order_args );
    $offline_order          = !empty($offline_orders) && is_array($offline_orders) ? count($offline_orders) : false;
}


?>
<div class="wr-project-wrapper">
    <div class="wr-project-box wr-employerproject">
        <div class="wr-employerproject-title">
            <?php do_action( 'workreap_project_type_tag', $product->get_id() );?>
            <?php if(!empty($offline_order) && !empty($proposal_status) && !in_array($proposal_status,array('hired','completed','cancelled'))){?>
                <span class="wr-project-tag wr-ongoing"><?php esc_html_e("Pending by admin","workreap");?></span>
            <?php } ?>
            <?php if($product->get_name()){?>
                <h3><?php echo esc_html($product->get_name());?></h3>
            <?php }?>
            <ul class="wr-blogviewdates">
                <?php do_action( 'workreap_posted_date_html', $product );?>
                <?php do_action( 'workreap_location_html', $product );?>
                <?php do_action( 'workreap_texnomies_html_v2', $product->get_id(),'expertise_level','wr-icon-briefcase' );?>
                <?php do_action( 'workreap_hiring_freelancer_html', $product );?>
            </ul>
        </div>
        <div class="wr-price">
            <?php if( !empty($project_price) ){?>
                <h4><?php echo do_shortcode( $project_price );?></h4>
            <?php } ?>
            <?php if( !empty($proposal_status) && in_array($proposal_status,array('hired','completed','cancelled'))){?>
                <div class="wr-project-detail">
                    <a href="<?php echo esc_url($activity_url);?>" class="wr-btn-solid-lg"><?php esc_html_e('Project activity','workreap');?></a>
                </div>
            <?php } else if( !empty($proposal_status) && $proposal_status === 'publish'){?>
                <div class="wr-project-detail">
                    <a href="<?php Workreap_Profile_Menu::workreap_profile_menu_link('proposals', $user_identity, '', 'listing',$product->get_id());?>" class="wr-btn-solid-lg"><?php esc_html_e('View all proposals','workreap');?></a>
                </div>
            <?php } ?>
        </div>
    </div>
    <div class="wr-project-box wr-profile-view">
        <div class="wr-project-table-content">
            <?php if( !empty($avatar) ){?>
                <img src="<?php echo esc_url($avatar);?>" alt="<?php echo esc_attr($user_name);?>">
            <?php } ?>
            <div class="wr-project-table-info">
                <?php if( !empty($user_name) ){?>
                    <h4><?php echo esc_html($user_name);?></h4>
                <?php } ?>
                <?php if( !empty($wr_review_users)){ ?>
                    <ul class="wr-blogviewdates">
                        <li>
                            <i class="fas fa-star wr-yellow"></i>
                            <em> <?php echo number_format($wr_total_rating,1,'.', '');?> </em>
                            <span>(<?php echo intval($wr_review_users);?>)</span>
                        </li>
                    </ul>
                <?php } ?>
            </div>
            <a href="<?php echo esc_url(get_the_permalink($linked_profile_id));?>" class="wr-btn-solid wr-success-tag"><?php esc_html_e('View profile','workreap');?></a>
        </div>
    </div>
    <?php if( isset($proposal_meta['price'])){?>
        <div class="wr-project-box wr-working-rate">
            <div class="wr-project-price">
                <h5><?php echo sprintf(esc_html__('%s budget working rate','workreap'),$user_name);?></h5>
                <span>
                    <?php 
                        if( empty($project_type) ||$project_type === 'fixed') {
                            workreap_price_format($proposal_meta['price']);
                        } else {
                            do_action( 'workreap_proposal_listing_price', $proposal_id );
                        }
                    ?>    
                </span>
            </div>
        </div>
    <?php } ?>
    <div class="wr-projectsinfo wr-project-box">
        <div class="wr-offer-milestone">
            <?php if( !empty($proposal_type) && $proposal_type === 'milestone' && !empty($milestone)){
                ?>
                <div class="wr-projectsinfo_title">
                    <h4><?php esc_html_e('Offered milestones','workreap');?></h4>
                    <p><?php esc_html_e('To start the project, You must click the“Hire & escrow milestone” button later you can escrow other milestones as well from the project activity.','workreap');?></p>
                </div>
                <ul class="wr-projectsinfo_list">
                    <?php 
                        foreach($milestone as $key => $value){ 
                            $title  = !empty($value['title']) ? $value['title'] : '';
                            $price  = isset($value['price']) ? $value['price'] : '';
                            $detail = !empty($value['detail']) ? $value['detail'] : '';
                    ?>  
                        <li>
                            <div class="wr-statusview">
                                <div class="wr-statusview_head">
                                    <div class="wr-statusview_title">
                                        <?php if( !empty($title) ){?>
                                            <h5><?php echo esc_html($title);?></h5>
                                        <?php } ?>
                                        <?php if( isset($price) ){?>
                                            <span><?php workreap_price_format($price);?></span>
                                        <?php } ?>
                                    </div>
                                    <?php if( !empty($detail) ){?>
                                        <p><?php echo do_shortcode($detail);?></p>
                                    <?php } ?>
                                </div>
                                <?php if( !empty($project_status) && $project_status === 'publish' 
                                && !empty($proposal_status) && $proposal_status === 'publish'
                                && empty($offline_order) ){?>
                                    <div class="wr-statusview_btns">
                                        <button class="wr-btnline <?php echo esc_attr($checkout_class);?>" data-key="<?php echo esc_attr($key);?>" data-id="<?php echo intval($proposal_id);?>"><?php echo esc_html__('Pay and hire','workreap');?></button>
                                    </div>
                                <?php } ?>
                            </div>
                        </li>
                    <?php } ?>
                </ul>
            <?php } ?>
            <?php if( !empty($proposal_meta['description'])) {?>
                <div class="wr-milestones-content">
                    <h6><?php esc_html_e('Special comments to employer','workreap');?></h6>
                    <p><?php echo do_shortcode($proposal_meta['description']);?></p>
                </div>
            <?php } ?>
        </div>
    </div>
    <div class="wr-project-box">
        <?php  do_action( 'workreap_before_hire_proposal_button', $proposal_id ); ?>
        <div class="wr-bidbtn wr-proposals-btn">
            <?php if( !empty($project_status) && $project_status === 'publish' && !empty($proposal_status) && $proposal_status === 'publish' && empty($offline_order) ) {?>
                <button class="wr-decline" data-bs-target="#wr_decline_proposal" data-bs-toggle="modal" ><?php esc_html_e('Decline proposal','workreap');?></button>
            <?php } ?>

            <?php if((in_array('wp-guppy/wp-guppy.php', apply_filters('active_plugins', get_option('active_plugins'))) || in_array('wpguppy-lite/wpguppy-lite.php', apply_filters('active_plugins', get_option('active_plugins')))) ){?>
                <button class="wr-btnline wr_proposal_chat" data-reciver_id="<?php echo intval($product_author_id);?>"><i class="wr-icon-message-square"></i><?php esc_html_e('Start chat','workreap');?></button>
            <?php } ?>

            <?php if( 
                    (!empty($proposal_type) && $proposal_type === 'fixed' && !empty($project_status) && $project_status === 'publish' && !empty($proposal_status) && $proposal_status === 'publish' ) || 
                    (!empty($project_type) && $project_type === 'fixed' && !empty($is_milestone) && $is_milestone === 'no' && !empty($project_status) && $project_status === 'publish' && !empty($proposal_status) && $proposal_status === 'publish' )){
                        if(empty($offline_order)){
                        ?>
                        <button class="wr-btn-solid-lg-lefticon <?php echo esc_attr($checkout_class);?>" data-key="" data-id="<?php echo intval($proposal_id);?>"><?php echo sprintf(esc_html__('Hire “%s”','workreap'),$user_name);?></button>
            <?php } } else {
                do_action( 'workreap_hire_proposal_button', $proposal_id );
            } ?>
        </div>
    </div>
</div>
<div class="modal fade wr-declinereason" id="wr_decline_proposal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
        <div class="wr-popup_title">
            <h5><?php esc_html_e('Add decline reason below','workreap');?></h5>
            <a href="javascrcript:void(0)" data-bs-dismiss="modal">
                <i class="wr-icon-x"></i>
            </a>
        </div>
        <div class="modal-body wr-popup-content">
            <form class="wr-themeform">
                <fieldset>
                    <div class="wr-themeform__wrap">
                        <div class="form-group">
                            <div class="wr-placeholderholder">
                                <textarea name="detail" id="wr_decline_detail" class="form-control wr-themeinput" placeholder="<?php esc_attr_e('Enter description','workreap');?>"></textarea>
                            </div>
                        </div>
                        <div class="wr-popup-terms form-group">
                            <button type="button" class="wr-btn-solid-lg wr_decline_proposal" data-id="<?php echo intval($proposal_id);?>"><?php esc_html_e('Submit question now','workreap');?><i class="wr-icon-arrow-right"></i></button>
                        </div>
                    </div>
                </fieldset>
            </form>
        </div>
        </div>
    </div>
</div>
<?php 
do_action('wpguppy_start_post_widget_chat', $proposal_id);
