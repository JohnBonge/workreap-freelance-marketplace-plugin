<?php
/**
 *  Create project basic
 *
 * @package     Workreap
 * @author      Amentotech <info@amentotech.com>
 * @link        https://codecanyon.net/user/amentotech/portfolio
 * @version     1.0
 * @since       1.0
*/
$post_id        = !empty($post_id) ? intval($post_id) : "";
$post_url       = !empty($post_url) ? esc_url($post_url) : "";
$new_job_url    = !empty($post_id) ? $post_url.'?step=2&post_id='.intval($post_id) : $post_url.'?step=2';
$duplicate_job  = !empty($post_url) ? $post_url.'?page_temp=projects' : '';
?>
<div class="row justify-content-center">
    <div class="col-xl-8 text-center">
        <div class="wr-postproject-title">
            <h3><?php esc_html_e('Choose where to start your project','workreap');?></h3>
            <p><?php esc_html_e('You can start a new project from scratch or you can use your previous posted job template','workreap');?></p>
        </div>
        <ul class="wr-newproject-list">
            <li>
                <a href="<?php echo esc_url($new_job_url);?>" class="wr-postproject-new">
                    <i class="wr-icon-file-text wr-purple-icon"></i>
                    <span><?php esc_html_e('Start a new project','workreap');?></span>
                    <p><?php esc_html_e('Create a new project from scratch','workreap');?></p>
                </a>
            </li>
            <li>
                <a href="<?php echo esc_url($duplicate_job);?>
                " class="wr-postproject-new">
                    <i class="wr-icon-copy wr-red-icon"></i>
                    <span><?php esc_html_e('Use template instead','workreap');?></span>
                    <p><?php esc_html_e('Create a new project using previous project','workreap');?></p>
                </a>
            </li>
        </ul>
    </div>
</div>