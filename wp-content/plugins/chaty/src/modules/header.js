import withLayoutChange from "../hoc/with-layout-change";
import withRoute from "../hoc/with-route";
import saveButton from '../components/save-button';
import compose from '../compose';

const $ = window.jQuery;
function headerModule( props ) {

    const $header       = $('.chaty-header');
    const $widgetBody   = $('#chaty-widget-body-tab');
    const $channels     = $('#chaty-social-channel');
    const $backButton   = $('.back-button');
    const $nextButton   = $('.next-button');
    const $chatyTab     = $('.chaty-widget-tab');
    const $stepTitle    = $('#process-step');
    const $currentStep  = $('#current-step');
    const $stepProgress = $('#step-progress');
    const $currentStepInput = $('#current_step');
    const tabList       = ['chaty-tab-social-channel', 'chaty-tab-customize-widget', 'chaty-tab-triger-targeting', 'chaty-tab-chatway'];
    let activeTab       = Number( props.route.get('step') || 0 );

    if( $header.length === 0 || $channels.length === 0 ) return;

    /**
     * on wordpress sidebar change, change the header position 
     * @props style = { left: value, top: value, width: value }
     */ 
    props.onLayoutChange( style => {
        $header.css(style);
        $widgetBody.css('margin-top', `${style.content}px`)
    })

    /**
     * Show tab 
     */ 
    const showTab = index => {

        if( index < tabList.length && index >= 0 ) {
            activeTab = index;
            // active the tab content
            $('.social-channel-tabs').removeClass('active');
            $(`#${tabList[index]}`).addClass('active');

            //active tab label or header
            $('.chaty-tab').removeClass('active completed').each(function(){
                $(this).addClass('completed');
                if( this.dataset.tabId === tabList[index] ) {
                    $(this).addClass('active');
                    return false;
                }
            })

            $currentStep.text(`${index+1}/4`)
            $stepTitle.text(setStepTitle(index))
            $currentStepInput.val(index)

            //next and back button show/hide
            $backButton.removeClass('cht-disable');
            $nextButton.removeClass('cht-disable');
            const $progress = strokeFullProgress() - ((index+1) / 4) * strokeFullProgress();
            $stepProgress.css({strokeDashoffset: $progress})

            if( index <= 0 ) {
                $backButton.addClass('cht-disable');
            } 

            if( index >= (tabList.length - 1) ) {
                $nextButton.addClass('cht-disable');
            }

            $chatyTab.removeClass(['step-0','step-1','step-2','step-3'])
            $chatyTab.addClass(`step-${index}`)

            // update url
            const locationURL = new URL(window.location.href);
            const search_params = locationURL.searchParams;

            // new value of "id" is set to "101"
            search_params.set('step', index);

            // change the search property of the main url
            locationURL.search = search_params.toString();

            // the new url string
            const new_url = locationURL.toString();
            window.history.replaceState({page_id: index}, '', new_url);
        }
    }

    function strokeFullProgress() {
        return 46.5 * 2 * Math.PI
    }

    const setStepTitle = index => {
        if(index == 0) {
            return "Select channels";
        } else if(index == 1) {
            return "Widget customization";
        } else if(index == 2) {
            return "Triggers and targeting";
        }
        return "Add live Chat";
    }

    /**
     * bring content into view
     */
    showTab( activeTab )
    $header.find('.chaty-tab').on('click', function(){
        // show tab setter method takes only the index of the tab 
        showTab( tabList.indexOf(this.dataset.tabId) );
        $widgetBody.removeClass(["step-0", "step-1", "step-2", "step-3"]);
        $widgetBody.addClass('step-'+tabList.indexOf(this.dataset.tabId))
        if( $header.css('position') === 'fixed' ) {
            window.scrollTo({
                top: ( innerWidth > 768 ? $header.outerHeight() : 0 ) + 32 + 'px',
                left: 0,
                behavior: 'smooth'
            });   
        } 
    })

    /**
     * Next button handler
     */ 
    $nextButton.on('click', ()=>{
        showTab( activeTab + 1 );
    })
    /**
     * Prev button handler
     */ 
    $backButton.on('click', () => {
        showTab( activeTab - 1 );
    })

    // save button handler
    saveButton();
}

export default compose( 
    withLayoutChange(),
    withRoute()
)( headerModule )