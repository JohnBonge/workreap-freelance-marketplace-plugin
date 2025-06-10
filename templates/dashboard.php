<?php
/**
 * Template Name: User Dashboard
 *
 * @package     Workreap
 * @subpackage  Workreap/templates
 * @author      Amentotech <info@amentotech.com>
 * @link        https://codecanyon.net/user/amentotech/portfolio
 * @version     1.0
 * @since       1.0
*/
global $current_user;
$user_identity  = intval($current_user->ID);
$user_type		= apply_filters('workreap_get_user_type', $user_identity );
$url_identity   = !empty($_GET['identity']) ? intval($_GET['identity']) : '';
$reference 		= !empty($_GET['ref'] ) ? $_GET['ref'] : '';
$mode 			= !empty($_GET['mode']) ? $_GET['mode'] : '';
$redirect_url   = '';

if( !is_user_logged_in() ){
    $redirect_url   = workreap_get_page_uri('login');
} else if( !empty($user_type) && !in_array($user_type,array('freelancers','employers') )){
  $redirect_url   = get_home_url();
} else if( !empty($user_type) && in_array($user_type,array('freelancers','employers') )){
    $redirect_url   = workreap_get_page_access($user_identity,$user_type,$reference,$mode);
}

if( !empty($url_identity) && $user_identity != $url_identity ){
    $redirect_url   = workreap_get_page_uri('dashboard');
}

//if(empty($reference) && !empty($user_type) && $user_type === 'freelancers'){
//    $redirect_url = Workreap_Profile_Menu::workreap_profile_menu_link('earnings', $user_identity, true, 'insights');
//}

if( !empty($redirect_url) ){
    wp_redirect( $redirect_url );
    exit;
}

get_header();

$post_id		= workreap_get_linked_profile_id( $user_identity );

do_action('workreap_start_before_wrapper');

$deactive_account	= get_post_meta( $post_id, '_deactive_account', true );
$deactive_account	= !empty($deactive_account) ? $deactive_account : 0;

