<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * 
 * Template to display faqs
 *
 * @package     Workreap
 * @subpackage  Workreap/admin/products_data
 * @author      Amentotech <info@amentotech.com>
 * @link        http://amentotech.com/
 * @version     1.0
 * @since       1.0
 */

global $woocommerce, $post;
$workreap_faq_meta = array_filter( (array) get_post_meta($post->ID, 'workreap_service_faqs', true) );
?>
<div class="options_group">
	<?php do_action( 'workreap_woocommerce_product_options_faqs_before' ); ?>
		<div class="form-field downloadable_files faq-data">
			<label><?php esc_html_e( 'FAQ\'s', 'workreap' ); ?></label>
			<table class="widefat">
				<thead>
					<tr>
						<th class="sort">&nbsp;</th>
						<th><?php esc_html_e( 'Question', 'workreap' ); ?> <?php echo wc_help_tip( esc_html__( 'This is the name of the videos shown to the customer.', 'workreap' ) ); ?></th>
						<th colspan="2"><?php esc_html_e( 'Answer', 'workreap' ); ?> <?php echo wc_help_tip( esc_html__( 'This is the URL or absolute path to the file which customers will get access to. URLs entered here should already be encoded.', 'workreap' ) ); ?></th>
						<th>&nbsp;</th>
					</tr>
				</thead>
				<tbody>                
					<?php
					$video_files = array();                   
					if ( $workreap_faq_meta ) {
						foreach ( $workreap_faq_meta as $key => $workreap_faq ) {
							include __DIR__ . '/html-product-faq.php';
						}
					}
					?>
				</tbody>
				<tfoot>
					<tr>
						<th colspan="5">
							<a href="javascript:void(0);" class="button faq-insert " ><?php esc_html_e( 'Add FAQ', 'workreap' ); ?></a>
						</th>
					</tr>
				</tfoot>
			</table>
		</div>
	<?php do_action( 'workreap_woocommerce_product_options_faqs_after' );?>
</div>
<script type="text/template" id="tmpl-load-faq-tr">
	<tr>
		<td class="sort"></td>
		<td class="file_name">
			<input type="text" name="faq[{{data.key}}][question]" value="" class="form-control" placeholder="<?php esc_attr_e('Enter question here', 'workreap');?>" autocomplete="off">
		</td>
		<td class="file_url">
			<textarea class="form-control" name="faq[{{data.key}}][answer]" placeholder="<?php esc_attr_e('Enter description', 'workreap');?>"></textarea>
		</td>
		<td class="file_url_choose" width="1%"></td>
		<td width="1%">
			<a href="javascript:void(0);" class="delete">
				<?php esc_html_e( 'Delete', 'workreap' ); ?>
			</a>
		</td>
	</tr>
</script>
