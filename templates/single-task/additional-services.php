<?php
/**
 * Single sub task
 *
 * @link       https://codecanyon.net/user/amentotech/portfolio
 * @since      1.0.0
 *
 * @package    Workreap
 * @subpackage Workreap_/public
 */
$max_services   = 6;
$total_services = !empty($workreap_subtask) && is_array($workreap_subtask) ? count($workreap_subtask) : 0;
if(!empty($workreap_subtask)){?>
<div class="wr-singleservice-tile">
    <div class="wr-addiservicesinfo">
        <div class="wr-addiservicesinfo_title">
            <h4><?php esc_html_e('Additional services', 'workreap');?></h4>
        </div>
        <ul class="wr-additionalservices">
            <?php 
                $counter    = 0;
                foreach($workreap_subtask as $workreap_subtask_id){
                    $counter++;
                    $price      = get_post_meta( $workreap_subtask_id, '_regular_price', true);
                    $li_class   = !empty($counter) && $counter > $max_services ? 'd-none' : 'wr-add-services';
                ?>
                <li class="<?php echo esc_attr($li_class);?>">
                    <div class="wr-additionalservices__content">
                        <div class="wr-additionalservices-title">
                            <h6><?php echo esc_html(get_the_title($workreap_subtask_id));?></h6>
                            <?php echo get_post_field('post_content', $workreap_subtask_id);?>
                        </div>
                        <div class="wr-additionalservice-price">
                            <h5><?php workreap_price_format($price);?></h5>
                        </div>
                    </div>
                </li>
            <?php }?>
            <?php if( !empty($total_services) && $total_services > $max_services ){ ?>
                <li class="wr-ad-load-more">
                    <div class="wr-selected__showmore">
                        <a href="javascript:void(0);"><?php esc_html_e('Load more','workreap');?></a>
                    </div>
                </li>
            <?php } ?>
        </ul>
    </div>
</div>
<?php }