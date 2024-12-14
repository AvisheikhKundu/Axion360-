<?php
/**
 * Premio Affilate Class
 *
 * @author  : Premio <contact@premio.io>
 * @license : GPL2
 * */

if (defined('ABSPATH') === false) {
    exit;
}

class Chaty_Affiliate_Program
{

    /**
     * The Name of this plugin.
     *
     * @var    string    $pluginName    The Name of this plugin.
     * @since  1.0.0
     * @access public
     */
    public $pluginName = "Chaty";

    /**
     * The Slug of this plugin.
     *
     * @var    string    $pluginSlug    The Slug of this plugin.
     * @since  1.0.0
     * @access public
     */
    public $pluginSlug = "chaty";

    /**
     * __construct method initializes the object and adds action hooks.
     *
     * @return void
     */
    public function __construct()
    {
        add_action("wp_ajax_".$this->pluginSlug."_affiliate_program", [$this, "affiliate_program"]);

        add_action('admin_notices', [$this, 'admin_notices']);

    }//end __construct()


    /**
     * affiliate_program method handles the affiliate program action.
     *
     * This method checks if the current user has the capability to manage options.
     * If the user has the capability, it retrieves the nonce and days values from the POST data.
     * Then, it verifies the nonce and performs the necessary actions based on the values.
     * If the days value is -1, it adds an option to hide the affiliate box.
     * Otherwise, it adds an option to set the date after which the affiliate box should be shown.
     *
     * @return void
     */
    public function affiliate_program()
    {
        if (current_user_can('manage_options')) {
            $nonce = filter_input(INPUT_POST, 'nonce');
            $days  = filter_input(INPUT_POST, 'days');
            if (!empty($nonce) && wp_verify_nonce($nonce, $this->pluginSlug."_affiliate_program")) {
                if ($days == -1) {
                    add_option($this->pluginSlug."_hide_affiliate_box", "1");
                } else {
                    $date = gmdate("Y-m-d", strtotime("+".$days." days"));
                    update_option($this->pluginSlug."_show_affiliate_box_after", $date);
                }
            }

            die;
        }

    }//end affiliate_program()


