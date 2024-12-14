<?php
/**
 * Trigger and Targeting Setting
 *
 * @author  : Premio <contact@premio.io>
 * @license : GPL2
 * */

if (defined('ABSPATH') === false) {
    exit;
}

$days = [
    "0"  => esc_html__("Everyday of week", "chaty"),
    "1"  => esc_html__("Sunday", "chaty"),
    "2"  => esc_html__("Monday", "chaty"),
    "3"  => esc_html__("Tuesday", "chaty"),
    "4"  => esc_html__("Wednesday", "chaty"),
    "5"  => esc_html__("Thursday", "chaty"),
    "6"  => esc_html__("Friday", "chaty"),
    "7"  => esc_html__("Saturday", "chaty"),
    "8"  => esc_html__("Sunday to Thursday", "chaty"),
    "9"  => esc_html__("Monday to Friday", "chaty"),
    "10" => esc_html__("Weekend", "chaty"),
];
$url_options = [
    'home'            => esc_html__("Homepage", "chaty"),
    'page_contains'   => esc_html__('Link that contain', "chaty"),
    'page_has_url'    => esc_html__('A specific link', "chaty"),
    'page_start_with' => esc_html__('Links starting with', "chaty"),
    'page_end_with'   => esc_html__('Links ending with', "chaty"),
    'wp_pages'        => esc_html__('WordPress Pages', "chaty"),
    'wp_posts'        => esc_html__('WordPress Posts', "chaty"),
    'wp_categories'   => esc_html__('WordPress Categories', "chaty"),
    'wp_tags'         => esc_html__('WordPress Tags',  "chaty")
];
$hasWooCommerce = 0;
if(is_plugin_active("woocommerce/woocommerce.php")) {
    $hasWooCommerce = 1;
}
if($hasWooCommerce) {
    $url_options['wc_products'] = esc_html__('WooCommerce products', "chaty");
    $url_options['wc_products_on_sale'] = esc_html__('WooCommerce products on sale', "chaty");
}
?>

