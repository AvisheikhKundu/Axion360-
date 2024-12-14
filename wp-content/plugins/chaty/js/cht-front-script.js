(function (factory) {
    "use strict";
    if (typeof define === 'function' && define.amd) {
        define(['jquery'], factory);
    } else if (typeof module !== 'undefined' && module.exports) {
        module.exports = factory(require('jquery'));
    } else {
        factory(jQuery);
    }
}(function ($, undefined) {
    var widgetData = [];
    var clientCountry = '';
    var isChatyInMobile = (/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|ipad|iris|kindle|Android|Silk|lge |maemo|midp|mmp|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino/i.test(navigator.userAgent) || /1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i.test(navigator.userAgent.substr(0, 4))) ? 1 : 0;
    var defaultFontFamily = ["System Stack", "Arial", "Tahoma", "Verdana", "Helvetica", "Times New Roman", "Trebuchet MS", "Georgia", "-apple-system,BlinkMacSystemFont,Segoe UI,Roboto,Oxygen-Sans,Ubuntu,Cantarell,Helvetica Neue,sans-serif"];
    var chatyEnv = 'dev';                           // change it to 'app' to remove log from console
    var isBoatUser = false;
    /**
     *
     * Trigger Variables
     *
     **/
    var chatyHasTimeDelay = false;
    var chatyMaxTimeInterval = 0;
    var chatyHasPageScroll = false;
    var chatyHasExitIntent = false;
    var chatyPageScrolls = [];
    var chatyTimeInterval;
    var chatyIntervalTime = 0;
    var lastScrollPer = 0;
    var customExtraCSS = "";
    var chatyHideTimeInterval;
    var chatyHideIntervalTime = 0;
    var ariaLabel = "";
    var chatyInterval = 0;

    function checkForChatySettings() {
        chatyInterval = setInterval(function () {
            if (typeof(chaty_settings) == "object") {
                clearInterval(chatyInterval);
                if (typeof chaty_settings == "object" && chaty_settings.data_analytics_settings != "on" || chaty_settings.data_analytics_settings == "off") {
                    isBoatUser = true;
                }
                widgetData = chaty_settings.chaty_widgets;
                checkForCountry();
            }
        }, 1000)
    }


    $(document).ready(function () {
        var botPattern = "(googlebot\/|bot|Googlebot-Mobile|Googlebot-Image|Google favicon|Mediapartners-Google|bingbot|slurp|java|wget|curl|Commons-HttpClient|Python-urllib|libwww|httpunit|nutch|phpcrawl|msnbot|jyxobot|FAST-WebCrawler|FAST Enterprise Crawler|biglotron|teoma|convera|seekbot|gigablast|exabot|ngbot|ia_archiver|GingerCrawler|webmon |httrack|webcrawler|grub.org|UsineNouvelleCrawler|antibot|netresearchserver|speedy|fluffy|bibnum.bnf|findlink|msrbot|panscient|yacybot|AISearchBot|IOI|ips-agent|tagoobot|MJ12bot|dotbot|woriobot|yanga|buzzbot|mlbot|yandexbot|purebot|Linguee Bot|Voyager|CyberPatrol|voilabot|baiduspider|citeseerxbot|spbot|twengabot|postrank|turnitinbot|scribdbot|page2rss|sitebot|linkdex|Adidxbot|blekkobot|ezooms|dotbot|Mail.RU_Bot|discobot|heritrix|findthatfile|europarchive.org|NerdByNature.Bot|sistrix crawler|ahrefsbot|Aboundex|domaincrawler|wbsearchbot|summify|ccbot|edisterbot|seznambot|ec2linkfinder|gslfbot|aihitbot|intelium_bot|facebookexternalhit|yeti|RetrevoPageAnalyzer|lb-spider|sogou|lssbot|careerbot|wotbox|wocbot|ichiro|DuckDuckBot|lssrocketcrawler|drupact|webcompanycrawler|acoonbot|openindexspider|gnam gnam spider|web-archive-net.com.bot|backlinkcrawler|coccoc|integromedb|content crawler spider|toplistbot|seokicks-robot|it2media-domain-crawler|ip-web-crawler.com|siteexplorer.info|elisabot|proximic|changedetection|blexbot|arabot|WeSEE:Search|niki-bot|CrystalSemanticsBot|rogerbot|360Spider|psbot|InterfaxScanBot|Lipperhey SEO Service|CC Metadata Scaper|g00g1e.net|GrapeshotCrawler|urlappendbot|brainobot|fr-crawler|binlar|SimpleCrawler|Livelapbot|Twitterbot|cXensebot|smtbot|bnf.fr_bot|A6-Indexer|ADmantX|Facebot|Twitterbot|OrangeBot|memorybot|AdvBot|MegaIndex|SemanticScholarBot|ltx71|nerdybot|xovibot|BUbiNG|Qwantify|archive.org_bot|Applebot|TweetmemeBot|crawler4j|findxbot|SemrushBot|yoozBot|lipperhey|y!j-asr|Domain Re-Animator Bot|AddThis)";
        var re = new RegExp(botPattern, 'i');
        var userAgent = navigator.userAgent;
        if (re.test(userAgent)) {
            isBoatUser = true;
        }

        if(isChatyInMobile) {
            $("body").addClass("cht-in-mobile");
        } else {
            $("body").addClass("cht-in-desktop");
        }

        if (typeof chaty_settings == "undefined") {
            console.log("Chaty settings doesn't exists");
            // check for chaty settings in case of JS Deferred
            checkForChatySettings()
        } else if (chaty_settings.chaty_widgets.length == 0) {
            console.log("Chaty widget doesn't exists");
        } else {
            widgetData = chaty_settings.chaty_widgets;
            checkForCountry();

            if (typeof chaty_settings == "object" && chaty_settings.data_analytics_settings != "on" || chaty_settings.data_analytics_settings == "off") {
                isBoatUser = true;
            }
        }

        if($(window).height() > $(window).width()) {
            $("body").addClass("cht-portrait").removeClass("cht-landscape");
        } else {
            $("body").addClass("cht-landscape").removeClass("cht-portrait");
        }

        $(document).on("click", "html, body", function (e) {
            if($(".chaty-popup-whatsapp-form.active").length) {
                $(".chaty-popup-whatsapp-form.active").each(function(){
                    var widgetId = $(this).data("widget");
                    var clickStatus = checkChatyCookieExpired(widgetId, "c-Whatsapp");
                    if ((!isEmpty(widgetId) || widgetId == 0) && clickStatus) {
                        saveChatyCookieString(widgetId, "c-Whatsapp");
                    }
                })
            }
            $(".form-open").removeClass("form-open");
            $(".chaty-outer-forms").removeClass("active");
            $(".chaty .chaty-widget.chaty-no-close-button:not(.has-single)").addClass("chaty-open");
            if($(".chaty .chaty-widget").hasClass("chaty-open")) {
                $(".chaty .chaty-widget:not(.chaty-no-close-button)").removeClass("chaty-open");
                $("body").removeClass("add-bg-blur-effect");
            }
            $("body").removeClass("add-bg-blur-effect");
        });

        $(document).on("click", ".chaty, .chaty-outer-forms", function (e) {
            e.stopPropagation();
        });

        $(document).on("click", ".chaty.form-open .chaty-i-trigger.single-channel a", function (e) {
            $("body").removeClass("add-bg-blur-effect");
        });

        $(document).on("click", ".chaty-close-view-list", function(){
            $(this).closest(".chaty").find(".chaty-widget").removeClass("chaty-open");
            $("body").removeClass("add-bg-blur-effect");
        });

        $(document).on("submit", ".whatsapp-chaty-form", function () {

            if ($(this).hasClass("form-google-analytics")) {
                var widgetChannel = "Whatsapp";
                if (window.hasOwnProperty("gtag")) {
                    gtag("event", "chaty_" + widgetChannel, {
                        eventCategory: "chaty_" + widgetChannel,
                        event_action: "chaty_" + widgetChannel,
                        method: "chaty_" + widgetChannel
                    });
                }
                if (window.hasOwnProperty("ga")) {
                    var ga_settings = window.ga.getAll()[0];
                    ga_settings && ga_settings.send("event", "click", {
                        eventCategory: "chaty_" + widgetChannel,
                        eventAction: "chaty_" + widgetChannel,
                        method: "chaty_" + widgetChannel
                    })
                }
            }
            var widgetId = $(this).data('widget');
            var chatyChannel = $(this).data('channel');
            var clickStatus = checkChatyCookieExpired(widgetId, "c-" + chatyChannel);

            if ((!isEmpty(widgetId) || widgetId == 0) && clickStatus) {
                saveChatyCookieString(widgetId, "c-" + chatyChannel);
                var widgetNonce = $("#chaty-widget-" + widgetId).data("nonce");
                if (!isBoatUser) {
                    $.ajax({
                        url: chaty_settings.ajax_url,
                        data: {
                            widgetId: widgetId,
                            userId: widgetId,
                            isMobile: isChatyInMobile,
                            channel: chatyChannel,
                            nonce: widgetNonce,
                            action: 'update_chaty_channel_click'
                        },
                        dataType: 'json',
                        method: 'post',
                    });
                }
            }

            if ($("#chaty-widget-" + widgetId).length) {
                $("#chaty-widget-" + widgetId).removeClass("form-open");
                $(this).closest(".chaty-outer-forms").removeClass("active");
                if ($("#chaty-widget-" + widgetId).find(".chaty-widget").hasClass("cssas-no-close-button")) {
                    $("#chaty-widget-" + widgetId).find(".chaty-widget:not(.has-single)").addClass("chaty-open")
                }
            }
            $("body").removeClass("add-bg-blur-effect");
        });

        $(document).on("click", ".chaty-close-button, .chaty-close-agent-list, .whatsapp-form-close-btn", function (e) {
            e.preventDefault();
            e.stopPropagation();
            var widgetId = $(this).closest(".chaty-outer-forms").data('widget');
            if (!isEmpty(widgetId) || widgetId == 0) {
                if ($("#chaty-widget-" + widgetId).length) {
                    $("#chaty-widget-" + widgetId).removeClass("form-open");
                    $(this).closest(".chaty-outer-forms").removeClass("active");
                    if ($("#chaty-widget-" + widgetId).find(".chaty-widget").hasClass("chaty-no-close-button")) {
                        $("#chaty-widget-" + widgetId).find(".chaty-widget:not(.has-single)").addClass("chaty-open");
                    }
                }
                if ($(this).closest(".chaty-whatsapp-btn-form").length) {
                    var dataChannel = $(this).closest(".chaty-outer-forms").data('channel');
                    if (!isEmpty(dataChannel)) {
                        var clickStatus = checkChatyCookieExpired(widgetId, "c-" + dataChannel);
                        if (clickStatus) {
                            saveChatyCookieString(widgetId, "c-" + dataChannel);
                        }
                    }
                    var visibleStatus = checkChatyCookieExpired(widgetId, 'v-widget');
                    if (visibleStatus) {
                        updateWidgetViews(widgetId);
                    }
                }

                if($(this).hasClass("whatsapp-form-close-btn")) {
                    clickStatus = checkChatyCookieExpired(widgetId, "c-Whatsapp");
                    if ((!isEmpty(widgetId) || widgetId == 0) && clickStatus) {
                        saveChatyCookieString(widgetId, "c-Whatsapp");
                    }
                }
            }
            $("body").removeClass("add-bg-blur-effect");
        });

        $(document).on("keypress", '.chaty-contact-input input[type="tel"]', function(e) {
            var charCode = (e.which) ? e.which : e.keyCode;
            // ascii code for 0-9 digits and comma
            if(charCode == 43 && $(this).val() == "") {
                return true;
            }
            if(charCode >= 48 && charCode <= 57) {
                return true;
            }
            return false;
        });

        $(document).on("change", '.chaty-contact-input input[type="tel"]', function (){
            var regex = new RegExp(/^(\+)?\d*$/);
            var phone_number = $(this).val();

            if (!regex.test(phone_number)) {
                $(this).val("");
            }
        });

        $(document).on("click", "a.chaty-qr-code-form", function (e) {
            e.preventDefault();
            // e.stopPropagation();
            var dataForm = $(this).data('form');
            if (!isEmpty(dataForm)) {
                if ($("#" + dataForm).length) {
                    var buttonHtml = $(this).html();

                    if($("#" + dataForm).hasClass("active")) {
                        $(this).closest(".chaty").find(".chaty-widget:not(.has-single)").addClass("chaty-open");
                        $(this).closest(".chaty").removeClass("form-open");
                        $("#" + dataForm).removeClass("active");
                        $("body").removeClass("add-bg-blur-effect");
                    } else {
                        $(this).closest(".chaty").find(".chaty-widget").removeClass("chaty-open");
                        $(this).closest(".chaty").addClass("form-open");
                        $("#" + dataForm).addClass("active");
                        buttonHtml = $(this).closest(".chaty").find(".chaty-widget .chaty-cta-close").find("button").html();
                        $(this).closest(".chaty").find(".open-chaty-channel").html(buttonHtml);
                    }
                }
            }
        });

        $(document).on("click", "a.chaty-contact-us-form", function (e) {
            e.preventDefault();
            // e.stopPropagation();
            var dataForm = $(this).data('form');
            if (!isEmpty(dataForm)) {
                if ($("#" + dataForm).length) {

                    if(googleV3Token != "") {
                        googleV3Token = "";
                        refreshG3Token();
                    }

                    if($("#" + dataForm).hasClass("active")) {
                        $(this).closest(".chaty").find(".chaty-widget:not(.has-single)").addClass("chaty-open");
                        $(this).closest(".chaty").removeClass("form-open");
                        $("#" + dataForm).removeClass("active");
                        $("body").removeClass("add-bg-blur-effect");

                    } else {
                        $(this).closest(".chaty").find(".chaty-widget").removeClass("chaty-open");
                        $(this).closest(".chaty").addClass("form-open");
                        $("#" + dataForm).addClass("active");
                        $("#" + dataForm).find(".chaty-ajax-success-message").remove();
                        $("#" + dataForm).find(".chaty-ajax-error-message").remove();
                        $("#" + dataForm).find(".has-chaty-error").removeClass("has-chaty-error");
                        buttonHtml = $(this).closest(".chaty").find(".chaty-widget .chaty-cta-close").find("button").html();
                        $(this).closest(".chaty").find(".open-chaty-channel").html(buttonHtml);
                    }
                }
            }
        });

        $(document).on("click", "a.chaty-whatsapp-btn-form", function (e) {
            e.preventDefault();
            // e.stopPropagation();
            var dataForm = $(this).data('form');
            if (!isEmpty(dataForm)) {
                if ($("#" + dataForm).length) {
                    //$("#" + dataForm).addClass("is-active");
                    if($("#" + dataForm).hasClass("active")) {
                        $(this).closest(".chaty").find(".chaty-widget:not(.has-single)").addClass("chaty-open");
                        $(this).closest(".chaty").removeClass("form-open");
                        $("#" + dataForm).removeClass("active");
                        $("body").removeClass("add-bg-blur-effect");
                        setTimeout(function(){
                            $("body").removeClass("add-bg-blur-effect");
                        }, 100);
                    } else {
                        $(this).closest(".chaty").find(".chaty-widget").removeClass("chaty-open");
                        $(this).closest(".chaty").addClass("form-open");
                        $("#" + dataForm).addClass("active");
                        var buttonHtml = $(this).closest(".chaty").find(".chaty-widget .chaty-cta-close").find("button").html()+"<span class='hide-cht-svg-bg'>"+chaty_settings.lang.hide_whatsapp_form+"</span>";
                        $(this).closest(".chaty").find(".chaty-widget").find(".open-chaty-channel").html(buttonHtml);
                    }
                    setTimeout(function(){
                        $(".chaty-whatsapp-btn-form.active .chaty-whatsapp-input").focus();
                    }, 100);
                }
            }
        });

        $(document).on("click", ".chaty-channel.chaty-agent-button", function (e) {
            e.preventDefault();
            // e.stopPropagation();
            var dataForm = $(this).data('form');
            if (!isEmpty(dataForm)) {
                if ($("#" + dataForm).length) {
                    if (!$(this).closest(".chaty").find(".chaty-widget").hasClass("has-single")) {
                        var buttonHtml = $(this).html();
                        $("#" + dataForm).addClass("is-active");

                        $(this).closest(".chaty").find(".chaty-widget").removeClass("chaty-open");
                        $(this).closest(".chaty").addClass("form-open");
                        $("#" + dataForm).addClass("active");
                        buttonHtml = $(this).closest(".chaty").find(".chaty-widget .chaty-cta-close").find("button").html();
                        $(this).closest(".chaty").find(".open-chaty-channel").html(buttonHtml);
                    } else {
                        if ($(this).closest(".chaty").hasClass("form-open")) {
                            $(this).closest(".chaty").find(".chaty-widget:not(.has-single)").addClass("chaty-open");
                            $(this).closest(".chaty").removeClass("form-open");
                            $("#" + dataForm).removeClass("active");
                            $("body").removeClass("add-bg-blur-effect");
                        } else {
                            buttonHtml = $(this).closest(".chaty").find(".chaty-widget .chaty-cta-close").find("button").html();
                            $("#" + dataForm).addClass("is-active");

                            $(this).closest(".chaty").find(".chaty-widget").removeClass("chaty-open");
                            $(this).closest(".chaty").addClass("form-open");
                            $("#" + dataForm).addClass("active");

                            $(this).closest(".chaty").find(".open-chaty-channel").html(buttonHtml);
                        }
                    }
                }
            }

        });

        /* track google analytics event */
        $(document).on("click", ".chaty-channel a.has-gae", function (e) {
            var widgetChannel = $(this).closest(".chaty-channel").data("channel");
            if (widgetChannel !== undefined && widgetChannel != "" && widgetChannel != null) {
                if (window.hasOwnProperty("gtag")) {
                    gtag("event", "chaty_" + widgetChannel, {
                        eventCategory: "chaty_" + widgetChannel,
                        event_action: "chaty_" + widgetChannel,
                        method: "chaty_" + widgetChannel
                    });
                }
                if (window.hasOwnProperty("ga")) {
                    var ga_settings = window.ga.getAll()[0];
                    ga_settings && ga_settings.send("event", "click", {
                        eventCategory: "chaty_" + widgetChannel,
                        eventAction: "chaty_" + widgetChannel,
                        method: "chaty_" + widgetChannel
                    })
                }
            }
        });

        $(document).on("mouseover", ".chaty-widget.has-single .chaty-channel a.has-on-hover[data-hover]", function () {
            $(this).find(".on-hover-text").html($(this).data("hover"));
        }).on("mouseleave", ".chaty-widget.has-single .chaty-channel a.has-on-hover[data-text]", function () {
            $(this).find(".on-hover-text").html($(this).data("text"));
        });

        $(document).on("submit", ".whatsapp-chaty-form.has-form-gae", function(){
            var widget_id = $(this).data("widget");
            var widgetChannel = $("#Whatsapp-"+ widget_id +"-channel").data("channel");
            if (widgetChannel !== undefined && widgetChannel != "" && widgetChannel != null) {
                if (window.hasOwnProperty("gtag")) {
                    gtag("event", "chaty_whatsapp_redirect", {
                        eventCategory: "chaty_whatsapp_redirect",
                        event_action: "chaty_whatsapp_redirect",
                        method: "chaty_whatsapp_redirect"
                    });
                }
                if (window.hasOwnProperty("ga")) {
                    var ga_settings = window.ga.getAll()[0];
                    ga_settings && ga_settings.send("event", "click", {
                        eventCategory: "chaty_whatsapp_redirect",
                        eventAction: "chaty_whatsapp_redirect",
                        method: "chaty_whatsapp_redirect"
                    })
                }
            }
        });

        /* toggle widget on CTA button click */
        $(document).on("click", ".chaty-i-trigger:not(.single-channel)", function () {
            if ($(this).closest(".chaty").hasClass("form-open")) {
                $(this).closest(".chaty").removeClass("form-open");
                $(this).closest(".chaty-widget:not(.has-single)").addClass("chaty-open");

            } else {
                $(this).closest(".chaty-widget").toggleClass("chaty-open");
            }
            $(".chaty-outer-forms.active").each(function(){
                $(this).removeClass("active");
                var widgetID = $(this).data("widget");
                $("#chaty-widget-"+widgetID).removeClass("form-open");
            });
            if ($(this).closest(".chaty").find(".chaty-widget").hasClass("chaty-no-close-button")) {
                $(this).closest(".chaty").find(".chaty-widget:not(.has-single)").addClass("chaty-open");
            }
        });

        $(document).on("click", ".chaty-i-trigger.single-channel .chaty-cta-close", function () {
            if ($(this).closest(".chaty").hasClass("form-open")) {
                $(this).closest(".chaty").removeClass("form-open");

                var chatyWidgetId = $(this).closest(".chaty").data("id");
                if($("#chaty-form-"+chatyWidgetId+"-Whatsapp").length && $("#chaty-form-"+chatyWidgetId+"-Whatsapp").hasClass("active")) {
                    var clickStatus = checkChatyCookieExpired(chatyWidgetId, "c-Whatsapp");
                    if ((!isEmpty(chatyWidgetId) || chatyWidgetId == 0) && clickStatus) {
                        saveChatyCookieString(chatyWidgetId, "c-Whatsapp");
                    }
                }
            }
            $(".chaty-outer-forms.active").each(function(){
                $(this).removeClass("active");
                var widgetID = $(this).data("widget");
                $("#chaty-widget-"+widgetID).removeClass("form-open");
            });
        });

        /* Open widget on hover */
        if (!isChatyInMobile) {
            $(document).on("mouseover", "body:not(.chaty-in-mobile) .chaty.open-on-hover .chaty-i-trigger:not(.single-channel)", function () {
                if (!$(this).closest(".chaty-widget").hasClass("chaty-open") && !$(this).closest(".chaty-widget").hasClass("on-chaty-widget")) {
                    $(this).closest(".chaty-widget").addClass("on-chaty-widget");
                    $(this).find(".chaty-cta-main").trigger("click");
                }
            }).on("mouseleave", "body:not(.chaty-in-mobile) .chaty.open-on-hover .chaty-i-trigger:not(.single-channel)", function () {
                if (!$(this).closest(".chaty-widget").hasClass("chaty-open")) {
                    $(this).closest(".chaty-widget").removeClass("on-chaty-widget")
                }
            });
        }

        /* Remove active class for CTA button */
        $(document).on("click", ".chaty-channel.single a", function(){
            var chatyWidgetId = $(this).closest(".chaty").data("id");
            if($(this).closest(".chaty").hasClass("first_click")) {
                saveChatyCookieString(chatyWidgetId, "c-widget");
                $(this).closest(".chaty-channel").removeClass("active");
            }
            removeChatyAnimation(chatyWidgetId);
        });

        $(document).on("click", ".chaty-chatway-channel", function (){
            if(typeof(window.$chatway) == 'object') {
                window.$chatway.openChatwayWidget()
            }
        });

        /* check for channel or widget click event */
        $(document).on("click", ".chaty-channel", function (e) {
            // return;
            var clickStatus;
            var chatyChannel;
            var chatyChannels = [];
            var isSingle = 0;
            var chatyWidgetIdentifier;
            var chatyWidgetId = $(this).closest(".chaty").data("id");
            if (typeof chatyWidgetId != 'undefined') {
                chatyWidgetIdentifier = $("#chaty-widget-" + chatyWidgetId).data("identifier");
                if (typeof chatyWidgetIdentifier != 'undefined') {
                    var userId = $("#chaty-widget-" + chatyWidgetId).data("user");
                    removeChatyAnimation(chatyWidgetId);
                    if ($(this).hasClass("chaty-cta-main") || $(this).hasClass("chaty-cta-close")) {
                        console.log("@!3123");
                        console.log($("#chaty-form-"+chatyWidgetId+"-Whatsapp").length);
                        console.log($("#chaty-form-"+chatyWidgetId+"-Whatsapp").length && $("#chaty-form-"+chatyWidgetId+"-Whatsapp").hasClass("active"));
                        if($("#chaty-form-"+chatyWidgetId+"-Whatsapp").length && $("#chaty-form-"+chatyWidgetId+"-Whatsapp").hasClass("active")) {
                            clickStatus = checkChatyCookieExpired(chatyWidgetId, "c-Whatsapp");
                            if ((!isEmpty(chatyWidgetId) || chatyWidgetId == 0) && clickStatus) {
                                saveChatyCookieString(chatyWidgetId, "c-Whatsapp");
                            }
                        }
                        clickStatus = checkChatyCookieExpired(chatyWidgetId, 'c-widget');
                        $("#chaty-widget-" + chatyWidgetId).find(".ch-pending-msg").remove();
                        if (clickStatus) {
                            saveChatyCookieString(chatyWidgetId, "c-widget");
                            if ($(this).hasClass("chaty-cta-main")) {
                                chatyChannels = [];
                                if($("#chaty-widget-" + chatyWidgetId).hasClass("chaty-has-chat-view")) {
                                    $(".chaty-chat-view.chaty-chat-view-" + chatyWidgetId + " .chaty-view-channels").find(".chaty-channel").each(function () {
                                        chatyChannel = $(this).data("channel");
                                        clickStatus = checkChatyCookieExpired(chatyWidgetId, "v-" + chatyChannel);
                                        if (clickStatus && typeof chatyChannel != 'undefined') {
                                            saveChatyCookieString(chatyWidgetId, "v-" + chatyChannel);
                                            chatyChannels.push(chatyChannel);
                                        }
                                    });
                                } else {
                                    $("#chaty-widget-" + chatyWidgetId + " .chaty-channel-list").find(".chaty-channel").each(function () {
                                        chatyChannel = $(this).data("channel");
                                        clickStatus = checkChatyCookieExpired(chatyWidgetId, "v-" + chatyChannel);
                                        if (clickStatus && typeof chatyChannel != 'undefined') {
                                            saveChatyCookieString(chatyWidgetId, "v-" + chatyChannel);
                                            chatyChannels.push(chatyChannel);
                                        }
                                    });
                                }
                                var widgetNonce = $("#chaty-widget-" + chatyWidgetId).data("nonce");
                                if (!isBoatUser) {
                                    $.ajax({
                                        url: chaty_settings.ajax_url,
                                        data: {
                                            widgetId: chatyWidgetId,
                                            userId: userId,
                                            isMobile: isChatyInMobile,
                                            channels: chatyChannels,
                                            isSingle: 0,
                                            nonce: widgetNonce,
                                            action: 'update_chaty_widget_click'
                                        },
                                        dataType: 'json',
                                        method: 'post',
                                    });
                                }
                            }
                        }
                        if ($("#chaty-widget-" + chatyWidgetId).hasClass("first_click")) {
                            $("#chaty-widget-" + chatyWidgetId + " .chaty-cta-main").removeClass("active");
                            $("#chaty-widget-" + chatyWidgetId + " .chaty-cta-main").removeClass("chaty-tooltip");
                        }
                    } else if ($(this).hasClass("single")) {
                        $("#chaty-widget-" + chatyWidgetId).find(".ch-pending-msg").remove();
                        clickStatus = checkChatyCookieExpired(chatyWidgetId, 'c-widget');
                        var widgetNonce = $("#chaty-widget-" + chatyWidgetId).data("nonce")
                        if (clickStatus) {
                            saveChatyCookieString(chatyWidgetId, 'c-widget');
                            isSingle = 0;
                            chatyChannels = [];
                            chatyChannel = $(this).data("channel");
                            clickStatus = checkChatyCookieExpired(chatyWidgetId, "c-" + chatyChannel);
                            if (clickStatus) {
                                chatyChannels.push(chatyChannel);
                                isSingle = 1;
                            }
                            if (!isBoatUser) {
                                $.ajax({
                                    url: chaty_settings.ajax_url,
                                    data: {
                                        widgetId: chatyWidgetId,
                                        userId: userId,
                                        isMobile: isChatyInMobile,
                                        channels: chatyChannels,
                                        isSingle: isSingle,
                                        nonce: widgetNonce,
                                        action: 'update_chaty_widget_click'
                                    },
                                    dataType: 'json',
                                    method: 'post',
                                });
                            }
                        }

                        /* checking for CTA status */
                        if ($("#chaty-widget-" + chatyWidgetId).hasClass("first_click")) {
                            $("#chaty-widget-" + chatyWidgetId + " .chaty-tooltip").removeClass("chaty-tooltip");
                            $("#chaty-widget-" + chatyWidgetId + " .single-channel a").addClass("chaty-tooltip");
                        }
                    } else if ($(this).hasClass("chaty-channel")) {
                        chatyChannel = $(this).data("channel");
                        clickStatus = checkChatyCookieExpired(chatyWidgetId, "c-" + chatyChannel);
                        if (clickStatus) {
                            saveChatyCookieString(chatyWidgetId, "c-" + chatyChannel);
                            var widgetNonce = $("#chaty-widget-" + chatyWidgetId).data("nonce");
                            if (!isBoatUser) {
                                $.ajax({
                                    url: chaty_settings.ajax_url,
                                    data: {
                                        widgetId: chatyWidgetId,
                                        userId: userId,
                                        isMobile: isChatyInMobile,
                                        channel: chatyChannel,
                                        nonce: widgetNonce,
                                        action: 'update_chaty_channel_click'
                                    },
                                    dataType: 'json',
                                    method: 'post',
                                });
                            }
                        }
                    }
                }
            }
        });

        $(document).on("submit", ".chaty-ajax-contact-form", function (e) {
            e.preventDefault();
            var inputErrorCounter = 0;
            $(this).find(".has-chaty-error").each(function () {
                $(this).removeClass("has-chaty-error");
            });
            $(this).find(".chaty-error-msg").remove();
            $(this).find(".chaty-ajax-error-message").remove();
            $(this).find(".chaty-ajax-success-message").remove();
            $(this).find(".is-required").each(function () {
                if (jQuery.trim($(this).val()) == "") {
                    inputErrorCounter++;
                    $(this).addClass("has-chaty-error");
                    if($(this).hasClass("chaty-text-block")) {
                        $(this).closest(".chaty-contact-input").find(".mce-tinymce").addClass("mce-error");
                    }
                }
            });
            if (inputErrorCounter == 0) {
                var $form = $(this);
                var form = $form[0];
                var data = new FormData(form);
                $(".chaty-submit-button").attr("disabled", true);
                $("#chaty-submit-button-"+ $form.data("index") + " .chaty-loader").addClass("active");
                jQuery.ajax({
                    url: chaty_settings.ajax_url,
                    enctype: 'multipart/form-data',
                    data: data,
                    type: 'post',
                    dataType: 'json',
                    cache: false,
                    contentType: false,
                    processData: false,
                    success: function (response) {
                        if(googleV3Token != "") {
                            googleV3Token = "";
                            refreshG3Token();
                        }
                        $(".chaty-ajax-error-message").remove();
                        $(".chaty-ajax-success-message").remove();
                        $(".chaty-submit-button").attr("disabled", false);
                        if (response.status == 1) {
                            $("#chaty-submit-button-"+ $form.data("index") + " .chaty-loader").removeClass("active");
                            $(".chaty-contact-inputs").append("<div class='chaty-ajax-success-message'>" + response.message + "</div>");
                            $(".chaty-ajax-contact-form").find(".chaty-contact-input .mce-tinymce").removeClass("mce-error");
                            $(".field-name, .field-email, .field-message, .field-phone").val("");
                            $(".chaty-ajax-contact-form").find(".chaty-input-field").val("");
                            $(".chaty-ajax-contact-form").find(".chaty-textarea-field").val("");
                            $("#"+$form.find(".chaty-text-block").attr("id")+"_ifr").contents().find("body").html("");
                            if (response.redirect_action == "yes") {
                                if (response.link_in_new_tab == "yes") {
                                    var openInNewTab = window.open(response.redirect_link, '_blank');
                                    if(openInNewTab == null) {
                                        window.open(response.redirect_link);
                                    }
                                } else {
                                    window.location = response.redirect_link;
                                }
                            }
                            if (response.close_form_after == "yes") {
                                setTimeout(function () {
                                    if ($(".chaty-outer-forms.active").length) {
                                        var widgetId = $(".chaty-outer-forms.active").data('widget');
                                        if (!isEmpty(widgetId) || widgetId == 0) {
                                            if ($("#chaty-widget-" + widgetId).length) {
                                                $("#chaty-widget-" + widgetId).removeClass("form-open");
                                                $("body").removeClass("add-bg-blur-effect");
                                                $(".chaty-outer-forms.active").removeClass("active");
                                                if ($("#chaty-widget-" + widgetId).find(".chaty-widget").hasClass("chaty-no-close-button")) {
                                                    $("#chaty-widget-" + widgetId).find(".chaty-widget:not(.has-single)").addClass("chaty-open")
                                                }
                                            }
                                        }
                                    }
                                }, parseInt(response.close_form_after_seconds) * 1000);
                            }
                        } else if (response.error == 1) {
                            if (response.errors.length) {
                                for (var i = 0; i < response.errors.length; i++) {
                                    $("." + response.errors[i].field).addClass("has-chaty-error");
                                    $("." + response.errors[i].field).after("<span class='chaty-error-msg'>" + response.errors[i].message + "</span>");
                                }
                            }
                            $(".chaty-loader").removeClass("active");
                        } else if(response.status == 0) {
                            $(".chaty-contact-inputs").append("<div class='chaty-ajax-error-message'>" + response.message + "</div>");
                            $(".chaty-loader").removeClass("active");
                        }
                        $(".email_suggestion").html('');
                    }
                });
            } else {
                $(".has-chaty-error:first").focus();
            }
            return false;
        });

        var domains = ['hotmail.com', 'gmail.com', 'aol.com', 'premio.io'];
        var topLevelDomains = ["com", "net", "org", "io"];
        jQuery(document).on('blur','.chaty-contact-form-box .field-email', function(event) {
            var widget_id = $(this).closest(".chaty-contact-form-box").data("widget");
            jQuery(this).mailcheck({
                // domains: domains,                       // optional
                // topLevelDomains: topLevelDomains,       // optional
                suggested: function(element, suggestion) {
                    // callback code
                    jQuery('#email_suggestion'+widget_id).html("Did you mean <b><i>" + suggestion.full + "</b></i>?");
                },
                empty: function(element) {
                    // callback code
                    jQuery('#email_suggestion'+widget_id).html('');
                }
            });
        });

        if($(".chaty-contact-form-box .field-email").length) {
            $(".chaty-contact-form-box .field-email").emailautocomplete({
                domains: ["protonmail.com", "yahoo.com", "gmail.com"] //add your own domains
            });
        }

        $(document).on("click", ".email_suggestion i", function (){
            $(this).closest(".chaty-contact-form-box").find(".field-email").val($(this).text()).focus();
            jQuery(this).closest(".email_suggestion").html('');
        });

        /* Click function for Call */
        $(document).on("click", ".chaty-widget.has-single .chaty-i-trigger .chaty-channel:not(.chaty-agent-button).Phone-channel", function () {
            window.location = $(this).find("a").prop("href");
        });

        $(document).on("click", ".chaty-widget.has-single .chaty-i-trigger .chaty-channel:not(.chaty-agent-button).Phone-channel a, .picmo__popupContainer", function (e) {
            e.stopPropagation();
            e.stopImmediatePropagation();
        });

        $(document).on("click", ".chaty-wp-emoji-input", function (){
            if($(".picmo__popupContainer").length) {
            } else {
                const {createPopup} = window.picmoPopup;
                const trig = document.querySelector("#chaty_whatsapp_input");

                const picker = createPopup({}, {
                    referenceElement: trig,
                    triggerElement: trig,
                    position: 'top',
                    hideOnEmojiSelect: false
                });

                picker.toggle();

                picker.addEventListener('emoji:select', (selection) => {
                    $('.chaty-whatsapp-input').val($(".chaty-whatsapp-input").val() + selection.emoji);
                });
            }
        });

        $(document).on("click", "#chaty_whatsapp_input", function (){
            if($(".picmo__popupContainer").length) {
                $(".picmo__popupContainer").remove();
            }
        });

    });

    function setChatyEditor() {
        if($(".chaty-text-block:not(.editor-loaded)").length) {
            $(".chaty-text-block:not(.editor-loaded)").each(function(){
                text_id = $(this).attr("id");
                wp.editor.initialize(
                    text_id,
                    {
                        tinymce: {
                            wpautop: false,
                            toolbar1: 'bold italic underline',
                        },
                        quicktags: false
                    }
                );
            })
        }
    }

    /**
     *
     * add class to body to check dimension
     * Added On: 08/17/2022
     * Added By: Chirag Thummar
     *
     * */
    $(window).resize(function(){
        if($(window).height() > $(window).width()) {
            $("body").addClass("cht-portrait").removeClass("cht-landscape");
        } else {
            $("body").addClass("cht-landscape").removeClass("cht-portrait");
        }
    });

    /**
     *
     * To remove animation when widget is clicked
     * Added On: 09/29/2021
     * Added By: Chirag Thummar
     *
     * */
    function removeChatyAnimation(widgetId) {
        if ($("#chaty-widget-" + widgetId).data("animation") != undefined && $("#chaty-widget-" + widgetId).data("animation") != "none") {
            var animationClass = "chaty-animation-" + $("#chaty-widget-" + widgetId).data("animation");
            $("#chaty-widget-" + widgetId + " ." + animationClass).removeClass(animationClass);
        }
        $("#chaty-widget-" + widgetId+ " .ch-pending-msg").remove();
    }

    function checkForCountry() {
        var hasCountryFilter = false;
        if (widgetData.length) {
            $.each(widgetData, function (key, widgetRecord) {
                if (isTrue(widgetRecord.triggers.has_countries) && !isEmpty(widgetRecord.triggers.countries) && widgetRecord.triggers.countries.length) {
                    hasCountryFilter = true;
                }
            });
        }
        if (hasCountryFilter) {
            clientCountry = getUserCountry();
            if (clientCountry != '') {
                startMakingWidgets();
            } else {
                getClientCountry();
            }
        } else {
            startMakingWidgets();
        }
    }

    /**
     *
     * Get client country from cloudflare API
     * Added On: 10/21/2021
     * Added By: Chirag Thummar
     *
     * */

    function getClientCountry() {
        var $ipurl = 'https://www.cloudflare.com/cdn-cgi/trace';
        $.get($ipurl, function (cloudflaredata) {
            var currentCountry = cloudflaredata.match("loc=(.*)");
            if (currentCountry.length > 1) {
                currentCountry = currentCountry[1];
                if (currentCountry) {
                    currentCountry = currentCountry.toUpperCase();
                    if (currentCountry == "") {
                        currentCountry = "-";
                    }
                    setUserCountry(currentCountry);
                    startMakingWidgets();
                }
            }
        });
    }

    /**
     *
     * Creating widgets from API response
     * Added On: 10/21/2021
     * Added By: Chirag Thummar
     *
     * */
    function startMakingWidgets() {
        if (widgetData.length) {
            $("body").append('<svg width="39" height="39" viewBox="0 0 39 39" class="hide-cht-svg-bg" fill="none" xmlns="http://www.w3.org/2000/svg"><defs> <linearGradient id="linear-gradient" x1="0.892" y1="0.192" x2="0.128" y2="0.85" gradientUnits="objectBoundingBox"> <stop offset="0" stop-color="#4a64d5"/> <stop offset="0.322" stop-color="#9737bd"/> <stop offset="0.636" stop-color="#f15540"/> <stop offset="1" stop-color="#fecc69"/> </linearGradient> </defs>');
            $.each(widgetData, function (key, widgetRecord) {

                var customCSS = "";
                var advanceCustomCSS = "";
                var activeChannels = 0;
                var activeChannelList = [];
                var channelSetting = {};

                /* check for country filter */
                var widgetStatus = checkForUserCountry(widgetRecord);
                widgetStatus = widgetStatus && checkForTimeSchedule(widgetRecord);
                widgetStatus = widgetStatus && checkForDayAndTimeSchedule(widgetRecord);

                // Chatway Settings
                let chatwaySettings = null;
                let hasChatway = 0;
                let chatwayPosition = null;
                let hasOnlyChatway = false;

                $.each(widgetRecord.channels, function (key, channel) {
                    var channelStatus = checkForChannel(channel);
                    if (channelStatus) {
                        if(channel.channel === "Chatway") {
                            hasChatway = true;
                            chatwayPosition = channel.chatway_position;
                            chatwaySettings = channel;
                            if (chatwayPosition === "inside-chaty" || widgetRecord.settings.cta_type === "chat-view") {
                                hasOnlyChatway = true;
                                activeChannels++;
                                activeChannelList.push(channel);
                            }
                        } else {
                            channelSetting = channel;
                            activeChannelList.push(channel);
                            activeChannels++;
                        }
                    }
                });

                if (widgetRecord.settings.default_state == "open" && activeChannels == 1) {
                    widgetRecord.settings.default_state = "click";
                    widgetData[key].settings.default_state = "click";
                }

                let chatwayClass = '';
                if(hasChatway) {
                    chatwayClass = 'has-chatway-channel'
                }

                let skipWidget = (activeChannels == 1 && hasOnlyChatway) ? false : true;

                if (skipWidget && widgetStatus && activeChannels > 0 && !$("#chaty-widget-" + widgetRecord.id).length) {
                    var widgetPosition = getWidgetPosition(widgetRecord.settings);
                    widgetPosition = (widgetPosition == "right") ? "right" : "left";
                    var toolTipPosition = getToolTipPosition(widgetRecord);
                    if(widgetRecord.settings.cta_type == "chat-view") {
                        var widgetHtml = "<div style='display: none' class='chaty chaty-has-chat-view chaty-id-" + widgetRecord.id + " chaty-widget-" + widgetRecord.id + " chaty-key-" + key + "' id='chaty-widget-" + widgetRecord.id + "' data-key='" + key + "' data-id='" + widgetRecord.id + "' data-identifier='" + widgetRecord.identifier + "' data-nonce='" + widgetRecord.settings.widget_token + "' >" +
                            "<div class='chaty-widget " + widgetPosition + "-position'>" +
                            "<div class='chaty-channels'>" +
                            "<div class='chaty-channel-list' id='csaas-channels-"+widgetRecord.id+"'></div>" +
                            "<div class='chaty-i-trigger csaas-widget-trigger "+chatwayClass+"'></div>" +
                            "</div>" +
                            "</div>" +
                            "</div>";
                        $("body").append(widgetHtml);

                        makeChatyChatView(widgetRecord);
                    } else {
                        var widgetHtml = "<div style='display: none' class='chaty chaty-id-" + widgetRecord.id + " chaty-widget-" + widgetRecord.id + " chaty-key-" + key + "' id='chaty-widget-" + widgetRecord.id + "' data-key='" + key + "' data-id='" + widgetRecord.id + "' data-identifier='" + widgetRecord.identifier + "' data-nonce='" + widgetRecord.settings.widget_token + "' >" +
                            "<div class='chaty-widget " + widgetPosition + "-position'>" +
                            "<div class='chaty-channels'>" +
                            "<div class='chaty-channel-list' id='csaas-channels-"+widgetRecord.id+"'></div>" +
                            "<div class='chaty-i-trigger csaas-widget-trigger "+chatwayClass+"'></div>" +
                            "</div>" +
                            "</div>" +
                            "</div>";
                        $("body").append(widgetHtml);
                    }

                    if (isTrue(widgetRecord.triggers.auto_hide_widget) && parseInt(widgetRecord.triggers.hide_after) > 0) {
                        $("#chaty-widget-" + widgetRecord.id).addClass("auto-hide-chaty");
                        $("#chaty-widget-" + widgetRecord.id).attr("data-time", widgetRecord.triggers.hide_after);
                    }

                    var clickStatus = checkChatyCookieExpired(widgetRecord.id, 'c-widget');
                    $("#chaty-widget-" + widgetRecord.id).addClass(widgetRecord.settings.show_cta);
                    if (activeChannels == 1 && widgetRecord.settings.cta_type != "chat-view") {
                        if (widgetRecord.settings.icon_view != "vertical") {
                            toolTipPosition = (widgetPosition != "right") ? "right" : "left";
                        }
                        var channelHtml = getChannelSetting(channelSetting, widgetRecord.id, toolTipPosition);
                        $("#chaty-widget-" + widgetRecord.id + " .chaty-i-trigger").append(channelHtml);
                        $("#chaty-widget-" + widgetRecord.id + " .chaty-i-trigger").addClass("single-channel");
                        $("#chaty-widget-" + widgetRecord.id + " .chaty-i-trigger .chaty-channel").addClass("single");

                        $("#chaty-widget-" + widgetRecord.id + " .chaty-widget").addClass("has-single");

                        var ctaText = widgetRecord.settings.cta_text;
                        if(!isEmpty(ctaText)) {
                            ctaText = htmlDecode(ctaText);
                        }
                        if (widgetRecord.settings.show_cta == "first_click") {
                            if (clickStatus) {
                                $("#chaty-widget-" + widgetRecord.id + " .chaty-tooltip").removeClass("chaty-tooltip");
                                $("#chaty-widget-" + widgetRecord.id + " .chaty-i-trigger .chaty-channel").addClass("active").addClass("chaty-tooltip").addClass("pos-"+toolTipPosition);

                                $("#chaty-widget-" + widgetRecord.id + " .chaty-i-trigger .chaty-channel").append("<span class='on-hover-text'>"+ctaText+"</span>").addClass("active").addClass("has-on-hover");
                                $("#chaty-widget-" + widgetRecord.id + " .chaty-i-trigger .chaty-channel a").append("<span class='on-hover-text'>"+ctaText+"</span>").addClass("has-on-hover");
                                $("#chaty-widget-" + widgetRecord.id + " .chaty-i-trigger .chaty-channel a").attr("data-text", ctaText);
                            } else {
                                $("#chaty-widget-" + widgetRecord.id + " .chaty-i-trigger .chaty-channel a").append("<span class='on-hover-text'>"+ctaText+"</span>").removeClass("active").addClass("has-on-hover");
                            }
                        }
                        if (widgetRecord.settings.show_cta == "all_time") {
                            $("#chaty-widget-" + widgetRecord.id + " .chaty-i-trigger .chaty-tooltip").append("<span class='on-hover-text'>"+ctaText+"</span>").addClass("active").addClass("has-on-hover");
                            $("#chaty-widget-" + widgetRecord.id + " .chaty-i-trigger.single-channel .chaty-channel a").attr("data-text", ctaText);
                        }

                        var channel = channelSetting;
                        if (channel.channel_type != "Instagram" || (channel.icon_color != "#ffffff" && channel.icon_color != "#fff")) {
                            customCSS += "#chaty-widget-" + widgetRecord.id + " ." + channel.channel_type + "-channel .color-element{ fill: " + channel.icon_color + "; color: " + channel.icon_color + ";}";
                            customCSS += "#chaty-widget-" + widgetRecord.id + " .channel-icon-" + channel.channel_type + " .color-element{ fill: " + channel.icon_color + "; color: " + channel.icon_color + ";}";
                        }

                        customCSS += "#chaty-widget-" + widgetRecord.id + " ." + channel.channel_type + "-channel .chaty-custom-icon { background-color: " + channel.icon_color + "; }";
                        customCSS += "#chaty-widget-" + widgetRecord.id + " ." + channel.channel_type + "-channel .chaty-svg { background-color: " + channel.icon_color + ";}";
                        customCSS += "#chaty-widget-" + widgetRecord.id + " .channel-icon-" + channel.channel_type + " .chaty-svg { background-color: " + channel.icon_color + ";}";

                        if(channel.channel_type == "Contact_Us") {
                            customCSS += ".chaty-contact-form-box #chaty-submit-button-" + widgetRecord.id + " {background-color: "+channel.contact_form_settings.button_bg_color+"; color: "+channel.contact_form_settings.button_text_color+";} ";
                            customCSS += "#chaty-form-" + widgetRecord.id + "-Contact_Us .chaty-contact-form-title {background-color: "+channel.contact_form_settings.title_bg_color+"; } ";
                        }

                        var closeHtml = '<div class="chaty-channel chaty-cta-close chaty-tooltip pos-' + toolTipPosition + '" data-hover="' + widgetRecord.settings.close_text + '">' +
                            '<div class="chaty-cta-button"><button type="button">' +
                            '<span class="chaty-svg">' +
                            '<svg viewBox="0 0 52 52" fill="none" xmlns="http://www.w3.org/2000/svg"><ellipse cx="26" cy="26" rx="26" ry="26" fill="' + widgetRecord.settings.widget_color + '"></ellipse><rect width="27.1433" height="3.89857" rx="1.94928" transform="translate(18.35 15.6599) scale(0.998038 1.00196) rotate(45)" fill="' + widgetRecord.settings.widget_icon_color + '"></rect><rect width="27.1433" height="3.89857" rx="1.94928" transform="translate(37.5056 18.422) scale(0.998038 1.00196) rotate(135)" fill="' + widgetRecord.settings.widget_icon_color + '"></rect></svg>' +
                            '</span>' +
                            '<span class="sr-only">Hide chaty</span>' +
                            '</button>' +
                            '</div>' +
                            '</div>';
                        $("#chaty-widget-" + widgetRecord.id + " .chaty-i-trigger").append(closeHtml);
                    } else {
                        $.each(widgetRecord.channels, function (key, channel) {
                            var channelStatus = checkForChannel(channel);
                            if (channelStatus && (channel.channel !== "Chatway" || (chatwayPosition === "inside-chaty" || widgetRecord.settings.cta_type === "chat-view"))) {

                                if (isValueEmpty(channel.channel_type)) {
                                    channel.channel_type = channel.channel;
                                }

                                if(widgetRecord.settings.cta_type == "chat-view") {
                                    var channelHtml = getChannelSetting(channel, widgetRecord.id, "top");
                                    $(".chaty-chat-view-" + widgetRecord.id + " .chaty-view-channels").append(channelHtml);
                                } else {
                                    var channelHtml = getChannelSetting(channel, widgetRecord.id, toolTipPosition);
                                    $("#chaty-widget-" + widgetRecord.id + " .chaty-channel-list").append(channelHtml);
                                }

                                if (channel.channel_type != "Instagram" || (channel.icon_color != "#ffffff" && channel.icon_color != "#fff")) {
                                    customCSS += "#chaty-widget-" + widgetRecord.id + " ." + channel.channel_type + "-channel .color-element{ fill: " + channel.icon_color + "; color: " + channel.icon_color + ";}";
                                    customCSS += "#chaty-widget-" + widgetRecord.id + " .channel-icon-" + channel.channel_type + " .color-element{ fill: " + channel.icon_color + "; color: " + channel.icon_color + ";}";
                                }

                                customCSS += "#chaty-widget-" + widgetRecord.id + " ." + channel.channel_type + "-channel .chaty-custom-icon { background-color: " + channel.icon_color + "; }";
                                customCSS += "#chaty-widget-" + widgetRecord.id + " ." + channel.channel_type + "-channel .chaty-svg { background-color: " + channel.icon_color + ";}";
                                customCSS += "#chaty-widget-" + widgetRecord.id + " .channel-icon-" + channel.channel_type + " .chaty-svg { background-color: " + channel.icon_color + ";}";

                                customCSS += ".chaty-chat-view-" + widgetRecord.id + " ." + channel.channel_type + "-channel .chaty-custom-icon { background-color: " + channel.icon_color + "; }";
                                customCSS += ".chaty-chat-view-" + widgetRecord.id + " ." + channel.channel_type + "-channel .chaty-svg { background-color: " + channel.icon_color + ";}";
                                customCSS += ".chaty-chat-view-" + widgetRecord.id + " .channel-icon-" + channel.channel_type + " .chaty-svg { background-color: " + channel.icon_color + ";}";

                                if(channel.channel_type == "Contact_Us") {
                                    customCSS += ".chaty-contact-form-box #chaty-submit-button-" + widgetRecord.id + " {background-color: "+channel.contact_form_settings.button_bg_color+"; color: "+channel.contact_form_settings.button_text_color+";} ";
                                    customCSS += "#chaty-form-" + widgetRecord.id + "-Contact_Us .chaty-contact-form-title {background-color: "+channel.contact_form_settings.title_bg_color+"; } ";
                                }
                            }
                        });

                        var widgetIcon = getWidgetIcon(widgetRecord.settings, widgetRecord.id);
                        /* check for widget CTA button */
                        var ctaText = widgetRecord.settings.cta_text;
                        if (widgetRecord.settings.show_cta == "first_click") {
                            if (!clickStatus) {
                                ctaText = "";
                            }
                        }

                        var ctaToolTipPosition = toolTipPosition;
                        if (widgetRecord.settings.icon_view == "horizontal") {
                            if (widgetPosition == "left") {
                                ctaToolTipPosition = "right";
                            } else {
                                ctaToolTipPosition = "left";
                            }
                        }

                        if(!isEmpty(ctaText)) {
                            ctaText = htmlDecode(ctaText);
                        }

                        var widgetButton = '<div class="chaty-channel chaty-cta-main chaty-tooltip has-on-hover pos-' + ctaToolTipPosition + ' active" data-widget="' + widgetRecord.id + '" >' +
                            '<span class="on-hover-text">'+ctaText+'</span>' +
                            '<div class="chaty-cta-button">' +
                            '<button type="button" class="open-chaty">' +
                            widgetIcon +
                            '<span class="sr-only">Open chaty</span>' +
                            '</button>' +
                            '<button type="button" class="open-chaty-channel"><span class="sr-only">chaty buttons</span></button>' +
                            '</div>' +
                            '</div>';
                        $("#chaty-widget-" + widgetRecord.id + " .chaty-i-trigger").html(widgetButton);

                        /* close button */
                        var closeHtml = '<div class="chaty-channel chaty-cta-close chaty-tooltip pos-' + toolTipPosition + '" data-hover="' + widgetRecord.settings.close_text + '">' +
                            '<div class="chaty-cta-button"><button type="button">' +
                            '<span class="chaty-svg">' +
                            '<svg viewBox="0 0 52 52" fill="none" xmlns="http://www.w3.org/2000/svg"><ellipse cx="26" cy="26" rx="26" ry="26" fill="' + widgetRecord.settings.widget_color + '"></ellipse><rect width="27.1433" height="3.89857" rx="1.94928" transform="translate(18.35 15.6599) scale(0.998038 1.00196) rotate(45)" fill="' + widgetRecord.settings.widget_icon_color + '"></rect><rect width="27.1433" height="3.89857" rx="1.94928" transform="translate(37.5056 18.422) scale(0.998038 1.00196) rotate(135)" fill="' + widgetRecord.settings.widget_icon_color + '"></rect></svg>' +
                            '</span>' +
                            '<span class="sr-only">Hide chaty</span>' +
                            '</button>' +
                            '</div>' +
                            '</div>';
                        $("#chaty-widget-" + widgetRecord.id + " .chaty-i-trigger").append(closeHtml);
                    }

                    /* check for State */
                    $.each(widgetRecord.channels, function (key, channel) {
                        if(channel.channel_type == "Contact_Us" && channel.hide_recaptcha_badge == "yes") {
                            customCSS += ".grecaptcha-badge {visibility: hidden;}";
                        }
                    });


                    if(widgetRecord.settings.cta_type == "chat-view") {
                        widgetRecord.settings.show_close_button == "yes";
                    }


                    var clickStatus = checkChatyCookieExpired(widgetRecord.id, 'c-widget');
                    if (clickStatus && (widgetRecord.settings.default_state != "open" || activeChannels == 1)) {
                        checkForPendingMessage(widgetRecord.settings, widgetRecord.id);
                        checkForWidgetAnimation(widgetRecord.settings, widgetRecord.id);
                    }

                    var extraSpace = 0;
                    /* check for close button */
                    if (widgetRecord.settings.default_state == "open" && !isTrue(widgetRecord.settings.show_close_button)) {
                        $("#chaty-widget-" + widgetRecord.id + " .chaty-widget").addClass("chaty-no-close-button").addClass("chaty-open");
                        extraSpace = 1;
                    }

                    if(hasChatway) {
                        $("#chaty-widget-"+widgetRecord.id+" .chaty-widget").addClass(chatwayPosition);
                        if (chatwayPosition === "above-chaty") {
                            $("#chaty-widget-"+widgetRecord.id+" .chaty-widget").addClass("has-chatway");

                            let toolTipPosition = getToolTipPosition(widgetRecord);

                            let channelHtml = getChannelSetting(chatwaySettings, widgetRecord.id, toolTipPosition, key, widgetRecord.settings);
                            if ($("#chaty-widget-"+widgetRecord.id).length) {
                                $("#chaty-widget-"+widgetRecord.id+" .chaty-channel-list").prepend(channelHtml);
                                customExtraCSS += "#chaty-widget-" + widgetRecord.id + " ." + chatwaySettings.channel_type + "-channel .color-element{ fill: " + chatwaySettings.icon_color + "; color: " + chatwaySettings.icon_color + ";}";
                                customExtraCSS += "#chaty-widget-" + widgetRecord.id + " ." + chatwaySettings.channel_type + "-channel .csaas-svg { background-color: " + chatwaySettings.icon_color + ";}";
                                customExtraCSS += ".channel-" + widgetRecord.id + "-" + chatwaySettings.channel_type + " .csaas-svg { background-color: " + chatwaySettings.icon_color + ";}";

                                customCSS += "#chaty-widget-" + widgetRecord.id + " ." + chatwaySettings.channel_type + "-channel .chaty-custom-icon { background-color: " + chatwaySettings.icon_color + "; }";
                                customCSS += "#chaty-widget-" + widgetRecord.id + " ." + chatwaySettings.channel_type + "-channel .chaty-svg { background-color: " + chatwaySettings.icon_color + ";}";
                                customCSS += "#chaty-widget-" + widgetRecord.id + " .channel-icon-" + chatwaySettings.channel_type + " .chaty-svg { background-color: " + chatwaySettings.icon_color + ";}";
                            }
                        }
                    }

                    /* checking for google analytics */
                    if (isTrue(widgetRecord.settings.is_google_analytics_enabled)) {
                        $("#chaty-widget-" + widgetRecord.id + " .chaty-channel-list .chaty-channel > a").addClass("has-gae");
                        $("#chaty-form-" + widgetRecord.id + "-chaty-chat-view .chaty-channel > a").addClass("has-gae");
                        $("#chaty-widget-" + widgetRecord.id + " .chaty-i-trigger.single-channel .chaty-channel > a").addClass("has-gae");

                        $(".chaty-outer-forms.chaty-whatsapp-btn-form.chaty-form-" + widgetRecord.id + " form.add-analytics").addClass("form-google-analytics");
                        $(".whatsapp-chaty-form-" + widgetRecord.id).addClass("has-form-gae");
                    }

                    /* checking for custom CSS */
                    if (isTrue(widgetRecord.settings.has_custom_css) && !isEmpty(widgetRecord.settings.custom_css)) {
                        advanceCustomCSS += widgetRecord.settings.custom_css;
                    }

                    /* check for State */
                    if (widgetRecord.settings.default_state == "hover") {
                        $("#chaty-widget-" + widgetRecord.id).addClass("open-on-hover");
                    } else if (widgetRecord.settings.default_state == "open") {

                        $("#chaty-widget-" + widgetRecord.id + " .chaty-widget").addClass("default-open");
                        if (clickStatus || !isTrue(widgetRecord.settings.show_close_button)) {
                            $("#chaty-widget-" + widgetRecord.id + " .chaty-widget:not(.has-single)").addClass("chaty-open");
                        }
                        if(!clickStatus) {
                            $("#chaty-widget-" + widgetRecord.id + " .chaty-widget").removeClass("default-open");
                        }
                    }

                    if($("#chaty-widget-" + widgetRecord.id + " .chaty-widget:not(.has-single):not(.chaty-no-close-button)").hasClass("default-open")) {
                        if (isTrue(widgetRecord.settings.bg_blur_effect)) {
                            $("body").addClass("add-bg-blur-effect");
                        }
                    } else {
                        $("body").removeClass("add-bg-blur-effect");
                    }

                    if (isTrue(widgetRecord.settings.bg_blur_effect)) {
                        $("#chaty-widget-" + widgetRecord.id + " .chaty-widget:not(.chaty-no-close-button)").addClass("has-bg-blur-effect");
                    }

                    /* set widget channel height */
                    var widgetSize = getWidgetSize(widgetRecord.settings.widget_size, widgetRecord.settings.custom_widget_size);
                    widgetSize = parseInt(widgetSize);
                    customCSS += "#chaty-widget-" + widgetRecord.id + " .chaty-channel-list .chaty-channel {width: " + widgetSize + "px; height: " + widgetSize + "px; }";
                    customCSS += "#chaty-widget-" + widgetRecord.id + " .chaty-channel-list .chaty-channel > a {width: " + widgetSize + "px; height: " + widgetSize + "px; }";
                    customCSS += "#chaty-widget-" + widgetRecord.id + " .chaty-channel-list .chaty-channel > a .chaty-custom-icon {display:block; width: " + widgetSize + "px; height: " + widgetSize + "px; line-height: " + widgetSize + "px; font-size: " + parseInt(widgetSize / 2) + "px; }";
                    customCSS += "#chaty-widget-" + widgetRecord.id + " .chaty-channel-list .chaty-channel button {width: " + widgetSize + "px; height: " + widgetSize + "px; margin: 0; padding:0; outline: none; border-radius: 50%;}";
                    customCSS += "#chaty-widget-" + widgetRecord.id + " .chaty-channel-list .chaty-channel .chaty-svg {width: " + widgetSize + "px; height: " + widgetSize + "px; }";
                    customCSS += "#chaty-widget-" + widgetRecord.id + " .chaty-channel-list .chaty-channel .chaty-svg img {width: " + widgetSize + "px; height: " + widgetSize + "px; }";
                    customCSS += "#chaty-widget-" + widgetRecord.id + " .chaty-channel-list .chaty-channel span.chaty-icon {width: " + widgetSize + "px; height: " + widgetSize + "px; }";
                    customCSS += "#chaty-widget-" + widgetRecord.id + " .chaty-channel-list .chaty-channel a {width: " + widgetSize + "px; height: " + widgetSize + "px; }";
                    customCSS += "#chaty-widget-" + widgetRecord.id + " .chaty-channel-list .chaty-channel .chaty-svg .chaty-custom-channel-icon {width: " + widgetSize + "px; height: " + widgetSize + "px; line-height: " + widgetSize + "px; display: block; font-size:" + (parseInt(widgetSize / 2)) + "px; }";
                    customCSS += "#chaty-widget-" + widgetRecord.id + " .chaty-i-trigger .chaty-cta-button {background-color: " + widgetRecord.settings.widget_color + "; }";
                    customCSS += "#chaty-widget-" + widgetRecord.id + " .chaty-i-trigger .chaty-cta-button button {background-color: " + widgetRecord.settings.widget_color + "; }";
                    customCSS += "#chaty-widget-" + widgetRecord.id + " .chaty-i-trigger .chaty-channel {width: " + widgetSize + "px; height: " + widgetSize + "px; }";
                    customCSS += "#chaty-widget-" + widgetRecord.id + " .chaty-i-trigger .chaty-channel > a {width: " + widgetSize + "px; height: " + widgetSize + "px; }";
                    customCSS += "#chaty-widget-" + widgetRecord.id + " .chaty-i-trigger .chaty-channel > a .chaty-custom-icon {display:block; width: " + widgetSize + "px; height: " + widgetSize + "px; line-height: " + widgetSize + "px; font-size: " + parseInt(widgetSize / 2) + "px; }";
                    customCSS += "#chaty-widget-" + widgetRecord.id + " .chaty-i-trigger .chaty-channel button {width: " + widgetSize + "px; height: " + widgetSize + "px; margin: 0; padding:0; outline: none; border-radius: 50%;}";
                    customCSS += "#chaty-widget-" + widgetRecord.id + " .chaty-i-trigger .chaty-channel .chaty-svg {width: " + widgetSize + "px; height: " + widgetSize + "px; }";
                    customCSS += "#chaty-widget-" + widgetRecord.id + " .chaty-i-trigger .chaty-channel .chaty-svg img {width: " + widgetSize + "px; height: " + widgetSize + "px; }";
                    customCSS += "#chaty-widget-" + widgetRecord.id + " .chaty-i-trigger .chaty-channel span.chaty-icon {width: " + widgetSize + "px; height: " + widgetSize + "px; }";
                    customCSS += "#chaty-widget-" + widgetRecord.id + " .chaty-i-trigger .chaty-channel a {width: " + widgetSize + "px; height: " + widgetSize + "px; }";
                    customCSS += "#chaty-widget-" + widgetRecord.id + " .chaty-i-trigger .chaty-channel .chaty-svg .chaty-custom-channel-icon {width: " + widgetSize + "px; height: " + widgetSize + "px; line-height: " + widgetSize + "px; display: block; font-size:" + (parseInt(widgetSize / 2)) + "px; }";

                    customCSS += "#chaty-widget-" + widgetRecord.id + " .chaty-i-trigger .ch-pending-msg {background-color: " + widgetRecord.settings.pending_mesg_count_bgcolor + "; color: " + widgetRecord.settings.pending_mesg_count_color + "; }";

                    customCSS += "#chaty-widget-" + widgetRecord.id + " .chaty-i-trigger .chaty-channel .chaty-svg .widget-fa-icon {line-height: " + widgetSize + "px; font-size:" + (parseInt(widgetSize / 2)) + "px; }";

                    if (widgetRecord.settings.icon_view == "vertical") {
                        customCSS += "#chaty-widget-" + widgetRecord.id + " .chaty-channel-list {height: " + (activeChannels * (widgetSize + 8)) + "px; }";
                        customCSS += "#chaty-widget-" + widgetRecord.id + " .chaty-channel-list {width: " + (widgetSize + 8) + "px; }";

                        for (var i = 0; i <= activeChannels; i++) {
                            customCSS += "#chaty-widget-" + widgetRecord.id + " .chaty-open .chaty-channel-list .chaty-channel:nth-child(" + (i + 1) + ") {-webkit-transform: translateY(-" + ((widgetSize + 8) * (activeChannels - i - extraSpace)) + "px); transform: translateY(-" + ((widgetSize + 8) * (activeChannels - i - extraSpace)) + "px);}";
                        }
                        if(hasChatway && chatwayPosition == "inside-chaty") {
                            let i = 0, j = 1;
                            for(let key = 0; key < activeChannelList.length; key++){
                                channel = activeChannelList[key]
                                customCSS += "body.chatway--active #chaty-widget-" + widgetRecord.id + " .chaty-open .chaty-channel-list .chaty-channel."+channel.channel+"-channel {-webkit-transform: translateY(-" + ((widgetSize + 8) * (activeChannels - i - extraSpace)) + "px); transform: translateY(-" + ((widgetSize + 8) * (activeChannels - i - extraSpace)) + "px);}";
                                if(channel.channel != "Chatway") {
                                    customCSS += "body:not(.chatway--active) #chaty-widget-" + widgetRecord.id + " .chaty-open .chaty-channel-list .chaty-channel."+channel.channel+"-channel {-webkit-transform: translateY(-" + ((widgetSize + 8) * (activeChannels - i  - j - extraSpace)) + "px); transform: translateY(-" + ((widgetSize + 8) * (activeChannels - i - j - extraSpace)) + "px);}";
                                } else {
                                    j = 0;
                                }
                                i++;
                            }
                        } else {
                            let i = 0;
                            for (i = 0; i <= activeChannels; i++) {
                                customCSS += "#chaty-widget-" + widgetRecord.id + " .chaty-open .chaty-channel-list .chaty-channel:nth-child(" + (i + 1) + ") {-webkit-transform: translateY(-" + ((widgetSize + 8) * (activeChannels - i - extraSpace)) + "px); transform: translateY(-" + ((widgetSize + 8) * (activeChannels - i - extraSpace)) + "px);}";
                            }
                            if (chatwayPosition == "above-chaty") {
                                customCSS += "#chaty-widget-" + widgetRecord.id + " .chaty-channel-list .Chatway-channel-link {-webkit-transform: translateY(-" + ((widgetSize + 8) * (extraSpace)) + "px); transform: translateY(-" + ((widgetSize + 8) * (1 - extraSpace)) + "px);}";
                            }
                        }
                    } else {
                        $("#chaty-widget-" + widgetRecord.id + " .chaty-widget").addClass("hor-mode");
                        customCSS += "#chaty-widget-" + widgetRecord.id + " .chaty-channel-list {width: " + (activeChannels * (widgetSize + 8)) + "px; }";
                        customCSS += "#chaty-widget-" + widgetRecord.id + " .chaty-channel-list {height: " + (widgetSize) + "px; }";
                        let i = 0;
                        if(hasChatway && chatwayPosition == "inside-chaty") {
                            let i = 0, j = 1;
                            for(let key = 0; key < activeChannelList.length; key++){
                                channel = activeChannelList[key]
                                customCSS += "body.chatway--active .chaty-widget-" + widgetRecord.id + " .chaty-widget.left-position.hor-mode.chaty-open .chaty-channel-list .chaty-channel:nth-child(" + (i + 1) + ") {-webkit-transform: translateX(" + ((widgetSize + 8) * (activeChannels - i - extraSpace)) + "px); transform: translateX(" + ((widgetSize + 8) * (activeChannels - i - extraSpace)) + "px);}";
                                customCSS += "body.chatway--active .chaty-widget-" + widgetRecord.id + " .chaty-widget.right-position.hor-mode.chaty-open .chaty-channel-list .chaty-channel:nth-child(" + (i + 1) + ") {-webkit-transform: translateX(-" + ((widgetSize + 8) * (activeChannels - i - extraSpace)) + "px); transform: translateX(-" + ((widgetSize + 8) * (activeChannels - i - extraSpace)) + "px);}";
                                if(channel.channel != "Chatway") {
                                    customCSS += "body:not(.chatway--active) #chaty-widget-" + widgetRecord.id + " .chaty-widget.left-position.hor-mode.chaty-open .chaty-channel-list .chaty-channel:nth-child(" + (i + 1) + ") {-webkit-transform: translateX(" + ((widgetSize + 8) * (activeChannels - i - j - extraSpace)) + "px); transform: translateX(" + ((widgetSize + 8) * (activeChannels - i - j - extraSpace)) + "px);}";
                                    customCSS += "body:not(.chatway--active) #chaty-widget-" + widgetRecord.id + " .chaty-widget.right-position.hor-mode.chaty-open .chaty-channel-list .chaty-channel:nth-child(" + (i + 1) + ") {-webkit-transform: translateX(-" + ((widgetSize + 8) * (activeChannels - i - j - extraSpace)) + "px); transform: translateX(-" + ((widgetSize + 8) * (activeChannels - i - j - extraSpace)) + "px);}";
                                } else {
                                    j = 0;
                                }
                                i++;
                            }
                        } else {
                            for (i = 0; i <= activeChannels; i++) {
                                customCSS += "#chaty-widget-" + widgetRecord.id + " .chaty-widget.left-position.hor-mode.chaty-open .chaty-channel-list .chaty-channel:nth-child(" + (i + 1) + ") {-webkit-transform: translateX(" + ((widgetSize + 8) * (activeChannels - i - extraSpace)) + "px); transform: translateX(" + ((widgetSize + 8) * (activeChannels - i - extraSpace)) + "px);}";
                                customCSS += "#chaty-widget-" + widgetRecord.id + " .chaty-widget.right-position.hor-mode.chaty-open .chaty-channel-list .chaty-channel:nth-child(" + (i + 1) + ") {-webkit-transform: translateX(-" + ((widgetSize + 8) * (activeChannels - i - extraSpace)) + "px); transform: translateX(-" + ((widgetSize + 8) * (activeChannels - i - extraSpace)) + "px);}";
                            }
                            if(chatwayPosition == "above-chaty") {
                                customCSS += "#chaty-widget-" + widgetRecord.id + " .chaty-widget.left-position.hor-mode .chaty-channel-list .Chatway-channel-link {-webkit-transform: translateX(" + ((widgetSize + 8) * (1 - extraSpace)) + "px); transform: translateX(" + ((widgetSize + 8) * (1 - extraSpace)) + "px);}";
                                customCSS += "#chaty-widget-" + widgetRecord.id + " .chaty-widget.right-position.hor-mode .chaty-channel-list .Chatway-channel-link {-webkit-transform: translateX(-" + ((widgetSize + 8) * (1 - extraSpace)) + "px); transform: translateX(-" + ((widgetSize + 8) * (1 - extraSpace)) + "px);}";
                            }
                        }
                    }


                    /* set widget position */
                    var bottomSpacing = widgetRecord.settings.bottom_spacing;
                    var sideSpacing = widgetRecord.settings.side_spacing;
                    customCSS += "#chaty-widget-" + widgetRecord.id + " .chaty-widget {bottom: "+(bottomSpacing)+"px}";

                    if (widgetPosition == "left") {
                        customCSS += "#chaty-widget-" + widgetRecord.id + " .chaty-widget {left: " + sideSpacing + "px; right: auto;}";
                        customCSS += ".chaty-outer-forms.pos-left.chaty-form-" + widgetRecord.id + " {left: " + sideSpacing + "px}";
                        $(".chaty-form-" + widgetRecord.id).addClass("pos-left");
                    } else {
                        customCSS += "#chaty-widget-" + widgetRecord.id + " .chaty-widget {right: " + sideSpacing + "px; left:auto;}";
                        $(".chaty-form-" + widgetRecord.id).addClass("pos-right");
                        customCSS += ".chaty-outer-forms.pos-right.chaty-form-" + widgetRecord.id + " {right: " + sideSpacing + "px; left:auto;}";
                    }
                    $(".chaty-form-" + widgetRecord.id).show();

                    var formBottomPos = widgetSize + 15 + parseInt(bottomSpacing)
                    customCSS += ".chaty-outer-forms.active.chaty-form-" + widgetRecord.id + " {-webkit-transform: translateY(-"+formBottomPos+"px); transform: translateY(-"+formBottomPos+"px)} ";
                    customCSS += "#chaty-widget-"+widgetRecord.id+".chaty:not(.form-open) .chaty-widget.chaty-open + .chaty-chat-view {-webkit-transform: translateY(-"+formBottomPos+"px); transform: translateY(-"+formBottomPos+"px)} ";

                    /* set on hover text color */
                    customCSS += "#chaty-widget-" + widgetRecord.id + " .chaty-tooltip:after {background-color: " + widgetRecord.settings.cta_bg_color + "; color: " + widgetRecord.settings.cta_text_color + "}";
                    customCSS += "#chaty-widget-" + widgetRecord.id + " .chaty-tooltip.pos-top:before {border-top-color: " + widgetRecord.settings.cta_bg_color + "; }";
                    customCSS += "#chaty-widget-" + widgetRecord.id + " .chaty-tooltip.pos-left:before {border-left-color: " + widgetRecord.settings.cta_bg_color + "; }";
                    customCSS += "#chaty-widget-" + widgetRecord.id + " .chaty-tooltip.pos-right:before {border-right-color: " + widgetRecord.settings.cta_bg_color + ";}";
                    customCSS += "#chaty-widget-" + widgetRecord.id + " .on-hover-text {background-color: " + widgetRecord.settings.cta_bg_color + "; color: " + widgetRecord.settings.cta_text_color + "}";
                    customCSS += "#chaty-widget-" + widgetRecord.id + " .chaty-tooltip.pos-top .on-hover-text:before {border-top-color: " + widgetRecord.settings.cta_bg_color + "; }";
                    customCSS += "#chaty-widget-" + widgetRecord.id + " .chaty-tooltip.pos-left .on-hover-text:before {border-left-color: " + widgetRecord.settings.cta_bg_color + "; }";
                    customCSS += "#chaty-widget-" + widgetRecord.id + " .chaty-tooltip.pos-right .on-hover-text:before {border-right-color: " + widgetRecord.settings.cta_bg_color + ";}";

                    /* Custom CSS for Agents */
                    var agentMaxHeight = formBottomPos + 72 + widgetSize;
                    if (agentMaxHeight > 0) {
                        customCSS += ".chaty-outer-forms.chaty-form-" + widgetRecord.id + " .chaty-agent-body {max-height: calc(100vh - " + agentMaxHeight + "px); overflow-y: auto; } ";
                    }

                    customCSS += "#chaty-form-" + widgetRecord.id + "-chaty-chat-view .chaty-view-header {background-color: " + widgetRecord.settings.cta_head_bg_color + ";}";
                    customCSS += "#chaty-form-" + widgetRecord.id + "-chaty-chat-view .chaty-view-header {color: " + widgetRecord.settings.cta_head_text_color + ";}";
                    customCSS += "#chaty-form-" + widgetRecord.id + "-chaty-chat-view .chaty-view-header svg {fill : " + widgetRecord.settings.cta_head_text_color + ";}";

                    /* Custom CSS for WhatsApp */
                    var whatsAppMaxHeight = formBottomPos + 72 + widgetSize;
                    if (whatsAppMaxHeight > 0) {
                        // customCSS += ".chaty-outer-forms.chaty-whatsapp-btn-form.chaty-form-" + widgetRecord.id + " .chaty-whatsapp-content {max-height: calc(100vh - " + whatsAppMaxHeight + "px); overflow-y: auto; } ";
                    }

                    /* Custom CSS for Contact Form */
                    var contactFormMaxHeight = formBottomPos + 82 + widgetSize;
                    if (contactFormMaxHeight > 0) {
                        customCSS += ".chaty-outer-forms.chaty-contact-form-box.chaty-form-" + widgetRecord.id + " .chaty-contact-inputs {max-height: calc(100vh - " + contactFormMaxHeight + "px); overflow-y: auto; } ";
                    }

                    if(bottomSpacing != 25 || sideSpacing != 25) {
                        $(".chaty-outer-forms.chaty-form-" + widgetRecord.id).addClass("custom-cht-pos");
                        $("#chaty-widget-"+widgetRecord.id).addClass("has-custom-pos");
                    }

                    var total_wp_form_size = parseInt($("#chaty-form-" + widgetRecord.id + "-Whatsapp .chaty-whatsapp-header").outerHeight()) + parseInt($("#chaty-form-" + widgetRecord.id + "-Whatsapp .chaty-whatsapp-footer").outerHeight()) + parseInt(widgetSize) + parseInt(bottomSpacing) + 20;
                    customCSS += "#chaty-form-" + widgetRecord.id + "-Whatsapp .chaty-whatsapp-body { max-height: calc(100vh - "+total_wp_form_size+"px); overflow-y: auto; }";

                    /* checking for triggers */
                    var visibleStatus = checkChatyCookieExpired(widgetRecord.id, 'v-widget');

                    if (visibleStatus) {
                        if (isTrue(widgetRecord.triggers.exit_intent) || isTrue(widgetRecord.triggers.has_time_delay) || isTrue(widgetRecord.triggers.has_display_after_page_scroll) > 0) {
                            /* checking for time delay */

                            if ((isTrue(widgetRecord.triggers.has_time_delay) && parseInt(widgetRecord.triggers.time_delay) == 0)) {
                                updateWidgetViews(widgetRecord.id);
                                $("#chaty-widget-" + widgetRecord.id).addClass("active");
                            } else if ((isTrue(widgetRecord.triggers.has_time_delay) && parseInt(widgetRecord.triggers.time_delay) > 0)) {
                                chatyHasTimeDelay = true;
                                if (parseInt(widgetRecord.triggers.time_delay) > chatyMaxTimeInterval) {
                                    chatyMaxTimeInterval = widgetRecord.triggers.time_delay;
                                }
                                $("#chaty-widget-" + widgetRecord.id).addClass("on-chaty-delay");
                                $("#chaty-widget-" + widgetRecord.id).addClass("delay-time-" + parseInt(widgetRecord.triggers.time_delay));
                                $("#chaty-widget-" + widgetRecord.id).attr("data-time", parseInt(widgetRecord.triggers.time_delay));
                            }

                            /* checking for page scroll */
                            if ((isTrue(widgetRecord.triggers.has_display_after_page_scroll) && parseInt(widgetRecord.triggers.display_after_page_scroll) == 0)) {
                                updateWidgetViews(widgetRecord.id);
                                $("#chaty-widget-" + widgetRecord.id).addClass("active");
                            } else if ((isTrue(widgetRecord.triggers.has_display_after_page_scroll) && parseInt(widgetRecord.triggers.display_after_page_scroll) > 0)) {
                                chatyHasPageScroll = true;
                                $("#chaty-widget-" + widgetRecord.id).addClass("on-chaty-scroll");
                                $("#chaty-widget-" + widgetRecord.id).addClass("page-scroll-" + parseInt(widgetRecord.triggers.display_after_page_scroll));
                                $("#chaty-widget-" + widgetRecord.id).attr("data-scroll", parseInt(widgetRecord.triggers.display_after_page_scroll));
                            }

                            /* checking for exit intent */
                            if (isTrue(widgetRecord.triggers.exit_intent)) {
                                chatyHasExitIntent = true;
                                $("#chaty-widget-" + widgetRecord.id).addClass("on-chaty-exit-intent");
                            }
                        } else {
                            // saveChatyCookieString(widgetRecord.id, 'v-widget');
                            updateWidgetViews(widgetRecord.id);
                            $("#chaty-widget-" + widgetRecord.id).addClass("active");
                        }
                    } else {
                        updateWidgetViews(widgetRecord.id);
                        $("#chaty-widget-" + widgetRecord.id).addClass("active");
                    }

                    /* check for font family */
                    if (!isEmpty(widgetRecord.settings.font_family) && widgetRecord.settings.font_family != "none") {

                        /* check for default browser font */
                        var fontFamily = widgetRecord.settings.font_family;
                        if ($.inArray(fontFamily, defaultFontFamily) != -1) {
                            if (fontFamily == "System Stack") {
                                fontFamily = "-apple-system,BlinkMacSystemFont,Segoe UI,Roboto,Oxygen-Sans,Ubuntu,Cantarell,Helvetica Neue,sans-serif";
                            }
                        } else {
                            /* load fonts from google */
                            $('head').append('<link rel="preload" as="style" href="https://fonts.googleapis.com/css?family=' + fontFamily + '&display=swap">');
                            $('head').append('<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=' + fontFamily + '&display=swap">');
                        }
                        customCSS += "#chaty-widget-" + widgetRecord.id + ", #chaty-widget-" + widgetRecord.id + " .chaty-tooltip:after {font-family: " + fontFamily + "}";
                    }
                }

                if(chatyHasExitIntent) {
                    bindExitIntentFunction();
                }

                /* set dynamic CSS for widget */
                if (customCSS != "") {
                    if (!$("#custom-chaty-css").length) {
                        $("head").append("<style id='custom-chaty-css'></style>");
                    }
                    $("#custom-chaty-css").append(customCSS);
                }

                /* set dynamic CSS for widget */
                if (advanceCustomCSS != "") {
                    if (!$("#custom-advance-chaty-css").length) {
                        $("head").append("<style id='custom-advance-chaty-css'></style>");
                    }
                    $("#custom-advance-chaty-css").append(advanceCustomCSS);
                }

                if (key == (widgetData.length - 1)) {

                }

                if($(".chaty-sms-channel").length) {
                    $(".chaty-sms-channel").each(function(){
                        var thisLink = $(this).attr("href");
                        thisLink = thisLink.replace(/{title}/g, getPageTitle());
                        thisLink = thisLink.replace(/{url}/g, window.location.href);
                        $(this).attr("href", thisLink);
                    });
                }

                $(document).on("click", "#chaty-widget-"+widgetRecord.id+" .chaty-i-trigger .chaty-channel", function (){
                    if($(this).closest(".chaty-widget").hasClass("has-single")) {
                        if ($(this).closest(".chaty").hasClass("form-open")) {
                            if (isTrue(widgetRecord.settings.bg_blur_effect)) {
                                $("body").addClass("add-bg-blur-effect");
                                console.log("@3233");
                            } else {
                                $("body").removeClass("add-bg-blur-effect");
                            }
                        }
                    } else {
                        if ($(this).closest(".chaty-widget").hasClass("chaty-open")) {
                            $("body").removeClass("add-bg-blur-effect");
                        } else {
                            if (!$(this).closest(".chaty-widget").hasClass("chaty-no-close-button")) {
                                if (isTrue(widgetRecord.settings.bg_blur_effect)) {
                                    $("body").addClass("add-bg-blur-effect");
                                }
                            }
                        }
                    }
                });

            });
            if (!$("#custom-advance-chaty-css").length) {
                $("head").append("<style id='custom-advance-chaty-css'></style>");
            }
            $("#custom-advance-chaty-css").append(customExtraCSS);
            if(($(".v3_site_key").length && !isEmpty($(".v3_site_key").val())) || ($(".v2_site_key").length && !isEmpty($(".v2_site_key").val()))) {
                LoadChatyGoogleRecaptcha();
            }
        }
        removeEmptyTooltip();
        checkForChatyTriggers();
    }


    function getPageTitle() {
        return $("title").length?$("title").text():"";
    }


    function makeChatyChatView(widgetRecord) {
        var widgetId = widgetRecord.id;
        var widgetIndex = getWidgetIndex(widgetId);
        if (widgetIndex == null) {
            widgetIndex = -1;
        }
        var bodyMsg = widgetRecord.settings.cta_body;
        var headMsg = widgetRecord.settings.cta_head;
        var pageTitle = $("title").text();
        if(!isEmpty(pageTitle)) {
            bodyMsg = bodyMsg.replace(/{title}/g, pageTitle);
            headMsg = headMsg.replace(/{title}/g, pageTitle);
        } else {
            bodyMsg = bodyMsg.replace(/{title}/g, '');
            headMsg = headMsg.replace(/{title}/g, '');
        }
        bodyMsg = bodyMsg.replace(/{url}/g, "<a target='_blank' href='"+window.location.href+"'>"+window.location.href+"</a>");
        headMsg = headMsg.replace(/{url}/g, "<a target='_blank' href='"+window.location.href+"'>"+window.location.href+"</a>");
        var formHtml = "";
        formHtml += "<div style='display:none;' class='chaty-chat-view chaty-chat-view-"+widgetId+" chaty-form-" + widgetId + "' data-channel='chaty-chat-view' id='chaty-form-" + widgetId + "-chaty-chat-view' data-widget='" + widgetId + "' data-index='" + widgetIndex + "'>";
        formHtml += "<div class='chaty-view-body'>";
        formHtml += "<div class='chaty-view-header'>"+headMsg;
        formHtml += "<div role='button' class='chaty-close-view-list'><svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 330 330'><path d='M325.607 79.393c-5.857-5.857-15.355-5.858-21.213.001l-139.39 139.393L25.607 79.393c-5.857-5.857-15.355-5.858-21.213.001s-5.858 15.355 0 21.213l150.004 150a15 15 0 0 0 21.212-.001l149.996-150c5.859-5.857 5.859-15.355.001-21.213z'/></svg></div>";
        formHtml += "</div>";
        formHtml += "<div class='chaty-view-content'>";
        formHtml += "<div class='chaty-top-content'>";
        formHtml += bodyMsg;
        formHtml += "</div>";
        formHtml += "<div class='chaty-view-channels'>";
        formHtml += "</div>";
        formHtml += "</div>";
        formHtml += "</div>";
        formHtml += "</div>";
        $("#chaty-widget-"+widgetId).append(formHtml);
    }


    /**
     *
     * To Esc HTML Tags
     * Added On: 07/14/2022
     * Added By: Chirag Thummar
     *
     * */
    function htmlDecode(input) {
        var doc = new DOMParser().parseFromString(input, "text/html");
        return doc.documentElement.textContent;
    }


    /**
     *
     * Checking for Channel (is normal chhanel or agent channel)
     * Added On: 10/19/2021
     * Added By: Chirag Thummar
     *
     * */
    function checkForChannel(channel) {
        if (isTrue(channel.is_agent)) {
            if (channel.agent_data.length) {
                if (((!isChatyInMobile && isTrue(channel.is_agent_desktop)) || (isChatyInMobile && isTrue(channel.is_agent_mobile)))) {
                    return true;
                }
            }
        } else {
            if (((!isChatyInMobile && isTrue(channel.is_desktop)) || (isChatyInMobile && isTrue(channel.is_mobile))) && (channel.value != '' || (channel.channel == "Contact_Us" || channel.channel == "Chatway"))) {
                return true;
            }
        }
        return false;
    }

    /**
     *
     * Update widget views
     * Added On: 10/19/2021
     * Added By: Chirag Thummar
     *
     * */

    function updateWidgetViews(widgetId) {
        if ($("#chaty-widget-" + widgetId + " .chaty-channel.chaty-default-open").length) {
            // $(".chaty-outer-forms").show();
            var dataForm = $("#chaty-widget-" + widgetId + " .chaty-channel.chaty-default-open a.chaty-whatsapp-btn-form").data('form');
            if (!isEmpty(dataForm)) {
                var clickStatus = checkChatyCookieExpired(widgetId, "c-" + $("#chaty-widget-" + widgetId + " .chaty-channel.chaty-default-open").data('channel'));
                if (clickStatus) {
                    $("#" + dataForm).addClass("is-active");
                    if ($("#" + dataForm).length) {
                        var buttonHtml = $("#chaty-widget-" + widgetId + " .chaty-channel.chaty-default-open a.chaty-whatsapp-btn-form").html()+"<span class='hide-cht-svg-bg'>"+chaty_settings.lang.hide_whatsapp_form+"</span>";

                        removeChatyAnimation(widgetId);
                        $("#chaty-widget-" + widgetId   ).find(".ch-pending-msg").remove();
                        $("#chaty-widget-" + widgetId + " .chaty-widget").removeClass("chaty-open");
                        $("#chaty-widget-" + widgetId).addClass("form-open");
                        $("#" + dataForm).addClass("active");
                        if($("#chaty-widget-" + widgetId + " .chaty-widget:not(.chaty-no-close-button)").hasClass("has-bg-blur-effect")) {
                            $("body").addClass("add-bg-blur-effect");
                        }
                        setTimeout(function(){
                            $(".chaty-whatsapp-btn-form.active .chaty-whatsapp-input").focus();
                        }, 100);

                        $("#chaty-widget-" + widgetId + " .open-chaty-channel").html(buttonHtml);
                        $("#chaty-widget-" + widgetId).addClass("active");

                        $("#chaty-widget-" + widgetId).addClass("active");
                        if ($("#chaty-widget-" + widgetId).hasClass("auto-hide-chaty")) {
                            var hideAfter = parseInt($("#chaty-widget-" + widgetId).data("time"));
                            if (hideAfter > 0) {
                                hideAfter = hideAfter + chatyHideIntervalTime;
                                $("#chaty-widget-" + widgetId).addClass("hide-after-" + hideAfter);
                            }
                        }

                        if (chaty_settings.data_analytics_settings == "on") {
                            var widgetChannels = [];
                            var widgetChannel = $("#chaty-widget-" + widgetId + " .chaty-channel.chaty-default-open").data('channel');
                            var viewChannelStatus = checkChatyCookieExpired(widgetId, "v-" + widgetChannel);

                            if (viewChannelStatus && typeof widgetChannel != 'undefined') {
                                saveChatyCookieString(widgetId, "v-" + widgetChannel);
                                widgetChannels.push(widgetChannel);
                            }

                            if (!isBoatUser && widgetChannels.length) {
                                var widgetNonce = $("#chaty-widget-" + widgetId).data("nonce");
                                $.ajax({
                                    url: chaty_settings.ajax_url,
                                    data: {
                                        widgetId: widgetId,
                                        channels: widgetChannels,
                                        userId: widgetId,
                                        isMobile: isChatyInMobile,
                                        widgetNonce: widgetNonce,
                                        action: 'update_chaty_channel_views',
                                    },
                                    type: 'post',
                                    dataType: 'json',
                                    success: function (response) {

                                    },
                                    error: function (XMLHttpRequest, textStatus, errorThrown) {
                                        monitorErrorLog(XMLHttpRequest, textStatus, errorThrown);
                                    }
                                });
                            }
                        }
                        return;
                    }
                }
            }
        }

        $("#chaty-widget-" + widgetId).addClass("active");
        if ($("#chaty-widget-" + widgetId).hasClass("auto-hide-chaty")) {
            var hideAfter = parseInt($("#chaty-widget-" + widgetId).data("time"));
            if (hideAfter > 0) {
                hideAfter = hideAfter + chatyHideIntervalTime;
                $("#chaty-widget-" + widgetId).addClass("hide-after-" + hideAfter);
            }
        }

        var viewStatus = checkChatyCookieExpired(widgetId, "v-widget");
        if (viewStatus) {
            saveChatyCookieString(widgetId, 'v-widget');
            var userId = $("#chaty-widget-" + widgetId).data("user");
            var widgetChannels = [];
            var isSingle = 0;
            var isDefaultOpen = 0;
            var widgetChannel;
            var widgetKey = $("#chaty-widget-" + widgetId).data("key");
            if (typeof widgetData[widgetKey] != undefined) {
                var activeWidgets = chatyGetCookie("activechatyWidgets");
                if (activeWidgets != null) {
                    activeWidgets = activeWidgets.split(",");
                    if ($.inArray(widgetId, activeWidgets) == -1) {
                        activeWidgets.push(widgetId);
                        activeWidgets = activeWidgets.join(",");
                        chatySetCookie("activechatyWidgets", activeWidgets, 1);
                    }
                } else {
                    activeWidgets = widgetId;
                    chatySetCookie("activechatyWidgets", activeWidgets, 1);
                }
            }
            if ($("#chaty-widget-" + widgetId + " .chaty-widget").hasClass("has-single")) {
                isSingle = 1;
                widgetChannel = $("#chaty-widget-" + widgetId + " .chaty-channel").data("channel");
                var viewChannelStatus = checkChatyCookieExpired(widgetId, "v-" + widgetChannel);
                if (viewChannelStatus && typeof widgetChannel != 'undefined') {
                    saveChatyCookieString(widgetId, "v-" + widgetChannel);
                    widgetChannels.push(widgetChannel);
                }
            } else if ($("#chaty-widget-" + widgetId + " .chaty-widget").hasClass("chaty-open")) {
                isDefaultOpen = 1;
                if($("#chaty-widget-" + widgetId).hasClass("chaty-has-chat-view")) {
                    $(".chaty-chat-view.chaty-chat-view-" + widgetId + " .chaty-view-channels").find(".chaty-channel").each(function () {
                        chatyChannel = $(this).data("channel");
                        clickStatus = checkChatyCookieExpired(widgetId, "v-" + chatyChannel);
                        if (clickStatus && typeof chatyChannel != 'undefined') {
                            saveChatyCookieString(widgetId, "v-" + chatyChannel);
                            widgetChannels.push(chatyChannel);
                        }
                    });
                } else {
                    $("#chaty-widget-" + widgetId + " .chaty-channel-list .chaty-channel").each(function () {
                        widgetChannel = $(this).data("channel");
                        var viewChannelStatus = checkChatyCookieExpired(widgetId, "v-" + widgetChannel);
                        if (viewChannelStatus && typeof widgetChannel != 'undefined') {
                            saveChatyCookieString(widgetId, "v-" + widgetChannel);
                            widgetChannels.push(widgetChannel);
                        }
                    });
                }
            }
            if (viewStatus && !isBoatUser) {
                var widgetNonce = $("#chaty-widget-" + widgetId).data("nonce");
                if (!isBoatUser) {
                    $.ajax({
                        url: chaty_settings.ajax_url,
                        data: {
                            widgetId: widgetId,
                            channels: widgetChannels,
                            userId: widgetId,
                            isMobile: isChatyInMobile,
                            isOpen: isDefaultOpen,
                            isSingle: isSingle,
                            widgetNonce: widgetNonce,
                            action: 'update_chaty_widget_views',
                        },
                        type: 'post',
                        dataType: 'json',
                        success: function (response) {

                        },
                        error: function (XMLHttpRequest, textStatus, errorThrown) {
                            monitorErrorLog(XMLHttpRequest, textStatus, errorThrown);
                        }
                    });
                }
            }
        }
    }

    /**
     *
     * check for visitor status and update it if required
     * Added On: 10/04/2021
     * Added By: Chirag Thummar
     *
     * */
    function updateVisitorCount(widgetId) {
        var userId = $("#chaty-widget-" + widgetId).data("user");
        var isOldUser = chatySaasCheckCookie("triggeredFor" + userId);
        if (!isOldUser) {
            chatySetCookie("triggeredFor" + userId, widgetId, 2);
            /*$.ajax({
                url: VISITOR_COUNT_API,
                data: {
                    widgetId: widgetId,
                    channels: [],
                    userId: userId
                },
                type: 'post',
                success: function (response) {

                },
                error: function (XMLHttpRequest, textStatus, errorThrown) {
                    monitorErrorLog(XMLHttpRequest, textStatus, errorThrown);
                }
            });*/
        }
    }

    /**
     *
     * Check for Triggers if exists
     * Added On: 10/04/2021
     * Added By: Chirag Thummar
     *
     * */

    function getWidgetSize(widgetSize, customSize) {
        return widgetSize;
    }

    /**
     *
     * To get widget CTA icon by it's key
     * Added On: 09/29/2021
     * Added By: Chirag Thummar
     *
     * */
    function getSvgIcon(iconName, widgetColor, iconColor, widgetId) {
        switch (iconName) {
            case"chat-smile":
                return '<svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="-496.8 507.1 54 54" style="enable-background-color:new -496.8 507.1 54 54;" xml:space="preserve"><style type="text/css">.chaty-sts1-'+widgetId+'{fill:'+ iconColor +';} .chaty-sts2{fill:none;stroke:#808080;stroke-width:1.5;stroke-linecap:round;stroke-linejoin:round;}</style><g><circle cx="-469.8" cy="534.1" r="27" fill="' + widgetColor + '"/></g><path class="chaty-sts1-'+ widgetId +'" d="M-459.5,523.5H-482c-2.1,0-3.7,1.7-3.7,3.7v13.1c0,2.1,1.7,3.7,3.7,3.7h19.3l5.4,5.4c0.2,0.2,0.4,0.2,0.7,0.2c0.2,0,0.2,0,0.4,0c0.4-0.2,0.6-0.6,0.6-0.9v-21.5C-455.8,525.2-457.5,523.5-459.5,523.5z"/><path class="chaty-sts2" d="M-476.5,537.3c2.5,1.1,8.5,2.1,13-2.7"/><path class="chaty-sts2" d="M-460.8,534.5c-0.1-1.2-0.8-3.4-3.3-2.8"/></svg>';
            case"chat-bubble":
                return '<svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="-496.9 507.1 54 54" style="enable-background-color:new -496.9 507.1 54 54;" xml:space="preserve"><style type="text/css">.chaty-sts2-'+widgetId+'{fill:'+ iconColor +';}</style><g><circle  cx="-469.9" cy="534.1" r="27" fill="' + widgetColor + '"/></g><path class="chaty-sts2-'+ widgetId +'" d="M-472.6,522.1h5.3c3,0,6,1.2,8.1,3.4c2.1,2.1,3.4,5.1,3.4,8.1c0,6-4.6,11-10.6,11.5v4.4c0,0.4-0.2,0.7-0.5,0.9   c-0.2,0-0.2,0-0.4,0c-0.2,0-0.5-0.2-0.7-0.4l-4.6-5c-3,0-6-1.2-8.1-3.4s-3.4-5.1-3.4-8.1C-484.1,527.2-478.9,522.1-472.6,522.1z   M-462.9,535.3c1.1,0,1.8-0.7,1.8-1.8c0-1.1-0.7-1.8-1.8-1.8c-1.1,0-1.8,0.7-1.8,1.8C-464.6,534.6-463.9,535.3-462.9,535.3z   M-469.9,535.3c1.1,0,1.8-0.7,1.8-1.8c0-1.1-0.7-1.8-1.8-1.8c-1.1,0-1.8,0.7-1.8,1.8C-471.7,534.6-471,535.3-469.9,535.3z   M-477,535.3c1.1,0,1.8-0.7,1.8-1.8c0-1.1-0.7-1.8-1.8-1.8c-1.1,0-1.8,0.7-1.8,1.8C-478.8,534.6-478.1,535.3-477,535.3z"/></svg>';
            case"chat-db":
                return '<svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="-496 507.1 54 54" style="enable-background-color:new -496 507.1 54 54;" xml:space="preserve"><style type="text/css">.chaty-sts3-'+widgetId+'{fill:'+ iconColor +';}</style><g><circle  cx="-469" cy="534.1" r="27" fill="' + widgetColor + '"/></g><path class="chaty-sts3-'+ widgetId +'" d="M-464.6,527.7h-15.6c-1.9,0-3.5,1.6-3.5,3.5v10.4c0,1.9,1.6,3.5,3.5,3.5h12.6l5,5c0.2,0.2,0.3,0.2,0.7,0.2c0.2,0,0.2,0,0.3,0c0.3-0.2,0.5-0.5,0.5-0.9v-18.2C-461.1,529.3-462.7,527.7-464.6,527.7z"/><path class="chaty-sts3-'+ widgetId +'" d="M-459.4,522.5H-475c-1.9,0-3.5,1.6-3.5,3.5h13.9c2.9,0,5.2,2.3,5.2,5.2v11.6l1.9,1.9c0.2,0.2,0.3,0.2,0.7,0.2c0.2,0,0.2,0,0.3,0c0.3-0.2,0.5-0.5,0.5-0.9v-18C-455.9,524.1-457.5,522.5-459.4,522.5z"/></svg>';
            default:
                return '<svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="-496 507.7 54 54" style="enable-background-color:new -496 507.7 54 54;" xml:space="preserve"><style type="text/css">.chaty-sts4-'+widgetId+'{fill: '+ iconColor +';}.chaty-st0{fill: #808080;}</style><g><circle cx="-469" cy="534.7" r="27" fill="' + widgetColor + '"/></g><path class="chaty-sts4-'+  widgetId +'" d="M-459.9,523.7h-20.3c-1.9,0-3.4,1.5-3.4,3.4v15.3c0,1.9,1.5,3.4,3.4,3.4h11.4l5.9,4.9c0.2,0.2,0.3,0.2,0.5,0.2 h0.3c0.3-0.2,0.5-0.5,0.5-0.8v-4.2h1.7c1.9,0,3.4-1.5,3.4-3.4v-15.3C-456.5,525.2-458,523.7-459.9,523.7z"/><path class="chaty-st0" d="M-477.7,530.5h11.9c0.5,0,0.8,0.4,0.8,0.8l0,0c0,0.5-0.4,0.8-0.8,0.8h-11.9c-0.5,0-0.8-0.4-0.8-0.8l0,0C-478.6,530.8-478.2,530.5-477.7,530.5z"/><path class="chaty-st0" d="M-477.7,533.5h7.9c0.5,0,0.8,0.4,0.8,0.8l0,0c0,0.5-0.4,0.8-0.8,0.8h-7.9c-0.5,0-0.8-0.4-0.8-0.8l0,0C-478.6,533.9-478.2,533.5-477.7,533.5z"/></svg>'
        }
    }

    /**
     *
     * To get channel settings
     * Added On: 10/01/2021
     * Added By: Chirag Thummar
     *
     * */

    function getChannelSetting(channel, widgetId, toolTipPosition) {
        var extraClass = "";
        if (isTrue(channel.is_agent)) {
            if (channel.agent_data.length) {
                var activeAgents = 0;
                var activeAgent = [];
                $.each(channel.agent_data, function (key, agent) {
                    if (agent.value != "") {
                        activeAgents++;
                        activeAgent = agent;
                    }
                });
                if (activeAgents > 0) {
                    var channelIcon, channelLink;
                    var widgetIndex = getWidgetIndex(widgetId);
                    if (widgetIndex == null) {
                        widgetIndex = -1;
                    }
                    createAgentList(channel, widgetId);
                    channelIcon = getChannelIcon(channel, widgetId);
                    channelLink = getChannelURL(channel, channelIcon, toolTipPosition, widgetId);

                    if (channel.channel != "Instagram" || (channel.icon_color != "#ffffff" && channel.icon_color != "#fff")) {
                        customExtraCSS += ".chaty-agent-" + widgetId + "-" + channel.channel + " .color-element {fill: " + channel.icon_color + "; color: " + channel.icon_color + ";}";
                    }
                    customExtraCSS += ".chaty-agent-" + widgetId + "-" + channel.channel + " .chaty-custom-icon { background-color: " + channel.icon_color + ";}";
                    customExtraCSS += ".chaty-agent-" + widgetId + "-" + channel.channel + " .chaty-svg-img { background-color: " + channel.icon_color + ";}";
                    return "<div data-form='chaty-form-" + widgetId + "-" + channel.channel_type + "' class='chaty-channel chaty-agent-button chaty-agent-" + widgetId + "-" + channel.channel + " " + channel.channel + "-channel" + extraClass + "' id='" + channel.channel + "-" + widgetId + "-channel' data-id='" + channel.channel_type + "-" + widgetId + "' data-widget='" + widgetId + "' data-channel='" + channel.channel + "'>" + channelLink + "</div>";

                }
            }
        } else {
            if (isValueEmpty(channel.channel_type)) {
                channel.channel_type = channel.channel;
            }
            var channelIcon = getChannelIcon(channel, widgetId);
            var channelLink = getChannelURL(channel, channelIcon, toolTipPosition, widgetId);
            if (channel.channel_type == "Contact_Us") {
                extraClass += " has-chaty-box chaty-contact-form";
            } else if (channel.channel_type == "Whatsapp") {
                if (isTrue(channel.is_default_open)) {
                    var clickStatus = checkChatyCookieExpired(widgetId, "c-" + channel.channel_type);
                    if (clickStatus) {
                        extraClass += " chaty-default-open"
                    }
                }
            }
            return "<div class='chaty-channel " + channel.channel + "-channel-link" + extraClass + "' id='" + channel.channel + "-" + widgetId + "-channel' data-id='" + channel.channel_type + "-" + widgetId + "' data-widget='" + widgetId + "' data-channel='" + channel.channel + "'>" + channelLink + "</div>";
        }
    }


    function createAgentList(channel, widgetId) {
        var formHtml = "";
        var widgetIndex = getWidgetIndex(widgetId);
        if (widgetIndex == null) {
            widgetIndex = -1;
        }
        formHtml += "<div style='display:none;' class='chaty-outer-forms chaty-agent-data chaty-agent-data-" + widgetId + " chaty-form-" + widgetId + "' data-channel='" + channel.channel_type + "' id='chaty-form-" + widgetId + "-" + channel.channel_type + "' data-widget='" + widgetId + "' data-index='" + widgetIndex + "'>";
        formHtml += "<div class='chaty-form'>";
        formHtml += "<div class='chaty-form-body'>";
        formHtml += "<div role='button' class='chaty-close-agent-list'><svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 330 330'><path d='M325.607 79.393c-5.857-5.857-15.355-5.858-21.213.001l-139.39 139.393L25.607 79.393c-5.857-5.857-15.355-5.858-21.213.001s-5.858 15.355 0 21.213l150.004 150a15 15 0 0 0 21.212-.001l149.996-150c5.859-5.857 5.859-15.355.001-21.213z'/></svg></div>";
        formHtml += "<div class='chaty-agent-header agent-info-" + widgetId + "-" + channel.channel + "'>";
        if (!isEmpty(channel.header_text)) {
            formHtml += "<div class='agent-main-header'>" + channel.header_text + "</div>";
        }
        if (!isEmpty(channel.header_sub_text)) {
            formHtml += "<div class='agent-sub-header'>" + channel.header_sub_text + "</div>";
        }
        formHtml += "</div>";
        formHtml += "<div class='chaty-agent-body agents-body-" + widgetId + " agent-body-" + widgetId + "-" + channel.channel + "'>";
        $.each(channel.agent_data, function (key, agent) {
            if (agent.value != "") {
                var agentIcon = agent.svg_icon;
                if (!isEmpty(agent.agent_image)) {
                    agentIcon = "<img class='chaty-agent-img' src='" + agent.agent_image + "' alt='" + agent.agent_title + "' />"
                }
                var agentLink = getAgentURL(agent, channel, widgetId, key, agentIcon, agent.agent_title);
                formHtml += "<div class='chaty-agent agent-info-" + widgetId + "-" + channel.channel + " agent-info-" + key + "'>" + agentLink + "</div>";
            }
            customExtraCSS += ".agent-info-" + widgetId + "-" + channel.channel + ".agent-info-" + key + " .chaty-agent-icon img { background-color: " + agent.agent_bg_color + "; } ";
            if (channel.channel != "Instagram" || (agent.agent_bg_color != "#ffffff" && agent.agent_bg_color != "#fff")) {
                customExtraCSS += ".agent-info-" + widgetId + "-" + channel.channel + ".agent-info-" + key + " .chaty-agent-icon .color-element { fill: " + agent.agent_bg_color + "; } ";
            }
            customExtraCSS += ".agent-info-" + widgetId + "-" + channel.channel + ".agent-info-" + key + " .chaty-custom-icon { background-color: " + agent.agent_bg_color + "; } ";
        });
        formHtml += "</div>";
        formHtml += "</div>";
        formHtml += "</div>";
        formHtml += "</div>";
        customExtraCSS += ".chaty-agent-header.agent-info-" + widgetId + "-" + channel.channel + " { background-color: " + channel.header_bg_color + "; color: " + channel.header_text_color + " } ";
        customExtraCSS += ".agent-info-" + widgetId + "-" + channel.channel + " .chaty-close-agent-list svg { fill: " + channel.header_text_color + " } ";
        $("body").append(formHtml);
    }

    function getAgentChannelURL(channel, agent, widgetId, channelIcon, toolTipPosition) {
        var agentURL = agent.value;
        var agentTarget = "_blank";
        if (channel.channel_type == "Whatsapp") {
            var whatsAppNumber = getWhatsAppNumber(agent.value);
            if (isChatyInMobile) {
                agentTarget = "";
                agentURL = "https://wa.me/" + whatsAppNumber;
            } else {
                agentTarget = "_blank";
                agentURL = "https://web.whatsapp.com/send?phone=" + whatsAppNumber;
            }
        } else if (channel.channel_type == "WeChat") {
            agentTarget = "";
            agentURL = "javascript:;";
        } else if (channel.channel_type == "Email") {
            agentTarget = "";
            agentURL = "mailto:" + agent.value;
        } else if (channel.channel_type == "Facebook_Messenger") {
            if (isChatyInMobile) {
                agentTarget = "";
            } else {
                agentTarget = "_blank";
            }
        } else if (channel.channel_type == "SMS") {
            agentTarget = "";
            agentURL = "sms:" + agent.value;
        } else if (channel.channel_type == "Telegram") {
            agentURL = trimChar(agent.value, "@");
            agentURL = "https://telegram.me/" + agentURL;
            agentTarget = "_blank";
        } else if (channel.channel_type == "Twitter") {
            agentURL = "https://twitter.com/" + $.trim(agent.value);
        } else if (channel.channel_type == "Phone") {
            agentTarget = "";
            agentURL = "tel:" + $.trim(agent.value);
        } else if (channel.channel_type == "Skype") {
            agentTarget = "";
            agentURL = "skype:" + $.trim(agent.value) + "?chat";
        } else if (channel.channel_type == "Snapchat") {
            agentURL = "https://www.snapchat.com/add/" + $.trim(agent.value);
        } else if (channel.channel_type == "Vkontakte") {
            agentURL = "https://vk.me/" + $.trim(agent.value);
        } else if (channel.channel_type == "Linkedin") {
            if (agent.link_type == "personal") {
                agentURL = "https://www.linkedin.com/in/" + $.trim(agent.value);
            } else {
                agentURL = "https://www.linkedin.com/company/" + $.trim(agent.value);
            }
        } else if (channel.channel_type == "Viber") {
            if(agent.viber_url != "") {
                agentURL = "viber://pa?chatURI=" + agent.value;
            } else {
                agentURL = trimChar(agent.value, "+");
                if (!isNaN(agentURL)) {
                    agentURL = agentURL.replace("+", "");
                    if (navigator.userAgent.match(/(iPod|iPhone|iPad)/)) {
                        agentURL = "+" + agentURL;
                    }
                    agentURL = "viber://chat?number=" + agentURL;
                }
            }
            agentTarget = "";
        } else if (channel.channel_type == "TikTok") {
            agentURL = trimChar($.trim(agent.value), "@");
            agentURL = "https://www.tiktok.com/@" + agentURL;
            agentTarget = "";
        }
        return "<a href='" + agentURL + "' target='" + agentTarget + "' class='chaty-tooltip pos-" + toolTipPosition + "' data-form='chaty-form-" + widgetId + "-" + channel.channel_type + "' data-hover='" + channel.hover_text + "'>" + channelIcon + "</a>";
    }

    function getAgentURL(agent, channel, widgetId, key, agentIcon, agentTitle) {
        var agentURL = agent.value;
        var agentTarget = "_blank";
        if (channel.channel_type == "Whatsapp") {
            var whatsAppNumber = getWhatsAppNumber(agent.value);

            var preSetMessage = "";
            if (!isEmpty(agent.pre_set_message)) {
                preSetMessage = decodeURI(agent.pre_set_message);
                var pageTitle = $("title").text();
                if (!isEmpty(pageTitle)) {
                    preSetMessage = preSetMessage.replace(/{title}/g, pageTitle);
                } else {
                    preSetMessage = preSetMessage.replace(/{title}/g, '');
                }
                preSetMessage = preSetMessage.replace(/{url}/g, window.location);
                preSetMessage = encodeURIComponent(preSetMessage);
            }
            if (isChatyInMobile) {
                agentTarget = "";
                agentURL = "https://wa.me/" + whatsAppNumber + "?text=" + preSetMessage;
            } else {
                agentTarget = "_blank";
                if (isTrue(agent.use_whatsapp_web)) {
                    agentURL = "https://web.whatsapp.com/send?phone=" + whatsAppNumber + "&text=" + preSetMessage;
                } else {
                    agentURL = "https://wa.me/" + whatsAppNumber + "?text=" + preSetMessage;
                }
            }
        } else if (channel.channel_type == "WeChat") {
            agentTarget = "";
            agentURL = "javascript:;";
        } else if (channel.channel_type == "Email") {
            agentTarget = "";
            agentURL = "mailto:" + agent.value;
        } else if (channel.channel_type == "Facebook_Messenger") {
            if (isChatyInMobile) {
                agentTarget = "";
            } else {
                agentTarget = "_blank";
            }
        } else if (channel.channel_type == "SMS") {
            agentTarget = "";
            agentURL = "sms:" + agent.value;
        } else if (channel.channel_type == "Telegram") {
            agentURL = trimChar(agent.value, "@");
            agentURL = "https://telegram.me/" + agentURL;
            agentTarget = "_blank";
        } else if (channel.channel_type == "Twitter") {
            agentURL = "https://twitter.com/" + $.trim(agent.value);
        } else if (channel.channel_type == "Instagram") {
            agentURL = "https://www.instagram.com/" + $.trim(agent.value);
        } else if (channel.channel_type == "Phone") {
            agentTarget = "";
            agentURL = "tel:" + $.trim(agent.value);
        } else if (channel.channel_type == "Skype") {
            agentTarget = "";
            agentURL = "skype:" + $.trim(agent.value) + "?chat";
        } else if (channel.channel_type == "Snapchat") {
            agentURL = "https://www.snapchat.com/add/" + $.trim(agent.value);
        } else if (channel.channel_type == "Vkontakte") {
            agentURL = "https://vk.me/" + $.trim(agent.value);
        } else if (channel.channel_type == "Linkedin") {
            if (agent.link_type == "personal") {
                agentURL = "https://www.linkedin.com/in/" + $.trim(agent.value);
            } else {
                agentURL = "https://www.linkedin.com/company/" + $.trim(agent.value);
            }
        } else if (channel.channel_type == "Viber") {
            if(agent.viber_url != "") {
                agentURL = "viber://pa?chatURI=" + agent.value;
            } else {
                agentURL = trimChar(agent.value, "+");
                if (!isNaN(agentURL)) {
                    agentURL = agentURL.replace("+", "");
                    if (navigator.userAgent.match(/(iPod|iPhone|iPad)/)) {
                        agentURL = "+" + agentURL;
                    }
                    agentURL = "viber://chat?number=" + agentURL;
                }
            }
            agentTarget = "";
        } else if (channel.channel_type == "TikTok") {
            agentURL = trimChar($.trim(agent.value), "@");
            agentURL = "https://www.tiktok.com/@" + agentURL;
            agentTarget = "";
        }
        return "<a href='" + agentURL + "' target='" + agentTarget + "'><span class='chaty-agent-icon'>" + agentIcon + "</span><span class='chaty-agent-title'>" + agentTitle + "</span></a>";
    }

    function getWhatsAppNumber(phoneNumber) {
        phoneNumber = trimChar(phoneNumber, "+");
        phoneNumber = phoneNumber.replace(/ /g, "");
        phoneNumber = phoneNumber.replace(/-/g, "");
        phoneNumber = phoneNumber.replace(/_/g, "");
        return phoneNumber;
    }


    function trimChar(string, charToRemove) {
        string = $.trim(string);
        while (string.charAt(0) == charToRemove) {
            string = string.substring(1);
        }

        while (string.charAt(string.length - 1) == charToRemove) {
            string = string.substring(0, string.length - 1);
        }

        return string;
    }


    /**
     *
     * To get channel URL
     * Added On: 10/01/2021
     * Added By: Chirag Thummar
     *
     * */
    function getChannelURL(channel, channelIcon, toolTipPosition, widgetId) {
        var extraClass = "";
        if (isTrue(channel.is_agent)) {
            channel.url = "javascript:;";
            channel.target = "";
        } else {
            if (channel.channel_type == "Whatsapp") {
                if (isTrue(channel.has_welcome_message)) {
                    channel.url = "javascript:;";
                    channel.target = "";
                    extraClass += " has-chaty-box chaty-whatsapp-btn-form";
                    startMakingWhatsAppPopup(channel, widgetId);
                } else {
                    var preSetMessage = "";
                    if (!isEmpty(channel.pre_set_message)) {
                        preSetMessage = decodeURI(channel.pre_set_message);
                        var pageTitle = $("title").text();
                        if (!isEmpty(pageTitle)) {
                            preSetMessage = preSetMessage.replace(/{title}/g, pageTitle);
                        } else {
                            preSetMessage = preSetMessage.replace(/{title}/g, '');
                        }
                        preSetMessage = preSetMessage.replace(/{url}/g, window.location);
                        preSetMessage = encodeURIComponent(preSetMessage);
                    }
                    if (isChatyInMobile) {
                        channel.target = "";
                        channel.url = "https://wa.me/" + channel.value + "?text=" + preSetMessage;
                    } else {
                        channel.target = "_blank";
                        if (isTrue(channel.is_use_web_version)) {
                            channel.url = "https://web.whatsapp.com/send?phone=" + channel.value + "&text=" + preSetMessage;
                        } else {
                            channel.url = "https://wa.me/" + channel.value + "?text=" + preSetMessage;
                        }
                    }
                }
            } else if (channel.channel_type == "WeChat") {
                if (!isEmpty(channel.qr_code_image_url)) {
                    startMakingWeChatChannel(channel, widgetId);
                    channel.url = "javascript:;";
                    channel.target = "";
                    extraClass += " has-chaty-box chaty-qr-code-form";
                }
            } else if (channel.channel_type == "Contact_Us") {
                startMakingContactForm(channel, widgetId);
                channel.url = "javascript:;";
                channel.target = "";
                extraClass += " has-chaty-box chaty-contact-us-form";
            } else if (channel.channel_type == "Email") {
                if (!isEmpty(channel.mail_subject)) {
                    var mailSubject = decodeURI(channel.mail_subject);
                    var pageTitle = $("title").text();
                    if (!isEmpty(pageTitle)) {
                        mailSubject = mailSubject.replace(/{title}/g, pageTitle);
                    } else {
                        mailSubject = mailSubject.replace(/{title}/g, '');
                    }
                    mailSubject = mailSubject.replace(/{url}/g, window.location);
                    mailSubject = encodeURIComponent(mailSubject);
                    channel.url += "?subject=" + mailSubject;
                }
            } else if (channel.channel_type == "Viber") {
                if(channel.viber_url != "") {
                    channel.url = "viber://pa?chatURI=" + channel.value;
                } else {
                    channel.value = trimChar(channel.value, "+");
                    if (isChatyInMobile && !isNaN(channel.value)) {
                        // channel.value = channel.value.replace("+", "");
                        if (navigator.userAgent.match(/(iPod|iPhone|iPad)/)) {
                            channel.value = "+" + channel.value;
                        }
                    }
                    channel.url = "viber://chat?number=" + channel.value;
                }
                channel.target = "";
            } else if (channel.channel_type == "Vkontakte") {
                channel.url = "https://vk.me/" + $.trim(channel.value);
            } else if (channel.channel_type == "Waze") {
                channel.url = decodeURI($.trim(channel.value));
            }
        }
        if(channel.channel == "Link" || channel.channel == "Custom_Link" || channel.channel == "Custom_Link_3" || channel.channel == "Custom_Link_4" || channel.channel == "Custom_Link_5") {
            if(!isEmpty(channel.hover_text)) {
                ariaLabel = channel.hover_text;
            } else {
                ariaLabel = channel.channell
            }
        }else {
            ariaLabel = channel.channel;
        }

        var onClickFn = "";
        if (!isEmpty(channel.click_event)) {
            onClickFn = 'onclick="' + channel.click_event + '"';
            channel.target = "";
            channel.url = "javascript:;";
        }
        return "<a href='" + channel.url + "' " + onClickFn + " target='" + channel.target + "' rel='nofollow noopener' aria-label='" + ariaLabel + "' class='chaty-tooltip " + channel.channel_type + "-channel chaty-"+(channel.channel_type).toLowerCase()+"-channel pos-" + toolTipPosition + extraClass + "' data-form='chaty-form-" + widgetId + "-" + channel.channel_type + "' data-hover='" + channel.hover_text + "'>" + channelIcon + "</a>";
    }

    function startMakingContactForm(channel, widgetId) {
        var formHtml = "";
        var widgetIndex = getWidgetIndex(widgetId);
        if (widgetIndex == null) {
            widgetIndex = -1;
        }
        formHtml += "<div style='display:none;' class='chaty-outer-forms chaty-contact-form-box chaty-form-" + widgetId + "' data-channel='" + channel.channel_type + "' id='chaty-form-" + widgetId + "-" + channel.channel_type + "' data-widget='" + widgetId + "' data-index='" + widgetIndex + "'>";
        formHtml += "<div class='chaty-form'>";
        formHtml += "<div class='chaty-form-body'>";
        formHtml += "<div role='button' class='close-chaty-form'><div class='chaty-close-button'></div></div>";
        formHtml += "<form class='chaty-ajax-contact-form' id='chaty-ajax-contact-form-" + widgetIndex + "' method='post' data-channel='" + channel.channel_type + "' data-widget='" + widgetId + "' data-token='" + channel.widget_token + "' data-index='" + channel.widget_index + "' enctype='multipart/form-data'>";
        formHtml += "<div class='chaty-contact-form-body'>";
        formHtml += "<div class='chaty-contact-form-title'><div class='form-title'>" + channel.contact_form_settings.contact_form_title + "</div><div class='chaty-close-button'><svg width='15' height='9' viewBox='0 0 15 9' fill='none' xmlns='http://www.w3.org/2000/svg'> <path d='M1 1L7.31429 8L14 1' stroke='white' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'></path></svg></div></div>";
        formHtml += "<div class='chaty-contact-inputs'>";

        if(channel.contact_form_settings.contact_form_field_order == '') {
            $.each(channel.contact_fields, function (key, contactField) {
                formHtml += "<div class='chaty-contact-input'>";
                var isRequired = isTrue(contactField.is_required) ? "is-required" : "";
                var required_indicator = "";
                if(isTrue(contactField.is_required)) {
                    required_indicator = "<span class='required_indicate'>*</span>"
                }
                if (contactField.type == "textarea") {
                    if (!isEmpty(contactField.title)) {
                        formHtml += "<label class='chaty-form-label' for='" + contactField.field + "-" + widgetId + "'>" + contactField.title +" "+required_indicator+"</label>";
                    } else {
                        formHtml += "<label class='sr-only' for='" + contactField.field + "-" + widgetId + "'>" + contactField.field + "</label>";
                    }
                    formHtml += "<textarea type='" + contactField.type + "' class='chaty-textarea-field " + isRequired + " field-" + contactField.field + "' placeholder='" + contactField.placeholder + "' name='" + contactField.field + "' id='" + contactField.field + "-" + widgetId + "' ></textarea>"
                } else {
                    if (!isEmpty(contactField.title)) {
                        formHtml += "<label class='chaty-form-label' for='" + contactField.field + "-" + widgetId + "'>" + contactField.title + " "+required_indicator+"</label>";
                    } else {
                        formHtml += "<label class='sr-only' for='" + contactField.field + "-" + widgetId + "'>" + contactField.field + "</label>";
                    }
                    console.log(contactField.field);
                    formHtml += "<input type='" + contactField.type + "' class='chaty-input-field " + isRequired + " field-" + contactField.field + "' placeholder='" + contactField.placeholder + "' name='" + contactField.field + "' id='" + contactField.field + "-" + widgetId + "' />"
                    if (contactField.field == "email") {
                        formHtml += '<p id="email_suggestion' + widgetId + '" class="email_suggestion"></p>';
                    }
                }
                formHtml += "</div>";
            });
        } else {
            $.each(channel.contact_form_settings.contact_form_field_order, function (orderKey, orderValue) {
                $.each(channel.contact_fields, function (key, contactField) {
                    if (contactField.title == orderValue) {
                        formHtml += "<div class='chaty-contact-input'>";
                        var isRequired = isTrue(contactField.is_required) ? "is-required" : "";
                        var required_indicator = "";
                        if(isTrue(contactField.is_required)) {
                            required_indicator = "<span class='required_indicate'>*</span>"
                        }
                        if (contactField.type == "textarea") {
                            if (!isEmpty(contactField.title)) {
                                formHtml += "<label class='chaty-form-label' for='" + contactField.field + "-" + widgetId + "'>" + contactField.title +" "+required_indicator+"</label>";
                            } else {
                                formHtml += "<label class='sr-only' for='" + contactField.field + "-" + widgetId + "'>" + contactField.field + "</label>";
                            }
                            formHtml += "<textarea type='" + contactField.type + "' class='chaty-textarea-field " + isRequired + " field-" + contactField.field + "' placeholder='" + contactField.placeholder + "' name='" + contactField.field + "' id='" + contactField.field + "-" + widgetId + "' ></textarea>"
                        } else {
                            if (!isEmpty(contactField.title)) {
                                formHtml += "<label class='chaty-form-label' for='" + contactField.field + "-" + widgetId + "'>" + contactField.title + " "+required_indicator+"</label>";
                            } else {
                                formHtml += "<label class='sr-only' for='" + contactField.field + "-" + widgetId + "'>" + contactField.field + "</label>";
                            }
                            formHtml += "<input type='" + contactField.type + "' class='chaty-input-field " + isRequired + " field-" + contactField.field + "' placeholder='" + contactField.placeholder + "' name='" + contactField.field + "' id='" + contactField.field + "-" + widgetId + "' />"
                            if (contactField.field == "email") {
                                formHtml += '<p id="email_suggestion' + widgetId + '" class="email_suggestion"></p>';
                            }
                        }
                        formHtml += "</div>";
                    }
                });

                $.each(channel.contact_custom_fields, function (key, value) {
                    if (value.unique_id == orderValue) {
                        if (value.is_active == 'yes') {
                            var isRequired = isTrue(value.is_required) ? "is-required" : "";
                            var required_indicator = "";
                            if(isTrue(value.is_required)) {
                                required_indicator = "<span class='required_indicate'>*</span>"
                            }
                            formHtml += "<div class='chaty-contact-input'>";
                            formHtml += "<label class='chaty-form-label' for='" + value.field_dropdown + "-" + key + "'>" + value.field_label + " "+required_indicator+"</label>";
                            formHtml += "<input type='hidden' name='custom_fields[" + key + "][label_" + value.field_dropdown + "]' value='" + value.field_label + "'>";
                            formHtml += "<input type='hidden' name='custom_fields[" + key + "][slug]' value='" + value.field_dropdown + "'>";
                            if (value.field_dropdown == 'text' || value.field_dropdown == 'url' || value.field_dropdown == 'date') {
                                formHtml += "<input type='" + value.field_dropdown + "' class='chaty-input-field " + isRequired + " field-" + value.field_dropdown + "' placeholder='" + value.placeholder + "' name='custom_fields[" + key + "][" + value.field_dropdown + "]' id='" + value.field_dropdown + "-" + key + "' />"
                            } else if (value.field_dropdown == "file") {
                                formHtml += "<input type='file' class='chaty-input-field " + isRequired + " field-" + value.field_dropdown + "' name='custom_fields[" + key + "][" + value.field_dropdown + "]' id='" + value.field_dropdown + "-" + key + "' accept='.jpg,.jpeg,.png,.gif,.pdf,.doc,.docx,.ppt,.pptx,.pps,.ppsx,.odt,.xls,.xlsx,.mp3,.mp4,.wav,.mpg,.avi,.mov,.wmv,.3gp,.ogv' multiple/>"
                            } else if (value.field_dropdown == "textarea") {
                                formHtml += "<textarea class='chaty-textarea-field " + isRequired + " field-" + value.field_dropdown + "' placeholder='" + value.placeholder + "' name='custom_fields[" + key + "][" + value.field_dropdown + "]' id='" + value.field_dropdown + "-" + key + "' ></textarea>"
                            } else if (value.field_dropdown == "dropdown") {
                                formHtml += "<select name='custom_fields[" + key + "][" + value.field_dropdown + "]' class='chaty-input-field " + isRequired + "'>";
                                if (value.dropdown_placeholder) {
                                    formHtml += "<option value=''>" + value.dropdown_placeholder + "</option>";
                                }
                                $.each(value.dropdown_option, function (key, val) {
                                    if (val) {
                                        formHtml += "<option value='"+val+"'>" + val + "</option>";
                                    }
                                });
                                formHtml += "</select>";
                            } else if (value.field_dropdown == "textblock") {
                                var unique_id = Math.floor(Math.random() * Math.random()) + Date.now();
                                formHtml += "<textarea rows='5' name='custom_fields[" + key + "][" + value.field_dropdown + "]' class='chaty-text-block chaty-textarea-field "+isRequired+"' id='text_editor_"+unique_id+"'></textarea>";
                            } else if (value.field_dropdown == 'number') {
                                formHtml += "<input type='tel' class='chaty-input-field " + isRequired + " field-" + value.field_dropdown + "' placeholder='" + value.placeholder + "' name='custom_fields[" + key + "][" + value.field_dropdown + "]' id='" + value.field_dropdown + "-" + key + "' />"
                            }
                            formHtml += "</div>";
                        }
                    }
                });
            });
        }

        if(isTrue(channel.enable_recaptcha)) {
            if (!isEmpty(channel.v2_site_key)) {
                formHtml += "<div class='chaty-contact-input'>";
                formHtml += "<div class='front-google-captcha' id='front_recaptcha_" + widgetIndex + "'></div>";
                formHtml += "</div>";
            }
            if (!isEmpty(channel.v3_site_key)) {
                formHtml += "<div class='chaty-contact-input'>";
                formHtml += "<div class='front-google-captcha' id='front_recaptcha_" + widgetIndex + "'></div>";
                formHtml += "</div>";
            }
        }
        formHtml += "</div>"; // chaty-contact-inputs
        formHtml += "<div class='chaty-contact-form-button'><button type='submit' id='chaty-submit-button-" + widgetId + "' class='chaty-submit-button'>" + channel.contact_form_settings.button_text + "<span class='chaty-loader'><span class='dashicons dashicons-update'></span></span></button></div>";
        formHtml += "</div>"; // chaty-contact-form-body
        formHtml += "<input type='hidden' name='nonce' value='"+channel.widget_token+"'>";
        formHtml += "<input type='hidden' name='action' value='chaty_front_form_save_data'>";
        formHtml += '<input type="hidden" name="channel" value="'+channel.channel_type+'">';
        formHtml += '<input type="hidden" name="widget" value="'+widgetId+'">';
        formHtml += '<input type="hidden" name="ref_url" value="'+window.location.href+'">';
        formHtml += '<input type="hidden" name="token" value="'+googleV3Token+'">';
        formHtml += '<input type="hidden" name="page_id" value="'+chaty_settings.page_id+'">';
        formHtml += '<input type="hidden" name="page_title" value="'+getPageTitle()+'">';

        var v2_captcha_response = $(".g-recaptcha-response").length ? $(".g-recaptcha-response").val() : "";
        formHtml += '<input type="hidden" name="v2token" value="'+v2_captcha_response+'">';
        if(isTrue(channel.enable_recaptcha)) {
            if (!isEmpty(channel.v2_site_key)) {
                formHtml += "<input type='hidden' id='v2_site_key' class='v2_site_key' value='" + channel.v2_site_key + "'>";
            }
            if (!isEmpty(channel.v3_site_key)) {
                formHtml += "<input type='hidden' id='v3_site_key' class='v3_site_key' value='" + channel.v3_site_key + "'>";
            }
        }
        formHtml += "</form>";
        formHtml += "</div>";
        formHtml += "</div>";
        formHtml += "</div>";
        $("body").append(formHtml);

        setChatyEditor();

    }

    /**
     *
     * to make WhatsApp popup form
     * Added On: 11/01/2021
     * Added By: Chirag Thummar
     *
     * */
    function startMakingWhatsAppPopup(channel, widgetId) {
        const currentDate = new Date();
        var currentMinute = (currentDate.getMinutes() < 10) ? "0"+currentDate.getMinutes() : currentDate.getMinutes();
        var currentHour = (currentDate.getHours() < 10) ? "0"+currentDate.getHours() : currentDate.getHours();
        const time = currentHour + ":" + currentMinute;
        var formHtml = "";
        var widgetIndex = getWidgetIndex(widgetId);
        if (widgetIndex == null) {
            widgetIndex = -1;
        }
        var formAction = "https://web.whatsapp.com/send";
        var formTarget = "";
        if (!isChatyInMobile) {
            if (isTrue(channel.is_use_web_version)) {
                formAction = "https://web.whatsapp.com/send";
            } else {
                formAction = "https://wa.me/" + channel.value;
            }
            formTarget = "_blank";
        } else {
            formAction = "https://wa.me/" + channel.value;
        }
        formHtml += "<div style='display:none;' class='chaty-outer-forms chaty-popup-whatsapp-form chaty-whatsapp-btn-form chaty-form-" + widgetId + "' data-channel='" + channel.channel_type + "' id='chaty-form-" + widgetId + "-" + channel.channel_type + "' data-widget='" + widgetId + "' data-index='" + widgetIndex + "'>";
        formHtml += "<div class='chaty-whatsapp-form'>";

        var headerTitle = !isEmpty(channel.wp_popup_headline)?channel.wp_popup_headline:"";
        formHtml += "<div class='chaty-whatsapp-header'>";
        formHtml += "<div class='header-wp-icon'>";
        formHtml += '<svg xmlns="http://www.w3.org/2000/svg" width="32" height="33" viewBox="0 0 32 33" fill="none"> <g filter="url(#filter0_f_9477_7201)"> <path d="M9.95924 25.2858L10.3674 25.5276C12.0818 26.545 14.0475 27.0833 16.052 27.0842H16.0562C22.2122 27.0842 27.2221 22.0753 27.2247 15.919C27.2258 12.9357 26.0652 10.1303 23.9565 8.01998C22.9223 6.97924 21.6919 6.15397 20.3365 5.59195C18.9812 5.02992 17.5278 4.74231 16.0606 4.74576C9.89989 4.74576 4.88975 9.75407 4.88756 15.91C4.88453 18.0121 5.47648 20.0722 6.59498 21.852L6.86071 22.2742L5.73223 26.394L9.95924 25.2858ZM2.50586 29.5857L4.41235 22.6249C3.23657 20.5878 2.618 18.2768 2.61873 15.9091C2.62183 8.50231 8.64941 2.47656 16.0564 2.47656C19.6508 2.47839 23.0245 3.87717 25.5618 6.41629C28.0991 8.95542 29.4952 12.3305 29.4939 15.9199C29.4906 23.3262 23.4621 29.353 16.0562 29.353H16.0504C13.8016 29.3521 11.592 28.788 9.62923 27.7177L2.50586 29.5857Z" fill="#B3B3B3"/> </g> <path d="M2.36719 29.447L4.27368 22.4862C3.09587 20.4442 2.47721 18.1278 2.48005 15.7705C2.48316 8.36364 8.51074 2.33789 15.9177 2.33789C19.5121 2.33972 22.8859 3.73849 25.4232 6.27762C27.9605 8.81675 29.3565 12.1918 29.3552 15.7812C29.3519 23.1875 23.3234 29.2143 15.9175 29.2143H15.9117C13.663 29.2134 11.4533 28.6493 9.49056 27.5791L2.36719 29.447Z" fill="white"/> <path d="M15.715 3.84769C9.17146 3.84769 3.85 9.16696 3.84767 15.7051C3.84445 17.9377 4.47318 20.1257 5.66119 22.016L5.94343 22.4646L4.48888 27.2525L9.23469 25.663L9.66824 25.9199C11.4891 27.0005 13.5769 27.5719 15.7061 27.5731H15.7105C22.249 27.5731 27.5705 22.2532 27.573 15.7146C27.5779 14.1562 27.2737 12.6123 26.6778 11.1722C26.082 9.73214 25.2064 8.42458 24.1017 7.3252C23.0032 6.21981 21.6963 5.34329 20.2567 4.74637C18.8171 4.14946 17.2734 3.844 15.715 3.84769Z" fill="#25D366"/> <path fill-rule="evenodd" clip-rule="evenodd" d="M12.0858 9.60401C11.8138 9.00922 11.5276 8.99717 11.2692 8.98687L10.5736 8.97852C10.3316 8.97852 9.93846 9.0679 9.60608 9.42544C9.27369 9.78297 8.33594 10.6471 8.33594 12.4046C8.33594 14.1622 9.63628 15.8605 9.81747 16.0991C9.99866 16.3377 12.3277 20.0594 16.0162 21.4913C19.0813 22.6813 19.705 22.4446 20.3706 22.3852C21.0361 22.3257 22.5175 21.521 22.8197 20.6869C23.1219 19.8527 23.1221 19.138 23.0315 18.9886C22.9409 18.8391 22.6989 18.7503 22.3357 18.5716C21.9725 18.3928 20.1888 17.5287 19.8562 17.4094C19.5236 17.2901 19.2818 17.2308 19.0396 17.5883C18.7975 17.9459 18.1029 18.7501 17.8911 18.9886C17.6793 19.227 17.4679 19.2569 17.1047 19.0783C16.7416 18.8998 15.5731 18.5224 14.1867 17.3054C13.108 16.3585 12.3799 15.1892 12.1679 14.8318C11.9559 14.4745 12.1454 14.2809 12.3274 14.1029C12.4902 13.9428 12.6901 13.6858 12.8719 13.4773C13.0537 13.2688 13.1135 13.1197 13.2343 12.8817C13.3551 12.6437 13.2949 12.4346 13.2041 12.256C13.1133 12.0774 12.4083 10.3105 12.0858 9.60401Z" fill="white"/> <defs> <filter id="filter0_f_9477_7201" x="1.21611" y="1.18682" width="29.5678" height="29.6889" filterUnits="userSpaceOnUse" color-interpolation-filters="sRGB"> <feFlood flood-opacity="0" result="BackgroundImageFix"/> <feBlend mode="normal" in="SourceGraphic" in2="BackgroundImageFix" result="shape"/> <feGaussianBlur stdDeviation="0.644873" result="effect1_foregroundBlur_9477_7201"/> </filter> </defs> </svg>';
        formHtml += "</div>";
        formHtml += "<div class='header-wp-title'>";
        formHtml += headerTitle;
        formHtml += "</div>";
        formHtml += "<div class='whatsapp-form-close-btn'>";
        formHtml += '<svg width="15" height="9" viewBox="0 0 15 9" fill="none" xmlns="http://www.w3.org/2000/svg"> <path d="M1 1L7.31429 8L14 1" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path> </svg>';
        formHtml += "</div>";
        formHtml += "</div>";


        formHtml += "<div class='chaty-whatsapp-body'>";
        if(!isEmpty(channel.chat_welcome_message)) {
            if (channel.wp_popup_profile != "") {
                formHtml += "<div class='chaty-whatsapp-content has-content'>";
                formHtml += "<div class='wp-profile-img'>";
                formHtml += "<img src='" + channel.wp_popup_profile + "'>"
                formHtml += "</div>";
            } else {
                formHtml += "<div class='chaty-whatsapp-content'>";
            }
            formHtml += "<div class='chaty-whatsapp-message'>";
            formHtml += "<div class='chaty-whatsapp-message-nickname'>" + channel.wp_popup_nickname + "</div>";
            formHtml += "<div class='chaty-whatsapp-message-content'></div>";
            formHtml += "<div class='chaty-whatsapp-message-time'>" + time + "</div>"
            formHtml += "</div>";
            formHtml += "</div>";
        }
        formHtml += "</div>";
        formHtml += "<div class='chaty-whatsapp-footer'>";
        formHtml += "<form action='" + formAction + "' target='" + formTarget + "' class='whatsapp-chaty-form-" + widgetId + " whatsapp-chaty-form " + (isTrue(channel.is_default_open) ? "add-analytics" : "") + "' data-widget='" + widgetId + "' data-channel='" + channel.channel_type + "' autocomplete='off'>";
        formHtml += "<div class='chaty-whatsapp-data'>";
        formHtml += `<div class='chaty-whatsapp-field ${(isTrue(channel.emoji_picker))?"has_emoji":""}'>`;
        if(isTrue(channel.emoji_picker)) {
            formHtml += '<button type="button" class="chaty-wp-emoji-input"><span class="hide-cht-svg-bg">' + chaty_settings.lang.emoji_picker + '</span><svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"> <path d="M12 2C6.47 2 2 6.5 2 12C2 14.6522 3.05357 17.1957 4.92893 19.0711C5.85752 19.9997 6.95991 20.7362 8.17317 21.2388C9.38642 21.7413 10.6868 22 12 22C14.6522 22 17.1957 20.9464 19.0711 19.0711C20.9464 17.1957 22 14.6522 22 12C22 10.6868 21.7413 9.38642 21.2388 8.17317C20.7362 6.95991 19.9997 5.85752 19.0711 4.92893C18.1425 4.00035 17.0401 3.26375 15.8268 2.7612C14.6136 2.25866 13.3132 2 12 2ZM15.5 8C15.8978 8 16.2794 8.15804 16.5607 8.43934C16.842 8.72064 17 9.10218 17 9.5C17 9.89782 16.842 10.2794 16.5607 10.5607C16.2794 10.842 15.8978 11 15.5 11C15.1022 11 14.7206 10.842 14.4393 10.5607C14.158 10.2794 14 9.89782 14 9.5C14 9.10218 14.158 8.72064 14.4393 8.43934C14.7206 8.15804 15.1022 8 15.5 8ZM8.5 8C8.89782 8 9.27936 8.15804 9.56066 8.43934C9.84196 8.72064 10 9.10218 10 9.5C10 9.89782 9.84196 10.2794 9.56066 10.5607C9.27936 10.842 8.89782 11 8.5 11C8.10218 11 7.72064 10.842 7.43934 10.5607C7.15804 10.2794 7 9.89782 7 9.5C7 9.10218 7.15804 8.72064 7.43934 8.43934C7.72064 8.15804 8.10218 8 8.5 8ZM12 17.5C9.67 17.5 7.69 16.04 6.89 14H17.11C16.3 16.04 14.33 17.5 12 17.5Z" fill="#CDD9E2"/> </svg></button>';
        }
        formHtml += "<input name='text' type='text' id='chaty_whatsapp_input' class='chaty-whatsapp-input' />";
        formHtml += "<button type='submit' class='chaty-whatsapp-button-button' >";
        formHtml += "<span class='hide-cht-svg-bg'>"+chaty_settings.lang.whatsapp_button+"</span>";
        formHtml += '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" fill="none"><g clip-path="url(#clip0_9452_6982)"><path d="M18.5703 9.99996L2.66037 17.6603L5.60665 9.99996L2.66037 2.33963L18.5703 9.99996Z" fill="white" stroke="white" stroke-width="1.6625" stroke-linecap="round" stroke-linejoin="round"></path><path d="M8.24069 9.99947L3.07723 9.99992" stroke="#C6D7E3" stroke-width="1.6625" stroke-linecap="round" stroke-linejoin="round"></path></g><defs><clipPath id="clip0_9452_6982"><rect width="20" height="20" fill="white"></rect></clipPath></defs></svg>';
        formHtml += "</button>";
        formHtml += "</div>";
        formHtml += "</div>";
        if (!isChatyInMobile && isTrue(channel.is_use_web_version)) {
            formHtml += "<input type='hidden' name='phone' value='" + channel.value + "' />";
        }
        formHtml += "</form>";
        formHtml += "</div>";
        formHtml += "</div>";
        formHtml += "</div>";
        $("body").append(formHtml);
        $("#chaty-form-" + widgetId + "-" + channel.channel_type + " .chaty-whatsapp-message .chaty-whatsapp-message-content").html(channel.chat_welcome_message);
        if (!isEmpty(channel.pre_set_message)) {
            var preSetMessage = channel.pre_set_message;
            var pageTitle = $("title").text();
            if (!isEmpty(pageTitle)) {
                preSetMessage = preSetMessage.replace(/{title}/g, pageTitle);
            } else {
                preSetMessage = preSetMessage.replace(/{title}/g, '');
            }
            preSetMessage = preSetMessage.replace(/{url}/g, window.location);
            $("#chaty-form-" + widgetId + "-" + channel.channel_type + " .chaty-whatsapp-input").val(preSetMessage);
        }
        $("#chaty-form-" + widgetId + "-" + channel.channel_type).show();
        $("#chaty-form-" + widgetId + "-" + channel.channel_type + " .chaty-whatsapp-input").attr("placeholder", channel.input_placeholder);
        $("#chaty-form-" + widgetId + "-" + channel.channel_type+" .chaty-whatsapp-header").css("background-color", channel.wp_popup_head_bg_color);
    }

    /**
     *
     * To decode HTML Tag
     * Added On: 07/17/2022
     * Added By: Chirag Thummar
     *
     * */

    function htmlDecode(input) {
        var doc = new DOMParser().parseFromString(input, "text/html");
        return doc.documentElement.textContent;
    }

    /**
     *
     * To get widget index
     * Added On: 11/01/2021
     * Added By: Chirag Thummar
     *
     * */
    function getWidgetIndex(widgetId) {
        var widgetIndex = null;
        if (widgetData.length) {
            $.each(widgetData, function (key, widgetRecord) {
                if (widgetRecord.id == widgetId) {
                    widgetIndex = key;
                }
            });
        }
        return widgetIndex;
    }

    /**
     *
     * to make WhatsApp popup form
     * Added On: 11/02/2021
     * Added By: Chirag Thummar
     *
     * */
    function startMakingWeChatChannel(channel, widgetId) {
        var formHtml = "";
        var widgetIndex = getWidgetIndex(widgetId);
        if (widgetIndex == null) {
            widgetIndex = -1;
        }
        var qrCodeTitle = !isEmpty(channel.wechat_header)?(channel.wechat_header+": "):"";
        qrCodeTitle += channel.value;
        formHtml += "<div style='display:none;' class='chaty-outer-forms chaty-wechat-form chaty-form-" + widgetId + "' data-channel='" + channel.channel_type + "' id='chaty-form-" + widgetId + "-" + channel.channel_type + "' data-widget='" + widgetId + "' data-index='" + widgetIndex + "'>";
        formHtml += "<div class='chaty-form'>";
        formHtml += "<div class='chaty-form-body qr-code-body'>";
        formHtml += '<div class="qr-code-header"><div class="qr-code-head-title"><svg width="25" height="22" viewBox="0 0 25 22" fill="none" xmlns="http://www.w3.org/2000/svg"> <path d="M17.2315 6.81844C17.6918 6.81844 18.1403 6.85385 18.5769 6.91286C17.7744 3.40752 14.092 0.751953 9.66605 0.751953C4.64999 0.751953 0.578125 4.15108 0.578125 8.34096C0.578125 10.7605 1.93541 12.8967 4.04806 14.2894L2.85601 16.6853L6.1135 15.2808C6.80985 15.5287 7.5416 15.7293 8.32057 15.8356C8.21435 15.3753 8.15533 14.9032 8.15533 14.4075C8.14353 10.2294 12.2154 6.81844 17.2315 6.81844ZM12.6875 4.16288C12.8363 4.16288 12.9836 4.19218 13.1211 4.24912C13.2586 4.30607 13.3835 4.38952 13.4887 4.49474C13.5939 4.59995 13.6773 4.72486 13.7343 4.86232C13.7912 4.99979 13.8205 5.14712 13.8205 5.29592C13.8205 5.44471 13.7912 5.59205 13.7343 5.72951C13.6773 5.86698 13.5939 5.99189 13.4887 6.0971C13.3835 6.20231 13.2586 6.28577 13.1211 6.34271C12.9836 6.39965 12.8363 6.42896 12.6875 6.42896C12.387 6.42896 12.0988 6.30958 11.8863 6.0971C11.6738 5.88461 11.5545 5.59642 11.5545 5.29592C11.5545 4.99542 11.6738 4.70722 11.8863 4.49474C12.0988 4.28225 12.387 4.16288 12.6875 4.16288ZM6.63281 6.44076C6.33231 6.44076 6.04412 6.32139 5.83163 6.1089C5.61914 5.89641 5.49977 5.60822 5.49977 5.30772C5.49977 5.00722 5.61914 4.71903 5.83163 4.50654C6.04412 4.29405 6.33231 4.17468 6.63281 4.17468C6.93331 4.17468 7.2215 4.29405 7.43399 4.50654C7.64648 4.71903 7.76585 5.00722 7.76585 5.30772C7.76585 5.60822 7.64648 5.89641 7.43399 6.1089C7.2215 6.32139 6.93331 6.44076 6.63281 6.44076Z" fill="white"/> <path d="M24.7978 14.4102C24.7978 11.0583 21.4105 8.34375 17.2324 8.34375C13.0543 8.34375 9.66699 11.0583 9.66699 14.4102C9.66699 17.7621 13.0543 20.4767 17.2324 20.4767C17.9169 20.4767 18.5779 20.3823 19.2034 20.2407L23.2871 21.9992L21.8708 19.1666C23.6412 18.0572 24.7978 16.3577 24.7978 14.4102ZM14.9545 14.0326C14.7304 14.0326 14.5114 13.9661 14.325 13.8416C14.1387 13.7171 13.9935 13.5401 13.9077 13.3331C13.822 13.1261 13.7995 12.8983 13.8432 12.6785C13.887 12.4587 13.9949 12.2568 14.1533 12.0983C14.3118 11.9399 14.5137 11.832 14.7335 11.7882C14.9533 11.7445 15.1811 11.767 15.3881 11.8527C15.5951 11.9385 15.7721 12.0837 15.8966 12.27C16.0211 12.4564 16.0876 12.6754 16.0876 12.8995C16.0994 13.525 15.58 14.0326 14.9545 14.0326ZM19.4985 14.0326C19.198 14.0326 18.9098 13.9132 18.6973 13.7007C18.4848 13.4882 18.3654 13.2 18.3654 12.8995C18.3654 12.599 18.4848 12.3108 18.6973 12.0983C18.9098 11.8858 19.198 11.7665 19.4985 11.7665C19.799 11.7665 20.0872 11.8858 20.2997 12.0983C20.5121 12.3108 20.6315 12.599 20.6315 12.8995C20.6315 13.2 20.5121 13.4882 20.2997 13.7007C20.0872 13.9132 19.799 14.0326 19.4985 14.0326Z" fill="white"/> </svg>'+qrCodeTitle+'</div><div class="qr-code-hide-btn chaty-close-button"><svg width="15" height="9" viewBox="0 0 15 9" fill="none" xmlns="http://www.w3.org/2000/svg"> <path d="M1 1L7.31429 8L14 1" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path> </svg></div></div>';
        if(!isEmpty(channel.wechat_qr_code_title)) {
            formHtml += '<div class="qr-code-title">'+channel.wechat_qr_code_title+':</div>';
        }
        formHtml += "<div role='button' class='close-chaty-form is-whatsapp-btn'><div aria-hidden='true' class='chaty-close-button'></div><span class='hide-cht-svg-bg'>"+chaty_settings.lang.hide_whatsapp_form+"</span></div>";
        formHtml += "<div class='qr-code-box'><div class='qr-code-image'><img src='" + channel.qr_code_image_url + "' alt='" + channel.title + "' /></div>";
        formHtml += "</div>";
        formHtml += "</div>";
        formHtml += "</div>";
        $("body").append(formHtml);

        $("#chaty-form-" + widgetId + "-" + channel.channel_type +" .qr-code-header").css("background-color",channel.wechat_header_color);
    }

    /**
     *
     * get tooltip position for channel
     * Added On: 09/29/2021
     * Added By: Chirag Thummar
     *
     * */
    function isEmpty(varVal) {
        if (varVal == null || varVal == "" || $.trim(varVal) == "") {
            return true
        }
        return false;
    }

    /**
     *
     * To get channel icon
     * Added On: 10/01/2021
     * Added By: Chirag Thummar
     *
     * */
    function getChannelIcon(channel) {
        if (channel.custom_image_url != "" && channel.custom_image_url != "null") {
            return "<span class='chaty-icon channel-icon-" + channel.channel_type + "'><span class='chaty-svg chaty-svg-img'><img src='" + channel.custom_image_url + "' alt='" + channel.hover_text + "' /></span></span>";
        }
        return "<span class='chaty-icon channel-icon-" + channel.channel_type + "'><span class='chaty-svg'>" + channel.svg_icon + "</span></span>";
    }

    /**
     *
     * check for empty or null items
     * Added On: 10/01/2021
     * Added By: Chirag Thummar
     *
     * */
    function isValueEmpty(value) {
        if (value == "" || $.trim(value) == "" || value == null || value == "null") {
            return true;
        }
        return false;
    }

    /**
     *
     * To get widget position
     * Added On: 10/01/2021
     * Added By: Chirag Thummar
     *
     * */
    function getWidgetPosition(widgetRecord) {
        if (widgetRecord.position == "custom") {
            return widgetRecord.custom_position;
        }
        return widgetRecord.position;
    }

    /**
     *
     * get tooltip position for channel
     * Added On: 09/29/2021
     * Added By: Chirag Thummar
     *
     * */
    function getToolTipPosition(widgetRecord) {
        if (widgetRecord.settings.icon_view != "vertical") {
            return "top";
        }
        var toolTipPosition = getWidgetPosition(widgetRecord.settings);
        if (toolTipPosition == "right") {
            return "left";
        }
        return "right";
    }

    /**
     *
     * Check for Triggers if exists
     * Added On: 10/04/2021
     * Added By: Chirag Thummar
     *
     * */

    function checkForChatyTriggers() {
        if ($(".chaty.auto-hide-chaty").length) {
            chatyHideTimeInterval = setInterval(function () {
                chatyHideIntervalTime++;
                var currentTime = chatyHideIntervalTime;
                if ($(".chaty.auto-hide-chaty.hide-after-" + chatyHideIntervalTime).length) {
                    var widgetId = $(".chaty.auto-hide-chaty.hide-after-" + currentTime).data("id");
                    $(".chaty-form-" + widgetId).removeClass("active");
                    $(".chaty.auto-hide-chaty.hide-after-" + currentTime).removeClass("active");
                    $("#chaty-widget-" + widgetId).removeClass("auto-hide-chaty");
                    $("body").removeClass("add-bg-blur-effect");
                }
                if ($(".chaty.auto-hide-chaty").length == 0) {
                    clearInterval(chatyHideTimeInterval);
                }
            }, 1000);
        }
        if (chatyHasTimeDelay) {
            chatyTimeInterval = setInterval(function () {
                chatyIntervalTime++;
                if ($(".chaty.delay-time-" + chatyIntervalTime).length) {
                    //$(".chaty.delay-time-"+chatyIntervalTime).addClass("active");
                    var widgetId = $(".chaty.delay-time-" + chatyIntervalTime).data("id");
                    removeTriggerRules(widgetId);
                }
            }, 1000);
        }

        if (chatyHasPageScroll) {
            $(window).on("scroll", function () {
                if (chatyHasPageScroll) {
                    var scrollHeight = $(document).height() - $(window).height();
                    var scrollPos = $(window).scrollTop();
                    if (scrollHeight != 0) {
                        var scrollPer = parseInt((scrollPos / scrollHeight) * 100);
                        if (lastScrollPer <= scrollPer) {
                            var startFrom = lastScrollPer;
                            lastScrollPer = scrollPer;
                            for (var i = startFrom; i <= scrollPer; i++) {
                                if ($.inArray(i, chatyPageScrolls) == -1) {
                                    if ($(".chaty.on-chaty-scroll.page-scroll-" + i).length) {
                                        $(".chaty.on-chaty-scroll.page-scroll-" + i).each(function () {
                                            //$(this).addClass("active");
                                            var widgetId = $(this).data("id");
                                            $(this).removeClass("on-chaty-scroll");
                                            removeTriggerRules(widgetId);
                                        });
                                    }
                                }
                            }
                            lastScrollPer = scrollPer;
                        }
                    }
                }
            });
            var hasScrollbar = window.innerWidth > document.documentElement.clientWidth;
            if (!hasScrollbar) {
                /*$(".chaty.on-chaty-scroll:not(.on-chaty-delay):not(.on-chaty-exit-intent)").each(function(){
                    $(this).addClass("active");
                    var widgetId = $(this).data("id");
                    removeTriggerRules(widgetId);
                });*/
            }
        }

        //if(chatyHasExitIntent) {

        //}
    }



    function close_chaty() {
        if (jQuery(".chaty.active .chaty-open").length) {
            jQuery(".chaty.active .chaty-open").each(function () {
                jQuery(this).find(".chaty-cta-close").trigger("click");
            })
        }
    }

    /**
     *
     * To load google Captcha V2 & V3
     * Added On: 10/02/2023
     * Added By: Chirag Thummar
     *
     * */
    function LoadChatyGoogleRecaptcha() {
        if(jQuery(".v2_site_key").length && jQuery(".v2_site_key").val() != "") {
            var dsq = document.createElement('script');
            dsq.type = 'text/javascript';
            dsq.async = true;
            dsq.src = 'https://www.google.com/recaptcha/api.js?onload=onloadChatyCallback&render=explicit&hl=en';
            (document.getElementsByTagName('head')[0] || document.getElementsByTagName('body')[0]).appendChild(dsq);
        }
        if(jQuery(".v3_site_key").length && jQuery(".v3_site_key").val() != "") {
            if (jQuery(".v3_site_key").length && jQuery(".v3_site_key").val() != "" && jQuery(".front-google-captcha").length) {
                jQuery(".front-google-captcha:not(.loaded)").each(function () {
                    jQuery(this).addClass("loaded");
                    var siteKey = jQuery(".v3_site_key").val();
                    var dsq = document.createElement('script');
                    dsq.type = 'text/javascript';
                    dsq.async = true;
                    dsq.src = 'https://www.google.com/recaptcha/api.js?onload=onloadCallbackChatyV3&render=' + siteKey;
                    (document.getElementsByTagName('head')[0] || document.getElementsByTagName('body')[0]).appendChild(dsq);
                    dsq.loadEventEnd = function() {

                    }
                })
            }
        }
    }

    /**
     *
     * Display widgets on Exit intent
     * Added On: 10/04/2021
     * Added By: Chirag Thummar
     *
     * */

    function mobileExitIntent() {
        if (window.history && window.history.pushState && chatyHasExitIntent) {
            function whenGoingBack() {
                if(chatyHasExitIntent) {
                    var hashLocation = location.hash;
                    var hashSplit = hashLocation.split("#!/");
                    var hashName = hashSplit[1];
                    if (hashName !== '') {
                        var hash = window.location.hash;
                        if (hash === '') {
                            showWidgetsOnExitIntent();
                        }
                    }
                }
            }

            var pageState = 100;
            if (window.history.state && window.history.state.page) {
                pageState = window.history.state.page;
            }
            window.history.pushState({page: pageState + 1}, '');
            window.history.pushState({page: pageState + 2}, '');

            window.onpopstate = function () {
                whenGoingBack();
            };
            window.history.onpopstate = function () {
                whenGoingBack();
            };
            window.addEventListener('popstate', function () {
                whenGoingBack();
            });
            document.addEventListener('backbutton', function () {
                whenGoingBack();
            });
            window.addEventListener('backbutton', function () {
                whenGoingBack();
            });
            $(window).on('popstate', function () {
                whenGoingBack();
            });
        }
    }

    /**
     *
     * Display widgets on Exit intent
     * Added On: 10/04/2021
     * Added By: Chirag Thummar
     *
     * */

    function bindExitIntentFunction() {
        if (isChatyInMobile) {
            // Back button was pressed.
            mobileExitIntent();
        } else {
            $(document).mouseleave(function (e) {
                function addEvent(obj, evt, fn) {
                    if (obj.addEventListener) {
                        obj.addEventListener(evt, fn, false);
                        showWidgetsOnExitIntent();
                    } else if (obj.attachEvent) {
                        obj.attachEvent("on" + evt, fn);
                    }
                }

                addEvent(document, 'mouseout', function (evt) {
                    if (evt.toElement == null && evt.relatedTarget == null) {
                        showWidgetsOnExitIntent();
                    }
                });
            });
        }
    }

    /**
     *
     * Display widgets on Exit intent
     * Added On: 10/04/2021
     * Added By: Chirag Thummar
     *
     * */

    function showWidgetsOnExitIntent() {
        if (chatyHasExitIntent && $(".on-chaty-exit-intent").length) {
            $(".on-chaty-exit-intent").each(function () {
                //$(this).addClass("active");
                var widgetId = $(this).data("id");
                $(this).removeClass("on-chaty-exit-intent");
                removeTriggerRules(widgetId);
                $("#chaty-widget-" + widgetId + " .chaty-widget").append("<div class='chaty-exit-intent'></div>");
                setTimeout(function () {
                    $(".chaty-exit-intent").addClass("animate");
                    setTimeout(function () {
                        $(".chaty-exit-intent").removeClass("animate");
                    }, 2500);
                }, 500);
            });
        }
    }

    /**
     *
     * Remove Trigger Rules if all rules executed
     * Added On: 10/04/2021
     * Added By: Chirag Thummar
     *
     * */
    function removeTriggerRules(widgetId) {

        updateWidgetViews(widgetId);

        $(".chaty-widget-" + widgetId).removeClass("on-chaty-delay");
        $(".chaty-widget-" + widgetId).removeClass("on-chaty-exit-intent");
        $(".chaty-widget-" + widgetId).removeClass("on-chaty-scroll");

        if (!$(".chaty.on-chaty-delay").length) {
            clearInterval(chatyTimeInterval);
            chatyHasTimeDelay = false;
        }
        if (!$(".chaty.on-chaty-exit-intent").length) {
            chatyHasExitIntent = false;
        }
        if (!$(".chaty.on-chaty-scroll").length) {
            chatyHasPageScroll = false;
        }
    }

    /**
     *
     * Remove Empty On hover class when text is empty
     * Added On: 10/04/2021
     * Added By: Chirag Thummar
     *
     * */
    function removeEmptyTooltip() {
        $(".chaty-tooltip").each(function () {
            if ($(this).data("hover") == "") {
                $(this).removeClass("left").removeClass("right").removeClass("top").removeClass("chaty-tooltip");
            }
        })
    }

    /**
     *
     * Set widget icon
     * Added On: 10/04/2021
     * Added By: Chirag Thummar
     *
     * */
    function getWidgetIcon(widgetRecord, widgetId) {
        /* set default icon if icon is blank */
        if (widgetRecord.widget_icon == "") {
            widgetRecord.widget_icon = "chat-base";
        } else if (widgetRecord.widget_icon == "chat-image" && isEmpty(widgetRecord.widget_icon_url)) {
            /* if custom icon is selected than check for image URL, if not exists then update icon with default icon */
            widgetRecord.widget_icon = "chat-base";
        } else if (widgetRecord.widget_icon == "chat-fa-icon" && isEmpty(widgetRecord.widget_fa_icon)) {
            widgetRecord.widget_icon = "chat-base";
        }

        if (widgetRecord.widget_icon == "chat-image") {
            return "<span class='chaty-svg' style='background-color: " + widgetRecord.widget_color + "'><img src='" + widgetRecord.widget_icon_url + "' alt='Chaty Widget' /></span>";
        } else if(widgetRecord.widget_icon == "chat-fa-icon") {
            return "<span class='chaty-svg' style='background-color: " + widgetRecord.widget_color + "'><i class='" + widgetRecord.widget_fa_icon + " widget-fa-icon' style='color: "+ widgetRecord.widget_icon_color +"'></i></span>";
        } else {
            return '<span class="chaty-svg">' + getSvgIcon(widgetRecord.widget_icon, widgetRecord.widget_color, widgetRecord.widget_icon_color, widgetId) + "</span>";
        }
    }

    /**
     *
     * check for widget animations if applicable
     * Added On: 10/04/2021
     * Added By: Chirag Thummar
     *
     * */
    function checkForWidgetAnimation(widgetRecord, widgetId) {
        var clickStatus = checkChatyCookieExpired(widgetId, 'c-widget');
        if (clickStatus && widgetRecord.attention_effect != "none" && widgetRecord.attention_effect != "") {
            $("#chaty-widget-" + widgetId).attr("data-animation", widgetRecord.attention_effect);
            if ($("#chaty-widget-" + widgetId + " .chaty-widget").hasClass("has-single")) {
                $("#chaty-widget-" + widgetId + " .chaty-i-trigger .chaty-channel .chaty-svg").addClass("chaty-animation-" + widgetRecord.attention_effect);
            } else {
                $("#chaty-widget-" + widgetId + " .chaty-i-trigger .chaty-cta-main .chaty-cta-button").addClass("chaty-animation-" + widgetRecord.attention_effect);
            }
        }
    }

    /**
     *
     * check for pending message if all criteria matches to display it
     * Added On: 10/04/2021
     * Added By: Chirag Thummar
     *
     * */
    function checkForPendingMessage(widgetRecord, widgetId) {
        var clickStatus = checkChatyCookieExpired(widgetId, 'c-widget');

        if (clickStatus && isTrue(widgetRecord.is_pending_mesg_enabled) && parseInt(widgetRecord.pending_mesg_count) > 0) {
            if ($("#chaty-widget-" + widgetId + " .chaty-widget").hasClass("has-single")) {
                if (widgetRecord.attention_effect == "sheen" || widgetRecord.attention_effect == "spin" || widgetRecord.attention_effect == "pulse") {
                    $("#chaty-widget-" + widgetId + " .chaty-i-trigger .chaty-channel").append("<span class='ch-pending-msg'>" + widgetRecord.pending_mesg_count + "</span>");
                } else {
                    $("#chaty-widget-" + widgetId + " .chaty-i-trigger .chaty-channel .chaty-svg").append("<span class='ch-pending-msg'>" + widgetRecord.pending_mesg_count + "</span>");
                }
            } else {
                if (widgetRecord.attention_effect == "jump" || widgetRecord.attention_effect == "waggle" || widgetRecord.attention_effect == "blink" || widgetRecord.attention_effect == "pulse-icon" || widgetRecord.attention_effect == "floating") {
                    $("#chaty-widget-" + widgetId + " .chaty-i-trigger .chaty-cta-main .chaty-cta-button").append("<span class='ch-pending-msg'>" + widgetRecord.pending_mesg_count + "</span>");
                } else {
                    $("#chaty-widget-" + widgetId + " .chaty-i-trigger .chaty-cta-main").append("<span class='ch-pending-msg'>" + widgetRecord.pending_mesg_count + "</span>");
                }
            }
        }
    }

    /**
     *
     * Add Prefix 0 to Number
     * Added On: 10/21/2021
     * Added By: Chirag Thummar
     *
     * */

    function addPrefixToNum(num) {
        num = num.toString();
        while (num.length < 2) num = "0" + num;
        return num;
    }

    /**
     *
     * Check for time Schedule
     * Added On: 10/21/2021
     * Added By: Chirag Thummar
     *
     * */
    function checkForDayAndTimeSchedule(widgetRecord) {
        var displayStatus = true;
        if (isTrue(widgetRecord.triggers.has_day_hours_scheduling_rules) && widgetRecord.triggers.day_hours_scheduling_rules.length > 0) {
            var displayRules = widgetRecord.triggers.day_hours_scheduling_rules;
            if (displayRules.length > 0) {
                displayStatus = false;
                var localDate = new Date();
                localDate = changeTimezone(localDate, widgetRecord.triggers.day_time_diff);
                var utcHours = localDate.getHours();
                var utcMin = localDate.getMinutes();
                var utcDay = localDate.getDay();
                for (var rule = 0; rule < displayRules.length; rule++) {
                    var hourStatus = 0;
                    var minStatus = 0;
                    var checkForTime = 0;
                    if (displayRules[rule].days == -1) {
                        checkForTime = 1;
                    } else if (displayRules[rule].days >= 0 && displayRules[rule].days <= 6) {
                        if (displayRules[rule].days == utcDay) {
                            checkForTime = 1;
                        }
                    } else if (displayRules[rule].days == 7) {
                        if (utcDay >= 0 && utcDay <= 4) {
                            checkForTime = 1;
                        }
                    } else if (displayRules[rule].days == 8) {
                        if (utcDay >= 1 && utcDay <= 5) {
                            checkForTime = 1;
                        }
                    } else if (displayRules[rule].days == 9) {
                        if (utcDay == 6 || utcDay == 0) {
                            checkForTime = 1;
                        }
                    }
                    if (checkForTime == 1) {
                        if (utcHours > displayRules[rule].start_hours && utcHours < displayRules[rule].end_hours) {
                            hourStatus = 1;
                        } else if (utcHours == displayRules[rule].start_hours && utcHours < displayRules[rule].end_hours) {
                            if (utcMin >= displayRules[rule].start_min) {
                                hourStatus = 1;
                            }
                        } else if (utcHours > displayRules[rule].start_hours && utcHours == displayRules[rule].end_hours) {
                            if (utcMin <= displayRules[rule].end_min) {
                                hourStatus = 1;
                            }
                        } else if (utcHours == displayRules[rule].start_hours && utcHours == displayRules[rule].end_hours) {
                            if (utcMin >= displayRules[rule].start_min && utcMin <= displayRules[rule].end_min) {
                                hourStatus = 1;
                            }
                        }
                        if (hourStatus == 1) {
                            if (utcMin >= displayRules[rule].start_min && utcMin <= displayRules[rule].end_min) {
                                minStatus = 1;
                            }
                        }
                    }

                    if (hourStatus == 1 && checkForTime == 1) {
                        displayStatus = 1;
                    }
                    if (displayStatus == 1) {
                        rule = displayRules.length + 1;
                    }
                }
            }
        }
        return displayStatus;
    }

    /**
     *
     * get tooltip position for channel
     * Added On: 09/29/2021
     * Added By: Chirag Thummar
     *
     * */
    function isTrue(varVal) {
        if (varVal == "1" || varVal == 1 || varVal == true || varVal == "true" || varVal == "yes" || varVal == "on") {
            return true
        }
        return false;
    }

    /**
     *
     * To get time diffrence between dates
     * Added On: 04/18/2022
     * Added By: Chirag Thummar
     *
     * */

    function changeTimezone(date, ianatz) {
        if (isNaN(ianatz)) {
            var invdate = new Date(date.toLocaleString('en-US', {
                timeZone: ianatz
            }));
            var diff = date.getTime() - invdate.getTime();
            return new Date(date.getTime() - diff); // needs to substract
        } else {
            var newDate = new Date();
            newDate = newDate.toLocaleString('en-US', {timeZone: 'UTC'});
            newDate = new Date(newDate);
            if (ianatz.indexOf("+") != -1) {
                var newTimeZone = ianatz.replace("+", "");
                var extraHours = parseInt(newTimeZone);
                var extraMin = parseFloat(newTimeZone % extraHours) * 60;
                extraMin = newDate.getUTCMinutes() + extraMin;
                if (extraMin > 59) {
                    extraHours = extraHours + parseInt(extraMin / 60);
                    extraMin = extraMin % 60;
                }
                newDate.setUTCHours(newDate.getUTCHours() + extraHours, extraMin);
            } else if (ianatz.indexOf("-") != -1) {
                var newTimeZone = ianatz.replace("-", "");
                var extraHours = parseInt(newTimeZone);
                var extraMin = parseFloat(newTimeZone % extraHours) * 60;
                extraMin = newDate.getUTCMinutes() - extraMin;
                if (extraMin < 0) {
                    extraHours = extraHours - parseInt(extraMin / 60);
                    extraMin = extraMin % 60;
                }
                newDate.setUTCHours(newDate.getUTCHours() - extraHours, -extraMin);
            }
            var diff = date.getTime() - newDate.getTime();
            return new Date(date.getTime() - diff); // needs to substract
        }
    }

    /**
     *
     * Check for time Schedule
     * Added On: 10/21/2021
     * Added By: Chirag Thummar
     *
     * */

    function checkForTimeSchedule(widgetRecord) {
        if (widgetRecord.triggers.has_date_scheduling_rules) {
            var chtStartDate = widgetRecord.triggers.date_scheduling_rules.start_date_time;
            var chtEndDate = widgetRecord.triggers.date_scheduling_rules.end_date_time;

            var localDate = new Date();

            localDate = changeTimezone(localDate, widgetRecord.triggers.time_diff);

            var currentTime = localDate.getFullYear() + "-" + (addPrefixToNum(localDate.getMonth() + 1)) + "-" + addPrefixToNum(localDate.getDate()) + " " + addPrefixToNum(localDate.getHours()) + ":" + addPrefixToNum(localDate.getMinutes()) + ":" + addPrefixToNum(localDate.getSeconds());

            if (chtEndDate == "") {
                if (chtStartDate <= currentTime) {
                    return true;
                }
            }

            if (chtStartDate == "") {
                if (chtEndDate >= currentTime) {
                    return true;
                }
            }

            if (chtStartDate != "" && chtEndDate != "") {
                if (chtStartDate <= currentTime && chtEndDate >= currentTime) {
                    return true;
                }
            }
            return false;
        }
        return true;
    }

    /**
     *
     * Check for visitos's country
     * Added On: 10/21/2021
     * Added By: Chirag Thummar
     *
     * */
    function checkForUserCountry(widgetRecord) {
        if (isTrue(widgetRecord.triggers.has_countries) && !isEmpty(widgetRecord.triggers.countries) && widgetRecord.triggers.countries.length) {
            clientCountry = getUserCountry();
            if (clientCountry != "-") {
                if ($.inArray(clientCountry, widgetRecord.triggers.countries) == -1) {
                    return false;
                }
            }
        }
        return true;
    }

    /**
     *
     * To get user id from cookie or storage
     * Added On: 09/28/2021
     * Added By: Chirag Thummar
     *
     * */
    function getUserCountry() {
        clientCountry = "";
        if (typeof Storage !== "undefined" && window.sessionStorage.getItem("chaty_user_country_code") != null) {
            clientCountry = window.sessionStorage.getItem("chaty_user_country_code");
        } else {
            if (chatyCheckCookie('chaty_user_country_code')) {
                clientCountry = chatyGetCookie('chaty_user_country_code');
            }
        }
        return clientCountry;
    }

    /**
     *
     * To save user id in cookie, will be created different for users and by browsers
     * Added On: 09/28/2021
     * Added By: Chirag Thummar
     *
     * */
    function setUserCountry(userCountry) {
        if (typeof Storage !== "undefined") {
            if (window.sessionStorage.getItem("chaty_user_country_code") == null) {
                window.sessionStorage.setItem("chaty_user_country_code", userCountry);
            }
        } else {
            if (!chatyCheckCookie('chaty_user_country_code')) {
                chatySetCookie('chaty_user_country_code', userCountry, 365);
            }
        }
    }

    /**
     *
     * To ger widget's setting stored cookie Array
     * Added On: 10/01/2021
     * Added By: Chirag Thummar
     *
     * */
    function checkForChatyCookieString(widgetId, cookieStr) {
        var cookieString = chatyGetCookie("chatyWidget_" + widgetId);
        var cookieArray = [];
        if (cookieString != null && cookieString != "") {
            cookieArray = JSON.parse(cookieString);
        }
        if (cookieArray.length > 0) {
            for (var i = 0; i < cookieArray.length; i++) {
                if (cookieArray[i]['k'] == cookieStr) {
                    return cookieArray[i]['v'];
                }
            }
        }
        return null;
    }

    /**
     *
     * To save widget's setting in cookie Array
     * Added On: 10/01/2021
     * Added By: Chirag Thummar
     *
     * */
    function saveChatyCookieString(widgetId, cookieStr) {
        var cookieString = chatyGetCookie("chatyWidget_" + widgetId);
        var cookieArray = [];
        if (cookieString != null && cookieString != "") {
            cookieArray = JSON.parse(cookieString);
        }
        var cookieFound = false;
        if (cookieArray.length > 0) {
            for (var i = 0; i < cookieArray.length; i++) {
                if (cookieArray[i]['k'] == cookieStr) {
                    cookieFound = true;
                    cookieArray[i]['v'] = new Date();
                }
            }
        }
        if (!cookieFound) {
            cookieArray.push({"k": cookieStr, "v": new Date()});
        }
        cookieString = JSON.stringify(cookieArray);
        chatySetCookie("chatyWidget_" + widgetId, cookieString, "7");
    }


    /**
     *
     * To check widget's setting cookie status stored in Array
     * Added On: 10/01/2021
     * Added By: Chirag Thummar
     *
     * */
    function checkChatyCookieExpired(widgetId, cookieStr) {
        var cookieValue = checkForChatyCookieString(widgetId, cookieStr);
        if (cookieValue != null && cookieValue != "") {
            cookieValue = new Date(cookieValue);
            var diffTime = Math.abs(new Date() - cookieValue);
            var diffDays = Math.floor(diffTime / (1000 * 60 * 60 * 24));
            if (diffDays >= 2) {
                return true;
            } else {
                return false;
            }
        }
        return true;
    }

    /**
     *
     * To save data in browser cookie
     * Added On: 09/28/2021
     * Added By: Chirag Thummar
     *
     * */
    function chatySetCookie(cname, cvalue, exdays) {
        var d = new Date();
        d.setTime(d.getTime() + exdays * 24 * 60 * 60 * 1000);
        var expires = "expires=" + d.toUTCString();
        document.cookie = cname + "=" + cvalue + "; " + expires + "; path=/; SameSite=Lax";
    }

    /**
     *
     * To get data from browser cookie using cookie name
     * Added On: 09/28/2021
     * Added By: Chirag Thummar
     *
     * */
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

    /**
     *
     * To check cookie exists or not in browser
     * Added On: 09/28/2021
     * Added By: Chirag Thummar
     *
     * */
    function chatyCheckCookie(cookieName) {
        var cookie = chatyGetCookie(cookieName);
        if (cookie != "" && cookie !== null) {
            return true;
        } else {
            return false;
        }
    }

    /**
     *
     * To remove cookie from browser
     * Added On: 09/28/2021
     * Added By: Chirag Thummar
     *
     * */
    function chatyDeleteCookie(cookieName) {
        document.cookie = cookieName + '=;expires=Thu, 01 Jan 1970 00:00:01 GMT;';
    }

    /**
     *
     * To get current date from Browser
     * Added On: 09/28/2021
     * Added By: Chirag Thummar
     *
     * */
    function getCurrentDate() {
        today = new Date();
        dd = today.getDate();
        mm = today.getMonth() + 1;
        yyyy = today.getFullYear();
        if (dd < 10) {
            dd = '0' + dd;
        }
        if (mm < 10) {
            mm = '0' + mm;
        }
        return yyyy + '-' + mm + '-' + dd;
    }

    /**
     *
     * Add log message to console
     *
     * */
    function addChatyLog(message) {
        if (chatyEnv != "app") {
            console.log(message);
        }
    }
}));

