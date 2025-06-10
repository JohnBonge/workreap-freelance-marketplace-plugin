<?php
/**
 * The template part for displaying the dashboard Task graph summary for freelancer
 *
 * @package     Workreap
 * @subpackage  Workreap/templates/dashboard/earning_template
 * @author      Amentotech <info@amentotech.com>
 * @link        https://codecanyon.net/user/amentotech/portfolio
 * @version     1.0
 * @since       1.0
*/

global $current_user, $workreap_settings;
$application_access		= !empty($workreap_settings['application_access']) ? $workreap_settings['application_access'] : '';
$user_identity          = intval($current_user->ID);
$payment_type           = array('tasks','projects');
if($application_access == 'project_based'){
    $payment_type   = 'projects';
} else if($application_access === 'task_based'){
    $payment_type   = 'tasks';
}
if ( !class_exists('WooCommerce') ) {
	return;
}

$meta_array	= array(
    array(
      'key'         => 'freelancer_id',
      'value'   	=> $user_identity,
      'compare' 	=> '=',
      'type' 		=> 'NUMERIC'
    ),
    array(
      'key'		    => '_task_status',
      'value'   	=> 'completed',
      'compare' 	=> '=',
    ),
    array(
      'key'         => 'payment_type',
      'value'   	=> $payment_type,
      'compare' 	=> 'IN',
    )
);

$completed_tasks  = workreap_tasks_earnings('shop_order',array('wc-completed'),$meta_array);
//print_r($completed_tasks);
$graph_keys       = !empty($completed_tasks['key']) ? $completed_tasks['key'] : '';
$graph_values     = !empty($completed_tasks['values']) ? $completed_tasks['values'] : '';

wp_enqueue_script('chart');
wp_enqueue_script('utils-chart');

$currency_symbol  = get_woocommerce_currency_symbol();
$currency_position = get_option('woocommerce_currency_pos');

//Projects Area
$completed_projects = 0;
$ongoing_projects = 0;
$cancelled_projects = 0;

$workreap_projects_args = array(
    'post_type'         => 'proposals',
    'post_status'       => array('completed','hired','rejected'),
    'posts_per_page'    => -1,
    'author'            => $current_user->ID,
    'order'             => 'DESC'
);

$workreap_projects_query  = new WP_Query( apply_filters('workreap_project_dashbaord_listings_args', $workreap_projects_args) );

if ( $workreap_projects_query->have_posts() ){
    while ( $workreap_projects_query->have_posts() ){
        $workreap_projects_query->the_post();
        global $post;

        if($post->post_status === 'completed'){
            $completed_projects++;
        } elseif($post->post_status === 'hired'){
            $ongoing_projects++;
        } elseif($post->post_status === 'rejected'){
            $cancelled_projects++;
        }

    }
}

$workreap_project_url = Workreap_Profile_Menu::workreap_profile_menu_link('projects', $user_identity,true,'listing');

//Gigs Area
$gig_completed = 0;
$gig_queues = 0;
$gig_cancelled = 0;

$workreap_gigs_args   = array(
    'post_type'         => 'product',
    'post_status'       => 'any',
    'posts_per_page'    => -1,
    'author'            => $current_user->ID,
    'order'             => 'DESC',
    'tax_query'         => array(
        array(
            'taxonomy' => 'product_type',
            'field'    => 'slug',
            'terms'    => 'tasks',
        ),
    ),
);

$workreap_gigs_query  = new WP_Query( apply_filters('workreap_admin_service_listings_args', $workreap_gigs_args) );

if ( $workreap_gigs_query->have_posts() ){
    while ( $workreap_gigs_query->have_posts() ){
        $workreap_gigs_query->the_post();
        global $product;

        $product_id             = $product->get_id();
        $workreap_total_sales    = $product->get_total_sales();

        //Gigs Queues
        $meta_task_queues_array = array(
            array(
                'key' => 'task_product_id',
                'value' => $product_id,
                'compare' => '=',
                'type' => 'NUMERIC'
            ),
            array(
                'key' => '_task_status',
                'value' => 'hired',
                'compare' => '=',
            )
        );
        $workreap_task_queues = workreap_get_post_count_by_meta('shop_order', array('wc-pending', 'wc-on-hold', 'wc-processing', 'wc-completed'), $meta_task_queues_array);
        $gig_queues = $gig_queues + $workreap_task_queues;

        //Gigs Completed
        $meta_task_completed_array = array(
            array(
                'key' => 'task_product_id',
                'value' => $product_id,
                'compare' => '=',
                'type' => 'NUMERIC'
            ),
            array(
                'key' => '_task_status',
                'value' => 'completed',
                'compare' => '=',
            )
        );
        $workreap_task_completed = workreap_get_post_count_by_meta('shop_order', array('wc-completed'), $meta_task_completed_array);
        $gig_completed = $gig_completed + $workreap_task_completed;

        //Gigs Cancelled
        $meta_task_cancelled_array = array(
            array(
                'key' => 'task_product_id',
                'value' => $product_id,
                'compare' => '=',
                'type' => 'NUMERIC'
            ),
            array(
                'key' => '_task_status',
                'value' => 'cancelled',
                'compare' => '=',
            )
        );
        $workreap_task_cancelled = workreap_get_post_count_by_meta('shop_order', array('wc-cancelled', 'wc-refunded', 'wc-failed','wc-completed'), $meta_task_cancelled_array);
        $gig_cancelled = $gig_cancelled + $workreap_task_cancelled;

    }
}

