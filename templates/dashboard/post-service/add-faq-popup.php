<?php
/**
 * FAQ form fields
 *
 * @package     Workreap
 * @subpackage  Workreap/templates/post_services
 * @author      Amentotech <info@amentotech.com>
 * @link        https://codecanyon.net/user/amentotech/portfolio
 * @version     1.0
 * @since       1.0
 */
?>
<div class="modal fade wr-addonpopup" id="addnewfaq" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog wr-modaldialog" role="document">
        <div class="modal-content">
            <div class="wr-popuptitle">
                <h4><?php esc_html_e('Add new FAQ', 'workreap'); ?></h4>
                <span class="close"><i class="wr-icon-x" data-bs-dismiss="modal"></i></span>
            </div>
            <div class="modal-body">
                <form class="wr-themeform wr-formlogin" id="wr-faq-form">
                    <fieldset>
                        <div class="form-group">
                            <label class="form-group-title"><?php esc_html_e('Add faq title', 'workreap'); ?>:</label>
                            <input type="text" id="service-question" class="form-control" placeholder="<?php esc_attr_e('Enter question here', 'workreap'); ?>" autocomplete="off">
                        </div>
                        <div class="form-group">
                            <label class="form-group-title"><?php esc_html_e('Add faq description', 'workreap'); ?>:</label>
                            <textarea class="form-control" id="service-answer" placeholder="<?php esc_attr_e('Enter brief answer', 'workreap'); ?>"></textarea>
                        </div>
                        <div class="form-group wr-form-btn">
                            <div class="wr-savebtn">
                                <span><?php esc_html_e('Click “Save & Update” to update your faq', 'workreap'); ?></span>
                                <span" class="wr-btn" id="wr-faqs-addlist"><?php esc_html_e('Save & Update', 'workreap'); ?></span>
                            </div>
                        </div>
                    </fieldset>
                </form>
            </div>
        </div>
    </div>
</div>