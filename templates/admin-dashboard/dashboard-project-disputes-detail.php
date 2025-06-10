<?php
/**
 * Dispute detail
 *
 * @package     Workreap
 * @subpackage  Workreap/templates/admin_dashboard
 * @author      Amentotech <info@amentotech.com>
 * @link        https://codecanyon.net/user/amentotech/portfolio
 * @version     1.0
 * @since       1.0
*/

global $current_user, $post, $workreap_settings;

$reference		= !empty($_GET['ref'] ) ? $_GET['ref'] : '';
$mode			= !empty($_GET['mode']) ? $_GET['mode'] : '';
$user_identity	= intval($current_user->ID);
$dispute_id		= !empty($_GET['id']) ? $_GET['id'] : '';
$user_type		= apply_filters('workreap_get_user_type', $user_identity );
$dispute_post	= get_post($dispute_id);
$proposal_id	= get_post_meta($dispute_id, '_dispute_order', true);
$_dispute_key	= get_post_meta($dispute_id, '_dispute_key', true);
$_dispute_key	= get_post_meta($dispute_id, '_dispute_key', true);

$dispute_resolve_status	= get_post_meta($dispute_id, 'dispute_status', true);
$freelancer_id		= get_post_meta($dispute_id, '_freelancer_id', true);
$employer_id		= get_post_meta($dispute_id, '_employer_id', true);

$freelancer_id			= !empty($freelancer_id) ? $freelancer_id : 0;
$employer_id			= !empty($employer_id) ? $employer_id : 0;

$winning_user		= get_post_meta($dispute_id, 'winning_party', true);
$freelancer_profile_id	= workreap_get_linked_profile_id($freelancer_id);
$employer_profile_id	= workreap_get_linked_profile_id($employer_id);
$employer_name			= workreap_get_username($employer_profile_id);
$freelancer_name		= workreap_get_username($freelancer_profile_id);
$final_date			= esc_html(date_i18n( get_option( 'date_format' ),  strtotime($dispute_post->post_date. ' + 5 days')));

$employer_avatar	= apply_filters(
	'workreap_avatar_fallback', workreap_get_user_avatar(array('width' => 100, 'height' => 100), $employer_profile_id), array('width' => 100, 'height' => 100)
);
$freelancer_avatar	= apply_filters(
	'workreap_avatar_fallback', workreap_get_user_avatar(array('width' => 100, 'height' => 100), $freelancer_profile_id), array('width' => 100, 'height' => 100)
);

$reply_name		= $employer_name;
$sender_id		= $employer_id;
$receiver_id	= $freelancer_id;

if($user_type == 'freelancers'){
	$reply_name		= $freelancer_name;
	$sender_id		= $freelancer_id;
	$receiver_id	= $employer_id;
}

