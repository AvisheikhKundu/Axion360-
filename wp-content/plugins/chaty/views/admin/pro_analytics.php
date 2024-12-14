<?php
/**
 * Chaty Analytics Pro Feature
 *
 * @author  : Premio <contact@premio.io>
 * @license : GPL2
 * */

if (defined('ABSPATH') === false) {
    exit;
}
?>

<link type="text/css" rel="stylesheet" href="https://fonts.googleapis.com/css?family=Lato:ital,wght@0,100;0,300;0,400;0,700;0,900;1,100;1,300;1,400;1,700;1,900&display=swap" />
<div class="container chaty-widgetanalytic-wrap wrap">
    <h2></h2>
    <div class="chaty-widgetanalytic-body">
        <div class="px-7 py-8 flex-1">
            <h2 class="chaty-widgetanalytic-heading"><?php printf(esc_html__("Unlock Chaty %s 🚀", "chaty"), "<span>".esc_html__("Analytics", "chaty")."</span>") ?></h2>

            <div class="flex items-center mt-5 space-x-3">
                <a class="btn rounded-lg drop-shadow-3xl font-normal" href="<?php echo esc_url($this->getUpgradeMenuItemUrl()); ?>" >
                    <?php esc_html_e('Upgrade to Pro 🚀', 'chaty'); ?>
                </a>
            </div>

            <div class="chaty-licenseimage">
                <img class="h-full w-auto" src="<?php echo esc_url(CHT_PLUGIN_URL) ?>admin/assets/images/analytics-image.png" alt="Chaty analytics" />
            </div>

            <h3><?php esc_html_e( 'What can you use it for?', 'chaty');?></h3>
            <ul class="mt-7 flex flex-col space-y-2">
                <li class="flex items-center py-6 px-7 bg-[#F9FAFB] rounded-md space-x-6 text-cht-gray-150 text-lg font-primary">
                    <img width="42" height="59" src="<?php echo esc_url(CHT_PLUGIN_URL) ?>admin/assets/images/channel-discover.svg" alt="Channel Discover">
                    <span class="max-w-[305px]"><?php printf(esc_html__("%s the most frequently used channels", "chaty"), "<strong>".esc_html__("Discover", "chaty")."</strong>") ?></span>
                </li>
                <li class="flex items-center py-6 px-7 bg-[#F9FAFB] rounded-md space-x-6 text-cht-gray-150 text-lg font-primary">
                    <img width="42" height="59" src="<?php echo esc_url(CHT_PLUGIN_URL) ?>admin/assets/images/channel-tracking.svg" alt="Channel Tracking">
                    <span><?php printf(esc_html__("Keep %s of how each widget performs", "chaty"), "<strong>".esc_html__("track", "chaty")."</strong>")  ?></span>
                </li>
                <li class="flex items-center py-6 px-7 bg-[#F9FAFB] rounded-md space-x-6 text-cht-gray-150 text-lg font-primary">
                    <img width="42" height="59" src="<?php echo esc_url(CHT_PLUGIN_URL) ?>admin/assets/images/channel-analyze.svg" alt="Channel Analyze">
                    <span><?php printf(esc_html__("%s the number of unique clicks and the %s", "chaty"), "<strong>".esc_html__("Analyze", "chaty")."</strong>", "<strong>".esc_html__("click-through rate", "chaty")."</strong>")  ?></span>
                </li>
            </ul>

            <div class="flex items-center mt-5 space-x-3">
                <a class="btn rounded-lg drop-shadow-3xl font-normal" href="<?php echo esc_url($this->getUpgradeMenuItemUrl()); ?>" >
                    <?php esc_html_e('Upgrade to Pro 🚀', 'chaty'); ?>
                </a>
            </div>
        </div>

    </div>
</div>

