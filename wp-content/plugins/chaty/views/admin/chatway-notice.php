<?php
$chatwayStatus = apply_filters('check_for_chatway_status', 'not-installed');
?>
<div class="chatway--notice mt-5 <?php echo esc_attr($chatwayStatus == 'completed'?'hidden':'') ?>">
    <div class="flex p-5 border border-solid border-cht-gray-50 mt-2.5 bg-[#f9fafb] hover:border-[#83a1b7] rounded-lg gap-4 relative pr-6">
        <div class="chatway-icon">
            <svg width="50" height="50" viewBox="0 0 50 50" fill="none" xmlns="http://www.w3.org/2000/svg"> <rect x="1" y="1" width="48" height="48" rx="24" fill="#F3F9FF"></rect> <rect x="0.5" y="0.5" width="49" height="49" rx="24.5" stroke="#0446DE" stroke-opacity="0.2"></rect> <path d="M21.4323 40.8939L23.6881 36.9504L25.3229 39.5109C25.3229 39.5109 24.2844 39.3259 23.3384 39.7783C22.3925 40.2307 21.4323 40.8939 21.4323 40.8939Z" fill="#0038A5"></path> <path d="M19.8166 39.0134C19.6387 38.6078 19.2713 38.3169 18.8359 38.2365L13.4717 37.2461C12.2047 37.0122 11.2852 35.9074 11.2852 34.619V18.6157C11.2852 16.6455 12.2507 14.8004 13.8694 13.6772L17.332 11.2746C18.7 10.3254 20.3863 9.95195 22.0271 10.2349L34.3999 12.3681C36.9627 12.81 38.8351 15.0328 38.8351 17.6334V29.7568C38.8351 31.1918 38.2579 32.5665 37.2334 33.5713L32.8267 37.8933C31.1557 39.5321 28.7773 40.2293 26.4861 39.7519L23.9259 39.2186C23.4695 39.1235 22.9966 39.2732 22.6781 39.6136L21.6016 40.7642C21.2739 41.1144 20.695 41.0154 20.5023 40.5762L19.8166 39.0134Z" fill="#0446DE"></path> <path d="M17.1588 15.6094C14.4688 15.0628 11.9531 17.1187 11.9531 19.8637V33.5345C11.9531 34.8223 12.8719 35.9268 14.1382 36.1613L19.4524 37.1455C19.8966 37.2277 20.2691 37.529 20.4426 37.9461L20.9194 39.0931C21.0638 39.4406 21.5256 39.5106 21.7666 39.2216L22.6864 38.1184C22.9629 37.7867 23.3827 37.6092 23.8132 37.6418L28.0387 37.9619C30.1706 38.1235 31.9895 36.4372 31.9895 34.2991V20.8063C31.9895 19.536 31.0949 18.4413 29.85 18.1883L17.1588 15.6094Z" fill="#0038A5"></path> <path d="M16.8715 15.5962C15.2228 15.2764 13.6914 16.5393 13.6914 18.2188V32.2758C13.6914 33.6035 14.6665 34.7297 15.9805 34.9198L19.6259 35.447C20.0697 35.5112 20.4516 35.7938 20.6429 36.1994L21.1551 37.2859C21.2968 37.6342 21.7574 37.708 22.0008 37.4215L22.7724 36.5261C23.0703 36.1805 23.524 36.0108 23.9755 36.0761L28.9365 36.7937C30.5473 37.0266 31.9904 35.7773 31.9904 34.1497V20.7314C31.9904 19.452 31.0834 18.3523 29.8275 18.1087L16.8715 15.5962Z" fill="white"></path> <path d="M26.3149 29.7134L18.6034 28.3615C18.0423 28.2631 17.5858 28.7119 17.8029 29.2386C18.5079 30.9493 20.4771 33.6386 23.9658 32.7419C24.6842 32.5573 26.3091 31.6233 26.7669 30.3793C26.8813 30.0685 26.6411 29.7706 26.3149 29.7134Z" fill="#0446DE"></path> <ellipse cx="18.8634" cy="23.3408" rx="1.36726" ry="2.34388" transform="rotate(-4.90348 18.8634 23.3408)" fill="#0446DE"></ellipse> <ellipse cx="26.0899" cy="24.6755" rx="1.36726" ry="2.34388" transform="rotate(-4.90348 26.0899 24.6755)" fill="#0446DE"></ellipse> </svg>
        </div>
        <div class="chatway-notice-message">
            <div class="chatway--notice--info plugin-not-installed <?php echo esc_attr($chatwayStatus == 'not-installed'?'':'hidden') ?>">
                <div class="text-sm pb-3">
                    <strong>Please note</strong> that the Chatway plugin is not installed on your WordPress dashboard and Chatway widget will not be visible on your website. <a target="_blank" href="<?php echo admin_url("plugin-install.php?s=chatway&tab=search&type=author") ?>" class="text-cht-primary hover:text-cht-primary-100 underline">Go to plugins</a> and install the Chatway plugin.
                </div>
                <div class="flex gap-2.5">
                    <a target="_blank" href="<?php echo admin_url("plugin-install.php?s=chatway&tab=search&type=author") ?>" class="text-cht-primary text-sm hover:text-cht-primary hover:bg-[#f9f6fd] items-center gap-1 border border-solid border-cht-primary-100 rounded inline-flex py-1 px-3">
                        Install Chatway plugin
                        <svg data-v-7dab8964="" width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg"><path data-v-7dab8964="" d="M12 8.66667V12.6667C12 13.0203 11.8595 13.3594 11.6095 13.6095C11.3594 13.8595 11.0203 14 10.6667 14H3.33333C2.97971 14 2.64057 13.8595 2.39052 13.6095C2.14048 13.3594 2 13.0203 2 12.6667V5.33333C2 4.97971 2.14048 4.64057 2.39052 4.39052C2.64057 4.14048 2.97971 4 3.33333 4H7.33333" stroke="#b78deb" stroke-width="1.33" stroke-linecap="round" stroke-linejoin="round"></path><path data-v-7dab8964="" d="M10 2H14V6" stroke="#b78deb" stroke-width="1.33" stroke-linecap="round" stroke-linejoin="round"></path><path data-v-7dab8964="" d="M6.66797 9.33333L14.0013 2" stroke="#b78deb" stroke-width="1.33" stroke-linecap="round" stroke-linejoin="round"></path></svg>
                    </a>
                    <button type="button" class="close-chatway-notice text-cht-gray-150 text-sm hover:text-cht-gray-150 items-center gap-1 border border-solid border-[#C6D7E3] hover:border-cht-gray-150 transition ease-in-out rounded inline-flex py-1 px-3 hover:bg-[#edf3f6]">
                        Close
                    </button>
                </div>
            </div>
            <div class="chatway--notice--info plugin-not-activated <?php echo esc_attr($chatwayStatus == 'not-activated'?'':'hidden') ?>">
                <div class="text-sm pb-3">
                    <strong>Please note</strong> that the Chatway plugin has not been activated. To activate it, <a target="_blank" href="<?php echo admin_url("plugin-install.php?s=chatway&tab=search&type=author") ?>" class="text-cht-primary hover:text-cht-primary-100 underline">go to plugins</a> and activate the Chatway again.
                </div>
                <div class="flex gap-2.5">
                    <a target="_blank" href="<?php echo admin_url("plugin-install.php?s=chatway&tab=search&type=author") ?>" class="text-cht-primary text-sm hover:text-cht-primary hover:bg-[#f9f6fd] items-center gap-1 border border-solid border-cht-primary-100 rounded inline-flex py-1 px-3">
                        Activate Chatway plugin
                        <svg data-v-7dab8964="" width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg"><path data-v-7dab8964="" d="M12 8.66667V12.6667C12 13.0203 11.8595 13.3594 11.6095 13.6095C11.3594 13.8595 11.0203 14 10.6667 14H3.33333C2.97971 14 2.64057 13.8595 2.39052 13.6095C2.14048 13.3594 2 13.0203 2 12.6667V5.33333C2 4.97971 2.14048 4.64057 2.39052 4.39052C2.64057 4.14048 2.97971 4 3.33333 4H7.33333" stroke="#b78deb" stroke-width="1.33" stroke-linecap="round" stroke-linejoin="round"></path><path data-v-7dab8964="" d="M10 2H14V6" stroke="#b78deb" stroke-width="1.33" stroke-linecap="round" stroke-linejoin="round"></path><path data-v-7dab8964="" d="M6.66797 9.33333L14.0013 2" stroke="#b78deb" stroke-width="1.33" stroke-linecap="round" stroke-linejoin="round"></path></svg>
                    </a>
                    <button type="button" class="close-chatway-notice text-cht-gray-150 text-sm hover:text-cht-gray-150 items-center gap-1 border border-solid border-[#C6D7E3] hover:border-cht-gray-150 transition ease-in-out rounded inline-flex py-1 px-3 hover:bg-[#edf3f6]">
                        Close
                    </button>
                </div>
            </div>
            <div class="chatway--notice--info plugin-not-logged-in <?php echo esc_attr($chatwayStatus == 'not-logged-in'?'':'hidden') ?>">
                <div class="text-sm pb-3">
                    <strong>Please note</strong> that you have not yet signed in to Chatway. <a target="_blank" href="<?php echo admin_url("admin.php?page=chatway") ?>" class="text-cht-primary hover:text-cht-primary-100 underline">Go to Chatway</a> and complete the sign-in process and the onboarding steps to display the Live Chat widget.
                </div>
                <div class="flex gap-2.5">
                    <a target="_blank" href="<?php echo admin_url('admin.php?page=chatway') ?>" class="text-cht-primary text-sm hover:text-cht-primary hover:bg-[#f9f6fd] items-center gap-1 border border-solid border-cht-primary-100 rounded inline-flex py-1 px-3">
                        Sign in to Chatway
                        <svg data-v-7dab8964="" width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg"><path data-v-7dab8964="" d="M12 8.66667V12.6667C12 13.0203 11.8595 13.3594 11.6095 13.6095C11.3594 13.8595 11.0203 14 10.6667 14H3.33333C2.97971 14 2.64057 13.8595 2.39052 13.6095C2.14048 13.3594 2 13.0203 2 12.6667V5.33333C2 4.97971 2.14048 4.64057 2.39052 4.39052C2.64057 4.14048 2.97971 4 3.33333 4H7.33333" stroke="#b78deb" stroke-width="1.33" stroke-linecap="round" stroke-linejoin="round"></path><path data-v-7dab8964="" d="M10 2H14V6" stroke="#b78deb" stroke-width="1.33" stroke-linecap="round" stroke-linejoin="round"></path><path data-v-7dab8964="" d="M6.66797 9.33333L14.0013 2" stroke="#b78deb" stroke-width="1.33" stroke-linecap="round" stroke-linejoin="round"></path></svg>
                    </a>
                    <button type="button" class="close-chatway-notice text-cht-gray-150 text-sm hover:text-cht-gray-150 items-center gap-1 border border-solid border-[#C6D7E3] hover:border-cht-gray-150 transition ease-in-out rounded inline-flex py-1 px-3 hover:bg-[#edf3f6]">
                        Close
                    </button>
                </div>
            </div>
            <div class="chatway--notice--info plugin-not-onboarded <?php echo esc_attr($chatwayStatus == 'not-onboarded'?'':'hidden') ?>">
                <div class="text-sm pb-3">
                    <strong>Please note</strong> that you have not completed the Chatway onboarding, and to make the Live Chat widget visible on your website, <a target="_blank" href="<?php echo admin_url("admin.php?page=chatway") ?>" class="text-cht-primary hover:text-cht-primary-100 underline">go to Chatway</a> and complete the onboarding steps.
                </div>
                <div class="flex gap-2.5">
                    <a target="_blank" href="<?php echo admin_url('admin.php?page=chatway') ?>" class="text-cht-primary text-sm hover:text-cht-primary hover:bg-[#f9f6fd] items-center gap-1 border border-solid border-cht-primary-100 rounded inline-flex py-1 px-3">
                        Complete onboarding
                        <svg data-v-7dab8964="" width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg"><path data-v-7dab8964="" d="M12 8.66667V12.6667C12 13.0203 11.8595 13.3594 11.6095 13.6095C11.3594 13.8595 11.0203 14 10.6667 14H3.33333C2.97971 14 2.64057 13.8595 2.39052 13.6095C2.14048 13.3594 2 13.0203 2 12.6667V5.33333C2 4.97971 2.14048 4.64057 2.39052 4.39052C2.64057 4.14048 2.97971 4 3.33333 4H7.33333" stroke="#b78deb" stroke-width="1.33" stroke-linecap="round" stroke-linejoin="round"></path><path data-v-7dab8964="" d="M10 2H14V6" stroke="#b78deb" stroke-width="1.33" stroke-linecap="round" stroke-linejoin="round"></path><path data-v-7dab8964="" d="M6.66797 9.33333L14.0013 2" stroke="#b78deb" stroke-width="1.33" stroke-linecap="round" stroke-linejoin="round"></path></svg>
                    </a>
                    <button type="button" class="close-chatway-notice text-cht-gray-150 text-sm hover:text-cht-gray-150 items-center gap-1 border border-solid border-[#C6D7E3] hover:border-cht-gray-150 transition ease-in-out rounded inline-flex py-1 px-3 hover:bg-[#edf3f6]">
                        Close
                    </button>
                </div>
            </div>
        </div>
        <button type="button" class="close-chatway-notice group absolute top-0.5 right-1.5 w-[18px] h-6" id="remove-chatway-notice">
            <svg class="pointer-events-none w-full h-full" data-v-1cf7b632="" width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg" svg-inline="" focusable="false" tabindex="-1">
                <path class="group-hover:stroke-red-500 hover:stroke-red-500" data-v-1cf7b632="" d="M2 4h12M5.333 4V2.667a1.333 1.333 0 011.334-1.334h2.666a1.333 1.333 0 011.334 1.334V4m2 0v9.333a1.334 1.334 0 01-1.334 1.334H4.667a1.334 1.334 0 01-1.334-1.334V4h9.334z" stroke="#49687E" stroke-linecap="round" stroke-linejoin="round"></path>
            </svg>
        </button>
    </div>
</div>