const $ = window.jQuery;
export default function channels() {
    /**
     * bring the newly created channel settings into view 
     */ 
    function bringNewChannelIntoView( target ) {
        const $scope =  $(`#chaty-social-${target}`);
        $scope[0].scrollIntoView({
            behavior: "smooth",
            block: "center"
        })
    }

    /**
     * show pro alert when users tries to use more than 2 channel 
     */ 
    function onChannelLimitExceeded( isExceeded ) {
        const $popover = $('.popover-upgrade-pro');
        if( isExceeded ) {
            // show and shake
            $popover.addClass('flex shake-it').removeClass('hidden');
            // scroll into view
            if( !$popover.isInViewport() ) {
                $popover[0].scrollIntoView({
                    behavior: "smooth",
                    block: "center"
                })
            }
            setTimeout(() =>  $popover.removeClass('shake-it'), 1000);
        } else {
            $popover.removeClass('flex shake-it').addClass('hidden');
        }
    }
    
    /**
     * channel update handler 
     */ 
    function init( props ) {
        const { action, target, channel, isExceeded } = props;
        onChannelLimitExceeded( isExceeded  );

        if( action === 'added' && !isExceeded && target ) {
            bringNewChannelIntoView( target );
        }

        /**
         * If just one channel is selected, 
         * then widget icon, color, default state, and icons view shouldn't appear in steps 2 and 3 
         */ 
        {
            const status = channel.length <= 1 // true | false;
            $('.chaty-widget-color, .chaty-widget-icon, .chaty-default-state, .chaty-icon-view').toggleClass('hidden', status);
        }

    }

    wp.hooks.addAction('chaty.channel_update', 'channelUpdateHandler', init)
}

// call when any channel is removed or updated
// const channel_list = [];
// jQuery('.channels-icons > .icon.active').each( (i, item) => {
//     channel_list.push( item.dataset.social );
// } )
// wp.hooks.doAction('chaty.channel_update', {
//     channel     : channel_list,         // active channel list
//     target      : social,               // channel that removed last
//     action      : 'removed',            // added || removed,
//     isExceeded  : false,
// }); 