$workreap_task_url = Workreap_Profile_Menu::workreap_profile_menu_link('orders', $user_identity,true,'listing');

?>
<div class="col-lg-8">
  <div class="wr-freelancer-counter">
      <ul class="wr-freelancer-counter-list wr-application-type-<?php echo esc_attr($application_access) ?>" id="wr-counter-two">
        <?php if($application_access !== 'task_based'): ?>
          <li>
              <div class="wr-counter-content">
                  <div class="wr-counter-icon-button">
                      <div class="wr-icon-green">
                          <i class="wr-icon-check-square"></i>
                      </div>
                      <div class="wr-counter-button">
                          <a href="<?php echo esc_url($workreap_project_url . '&order_type=completed') ?>" class="wr-counter-button-active"><?php esc_html_e('View','workreap'); ?></a>
                      </div>
                  </div>
                  <h3 class="wr-counter-value"><span class="counter-value" data-count="<?php esc_html_e($completed_projects); ?>"><?php esc_html_e($completed_projects); ?></span></h3>
                  <strong><?php esc_html_e('Completed projects','workreap'); ?></strong>
                  <div class="wr-icon-watermark">
                      <i class="wr-icon-check-square"></i>
                  </div>
              </div>
          </li>
          <li>
              <div class="wr-counter-content">
                  <div class="wr-counter-icon-button">
                      <div class="wr-icon-yellow">
                          <i class="wr-icon-watch"></i>
                      </div>
                      <div class="wr-counter-button">
                          <a class="wr-counter-button-active" href="<?php echo esc_url($workreap_project_url . '&order_type=hired') ?>"><?php esc_html_e('View','workreap'); ?></a>
                      </div>
                  </div>
                  <h3 class="wr-counter-value"><span class="counter-value" data-count="<?php esc_html_e($ongoing_projects); ?>"><?php esc_html_e($ongoing_projects); ?></span></h3>
                  <strong><?php esc_html_e('Ongoing projects','workreap'); ?></strong>
                  <div class="wr-icon-watermark">
                      <i class="wr-icon-watch"></i>
                  </div>
              </div>
          </li>
          <li>
              <div class="wr-counter-content">
                  <div class="wr-counter-icon-button">
                      <div class="wr-icon-red">
                          <i class="wr-icon-x-square"></i>
                      </div>
                      <div class="wr-counter-button">
                          <a class="wr-counter-button-active" href="<?php echo esc_url($workreap_project_url . '&order_type=refunded') ?>"><?php esc_html_e('View','workreap'); ?></a>
                      </div>
                  </div>
                  <h3 class="wr-counter-value"><span class="counter-value" data-count="<?php esc_html_e($cancelled_projects); ?>"><?php esc_html_e($cancelled_projects); ?></span></h3>
                  <strong><?php esc_html_e('Cancelled projects','workreap'); ?></strong>
                  <div class="wr-icon-watermark">
                      <i class="wr-icon-x-square"></i>
                  </div>
              </div>
          </li>
        <?php endif; ?>
        <?php if($application_access !== 'project_based'): ?>
          <li>
              <div class="wr-counter-content">
                  <div class="wr-counter-icon-button">
                      <div class="wr-icon-purple">
                          <i class="wr-icon-briefcase"></i>
                      </div>
                      <div class="wr-counter-button">
                          <a class="wr-counter-button-active" href="<?php echo esc_url($workreap_task_url . '&order_type=completed'); ?>"><?php esc_html_e('View','workreap'); ?></a>
                      </div>
                  </div>
                  <h3 class="wr-counter-value"><span class="counter-value" data-count="<?php esc_html_e($gig_completed); ?>"><?php esc_html_e($gig_completed); ?></span></h3>
                  <strong><?php esc_html_e('Task sold','workreap'); ?></strong>
                  <div class="wr-icon-watermark">
                      <i class="wr-icon-briefcase"></i>
                  </div>
              </div>
          </li>
          <li>
              <div class="wr-counter-content">
                  <div class="wr-counter-icon-button">
                      <div class="wr-icon-orange">
                          <i class="wr-icon-clock"></i>
                      </div>
                      <div class="wr-counter-button">
                          <a class="wr-counter-button-active" href="<?php echo esc_url($workreap_task_url . '&order_type=hired'); ?>"><?php esc_html_e('View','workreap'); ?></a>
                      </div>
                  </div>
                  <h3 class="wr-counter-value"><span class="counter-value" data-count="<?php esc_html_e($gig_queues); ?>"><?php esc_html_e($gig_queues); ?></span></h3>
                  <strong><?php esc_html_e('Ongoing tasks','workreap'); ?></strong>
                  <div class="wr-icon-watermark">
                      <i class="wr-icon-clock"></i>
                  </div>
              </div>
          </li>
          <li>
              <div class="wr-counter-content">
                  <div class="wr-counter-icon-button">
                      <div class="wr-icon-red">
                          <i class="wr-icon-x-octagon"></i>
                      </div>
                      <div class="wr-counter-button">
                          <a class="wr-counter-button-active" href="<?php echo esc_url($workreap_task_url . '&order_type=cancelled'); ?>"><?php esc_html_e('View','workreap'); ?></a>
                      </div>
                  </div>
                  <h3 class="wr-counter-value"><span class="counter-value" data-count="<?php esc_html_e($gig_cancelled); ?>"><?php esc_html_e($gig_cancelled); ?></span></h3>
                  <strong><?php esc_html_e('Cancelled tasks','workreap'); ?></strong>
                  <div class="wr-icon-watermark">
                      <i class="wr-icon-x-octagon"></i>
                  </div>
              </div>
          </li>
        <?php endif; ?>
      </ul>
  </div>
    <div class="wr-employercontainer">
        <div class="wr-tabfilter">
            <div class="wr-tabfiltertitle">
                <h5><?php esc_html_e('Earning history', 'workreap'); ?></h5>
            </div>
        </div>
        <div class="wr-tabfilteritem">
            <canvas id="canvaschart" class="wr-linechart"></canvas>
        </div>
    </div>
