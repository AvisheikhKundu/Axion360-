const $ = window.jQuery;
export default function chatwayChannel() {
    const handler = {
        init() {
            this.$chatwayPosition       = 'above-chaty';
            $(document).on("click", "#add-chatyway", function (e){
                e.preventDefault();
                clearClasses();
                $("#chatyway-info-popup").show();
                $("#chatyway-info-popup .chaty-popup-inner").addClass('step-1');
            });

            $(document).on("click", ".close-chatway-notice", function (e){
                e.preventDefault();
                $(".chatway--notice").remove()
            })

            $(document).on("click", "#add-live-channel", function (e){
                clearClasses();
                if($(".has--no--widgets").length) {
                    $("#chatyway-info-popup .chaty-popup-inner").addClass('step-1');
                    $("#chatyway-info-popup").hide();
                } else {
                    $("#chatyway-info-popup .chaty-popup-inner").addClass('step-2');
                }
            });

            $(document).on("click", "#change-chatway-position", function (e){
                $("#chatway-position-settings").toggleClass("hidden");
            });

            $(document).on("change","#chatway-position-settings input[name='chatway_position']:checked", function(){
                startAnimation()
            });

            $(document).on("click", ".close-chatway-notice", function(e){
                e.preventDefault();
                $(".chatway-notice").addClass('hidden');
            });

            $(document).on("click", "#go-to-first-step", function(e){
                e.preventDefault();
                $("#chaty-social-channel").trigger('click');
            });

            $(document).on("click", "#add-live-chat-btn", function(e){
                e.preventDefault();
                $("#add-chatyway").trigger('click');
            });

            $(document).on("click", "#add-chatway-channel", function(e){
                e.preventDefault();
                $("#chatyway-info-popup").hide();
            });

            $(document).on("click", "#open-widget-list", function(e){
                e.stopPropagation();
                e.preventDefault();
                $(".dropdown--list").slideToggle(200);
            });

            $(document).on("click", "body, html", function(e){
                $(".dropdown--list").slideUp(200);
            })

            $(document).on("click", ".dropdown--list a", function(e){
                e.stopPropagation();
                e.preventDefault();
                let widgetId = $(this).attr("data-widget");
                $("#add_chatway_to_widget").val(widgetId);
                $("#chatyway-info-popup").show();
                $(".dropdown--list").slideUp(200);
            })
        },
    };

    const addChatwayChannel = function(e) {
        clearClasses()
        $("#chatyway-info-popup .chaty-popup-inner").addClass('step-2')
    };
    const clearClasses = function (){
        $("#chatyway-info-popup .chaty-popup-inner").removeClass(['step-1','step-2','step-3'])
    };

    const closeChatwayNotice = function() {

    }
    handler.init();
}