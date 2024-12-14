<div class="chaty-popup chatyway-popup" id="chatyway-info-popup" data-step="1">
    <div class="chaty-popup-outer"></div>
    <div class="chaty-popup-inner popup-pos-bottom step-1">
        <div class="chaty-popup-content">
            <div class="chaty-popup-close">
                <a href="javascript:void(0)" class="close-delete-pop close-chaty-popup-btn right-2 top-2 relative">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16"><path d="M15.6 15.5c-.53.53-1.38.53-1.91 0L8.05 9.87 2.31 15.6c-.53.53-1.38.53-1.91 0s-.53-1.38 0-1.9l5.65-5.64L.4 2.4C-.13 1.87-.13 1.02.4.49s1.38-.53 1.91 0l5.64 5.63L13.69.39c.53-.53 1.38-.53 1.91 0s.53 1.38 0 1.91L9.94 7.94l5.66 5.65c.52.53.52 1.38 0 1.91z"></path></svg>
                </a>
            </div>
            <div class="a-card a-card--normal chatyway-popup-box">
                <div class="cloud-images">
                    <img src="<?php echo esc_url(plugins_url('../../admin/assets/images/modal/cloud-1.png', __FILE__)); ?>" alt="" class="floating-image cloud-1" />
                    <img src="<?php echo esc_url(plugins_url('../../admin/assets/images/modal/cloud-5.png', __FILE__)); ?>" alt="" class="floating-image cloud-5" />
                    <img src="<?php echo esc_url(plugins_url('../../admin/assets/images/modal/cloud-2.png', __FILE__)); ?>" alt="" class="floating-image cloud-2" />
                    <img src="<?php echo esc_url(plugins_url('../../admin/assets/images/modal/cloud-3.png', __FILE__)); ?>" alt="" class="floating-image cloud-3" />
                    <img src="<?php echo esc_url(plugins_url('../../admin/assets/images/modal/cloud-4.png', __FILE__)); ?>" alt="" class="floating-image cloud-4" />
                </div>
                <div class="chatway-steps step-1 w-full" >
                    <div class="chatyway-popup-box-logo mb-5 text-center">
                        <img class="inline-block" src="<?php echo esc_url(plugins_url('../../admin/assets/images/logo-color.svg', __FILE__)); ?>" alt="Chaty" class="logo">
                    </div>
                    <div class="chatway-title text-2xl pb-4 font-primary text-cht-gray-150"><?php esc_html_e("Add Chatway Live Chat widget to your website", "chaty"); ?></div>
                    <ul class="text-left">
                        <li class="text-cht-gray-150 text-base flex">
                            <span class="flex-none inline-flex items-center w-6 h-6 bg-[#e4fff5] mr-2 rounded-full text-center"><svg class="mx-auto" width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg" svg-inline="" role="presentation" focusable="false" tabindex="-1"><path d="M13.333 4l-7.334 7.333L2.666 8" stroke="#68CB9B" stroke-width="1.67" stroke-linecap="round" stroke-linejoin="round"></path></svg></span>
                            <?php esc_html_e("Unlimited conversations, email, and Facebook Messenger integrations", "chaty"); ?>
                        </li>
                        <li class="text-cht-gray-150 text-base flex">
                            <span class="flex-none inline-flex items-center w-6 h-6 bg-[#e4fff5] mr-2 rounded-full text-center"><svg class="mx-auto" width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg" svg-inline="" role="presentation" focusable="false" tabindex="-1"><path d="M13.333 4l-7.334 7.333L2.666 8" stroke="#68CB9B" stroke-width="1.67" stroke-linecap="round" stroke-linejoin="round"></path></svg></span>
                            <?php esc_html_e("Team collaboration with agents", "chaty"); ?>
                        </li>
                        <li class="text-cht-gray-150 text-base flex">
                            <span class="flex-none inline-flex items-center w-6 h-6 bg-[#e4fff5] mr-2 rounded-full text-center"><svg class="mx-auto" width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg" svg-inline="" role="presentation" focusable="false" tabindex="-1"><path d="M13.333 4l-7.334 7.333L2.666 8" stroke="#68CB9B" stroke-width="1.67" stroke-linecap="round" stroke-linejoin="round"></path></svg></span>
                            <?php esc_html_e("Canned responses, private notes, reminders, and more", "chaty"); ?>
                        </li>
                        <li class="text-cht-gray-150 text-base flex">
                            <span class="flex-none inline-flex items-center w-6 h-6 bg-[#e4fff5] mr-2 rounded-full text-center"><svg class="mx-auto" width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg" svg-inline="" role="presentation" focusable="false" tabindex="-1"><path d="M13.333 4l-7.334 7.333L2.666 8" stroke="#68CB9B" stroke-width="1.67" stroke-linecap="round" stroke-linejoin="round"></path></svg></span>
                            <?php esc_html_e("iOS & Android apps available", "chaty"); ?>
                        </li>
                    </ul>
                    <div class="chatyway-popup-box-img mb-5">
                        <img src="<?php echo esc_url(CHT_PLUGIN_URL.'admin/assets/images/chatyway-app.png'); ?>" alt="chatyway">
                    </div>
                    <div class="chatway-footer mt-5">
                        <?php if(!is_plugin_active('chatway-live-chat/chatway.php')) { ?>
                            <a target="_blank" href="<?php echo admin_url("plugin-install.php?s=chatway&tab=search&type=author") ?>" id="add-live-channel" class="inline-flex font-primary items-center gap-2.5 px-12 border border-solid text-center justify-center border-cht-primary h-10 text-base rounded-lg text-white hover:text-white bg-cht-primary hover:bg-cht-primary-100">
                                <?php esc_html_e("Add Live Chat", "chaty") ?>
                                <svg class="ml-1" width="6" height="11" viewBox="0 0 6 11" fill="none" xmlns="http://www.w3.org/2000/svg"> <path d="M1 9.5L5 5.5L1 1.5" stroke="white" stroke-width="1.33" stroke-linecap="round" stroke-linejoin="round"></path> </svg>
                            </a>
                        <?php } else { ?>
                            <a href="javascript:;" id="add-live-channel" class="inline-flex font-primary items-center gap-2.5 px-12 border border-solid text-center justify-center border-cht-primary h-10 text-base rounded-lg text-white hover:text-white bg-cht-primary hover:bg-cht-primary-100">
                                <?php esc_html_e("Add Live Chat", "chaty") ?>
                                <svg class="ml-1" width="6" height="11" viewBox="0 0 6 11" fill="none" xmlns="http://www.w3.org/2000/svg"> <path d="M1 9.5L5 5.5L1 1.5" stroke="white" stroke-width="1.33" stroke-linecap="round" stroke-linejoin="round"></path> </svg>
                            </a>
                        <?php } ?>
                    </div>
                </div>
                <div class="chatway-steps step-2 w-full" >
                    <div class="text-center text-2xl font-medium text-cht-gray-150">How Would You Like to Show The Live Chat Icon?</div>
                    <div class="text-center text-cht-primary text-sm">You can change it whenever you like</div>
                    <div class="chatway-positions my-5 has-check-icon">
                        <div class="flex gap-4">
                            <div class="chatway-position">
                                <input type="radio" class="sr-only" name="chatway_position" value="above-chaty" id="above-chaty" checked>
                                <label class="block cursor-pointer rounded-lg border border-[#c6d7e3] pointer relative mb-1" for="above-chaty">
                                    <img class="w-full h-auto" src="<?php echo esc_url(CHT_PLUGIN_URL.'admin/assets/images/chatway/above-chaty.svg'); ?>" alt="chatyway">
                                    <img class="w-5 h-5 absolute top-1.5 right-1.5 hidden" src="<?php echo esc_url(CHT_PLUGIN_URL.'admin/assets/images/chatway/select-icon.svg'); ?>" alt="chatyway">
                                </label>
                                <span class="block text-center text-sm text-cht-gray-150">An icon above Chaty's icon</span>
                            </div>
                            <div class="chatway-position">
                                <input type="radio" class="sr-only" name="chatway_position" value="inside-chaty" id="inside-chaty">
                                <label class="block cursor-pointer rounded-lg border border-[#c6d7e3] pointer relative mb-1" for="inside-chaty">
                                    <img class="w-full h-auto" src="<?php echo esc_url(CHT_PLUGIN_URL.'admin/assets/images/chatway/inside-chaty.svg'); ?>" alt="chatyway">
                                    <img class="w-5 h-5 absolute top-1.5 right-1.5 hidden" src="<?php echo esc_url(CHT_PLUGIN_URL.'admin/assets/images/chatway/select-icon.svg'); ?>" alt="chatyway">
                                </label>
                                <span class="block text-center text-sm text-cht-gray-150">Show it as one of Chaty's channel</span>
                            </div>
                            <div class="chatway-position">
                                <input type="radio" class="sr-only" name="chatway_position" value="outside-chaty" id="outside-chaty">
                                <label class="block cursor-pointer rounded-lg border border-[#c6d7e3] pointer relative mb-1" for="outside-chaty">
                                    <img class="w-full h-auto" src="<?php echo esc_url(CHT_PLUGIN_URL.'admin/assets/images/chatway/outside-chaty.svg'); ?>" alt="chatyway">
                                    <img class="w-5 h-5 absolute top-1.5 right-1.5 hidden" src="<?php echo esc_url(CHT_PLUGIN_URL.'admin/assets/images/chatway/select-icon.svg'); ?>" alt="chatyway">
                                </label>
                                <span class="block text-center text-sm text-cht-gray-150">Place manually from the Chatway settings</span>
                            </div>
                        </div>
                    </div>
                    <div class="text-center">
                        <button type="button" id="chatway-view-button" class="font-primary button-shadow transition items-center gap-2.5 px-12 border border-solid text-center justify-center border-cht-primary h-10 text-base rounded-lg text-white hover:text-white bg-cht-primary hover:bg-cht-primary-100">Select View</button>
                    </div>
                </div>
                <div class="chatway-steps step-3 w-full text-cht-gray-150" >
                    <div class="text-center text-2xl font-medium">Chatway Added Successfully!</div>
                    <div class="text-center mt-1 font-normal text-base"><strong>Note: </strong>Please ensure that you completed the Chatway onboarding</div>
                    <div class="chaty--chatway flex my-10 items-center justify-center gap-2.5">
                        <img class="w-auto h-auto" src="<?php echo esc_url(CHT_PLUGIN_URL.'admin/assets/images/chatway/chaty.svg'); ?>" alt="chatyway">
                        <img class="w-auto h-auto" src="<?php echo esc_url(CHT_PLUGIN_URL.'admin/assets/images/chatway/chaty-chatway.svg'); ?>" alt="chatyway">
                        <img class="w-auto h-auto" src="<?php echo esc_url(CHT_PLUGIN_URL.'admin/assets/images/chatway/chatway.svg'); ?>" alt="chatyway">
                    </div>
                    <div class="text-center">
                        <button type="button" id="add-chatway-channel" class="font-primary w-full button-shadow transition items-center gap-2.5 px-12 border border-solid text-center justify-center border-cht-primary h-10 text-base rounded-lg text-white hover:text-white bg-cht-primary hover:bg-cht-primary-100">Done</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="chaty-popup" id="remove-chatway-channel">
    <div class="chaty-popup-outer"></div>
    <div class="chaty-popup-inner popup-pos-bottom">
        <div class="chaty-popup-content">
            <div class="chaty-popup-close">
                <a href="javascript:void(0)" class="close-delete-pop close-chaty-popup-btn relative top-5 right-5">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16"><path d="M15.6 15.5c-.53.53-1.38.53-1.91 0L8.05 9.87 2.31 15.6c-.53.53-1.38.53-1.91 0s-.53-1.38 0-1.9l5.65-5.64L.4 2.4C-.13 1.87-.13 1.02.4.49s1.38-.53 1.91 0l5.64 5.63L13.69.39c.53-.53 1.38-.53 1.91 0s.53 1.38 0 1.91L9.94 7.94l5.66 5.65c.52.53.52 1.38 0 1.91z"/></svg>
                </a>
            </div>
            <div class="a-card a-card--normal">
                <div class="chaty-popup-header font-medium text-cht-gray-150 py-4 text-left px-5">
                    <?php esc_html_e("Want to remove Chatway Widget?", "chaty") ?>
                </div>
                <div class="text-cht-gray-150 text-base px-5 py-6">
                    <?php esc_html_e("Chatway widget will continue to appear on your website as a separate icon. To remove it completely you need to uninstall Chatway.", "chaty") ?>
                </div>
                <div class="chaty-popup-footer flex px-5 justify-end">
                    <button type="button" class="remove--chatway btn btn-primary bg-transparent text-cht-gray-150 border-cht-gray-150 hover:bg-transparent hover:text-cht-gray-150 rounded-lg mr-3"><?php esc_html_e("Remove from Channels", "chaty") ?></button>
                    <a target="_blank" href="<?php echo admin_url("plugins.php") ?>" class="btn btn-default btn btn-primary bg-transparent text-cht-primary-100 btn rounded-lg btn-primary "><?php esc_html_e("Manage Plugins", "chaty") ?></a>
                </div>
            </div>
        </div>
    </div>