function launch_chaty(widget_number) {
    if (widget_number == undefined || widget_number == "widget_index") {
        widget_number = 1;
    }
    if (jQuery("#chaty-widget-_"+widget_number).length) {
        jQuery("#chaty-widget-_"+widget_number+" .chaty-cta-button .open-chaty").trigger("click");
    }
}

var googleV3Token = "";
function onloadCallbackChatyV3() {
    var siteKey = jQuery(".v3_site_key").val();
    if(siteKey && googleV3Token == "") {
        grecaptcha.ready(function () {
            grecaptcha.execute(siteKey, {action: 'contact_form'}).then(function (token) {
                googleV3Token = token;
            });
        });
    }
}

function refreshG3Token() {
    if (typeof grecaptcha === "function" || typeof grecaptcha === "object") {
        var siteKey = jQuery(".v3_site_key").val();
        if (siteKey && googleV3Token == "") {
            grecaptcha.ready(function () {
                grecaptcha.execute(siteKey, {action: 'contact_form'}).then(function (token) {
                    googleV3Token = token;
                });
            });
        }
    }
}

function onloadChatyCallback() {
    if (jQuery(".v2_site_key").length && jQuery(".v2_site_key").val() != "" && jQuery(".front-google-captcha").length) {
        jQuery(".front-google-captcha:not(.loaded)").each(function () {
            var thisId = jQuery(this).attr("id");
            jQuery(this).addClass("loaded");
            if (document.getElementById(thisId)) {
                grecaptcha.render(thisId, {'sitekey': jQuery(".v2_site_key").val()});
            }
        })
    }

}
