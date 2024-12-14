const $ = window.jQuery;
export default function ruleButtonHandler() {
    $('.create-rule').on('click', function() {
        const $parent = $(this).parents('.chaty-option-box');
        $parent.addClass('show-remove-rule-button');
    })

    $('.remove-rules').on('click', function() {
        const $parent = $(this).parents('.chaty-option-box');
        $parent.removeClass('show-remove-rule-button');
    })
}