</div>


<div class="chaty-popup" id="chatway-position-changed" >
    <div class="chaty-popup-outer"></div>
    <div class="chaty-popup-inner popup-pos-bottom w-[640px]">
        <div class="chaty-popup-content">
            <div class="chaty-popup-close">
                <a href="javascript:void(0)" class="close-delete-pop close-chaty-popup-btn relative top-5 right-5">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16"><path d="M15.6 15.5c-.53.53-1.38.53-1.91 0L8.05 9.87 2.31 15.6c-.53.53-1.38.53-1.91 0s-.53-1.38 0-1.9l5.65-5.64L.4 2.4C-.13 1.87-.13 1.02.4.49s1.38-.53 1.91 0l5.64 5.63L13.69.39c.53-.53 1.38-.53 1.91 0s.53 1.38 0 1.91L9.94 7.94l5.66 5.65c.52.53.52 1.38 0 1.91z"/></svg>
                </a>
            </div>
            <div class="a-card a-card--normal">
                <div class="chaty-popup-header font-medium text-cht-gray-150 py-4 text-left px-5">
                    <?php esc_html_e("View method has been changed 🥳", "chaty") ?>
                </div>
                <div class="modal-details chatway--options p-5" id="chatway--options" >
                    <div class="chatway--option old-view">
                        <div class="chatway--frame old-chatway--frame">
                            <div class="chatway--button">
                                <img class="chatway-view-icon w-12 h-12" src="<?php echo esc_url(CHT_PLUGIN_URL.'admin/assets/images/chatway/chatway-icon.svg'); ?>" alt="chatway" />
                            </div>
                            <div class="chaty-icon-buttons">
                                <img class="chaty--channel w-12 h-12" src="<?php echo esc_url(CHT_PLUGIN_URL.'admin/assets/images/chatway/chaty-channel.svg'); ?>" alt="chatway" />
                                <div class="chat--cta-button">
                                    <img class="chaty--close-icon w-12 h-12" src="<?php echo esc_url(CHT_PLUGIN_URL.'admin/assets/images/chatway/chaty-close-icon.svg'); ?>" alt="chatway" />
                                    <img class="chaty--cta-icon w-12 h-12" src="<?php echo esc_url(CHT_PLUGIN_URL.'admin/assets/images/chatway/chaty-icon.svg'); ?>" alt="chatway" />
                                </div>
                            </div>
                            <img class="chatway--area" src="<?php echo esc_url(CHT_PLUGIN_URL.'admin/assets/images/chatway/chatway-area.svg'); ?>" alt="chatway" />
                            <img src="<?php echo esc_url(CHT_PLUGIN_URL.'admin/assets/images/chatway/chatway-frame.svg'); ?>" class="chatway--base" alt="" />
                        </div>
                        <div class="view--title">
                            {{ $t('chatway.old_view') }}
                        </div>
                    </div>
                    <div class="chatway-arrow">
                        <img src="<?php echo esc_url(CHT_PLUGIN_URL.'admin/assets/images/chatway/chatway-arrow.svg'); ?>" alt="" />
                    </div>
                    <div class="chatway--option new-view">
                        <div class="chatway--frame new-chatway--frame">
                            <div class="chaty-icon-buttons">
                                <img class="chaty--channel w-12 h-12" src="<?php echo esc_url(CHT_PLUGIN_URL.'admin/assets/images/chatway/chaty-channel.svg'); ?>" alt="chatway" />
                                <div class="chat--cta-button">
                                    <img class="chaty--close-icon w-12 h-12" src="<?php echo esc_url(CHT_PLUGIN_URL.'admin/assets/images/chatway/chaty-close-icon.svg'); ?>" alt="chatway" />
                                    <img class="chaty--cta-icon w-12 h-12" src="<?php echo esc_url(CHT_PLUGIN_URL.'admin/assets/images/chatway/chaty-icon.svg'); ?>" alt="chatway" />
                                </div>
                            </div>
                            <img class="chatway--area" src="<?php echo esc_url(CHT_PLUGIN_URL.'admin/assets/images/chatway/chatway-area.svg'); ?>" alt="chatway" />
                            <img src="<?php echo esc_url(CHT_PLUGIN_URL.'admin/assets/images/chatway/chatway-frame.svg'); ?>" class="chatway--base" alt="" />
                        </div>
                        <div class="view--title">
                            {{ $t('chatway.new_view') }}
                        </div>
                    </div>
                </div>
                <div class="p-5 text-center">
                    <button type="button" class="close-chaty-popup-btn px-5 py-2 text-cht-primary-100 border border-solid border-cht-primary-100 text-sm rounded"><?php esc_html_e('Close', 'chaty'); ?></button>
                </div>
            </div>
        </div>
    </div>
</div>