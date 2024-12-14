import './utils/extend';
import headerModule from './modules/header';
import preview from './modules/preview';
import colorPicker from './modules/color-picker';
import activeWidgetHandler from './components/active-widget';
import customizeButton from './components/customizer-button';
import hidePopup from './components/hide-popup';
import settingsButton from './components/settings-button';
import widgetSize from './components/widget-size';
import collapse from './components/collapse';
import channels from './modules/channels';
import ruleButtonHandler from './components/rule-button';
import chatwayChannel from './modules/chatway';

jQuery(function(){
    headerModule();
    preview();
    colorPicker();
    activeWidgetHandler();
    customizeButton();
    hidePopup();
    settingsButton();
    widgetSize();
    collapse();
    channels();
    ruleButtonHandler();
    chatwayChannel();
})