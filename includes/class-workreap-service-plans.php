<?php

/**
 *
 * Class 'Workreap_Service_Plans' defines task plans
 * 
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @package     Workreap
 * @subpackage  Workreap/includes
 * @author      Amentotech <info@amentotech.com>
 * @link        https://codecanyon.net/user/amentotech/portfolio
 * @version     1.0
 * @since       1.0
*/

if (!class_exists('Workreap_Service_Plans')){
    
    class Workreap_Service_Plans{
  
        private static $instance = null;

        public function __construct(){
           
        }

        /**
         * Returns the *Singleton* instance of this class.
         *
         * @return
         * @throws error
         * @author Amentotech <theamentotech@gmail.com>
         */
        public static function getInstance(){

            if (self::$instance==null){
                self::$instance = new Workreap_Service_Plans();
            }
            return self::$instance;
        }

        /**
         * @ Default task plans
         * @return
        */
        public static function service_plans(){
            global $workreap_settings;
		    $remove_price_plans		= !empty($workreap_settings['remove_price_plans']) ? $workreap_settings['remove_price_plans'] : 'no';

            $workreap_service_plans = array(
                'basic' => array(
                    'title' => array(
                        'id'            => 'title',
                        'label'         => __('Package title', 'workreap'),
                        'type'          =>'text',
                        'value'         => '',
                        'class'         => 'title',
                        'placeholder'   => esc_html__('Add title', 'workreap'),
                        'title'         => esc_html__('Please enter title', 'workreap'),
                        'required'      => true,
                    ),
                    'description'=> array(
                        'id'            => 'description',
                        'label'         => esc_html__('Description', 'workreap'),
                        'type'          => 'textarea',
                        'default_value' => '',
                        'class'         =>'description',
                        'placeholder'   => esc_html__('Description', 'workreap'),
                        'title'         => esc_html__('Please enter description', 'workreap'),
                        'required'      => true,
                    ),
                    'price'=> array(
                        'id'            => 'price',
                        'label'         => esc_html__('Price', 'workreap'),
                        'type'          => 'number',
                        'min'           => 0,
                        'max'           => 0,
                        'default_value' => '',
                        'class'         =>'price',
                        'placeholder'   => esc_html__('Price', 'workreap'),
                        'title'         => esc_html__('Please enter price', 'workreap'),
                        'required'      => true,
                        
                    ),
                    'delivery_time'=> array(
                        'id'            => 'delivery_time',
                        'label'         => esc_html__('Delivery time', 'workreap'),
                        'type'          => 'terms_dropdwon',
                        'taxonomy'      => 'delivery_time',
                        'default_value' => '',
                        'class'         =>'delivery-time',
                        'placeholder'   => esc_html__('Delivery time', 'workreap'),
                        'title'         => esc_html__('Please enter delivery time', 'workreap'),
                        'required'      => true,
                    ), 
                    'featured_package'=> array(
                        'id'            => 'featured_package',
                        'label'         => esc_html__('Featured package', 'workreap'),
                        'type'          => 'featured_package',
                        'default_value' => '',
                        'class'         =>'featured-package',
                        'placeholder'   => esc_html__('Featured package', 'workreap'),
                        'title'         => esc_html__('Please select featured package', 'workreap'),
                        'required'      => true,
                    ),                  
                ),            
                'premium' => array(
                    'title' => array(
                        'id'            => 'title',
                        'label'         => esc_html__('Package title', 'workreap'),
                        'type'          => 'text',
                        'value'         => '',
                        'class'         => 'title',
                        'placeholder'   => esc_html__('Add title', 'workreap'),
                        'title'         => esc_html__('Please enter title', 'workreap'),
                        'required'      => true,
                    ),
                    'description'=> array(
                        'id'            => 'description',
                        'label'         => esc_html__('Description', 'workreap'),
                        'type'          => 'textarea',
                        'default_value' => '',
                        'class'         =>'description',
                        'placeholder'   => esc_html__('Description', 'workreap'),
                        'title'         => esc_html__('Please enter description', 'workreap'),
                        'required'      => true,
                    ),
                    'price'=> array(
                        'id'            => 'price',
                        'label'         => esc_html__('Price', 'workreap'),
                        'type'          => 'number',
                        'min'           => 0,
                        'max'           => 0,
                        'default_value' => '',
                        'class'         =>'price',
                        'placeholder'   => esc_html__('Price', 'workreap'),
                        'title'         => esc_html__('Please enter price', 'workreap'),
                        'required'      => true,
                    ),
                    'delivery_time'=> array(
                        'id'            => 'delivery_time',
                        'label'         => esc_html__('Delivery time', 'workreap'),
                        'type'          => 'terms_dropdwon',
                        'taxonomy'      => 'delivery_time',
                        'default_value' => '',
                        'class'         =>'delivery-time',
                        'placeholder'   => esc_html__('Delivery time', 'workreap'),
                        'title'         => esc_html__('Please enter delivery time', 'workreap'),
                        'required'      => true,
                    ),      
                    'featured_package'=> array(
                        'id'            => 'featured_package',
                        'label'         => esc_html__('Featured package', 'workreap'),
                        'type'          => 'featured_package',
                        'default_value' => '',
                        'class'         =>'featured-package',
                        'placeholder'   => esc_html__('Featured package', 'workreap'),
                        'title'         => esc_html__('Please select featured package', 'workreap'),
                        'required'      => true,
                    ),                
                ),
                'pro' => array(
                    'title' => array(
                        'id'            => 'title',   
                        'label'         => esc_html__('Package title', 'workreap'),
                        'type'          => 'text',
                        'value'         => '',
                        'class'         => 'title',
                        'placeholder'   => esc_html__('Add title', 'workreap'),
                        'title'         => esc_html__('Please enter title', 'workreap'),
                        'required'      => true,
                    ),
                    'description'=> array(
                        'id'            => 'description',
                        'label'         => esc_html__('Description', 'workreap'),
                        'type'          => 'textarea',
                        'default_value' => '',
                        'class'         => 'description',
                        'placeholder'   => esc_html__('Description', 'workreap'),
                        'title'         => esc_html__('Please enter description', 'workreap'),
                        'required'      => true,
                    ),
                    'price'=> array(
                        'id'            => 'price',
                        'label'         => esc_html__('Price', 'workreap'),
                        'type'          => 'number',
                        'min'           => 0,
                        'max'           => 0,
                        'default_value' => '',
                        'class'         =>'price',
                        'placeholder'   => esc_html__('Price', 'workreap'),
                        'title'         => esc_html__('Please enter price', 'workreap'),
                        'required'      => true,
                    ),
                    'delivery_time'=> array(
                        'id'            => 'delivery_time',
                        'label'         => esc_html__('Delivery time', 'workreap'),
                        'type'          => 'terms_dropdwon',
                        'taxonomy'      => 'delivery_time',
                        'default_value' => '',
                        'class'         =>'delivery-time',
                        'placeholder'   => esc_html__('Delivery time', 'workreap'),
                        'title'         => esc_html__('Please delivery time', 'workreap'),
                        'required'      => true,
                    ),
                    'featured_package'=> array(
                        'id'            => 'featured_package',
                        'label'         => esc_html__('Featured package', 'workreap'),
                        'type'          => 'featured_package',
                        'default_value' => '',
                        'class'         =>'featured-package',
                        'placeholder'   => esc_html__('Featured package', 'workreap'),
                        'title'         => esc_html__('Please select featured package', 'workreap'),
                        'required'      => true,
                    ),   
                ),
            );
            
            if(!empty($remove_price_plans) && $remove_price_plans == 'yes'){
                unset( $workreap_service_plans['premium'] );
                unset( $workreap_service_plans['pro'] );
            }

            return apply_filters('workreap_service_plans', $workreap_service_plans);
        }
      
    }
}