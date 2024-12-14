<?php
/**
 * Contact form leads
 *
 * @author  : Premio <contact@premio.io>
 * @license : GPL2
 *
 * */

if (defined('ABSPATH') === false) {
    exit;
}

$data = [
    'logo'          => CHT_PLUGIN_URL . 'admin/assets/images/logo-color.svg',
    'upgrade_url'   => $this->getUpgradeMenuItemUrl(),
    'upgrade_text'  => esc_html__('Upgrade to Pro', 'chaty'),
    'title'         => esc_html__( 'Create a new Chaty widgets for your website. What can you use it for?', 'chaty' ),
    'features'      => array(
        [
            'logo'  => CHT_PLUGIN_URL . "admin/assets/images/pro-devices.png",
            'title' => esc_html__("Create separate designs for desktop and mobile", "chaty"),
            'desc'  => esc_html__("E.g. the mobile version can have a bigger widget, in a different color and a different position", "chaty")
        ],
        [
            'logo'  => CHT_PLUGIN_URL . "admin/assets/images/pro-language.png",
            'title' => esc_html__("Create separate designs for desktop and mobile", "chaty"),
            'desc'  => esc_html__("You can show different form and buttons based on URL (E.g. WhatsApp message to a French number and call your French phone number)", "chaty")
        ],
        [
            'logo'  => CHT_PLUGIN_URL . "admin/assets/images/pro-widget.png",
            'title' => esc_html__("Show separate widgets for different products", "chaty"),
            'desc'  => esc_html__("On your website (e.g. you can show the Facebook Messenger channel for products in the yourdomain.com/high-end/* category)", "chaty")
        ],
        [
            'logo'  => CHT_PLUGIN_URL . "admin/assets/images/pro-page.png",
            'title' => esc_html__("Display different channels for your landing pages", "chaty"),
            'desc'  => esc_html__("This way you can track the results better and have the right person assign to the relevant channel.", "chaty")
        ],
        [
            'logo'  => CHT_PLUGIN_URL . "admin/assets/images/pro-support.png",
            'title' => esc_html__("Show one widget on your support and contact pages", "chaty"),
            'desc'  => esc_html__("and a different widget on your sales pages.", "chaty")
        ],
        [
            'logo'  => CHT_PLUGIN_URL . "admin/assets/images/pro-chat.png",
            'title' => esc_html__("Display different call-to-action messages", "chaty"),
            'desc'  => esc_html__("for different pages on your website or separate call-to-action messages for mobile and desktop", "chaty")
        ]
    )
]

?>
<div class="container mt-6">
    <a href="<?php echo esc_url( $this->getDashboardUrl() ) ?>">
        <img src="<?php echo esc_url($data['logo']); ?>" alt="Chaty" class="logo">   
    </a>
    <header class="flex py-4 flex-col items-start sm:flex-row sm:justify-between">
        <h2 class="font-primary text-cht-gray-150 text-[26px] font-semibold">
            <?php echo esc_attr($data['title']) ?>
        </h2>
        <a class="btn text-base mt-3 sm:mt-0 rounded-lg font-normal border-cht-primary bg-cht-primary text-white drop-shadow-3xl" href="<?php echo esc_url($data['upgrade_url']); ?>">
            <?php echo esc_attr($data['upgrade_text']) ?>
        </a>
    </header>
    <main>
        <div class="chaty-new-widget-row">
            <ul class="grid grid-cols-1 sm:grid-cols-3 gap-5 mt-2">
                <?php foreach( $data['features'] as $item ): ?>
                <li class="bg-white shadow-cht-gray-150/10 rounded-xl border border-solid border-gray-300 overflow-hidden">
                    <div class="p-5 border-b-4 border-cht-primary h-full">
                        <img class="mb-5" src="<?php echo esc_url($item['logo']) ?>"/>
                        <h2 class="text-cht-gray-150 pb-3 font-semibold font-primary text-[18px]"><?php echo esc_attr($item['title']) ?></h2>
                        <p class="text-sm font-primary font-normal text-[#49687ECC]"><?php echo esc_attr($item['desc']) ?></p>
                    </div>
                </li>
                <?php endforeach; ?>
            </ul>
        </div>
    </main>
</div>
