const DiscountNotice = () =>
{
    return (
        <div class="omnisend-custom-notice omnisend-notice">
            <img src="/wp-content/plugins/omnisend/assets/img/omnisend-notice-discount-icon.svg" />

            <div>Get 30% off Omnisend for 6 months with code <span class="omnisend-notice omnisend-custom-notice-strong-text">ONLYHOSTINGER30</span></div>
            <a href="https://app.omnisend.com/registrationv2?utm_source=wordpress_plugin&utm_medium=banner_discount" target="_blank" class="omnisend-notice omnisend-custom-notice-discount-button">Get Omnisend discount</a>
        </div>
    );
};

export default DiscountNotice;