<style>
    /* Widget analytics page design */
    .chaty-widgetanalytic-body {
        display: flex;
        justify-content: space-evenly;
    }
    .chaty-widgetanalytic-body .px-7.py-8.flex-1 h2.chaty-widgetanalytic-heading {
        font-family: 'Lato';
        font-style: normal;
        font-weight: 800;
        font-size: 36px !important;
        line-height: 48px;
        text-align: center;
        color: #000000;
        display: block;
        margin: 40px auto;
        display: Block;
        justify-content: center;
        align-items: end;
        max-width: 500px;
    }

    .chaty-widgetanalytic-body .px-7.py-8.flex-1 h2.chaty-widgetanalytic-heading span{
        color: #3C85F7;
        font-size: 36px !important;
        font-weight: 800;
    }


    .chaty-widgetanalytic-body .px-7.py-8.flex-1 h3{
        font-family: 'Lato';
        font-style: normal;
        font-weight: 600;
        font-size: 32px;
        line-height: 29px;
        color: #000000;
        text-align: center;
        margin:20px 0 16px;
    }

    .chaty-widgetanalytic-heading {
        font-size: 35px !important;
    }

    .chaty-widgetanalytic-body ul.mt-7.flex.flex-col.space-y-2 {
        display: flex;
        flex-direction: column;
        margin-top: 1.75rem;
        align-items: baseline;
    }

    .chaty-widgetanalytic-body img {
        height: auto;
        max-width: 100%;
        display: block;
        vertical-align: middle;
    }

    .chaty-widgetanalytic-body li {
        flex-direction:column;
        padding:26px 35px 26px 35px;
        box-sizing: border-box;
        width: 282px;
        height: 153.26px;
        background: #FFFFFF;
        border-top: 2px solid #DFDFFC;
        box-shadow: 0px 8px 24px rgba(0, 0, 0, 0.08);
        border-radius: 16px;
        margin: 0px 10px 20px 10px;

        display:flex;
        font-size: 1.125rem;
        line-height: 1.75rem;
        align-items: center;

    }
    .chaty-widgetanalytic-body ul.mt-7.flex.flex-col.space-y-2 {
        flex-direction:column;
        flex-flow:wrap;
        margin-bottom: 1.75rem;
    }

    .chaty-widgetanalytic-body .mt-5{
        text-align:center;
        border-radius:8px;
        margin-top:3.25rem;
        margin-bottom:2.25rem;
    }

    .chaty-widgetanalytic-body span{
        font-family: 'Lato';
        font-style: normal;
        font-weight: 400;
        font-size: 14px;
        line-height: 17px;
        text-align: center;
        margin-top:20px;
        margin-left: 0px;
        color: #000000;
        max-width: 405px;
    }

    .chaty-widgetanalytic-body a.btn.rounded-lg.drop-shadow-3xl.font-normal{
        padding: 15px 45px;
        font-size:20px;
        text-align:center;
        font-weight: 400;
        border-radius: 0.5rem;
        background-color: #3c85f7;
        color: #fff;
        text-decoration-line: none;
        line-height: 1.25rem;
        --tw-drop-shadow: drop-shadow(0px 9px 7px rgba (60 133 247 /0.37%));
        border: 1px solid #3c85f7;
        outline: none;
        box-shadow: none;
        transition: all 0.2s linear;
    }
    .chaty-widgetanalytic-body a.btn.rounded-lg.drop-shadow-3xl.font-normal:hover {
        box-shadow: rgba(60, 133, 247, 0.25) 0px 8px 24px;
    }

    .chaty-widgetanalytic-body ul li img{
        width:auto;
        height:48px;
    }

    .chaty-widgetanalytic-body img.h-full.w-auto{
        display: flex;
        margin: 0 auto;
        justify-content: center;
        align-items: center;
        width: auto;
        height:100%;
    }


    .chaty-widgetanalytic-body .px-7.py-8.flex-1 h2.chaty-widgetanalytic-heading img{
        /*float:right;*/
        margin-top: 8px !important;
    }

    @media screen and (max-width: 768px){
        .chaty-widgetanalytic-body .px-7.py-8.flex-1 h2.chaty-widgetanalytic-heading span,
        .chaty-widgetanalytic-body .px-7.py-8.flex-1 h2.chaty-widgetanalytic-heading{
            font-size: 28px;
        }
        .chaty-widgetanalytic-body li {
            margin: 0px 20px 20px 20px;
        }
    }
</style>
