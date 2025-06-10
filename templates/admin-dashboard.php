<?php
/**
 * Template Name: Admin Dashboard
 *
 * @package     Workreap
 * @subpackage  Workreap/templates
 * @author      Amentotech <info@amentotech.com>
 * @link        https://codecanyon.net/user/amentotech/portfolio
 * @version     1.0
 * @since       1.0
*/
if( ! current_user_can('administrator') ){
    $redirect_url   = get_home_url();
    wp_redirect( $redirect_url );
    exit;
}

global $current_user, $workreap_settings;
$url_identity 	= !empty($_GET['identity']) ? intval($_GET['identity']) : '';
$reference 		= !empty($_GET['ref'] ) ? $_GET['ref'] : '';
$mode 			= !empty($_GET['mode']) ? $_GET['mode'] : '';

if(isset($_POST['months']) || isset($_POST['years']) && $reference == 'earnings' && $mode == 'manage'){
	$month	    = !empty($_POST['months']) ? sprintf("%02d", $_POST['months']) : '';
	$year	    = !empty($_POST['years']) ? $_POST['years'] : '';
	$file_name	= !empty($month) ? $month.'-'.$year : 'earnings';
	header('Content-Type: text/csv');
	header('Content-Disposition: attachment; filename="'.$file_name.'.csv"');

	ob_end_clean();

	$output_handle 		= fopen('php://output', 'w');
	$payout_methods		= workreap_get_payouts_lists();
	$filename           = "website_data_" . date('Ymd') . ".xls";
	$withdraw_titles	= array(
        esc_html__('User name','workreap'),
        esc_html__('Account title','workreap'),
        esc_html__('Price','workreap'),
        esc_html__('Status','workreap'),
        esc_html__('Month','workreap'),
        esc_html__('Year','workreap'),
        esc_html__('Details','workreap'),
	);

	$staus		= array('pending','publish','rejected');
	$args_array = array(
		'post_status'		=> $staus,
		'post_type'			=> 'withdraw',
		'posts_per_page' 	=> -1,
	);


	if( !empty($year) ){
		$args_array['meta_query'][] = array('key' 		=> '_year',
											'value' 	=> intval($year),
											'compare' 	=> '=',
										);
	}

	if( !empty($month) ){
		$args_array['meta_query'][] = array(
											'key' 		=> '_month',
											'value' 	=> $month,
											'compare' 	=> '=',
										);
	}

	$post_data		= get_posts($args_array);
	$csv_fields     = array();

	foreach($withdraw_titles as $title){
		$csv_fields[] = $title;
	}

	fputcsv($output_handle, $csv_fields);

	if( !empty($post_data) ){
		foreach($post_data as $row){
			$post_author	= !empty($row->post_author) ? $row->post_author : 0;
			$freelancer_name	= !empty($post_author) ? workreap_get_username($post_author) : '';

			$account_name			= get_post_meta( $row->ID, '_payment_method' ,true);
			$account_name			= !empty($account_name) ? $account_name : '';

			$account_name_val	= !empty($account_name) && !empty($payout_methods[$account_name]['label']) ? $payout_methods[$account_name]['label'] : '';
			$account_details	= get_post_meta( $row->ID, '_account_details',true );
			$account_details	= !empty($account_details) ? maybe_unserialize( $account_details ) : array();

			$account_detail		= '';
			$payout_details	= array();

			if( !empty($payout_methods[$account_name]['fields'])) {
				foreach( $payout_methods[$account_name]['fields'] as $key => $field ){

					if(isset($account_details[$key])){
						$account_detail			.= $field['title'].':';
						$account_detail			.= ' ';
						$account_detail			.= !empty($account_details[$key]) ? $account_details[$key]."\r	" : '';
					}

				}
			}

			$price			= get_post_meta( $row->ID, '_withdraw_amount' ,true);
			$price			= !empty($price) ? $price : 0;
			$year			= get_post_meta( $row->ID, '_year' ,true);
			$year			= !empty($year) ? $year : 0;
			$month			= get_post_meta( $row->ID, '_month' ,true);
			$month			= !empty($month) ? $month : 0;
			$status			= get_post_status( $row->ID );
			$status			= !empty($status) ? ucfirst($status) : '';
			$row_data       = array();
			$row_data['freelancer_name']	= $freelancer_name;
			$row_data['account']		= $account_name_val;
			$row_data['price']			= html_entity_decode(workreap_price_format($price,'return'));
			$row_data['status']			= $status;
			$row_data['month']			= $month;
			$row_data['year']			= $year;
			$row_data['details']		= $account_detail;
			$OutputRecord = $row_data;
			fputcsv($output_handle, $OutputRecord);
		}
	}

	fclose( $output_handle );
	exit;
}

$user_identity 	= intval($current_user->ID);
$user_type		= apply_filters('workreap_get_user_type', $user_identity );
$redirect_url   = '';

if( !is_user_logged_in() ){
    $redirect_url   = get_home_url();
} else if( !empty($user_type) && $user_type != 'administrator' ){
    $redirect_url  = !empty($workreap_settings['tpl_dashboard']) ? get_the_permalink( $workreap_settings['tpl_dashboard'] ) : '';
}

if( !empty($redirect_url) ){
    wp_redirect( $redirect_url );
    exit;
}

get_header();
$post_id		= workreap_get_linked_profile_id( $user_identity );
$user_name      = !empty($current_user->user_login) ? $current_user->user_login : '';
do_action('workreap_start_before_wrapper');

