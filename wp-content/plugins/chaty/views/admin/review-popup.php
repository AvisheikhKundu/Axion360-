<?php
/**
 * Chaty Popups for widget and contact form lead
 *
 * @author  : Premio <contact@premio.io>
 * @license : GPL2
 * */

if (defined('ABSPATH') === false) {
    exit;
}

if(get_option("chaty_views")) { ?>
    <div class="chaty-popup" id="chaty-rating-popup" style="display: none">
        <div class="chaty-popup-outer"></div>
        <div class="chaty-popup-inner popup-pos-bottom cht-popup-content">
            <div class="chaty-popup-content">
                <div class="rating-modal">
                    <div class="chaty-popup-close">
                        <a href="javascript:void(0)" class="close-rating-popup right-2 top-2 relative">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16"><path d="M15.6 15.5c-.53.53-1.38.53-1.91 0L8.05 9.87 2.31 15.6c-.53.53-1.38.53-1.91 0s-.53-1.38 0-1.9l5.65-5.64L.4 2.4C-.13 1.87-.13 1.02.4.49s1.38-.53 1.91 0l5.64 5.63L13.69.39c.53-.53 1.38-.53 1.91 0s.53 1.38 0 1.91L9.94 7.94l5.66 5.65c.52.53.52 1.38 0 1.91z"></path></svg>
                        </a>
                    </div>
                    <img style="width: auto; margin: 0 auto" src="<?php echo esc_url(CHT_PLUGIN_URL."admin/assets/images/logo-color.svg") ?>">
                    <div class="rating-modal-steps active" id="step-1">
                        <div class="upgrade-title"><?php esc_html_e("Seems like Chaty is bringing you value 🥳", "chaty"); ?></div>
                        <div class="upgrade-desc"><?php esc_html_e("Can you please show us some love and rate Chaty? It'll really help us spread the word", "chaty"); ?></div>
                        <div class="upgrade-rating">
                            <div id="chaty-rating"></div>
                        </div>
                        <div class="upgrade-user-rating"><span>0/5</span> <?php esc_html_e("rating", "chaty"); ?></div>
                    </div>
                    <div class="rating-modal-steps" id="step-2">
                        <div class="upgrade-title"><?php esc_html_e("Share Your Experience", "chaty"); ?></div>
                        <div class="upgrade-rating">
                            <div id="chaty-rated-rating" class="chaty-rated-rating"></div>
                        </div>
                        <div class="upgrade-user-rating"><span>0/5</span> <?php esc_html_e("rating", "chaty"); ?></div>
                        <div class="upgrade-review-textarea">
                            <label for="upgrade-review-comment"><?php esc_html_e("Review (optional)", "chaty"); ?><span>1000</span></label>
                            <textarea id="upgrade-review-comment" placeholder="<?php esc_html_e("Please write your review here", "chaty"); ?>"></textarea>
                        </div>
                        <div class="upgrade-modal-button">
                            <a href="javascript:;" id="upgrade-review-button" class="upgrade-review-button"><?php esc_html_e("Submit", "chaty"); ?></a>
                        </div>
                    </div>
                    <div class="rating-modal-steps" id="step-3">
                        <div class="upgrade-title"><?php esc_html_e("Would you like to be reminded in the future?", "chaty"); ?></div>
                        <div class="upgrade-review-textarea">
                            <label for="upgrade-review-reminder"><?php esc_html_e("Remind me after", "chaty"); ?></label>
                            <select id="upgrade-review-reminder" class="upgrade-review-reminder">
                                <option value="7"><?php esc_html_e("7 Days", "chaty"); ?></option>
                                <option value="14"><?php esc_html_e("14 Days", "chaty"); ?></option>
                                <option value="-1"><?php esc_html_e("Don't remind me", "chaty"); ?></option>
                            </select>
                        </div>
                        <div class="upgrade-modal-button">
                            <a type="button" id="remind-review-button" class="upgrade-review-button"><?php esc_html_e("Submit", "chaty"); ?></a>
                        </div>
                    </div>
                    <div class="rating-modal-steps" id="step-4">
                        <div class="upgrade-title">
                            <?php esc_html_e("Five Stars!", "chaty"); ?>
                            <div class="chaty-rated-rating">
                                <div class="jq-star"><svg shape-rendering="geometricPrecision" xmlns="http://www.w3.org/2000/svg" x="0px" y="0px" width="305px" height="305px" viewBox="60 -62 309 309" style="enable-background:new 64 -59 305 305; stroke-width:0px;" xml:space="preserve"> <polygon data-side="center" className="svg-empty-28" points="281.1,129.8 364,55.7 255.5,46.8 214,-59 172.5,46.8 64,55.4 146.8,129.7 121.1,241 212.9,181.1 213.9,181 306.5,241 " style="fill: transparent; stroke: #ffa83e;"></polygon> <polygon data-side="left" className="svg-empty-28" points="281.1,129.8 364,55.7 255.5,46.8 214,-59 172.5,46.8 64,55.4 146.8,129.7 121.1,241 213.9,181.1 213.9,181 306.5,241 " style="stroke-opacity: 0;"></polygon> <polygon data-side="right" className="svg-empty-28" points="364,55.7 255.5,46.8 214,-59 213.9,181 306.5,241 281.1,129.8 " style="stroke-opacity: 0;"></polygon> </svg></div>
                                <div class="jq-star"><svg shape-rendering="geometricPrecision" xmlns="http://www.w3.org/2000/svg" x="0px" y="0px" width="305px" height="305px" viewBox="60 -62 309 309" style="enable-background:new 64 -59 305 305; stroke-width:0px;" xml:space="preserve"> <polygon data-side="center" className="svg-empty-28" points="281.1,129.8 364,55.7 255.5,46.8 214,-59 172.5,46.8 64,55.4 146.8,129.7 121.1,241 212.9,181.1 213.9,181 306.5,241 " style="fill: transparent; stroke: #ffa83e;"></polygon> <polygon data-side="left" className="svg-empty-28" points="281.1,129.8 364,55.7 255.5,46.8 214,-59 172.5,46.8 64,55.4 146.8,129.7 121.1,241 213.9,181.1 213.9,181 306.5,241 " style="stroke-opacity: 0;"></polygon> <polygon data-side="right" className="svg-empty-28" points="364,55.7 255.5,46.8 214,-59 213.9,181 306.5,241 281.1,129.8 " style="stroke-opacity: 0;"></polygon> </svg></div>
                                <div class="jq-star"><svg shape-rendering="geometricPrecision" xmlns="http://www.w3.org/2000/svg" x="0px" y="0px" width="305px" height="305px" viewBox="60 -62 309 309" style="enable-background:new 64 -59 305 305; stroke-width:0px;" xml:space="preserve"> <polygon data-side="center" className="svg-empty-28" points="281.1,129.8 364,55.7 255.5,46.8 214,-59 172.5,46.8 64,55.4 146.8,129.7 121.1,241 212.9,181.1 213.9,181 306.5,241 " style="fill: transparent; stroke: #ffa83e;"></polygon> <polygon data-side="left" className="svg-empty-28" points="281.1,129.8 364,55.7 255.5,46.8 214,-59 172.5,46.8 64,55.4 146.8,129.7 121.1,241 213.9,181.1 213.9,181 306.5,241 " style="stroke-opacity: 0;"></polygon> <polygon data-side="right" className="svg-empty-28" points="364,55.7 255.5,46.8 214,-59 213.9,181 306.5,241 281.1,129.8 " style="stroke-opacity: 0;"></polygon> </svg></div>
                                <div class="jq-star"><svg shape-rendering="geometricPrecision" xmlns="http://www.w3.org/2000/svg" x="0px" y="0px" width="305px" height="305px" viewBox="60 -62 309 309" style="enable-background:new 64 -59 305 305; stroke-width:0px;" xml:space="preserve"> <polygon data-side="center" className="svg-empty-28" points="281.1,129.8 364,55.7 255.5,46.8 214,-59 172.5,46.8 64,55.4 146.8,129.7 121.1,241 212.9,181.1 213.9,181 306.5,241 " style="fill: transparent; stroke: #ffa83e;"></polygon> <polygon data-side="left" className="svg-empty-28" points="281.1,129.8 364,55.7 255.5,46.8 214,-59 172.5,46.8 64,55.4 146.8,129.7 121.1,241 213.9,181.1 213.9,181 306.5,241 " style="stroke-opacity: 0;"></polygon> <polygon data-side="right" className="svg-empty-28" points="364,55.7 255.5,46.8 214,-59 213.9,181 306.5,241 281.1,129.8 " style="stroke-opacity: 0;"></polygon> </svg></div>
                                <div class="jq-star"><svg shape-rendering="geometricPrecision" xmlns="http://www.w3.org/2000/svg" x="0px" y="0px" width="305px" height="305px" viewBox="60 -62 309 309" style="enable-background:new 64 -59 305 305; stroke-width:0px;" xml:space="preserve"> <polygon data-side="center" className="svg-empty-28" points="281.1,129.8 364,55.7 255.5,46.8 214,-59 172.5,46.8 64,55.4 146.8,129.7 121.1,241 212.9,181.1 213.9,181 306.5,241 " style="fill: transparent; stroke: #ffa83e;"></polygon> <polygon data-side="left" className="svg-empty-28" points="281.1,129.8 364,55.7 255.5,46.8 214,-59 172.5,46.8 64,55.4 146.8,129.7 121.1,241 213.9,181.1 213.9,181 306.5,241 " style="stroke-opacity: 0;"></polygon> <polygon data-side="right" className="svg-empty-28" points="364,55.7 255.5,46.8 214,-59 213.9,181 306.5,241 281.1,129.8 " style="stroke-opacity: 0;"></polygon> </svg></div>
                            </div>
                        </div>
                        <div class="upgrade-desc"><?php esc_html_e("Feel free to connect for questions and suggestions. Thank you for choosing us!", "chaty"); ?></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        (function (factory) {
            "use strict";
            if (typeof define === 'function' && define.amd) {
                define(['jquery'], factory);
            }
            else if(typeof module !== 'undefined' && module.exports) {
                module.exports = factory(require('jquery'));
            }
            else {
                factory(jQuery);
            }
        }(function ($, undefined) {
            $(document).ready(function(){
                if(typeof(chaty_rating_settings) == "object") {
                    if ($("#chaty-rating-popup").length && typeof (chaty_rating_settings) == "object") {
                        $("#chaty-rating-popup").show();
                        $("#chaty-rating").starRating({
                            initialRating: 0,
                            useFullStars: true,
                            strokeColor: '#FDB10C',
                            ratedColor: '#FDB10C',
                            activeColor: '#FDB10C',
                            strokeWidth: 0,
                            minRating: 1,
                            starSize: 32,
                            useGradient: 0,
                            onLeave: function() {
                                $(".upgrade-user-rating span").text("0/5");
                            },
                            onHover: function (currentRate) {
                                $(".upgrade-user-rating span").text(currentRate + "/5");
                            },
                            callback: function(currentRate) {
                                if (currentRate !== 5) {
                                    $(".rating-modal-steps").removeClass("active");
                                    $(".rating-modal-steps#step-2").addClass("active");
                                    $("#chaty-rated-rating").html("");
                                    for (i = 0; i < parseInt(currentRate); i++) {
                                        var ratingStar = '<div class="jq-star"><svg shape-rendering="geometricPrecision" xmlns="http://www.w3.org/2000/svg" x="0px" y="0px" width="305px" height="305px" viewBox="60 -62 309 309" style="enable-background:new 64 -59 305 305; stroke-width:0px;" xml:space="preserve"> <polygon data-side="center" className="svg-empty-28" points="281.1,129.8 364,55.7 255.5,46.8 214,-59 172.5,46.8 64,55.4 146.8,129.7 121.1,241 212.9,181.1 213.9,181 306.5,241 " style="fill: transparent; stroke: #ffa83e;"></polygon> <polygon data-side="left" className="svg-empty-28" points="281.1,129.8 364,55.7 255.5,46.8 214,-59 172.5,46.8 64,55.4 146.8,129.7 121.1,241 213.9,181.1 213.9,181 306.5,241 " style="stroke-opacity: 0;"></polygon> <polygon data-side="right" className="svg-empty-28" points="364,55.7 255.5,46.8 214,-59 213.9,181 306.5,241 281.1,129.8 " style="stroke-opacity: 0;"></polygon> </svg></div>';
                                        $("#chaty-rated-rating").append(ratingStar);
                                    }
                                } else {
                                    $(".rating-modal-steps").removeClass("active");
                                    $(".rating-modal-steps#step-4").addClass("active");
                                    window.open(chaty_rating_settings.review_link, '_blank');
                                    set_review_reminder(-1);
                                }
                            }
                        })
                    }

                    $(document).on("keyup", "#upgrade-review-comment", function () {
                        var commentLength = 1000 - parseInt($.trim($(this).val()).length);
                        if (commentLength < 0) {
                            var userComment = $.trim($(this).val());
                            userComment = userComment.slice(0, 1000);
                            $(".upgrade-review-textarea label span").text(0);
                            $(this).val(userComment);
                        } else {
                            $(".upgrade-review-textarea label span").text(commentLength);
                        }
                    });

                    $(document).on("change", "#upgrade-review-comment", function () {
                        var commentLength = 1000 - parseInt($.trim($(this).val()).length);
                        if (commentLength < 0) {
                            var userComment = $.trim($(this).val());
                            userComment = userComment.slice(0, 1000);
                            $(".upgrade-review-textarea label span").text(0);
                            $(this).val(userComment);
                        } else {
                            $(".upgrade-review-textarea label span").text(commentLength);
                        }
                    });

                    $("#chaty-rating-popup .chaty-popup-outer").on("click", function(e){
                        if($(".rating-modal-steps#step-4").hasClass("active")) {
                            $("#chaty-rating-popup").remove();
                            set_review_reminder(-1);
                        } else {
                            set_review_reminder(14);
                        }
                        $("#chaty-rating-popup").remove();
                    });

                    $("#remind-review-button").on("click", function(){
                        set_review_reminder($("#upgrade-review-reminder").val());
                        $("#chaty-rating-popup").remove();
                    });

                    $(".close-rating-popup").on("click", function(e){
                        e.preventDefault();
                        if($(".rating-modal-steps#step-4").hasClass("active")) {
                            $("#chaty-rating-popup").remove();
                            set_review_reminder(-1);
                        } else if($(".rating-modal-steps#step-3").hasClass("active")) {
                            $("#chaty-rating-popup").remove();
                            set_review_reminder(14);
                        } else {
                            $(".rating-modal-steps").removeClass("active");
                            $(".rating-modal-steps#step-3").addClass("active");
                        }
                    });

                    $("#upgrade-review-button").on("click", function (e) {
                        e.stopPropagation();
                        e.preventDefault();
                        $("#chaty-rating-popup").hide();
                        $.ajax({
                            url: chaty_rating_settings.ajax_url,
                            data: {
                                action: "chaty_review_box_message",
                                rating: $("#chaty-rated-rating .jq-star").length,
                                nonce: chaty_rating_settings.review_box_nonce,
                                message: $.trim($("#upgrade-review-comment").val())
                            },
                            type: "post",
                            success: function () {
                                $("#chaty-rating-popup").remove();
                                set_review_reminder(-1);
                            }
                        });
                    });
                }
            });

            function set_review_reminder(noOfDays) {
                $(".chaty-premio-review-box").remove()
                $.ajax({
                    url: chaty_rating_settings.ajax_url,
                    data: {
                        action: "chaty_review_box",
                        days: noOfDays,
                        nonce: chaty_rating_settings.review_nonce
                    },
                    type: "post",
                });
            }
        }));
    </script>
<?php
}