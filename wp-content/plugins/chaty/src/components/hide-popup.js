const $ = window.jQuery;
export default function hidePopup() {
    $(".close-chaty-popup-btn").on("click", function(e){
        e.stopPropagation();
        $(".chaty-popup").hide();
        if($(this).hasClass("channel-setting-btn")) {
            $("#chaty-social-channel").trigger("click");
            $(window).scrollTop($("#channels-selected-list").offset().top - 120);
        }
    })
}