?>
<div class="col-xl-4">
	<div class="wr-dbholder">
		<div class="wr-dbbox wr-dbboxtitle">
			<h5><?php esc_html_e('Dispute resolution', 'workreap');?></h5>
		</div>
		<div class="wr-dbbox">
			<?php if($dispute_resolve_status == 'resolved'){?>
				<ul class="wr-payoutmethod">
					<li class="wr-radiobox">
						<div class="wr-radiodispute">
							<div class="wr-radiolist payoutlists">
								<span class="wr-wininginfomain">
									<img src="<?php echo esc_url($employer_avatar);?>" alt="<?php echo esc_attr($employer_name);?>">
									<span class="wr-wininginfo">
										<em><?php esc_html_e('Employer', 'workreap');?></em>
										<i><?php echo esc_html($employer_name);?></i>
									</span>
								</span>
								<?php if($winning_user == $employer_id){?>
									<figure>
										<img src="<?php echo esc_url(WORKREAP_DIRECTORY_URI . 'admin-dashboard/images/disputes/resolved/img-1.png'); ?>" alt="<?php esc_attr_e('Winner', 'workreap');?>">
									</figure>
								<?php }?>

							</div>
						</div>
					</li>
					<li class="wr-radiobox">
						<div class="wr-radiodispute">
							<div class="wr-radiolist payoutlists">
								<span class="wr-wininginfomain">
									<img src="<?php echo esc_url($freelancer_avatar);?>" alt="<?php echo esc_attr($freelancer_name);?>">
									<span class="wr-wininginfo">
										<em><?php esc_html_e('Freelancer', 'workreap');?></em>
										<i><?php echo esc_html($freelancer_name);?></i>
									</span>
								</span>

								<?php if($winning_user == $freelancer_id){?>
									<figure>
										<img src="<?php echo esc_url(WORKREAP_DIRECTORY_URI . 'admin-dashboard/images/disputes/resolved/img-1.png'); ?>" alt="<?php esc_attr_e('winner', 'workreap');?>">
									</figure>
								<?php }?>
							</div>
						</div>
					</li>
					<li class="wr-radiobox wr-resolved">
						<div class="wr-radiodispute">
							<div class="wr-radiolist payoutlists">
								<span class="wr-wininginfomain">
									<span class="wr-wininginfo wr-greencheck">
										<span class="wr-icon-check"></span>
										<i><?php esc_html_e('This dispute has been resolved', 'workreap');?></i>
									</span>
								</span>
							</div>
						</div>
					</li>
				</ul>
			<?php } else {?>
				<div class="wr-disputeradiotitle">
					<h6 class="wr-titleinput"><?php esc_html_e('Choose wining party', 'workreap');?>:</h6>
				</div>
				<form class="wr-themeform wr-loginform" id="admin-dispute-resolve-form">
					<ul class="wr-payoutmethod wr-payoutmethodvtwo">
						<li class="wr-radiobox">
							<input type="radio" id="a-option" value="<?php echo intval($employer_id);?>" name="user_id">
							<div class="wr-radiodispute">
								<div class="wr-radio">
									<label for="a-option" class="wr-radiolist payoutlists">
										<span class="wr-wininginfomain">
											<img src="<?php echo esc_url($employer_avatar);?>" alt="<?php esc_attr($employer_name);?>">
											<span class="wr-wininginfo">
												<em><?php esc_html_e('Employer', 'workreap');?></em>
												<i><?php echo esc_html($employer_name);?></i>
											</span>
										</span>
									</label>
								</div>
							</div>
						</li>
						<li class="wr-radiobox">
							<input type="radio" id="ab-option" name="user_id" value="<?php echo intval($freelancer_id);?>" checked="checked">
							<div class="wr-radiodispute">
								<div class="wr-radio">
									<label for="ab-option" class="wr-radiolist payoutlists">
										<span class="wr-wininginfomain">
											<img src="<?php echo esc_url($freelancer_avatar);?>" alt="<?php esc_attr($freelancer_name);?>">
											<span class="wr-wininginfo">
												<em><?php esc_html_e('Freelancer', 'workreap');?>:</em>
												<i><?php echo esc_html($freelancer_name);?></i>
											</span>
										</span>
									</label>
								</div>
							</div>
						</li>
					</ul>
					<fieldset>
						<div class="form-group-wrap">
							<div class="form-group form-vertical">
								<label class="wr-titleinput"><?php esc_html_e('Add dispute feedback', 'workreap');?>:</label>
								<textarea class="form-control" id="dispute_feedback" name="dispute-detail" placeholder="<?php esc_attr_e('Enter Details', 'workreap');?>"></textarea>
							</div>
							<div class="form-group form-vertical">
								<label class="wr-titleinput"><?php esc_html_e('Upload photo (optional)', 'workreap');?>:</label>
								<div class="wr-uploadarea" id="workreap-upload-attachment">
									<ul class="wr-uploadbar wr-bars workreap-fileprocessing workreap-infouploading" id="workreap-fileprocessing"></ul>
									<div class="wr-uploadbox workreap-dragdroparea" id="workreap-droparea" >
										<em>
                                            <?php echo wp_sprintf( esc_html__( 'You can upload jpg,jpeg,gif,png,zip,rar,mp3 mp4 and pdf only. Make sure your file does not exceed %s mb.', 'workreap'), $workreap_settings['upload_file_size'] );?>
											<label for="file2">
												<span id="workreap-attachment-btn-clicked">
													<input id="file2" type="file" name="file">
													<?php esc_html_e('Click here to upload', 'workreap');?>
												</span>
											</label>
										</em>
									</div>
								</div>
							</div>
							<div class="form-group wr-dbtnarea wr-dbtnarea-row">
								<a href="javascript:void(0);" class="wr-btn project-resolve-dispute-btn"  data-proposal_id="<?php echo intval($proposal_id); ?>" data-employer-id="<?php echo esc_attr($employer_id); ?>" data-freelancer-id="<?php echo esc_attr($freelancer_id); ?>" data-dispute-id="<?php echo esc_attr($dispute_id); ?>"><?php esc_html_e('Submit', 'workreap');?><span class="rippleholder wr-jsripple"><em class="ripplecircle"></em></span></a>
								<em><?php esc_html_e('Click “Submit” to add dispute feedback', 'workreap');?></em>
							</div>
						</div>
					</fieldset>
				</form>
			<?php }?>
		</div>
	</div>
