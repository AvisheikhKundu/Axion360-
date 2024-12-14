<?php
/**
 * Contact form leads
 *
 * @author  : Premio <contact@premio.io>
 * @license : GPL2
 * */

if (defined('ABSPATH') === false) {
    exit;
}

$value = get_option('cht_social_'.$social['slug']);
// get setting for media if already saved
$imageUrl = plugin_dir_url("")."chaty/admin/assets/images/chaty-default.png";
if (empty($value)) {
    // Initialize default values if not found
    $value = [
        'value'      => '',
        'is_mobile'  => 'checked',
        'is_desktop' => 'checked',
        'image_id'   => '',
        'title'      => $social['title'],
        'bg_color'   => "",
    ];
}

if (!isset($value['bg_color']) || empty($value['bg_color'])) {
    $value['bg_color'] = $social['color'];
    // Initialize background color value if not exists. 2.1.0 change
}

if (!isset($value['image_id'])) {
    $value['image_id'] = '';
    // Initialize custom image id if not exists. 2.1.0 change
}

if (!isset($value['title'])) {
    $value['title'] = $social['title'];
    // Initialize title if not exists. 2.1.0 change
}

if (!isset($value['fa_icon'])) {
    $value['fa_icon'] = "";
    // Initialize title if not exists. 2.1.0 change
}

if (!isset($value['value'])) {
    $value['value'] = "";
    // Initialize title if not exists. 2.1.0 change
}

$imageId = $value['image_id'];
$status  = 0;
if (!empty($imageId)) {
    $imageUrl = wp_get_attachment_image_src($imageId, "full")[0];
    // get custom image URL if exists
    $status = 1;
}

if ($imageUrl == "") {
    $imageUrl = plugin_dir_url("")."chaty/admin/assets/images/chaty-default.png";
    // Initialize with default image if custom image is not exists
    $status  = 0;
    $imageId = "";
}

if($social['slug'] == "Twitter" && ($value['bg_color'] == "#1ab2e8" || $value['bg_color'] == "rgb(26, 178, 232)") ) {
    $value['bg_color'] = "#000000";
}

$color = "";
if (!empty($value['bg_color'])) {
    $value['bg_color'] = $this->validate_color($value['bg_color'], $social['color']);
    $color = "background-color: ".esc_attr($value['bg_color']);
}

if ($social['slug'] == "Whatsapp") {
//    $val            = $value['value'];
//    $val            = str_replace("+", "", $val);
//    $value['value'] = $val;
} else if ($social['slug'] == "Facebook_Messenger") {
    $val = $value['value'];
    $val = str_replace("facebook.com", "m.me", $val);
    // Replace facebook.com with m.me version 2.0.1 change.
    $val = str_replace("www.", "", $val);
    // Replace www. with blank version 2.0.1 change.
    $value['value'] = $val;

    $val       = trim($val, "/");
    $valArray  = explode("/", $val);
    $total     = (count($valArray) - 1);
    $lastValue = $valArray[$total];
    $lastValue = explode("-", $lastValue);
    $totalText = (count($lastValue) - 1);
    $totalText = $lastValue[$totalText];

    if (is_numeric($totalText)) {
        $valArray[$total] = $totalText;
        $value['value']   = implode("/", $valArray);
    }
}//end if

$value['value'] = esc_attr(wp_unslash($value['value']));
$value['title'] = esc_attr(wp_unslash($value['title']));

$svgIcon = $social['svg'];

$helpTitle = "";
$helpText  = "";
$helpLink  = "";

if ((isset($social['help']) && !empty($social['help'])) || isset($social['help_link'])) {
    $helpTitle = isset($social['help_title']) ? $social['help_title'] : "Doesn't work?";
    $helpText  = isset($social['help']) ? $social['help'] : "";
    if (isset($social['help_link']) && !empty($social['help_link'])) {
        $helpLink = $social['help_link'];
    }
}



$channelType = "";
$placeholder = $social['example'];
if ($social['slug'] == "Link" || $social['slug'] == "Custom_Link" || $social['slug'] == "Custom_Link_3" || $social['slug'] == "Custom_Link_4" || $social['slug'] == "Custom_Link_5") {
    if (isset($value['channel_type'])) {
        $channelType = esc_attr(wp_unslash($value['channel_type']));
    }

    if (!empty($channelType)) {
        foreach ($this->socials as $icon) {
            if ($icon['slug'] == $channelType) {
                $svgIcon = $icon['svg'];

                $placeholder = $icon['example'];

                if ((isset($icon['help']) && !empty($icon['help'])) || isset($icon['help_link'])) {
                    $helpTitle = isset($icon['help_title']) ? $icon['help_title'] : "Doesn't work?";
                    $helpText  = isset($icon['help']) ? $icon['help'] : "";
                    if (isset($icon['help_link']) && !empty($icon['help_link'])) {
                        $helpLink = $icon['help_link'];
                    }
                }
            }
        }
    }
}//end if

if (empty($channelType)) {
    $channelType = $social['slug'];
}

// Check if the 'value' field is not empty
if (!empty($value['value'])) {

    // Extract the social slug for readability
    $socialSlug = $social['slug'];

    // Check if the social slug is one of the specified values
    if ($socialSlug == "Whatsapp" || $socialSlug == "Phone" || $socialSlug == "SMS") {
        // Clean the 'value' field for numbers
        $value['value'] = $this->cleanStringForNumbers($value['value']);
    }

    // Check if the social slug is "Whatsapp"
    if ($socialSlug == "Whatsapp") {
        // Remove leading '+' and then prepend '+'
        $value['value'] = "+" . ltrim($value['value'], "+");
    }

    // Social slugs that require numeric formatting
    $numericSocials = ["Whatsapp", "Viber"];

    // Social slugs that require username formatting
    $usernameSocials = ["Telegram", "Snapchat", "TikTok"];

    // Check if the current social slug requires numeric formatting
    if (in_array($socialSlug, $numericSocials)) {
        // Remove unwanted characters and prepend '+'
        $val = str_replace(["+", "-", " "], "", $value['value']);

        if(is_numeric($val)) {
            $value['value'] = "+" . $val;
        }
    }
    // Check if the current social slug requires username formatting
    elseif (in_array($socialSlug, $usernameSocials)) {
        // Remove '@' if present and prepend '@'
        $value['value'] = "@" . trim($value['value'], "@");
    }
    // No special formatting required for other social slugs
}
$isAgent = 0;