</div>
<?php
$script = "
window.addEventListener('load', (event) =>{
  var activity = document.getElementById('canvaschart');
  if (activity !== null) {
  activity.height = 100;
  var config = {
    type: 'line',
    data: {
      labels: [".do_shortcode( $graph_keys )."],
      datasets: [{
        pointBackgroundColor: window.chartColors.dark_blue,
        backgroundColor: 'rgba(0,117,214,0.03)',
        borderColor: window.chartColors.dark_blue,
        borderWidth: 1,
        fill: true,
        pointBorderColor: '#ffffff',
        pointHoverBackgroundColor: '#fad85a',
        data: [".do_shortcode( $graph_values )."],
      }]
    },
    options: {
      responsive: true,
      title:false,

      position: 'nearest',
      animation:{
        duration:1000,
        easing:'linear',
        delay: 1500,
      },
      interaction: {
        intersect: false,
        mode: 'point',
        },
      font: {
        family: 'Nunito'
      },
      plugins: {
        filler: {
        propagate: false,
        },
        tooltip: {
          yAlign: 'bottom',
          displayColors:false,
          padding:{
            x:15,
            top:15,
            bottom:9,
          },
          borderColor:'#eee',
          borderWidth:1,
          titleColor: '#353648',
          bodyColor: '#353648',
          bodySpacing: 6,
          titleMarginBottom: 9,
          backgroundColor:'rgba(255, 255, 255)',
          callbacks: {
            title: function(context){
              return '".esc_html__('Earning:','workreap')."'
            },
            label: function(context){
              return '".html_entity_decode($currency_symbol)."' + context.dataset.data[context.dataIndex]
            }
          }
        },
        legend:{
          display:false,
        },
      },
      elements: {
        line: {
        tension: 0.000001
        },
      },
      scales: {
        y:{
          ticks: {
          fontSize: 12, fontFamily: '', fontColor: '#000', fontStyle: '500',
          beginAtZero: true,
          callback: function(value, index, values) {
          if(parseInt(value) >= 1000){
                var formattedValue = value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ',');
                return " . ($currency_position == 'left' || $currency_position == 'left_space' ? '"' . html_entity_decode($currency_symbol) . '" + formattedValue' : 'formattedValue + "' . html_entity_decode($currency_symbol) . '"') . ";
          } else {
                return " . ($currency_position == 'left' || $currency_position == 'left_space' ? '"' . html_entity_decode($currency_symbol) . '" + value' : 'value + "' . html_entity_decode($currency_symbol) . '"') . ";
              }
            }
          }
        },
        x:{
        ticks: {fontSize: 12, fontFamily: '', fontColor: '#000', fontStyle: '500'},
        grid:{
            display : false
          }
        }
      },
      },
    }
    var ctx = document.getElementById('canvaschart').getContext('2d');

    var myLine = new Chart(ctx, config);
  };
})";
wp_add_inline_script( 'utils-chart', $script, 'after' );
