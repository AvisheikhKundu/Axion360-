const $ = window.jQuery;

export default function settingsButton() {
    $('.chaty-settings').on('click', function( ev ){
        
        ev.preventDefault();
        ev.stopPropagation();

        $(this).toggleClass('enable');

        const $scope    = $(this).parents('.chaty-channel');
        const scrollTop = $(window).scrollTop();
        const distance  = $scope.offset().top - scrollTop - 130;
        window.scrollBy({
            top: distance,
            left: 0,
            behavior: 'smooth'
        });
    })
}