?>
<!-- Social media setting box: start -->
<li data-id="<?php echo esc_attr($social['slug']) ?>" class="chaty-channel <?php echo ($isAgent == 1) ? "has-agent-view" : "" ?>" data-channel="<?php echo esc_attr($channelType) ?>" id="chaty-social-<?php echo esc_attr($social['slug']) ?>">
    <!-- channel default settings start -->
    <div class="channels-selected__item <?php echo esc_attr(($status) ? "img-active" : "") ?> free 1 available">
        <!-- icon and input field start -->
        <div class="chaty-default-settings space-x-3 flex items-center">
            <!-- drag icon and channel icon start -->
            <div class="flex space-x-2 items-center relative">
                <div class="move-icon opacity-0 transition duration-150 hover:opacity-100 cursor-grab">
                    <img src="<?php echo esc_url(CHT_PLUGIN_URL."admin/assets/images/move-icon.png") ?>">
                </div>
                <div class="icon icon-md active" data-label="<?php echo esc_attr($social['title']); ?>" id="chaty_image_<?php echo esc_attr($social['slug']) ?>">
                    <span style="" class="custom-chaty-image custom-image-<?php echo esc_attr($social['slug']) ?>" id="image_data_<?php echo esc_attr($social['slug']) ?>">
                        <img src="<?php echo esc_url($imageUrl) ?>" />
                        <span onclick="remove_chaty_image('<?php echo esc_attr($social['slug']) ?>')" class="remove-icon-img"></span>
                    </span>
                    <span class="default-chaty-icon <?php echo (isset($value['fa_icon'])&&!empty($value['fa_icon'])) ? "has-fa-icon" : "" ?> custom-icon-<?php echo esc_attr($social['slug']) ?> default_image_<?php echo esc_attr($social['slug']) ?>">
                        <?php echo $svgIcon; ?>
                        <span class="facustom-icon" style="background-color: <?php echo esc_attr($value['bg_color']) ?>"><i class="<?php echo esc_attr($value['fa_icon']) ?>"></i></span>
                    </span>
                </div>
                <?php if(isset($social['header_help']) && !empty($social['header_help'])) { ?>
                    <span class="header-tooltip header-icon-tooltip">
                        <span class="header-tooltip-text text-center"><?php echo esc_attr($social['header_help']) ?></span>
                        <span class="ml-1">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16" fill="none">
                                <path d="M8.00004 14.6654C11.6819 14.6654 14.6667 11.6806 14.6667 7.9987C14.6667 4.3168 11.6819 1.33203 8.00004 1.33203C4.31814 1.33203 1.33337 4.3168 1.33337 7.9987C1.33337 11.6806 4.31814 14.6654 8.00004 14.6654Z" stroke="#72777c" stroke-width="1.33" stroke-linecap="round" stroke-linejoin="round"></path>
                                <path d="M8 10.6667V8" stroke="#72777c" stroke-width="1.33" stroke-linecap="round" stroke-linejoin="round"></path>
                                <path d="M8 5.33203H8.00667" stroke="#72777c" stroke-width="1.33" stroke-linecap="round" stroke-linejoin="round"></path>
                            </svg>
                        </span>
                    </span>
                <?php } ?>
            </div>
            <!-- drag icon and channel icon end -->
            <!-- input field start-->
            <?php if ($social['slug'] != 'Contact_Us') { ?>
            <?php if (($social['slug'] == "Whatsapp" || $channelType == "Whatsapp") && !empty($value['value'])) {
//                    $value['value'] = trim($value['value'], "+");
//                    $value['value'] = "+".$value['value'];
                    if($value['value'][1] == "0") {
                        $value['value'][1] = " ";
                        $value['value'] = str_replace(' ', '', $value['value']);
                    }
                } ?>
            <div class="channels__input-box flex-auto">
                <div class="p-relative test-btn <?php echo esc_attr($social['slug']) ?>-input">
                    <input data-label="<?php echo esc_attr($social['title']) ?>" placeholder="<?php echo esc_attr($placeholder); ?>" type="text" class="channels__input custom-channel-<?php echo esc_attr($channelType) ?> <?php echo isset($social['attr']) ? esc_attr($social['attr']) : "" ?>" name="cht_social_<?php echo esc_attr($social['slug']); ?>[value]" value="<?php echo esc_attr(wp_unslash($value['value'])); ?>" data-gramm_editor="false" id="channel_input_<?php echo esc_attr($social['slug']); ?>" />

                    <?php if($social['slug'] == 'Whatsapp' || $social['slug'] == 'Facebook_Messenger') { ?>
                        <button type="button" class="wf-test-button <?php echo !empty($value['value']) ? "active" : "" ?>" data-slug="<?php echo esc_attr($social['slug']) ?>"><?php esc_html_e('Test', 'chaty') ?></button>
                    <?php } ?>
                </div>
            </div>
            <?php } ?>
            <!-- input field end -->
        </div>
        <!-- icon and input field end -->

        <!-- example and help text start -->
        <div class="flex items-center flex-wrap gap-3 pl-20 ml-1">
            <?php if ($social['slug'] != 'Contact_Us') { ?>


            <!-- checking for extra help message for social media -->
            <div class="help-section inline-block relative">
                <?php if ((isset($social['help']) && !empty($social['help'])) || isset($social['help_link'])) { ?>
                <div class="viber-help">
                    <?php if (isset($helpLink) && !empty($helpLink)) { ?>
                    <a class="help-link" href="<?php echo esc_url($helpLink) ?>" target="_blank"><?php echo esc_attr($helpTitle); ?></a>
                        <?php } else if (isset($helpText) && !empty($helpText)) { ?>
                        <?php
                        $allowedHTML = [
                            'a'      => [
                                'href'  => [],
                                'title' => [],
                                'target' => []
                            ],
                            'br'     => [],
                            'em'     => [],
                            'strong' => [],
                        ]; ?>
                        <span class="help-text"><?php echo wp_kses($helpText, $allowedHTML); ?></span>
                        <!-- $helpText contains HTML -->
                        <span class="help-title"><?php echo esc_attr($helpTitle); ?></span>
                    <?php } ?>
                </div>
                <?php } ?>
            </div>
            <?php }//end if
        ?>
        </div>

        <!-- example and help text end -->
        <!-- whatsapp settings start -->
        <?php if ($social['slug'] == "Whatsapp" || $social['slug'] == "Link" || $social['slug'] == "Custom_Link" || $social['slug'] == "Custom_Link_3" || $social['slug'] == "Custom_Link_4" || $social['slug'] == "Custom_Link_5") { ?>
        <div class="Whatsapp-settings sm:pl-20 ml-1 mt-2 font-primary text-cht-gray-150 advanced-settings extra-chaty-settings">
            <?php $embeddedWindow = isset($value['embedded_window']) ? $value['embedded_window'] : "no"; ?>
            <div class="chaty-setting-co">
                <input type="hidden" name="cht_social_<?php echo esc_attr($social['slug']); ?>[embedded_window]" value="no">
                <label class="flex mt-1 items-center chaty-switch full-width chaty-embedded-window" for="whatsapp_embedded_window_<?php echo esc_attr($social['slug']); ?>">
                    <input type="checkbox" class="embedded_window-checkbox" name="cht_social_<?php echo esc_attr($social['slug']); ?>[embedded_window]" id="whatsapp_embedded_window_<?php echo esc_attr($social['slug']); ?>" value="yes" <?php checked($embeddedWindow, "yes") ?>>
                    <div class="chaty-slider round"></div>
                    <?php esc_html_e("Enable WhatsApp Chat Widget", "chaty") ?> &#128172;
                    <div class="html-tooltip top">
                        <span>
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" fill="none">
                                <path d="M8.00004 14.6654C11.6819 14.6654 14.6667 11.6806 14.6667 7.9987C14.6667 4.3168 11.6819 1.33203 8.00004 1.33203C4.31814 1.33203 1.33337 4.3168 1.33337 7.9987C1.33337 11.6806 4.31814 14.6654 8.00004 14.6654Z" stroke="#72777c" stroke-width="1.33" stroke-linecap="round" stroke-linejoin="round">
                                </path>
                                <path d="M8 10.6667V8" stroke="#72777c" stroke-width="1.33" stroke-linecap="round" stroke-linejoin="round"></path>
                                <path d="M8 5.33203H8.00667" stroke="#72777c" stroke-width="1.33" stroke-linecap="round" stroke-linejoin="round"></path>
                            </svg>
                        </span>
                        <span class="tooltip-text top">
                            <?php esc_html_e("Engage visitors with a WhatsApp-style chat window with a welcome message. Visitors can start conversations by typing messages and  clicking on 'Send' will redirect them to WhatsApp.", "chaty") ?>
                            <img src="<?php echo esc_url(CHT_PLUGIN_URL) ?>/admin/assets/images/chaty-wa-widget-ez.gif" loading="lazy"/>
                        </span>
                    </div>
                </label>
            </div>
            <!-- advance setting for Whatsapp -->
            <div class="whatsapp-welcome-message mt-4 <?php echo ($embeddedWindow == "yes") ? "active" : "" ?>">
                <div class="chaty-setting-col">
                    <label style="display: block; width: 100%" for="cht_social_embedded_message_<?php echo esc_attr($social['slug']); ?>"><?php esc_html_e("Welcome message", "chaty") ?></label>
                    <div class="full-width">
                        <div class="full-width">
                            <?php $unique_id = uniqid(); ?>
                            <?php $embeddedMessage = isset($value['embedded_message']) ? $value['embedded_message'] : esc_html__("How can I help you? :)", "chaty"); ?>
                            <textarea class="chaty-setting-textarea chaty-whatsapp-setting-textarea" data-id="<?php echo esc_attr($unique_id) ?>" id="cht_social_embedded_message_<?php echo esc_attr($unique_id) ?>" type="text" name="cht_social_<?php echo esc_attr($social['slug']); ?>[embedded_message]"><?php echo esc_textarea($embeddedMessage) ?></textarea>
                        </div>
                    </div>
                </div>
                <div class="chaty-setting-col">
                    <?php $isDefaultOpen = isset($value['is_default_open']) ? $value['is_default_open'] : "no"; ?>
                    <input type="hidden" name="cht_social_<?php echo esc_attr($social['slug']); ?>[is_default_open]" value="no">
                    <label class="chaty-switch" for="whatsapp_default_open_embedded_window_<?php echo esc_attr($social['slug']); ?>">
                        <input type="checkbox" name="cht_social_<?php echo esc_attr($social['slug']); ?>[is_default_open]" id="whatsapp_default_open_embedded_window_<?php echo esc_attr($social['slug']); ?>" value="yes" <?php checked($isDefaultOpen, "yes") ?>>
                        <div class="chaty-slider round"></div>
                        <?php esc_html_e("Open the chat widget on page load", "chaty") ?>
                        <span class="icon label-tooltip" data-label="<?php esc_html_e("Open the WhatsApp chat popup on page load, after the user sends a message or closes the window, the window will stay closed to avoid disruption", "chaty") ?>" >
                            <span>
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" fill="none">
                                    <path d="M8.00004 14.6654C11.6819 14.6654 14.6667 11.6806 14.6667 7.9987C14.6667 4.3168 11.6819 1.33203 8.00004 1.33203C4.31814 1.33203 1.33337 4.3168 1.33337 7.9987C1.33337 11.6806 4.31814 14.6654 8.00004 14.6654Z" stroke="#72777c" stroke-width="1.33" stroke-linecap="round" stroke-linejoin="round"></path>
                                    <path d="M8 10.6667V8" stroke="#72777c" stroke-width="1.33" stroke-linecap="round" stroke-linejoin="round"></path>
                                    <path d="M8 5.33203H8.00667" stroke="#72777c" stroke-width="1.33" stroke-linecap="round" stroke-linejoin="round"></path>
                                </svg>
                            </span>
                        </span>
                    </label>
                </div>
                <div class="sm:flex sm:items-center sm:space-x-3 mt-4">
                    <?php $wp_popup_headline = isset($value['wp_popup_headline']) ? $value['wp_popup_headline'] : esc_html__("Let's chat on WhatsApp","chaty") ?>
                    <div class="chaty-setting-col inline-options">
                        <label class="font-primary text-base text-cht-gray-150 sm:w-44">
                            <?php esc_html_e("Headline", "chaty") ?>
                        </label>
                        <div>
                            <input type="text" id="wp_popup_headline" name="cht_social_<?php echo esc_attr($social['slug']); ?>[wp_popup_headline]" value="<?php echo esc_attr($wp_popup_headline) ?>">
                        </div>
                    </div>
                    <div class="chaty-setting-col inline-options">
                        <?php
                        $wp_popup_head_bg_color = isset($value['wp_popup_head_bg_color']) ? $value['wp_popup_head_bg_color'] : "#4AA485";
                        $wp_popup_head_bg_color = $this->validate_color($wp_popup_head_bg_color, "#4AA485");
                        ?>
                        <label class="font-primary text-base text-cht-gray-150 sm:w-44">
                            <?php esc_html_e("Header Background", "chaty") ?>
                        </label>
                        <div>
                            <input type="text" name="cht_social_<?php echo esc_attr($social['slug']); ?>[wp_popup_head_bg_color]" class="chaty-color-field button-color" value="<?php echo esc_attr($wp_popup_head_bg_color) ?>" />
                        </div>
                    </div>
                </div>
                <div class="">
                    <?php $wp_popup_nickname = isset($value['wp_popup_nickname']) ? $value['wp_popup_nickname'] : "" ?>
                    <div class="sm:flex sm:items-center sm:space-x-3 mt-4">
                        <div class="chaty-setting-col inline-options">
                            <label class="font-primary text-base text-cht-gray-150 sm:w-44">
                                <?php esc_html_e("Nickname", "chaty") ?>
                            </label>
                            <div>
                                <input type="text" id="wp_popup_nickname" placeholder="Micheal" name="cht_social_<?php echo esc_attr($social['slug']); ?>[wp_popup_nickname]" value="<?php echo esc_attr($wp_popup_nickname) ?>">
                            </div>
                        </div>
                    </div>
                    <?php $wp_popup_profile = isset($value['wp_popup_profile']) ? $value['wp_popup_profile'] : "" ?>
                    <div class="chaty-setting-col sm:flex sm:items-center sm:space-x-3 mt-4">
                        <div class="chaty-setting-col">
                            <label class="font-primary text-base text-cht-gray-150 sm:w-44">
                                <?php esc_html_e("Add a profile image", "chaty") ?>
                            </label>
                            <div class="sm:flex sm:items-center custom-img-upload <?php echo esc_attr(!empty($wp_popup_profile)?"active":"") ?>" id="<?php echo esc_attr($social['slug']); ?>-custom-image-upload">
                                <div class="image-info">
                                    <?php if(!empty($wp_popup_profile)) { ?>
                                        <img src="<?php echo esc_url($wp_popup_profile) ?>" alt="<?php esc_html_e("Profile image", "chaty"); ?>" />
                                    <?php } ?>
                                </div>
                                <a href="javascript:;" class="upload-chaty-icon flex items-center px-2 upload-wp-profile img-upload-btn" data-for="<?php echo esc_attr($social['slug']); ?>">
                                    <?php esc_html_e('Upload Image', 'chaty') ?>
                                </a>
                                <a href="javascript:;" class="remove-custom-img" data-for="<?php echo esc_attr($social['slug']); ?>">
                                    <?php esc_html_e('Remove', 'chaty') ?>
                                </a>
                                <input class="img-value" type="hidden" id="wp_popup_profile" name="cht_social_<?php echo esc_attr($social['slug']); ?>[wp_popup_profile]" value="<?php echo esc_attr($wp_popup_profile) ?>">
                            </div>
                        </div>
                    </div>
                    <?php $input_placeholder = isset($value['input_placeholder']) ? $value['input_placeholder'] : esc_html__("Write your message...","chaty") ?>
                    <div class="sm:flex sm:items-center sm:space-x-3 mt-4">
                        <div class="chaty-setting-col inline-options">
                            <label class="font-primary text-base text-cht-gray-150 sm:w-44">
                                <?php esc_html_e("Text input placeholder", "chaty") ?>
                            </label>
                            <div>
                                <input type="text" class="whatsapp-placeholder" id="input_placeholder_<?php echo esc_attr($social['slug']); ?>" name="cht_social_<?php echo esc_attr($social['slug']); ?>[input_placeholder]" value="<?php echo esc_attr($input_placeholder) ?>">
                            </div>
                        </div>
                    </div>
                    <div class="chaty-setting-col mt-4">
                        <?php $emoji_picker= isset($value['emoji_picker']) ? $value['emoji_picker'] : "yes"; ?>
                        <input type="hidden" name="cht_social_<?php echo esc_attr($social['slug']); ?>[emoji_picker]" value="no" >
                        <label class="chaty-switch text-base font-primary text-cht-gray-150" for="whatsapp_emoji_picker_<?php echo esc_attr($social['slug']); ?>">
                            <input type="checkbox" class="whatsapp-emoji" name="cht_social_<?php echo esc_attr($social['slug']); ?>[emoji_picker]" id="whatsapp_emoji_picker_<?php echo esc_attr($social['slug']); ?>" value="yes" <?php checked($emoji_picker, "yes") ?> >
                            <div class="chaty-slider round"></div>
                            <span class="text-cht-gray-150">
                                    <?php esc_html_e("Enable emoji picker", "chaty") ?>
                                </span>

                            <div class="html-tooltip top">
                                    <span>
                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" fill="none">
                                            <path d="M8.00004 14.6654C11.6819 14.6654 14.6667 11.6806 14.6667 7.9987C14.6667 4.3168 11.6819 1.33203 8.00004 1.33203C4.31814 1.33203 1.33337 4.3168 1.33337 7.9987C1.33337 11.6806 4.31814 14.6654 8.00004 14.6654Z" stroke="#72777c" stroke-width="1.33" stroke-linecap="round" stroke-linejoin="round"/>
                                            <path d="M8 10.6667V8" stroke="#72777c" stroke-width="1.33" stroke-linecap="round" stroke-linejoin="round"/>
                                            <path d="M8 5.33203H8.00667" stroke="#72777c" stroke-width="1.33" stroke-linecap="round" stroke-linejoin="round"/>
                                        </svg>
                                    </span>
                                <span class="tooltip-text top">
                                        <?php esc_html_e("Allow visitors to pick emoji from the emoji picker and enter them into the message input field", "chaty") ?>
                                        <img alt="chaty" src="<?php echo esc_url(CHT_PLUGIN_URL) ?>/admin/assets/images/chaty-emoji.png" loading="lazy"/>
                                    </span>
                            </div>
                        </label>
                    </div>
                </div>
            </div>
        </div>
        <?php }//end if
        ?>
        <!-- whatsapp settings end -->

        <!-- device, agent and settings button start -->
        <div class="flex items-center sm:pl-20 ml-1 mt-2">
            <?php if ($social['slug'] == 'Contact_Us') { ?>
                <?php $fields = [
                    'name'    => [
                        'title'       => esc_html__("Name", "chaty"),
                        'placeholder' => esc_html__("Enter your name", "chaty"),
                        'is_required' => 1,
                        'type'        => 'input',
                        'is_enabled'  => 1,
                    ],
                    'email'   => [
                        'title'       => esc_html__("Email", "chaty"),
                        'placeholder' => esc_html__("Enter your email address", "chaty"),
                        'is_required' => 1,
                        'type'        => 'email',
                        'is_enabled'  => 1,
                    ],
                    'phone'   => [
                        'title'       => esc_html__("Phone", "chaty"),
                        'placeholder' => esc_html__("Enter your phone number", "chaty"),
                        'is_required' => 1,
                        'type'        => 'input',
                        'is_enabled'  => 1,
                    ],
                    'message' => [
                        'title'       => esc_html__("Message", "chaty"),
                        'placeholder' => esc_html__("Enter your message", "chaty"),
                        'is_required' => 1,
                        'type'        => 'textarea',
                        'is_enabled'  => 1,
                    ],
                ];
                echo '<div class="form-field-setting-col">';
                foreach ($fields as $label => $field) {
                    $saved_value = isset($value[$label]) ? $value[$label] : [];
                    $field_value = [
                        'is_active'   => (isset($saved_value['is_active'])) ? $saved_value['is_active'] : 'yes',
                        'is_required' => (isset($saved_value['is_required'])) ? $saved_value['is_required'] : 'yes',
                        'placeholder' => (isset($saved_value['placeholder'])) ? $saved_value['placeholder'] : $field['placeholder'],
                        'field_label' => (isset($saved_value['field_label'])) ? $saved_value['field_label'] : $field['title'],
                    ];
                    ?>
                    <div class="field-setting-col  <?php echo ($field_value['is_active'] == "yes") ? "" : "hide-label-setting" ?> mb-2.5">
                        <input type="hidden" name="cht_social_<?php echo esc_attr($social['slug']); ?>[<?php echo esc_attr($label) ?>][is_active]" value="no">
                        <input type="hidden" name="cht_social_<?php echo esc_attr($social['slug']); ?>[<?php echo esc_attr($label) ?>][is_required]" value="no">

                        <div class="label-flex mb-4">
                            <label class="chaty-switch chaty-switch-toggle text-cht-gray-150 text-base" for="field_for_<?php echo esc_attr($social['slug']); ?>_<?php echo esc_attr($label) ?>">
                                <input type="checkbox" class="chaty-field-setting" name="cht_social_<?php echo esc_attr($social['slug']); ?>[<?php echo esc_attr($label) ?>][is_active]" id="field_for_<?php echo esc_attr($social['slug']); ?>_<?php echo esc_attr($label) ?>" value="yes" <?php checked($field_value['is_active'], "yes") ?>>
                                <div class="chaty-slider round"></div>
                                <span class="field-label"><?php echo esc_attr($field_value['field_label']) ?>
                                    <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <g clip-path="url(#clip0_8719_30645)">
                                            <path d="M7.33398 2.66699H2.66732C2.3137 2.66699 1.97456 2.80747 1.72451 3.05752C1.47446 3.30756 1.33398 3.6467 1.33398 4.00033V13.3337C1.33398 13.6873 1.47446 14.0264 1.72451 14.2765C1.97456 14.5265 2.3137 14.667 2.66732 14.667H12.0006C12.3543 14.667 12.6934 14.5265 12.9435 14.2765C13.1935 14.0264 13.334 13.6873 13.334 13.3337V8.66699" stroke="#C6D7E3" stroke-width="1.33" stroke-linecap="round" stroke-linejoin="round"/>
                                            <path d="M12.334 1.66617C12.5992 1.40095 12.9589 1.25195 13.334 1.25195C13.7091 1.25195 14.0688 1.40095 14.334 1.66617C14.5992 1.93138 14.7482 2.29109 14.7482 2.66617C14.7482 3.04124 14.5992 3.40095 14.334 3.66617L8.00065 9.9995L5.33398 10.6662L6.00065 7.9995L12.334 1.66617Z" stroke="#C6D7E3" stroke-width="1.33" stroke-linecap="round" stroke-linejoin="round"/>
                                        </g>
                                        <defs>
                                            <clipPath id="clip0_8719_30645">
                                                <rect width="16" height="16" fill="white"/>
                                            </clipPath>
                                        </defs>
                                    </svg>
                                </span>
                            </label>
                            <div class="label-input">
                                <input type="text" class="label-input-field chaty-input-text" value="<?php echo esc_attr($field_value['field_label']) ?>" name="cht_social_<?php echo esc_attr($social['slug']); ?>[<?php echo esc_attr($label) ?>][field_label]" />
                            </div>
                        </div>
                        <div class="field-settings <?php echo ($field_value['is_active'] == "yes") ? "active" : "" ?>">
                            <div class="chaty-setting-col pb-4 grid sm:grid-cols-2 items-center gap-3">
                                <div>
                                    <input class="rounded-lg w-full chaty-input-text contact_form_custom_value" data-type="<?php echo esc_attr($field['type']) ?>" id="placeholder_for_<?php echo esc_attr($social['slug']); ?>_<?php echo esc_attr($label) ?>" type="text" name="cht_social_<?php echo esc_attr($social['slug']); ?>[<?php echo esc_attr($label) ?>][placeholder]" value="<?php echo esc_attr($field_value['placeholder']) ?>" >
                                </div>
                                <div class="flex items-center space-x-3">
                                    <div class="checkbox">
                                        <label for="field_required_for_<?php echo esc_attr($social['slug']); ?>_<?php echo esc_attr($label) ?>" class="chaty-checkbox text-cht-gray-150 text-base flex items-center">
                                            <input class="sr-only" type="checkbox" id="field_required_for_<?php echo esc_attr($social['slug']); ?>_<?php echo esc_attr($label) ?>" name="cht_social_<?php echo esc_attr($social['slug']); ?>[<?php echo esc_attr($label) ?>][is_required]" value="yes" <?php checked($field_value['is_required'], "yes") ?> />
                                            <span class="mt-[3px] mr-2"></span>
                                            <?php esc_html_e("Required?", "chaty") ?>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php }//end foreach
                echo '</div>'; ?>
            <?php } ?>
        </div>

        <?php if ($social['slug'] == 'Contact_Us') { ?>
            <div class="custom-field-setting">
                <a href="<?php echo esc_url($this->getUpgradeMenuItemUrl()); ?>" target="_blank" class="space-x-1 pro-button-wrap">
                    <span>
                        <svg width="16" height="16" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg"> <path d="M10 6V14M6 10H14M5.8 19H14.2C15.8802 19 16.7202 19 17.362 18.673C17.9265 18.3854 18.3854 17.9265 18.673 17.362C19 16.7202 19 15.8802 19 14.2V5.8C19 4.11984 19 3.27976 18.673 2.63803C18.3854 2.07354 17.9265 1.6146 17.362 1.32698C16.7202 1 15.8802 1 14.2 1H5.8C4.11984 1 3.27976 1 2.63803 1.32698C2.07354 1.6146 1.6146 2.07354 1.32698 2.63803C1 3.27976 1 4.11984 1 5.8V14.2C1 15.8802 1 16.7202 1.32698 17.362C1.6146 17.9265 2.07354 18.3854 2.63803 18.673C3.27976 19 4.11984 19 5.8 19Z" stroke="black" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/> </svg>
                    </span>
                    <span><?php esc_html_e("Add Custom Field", "chaty"); ?></span>
                    <div class="pro-button">
                        <span class="pro-btn text-white rounded-md bg-cht-primary hover:text-white">
                            <?php esc_html_e('Upgrade to Pro', 'chaty');?>
                        </span>
                    </div>
                </a>
            </div>
        <?php } ?>

        <div class="flex items-center sm:pl-20 ml-1">
            <div class="channels__device-box ml-2 chaty-setting-col sm:flex items-center space-y-2 sm:space-y-0 sm:space-x-3 mr-2">
                <label class="font-primary text-base text-cht-gray-150"><?php esc_html_e("Show on", "chaty") ?></label>
                <div class="device-box">
                    <?php
                    $slug      = esc_attr($this->del_space($social['slug']));
                    $slug      = str_replace(' ', '_', $slug);
                    $isDesktop = isset($value['is_desktop']) && $value['is_desktop'] == "checked" ? "checked" : '';
                    $isMobile  = isset($value['is_mobile']) && $value['is_mobile'] == "checked" ? "checked" : '';
                    ?>
                    <!-- setting for desktop -->
                    <label class="device_view cursor-pointer" for="<?php echo esc_attr($slug); ?>Desktop">
                        <input type="checkbox" id="<?php echo esc_attr($slug); ?>Desktop" class="channels__view-check sr-only js-chanel-icon js-chanel-desktop" data-type="<?php echo esc_attr(str_replace(' ', '_', strtolower(esc_attr($this->del_space($social['slug']))))); ?>" name="cht_social_<?php echo esc_attr($social['slug']); ?>[is_desktop]" value="checked" data-gramm_editor="false" <?php echo esc_attr($isDesktop) ?> />
                        <span class="channels__view-txt">
                            <svg width="16" height="12" viewBox="0 0 16 12" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M13.3333 10.0001C14.0667 10.0001 14.6667 9.40008 14.6667 8.66675V2.00008C14.6667 1.26675 14.0667 0.666748 13.3333 0.666748H2.66667C1.93333 0.666748 1.33333 1.26675 1.33333 2.00008V8.66675C1.33333 9.40008 1.93333 10.0001 2.66667 10.0001H0.666667C0.3 10.0001 0 10.3001 0 10.6667C0 11.0334 0.3 11.3334 0.666667 11.3334H15.3333C15.7 11.3334 16 11.0334 16 10.6667C16 10.3001 15.7 10.0001 15.3333 10.0001H13.3333ZM3.33333 2.00008H12.6667C13.0333 2.00008 13.3333 2.30008 13.3333 2.66675V8.00008C13.3333 8.36675 13.0333 8.66675 12.6667 8.66675H3.33333C2.96667 8.66675 2.66667 8.36675 2.66667 8.00008V2.66675C2.66667 2.30008 2.96667 2.00008 3.33333 2.00008Z" />
                            </svg>
                        </span>
                        <span class="device-tooltip">
                            <span class="on"><?php esc_html_e("Hide on desktop", "chaty") ?></span>
                            <span class="off"><?php esc_html_e("Show on desktop", "chaty") ?></span>
                        </span>
                    </label>

                    <!-- setting for mobile -->
                    <label class="device_view cursor-pointer" for="<?php echo esc_attr($slug); ?>Mobile">
                        <input type="checkbox" id="<?php echo esc_attr($slug); ?>Mobile" class="channels__view-check sr-only js-chanel-icon js-chanel-mobile" data-type="<?php echo esc_attr(str_replace(' ', '_', strtolower(esc_attr($this->del_space($social['slug']))))); ?>" name="cht_social_<?php echo esc_attr($social['slug']); ?>[is_mobile]" value="checked" data-gramm_editor="false" <?php echo esc_attr($isMobile) ?>>
                        <span class="channels__view-txt">
                            <svg width="9" height="16" viewBox="0 0 9 16" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path d="M7.33301 0.666748H1.99967C1.07967 0.666748 0.333008 1.41341 0.333008 2.33341V13.6667C0.333008 14.5867 1.07967 15.3334 1.99967 15.3334H7.33301C8.25301 15.3334 8.99967 14.5867 8.99967 13.6667V2.33341C8.99967 1.41341 8.25301 0.666748 7.33301 0.666748ZM4.66634 14.6667C4.11301 14.6667 3.66634 14.2201 3.66634 13.6667C3.66634 13.1134 4.11301 12.6667 4.66634 12.6667C5.21967 12.6667 5.66634 13.1134 5.66634 13.6667C5.66634 14.2201 5.21967 14.6667 4.66634 14.6667ZM7.66634 12.0001H1.66634V2.66675H7.66634V12.0001Z" />
                            </svg>
                        </span>
                        <span class="device-tooltip">
                            <span class="on"><?php esc_html_e("Hide on mobile", "chaty") ?></span>
                            <span class="off"><?php esc_html_e("Show on mobile", "chaty") ?></span>
                        </span>
                    </label>
                </div>
            </div>

            <?php if ($slug != 'Custom_Link' && $slug != 'Custom_Link_3' && $slug != 'Custom_Link_4' && $slug != 'Custom_Link_5' && $slug != 'Contact_Us' && $slug != 'Link') { ?>
            <div class="channels__agent-box relative">
                <a href="#" class="add-agent-btn space-x-1 pro-button-wrap">
                    <svg width="14" height="12" viewBox="0 0 14 12" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M5.25 5.14286C6.69975 5.14286 7.875 3.99159 7.875 2.57143C7.875 1.15127 6.69975 0 5.25 0C3.80025 0 2.625 1.15127 2.625 2.57143C2.625 3.99159 3.80025 5.14286 5.25 5.14286Z" fill="currentColor" />
                        <path d="M5.25 6.85714C8.1495 6.85714 10.5 9.15968 10.5 12H0C0 9.15968 2.35051 6.85714 5.25 6.85714Z" fill="currentColor" />
                        <path d="M12.25 3.42857C12.25 2.95518 11.8582 2.57143 11.375 2.57143C10.8918 2.57143 10.5 2.95518 10.5 3.42857V4.28571H9.625C9.14175 4.28571 8.75 4.66947 8.75 5.14286C8.75 5.61624 9.14175 6 9.625 6H10.5V6.85714C10.5 7.33053 10.8918 7.71429 11.375 7.71429C11.8582 7.71429 12.25 7.33053 12.25 6.85714V6H13.125C13.6082 6 14 5.61624 14 5.14286C14 4.66947 13.6082 4.28571 13.125 4.28571H12.25V3.42857Z" fill="currentColor" />
                    </svg>
                    <span><?php esc_html_e("Add Agents", "chaty"); ?></span>
                    <span class="header-tooltip">
                        <span class="header-tooltip-text text-center"><?php esc_html_e('Learn more here: https://premio.io/help/chaty/how-to-use-chaty-with-different-agents/', 'chaty');?></span>
                        <span class="ml-1">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16" fill="none">
                                <path d="M8.00004 14.6654C11.6819 14.6654 14.6667 11.6806 14.6667 7.9987C14.6667 4.3168 11.6819 1.33203 8.00004 1.33203C4.31814 1.33203 1.33337 4.3168 1.33337 7.9987C1.33337 11.6806 4.31814 14.6654 8.00004 14.6654Z" stroke="#72777c" stroke-width="1.33" stroke-linecap="round" stroke-linejoin="round"></path>
                                <path d="M8 10.6667V8" stroke="#72777c" stroke-width="1.33" stroke-linecap="round" stroke-linejoin="round"></path>
                                <path d="M8 5.33203H8.00667" stroke="#72777c" stroke-width="1.33" stroke-linecap="round" stroke-linejoin="round"></path>
                            </svg>
                        </span>
                    </span>
                    <div class="pro-button">
                        <span class="pro-btn text-white rounded-md bg-cht-primary hover:text-white">
                            <?php esc_html_e('Upgrade to Pro', 'chaty');?>
                        </span>
                    </div>
                </a>
            </div>
            <?php } else if($slug != 'Contact_Us') { ?>
            <div class="channels__agent-box transparent relative hidden">
                <a href="#" class="add-agent-btn pro-button-wrap">
                    <svg width="14" height="12" viewBox="0 0 14 12" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M5.25 5.14286C6.69975 5.14286 7.875 3.99159 7.875 2.57143C7.875 1.15127 6.69975 0 5.25 0C3.80025 0 2.625 1.15127 2.625 2.57143C2.625 3.99159 3.80025 5.14286 5.25 5.14286Z" fill="white" />
                        <path d="M5.25 6.85714C8.1495 6.85714 10.5 9.15968 10.5 12H0C0 9.15968 2.35051 6.85714 5.25 6.85714Z" fill="white" />
                        <path d="M12.25 3.42857C12.25 2.95518 11.8582 2.57143 11.375 2.57143C10.8918 2.57143 10.5 2.95518 10.5 3.42857V4.28571H9.625C9.14175 4.28571 8.75 4.66947 8.75 5.14286C8.75 5.61624 9.14175 6 9.625 6H10.5V6.85714C10.5 7.33053 10.8918 7.71429 11.375 7.71429C11.8582 7.71429 12.25 7.33053 12.25 6.85714V6H13.125C13.6082 6 14 5.61624 14 5.14286C14 4.66947 13.6082 4.28571 13.125 4.28571H12.25V3.42857Z" fill="white" />
                    </svg>
                    <span><?php esc_html_e("Add Agents", "chaty"); ?></span>
                    <span class="header-tooltip">
                        <span class="header-tooltip-text text-center"><?php esc_html_e('Learn more here: https://premio.io/help/chaty/how-to-use-chaty-with-different-agents/', 'chaty');?></span>
                        <span class="ml-1">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16" fill="none">
                                <path d="M8.00004 14.6654C11.6819 14.6654 14.6667 11.6806 14.6667 7.9987C14.6667 4.3168 11.6819 1.33203 8.00004 1.33203C4.31814 1.33203 1.33337 4.3168 1.33337 7.9987C1.33337 11.6806 4.31814 14.6654 8.00004 14.6654Z" stroke="#72777c" stroke-width="1.33" stroke-linecap="round" stroke-linejoin="round"></path>
                                <path d="M8 10.6667V8" stroke="#72777c" stroke-width="1.33" stroke-linecap="round" stroke-linejoin="round"></path>
                                <path d="M8 5.33203H8.00667" stroke="#72777c" stroke-width="1.33" stroke-linecap="round" stroke-linejoin="round"></path>
                            </svg>
                        </span>
                    </span>
                    <div class="pro-button">
                        <span class="pro-btn text-white rounded-md bg-cht-primary hover:text-white">
                            <?php esc_html_e('Upgrade to Pro', 'chaty');?>
                        </span>
                    </div>
                </a>
            </div>
            <?php }//end if
            ?>

            <?php
            $closeClass = "active";
            if ($social['slug'] == 'Contact_Us') {
                $settingStatus = get_option("chaty_contact_us_setting");
                if ($settingStatus === false) {
                    $closeClass = "";
                }
            }
            ?>

            <!-- settings button -->
            <div class="chaty-settings <?php echo esc_attr($closeClass) ?>" data-nonce="<?php echo esc_attr(wp_create_nonce($social['slug']."-settings")) ?>" id="<?php echo esc_attr($social['slug']); ?>-close-btn" onclick="toggle_chaty_setting('<?php echo esc_attr($social['slug']); ?>')">
                <a class="flex items-center space-x-1.5" href="javascript:;">
                    <span>
                        <svg width="16" height="16" viewBox="0 0 16 16" fill="none">
                            <path d="M8 10C9.10457 10 10 9.10457 10 8C10 6.89543 9.10457 6 8 6C6.89543 6 6 6.89543 6 8C6 9.10457 6.89543 10 8 10Z" stroke="currentColor" stroke-width="1.33" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M12.9332 9.99984C12.8444 10.2009 12.818 10.424 12.8572 10.6402C12.8964 10.8565 12.9995 11.056 13.1532 11.2132L13.1932 11.2532C13.3171 11.377 13.4155 11.5241 13.4826 11.6859C13.5497 11.8478 13.5842 12.0213 13.5842 12.1965C13.5842 12.3717 13.5497 12.5452 13.4826 12.7071C13.4155 12.869 13.3171 13.016 13.1932 13.1398C13.0693 13.2638 12.9223 13.3621 12.7604 13.4292C12.5986 13.4963 12.4251 13.5309 12.2498 13.5309C12.0746 13.5309 11.9011 13.4963 11.7392 13.4292C11.5774 13.3621 11.4303 13.2638 11.3065 13.1398L11.2665 13.0998C11.1094 12.9461 10.9098 12.843 10.6936 12.8038C10.4773 12.7646 10.2542 12.7911 10.0532 12.8798C9.85599 12.9643 9.68782 13.1047 9.56937 13.2835C9.45092 13.4624 9.38736 13.672 9.3865 13.8865V13.9998C9.3865 14.3535 9.24603 14.6926 8.99598 14.9426C8.74593 15.1927 8.40679 15.3332 8.05317 15.3332C7.69955 15.3332 7.36041 15.1927 7.11036 14.9426C6.86031 14.6926 6.71984 14.3535 6.71984 13.9998V13.9398C6.71467 13.7192 6.64325 13.5052 6.51484 13.3256C6.38644 13.1461 6.20699 13.0094 5.99984 12.9332C5.79876 12.8444 5.57571 12.818 5.35944 12.8572C5.14318 12.8964 4.94362 12.9995 4.7865 13.1532L4.7465 13.1932C4.62267 13.3171 4.47562 13.4155 4.31376 13.4826C4.15189 13.5497 3.97839 13.5842 3.80317 13.5842C3.62795 13.5842 3.45445 13.5497 3.29258 13.4826C3.13072 13.4155 2.98367 13.3171 2.85984 13.1932C2.73587 13.0693 2.63752 12.9223 2.57042 12.7604C2.50332 12.5986 2.46879 12.4251 2.46879 12.2498C2.46879 12.0746 2.50332 11.9011 2.57042 11.7392C2.63752 11.5774 2.73587 11.4303 2.85984 11.3065L2.89984 11.2665C3.05353 11.1094 3.15663 10.9098 3.19584 10.6936C3.23505 10.4773 3.20858 10.2542 3.11984 10.0532C3.03533 9.85599 2.89501 9.68782 2.71615 9.56937C2.53729 9.45092 2.32769 9.38736 2.11317 9.3865H1.99984C1.64622 9.3865 1.30708 9.24603 1.05703 8.99598C0.80698 8.74593 0.666504 8.40679 0.666504 8.05317C0.666504 7.69955 0.80698 7.36041 1.05703 7.11036C1.30708 6.86031 1.64622 6.71984 1.99984 6.71984H2.05984C2.2805 6.71467 2.49451 6.64325 2.67404 6.51484C2.85357 6.38644 2.99031 6.20699 3.0665 5.99984C3.15525 5.79876 3.18172 5.57571 3.14251 5.35944C3.10329 5.14318 3.00019 4.94362 2.8465 4.7865L2.8065 4.7465C2.68254 4.62267 2.58419 4.47562 2.51709 4.31376C2.44999 4.15189 2.41545 3.97839 2.41545 3.80317C2.41545 3.62795 2.44999 3.45445 2.51709 3.29258C2.58419 3.13072 2.68254 2.98367 2.8065 2.85984C2.93033 2.73587 3.07739 2.63752 3.23925 2.57042C3.40111 2.50332 3.57462 2.46879 3.74984 2.46879C3.92506 2.46879 4.09856 2.50332 4.26042 2.57042C4.42229 2.63752 4.56934 2.73587 4.69317 2.85984L4.73317 2.89984C4.89029 3.05353 5.08985 3.15663 5.30611 3.19584C5.52237 3.23505 5.74543 3.20858 5.9465 3.11984H5.99984C6.19702 3.03533 6.36518 2.89501 6.48363 2.71615C6.60208 2.53729 6.66565 2.32769 6.6665 2.11317V1.99984C6.6665 1.64622 6.80698 1.30708 7.05703 1.05703C7.30708 0.80698 7.64621 0.666504 7.99984 0.666504C8.35346 0.666504 8.6926 0.80698 8.94264 1.05703C9.19269 1.30708 9.33317 1.64622 9.33317 1.99984V2.05984C9.33402 2.27436 9.39759 2.48395 9.51604 2.66281C9.63449 2.84167 9.80266 2.98199 9.99984 3.0665C10.2009 3.15525 10.424 3.18172 10.6402 3.14251C10.8565 3.10329 11.056 3.00019 11.2132 2.8465L11.2532 2.8065C11.377 2.68254 11.5241 2.58419 11.6859 2.51709C11.8478 2.44999 12.0213 2.41545 12.1965 2.41545C12.3717 2.41545 12.5452 2.44999 12.7071 2.51709C12.869 2.58419 13.016 2.68254 13.1398 2.8065C13.2638 2.93033 13.3621 3.07739 13.4292 3.23925C13.4963 3.40111 13.5309 3.57462 13.5309 3.74984C13.5309 3.92506 13.4963 4.09856 13.4292 4.26042C13.3621 4.42229 13.2638 4.56934 13.1398 4.69317L13.0998 4.73317C12.9461 4.89029 12.843 5.08985 12.8038 5.30611C12.7646 5.52237 12.7911 5.74543 12.8798 5.9465V5.99984C12.9643 6.19702 13.1047 6.36518 13.2835 6.48363C13.4624 6.60208 13.672 6.66565 13.8865 6.6665H13.9998C14.3535 6.6665 14.6926 6.80698 14.9426 7.05703C15.1927 7.30708 15.3332 7.64621 15.3332 7.99984C15.3332 8.35346 15.1927 8.6926 14.9426 8.94264C14.6926 9.19269 14.3535 9.33317 13.9998 9.33317H13.9398C13.7253 9.33402 13.5157 9.39759 13.3369 9.51604C13.158 9.63449 13.0177 9.80266 12.9332 9.99984V9.99984Z" stroke="currentColor" stroke-width="1.33" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </span> 
                    <span><?php esc_html_e("Settings", "chaty") ?></span>
                </a>
            </div>
        </div>
        <!-- device, agent and settings button end -->


        <!-- advance setting fields: start -->
        <?php $className = "not-is-pro"; ?>
        <div class="chaty-advance-settings mt-5 space-y-4 <?php echo esc_attr($className); ?>" style="<?php echo (empty($closeClass) && $social['slug'] == 'Contact_Us') ? "display:block" : ""; ?>">
            <!-- Settings for custom icon and color -->
            <div class="chaty-setting-col sm:flex items-center space-y-2 sm:space-y-0 sm:space-x-3">
                <label class="font-primary text-base text-cht-gray-150 sm:w-44"><?php esc_html_e("Icon Appearance", "chaty") ?></label>
                <div class="flex items-center">
                    <!-- input for custom color -->
                    <input type="text" name="cht_social_<?php echo esc_attr($social['slug']); ?>[bg_color]" class="chaty-color-field chaty-bg-color" value="<?php echo esc_attr($this->validate_color($value['bg_color'], $social['color'])) ?>" />

                    <!-- button to upload custom image -->
                    <div class="pro-features upload-image mx-2">
                        <div class="pro-item">
                            <a target="_blank" href="<?php echo esc_url($this->getUpgradeMenuItemUrl());?>" class="upload-chaty-icon"><span class="dashicons dashicons-upload"></span> <?php esc_html_e("Custom Image", "chaty") ?></a>
                        </div>
                        <div class="pro-button">
                            <a target="_blank" href="<?php echo esc_url($this->getUpgradeMenuItemUrl());?>"><?php esc_html_e('Upgrade to Pro', 'chaty');?></a>
                        </div>
                    </div>

                    <div class="pro-features upload-image">
                        <div class="pro-item">
                            <a target="_blank" href="<?php echo esc_url($this->getUpgradeMenuItemUrl());?>" class="upload-chaty-icon"><?php esc_html_e("Change Icon", "chaty") ?></a>
                        </div>
                        <div class="pro-button">
                            <a target="_blank" href="<?php echo esc_url($this->getUpgradeMenuItemUrl());?>"><?php esc_html_e('Upgrade to Pro', 'chaty');?></a>
                        </div>
                    </div>
                </div>
            </div>

            <?php if ($social['slug'] == "Link" || $social['slug'] == "Custom_Link" || $social['slug'] == "Custom_Link_3" || $social['slug'] == "4" || $social['slug'] == "Custom_Link_5") {
            $channelType = "";
            $channelValue= "";
            if (isset($value['channel_type'])) {
                $channelType = esc_attr(wp_unslash($value['channel_type']));
                $channelValue= $value['channel_type'];
            }

            $socials = $this->socials;
            ?>
            <div class="chaty-setting-col space-y-2 sm:space-y-0 sm:flex sm:items-center sm:space-x-3">
                <label class="font-primary text-base text-cht-gray-150 sm:w-44"><?php esc_html_e("Channel type", "chaty") ?></label>
                <div>
                    <!-- input for custom title -->
                    <select class="channel-select-input" name="cht_social_<?php echo esc_attr($social['slug']); ?>[channel_type]" value="<?php echo esc_attr($channelValue) ?>">
                        <option value="<?php echo esc_attr($social['slug']) ?>"><?php esc_html_e("Custom channel", "chaty") ?></option>
                        <?php foreach ($socials as $socialIcon) {
                            $selected = ($socialIcon['slug'] == $channelType) ? "selected" : "";
                            if ($socialIcon['slug'] != 'Custom_Link' && $socialIcon['slug'] != 'Custom_Link_3' && $socialIcon['slug'] != 'Custom_Link_4' && $socialIcon['slug'] != 'Custom_Link_5' && $socialIcon['slug'] != 'Contact_Us' && $socialIcon['slug'] != 'Link' && $socialIcon['slug'] != 'Chatway') { ?>
                            <option <?php echo esc_attr($selected) ?> value="<?php echo esc_attr($socialIcon['slug']) ?>"><?php echo esc_attr($socialIcon['title']) ?></option>
                        <?php }
                        }?>
                    </select>
                </div>
            </div>
            <?php
            }//end if
            ?>

            <?php if ($social['slug'] != "WeChat") { ?>
                <div class="chaty-setting-col sm:flex sm:items-center space-y-2 sm:space-y-0 sm:space-x-3">
                    <label class="font-primary text-base text-cht-gray-150 sm:w-44"><?php esc_html_e("On Hover Text", "chaty") ?>
                        <span class="header-tooltip">
                            <span class="header-tooltip-text text-center"><?php esc_html_e('The text that will appear next to your channel when a visitor hovers over it', 'chaty');?></span>
                            <span class="ml-1">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16" fill="none">
                                    <path d="M8.00004 14.6654C11.6819 14.6654 14.6667 11.6806 14.6667 7.9987C14.6667 4.3168 11.6819 1.33203 8.00004 1.33203C4.31814 1.33203 1.33337 4.3168 1.33337 7.9987C1.33337 11.6806 4.31814 14.6654 8.00004 14.6654Z" stroke="#72777c" stroke-width="1.33" stroke-linecap="round" stroke-linejoin="round"></path>
                                    <path d="M8 10.6667V8" stroke="#72777c" stroke-width="1.33" stroke-linecap="round" stroke-linejoin="round"></path>
                                    <path d="M8 5.33203H8.00667" stroke="#72777c" stroke-width="1.33" stroke-linecap="round" stroke-linejoin="round"></path>
                                </svg>
                            </span>
                        </span>
                    </label>
                    <div>
                        <input type="text" class="chaty-title" name="cht_social_<?php echo esc_attr($social['slug']); ?>[title]" value="<?php echo esc_attr($value['title']) ?>">
                    </div>
                </div>
            <?php } else { ?>
                <input type="hidden" class="chaty-title" name="cht_social_<?php echo esc_attr($social['slug']); ?>[title]" value="<?php echo esc_attr($value['title']) ?>">
            <?php } ?>

            <?php if($social['slug'] == "Contact_Us") {?>
                <div class="Contact_Us-settings advanced-settings">
                    <div class="form-field-setting-col my-3 space-y-4">
                        <div class="chaty-setting-col sm:flex sm:items-center sm:space-x-3">
                            <label class="font-primary text-base text-cht-gray-150 sm:w-44"><?php esc_html_e("Contact Form Title", "chaty") ?></label>
                            <div>
                                <?php $contactFormTitle = isset($value['contact_form_title']) ? $value['contact_form_title'] : esc_html__("Contact Us", "chaty"); ?>
                                <input class="chaty-input-text" id="cht_social_message_<?php echo esc_attr($social['slug']); ?>_form_title" type="text" name="cht_social_<?php echo esc_attr($social['slug']); ?>[contact_form_title]" value="<?php echo esc_attr($contactFormTitle) ?>">
                            </div>
                        </div>
                        <?php
                        $field_value = isset($value['contact_form_title_bg_color']) ? $value['contact_form_title_bg_color'] : "#A886CD";
                        $field_value = $this->validate_color($field_value, "#A886CD");

                        ?>
                        <div class="chaty-setting-col sm:flex sm:items-center sm:space-x-3">
                            <label class="font-primary text-base text-cht-gray-150 sm:w-44" for="title_bg_color_for_<?php echo esc_attr($social['slug']); ?>"><?php esc_html_e("Title background color", "chaty") ?></label>
                            <div>
                                <input id="title_bg_color_for_<?php echo esc_attr($social['slug']); ?>" class="chaty-color-field button-color" type="text" name="cht_social_<?php echo esc_attr($social['slug']); ?>[contact_form_title_bg_color]" value="<?php echo esc_attr($field_value); ?>" >
                            </div>
                        </div>
                    </div>
                    <div class="form-field-setting-col my-3 space-y-4">
                        <div class="form-field-title"><?php esc_html_e("Submit Button", "chaty") ?></div>

                        <?php
                        $fieldValue = isset($value['button_text_color']) ? $value['button_text_color'] : "#ffffff";
                        $fieldValue = $this->validate_color($fieldValue, "#ffffff");
                        ?>
                        <div class="chaty-setting-col flex items-center space-x-3">
                            <label class="font-primary text-base text-cht-gray-150 sm:w-44" for="button_text_color_for_<?php echo esc_attr($social['slug']); ?>"><?php esc_html_e("Text color", "chaty") ?></label>
                            <div>
                                <input id="button_text_color_for_<?php echo esc_attr($social['slug']); ?>" class="chaty-color-field button-color" type="text" name="cht_social_<?php echo esc_attr($social['slug']); ?>[button_text_color]" value="<?php echo esc_attr($fieldValue); ?>">
                            </div>
                        </div>

                        <?php
                        $fieldValue = isset($value['button_bg_color']) ? $value['button_bg_color'] : "#A886CD";
                        $fieldValue = $this->validate_color($fieldValue, "#A886CD");
                        ?>
                        <div class="chaty-setting-col flex items-center space-x-3">
                            <label class="font-primary text-base text-cht-gray-150 sm:w-44" for="button_bg_color_for_<?php echo esc_attr($social['slug']); ?>"><?php esc_html_e("Background color", "chaty") ?></label>
                            <div>
                                <input id="button_bg_color_for_<?php echo esc_attr($social['slug']); ?>" class="chaty-color-field button-color" type="text" name="cht_social_<?php echo esc_attr($social['slug']); ?>[button_bg_color]" value="<?php echo esc_attr($fieldValue); ?>">
                            </div>
                        </div>

                        <?php $fieldValue = isset($value['button_text']) ? $value['button_text'] : esc_html__("Chat", "chaty") ?>
                        <div class="chaty-setting-col sm:flex sm:items-center sm:space-x-3">
                            <label class="font-primary text-base text-cht-gray-150 sm:w-44" for="button_text_for_<?php echo esc_attr($social['slug']); ?>"><?php esc_html_e("Button text", "chaty") ?></label>
                            <div>
                                <input class="chaty-input-text" id="button_text_for_<?php echo esc_attr($social['slug']); ?>" type="text" name="cht_social_<?php echo esc_attr($social['slug']); ?>[button_text]" value="<?php echo esc_attr($fieldValue); ?>">
                            </div>
                        </div>
                        <?php $fieldValue = isset($value['thanks_message']) ? $value['thanks_message'] : esc_html__("Your message was sent successfully", "chaty") ?>
                        <div class="chaty-setting-col sm:flex sm:items-center sm:space-x-3">
                            <label class="font-primary text-base text-cht-gray-150 sm:w-44" for="thanks_message_for_<?php echo esc_attr($social['slug']); ?>"><?php esc_html_e("Thank you message", "chaty") ?></label>
                            <div>
                                <input class="chaty-input-text" id="thanks_message_for_<?php echo esc_attr($social['slug']); ?>" type="text" name="cht_social_<?php echo esc_attr($social['slug']); ?>[thanks_message]" value="<?php echo esc_attr($fieldValue); ?>">
                            </div>
                        </div>

                        <?php $fieldValue = isset($value['redirect_action']) ? $value['redirect_action'] : "no" ?>
                        <div class="chaty-setting-col">
                            <input type="hidden" name="cht_social_<?php echo esc_attr($social['slug']); ?>[redirect_action]" value="no">
                            <label class="chaty-switch flex items-center font-primary text-cht-gray-150 text-base" for="redirect_action_<?php echo esc_attr($social['slug']); ?>">
                                <input type="checkbox" class="chaty-redirect-setting" name="cht_social_<?php echo esc_attr($social['slug']); ?>[redirect_action]" id="redirect_action_<?php echo esc_attr($social['slug']); ?>" value="yes" <?php checked($fieldValue, "yes") ?>>
                                <div class="chaty-slider round"></div>
                                <?php esc_html_e("Redirect visitors after submission", "chaty") ?>
                            </label>
                        </div>
                        <div class="redirect_action-settings <?php echo ($fieldValue == "yes") ? "active" : "" ?>">
                            <?php $fieldValue = isset($value['redirect_link']) ? $value['redirect_link'] : "" ?>
                            <div class="chaty-setting-col sm:flex sm:items-center sm:space-x-3 my-2">
                                <label class="font-primary text-base text-cht-gray-150 sm:w-44" for="redirect_link_for_<?php echo esc_attr($social['slug']); ?>"><?php esc_html_e("Redirect link", "chaty") ?></label>
                                <div>
                                    <input id="redirect_link_for_<?php echo esc_attr($social['slug']); ?>" placeholder="<?php echo esc_url(site_url("/")) ?>" type="text" name="cht_social_<?php echo esc_attr($social['slug']); ?>[redirect_link]" value="<?php echo esc_attr($fieldValue); ?>">
                                </div>
                            </div>
                            <?php $fieldValue = isset($value['link_in_new_tab']) ? $value['link_in_new_tab'] : "no" ?>
                            <div class="chaty-setting-col sm:flex sm:items-center">
                                <input type="hidden" name="cht_social_<?php echo esc_attr($social['slug']); ?>[link_in_new_tab]" value="no">
                                <label class="chaty-switch font-primary text-cht-gray-150" for="link_in_new_tab_<?php echo esc_attr($social['slug']); ?>">
                                    <input type="checkbox" class="chaty-field-setting" name="cht_social_<?php echo esc_attr($social['slug']); ?>[link_in_new_tab]" id="link_in_new_tab_<?php echo esc_attr($social['slug']); ?>" value="yes" <?php checked($fieldValue, "yes") ?>>
                                    <div class="chaty-slider round"></div>
                                    <?php esc_html_e("Open in a new tab", "chaty") ?>
                                </label>
                            </div>
                        </div>

                        <?php $fieldValue = isset($value['close_form_after']) ? $value['close_form_after'] : "no" ?>
                        <div class="chaty-setting-col">
                            <input type="hidden" name="cht_social_<?php echo esc_attr($social['slug']); ?>[close_form_after]" value="no">
                            <label class="chaty-switch flex items-center font-primary text-cht-gray-150 text-base" for="close_form_after_<?php echo esc_attr($social['slug']); ?>">
                                <input type="checkbox" class="chaty-close_form_after-setting" name="cht_social_<?php echo esc_attr($social['slug']); ?>[close_form_after]" id="close_form_after_<?php echo esc_attr($social['slug']); ?>" value="yes" <?php checked($fieldValue, "yes") ?>>
                                <div class="chaty-slider round"></div>
                                <?php esc_html_e("Close form automatically after submission", "chaty") ?>
                                <span class="icon label-tooltip inline-message hidden sm:inline-block" data-label="<?php esc_html_e("Close the form automatically after a few seconds based on your choice", "chaty") ?>">
                                    <span>
                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" fill="none">
                                            <path d="M8.00004 14.6654C11.6819 14.6654 14.6667 11.6806 14.6667 7.9987C14.6667 4.3168 11.6819 1.33203 8.00004 1.33203C4.31814 1.33203 1.33337 4.3168 1.33337 7.9987C1.33337 11.6806 4.31814 14.6654 8.00004 14.6654Z" stroke="#72777c" stroke-width="1.33" stroke-linecap="round" stroke-linejoin="round"></path>
                                            <path d="M8 10.6667V8" stroke="#72777c" stroke-width="1.33" stroke-linecap="round" stroke-linejoin="round"></path>
                                            <path d="M8 5.33203H8.00667" stroke="#72777c" stroke-width="1.33" stroke-linecap="round" stroke-linejoin="round"></path>
                                        </svg>
                                    </span>
                                </span>
                            </label>
                        </div>
                        <div class="close_form_after-settings <?php echo ($fieldValue == "yes") ? "active" : "" ?>">
                            <?php $fieldValue = isset($value['close_form_after_seconds']) ? $value['close_form_after_seconds'] : "3" ?>
                            <div class="chaty-setting-col sm:flex sm:items-center sm:space-x-3">
                                <label class="font-primary text-base text-cht-gray-150 sm:w-44" for="close_form_after_seconds_<?php echo esc_attr($social['slug']); ?>"><?php esc_html_e("Close after(Seconds)", "chaty") ?></label>
                                <div>
                                    <input id="close_form_after_seconds_<?php echo esc_attr($social['slug']); ?>" type="number" min="0" name="cht_social_<?php echo esc_attr($social['slug']); ?>[close_form_after_seconds]" value="<?php echo esc_attr($fieldValue); ?>">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-field-setting-col my-3 no-margin">
                        <input type="hidden" value="no"name="cht_social_<?php echo esc_attr($social['slug']); ?>[send_leads_in_email]">
                        <input type="hidden" value="yes" name="cht_social_<?php echo esc_attr($social['slug']); ?>[save_leads_locally]">
                        <?php $fieldValue = isset($val['save_leads_locally']) ? $val['save_leads_locally'] : "yes" ?>
                        <div class="chaty-setting-col">
                            <label class="text-cht-gray-150 font-primary full-width chaty-switch text-base" for="save_leads_locally_<?php echo esc_attr($social['slug']); ?>">
                                <input type="checkbox" disabled id="save_leads_locally_<?php echo esc_attr($social['slug']); ?>" value="yes" name="cht_social_<?php echo esc_attr($social['slug']); ?>[save_leads_locally]" <?php checked($fieldValue, "yes") ?>>
                                <div class="chaty-slider round"></div>
                                Save leads to<a href="<?php echo esc_url(admin_url("admin.php?page=chaty-contact-form-feed")) ?>" target="_blank">this site</a>
                                <div class="html-tooltip hidden sm:inline-block top no-position">
                                    <span>
                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" fill="none">
                                            <path d="M8.00004 14.6654C11.6819 14.6654 14.6667 11.6806 14.6667 7.9987C14.6667 4.3168 11.6819 1.33203 8.00004 1.33203C4.31814 1.33203 1.33337 4.3168 1.33337 7.9987C1.33337 11.6806 4.31814 14.6654 8.00004 14.6654Z" stroke="#72777c" stroke-width="1.33" stroke-linecap="round" stroke-linejoin="round"></path>
                                            <path d="M8 10.6667V8" stroke="#72777c" stroke-width="1.33" stroke-linecap="round" stroke-linejoin="round"></path>
                                            <path d="M8 5.33203H8.00667" stroke="#72777c" stroke-width="1.33" stroke-linecap="round" stroke-linejoin="round"></path>
                                        </svg>
                                    </span>
                                    <span class="tooltip-text top"><?php echo sprintf(esc_html__("Your leads will be saved in your local database, you'll be able to find them %1\$s here", "chaty"), '<a target="_blank" href="'.esc_url(admin_url("admin.php?page=chaty-contact-form-feed")).'">'.esc_html__("here", "chaty").'</a>') ?></span>
                                </div>
                            </label>
                        </div>
                        <?php $fieldValue = isset($value['send_leads_in_email']) ? $value['send_leads_in_email'] : "no" ?>
                        <div class="chaty-setting-col">
                            <label class="text-cht-gray-150 font-primary full-width chaty-switch text-base no-padding pro-feature" for="save_leads_to_email_<?php echo esc_attr($social['slug']); ?>" class="email-setting full-width font-primary text-cht-gray-150">
                                <div class="chaty-switch">
                                    <input class="email-setting-field" disabled type="checkbox" id="save_leads_to_email_<?php echo esc_attr($social['slug']); ?>" value="yes" name="cht_social_<?php echo esc_attr($social['slug']); ?>[send_leads_in_email]">
                                    <div class="chaty-slider round"></div>
                                    <?php esc_html_e('Send leads to your email', 'chaty') ?>
                                </div>
                                <div>
                                    <span class="icon label-tooltip" data-label="<?php esc_html_e("Get your leads by email, whenever you get a new email you'll get an email notification", "chaty") ?>">
                                        <span class="mt-1.5 inline-block">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" fill="none">
                                                <path d="M8.00004 14.6654C11.6819 14.6654 14.6667 11.6806 14.6667 7.9987C14.6667 4.3168 11.6819 1.33203 8.00004 1.33203C4.31814 1.33203 1.33337 4.3168 1.33337 7.9987C1.33337 11.6806 4.31814 14.6654 8.00004 14.6654Z" stroke="#72777c" stroke-width="1.33" stroke-linecap="round" stroke-linejoin="round"></path>
                                                <path d="M8 10.6667V8" stroke="#72777c" stroke-width="1.33" stroke-linecap="round" stroke-linejoin="round"></path>
                                                <path d="M8 5.33203H8.00667" stroke="#72777c" stroke-width="1.33" stroke-linecap="round" stroke-linejoin="round"></path>
                                            </svg>
                                        </span>
                                    </span>
                                    <a class="opacity-0 px-5 py-1.5 group-hover:opacity-100 ml-4 pro-btn bg-cht-primary inline-block rounded-[6px] text-white hover:text-white" target="_blank" href="<?php echo esc_url($this->getUpgradeMenuItemUrl());?>">
                                        <?php esc_html_e('Upgrade to Pro', 'chaty');?>
                                    </a>
                                </div>
                            </label>
                        </div>
                        <div class="email-settings <?php echo ($fieldValue == "yes") ? "active" : "" ?>">
                            <div class="chaty-setting-col">
                                <label for="email_for_<?php echo esc_attr($social['slug']); ?>"><?php esc_html_e("Email address", "chaty") ?></label>
                                <div>
                                    <?php $fieldValue = isset($value['email_address']) ? $value['email_address'] : "" ?>
                                    <input id="email_for_<?php echo esc_attr($social['slug']); ?>" type="text" name="cht_social_<?php echo esc_attr($social['slug']); ?>[email_address]" value="<?php echo esc_attr($fieldValue); ?>">
                                </div>
                            </div>
                            <div class="chaty-setting-col">
                                <label for="sender_name_for_<?php echo esc_attr($social['slug']); ?>"><?php esc_html_e("Sender's name", "chaty") ?></label>
                                <div>
                                    <?php $fieldValue = isset($value['sender_name']) ? $value['sender_name'] : "" ?>
                                    <input id="sender_name_for_<?php echo esc_attr($social['slug']); ?>" type="text" name="cht_social_<?php echo esc_attr($social['slug']); ?>[sender_name]" value="<?php echo esc_attr($fieldValue); ?>">
                                </div>
                            </div>
                            <div class="chaty-setting-col">
                                <label for="email_subject_for_<?php echo esc_attr($social['slug']); ?>"><?php esc_html_e("Email subject", "chaty") ?></label>
                                <div>
                                    <?php $fieldValue = isset($value['email_subject']) ? $value['email_subject'] : "New lead from Chaty - {name} - {date} {hour}" ?>
                                    <input id="email_subject_for_<?php echo esc_attr($social['slug']); ?>" type="text" name="cht_social_<?php echo esc_attr($social['slug']); ?>[email_subject]" value="<?php echo esc_attr($fieldValue); ?>">
                                    <div class="mail-merge-tags">
                                        <span>{name}</span><span>{phone}</span><span>{email}</span><span>{date}</span><span>{hour}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php $field_value = isset($value['enable_recaptcha']) ? $value['enable_recaptcha'] : "no" ?>
                        <input type="hidden" value="no" name="cht_social_<?php echo esc_attr($social['slug']); ?>[enable_recaptcha]" >
                        <div class="chaty-setting-col">
                            <label class="text-cht-gray-150 font-primary full-width chaty-switch text-base pro-feature" for="enable_recaptcha_<?php echo esc_attr($social['slug']); ?>" class="email-setting full-width font-primary text-cht-gray-150">
                                <div class="chaty-switch">
                                <input class="captcha-setting-field" type="checkbox" id="enable_recaptcha_<?php echo esc_attr($social['slug']); ?>" value="yes" name="cht_social_<?php echo esc_attr($social['slug']); ?>[enable_recaptcha]" <?php checked($field_value, "yes") ?> disabled>
                                    <div class="chaty-slider round"></div>
                                    <?php esc_html_e('Enable reCAPTCHA', 'chaty') ?>
                                    <span class="header-tooltip">
                                    <span class="header-tooltip-text text-center">
                                        <?php printf(esc_html__("Click %1\$s to add your website. (please make sure you select V3). After adding your website you'll get your site Key and secret key.", "chaty"), "<a target='_blank' href='https://www.google.com/recaptcha/admin/create'>".esc_html__("here", "chaty")."</a>") ?>
                                    </span>
                                    <span class="ml-1">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16" fill="none">
                                            <path d="M8.00004 14.6654C11.6819 14.6654 14.6667 11.6806 14.6667 7.9987C14.6667 4.3168 11.6819 1.33203 8.00004 1.33203C4.31814 1.33203 1.33337 4.3168 1.33337 7.9987C1.33337 11.6806 4.31814 14.6654 8.00004 14.6654Z" stroke="#72777c" stroke-width="1.33" stroke-linecap="round" stroke-linejoin="round"></path>
                                            <path d="M8 10.6667V8" stroke="#72777c" stroke-width="1.33" stroke-linecap="round" stroke-linejoin="round"></path>
                                            <path d="M8 5.33203H8.00667" stroke="#72777c" stroke-width="1.33" stroke-linecap="round" stroke-linejoin="round"></path>
                                        </svg>
                                    </span>
                                </span>
                                </div>
                                <div>
                                    <a class="opacity-0 px-5 py-1.5 group-hover:opacity-100 ml-4 pro-btn bg-cht-primary inline-block rounded-[6px] text-white hover:text-white" target="_blank" href="<?php echo esc_url($this->getUpgradeMenuItemUrl());?>">
                                        <?php esc_html_e('Upgrade to Pro', 'chaty');?>
                                    </a>
                                </div>
                            </label>
                        </div>
                        <?php $field_value = isset($value['capture_ip_address']) ? $value['capture_ip_address'] : "no" ?>
                        <input type="hidden" value="no" name="cht_social_<?php echo esc_attr($social['slug']); ?>[capture_ip_address]" >
                        <div class="chaty-setting-col">
                            <label for="capture_ip_address_<?php echo esc_attr($social['slug']); ?>" class="full-width chaty-switch text-base text-cht-gray-150 flex items-center group-custom pro-feature">
                                <input class="capture-ip-address-field" type="checkbox" id="capture_ip_address_<?php echo esc_attr($social['slug']); ?>" value="yes" name="cht_social_<?php echo esc_attr($social['slug']); ?>[capture_ip_address]" <?php checked($field_value, "yes") ?> disabled>
                                <div class="chaty-slider round"></div>
                                <?php esc_html_e("Capture IP address", "chaty") ?>
                                <span class="header-tooltip">
                                <span class="header-tooltip-text text-center">
                                    <?php printf(esc_html__("Capture the visitor's IP address when they submit the form.")) ?>
                                </span>
                                <span class="ml-1">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16" fill="none">
                                        <path d="M8.00004 14.6654C11.6819 14.6654 14.6667 11.6806 14.6667 7.9987C14.6667 4.3168 11.6819 1.33203 8.00004 1.33203C4.31814 1.33203 1.33337 4.3168 1.33337 7.9987C1.33337 11.6806 4.31814 14.6654 8.00004 14.6654Z" stroke="#72777c" stroke-width="1.33" stroke-linecap="round" stroke-linejoin="round"></path>
                                        <path d="M8 10.6667V8" stroke="#72777c" stroke-width="1.33" stroke-linecap="round" stroke-linejoin="round"></path>
                                        <path d="M8 5.33203H8.00667" stroke="#72777c" stroke-width="1.33" stroke-linecap="round" stroke-linejoin="round"></path>
                                    </svg>
                                </span>
                                </span>
                                <div>
                                    <a class="opacity-0 px-5 py-1.5 group-hover:opacity-100 ml-4 pro-btn bg-cht-primary inline-block rounded-[6px] text-white hover:text-white" target="_blank" href="<?php echo esc_url($this->getUpgradeMenuItemUrl());?>">
                                        <?php esc_html_e('Upgrade to Pro', 'chaty');?>
                                    </a>
                                </div>
                            </label>
                        </div>
                        <?php $field_value = isset($value['send_leads_mailchimp_mailpoet']) ? $value['send_leads_mailchimp_mailpoet'] : "no" ?>
                        <input type="hidden" value="no" name="cht_social_<?php echo esc_attr($social['slug']); ?>[send_leads_mailchimp_mailpoet]" >
                        <div class="chaty-setting-col">
                            <label class="text-cht-gray-150 font-primary full-width chaty-switch text-base pro-feature" for="send_leads_mailchimp_mailpoet<?php echo esc_attr($social['slug']); ?>" class="email-setting full-width font-primary text-cht-gray-150">
                                <div class="chaty-switch">
                                    <input class="" type="checkbox" id="send_leads_mailchimp_mailpoet<?php echo esc_attr($social['slug']); ?>" value="yes" name="cht_social_<?php echo esc_attr($social['slug']); ?>[send_leads_mailchimp_mailpoet]" <?php checked($field_value, "yes") ?> disabled>
                                    <div class="chaty-slider round"></div>
                                    <?php esc_html_e('Send leads to Mailchimp/Klaviyo', 'chaty') ?>
                                </div>
                                <div>
                                    <a class="opacity-0 px-5 py-1.5 group-hover:opacity-100 ml-4 pro-btn bg-cht-primary inline-block rounded-[6px] text-white hover:text-white" target="_blank" href="<?php echo esc_url($this->getUpgradeMenuItemUrl());?>">
                                        <?php esc_html_e('Upgrade to Pro', 'chaty');?>
                                    </a>
                                </div>
                            </label>
                        </div>
                    </div>
                </div>
            <?php } ?>
            <div class="Whatsapp-settings advanced-settings">
                <?php $preSetMessage = isset($value['pre_set_message']) ? $value['pre_set_message'] : ""; ?>
                <?php $embedded_window = isset($value['embedded_window']) ? $value['embedded_window'] : "no"; ?>
                <div class="chaty-setting-col sm:flex items-start space-y-2 sm:space-y-0 sm:space-x-3">
                    <label class="font-primary text-base text-cht-gray-150 sm:w-44 mt-2" style="flex: 0 0 175px;">
                        <?php esc_html_e("Pre Set Message", "chaty") ?>
                        <span class="icon label-tooltip inline-tooltip"data-label="<?php esc_html_e("Add your own pre-set message that's automatically added to the user's message. You can also use merge tags and add the URL or the title of the current visitor's page. E.g. you can add the current URL of a product to the message so you know which product the visitor is talking about when the visitor messages you", "chaty"); ?>">
                            <span>
                                <svg xmlns="http://www.w3.org/2000/svg" class="inline-block" width="20" height="27" viewBox="0 0 20 20" fill="none">
                                    <path d="M8.00004 14.6654C11.6819 14.6654 14.6667 11.6806 14.6667 7.9987C14.6667 4.3168 11.6819 1.33203 8.00004 1.33203C4.31814 1.33203 1.33337 4.3168 1.33337 7.9987C1.33337 11.6806 4.31814 14.6654 8.00004 14.6654Z" stroke="#72777c" stroke-width="1.33" stroke-linecap="round" stroke-linejoin="round"></path>
                                    <path d="M8 10.6667V8" stroke="#72777c" stroke-width="1.33" stroke-linecap="round" stroke-linejoin="round"></path>
                                    <path d="M8 5.33203H8.00667" stroke="#72777c" stroke-width="1.33" stroke-linecap="round" stroke-linejoin="round"></path>
                                </svg>
                            </span>
                        </span>
                    </label>
                    <div class="custom-input-tags">
                        <div class="pro-features">
                            <div class="pro-item">
                                <?php $preSetMessage = isset($value['pre_set_message']) ? $value['pre_set_message'] : ""; ?>
                                <input class="pre-set-message" disabled id="cht_social_message_<?php echo esc_attr($social['slug']); ?>" type="text" name="cht_social_<?php echo esc_attr($social['slug']); ?>[pre_set_message]" value="<?php echo esc_attr($preSetMessage) ?>">
                                <?php include "custom-tags.php" ?>
                            </div>
                            <div class="pro-button h-[44px]">
                                <a target="_blank" class="rounded-[6px]" href="<?php echo esc_url($this->getUpgradeMenuItemUrl());?>"><?php esc_html_e('Upgrade to Pro', 'chaty');?></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="SMS-settings advanced-settings">
                <?php $preSetMessage = isset($value['sms_pre_set_message']) ? $value['sms_pre_set_message'] : ""; ?>
                <div class="chaty-setting-col sm:flex items-start space-y-2 sm:space-y-0 sm:space-x-3">
                    <label class="font-primary text-base text-cht-gray-150 sm:w-44 mt-2" style="flex: 0 0 175px;">
                        <?php esc_html_e("Pre Set Message", "chaty") ?>
                        <span class="icon label-tooltip inline-tooltip" data-label="<?php esc_html_e("Add your own pre-set message that's automatically added to the user's message. You can also use merge tags and add the URL or the title of the current visitor's page. E.g. you can add the current URL of a product to the message so you know which product the visitor is talking about when the visitor messages you", "chaty"); ?>">
                            <span>
                                <svg xmlns="http://www.w3.org/2000/svg" class="inline-block" width="20" height="27" viewBox="0 0 20 20" fill="none">
                                    <path d="M8.00004 14.6654C11.6819 14.6654 14.6667 11.6806 14.6667 7.9987C14.6667 4.3168 11.6819 1.33203 8.00004 1.33203C4.31814 1.33203 1.33337 4.3168 1.33337 7.9987C1.33337 11.6806 4.31814 14.6654 8.00004 14.6654Z" stroke="#72777c" stroke-width="1.33" stroke-linecap="round" stroke-linejoin="round"></path>
                                    <path d="M8 10.6667V8" stroke="#72777c" stroke-width="1.33" stroke-linecap="round" stroke-linejoin="round"></path>
                                    <path d="M8 5.33203H8.00667" stroke="#72777c" stroke-width="1.33" stroke-linecap="round" stroke-linejoin="round"></path>
                                </svg>
                            </span>
                        </span>
                    </label>
                    <div class="custom-input-tags">
                        <div class="pro-features">
                            <div class="pro-item">
                                <?php $preSetMessage = isset($value['sms_pre_set_message']) ? $value['sms_pre_set_message'] : ""; ?>
                                <input class="pre-set-message" disabled id="sms_pre_set_message_<?php echo esc_attr($social['slug']); ?>" type="text" name="cht_social_<?php echo esc_attr($social['slug']); ?>[sms_pre_set_message]" value="<?php echo esc_attr($preSetMessage) ?>">
                                <?php include "custom-tags.php" ?>
                            </div>
                            <div class="pro-button h-[44px]">
                                <a target="_blank" class="rounded-[6px]" href="<?php echo esc_url($this->getUpgradeMenuItemUrl());?>"><?php esc_html_e('Upgrade to Pro', 'chaty');?></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="Email-settings advanced-settings">
                <div class="chaty-setting-col sm:flex items-start sm:space-x-3">
                    <label class="font-primary text-base text-cht-gray-150 sm:w-44 space-x-2"><?php esc_html_e("Mail Subject", "chaty") ?>
                        <span class="icon label-tooltip inline-tooltip" data-label="<?php esc_html_e("Add your own pre-set message that's automatically added to the user's message. You can also use merge tags and add the URL or the title of the current visitor's page. E.g. you can add the current URL of a product to the message so you know which product the visitor is talking about when the visitor messages you", "chaty"); ?>">
                            <span>
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" fill="none">
                                    <path d="M8.00004 14.6654C11.6819 14.6654 14.6667 11.6806 14.6667 7.9987C14.6667 4.3168 11.6819 1.33203 8.00004 1.33203C4.31814 1.33203 1.33337 4.3168 1.33337 7.9987C1.33337 11.6806 4.31814 14.6654 8.00004 14.6654Z" stroke="#72777c" stroke-width="1.33" stroke-linecap="round" stroke-linejoin="round">
                                    </path>
                                    <path d="M8 10.6667V8" stroke="#72777c" stroke-width="1.33" stroke-linecap="round" stroke-linejoin="round"></path>
                                    <path d="M8 5.33203H8.00667" stroke="#72777c" stroke-width="1.33" stroke-linecap="round" stroke-linejoin="round"></path>
                                </svg>
                            </span>
                        </span>
                    </label>
                    <div>
                        <div class="pro-features">
                            <div class="pro-item">
                                <input disabled id="cht_social_message_<?php echo esc_attr($social['slug']); ?>" type="text" name="" value="">
                                <span class="supported-tags mt-2"><span class="icon label-tooltip support-tooltip" data-label="<?php esc_html_e("{title} tag grabs the page title of the webpage","chaty") ?>">{title}</span> and <span class="icon label-tooltip support-tooltip" data-label="<?php esc_html_e("{url} tag grabs the URL of the page","chaty") ?>">{url}</span> <?php esc_html_e("tags are supported", "chaty") ?></span>
                            </div>
                            <div class="pro-button">
                                <a target="_blank" href="<?php echo esc_url($this->getUpgradeMenuItemUrl());?>"><?php esc_html_e('Upgrade to Pro', 'chaty');?></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="WeChat-settings advanced-settings">
                <div class="clear clearfix"></div>
                <div class="pro-feature-title"><?php esc_html_e("Pro Features", "chaty"); ?> 🚀</div>
                <div class="pro-features d-block">
                    <div class="pro-item">
                        <div class="sm:flex sm:items-center sm:space-x-3 mt-4">
                            <?php $wechat_header = isset($value['wechat_header']) ? $value['wechat_header'] : esc_html__("WeChat ID","chaty") ?>
                            <div class="chaty-setting-col inline-options">
                                <label class="font-primary text-base text-cht-gray-150 sm:w-44">
                                    <?php esc_html_e("Heading", "chaty") ?>
                                </label>
                                <div>
                                    <input type="text" disabled class="qr-header-title" id="<?php echo esc_attr($social['slug']); ?>_header_title" name="cht_social_<?php echo esc_attr($social['slug']); ?>[wechat_header]" value="<?php echo esc_attr($wechat_header) ?>">
                                </div>
                            </div>
                            <div class="chaty-setting-col inline-options">
                                <?php
                                $wechat_header_color = isset($value['wechat_header_color']) ? $value['wechat_header_color'] : "#A886CD";
                                $wechat_header_color = $this->validate_color($wechat_header_color, "#A886CD");
                                ?>
                                <label class="font-primary text-base text-cht-gray-150 sm:w-44">
                                    <?php esc_html_e("Header Background", "chaty") ?>
                                </label>
                                <div>
                                    <input type="text" disabled id="<?php echo esc_attr($social['slug']); ?>_header_color" name="cht_social_<?php echo esc_attr($social['slug']); ?>[wechat_header_color]" class="chaty-color-field button-color wechat-header-color" value="<?php echo esc_attr($wechat_header_color) ?>" />
                                </div>
                            </div>
                        </div>
                        <div class="sm:flex sm:items-center sm:space-x-3 mt-4">
                            <?php $wechat_qr_code_title = isset($value['wechat_qr_code_title']) ? $value['wechat_qr_code_title'] : esc_html__("Scan QR Code","chaty") ?>
                            <div class="chaty-setting-col inline-options">
                                <label class="font-primary text-base text-cht-gray-150 sm:w-44">
                                    <?php esc_html_e("QR code heading", "chaty") ?>
                                </label>
                                <div>
                                    <input type="text" disabled class="qr-code-header-title" id="<?php echo esc_attr($social['slug']); ?>_qr_code_title" name="cht_social_<?php echo esc_attr($social['slug']); ?>[wechat_qr_code_title]" value="<?php echo esc_attr($wechat_qr_code_title) ?>">
                                </div>
                            </div>
                        </div>
                        <div class="sm:flex sm:items-center sm:space-x-3 mt-4">
                            <div class="chaty-setting-col ">
                                <label class="font-primary text-base text-cht-gray-150 sm:w-44"><?php esc_html_e("Upload QR Code", "chaty") ?></label>
                                <div class="relative qr-code-setting <?php echo esc_attr($social['slug']); ?>-qr-code-setting <?php echo esc_attr($status?"active":"") ?>">
                                    <a class="img-upload-btn" href="javascript:;" >
                                        <?php esc_html_e("Upload", "chaty") ?>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="pro-link">
                        <div class="pro-btn">
                            <a target="_blank" href="<?php echo esc_url($this->getUpgradeMenuItemUrl()); ?>">Upgrade to Pro</a>
                        </div>
                    </div>
                </div>
            </div>
            <div
                class="Link-settings Custom_Link-settings Custom_Link_3-settings Custom_Link_4-settings Custom_Link_5-settings advanced-settings">
                <?php $isChecked = (!isset($value['new_window']) || $value['new_window'] == 1) ? 1 : 0; ?>
                <div class="clear clearfix"></div>
                <div class="chaty-setting-col flex items-center space-x-3">
                    <label class="font-primary text-cht-gray-150 sm:w-44"><?php esc_html_e("Open In a New Tab", "chaty") ?></label>
                    <div>
                        <input type="hidden" name="cht_social_<?php echo esc_attr($social['slug']); ?>[new_window]" value="0">
                        <label class="channels__view" for="cht_social_window_<?php echo esc_attr($social['slug']); ?>">
                            <input id="cht_social_window_<?php echo esc_attr($social['slug']); ?>" type="checkbox" class="channels__view-check" name="cht_social_<?php echo esc_attr($social['slug']); ?>[new_window]" value="1" <?php checked($isChecked,1) ?>>
                            <span class="chaty-slider round"></span>
                        </label>
                    </div>
                </div>
            </div>
            <div class="Linkedin-settings advanced-settings">
                <?php $isChecked = "personal"; ?>
                <!-- Advance setting for Custom Link -->
                <div class="chaty-setting-col sm:flex sm:items-center sm:space-x-3">
                    <label class="font-primary text-base text-cht-gray-150 sm:w-44"><?php esc_html_e("LinkedIn", "chaty") ?></label>
                    <div class="cta-action-radio tab-tab-select bg-cht-gray-50 inline-block rounded-md p-1">
                        <div class="i-block">
                            <label class="custom-control custom-radio">
                                <input type="radio" name="cht_social_<?php echo esc_attr($social['slug']); ?>[link_type]" class="custom-control-input" <?php checked($isChecked, "personal") ?> value="personal" />
                                <span class="custom-control-label px-2 py-1 inline-block text-cht-gray-150 rounded-[3px]"><?php esc_html_e("Personal", "chaty") ?></span>
                            </label>
                        </div>
                        <div class="i-block">
                            <label class="custom-control custom-radio">
                                <input type="radio" name="cht_social_<?php echo esc_attr($social['slug']); ?>[link_type]" class="custom-control-input" <?php checked($isChecked, "company") ?> value="company" />
                                <span class="custom-control-label px-2 py-1 inline-block text-cht-gray-150 rounded-[3px]"><?php esc_html_e("Company", "chaty") ?></span>
                            </label>
                        </div>
                    </div>
                </div>
            </div>

            <?php $useWhatsappWeb = isset($value['use_whatsapp_web']) ? $value['use_whatsapp_web'] : "yes"; ?>
            <?php $embedded_window = isset($value['embedded_window']) ? $value['embedded_window'] : "no"; ?>
            <div class="Whatsapp-settings advanced-settings">
                <div class="chaty-setting-col sm:flex items-start space-y-2 sm:space-y-0 sm:space-x-3">
                    <label class="font-primary text-base text-cht-gray-150 sm:w-44 mt-2"><?php esc_html_e("Whatsapp Web", "chaty") ?>
                    <span class="header-tooltip">
                        <span class="header-tooltip-text text-center"><?php esc_html_e("If unchecked, visitors will be redirected to chat with you via the WhatsApp desktop app. Please note if they don't have it installed, they'll be redirected to WhatsApp Web", 'chaty');?></span>
                        <span class="ml-1">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16" fill="none">
                                <path d="M8.00004 14.6654C11.6819 14.6654 14.6667 11.6806 14.6667 7.9987C14.6667 4.3168 11.6819 1.33203 8.00004 1.33203C4.31814 1.33203 1.33337 4.3168 1.33337 7.9987C1.33337 11.6806 4.31814 14.6654 8.00004 14.6654Z" stroke="#72777c" stroke-width="1.33" stroke-linecap="round" stroke-linejoin="round"></path>
                                <path d="M8 10.6667V8" stroke="#72777c" stroke-width="1.33" stroke-linecap="round" stroke-linejoin="round"></path>
                                <path d="M8 5.33203H8.00667" stroke="#72777c" stroke-width="1.33" stroke-linecap="round" stroke-linejoin="round"></path>
                            </svg>
                        </span>
                    </span>
                    </label>
                    <input type="hidden" name="cht_social_<?php echo esc_attr($social['slug']); ?>[use_whatsapp_web]" value="no" />
                    <div>
                        <div class="checkbox">
                            <label for="cht_social_<?php echo esc_attr($social['slug']); ?>_use_whatsapp_web" class="chaty-checkbox font-primary text-cht-gray-150">
                                <input class="sr-only" type="checkbox" id="cht_social_<?php echo esc_attr($social['slug']); ?>_use_whatsapp_web" name="cht_social_<?php echo esc_attr($social['slug']); ?>[use_whatsapp_web]" value="yes" <?php echo checked($useWhatsappWeb, "yes") ?> />
                                <span></span>
                                <?php esc_html_e("Use Whatsapp Web directly on desktop", "chaty") ?>
                            </label>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- advance setting fields: end -->

        <!-- remove social media setting button: start -->
        <button type="button" class="btn-cancel absolute right-2 top-2 text-cht-gray-150 hover:text-red-500" data-social="<?php echo esc_attr($social['slug']); ?>">
            <svg class="pointer-events-none" data-v-1cf7b632="" width="18" height="18" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg" svg-inline="" focusable="false" tabindex="-1">
                <path data-v-1cf7b632="" d="M2 4h12M5.333 4V2.667a1.333 1.333 0 011.334-1.334h2.666a1.333 1.333 0 011.334 1.334V4m2 0v9.333a1.334 1.334 0 01-1.334 1.334H4.667a1.334 1.334 0 01-1.334-1.334V4h9.334z" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"></path>
            </svg>
        </button>
        <!-- remove social media setting button: end -->

    </div>
    <!-- channel default settings end -->
