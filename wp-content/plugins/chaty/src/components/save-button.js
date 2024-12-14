const $ = window.jQuery;
export default function saveButton() {
    const arrowBtn = $('.save-button-container .arrow-btn');
    arrowBtn.on('click', function() {
        const $saveDashboardBtn = $('.save-dashboard-button');
        const $footer           = $('.footer-buttons');
        const footerOffset      = $footer.offset();
        const buttonOffset      = $(this).offset();
        const top               = buttonOffset.top - footerOffset.top + 45;
        const left              = buttonOffset.left - footerOffset.left + 40;

        if( $(this).attr('data-click-state') == 1 ) {
            $(this).attr('data-click-state', 0).removeClass('active');
            $saveDashboardBtn.css({
                display: 'none',
            })
        } else {
            $(this).attr('data-click-state', 1).addClass('active');
            $saveDashboardBtn.css({
                position: 'absolute',
                left: left + 'px',
                top: top + 'px',
                display: 'inline-block',
                transform: 'translateX(-100%)'
            })
        }

        return false;
    })

    $(window).on('click', ev => {
        if( $('.arrow-btn.active') ) {
            const $saveDashboardBtn = $('.save-dashboard-button');
            $saveDashboardBtn.css({
                display: 'none',
            })
            $('.arrow-btn.active').attr('data-click-state', 0).removeClass('active');
        }
    })
}