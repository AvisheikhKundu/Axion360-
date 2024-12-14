<?php
$value = get_option('cht_social_'.$social['slug']);
$imageUrl = CHT_PLUGIN_URL."admin/assets/images/chaty-default.png";
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

$is_pro = false;
$disabled = "";
if(!$is_pro) {
    $disabled = "disabled";
}

if (!isset($value['value'])) {
    $value['value'] = "";
}

if (!isset($value['bg_color']) || empty($value['bg_color'])) {
    $value['bg_color'] = $social['color'];
    // Initialize background color value if not exists. 2.1.0 change
}

if($social['slug'] == "Twitter" && ($value['bg_color'] == "#1ab2e8" || $value['bg_color'] == "rgb(26, 178, 232)") ) {
    $value['bg_color'] = "#000000";
}
$value['bg_color'] = $this->validate_color($value['bg_color'], $social['color']);

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

$is_agent = 0;
$color = "";
if (!empty($value['bg_color'])) {
    $color = "background-color: ".esc_attr($value['bg_color']);
    // set background color of icon if exists
}

$value['value'] = esc_attr(wp_unslash($value['value']));
$value['title'] = esc_attr(wp_unslash($value['title']));

$svg_icon = $social['svg'];

$help_title = "";
$help_text  = "";
$help_link  = "";

$channel_type = "";
$placeholder  = $social['example'];

if (empty($channel_type)) {
    $channel_type = $social['slug'];
}

// Check if the 'value' field is not empty
if (!empty($value['value'])) {
    // Extract the social slug for readability
    $socialSlug = $social['slug'];
}

