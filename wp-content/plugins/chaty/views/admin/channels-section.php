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
?>
<div style="display: none">
    <?php
    $embeddedMessage = "";
    $settings        = [
        'media_buttons'    => false,
        'wpautop'          => false,
        'drag_drop_upload' => false,
        'textarea_name'    => 'chat_editor_channel',
        'textarea_rows'    => 4,
        'quicktags'        => false,
        'tinymce'          => [
            'toolbar1' => 'bold, italic, underline',
            'toolbar2' => '',
            'toolbar3' => '',
        ],
    ];
    wp_editor($embeddedMessage, "chat_editor_channel", $settings);
    ?>
</div>

<section class="section one chaty-setting-form" xmlns="http://www.w3.org/1999/html">
    <?php
    $chtWidgetTitle = get_option("cht_widget_title");
    $chtWidgetTitle = empty($chtWidgetTitle) ? "Widget-1" : $chtWidgetTitle;
    if (isset($_GET['widget_title']) && empty(!$_GET['widget_title'])) {
        $chtWidgetTitle = filter_input(INPUT_GET, 'widget_title');
    }

    ?>
    <div class="chaty-input mb-10">
        <label class="font-primary text-cht-gray-150 text-base block mb-3" for="cht_widget_title"><?php esc_html_e('Name', 'chaty'); ?></label>
        <input class="w-full sm:w-96" id="cht_widget_title" type="text" name="cht_widget_title" value="<?php echo esc_attr($chtWidgetTitle) ?>">
    </div>
    <?php
    // } ?>
    <?php
    $socialApp = $icons = get_option('cht_numb_slug', false);
    $addChatway = is_plugin_active('chatway-live-chat/chatway.php') ? true : false;
    $isChatwayAdded = get_option('cht_is_chatway_added', false);

    $socialApp = ($socialApp == false) ? "whatsapp" : $socialApp;
    $socialApp = trim($socialApp, ",");
    $socialApp = explode(",", $socialApp);
    $socialApp = array_unique($socialApp);
    if((isset($_GET['chaty_action']) && $_GET['chaty_action'] == 'add_chatway') || ($addChatway && !$isChatwayAdded)) {
        if(!in_array('Chatway', $socialApp)) {
            array_unshift($socialApp, "Chatway");
        }
    }
    $imageUrl  = plugin_dir_url("")."chaty/admin/assets/images/chaty-default.png";
    ?>
    <input type="hidden" id="default_image" value="<?php echo esc_url($imageUrl)  ?>" />
    <div class="channels-icons flex max-w-full flex-wrap" id="channel-list">
        <?php if ($this->socials) :
            foreach ($this->socials as $key => $social) :
                $value       = get_option('cht_social'.'_'.$social['slug']);
                $activeClass = '';
                foreach ($socialApp as $keySoc) :
                    if ($keySoc == $social['slug']) {
                        $activeClass = 'active';
                    }
                endforeach;
                $customClass = in_array($social['slug'], array("Link", "Custom_Link", "Custom_Link_3", "Custom_Link_4", "Custom_Link_5", "Custom_Link_6"))?"custom-link":"";
                ?>
                <div class="icon cursor-pointer icon-sm chat-channel-<?php echo esc_attr($social['slug']); ?> <?php echo esc_attr($activeClass) ?> <?php echo esc_attr($customClass) ?>" data-social="<?php echo esc_attr($social['slug']); ?>" data-label="<?php echo esc_attr($social['title']); ?>">
                    <span class="icon-box">
                        <?php echo $social['svg']; ?>
                    </span>
                    <span class="channel-title"><?php echo esc_html($social['title']); ?></span>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
    <div class="custom-channel-button flex items-center gap-2.5">
        <a href="#" class="custom-channel-link">
            <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg" svg-inline="" focusable="false" tabindex="-1"><path d="M15.833 1.75H4.167A2.417 2.417 0 001.75 4.167v11.666a2.417 2.417 0 002.417 2.417h11.666a2.417 2.417 0 002.417-2.417V4.167a2.417 2.417 0 00-2.417-2.417zM10 6.667v6.666" stroke="#83A1B7" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path><path d="M6.667 10h6.666" stroke="#83A1B7" stroke-width="1.67" stroke-linecap="round" stroke-linejoin="round"></path></svg>
            <?php esc_html_e('Custom Channel', 'chaty'); ?>
        </a>
        <a href="javascript:;" id="add-chatyway" class="add-chatyway-btn inline-flex font-primary items-center gap-0.5 py-1 px-2.5 border border-solid text-center justify-center !border-[#0446de] rounded-lg !text-[#0446de] hover:text-[#0446de] hover:bg-[#edf3f6]">
            <svg class="h-5 w-auto" width="20" height="24" viewBox="0 0 20 24" fill="none" xmlns="http://www.w3.org/2000/svg" svg-inline="" role="presentation" focusable="false" tabindex="-1"><path d="M7.367 22.703l1.638-2.863L10.19 21.7s-.754-.134-1.44.194c-.687.328-1.384.81-1.384.81z" fill="#0038A5"></path><path d="M6.193 21.342a.97.97 0 00-.712-.564l-3.894-.72A1.94 1.94 0 010 18.153V6.534c0-1.43.7-2.77 1.876-3.585L4.39 1.205A4.606 4.606 0 017.798.45l8.982 1.548A3.879 3.879 0 0120 5.821v8.8c0 1.043-.42 2.04-1.163 2.77l-3.199 3.138a5.091 5.091 0 01-4.603 1.349l-1.858-.387a.97.97 0 00-.906.286l-.782.836a.485.485 0 01-.798-.137l-.498-1.134z" fill="#0446DE"></path><path d="M4.264 4.353a3.152 3.152 0 00-3.78 3.089v9.924a1.94 1.94 0 001.587 1.907l3.858.714a.97.97 0 01.719.582l.346.832c.105.253.44.303.615.094l.668-.801a.97.97 0 01.818-.346l3.067.232a2.667 2.667 0 002.868-2.659V8.126a1.94 1.94 0 00-1.553-1.9L4.264 4.352z" fill="#0038A5"></path><path d="M4.055 4.34a1.94 1.94 0 00-2.309 1.905v10.204a1.94 1.94 0 001.662 1.92l2.646.383a.97.97 0 01.739.546l.371.789a.364.364 0 00.614.098l.56-.65a.97.97 0 01.874-.327l3.601.521a1.94 1.94 0 002.217-1.92V8.07a1.94 1.94 0 00-1.57-1.904L4.055 4.34z" fill="#fff"></path><path d="M10.91 14.59L5.31 13.61c-.407-.072-.738.254-.58.636.511 1.242 1.94 3.195 4.473 2.544.522-.134 1.701-.812 2.034-1.715.083-.226-.092-.442-.329-.484z" fill="#0446DE"></path><ellipse cx="5.503" cy="9.962" rx=".993" ry="1.702" transform="rotate(-4.903 5.503 9.962)" fill="#0446DE"></ellipse><ellipse cx="10.749" cy="10.931" rx=".993" ry="1.702" transform="rotate(-4.903 10.749 10.93)" fill="#0446DE"></ellipse></svg>
            Add Live Chat
        </a>
        <div class="manage--chatway">
            <a href="<?php echo admin_url("plugin-install.php?s=chatway&tab=search&type=author") ?>" target="_blank" class="plugin-add--chatyway inline-flex font-primary items-center gap-0.5 py-1 px-2.5 border border-solid text-center justify-center !border-[#0446de] rounded-lg !text-[#0446de] hover:text-[#0446de] hover:bg-[#edf3f6]">
                <svg class="h-5 w-auto" width="20" height="24" viewBox="0 0 20 24" fill="none" xmlns="http://www.w3.org/2000/svg" svg-inline="" role="presentation" focusable="false" tabindex="-1"><path d="M7.367 22.703l1.638-2.863L10.19 21.7s-.754-.134-1.44.194c-.687.328-1.384.81-1.384.81z" fill="#0038A5"></path><path d="M6.193 21.342a.97.97 0 00-.712-.564l-3.894-.72A1.94 1.94 0 010 18.153V6.534c0-1.43.7-2.77 1.876-3.585L4.39 1.205A4.606 4.606 0 017.798.45l8.982 1.548A3.879 3.879 0 0120 5.821v8.8c0 1.043-.42 2.04-1.163 2.77l-3.199 3.138a5.091 5.091 0 01-4.603 1.349l-1.858-.387a.97.97 0 00-.906.286l-.782.836a.485.485 0 01-.798-.137l-.498-1.134z" fill="#0446DE"></path><path d="M4.264 4.353a3.152 3.152 0 00-3.78 3.089v9.924a1.94 1.94 0 001.587 1.907l3.858.714a.97.97 0 01.719.582l.346.832c.105.253.44.303.615.094l.668-.801a.97.97 0 01.818-.346l3.067.232a2.667 2.667 0 002.868-2.659V8.126a1.94 1.94 0 00-1.553-1.9L4.264 4.352z" fill="#0038A5"></path><path d="M4.055 4.34a1.94 1.94 0 00-2.309 1.905v10.204a1.94 1.94 0 001.662 1.92l2.646.383a.97.97 0 01.739.546l.371.789a.364.364 0 00.614.098l.56-.65a.97.97 0 01.874-.327l3.601.521a1.94 1.94 0 002.217-1.92V8.07a1.94 1.94 0 00-1.57-1.904L4.055 4.34z" fill="#fff"></path><path d="M10.91 14.59L5.31 13.61c-.407-.072-.738.254-.58.636.511 1.242 1.94 3.195 4.473 2.544.522-.134 1.701-.812 2.034-1.715.083-.226-.092-.442-.329-.484z" fill="#0446DE"></path><ellipse cx="5.503" cy="9.962" rx=".993" ry="1.702" transform="rotate(-4.903 5.503 9.962)" fill="#0446DE"></ellipse><ellipse cx="10.749" cy="10.931" rx=".993" ry="1.702" transform="rotate(-4.903 10.749 10.93)" fill="#0446DE"></ellipse></svg>
                Add Live Chat
            </a>
            <a href="<?php echo admin_url("admin.php?page=chatway") ?>" target="_blank" class="plugin-manage--chatway inline-flex font-primary items-center gap-0.5 py-1 px-2.5 border border-solid text-center justify-center !border-[#0446de] rounded-lg !text-[#0446de] hover:text-[#0446de] hover:bg-[#edf3f6]">
                <svg class="h-5 w-auto" width="20" height="24" viewBox="0 0 20 24" fill="none" xmlns="http://www.w3.org/2000/svg" svg-inline="" role="presentation" focusable="false" tabindex="-1"><path d="M7.367 22.703l1.638-2.863L10.19 21.7s-.754-.134-1.44.194c-.687.328-1.384.81-1.384.81z" fill="#0038A5"></path><path d="M6.193 21.342a.97.97 0 00-.712-.564l-3.894-.72A1.94 1.94 0 010 18.153V6.534c0-1.43.7-2.77 1.876-3.585L4.39 1.205A4.606 4.606 0 017.798.45l8.982 1.548A3.879 3.879 0 0120 5.821v8.8c0 1.043-.42 2.04-1.163 2.77l-3.199 3.138a5.091 5.091 0 01-4.603 1.349l-1.858-.387a.97.97 0 00-.906.286l-.782.836a.485.485 0 01-.798-.137l-.498-1.134z" fill="#0446DE"></path><path d="M4.264 4.353a3.152 3.152 0 00-3.78 3.089v9.924a1.94 1.94 0 001.587 1.907l3.858.714a.97.97 0 01.719.582l.346.832c.105.253.44.303.615.094l.668-.801a.97.97 0 01.818-.346l3.067.232a2.667 2.667 0 002.868-2.659V8.126a1.94 1.94 0 00-1.553-1.9L4.264 4.352z" fill="#0038A5"></path><path d="M4.055 4.34a1.94 1.94 0 00-2.309 1.905v10.204a1.94 1.94 0 001.662 1.92l2.646.383a.97.97 0 01.739.546l.371.789a.364.364 0 00.614.098l.56-.65a.97.97 0 01.874-.327l3.601.521a1.94 1.94 0 002.217-1.92V8.07a1.94 1.94 0 00-1.57-1.904L4.055 4.34z" fill="#fff"></path><path d="M10.91 14.59L5.31 13.61c-.407-.072-.738.254-.58.636.511 1.242 1.94 3.195 4.473 2.544.522-.134 1.701-.812 2.034-1.715.083-.226-.092-.442-.329-.484z" fill="#0446DE"></path><ellipse cx="5.503" cy="9.962" rx=".993" ry="1.702" transform="rotate(-4.903 5.503 9.962)" fill="#0446DE"></ellipse><ellipse cx="10.749" cy="10.931" rx=".993" ry="1.702" transform="rotate(-4.903 10.749 10.93)" fill="#0446DE"></ellipse></svg>
                Manage Live Chat
            </a>
        </div>
    </div>

    <input type="hidden" class="add_slug" name="cht_numb_slug" placeholder="test" value="<?php echo esc_attr(get_option('cht_numb_slug')); ?>" id="cht_numb_slug" >

    <?php include plugin_dir_path(__FILE__) . "chatway-notice.php"; ?>

    <div class="channels-selected mt-5">
        <div class="channel-empty-state relative <?php echo esc_attr(count($this->socials) == 0?"active":"") ?>"">
            <img class="-left-3 sm:-left-5 md:-left-8 relative" src="<?php echo esc_url(CHT_PLUGIN_URL."admin/assets/images/empty-state-star.png") ?>"/>
            <p class="absolute top-4 left-0 text-base text-cht-gray-150 w-52 text-center opacity-60"><?php esc_html_e('So many channels to choose from...', 'chaty'); ?></p>
        </div>
        <ul id="channels-selected-list" class="channels-selected-list channels-selected">
            <?php if ($this->socials) {
                foreach ($socialApp as $keySoc) {
                    foreach ($this->socials as $key => $social) {
                        if ($social['slug'] != $keySoc) {
                            // compare social media slug
                            continue;
                        }

                        // get setting for media if already saved
                        if($social['slug'] == "Chatway") {
                            include plugin_dir_path(__FILE__) . "chatway-setting.php";
                        } else {
                            include plugin_dir_path(__FILE__) . "channel.php";
                        }
                        ?>
                    <?php } ?>
                <?php } ?>
            <?php }; ?>
            <?php
            $proClass = "free";
            $text     = get_option("cht_close_button_text");
            $text     = wp_strip_all_tags(($text === false) ? "Hide" : $text);
            ?>
            <!-- close setting strat -->
            <li class="chaty-cls-setting" data-id="" id="chaty-social-close">
                <div class="channels-selected__item pro 1 available flex items-start space-x-3 ml-4">
                    <div class="chaty-default-settings">
                        <div class="move-icon hidden">
                            <img src="<?php echo esc_url(CHT_PLUGIN_URL."admin/assets/images/move-icon.png") ?>" style="opacity:0"; />
                        </div>
                        <div class="icon icon-md active" data-label="close">
                            <span id="image_data_close">
                                <svg viewBox="0 0 54 54" fill="none" xmlns="http://www.w3.org/2000/svg"><ellipse cx="26" cy="26" rx="26" ry="26" fill="#A886CD"></ellipse><rect width="27.1433" height="3.89857" rx="1.94928" transform="translate(18.35 15.6599) scale(0.998038 1.00196) rotate(45)" fill="white"></rect><rect width="27.1433" height="3.89857" rx="1.94928" transform="translate(37.5056 18.422) scale(0.998038 1.00196) rotate(135)" fill="white"></rect></svg>
                            </span>
                            <span class="default_image_close" style="display: none;">
                                 <svg viewBox="0 0 54 54" fill="none" xmlns="http://www.w3.org/2000/svg"><ellipse cx="26" cy="26" rx="26" ry="26" fill="#A886CD"></ellipse><rect width="27.1433" height="3.89857" rx="1.94928" transform="translate(18.35 15.6599) scale(0.998038 1.00196) rotate(45)" fill="white"></rect><rect width="27.1433" height="3.89857" rx="1.94928" transform="translate(37.5056 18.422) scale(0.998038 1.00196) rotate(135)" fill="white"></rect></svg>
                            </span>
                        </div>
                    </div>
                    <div>
                        <div class="channels__input-box cls-btn-settings active">
                            <input type="text" class="channels__input close-button-text" name="cht_close_button_text" value="<?php echo esc_attr((wp_unslash($text))) ?>" >
                        </div>
                        <div class="input-example cls-btn-settings active font-primary text-cht-gray-150 text-base mt-1">
                            <?php esc_html_e('On hover Close button text', 'chaty'); ?>
                        </div>
                    </div>
                </div>
            </li>
            <!-- close setting end -->
        </ul>

        <div class="channels-selected__item disabled" style="opacity: 0; display: none;"></div>

        <input type="hidden" id="is_pro_plugin" value="0" />
    </div>
</section>
