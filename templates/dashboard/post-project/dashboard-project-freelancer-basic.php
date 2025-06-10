<?php
global  $workreap_settings;
$employer_dispute_issues      = !empty($workreap_settings['employer_project_dispute_issues']) ? $workreap_settings['employer_project_dispute_issues'] : array();
$tpl_terms_conditions   = !empty( $workreap_settings['tpl_terms_conditions'] ) ? $workreap_settings['tpl_terms_conditions'] : '';
$tpl_privacy            = !empty( $workreap_settings['tpl_privacy'] ) ? $workreap_settings['tpl_privacy'] : '';
$term_link              = !empty($tpl_terms_conditions) ? '<a target="_blank" href="'.get_the_permalink($tpl_terms_conditions).'">'.get_the_title($tpl_terms_conditions).'</a>' : '';
$privacy_link           = !empty($tpl_privacy) ? '<a target="_blank" href="'.get_the_permalink($tpl_privacy).'">'.get_the_title($tpl_privacy).'</a>' : '';

$proposal_id    = !empty($args['proposal_id']) ? intval($args['proposal_id']) : 0;
$project_id     = !empty($args['project_id']) ? intval($args['project_id']) : 0;
$project_title  = !empty($args['project_title']) ? esc_attr($args['project_title']) : 0;
$freelancer_id      = !empty($args['freelancer_id']) ? intval($args['freelancer_id']) : 0;
$proposal_status= !empty($args['proposal_status']) ? esc_attr($args['proposal_status']) :'';
$complete_option= !empty($args['complete_option']) ? esc_attr($args['complete_option']) :'';
$proposal_type  = get_post_meta( $proposal_id, 'proposal_type', true );
$profile_id     = workreap_get_linked_profile_id($freelancer_id, '','freelancers');
$user_name      = workreap_get_username($profile_id);
$avatar         = apply_filters( 'workreap_avatar_fallback', workreap_get_user_avatar(array('width' => 50, 'height' => 50), $profile_id), array('width' => 50, 'height' => 50));
$proposal_price = isset($args['proposal_meta']['price']) ? $args['proposal_meta']['price'] : 0;
do_action('workreap_project_completed_form',$args);
?>
<div class="wr-projectsstatus_head">
    <div class="wr-projectsstatus_info">
        <?php if( !empty($avatar) ){?>
            <figure class="wr-projectsstatus_img">
                <img src="<?php echo esc_url($avatar);?>" alt="<?php echo esc_attr($user_name);?>">
            </figure>
        <?php } ?>
        <div class="wr-projectsstatus_name">
            <?php do_action( 'workreap_freelancer_proposal_status_tag', $proposal_id );?>
            <?php if( !empty($user_name) ){?>
                <h5><?php echo esc_html($user_name);?></h5>
            <?php } ?>
        </div>
    </div>
    <div class="wr-projectsstatus_budget">
        <strong>
            <span>
            <?php if( empty($proposal_type) || $proposal_type === 'fixed') {
                    workreap_price_format($proposal_price);
                } else {
                    do_action( 'workreap_proposal_listing_price', $proposal_id );
                }?>    
            </span>
            <?php do_action( 'workreap_project_estimation_html', $project_id );?>
            <?php 
                if( empty($proposal_type) || $proposal_type === 'fixed') {
                    esc_html_e('Total project budget','workreap');
                }
            ?>
        </strong>
        <?php if( !empty($proposal_status) && in_array($proposal_status,array('hired','cancelled')) ){?>
            <div class="wr-projectsstatus_option">
                <a href="javascript:void(0);"><i class="wr-icon-more-horizontal"></i></a>
                <ul class="wr-contract-list">
                    <?php if( !empty($proposal_status) && $proposal_status === 'hired' ){?>
                        <?php if( !empty($complete_option) && $complete_option === 'yes' ){?>
                            <li>
                                <span class="wr_proposal_completed" data-proposal_id="<?php echo intval($proposal_id);?>" data-title="<?php echo esc_attr($project_title);?>"><?php esc_html_e('Complete contract','workreap');?></span>
                            </li>
                        <?php } ?>
                    <?php } ?>
                    <?php if( !empty($proposal_status) && in_array($proposal_status,array('hired')) ){?>
                        <li>
                            <span id="taskrefundrequest"><?php esc_html_e('Create refund request','workreap');?></span>
                        </li>
                        <?php } 
                        if( !empty($proposal_type) && $proposal_type !='fixed'){
                            do_action('workreap_project_history_menu',$proposal_id,$proposal_type);
                        } ?>
                </ul>
            </div>
        <?php } ?>
    </div>
</div>
<script type="text/template" id="tmpl-load-task-refund-request">
    <div class="modal-dialog wr-modaldialog" role="document">
        <div class="modal-content">
            <div class="wr-popuptitle">
                <h4><?php esc_html_e('Create refund request', 'workreap') ?></h4>
                <a href="javascript:void(0);" class="close"><i class="wr-icon-x" data-bs-dismiss="modal"></i></a>
            </div>
            <div class="modal-body">
                <div class="wr-popupbodytitle">
                    <?php esc_html_e('Choose issue you want to highlight', 'workreap') ?>
                </div>
                <form name="refund-request" id="project-refund-request">
                    <input type="hidden" name="proposal_id" value="<?php echo intval($proposal_id); ?>">
                    <div class="wr-disputelist">
                        <ul class="wr-radiolist">
                            <?php if (!empty($employer_dispute_issues)) {
                                foreach ($employer_dispute_issues as $key => $issue) { ?>
                                    <li>
                                        <div class="wr-radio">
                                            <input type="radio" id="f-option-<?php echo esc_attr($key); ?>" value="<?php echo esc_attr($issue); ?>" name="dispute_issue">
                                            <label for="f-option-<?php echo esc_attr($key); ?>"><?php echo esc_html($issue); ?></label>
                                        </div>
                                    </li>
                                <?php }
                            } ?>
                        </ul>
                    </div>
                    <div class="wr-popupbodytitle">
                        <h5><?php esc_html_e('Add dispute details', 'workreap'); ?></h5>
                    </div>
                    <textarea class="form-control" placeholder="<?php esc_attr_e('Enter dispute details', 'workreap'); ?>" id="dispute-details" name="dispute-details"></textarea>
                    <div class="wr-popupbtnarea">
                        <div class="wr-checkterm">
                            <div class="wr-checkbox">
                                <input id="check3" type="checkbox" name="dispute_terms">
                                <label for="check3">
                                    <span>
                                        <?php echo sprintf(esc_html__('By clicking you agree with our %s and %s', 'workreap'), $term_link, $privacy_link); ?>
                                    </span>
                                </label>
                            </div>
                        </div>
                        <a href="javascript:void(0);" id="projectrefundrequest-submit" class="wr-btn"><?php esc_html_e('Submit', 'workreap'); ?> <span class="rippleholder wr-jsripple"><em class="ripplecircle"></em></span></a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</script>
<?php 
$scripts = '
jQuery(document).ready(function () {
    jQuery(".wr-projectsstatus_option > a").on("click",function() {
        jQuery(".wr-contract-list").slideToggle();
    });
});
';
wp_add_inline_script('workreap', $scripts, 'after');