$icons = [
    'icon_1' => '<svg width="32" height="32" viewBox="0 0 32 32" fill="none" xmlns="http://www.w3.org/2000/svg"> <path class="color-element" d="M32 16C32 7.16344 24.8366 0 16 0C7.16344 0 0 7.16344 0 16C0 24.8366 7.16344 32 16 32C24.8366 32 32 24.8366 32 16Z" fill="#0446DE" style="fill: rgb(4, 70, 222);"></path> <path fill-rule="evenodd" clip-rule="evenodd" d="M10.8163 6C8.70862 6 7 7.70862 7 9.81631V19.7642C7 21.8719 8.70862 23.5806 10.8163 23.5806H13.1193C13.6704 23.5806 14.1946 23.8188 14.557 24.2341L15.8723 25.7413C16.1733 26.0862 16.7095 26.0862 17.0105 25.7413L18.3258 24.2341C18.6882 23.8188 19.2124 23.5806 19.7635 23.5806H22.0665C24.1742 23.5806 25.8828 21.8719 25.8828 19.7642V9.81631C25.8828 7.70862 24.1742 6 22.0665 6H10.8163ZM13.3463 16.9519C12.8308 16.951 12.2872 16.9499 12.2872 17.3685C12.2872 19.0157 14.0506 21.1062 16.0638 21.1062H16.819C18.8323 21.1062 20.5956 19.0157 20.5956 17.3685C20.5956 16.9499 20.052 16.951 19.5365 16.9519C19.5118 16.952 19.4872 16.9521 19.4626 16.9521H13.4202C13.3957 16.9521 13.371 16.952 13.3463 16.9519Z" fill="white"></path> </svg>',
    'icon_2' => '<svg width="32" height="32" viewBox="0 0 32 32" fill="none" xmlns="http://www.w3.org/2000/svg"> <path class="color-element" d="M32 16C32 7.16344 24.8366 0 16 0C7.16344 0 0 7.16344 0 16C0 24.8366 7.16344 32 16 32C24.8366 32 32 24.8366 32 16Z" fill="#0446DE"/> <path fill-rule="evenodd" clip-rule="evenodd" d="M12.652 7H22.788C24.0225 7 25.0621 8.03959 24.9971 9.27411V20.9695C24.9971 21.2294 24.8671 21.4244 24.6723 21.5543H24.4773C24.2174 21.5543 24.1524 21.5543 24.0225 21.4243L22.788 20.1898V12.6528C22.788 10.7686 21.2935 9.27411 19.4093 9.27411H10.3778C10.3778 8.03959 11.4174 7 12.652 7ZM9.27411 10.3793H19.4101C20.6447 10.3793 21.6842 11.4189 21.6842 12.6534V24.4787C21.6842 24.7386 21.5543 24.9335 21.3593 25.0635H21.1645C20.9045 25.0635 20.8395 25.0635 20.7096 24.9335L17.4609 21.6848H9.27411C8.03959 21.6848 7 20.6452 7 19.4107V12.6534C7 11.4189 8.03959 10.3793 9.27411 10.3793Z" fill="white"/> </svg>',
    'icon_3' => '<svg width="32" height="32" viewBox="0 0 32 32" fill="none" xmlns="http://www.w3.org/2000/svg"><path class="color-element" d="M32 16C32 7.16344 24.8366 0 16 0C7.16344 0 0 7.16344 0 16C0 24.8366 7.16344 32 16 32C24.8366 32 32 24.8366 32 16Z" fill="#0446DE"/><path d="M14.3145 8H17.6855C19.5936 8 21.5018 8.76327 22.8374 10.1626C24.1731 11.4982 25 13.4064 25 15.3145C25 19.1308 22.0742 22.3109 18.2579 22.6289V25.4276C18.2579 25.6819 18.1308 25.8728 17.9399 26C17.8127 26 17.8127 26 17.6855 26C17.5583 26 17.3675 25.8728 17.2403 25.7456L14.3145 22.5654C12.4064 22.5654 10.4982 21.8021 9.16254 20.4028C7.82685 19.0035 7 17.159 7 15.2509C7 11.2438 10.3074 8 14.3145 8ZM20.4841 16.3957C21.1837 16.3957 21.6289 15.9505 21.6289 15.2509C21.6289 14.5513 21.1837 14.106 20.4841 14.106C19.7845 14.106 19.3392 14.5513 19.3392 15.2509C19.4028 15.9505 19.8481 16.3957 20.4841 16.3957ZM16.0318 16.3957C16.7314 16.3957 17.1766 15.9505 17.1766 15.2509C17.1766 14.5513 16.7314 14.106 16.0318 14.106C15.3322 14.106 14.8869 14.5513 14.8869 15.2509C14.8869 15.9505 15.3322 16.3957 16.0318 16.3957ZM11.5159 16.3957C12.2155 16.3957 12.6608 15.9505 12.6608 15.2509C12.6608 14.5513 12.2155 14.106 11.5159 14.106C10.8163 14.106 10.371 14.5513 10.371 15.2509C10.371 15.9505 10.8163 16.3957 11.5159 16.3957Z" fill="white"/></svg>',
    'icon_4' => '<svg width="32" height="32" viewBox="0 0 32 32" fill="none" xmlns="http://www.w3.org/2000/svg"> <path class="color-element" d="M32 16C32 7.16344 24.8366 0 16 0C7.16344 0 0 7.16344 0 16C0 24.8366 7.16344 32 16 32C24.8366 32 32 24.8366 32 16Z" fill="#0446DE"/> <path fill-rule="evenodd" clip-rule="evenodd" d="M22.6678 9H9.21263C7.95682 9 7 10.0166 7 11.2126V19.0465C7 20.3023 8.01662 21.2591 9.21263 21.2591H20.7542L23.9834 23.7996C24.103 23.9192 24.2226 23.9192 24.4019 23.9192H24.6412C24.8804 23.7996 25 23.5604 25 23.381V11.2126C24.8804 10.0166 23.8638 9 22.6678 9ZM20.6208 15.2695C20.8241 15.4601 20.8344 15.7793 20.6438 15.9827C17.7482 19.0713 13.898 18.4179 12.2984 17.714C12.0433 17.6018 11.9275 17.304 12.0397 17.049C12.152 16.7939 12.4498 16.6781 12.7048 16.7903C14.0952 17.4021 17.4211 17.9447 19.9076 15.2925C20.0982 15.0892 20.4175 15.0789 20.6208 15.2695ZM22.3928 15.536C22.3588 15.1276 22.2225 14.5057 21.8355 14.0218C21.6355 13.7718 21.3642 13.5541 21.0078 13.4345C20.652 13.315 20.2472 13.3052 19.7988 13.4129C19.5278 13.4779 19.3609 13.7502 19.4259 14.0212C19.4909 14.2922 19.7633 14.4591 20.0343 14.3941C20.3334 14.3223 20.5415 14.3425 20.6866 14.3912C20.831 14.4396 20.9485 14.5285 21.0475 14.6522C21.2584 14.9159 21.3614 15.3106 21.3872 15.6198C21.4103 15.8975 21.6542 16.1038 21.9319 16.0808C22.2096 16.0576 22.4159 15.8137 22.3928 15.536Z" fill="white"/> </svg>'
];