    /**
     * admin_notices method displays notices in the admin area for users with the 'manage_options' capability.
     *
     * @return void
     */
    public function admin_notices()
    {
        if (current_user_can('manage_options')) {
            $isHidden = get_option($this->pluginSlug."_hide_affiliate_box");
            if ($isHidden !== false) {
                return;
            }

            $dateToShow = get_option($this->pluginSlug."_show_affiliate_box_after");
            if ($dateToShow === false || empty($dateToShow)) {
                $date = gmdate("Y-m-d", strtotime("+5 days"));
                update_option($this->pluginSlug."_show_affiliate_box_after", $date);
                return;
            }

            $currentDate = gmdate("Y-m-d");
            if ($currentDate < $dateToShow) {
                return;
            }
            ?>
            <style>
                .<?php echo esc_attr($this->pluginSlug) ?>-premio-affiliate p a {
                    display: inline-block;
                    float: right;
                    text-decoration: none;
                    color: #999999;
                    position: absolute;
                    right: 12px;
                    top: 12px;
                }

                .notice.chaty-premio-affiliate {
                    display: block !important;
                }

                .<?php echo esc_attr($this->pluginSlug) ?>-premio-affiliate p a:hover, .<?php echo esc_attr($this->pluginSlug) ?>-premio-affiliate p a:focus {
                    color: #333333;
                }

                .<?php echo esc_attr($this->pluginSlug) ?>-premio-affiliate .button span {
                    display: inline-block;
                    line-height: 27px;
                    font-size: 16px;
                }

                .<?php echo esc_attr($this->pluginSlug) ?>-premio-affiliate {
                    padding: 1px 100px 12px 12px;
                    margin: 15px 15px 2px;
                    position: relative;
                }

                .<?php echo esc_attr($this->pluginSlug) ?>-affiliate-popup {
                    position: fixed;
                    width: 100%;
                    height: 100%;
                    z-index: 10001;
                    background: rgba(0, 0, 0, 0.65);
                    top: 0;
                    left: 0;
                    display: none;
                }

                .<?php echo esc_attr($this->pluginSlug) ?>-affiliate-popup-content {
                    background: #ffffff;
                    padding: 20px;
                    position: absolute;
                    max-width: 450px;
                    width: 100%;
                    margin: 0 auto;
                    top: 45%;
                    left: 0;
                    right: 0;
                    -webkit-border-radius: 5px;
                    -moz-border-radius: 5px;
                    border-radius: 5px;
                :;
                }

                .<?php echo esc_attr($this->pluginSlug) ?>-affiliate-title {
                    padding: 0 0 10px 0;
                    font-weight: bold;
                }

                .<?php echo esc_attr($this->pluginSlug) ?>-affiliate-options a {
                    display: block;
                    margin: 5px 0 5px 0;
                    color: #333;
                    text-decoration: none;
                }

                .<?php echo esc_attr($this->pluginSlug) ?>-affiliate-options a.dismiss {
                    color: #999;
                }

                .<?php echo esc_attr($this->pluginSlug) ?>-affiliate-options a:hover, .affiliate-options a:focus {
                    color: #0073aa;
                }

                button.<?php echo esc_attr($this->pluginSlug) ?>-close-affiliate-popup {
                    position: absolute;
                    top: 5px;
                    right: 0;
                    border: none;
                    background: transparent;
                    cursor: pointer;
                }

                a.button.button-primary.<?php echo esc_attr($this->pluginSlug) ?>-affiliate-btn {
                    font-size: 14px;
                    background: #F51366;
                    color: #fff;
                    border: solid 1px #F51366;
                    border-radius: 3px;
                    line-height: 24px;
                    -webkit-box-shadow: 0 3px 5px -3px #333333;
                    -moz-box-shadow: 0 3px 5px -3px #333333;
                    box-shadow: 0 3px 5px -3px #333333;
                    text-shadow: none;
                }
            </style>
            <div class="notice notice-info <?php echo esc_attr($this->pluginSlug) ?>-premio-affiliate <?php echo esc_attr($this->pluginSlug) ?>-premio-affiliate">
                <p>
                    <?php printf(esc_html__("Hi there, you've been using %1\$s for a while now. Do you know that %2\$s has an affiliate program? Join now and get %3\$s"), "<b>".esc_attr($this->pluginName)."</b>", "<b>".esc_attr($this->pluginName)."</b>", "<b>25% lifetime commission</b>") ?>
                    <a href="javascript:;" class="dismiss-btn"><span class="dashicons dashicons-no-alt"></span><?php  esc_html_e("Dismiss", 'stars-testimonials')?></a>
                </p>
                <div class="clear clearfix"></div>
                <a class="button button-primary <?php echo esc_attr($this->pluginSlug) ?>-affiliate-btn" target="_blank" href="https://premio.io/affiliates/?utm_source=inapp&plugin=stars-testimonials&domain=<?php echo esc_attr($_SERVER['HTTP_HOST']) ?>"><?php esc_html_e("Tell me more", 'stars-testimonials') ?>  <span class="dashicons dashicons-arrow-right-alt"></span></a>
            </div>
            <div class="<?php echo esc_attr($this->pluginSlug) ?>-affiliate-popup">
                <div class="<?php echo esc_attr($this->pluginSlug) ?>-affiliate-popup-content">
                    <button class="<?php echo esc_attr($this->pluginSlug) ?>-close-affiliate-popup"><span class="dashicons dashicons-no-alt"></span></button>
                    <div class="<?php echo esc_attr($this->pluginSlug) ?>-affiliate-title"><?php esc_html_e("Would you like us to remind  you about this later?", 'stars-testimonials') ?></div>
                    <div class="<?php echo esc_attr($this->pluginSlug) ?>-affiliate-options">
                        <a href="javascript:;" data-days="3"><?php esc_html_e("Remind me in 3 days", 'stars-testimonials') ?></a>
                        <a href="javascript:;" data-days="10"><?php esc_html_e("Remind me in 10 days", 'stars-testimonials') ?></a>
                        <a href="javascript:;" data-days="-1" class="dismiss"><?php esc_html_e("Don't remind me about this", 'stars-testimonials') ?></a>
                    </div>
                </div>
            </div>
            <script>
                jQuery(document).ready(function () {
                    jQuery(document).on("click", ".<?php echo esc_attr($this->pluginSlug) ?>-premio-affiliate p a.dismiss-btn", function () {
                        jQuery(".<?php echo esc_attr($this->pluginSlug) ?>-affiliate-popup").show();
                    });
                    jQuery(document).on("click", ".<?php echo esc_attr($this->pluginSlug) ?>-close-affiliate-popup", function () {
                        jQuery(".<?php echo esc_attr($this->pluginSlug) ?>-affiliate-popup").hide();
                    });
                    jQuery(document).on("click", ".<?php echo esc_attr($this->pluginSlug) ?>-affiliate-options a", function () {
                        var dataDays = jQuery(this).attr("data-days");
                        jQuery(".<?php echo esc_attr($this->pluginSlug) ?>-affiliate-popup").hide();
                        jQuery(".<?php echo esc_attr($this->pluginSlug) ?>-premio-affiliate").hide();
                        jQuery.ajax({
                            url: "<?php echo esc_url(admin_url("admin-ajax.php")) ?>",
                            data: "action=<?php echo esc_attr($this->pluginSlug) ?>_affiliate_program&days=" + dataDays + "&nonce=<?php echo esc_attr(wp_create_nonce($this->pluginSlug."_affiliate_program")) ?>",
                            type: "post",
                            success: function () {
                                jQuery(".<?php echo esc_attr($this->pluginSlug) ?>-affiliate-popup").remove();
                                jQuery(".<?php echo esc_attr($this->pluginSlug) ?>-premio-affiliate").remove();
                            }
                        });
                    });
                });
            </script>
            <?php
        }//end if

    }//end admin_notices()


}//end class

$chatyAffiliateProgram = new Chaty_Affiliate_Program();
