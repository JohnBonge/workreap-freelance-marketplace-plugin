<?php
/**
 * User profile avatar
 *
 * @package     Workreap
 * @subpackage  Workreap/templates
 * @author      Amentotech <info@amentotech.com>
 * @link        https://codecanyon.net/user/amentotech/portfolio
 * @version     1.0
 * @since       1.0
*/

global $current_user;
?>
<script type="text/template" id="tmpl-load-profile-avatar">
    <div class="wr-popuptitle">
        <h4><?php esc_html_e('Upload profile photo', 'workreap'); ?></h4>
        <a href="javascript:void(0);" class="close"><i class="wr-icon-x" data-bs-dismiss="modal"></i></a>
    </div>
    <div class="modal-body">
        <form class="wr-dhb-orders-listing">
            <div id="crop_img_area"></div>
        </form>
        <div class="wr-dhb-mainheading__rightarea">
            <em> <?php esc_html_e('Click “Save” to update profile photo', 'workreap'); ?></em>
            <a href="javascript:void(0);" class="wr-btn"><?php esc_html_e('Save', 'workreap'); ?>
                <span class="rippleholder wr-jsripple" id="save-profile-img" ><em class="ripplecircle"></em></span>
            </a>
        </div>
    </div>
</script>
<script type="text/template" id="tmpl-load-profile-image">
	<img class="attachment_url" src="{{data.url}}" alt="{{data.name}}">
	<input type="hidden" name="basic[profile_image]" value="{{data.url}}">
</script>
<script type="text/template" id="tmpl-load-default-image">
	<figure id="thumb-{{data.id}}" >
		<img class="attachment_url" alt="<?php esc_attr_e('Profile avatar', 'workreap' ); ?>">
		<div class="progress wr-upload-progressbar"><div style="width:{{data.percentage}}%" class="progress-bar"></div></div>
	</figure>
</script>