$positions = [
    'above-chaty' => esc_html__("An icon above Chaty’s icon", "chaty"),
    'inside-chaty' => esc_html__("Show it as one of Chaty’s channel", "chaty"),
    'outside-chaty' => esc_html__("Place manually from the Chatway settings", "chaty"),
];

if(isset($_REQUEST['position']) && isset($positions[$_REQUEST['position']])) {
    $value['chatway_position'] = esc_attr($_REQUEST['position']);
} else {
    $addChatway = is_plugin_active('chatway-live-chat/chatway.php') ? true : false;
    $isChatwayAdded = get_option('cht_is_chatway_added', false);
    if ($addChatway && !$isChatwayAdded) {
        $value['chatway_position'] = 'outside-chaty';
    }
}

if(isset($_GET['chaty_action']) && $_GET['chaty_action'] == 'add_chatway') {
    if(isset($_REQUEST['chatway_position']) && isset($positions[$_REQUEST['chatway_position']])) {
        $value['chatway_position'] = esc_attr($_REQUEST['chatway_position']);
    }
}

$chtPosition = isset($value['chatway_position'])?$value['chatway_position']:'above-chaty';
if(!isset($positions[$chtPosition])) {
    $chtPosition = 'above-chaty';
}

$imgClass = '';
$imageId = $value['image_id'];
if (!empty($value['fa_icon'])) {
    $imgClass = "icon-active";
    $value['chatway_icon'] = 'custom-image';
} else if(!empty($imageId)) {
    $imageUrl = wp_get_attachment_image_src($imageId, "full")[0];
    // get custom image URL if exists
    if ($imageUrl == "") {
        $value['chatway_icon'] = 'icon_1';
        $imageUrl = CHT_PLUGIN_URL."admin/assets/images/chaty-default.png";
        // Initialize with default image if custom image is not exists
    } else {
        $imgClass = "img-active";
    }
}

$chtIcon = isset($value['chatway_icon'])?$value['chatway_icon']:'icon_1';
if($chtIcon != 'custom-image' && !isset($icons[$chtIcon])) {
    $chtIcon = 'icon_1';
}
if($chtIcon != 'custom-image') {
    $svg_icon = $icons[$chtIcon];
}