<section class="section">
    <div class="form-horizontal space-y-7">
        <div class="form-horizontal__item" id="trigger-setting">
            <label class="form-horizontal__item-label font-primary text-cht-gray-150 text-base block mb-3">
                <?php esc_html_e('Trigger', 'chaty');?>:
            </label>
            <?php
             $cht_active = get_option('cht_active');
             $cht_active = ($cht_active === false) ? 1 : $cht_active;
            ?>
            <!-- button for activiting widget -->
            <input type="hidden" name="cht_active" value="0">
            <div class="flex items-center space-x-2">
                <div>
                    <input type="hidden" name="cht_active" value="0"  >
                    <label class="text-base text-cht-gray-150 font-primary" for="active_widget"><?php esc_html_e("Active", "chaty") ?></label>
                </div>
                <label class="chaty-switch font-primary text-cht-gray-150 text-base" for="active_widget">
                    <input type="checkbox" id="active_widget" name="cht_active" class="cht_active" name="cht_active" value="1" <?php checked($cht_active, 1) ?>>
                    <div class="chaty-slider round"></div>
                </label>
            </div>
            <!-- end of wiget button button -->

            <!-- show when widget is deactivated -->
            <div class="widget-disable-alert bg-[#f9fafb] text-[#49687E] mt-3 select-none flex items-center space-x-3.5 text-base w-52 justify-center rounded-lg border border-solid border-[#eaeff2] py-4">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                    <path d="M10.2898 3.86001L1.81978 18C1.64514 18.3024 1.55274 18.6453 1.55177 18.9945C1.55079 19.3437 1.64127 19.6871 1.8142 19.9905C1.98714 20.2939 2.2365 20.5468 2.53748 20.7239C2.83847 20.901 3.18058 20.9962 3.52978 21H20.4698C20.819 20.9962 21.1611 20.901 21.4621 20.7239C21.763 20.5468 22.0124 20.2939 22.1853 19.9905C22.3583 19.6871 22.4488 19.3437 22.4478 18.9945C22.4468 18.6453 22.3544 18.3024 22.1798 18L13.7098 3.86001C13.5315 3.56611 13.2805 3.32313 12.981 3.15449C12.6814 2.98585 12.3435 2.89726 11.9998 2.89726C11.656 2.89726 11.3181 2.98585 11.0186 3.15449C10.7191 3.32313 10.468 3.56611 10.2898 3.86001Z" fill="#FFC700" stroke="#CB9E00" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M12 9V13" stroke="#092030" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M12 17H12.01" stroke="#092030" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                <span><?php esc_html_e("Widget turned off", "chaty") ?></span>
            </div>

            <div class="trigger-block group-control-wrap trigger-block-wrapper">
                <div class="p-5">
                    <?php $checked = get_option('chaty_trigger_on_time') ?>
                    <?php $time = get_option('chaty_trigger_time'); ?>
                    <?php $time = empty($time) ? "0" : $time; ?>
                    <?php $checked = empty($checked) ? "yes" : $checked; ?>
                    <input type="hidden" name="chaty_trigger_on_time" value="no" >
                    <div class="trigger-option-block flex space-x-3 items-center">
                        <label class="chaty-switch font-primary text-cht-gray-150 text-base" for="trigger_on_time">
                            <input type="checkbox" name="chaty_trigger_on_time" id="trigger_on_time" value="yes" <?php checked($checked, "yes") ?> >
                            <div class="chaty-slider round"></div>
                        </label>
                        <div class="trigger-block-input text-cht-gray-150 text-base font-primary">
                            Display after <input type="number" min="0" id="chaty_trigger_time" name="chaty_trigger_time" value="<?php echo esc_attr($time) ?>"> seconds on the page
                        </div>
                    </div>
                    <?php
                    // Inline comment. ?>
                    <?php $checked = get_option('chaty_trigger_on_exit') ?>
                    <?php $time = get_option('chaty_trigger_on_exit'); ?>
                    <?php $time = empty($time) ? "0" : $time; ?>
                    <?php $checked = empty($checked) ? "no" : $checked; ?>
                    <div class="trigger-option-block flex space-x-3 py-3 items-center">
                        <div>
                            <input type="hidden" name="chaty_trigger_on_exit" value="no" >
                            <label class="chaty-switch font-primary text-cht-gray-150 text-base" for="chaty_trigger_on_exit">
                                <input type="checkbox" name="chaty_trigger_on_exit" id="chaty_trigger_on_exit" value="yes" <?php checked($checked, "yes") ?> >
                                <div class="chaty-slider round"></div>
                            </label>
                        </div>
                        <div class="trigger-block-input font-primary text-base text-cht-gray-150 mt-2">
                            <?php esc_html_e('Display when visitor is about to leave the page', 'chaty') ?>
                        </div>
                    </div>
                    <?php $checked = get_option('chaty_trigger_on_scroll') ?>
                    <?php $time = get_option('chaty_trigger_on_page_scroll'); ?>
                    <?php $time = empty($time) ? "0" : $time; ?>
                    <?php $checked = empty($checked) ? "no" : $checked; ?>
                    <div class="trigger-option-block flex items-center space-x-3">
                        <div>
                            <input type="hidden" name="chaty_trigger_on_scroll" value="no" >
                            <label class="chaty-switch font-primary text-cht-gray-150 text-base" for="chaty_trigger_on_scroll">
                                <input type="checkbox" name="chaty_trigger_on_scroll" id="chaty_trigger_on_scroll" value="yes" <?php checked($checked, "yes") ?> >
                                <div class="chaty-slider round"></div>
                            </label>
                        </div>
                        <div class="trigger-block-input text-base font-primary text-cht-gray-150">
                            Display after <input type="number" min="0" id="chaty_trigger_on_page_scroll" name="chaty_trigger_on_page_scroll" value="<?php echo esc_attr($time) ?>"> % on page
                        </div>
                    </div>
                </div>
                <!-- tooltip -->
                <div class="trigger-tooltip border-t border-x-0 border-b-0 border-solid border-[#eaeff2] py-3.5 px-5">
                    <span data-target="uid-123456" class="chaty-targeted-collapse flex-inline items-center font-primary text-base space-x-1 text-cht-primary cursor-pointer">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16" fill="none">
                            <path d="M6 12L10 8L6 4" stroke="currentColor" stroke-width="1.33" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                        <span><?php esc_html_e("How triggers works?", "chaty") ?></span>
                    </span>
                    <p id="uid-123456" class="text-sm text-cht-gray-150 font-primary px-2 pt-2 hidden"><?php esc_html_e("Your Chaty widget will first appear to the user according to the selected trigger. After the widget appeared for the first time, it'll always be visible on-load - once the user is aware of the widget, the user expects it to always appear", "chaty") ?></p>
                </div> <!-- end trigger tooltip -->
            </div>
        </div>



        <div class="form-horizontal__item">
            <label class="form-horizontal__item-label font-primary text-cht-gray-150 text-base block mb-3">
                <?php esc_html_e("Show on pages", "chaty") ?>
                <span class="header-tooltip">
                    <span class="header-tooltip-text text-center"><?php esc_html_e("Use this feature to show the widget for specific products, posts or on certain posts or pages by excluding or including them in the rules", "chaty") ?></span>
                    <span class="ml-1">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16" fill="none">
                            <path d="M8.00004 14.6654C11.6819 14.6654 14.6667 11.6806 14.6667 7.9987C14.6667 4.3168 11.6819 1.33203 8.00004 1.33203C4.31814 1.33203 1.33337 4.3168 1.33337 7.9987C1.33337 11.6806 4.31814 14.6654 8.00004 14.6654Z" stroke="#72777c" stroke-width="1.33" stroke-linecap="round" stroke-linejoin="round"></path>
                            <path d="M8 10.6667V8" stroke="#72777c" stroke-width="1.33" stroke-linecap="round" stroke-linejoin="round"></path>
                            <path d="M8 5.33203H8.00667" stroke="#72777c" stroke-width="1.33" stroke-linecap="round" stroke-linejoin="round"></path>
                        </svg>
                    </span>
                </span>
            </label>
            <div class="chaty-option-box">
                <div class="chaty-page-options relative hidden" id="chaty-pro-options">
                    <div class="chaty-page-option relative group-control-wrap p-5 mb-3">
                        <div class="url-content">
                            <div class="grid grid-cols-2 gap-4">
                                <div class="url-select">
                                    <select class="cht-free-required url-select" id="url_shown_on___count___option">
                                        <option disabled value="show_on"><?php esc_html_e("Show on", "chaty") ?></option>
                                        <option disabled value="not_show_on"><?php esc_html_e("Don't show on", "chaty") ?></option>
                                    </select>
                                </div>

                                <div class="url-option">
                                    <select class="url-options" id="url_rules___count___option">
                                        <option selected="selected" value=""><?php esc_html_e("Select Rule", "chaty") ?></option>
                                        <?php foreach ($url_options as $key => $value) {
                                            echo '<option disabled value="'.esc_attr($key).'">'.esc_attr($value).'</option>';
                                        } ?>
                                    </select>
                                </div>
                            </div>

                            <div class="flex items-center mt-4">
                                <div class="url-box">
                                    <span class='chaty-url'><?php echo esc_url(site_url("/")); ?></span>
                                </div>

                                <div class="url-values flex-auto">
                                    <input type="text" class="cht-free-required" value="" id="url_rules___count___value" />
                                </div>
                            </div>

                            <div class="url-buttons">
                                <a class="remove-chaty absolute" href="javascript:;">
                                    <svg width="14" height="13" viewBox="0 0 14 13" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <rect width="15.6301" height="2.24494" rx="1.12247" transform="translate(2.26764 0.0615997) rotate(45)" fill="white"></rect>
                                        <rect width="15.6301" height="2.24494" rx="1.12247" transform="translate(13.3198 1.649) rotate(135)" fill="white"></rect>
                                    </svg>
                                </a>
                            </div>

                        </div>
                    </div>

                    <div class="chaty-pro-feature">
                        <a target="_blank" href="<?php echo esc_url($this->getUpgradeMenuItemUrl());?>">
                            <?php esc_html_e('Upgrade to Pro', 'chaty');?>
                        </a>
                    </div>
                </div>
                <div>
                    <a href="javascript:;" class="create-rule border border-solid border-cht-gray-150/60 text-cht-gray-150 text-base px-3 py-1 rounded-lg inline-block hover:text-cht-primary hover:border-cht-primary mr-4" id="create-rule"><?php esc_html_e("Add Rule", "chaty") ?></a>
                    <a href="javascript:;" class="remove-rules hidden rounded-lg bg-transparent  px-3 py-1 border border-red-500 text-red-500 hover:bg-red-500/10  focus:bg-red-500/10 hover:text-red-500 btn-primary"><?php esc_html_e("Remove Rules", "chaty") ?></a>
                </div>
            </div>
        </div>

        <div class="form-horizontal__item  flex-center" id="scroll-to-item">
            <label class="form-horizontal__item-label font-primary text-cht-gray-150 text-base block mb-3">
                <?php esc_html_e('Date scheduling', 'chaty');?>
                <span class="header-tooltip">
                    <span class="header-tooltip-text text-center"><?php esc_html_e('Schedule the specific time and date when your Chaty widget appears.', 'chaty');?></span>
                    <span class="ml-1">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16" fill="none">
                            <path d="M8.00004 14.6654C11.6819 14.6654 14.6667 11.6806 14.6667 7.9987C14.6667 4.3168 11.6819 1.33203 8.00004 1.33203C4.31814 1.33203 1.33337 4.3168 1.33337 7.9987C1.33337 11.6806 4.31814 14.6654 8.00004 14.6654Z" stroke="#72777c" stroke-width="1.33" stroke-linecap="round" stroke-linejoin="round"></path>
                            <path d="M8 10.6667V8" stroke="#72777c" stroke-width="1.33" stroke-linecap="round" stroke-linejoin="round"></path>
                            <path d="M8 5.33203H8.00667" stroke="#72777c" stroke-width="1.33" stroke-linecap="round" stroke-linejoin="round"></path>
                        </svg>
                    </span>
                </span>
            </label>
            <?php
            $timezone  = "";
            $startDate = "";
            $startTime = "";
            $endDate   = "";
            $endTime   = "";
            $status    = "no";
            ?>
            <div class="chaty-option-box">
                <div id="date-schedule" class="<?php echo ($status == "yes") ? "active" : "" ?>">
                    <div class="date-schedule-box group-control-wrap p-5">
                        <div class="date-schedule relative">
                            <!-- input fields start -->
                            <div class="date-schedule-items">
                                <div class="select-box">
                                    <label class="font-primary text-cht-gray-150 text-base block mb-2"><?php esc_html_e('Timezone', 'chaty');?></label>
                                    <select class="select2-box font-primary text-cht-gray-150 text-base" name="cht_date_rules[timezone]" id="cht_date_rules_time_zone">
                                        <?php echo esc_attr(chaty_timezone_choice($timezone, true)); ?>
                                    </select>
                                </div>
                                
                                <div class="date-time-box grid grid-cols-2 gap-4">
                                    <div class="date-select-option">
                                        <label for="date_start_date" class="font-primary text-cht-gray-150 text-base block mb-2">
                                            <?php esc_html_e('Start Date', 'chaty');?>
                                            <span class="header-tooltip">
                                                <span class="header-tooltip-text text-center"><?php esc_html_e('Schedule a date from which the Chaty widget will be displayed (the starting date is included)', 'chaty');?></span>
                                                <span>
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16" fill="none">
                                                        <path d="M8.00004 14.6654C11.6819 14.6654 14.6667 11.6806 14.6667 7.9987C14.6667 4.3168 11.6819 1.33203 8.00004 1.33203C4.31814 1.33203 1.33337 4.3168 1.33337 7.9987C1.33337 11.6806 4.31814 14.6654 8.00004 14.6654Z" stroke="#72777c" stroke-width="1.33" stroke-linecap="round" stroke-linejoin="round"></path>
                                                        <path d="M8 10.6667V8" stroke="#72777c" stroke-width="1.33" stroke-linecap="round" stroke-linejoin="round"></path>
                                                        <path d="M8 5.33203H8.00667" stroke="#72777c" stroke-width="1.33" stroke-linecap="round" stroke-linejoin="round"></path>
                                                    </svg>
                                                </span>
                                            </span>
                                        </label>
                                        <input type="text" class="ui-timepicker-input" name="cht_date_rules[start_date]" id="date_start_date" value="<?php echo esc_attr($startDate) ?>" >
                                    </div>
                                    <div class="time-select-option">
                                        <label class="font-primary text-cht-gray-150 text-base block mb-2" for="date_start_time"><?php esc_html_e('Start Time', 'chaty');?></label>
                                        <input type="text" name="cht_date_rules[start_time]" id="date_start_time" value="<?php echo esc_attr($startTime) ?>">
                                    </div>
                                </div>

                                <div class="date-time-box grid grid-cols-2 gap-4">
                                    <div class="date-select-option">
                                        <label class="font-primary text-cht-gray-150 text-base flex my-2" for="date_start_date">
                                            <?php esc_html_e('End Date', 'chaty');?>
                                            <span class="header-tooltip">
                                                <span class="header-tooltip-text text-center"><?php esc_html_e('Schedule a date from which the Chaty widget will stop being displayed (the end date is included)', 'chaty');?></span>
                                                <span>
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16" fill="none">
                                                        <path d="M8.00004 14.6654C11.6819 14.6654 14.6667 11.6806 14.6667 7.9987C14.6667 4.3168 11.6819 1.33203 8.00004 1.33203C4.31814 1.33203 1.33337 4.3168 1.33337 7.9987C1.33337 11.6806 4.31814 14.6654 8.00004 14.6654Z" stroke="#72777c" stroke-width="1.33" stroke-linecap="round" stroke-linejoin="round"></path>
                                                        <path d="M8 10.6667V8" stroke="#72777c" stroke-width="1.33" stroke-linecap="round" stroke-linejoin="round"></path>
                                                        <path d="M8 5.33203H8.00667" stroke="#72777c" stroke-width="1.33" stroke-linecap="round" stroke-linejoin="round"></path>
                                                    </svg>
                                                </span>
                                            </span>
                                        </label>
                                        <input type="text" name="cht_date_rules[end_date]" id="date_end_date" value="<?php echo esc_attr($endDate) ?>">
                                    </div>

                                    <div class="time-select-option">
                                        <label for="date_end_time" class="font-primary text-cht-gray-150 text-base block my-2"><?php esc_html_e('End Time', 'chaty');?></label>
                                        <input type="text" name="cht_date_rules[end_time]" id="date_end_time" value="<?php echo esc_attr($endTime) ?>">
                                    </div>
                                </div>
                            <!-- input fields end -->
                            </div>
                            <div class="chaty-pro-feature">
                                <a target="_blank" href="<?php echo esc_url($this->getUpgradeMenuItemUrl()) ?>">
                                    <?php esc_html_e('Upgrade to Pro', 'chaty'); ?>
                                </a>
                            </div>
                        </div>
                        <a href="javascript:;" class="remove-rules rounded-lg bg-transparent border-red-500 text-red-500 hover:bg-red-500/10  focus:bg-red-500/10 hover:text-red-500 px-3 py-1 border btn-primary inline-block mt-5" id="remove-date-rule"><?php esc_html_e('Remove Rules', 'chaty');?></a>
                    </div>
                    <div class="date-schedule-button">
                        <a href="javascript:;" class="create-rule border border-solid border-cht-gray-150/60 text-cht-gray-150 text-base px-3 py-1 rounded-lg inline-block hover:text-cht-primary hover:border-cht-primary" id="create-date-rule"><?php esc_html_e('Add Rule', 'chaty');?></a>
                    </div>
                </div>
            </div>
            <input type="hidden" name="cht_date_rules[status]" id="cht_date_rules" value="<?php echo esc_attr($status) ?>" />
        </div>
        <div class="form-horizontal__item flex-center">
            <label class="form-horizontal__item-label font-primary text-cht-gray-150 text-base block mb-3">
                <?php esc_html_e('Days and hours', 'chaty');?>
                <span class="header-tooltip">
                    <span class="header-tooltip-text text-center"><?php esc_html_e("Display the widget on specific days and hours based on your opening days and hours", "chaty") ?></span>
                    <span class="ml-1">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16" fill="none">
                            <path d="M8.00004 14.6654C11.6819 14.6654 14.6667 11.6806 14.6667 7.9987C14.6667 4.3168 11.6819 1.33203 8.00004 1.33203C4.31814 1.33203 1.33337 4.3168 1.33337 7.9987C1.33337 11.6806 4.31814 14.6654 8.00004 14.6654Z" stroke="#72777c" stroke-width="1.33" stroke-linecap="round" stroke-linejoin="round"></path>
                            <path d="M8 10.6667V8" stroke="#72777c" stroke-width="1.33" stroke-linecap="round" stroke-linejoin="round"></path>
                            <path d="M8 5.33203H8.00667" stroke="#72777c" stroke-width="1.33" stroke-linecap="round" stroke-linejoin="round"></path>
                        </svg>
                    </span>
                </span>
            </label>
            <div class="chaty-option-box">
                <div class="chaty-page-options relative hidden" id="chaty-page-options">
                    <div class="chaty-data-and-time-rules group-control-wrap mb-3 p-5 relative">
                        <div class="chaty-date-time-option first last" data-index="__count__">
                            <div class="date-time-content pointer-events-none grid sm:grid-cols-2 gap-4">
                                <div class="date-time-data space-y-3">
                                
                                    <div class="day-select col-span-2">
                                        <label class="block font-primary text-base text-cht-gray-150 mb-1.5"><?php esc_html_e("Select Day", "chaty") ?></label>
                                        <select class="cht-free-required w-full text-cht-gray-150" id="url_shown_on___count___option">
                                            <?php foreach ($days as $key => $value) { ?>
                                                <option value="<?php echo esc_attr($key) ?>"><?php echo esc_attr($value) ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>

                                    <div class="select-time-form">
                                        <label class="block font-primary text-base text-cht-gray-150 mb-1.5"><?php esc_html_e("From", "chaty") ?></label>
                                        <input type="text" class="cht-free-required time-picker w-full ui-timepicker-input" value=""  id="start_time___count__" />
                                    </div>

                                    <div class="select-time-to">
                                        <label class="block font-primary text-base text-cht-gray-150 mb-1.5"><?php esc_html_e("To", "chaty") ?></label>
                                        <input type="text" class="cht-free-required time-picker w-full ui-timepicker-input" value="" id="end_time___count__" />
                                    </div>

                                    <div class="day-label col-span-2">
                                        <label class="block font-primary text-base text-cht-gray-150 mb-1.5"><?php esc_html_e("GMT", "chaty") ?></label>
                                        <select class="cht-free-required gmt-data w-full text-cht-gray-150" id="url_shown_on___count___option">
                                            <?php echo esc_attr(chaty_timezone_choice("", false)) ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="chaty-pro-feature">
                                <a target="_blank" href="<?php echo esc_url($this->getUpgradeMenuItemUrl()) ?>">
                                    <?php esc_html_e('Upgrade to Pro', 'chaty'); ?>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <div>
                    <a href="javascript:;" class="create-rule border border-solid border-cht-gray-150/60 text-cht-gray-150 text-base px-3 py-1 rounded-lg inline-block hover:text-cht-primary hover:border-cht-primary mr-4" id="create-data-and-time-rule"><?php esc_html_e("Add Rule", "chaty") ?></a>
                    <a href="javascript:;" class="remove-rules hidden rounded-lg bg-transparent  px-3 py-1 border border-red-500 text-red-500 hover:bg-red-500/10  focus:bg-red-500/10 hover:text-red-500 btn-primary"><?php esc_html_e("Remove Rules", "chaty") ?></a>
                </div>
            </div>
        </div>

        <div class="form-horizontal__item" id="custom-rules">
            <label class="form-horizontal__item-label text-cht-gray-150 font-primary text-base mb-2 inline-block">
                <?php esc_html_e("Traffic source", "chaty") ?>
                <span class="header-tooltip">
                    <span class="header-tooltip-text text-center"><?php esc_html_e("Show the widget only to visitors who come from specific traffic sources including direct traffic, social networks, search engines, Google Ads, or any other traffic source.", "chaty") ?></span>
                    <span class="ml-1">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16" fill="none">
                            <path d="M8.00004 14.6654C11.6819 14.6654 14.6667 11.6806 14.6667 7.9987C14.6667 4.3168 11.6819 1.33203 8.00004 1.33203C4.31814 1.33203 1.33337 4.3168 1.33337 7.9987C1.33337 11.6806 4.31814 14.6654 8.00004 14.6654Z" stroke="#72777c" stroke-width="1.33" stroke-linecap="round" stroke-linejoin="round"></path>
                            <path d="M8 10.6667V8" stroke="#72777c" stroke-width="1.33" stroke-linecap="round" stroke-linejoin="round"></path>
                            <path d="M8 5.33203H8.00667" stroke="#72777c" stroke-width="1.33" stroke-linecap="round" stroke-linejoin="round"></path>
                        </svg>
                    </span>
                </span>
            </label>
            <?php
            $checked = get_option('chaty_traffic_source');
            $checked = empty($checked) ? "no" : $checked;
            ?>
            <div class="chaty-option-box traffic-options-box <?php echo ($checked == "yes") ? "active" : "" ?>">
                <div class="traffic-default">
                    <a href="javascript:;" class="create-rule border border-solid border-cht-gray-150/60 text-cht-gray-150 text-base px-3 py-1 rounded-lg inline-block hover:text-cht-primary hover:border-cht-primary" id="update-chaty-traffic-source-rule"><?php esc_html_e("Add Rule", "chaty") ?></a>
                    <input type="hidden" name="chaty_traffic_source" id="chaty_traffic_source" value="<?php echo esc_attr($checked) ?>">
                </div>
                <div class="traffic-active">
                    <div class="trigger-block no-margin">
                        <div class="chaty-pro-block space-y-3">
                            <?php
                            $checked = get_option('chaty_traffic_source_direct_visit');
                            $checked = empty($checked) ? "no" : $checked;
                            ?>
                            <input type="hidden" name="chaty_traffic_source_direct_visit" value="no">
                            <div class="mb-2">
                                <label class="chaty-switch text-cht-gray-150 text-base font-primary" for="chaty_traffic_source_direct_visit">
                                    <input type="checkbox" disabled="disabled" name="chaty_traffic_source_direct_visit" id="chaty_traffic_source_direct_visit" value="yes"  >
                                    <div class="chaty-slider round"></div>
                                    <?php esc_html_e("Direct visit", "chaty") ?>
                                    <span class="header-tooltip">
                                        <span class="header-tooltip-text text-center"><?php esc_html_e("Show the Chaty to visitors who arrived to your website from direct traffic", "chaty") ?></span>
                                        <span>
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16" fill="none">
                                                <path d="M8.00004 14.6654C11.6819 14.6654 14.6667 11.6806 14.6667 7.9987C14.6667 4.3168 11.6819 1.33203 8.00004 1.33203C4.31814 1.33203 1.33337 4.3168 1.33337 7.9987C1.33337 11.6806 4.31814 14.6654 8.00004 14.6654Z" stroke="#72777c" stroke-width="1.33" stroke-linecap="round" stroke-linejoin="round"></path>
                                                <path d="M8 10.6667V8" stroke="#72777c" stroke-width="1.33" stroke-linecap="round" stroke-linejoin="round"></path>
                                                <path d="M8 5.33203H8.00667" stroke="#72777c" stroke-width="1.33" stroke-linecap="round" stroke-linejoin="round"></path>
                                            </svg>
                                        </span>
                                    </span>
                                </label>
                            </div>
                            <?php
                            $checked = get_option('chaty_traffic_source_social_network');
                            $checked = empty($checked) ? "no" : $checked;
                            ?>
                            <div class="my-2">
                                <input type="hidden" name="chaty_traffic_source_social_network" value="no">
                                <label class="chaty-switch font-primary text-cht-gray-150 text-base" for="chaty_traffic_source_social_network">
                                    <input type="checkbox" disabled="disabled" name="chaty_traffic_source_social_network" id="chaty_traffic_source_social_network" value="yes" >
                                    <div class="chaty-slider round"></div>
                                    <?php esc_html_e("Social networks", "chaty") ?>
                                    <span class="header-tooltip">
                                        <span class="header-tooltip-text text-center"><?php esc_html_e("Show the Chaty to visitors who arrived to your website from social networks including: Facebook, Twitter, Pinterest, Instagram, Google+, LinkedIn, Delicious, Tumblr, Dribbble, StumbleUpon, Flickr, Plaxo, Digg and more", "chaty") ?></span>
                                        <span>
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16" fill="none">
                                                <path d="M8.00004 14.6654C11.6819 14.6654 14.6667 11.6806 14.6667 7.9987C14.6667 4.3168 11.6819 1.33203 8.00004 1.33203C4.31814 1.33203 1.33337 4.3168 1.33337 7.9987C1.33337 11.6806 4.31814 14.6654 8.00004 14.6654Z" stroke="#72777c" stroke-width="1.33" stroke-linecap="round" stroke-linejoin="round"></path>
                                                <path d="M8 10.6667V8" stroke="#72777c" stroke-width="1.33" stroke-linecap="round" stroke-linejoin="round"></path>
                                                <path d="M8 5.33203H8.00667" stroke="#72777c" stroke-width="1.33" stroke-linecap="round" stroke-linejoin="round"></path>
                                            </svg>
                                        </span>
                                    </span>
                                </label>
                            </div>
                            <?php
                            $checked = get_option('chaty_traffic_source_search_engine');
                            $checked = empty($checked) ? "no" : $checked;
                            ?>
                            <div class="my-2">
                                <input type="hidden" name="chaty_traffic_source_search_engine" value="no">
                                <label class="chaty-switch font-primary text-cht-gray-150 text-base" for="chaty_traffic_source_search_engine">
                                    <input type="checkbox" disabled="disabled" name="chaty_traffic_source_search_engine" id="chaty_traffic_source_search_engine" value="yes" >
                                    <div class="chaty-slider round"></div>
                                    <?php esc_html_e("Search engines", "chaty") ?>
                                    <span class="header-tooltip">
                                        <span class="header-tooltip-text text-center"><?php esc_html_e("Show the Chaty to visitors who arrived from search engines including: Google, Bing, Yahoo!, Yandex, AOL, Ask, WOW,  WebCrawler, Baidu and more", "chaty") ?></span>
                                        <span>
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16" fill="none">
                                                <path d="M8.00004 14.6654C11.6819 14.6654 14.6667 11.6806 14.6667 7.9987C14.6667 4.3168 11.6819 1.33203 8.00004 1.33203C4.31814 1.33203 1.33337 4.3168 1.33337 7.9987C1.33337 11.6806 4.31814 14.6654 8.00004 14.6654Z" stroke="#72777c" stroke-width="1.33" stroke-linecap="round" stroke-linejoin="round"></path>
                                                <path d="M8 10.6667V8" stroke="#72777c" stroke-width="1.33" stroke-linecap="round" stroke-linejoin="round"></path>
                                                <path d="M8 5.33203H8.00667" stroke="#72777c" stroke-width="1.33" stroke-linecap="round" stroke-linejoin="round"></path>
                                            </svg>
                                        </span>
                                    </span>
                                </label>
                            </div>
                            <?php
                            $checked = get_option('chaty_traffic_source_google_ads');
                            $checked = empty($checked) ? "no" : $checked;
                            ?>
                            <div class="mt-2">
                                <input type="hidden" name="chaty_traffic_source_google_ads" value="no">
                                <label class="chaty-switch text-cht-gray-150 font-primary text-base" for="chaty_traffic_source_google_ads">
                                    <input type="checkbox" disabled="disabled" name="chaty_traffic_source_google_ads" id="chaty_traffic_source_google_ads" value="yes" >
                                    <div class="chaty-slider round"></div>
                                    <?php esc_html_e("Google Ads", "chaty") ?>
                                    <span class="header-tooltip">
                                        <span class="header-tooltip-text text-center"><?php esc_html_e("Show the Chaty to visitors who arrived from search engines including: Google, Bing, Yahoo!, Yandex, AOL, Ask, WOW,  WebCrawler, Baidu and more", "chaty") ?></span>
                                        <span>
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16" fill="none">
                                                <path d="M8.00004 14.6654C11.6819 14.6654 14.6667 11.6806 14.6667 7.9987C14.6667 4.3168 11.6819 1.33203 8.00004 1.33203C4.31814 1.33203 1.33337 4.3168 1.33337 7.9987C1.33337 11.6806 4.31814 14.6654 8.00004 14.6654Z" stroke="#72777c" stroke-width="1.33" stroke-linecap="round" stroke-linejoin="round"></path>
                                                <path d="M8 10.6667V8" stroke="#72777c" stroke-width="1.33" stroke-linecap="round" stroke-linejoin="round"></path>
                                                <path d="M8 5.33203H8.00667" stroke="#72777c" stroke-width="1.33" stroke-linecap="round" stroke-linejoin="round"></path>
                                            </svg>
                                        </span>
                                    </span>
                                </label>
                            </div>
                            <?php
                            $customRules = get_option("chaty_custom_traffic_rules")
                            ?>
                            <div class="traffic-custom-rules">
                                <div class="custom-rule-title text-cht-gray-150 font-primary text-base"><?php esc_html_e("Specific URL", "chaty") ?></div>
                                <div class="traffic-custom-rules-box">
                                    <?php if (!empty($customRules) && is_array($customRules) && count($customRules) > 0) {
                                        foreach ($customRules as $key => $rule) { ?>
                                            <div class="custom-traffic-rule">
                                                <div class="traffic-option">
                                                    <select name="chaty_custom_traffic_rules[<?php echo esc_attr($key) ?>][url_option]">
                                                        <option value="contain" <?php selected($rule['url_option'], "contain") ?>>Contains</option>
                                                        <option value="not_contain" <?php selected($rule['url_option'], "not_contain") ?>>Not contains</option>
                                                    </select>
                                                </div>
                                                <div class="traffic-url">
                                                    <input type="text" name="chaty_custom_traffic_rules[<?php echo esc_attr($key) ?>][url_value]" value="<?php echo esc_attr($rule['url_value']) ?>" placeholder="https://www.example.com" />
                                                </div>
                                                <div class="traffic-action">
                                                    <a class="remove-traffic-option" href="javascript:;">
                                                        <svg width="14" height="13" viewBox="0 0 14 13" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                            <rect width="15.6301" height="2.24494" rx="1.12247" transform="translate(2.26764 0.0615997) rotate(45)" fill="white"></rect>
                                                            <rect width="15.6301" height="2.24494" rx="1.12247" transform="translate(13.3198 1.649) rotate(135)" fill="white"></rect>
                                                        </svg>
                                                    </a>
                                                </div>
                                            </div>
                                        <?php }//end foreach
                                    } else { ?>
                                        <div class="custom-traffic-rule">
                                            <div class="traffic-option">
                                                <select name="chaty_custom_traffic_rules[0][url_option]">
                                                    <option value="contain"><?php esc_html_e("Contains", "chaty") ?></option>
                                                    <option value="not_contain"><?php esc_html_e("Not contains", "chaty") ?></option>
                                                </select>
                                            </div>
                                            <div class="traffic-url">
                                                <input type="text" name="chaty_custom_traffic_rules[0][url_value]" />
                                            </div>
                                            <div class="traffic-action">
                                                <a class="remove-traffic-option" href="javascript:;">
                                                    <svg width="14" height="13" viewBox="0 0 14 13" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                        <rect width="15.6301" height="2.24494" rx="1.12247" transform="translate(2.26764 0.0615997) rotate(45)" fill="white"></rect>
                                                        <rect width="15.6301" height="2.24494" rx="1.12247" transform="translate(13.3198 1.649) rotate(135)" fill="white"></rect>
                                                    </svg>
                                                </a>
                                            </div>
                                        </div>
                                    <?php }//end if
                                    ?>
                                </div>
                            </div>
                            <div class="chaty-pro-feature">
                                <a target="_blank" href="<?php echo esc_url($this->getUpgradeMenuItemUrl());?>">
                                    <?php esc_html_e('Upgrade to Pro', 'chaty');?>
                                </a>
                            </div>
                        </div>
                        <div class="clear clearfix"></div>
                        <div class="traffic-rule-actions">
                            <a href="javascript:;" class="create-rule border border-solid border-cht-gray-150/60 text-cht-gray-150 text-base px-3 py-1 rounded-lg inline-block hover:text-cht-primary hover:border-cht-primary mr-4" id="add-traffic-rule"><?php esc_html_e("Add Rule", "chaty") ?></a>
                            <a href="javascript:;" class="remove-rules rounded-lg bg-transparent  px-3 py-1 border border-red-500 text-red-500 hover:bg-red-500/10  focus:bg-red-500/10 hover:text-red-500 btn-primary inline-block" id="remove-traffic-rules"><?php esc_html_e("Remove Rules", "chaty") ?></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="form-horizontal__item">
            <label class="form-horizontal__item-label mb-3 inline-block text-cht-gray-150 font-primary text-base">
                <?php esc_html_e('Country targeting', 'chaty');?>
                <span class="header-tooltip">
                    <span class="header-tooltip-text text-center"><?php esc_html_e("Target your widget to specific countries. You can create different widgets for different countries", "chaty") ?></span>
                    <span class="ml-1">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16" fill="none">
                            <path d="M8.00004 14.6654C11.6819 14.6654 14.6667 11.6806 14.6667 7.9987C14.6667 4.3168 11.6819 1.33203 8.00004 1.33203C4.31814 1.33203 1.33337 4.3168 1.33337 7.9987C1.33337 11.6806 4.31814 14.6654 8.00004 14.6654Z" stroke="#72777c" stroke-width="1.33" stroke-linecap="round" stroke-linejoin="round"></path>
                            <path d="M8 10.6667V8" stroke="#72777c" stroke-width="1.33" stroke-linecap="round" stroke-linejoin="round"></path>
                            <path d="M8 5.33203H8.00667" stroke="#72777c" stroke-width="1.33" stroke-linecap="round" stroke-linejoin="round"></path>
                        </svg>
                    </span>
                </span>
            </label>
            <div class="chaty-option-box">
                <div class="chaty-page-options hidden relative">
                    <div class="country-list-box mb-5">
                        <select class="country-list chaty-select">
                            <option value=""><?php esc_html_e('All Countries', 'chaty');?></option>
                        </select>
                    </div>
                    <div class="chaty-pro-feature">
                        <a target="_blank" href="<?php echo esc_url($this->getUpgradeMenuItemUrl());?>">
                            <?php esc_html_e('Upgrade to Pro', 'chaty');?>
                        </a>
                    </div>
                </div>
                <div>
                    <a href="javascript:;" class="create-rule border border-solid border-cht-gray-150/60 text-cht-gray-150 text-base px-3 py-1 rounded-lg inline-block hover:text-cht-primary hover:border-cht-primary mr-4" id="add-traffic-rule"><?php esc_html_e('Add Rule', 'chaty');?></a>
                    <a href="javascript:;" class="remove-rules rounded-lg bg-transparent  px-3 py-1 border border-red-500 text-red-500 hover:bg-red-500/10  focus:bg-red-500/10 hover:text-red-500 btn-primary hidden"><?php esc_html_e('Remove Rules', 'chaty');?></a>
                </div>
            </div>
        </div>

        <div class="form-horizontal__item">
            <label class="form-horizontal__item-label font-primary text-base text-cht-gray-150 block mb-3"><?php esc_html_e('Custom CSS', 'chaty');?>
            <span class="header-tooltip">
                <span class="header-tooltip-text text-center"><?php esc_html_e("Use this option if you wish to modify your widget additionally. This step is optional.", 'chaty');?></span>
                <span class="ml-1">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16" fill="none">
                        <path d="M8.00004 14.6654C11.6819 14.6654 14.6667 11.6806 14.6667 7.9987C14.6667 4.3168 11.6819 1.33203 8.00004 1.33203C4.31814 1.33203 1.33337 4.3168 1.33337 7.9987C1.33337 11.6806 4.31814 14.6654 8.00004 14.6654Z" stroke="#72777c" stroke-width="1.33" stroke-linecap="round" stroke-linejoin="round"></path>
                        <path d="M8 10.6667V8" stroke="#72777c" stroke-width="1.33" stroke-linecap="round" stroke-linejoin="round"></path>
                        <path d="M8 5.33203H8.00667" stroke="#72777c" stroke-width="1.33" stroke-linecap="round" stroke-linejoin="round"></path>
                    </svg>
                </span>
            </span>
            </label>
            <div class="chaty-option-box">
                <div class="chaty-page-options relative">
                    <div class="country-list-box pointer-events-none">
                        <textarea class="custom-css"></textarea>
                    </div>
                    <div class="chaty-pro-feature">
                        <a target="_blank" href="<?php echo esc_url($this->getUpgradeMenuItemUrl());?>">
                            <?php esc_html_e('Upgrade to Pro', 'chaty');?>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php
    $chaty_updated_on = get_option("chaty_updated_on");
    if($chaty_updated_on === false) {
        add_option("chaty_updated_on" , gmdate("Y-m-d"));
    }
    ?>
    <input type="hidden" name="chaty_updated_on" value="<?php echo esc_attr(time()); ?>">
</section>
