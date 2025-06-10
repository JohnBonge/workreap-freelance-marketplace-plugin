<?php
    $proposal_id    = !empty($args['proposal_id']) ? intval($args['proposal_id']) : 0;
    $project_id     = !empty($args['project_id']) ? intval($args['project_id']) : 0;
    $freelancer_id      = !empty($args['freelancer_id']) ? intval($args['freelancer_id']) : 0;
    $proposal_status= !empty($args['proposal_status']) ? esc_attr($args['proposal_status']) : '';
    $proposal_meta  = !empty($args['proposal_meta']) ? ($args['proposal_meta']) : array();

    $hired_balance      = !empty($args['hired_balance']) ? ($args['hired_balance']) : 0;
    $earned_balance     = !empty($args['earned_balance']) ? ($args['earned_balance']) : 0;
    $remaning_balance   = !empty($args['remaning_balance']) ? ($args['remaning_balance']) : 0;
    $mileastone_array   = !empty($args['mileastone_array']) ? ($args['mileastone_array']) : array();
    $completed_mil_array= !empty($args['completed_mil_array']) ? ($args['completed_mil_array']) : array();

?>
<div class="wr-counterinfo">
    <ul class="wr-counterinfo_list">
        <li>
            <strong class="wr-counterinfo_escrow"><i class="wr-icon-clock"></i></strong>
            <span><?php esc_html_e('Total escrow amount','workreap');?></span>
            <h5><?php workreap_price_format($hired_balance);?> </h5>
        </li>
        <li>
            <strong class="wr-counterinfo_earned"><i class="wr-icon-briefcase"></i></strong>
            <span><?php esc_html_e('Total earned amount','workreap');?></span>
            <h5><?php workreap_price_format($earned_balance);?></h5>
        </li>
        <li>
            <strong class="wr-counterinfo_remaining"><i class="wr-icon-dollar-sign"></i></strong>
            <span><?php esc_html_e('Remaining project budget','workreap');?></span>
            <h5><?php workreap_price_format($remaning_balance);?></h5>
        </li>
    </ul>
</div>
<?php if( !empty($mileastone_array) ){?>
    <div class="wr-projectsinfo">
        <div class="wr-projectsinfo_title">
            <h4><?php esc_html_e('Project roadmap','workreap');?></h4>
        </div>
        <ul class="wr-projectsinfo_list">
            <?php
                foreach($mileastone_array as $key => $value){
                    $status = !empty($value['status']) ? $value['status'] : '';
                    $price  = !empty($value['price']) ? $value['price'] : 0;
                    $title  = !empty($value['title']) ? $value['title'] : '';
                    $detail = !empty($value['detail']) ? $value['detail'] : '';
                    ?>
                    <li>
                        <div class="wr-statusview">
                            <div class="wr-statusview_head">
                                <div class="wr-statusview_title">
                                    <div class="wr-mile-title">
                                        <span><?php workreap_price_format($price);?></span>
                                        <?php do_action( 'workreap_milestone_proposal_status_tag', $status );?>
                                    </div>
                                    <?php if( !empty($title) ){?>
                                        <h5><?php echo esc_html($title);?></h5>
                                    <?php } ?>
                                    <?php if( !empty($detail) ){?>
                                        <p><?php echo esc_html($detail);?></p>
                                    <?php } ?>
                                </div>
                            </div>
                            <?php if( !empty($proposal_status) && $proposal_status === 'hired' ){?>
                                <?php if( !empty($status) && $status === 'decline' && !empty($value['decline_reason'])){?>
                                    <div class="wr-statusview_alert">
                                        <span><i class="wr-icon-info"></i><?php esc_html_e('The employer declined this milestone invoice. Read the comment below and try again','workreap');?></span>
                                        <p><?php echo esc_html($value['decline_reason']);?></p>
                                    </div>
                                <?php } ?>
                                <?php if( !empty($status) && in_array($status,array('hired','decline')) ){?>
                                    <div class="wr-statusview_btns">
                                        <span class="wr-btn_approve wr_update_milestone" data-status="requested" data-id="<?php echo intval($proposal_id);?>" data-key="<?php echo esc_attr($key);?>"><?php esc_html_e('Mark as completed','workreap');?></span>
                                    </div>
                                <?php } ?>
                            <?php } ?>
                        </div>
                    </li>
            <?php } ?>
        </ul>
    </div>
<?php } ?>
<?php if( !empty($completed_mil_array) ){?>
    <div class="wr-projectsinfo">
        <div class="wr-projectsinfo_title">
            <h4><?php esc_html_e('Completed milestones','workreap');?></h4>
        </div>
        <ul class="wr-projectsinfo_list">
            <?php
                foreach($completed_mil_array as $key => $value){
                    $status = !empty($value['status']) ? $value['status'] : '';
                    $price  = !empty($value['price']) ? $value['price'] : 0;
                    $title  = !empty($value['title']) ? $value['title'] : '';
                    $detail = !empty($value['detail']) ? $value['detail'] : '';
                    ?>
                    <li>
                        <div class="wr-statusview">
                            <div class="wr-statusview_head">
                                <div class="wr-statusview_title">
                                    <div class="wr-mile-title">
                                        <span><?php workreap_price_format($price);?></span>
                                        <?php do_action( 'workreap_milestone_proposal_status_tag', $status );?>
                                    </div>
                                    <?php if( !empty($title) ){?>
                                        <h5><?php echo esc_html($title);?></h5>
                                    <?php } ?>
                                    <?php if( !empty($detail) ){?>
                                        <p><?php echo esc_html($detail);?></p>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>
                    </li>
            <?php } ?>
        </ul>
    </div>
<?php }