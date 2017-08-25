<?php
/**********************************************************************************************************************************
*
* Ajax Payment System for Pay per post
* 
* Author: Webbu Design
*
***********************************************************************************************************************************/


add_action( 'PF_AJAX_HANDLER_pfget_listingpaymentsystem', 'pf_ajax_listingpaymentsystem' );
add_action( 'PF_AJAX_HANDLER_nopriv_pfget_listingpaymentsystem', 'pf_ajax_listingpaymentsystem' );

function pf_ajax_listingpaymentsystem(){
  
	//Security
  check_ajax_referer( 'pfget_lprice', 'security');
  
	header('Content-Type: application/json; charset=UTF-8;');

  $c = $p = $f = $o = $px = $output = $total_pr_output = $total_pr = '';
  $hide_somepayments = 0;

  /* Listing Type */
  if(isset($_POST['c']) && $_POST['c']!=''){
    $c = esc_attr($_POST['c']);
  }


  /* Selected Package */
  if(isset($_POST['p']) && $_POST['p']!=''){
    $p = esc_attr($_POST['p']);
  }

  /*Featured Status*/
  if(isset($_POST['f']) && $_POST['f']!=''){
    $f = esc_attr($_POST['f']);
  }

  /*Order ID*/
  if(isset($_POST['o']) && $_POST['o']!=''){
    $o = esc_attr($_POST['o']);
  }

  /*Process*/
  if(isset($_POST['px']) && $_POST['px']!=''){
    $px = esc_attr($_POST['px']);
    /*1-> Edit */
  }


  /* Start: Defaults from Options Panel */
    $bank_status = PFSAIssetControl('setup20_paypalsettings_bankdeposit_status','','0');
    $paypal_status = PFSAIssetControl('setup20_paypalsettings_paypal_status','','1');
    $stripe_status = PFSAIssetControl('setup20_stripesettings_status','','0');

    $current_user = wp_get_current_user();
    $user_id = $current_user->ID;
  /* End: Defaults from Options Panel */


    $results = pointfinder_calculate_listingtypeprice($c,$f,$p);

    $total_pr = $results['total_pr'];
    $cat_price = $results['cat_price'];
    $pack_price = $results['pack_price'];
    $featured_price = $results['featured_price'];
    $total_pr_output = $results['total_pr_output'];
    $featured_pr_output = $results['featured_pr_output'];
    $pack_pr_output = $results['pack_pr_output'];
    $cat_pr_output = $results['cat_pr_output'];
    $pack_title = $results['pack_title'];

    if ($f == 1 && pointfinder_get_package_price_ppp($p) == 0) {
      $hide_somepayments = 1;
    }

    
    if (!empty($o)) {
      $bank_current = get_post_meta( $o, 'pointfinder_order_bankcheck', true);
    } else {
      $bank_current = false;
    }
    

    /* Price info show */
    if ($total_pr != 0) {
      $output .= '<div class="pf-membership-price-header">'.esc_html__("Summary",'pointfindert2d').'</div>';
      $output .= '
      <div class="pf-membership-package-box">
        <div class="pf-lppack-package-info">
        <ul>';
          if ($cat_price != 0) {
            $output .= '<li><span class="pf-lppack-package-info-title">'.esc_html__("Listing Type :",'pointfindert2d').' </span> <span class="pf-lppack-package-info-price">'.$cat_pr_output.'</span></li>';
          }
          if ($pack_price != 0) {
            $output .= '<li><span class="pf-lppack-package-info-title">'.sprintf(__("Package (%s) :",'pointfindert2d'),'<small>'.$pack_title.'</small>').' </span> <span class="pf-lppack-package-info-price">'.$pack_pr_output.'</span></li>';
          }
          if ($featured_price != 0) {
            $output .= '<li><span class="pf-lppack-package-info-title">'.esc_html__("Featured Item :",'pointfindert2d').' </span> <span class="pf-lppack-package-info-price">'.$featured_pr_output.'</span></li>';
          }
          $output .= '<li class="pf-total-pricelp"><span class="pf-lppack-package-info-title">'.esc_html__("Total :",'pointfindert2d').' </span> <span class="pf-lppack-package-info-price">'.$total_pr_output.'</span></li>';

      $output .= ' 
        </ul>
        </div>
      </div>';
    }
   

    /*Payment Options*/
    if ($total_pr != 0) {
      $output .= '<div class="pf-membership-price-header">'.esc_html__("Payment Options",'pointfindert2d').'</div>';
      
      if ($bank_status == 1 && $px != 1) {
       
        if ($bank_current != false && !empty($bank_current)) {
          $output .= '
          <div class="pf-lpacks-upload-option">
            <input name="pf_lpacks_payment_selection" type="radio" id="pfm-payment-bank" value="bank" disabled="disabled">
            <label for="pfm-payment-bank">'.esc_html__("Bank Transfer",'pointfindert2d').' <font style="font-weight:normal;"> '.esc_html__('(Disabled - Please complete or cancel existing transfer.)','pointfindert2d').'</font></label>
            <div class="pfm-active">
            <p>'.__("Make your payment directly into our bank account. Please use your Order ID as the payment reference. Your order won't be approved until the funds have cleared in our account.",'pointfindert2d').'</p>
            </div>
          </div>';
        } else {
          $output .= '
          <div class="pf-lpacks-upload-option">
            <input name="pf_lpacks_payment_selection" type="radio" id="pfm-payment-bank" value="bank">
            <label for="pfm-payment-bank">'.esc_html__("Bank Transfer",'pointfindert2d').'</label>
            <div class="pfm-active">
            <p>'.__("Make your payment directly into our bank account. Please use your Order ID as the payment reference. Your order won't be approved until the funds have cleared in our account.",'pointfindert2d').'</p>
            </div>
          </div>';
        }

      }
      if ($paypal_status == 1) {  
        $output .= '
        <div class="pf-lpacks-upload-option">
          <input name="pf_lpacks_payment_selection" type="radio" id="pfm-payment-paypal" value="paypal">
          <label for="pfm-payment-paypal">'.esc_html__('Paypal','pointfindert2d').'</label>
          <div class="pfm-active">
          <p>'.__("Pay via PayPal; you can pay with your credit card if you don't have a PayPal account.",'pointfindert2d').'</p>
          </div>
        </div>';
        $setup31_userpayments_recurringoption = PFSAIssetControl('setup31_userpayments_recurringoption','','1');
        if($setup31_userpayments_recurringoption == 1 && $px != 1 && $hide_somepayments == 0){
          $output .= '
          <div class="pf-lpacks-upload-option">
            <input name="pf_lpacks_payment_selection" type="radio" id="pfm-payment-paypal2" value="paypal2">
            <label for="pfm-payment-paypal2">'.esc_html__('Paypal Recurring Payment','pointfindert2d').'</label>
            <div class="pfm-active">
            <p>'.__("Pay via PayPal Recurring Payment; you can create automated payments for this order.",'pointfindert2d').'</p>
            </div>
          </div>';
        }
      }
      if ($stripe_status == 1) {
        $output .= '
        <div class="pf-lpacks-upload-option">
          <input name="pf_lpacks_payment_selection" type="radio" id="pfm-payment-stripe" value="stripe">
          <label for="pfm-payment-stripe">'.esc_html__('Credit Card (Stripe)','pointfindert2d').'</label>
          <div class="pfm-active">
          <p>'.__("Pay via Credit Card; you can pay with your credit card. (This service is using Stripe Payment Gateway)",'pointfindert2d').'</p>
          </div>
        </div>';
      }
      if ($stripe_status != 1 && $paypal_status != 1 && $bank_status != 1) {
        $output .= '<div class="pf-lpacks-upload-option">'.esc_html__('Please enable a payment system by using Options Panel','pointfindert2d').'</div>';
      }else{
        $output .= '
        <script>
        (function($) {
        "use strict";
          $(function(){
            var lpacks_radio = $(".pf-lpacks-upload-option input[type=\'radio\']");
            lpacks_radio.on("change", function () {
            lpacks_radio.parents().removeClass("active");
            $(this).parent().addClass("active");
            });
          });
        })(jQuery);
        </script>
        ';
      }
    }else{
      $output .= '<input name="pf_lpacks_payment_selection" type="hidden" id="pfm-payment-free" value="free">';
    }

  
  echo json_encode(array('html'=>trim($output),'totalpr'=>$total_pr));

die();
}

?>