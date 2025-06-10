<?php
global  $workreap_settings;
$freelancer_dispute_issues      = !empty($workreap_settings['freelancer_project_dispute_issues']) ? $workreap_settings['freelancer_project_dispute_issues'] : array();

$tpl_terms_conditions   = !empty( $workreap_settings['tpl_terms_conditions'] ) ? $workreap_settings['tpl_terms_conditions'] : '';
$tpl_privacy            = !empty( $workreap_settings['tpl_privacy'] ) ? $workreap_settings['tpl_privacy'] : '';
$term_link              = !empty($tpl_terms_conditions) ? '<a target="_blank" href="'.get_the_permalink($tpl_terms_conditions).'">'.get_the_title($tpl_terms_conditions).'</a>' : '';
$privacy_link           = !empty($tpl_privacy) ? '<a target="_blank" href="'.get_the_permalink($tpl_privacy).'">'.get_the_title($tpl_privacy).'</a>' : '';

$proposal_status= !empty($args['proposal_status']) ? esc_attr($args['proposal_status']) :'';
$proposal_id    = !empty($args['proposal_id']) ? intval($args['proposal_id']) : 0;
$project_id     = !empty($args['project_id']) ? intval($args['project_id']) : 0;
$employer_id       = !empty($args['employer_id']) ? intval($args['employer_id']) : 0;
$proposal_meta  = !empty($args['proposal_meta']) ? ($args['proposal_meta']) :array();
$proposal_type  = get_post_meta( $proposal_id, 'proposal_type', true );
$profile_id     = workreap_get_linked_profile_id($employer_id, '','employers');
$user_name      = workreap_get_username($profile_id);
$avatar         = apply_filters( 'workreap_avatar_fallback', workreap_get_user_avatar(array('width' => 50, 'height' => 50), $profile_id), array('width' => 50, 'height' => 50));
$proposal_price = isset($args['proposal_meta']['price']) ? $args['proposal_meta']['price'] : 0;
$milestone_total= isset($args['milestone_total']) ? esc_attr($args['milestone_total']) : '';
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
            <?php if( empty($proposal_type) || $proposal_type === 'fixed') { esc_html_e('Total project budget','workreap'); }?>
        </strong>
        <?php if( !empty($proposal_status) && in_array($proposal_status,array('hired')) ){?>
            <div class="wr-projectsstatus_option">
                <a href="javascript:void(0);"><i class="wr-icon-more-horizontal"></i></a>
                <ul class="wr-contract-list">
                    <li>
                        <span id="taskrefundrequest"><?php esc_html_e('Raise a dispute','workreap');?></span>
                    </li>
                    <?php if( !empty($milestone_total) && !empty($proposal_price) && !empty($proposal_meta['proposal_type']) && $proposal_meta['proposal_type'] === 'milestone' && $proposal_price > $milestone_total ){?>
                        <li>
                            <span id="wr-add_milestone"><?php esc_html_e('Ceate a milestone','workreap');?></span>
                        </li>
                    <?php } ?>
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
                            <?php if (!empty($freelancer_dispute_issues)) {
                                foreach ($freelancer_dispute_issues as $key => $issue) { ?>
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
<?php if( !empty($milestone_total) && !empty($proposal_price) && $proposal_price > $milestone_total && !empty($proposal_meta['proposal_type']) && $proposal_meta['proposal_type'] === 'milestone' ){?>
	<div class="modal fade wr-workinghours-popup" id="tbaddmilestone" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
           <div class="wr-popup_title">
               <h5><?php esc_html_e('Add new milestone','workreap');?></h5>
               <a href="javascrcript:void(0)" data-bs-dismiss="modal">
                   <i class="wr-icon-x"></i>
               </a>
           </div>
            <div class="modal-body wr-popup-content">
                <form class="wr-themeform" id="wr_submit_milestone">
                    <fieldset>
                        <div class="wr-themeform__wrap">
                        	<label class="wr-label"><?php esc_html_e('How many milestones you want to add?','workreap');?>
                                <a href="javascript:void(0)" class="wr-addicon" id="wr-add-milestone"><?php esc_html_e('Add milestone','workreap');?><i class="wr-icon-plus"></i></a>
                            </label>
                            <div id="wr-list-milestone" class="wr-dragslots">                                
                            </div>
                            <input type="hidden" type="text" value="<?php echo intval($proposal_id);?>" name="proposal_id">
                            <div class="form-group wr-btnarea">
                                <button class="wr-btn-solid-lg wr-success-tag" id="wr_add_milestonebtn"><?php esc_html_e('Add new milestone','workreap');?></button>
                            </div>
                        </div>
                    </fieldset>
                </form>
            </div>
          </div>
        </div>
    </div>
    <script type="text/template" id="tmpl-load-project-milestone">
        <div class="form-group" id="workreap-milestone-{{data.id}}">
            <div class="wr-milestones-prices">
                <div class="wr-grapinput">
                    <div class="wr-milestones-input">
                        <div class="wr-placeholderholder wr-addslots">
                            <input type="text" name="milestone[{{data.id}}][price]" class="form-control wr-themeinput" placeholder="<?php esc_attr_e('Enter price','workreap');?>">
                        </div>
                        <div class="wr-placeholderholder">
                            <input type="text" name="milestone[{{data.id}}][title]" class="form-control wr-themeinput" placeholder="<?php esc_attr_e('Enter title','workreap');?>">
                        </div>
                        <a href="javascript:;" data-id="{{data.id}}" class="wr-remove-milestone wr-removeicon"><i class="wr-icon-trash-2"></i></a>
                    </div>
                    <div class="wr-placeholderholder">
                        <textarea class="form-control wr-themeinput" name="milestone[{{data.id}}][detail]" placeholder="<?php esc_attr_e('Enter description', 'workreap');?>"></textarea>
                    </div>
                </div>
                <input type="hidden" name="milestone[{{data.id}}][status]" value="">
            </div>
        </div>
	</script>
<?php } ?>
<?php 
$scripts = '
jQuery(document).ready(function () {
    jQuery(".wr-projectsstatus_option > a").on("click",function() {
        jQuery(".wr-contract-list").slideToggle();
    });
    removeMilestone();
    jQuery("#wr-add-milestone").on("click", function (e) {
		let counter 	            = workreap_unique_increment(10);
		var load_milestone_temp 	= wp.template("load-project-milestone");
		var data 		            = {id: counter};
		load_milestone_temp	        = load_milestone_temp(data);
		jQuery("#wr-list-milestone").append(load_milestone_temp);
		removeMilestone();
	});
    function removeMilestone(){
		jQuery(".wr-remove-milestone").on("click", function (e) {
			jQuery(this).closest(".wr-milestones-prices").remove();
		});
	}
	var wr_sortable = document.getElementById("wr-list-milestone");
	if (wr_sortable !== null) {
		var wr_sortable = Sortable.create(wr_sortable, {
			animation: 350,
		});
	} 
});
';
wp_add_inline_script('workreap', $scripts, 'after');