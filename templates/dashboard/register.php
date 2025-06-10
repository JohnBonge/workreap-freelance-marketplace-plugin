<div class="lb-loginconatiner" id="userregistration">
   	<?php if(!empty($background_banner)){?>
   	 	<figure> <img src="<?php echo esc_attr($background_banner);?>" alt="<?php esc_attr_e('Registration', 'workreap');?>"></figure>
    <?php }?>
    <div class="wr-popupcontainer wr-popupcontainervtwo">
        <div class="wr-popuptitle">
            <h4><?php esc_html_e('Join Our Community', 'workreap');?></h4>
        </div>
        <div class="modal-body">
            <form id="userregistration-from" class="user-registration-form wr-themeform wr-formlogin">
                <fieldset>
                    <?php
                    do_action('workreap_user_registration_fields_before', $registration_fields);
                    do_action('workreap_render_user_registration_fields', $registration_fields);
                    do_action('workreap_render_acf_user_registration_fields', $registration_fields);
                    do_action('workreap_user_registration_fields_after', $registration_fields);
                    ?>
                </fieldset>
            </form>
        </div>
        <div class="modal-footer wr-loginfooterinfo">
            <a href="<?php echo esc_url($login_page);?>"><em><?php esc_html_e('Already have account?', 'workreap');?></em>&nbsp;<?php esc_html_e('Sign In Now', 'workreap');?></a>
        </div>
    </div>
</div>