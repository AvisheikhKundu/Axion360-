const $ = window.jQuery;

export default function colorPicker() {
    const ChatyColorPicker = {
        init() {
            
            this.extendJquery();

            // enable color picker when page is refreshed
            this.trigger( false, {
                $scope  : $(document),
                element : '.chaty-color-field'
            })
    
            // custom event to enable color picker
            $(document).on('chatyColorPicker/trigger', this.trigger.bind(this) )
    
        },
    
        // To manage opening and closing color picker
        STATE: {
            current: null,
            set add( $element ) {
                if( !$element.is( this.current ) && this.current ) {
                    this.current.parent().next().slideUp();
                }
                this.current = $element;
                this.closeAll;
            },
    
            get closeAll() {
                const self = this;
                $('html, .preview-section-chaty').on('click', function( ev ){
                    if( !ev.target.closest('.cht-colorpicker__dropdown') ) {
                        self.current.parent().next().slideUp();
                    }
                })
            }
        },
    
        /**
         *
         * @event chatyColorPicker/trigger
         * this event will be used to enable color picker
         *
         */
         trigger( ev = false, settings ) {
    
            if( ev ) {
                this.eventUtils( ev )
            }
    
            const colors    = ['#202020','#86cd91', '#1E88E5', '#ff6060', '#49E670', '#ffdb5e', '#ff95ee'];
            const $inputEl  = settings.$scope.find( settings.element );
    
            $inputEl.each( (index, input) => {
    
                const $input = settings.$scope.find(input);
    
                // avoid duplicate color picker creation
                if( $input.data('chaty-color-picker') ) {
                    return;
                }
    
                const color     = $input.val() || '#202020';
                const colorHex  = AColorPicker.parseColor(color, "hex")
                const config    = Object.assign({
                    $scope              : settings.$scope,
                    $input              : $input,
                    defaultColor        : color,
                    colors              : colors,
                    defaultColorDarker  : this.colorLuminance( colorHex , -0.1),
                }, this)
    
                config.addReplacer();
                $input.attr('data-chaty-color-picker', true);
            })
    
        },
    
        eventUtils( ev ) {
            ev.preventDefault();
            ev.stopPropagation();
        },
    
        addReplacer() {
            const self = this;
    
            self.$input.css('display', 'none');
            self.$input.after(`
                <div class="cht-colorpicker replacer">
                    <div class="cht-colorpicker__preview">
                        <span class="cht-colorpicker__preview--inner" style="background-color: ${self.defaultColor}; border-color: ${self.defaultColorDarker}"></span>
                    </div>
                    <div class="cht-colorpicker__dropdown">
                        ${self.colorTemplate()}
                    </div>
                </div>
            `);
    
            const $scopeColorPalate = self.$input.parent().find('.cht-colorpicker')
            const $dropdown = $scopeColorPalate.find('.cht-colorpicker__dropdown');
    
            const picker = AColorPicker.createPicker($dropdown, {
                attachTo    : $scopeColorPalate,
                color       : this.defaultColor,
                showAlpha   : true,
                showHSL     : false,
                // showRGB     : false,
            });
    
            self.initalize( $scopeColorPalate );
    
            picker.on("change", function (picker, color) {
                self.onChange.call(self, color, $scopeColorPalate, true)
            });
    
        },
    
        colorTemplate() {
            return `
            <ul class="palate">
                ${this.colors.map( (color, index) => `<li data-color="${color}" ${color === this.defaultColor ? 'class="active"': ''}>
                    <span class="template-color" style="background-color: ${color}"></span>
                </li>` ).join('')}
                <li class="custom-color ${this.colors.includes(this.defaultColor) ? '' : 'active'}">
                    <div>
                        <svg class="pointer-events-none" width="16" height="16" viewBox="0 0 12 12" fill="none" xmlns="http://www.w3.org/2000/svg" svg-inline="" focusable="false" tabindex="-1"><path fill-rule="evenodd" clip-rule="evenodd" d="M7 1a1 1 0 00-2 0v4H1a1 1 0 000 2h4v4a1 1 0 102 0V7h4a1 1 0 100-2H7V1z" fill="currentColor"></path></svg>
                    </div>
                </li>
            </ul>
            `
        },
        /**
         * extend jQuery methods
         */ 
        extendJquery() {
            $.fn.extend({
                premioFixHorizontalPosition() {
                    const left  = this.parent().offset().left
                    const availableWidth = innerWidth - left;
                    if( this.outerWidth() + 40 > availableWidth ) {
                        this.css('right', '0')
                    }
                    return this;
                }
            })
        },
    
        /**
         *
         *
         * @param $scope = $scopeColorPalate
         * manage event listens for
         * - toggling color palate
         * - selecting pre defined color
         * - custom color picking
         */
        initalize( $scope ) {
            const self          = this;
            const $preview      = $scope.find('.cht-colorpicker__preview--inner');
            const $dropdown     = $scope.find('.cht-colorpicker__dropdown');
            const $customColor  = $scope.find('.custom-color');
            const $templateColor= $scope.find('.template-color');
            const $palate       = $scope.find('.palate');
            const $acolorpicker = $scope.find('.a-color-picker');
    
            // show hide color palate by clicking preivew
            $preview.on('click', function( ev ) {
                self.eventUtils( ev );
                $dropdown
                    .premioFixHorizontalPosition()
                    .slideToggle();
                $acolorpicker.hide();
                setTimeout(()=>{
                    $palate.show();
                }, 500)
    
                // setter method: It helps to clsoe already opend color picker
                self.STATE.add = $preview
            })
    
            // select pre defined template color
            $templateColor.on('click', function( ev ){
                self.eventUtils( ev );
    
                $scope.find('li').removeClass('active');
                const $target = jQuery(this).parent()
    
                $target.addClass('active');
                self.onChange.call(self , $target.data('color'), $scope, false )
            })
    
            // show custom color palate
            $customColor.on('click', function(){
                $scope.find('li').removeClass('active');
                jQuery(this).parent().addClass('active')
    
                $palate.hide();
                $acolorpicker.show();
            })
    
        },
        /**
         *
         *
         * @method colorLuminance is used for making the color dark
         * @param hex contains hexa color code
         * @param lum contains the value of color darknesss
         *
         */
        colorLuminance(hex, lum) {
            // validate hex string
            hex = String(hex).replace(/[^0-9a-f]/gi, '');
            if (hex.length < 6) {
                hex = hex[0]+hex[0]+hex[1]+hex[1]+hex[2]+hex[2];
            }
            lum = lum || 0;
            // convert to decimal and change luminosity
            var rgb = "#", c, i;
            for (i = 0; i < 3; i++) {
                c = parseInt(hex.substr(i*2,2), 16);
                c = Math.round(Math.min(Math.max(0, c + (c * lum)), 255)).toString(16);
                rgb += ("00"+c).substr(c.length);
            }
            return rgb;
        },
    
        updatePreviewColor( $scope, color, colorDark ) {
            const $preview  = $scope.find('.cht-colorpicker__preview--inner');
            $preview.css({
                backgroundColor: color,
                borderColor: colorDark
            })
        },
    
        updateCustomPreviewColor( $scope, colorDark ) {
            const $customColor  = $scope.find('.custom-color');
            $customColor.css({
                borderColor: colorDark
            })
        },
    
        updateChannelIconColor({$scope, color, type, channel }) {
            // for chaty and agent icon background color
            jQuery("#chaty_image_"+channel+" .custom-chaty-image").css('background-color', color);
            jQuery("#chaty_image_"+channel+" .facustom-icon").css('background-color', color);
            jQuery("#chaty_image_"+channel+" .color-element").css('fill', color);
        },
    
        updateAgentIconColor({$scope, color, type, channel }) {
            // for chaty and agent icon background color
            console.log("color: "+color);
            console.log("channel: "+channel);
            jQuery("#image_agent_data_agent-"+channel+" .custom-agent-image").css('background-color', color);
            jQuery("#image_agent_data_agent-"+channel+" .facustom-icon").css('background-color', color);
            jQuery("#image_agent_data_agent-"+channel+" .color-element").css('fill', color);
        },
    
        updateAgentUserIconColor({$scope, color, type, channel, agentIndex }) {
            // for chaty and agent icon background color
            jQuery("#image_agent_data_"+channel+"-"+agentIndex+" .custom-agent-image").css('background-color', color);
            jQuery("#image_agent_data_"+channel+"-"+agentIndex+" .facustom-icon").css('background-color', color);
            jQuery("#image_agent_data_"+channel+"-"+agentIndex+" .color-element").css('fill', color);
        },
    
        /**
         *
         *
         * @param color contains rgba/hexa color
         * @param $scope contains jqueryHTMLElement, it's a widget scope
         * @param isCustom Boolean, it's true when custom color used
         *
         */
        onChange( color, $scope, isCustom = false ) {
    
            const colorHex      = AColorPicker.parseColor(color, "hex")
            const colorDark     = isCustom ? this.colorLuminance( colorHex , -0.1) : colorHex
            const $parent       = $scope.parents('.chaty-channel');
            const channel       = $parent.data('channel');
    
            // assign value into input field
            this.$input.val(color).attr('value', color);
            // update preview color
            this.updatePreviewColor( $scope, color, colorDark );
            // update custom preview border color
            if( isCustom ) {
                this.updateCustomPreviewColor( $scope, colorDark );
            }
    
    
            // for chaty icon background color
            if(this.$input.hasClass("chaty-bg-color")) {
                console.log("color: "+color);
                console.log("channel: "+channel);
                this.updateChannelIconColor({
                    type: 'chaty-bg-color', // input class
                    $scope: jQuery(`.custom-image-${channel}`).parent(),
                    color: color,
                    channel: channel
                });
            }
    
            if(this.$input.hasClass("agent-bg-color")) {
                this.updateAgentIconColor({
                    type: 'agent-bg-color', // input class
                    $scope: jQuery(`.custom-image-${channel}`).parent(),
                    color: color,
                    channel: channel
                });
            }
    
            if(this.$input.hasClass("agent-icon-color")) {
                const agentIndex = $scope.parents('.agent-channel-setting').data("item");
                this.updateAgentUserIconColor({
                    type: 'agent-icon-color', // input class
                    $scope: jQuery(`.custom-image-${channel}`).parent(),
                    color: color,
                    channel: channel,
                    agentIndex: agentIndex
                });
            }
    
            // for agent icon background color
            // this.updateIconColor({
            //     type    : 'agent-bg-color', // input class
            //     $scope  : jQuery(`.custom-agent-image-${channel}`).parent(),
            //     color   : color,
            // })
            //
            // // agent header background color
            // this.updateIconColor({
            //     type    : `agent_head_bg_color_${channel}`, // input id
            //     $scope  : jQuery(document),
            //     channel : channel
            // })
            //
            // // agent header text color
            // this.updateIconColor({
            //     type    : `agent_head_text_color_${channel}`, // input id
            //     $scope  : jQuery(document),
            //     channel : channel
            // })
    
            change_custom_preview();
    
        }
    
    }

    ChatyColorPicker.init()
}