$is_verified 	= get_user_meta($user_identity, '_is_verified', true);
$is_verified    = !empty($is_verified) ? $is_verified : '';
$app_task_base      = workreap_application_access('task');
$app_project_base   = workreap_application_access('project');
if( !empty($deactive_account) && $deactive_account == 1 ){ ?>
    <section class="overflow-hidden wr-main-section wr-deactived-account">
        <div class="container">
            <div class="row">
                <div class="col-sm-12">
                    <div class="wr-deactived-popup">
                        <div class="wr-alertpopup">
                            <span class="wr-redbgbf wr-red">
                                <i class="wr-icon-slash"></i>
                            </span>
                            <h3><?php esc_html_e('Account deactivated','workreap');?></h3>
                            <p><?php esc_html_e("You can not perform any action without restoring your account.Click “Restore my account” and let's get started","workreap");?></p>
                            <ul class="wr-btnareafull">
                                <li><span data-id="<?php echo intval($url_identity);?>" class="wr-pb wr-active-account wr-greenbtn"><?php esc_html_e('Remove anyway','workreap');?></span></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
<?php } else { ?>
    <section class="overflow-hidden wr-main-section">
        <div class="container">
            <?php 
                if( empty($mode) || $mode != 'verification' ){
                    do_action( 'workreap_verify_account_notice', $is_verified );
                }
            ?>
            <div class="row">
                <div class="col-sm-12">
                    <?php
                        if( have_posts() ):
                            while ( have_posts() ) : the_post();
                                the_content();
                                wp_link_pages( array(
                                        'before'      => '<div class="wr-paginationvtwo"><nav class="wr-pagination"><ul>',
                                        'after'       => '</ul></nav></div>',
                                ) );
                            endwhile;

                            if ( !empty($reference) && $reference === 'cart' && $user_type === 'employers') {
                                workreap_get_template_part('dashboard/dashboard', 'cart-page');
                            } else if ( !empty($reference) && $reference === 'offers-cart' && $user_type === 'employers') {
                                do_action( 'workreap_offers_cart', $args );
                            } elseif (is_user_logged_in()  && $url_identity === $user_identity
                             ) {
                                if ( !empty($app_task_base) && !empty($reference) && !empty($mode) && $reference === 'task' && $mode === 'listing' && $user_type === 'freelancers') {
                                    workreap_get_template_part('dashboard/dashboard', 'services-listing');
                                } else if ( !empty($reference) && !empty($mode) && $reference === 'offers' && $mode === 'listing') {
                                    do_action( 'workreap_offers_listing', $args );
                                } else if ( !empty($app_task_base) && !empty($reference) && $reference === 'tasks-orders') {
                                    if( !empty($mode) && $mode == 'detail' ){
                                        workreap_get_template_part('dashboard/dashboard', $user_type.'-tasks-detail');
                                    } else {
                                        workreap_get_template_part('dashboard/dashboard', $user_type.'-tasks-orders');
                                    }
                                } else if ( !empty($reference) && !empty($mode) && $reference === 'dashboard' && ( $mode === 'verification' || $mode === 'billing' || $mode === 'profile' || $mode === 'account' ) ) {
                                    workreap_get_template_part('dashboard/dashboard', 'settings',array( 'id' => $url_identity ) );
                                }else if ( !empty($reference) && !empty($mode) && $reference === 'disputes' && $mode === 'listing') {
                                    workreap_get_template_part('dashboard/dashboard', 'disputes');
                                }else if ( !empty($reference) && !empty($mode) && $reference === 'disputes' && $mode === 'detail') {
                                    workreap_get_template_part('dashboard/dashboard', 'disputes-detail');
                                }else if ( !empty($reference) && !empty($mode) && $reference === 'saved' && $mode === 'listing') {
                                    workreap_get_template_part('dashboard/dashboard', 'saved-items');
                                }else if ( !empty($reference) && !empty($mode) && $reference === 'notifications' && $mode === 'listing') {
                                    workreap_get_template_part('dashboard/dashboard', 'notifications');
                                }else if ( !empty($reference) && !empty($mode) && $reference === 'projects' && !empty($app_project_base)) {
                                    if( !empty($user_type) && $user_type === 'employers' ){
                                        if( !empty($mode) && $mode === 'listing' ){
                                            workreap_get_template_part('dashboard/post-project/employer/dashboard', 'employer-projects');
                                        } else if( !empty($mode) && $mode === 'activity' ){
                                            workreap_get_template_part('dashboard/post-project/employer/dashboard', 'proposals-activity');
                                        } 
                                    } elseif( !empty($user_type) && $user_type === 'freelancers' ){
                                        if( !empty($mode) && $mode === 'listing' ){
                                            workreap_get_template_part('dashboard/post-project/freelancer/dashboard', 'freelancer-projects');
                                        }else if( !empty($mode) && $mode === 'activity'){
                                            workreap_get_template_part('dashboard/post-project/freelancer/dashboard', 'proposals-activity');
                                        }
                                    }
                                }else if ( !empty($reference) && !empty($mode) && $reference === 'proposals' && !empty($app_project_base)) {
                                    if( !empty($user_type) && $user_type === 'employers' ){
                                        if( !empty($mode) && $mode === 'listing'){
                                            workreap_get_template_part('dashboard/post-project/employer/dashboard', 'project-proposals');
                                        } else if( !empty($mode) && $mode === 'detail'){
                                            workreap_get_template_part('dashboard/post-project/employer/dashboard', 'proposals-detail');
                                        }else if( !empty($mode) && $mode === 'dispute'){
                                            workreap_get_template_part('dashboard/post-project/dashboard', 'disputes-detail');
                                        }
                                    } else if( !empty($user_type) && $user_type === 'freelancers' ){
                                        if( !empty($mode) && $mode === 'dispute'){
                                            workreap_get_template_part('dashboard/post-project/dashboard', 'disputes-detail');
                                        }
                                    }
                                }else if ( !empty($reference) && $reference === 'inbox' || !empty($reference) && $reference === 'chat' ) {
                                    workreap_get_template_part('dashboard/dashboard', 'inbox');
                                }else if ( !empty($reference) && $reference === 'earnings' && $user_type === 'freelancers' ) {
                                    workreap_get_template_part('dashboard/dashboard', 'earnings');
                                }else if ( !empty($reference) && $reference === 'invoices' ) {
                                    if (  !empty($mode) && $mode === 'detail'){
                                        $args               = array();
                                        $args['identity']   = !empty($_GET['identity']) ? intval($_GET['identity']) : "";
                                        $args['order_id']   = !empty($_GET['id']) ? intval($_GET['id']) : "";
                                        ?>
                                        <div class="wr-invoicehead">
                                            <span data-order_id="<?php echo intval($args['order_id']);?>" class="wr-download-pdf wr-btn"><i class="wr-icon-download"></i><?php esc_html_e('Export PDF','workreap');?></span>
                                        </div>
                                        <?php 
                                        if(!empty($user_type ) && $user_type === 'employers' ) {
                                            workreap_get_template_part('dashboard/dashboard', 'invoice-detail',$args);
                                        } elseif(!empty($user_type ) && $user_type === 'freelancers' ) {
                                            workreap_get_template_part('dashboard/dashboard', 'freelancer-invoice-detail',$args);
                                        }
                                    } else if( !empty($mode) && $mode === 'listing'){
                                        workreap_get_template_part('dashboard/dashboard', 'invoices');
                                    } else if( !empty($mode) && $mode === 'hourly-detail'){
                                        $args               = array();
                                        $args['identity']   = !empty($_GET['identity']) ? intval($_GET['identity']) : "";
                                        $args['order_id']   = !empty($_GET['id']) ? intval($_GET['id']) : "";
                                        ?>
                                        <div class="wr-invoicehead">
                                            <span data-order_id="<?php echo intval($args['order_id']);?>" class="wr-download-pdf wr-btn"><i class="wr-icon-download"></i><?php esc_html_e('Export PDF','workreap');?></span>
                                        </div>
                                        <?php
                                        if(!empty($user_type ) && $user_type === 'employers' ) {
                                            do_action( 'workreap_employer_invoice_details', $args );
                                        } elseif(!empty($user_type ) && $user_type === 'freelancers' ) {
                                            do_action( 'workreap_freelancer_invoice_details', $args );
                                        } 
                                    }

                                }else if ( !empty($app_task_base) &&  !empty($reference) && $reference === 'orders' && $user_type === 'freelancers') {
                                    workreap_get_template_part('dashboard/dashboard', $user_type.'-tasks-orders');
                                }else if (  !empty($reference) && $reference === 'portfolio' && $user_type === 'freelancers' && $mode === 'update' ) {
                                    workreap_get_template_part('dashboard/dashboard', 'update-portfolio');
                                }else if (  !empty($reference) && $reference === 'portfolio' && $user_type === 'freelancers' && $mode === 'listing' ) {
                                    //workreap_get_template_part('dashboard/dashboard', 'list-portfolio');
                                }else if (  !empty($reference) && $reference === 'meeting' ) {
                                    do_action( 'workreap_meeting_load_dashboard_templates');
                                } else {
	                                workreap_get_template_part('dashboard/dashboard', 'settings' );
                                }
                            } elseif (is_user_logged_in()) {
                                if(!empty($reference) && $reference === 'meeting' ){
                                    do_action( 'workreap_meeting_load_dashboard_templates');
                                } else {
                                    workreap_get_template_part('dashboard/dashboard', 'settings' );
                                }
	                           
                            }
                            do_action( 'workreap_load_dashboard_templates');
                        endif;
                    ?>
                </div>
            </div>
        </div>
    </section>

    <div class="modal fade" id="wr_completetask" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog wr-modaldialog" role="document">
            <div class="modal-content">
                <div class="wr-popuptitle">
                    <h4 id="wr_project_ratingtitle"><?php esc_html_e('Complete task','workreap');?></h4>
                    <a href="javascript:void(0);" class="close"><i class="wr-icon-x" data-bs-dismiss="modal"></i></a>
                </div>
                <div class="modal-body wr-taskcomplete_popup" id="wr_taskcomplete_form"></div>
            </div>
        </div>
    </div>
    <?php do_action( 'workreap_project_completed_model' );?>
    <div class="modal fade wr-excfreelancerpopup" id="wr_excfreelancerpopup" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog wr-modaldialog" role="document">
            <div class="modal-content" id="wr_wr_viewrating">
            </div>
        </div>
    </div>
    <script type="text/template" id="tmpl-load-completedtask_form">
        <div class="wr-completetask">
            <div class="wr-themeform">
                <fieldset>
                    <div class="wr-themeform__wrap">
                        <div class="form-group">
                            <label class="wr-titleinput"><?php esc_attr_e('Feedback title','workreap');?></label>
                            <input type="text" class="form-control" id="wr_rating_title-{{data.order_id}}" name="title" placeholder="<?php esc_attr_e('Add feedback title','workreap');?>">
                        </div>
                        <div class="form-group">
                            <label class="wr-titleinput"><?php esc_attr_e('Task rating','workreap');?></label>
                            <div class="wr-my-ratingholder">
                                <ul id="wr_stars-{{data.order_id}}" class='wr-rating-stars wr_stars'>
                                    <li class='wr-star' data-value='1'  data-id="{{data.order_id}}">
                                        <i class='wr-icon-star fa-fw'></i>
                                    </li>
                                    <li class='wr-star' data-value='2'  data-id="{{data.order_id}}">
                                        <i class='wr-icon-star fa-fw'></i>
                                    </li>
                                    <li class='wr-star' data-value='3'  data-id="{{data.order_id}}">
                                        <i class='wr-icon-star fa-fw'></i>
                                    </li>
                                    <li class='wr-star' data-value='4'  data-id="{{data.order_id}}">
                                        <i class='wr-icon-star fa-fw'></i>
                                    </li>
                                    <li class='wr-star' data-value='5'  data-id="{{data.order_id}}">
                                        <i class='wr-icon-star fa-fw'></i>
                                    </li>
                                </ul>
                                <input type="hidden" id="wr_task_rating-{{data.order_id}}" name="rating" value="1">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="wr-titleinput"><?php esc_attr_e('Add feedback','workreap');?></label>
                            <textarea class="form-control" id="wr_rating_details-{{data.order_id}}" name="details" placeholder="<?php esc_attr_e('Feedback','workreap');?>"></textarea>
                        </div>
                        <div class="form-group wr-formbtn">
                            <ul class="wr-formbtnlist">
                                <li id="wr_without_feedback">
                                    <a href="javascript:void(0);" data-task_id="{{data.task_id}}" data-order_id="{{data.order_id}}" data-user_id="<?php echo intval($url_identity);?>" class="wr-btn wr-plainbtn wr_complete_task"><?php esc_html_e('Complete without feedback','workreap');?></a>
                                </li>
                                <li><a href="javascript:void(0);" data-task_id="{{data.task_id}}" data-user_id="<?php echo intval($url_identity);?>" data-order_id="{{data.order_id}}" class="wr-btn wr-greenbg wr_rating_task"><?php esc_html_e('Submit','workreap');?></a></li>
                            </ul>
                        </div>
                    </div>
                </fieldset>
            </div>
        </div>
    </script>
    <script type="text/template" id="tmpl-load-completedproject_form">
        <div class="wr-completetask">
            <div class="wr-themeform">
                <fieldset>
                    <div class="wr-themeform__wrap">
                        <div class="form-group">
                            <label class="wr-titleinput"><?php esc_attr_e('Feedback title','workreap');?></label>
                            <input type="text" class="form-control" id="wr_rating_title-{{data.order_id}}" name="title" placeholder="<?php esc_attr_e('Add feedback title','workreap');?>">
                        </div>
                        <div class="form-group">
                            <label class="wr-titleinput"><?php esc_attr_e('Project rating','workreap');?></label>
                            <div class="wr-my-ratingholder">
                                <ul id="wr_stars-{{data.proposal_id}}" class='wr-rating-stars wr_stars'>
                                    <li class='wr-star active' data-value='1'  data-id="{{data.proposal_id}}">
                                        <i class='wr-icon-star fa-fw'></i>
                                    </li>
                                    <li class='wr-star' data-value='2'  data-id="{{data.proposal_id}}">
                                        <i class='wr-icon-star fa-fw'></i>
                                    </li>
                                    <li class='wr-star' data-value='3'  data-id="{{data.proposal_id}}">
                                        <i class='wr-icon-star fa-fw'></i>
                                    </li>
                                    <li class='wr-star' data-value='4'  data-id="{{data.proposal_id}}">
                                        <i class='wr-icon-star fa-fw'></i>
                                    </li>
                                    <li class='wr-star' data-value='5'  data-id="{{data.proposal_id}}">
                                        <i class='wr-icon-star fa-fw'></i>
                                    </li>
                                </ul>
                                <input type="hidden" id="wr_task_rating-{{data.proposal_id}}" name="rating" value="1">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="wr-titleinput"><?php esc_attr_e('Add feedback','workreap');?></label>
                            <textarea class="form-control" id="wr_rating_details-{{data.proposal_id}}" name="details" placeholder="<?php esc_attr_e('Feedback','workreap');?>"></textarea>
                        </div>
                        <div class="form-group wr-formbtn">
                            <ul class="wr-formbtnlist">
                                <li id="wr_without_feedback">
                                    <a href="javascript:void(0);" data-proposal_id="{{data.proposal_id}}" data-user_id="<?php echo intval($url_identity);?>" class="wr-btn wr-plainbtn wr_complete_project"><?php esc_html_e('Complete without review','workreap');?></a>
                                </li>
                                <li><a href="javascript:void(0);" data-user_id="<?php echo intval($url_identity);?>" data-proposal_id="{{data.proposal_id}}" class="wr-btn-solid-lg wr-greenbg wr_rating_project"><?php esc_html_e('Complete contract','workreap');?></a></li>
                            </ul>
                        </div>
                    </div>
                </fieldset>
            </div>
        </div>
    </script>
    <script type="text/template" id="tmpl-load-project-rating">
        <div class="wr-completetask">
            <div class="wr-themeform">
                <fieldset>
                    <div class="wr-themeform__wrap">
                        <div class="form-group pt-0">
                            <div class="wr-my-ratingholder">
                                <ul id="wr_stars-{{data.proposal_id}}" class='wr-rating-stars wr_stars'>
                                    <li class='wr-star' data-value='1'  data-id="{{data.proposal_id}}">
                                        <i class='wr-icon-star fa-fw'></i>
                                    </li>
                                    <li class='wr-star' data-value='2'  data-id="{{data.proposal_id}}">
                                        <i class='wr-icon-star fa-fw'></i>
                                    </li>
                                    <li class='wr-star' data-value='3'  data-id="{{data.proposal_id}}">
                                        <i class='wr-icon-star fa-fw'></i>
                                    </li>
                                    <li class='wr-star' data-value='4'  data-id="{{data.proposal_id}}">
                                        <i class='wr-icon-star fa-fw'></i>
                                    </li>
                                    <li class='wr-star' data-value='5'  data-id="{{data.proposal_id}}">
                                        <i class='wr-icon-star fa-fw'></i>
                                    </li>
                                </ul>
                                <input type="hidden" id="wr_task_rating-{{data.proposal_id}}" name="rating" value="1">
                            </div>
                        </div>
                        <div class="form-group">
                            <input type="text" class="form-control" id="wr_rating_title-{{data.proposal_id}}" name="title" placeholder="<?php esc_attr_e('Add feedback title','workreap');?>">
                        </div>
                        <div class="form-group">
                            <textarea class="form-control" id="wr_rating_details-{{data.proposal_id}}" name="details" placeholder="<?php esc_attr_e('Feedback','workreap');?>"></textarea>
                        </div>
                        <div class="form-group wr-formbtn">
                            <ul class="wr-formbtnlist">
                                <li><a href="javascript:void(0);" data-user_id="<?php echo intval($url_identity);?>" data-proposal_id="{{data.proposal_id}}" class="wr-btn-solid-lg wr-greenbg wr_rating_project"><?php esc_html_e('Complete contract','workreap');?></a></li>
                            </ul>
                        </div>
                    </div>
                </fieldset>
            </div>
        </div>
    </script>
    <script type="text/template" id="tmpl-load-cancelledtask_form">
        <div class="wr-completetask">
            <div class="wr-themeform">
                <fieldset>
                    <div class="wr-themeform__wrap">
                        <div class="form-group">
                            <textarea class="form-control" id="wr_details-{{data.order_id}}" name="details" placeholder="<?php esc_attr_e('Add cancellation reason','workreap');?>"></textarea>
                        </div>
                        <div class="form-group wr-formbtn">
                            <ul class="wr-formbtnlist">
                                <li><a href="javascript:void(0);" data-task_id="{{data.task_id}}" data-order_id="{{data.order_id}}" data-user_id="<?php echo intval($url_identity);?>"  class="wr-btn wr-greenbg wr_cancelled_task"><?php esc_html_e('Cancel task','workreap');?></a></li>
                            </ul>
                        </div>
                    </div>
                </fieldset>
            </div>
        </div>
    </script>
    <script type="text/template" id="tmpl-load-rating_form">
        <div class="wr-completetask">
            <div class="wr-themeform">
                <fieldset>
                    <div class="wr-themeform__wrap">
                        <div class="form-group pt-0">
                            <div class="wr-my-ratingholder">
                                <ul id="wr_stars-{{data.order_id}}" class='wr-rating-stars wr_stars'>
                                    <li class='wr-star' data-value='1'  data-id="{{data.order_id}}">
                                        <i class='wr-icon-star fa-fw'></i>
                                    </li>
                                    <li class='wr-star' data-value='2'  data-id="{{data.order_id}}">
                                        <i class='wr-icon-star fa-fw'></i>
                                    </li>
                                    <li class='wr-star' data-value='3'  data-id="{{data.order_id}}">
                                        <i class='wr-icon-star fa-fw'></i>
                                    </li>
                                    <li class='wr-star' data-value='4'  data-id="{{data.order_id}}">
                                        <i class='wr-icon-star fa-fw'></i>
                                    </li>
                                    <li class='wr-star' data-value='5'  data-id="{{data.order_id}}">
                                        <i class='wr-icon-star fa-fw'></i>
                                    </li>
                                </ul>
                                <input type="hidden" id="wr_task_rating-{{data.order_id}}" name="rating" value="1">
                            </div>
                        </div>
                        <div class="form-group">
                            <input type="text" class="form-control" id="wr_rating_title-{{data.order_id}}" name="title" placeholder="<?php esc_attr_e('Add feedback title','workreap');?>">
                        </div>
                        <div class="form-group">
                            <textarea class="form-control" id="wr_rating_details-{{data.order_id}}" name="details" placeholder="<?php esc_attr_e('Feedback','workreap');?>"></textarea>
                        </div>
                        <div class="form-group wr-formbtn">
                            <ul class="wr-formbtnlist">
                                <li><a href="javascript:void(0);" data-task_id="{{data.task_id}}" data-order_id="{{data.order_id}}" class="wr-btn wr-greenbg wr_taskrating_task"><?php esc_html_e('Complete now','workreap');?></a></li>
                            </ul>
                        </div>
                    </div>
                </fieldset>
            </div>
        </div>
    </script>
<?php }
do_action('workreap_dashboard_after_wrapper');
get_footer();
