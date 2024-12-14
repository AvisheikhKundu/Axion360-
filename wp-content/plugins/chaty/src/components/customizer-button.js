const $ = window.jQuery;
export default function customizeButton() {

    $(document).on('click', '.customize-agent-button', function() {
        const $scope = $(this).parents('.chaty-channel');
        $scope.find('.customize-agent-button, .agent-button-action').toggleClass('enable');
        $scope.find('.chaty-channel-main-settings').slideToggle(200);
    });

    $(document).on('click', '.agent-channel-setting-button', function(){
        const $scope = $(this).parents('.agent-channel-setting');
        $scope.find('.agent-channel-setting-advance').slideToggle(200)
        $(this).toggleClass('enable');
    })
    
}