?>
<!-- Social media setting box: start -->
<li data-id="<?php echo esc_attr($social['slug']) ?>" class="chaty-channel group <?php echo esc_attr($chtPosition) ?> <?php echo ($is_agent == 1) ? "has-agent-view" : "" ?>" data-channel="<?php echo esc_attr($channel_type) ?>" id="chaty-social-<?php echo esc_attr($social['slug']) ?>">
    <!-- channel visual fields start -->
    <div class="channels-selected__item free 1 available">
        <div class="chaty-default-settings flex gap-3">
            <div class="flex relative">
                <!-- draggable element -->
                <div class="move-icon mt-2 mr-1 transition duration-200 self-start opacity-0 group-hover:opacity-100">
                    <img src="<?php echo esc_url(CHT_PLUGIN_URL."admin/assets/images/move-icon.png") ?>">
                </div>

                <div class="icon chaty-icon icon-md active <?php echo esc_attr($imgClass) ?>" data-label="<?php esc_html_e("Chatway", "chaty"); ?>" id="chaty_image_<?php echo esc_attr($social['slug']) ?>">
                    <span style="" class="custom-chaty-image custom-image-<?php echo esc_attr($social['slug']) ?>" id="image_data_<?php echo esc_attr($social['slug']) ?>">
                        <img src="<?php echo esc_url($imageUrl) ?>" />
                    </span>
                    <span class="default-chaty-icon chaty-main-svg" >
                        <?php echo $svg_icon; ?>
                    </span>
                    <span class="facustom-icon flex items-center justify-center" style="background-color: <?php echo esc_attr($value['bg_color']) ?>">
                        <i class="<?php echo esc_attr($value['fa_icon']) ?>"></i>
                    </span>
                    <span onclick="remove_custom_image('<?php echo esc_attr($social['slug']) ?>')" class="remove-icon-img"></span>
                    <input class="fa-icon" type="hidden" name="cht_social_<?php echo esc_attr($social['slug']); ?>[fa_icon]" value="<?php echo esc_attr($value['fa_icon']) ?>">
                </div>
            </div>

            <div class="flex-auto space-y-1">
                <!-- Social Media input  -->
                <div class="channels__input-box mb-1">
                    <div class="chatway-channel-setting">
                        <div class="font-primary text-sm text-cht-gray-150 pb-1"><?php esc_attr_e('Chatway Live Chat', 'chaty'); ?></div>
                        <div class="inline-flex chatway-icons p-2.5 border-[#c6d7e3] border-solid border rounded-lg gap-4">
                            <?php foreach($icons as $key => $icon) { ?>
                                <label for="icon-<?php echo esc_attr($key) ?>">
                                    <span class="chatway-icon">
                                        <?php echo $icon ?>
                                    </span>
                                    <input class="sr-only" type="radio" <?php checked($chtIcon, $key) ?> value="<?php echo esc_attr($key) ?>" name="cht_social_<?php echo esc_attr($social['slug']); ?>[chatway_icon]" id="icon-<?php echo esc_attr($key) ?>" >
                                    <span class="radio-button"></span>
                                </label>
                            <?php } ?>
                            <input class="sr-only" type="radio" <?php checked($chtIcon, 'custom-image') ?> value="<?php echo esc_attr('custom-image') ?>" name="cht_social_<?php echo esc_attr($social['slug']); ?>[chatway_icon]" id="chatway-custom-image" />
                            <div class="chatway-custom-image <?php echo esc_attr($imgClass) ?> items-center inline-flex px-3 border-l border-solid border-[#c6d7e3]">
                                <div>
                                    <div class="font-primary text-sm text-cht-gray-150">
                                        Custom Image
                                    </div>
                                    <div class="custom-image-selector">
                                        <button id="chatway-custom-image" type="button" class="border border-solid border-[#c6d7e3] hover:bg-[#edf3f6] rounded px-2 py-0.5 mt-1">Select Image</button>
                                    </div>
                                    <div class="custom-image-preview items-center justify-center mt-1">
                                        <img class="w-8 h-8 object-cover rounded-full" src="<?php echo esc_url($imageUrl) ?>" />
                                        <button id="chatway-remove-custom-image" type="button" class="border border-solid border-[#c6d7e3] hover:bg-[#edf3f6] rounded px-2 py-0.5 ml-1.5">Remove</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="chatway-outside-setting hidden text-sm text-cht-gray-150">
                        <div class="chatway-mail-logo">
                            <svg width="50" height="50" viewBox="0 0 50 50" fill="none" xmlns="http://www.w3.org/2000/svg"> <rect x="1" y="1" width="48" height="48" rx="24" fill="#F3F9FF"></rect> <rect x="0.5" y="0.5" width="49" height="49" rx="24.5" stroke="#0446DE" stroke-opacity="0.2"></rect> <path d="M21.4323 40.8939L23.6881 36.9504L25.3229 39.5109C25.3229 39.5109 24.2844 39.3259 23.3384 39.7783C22.3925 40.2307 21.4323 40.8939 21.4323 40.8939Z" fill="#0038A5"></path> <path d="M19.8166 39.0134C19.6387 38.6078 19.2713 38.3169 18.8359 38.2365L13.4717 37.2461C12.2047 37.0122 11.2852 35.9074 11.2852 34.619V18.6157C11.2852 16.6455 12.2507 14.8004 13.8694 13.6772L17.332 11.2746C18.7 10.3254 20.3863 9.95195 22.0271 10.2349L34.3999 12.3681C36.9627 12.81 38.8351 15.0328 38.8351 17.6334V29.7568C38.8351 31.1918 38.2579 32.5665 37.2334 33.5713L32.8267 37.8933C31.1557 39.5321 28.7773 40.2293 26.4861 39.7519L23.9259 39.2186C23.4695 39.1235 22.9966 39.2732 22.6781 39.6136L21.6016 40.7642C21.2739 41.1144 20.695 41.0154 20.5023 40.5762L19.8166 39.0134Z" fill="#0446DE"></path> <path d="M17.1588 15.6094C14.4688 15.0628 11.9531 17.1187 11.9531 19.8637V33.5345C11.9531 34.8223 12.8719 35.9268 14.1382 36.1613L19.4524 37.1455C19.8966 37.2277 20.2691 37.529 20.4426 37.9461L20.9194 39.0931C21.0638 39.4406 21.5256 39.5106 21.7666 39.2216L22.6864 38.1184C22.9629 37.7867 23.3827 37.6092 23.8132 37.6418L28.0387 37.9619C30.1706 38.1235 31.9895 36.4372 31.9895 34.2991V20.8063C31.9895 19.536 31.0949 18.4413 29.85 18.1883L17.1588 15.6094Z" fill="#0038A5"></path> <path d="M16.8715 15.5962C15.2228 15.2764 13.6914 16.5393 13.6914 18.2188V32.2758C13.6914 33.6035 14.6665 34.7297 15.9805 34.9198L19.6259 35.447C20.0697 35.5112 20.4516 35.7938 20.6429 36.1994L21.1551 37.2859C21.2968 37.6342 21.7574 37.708 22.0008 37.4215L22.7724 36.5261C23.0703 36.1805 23.524 36.0108 23.9755 36.0761L28.9365 36.7937C30.5473 37.0266 31.9904 35.7773 31.9904 34.1497V20.7314C31.9904 19.452 31.0834 18.3523 29.8275 18.1087L16.8715 15.5962Z" fill="white"></path> <path d="M26.3149 29.7134L18.6034 28.3615C18.0423 28.2631 17.5858 28.7119 17.8029 29.2386C18.5079 30.9493 20.4771 33.6386 23.9658 32.7419C24.6842 32.5573 26.3091 31.6233 26.7669 30.3793C26.8813 30.0685 26.6411 29.7706 26.3149 29.7134Z" fill="#0446DE"></path> <ellipse cx="18.8634" cy="23.3408" rx="1.36726" ry="2.34388" transform="rotate(-4.90348 18.8634 23.3408)" fill="#0446DE"></ellipse> <ellipse cx="26.0899" cy="24.6755" rx="1.36726" ry="2.34388" transform="rotate(-4.90348 26.0899 24.6755)" fill="#0446DE"></ellipse> </svg>
                        </div>
                        <div class="chatway--message">
                            <b>Please note</b> all position-related changes to the Chatway widget should be made through the Chatway dashboard only
                        </div>
                    </div>
                    <div class="mt-4 flex items-center chatway--buttons">
                        <button id="change-chatway-position" class="chaty--button border font-primary text-sm text-cht-gray-150 border-solid border-[#c6d7e3] hover:bg-[#edf3f6] rounded px-2.5 py-1 mr-2.5" type="button">
                            <?php esc_attr_e("Change View Method", "chaty"); ?>
                            <svg data-v-7dab8964="" width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg"><path data-v-7dab8964="" d="M4 6L8 10L12 6" stroke="#83A1B7" stroke-width="1.33" stroke-linecap="round" stroke-linejoin="round"></path></svg>
                        </button>
                        <a target="_blank" href="<?php echo admin_url("plugin-install.php?s=chatway&tab=search&type=author") ?>" class="plugin-add--chatyway border font-primary text-sm text-[#3549fc] border-solid bg-white border-[#3549fc] hover:text-[#3549fc] focus:text-[#3549fc] rounded px-2.5 py-1 hover:bg-[#edf3f6]" type="button">
                            <svg class="h-5 w-auto mr-1" width="20" height="24" viewBox="0 0 20 24" fill="none" xmlns="http://www.w3.org/2000/svg" svg-inline="" role="presentation" focusable="false" tabindex="-1">
                                <path d="M7.367 22.703l1.638-2.863L10.19 21.7s-.754-.134-1.44.194c-.687.328-1.384.81-1.384.81z" fill="#0038A5"></path><path d="M6.193 21.342a.97.97 0 00-.712-.564l-3.894-.72A1.94 1.94 0 010 18.153V6.534c0-1.43.7-2.77 1.876-3.585L4.39 1.205A4.606 4.606 0 017.798.45l8.982 1.548A3.879 3.879 0 0120 5.821v8.8c0 1.043-.42 2.04-1.163 2.77l-3.199 3.138a5.091 5.091 0 01-4.603 1.349l-1.858-.387a.97.97 0 00-.906.286l-.782.836a.485.485 0 01-.798-.137l-.498-1.134z" fill="#0446DE"></path><path d="M4.264 4.353a3.152 3.152 0 00-3.78 3.089v9.924a1.94 1.94 0 001.587 1.907l3.858.714a.97.97 0 01.719.582l.346.832c.105.253.44.303.615.094l.668-.801a.97.97 0 01.818-.346l3.067.232a2.667 2.667 0 002.868-2.659V8.126a1.94 1.94 0 00-1.553-1.9L4.264 4.352z" fill="#0038A5"></path><path d="M4.055 4.34a1.94 1.94 0 00-2.309 1.905v10.204a1.94 1.94 0 001.662 1.92l2.646.383a.97.97 0 01.739.546l.371.789a.364.364 0 00.614.098l.56-.65a.97.97 0 01.874-.327l3.601.521a1.94 1.94 0 002.217-1.92V8.07a1.94 1.94 0 00-1.57-1.904L4.055 4.34z" fill="#fff"></path><path d="M10.91 14.59L5.31 13.61c-.407-.072-.738.254-.58.636.511 1.242 1.94 3.195 4.473 2.544.522-.134 1.701-.812 2.034-1.715.083-.226-.092-.442-.329-.484z" fill="#0446DE"></path>
                                <ellipse cx="5.503" cy="9.962" rx=".993" ry="1.702" transform="rotate(-4.903 5.503 9.962)" fill="#0446DE"></ellipse>
                                <ellipse cx="10.749" cy="10.931" rx=".993" ry="1.702" transform="rotate(-4.903 10.749 10.93)" fill="#0446DE"></ellipse>
                            </svg>
                            <?php esc_attr_e("Add Live Chat", "chaty"); ?>
                        </a>
                        <a target="_blank" href="<?php echo admin_url("admin.php?page=chatway") ?>" class="plugin-manage--chatway border font-primary text-sm text-cht-gray-150 border-solid border-[#c6d7e3] hover:bg-[#edf3f6] rounded px-2.5 py-1" type="button">
                            <?php esc_attr_e("Chatway Dashboard", "chaty"); ?>
                            <svg data-v-7dab8964="" width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg"><path data-v-7dab8964="" d="M12 8.66667V12.6667C12 13.0203 11.8595 13.3594 11.6095 13.6095C11.3594 13.8595 11.0203 14 10.6667 14H3.33333C2.97971 14 2.64057 13.8595 2.39052 13.6095C2.14048 13.3594 2 13.0203 2 12.6667V5.33333C2 4.97971 2.14048 4.64057 2.39052 4.39052C2.64057 4.14048 2.97971 4 3.33333 4H7.33333" stroke="#83A1B7" stroke-width="1.33" stroke-linecap="round" stroke-linejoin="round"></path><path data-v-7dab8964="" d="M10 2H14V6" stroke="#83A1B7" stroke-width="1.33" stroke-linecap="round" stroke-linejoin="round"></path><path data-v-7dab8964="" d="M6.66797 9.33333L14.0013 2" stroke="#83A1B7" stroke-width="1.33" stroke-linecap="round" stroke-linejoin="round"></path></svg>
                        </a>
                    </div>

                    <div id="chatway-position-settings" class="mt-4 border-solid border-[#c6d7e3] border rounded-lg hidden">
                        <div class="p-4 pb-2.5">
                            <div class="text-sm font-medium pb-3 text-cht-gray-150"><?php esc_attr_e('How would you like to show the Live Chat icon?', 'chaty'); ?></div>
                            <ul class="chatway-positions" data-value="<?php echo esc_attr($chtPosition); ?>">
                                <?php foreach($positions as $key=>$position) { ?>
                                    <li>
                                        <input class="sr-only" type="radio" id="position-<?php echo esc_attr($key) ?>" name="cht_social_<?php echo esc_attr($social['slug']); ?>[chatway_position]" value="<?php echo esc_attr($key) ?>" <?php checked($chtPosition, $key) ?> />
                                        <label class="text-base relative text-cht-gray-150 flex items-center gap-1.5 cursor-pointer" for="position-<?php echo esc_attr($key) ?>">
                                            <span class="inline-flex w-4 h-4 rounded-full border border-solid border-[#83a1b7]"></span>
                                            <?php echo esc_attr($position); ?>
                                        </label>
                                    </li>
                                <?php } ?>
                            </ul>
                        </div>
                    </div>
                    <div class="mt-4 pt-0 border-t border-solid border-[#eaeff2]"></div>
                </div>
            </div>
        </div>

        <div class="device-agent-settings mt-2">
            <div class="channels__device-box ml-2 chaty-setting-col sm:flex items-center space-y-2 sm:space-y-0 sm:space-x-3 mr-2">
                <label class="font-primary text-base text-cht-gray-150"><?php esc_html_e("Show on", "chaty") ?></label>
                <div class="device-box">
                    <?php
                    $slug       = esc_attr($this->del_space($social['slug']));
                    $slug       = str_replace(' ', '_', $slug);
                    $is_desktop = isset($value['is_desktop']) && $value['is_desktop'] == "checked" ? "checked" : '';
                    $is_mobile  = isset($value['is_mobile']) && $value['is_mobile'] == "checked" ? "checked" : '';
                    ?>
                    <!-- setting for desktop -->
                    <label class="device_view cursor-pointer" for="<?php echo esc_attr($slug); ?>Desktop">
                        <input type="checkbox" id="<?php echo esc_attr($slug); ?>Desktop" class="channels__view-check sr-only js-chanel-icon js-chanel-desktop" data-type="<?php echo esc_attr(str_replace(' ', '_', strtolower(esc_attr($this->del_space($social['slug']))))); ?>" name="cht_social_<?php echo esc_attr($social['slug']); ?>[is_desktop]" value="checked" data-gramm_editor="false" <?php echo esc_attr($is_desktop) ?> />
                        <span class="device-view-txt">
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
                        <input type="checkbox" id="<?php echo esc_attr($slug); ?>Mobile" class="channels__view-check sr-only js-chanel-icon js-chanel-mobile" data-type="<?php echo esc_attr(str_replace(' ', '_', strtolower(esc_attr($this->del_space($social['slug']))))); ?>" name="cht_social_<?php echo esc_attr($social['slug']); ?>[is_mobile]" value="checked" data-gramm_editor="false" <?php echo esc_attr($is_mobile) ?> >
                        <span class="device-view-txt">
                            <svg width="9" height="16" viewBox="0 0 9 16" fill="none" xmlns="http://www.w3.org/2000/svg">
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

            <!-- button for advance setting -->
            <div class="chaty-settings active" data-nonce="<?php echo esc_attr(wp_create_nonce($social['slug']."-settings")) ?>" id="<?php echo esc_attr($social['slug']); ?>-close-btn" onclick="toggle_chaty_setting('<?php echo esc_attr($social['slug']); ?>')">
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

        <!-- advance setting fields: start -->
        <div class="chaty-advance-settings not-is-pro" style="<?php echo (empty($close_class) && $social['slug'] == 'Contact_Us') ? "display:block" : ""; ?>">
            <div class="chaty-channel-setting space-y-4">
                <!-- Settings for custom icon and color -->
                <div class="chaty-setting-col sm:flex items-center space-y-2 sm:space-y-0 sm:space-x-3">
                    <label class="font-primary text-base text-cht-gray-150 sm:w-44"><?php esc_html_e("Icon Appearance", "chaty") ?></label>
                    <div class="flex items-center">
                        <!-- input for custom color -->
                        <input type="text" name="cht_social_<?php echo esc_attr($social['slug']); ?>[bg_color]" class="chaty-color-field chaty-bg-color" value="<?php echo esc_attr($value['bg_color']) ?>" />

                        <div class="flex items-center space-x-2">
                            <!-- button to upload custom image -->
                            <!-- hidden input value for image -->
                            <input id="cht_social_image_<?php echo esc_attr($social['slug']); ?>" type="hidden" name="cht_social_<?php echo esc_attr($social['slug']); ?>[image_id]" value="<?php echo esc_attr($imageId) ?>" />
                        </div>
                    </div>
                </div>
                <div class="clear clearfix"></div>

                <!-- Settings for custom title -->
                <div class="chaty-setting-col sm:flex sm:items-center sm:space-x-3">
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
                        <!-- input for custom title -->
                        <input type="text" class="chaty-title" name="cht_social_<?php echo esc_attr($social['slug']); ?>[title]" value="<?php echo esc_attr($value['title']) ?>">
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- channel visual fields end -->
    <!-- remove button start -->
    <button type="button" class="is-chatway btn-cancel absolute right-2 top-2 text-cht-gray-150 hover:text-red-500" data-social="<?php echo esc_attr($social['slug']); ?>">
        <svg class="pointer-events-none" data-v-1cf7b632="" width="18" height="18" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg" svg-inline="" focusable="false" tabindex="-1">
            <path d="M2 4h12M5.333 4V2.667a1.333 1.333 0 011.334-1.334h2.666a1.333 1.333 0 011.334 1.334V4m2 0v9.333a1.334 1.334 0 01-1.334 1.334H4.667a1.334 1.334 0 01-1.334-1.334V4h9.334z" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"></path>
        </svg>
    </button>
    <!-- remove button end -->
</li>
<!-- Social media setting box: end -->