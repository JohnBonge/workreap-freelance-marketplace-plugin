<?php
    $proposal_id    = !empty($args['proposal_id']) ? intval($args['proposal_id']) : 0;
    $project_id     = !empty($args['project_id']) ? intval($args['project_id']) : 0;
    $freelancer_id      = !empty($args['freelancer_id']) ? intval($args['freelancer_id']) : 0;
    $proposal_status= !empty($args['proposal_status']) ? esc_attr($args['proposal_status']) : 0;
    $proposal_meta  = !empty($args['proposal_meta']) ? ($args['proposal_meta']) : array();
    $user_identity  = !empty($args['user_identity']) ? intval($args['user_identity']) : 0;

    $hired_balance      = !empty($args['hired_balance']) ? ($args['hired_balance']) : 0;
    $earned_balance     = !empty($args['earned_balance']) ? ($args['earned_balance']) : 0;
    $remaning_balance   = !empty($args['remaning_balance']) ? ($args['remaning_balance']) : 0;
    $mileastone_array   = !empty($args['mileastone_array']) ? ($args['mileastone_array']) : array();
    $completed_mil_array= !empty($args['completed_mil_array']) ? ($args['completed_mil_array']) : array();
    $user_balance       = get_user_meta( $user_identity, '_employer_balance', true );
    $user_balance       = !empty($user_balance) ? $user_balance : 0;
    if( !empty($user_balance) ){
        $checkout_class         = 'wr_proposal_hiring';
    } else {
        $checkout_class     = 'wr_hire_proposal';
    }
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
            <span><?php esc_html_e('Total amount spent','workreap');?></span>
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
                                        <?php 
                                            if( isset($status) && $status != 'requested' ){
                                                do_action( 'workreap_milestone_proposal_status_tag', $status );
                                            }
                                        ?>
                                    </div>
                                    <?php if( !empty($title) ){?>
                                        <h5><?php echo esc_html($title);?></h5>
                                    <?php } ?>
                                    <?php if( !empty($detail) ){?>
                                        <p><?php echo esc_html($detail);?></p>
                                    <?php } ?>
                                </div>
                                
                            </div>
                            <?php if( !empty($status) && $status === 'decline' && !empty($value['decline_reason'])){?>
                                <div class="wr-statusview_alert">
                                    <span><i class="wr-icon-info"></i><?php esc_html_e('The employer declined this milestone invoice. Read the comment below and try again','workreap');?></span>
                                    <p><?php echo esc_html($value['decline_reason']);?></p>
                                </div>
                            <?php } ?>
                            <?php if( !empty($proposal_status) && $proposal_status === 'hired' ){?>
                                <?php if( !empty($status) && $status === 'requested' ){?>
                                    <div class="wr-statusview_btns">
                                        <span class="wr-btn_approve wr_update_milestone" data-status="completed" data-id="<?php echo intval($proposal_id);?>" data-key="<?php echo esc_attr($key);?>"><?php esc_html_e('Approve','workreap');?></span>
                                        <span class="wr-btn_decline" data-bs-target="#wr_milestone_declinereason-<?php echo esc_attr($key);?>" data-bs-toggle="modal" ><?php esc_html_e('Decline','workreap');?></span>
                                    </div>
                                    <div class="modal fade wr-declinereason" id="wr_milestone_declinereason-<?php echo esc_attr($key);?>" tabindex="-1" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                            <div class="wr-popup_title">
                                                <h5><?php esc_html_e('Add decline reason below','workreap');?></h5>
                                                <a href="javascrcript:void(0)" data-bs-dismiss="modal">
                                                    <i class="wr-icon-x"></i>
                                                </a>
                                            </div>
                                            <div class="modal-body wr-popup-content">
                                                <div class="wr-themeform">
                                                    <fieldset>
                                                        <div class="wr-themeform__wrap">
                                                            <div class="form-group">
                                                                <div class="wr-placeholderholder">
                                                                    <textarea id="milestone_declinereason-<?php echo esc_attr($key);?>" class="form-control wr-themeinput" placeholder="<?php esc_attr_e("Enter description","workreap");?>"></textarea>
                                                                </div>
                                                            </div>
                                                            <div class="wr-popup-terms form-group">
                                                                <button type="button" data-id="<?php echo intval($proposal_id);?>" data-status="decline" data-key="<?php echo esc_attr($key);?>" class="wr-btn-solid-lg wr_decline_milestone"><?php esc_html_e('Submit question now','workreap');?><i class="wr-icon-arrow-right"></i></button>
                                                            </div>
                                                        </div>
                                                    </fieldset>
                                                </div>
                                            </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php } else if(empty($status)){
                                    $is_offline_enabled = apply_filters('workreap_check_offline_payment_methods_enabled', false);
                                    if(!empty($is_offline_enabled) && !empty($value['milestone_order_id'])){ ?>
                                             <div class="wr-statusview_alert">
                                                <span><i class="wr-icon-info"></i><?php echo esc_html__('Pending by admin','workreap');?></span>
                                            </div>
                                        <?php
                                    } else { ?>
                                    <div class="wr-statusview_btns">
                                        <span class="wr-btn_decline <?php echo esc_attr($checkout_class);?>" data-key="<?php echo esc_attr($key);?>" data-id="<?php echo intval($proposal_id);?>"><?php esc_html_e('Escrow','workreap');?></span>
                                    </div>
                                <?php } 
                            } ?>
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