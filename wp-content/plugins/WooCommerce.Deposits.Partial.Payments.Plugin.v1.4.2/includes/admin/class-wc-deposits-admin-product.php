<?php
/*Copyright: © 2014 Abdullah Ali.
License: GNU General Public License v3.0
License URI: http://www.gnu.org/licenses/gpl-3.0.html
*/

if (!defined('ABSPATH')) {
  exit; // Exit if accessed directly
}

/**
 * @brief Adds the necessary panel to the product editor in the admin area
 *
 */
class WC_Deposits_Admin_Product {
  public $wc_deposits;

  public function __construct(&$wc_deposits)
  {
    $this->wc_deposits = $wc_deposits;
    // Hook the product admin page
    add_action('woocommerce_product_write_panel_tabs', array($this, 'tab_options_tab'));
    add_action('woocommerce_product_write_panels', array($this, 'tab_options'));
    add_action('woocommerce_process_product_meta', array($this, 'process_product_meta'));
  }

  /**
  * @brief Adds an extra tab to the product editor
  *
  * @return void
  */
  public function tab_options_tab()
  {
    ?><li class="deposits_tab"><a href="#deposits_tab_data"><?php _e('Deposit', 'woocommerce-deposits'); ?></a></li><?php
  }

  /**
  * @brief Adds tab contents in product editor
  *
  * @return void
  */
  public function tab_options()
  {
    global $post;
    $product = get_product($post->ID);
    ?>
      <div id="deposits_tab_data" class="panel woocommerce_options_panel">
        <div class="options_group">
          <p class="form-field">
            <?php woocommerce_wp_checkbox(array(
              'id' => '_wc_deposits_enable_deposit',
              'label' => __('Enable deposit', 'woocommerce-deposits'),
              'description' => __('Enable this to require a deposit for this item.', 'woocommerce-deposits'),
              'desc_tip' => true));
            ?>
            <?php woocommerce_wp_checkbox(array(
              'id' => '_wc_deposits_force_deposit',
              'label' => __('Force deposit', 'woocommerce-deposits'),
              'description' => __('If you enable this, the customer will not be allowed to make a full payment.',
                'woocommerce-deposits'),
              'desc_tip' => true));
            ?>
          </p>
        </div>

        <div class="options_group">
          <p class="form-field">
            <?php woocommerce_wp_radio(array(
                'id' => '_wc_deposits_amount_type',
                'label' => __('Specify the type of deposit:', 'woocommerce-deposits'),
                'options' => array(
                  'fixed' => __('Fixed value', 'woocommerce-deposits'),
                  'percent' => __('Percentage of price', 'woocommerce-deposits')
                  )
              ));
            ?>
            <?php woocommerce_wp_text_input(array(
              'id' => '_wc_deposits_deposit_amount',
              'label' => __( 'Despoit amount', 'woocommerce-deposits' ),
              'description' => __( 'This is the minimum deposited amount.<br/>Note: Tax will be added to the deposit amount you specify here.',
                'woocommerce-deposits' ),
              'type' => 'number',
              'desc_tip' => true, 'custom_attributes' => array(
                'min'   => '0.0',
                'step'  => '0.01'
                )
              ));
            ?>
          </p>
        </div>

        <?php if ($product->is_type('booking') && $product->has_persons()) : // check if the product has a 'booking' type, and if so, check if it has persons. ?>
          <div class="options_group">
            <p class="form-field">
              <?php woocommerce_wp_checkbox(array(
                'id' => '_wc_deposits_enable_per_person',
                'label' => __('Multiply by persons', 'woocommerce-deposits'),
                'description' => __('Enable this to multiply the deposit by person count. (Only works when Fixed Value is active)',
                  'woocommerce-deposits'),
                'desc_tip' => true));
              ?>
            </p>
          </div>
        <?php endif; ?>
      </div>
    <?php
  }

  /**
  * @brief Updates the product's metadata
  *
  * @return void
  */
  public function process_product_meta($post_id)
  {
    $product = get_product($post_id);

    $enable_deposit = isset($_POST['_wc_deposits_enable_deposit']) ? 'yes' : 'no';
    $force_deposit = isset($_POST['_wc_deposits_force_deposit']) ? 'yes' : 'no';
    $enable_persons = isset($_POST['_wc_deposits_enable_per_person']) ? 'yes' : 'no';
    $amount_type = (isset($_POST['_wc_deposits_amount_type']) &&
                           ($_POST['_wc_deposits_amount_type'] === 'fixed' ||
                            $_POST['_wc_deposits_amount_type'] === 'percent')) ?
                              $_POST['_wc_deposits_amount_type'] : 'fixed';
    $amount = isset($_POST['_wc_deposits_deposit_amount']) &&
              is_numeric($_POST['_wc_deposits_deposit_amount']) ? floatval($_POST['_wc_deposits_deposit_amount']) : 0.0;

    if ($amount <= 0 || ($amount_type === 'percent' && $amount >= 100)) {
      $enable_deposit = 'no';
      $amount = '';
    }

    update_post_meta($post_id, '_wc_deposits_enable_deposit', $enable_deposit);
    update_post_meta($post_id, '_wc_deposits_force_deposit', $force_deposit);
    update_post_meta($post_id, '_wc_deposits_amount_type', $amount_type);
    update_post_meta($post_id, '_wc_deposits_deposit_amount', $amount);

    if ($product->is_type('booking') && $product->has_persons()) {
      update_post_meta($post_id, '_wc_deposits_enable_per_person', $enable_persons);
    }
  }
}
