/**
 * easyModal.js v1.3.2
 * A minimal jQuery modal that works with your CSS.
 * Author: Flavius Matis - http://flaviusmatis.github.com/
 * URL: https://github.com/flaviusmatis/easyModal.js
 *
 * Copyright 2012, Flavius Matis
 * Released under the MIT license.
 * http://flaviusmatis.github.com/license.html
 */

/* jslint browser: true */
/* global jQuery */

jQuery( function($) {
    'use strict';
    var chatyError;
    var forceSubmit = false;
    var whatsappStatus = false;
    var phoneStatus = false;
    var fbStatus = false;
    var smsStatus = false;
    var viberStatus = false;
    var phoneNumberStatus = false;
    function checkForDevices() {
        $(".chaty-popup").hide();
        if($("#cht-form .js-chanel-desktop").length == 0 || $("#cht-form .js-chanel-mobile").length == 0) {
            $("#no-device-popup").show();
            return false;
        } else if($("#cht-form .js-chanel-desktop:checked").length == 0 && $("#cht-form .js-chanel-mobile:checked").length == 0) {
            $("#device-popup").show();
            return false;
        } else {
            var inputError = 0;
            $("#channels-selected-list > li:not(#chaty-social-close)").find(".channels__input").each(function(){
                if(jQuery.trim($(this).val()) == "") {
                    inputError++;
                } else if(jQuery(this).hasClass('custom-channel-Whatsapp') && jQuery.trim($(this).val()).indexOf("-") == -1) {
                    inputError++;
                }
            });
            if(inputError == $("#channels-selected-list > li:not(#chaty-social-close)").find(".channels__input").length) {
                if (!$("#chaty-social-Contact_Us").length && !$("#chaty-social-Chatway").length) {
                    $("#no-device-value").show();
                    return false;
                }
            }
        }
        return checkForTriggers();
    }

    function get_chatway_status() {
        $.ajax({
            type: 'POST',
            url: ajaxurl,
            data: {
                action: 'get_chatway_status',
            },
            success: function(dataStatus) {
                if(dataStatus == 'completed') {
                    $(".chatway--notice--info").addClass('hidden');
                    $('.chatway--notice').addClass('hidden');
                } else {
                    $('.chatway--notice').removeClass('hidden');
                    $(".chatway--notice--info").addClass('hidden');
                    $(".plugin-"+dataStatus).removeClass('hidden');
                }

                if(dataStatus == 'not-installed' || dataStatus == 'not-activated') {
                    $(".chaty-widget-tab").removeClass('chatway--active');
                } else {
                    $(".chaty-widget-tab").addClass('chatway--active');
                }
            }
        });
    }

    function checkForTriggers() {
        $(".chaty-popup").hide();
        if(!$("#trigger_on_time").is(":checked") && !$("#chaty_trigger_on_exit").is(":checked") && !$("#chaty_trigger_on_scroll").is(":checked")) {
            $("#trigger-popup").show();
            return false;
        }
        return checkForStatus();
    }

    function checkForStatus() {
        $(".chaty-popup").hide();
        if(!$(".cht_active").is(":checked")) {
            $("#status-popup").show();
            return false;
        }
        forceSubmit = true;
        $("#cht-form").trigger("submit");
        return true;
    }

    function checkForWhatsAppNumber() {

        if(isWhatsAppValidated) {
            return checkPreSettings();
        }

        $(".phone-number-list").html("");
        if($("#channel_input_Whatsapp").length) {
            if($("#channel_input_Whatsapp").val() != "") {
                if($("#channel_input_Whatsapp").val().indexOf("-0") != -1) {
                    let inputVal = $("#channel_input_Whatsapp").val();
                    let phoneLabel = $(".phone-number-list").data("label");
                    let testLink = `https://wa.me/`+toHtmlEntities($.trim(inputVal).replace(/[^a-zA-Z0-9 ]/g, '')) ;
                    let phoneLink = `<a class="whatsapp-test-btn" target="_blank" href='${testLink}'>Test</a>`;
                    let btnHtml = `<div class='number-list is-not-agent'>${phoneLabel}: <b>${inputVal}</b> ${phoneLink}</div>`;
                    $(".phone-number-list").html(btnHtml);
                    $("#whatsapp-message-popup").show();
                    return false;
                }
            }
        }
        return checkPreSettings();
    }

    function checkPreSettings() {
        isWhatsAppValidated = true;
        if(!whatsappStatus) {
            whatsappStatus = true;
            var phoneNumberReg = /^[\+]?[(]?[0-9]{3}[)]?[-\s\.]?[0-9]{3}[-\s\.]?[0-9]{4,6}$/;
            if ($("#cht-form #Whatsapp").length && $("#cht-form #Whatsapp").val() != "") {
                var InputVal = jQuery.trim($("#cht-form #Whatsapp").val());
                chatyError = check_for_number_chaty(InputVal, "Whatsapp");
                if(chatyError) {
                    $("#custom-message-popup .chaty-popup-header").text("Whatsapp number is not valid");
                    $("#custom-message-popup .chaty-popup-body").text("Seems like the WhatsApp number you're trying to enter isn't in the right syntax. Would you like to publish it anyway?");
                    $("#custom-message-popup").show();
                    return false;
                }
            }
        } else if(!phoneStatus) {
            phoneStatus = true;
            if ($("#cht-form #Phone").length && $("#cht-form #Phone").val() != "") {
                var InputVal = jQuery.trim($("#cht-form #Phone").val());
                chatyError = check_for_number_chaty(InputVal, "Phone");
                if(chatyError) {
                    $("#custom-message-popup .chaty-popup-header").text("Phone number is not valid");
                    $("#custom-message-popup .chaty-popup-body").text("Seems like the phone number you're trying to enter isn't in the right syntax. Would you like to publish it anyway?");
                    $("#custom-message-popup").show();
                    return false;
                }
            }
        } else if(!fbStatus) {
            fbStatus = true;
            if ($("#cht-form #Facebook_Messenger").length && $("#cht-form #Facebook_Messenger").val() != "") {
                var faceBookMeReg = /(?:http:\/\/)?m\.me\/(?:(?:\w)*#!\/)?(?:pages\/)?(?:[\w\-]*\/)*([\w\-]*)/;
                var faceBookReg = /(?:http:\/\/)?facebook\.com\/(?:(?:\w)*#!\/)?(?:pages\/)?(?:[\w\-]*\/)*([\w\-]*)/;
                var InputVal = jQuery.trim($("#Facebook_Messenger").val());
                $("#cht-form #Facebook_Messenger").val(InputVal);
                if (!faceBookReg.test(InputVal) && !faceBookMeReg.test(InputVal)) {
                    $("#custom-message-popup .chaty-popup-header").text("Facebook page's URL is not valid");
                    $("#custom-message-popup .chaty-popup-body").text("Please make sure your Facebook page's URL looks like, <br/>https://m.me/YOURPAGE");
                    $("#custom-message-popup").show();
                    return false;
                }
            }
        } else if(!smsStatus) {
            smsStatus = true;
            if ($("#cht-form #SMS").length && $("#cht-form #SMS").val() != "") {
                var InputVal = jQuery.trim($("#cht-form #SMS").val());
                chatyError = check_for_number_chaty(InputVal, "SMS");
                if(chatyError) {
                    $("#custom-message-popup .chaty-popup-header").text("SMS number is not valid");
                    $("#custom-message-popup .chaty-popup-body").text("Seems like the SMS number you're trying to enter isn't in the right syntax. Would you like to publish it anyway?");
                    $("#custom-message-popup").show();
                    return false;
                }
            }
        } else if(!viberStatus) {
            viberStatus = true;
            if ($("#cht-form #Viber").length && $("#cht-form #Viber").val() != "") {
                var InputVal = jQuery.trim($("#cht-form #Viber").val());
                chatyError = check_for_number_chaty(InputVal, "Viber");
                if(chatyError) {
                    $("#custom-message-popup .chaty-popup-header").text("Viber number is not valid");
                    $("#custom-message-popup .chaty-popup-body").text("Seems like the Viber number you're trying to enter isn't in the right syntax. Would you like to publish it anyway?");
                    $("#custom-message-popup").show();
                    return false;
                }
            }
        } else if(!phoneNumberStatus) {
            phoneNumberStatus = true;
            if($("#channels-selected-list .phone-number").length) {
                $("#channels-selected-list .phone-number").each(function(){
                    if(jQuery.trim($(this).val()) != '') {
                        var inputLen = (jQuery.trim($(this).val())).length;
                        if(inputLen > 13) {
                            $("#custom-message-popup .chaty-popup-header").text($(this).data("label")+" number is not valid");
                            $("#custom-message-popup .chaty-popup-body").text("Seems like the "+$(this).data("label")+" number you're trying to enter isn't valid. Would you like to publish it anyway?");
                            $("#custom-message-popup").show();
                            return false;
                        }
                    }
                });
            }
        }
        return checkForDevices();
    }

    $(document).on("change", 'input[name="cht_social_Chatway[chatway_position]"]:checked', function(e){
        $("#chaty-social-Chatway").removeClass(['above-chaty', 'inside-chaty', 'outside-chaty']);
        $("#chaty-social-Chatway").addClass($(this).val());
        change_custom_preview();
    });

    $(window).on("load", function(){
        setTimeout(() => {
            $(".wp-editor-container iframe").contents().find('body').css({
                    backgroundColor: '#fff',
                    margin: 0,
                    padding: '0px 10px'
                });
        }, 500)
    });

    let isWhatsAppValidated = true;

    $(document).ready(function () {

        $(document).on("click", ".btn-cancel.is-chatway, .icon.chat-channel-Chatway", function(e){
            e.preventDefault();
            $("#remove-chatway-channel").show();
        });

        $(document).on("click", ".btn-cancel.is-chatway", function(e){
            e.preventDefault();
            $("#remove-chatway-channel").show();
        });

        $(document).on("click", "#chatway-view-button", function (e){
            let token = 'free';
            if ($('section').is('#pro')) {
                token = 'pro';
            }
            $("#chatyway-info-popup .chaty-popup-inner").removeClass(['step-1','step-2','step-3']);
            $("#chatyway-info-popup .chaty-popup-inner").addClass('step-3');
            const chatwayPosition = $('input[name="chatway_position"]:checked').val();
            if($("#chaty_widget_url").length) {
                $("#chatyway-info-popup").hide();
                window.location = $("#chaty_widget_url").val() + "&chatway_position=" + chatwayPosition;
                return;
            }
            $.ajax({
                type: 'POST',
                dataType: 'json',
                url: ajaxurl,
                data: {
                    action: 'choose_social',
                    social: 'Chatway',
                    position: chatwayPosition,
                    nonce_code: cht_nonce_ajax.cht_nonce,
                    version: token,
                    widget_index: $("#widget_index").val()
                },
                beforeSend: function (xhr) {

                },
                success: function (data) {
                    $("#channels-selected-list").prepend(data);
                    let social = 'Chatway';
                    var newIconLib = {
                        "material":{
                            "regular":{
                                "list-icon":"",
                                "icon-style":"mt-regular",
                                "icons":["some","some2"],
                            }
                        }
                    }
                    if($("#icon-picker-"+social).length && $("#select-icon-"+social).length) {
                        AestheticIconPicker({
                            'selector': '#icon-picker-' + social, // must be an ID
                            'onClick': '#select-icon-' + social,  // must be an ID
                            "iconLibrary": newIconLib
                        });
                    }
                    if($("#icon-picker-agent-"+social).length && $("#select-icon-agent-"+social).length) {
                        AestheticIconPicker({
                            'selector': '#icon-picker-agent-' + social, // must be an ID
                            'onClick': '#select-icon-agent-' + social,  // must be an ID
                            "iconLibrary": newIconLib
                        });
                    }

                    $(document).trigger('chatyColorPicker/trigger', [{
                        $scope   : $(`#chaty-social-Chatway`),
                        element  : '.chaty-color-field'
                    }]);

                    // call when any channel is removed or updated
                    const channel_list = [];
                    $('.channels-icons > .icon.active').each( (i, item) => {
                        channel_list.push( item.dataset.social );
                    } )
                    wp.hooks.doAction('chaty.channel_update', {
                        channel     : channel_list,         // active channel list
                        target      : 'Chatway',            // channel that removed last
                        action      : 'added',              // added || removed,
                        isExceeded  : false,
                    });

                    if($("#chaty-chatway").hasClass('completed')) {
                        $("#chaty-social-channel").trigger('click');
                    }

                    get_chatway_status();

                    change_custom_preview();
                }
            });
            //$("#chatyway-info-popup").hide();
        });

        document.addEventListener('visibilitychange', function() {
            if ($("#chaty-social-Chatway").length && document.visibilityState === 'visible') {
                get_chatway_status();
            }
        });

        $(".add-live-chat-btn").on("click", function (){
            $("#chatyway-info-popup").hide();
        });

        $(document).on("change", 'input[name="cht_social_Chatway[chatway_icon]"]:checked', function(){
            let chatwayIcon = $('label[for="icon-'+$(this).val()+'"] .chatway-icon').html();
            let iconColor = $('input[name="cht_social_Chatway[bg_color]"]').val();
            let socId = 'Chatway';
            $("#chaty-social-Chatway .chaty-main-svg").html(chatwayIcon);
            $("#chaty-social-Chatway .chaty-main-svg .color-element").css({'fill': iconColor});
            jQuery('#cht_social_image_' + socId).val("");
            jQuery('#cht_social_image_src_' + socId).attr("src", default_image);
            jQuery("#chaty_image_"+socId).removeClass("icon-active").removeClass("img-active");
            jQuery("#chaty_image_"+socId+ " .fa-icon").val("");
            change_custom_preview();
        });

        $(document).on("click", "#chatway-custom-image", function(e){
            e.preventDefault();
            let selectedsocialSlug = 'Chatway';
            var image = wp.media({
                title: 'Upload Image',
                // mutiple: true if you want to upload multiple files at once
                multiple: false,
                library: {
                    type: 'image',
                }
            }).open()
                .on('select', function (e) {
                    var uploaded_image = image.state().get('selection').first();
                    let imageData = uploaded_image.toJSON();
                    $('#cht_social_image_' + selectedsocialSlug).val(imageData.id);
                    $('.custom-image-' + selectedsocialSlug + " img").attr("src", imageData.url);
                    $('.chatway-custom-image img').attr('src', imageData.url);
                    $("#chaty_image_" + selectedsocialSlug).addClass("img-active").removeClass("icon-active");
                    $("#chaty_image_"+selectedsocialSlug+ " .fa-icon").val("");
                    $(".chatway-custom-image").addClass('img-active')
                    change_custom_preview();
                    $("#chatway-custom-image").prop('checked', true)
                });
        });

        $(document).on("click", "#chatway-remove-custom-image", function(e){
            $(".chatway-custom-image").removeClass('img-active');
            $("#chaty_image_Chatway").removeClass("img-active").removeClass("icon-active");
            $("#chaty_image_Chatway .fa-icon").val("");
            $('#cht_social_image_Chatway').val('');
        })

        $(document).on("click", ".remove--chatway", function(e){
            e.preventDefault();
            $("#chaty-social-Chatway").remove();
            $(".chat-channel-Chatway").removeClass("active");
            change_custom_preview();
            $("#remove-chatway-channel").hide();
            // call when any channel is removed or updated
            const channel_list4 = [];
            jQuery('.channels-icons > .icon.active').each( (i, item) => {
                channel_list4.push( item.dataset.social );
            } )
            wp.hooks.doAction('chaty.channel_update', {
                channel     : channel_list4,        // active channel list
                target      : 'Chatway',            // channel that removed last
                action      : 'removed',            // added || removed,
                isExceeded  : false,
            });
        });



        $(document).on("change", "input[name='cht_position']:checked", function(){
            if($(this).val() == "custom") {
                $("#positionPro").show();
            } else {
                $("#positionPro").hide();
            }
            change_custom_preview();
        });

        jQuery(".add-live-chat-btn").on("click", function (){
            jQuery("#chatyway-info-popup").hide();
        });

        var InMobile = (/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|ipad|iris|kindle|Android|Silk|lge |maemo|midp|mmp|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino/i.test(navigator.userAgent) || /1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i.test(navigator.userAgent.substr(0, 4))) ? 1 : 0;

        $(document).on("change", "input[name='cta_type']:checked", function(){
            if($(this).val() == "simple-view") {
                $("#simple-view").removeClass("hide-it");
                $("#chat-view").addClass("hide-it");
                $(".chaty-chat-view-option").removeClass("hide-option");
                $("#cta-header-color-setting").addClass("hide-it");
            } else {
                $("#simple-view").addClass("hide-it");
                $("#chat-view").removeClass("hide-it");
                $(".chaty-chat-view-option").addClass("hide-option");
                $("#cta-header-color-setting").removeClass("hide-it");
            }

            change_custom_preview();
        });

        $(document).on("click", ".add-properties", function(e){
            e.preventDefault();
            $(this).closest(".properties-box").toggleClass("active");
        });

        $(document).on("click", ".properties-list a", function(e){
            e.preventDefault();
            var inputText = $.trim($(this).data("txt"));
            if($(this).closest(".form-horizontal__item").hasClass("has-iframe")) {
                var iframeData = $(this).closest(".form-horizontal__item").find("iframe");
                if(iframeData.contents().find('p').length) {
                    iframeData.contents().find('p:last-child').append(" "+inputText);
                } else {
                    iframeData.contents().find('.mce-content-body').append(" "+inputText);
                }
            } else {
                inputText = $.trim($.trim($(this).closest(".form-horizontal__item").find(".add-properties").val()) + " "+ inputText);
                $(this).closest(".form-horizontal__item").find(".add-properties").val(inputText);
            }

            change_custom_preview();
        });

        $(document).on("click", "#cta_woocommerce_status", function(){
            if($(this).is(":checked")) {
                $(".woocommerce-settings").addClass("active");
            } else {
                $(".woocommerce-settings").removeClass("active");
            }
        });

        $(".form-fonts").SumoSelect({
            placeholder: 'Select font family',
            csvDispCount: 3
        });

        $(".url-options, .url-select, .country-list").SumoSelect();

        $("#chaty_attention_effect, #chaty_default_state").SumoSelect({
            placeholder: 'Select font family',
            csvDispCount: 3
        });

        $(document).on("click", "#chaty-social-channel", function(){
            $("#current_step").val(0);
        });

        $(document).on("click", "#chaty-app-customize-widget", function(){
            $("#current_step").val(1);
        });

        $(document).on("click", "#chaty-triger-targeting", function(){
            $("#current_step").val(2);
        });

        jQuery(document).on("click", "#update-chaty-traffic-source-rule", function(e){
            jQuery(".traffic-options-box").addClass("active");
            jQuery("#chaty_traffic_source").val("yes");
        });
        jQuery(document).on("click", "#remove-traffic-rules", function(e){
            jQuery(".traffic-options-box").removeClass("active");
            jQuery("#chaty_traffic_source").val("no");
        });
        jQuery(document).on("click", ".remove-traffic-option", function(e){
            jQuery(this).closest(".custom-traffic-rule").remove();
        });

        $(document).on("click", ".field-setting-col:not(.hide-label-setting) .field-label", function(e){
            e.preventDefault();
            $(this).closest(".label-flex").toggleClass("input-active");
        });

        $(document).on("click", ".chaty-switch-toggle", function(){
            setTimeout(function(){
                $(".chaty-field-setting").each(function(){
                    if($(this).is(":checked")) {
                        $(this).closest(".field-setting-col").find(".field-settings").addClass("active");
                        $(this).closest(".field-setting-col").removeClass("hide-label-setting");
                    } else {
                        $(this).closest(".field-setting-col").find(".field-settings").removeClass("active");
                        $(this).closest(".field-setting-col").addClass("hide-label-setting");
                    }
                });
            },100);
        });



        $(document).on("change", ".chaty-close_form_after-setting", function(){
            setTimeout(function(){
                $(".chaty-close_form_after-setting").each(function(){
                    if($(this).is(":checked")) {
                        $(this).closest(".form-field-setting-col").find(".close_form_after-settings").addClass("active");
                    } else {
                        $(this).closest(".form-field-setting-col").find(".close_form_after-settings").removeClass("active");
                    }
                });
            },100);
        });

        $(document).on("click", ".agent-button-action, .add-agent-btn", function(e){
            e.preventDefault();
            jQuery("#agent-popup").show();
        });

        $(document).on("keyup", "#channel_input_Whatsapp", function(){
            if($.trim($(this).val()) != "") {
                var iti = cht_settings.channel_settings['Whatsapp_Country'];
                var data = iti.getSelectedCountryData()
                var value = $.trim($(this).val());
                if(value[0] != "+") {
                    value = "+"+value;
                    $(this).val(value);
                    $(this).trigger("change");
                }

                if (Reflect.has(data, 'dialCode')) {
                    var dialCode = data.dialCode;

                    if (value.length > dialCode.length + 1 && !value.includes('-') && value.startsWith(`+${dialCode}`)) {
                        var number = value.replace(`+${dialCode}`, '')
                        number = number.replaceAll(" ", "");
                        $(this).val('+' + dialCode + '-' + number);
                    }
                }

                if(data.dialCode) {
                    if ((value.length <= data.dialCode.length + 1)) {
                        $(this).closest(".test-btn").find(".wf-test-button").removeClass("active");
                    } else {
                        $(this).closest(".test-btn").find(".wf-test-button").addClass("active");
                    }
                }

                if(value.length > 0 && !data.dialCode) {
                    $(this).closest(".test-btn").find(".wf-test-button").removeClass("active");
                }

                if(value[0] == "+" && $(this).val().length == 1) {
                    $(this).closest(".channels__input-box").find(".iti__selected-flag").find(".iti__flag").attr("class", "iti__flag");
                    $(this).closest(".channels__input-box").find(".iti__selected-flag").attr("title", "");
                }
            } else if($.trim($(this).val()) == "") {
                $(this).closest(".channels__input-box").find(".iti__selected-flag").find(".iti__flag").attr("class", "iti__flag");
                $(this).closest(".channels__input-box").find(".iti__selected-flag").attr("title", "");
            }
        });

        $(document).on("click", ".remove-zero-btn", function(){
            let inputVal = $("#channel_input_Whatsapp").val();
            inputVal = inputVal.replace(/-0+/, '-');
            $("#channel_input_Whatsapp").val(inputVal);
            $(".chaty-popup").hide();
            isWhatsAppValidated = true;
            checkPreSettings();
        });

        $(document).on("change", ".custom-channel-Whatsapp", function(){
            isWhatsAppValidated = false;
        });

        $(document).on("change", ".chaty-redirect-setting", function(){
            setTimeout(function(){
                $(".chaty-redirect-setting").each(function(){
                    if($(this).is(":checked")) {
                        $(this).closest(".form-field-setting-col").find(".redirect_action-settings").addClass("active");
                    } else {
                        $(this).closest(".form-field-setting-col").find(".redirect_action-settings").removeClass("active");
                    }
                });
            },100);
        });

        $(document).on("click", ".email-setting", function(){
            setTimeout(function(){
                $(".email-setting-field").each(function(){
                    if($(this).is(":checked")) {
                        $(this).closest(".form-field-setting-col").find(".email-settings").addClass("active");
                    } else {
                        $(this).closest(".form-field-setting-col").find(".email-settings").removeClass("active");
                    }
                });
            },100);
        });

        // call when any channel is removed or updated
        const channel_list3 = [];
        jQuery('.channels-icons > .icon.active').each( (i, item) => {
            channel_list3.push( item.dataset.social );
        } )

        wp.hooks.doAction('chaty.channel_update', {
            channel     : channel_list3,      // active channel list
            target      : null,               // channel that removed last
            action      : 'added',            // added || removed,
            isExceeded  : false,
        });

        setTimeout(() => {
            $(".whatsapp-welcome-message iframe")
            .contents().find('body')
            .css({ backgroundColor: '#fff' });
        }, 100)

        $(document).on("click", ".chaty-embedded-window", function(){
            setTimeout(function(){
                $(".embedded_window-checkbox").each(function(){
                    if($(this).is(":checked")) {
                        $(this).closest("li.chaty-channel").find(".whatsapp-welcome-message").addClass("active");
                        // make the welcome message input box background white
                        // issues: It used to take the default background color of the theme like twenty twenty one
                        const 
                            iframe = $(this).closest("li.chaty-channel").find(".whatsapp-welcome-message iframe");
                            iframe.contents().find('body').css({
                                backgroundColor: '#fff'
                            });

                        var buttonHtml = $(".csaas-widget").find(".csaas-cta-close").find("button").html();
                        var dataForm = "csaas-form-0-Whatsapp";
                        $("#" + dataForm).addClass("is-active");

                        $(".csaas-widget").removeClass("csaas-open");
                        $(".csaas").addClass("form-open");
                        $("#" + dataForm).addClass("active");

                        $(".csaas-widget").find(".open-csaas-channel").html(buttonHtml);
                    } else {
                        $(this).closest("li.chaty-channel").find(".whatsapp-welcome-message").removeClass("active");

                        var dataForm = "csaas-form-0-Whatsapp";
                        $("#" + dataForm).removeClass("is-active");

                        $(".csaas-widget").addClass("csaas-open");
                        $(".csaas").removeClass("form-open");
                        $("#" + dataForm).removeClass("active");
                    }
                });
            },300);
        });

        jQuery("input[name='switchPreview']:checked").trigger("change");

        $(document).on("change", "#cht-form input", function(){
            whatsappStatus = false;
            phoneStatus = false;
            fbStatus = false;
            smsStatus = false;
            viberStatus = false;
            phoneNumberStatus = false;
        });

        $(document).on("click", ".remove-js-script", function(e){
            e.preventDefault();
            $(this).closest(".channels__input-box").find("input").val("");
            $(this).closest(".channels__input-box").find("input").removeClass("cht-input-error").removeClass("cht-js-error");
            $(this).closest(".channels__input-box").find(".cht-error-message").remove();
            $(this).remove();
        });

        $("#cht-form").on("submit", function () {
            if(forceSubmit) {
                return true;
            }
            $(this).find(".cht-input-error").removeClass("cht-input-error");
            $(this).find(".cht-js-error").removeClass("cht-input-error");
            $(this).find(".cht-error-message").remove();
            $(this).find(".remove-js-script").remove();
            set_social_channel_order();
            var errorCount = 0;
            if ($("#chaty-page-options .cht-required").length) {
                $("#chaty-page-options .cht-required").each(function () {
                    if (jQuery.trim($(this).val()) == "") {
                        $(this).addClass("cht-input-error");
                        errorCount++;
                    }
                });
            }
            if ($(".chaty-data-and-time-rules .cht-required").length) {
                $(".chaty-data-and-time-rules .cht-required").each(function () {
                    if (jQuery.trim($(this).val()) == "") {
                        $(this).addClass("cht-input-error");
                        errorCount++;
                    }
                });
            }
            if (!cht_nonce_ajax.has_js_access) {
                $("#channels-selected-list .channels__input").each(function(){
                    if($.trim($(this).val()) != "") {
                        if(($.trim($(this).val()).toLowerCase()).indexOf("javascript") != -1) {
                            $("body, html").scrollTop(0);
                            $("#chaty-social-channel").trigger("click");
                            $(this).addClass("cht-input-error cht-js-error");
                            $(this).after("<span class='cht-error-message'>"+cht_nonce_ajax.js_message+"</span><a href='#' class='remove-js-script'>"+cht_nonce_ajax.remove+"</a>")
                            errorCount++;
                        }
                    }
                });
            }
            if(errorCount == 0) {
                return checkForWhatsAppNumber();
            } else {
                $(".cht-input-error:first").focus();
                return false;
            }
        });

        $(".chaty-popup-outer").on("click", function(e){
            $(".chaty-popup").hide();
        });
        $(".check-for-numbers").on("click", function(){
            checkPreSettings();
        });
        $(".check-for-device").on("click", function(){
            checkForDevices();
        });
        $(".check-for-triggers").on("click", function(){
            checkForTriggers();
        });
        $(".check-for-status").on("click", function(){
            checkForStatus();
        });
        $(".change-status-and-save").on("click", function(){
            $(".cht_active").prop("checked", true);
            forceSubmit = true;
            $(".chaty-popup").hide();
            $("#cht-form").trigger("submit");
        });
        $(".status-and-save").on("click", function(){
            $(".cht_active").prop("checked", false);
            forceSubmit = true;
            $(".chaty-popup").hide();
            $("#cht-form").trigger("submit");
        });

        jQuery(document).on("click", "#create-date-rule", function(e){
            jQuery("#date-schedule").addClass("active");
            jQuery("#cht_date_rules").val("yes");
        });
        jQuery(document).on("click", "#remove-date-rule", function(e){
            jQuery("#date-schedule").removeClass("active");
            jQuery("#cht_date_rules").val("no");
        });


        jQuery(document).on("click", ".wf-test-button", function (){
            var slug = jQuery(this).data('slug');
            jQuery("#"+slug+"_popup").addClass("active");
            var link = "";
            var value = $(".custom-channel-"+slug).val();
            console.log(value);

            if(slug == 'Whatsapp') {
                var val = value.replaceAll("+", "").replaceAll(" ", "").replaceAll("-", "");
                if(InMobile) {
                    link = "https://wa.me/" + val;
                } else {
                    link = "https://web.whatsapp.com/send?phone=" + val;
                }
                $("#"+slug+"_popup").find(".phone-value").text(value);
            }
            if(slug == "Facebook_Messenger") {
                link = value;
                $("#"+slug+"_popup").find(".phone-value").text(value);
            }


            window.open(link, '_blank');
        });

        $(document).on("click", ".edit-number", function (){
            var slug = $(this).closest(".test-popup").data("label");
            $(".custom-channel-"+slug).focus();
            $(".test-popup").removeClass("active");
        });

        jQuery(document).on("click", ".test-popup-bg, .test-popup-close-btn, .test-popup .save-btn", function (){
            jQuery(".test-popup").removeClass("active");
        });

        $(document).on("keyup", ".custom-channel-Facebook_Messenger", function (){
            if($(this).val() != "") {
                $(this).closest(".test-btn").find(".wf-test-button").addClass("active");
            } else {
                $(this).closest(".test-btn").find(".wf-test-button").removeClass("active");
            }
        });

        $(document).on("click", ".test-popup .contact-link", function (){
            $(".chaty-help-form").addClass("active");
            $(".test-popup").removeClass("active");
        });

        $(document).on("keyup", "#wp_popup_headline, #wp_popup_nickname", function (){
            change_custom_preview();
        });

        var customImageFor = "";
        $(document).on("click", ".upload-wp-profile", function (){
            customImageFor = $(this).data("for");
            var image = wp.media({
                title: 'Select Whatsapp Profile',
                multiple: false,
                library: {
                    type: 'image',
                }
            }).open()
                .on('select', function (e) {
                    var uploaded_image = image.state().get('selection').first();
                    var imageData = uploaded_image.toJSON();
                    $("#"+customImageFor+"-custom-image-upload .img-value").val(imageData.url);
                    $("#"+customImageFor+"-custom-image-upload").addClass("active");
                    $("#"+customImageFor+"-custom-image-upload .image-info").html("<img src='"+imageData.url+"'>");
                    change_custom_preview();
                });
        });

        $(document).on("click", ".remove-custom-img", function (){
            $(this).closest(".custom-img-upload").removeClass("active");
            $(this).closest(".custom-img-upload").find(".image-info").html("");
            $(this).closest(".custom-img-upload").find(".img-value").val("");
            change_custom_preview();
        });

        checkForUserCountry();
    });
});

function checkForUserCountry() {
    var userCountry = getUserCountry();
    if(userCountry != "") {
        setWhatsAppCountryFlag();
    }
}

function getUserCountry() {
    var clientCountry = chatyGetCookie("cht_country_code");
    if(!clientCountry) {
        setClientCountry();
    }
    return clientCountry;
}

function setClientCountry() {
    var clientCountry = chatyGetCookie("cht_country_code");
    if(!clientCountry) {
        var $apiURL = 'https://www.cloudflare.com/cdn-cgi/trace';
        jQuery.get($apiURL, function (countryData) {
            if (countryData) {
                var countryCode = "-";
                var countryInfo = countryData.match("loc=(.*)");
                if (countryInfo.length > 1) {
                    countryInfo = countryInfo[1];
                    if (countryInfo) {
                        countryCode = countryInfo.toUpperCase();
                        if (countryCode == "") {
                            countryCode = "-";
                        }
                    }
                }
            }
            chatySetCookie("cht_country_code", countryCode, 30 * 24);
            setWhatsAppCountryFlag();
        });
    }
}

function setWhatsAppCountryFlag() {
    if(jQuery("#channel_input_Whatsapp").length) {
        cht_settings.channel_settings['Whatsapp'] = document.querySelector("#channel_input_Whatsapp");
        cht_settings.channel_settings['Whatsapp_Country'] = window.intlTelInput(cht_settings.channel_settings['Whatsapp'], {
            formatOnDisplay: false,
            hiddenInput: 'full_number',
            initialCountry: 'auto',
            nationalMode: false,
            autoHideDialCode: false,
            utilsScript: cht_settings.plugin_url + "admin/assets/js/utils.js",
        });

        var user_country = getUserCountry();
        if(user_country != "-" && jQuery("#channel_input_Whatsapp").val() == "") {
            setTimeout(function(){
                cht_settings.channel_settings['Whatsapp_Country'].setCountry(user_country);
                jQuery("#channel_input_Whatsapp").trigger("keyup");
            }, 200);
        } else if(jQuery("#channel_input_Whatsapp").val() != "") {
            setTimeout(function(){
                jQuery("#channel_input_Whatsapp").trigger("keyup");
            }, 200);
        }
    }

    if(jQuery(".custom-channel-Whatsapp:not(#channel_input_Whatsapp)").length) {
        jQuery(".custom-channel-Whatsapp:not(#channel_input_Whatsapp)").each(function(){
            var dataSlag = jQuery(this).closest("li.chaty-channel").data("id");
            if(dataSlag != undefined) {
                if(jQuery("#channel_input_"+dataSlag).length) {
                    cht_settings.channel_settings[dataSlag] = document.querySelector("#channel_input_"+dataSlag);
                    window.intlTelInput(cht_settings.channel_settings[dataSlag], {
                        dropdownContainer: document.body,
                        formatOnDisplay: true,
                        hiddenInput: "full_number",
                        initialCountry: "auto",
                        nationalMode: false,
                        utilsScript: cht_settings.plugin_url + "admin/assets/js/utils.js",
                    });
                }
            }
        });
    }
}

function check_for_number_chaty(phoneNumber, validationFor) {
    if (phoneNumber != "") {
        if (phoneNumber[0] == "+") {
            phoneNumber = phoneNumber.substr(1, phoneNumber.length)
        }
        if (validationFor == "Phone") {
            if (phoneNumber[0] == "*") {
                phoneNumber = phoneNumber.substr(1, phoneNumber.length)
            }
        }
        if (isNaN(phoneNumber)) {
            return true;
        }
    }
    return false;
}

(function ($) {
    var closeAction = 0;

    jQuery(window).on('popstate', function(event) {
        window.onbeforeunload = null;
        if(window.history && window.history.pushState) {
            window.history.back();
        }
    });

    jQuery(document).ready(function () {
        if(!jQuery(".chaty-table").length) {
            jQuery('body input, body .icon, body textarea, body .btn-cancel:not(.close-btn-set) ').on("click", function (event) {
                window.onbeforeunload = function (e) {
                    e = e || window.event;
                    e.preventDefault = true;
                    e.cancelBubble = true;
                    e.returnValue = 'Your beautiful goodbye message';
                };
            });
        }

        jQuery(document).on('submit', 'form', function (event) {
            window.onbeforeunload = null;
        });

        jQuery(document).on('change', '.channel-select-input', function (event) {
            var selChannel = $(this).closest("li").attr("data-id");
            jQuery.ajax({
                type: 'POST',
                url: ajaxurl,
                dataType: 'json',
                data: {
                    social: jQuery(this).val(),
                    channel: selChannel,
                    action: 'get_chaty_settings'
                },
                success: function (response) {
                    if(response.status == 1) {
                        if(response.data.slug == "Whatsapp") {
                            if($("#channel_input_"+response.channel).length) {
                                cht_settings.channel_settings[response.channel] = document.querySelector("#channel_input_"+response.channel);
                                window.intlTelInput(cht_settings.channel_settings[response.channel], {
                                    dropdownContainer: document.body,
                                    formatOnDisplay: true,
                                    hiddenInput: "full_number",
                                    initialCountry: "auto",
                                    nationalMode: false,
                                    utilsScript: cht_settings.plugin_url + "admin/assets/js/utils.js",
                                });
                            }
                        } else {
                            $("#chaty-social-"+response.channel+" .channels__input-box").html("<input type='text' class='channels__input' name='cht_social_"+response.channel+"[value]' id='channel_input_"+response.channel+"' />");
                        }
                        jQuery(".custom-icon-"+response.channel+" svg").html(response.data.svg);
                        jQuery("#chaty-social-"+response.channel).attr("data-channel", response.data.slug);
                        jQuery("#chaty-social-"+response.channel).find(".sp-preview-inner").css("background-color", response.data.color);
                        jQuery("#chaty-social-"+response.channel).find(".chaty-color-field").val(response.data.color);
                        jQuery("#chaty-social-"+response.channel).find(".channels__input").attr("placeholder", response.data.placeholder);
                        jQuery("#chaty-social-"+response.channel).find(".channel-example").text(response.data.example);
                        jQuery("#chaty-social-"+response.channel).find(".chaty-title").val(response.data.title);
                        jQuery("#chaty-social-"+response.channel).find(".icon").attr("data-title", response.data.title);
                        jQuery("#chaty-social-"+response.channel).find(".chaty-color-field").trigger("change");
                        jQuery(".help-section").html("");
                        if(response.data.help_link != "") {
                            jQuery(".help-section").html('<div class="viber-help"><a target="_blank" href="'+response.data.help_link+'">'+response.data.help_title+'</a></div>');
                        } else if(response.data.help_text != "") {
                            jQuery(".help-section").html('<div class="viber-help"><span class="help-text">'+response.data.help_text+'</span><span class="help-title">'+response.data.help_title+'</span></div>');
                        }
                    }
                }
            })
        });

        jQuery(document).on("click", "#chaty_icons_view", function(e){
            jQuery(".page-body .chaty-widget").removeClass("vertical").removeClass("horizontal");
            jQuery(".page-body .chaty-widget").addClass(jQuery(this).val());
        });

        jQuery('.upg').on("click", function (event) {
            jQuery('.valid_domain_input').val(jQuery('.valid_domain_input').val().replace(' ', ''));
            if (!/^(http(s)?:\/\/)?(www\.)?[a-z0-9]+([\-\.]{1}[a-z0-9]+)*\.[a-z]{2,5}(:[0-9]{1,5})?(\/.*)?$/.test(jQuery('.valid_domain_input').val())) {
                event.preventDefault();
                jQuery('.valid_domain').fadeIn().css({
                    display: 'block'
                });
            }
        });

        jQuery(document).on("blur", "#channels-selected-list > li:not(#chaty-social-close) .channels__input", function(){
            if(jQuery(this).hasClass("border-red") && jQuery(this).val() != "") {
                jQuery(this).removeClass("border-red");
            }
        });

        var count_click = 1000000003;
        jQuery('.show_up').on("click", function () {
            count_click += 10;
            jQuery('#upgrade-modal').css({
                'z-index': count_click,
                display: 'block',
                'margin-left': '-258px'
            });
        });

        (function colorPicker() {
            jQuery('.color-picker-btn, .color-picker-btn-close, .color-picker-custom button').on('click', function (e) {
                e.preventDefault();
                jQuery('.color-picker-box').toggle();
                jQuery('.color-picker-btn').toggle();
            });

            // hide color input when use click outside
            $(window).on('click', function(ev){
                if( jQuery(ev.target).parents('.color-picker-dropdown').length === 0 ) {
                    jQuery('.color-picker-btn').show();
                    jQuery('.color-picker-box').hide()
                }
            })

            jQuery('.color-picker-radio input').on("change", function () {
                var $this = jQuery(this);
                jQuery('.color-picker-custom input[name="cht_custom_color"]').val('');
                jQuery('.color-picker-custom .circle').html('?').css({
                    'background-color': '#fff'
                });
                if ($this.prop('checked')) {
                    jQuery('.color-picker-radio input').prop('checked', false);
                    $this.prop('checked', true);
                    var color = $this.val();
                    var title = $this.prop('title');
                } else {
                    color = jQuery('.color-picker-custom input').val();
                    title = 'Custom';
                }

                if(color != "") {
                    var hashExists = color.indexOf("#");
                    if (hashExists == -1) {
                        color = "#" + color;
                    }
                }
                jQuery('.color-picker-btn .circle').css({backgroundColor: color});
                jQuery('.color-picker-btn .text').text(title);
                jQuery('#chaty-social-close ellipse').attr("fill", color);
            });

            jQuery('.color-picker-custom input').on("change", function () {
                jQuery('.color-picker-radio input').prop('checked', false);

                var $this = jQuery(this);

                var color = $this.val();

                if(color != "") {
                    var hashExists = color.indexOf("#");
                    if (hashExists == -1) {
                        color = "#" + color;
                    }
                }
                jQuery('.color-picker-btn .circle').css({backgroundColor: color});
                jQuery('.color-picker-btn .text').text('Custom');
                jQuery('#chaty-social-close ellipse').attr("fill", color);
            });
        }());

        (function customSelect() {
            jQuery('[name="cht_position"]').on("change", function () {
                if (jQuery('#positionCustom').prop('checked')) {
                    jQuery('#positionPro').show();
                } else {
                    jQuery('#positionPro').hide();
                }
            });
        }());


        /**
         * add Token
         */

        var AddTokenBtn = jQuery('.update_token');

        AddTokenBtn.on('click', function (e) {
            e.preventDefault();
            var token = jQuery('input[name="cht_token"]').val();

            $.ajax({
                type: 'POST',
                url: ajaxurl,
                data: {
                    action: 'add_token',
                    nonce_code: cht_nonce_ajax.cht_nonce,
                    token: token
                },
                beforeSend: function (xhr) {

                },
                success: function (bool) {
                    if (bool) {
                        alert('Your pro plan is activated');
                        location.reload();
                    } else {
                        alert('You`ve entered a wrong token');
                    }
                },
                error: function (xhr, status, error) {

                }
            });
        });
    });
}(jQuery));

(function ($) {
    jQuery(document).ready(function () {
        two_soc();

        var socialIcon = jQuery('.channels-icons > .icon-sm:not(.chat-channel-Chatway)');

        var socialInputsContainer = jQuery('.social-inputs');

        var click = 0;
        jQuery('input[name=cht_custom_color]').on("keyup", function (event) {
            var color = jQuery(this).val();
            jQuery('.circle').html('');
            if(color != "") {
                var hashExists = color.indexOf("#");
                if(hashExists == -1) {
                    color = "#"+color;
                }
                jQuery('.color-picker-custom .circle').css({
                    'background-color': color
                });
            }
            if (jQuery(this).val().length < 1) {
                jQuery('.color-picker-custom .circle').html('?');
            }
        });
        socialIcon.on('click', function () {

            jQuery('.popover').removeClass("shake-it");

            ++click;
            two_soc();

            var $this = jQuery(this);

            var social = $this.data('social');

            var socialItem = socialInputsContainer.find('.social-form-group');

            if ($this.hasClass('active')) {
                var del = ',' + jQuery(this).attr('data-social');

                var newlocaldata = jQuery('.add_slug').val();
                newlocaldata = newlocaldata.replace(del, '');
                jQuery('.add_slug').val(newlocaldata);
                newlocaldata = newlocaldata.replace(del, '');
                jQuery('.add_slug').val(newlocaldata);
                newlocaldata = newlocaldata.replace(del, '');
                jQuery('.add_slug').val(newlocaldata);
                newlocaldata = newlocaldata.replace(del, '');


                jQuery('.add_slug').val(newlocaldata);
                $this.toggleClass('active');

                // call when any channel is removed or updated
                const channel_list = [];
                jQuery('.channels-icons > .icon.active').each( (i, item) => {
                    channel_list.push( item.dataset.social );
                } )
                wp.hooks.doAction('chaty.channel_update', {
                    channel     : channel_list,         // active channel list
                    target      : social,               // channel that removed last
                    action      : 'removed',            // added || removed,
                    isExceeded  : false,
                }); 
                
                return;
            }
            socialIcon.addClass('disabled');
            icon = jQuery(this).data('social');

            if (jQuery('.add_slug').val().indexOf(icon) == '1' && jQuery('.add_slug').val() != '') {
                var del = ',' + icon;
                var newlocaldata = jQuery('.add_slug').val();

                newlocaldata = newlocaldata.replace(del, '');
                jQuery('.add_slug').val(newlocaldata);
                newlocaldata = newlocaldata.replace(del, '');
                jQuery('.add_slug').val(newlocaldata);
                newlocaldata = newlocaldata.replace(del, '');
                jQuery('.add_slug').val(newlocaldata);
            } else {
                jQuery('.add_slug').val(jQuery('.add_slug').val() + ',' + jQuery(this).attr('data-social'));
            }

            $this.toggleClass('active');

            $.ajax({
                type: 'POST',
                dataType: 'json',
                url: ajaxurl,
                data: {
                    action: 'choose_social',
                    social: social,
                    nonce_code: cht_nonce_ajax.cht_nonce,
                    version: 'free',
                    widget_index: jQuery("#widget_index").val()
                },
                beforeSend: function (xhr) {

                },
                success: function (data) {
                    var item = jQuery(data);
                    var itemName = item.find('.icon').data('title');
                    var itemChannel = item.data('channel');

                    if (!jQuery('.channels-selected div[data-social="' + itemName + '"]').length) {
                        jQuery('#chaty-social-close').before(item);
                        if(jQuery("#chaty-social-"+social+" .chaty-whatsapp-setting-textarea").length) {
                            editorId = jQuery("#chaty-social-"+social+" .chaty-whatsapp-setting-textarea").attr("id");
                            tinymce.init({
                                selector: jQuery("#chaty-social-"+social+" .chaty-whatsapp-setting-textarea").attr("id"),
                                toolbar: 'bold, italic, underline | emoji',
                                menubar: false,
                                branding: false,
                                setup: function(editor) {

                                    editor.addButton('emoji', {
                                        image: cht_nonce_ajax.icon_img,
                                        onclick: insertEmoji,
                                        classes: 'emoji-custom-icon'
                                    });

                                    editor.on('keyup', function (e){
                                        change_custom_preview();
                                        jQuery("#csaas-form-0-Whatsapp .csaas-whatsapp-message .csaas-whatsapp-message-content").html(editor.getContent());
                                    });

                                    function insertEmoji() {
                                        const { createPopup } = window.picmoPopup;
                                        const trigger = jQuery(".mce-emoji-custom-icon button").attr("id");
                                        const trig = document.querySelector("#"+trigger);

                                        const picker = createPopup({}, {
                                            referenceElement: trig,
                                            triggerElement: trig,
                                            position: 'right-start',
                                            hideOnEmojiSelect: false
                                        });

                                        picker.toggle();

                                        picker.addEventListener('emoji:select', (selection) => {
                                            let editor = tinyMCE.get(editorId);
                                            if(!editor.selection.getNode() || editor.selection.getNode() === editor.getBody()) {
                                                editor.focus();
                                                // Move the cursor to the end of the content
                                                editor.selection.select(editor.getBody(), true);
                                                editor.selection.collapse(false);
                                                tinymce.activeEditor.execCommand('mceInsertContent', false, selection.emoji);
                                            } else {
                                                tinymce.activeEditor.execCommand('mceInsertContent', false, selection.emoji);
                                            }
                                            change_custom_preview();
                                            jQuery("#csaas-form-0-Whatsapp .csaas-whatsapp-message .csaas-whatsapp-message-content").html(editor.getContent());
                                        });
                                    }

                                }
                            });
                            tinymce.execCommand( 'mceAddEditor', true, editorId);
                        }
                    }

                    socialIcon.removeClass('disabled');
                    $("#iconWidget").show();
                    detectIcon();
                    two_soc();
                    set_social_channel_order();
                    check_for_chaty_close_button();

                    // if(social == "Whatsapp") {
                        if($("#channel_input_Whatsapp").length) {
                            cht_settings.channel_settings['Whatsapp'] = document.querySelector("#channel_input_Whatsapp");
                            cht_settings.channel_settings['Whatsapp_Country'] = window.intlTelInput(cht_settings.channel_settings['Whatsapp'], {
                                formatOnDisplay: false,
                                hiddenInput: 'full_number',
                                initialCountry: 'auto',
                                nationalMode: false,
                                autoHideDialCode: false,
                                utilsScript: cht_settings.plugin_url + "admin/assets/js/utils.js",
                            });

                            var user_country = getUserCountry();
                            if(user_country != "-" && $("#channel_input_Whatsapp").val() == "") {
                                setTimeout(function(){
                                    cht_settings.channel_settings['Whatsapp_Country'].setCountry(user_country);
                                    $("#channel_input_Whatsapp").closest(".channels__input-box").find(".iti__selected-flag").find(".iti__flag").addClass("iti__"+user_country);
                                    $("#channel_input_Whatsapp").trigger("keyup");
                                }, 200);
                            } else if($("#channel_input_Whatsapp").val() != "") {
                                setTimeout(function(){
                                    $("#channel_input_Whatsapp").trigger("keyup");
                                }, 200);
                            }
                        }
                    // }

                    if(jQuery(".custom-channel-Whatsapp").length) {
                        jQuery(".custom-channel-Whatsapp").each(function(){
                            if(!jQuery(this).closest(".iti__flag-container").length) {
                                var dataChannel = jQuery(this).closest("li.chaty-channel").data("id");
                                if(jQuery("#channel_input_"+dataChannel).length) {
                                    cht_settings.channel_settings[dataChannel] = document.querySelector("#channel_input_" + dataChannel);
                                    window.intlTelInput(cht_settings.channel_settings[dataChannel], {
                                        dropdownContainer: document.body,
                                        formatOnDisplay: true,
                                        hiddenInput: "full_number",
                                        initialCountry: "auto",
                                        nationalMode: false,
                                        utilsScript: cht_settings.plugin_url + "admin/assets/js/utils.js",
                                    });
                                }
                            }
                        });
                    }

                    // trigger color picker
                    jQuery(document).trigger('chatyColorPicker/trigger', [{
                        $scope   : jQuery(`#chaty-social-${social}`), 
                        element  : '.chaty-color-field' 
                    }]);
                    
                    // call when any channel is removed or updated
                    const channel_list = [];
                    jQuery('.channels-icons > .icon.active').each( (i, item) => {
                        channel_list.push( item.dataset.social );
                    } )
                    wp.hooks.doAction('chaty.channel_update', {
                        channel     : channel_list, // active channel list
                        target      : social, // channel that newly added
                        action      : 'added', // added || removed,
                        isExceeded  : false,
                    }); 

                },
                error: function (xhr, status, error) {

                }
            });

            two_soc();
        });

        /**
         * Cancel Btn
         *
         */
        var cancelBtn = jQuery('body');

        cancelBtn.on('click', '.icon, .btn-cancel:not(.close-btn-set):not(.is-chatway)', function (e) {

            if (jQuery(this).hasClass("close-btn-set")) {
                return;
            }

            e.preventDefault();

            if (jQuery(this).hasClass('icon') && jQuery(this).hasClass('active')) {
                return;
            }

            icon = jQuery(this).data('social');
            if (jQuery(this).hasClass('btn-cancel')) {
                jQuery('.icon.active[data-social^="' + icon + '"]').removeClass('active');

                var del = ',' + icon;
                var newlocaldata = jQuery('.add_slug').val();
                newlocaldata = newlocaldata.replace(del, '');

                jQuery('.add_slug').val(newlocaldata);

                // call when any channel is removed or updated
                const channel_list = [];
                jQuery('.channels-icons > .icon.active').each( (i, item) => {
                    channel_list.push( item.dataset.social );
                } )
                wp.hooks.doAction('chaty.channel_update', {
                    channel     : channel_list,         // active channel list
                    target      : icon,               // channel that removed last
                    action      : 'removed',            // added || removed,
                    isExceeded  : false,
                }); 

            }
            var del_item = jQuery('#chaty-social-' + icon);
            del_item.remove();

            var item = jQuery(this).parent('.channels-selected__item');

            // $.ajax({
            //     type: 'POST',
            //     dataType: 'json',
            //     url: ajaxurl,
            //     data: {
            //         action: 'remove_social',
            //         nonce_code: cht_nonce_ajax.cht_nonce,
            //         social: social,
            //         widget_index: jQuery("#widget_index").val()
            //     },
            //     beforeSend: function (xhr) {
            //
            //     },
            //     success: function (bool) {
            //         if (bool) {
            //             item.closest("li").remove();
            //             del_item.remove();
            //
            //
            //             jQuery('.icon-sm').each(function () {
            //                 if (jQuery(this).data('social') === social) {
            //                     // jQuery(this).removeClass('active');
            //                 }
            //             });
            //             set_social_channel_order();
            //         }
            //         check_for_chaty_close_button();
            //     },
            //     error: function (xhr, status, error) {
            //
            //     }
            // });
            detectIcon();
            two_soc();
            set_social_channel_order();
            check_for_chaty_close_button();
        });

        function two_soc() {
            if (jQuery('section').is('#pro')) {
                return;
            }

            if (jQuery('.channels-selected__item').length <= 1) {
                jQuery('.channels-selected__item').hide();
            } else if (jQuery('.channels-selected__item').length >= 2) {
                jQuery('.channels-selected__item').show();
            }
        }

        jQuery('.btn-help').on("click", function (event) {
            window.open(
                'https://premio.io/help/chaty/',
                '_blank' // <- This is what makes it open in a new window.
            );
        });


        var freeCustomInput = jQuery('.free-custom-radio, .free-custom-checkbox');
        
        freeCustomInput.on('click', function (e) {
            e.stopPropagation();
        });
        var chatyCta = jQuery('[name=cht_cta]');
        var toolTip = jQuery('.preview .tooltip-show');

        chatyCta.on("keyup", function () {
            var $icon = jQuery('.preview .page .icon');
            if (chatyCta.val().length >= 1) {
                $icon.removeClass('no-tooltip');
            } else {
                $icon.addClass('no-tooltip');
            }
            toolTip.attr('data-title', chatyCta.val());
        });


        var baseIcon = '<svg version="1.1" id="ch" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="-496 507.7 54 54" style="enable-background:new -496 507.7 54 54;" xml:space="preserve">\n' +
                '                            <style type="text/css">.st0 {fill: #A886CD;}  .st1 {fill: #FFFFFF;}\n' +
                '                        </style><g><circle class="st0" cx="-469" cy="534.7" r="27"/></g><path class="st1" d="M-459.9,523.7h-20.3c-1.9,0-3.4,1.5-3.4,3.4v15.3c0,1.9,1.5,3.4,3.4,3.4h11.4l5.9,4.9c0.2,0.2,0.3,0.2,0.5,0.2 h0.3c0.3-0.2,0.5-0.5,0.5-0.8v-4.2h1.7c1.9,0,3.4-1.5,3.4-3.4v-15.3C-456.5,525.2-458,523.7-459.9,523.7z"/>\n' +
                '                                                    <path class="st0" d="M-477.7,530.5h11.9c0.5,0,0.8,0.4,0.8,0.8l0,0c0,0.5-0.4,0.8-0.8,0.8h-11.9c-0.5,0-0.8-0.4-0.8-0.8l0,0\n' +
                '                            C-478.6,530.8-478.2,530.5-477.7,530.5z"/>\n' +
                '                                                    <path class="st0" d="M-477.7,533.5h7.9c0.5,0,0.8,0.4,0.8,0.8l0,0c0,0.5-0.4,0.8-0.8,0.8h-7.9c-0.5,0-0.8-0.4-0.8-0.8l0,0\n' +
                '                            C-478.6,533.9-478.2,533.5-477.7,533.5z"/>\n' +
                '                        </svg>',
            defaultIcon = '<svg version="1.1" id="ch" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="-496 507.7 54 54" style="enable-background:new -496 507.7 54 54;" xml:space="preserve">\n' +
                '                            <style type="text/css">.st0 {fill: #A886CD;}  .st1 {fill: #FFFFFF;}\n' +
                '                        </style><g><circle class="st0" cx="-469" cy="534.7" r="27"/></g><path class="st1" d="M-459.9,523.7h-20.3c-1.9,0-3.4,1.5-3.4,3.4v15.3c0,1.9,1.5,3.4,3.4,3.4h11.4l5.9,4.9c0.2,0.2,0.3,0.2,0.5,0.2 h0.3c0.3-0.2,0.5-0.5,0.5-0.8v-4.2h1.7c1.9,0,3.4-1.5,3.4-3.4v-15.3C-456.5,525.2-458,523.7-459.9,523.7z"/>\n' +
                '                                                    <path class="st0" d="M-477.7,530.5h11.9c0.5,0,0.8,0.4,0.8,0.8l0,0c0,0.5-0.4,0.8-0.8,0.8h-11.9c-0.5,0-0.8-0.4-0.8-0.8l0,0\n' +
                '                            C-478.6,530.8-478.2,530.5-477.7,530.5z"/>\n' +
                '                                                    <path class="st0" d="M-477.7,533.5h7.9c0.5,0,0.8,0.4,0.8,0.8l0,0c0,0.5-0.4,0.8-0.8,0.8h-7.9c-0.5,0-0.8-0.4-0.8-0.8l0,0\n' +
                '                            C-478.6,533.9-478.2,533.5-477.7,533.5z"/>\n' +
                '                        </svg>',
            iconBlock = document.getElementById('iconWidget'),
            desktopIcon,
            mobileIcon,
            colorFill = jQuery('.color-picker-radio input:checked').val();

        jQuery(document).on("keyup", "textarea.test_textarea, .chaty-title", function(){
            detectIcon();
        });

        jQuery('.js-switch-preview').on('change', function () {
            if (getPreviewDesktop()) {
                jQuery(this).closest(".preview").removeClass('mobiel-view');
            } else {
                jQuery(this).closest(".preview").addClass('mobiel-view');
            }
            detectIcon();
        });

        jQuery(document).on("change","input[name='cht_pending_messages']", function(){
            if(jQuery("#cht_pending_messages").is(":checked")) {
                jQuery(".pending-message-items").addClass("active");
            } else {
                jQuery(".pending-message-items").removeClass("active");
            }
            detectIcon();
        });

        jQuery(document).on("change","#cht_number_of_messages", function(){
            detectIcon();
        });

        jQuery(document).on("change","#cht_number_color, #cht_number_bg_color", function(){
            detectIcon();
        });

        jQuery(document).on("keyup","#cht_number_of_messages", function(){
            detectIcon();
        });

        jQuery(document).on("blur","#cht_number_of_messages", function(){
            detectIcon();
        });

        function detectIcon() {
            change_custom_preview();
        }

        function stickyelement_iconformat(icon) {
            var originalOption = icon.element;
            return jQuery('<span><i class="' + icon.text + '"></i> ' + icon.text + '</span>');
        }

        function setIcon(icon, colorSelf) {
            if(!jQuery("#iconWidget").length) {
                return;
            }
            if (icon) {
                //jQuery('.preview .page .chaty-widget').show();
                iconBlock.innerHTML = icon;
            } else {
                //jQuery('.preview .page .chaty-widget').hide();
                iconBlock.innerHTML = '';
            }
            if (colorSelf) {
                var color = jQuery('.color-picker-custom input').val() ? jQuery('.color-picker-custom input').val() : jQuery('.color-picker-radio input:checked').val();
                if(color != "") {
                    var hashExists = color.indexOf("#");
                    if (hashExists == -1) {
                        color = "#" + color;
                    }
                }
                jQuery('.preview .page #iconWidget svg circle').css({fill: color});
                jQuery('.preview .page .chaty-close-icon ellipse').css({fill: color});
                jQuery('#chaty-social-close ellipse').attr("fill", color);
            }

            thisVal = jQuery("#chaty_default_state").val();
            if(thisVal == "open") {
                jQuery(".hide-show-button").addClass("active");
            } else {
                jQuery(".hide-show-button").removeClass("active");
            }

            jQuery(".chaty-widget").removeClass("active").removeClass("hover").removeClass("click").removeClass("hide_arrow");
            if(thisVal == "open") {
                jQuery(".chaty-widget").addClass("active").addClass("click");
                if(thisVal == "open" && jQuery(eClass+':checked').length > 1) {
                    jQuery(".chaty-widget").addClass("hide_arrow");
                }
            } else if(thisVal == "hover") {
                jQuery(".chaty-widget").addClass("hover");
            } else {
                jQuery(".chaty-widget").addClass("click");
            }

            jQuery(".chaty-channels").html("");
            var eClass = ".js-chanel-mobile";
            if (getPreviewDesktop()) {
                var eClass = ".js-chanel-desktop";
            }

            if(thisVal == "open" && jQuery(eClass+':checked').length > 1) {
                jQuery("#chaty_attention_effect").val("");
                jQuery("#chaty_attention_effect, .test_textarea").attr("disabled", true);
                jQuery("#chaty_attention_effect option:first-child").text("Doesn't apply for the open state");
                if(jQuery(".test_textarea").val() != "Doesn't apply for the open state") {
                    jQuery(".test_textarea").attr("data-value", jQuery(".test_textarea").val());
                }
                jQuery(".test_textarea").val("Doesn't apply for the open state");
                jQuery("#cht_number_of_messages").attr("disabled", true);
                jQuery("#cht_pending_messages").attr("disabled", true);
                jQuery(".disable-message").addClass("label-tooltip").addClass("icon");
                jQuery("#cht_pending_messages").attr("checked", false);
                jQuery(".pending-message-items").removeClass("active");
                jQuery(".cta-action-radio input").attr("disabled", true);
            } else {
                jQuery("#chaty_attention_effect, .test_textarea").attr("disabled", false);
                jQuery("#chaty_attention_effect option:first-child").text("None");
                jQuery(".test_textarea").attr("placeholder","");
                if(jQuery(".test_textarea").val() == "Doesn't apply for the open state") {
                    jQuery(".test_textarea").val(jQuery(".test_textarea").attr("data-value"));
                }
                jQuery("#cht_number_of_messages").attr("disabled", false);
                jQuery("#cht_pending_messages").attr("disabled", false);
                jQuery(".disable-message").removeClass("label-tooltip").removeClass("icon");
                jQuery(".cta-action-radio input").attr("disabled", false);
            }
            jQuery(".page-body .chaty-widget").removeClass("vertical").removeClass("horizontal");

            if (jQuery(eClass+':checked').length > 1) {
                jQuery(eClass+':checked').each(function(){
                    var socialIcon = jQuery(this).closest("li").find(".icon").html();
                    var socialIcon = jQuery(this).closest("li").find(".icon").html();
                    var socialIconText = jQuery(this).closest("li").find(".chaty-title").val();
                    var eClass = jQuery(this).closest(".channels-selected__item").hasClass("img-active")?"img-active":"";
                    if(socialIconText != "") {
                        socialIconText = "<span class='social-tooltip'>"+socialIconText+"</span>";
                    }
                    jQuery(".chaty-channels").append("<div class='social-item-box "+eClass+"'><span class='tooltip-icon'>"+socialIcon+"</span>"+socialIconText+"</div>");
                });

                if(jQuery("#chaty_default_state").val() == "open" && jQuery("#cht_close_button").is(":checked")) {
                    jQuery("#iconWidget").css("display", "block");
                    jQuery(".chaty-widget .tooltiptext").css("display","block");
                    jQuery(".chaty-widget").removeClass("hide-arrow");
                } else if(jQuery("#chaty_default_state").val() != "open") {
                    jQuery("#iconWidget").css("display", "block");
                    jQuery(".chaty-widget .tooltiptext").css("display","block");
                    jQuery(".chaty-widget").removeClass("hide-arrow");
                } else if(jQuery("#chaty_default_state").val() == "open") {
                    jQuery("#iconWidget").hide();
                    jQuery(".chaty-widget .tooltiptext").hide();
                    jQuery(".chaty-widget").addClass("hide-arrow");
                }
                jQuery(".chaty-widget").removeClass("has-single");
                jQuery(".page-body .chaty-widget").addClass(jQuery("#chaty_icons_view").val());

            } else if (jQuery(eClass+':checked').length == 1) {
                if(jQuery("#chaty_default_state").val() == "open" && !jQuery("#cht_close_button").is(":checked")) {
                    jQuery("#iconWidget").css("display","block");
                    jQuery(".chaty-widget .tooltiptext").css("display","block");
                    jQuery(".chaty-widget").removeClass("hide-arrow");
                } else if(jQuery("#chaty_default_state").val() != "open") {
                    jQuery("#iconWidget").css("display","block");
                    jQuery(".chaty-widget .tooltiptext").css("display","block");
                    jQuery(".chaty-widget").removeClass("hide-arrow");
                }
                jQuery(".chaty-widget").addClass("has-single");
                jQuery(".chaty-widget").removeClass("hide-arrow");
            } else if (jQuery(eClass+':checked').length == 0) {
                jQuery("#iconWidget").hide();
                jQuery(".chaty-widget .tooltiptext").hide();
                jQuery(".chaty-widget").addClass("hide-arrow");
                jQuery(".chaty-widget").removeClass("has-single");
            }
            jQuery(".chaty-channels .remove-icon-img").remove();

            if(jQuery("#trigger_on_time").is(":checked")) {
                jQuery("#chaty_trigger_time").attr("readonly", false);
            } else {
                jQuery("#chaty_trigger_time").attr("readonly", true);
            }

            if(jQuery("#chaty_trigger_hide").is(":checked")) {
                jQuery("#chaty_trigger_hide_time").attr("readonly", false);
            } else {
                jQuery("#chaty_trigger_hide_time").attr("readonly", true);
            }

            if(jQuery("#chaty_trigger_on_scroll").is(":checked")) {
                jQuery("#chaty_trigger_on_page_scroll").attr("readonly", false);
            } else {
                jQuery("#chaty_trigger_on_page_scroll").attr("readonly", true);
            }


            if(jQuery(".chaty-widget .tooltiptext").text() == "") {
                jQuery(".chaty-widget .tooltiptext").hide();
            } else {
                if(jQuery("#chaty_default_state").val() == "open" && jQuery(eClass+':checked').length > 1) {
                    jQuery(".chaty-widget .tooltiptext").hide();
                } else {
                    jQuery(".chaty-widget .tooltiptext").css("display", "block");
                }
            }
        }

        function getPreviewDesktop() {
            return jQuery('#previewDesktop').is(':checked') ? true : false;
        }

        function changeWidgetIcon() {

            jQuery(document).on('change', '.js-chanel-icon', function () {
                detectIcon();
            });

            jQuery(document).on('change', '.js-widget-i', function (ev) {
                if (ev.target.classList.contains('js-upload')) {
                    defaultIcon = jQuery('.file-preview-image').last().parent().html();
                } else {
                    defaultIcon = jQuery('i[data-type=' + jQuery(".js-widget-i:checked").val() + ']').html()
                }
                detectIcon();
            });
        }

        changeWidgetIcon();

        if (jQuery(".js-widget-i:checked").attr("data-type") !== 'chat-image') {
            defaultIcon = jQuery('i[data-type=' + jQuery(".js-widget-i:checked").attr("data-type") + ']').html();
            detectIcon();
        };
    });

    /*jQuery(window).on("load", function(){
        if(jQuery("#setting-error-settings_updated").length) {
            jQuery(".toast-message").show();
            jQuery(".toast-message").addClass("active");

            setTimeout(function(){
                jQuery(".toast-message").removeClass("active");
            }, 5000);
        }
    });*/

    jQuery(document).ready(function () {

        if(jQuery(".toast-message").length) {
            jQuery(".toast-message").show();
            jQuery(".toast-message").addClass("active");

            setTimeout(function(){
                jQuery(".toast-message").removeClass("active");
            }, 5000);
        }

        jQuery(document).on("click", ".toast-close-btn a", function(e){
            e.preventDefault();
            jQuery(".toast-message").removeClass("active");
        });

        jQuery(document).on("click", ".chaty-popup-box button, #chaty-intro-popup", function(e){
            e.stopPropagation();
            var nonceVal = jQuery("#chaty_update_popup_status").val();
            $("#chaty-intro-popup").remove();
            $.ajax({
                type: 'POST',
                url: ajaxurl,
                data: {
                    action: 'update_popup_status',
                    nonce: nonceVal
                },
                beforeSend: function (xhr) {

                },
                success: function (res) {

                },
                error: function (xhr, status, error) {

                }
            });
        });

        jQuery(document).on("click", ".chaty-popup-box", function(e){
            //e.stopPropagation();
        });


        jQuery(document).on("click", ".remove-chaty-options", function (e) {
            e.preventDefault();
            e.stopPropagation();
            if(confirm("Are you sure you want to delete this widget?")) {
                $.ajax({
                    type: 'POST',
                    url: ajaxurl,
                    data: {
                        action: 'remove_chaty_widget',
                        nonce_code: cht_nonce_ajax.cht_nonce,
                        widget_index: jQuery("#widget_index").val()
                    },
                    beforeSend: function (xhr) {

                    },
                    success: function (res) {
                        window.location = res;
                    },
                    error: function (xhr, status, error) {

                    }
                });
            }
        })

        /* Date: 2019-07-26 */
        var location_href = window.location.href;
        if (window.location.href.indexOf('page=chaty-app&widget=') > -1) {
            jQuery('#toplevel_page_chaty-app .wp-submenu.wp-submenu-wrap li').each(function () {
                var element_href = jQuery(this).find('a').attr('href');
                if (typeof element_href !== 'undefined') {
                    jQuery(this).removeClass('current');
                    if (window.location.href.indexOf(element_href) > -1 && element_href.indexOf('&widget=') > -1) {
                        jQuery(this).addClass('current');
                    }
                }
            });
        }
    });
}(jQuery));

jQuery(window).on("resize", function () {
    check_for_preview_pos();
});
jQuery(window).on("scroll", function () {
    check_for_preview_pos();
});
jQuery(document).ready(function () {
    check_for_preview_pos();
});


function check_for_preview_pos() {
    if(jQuery(".chaty-setting-form").length) {
        if(jQuery(window).width() > 1179) {
            var topPos = parseInt(jQuery(".chaty-setting-form").offset().top);
            jQuery(".btn-save-sticky").css("top", (topPos+58));
            //jQuery(".preview").css("top", (topPos+18));
            jQuery(".btn-help, .preview-help-btn").css("top", (topPos+58+145));
            jQuery("a.remove-chaty-widget-sticky").css("top", (topPos+58+145+119));
        } else {
            jQuery(".btn-save-sticky").attr("style", "");
            //jQuery(".preview").attr("style", "");
            jQuery(".btn-help, .preview-help-btn").attr("style", "");
            jQuery("a.remove-chaty-widget-sticky").attr("style", "");
        }
    }

}

var totalPageOptions = 0;
var pageOptionContent = "";
var totalDateAndTimeOptions = 0;
var dateAndTimeOptionContent = "";
jQuery(document).ready(function () {
    var isChatyInMobile = false; //initiate as false
    if (/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|ipad|iris|kindle|Android|Silk|lge |maemo|midp|mmp|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino/i.test(navigator.userAgent)
        || /1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i.test(navigator.userAgent.substr(0, 4))) {
        isChatyInMobile = true;
    }
    totalPageOptions = parseInt(jQuery(".chaty-page-option").length);
    pageOptionContent = jQuery(".chaty-page-options-html").html();
    jQuery(".chaty-page-options-html").remove();
    totalDateAndTimeOptions = parseInt(jQuery(".chaty-date-time-option").length);
    dateAndTimeOptionContent = jQuery(".chaty-date-and-time-options-html").html();
    jQuery(".chaty-date-and-time-options-html").remove();

    set_wp_editor();

    // show .chaty-page-options and .remove-rules button
    jQuery(".create-rule").on("click", function () {
        const $parent = jQuery(this).parents('.chaty-option-box');
        $parent.find(".chaty-page-options").removeClass('hidden');
        $parent.find('.remove-rules').removeClass('hidden').addClass('inline-block');
    });
    
    // hide .chaty-page-options and .remove-rules button
    jQuery('.remove-rules').on('click', function() {
        const $parent = jQuery(this).parents('.chaty-option-box');
        $parent.find(".chaty-page-options").addClass('hidden');
        $parent.find('.remove-rules').removeClass('inline-block').addClass('hidden');
    })

    jQuery(document).on("change", "#chaty_attention_effect", function(){
        change_custom_preview();
    });

    jQuery(document).on("change", "input[name='cht_color']:checked", function(){

        var $this = jQuery(this);

        var color = $this.val();

        var title = $this.prop('title');
        jQuery('.color-picker-btn .circle').css({backgroundColor: color});
        jQuery('.color-picker-btn .text').text(title);
        jQuery('#chaty-social-close ellipse').attr("fill", color);
        jQuery('.preview .page #iconWidget svg circle').css({fill: color});
        jQuery('.preview .page .chaty-close-icon ellipse').css({fill: color});
        jQuery("#cht_custom_color").val(jQuery(this).val());
        change_custom_preview();
    });
    // jQuery('input[name="cht_color"]:checked').trigger("change");

    jQuery(document).on("change", "input[name='cht_position']:checked", function(){
        change_custom_preview();
    });

    setInterval(function(){
        var currentClass = jQuery("#chaty_attention_effect").attr("data-effect");
        if(currentClass != "") {
            jQuery("#iconWidget").removeClass("chaty-animation-"+currentClass);
            jQuery("#iconWidget").removeClass("start-now");
            setTimeout(function(){
                jQuery("#iconWidget").addClass("chaty-animation-"+jQuery("#chaty_attention_effect").val()).addClass("start-now");
            }, 1000);
        } else {
            jQuery("#chaty_attention_effect").attr("data-effect", jQuery("#chaty_attention_effect").val());
        }
    }, 5000);

    jQuery(document).on("click", ".remove-chaty", function () {
        jQuery(".page-options").toggle();
    });

    jQuery(document).on("click", ".remove-page-option", function () {
        jQuery(".chaty-data-and-time-rules ").toggle();
    });

    jQuery("#image-upload-content .custom-control-label").on("click", function (e) {
        e.stopPropagation();
        jQuery(this).closest(".custom-control").find("input[type=radio]").attr("checked", true);
        jQuery('.js-widget-i').trigger("change");
        return false;
    });

     // trigger color picker
    jQuery(document).trigger('chatyColorPicker/trigger', [{
        $scope   : jQuery(document), 
        element  : '.chaty-color-field' 
    }]);

    jQuery(".chaty-color-field").on("change", function () {
        chaty_set_bg_color();
        change_custom_preview();
    });

    jQuery(".remove-chaty-img").on("click", function (e) {
        e.stopPropagation();
    });

    if(!isChatyInMobile) {
        jQuery("#channels-selected-list").sortable({
            placeholder: "ui-chaty-state-hl",
            items: "li.chaty-channel:not(#chaty-social-close)",
            handle: '.move-icon',
            stop: function () {
                set_wp_editor();
            },
            update: function (event, ui) {
                set_social_channel_order();
                change_custom_preview();
                set_wp_editor();
            }
        });
    }

    function set_wp_editor() {
        if(jQuery(".chaty-whatsapp-setting-textarea").length) {
            jQuery(".chaty-whatsapp-setting-textarea").each(function(){
                if(jQuery("#cht_social_embedded_message_"+jQuery(this).data("id")+"_ifr").length) {
                    tinymce.get(jQuery(this).attr("id")).remove();
                }
                var editorId = jQuery(this).attr("id");
                tinymce.init({
                    selector: jQuery(this).attr("id"),
                    toolbar: 'bold, italic, underline | emoji',
                    menubar: false,
                    branding: false,
                    setup: function(editor) {

                        editor.addButton('emoji', {
                            image: cht_nonce_ajax.icon_img,
                            onclick: insertEmoji,
                            classes: 'emoji-custom-icon'
                        });

                        editor.on('keyup', function (e){
                            change_custom_preview();
                            jQuery("#csaas-form-0-Whatsapp .csaas-whatsapp-message .csaas-whatsapp-message-content").html(editor.getContent());
                        });

                        function insertEmoji() {
                            const { createPopup } = window.picmoPopup;
                            const trigger = jQuery(".mce-emoji-custom-icon button").attr("id");
                            const trig = document.querySelector("#"+trigger);

                            const picker = createPopup({}, {
                                referenceElement: trig,
                                triggerElement: trig,
                                position: 'right-start',
                                hideOnEmojiSelect: false
                            });

                            picker.toggle();

                            picker.addEventListener('emoji:select', (selection) => {
                                let editor = tinyMCE.get(editorId);
                                if(!editor.selection.getNode() || editor.selection.getNode() === editor.getBody()) {
                                    editor.focus();
                                    // Move the cursor to the end of the content
                                    editor.selection.select(editor.getBody(), true);
                                    editor.selection.collapse(false);
                                    tinymce.activeEditor.execCommand('mceInsertContent', false, selection.emoji);
                                } else {
                                    tinymce.activeEditor.execCommand('mceInsertContent', false, selection.emoji);
                                }
                                change_custom_preview();
                                jQuery("#csaas-form-0-Whatsapp .csaas-whatsapp-message .csaas-whatsapp-message-content").html(editor.getContent());
                            });
                        }
                    }
                });
                tinymce.execCommand( 'mceAddEditor', true, jQuery(this).attr("id"));
            });
        }
    }

    jQuery(".close-button-img img, .close-button-img .image-upload").on("click", function () {
        var image = wp.media({
            title: 'Upload Image',
            // mutiple: true if you want to upload multiple files at once
            multiple: false,
            library: {
                type: 'image',
            }
        }).open()
        .on('select', function (e) {
            var uploaded_image = image.state().get('selection').first();
            imageData = uploaded_image.toJSON();
            jQuery('.close-button-img').addClass("active");
            jQuery('.close-button-img input').val(imageData.id);
            jQuery('.close-button-img img').attr("src", imageData.url);
            change_custom_preview();
        });
    });

    jQuery(".remove-close-img").on("click", function () {
        default_image = jQuery("#default_image").val();
        jQuery('.close-button-img').removeClass("active");
        jQuery('.close-button-img input').val("");
        jQuery('.close-button-img img').attr("src", default_image);
        change_custom_preview();
    });



    jQuery(document).on("click", ".chaty-widget.click", function(e){
        e.preventDefault();
        // jQuery(".chaty-channels").toggle();
        jQuery(".chaty-widget").toggleClass("active");
    });

    jQuery(document).on('change', '.url-options.cht-required', function (ev) {
        thisVal = jQuery(this).val();
        siteURL = jQuery("#chaty_site_url").val();
        newURL = siteURL;
        if (thisVal == "page_has_url") {
            newURL = siteURL;
        } else if (thisVal == "page_contains") {
            newURL = siteURL + "%s%";
        } else if (thisVal == "page_start_with") {
            newURL = siteURL + "s%";
        } else if (thisVal == "page_end_with") {
            newURL = siteURL + "%s";
        }
        jQuery(this).closest(".url-content").find(".chaty-url").text(newURL);
    });

    check_for_chaty_close_button();
    chaty_set_bg_color();
    change_custom_preview();

    jQuery(".chaty-settings.cls-btn a, .close-btn-set").on("click", function (e) {
        e.preventDefault();
        jQuery(".cls-btn-settings, .close-btn-set").toggleClass("active");
    });

    /*Default Values*/
    if (jQuery("input[name='cht_position']:checked").length == 0) {
        jQuery("#right-position").attr("checked", true);
        jQuery("input[name='cht_position']:checked").trigger("change");
    }
    if (jQuery("input[name='widget_icon']:checked").length == 0) {
        jQuery("input[name='widget_icon']:first").attr("checked", true);
        jQuery("input[name='widget_icon']:checked").trigger("change");
    }

    /*font family Privew*/
    jQuery(document).on( 'change', '#cht_widget_font', function() {
        var fontFamily = jQuery("#cht_widget_font").val();
        if(fontFamily == "System Stack") {
            fontFamily = "-apple-system,BlinkMacSystemFont,Segoe UI,Roboto,Oxygen-Sans,Ubuntu,Cantarell,Helvetica Neue,sans-serif";
        }
        var dataGroup = jQuery("#cht_widget_font option:selected").data("group");
        jQuery(".chaty-google-font").remove();
        if(dataGroup == "Google Fonts") {
            jQuery("head").append('<link class="chaty-google-font" rel="preload" as="style" href="https://fonts.googleapis.com/css?family=' + fontFamily + '&display=swap">');
            jQuery("head").append('<link class="chaty-google-font" rel="stylesheet" href="https://fonts.googleapis.com/css?family=' + fontFamily + '&display=swap">');
        }
        change_custom_preview();
    });

    if(jQuery("#cht_widget_font").val() != "") {
        var fontFamily = jQuery("#cht_widget_font").val();
        if(fontFamily == "System Stack") {
            fontFamily = "-apple-system,BlinkMacSystemFont,Segoe UI,Roboto,Oxygen-Sans,Ubuntu,Cantarell,Helvetica Neue,sans-serif";
        }
        jQuery("#csaas-widget-0").css("font-family", fontFamily);
        var dataGroup = jQuery("#cht_widget_font option:selected").data("group");
        jQuery(".chaty-google-font").remove();
        if(dataGroup == "Google Fonts") {
            jQuery("head").append('<link class="chaty-google-font" rel="preload" as="style" href="https://fonts.googleapis.com/css?family=' + fontFamily + '&display=swap">');
            jQuery("head").append('<link class="chaty-google-font" rel="stylesheet" href="https://fonts.googleapis.com/css?family=' + fontFamily + '&display=swap">');
        }
    }
});

jQuery(window).on("load", function() {
    check_for_chaty_close_button();
    chaty_set_bg_color();
    jQuery(".chaty-page-options .chaty-page-option").removeClass("last");
    jQuery(".chaty-page-options .chaty-page-option:last").addClass("last");

    jQuery('.url-options.cht-required').each(function () {
        jQuery(this).trigger("change");
    });
});

var selectedsocialSlug = "";

function upload_chaty_image(socialSlug) {
    selectedsocialSlug = socialSlug;
    var image = wp.media({
        title: 'Upload Image',
        // mutiple: true if you want to upload multiple files at once
        multiple: false,
        library: {
            type: 'image',
        }
    }).open()
        .on('select', function (e) {
            var uploaded_image = image.state().get('selection').first();
            imageData = uploaded_image.toJSON();
            jQuery('#cht_social_image_' + selectedsocialSlug).val(imageData.id);
            jQuery('.custom-image-' + selectedsocialSlug + " img").attr("src", imageData.url);
            jQuery("#chaty-social-" + selectedsocialSlug + " .channels-selected__item").addClass("img-active");
            change_custom_preview();
        });
}

function toggle_chaty_setting(socId) {
    jQuery("#chaty-social-" + socId).find(".chaty-advance-settings").toggle();
    jQuery("#chaty-social-" + socId).find(".chaty-advance-settings").toggleClass('active');
    if(socId == "Contact_Us") {
        if(jQuery("#Contact_Us-close-btn").length) {
            var nonce = jQuery("#Contact_Us-close-btn").data("nonce");
            if (!jQuery("#Contact_Us-close-btn").hasClass("active")) {
                jQuery("#Contact_Us-close-btn").addClass("active")
                jQuery.ajax({
                    type: 'POST',
                    url: ajaxurl,
                    data: {
                        "nonce": nonce,
                        "action": 'update_channel_setting'
                    },
                    success: function (response) {

                    }
                });
            }
        }
    }
    if(jQuery("#chaty-social-" + socId+ " .chaty-advance-settings.active").length) {
        jQuery("body,html").animate({
            scrollTop: jQuery("#chaty-social-" + socId+ " .chaty-advance-settings.active").offset().top - 50
        }, 500);
    }
    // change_custom_preview();
}

function chaty_set_bg_color() {
    jQuery(".chaty-color-field:not(.button-color)").each(function () {
        if (jQuery(this).val() != "" && jQuery(this).val() != "#ffffff") {
            if (jQuery(this).closest("li").data("id") != "Linkedin" || (jQuery(this).closest("li").data("id") == "Linkedin" && jQuery(this).val() != "#ffffff")) {
                defaultColor = jQuery(this).val();
                jQuery(this).closest(".channels-selected__item").find(".color-element").css("fill", defaultColor);
                jQuery(this).closest(".channels-selected__item").find(".custom-chaty-image").css("background", defaultColor);
                jQuery(this).closest(".channels-selected__item").find(".facustom-icon").css("background", defaultColor);
            }
        }
    });
    change_custom_preview();
}

function upload_qr_code() {
    var image = wp.media({
        title: 'Upload QR Image',
        multiple: false,
        library: {
            type: 'image',
        }
    }).open()
        .on('select', function (e) {
            var uploaded_image = image.state().get('selection').first();
            imageData = uploaded_image.toJSON();
            jQuery('#upload_qr_code_val').val(imageData.id);
            jQuery('#upload_qr_code img').attr("src", imageData.url);
            jQuery(".remove-qr-code").addClass("active");
            change_custom_preview();
        });
}

function remove_qr_code() {
    jQuery(".remove-qr-code").removeClass("active");
    jQuery('#upload_qr_code_val').val("");
    default_image = jQuery("#default_image").val();
    jQuery('#upload_qr_code img').attr("src", default_image);
    change_custom_preview();
}

function remove_chaty_image(socId) {
    default_image = jQuery("#default_image").val();
    jQuery("#chaty-social-" + socId + " .channels-selected__item").removeClass("img-active");
    jQuery('#cht_social_image_' + socId).val("");
    jQuery('#cht_social_image_src_' + socId).attr("src", default_image);
    change_custom_preview();
}

var baseIcon = '<svg version="1.1" id="ch" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="-496 507.7 54 54" style="enable-background:new -496 507.7 54 54;" xml:space="preserve">\n' +
        '                            <style type="text/css">.st0 {fill: #A886CD;}  .st1 {fill: #FFFFFF;}\n' +
        '                        </style><g><circle class="st0" cx="-469" cy="534.7" r="27"/></g><path class="st1" d="M-459.9,523.7h-20.3c-1.9,0-3.4,1.5-3.4,3.4v15.3c0,1.9,1.5,3.4,3.4,3.4h11.4l5.9,4.9c0.2,0.2,0.3,0.2,0.5,0.2 h0.3c0.3-0.2,0.5-0.5,0.5-0.8v-4.2h1.7c1.9,0,3.4-1.5,3.4-3.4v-15.3C-456.5,525.2-458,523.7-459.9,523.7z"/>\n' +
        '                                                    <path class="st0" d="M-477.7,530.5h11.9c0.5,0,0.8,0.4,0.8,0.8l0,0c0,0.5-0.4,0.8-0.8,0.8h-11.9c-0.5,0-0.8-0.4-0.8-0.8l0,0\n' +
        '                            C-478.6,530.8-478.2,530.5-477.7,530.5z"/>\n' +
        '                                                    <path class="st0" d="M-477.7,533.5h7.9c0.5,0,0.8,0.4,0.8,0.8l0,0c0,0.5-0.4,0.8-0.8,0.8h-7.9c-0.5,0-0.8-0.4-0.8-0.8l0,0\n' +
        '                            C-478.6,533.9-478.2,533.5-477.7,533.5z"/>\n' +
        '                        </svg>',
    defaultIcon = '<svg version="1.1" id="ch" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="-496 507.7 54 54" style="enable-background:new -496 507.7 54 54;" xml:space="preserve">\n' +
        '                            <style type="text/css">.st0 {fill: #A886CD;}  .st1 {fill: #FFFFFF;}\n' +
        '                        </style><g><circle class="st0" cx="-469" cy="534.7" r="27"/></g><path class="st1" d="M-459.9,523.7h-20.3c-1.9,0-3.4,1.5-3.4,3.4v15.3c0,1.9,1.5,3.4,3.4,3.4h11.4l5.9,4.9c0.2,0.2,0.3,0.2,0.5,0.2 h0.3c0.3-0.2,0.5-0.5,0.5-0.8v-4.2h1.7c1.9,0,3.4-1.5,3.4-3.4v-15.3C-456.5,525.2-458,523.7-459.9,523.7z"/>\n' +
        '                                                    <path class="st0" d="M-477.7,530.5h11.9c0.5,0,0.8,0.4,0.8,0.8l0,0c0,0.5-0.4,0.8-0.8,0.8h-11.9c-0.5,0-0.8-0.4-0.8-0.8l0,0\n' +
        '                            C-478.6,530.8-478.2,530.5-477.7,530.5z"/>\n' +
        '                                                    <path class="st0" d="M-477.7,533.5h7.9c0.5,0,0.8,0.4,0.8,0.8l0,0c0,0.5-0.4,0.8-0.8,0.8h-7.9c-0.5,0-0.8-0.4-0.8-0.8l0,0\n' +
        '                            C-478.6,533.9-478.2,533.5-477.7,533.5z"/>\n' +
        '                        </svg>'
var iconBlock = document.getElementById('iconWidget');

function set_social_channel_order() {
    socialString = [];
    jQuery("#channels-selected-list li").each(function () {
        socialString.push(jQuery(this).attr("data-id"));
    });
    socialString = socialString.join(",");
    jQuery("#cht_numb_slug").val(socialString);
    check_for_chaty_close_button();
}
var imageDataEvent = false;
function check_for_chaty_close_button() {
    if (jQuery("#channels-selected-list > li:not(.chaty-cls-setting)").length >= 2) {
        jQuery("#chaty-social-close").show();
    } else {
        jQuery("#chaty-social-close").hide();
    }
    var srtString = "";
    jQuery("#channels-selected-list > li").each(function(){
        if(jQuery(this).attr("data-id") != "undefined" && jQuery(this).attr("data-id") != "") {
            srtString += jQuery(this).attr("data-id")+",";
        }
        srtString = srtString.trimRight(",")
    });
    jQuery(".add_slug").val(srtString);
}

function getChtPreviewDesktop() {
    return jQuery('#previewDesktop').is(':checked') ? true : false;
}

function setChtIcon(icon, colorSelf) {
    if(!jQuery("iconWidget").length) {
        return;
    }
    iconBlock = document.getElementById('iconWidget');
    if (icon) {
        //jQuery('.preview .page .chaty-widget').show();
        iconBlock.innerHTML = icon;
    } else {
        //jQuery('.preview .page .chaty-widget').hide();
        iconBlock.innerHTML = '';
    }
    if (colorSelf) {
        var color = jQuery('.color-picker-custom input').val() ? jQuery('.color-picker-custom input').val() : jQuery('.color-picker-radio input:checked').val();
        jQuery('.preview .page #iconWidget svg circle').css({fill: color});
        jQuery('.preview .page .chaty-close-icon ellipse').css({fill: color});
        jQuery('#chaty-social-close ellipse').attr("fill", color);
    }
    jQuery('.js-widget-i:checked').trigger("change");
}


jQuery(".close-chaty-popup-btn").on("click", function(e){
    e.stopPropagation();
    jQuery(".chaty-popup").hide();
})


jQuery( function($) {
    'use strict';
    $(document).ready(function(){
        $(document).on("click", ".chat-slider-options .slide-option", function(e){
            e.preventDefault();
            if(!$(this).hasClass("active")) {
                var dataSlide = $(this).data("slide");
                $(".chat-slides .chat-slide").removeClass("active");
                $(".chat-slides .chat-slide-"+dataSlide).addClass("active");
                $(".chat-slider-options .slide-option").removeClass("active");
                $(".chat-slider-options .slide-"+dataSlide).addClass("active");
            }
        });

        jQuery(document).on("click", ".add-chatyway", function (e){
            e.preventDefault();
            jQuery("#chatyway-info-popup").show();
        });


        $(document).on("click", "a.close-dashboard-pro-box", function(e){
            e.preventDefault();
            $(".dashboard-pro-box").slideUp();
        });

        $(document).on("click", "#cta-option-chat-view-pro", function(){
            $("#chat-view-popup").show();
        });

        $(document).on("click", ".chat-view-popup-content", function(e){
            e.stopPropagation();
        });

        $(document).on("mouseover", ".chat-view-content", function(e){
            $(".chat-view-content").addClass("stop-animate");
        }).on("mouseleave", ".chat-view-content", function () {
            $(".chat-view-content").removeClass("stop-animate");
        });

        $(document).on("click", ".chat-view-popup-overlay", function(){
            $("#chat-view-popup").hide();
        });

        $(document).on("click", ".chat-slider-options .prev-slide, .chat-slider-options .next-slide", function(e){
            e.preventDefault();
            var dataSlide = parseInt($(".chat-slider-options .slide-option.active").data("slide"));
            $(".chat-slides .chat-slide").removeClass("active");
            $(".chat-slider-options .slide-option").removeClass("active");
            if(dataSlide == 1) {
                dataSlide = 2;
            } else {
                dataSlide = 1;
            }
            $(".chat-slides .chat-slide-"+dataSlide).addClass("active");
            $(".chat-slider-options .slide-"+dataSlide).addClass("active");
        });

        setInterval(function(){
            if(!$(".chat-view-content").hasClass("stop-animate")) {
                var dataSlide = parseInt($(".chat-slider-options .slide-option.active").data("slide"));
                $(".chat-slides .chat-slide").removeClass("active");
                $(".chat-slider-options .slide-option").removeClass("active");
                if(dataSlide == 1) {
                    dataSlide = 2;
                } else {
                    dataSlide = 1;
                }
                $(".chat-slides .chat-slide-"+dataSlide).addClass("active");
                $(".chat-slider-options .slide-"+dataSlide).addClass("active");
            }
        }, 4000);
    });
});


function chatySetCookie(cname, cvalue, exdays) {
    var d = new Date();
    d.setTime(d.getTime() + exdays * 24 * 60 * 60 * 1000);
    var expires = "expires=" + d.toUTCString();
    document.cookie = cname + "=" + cvalue + "; " + expires + "; path=/; SameSite=Lax";
}

function chatyGetCookie(cookieName) {
    var cookieName = cookieName + "=";
    var ca = document.cookie.split(';');
    for (var i = 0; i < ca.length; i++) {
        var c = ca[i];
        while (c.charAt(0) == ' ') {
            c = c.substring(1);
        }
        if (c.indexOf(cookieName) == 0) {
            return c.substring(cookieName.length, c.length); // return data if cookie exists
        }
    }
    return null; // return null if cookie doesn't exists
}

function chatyCheckCookie(cookieName) {
    var cookie = chatyGetCookie(cookieName);
    if (cookie != "" && cookie !== null) {
        return true;
    } else {
        return false;
    }
}

function chatyDeleteCookie(cookieName) {
    document.cookie = cookieName + '=;expires=Thu, 01 Jan 1970 00:00:01 GMT;';
}


function remove_custom_image(socId) {
    console.log('socId: '+ socId);
    default_image = jQuery("#default_image").val();
    jQuery('#cht_social_image_' + socId).val("");
    jQuery('#cht_social_image_src_' + socId).attr("src", default_image);
    jQuery("#chaty_image_"+socId).removeClass("icon-active").removeClass("img-active");
    jQuery("#chaty_image_"+socId+ " .fa-icon").val("");
    jQuery("input[name='cht_social_Chatway[chatway_icon]']:first").prop('checked', true).trigger('change');
    console.log(socId)
    if(socId == 'Chatway') {
        jQuery(".chatway-custom-image").removeClass('img-active');
    }
    change_custom_preview();

}