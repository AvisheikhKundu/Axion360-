/**
 * Detect wordpress layout change  
 * implemented HOC to use this same functionility everywhere
 */ 
 let $ = window.jQuery;
 let sidebarWidth      = $('#adminmenuwrap').outerWidth();
 let adminBarHeight    = $('#wpadminbar').outerHeight();
 let headerEnd         = $('.chaty-header').outerHeight();
 const position        = Boolean(window.isRtl) ? 'right' : 'left';
 
 const calculateTop = function() {
     if( innerWidth < 600 ) 
         return ( scrollY <= adminBarHeight ? adminBarHeight - scrollY : 0 ) + 'px';
         return adminBarHeight + 'px';
 }
 
 const calculateHorizontalGap = function() {
     if( innerWidth >= 783 ) 
         return sidebarWidth + 'px';
         return 0;
 }
 
 const calcualteContent = function() {
     if( innerWidth < 640 )
         return (headerEnd || 0) + 20;
         return (headerEnd || 0) + adminBarHeight
 }
 
 export default function withLayoutChange() {
     return {
         onLayoutChange( callback ) {
             
             /**
              * calcualte postion on initial page relad 
              */ 
             callback({
                top     : calculateTop(),
                [position]: calculateHorizontalGap(),
                width   : `calc(100% - ${calculateHorizontalGap()})`,
                content : calcualteContent()
             });
             /**
              * calcualte postion on scroll
              */ 
             onscroll = function() {
                 callback({
                    top     : calculateTop(),
                    [position]: calculateHorizontalGap(),
                    width   : `calc(100% - ${calculateHorizontalGap()})`,
                    content : calcualteContent()
                 });
             }
             
             /**
              * @param eventData contains the sidebar position like "folded, responsive" 
              */ 
             $(document).on( 'wp-menu-state-set wp-collapse-menu', function( event, eventData ) {
                sidebarWidth      = $('#adminmenuwrap').outerWidth();
                adminBarHeight    = $('#wpadminbar').outerHeight();
                headerEnd         = $('.chaty-header').outerHeight();
 
                callback({
                    top     : calculateTop(),
                    [position]: calculateHorizontalGap(),
                    width   : `calc(100% - ${calculateHorizontalGap()})`,
                    content : calcualteContent()
                })
                
             });
         }  
     }
 }