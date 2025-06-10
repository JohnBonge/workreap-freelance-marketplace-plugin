<?php
/**
*  Project preferences
*
* @package     Workreap
* @author      Amentotech <info@amentotech.com>
* @link        https://codecanyon.net/user/amentotech/portfolio
* @version     1.0
* @since       1.0
*/
global $workreap_settings,$current_user;
if ( !class_exists('WooCommerce') ) {
	return;
}

$post_id            = !empty($post_id) ? intval($post_id) : "";
$step_id            = !empty($step) ? intval($step) : "";

$no_of_freelancers  = !empty($workreap_settings['no_of_freelancers']) ? $workreap_settings['no_of_freelancers'] : array();
$remove_languages  = !empty($workreap_settings['remove_languages']) ? $workreap_settings['remove_languages'] : 'no';

$list_freelancers   = array();
if( !empty($no_of_freelancers) && $no_of_freelancers > 0 ){
    for ($x = 1; $x <= $no_of_freelancers; $x++) {
        $list_freelancers[$x]   = sprintf(_n('%s freelancer','%s freelancers',$x,'workreap'),$x);
    }
}

$selected_expertise = !empty($product) ? wp_get_post_terms( $product->get_id(), 'expertise_level', array('fields' =>'ids') ) : array();
$selected_expertise = !empty($selected_expertise[0]) ? intval($selected_expertise[0]) : '';
$selected_languages = !empty($product) ? wp_get_post_terms( $product->get_id(), 'languages', array('fields' =>'ids') ) : array();

$selected_skills        = !empty($product) ? wp_get_post_terms( $product->get_id(), 'skills', array('fields' =>'ids') ) : array();
$selected_freelancers   = !empty($product) ? get_post_meta( $product->get_id(), 'no_of_freelancers', true ) : '';
$selected_freelancers   = !empty($selected_freelancers) ? intval($selected_freelancers) : '';
?>
<div class="row">
    <?php do_action( 'workreap_project_sidebar', $step_id,$post_id );?>
    <div class="col-xl-9 col-lg-8">
        <div class="wr-project-wrapper">
            <div class="wr-project-box">
                <div class="wr-maintitle">
                    <h4><?php esc_html_e('Which skills your freelancer should have?','workreap');?></h4>
                </div>
                <form class="wr-themeform wr-project-form">
                    <fieldset>
                        <div class="wr-themeform__wrap">
                            <div class="form-group form-group-half">
                                <label class="wr-label"><?php esc_html_e('No. of freelancers','workreap');?></label>
                                <div class="wr-select">
                                    <?php do_action( 'workreap_custom_dropdown_html', $list_freelancers,'no_of_freelancers','wr-num-freelancer',$selected_freelancers );?>
                                </div>
                            </div>
                            <div class="form-group form-group-half">
                                <label class="wr-label"><?php esc_html_e('Expertise level','workreap');?></label>
                                <div class="wr-select">
                                    <?php
                                        $expertise_args = array(
                                            'show_option_none'  => esc_html__('Choose experties level', 'workreap'),
                                            'option_none_value' => '',
                                            'show_count'    => false,
                                            'hide_empty'    => false,
                                            'name'          => 'expertise_level',
                                            'class'         => 'wr-select-cat wr-expertise-level',
                                            'taxonomy'      => 'expertise_level',
                                            'value_field'   => 'term_id',
                                            //'multiple'      => 'multiple',
                                            'orderby'       => 'name',
                                            'hide_if_empty' => false,
                                            'echo'          => true,
                                            'required'      => false,
                                            'selected'      => $selected_expertise,
                                        );
                                        do_action('workreap_taxonomy_dropdown', $expertise_args);
                                    ?>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="wr-label"><?php esc_html_e('Skills required','workreap');?></label>
                                <div class="wr-select">
                                    <?php
                                        $skills_args = array(
                                            'class'         => 'wr-select2-cat wr-select2-skills',
                                            'taxonomy'      => 'skills',
                                            'value_field'   => 'term_id',
                                            'orderby'       => 'name',
                                            'name'          => 'skills[]',
                                            'selected'      => $selected_skills,
                                        );
                                        do_action('workreap_custom_taxonomy_dropdown', $skills_args);
                                    ?>
                                </div>
                            </div>
                            <?php if(!empty($remove_languages) && $remove_languages === 'no'){?>
                                <div class="form-group">
                                    <label class="wr-label"><?php esc_html_e('Languages','workreap');?></label>
                                    <div class="wr-select">
                                        <?php
                                            $languages_args = array(
                                                'class'         => 'wr-select2-languages',
                                                'taxonomy'      => 'languages',
                                                'value_field'   => 'term_id',
                                                'orderby'       => 'name',
                                                'name'          => 'languages[]',
                                                'selected'      => $selected_languages,
                                            );
                                            do_action('workreap_custom_taxonomy_dropdown', $languages_args);
                                        ?>
                                    </div>
                                </div>
                            <?php }?>
                        </div>
                    </fieldset>
                </form>
            </div>
            <div class="wr-project-box">
                <div class="wr-projectbtns">
                    <a href="javascript:void(0)" class="wr-btn-solid-lg-lefticon wr-save-project" data-step_id="3" data-project_id="<?php echo intval($post_id);?>">
                        <?php esc_html_e('Save & continue','workreap');?>
                        <i class="wr-icon-chevron-right"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
