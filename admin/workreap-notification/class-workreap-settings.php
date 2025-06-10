<?php

/**
 *
 * Class 'Workreap_Notifications' defines to remove the product data default tabs
 *
 * @package     Workreap
 * @subpackage  Workreap/admin
 * @author      Amentotech <info@amentotech.com>
 * @link        https://codecanyon.net/user/amentotech/portfolio
 * @version     1.0
 * @since       1.0
 */

class Workreap_NotificationsSettings
{

    /**
     * Add action hooks
     *
     * @since    1.0.0
     * @access   public
     */
    public function __construct()
    {
        add_filter('workreap_list_notification', array(&$this, 'workreap_list_notification'), 10, 2);
    }

    /**
     * Filter to get notification options
     *
     * @since    1.0.0
     */
    public function workreap_list_notification($type = '', $value = '')
    {
        $list   = array(
            // notification for registration
            'registration'   => array(
                'type'      => 'registration',
                'settings'  => array(
                    'image_type'    => 'defult',
                    'tage'          => array('name', 'email', 'sitename')
                ),
                'options'   => array(
                    'title'             => esc_html__('Registration', 'workreap'),
                    'tag_title'         => esc_html__('Notification setting variables', 'workreap'),
                    'content_title'     => esc_html__('Notification content', 'workreap'),
                    'enable_title'      => esc_html__('Enable/disable notification for registration', 'workreap'),
                    'flash_message_title' => esc_html__('Enable/disable flash message', 'workreap'),
                    'flash_message_option'     => true,
                    'content'           => esc_html__('Thank you for joining, an email has been sent to your email for verification', 'workreap'),
                    'tags'              => __('{{name}}     — To display the name.<br>
                                            {{email}}       — To display the email address.<br>
                                            {{sitename}}    — To display the site name.<br>
                                            '),
                ),

            ),
            // notification for send request
            'approval_request'   => array(
                'type'      => 'registration',
                'settings'  => array(
                    'image_type'    => 'defult',
                    'tage'          => array('name', 'email', 'sitename')
                ),
                'options'   => array(
                    'title'             => esc_html__('Approval user request', 'workreap'),
                    'tag_title'         => esc_html__('Notification setting variables', 'workreap'),
                    'content_title'     => esc_html__('Notification content', 'workreap'),
                    'enable_title'      => esc_html__('Enable/disable notification for approval user', 'workreap'),
                    'flash_message_title' => esc_html__('Enable/disable flash message', 'workreap'),
                    'flash_message_option'     => true,
                    'content'           => esc_html__('Thank you for joining, your account will be approved once reviewed by the admin', 'workreap'),
                    'tags'              => __('
                                            {{name}}  — To display the name.<br>
                                            {{email}}    — To display the email address.<br>
                                            {{sitename}} — To display the site name.<br>
                                            '),
                ),

            ),
            // notification for approved request from admin
            'approved_account_request'   => array(
                'type'      => 'registration',
                'settings'  => array(
                    'image_type'    => 'defult',
                    'tage'          => array('name', 'email', 'sitename')
                ),
                'options'   => array(
                    'title'             => esc_html__('Account approved', 'workreap'),
                    'tag_title'         => esc_html__('Notification setting variables', 'workreap'),
                    'content_title'     => esc_html__('Notification content', 'workreap'),
                    'enable_title'      => esc_html__('Enable/disable notification for account approve user', 'workreap'),
                    'flash_message_title' => esc_html__('Enable/disable flash message', 'workreap'),
                    'flash_message_option'     => true,
                    'content'           => esc_html__('Congratulations! Your account has been approved.', 'workreap'),
                    'tags'              => __('
                                            {{name}}  — To display the name.<br>
                                            {{email}}    — To display the email address.<br>
                                            {{sitename}} — To display the site name.<br>
                                            '),
                ),

            ),
            // notification for approved request
            'reject_account_request'   => array(
                'type'      => 'registration',
                'settings'  => array(
                    'image_type'    => 'defult',
                    'tage'          => array('name', 'email', 'sitename')
                ),
                'options'   => array(
                    'title'             => esc_html__('Rejected user account', 'workreap'),
                    'tag_title'         => esc_html__('Notification setting variables', 'workreap'),
                    'content_title'     => esc_html__('Notification content', 'workreap'),
                    'enable_title'      => esc_html__('Enable/disable notification for rejected user', 'workreap'),
                    'flash_message_title' => esc_html__('Enable/disable flash message', 'workreap'),
                    'flash_message_option'     => true,
                    'content'           => esc_html__('Unfortunately, your account has not been approved, Please try again.', 'workreap'),
                    'tags'              => __('
                                            {{name}}  — To display the name.<br>
                                            {{email}}    — To display the email address.<br>
                                            {{sitename}} — To display the site name.<br>
                                            '),
                ),

            ),

            // notification for send verification request
            'account_verification_request'   => array(
                'type'      => 'registration',
                'settings'  => array(
                    'image_type'    => 'defult',
                    'tage'          => array('name', 'email', 'sitename')
                ),
                'options'   => array(
                    'title'             => esc_html__('Verification request', 'workreap'),
                    'tag_title'         => esc_html__('Notification setting variables', 'workreap'),
                    'content_title'     => esc_html__('Notification content', 'workreap'),
                    'enable_title'      => esc_html__('Enable/disable notification for verification request', 'workreap'),
                    'flash_message_title' => esc_html__('Enable/disable flash message', 'workreap'),
                    'flash_message_option'     => true,
                    'content'           => esc_html__('Thank you upload identity information, your account will be verified once reviewed by the admin', 'workreap'),
                    'tags'              => __('
                                            {{name}}  — To display the name.<br>
                                            {{email}}    — To display the email address.<br>
                                            {{sitename}} — To display the site name.<br>
                                            '),
                ),

            ),

            // notification for approve verification request
            'approve_verification_request'   => array(
                'type'      => 'registration',
                'settings'  => array(
                    'image_type'    => 'defult',
                    'tage'          => array('name', 'email', 'sitename')
                ),
                'options'   => array(
                    'title'             => esc_html__('Approve verification request', 'workreap'),
                    'tag_title'         => esc_html__('Notification setting variables', 'workreap'),
                    'content_title'     => esc_html__('Notification content', 'workreap'),
                    'enable_title'      => esc_html__('Enable/disable notification for verification request', 'workreap'),
                    'flash_message_title' => esc_html__('Enable/disable flash message', 'workreap'),
                    'flash_message_option'     => true,
                    'content'           => esc_html__('Congratulations! Your account Verification  has been approved.', 'workreap'),
                    'tags'              => __('
                                            {{name}}  — To display the name.<br>
                                            {{email}}    — To display the email address.<br>
                                            {{sitename}} — To display the site name.<br>
                                            '),
                ),

            ),
            // notification for reject verification request
            'reject_verification_request'   => array(
                'type'      => 'registration',
                'settings'  => array(
                    'image_type'    => 'defult',
                    'tage'          => array('name', 'email', 'sitename')
                ),
                'options'   => array(
                    'title'             => esc_html__('Verification request', 'workreap'),
                    'tag_title'         => esc_html__('Notification setting variables', 'workreap'),
                    'content_title'     => esc_html__('Notification content', 'workreap'),
                    'enable_title'      => esc_html__('Enable/disable notification for verification request', 'workreap'),
                    'flash_message_title' => esc_html__('Enable/disable flash message', 'workreap'),
                    'flash_message_option'     => true,
                    'content'           => esc_html__('Unfortunately, your account has been rejected, Please try again.', 'workreap'),
                    'tags'              => __('
                                            {{name}}  — To display the name.<br>
                                            {{email}}    — To display the email address.<br>
                                            {{sitename}} — To display the site name.<br>
                                            '),
                ),

            ),
            // notification for reset password
            'reset_password'   => array(
                'type'      => 'registration',
                'settings'  => array(
                    'image_type'    => 'defult',
                    'tage'          => array('name', 'email', 'sitename')
                ),
                'options'   => array(
                    'title'             => esc_html__('Reset password', 'workreap'),
                    'tag_title'         => esc_html__('Notification setting variables', 'workreap'),
                    'content_title'     => esc_html__('Notification content', 'workreap'),
                    'enable_title'      => esc_html__('Enable/disable notification for reset password', 'workreap'),
                    'flash_message_title' => esc_html__('Enable/disable flash message', 'workreap'),
                    'flash_message_option'     => true,
                    'content'           => esc_html__('You have successfully restored your password', 'workreap'),
                    'tags'              => __('
                                            {{name}}  — To display the name.<br>
                                            {{email}}    — To display the email address.<br>
                                            {{sitename}} — To display the site name.<br>
                                            '),
                ),

            ),

            // notification for submint task
            'submint_task'  => array(
                'type'      => 'task',
                'settings'  => array(
                    'image_type'    => 'defult',
                    'tage'          => array('email', 'sitename', 'task_name', 'freelancer_name', 'task_link'),
                ),
                'options'   => array(
                    'title'             => esc_html__('Task submission', 'workreap'),
                    'tag_title'         => esc_html__('Notification setting variables', 'workreap'),
                    'content_title'     => esc_html__('Notification content', 'workreap'),
                    'enable_title'      => esc_html__('Enable/disable notification for task submission', 'workreap'),
                    'flash_message_title' => esc_html__('Enable/disable flash message', 'workreap'),
                    'flash_message_option'     => false,
                    'content'           => __('Thank you for submitting the task <strong>“{{task_name}}”</strong>, we will review and approve the task soon.', 'workreap'),
                    'tags'              => __('
                                            {{task_name}}       — To display the task title.<br>
                                            {{task_link}}       — To display the task link.<br>
                                            {{freelancer_name}}     — To display the freelancer name.<br>
                                            {{task_name}}       — To display the task title.<br>
                                            {{email}}    — To display the email address.<br>
                                            {{sitename}} — To display the site name.<br>
                                            '),
                ),

            ),

            // notification for approve task
            'task_approved'  => array(
                'type'      => 'task',
                'settings'  => array(
                    'image_type'    => 'defult',
                    'tage'          => array('email', 'sitename', 'task_name', 'freelancer_name', 'task_link'),
                    'btn_settings'  => array('link_type' => 'single_post', 'text' => esc_html__('View task', 'workreap'))
                ),
                'options'   => array(
                    'title'             => esc_html__('Task approved', 'workreap'),
                    'tag_title'         => esc_html__('Notification setting variables', 'workreap'),
                    'content_title'     => esc_html__('Notification content', 'workreap'),
                    'enable_title'      => esc_html__('Enable/disable notification for task approve', 'workreap'),
                    'flash_message_title' => esc_html__('Enable/disable flash message', 'workreap'),
                    'flash_message_option'     => false,
                    'content'           => __('<strong>“{{task_name}}”</strong>has been approved successfully.', 'workreap'),
                    'tags'              => __('
                                            {{task_name}}       — To display the task title.<br>
                                            {{task_link}}       — To display the task link.<br>
                                            {{freelancer_name}}     — To display the freelancer name.<br>
                                            {{task_name}}       — To display the task title.<br>
                                            {{email}}    — To display the email address.<br>
                                            {{sitename}} — To display the site name.<br>
                                            '),
                ),

            ),

            // notification for rejected task
            'task_rejected'  => array(
                'type'      => 'task',
                'settings'  => array(
                    'image_type'        => 'freelancer_image',
                    'admin_comments'    => 'yes',
                    'tage'              => array('sitename', 'task_name', 'freelancer_name', 'task_link', 'admin_feedback'),
                ),
                'options'   => array(
                    'title'             => esc_html__('Task rejected', 'workreap'),
                    'tag_title'         => esc_html__('Notification setting variables', 'workreap'),
                    'content_title'     => esc_html__('Notification content', 'workreap'),
                    'enable_title'      => esc_html__('Enable/disable notification for task rejected', 'workreap'),
                    'flash_message_title' => esc_html__('Enable/disable flash message', 'workreap'),
                    'flash_message_option'     => false,
                    'content'           => __('Unfortunately the task <strong>“{{task_name}}”</strong>has been rejected. Please make the required changes and resubmit.<br>{{admin_feedback}}', 'workreap'),
                    'tags'              => __('
                                            {{task_name}}       — To display the task title.<br>
                                            {{task_link}}       — To display the task link.<br>
                                            {{freelancer_name}}     — To display the freelancer name.<br>
                                            {{task_name}}       — To display the task title.<br>
                                            {{admin_feedback}}    — To display the admin feedback.<br>
                                            {{sitename}} — To display the site name.<br>
                                            '),
                ),

            ),
            // notification to freelancer for new order
            'freelancer_new_order'  => array(
                'type'      => 'order',
                'settings'  => array(
                    'image_type'    => 'employer_image',
                    'tage'          => array('sitename', 'task_name', 'freelancer_name', 'task_link', 'employer_name', 'order_id', 'freelancer_order_amount', 'freelancer_order_link'),
                    'btn_settings'  => array('link_type' => 'view_freelancer_order', 'text' => esc_html__('View order', 'workreap'))
                ),
                'options'   => array(
                    'title'             => esc_html__('New order for freelancer', 'workreap'),
                    'tag_title'         => esc_html__('Notification setting variables', 'workreap'),
                    'content_title'     => esc_html__('Notification content', 'workreap'),
                    'enable_title'      => esc_html__('Enable/disable notification to freelancer for new order', 'workreap'),
                    'flash_message_title' => esc_html__('Enable/sisable flash message', 'workreap'),
                    'flash_message_option'     => true,
                    'content'           => __('You have received a new order request form <strong>“{{employer_name}}”</strong> for the task <strong>“{{task_name}}”</strong>', 'workreap'),
                    'tags'              => __('
                                            {{task_name}}       — To display the task title.<br>
                                            {{task_link}}       — To display the task link.<br>
                                            {{freelancer_name}}     — To display the freelancer name.<br>
                                            {{employer_name}}      — To display the employer name.<br>
                                            {{order_id}}        — To display the order id.<br>
                                            {{freelancer_order_amount}}     — To display the freelancer amount.<br>
                                            {{freelancer_order_link}}       — To display the freelancer order url.<br>
                                            {{sitename}} — To display the site name.<br>
                                            '),
                ),

            ),

            // notification to employer for new order
            'employer_new_order'  => array(
                'type'      => 'order',
                'settings'  => array(
                    'image_type'    => 'freelancer_image',
                    'tage'          => array('sitename', 'task_name', 'freelancer_name', 'task_link', 'employer_name', 'email', 'order_id', 'employer_order_amount', 'employer_order_link'),
                ),
                'options'   => array(
                    'title'             => esc_html__('Notification to employer on new order', 'workreap'),
                    'tag_title'         => esc_html__('Notification setting variables', 'workreap'),
                    'content_title'     => esc_html__('Notification content', 'workreap'),
                    'enable_title'      => esc_html__('Enable/disable notification to employer for new order', 'workreap'),
                    'flash_message_title' => esc_html__('Enable/disable flash message', 'workreap'),
                    'flash_message_option'     => true,
                    'content'           => __('Thank you for ordering <strong>{{task_name}}</strong>. I will get in touch with you shortly', 'workreap'),
                    'tags'              => __('
                                            {{task_name}}       — To display the task title.<br>
                                            {{task_link}}       — To display the task link.<br>
                                            {{freelancer_name}}     — To display the freelancer name.<br>
                                            {{employer_name}}      — To display the employer name.<br>
                                            {{order_id}}        — To display the order id.<br>
                                            {{employer_order_amount}}     — To display the employer amount.<br>
                                            {{employer_order_link}}       — To display the employer order url.<br>
                                            {{email}}    — To display the email address.<br>
                                            {{sitename}} — To display the site name.<br>
                                            '),
                ),

            ),
            // notification to employer for order complete request
            'employer_order_request'  => array(
                'type'      => 'order',
                'settings'  => array(
                    'image_type'    => 'defult',
                    'tage'          => array('employer_name', 'freelancer_name', 'task_name', 'task_link', 'order_id', 'employer_order_link', 'sitename', 'employer_order_amount'),
                    'btn_settings'  => array('link_type' => 'employer_order_link', 'text' => esc_html__('View activity', 'workreap'))
                ),
                'options'   => array(
                    'title'             => esc_html__('Notification employer final delivery', 'workreap'),
                    'tag_title'         => esc_html__('Notification setting variables', 'workreap'),
                    'content_title'     => esc_html__('Notification content', 'workreap'),
                    'enable_title'      => esc_html__('Enable/disable notification final delivery', 'workreap'),
                    'flash_message_title' => esc_html__('Enable/disable flash message', 'workreap'),
                    'flash_message_option'     => true,
                    'content'           => __('<strong>“{{freelancer_name}}”</strong> has sent you the final delivery for the task <strong>{{task_name}}</strong>', 'workreap'),
                    'tags'              => __('
                                            {{task_name}}       — To display the task title.<br>
                                            {{task_link}}       — To display the task link.<br>
                                            {{freelancer_name}}     — To display the freelancer name.<br>
                                            {{employer_name}}      — To display the employer name.<br>
                                            {{order_id}}        — To display the order id.<br>
                                            {{employer_order_amount}}     — To display the employer amount.<br>
                                            {{employer_order_link}}       — To display the employer order url.<br>
                                            {{sitename}} — To display the site name.<br>
                                            '),
                ),

            ),
            // notification for employer or freelancer
            'user_activity'  => array(
                'type'      => 'order',
                'settings'  => array(
                    'image_type'    => 'sender_image',
                    'tage'          => array('sender_name', 'receiver_name', 'task_name', 'task_link', 'order_id', 'sender_comments', 'order_link', 'sitename'),
                    'btn_settings'  => array('link_type' => 'order_link', 'text' => esc_html__('View order details', 'workreap'))
                ),
                'options'   => array(
                    'title'             => esc_html__('Notification user task activity', 'workreap'),
                    'tag_title'         => esc_html__('Notification setting variables', 'workreap'),
                    'content_title'     => esc_html__('Notification content', 'workreap'),
                    'enable_title'      => esc_html__('Enable/disable notification task activity', 'workreap'),
                    'flash_message_title' => esc_html__('Enable/disable flash message', 'workreap'),
                    'flash_message_option'     => false,
                    'content'           => __('You have received a note from <strong>“{{sender_name}}”</strong> on the task <strong>“{{task_name}}”</strong>', 'workreap'),
                    'tags'              => __('
                                            {{task_name}}       — To display the task title.<br>
                                            {{task_link}}       — To display the task link.<br>
                                            {{sender_name}}     — To display the sender name.<br>
                                            {{receiver_name}}   — To display the receiver name.<br>
                                            {{order_id}}        — To display the order id.<br>
                                            {{sender_comments}}     — To display the sender comment.<br>
                                            {{order_link}}       — To display the order url.<br>
                                            {{sitename}} — To display the site name.<br>
                                            '),
                ),
            ),
            // notification to byer for cancel order
            'order_rejected'  => array(
                'type'      => 'order',
                'settings'  => array(
                    'image_type'    => 'employer_image',
                    'tage'          => array('employer_name', 'freelancer_name', 'task_name', 'task_link', 'order_id', 'freelancer_order_link', 'sitename', 'employer_comments'),
                    'btn_settings'  => array('link_type' => 'view_freelancer_order', 'text' => esc_html__('View activity', 'workreap'))
                ),
                'options'   => array(
                    'title'             => esc_html__('Notification to freelancer for reject order', 'workreap'),
                    'tag_title'         => esc_html__('Notification setting variables', 'workreap'),
                    'content_title'     => esc_html__('Notification content', 'workreap'),
                    'enable_title'      => esc_html__('Enable/disable notification to freelancer for reject order', 'workreap'),
                    'flash_message_title' => esc_html__('Enable/disable flash message', 'workreap'),
                    'flash_message_option'     => true,
                    'content'           => __('<strong>“{{employer_name}}”</strong> has cancelled the order of <strong>{{task_name}}</strong> and has left some comments for you.', 'workreap'),
                    'tags'              => __('
                                            {{task_name}}       — To display the task title.<br>
                                            {{task_link}}       — To display the task link.<br>
                                            {{freelancer_name}}     — To display the freelancer name.<br>
                                            {{employer_name}}      — To display the employer name.<br>
                                            {{order_id}}        — To display the order id.<br>
                                            {{freelancer_order_link}}    — To display the freelancer order url.<br>
                                            {{employer_comments}}       — To display the employer comments.<br>
                                            {{sitename}} — To display the site name.<br>
                                            '),
                ),
            ),
            // notification to byer for complete order
            'order_completed'  => array(
                'type'      => 'order',
                'settings'  => array(
                    'image_type'    => 'employer_image',
                    'tage'          => array('employer_name', 'freelancer_name', 'task_name', 'task_link', 'order_id', 'employer_rating', 'freelancer_order_link', 'sitename', 'employer_comments'),
                    'btn_settings'  => array('link_type' => 'view_freelancer_order', 'text' => esc_html__('View activity', 'workreap'))
                ),
                'options'   => array(
                    'title'             => esc_html__('Notification to freelancer complete order', 'workreap'),
                    'tag_title'         => esc_html__('Notification setting variables', 'workreap'),
                    'content_title'     => esc_html__('Notification content', 'workreap'),
                    'enable_title'      => esc_html__('Enable/disable notification to freelancer to complete order', 'workreap'),
                    'flash_message_title' => esc_html__('Enable/disable flash message', 'workreap'),
                    'flash_message_option'     => true,
                    'content'           => __('Congratulations! <strong>“{{employer_name}}”</strong> has closed the <strong>{{task_name}}</strong> and has left some comments for you.', 'workreap'),
                    'tags'              => __('
                                            {{task_name}}       — To display the task title.<br>
                                            {{task_link}}       — To display the task link.<br>
                                            {{freelancer_name}}     — To display the freelancer name.<br>
                                            {{employer_name}}      — To display the employer name.<br>
                                            {{order_id}}        — To display the order id.<br>
                                            {{freelancer_order_link}}   — To display the freelancer order url.<br>
                                            {{employer_comments}}      — To display the employer comments.<br>
                                            {{employer_rating}}        — To display the ratings.<br>
                                            {{sitename}} — To display the site name.<br>
                                            '),
                ),
            ),
            // notification to freelancer for dispute
            'refund_request'  => array(
                'type'      => 'dispute',
                'settings'  => array(
                    'image_type'    => 'employer_image',
                    'tage'          => array('employer_name', 'freelancer_name', 'task_name', 'task_link', 'order_id', 'freelancer_order_amount', 'freelancer_order_link', 'sitename', 'employer_comments', 'view_freelancer_refund_request'),
                    'btn_settings'  => array('link_type' => 'view_freelancer_refund_request', 'text' => esc_html__('View refund request', 'workreap'))
                ),
                'options'   => array(
                    'title'             => esc_html__('Notification to freelancer for dispute', 'workreap'),
                    'tag_title'         => esc_html__('Notification setting variables', 'workreap'),
                    'content_title'     => esc_html__('Notification content', 'workreap'),
                    'enable_title'      => esc_html__('Enable/disable notification to freelancer for dispute', 'workreap'),
                    'flash_message_title' => esc_html__('Enable/disable flash message', 'workreap'),
                    'flash_message_option'     => true,
                    'content'           => __('<strong>{{employer_name}}</strong> submitted a refund request against <strong>{{task_name}}</strong>', 'workreap'),
                    'tags'              => __('
                                            {{task_name}}       — To display the task title.<br>
                                            {{task_link}}       — To display the task link.<br>
                                            {{freelancer_name}}     — To display the freelancer name.<br>
                                            {{employer_name}}      — To display the employer name.<br>
                                            {{order_id}}        — To display the order id.<br>
                                            {{freelancer_order_link}}   — To display the freelancer order url.<br>
                                            {{employer_comments}}      — To display the employer comments.<br>
                                            {{freelancer_order_amount}} — To display the freelancer amount.<br>
                                            {{sitename}} — To display the site name.<br>
                                            '),
                ),
            ),
            // notification to employer for complete order
            'refund_comments'  => array(
                'type'      => 'dispute',
                'settings'  => array(
                    'image_type'    => 'sender_image',
                    'tage'          => array('sender_name', 'receiver_name', 'task_name', 'task_link', 'order_id', 'dispute_comment', 'view_comments'),
                    'btn_settings'  => array('link_type' => 'view_comments', 'text' => esc_html__('View comment', 'workreap'))
                ),
                'options'   => array(
                    'title'             => esc_html__('Notification to user to recive dispute comment', 'workreap'),
                    'tag_title'         => esc_html__('Notification setting variables', 'workreap'),
                    'content_title'     => esc_html__('Notification content', 'workreap'),
                    'enable_title'      => esc_html__('Enable/disable notification to send user', 'workreap'),
                    'flash_message_title' => esc_html__('Enable/disable flash message', 'workreap'),
                    'flash_message_option'     => true,
                    'content'           => __('<strong>“{{sender_name}}”</strong> has left some comments on the refund request against <strong>{{task_name}}</strong>', 'workreap'),
                    'tags'              => __('
                                            {{sender_name}}         — To display the sender name.<br>
                                            {{receiver_name}}       — To display the reciver name.<br>
                                            {{task_name}}           — To display the task title.<br>
                                            {{task_link}}           — To display the task link.<br>
                                            {{order_id}}            — To display the order id.<br>
                                            {{dispute_comment}}     — To display the sender comments.<br>
                                            {{view_comments}}       — To display the dispute url.<br>
                                            '),
                ),
            ),
            // notification to employer for complete order
            'admin_refund_comments'  => array(
                'type'      => 'dispute',
                'settings'  => array(
                    'image_type'    => 'defult',
                    'tage'          => array('receiver_name', 'task_name', 'task_link', 'order_id', 'dispute_comment', 'view_comments'),
                    'btn_settings'  => array('link_type' => 'view_comments', 'text' => esc_html__('View comment', 'workreap'))
                ),
                'options'   => array(
                    'title'             => esc_html__('Notification to user after admin comment', 'workreap'),
                    'tag_title'         => esc_html__('Notification setting variables', 'workreap'),
                    'content_title'     => esc_html__('Notification content', 'workreap'),
                    'enable_title'      => esc_html__('Enable/disable notification to user froma admin', 'workreap'),
                    'flash_message_title' => esc_html__('Enable/disable flash message', 'workreap'),
                    'flash_message_option'     => true,
                    'content'           => __('Hello <strong>“{{receiver_name}}”</strong> Admin has left some comments on the dispute', 'workreap'),
                    'tags'              => __('
                                            {{receiver_name}}       — To display the reciver name.<br>
                                            {{task_name}}           — To display the task title.<br>
                                            {{task_link}}           — To display the task link.<br>
                                            {{order_id}}            — To display the order id.<br>
                                            {{dispute_comment}}     — To display the sender comments.<br>
                                            {{view_comments}}       — To display the dispute url.<br>
                                            '),
                ),
            ),
            // notification to employer for dispute decline
            'refund_decline'  => array(
                'type'      => 'dispute',
                'settings'  => array(
                    'image_type'    => 'freelancer_image',
                    'tage'          => array('freelancer_name', 'employer_name', 'task_name', 'task_link', 'order_id', 'dispute_comment', 'view_comments'),
                    'btn_settings'  => array('link_type' => 'employer_order_link', 'text' => esc_html__('Create dispute', 'workreap'))
                ),
                'options'   => array(
                    'title'             => esc_html__('Notification to user to decline refund request', 'workreap'),
                    'tag_title'         => esc_html__('Notification setting variables', 'workreap'),
                    'content_title'     => esc_html__('Notification content', 'workreap'),
                    'enable_title'      => esc_html__('Enable/disable notification decline refund request', 'workreap'),
                    'flash_message_title' => esc_html__('Enable/disable flash message', 'workreap'),
                    'flash_message_option'     => true,
                    'content'           => __('Your refund request has been declined by <strong>{{freelancer_name}}</strong> against <strong>{{task_name}}</strong>', 'workreap'),
                    'tags'              => __('
                                            {{freelancer_name}}         — To display the freelancer name.<br>
                                            {{employer_name}}          — To display the employer name.<br>
                                            {{task_name}}           — To display the task title.<br>
                                            {{task_link}}           — To display the task link.<br>
                                            {{order_id}}            — To display the order id.<br>
                                            {{dispute_comment}}     — To display the freelancer comments.<br>
                                            {{view_comments}}       — To display the dispute url.<br>
                                            '),
                ),
            ),
            // notification to employer for dispute approved
            'refund_approved'  => array(
                'type'      => 'dispute',
                'settings'  => array(
                    'image_type'    => 'freelancer_image',
                    'tage'          => array('freelancer_name', 'employer_name', 'task_name', 'task_link', 'order_id', 'dispute_comment', 'view_comments')
                ),
                'options'   => array(
                    'title'             => esc_html__('Notification to user after approved refund request', 'workreap'),
                    'tag_title'         => esc_html__('Notification setting variables', 'workreap'),
                    'content_title'     => esc_html__('Notification content', 'workreap'),
                    'enable_title'      => esc_html__('Enable/disable notification to send user after approved refund request', 'workreap'),
                    'flash_message_title'   => esc_html__('Enable/disable flash message', 'workreap'),
                    'flash_message_option'  => true,
                    'content'           => __('Congratulations! Your refund request has been approved by <strong>{{freelancer_name}}</strong> against <strong>{{task_name}}</strong><br>{{dispute_comment}}', 'workreap'),
                    'tags'              => __('
                                            {{freelancer_name}}         — To display the freelancer name.<br>
                                            {{employer_name}}          — To display the employer name.<br>
                                            {{task_name}}           — To display the task title.<br>
                                            {{task_link}}           — To display the task link.<br>
                                            {{order_id}}            — To display the order id.<br>
                                            {{dispute_comment}}     — To display the freelancer comments.<br>
                                            {{view_comments}}       — To display the dispute url.<br>
                                            '),
                ),
            ),
            // notification to freelancer form admin refunded
            'freelancer_refunded'  => array(
                'type'      => 'dispute',
                'settings'  => array(
                    'tage'          => array('freelancer_name', 'employer_name', 'task_name', 'task_link', 'order_id', 'order_amount')
                ),
                'options'   => array(
                    'title'             => esc_html__('Notification to freelancer for dispute resolved ', 'workreap'),
                    'tag_title'         => esc_html__('Notification setting variables', 'workreap'),
                    'content_title'     => esc_html__('Notification content', 'workreap'),
                    'enable_title'      => esc_html__('Enable/disable notification for dispute resolved', 'workreap'),
                    'flash_message_title'   => esc_html__('Enable/disable flash message', 'workreap'),
                    'flash_message_option'  => true,
                    'content'           => __('Congratulations! The dispute has been resolved against <strong>{{task_name}}</strong> in your favor. We have marked the task as completed and credited the amount into your wallet', 'workreap'),
                    'tags'              => __('
                                            {{freelancer_name}}         — To display the freelancer name.<br>
                                            {{employer_name}}          — To display the employer name.<br>
                                            {{task_name}}           — To display the task title.<br>
                                            {{task_link}}           — To display the task link.<br>
                                            {{order_id}}            — To display the order id.<br>
                                            {{order_amount}}        — To display the order amount.<br>
                                            '),
                ),
            ),
            // notification to freelancer form admin decline dispute
            'freelancer_cancelled_refunded'  => array(
                'type'      => 'dispute',
                'settings'  => array(
                    'tage'          => array('freelancer_name', 'employer_name', 'task_name', 'task_link', 'order_id', 'order_amount')
                ),
                'options'   => array(
                    'title'             => esc_html__('Notification to freelancer for dispute in favor of employer', 'workreap'),
                    'tag_title'         => esc_html__('Notification setting variables', 'workreap'),
                    'content_title'     => esc_html__('Notification content', 'workreap'),
                    'enable_title'      => esc_html__('Enable/disable notification for dispute in favor of employer', 'workreap'),
                    'flash_message_title'   => esc_html__('Enable/disable flash message', 'workreap'),
                    'flash_message_option'  => true,
                    'content'           => __('The dispute for the task <strong>{{task_name}}</strong> has been resolved against your favor.', 'workreap'),
                    'tags'              => __('
                                            {{freelancer_name}}         — To display the freelancer name.<br>
                                            {{employer_name}}          — To display the employer name.<br>
                                            {{task_name}}           — To display the task title.<br>
                                            {{task_link}}           — To display the task link.<br>
                                            {{order_id}}            — To display the order id.<br>
                                            {{order_amount}}        — To display the order amount.<br>
                                            '),
                ),
            ),
            // notification to employer form admin refunded
            'employer_refunded'  => array(
                'type'      => 'dispute',
                'settings'  => array(
                    'tage'          => array('freelancer_name', 'employer_name', 'task_name', 'task_link', 'order_id', 'order_amount')
                ),
                'options'   => array(
                    'title'             => esc_html__('Notification to employer for dispute resolved', 'workreap'),
                    'tag_title'         => esc_html__('Notification setting variables', 'workreap'),
                    'content_title'     => esc_html__('Notification content', 'workreap'),
                    'enable_title'      => esc_html__('Enable/disable notification to employer for dispute resolved', 'workreap'),
                    'flash_message_title'   => esc_html__('Enable/disable flash message', 'workreap'),
                    'flash_message_option'  => true,
                    'content'           => __('Congratulations! The dispute has been resolved against <strong>{{task_name}}</strong> in your favor. We have marked the task as completed and credited the amount into your wallet. You can now start with new freelancer', 'workreap'),
                    'tags'              => __('
                                            {{freelancer_name}}         — To display the freelancer name.<br>
                                            {{employer_name}}          — To display the employer name.<br>
                                            {{task_name}}           — To display the task title.<br>
                                            {{task_link}}           — To display the task link.<br>
                                            {{order_id}}            — To display the order id.<br>
                                            {{order_amount}}        — To display the order amount.<br>
                                            '),
                ),
            ),
            // notification to employer form admin decline dispute
            'employer_cancelled_refunded'  => array(
                'type'      => 'dispute',
                'settings'  => array(
                    'tage'          => array('freelancer_name', 'employer_name', 'task_name', 'task_link', 'order_id', 'order_amount')
                ),
                'options'   => array(
                    'title'             => esc_html__('Notification to employer for dispute in favor of employer ', 'workreap'),
                    'tag_title'         => esc_html__('Notification setting variables', 'workreap'),
                    'content_title'     => esc_html__('Notification content', 'workreap'),
                    'enable_title'      => esc_html__('Enable/disable notification for dispute in favor of employer', 'workreap'),
                    'flash_message_title'   => esc_html__('Enable/disable flash message', 'workreap'),
                    'flash_message_option'  => true,
                    'content'           => __('The dispute for the task <strong>{{task_name}}</strong> has been resolved against your favor.', 'workreap'),
                    'tags'              => __('
                                            {{freelancer_name}}         — To display the freelancer name.<br>
                                            {{employer_name}}          — To display the employer name.<br>
                                            {{task_name}}           — To display the task title.<br>
                                            {{task_link}}           — To display the task link.<br>
                                            {{order_id}}            — To display the order id.<br>
                                            {{order_amount}}        — To display the order amount.<br>
                                            '),
                ),
            ),

            // notification to freelancer for project dispute
            'project_refund_request'  => array(
                'type'      => 'dispute',
                'settings'  => array(
                    'image_type'    => 'employer_image',
                    'tage'          => array('employer_name', 'freelancer_name', 'project_title', 'project_link', 'dispute_order_amount', 'sitename', 'employer_comments', 'view_freelancer_project_refund_request'),
                    'btn_settings'  => array('link_type' => 'view_freelancer_project_refund_request', 'text' => esc_html__('View refund request', 'workreap'))
                ),
                'options'   => array(
                    'title'             => esc_html__('Notification to freelancer for project dispute', 'workreap'),
                    'tag_title'         => esc_html__('Notification setting variables', 'workreap'),
                    'content_title'     => esc_html__('Notification content', 'workreap'),
                    'enable_title'      => esc_html__('Enable/disable notification to freelancer for project dispute', 'workreap'),
                    'flash_message_title' => esc_html__('Enable/disable flash message', 'workreap'),
                    'flash_message_option'     => true,
                    'content'           => __('Project refund request received from <strong>{{employer_name}}</strong> of <strong>{{project_title}}</strong> project', 'workreap'),
                    'tags'              => __('
                                            {{project_title}}       — To display the task title.<br>
                                            {{project_link}}       — To display the task link.<br>
                                            {{freelancer_name}}     — To display the freelancer name.<br>
                                            {{employer_name}}      — To display the employer name.<br>
                                            {{dispute_order_amount}}   — To display the dispute amount.<br>
                                            {{employer_comments}}      — To display the employer comments.<br>
                                            {{sitename}} — To display the site name.<br>
                                            '),
                ),
            ),
            // notification to reciver on project dispute comments
            'project_refund_comments'  => array(
                'type'      => 'dispute',
                'settings'  => array(
                    'image_type'    => 'sender_image',
                    'tage'          => array('sender_name', 'receiver_name', 'project_title', 'project_link', 'dispute_comment'),
                    'btn_settings'  => array('link_type' => 'view_project_dispute_comments', 'text' => esc_html__('View comment', 'workreap'))
                ),
                'options'   => array(
                    'title'             => esc_html__('Notification to user to recive project dispute comment', 'workreap'),
                    'tag_title'         => esc_html__('Notification setting variables', 'workreap'),
                    'content_title'     => esc_html__('Notification content', 'workreap'),
                    'enable_title'      => esc_html__('Enable/disable notification to user to recive project dispute comment', 'workreap'),
                    'flash_message_title' => esc_html__('Enable/disable flash message', 'workreap'),
                    'flash_message_option'     => true,
                    'content'           => __('You have received a new dispute comment from {{sender_name}}', 'workreap'),
                    'tags'              => __('
                                            {{sender_name}}         — To display the sender name.<br>
                                            {{receiver_name}}       — To display the reciver name.<br>
                                            {{project_title}}       — To display the project title.<br>
                                            {{project_link}}        — To display the project link.<br>
                                            {{dispute_comment}}     — To display the sender comments.<br>
                                            '),
                ),
            ),
            // notification to employer for project refund request decline form freelancer
            'project_refund_decline'  => array(
                'type'      => 'dispute',
                'settings'  => array(
                    'image_type'    => 'freelancer_image',
                    'tage'          => array('employer_name', 'freelancer_name', 'project_title', 'project_link', 'sitename', 'employer_comments'),
                    'btn_settings'  => array('link_type' => 'view_project_dispute_comments', 'text' => esc_html__('View refund request', 'workreap'))
                ),
                'options'   => array(
                    'title'             => esc_html__('Notification to employer for project refund request decline', 'workreap'),
                    'tag_title'         => esc_html__('Notification setting variables', 'workreap'),
                    'content_title'     => esc_html__('Notification content', 'workreap'),
                    'enable_title'      => esc_html__('Enable/disable notification to employer for project refund request decline', 'workreap'),
                    'flash_message_title' => esc_html__('Enable/disable flash message', 'workreap'),
                    'flash_message_option'     => true,
                    'content'           => __('Oho! A dispute has been declined by <strong>{{freelancer_name}}</strong>', 'workreap'),
                    'tags'              => __('
                                            {{project_title}}       — To display the task title.<br>
                                            {{project_link}}       — To display the task link.<br>
                                            {{freelancer_name}}     — To display the freelancer name.<br>
                                            {{employer_name}}      — To display the employer name.<br>
                                            {{sitename}} — To display the site name.<br>
                                            '),
                ),
            ),

            // notification to employer for project refund request decline form freelancer
            'project_refund_approved'  => array(
                'type'      => 'dispute',
                'settings'  => array(
                    'image_type'    => 'freelancer_image',
                    'tage'          => array('employer_name', 'freelancer_name', 'project_title', 'project_link', 'sitename', 'employer_comments'),
                    'btn_settings'  => array('link_type' => 'view_project_dispute_comments', 'text' => esc_html__('View refund request', 'workreap'))
                ),
                'options'   => array(
                    'title'             => esc_html__('Notification to employer for project refund request approved from freelancer', 'workreap'),
                    'tag_title'         => esc_html__('Notification setting variables', 'workreap'),
                    'content_title'     => esc_html__('Notification content', 'workreap'),
                    'enable_title'      => esc_html__('Enable/disable notification to employer for project refund request approved from freelancer', 'workreap'),
                    'flash_message_title' => esc_html__('Enable/disable flash message', 'workreap'),
                    'flash_message_option'     => true,
                    'content'           => __('Woohoo! <strong>{{freelancer_name}}</strong> approved the dispute refund request in your favor', 'workreap'),
                    'tags'              => __('
                                            {{project_title}}       — To display the task title.<br>
                                            {{project_link}}       — To display the task link.<br>
                                            {{freelancer_name}}     — To display the freelancer name.<br>
                                            {{employer_name}}      — To display the employer name.<br>
                                            {{sitename}} — To display the site name.<br>
                                            '),
                ),
            ),

            // notification to user for admin comment on project dispute
            'project_admin_dispute_comment'  => array(
                'type'      => 'dispute',
                'settings'  => array(
                    'tage'          => array('employer_name', 'freelancer_name', 'project_title', 'project_link', 'sitename', 'dispute_comment', 'admin_name'),
                    'btn_settings'  => array('link_type' => 'view_project_dispute_comments', 'text' => esc_html__('View comment', 'workreap'))
                ),
                'options'   => array(
                    'title'             => esc_html__('Notification to user for project dispute comment', 'workreap'),
                    'tag_title'         => esc_html__('Notification setting variables', 'workreap'),
                    'content_title'     => esc_html__('Notification content', 'workreap'),
                    'enable_title'      => esc_html__('Enable/disable notification to user for project dispute comment', 'workreap'),
                    'flash_message_title' => esc_html__('Enable/disable flash message', 'workreap'),
                    'flash_message_option'     => true,
                    'content'           => __('You have received a new dispute comment from {{admin_name}}', 'workreap'),
                    'tags'              => __('
                                            {{project_title}}       — To display the project title.<br>
                                            {{project_link}}       — To display the project link.<br>
                                            {{freelancer_name}}     — To display the freelancer name.<br>
                                            {{employer_name}}      — To display the employer name.<br>
                                            {{admin_name}}      — To display the admin name.<br>
                                            {{sitename}} — To display the site name.<br>
                                            '),
                ),
            ),

            // notification to wining user for admin comment on project dispute
            'admin_resolved_project_dispute_winning'  => array(
                'type'      => 'dispute',
                'settings'  => array(
                    'tage'          => array('employer_name', 'freelancer_name', 'project_title', 'project_link', 'sitename', 'dispute_comment', 'admin_name'),
                    'btn_settings'  => array('link_type' => 'view_project_dispute_comments', 'text' => esc_html__('View comment', 'workreap'))
                ),
                'options'   => array(
                    'title'             => esc_html__('Notification to winnig party user for project dispute resolved', 'workreap'),
                    'tag_title'         => esc_html__('Notification setting variables', 'workreap'),
                    'content_title'     => esc_html__('Notification content', 'workreap'),
                    'enable_title'      => esc_html__('Enable/disable notification to winnig party user for project dispute resolved', 'workreap'),
                    'flash_message_title' => esc_html__('Enable/disable flash message', 'workreap'),
                    'flash_message_option'     => true,
                    'content'           => __('Woohoo! <strong>{{admin_name}}</strong> approved the dispute refund request in your favor.', 'workreap'),
                    'tags'              => __('
                                            {{project_title}}       — To display the project title.<br>
                                            {{project_link}}       — To display the project link.<br>
                                            {{freelancer_name}}     — To display the freelancer name.<br>
                                            {{employer_name}}      — To display the employer name.<br>
                                            {{admin_name}}      — To display the admin name.<br>
                                            {{sitename}} — To display the site name.<br>
                                            '),
                ),
            ),
            // notification to losser user for admin comment on project dispute
            'admin_resolved_project_dispute_loser'  => array(
                'type'      => 'dispute',
                'settings'  => array(
                    'tage'          => array('employer_name', 'freelancer_name', 'project_title', 'project_link', 'sitename', 'dispute_comment', 'admin_name'),
                    'btn_settings'  => array('link_type' => 'view_project_dispute_comments', 'text' => esc_html__('View comment', 'workreap'))
                ),
                'options'   => array(
                    'title'             => esc_html__('Notification to loseing party user for project dispute resolved', 'workreap'),
                    'tag_title'         => esc_html__('Notification setting variables', 'workreap'),
                    'content_title'     => esc_html__('Notification content', 'workreap'),
                    'enable_title'      => esc_html__('Enable/disable notification to loseing party user for project dispute resolved', 'workreap'),
                    'flash_message_title' => esc_html__('Enable/disable flash message', 'workreap'),
                    'flash_message_option'     => true,
                    'content'           => __('Oho! <strong>{{admin_name}}</strong> did not approve the dispute refund request in your favor.', 'workreap'),
                    'tags'              => __('
                                            {{project_title}}       — To display the project title.<br>
                                            {{project_link}}       — To display the project link.<br>
                                            {{freelancer_name}}     — To display the freelancer name.<br>
                                            {{employer_name}}      — To display the employer name.<br>
                                            {{admin_name}}      — To display the admin name.<br>
                                            {{sitename}} — To display the site name.<br>
                                            '),
                ),
            ),



            // notification to freelancer form admin refunded
            'package_purchases'  => array(
                'type'      => 'packages',
                'settings'  => array(
                    'tage'          => array('freelancer_name', 'order_id', 'order_amount', 'package_name', 'post_a_task'),
                    'btn_settings'  => array('link_type' => 'single_post', 'text' => esc_html__('Post a task', 'workreap'))
                ),
                'options'   => array(
                    'title'             => esc_html__('Notification to freelancer for package purchases ', 'workreap'),
                    'tag_title'         => esc_html__('Notification setting variables', 'workreap'),
                    'content_title'     => esc_html__('Notification content', 'workreap'),
                    'enable_title'      => esc_html__('Enable/disable notification for package purchases', 'workreap'),
                    'flash_message_title'   => esc_html__('Enable/disable flash message', 'workreap'),
                    'flash_message_option'  => true,
                    'content'           => __('Congratulations! You have successfully purchased the “{{package_name}}” package. You can now post a service and get orders', 'workreap'),
                    'tags'              => __('
                                            {{freelancer_name}}         — To display the freelancer name.<br>
                                            {{package_name}}        — To display the package title.<br>
                                            {{post_a_task}}         — To display the post a task url.<br>
                                            {{order_id}}            — To display the order id.<br>
                                            {{order_amount}}        — To display the order amount.<br>
                                            '),
                ),
            ),
            // notification to employer on approved project
            'approve_project'  => array(
                'type'      => 'projects',
                'settings'  => array(
                    'tage'          => array('employer_name', 'project_title', 'project_link', 'project_id')
                ),
                'options'   => array(
                    'title'             => esc_html__('Notification to employer for approved project ', 'workreap'),
                    'tag_title'         => esc_html__('Notification setting variables', 'workreap'),
                    'content_title'     => esc_html__('Notification content', 'workreap'),
                    'enable_title'      => esc_html__('Enable/disable notification for approved project', 'workreap'),
                    'flash_message_title'   => esc_html__('Enable/disable flash message', 'workreap'),
                    'flash_message_option'  => true,
                    'content'           => __('Woohoo! Your project {{project_title}} has been approved.', 'workreap'),
                    'tags'              => __('
                                            {{employer_name}}          — To display the employer name.<br>
                                            {{project_title}}           — To display the project title.<br>
                                            {{project_link}}           — To display the project link.<br>
                                            {{project_id}}            — To display the project id.<br>
                                            '),
                ),
            ),

            // Notification to employer on project reject
            'rejected_project'  => array(
                'type'      => 'projects',
                'settings'  => array(
                    'admin_comments'    => 'yes',
                    'tage'              => array('employer_name', 'project_title', 'project_link', 'project_id', 'admin_feedback')
                ),
                'options'   => array(
                    'title'             => esc_html__('Notification to employer for rejected project ', 'workreap'),
                    'tag_title'         => esc_html__('Notification setting variables', 'workreap'),
                    'content_title'     => esc_html__('Notification content', 'workreap'),
                    'enable_title'      => esc_html__('Enable/disable notification for rejected project', 'workreap'),
                    'flash_message_title'   => esc_html__('Enable/disable flash message', 'workreap'),
                    'flash_message_option'  => true,
                    'content'           => __('Oho! Your project {{project_title}} has been rejected.<br>{{admin_feedback}}', 'workreap'),
                    'tags'              => __('
                                            {{employer_name}}          — To display the employer name.<br>
                                            {{project_title}}       — To display the project title.<br>
                                            {{project_link}}        — To display the project link.<br>
                                            {{project_id}}          — To display the project id.<br>
                                            {{admin_feedback}}      — To display the admin feedback.<br>
                                            '),
                ),
            ),
            // notification to freelancer on project invitation
            'project_inviation'  => array(
                'type'      => 'projects',
                'settings'  => array(
                    'image_type'    => 'employer_image',
                    'tage'          => array('freelancer_name', 'employer_name', 'project_title', 'project_link', 'project_id'),
                    'btn_settings'  => array('link_type' => 'project_link', 'text' => esc_html__('View project', 'workreap'))
                ),
                'options'   => array(
                    'title'             => esc_html__('Notification to freelancer on invitation ', 'workreap'),
                    'tag_title'         => esc_html__('Notification setting variables', 'workreap'),
                    'content_title'     => esc_html__('Notification content', 'workreap'),
                    'enable_title'      => esc_html__('Enable/disable notification freelancer on invitation', 'workreap'),
                    'flash_message_title'   => esc_html__('Enable/disable flash message', 'workreap'),
                    'flash_message_option'  => true,
                    'content'           => __('You have received a project invitation from {{employer_name}}', 'workreap'),
                    'tags'              => __('
                                            {{employer_name}}          — To display the employer name.<br>
                                            {{freelancer_name}}         — To display the employer name.<br>
                                            {{project_title}}       — To display the project title.<br>
                                            {{project_link}}        — To display the project link.<br>
                                            {{project_id}}          — To display the project id.<br>
                                            '),
                ),
            ),
            // notification to employer on reciving proposal
            'recived_proposal'  => array(
                'type'      => 'proposals',
                'settings'  => array(
                    'image_type'    => 'freelancer_image',
                    'tage'          => array('freelancer_name', 'employer_name', 'project_title', 'project_link', 'project_id', 'proposal_id', 'employer_proposal_link'),
                    'btn_settings'  => array('link_type' => 'employer_proposal_link', 'text' => esc_html__('View proposal', 'workreap'))
                ),
                'options'   => array(
                    'title'             => esc_html__('Notification to employer on proposal submitation ', 'workreap'),
                    'tag_title'         => esc_html__('Notification setting variables', 'workreap'),
                    'content_title'     => esc_html__('Notification content', 'workreap'),
                    'enable_title'      => esc_html__('Enable/disable notification employer on proposal submitation', 'workreap'),
                    'flash_message_title'   => esc_html__('Enable/disable flash message', 'workreap'),
                    'flash_message_option'  => true,
                    'content'           => __('{{freelancer_name}} submit a new proposal on project {{project_title}}', 'workreap'),
                    'tags'              => __('
                                            {{employer_name}}          — To display the employer name.<br>
                                            {{freelancer_name}}         — To display the employer name.<br>
                                            {{project_title}}       — To display the project title.<br>
                                            {{project_link}}        — To display the project link.<br>
                                            {{project_id}}          — To display the project id.<br>
                                            {{proposal_id}}          — To display the proposal id.<br>
                                            {{employer_proposal_link}} — To display the employer proposal detail page.<br>
                                            '),
                ),
            ),
            // notification to freelancer of rejected proposal
            'rejected_proposal'  => array(
                'type'      => 'proposals',
                'settings'  => array(
                    'image_type'    => 'freelancer_image',
                    'tage'          => array('freelancer_name', 'employer_name', 'project_title', 'project_link', 'project_id', 'proposal_id', 'freelancer_proposals_link'),
                    'btn_settings'  => array('link_type' => 'freelancer_proposals_link', 'text' => esc_html__('View proposals', 'workreap'))
                ),
                'options'   => array(
                    'title'             => esc_html__('Notification to freelancer on the rejection of the proposal', 'workreap'),
                    'tag_title'         => esc_html__('Notification setting variables', 'workreap'),
                    'content_title'     => esc_html__('Notification content', 'workreap'),
                    'enable_title'      => esc_html__('Enable/disable notification to freelancer on the rejection of the proposal', 'workreap'),
                    'flash_message_title'   => esc_html__('Enable/disable flash message', 'workreap'),
                    'flash_message_option'  => true,
                    'content'           => __('Oho! your proposal on {{project_title}} has been rejected by {{employer_name}}', 'workreap'),
                    'tags'              => __('
                                            {{employer_name}}          — To display the employer name.<br>
                                            {{freelancer_name}}         — To display the employer name.<br>
                                            {{project_title}}       — To display the project title.<br>
                                            {{project_link}}        — To display the project link.<br>
                                            {{project_id}}          — To display the project id.<br>
                                            {{proposal_id}}          — To display the proposal id.<br>
                                            {{freelancer_proposals_link}} — To display the freelancer proposals page.<br>
                                            '),
                ),
            ),
            // notification to freelancer on hire proposal
            'hired_proposal'  => array(
                'type'      => 'proposals',
                'settings'  => array(
                    'image_type'    => 'employer_image',
                    'tage'          => array('freelancer_name', 'employer_name', 'project_title', 'project_link', 'project_id', 'proposal_id', 'freelancer_proposal_activity'),
                    'btn_settings'  => array('link_type' => 'freelancer_proposal_activity', 'text' => esc_html__('View proposals', 'workreap'))
                ),
                'options'   => array(
                    'title'             => esc_html__('Notification to freelancer on hired project', 'workreap'),
                    'tag_title'         => esc_html__('Notification setting variables', 'workreap'),
                    'content_title'     => esc_html__('Notification content', 'workreap'),
                    'enable_title'      => esc_html__('Enable/disable notification to freelancer on hired project', 'workreap'),
                    'flash_message_title'   => esc_html__('Enable/disable flash message', 'workreap'),
                    'flash_message_option'  => true,
                    'content'           => __('Woohoo! {{employer_name}} hired you for {{project_title}} project', 'workreap'),
                    'tags'              => __('
                                            {{employer_name}}          — To display the employer name.<br>
                                            {{freelancer_name}}         — To display the employer name.<br>
                                            {{project_title}}       — To display the project title.<br>
                                            {{project_link}}        — To display the project link.<br>
                                            {{project_id}}          — To display the project id.<br>
                                            {{proposal_id}}          — To display the proposal id.<br>
                                            {{freelancer_proposal_activity}} — To display the freelancer proposal activity page.<br>
                                            '),
                ),
            ),
            'hired_proposal_milestone'  => array(
                'type'      => 'proposals',
                'settings'  => array(
                    'image_type'    => 'employer_image',
                    'tage'          => array('freelancer_name', 'employer_name', 'project_title', 'project_link', 'project_id', 'proposal_id', 'freelancer_proposal_activity', 'milestone_title'),
                    'btn_settings'  => array('link_type' => 'freelancer_proposal_activity', 'text' => esc_html__('View proposals', 'workreap'))
                ),
                'options'   => array(
                    'title'             => esc_html__('Notification to freelancer on hired project milestone', 'workreap'),
                    'tag_title'         => esc_html__('Notification setting variables', 'workreap'),
                    'content_title'     => esc_html__('Notification content', 'workreap'),
                    'enable_title'      => esc_html__('Enable/disable notification to freelancer on hired project milestone', 'workreap'),
                    'flash_message_title'   => esc_html__('Enable/disable flash message', 'workreap'),
                    'flash_message_option'  => true,
                    'content'           => __('Your milestone {{milestone_title}} of {{project_title}} has been approved', 'workreap'),
                    'tags'              => __('
                                            {{employer_name}}          — To display the employer name.<br>
                                            {{freelancer_name}}         — To display the employer name.<br>
                                            {{project_title}}       — To display the project title.<br>
                                            {{project_link}}        — To display the project link.<br>
                                            {{project_id}}          — To display the project id.<br>
                                            {{proposal_id}}          — To display the proposal id.<br>
                                            {{milestone_title}}      — To display the milestone title.<br>
                                            '),
                ),
            ),
            'project_activity_comments'  => array(
                'type'      => 'proposals',
                'settings'  => array(
                    'image_type'    => 'sender_image',
                    'tage'          => array('sender_name', 'receiver_name', 'project_title', 'project_link', 'activity_comment'),
                    'btn_settings'  => array('link_type' => 'project_activity_link', 'text' => esc_html__('View activity', 'workreap'))
                ),
                'options'   => array(
                    'title'             => esc_html__('Notification to user to recive project activity comment', 'workreap'),
                    'tag_title'         => esc_html__('Notification setting variables', 'workreap'),
                    'content_title'     => esc_html__('Notification content', 'workreap'),
                    'enable_title'      => esc_html__('Enable/disable notification to user to recive project activity comment', 'workreap'),
                    'flash_message_title' => esc_html__('Enable/disable flash message', 'workreap'),
                    'flash_message_option'     => true,
                    'content'           => __('A new activity performed by <strong>{{sender_name}}</strong> on a <strong>{{project_title}}</strong> project', 'workreap'),
                    'tags'              => __('
                                            {{sender_name}}         — To display the sender name.<br>
                                            {{receiver_name}}       — To display the reciver name.<br>
                                            {{project_title}}       — To display the project title.<br>
                                            {{project_link}}        — To display the project link.<br>
                                            {{activity_comment}}     — To display the sender comments.<br>
                                            '),
                ),
            ),
            'milestone_creation'  => array(
                'type'      => 'proposals',
                'settings'  => array(
                    'image_type'    => 'freelancer_image',
                    'tage'          => array('freelancer_name', 'employer_name', 'project_title', 'project_link', 'project_id', 'proposal_id', 'employer_proposal_activity'),
                    'btn_settings'  => array('link_type' => 'employer_proposal_activity', 'text' => esc_html__('View activity', 'workreap'))
                ),
                'options'   => array(
                    'title'             => esc_html__('Notification to employer on milestone creation', 'workreap'),
                    'tag_title'         => esc_html__('Notification setting variables', 'workreap'),
                    'content_title'     => esc_html__('Notification content', 'workreap'),
                    'enable_title'      => esc_html__('Enable/disable notification to employer on milestone creation', 'workreap'),
                    'flash_message_title'   => esc_html__('Enable/disable flash message', 'workreap'),
                    'flash_message_option'  => true,
                    'content'           => __('<strong>{{freelancer_name}}</strong> add new milestone for the project <strong>{{project_title}}</strong>', 'workreap'),
                    'tags'              => __('
                                            {{employer_name}}          — To display the employer name.<br>
                                            {{freelancer_name}}         — To display the employer name.<br>
                                            {{project_title}}       — To display the project title.<br>
                                            {{project_link}}        — To display the project link.<br>
                                            {{project_id}}          — To display the project id.<br>
                                            {{proposal_id}}          — To display the proposal id.<br>
                                            '),
                ),
            ),
            'milestone_request'  => array(
                'type'      => 'proposals',
                'settings'  => array(
                    'image_type'    => 'freelancer_image',
                    'tage'          => array('freelancer_name', 'employer_name', 'project_title', 'project_link', 'project_id', 'proposal_id', 'employer_proposal_activity', 'milestone_title'),
                    'btn_settings'  => array('link_type' => 'employer_proposal_activity', 'text' => esc_html__('View activity', 'workreap'))
                ),
                'options'   => array(
                    'title'             => esc_html__('Notification to employer on milestone completed request', 'workreap'),
                    'tag_title'         => esc_html__('Notification setting variables', 'workreap'),
                    'content_title'     => esc_html__('Notification content', 'workreap'),
                    'enable_title'      => esc_html__('Enable/disable notification to employer on milestone completed request', 'workreap'),
                    'flash_message_title'   => esc_html__('Enable/disable flash message', 'workreap'),
                    'flash_message_option'  => true,
                    'content'           => __('A new milestone <strong>{{milestone_title}}</strong> of <strong>{{project_title}}</strong> approval received from <strong>{{freelancer_name}}</strong>', 'workreap'),
                    'tags'              => __('
                                            {{employer_name}}          — To display the employer name.<br>
                                            {{freelancer_name}}         — To display the employer name.<br>
                                            {{project_title}}       — To display the project title.<br>
                                            {{project_link}}        — To display the project link.<br>
                                            {{project_id}}          — To display the project id.<br>
                                            {{proposal_id}}          — To display the proposal id.<br>
                                            {{milestone_title}}      — To display the milestone title.<br>
                                            '),
                ),
            ),
            'milestone_completed'  => array(
                'type'      => 'proposals',
                'settings'  => array(
                    'image_type'    => 'employer_image',
                    'tage'          => array('freelancer_name', 'employer_name', 'project_title', 'project_link', 'project_id', 'proposal_id', 'freelancer_proposal_activity', 'milestone_title'),
                    'btn_settings'  => array('link_type' => 'freelancer_proposal_activity', 'text' => esc_html__('View activity', 'workreap'))
                ),
                'options'   => array(
                    'title'             => esc_html__('Notification to freelancer after milestone completed', 'workreap'),
                    'tag_title'         => esc_html__('Notification setting variables', 'workreap'),
                    'content_title'     => esc_html__('Notification content', 'workreap'),
                    'enable_title'      => esc_html__('Enable/disable notification to freelancer after milestone completed', 'workreap'),
                    'flash_message_title'   => esc_html__('Enable/disable flash message', 'workreap'),
                    'flash_message_option'  => true,
                    'content'           => __('Your milestone {{milestone_title}} of {{project_title}} marked as completed by {{employer_name}}', 'workreap'),
                    'tags'              => __('
                                            {{employer_name}}          — To display the employer name.<br>
                                            {{freelancer_name}}         — To display the employer name.<br>
                                            {{project_title}}       — To display the project title.<br>
                                            {{project_link}}        — To display the project link.<br>
                                            {{project_id}}          — To display the project id.<br>
                                            {{proposal_id}}          — To display the proposal id.<br>
                                            {{milestone_title}}      — To display the milestone title.<br>
                                            '),
                ),
            ),
            'milestone_decline'  => array(
                'type'      => 'proposals',
                'settings'  => array(
                    'image_type'    => 'employer_image',
                    'tage'          => array('freelancer_name', 'employer_name', 'project_title', 'project_link', 'project_id', 'proposal_id', 'freelancer_proposal_activity', 'milestone_title'),
                    'btn_settings'  => array('link_type' => 'freelancer_proposal_activity', 'text' => esc_html__('View activity', 'workreap'))
                ),
                'options'   => array(
                    'title'             => esc_html__('Notification to freelancer after milestone decline', 'workreap'),
                    'tag_title'         => esc_html__('Notification setting variables', 'workreap'),
                    'content_title'     => esc_html__('Notification content', 'workreap'),
                    'enable_title'      => esc_html__('Enable/disable notification to freelancer after milestone decline', 'workreap'),
                    'flash_message_title'   => esc_html__('Enable/disable flash message', 'workreap'),
                    'flash_message_option'  => true,
                    'content'           => __('Your milestone {{milestone_title}} of {{project_title}} has been declined by {{employer_name}}', 'workreap'),
                    'tags'              => __('
                                            {{employer_name}}          — To display the employer name.<br>
                                            {{freelancer_name}}         — To display the employer name.<br>
                                            {{project_title}}       — To display the project title.<br>
                                            {{project_link}}        — To display the project link.<br>
                                            {{project_id}}          — To display the project id.<br>
                                            {{proposal_id}}          — To display the proposal id.<br>
                                            {{milestone_title}}      — To display the milestone title.<br>
                                            '),
                ),
            ),
        );
        $list   = apply_filters('workreap_filter_list_notification', $list);
        if (!empty($type) && $type == 'type') {
            $new_list   = array();
            foreach ($list as $key => $val) {
                if (!empty($val['type']) && $val['type'] === $value) {
                    $new_list[$key] = !empty($val['options']) ? $val['options'] : array();
                }
            }
            $list   = $new_list;
        } else if (!empty($type) && $type == 'settings') {
            $list   = !empty($list[$value]['settings']) ? $list[$value]['settings'] : array();
        }

        return $list;
    }
}
new Workreap_NotificationsSettings();
