const $ = window.jQuery;
export default function activeWidgetHandler( button = null ) {
    if($('#active_widget').length) {
        const $triggerBlockWrapper  = $('.trigger-block-wrapper');
        const $disableWidetAlert    = $('.widget-disable-alert');
        const $activeWidgetButton   = $('#active_widget');

        if (button === null) {
            button = $activeWidgetButton[0];
        }

        if (button.checked) {
            $triggerBlockWrapper.show();
            $disableWidetAlert.hide();
        } else {
            $triggerBlockWrapper.hide();
            $disableWidetAlert.show();
        }

        $('#active_widget').on('change', function () {
            activeWidgetHandler(this);
        });
    }
}