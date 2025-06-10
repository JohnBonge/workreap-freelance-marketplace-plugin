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
global $post;
$post_id        = !empty($args['post_id']) ? intval($args['post_id']) : $post->ID;
$wr_post_meta   = get_post_meta( $post_id,'wr_post_meta',true );
$wr_post_meta   = !empty($wr_post_meta) ? $wr_post_meta : array();
$education      = !empty($wr_post_meta['education']) ? $wr_post_meta['education'] : array();
$tab_content    = '';
if( !empty($education) ){ ?>
    <div class="wr-asidebox wr-freelancerinfo">
        <div class="wr-freesingletitle">
            <h4><?php esc_html_e('Qualification background','workreap');?></h4>
        </div>
        <div id="wr-themeaccordion" class="wr-themeaccordion">
            <?php foreach($education as $key => $value ){
                $degree_title	= !empty($value['title']) ? $value['title'] : '';
                $institute		= !empty($value['institute']) ? $value['institute'] : '';
                $enddate 		= !empty( $value['end_date'] ) ? $value['end_date'] : '';
                $description    = !empty($value['description']) ? $value['description'] : '';
                $end_date 		= !empty( $enddate ) ? date_i18n(get_option( 'date_format' ), strtotime(apply_filters('workreap_date_format_fix',$enddate ))) : '';
                ?>
                <div class="wr-themeaccordion_item">
                    <div class="wr-themeaccordion_head" id="heading-<?php echo esc_attr($key);?>">
                        <div class="wr-themeaccordion_title collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-<?php echo esc_attr($key);?>" aria-expanded="false">
                            <h6>
                                 <?php if( !empty($institute) ){?>
                                    <span><?php echo esc_html($institute);?></span>
                                <?php } ?>
                                <?php 
                                    if( !empty($degree_title) ){
                                        echo esc_html($degree_title);
                                    }
                                ?>
                                <?php if( !empty($end_date) ){?>
                                    <span><?php echo esc_html($end_date);?></span>
                                <?php } ?>
                            </h6>
                        </div>
                    </div>
                    <div id="collapse-<?php echo esc_attr($key);?>" class="wr-themeaccordion_info collapse" aria-labelledby="heading-<?php echo esc_attr($key);?>" data-bs-parent="#wr-themeaccordion">
                        <?php if( !empty($description) ){?>
                            <p><?php echo esc_html($description);?></p>
                        <?php } ?>
                    </div>
                </div>
            <?php } ?>
        </div>
    </div>
<?php }

