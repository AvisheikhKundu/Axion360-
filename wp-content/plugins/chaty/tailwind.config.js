const defaultTheme = require('tailwindcss/defaultTheme');

module.exports = {
    content: [
        './includes/**/*.php',
        './admin/**/*.php',
        './views/**/*.php',
        './admin/assets/js/*.js',
    ],

    theme: {
        extend: {
            fontFamily: {
                primary: ['Rubik','sans-serif', ...defaultTheme.fontFamily.sans],
            },

            colors: {
                'cht-gray-150'      : '#49687e',
                'cht-gray-100'      : '#F7F8FC',
                'cht-gray-50'       : '#eaeff2',
                'cht-primary'       : '#b78deb',
                'cht-primary-100'   : '#9565d0',
                'cht-primary-50'    : '#e6def3',
                'cht-black'         : '#181749',
                'cht-black-100'     : '#413972',
                'cht-green'         : '#00a65a',
                'cht-red'           : '#dd4b39'
            },

            backgroundImage: {
                'gradient-100': 'linear-gradient(93.92deg, #675cd3 7.55%, #d87abf 95.33%)',
            },

            dropShadow: {
                '3xl': '0px 9px 7px rgba(183, 141, 235, .4)',
                '4xl': '0px 12px 19px rgba(183, 141, 235, .4)',
                '5xl': '0px 28px 28px rgba(0, 0, 0, 0.25)',
                '6xl': '0px 0px 15px rgba(0, 0, 0, 0.1)',
            },
        }
    },

};