</li>
<!-- Social media setting box: end -->


<div class="test-popup" id="Whatsapp_popup" data-label="Whatsapp">
    <div class="test-popup-bg"></div>
    <div class="test-popup-content">
        <button class="test-popup-close-btn" type="button">
            <span class="svg-icon">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512"><path d="M310.6 150.6c12.5-12.5 12.5-32.8 0-45.3s-32.8-12.5-45.3 0L160 210.7 54.6 105.4c-12.5-12.5-32.8-12.5-45.3 0s-12.5 32.8 0 45.3L114.7 256 9.4 361.4c-12.5 12.5-12.5 32.8 0 45.3s32.8 12.5 45.3 0L160 301.3 265.4 406.6c12.5 12.5 32.8 12.5 45.3 0s12.5-32.8 0-45.3L205.3 256 310.6 150.6z"></path></svg>
            </span>
        </button>
        <div class="test-popup-header">
            <span class="divider"></span>
            <span class="title"><?php esc_html_e('WhatsApp test', 'chaty') ?></span>
        </div>
        <div class="test-popup-body">
            <div class="title"><?php esc_html_e('Did we redirect you to the correct Whatsapp link for this number?', 'chaty') ?></div>
            <div class="phone-value"><?php esc_html_e('+91-7485963214', 'chaty') ?></div>
            <div class="popup-btn">
                <button type="button" class="edit-number"><?php esc_html_e('Edit Number', 'chaty') ?></button>
                <button type="button" class="save-btn"><?php esc_html_e('Yes, Correct', 'chaty') ?></button>
            </div>
            <div class="contact-link"><?php esc_html_e('Having trouble? Contact ', 'chaty') ?><a href="javascript:;"><?php esc_html_e('Support', 'chaty') ?></a></div>
        </div>
    </div>
