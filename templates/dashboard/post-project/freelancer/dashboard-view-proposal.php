<?php
    $proposal_id	= !empty($args['id']) ? intval($args['id']) : 3767;
    $project_id     = get_post_meta( $proposal_id, 'project_id', true );    
    $project_id     = !empty($project_id) ? intval($project_id) : 0;
    $product            = wc_get_product($project_id);
    $product_author_id  = get_post_field ('post_author', $project_id);
    $linked_profile_id  = workreap_get_linked_profile_id($product_author_id, '','employers');
    $user_name          = workreap_get_username($linked_profile_id);
?>
<div id="fixed-project" class="wr-projectdetail-sidebar mCustomScrollbar">
    <div class="wr-project-wrapper-two">
        <div class="wr-project-box wr-employerproject">
            <div class="wr-employerproject-title">
                <?php do_action( 'workreap_freelancer_proposal_status_tag', $proposal_id );?>
                <?php do_action( 'workreap_project_type_tag', $project_id );?>
                <div class="wr-verified-info">
                    <?php echo esc_html($user_name);?><?php do_action( 'workreap_verification_tag_html', $linked_profile_id ); ?>
                    <?php if( !empty($product->get_name()) ){?>
                        <h5><?php echo esc_html($product->get_name());?></h5>
                    <?php } ?>
                </div>
                <ul class="wr-template-view"> 
                    <?php do_action( 'workreap_posted_date_html', $product );?>
                    <?php do_action( 'workreap_location_html', $product );?>
                    <?php do_action( 'workreap_texnomies_html_v2', $product->get_id(),'expertise_level','wr-icon-briefcase' );?>
                    <?php do_action( 'workreap_hiring_freelancer_html', $product );?>
                </ul>
            </div>
            <a href="javascript:void(0)" class="wr-sidebar-close"><i class="wr-icon-x"></i></a>
        </div>
    </div>
</div>