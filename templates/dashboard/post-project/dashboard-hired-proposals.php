<?php

$proposal_id    = !empty($args['proposal_id']) ? intval($args['proposal_id']) : 0;
$project_id     = !empty($args['project_id']) ? intval($args['project_id']) : 0;
$freelancer_id      = !empty($args['freelancer_id']) ? intval($args['freelancer_id']) : 0;
$user_identity  = !empty($args['user_identity']) ? intval($args['user_identity']) : 0;
$proposal_args = array(
    'post_type' 		=> 'proposals',
    'post_status' 		=> array('hired','cancelled','completed','refunded','disputed'),
    'posts_per_page' 	=> -1,
    'meta_query'        => array(
        array(
            'key'       => 'project_id',
            'value'     => $project_id,
            'compare' 	=> '=',
        )
    )
);
$proposals          = get_posts( $proposal_args );
$count_proposals    = !empty($proposals) && is_array($proposals) ? count($proposals) : 0;
if( !empty($proposals)){ ?>
<div class="wr-project-box wr-project-box-two">
    <div class=" wr-proposal">
        <div class="wr-propposal_title">
            <h5><?php esc_html_e('Hired freelancers','workreap');?><span>(<?php echo intval($count_proposals);?>)</span></h5>
            <a href="javascript:void(0)" class="wr-propsal-list-show"><i class ="wr-icon-plus"></i></a>
        </div>
    </div>
</div>
<ul class="wr-prouserslist mCustomScrollbar">
    <?php
        foreach($proposals as $proposal ){
            $freelancer_id  = get_post_field( 'post_author', $proposal->ID );
            $freelancer_id  = !empty($freelancer_id) ? intval($freelancer_id) : 0;

            $linked_profile_id  = workreap_get_linked_profile_id($freelancer_id, '','freelancers');
            $user_name          = workreap_get_username($linked_profile_id);
            $avatar             = apply_filters( 'workreap_avatar_fallback', workreap_get_user_avatar(array('width' => 50, 'height' => 50), $linked_profile_id), array('width' => 50, 'height' => 50));
            $li_class           = !empty($proposal_id) && $proposal_id == $proposal->ID ? 'wr-active-proposal' : '';
        ?>
        <li class="wr-proposals-list <?php echo esc_attr($li_class);?>">
            <a href="<?php echo esc_url(Workreap_Profile_Menu::workreap_profile_menu_link('projects', $user_identity, true, 'activity',$proposal->ID));?>">
                <img src="<?php echo esc_url($avatar);?>" alt="<?php echo esc_attr($user_name);?>">
                <h6><?php echo esc_html($user_name);?></h6>
                <?php do_action( 'workreap_freelancer_proposal_status_tag', $proposal->ID );?>
            </a>
        </li>
    <?php } ?>

</ul>
<?php }