</div>

<div class="test-popup" id="Facebook_Messenger_popup" data-label="Facebook_Messenger">
    <div class="test-popup-bg"></div>
    <div class="test-popup-content">
        <button class="test-popup-close-btn" type="button">
            <span class="svg-icon">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512"><path d="M310.6 150.6c12.5-12.5 12.5-32.8 0-45.3s-32.8-12.5-45.3 0L160 210.7 54.6 105.4c-12.5-12.5-32.8-12.5-45.3 0s-12.5 32.8 0 45.3L114.7 256 9.4 361.4c-12.5 12.5-12.5 32.8 0 45.3s32.8 12.5 45.3 0L160 301.3 265.4 406.6c12.5 12.5 32.8 12.5 45.3 0s12.5-32.8 0-45.3L205.3 256 310.6 150.6z"></path></svg>
            </span>
        </button>
        <div class="test-popup-header">
            <span class="divider"></span>
            <span class="title"><?php esc_html_e('Facebook Messenger test', 'chaty') ?></span>
        </div>
        <div class="test-popup-body">
            <div class="title"><?php esc_html_e('Did we redirect you to the correct Facebook Messenger link?', 'chaty') ?></div>
            <div class="phone-value"><?php esc_html_e('m.me/coca-cola', 'chaty') ?></div>
            <div class="popup-btn">
                <button type="button" class="edit-number"><?php esc_html_e('Edit Link', 'chaty') ?></button>
                <button type="button" class="save-btn"><?php esc_html_e('Yes, Correct', 'chaty') ?></button>
            </div>
            <div class="contact-link"><?php esc_html_e('Having trouble? Contact ', 'chaty') ?><a href="javascript:;"><?php esc_html_e('Support', 'chaty') ?></a></div>
        </div>
    </div>
</div>
