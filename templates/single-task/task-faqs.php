<?php
/**
 * Single task task faq's
 *
 * @link       https://codecanyon.net/user/amentotech/portfolio
 * @since      1.0.0
 *
 * @package    Workreap
 * @subpackage Workreap_/public
 */

$product_id = $product->get_id();
$faqs_data = get_post_meta($product_id, 'workreap_service_faqs', true);

if (is_array($faqs_data) && !empty($faqs_data)) {?>
    <div class="wr-singleservice-tile wr-detailsfaq">
        <div class="wr-sectiontitle wr-sectiontitlev2">
            <h4><?php esc_html_e('Fequently asked questions', 'workreap'); ?></h4>
        </div>
        <div id="wr-accordion" class="wr-faq">
            <?php if (is_array($faqs_data) && !empty($faqs_data)) {
                $count = 0;
                foreach ($faqs_data as $val) {
                    $count++;
                    $faq_expand = 'faq' . $count;
                    $expanded   = ($count == 1) ? esc_html__('true', 'workreap') : 'false';
                    $tab_option = ($count == 1) ? esc_html__('show', 'workreap') : '';
                    ?>
                    <div class="wr-faq__content">
                        <div class="wr-faq__title" role="list" data-bs-toggle="collapse" data-bs-target="#<?php echo esc_attr($faq_expand); ?>" aria-expanded="<?php echo esc_attr($expanded); ?>">
                            <h6 class="wr-select"><?php echo esc_html($val['question']); ?></h6>
                        </div>
                        <div id="<?php echo esc_attr($faq_expand); ?>" class="collapse <?php echo esc_attr($tab_option);?>" data-bs-parent="#wr-accordion">
                            <div class="wr-sectiontitle wr-sectiontitlev2">
                                <p><?php echo esc_html($val['answer']); ?></p>
                            </div>
                        </div>
                    </div>
                <?php }
            } ?>
        </div>
    </div>
<?php }
