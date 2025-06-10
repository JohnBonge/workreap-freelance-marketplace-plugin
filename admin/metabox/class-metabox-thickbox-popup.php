<?php

namespace thickboxmodel;

// die if accessed directly
if (!defined('ABSPATH')) {
    die('no kiddies please!');
}

/**
 * 
 * Class 'Workreap_Admin_Metabox_Thickbox_Modal' defines the custom post type Employers
 *
 * @package     Workreap
 * @subpackage  Workreap/admin/metabox
 * @author      Amentotech <info@amentotech.com>
 * @link        http://amentotech.com/
 * @version     1.0
 * @since       1.0
 */

if (!class_exists('Workreap_Admin_Metabox_Thickbox_Modal')) {

    class Workreap_Admin_Metabox_Thickbox_Modal {

        /**
         * @access  public
         * @Init Hooks in Constructor
        */
        public function __construct() {

            add_action('admin_footer', array(&$this, 'workreap_prepare_profile_popup'));
        }

        /**
         * Thickbox popup
         * @access  public
         *
        */
        public function workreap_prepare_profile_popup() {
            global $post;
            add_ThickBox();
            ob_start();
            ?>
            <div class="modal hidden fade workreap-profilepopup" tabindex="-1" role="dialog" id="workreap-thickbox-popup">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="workreap-modalcontent modal-content">
                        <div class="modal-body" id="workreap-profile-model"></div>
                    </div>
                </div>
            </div>
            <?php 
            echo ob_get_clean();
		}

    }

}

new Workreap_Admin_Metabox_Thickbox_Modal();
