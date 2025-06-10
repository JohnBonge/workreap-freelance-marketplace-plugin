<?php
/**
 *  FAQs
 *
 * @package     Workreap
 * @subpackage  Workreap/templates/post_services
 * @author      Amentotech <info@amentotech.com>
 * @link        https://codecanyon.net/user/amentotech/portfolio
 * @version     1.0
 * @since       1.0
*/
$current_page_url   = get_the_permalink();
if(isset($_GET['postid']) && !empty($_GET['postid'])){
    $post_id = intval($_GET['postid']);
    $current_page_url   = add_query_arg('post', $post_id, $current_page_url);
}

$current_page_url   = add_query_arg('step', $step, $current_page_url);

if ( !class_exists('WooCommerce') ) {
	return;
}

$workreap_plans_values = get_post_meta($post_id, 'workreap_product_plans', TRUE);
$workreap_service_plans = Workreap_Service_Plans::service_plans();?>
<div id="service-pricing-wrapper">
    <form id="service-faqs-form" class="wr-themeform" action="<?php echo esc_url($current_page_url);?>" method="post" novalidate enctype="multipart/form-data">
        <fieldset>
            <div class="form-group wr-uploadbar-listholder">
                <div class="wr-postserviceholder">
                    <div class="wr-postservicetitle">
                        <h4><?php esc_html_e('Common FAQ’s', 'workreap');?><sup class="asterik_red">*</sup></h4>
                        <a href="javascript:void(0);" data-bs-target="#addnewfaq" data-bs-toggle="modal"><?php esc_html_e('Add more', 'workreap');?></a>
                    </div>
                    <ul id="tbslothandle" class="wr-uploadbar-list">
                        <?php if(isset($service_faq) && is_array($service_faq) && count($service_faq)>0){
                            foreach($service_faq as $key=>$workreap_faq){?>
                                <li id="workreap-faq-<?php echo esc_attr($key);?>" class="workreap-faqlistitem">
                                    <div class="wr-uploadbar-content" data-bs-toggle="collapse" data-bs-target="#faquploadbar<?php echo esc_attr($key);?>" role="list" aria-expanded="false">
                                        <h6><?php echo esc_html($workreap_faq['question']);?></h6>
                                    </div>
                                    <div id="faquploadbar<?php echo esc_attr($key);?>" class="collapse" data-bs-parent="#tbslothandle">
                                        <div class="wr-uploadcontent">
                                            <div class="wr-profileform__content">
                                                <label class="wr-titleinput"><?php esc_html_e('Add faq title', 'workreap');?>:</label>
                                                <input type="text" name="faq[<?php echo esc_attr($key);?>][question]"  class="form-control" placeholder="<?php esc_attr_e('Add title', 'workreap');?>" autocomplete="off" value="<?php echo esc_attr($workreap_faq['question']);?>">
                                            </div>
                                            <div class="wr-profileform__content">
                                                <label class="wr-titleinput"><?php esc_html_e('Add faq description', 'workreap');?>:</label>
                                                <textarea class="form-control" name="faq[<?php echo esc_attr($key);?>][answer]" placeholder="<?php esc_attr_e('Description', 'workreap');?>"><?php echo esc_html($workreap_faq['answer']);?></textarea>
                                            </div>
                                            <div class="wr-profileform__content">
                                                <label class="wr-titleinput"></label>
                                                <div class="wr-dhbbtnarea">
                                                    <a href="javascript:void(0);" class="wr-btn wr-btnvthree workreap-faq-delete" data-faq_key="<?php echo esc_attr($key);?>"><?php esc_html_e('Delete', 'workreap');?></a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                            <?php
                            }
                        }?>
                    </ul>
                </div>
            </div>
            <div class="form-group wr-postserviceformbtn">
                <div class="wr-savebtn">
                    <span><?php esc_html_e('Click “Save &amp; Update” to submit task', 'workreap');?></span>
                    <button type="submit" class="wr-btn wr-service-plans"><?php esc_html_e('Submit task', 'workreap');?></button>
                    <input type="hidden" name="post_id" id="service_id" value="<?php echo (int)$post_id;?>">
                </div>
            </div>
        </fieldset>

    </form>
    <script type="text/template" id="tmpl-load-service-faq">
        <li id="workreap-faq-{{data.id}}" class="workreap-faqlist">
            <div class="wr-uploadbar-content" data-bs-toggle="collapse" data-bs-target="#uploadbar{{data.id}}" role="list" aria-expanded="false">
                <h6>{{data.question}}</h6>
            </div>
            <div id="uploadbar{{data.id}}" class="collapse" data-bs-parent="#tbslothandle">
                <div class="wr-uploadcontent">
                    <div class="wr-profileform__content">
                        <label class="wr-titleinput"><?php esc_html_e('Add faq title', 'workreap');?>:</label>
                        <input type="text" name="faq[{{data.id}}][question]" value="{{data.question}}"  class="form-control" placeholder="<?php esc_attr_e('Enter question here', 'workreap');?>" autocomplete="off">
                    </div>
                    <div class="wr-profileform__content">
                        <label class="wr-titleinput"><?php esc_html_e('Add faq description', 'workreap');?>:</label>
                        <textarea class="form-control" name="faq[{{data.id}}][answer]" placeholder="<?php esc_attr_e('Enter description', 'workreap');?>">{{data.answer}}</textarea>
                    </div>
                    <div class="wr-profileform__content">
                        <label class="wr-titleinput"></label>
                        <div class="wr-dhbbtnarea">
                            <a href="javascript:void(0);" class="wr-btn wr-btnvthree workreap-faq-delete" data-faq_key="{{data.id}}"><?php esc_html_e('Delete', 'workreap');?></a>
                        </div>
                    </div>
                </div>
            </div>
        </li>
    </script>
</div>