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
$experience     = !empty($wr_post_meta['experience']) ? $wr_post_meta['experience'] : array();
$list_num       = 4;
$countries  = workreap_get_countries();
if( !empty($experience) ){ ?>
    <div class="wr-asidebox wr-freelancerinfo">
        <div class="wr-freesingletitle">
            <h4><?php esc_html_e('Experience','workreap');?></h4>
        </div>
        <ul class="wr-themeaccordion">
            <?php $counter   = 0;
             $count_exp      = !empty($experience) && is_array($experience) ? count($experience) : 0;
            foreach($experience as $key => $value ){
                $counter ++;
                $job_title	    = !empty($value['job_title']) ? $value['job_title'] : '';
                $company		= !empty($value['company']) ? $value['company'] : '';
                $location		= !empty($value['location']) ? $value['location'] : '';
                $enddate 		= !empty( $value['end_date'] ) ? $value['end_date'] : '';
                $description    = !empty($value['description']) ? $value['description'] : '';
                $end_date 		= !empty( $enddate ) ? date_i18n(get_option( 'date_format' ), strtotime(apply_filters('workreap_date_format_fix',$enddate ))) : '';
                $li_class       = $counter > $list_num ? 'd-none wt-edu-hide' : '';
                $location       = !empty($location) && !empty($countries[$location]) ? $countries[$location] : '';
               ?>
                <li class="wr-themeaccordion_item <?php echo esc_attr($li_class);?>">
                <div class="wr-themeaccordion_content">
                    <?php 
                        if( !empty($job_title) ){
                            echo '<h6>'.esc_html($job_title).'</h6>';
                        }
                    ?>
                    <?php if( !empty($company) || !empty($location) || !empty($end_date) ){?>
                        <ul class="wt-field-options">
                            <?php if( !empty($company) ){?>
                                <li>
                                    <svg width="14" height="14" viewBox="0 0 14 14" fill="none">
                                        <path opacity="0.6" d="M9.55912 3.49996C9.55912 2.2113 8.51445 1.16663 7.22579 1.16663C5.93713 1.16663 4.89246 2.2113 4.89246 3.49996M4.89246 6.12496V7.87496M9.55912 6.12496V7.87496M12.4758 6.99996V9.09996C12.4758 10.4067 12.4758 11.0601 12.2215 11.5593C11.9978 11.9983 11.6408 12.3553 11.2018 12.579C10.7026 12.8333 10.0492 12.8333 8.74246 12.8333H5.70912C4.40233 12.8333 3.74894 12.8333 3.24981 12.579C2.81077 12.3553 2.45381 11.9983 2.23011 11.5593C1.97579 11.0601 1.97579 10.4067 1.97579 9.09996V6.99996M3.14246 6.99996H11.3091C11.8527 6.99996 12.1245 6.99996 12.3389 6.91115C12.6248 6.79274 12.8519 6.56562 12.9703 6.27976C13.0591 6.06536 13.0591 5.79356 13.0591 5.24996V5.24996C13.0591 4.70636 13.0591 4.43456 12.9703 4.22016C12.8519 3.9343 12.6248 3.70718 12.3389 3.58877C12.1245 3.49996 11.8527 3.49996 11.3091 3.49996H3.14246C2.59886 3.49996 2.32706 3.49996 2.11266 3.58877C1.82679 3.70718 1.59967 3.9343 1.48126 4.22016C1.39246 4.43456 1.39246 4.70636 1.39246 5.24996V5.24996C1.39246 5.79356 1.39246 6.06536 1.48126 6.27976C1.59967 6.56562 1.82679 6.79274 2.11266 6.91115C2.32706 6.99996 2.59886 6.99996 3.14246 6.99996Z" stroke="#585858" stroke-width="1.3125" stroke-linecap="round" stroke-linejoin="round"/>
                                    </svg>
                                    <?php echo esc_html($company);?>
                                </li>
                            <?php } ?>
                            <?php if( !empty($location) ){?>
                                <li>
                                    <svg width="15" height="14" viewBox="0 0 15 14" fill="none">
                                        <g opacity="0.6">
                                        <path d="M1.97583 6.12496C1.97583 3.20829 4.30916 1.16663 7.22583 1.16663C10.1425 1.16663 12.4758 3.20829 12.4758 6.12496C12.4758 10.7052 8.3925 12.8332 7.22583 12.8332C6.05916 12.8332 1.97583 10.7052 1.97583 6.12496Z" stroke="#585858" stroke-width="1.3125"/>
                                        <path d="M8.97583 6.41663C8.97583 7.38312 8.19233 8.16663 7.22583 8.16663C6.25933 8.16663 5.47583 7.38312 5.47583 6.41663C5.47583 5.45013 6.25933 4.66663 7.22583 4.66663C8.19233 4.66663 8.97583 5.45013 8.97583 6.41663Z" stroke="#585858" stroke-width="1.3125"/>
                                        </g>
                                    </svg>
                                    <?php echo esc_html($location);?>
                                </li>
                            <?php } ?>
                            <?php if( !empty($end_date) ){?>
                                <li>
                                    <svg width="14" height="14" viewBox="0 0 14 14" fill="none">
                                        <path opacity="0.6" d="M4.89252 0.583374V2.33337M9.55918 0.583374V2.33337M4.89252 7.00004H7.22585H9.55918M6.99252 12.8334H7.45918C9.41937 12.8334 10.3995 12.8334 11.1482 12.4519C11.8067 12.1163 12.3422 11.5809 12.6777 10.9223C13.0592 10.1737 13.0592 9.19356 13.0592 7.23337V6.76671C13.0592 4.80652 13.0592 3.82643 12.6777 3.07774C12.3422 2.41917 11.8067 1.88374 11.1482 1.54818C10.3995 1.16671 9.41937 1.16671 7.45918 1.16671H6.99252C5.03233 1.16671 4.05224 1.16671 3.30355 1.54818C2.64498 1.88374 2.10955 2.41917 1.77399 3.07774C1.39252 3.82643 1.39252 4.80652 1.39252 6.76671V7.23337C1.39252 9.19356 1.39252 10.1737 1.77399 10.9223C2.10955 11.5809 2.64498 12.1163 3.30355 12.4519C4.05224 12.8334 5.03233 12.8334 6.99252 12.8334Z" stroke="#585858" stroke-width="1.3125" stroke-linecap="round" stroke-linejoin="round"/>
                                    </svg>
                                    <?php echo esc_html($end_date);?>
                                </li>
                            <?php } ?>

                        </ul>
                        <?php if( !empty($description) ){?>
                            <div class="wr-description-container">
                                <p><?php echo workreapReadMoreDescription(do_shortcode($description),150);?></p>
                            </div>
                        <?php } ?>
                    <?php } ?>
                </div>
                </li>
                <?php if($counter == $list_num && $count_exp > $list_num){ ?>
                        <li class="wr-load-more wr-secondary-btn"><a href="javascript:;"><?php esc_html_e("Load more","workreap");?></a></li>
                <?php } ?>
            <?php } ?>
            </ul>
    </div>
<?php }

