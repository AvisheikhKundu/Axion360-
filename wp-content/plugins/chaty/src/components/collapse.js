const $ = window.jQuery;
export default function collapse() {
    const $buttons = $('.chaty-targeted-collapse');
    $buttons.on('click', function( ev ){
        ev.preventDefault();
        const id        = this.dataset.target;
        const $target   = $(`#${id}`);
        const $element  = $(this); 

        $target.slideToggle(300, 
            function() {
                if( $target.is(':hidden') ){
                    $element.find('svg').css('transform', 'rotate(0deg)');
                    
                } else {
                    $element.find('svg').css('transform', 'rotate(90deg)');
                }
            }
        )
    })
}