$avatar_url = get_avatar_url($current_user->ID,array('size',40));

?>

<div class="wr-mainwrapper">
    <div class="wr-sidebarwrapperholder">
        <aside id="wr-sidebarwrapper" class="wr-sidebarwrapper">
            <div id="wr-btnmenutogglev2" class="wr-btnmenutogglev2">
                <a href="javascript:void(0);"><i class="wr-icon-sliders"></i></a>
            </div>
            <div id="wr-btnmenutoggle" class="wr-btnmenutoggle">
                <a href="javascript:void(0);"><i class="wr-icon-sliders"></i></a>
            </div>
            <div class="wr-adminhead">
                <?php if( !empty($avatar_url) ){?>
                    <strong class="wr-adminhead__img">
                        <a href="javascript:void(0);">
                            <img src="<?php echo esc_url($avatar_url);?>" alt="<?php echo esc_attr($user_name);?>">
                        </a>
                    </strong>
                <?php } ?>
                <?php if( !empty($user_name) ){?>
                    <div class="wr-adminhead__title">
                        <h4><?php esc_html_e('Administrator','workreap');?></h4>
                        <span><?php echo esc_html($user_name);?></span>
                    </div>
                <?php } ?>
            </div>
            <nav id="wr-navdashboard" class="wr-navdashboard">
                <ul><?php workreap_get_template_part('dashboard/menus/admin/menu', 'list-items');?></ul>
            </nav>
        </aside>
    </div>
    <div class="wr-subwrapper">
        <div class="theme-container">
            <div class="wr-main">
                <div class="row">
                <?php if( have_posts() ):
                    while ( have_posts() ) : the_post();
                        the_content();
                        wp_link_pages( array(
                                'before'      => '<div class="wr-paginationvtwo"><nav class="wr-pagination"><ul>',
                                'after'       => '</ul></nav></div>',
                        ) );
                    endwhile;
                    if (is_user_logged_in() ) {
                        if ( !empty($reference) && !empty($mode) && $reference === 'task' && $mode === 'listing' && $user_type === 'freelancers') {
                            workreap_get_template_part('dashboard/dashboard', 'services-listing');
                        }else if ( !empty($reference) && $reference === 'inbox' ) {
                            workreap_get_template_part('admin-dashboard/dashboard', 'inbox');
                        }else if ( !empty($reference) && !empty($mode) && $reference === 'disputes' && $mode === 'listing') {
                            workreap_get_template_part('admin-dashboard/dashboard', 'disputes');
                        }else if ( !empty($reference) && !empty($mode) && $reference === 'disputes' && $mode === 'detail') {
                            workreap_get_template_part('admin-dashboard/dashboard', 'disputes-detail');
                        }else if ( !empty($reference) && !empty($mode) && $reference === 'project' && $mode === 'dispute') {
                            workreap_get_template_part('admin-dashboard/dashboard', 'project-disputes-detail');
                        }else if ( !empty($reference) && !empty($mode) && $reference === 'saved' && $mode === 'listing') {
                            workreap_get_template_part('admin-dashboard/dashboard', 'saved-items');
                        }else if ( !empty($reference) && !empty($mode) && $reference === 'notifications' && $mode === 'listing') {
                            workreap_get_template_part('admin-dashboard/dashboard', 'notifications');
                        }else if ( !empty($reference) && $reference === 'inbox' ) {
                            workreap_get_template_part('admin-dashboard/dashboard', 'inbox');
                        }else if ( !empty($reference) && $reference === 'earnings' && $mode === 'manage') {
                            workreap_get_template_part('admin-dashboard/dashboard', 'earnings');
                        } else if ( !empty($reference) && $reference === 'task') {
                            workreap_get_template_part('admin-dashboard/dashboard', 'task');
                        }  else if ( !empty($reference) && $reference === 'projects') {
                            workreap_get_template_part('admin-dashboard/dashboard', 'projects');
                        }  else {
                            workreap_get_template_part('admin-dashboard/dashboard', 'insights');
                        }
                    }
                endif; ?>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="wr_completetask" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog wr-modaldialog" role="document">
        <div class="modal-content">
            <div class="wr-popuptitle">
                <h4 id="wr_ratingtitle"><?php esc_html_e('Complete Task','workreap');?></h4>
                <a href="javascript:void(0);" class="close"><i class="wr-icon-x" data-bs-dismiss="modal"></i></a>
            </div>
            <div class="modal-body" id="wr_taskcomplete_form"></div>
        </div>
    </div>
</div>
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
                            <li id="wr_without_feedback"><a href="javascript:void(0);" data-task_id="{{data.task_id}}" data-order_id="{{data.order_id}}" class="wr-btn wr-plainbtn wr_complete_task"><?php esc_html_e('Complete without feedback','workreap');?></a></li>
                            <li><a href="javascript:void(0);" data-task_id="{{data.task_id}}" data-order_id="{{data.order_id}}" class="wr-btn wr-greenbg wr_rating_task"><?php esc_html_e('Complete now','workreap');?></a></li>
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
                            <li><a href="javascript:void(0);" data-task_id="{{data.task_id}}" data-order_id="{{data.order_id}}" class="wr-btn wr-greenbg wr_cancelled_task"><?php esc_html_e('Cancel task','workreap');?></a></li>
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

<?php
do_action('workreap_admin_dashboard_after_wrapper');
get_footer('admin');
