<?php
/**********************************************************************************************************************************
*
* Size Control Settings
* 
* Author: Webbu Design
*
***********************************************************************************************************************************/

if (!class_exists("Redux_Framework_PF_PGcontrol_Config")) {
	

    class Redux_Framework_PF_PGcontrol_Config{

        public $args = array();
        public $sections = array();
        public $theme;
        public $ReduxFramework;

        public function __construct() {
            if ( !class_exists("ReduxFramework" ) ) {return;}
            if (  true == Redux_Helpers::isTheme(__FILE__) ) {$this->initSettings();} else {add_action('plugins_loaded', array($this, 'initSettings'), 10);}
        }

        public function initSettings() {
            $this->setArguments(); 
            $this->setSections();
            if (!isset($this->args['opt_name'])) { return;}
            $this->ReduxFramework = new ReduxFramework($this->sections, $this->args);
        }


        public function setSections() {
            /**
            *Start : PAYPAL
            **/
                $this->sections[] = array(
                    'id' => 'setup20_paypalsettings',
                    'subsection' => true,
                    'title' => esc_html__('Paypal Settings', 'pointfindert2d'),
                    'fields' => array(
                            array(
                                'id' => 'setup20_paypalsettings_paypal_status',
                                'type' => 'button_set',
                                'title' => esc_html__('Paypal Payment System', 'pointfindert2d') ,
                                'options' => array(
                                    '1' => esc_html__('Enable', 'pointfindert2d') ,
                                    '0' => esc_html__('Disable', 'pointfindert2d')
                                ) ,
                                'default' => 0
                            ) ,
                            array(
                                'id' => 'setup20_paypalsettings_paypal_sandbox',
                                'desc' => esc_html__('If you are using LIVE site please disable this after test.', 'pointfindert2d') ,
                                'type' => 'button_set',
                                'title' => esc_html__('Sandbox(TEST) Mode', 'pointfindert2d') ,
                                'options' => array(
                                    '1' => esc_html__('Enable', 'pointfindert2d') ,
                                    '0' => esc_html__('Disable', 'pointfindert2d')
                                ) ,
                                'default' => '1',
                                'required' => array('setup20_paypalsettings_paypal_status','=','1')
                            ) ,
                            array(
                                'id' => 'setup31_userpayments_recurringoption',
                                'type' => 'button_set',
                                'title' => esc_html__('Paypal Recurring Payments', 'pointfindert2d') ,
                                'options' => array(
                                    '1' => esc_html__('Enable', 'pointfindert2d') ,
                                    '0' => esc_html__('Disable', 'pointfindert2d')
                                ) ,
                                'default' => '1',
                                'required' => array('setup20_paypalsettings_paypal_status','=','1')
                            ) ,
                            
                            array(
                                'id' => 'setup20_paypalsettings_paypal_verified',
                                'desc' => esc_html__('If this option is enabled: Pointfinder will only accept payments from verified Paypal Users', 'pointfindert2d') ,
                                'type' => 'button_set',
                                'title' => esc_html__('Accept Only Verified Users', 'pointfindert2d') ,
                                'options' => array(
                                    '1' => esc_html__('Enable', 'pointfindert2d') ,
                                    '0' => esc_html__('Disable', 'pointfindert2d')
                                ) ,
                                'default' => '0',
                                'required' => array('setup20_paypalsettings_paypal_status','=','1')
                            ) ,
                            array(
                                'id'        => 'setup20_paypalsettings_paypal_price_unit',
                                'type'      => 'text',
                                'title'     => esc_html__('Paypal Price Unit', 'pointfindert2d'),
                                'default'   => 'USD',
                                'required' => array('setup20_paypalsettings_paypal_status','=','1'),
                                'desc'      => sprintf(esc_html__('You can find all currency codes on this page %s', 'pointfindert2d'),'<a href="https://developer.paypal.com/docs/classic/api/currency_codes/" target="_blank">https://developer.paypal.com/docs/classic/api/currency_codes/</a>'),
                            ),
                            array(
                                'id'        => 'setup20_paypalsettings_paypal_api_user',
                                'type'      => 'text',
                                'title'     => esc_html__('Paypal API User', 'pointfindert2d'),
                                'required' => array('setup20_paypalsettings_paypal_status','=','1')
                            ),
                            array(
                                'id'        => 'setup20_paypalsettings_paypal_api_pwd',
                                'type'      => 'text',
                                'title'     => esc_html__('Paypal API Password', 'pointfindert2d'),
                                'required' => array('setup20_paypalsettings_paypal_status','=','1')
                            ),
                            array(
                                'id'        => 'setup20_paypalsettings_paypal_api_signature',
                                'type'      => 'text',
                                'title'     => esc_html__('Paypal API Signature', 'pointfindert2d'),
                                'required' => array('setup20_paypalsettings_paypal_status','=','1')
                            )
                        ) ,
                );
            /**
            *End : PAYPAL
            **/

            /**
            *Start : Stripe
            **/
                $this->sections[] = array(
                    'id' => 'setup20_stripesettings',
                    'title' => esc_html__('Stripe Settings', 'pointfindert2d'),
                    'fields' => array(
                            array(
                                'id' => 'setup20_stripesettings_status',
                                'type' => 'button_set',
                                'title' => esc_html__('Stripe Payment System', 'pointfindert2d') ,
                                'options' => array(
                                    '1' => esc_html__('Enable', 'pointfindert2d') ,
                                    '0' => esc_html__('Disable', 'pointfindert2d')
                                ) ,
                                'default' => 0
                            ) ,
                            array(
                                'id'        => 'setup20_stripesettings_secretkey',
                                'type'      => 'text',
                                'title'     => esc_html__('Secret Key', 'pointfindert2d'),
                                'required' => array('setup20_stripesettings_status','=','1')
                            ),
                            array(
                                'id'        => 'setup20_stripesettings_publishkey',
                                'type'      => 'text',
                                'title'     => esc_html__('Publishable Key', 'pointfindert2d'),
                                'required' => array('setup20_stripesettings_status','=','1')
                            ),
                            array(
                                'id'        => 'setup20_stripesettings_sitename',
                                'type'      => 'text',
                                'title'     => esc_html__('Site Name', 'pointfindert2d'),
                                'desc' => esc_html__('This will seen in payment box. Ex: Stripe.com', 'pointfindert2d') ,
                                'required' => array('setup20_stripesettings_status','=','1')
                            ),
                            array(
                                'id'        => 'setup20_stripesettings_currency',
                                'type'      => 'text',
                                'title'     => esc_html__('Stripe Currency', 'pointfindert2d'),
                                'default'   => 'USD',
                                'required' => array('setup20_stripesettings_status','=','1'),
                                'desc'      => sprintf(esc_html__('Please check this page for other currencies: %s CURRENCY CODES %s', 'pointfindert2d'),'<a href="https://support.stripe.com/questions/which-currencies-does-stripe-support" target="_blank">','</a>'),
                            ),
                            array(
                                'id' => 'setup20_stripesettings_decimals',
                                'type' => 'button_set',
                                'title' => esc_html__('Decimals', 'pointfindert2d') ,
                                'desc'      => sprintf(esc_html__('Please check this page: %s DECIMAL INFO %s %s If your currency listed in this page please use decimal number 0', 'pointfindert2d'),'<a href="https://support.stripe.com/questions/which-zero-decimal-currencies-does-stripe-support" target="_blank">','</a>','<br/>'),
                                'options' => array(
                                    '2' => esc_html__('2', 'pointfindert2d') ,
                                    '0' => esc_html__('0', 'pointfindert2d')
                                ) ,
                                'default' => '2',
                                'required' => array('setup20_stripesettings_status','=','1'),
                            )
                    )
                );
            /**
            *End : Stripe
            **/

			
        }

        

        public function setArguments() {


            $this->args = array(
                'opt_name'             => 'pfpgcontrol_options',
                'display_name'         => esc_html__('Point Finder Payment Gateways','pointfindert2d'),
                'menu_type'            => 'submenu',
                'page_parent'          => 'pointfinder_tools',
                'menu_title'           => esc_html__('Payment Gateways','pointfindert2d'),
                'page_title'           => esc_html__('Payment Gateways', 'pointfindert2d'),
                'admin_bar'            => false,
                'allow_sub_menu'       => false,
                'admin_bar_priority'   => 50,
                'global_variable'      => '',
                'dev_mode'             => false,
                'update_notice'        => false,
                'menu_icon'            => 'dashicons-twitter',
                'page_slug'            => '_pfpgconf',
                'save_defaults'        => false,
                'default_show'         => false,
                'default_mark'         => '',
                'transient_time'       => 60 * MINUTE_IN_SECONDS,
                'output'               => true,
                'output_tag'           => false,
                'database'             => '',
                'system_info'          => false,
                'domain'               => 'redux-framework',
                'hide_reset'           => true,
                'update_notice'        => false,
                'compiler'             => true,
            );


        }

    }

    global $pointfinder_pg_options;
    $pointfinder_pg_options = new Redux_Framework_PF_PGcontrol_Config();
	
}