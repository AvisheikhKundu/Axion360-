const $ = window.jQuery;
export default function preview() {
    const handler = {

        init() {
            this.$previewBtn    = $('.preview-help-btn');
            this.$asidePreview  = $('.preview-section-chaty');
    
            this.resizeHandler();                                           /* set button position on initial page refresh */
            this.$previewBtn.on('click', this.showPreview.bind(this));      /* show preview on clicking preview button */
            this.$asidePreview.on('click', this.removePreview.bind(this));  /* remove preview on clicking overlay */
            $(window).resize(this.resizeHandler.bind(this));                /* set button position on page resize */
        },
    
        showPreview( ev ) {
            ev.preventDefault();
            ev.stopPropagation();
    
            this.$asidePreview
                .removeClass('pb-20 hidden')
                .addClass('fixed top-0 left-0 flex items-center justify-center w-full h-screen bg-black/70')
                .css('z-index', 9999999)
                .attr('data-show', 1);
    
            this.$asidePreview.find('.preview')
                .removeClass('sticky')
                .css('max-width', '350px');
    
            return;
        },
    
        removeHandler() {
            this.$asidePreview
                .addClass('pb-20 hidden')
                .removeClass('fixed top-0 left-0 flex items-center justify-center w-full h-screen bg-black/70')
                .removeAttr('style')
                .attr('data-show', 0);
    
            this.$asidePreview.find('.preview')
                .addClass('sticky')
                .removeAttr('style');
        },
    
        removePreview( ev ) {
            if( ev && !ev.target.closest('.preview') && this.$asidePreview.attr('data-show') == 1 ) {
                this.removeHandler();
            }
        },
    
        position() {
            const $contaienr = $('#chaty-widget-body-tab');
            // return if container does not exists
            if( $contaienr.length === 0 ) return;
    
            const offset     = $contaienr.offset();
            const width      = jQuery(document).width();
            return {
                centerY : window.innerHeight / 2,
                left    : offset.left,
                right   : width - ( offset.left + $contaienr.outerWidth() ),
                width   : width,
                containerWidth: $contaienr.outerWidth(),
            }
        },
    
        resizeHandler() {
            // return if position property does not exists
            if( !this.position() ) return;
            const { centerY, right, width } = this.position();
    
            if( width <= 1024 ) {
                this.$previewBtn.css({
                    top         : centerY + 'px',
                    right       : 0,
                    transform   : 'rotate(-90deg) translateX(137%)',
                    opacity     : 1,
                    zIndex      : 999999,
                })
    
                this.$asidePreview.addClass('hidden');
    
            } else {
                this.removeHandler();
                this.$asidePreview.removeClass('hidden');
                this.$previewBtn.css({
                    opacity     : 0,
                })
            }
        }
    
    }

    handler.init();
}