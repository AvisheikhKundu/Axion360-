const $ = window.jQuery;
export default function widgetSize() {
    $('.widget-size-control').on('change', function() {

        if( this.type === 'radio' ) {
            $('#custom-widget-size').css({
                display: this.id === 'size-custom' ? 'block' : 'none'
            })

            $('.widget-size-control').prop('checked', false);
            $(this).prop('checked', true);
        }
        
        $('#custom-widget-size-input').val( this.value );
        change_custom_preview();
    })
}