</div>
<script type="text/template" id="tmpl-load-chat-media-attachments">
	<li id="thumb-{{data.id}}" class="workreap-list wr-uploading">
		<div class="wr-filedesciption">
			<span>{{data.name}}</span>
			<input type="hidden" class="attachment_url" name="attachments[{{data.attachment_id}}]" value="{{data.url}}">
			<em class="wr-remove"><a href="javascript:void(0)" class="workreap-remove-attachment wr-remove-attachment"><?php esc_html_e('remove', 'workreap');?></a></em>
		</div>
		<div class="progress">
			<div class="progress-bar uploadprogressbar" style="width:0%"></div>
		</div>
	</li>
</script>
<div class="col-xl-5 wr-disputedetailorder">
	<div class="wr-disputearea wr-disputesummery">
		<div class="wr-disputemain">
			<div class="wr-tabbitem__list">
				<div class="wr-deatlswithimg">
					<div class="wr-disputedisc">
						<div class="wr-bordertags">
							<span class="wr-tag-bordered"><?php echo esc_html(workreap_dispute_status($dispute_id));?></span>
						</div>
						<span><?php echo esc_html(date_i18n( get_option( 'date_format' ),  strtotime($dispute_post->post_date)));?></span>
						<h5><span><?php echo esc_html($dispute_post->post_title);?></span></h5>
					</div>
				</div>
			</div>
		</div>
		<div class="wr-extrasarticles">
			<div class="wr-db-extrasarticles">
				<div class="wr-articletitle">
					<h4><?php echo esc_html($_dispute_key);?></h4>
				</div>
				<div class="wr-articlediscription">
					<?php echo wpautop(nl2br($dispute_post->post_content));?>
				</div>
			</div>
		</div>
	</div>
	
	<div class="wr-disputearea-wrapper">
		<div class="wr-dbholder">
			<div class="wr-dbbox wr-dbboxtitle">
				<h5><?php esc_html_e('Dispute conversation', 'workreap');?></h5>
			</div>
			<div class="wr-dbbox">
				<ul class="wr-conversation">
					<?php
					$args = array(
						'post_id' => $dispute_id,
						'hierarchical' => true,
						'order'     => 'ASC',
					);
					$comments = get_comments( $args );
					foreach ($comments as $key => $comment) {

						if(!empty($comment->comment_parent)){
							continue;
						}

						$date			= !empty( $comment->comment_date ) ? $comment->comment_date : '';
						$author_id		= !empty( $comment->user_id ) ? $comment->user_id : '';
						$comments_id	= !empty( $comment->comment_ID ) ? $comment->comment_ID : '';
						$date			= !empty( $date ) ? date_i18n('F j, Y', strtotime($date)) : '';
						$author			= !empty( $comment->comment_author ) ? $comment->comment_autho : '';
						$message		= $comment->comment_content;
						$user 			= get_userdata( $author_id );
						
						if(empty($user)){	
							continue;
						}
						$user_roles 	= $user->roles;

						if (!empty($user_roles) && is_array($user_roles) && in_array( 'administrator', $user_roles, true ) ) {
							$author_name       		= $user->display_name;
							$avatar					= get_avatar_url( $user->ID, ['size' => '80']  );
							$comment_author_type	= esc_html__('Administrator', 'workreap');
						} else {
							$comment_user_type	= apply_filters('workreap_get_user_type', $author_id);
							$linked_profile_id		= workreap_get_linked_profile_id($author_id, '', $comment_user_type);
							
							if($comment_user_type == 'freelancers'){
								$comment_author_type	= esc_html__('Freelancer', 'workreap');
							} elseif($comment_user_type == 'employers'){
								$comment_author_type	= esc_html__('Employer', 'workreap');
							} else {
								$comment_author_type	= esc_html__('Administrator', 'workreap');
							}
							$author_name       		= workreap_get_username($linked_profile_id);
							$avatar	= apply_filters(
								'workreap_avatar_fallback', workreap_get_user_avatar(array('width' => 100, 'height' => 100), $linked_profile_id), array('width' => 100, 'height' => 100)
							);
						}

						$message_files	= get_comment_meta( $comment->comment_ID, 'message_files', true);
						$child_comments	= get_comments(array('parent' => $comments_id, 'hierarchical' => true, 'order' => 'ASC'));
						?>
						<li>
							<div class="wr-conversation__content" id="comment-<?php echo intval($comment->comment_ID);?>">
								<div class="wr-conversation__header">
									<?php if(!empty($avatar)){?>
										<img src="<?php echo esc_url($avatar);?>" alt="<?php echo esc_attr($author_name);?>">
									<?php }?>
									<div class="wr-conversation__title">
										<?php if(!empty($comment_author_type)){?>
											<span><?php echo esc_html(ucfirst($comment_author_type));?></span>
										<?php }?>
										<?php if(!empty($author_name)){?>
											<h5><?php echo esc_html($author_name);?></h5>
										<?php }?>
									</div>
									<?php if(!in_array($dispute_resolve_status, array('resolved', 'refunded'))){ ?>
										<a href="javascript:void(0);" class="wr-btn workreap-comment-reply" data-comment_id="<?php echo intval($comments_id);?>"><?php esc_html_e('Reply', 'workreap');?><span class="rippleholder wr-jsripple"><em class="ripplecircle"></em></span></a>
									<?php }?>
								</div>
								<?php if(!empty($message)){?>
									<div class="wr-conversation__detail">
										<?php echo wpautop(nl2br($message));?>
									</div>
								<?php }?>
								 <?php if (!empty($message_files)){ ?>
									<div class="wr-documentlist">
										<ul class="wr-doclist">
											<?php foreach ($message_files as $message_file) {

											$src =  WORKREAP_DIRECTORY_URI . 'public/images/doc.jpg';
											$file_url   = $message_file['url'];
											$file_uname = $message_file['name'];

											if (isset($message_file['ext']) && !empty($message_file['ext'])){
												if ($message_file['ext'] == 'pdf'){
													$src =  WORKREAP_DIRECTORY_URI . 'public/images/pdf.jpg';
												}elseif ($message_file['ext'] == 'png'){
													$src =  WORKREAP_DIRECTORY_URI . 'public/images/png.jpg';
												}elseif ($message_file['ext'] == 'ppt'){
													$src =  WORKREAP_DIRECTORY_URI . 'public/images/ppt.jpg';
												}elseif ($message_file['ext'] == 'psd'){
													$src =  WORKREAP_DIRECTORY_URI . 'public/images/psd.jpg';
												}elseif ($message_file['ext'] == 'php'){
													$src =  WORKREAP_DIRECTORY_URI . 'public/images/php.jpg';
												}
											}

											?>
											<li>
												<a href="<?php echo esc_url( $file_url ); ?>" class="wr-download-attachment" data-id="<?php echo esc_attr( $comment->comment_ID ); ?>" ><img src="<?php echo esc_url( $src ); ?>" alt="<?php echo esc_attr( $file_uname ); ?>"></a>
											</li>
											<?php } ?>
										</ul>

										<a href="javascript:void(0);" class="wr-download-attachment" data-id="<?php echo esc_attr( $comments_id ); ?>" >Download file(s)</a>

									</div>
								<?php } ?>
							</div>
							<?php if(!empty($child_comments)){?>
								<?php foreach ($child_comments as $key => $comment) {
									$child_comment_author_id	= !empty( $comment->user_id ) ? $comment->user_id : '';
									$comment_author_type      	= apply_filters('workreap_get_user_type', $child_comment_author_id);
									$linked_profile_id 			= workreap_get_linked_profile_id($child_comment_author_id, '', $comment_author_type);
									$author_name       			= workreap_get_username($linked_profile_id);
									$child_comment_message		= $comment->comment_content;

									$user		= get_userdata( $child_comment_author_id );
									if(empty($user)){	
										continue;
									}
									$user_roles	= $user->roles;

									if (!empty($user_roles) && is_array($user_roles) && in_array( 'administrator', $user_roles, true ) ) {
										$author_name	= $user->display_name;
										$avatar			= get_avatar_url( $user->ID, ['size' => '80'] );
										$comment_author_type	= esc_html__('Administrator', 'workreap');
									} else {
										$comment_user_type	= apply_filters('workreap_get_user_type', $child_comment_author_id);
										$linked_profile_id	= workreap_get_linked_profile_id($author_id, '', $comment_user_type);
										if($comment_user_type == 'freelancers'){
											$comment_author_type	= esc_html__('Freelancer', 'workreap');
										} elseif($comment_user_type == 'employers'){
											$comment_author_type	= esc_html__('Employer', 'workreap');
										} else {
											$comment_author_type	= esc_html__('Administrator', 'workreap');
										}
										$author_name       		= workreap_get_username($linked_profile_id);
										$avatar	= apply_filters(
											'workreap_avatar_fallback', workreap_get_user_avatar(array('width' => 100, 'height' => 100), $linked_profile_id), array('width' => 100, 'height' => 100)
										);
									}

									$message_files	= get_comment_meta( $comment->comment_ID, 'message_files', true);
									?>
									<div class="wr-conversation__content" id="comment-<?php echo intval($comment->comment_ID);?>">
										<div class="wr-conversation__header">
											<?php if(!empty($avatar)){?>
												<figure><img src="<?php echo esc_url($avatar);?>" alt="<?php echo esc_attr($author_name);?>"></figure>
											<?php }?>
											<div class="wr-conversation__title">
												<?php if(!empty($comment_author_type)){?>
													<span><?php echo esc_html(ucfirst($comment_author_type));?></span>
												<?php }?>
												<?php if(!empty($author_name)){?>
													<h5><?php echo esc_html($author_name);?></h5>
												<?php }?>
											</div>
										</div>
										<?php if (!empty($child_comment_message)){ ?>
										<div class="wr-conversation__detail">
											<?php echo wpautop(nl2br($child_comment_message));?>
										</div>
										<?php }?>
										<?php if (!empty($message_files)){ ?>
											<div class="wr-documentlist">
												<ul class="wr-doclist">
													<?php foreach ($message_files as $message_file) {

													$src =  WORKREAP_DIRECTORY_URI . 'public/images/doc.jpg';
													$file_url   = $message_file['url'];
													$file_uname = $message_file['name'];

													if (isset($message_file['ext']) && !empty($message_file['ext'])){
														if ($message_file['ext'] == 'pdf'){
															$src =  WORKREAP_DIRECTORY_URI . 'public/images/pdf.jpg';
														}elseif ($message_file['ext'] == 'png'){
															$src =  WORKREAP_DIRECTORY_URI . 'public/images/png.jpg';
														}elseif ($message_file['ext'] == 'ppt'){
															$src =  WORKREAP_DIRECTORY_URI . 'public/images/ppt.jpg';
														}elseif ($message_file['ext'] == 'psd'){
															$src =  WORKREAP_DIRECTORY_URI . 'public/images/psd.jpg';
														}elseif ($message_file['ext'] == 'php'){
															$src =  WORKREAP_DIRECTORY_URI . 'public/images/php.jpg';
														}
													}

													?>
													<li>
														<a href="<?php echo esc_url( $file_url ); ?>" class="wr-download-attachment" data-id="<?php echo esc_attr( $comments_id ); ?>" ><img src="<?php echo esc_url( $src ); ?>" alt="<?php echo esc_attr( $file_uname ); ?>"></a>
													</li>

													<?php } ?>
												</ul>

												<a href="javascript:void(0);" class="wr-download-attachment" data-id="<?php echo esc_attr( $comments_id ); ?>" ><?php esc_html_e('Download file(s)', 'workreap');?></a>
											</div>
										<?php } ?>

									</div>
								<?php }?>
							<?php }?>
						</li>
					<?php }?>

				</ul>
				<?php if($dispute_resolve_status !== 'resolved'){?>
					<form class="wr-themeform wr-refundform_form" id="project-dispute-reply-form">
						<input type="hidden" name="dispute_id" id="dispute_id" value="<?php echo intval($dispute_id);?>" >
						<input type="hidden" name="sender_id" id="sender_id" value="<?php echo intval($sender_id);?>" >
						<input type="hidden" name="parent_comment_id" id="parent_comment_id" value="0" >
						<input type="hidden" name="action_type" id="action_type" value="reply" >
						<div class="wr-disputereply">
							<h5><?php esc_html_e('Dispute Reply', 'workreap');?>:</h5>
							<textarea class="form-control" id="dispute_comment" name="dispute_comment" placeholder="<?php esc_attr_e('Enter dispute reply', 'workreap');?>"></textarea>
						</div>
						<div class="wr-disputebtn">
							<a href="javascript:void(0);" id="project-dispute-reply-btn" class="wr-btn"><?php esc_html_e('Submit', 'workreap'); ?><span class="rippleholder wr-jsripple"><em class="ripplecircle"></em></span></a>
							<em><?php esc_html_e('Click “Submit” to add dispute reply', 'workreap');?></em>
						</div>
					</form>
				<?php }?>
			</div>
		</div>
	</div>
</div>
<div class="col-xl-3 wr-task-admin-history">
	<?php 
		$proposal_type	= get_post_meta( $proposal_id, 'proposal_type', true );
		if( empty($proposal_type)  || $proposal_type === 'fixed' || $proposal_type === 'milestone' ){
			do_action('workreap_proposal_order_budget_details', $proposal_id, $user_type);
		} else {
			do_action('workreap_hourly_proposal_order_budget_details', $proposal_id, $user_type);
		}
	?>
	<?php workreap_get_template_part('dashboard/dashboard', 'tasks-activity-history');?>
</div>