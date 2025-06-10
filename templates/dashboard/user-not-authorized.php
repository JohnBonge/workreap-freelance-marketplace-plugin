<?php
global $workreap_settings
?>

<div class="wr-submitreview wr-submitreviewv3">
    <figure>
        <img src="<?php echo esc_url(!empty($workreap_settings['empty_listing_image']['url']) ? $workreap_settings['empty_listing_image']['url'] : WORKREAP_DIRECTORY_URI . 'public/images/empty.png') ?>" alt="<?php esc_attr_e('task', 'workreap'); ?>">
    </figure>
    <h4><?php esc_html_e('You are not authorized to access this page.', 'workreap'); ?></h4>
</div>