$scripts	= "
jQuery(document).ready(function($){
    'use strict';
    // Make category drop-down select2 on add service

    jQuery('.wr-num-freelancer').select2({
        theme: 'default wr-select2-dropdown',
        allowClear: true,
        placeholder: scripts_vars.num_freelancer_option,
        language: {
				noResults: function(){
                    return scripts_vars.select_not_found
                },
                searching: function(){
                    return scripts_vars.select_searching
                },
        }
    });
    jQuery('.wr-expertise-level').select2({
        theme: 'default wr-select2-dropdown',
        allowClear: true,
        placeholder: scripts_vars.expertise_level_option,
        language: {
				noResults: function(){
                    return scripts_vars.select_not_found
                },
                searching: function(){
                    return scripts_vars.select_searching
                },
            }
    });

    

    // Make category drop-down select2 on add service
    jQuery('.wr-select2-languages').select2({
        theme: 'default wr-select2-dropdown',
        allowClear: true,
        multiple: true,
        language: {
				noResults: function(){
                    return scripts_vars.select_not_found
                },
                searching: function(){
                    return scripts_vars.select_searching
                },
            }
    });

    if ( $.isFunction($.fn.select2) ) {
        jQuery('.wr-select2-languages').select2({
            theme: 'default wr-select2-dropdown',
            multiple: true,
            placeholder: scripts_vars.languages_option,
            language: {
				noResults: function(){
                    return scripts_vars.select_not_found
                },
                searching: function(){
                    return scripts_vars.select_searching
                },
            }
        });
    }
    jQuery('.wr-select2-languages').trigger('change');
    // Make category drop-down select2 on add service
    jQuery('.wr-select2-skills').select2({
        theme: 'default wr-select2-dropdown',
        allowClear: true,
        multiple: true,
        language: {
				noResults: function(){
                    return scripts_vars.select_not_found
                },
                searching: function(){
                    return scripts_vars.select_searching
                },
            }
    });
    if ( $.isFunction($.fn.select2) ) {
        jQuery('.wr-select2-skills').select2({
            theme: 'default wr-select2-dropdown',
            multiple: true,
            placeholder: scripts_vars.skills_option,
            language: {
				noResults: function(){
                    return scripts_vars.select_not_found
                },
                searching: function(){
                    return scripts_vars.select_searching
                },
            }
        });
    }
    jQuery('.wr-select2-skills').trigger('change');

    });";
    wp_add_inline_script('workreap', $scripts, 'after');
