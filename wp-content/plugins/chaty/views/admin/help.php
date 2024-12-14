<?php
if (! defined('ABSPATH')) {
    exit;
}
?>
<style>
    .chaty-help-btn,.chaty-help-form{position:fixed;z-index:1001;display:none}.chaty-help-btn{display:block!important;right:20px;bottom:20px}.chaty-help-btn a{box-shadow:0 0 5px rgb(0 0 0 / 20%);display:block;border:3px solid #fff;width:50px;height:50px;-webkit-border-radius:50%;-moz-border-radius:50%;border-radius:50%;position:relative}.chaty-help-btn a img{width:100%;height:auto;display:block;-webkit-border-radius:50%;-moz-border-radius:50%;border-radius:50%}.chaty-help-form{right:85px;border:1px solid #e9edf0;bottom:25px;background:#fff;-webkit-border-radius:10px;-moz-border-radius:10px;border-radius:10px;width:320px;direction:ltr;opacity:0;transition:.4s;-webkit-transition:.4s;-moz-transition:.4s}.chaty-help-form.active{opacity:1;pointer-events:inherit;display:block}.chaty-help-header{background:#f4f4f4;border-bottom:1px solid #e9edf0;padding:5px 20px;-webkit-border-radius:10px;-moz-border-radius:10px;border-radius:10px 10px 0 0;font-size:16px;text-align:right}.chaty-help-header b{float:left}.chaty-help-content{margin-bottom:10px;padding:20px 20px 10px}.chaty-help-form p{margin:0 0 1em}.folder-form-field{margin-bottom:10px}.folder-form-field input,.folder-form-field textarea{min-height:1px;line-height:1.4;-webkit-border-radius:5px;-moz-border-radius:5px;border-radius:5px;padding:5px 10px;display:block;width:100%;box-sizing:border-box;border:1px solid #c5c5c5}.folder-form-field textarea{width:100%;height:100px;margin-bottom:0}.chaty-help-button{border:none;padding:8px 0;width:100%;background:#ff6624;color:#fff;border-radius:18px;cursor:pointer}.chaty-help-form .error-message{font-weight:400;font-size:14px;display:block}.chaty-help-form input.input-error,.chaty-help-form textarea.input-error{border-color:#dc3232}.chaty-help-btn span.tooltiptext{position:absolute;background:#000;font-size:12px;color:#fff;top:-35px;width:140%;text-align:center;left:-20%;border-radius:5px;direction:ltr}p.error-p,p.success-p{margin:0;font-size:14px;text-align:center}.chaty-help-btn span.tooltiptext:after{bottom:-10px;content:"";transform:translateX(-50%);height:10px;width:0;border-width:10px 5px 0;border-style:solid;border-color:#000 transparent transparent;left:50%;position:absolute}p.success-p{color:green}p.error-p{color:#dc3232}html[dir=rtl] .chaty-help-btn{left:20px;right:auto}html[dir=rtl] .chaty-help-form{left:85px;right:auto}.folder-popup-body h3{line-height:24px}.folder-popup-overlay .form-control input{width:100%;margin:0 0 15px}body.plugins-php .tooltiptext{display:none}.help-form-footer{text-align:center}.help-form-footer p{margin:0;padding:0}.help-form-footer p+p{margin:0;padding:10px 0}.chaty-form-response p{margin:0;padding:8px 0}
</style>
<div class="chaty-help-form">
    <form action="<?php echo esc_url(admin_url('admin-ajax.php')) ?>" method="post" id="chaty-help-form">
        <div class="chaty-help-header">
            <b>Gal Dubinski</b> ><?php esc_html_e("Co-Founder at Premio", "chaty") ?><
        </div>
        <div class="chaty-help-content">
            <p><?php esc_html_e("Hello! Are you experiencing any problems with Chaty? Please let me know :)", 'chaty'); ?></p>
            <div class="folder-form-field">
                <input type="text" name="user_email" id="user_email" placeholder="<?php esc_html_e("Email", 'chaty'); ?>">
            </div>
            <div class="folder-form-field">
                <textarea type="text" name="textarea_text" id="textarea_text" placeholder="<?php esc_html_e("How can I help you?", 'chaty'); ?>"></textarea>
            </div>
            <div class="form-button">
                <button type="submit" class="chaty-help-button" ><?php esc_html_e("Chat") ?></button>
                <input type="hidden" name="action" value="wcp_admin_send_message_to_owner"  >
                <input type="hidden" id="nonce" name="nonce" value="<?php echo esc_attr(wp_create_nonce('chaty_send_message_to_owner')) ?>"  >
            </div>
        </div>
        <div class="help-form-footer">
            <p><?php esc_html_e("Or", 'chaty'); ?></p>
            <p><a href="https://premio.io/help/chaty/?utm_source=pluginspage" target="_blank"><?php esc_html_e("Visit our Help Center >>", 'chaty'); ?></a></p>
        </div>
    </form>
    <div class="chaty-form-response"></div>
</div>
<div class="chaty-help-btn">
    <!-- Free/Pro Only URL Change -->
    <a class="chaty-help-tooltip" href="javascript:;"><img src="<?php echo esc_url(CHT_PLUGIN_URL."admin/assets/images/premio-owner.png") ?>" alt="<?php esc_html_e("Need help?", 'chaty'); ?>"  /></a>
    <?php
    $option = get_option("hide_chaty_cta");
    if ($option !== "yes") { ?>
        <span class="tooltiptext"><?php esc_html_e("Need help?", "chaty") ?></span>
    <?php } ?>
</div>
<script>
    jQuery(document).ready(function(){
        jQuery("#chaty-help-form").submit(function(){
            jQuery(".chaty-help-button").attr("disabled",true);
            jQuery(".chaty-help-button").text("<?php esc_html_e("Sending Request...", "chaty") ?>");
            formData = jQuery(this).serialize();
            jQuery.ajax({
                url: "<?php echo esc_url(admin_url('admin-ajax.php')) ?>",
                data: formData,
                type: "post",
                success: function(responseArray){
                    jQuery("#chaty-help-form").find(".error-message").remove();
                    jQuery("#chaty-help-form").find(".input-error").removeClass("input-error");
                    if(responseArray.error == 1) {
                        jQuery(".chaty-help-button").attr("disabled",false);
                        jQuery(".chaty-help-button").text("<?php esc_html_e("Chat", 'chaty'); ?>");
                        for(i=0;i<responseArray.errors.length;i++) {
                            jQuery("#"+responseArray.errors[i]['key']).addClass("input-error");
                            jQuery("#"+responseArray.errors[i]['key']).after('<span class="error-message">'+responseArray.errors[i]['message']+'</span>');
                        }
                    } else if(responseArray.status == 1) {
                        jQuery(".chaty-help-button").text("<?php esc_html_e("Done!", 'chaty'); ?>");
                        setTimeout(function(){
                            jQuery("#user_email").val("");
                            jQuery("#textarea_text").val("");
                            jQuery("#chaty-help-form").hide();
                            jQuery(".chaty-help-header").hide();
                            jQuery(".help-form-footer").hide();
                            jQuery(".chaty-form-response").html("<p class='success-p'><?php esc_html_e("Your message is sent successfully.", 'chaty'); ?></p>");
                        },1000);
                    } else if(responseArray.status == 0) {
                        jQuery("#chaty-help-form").hide();
                        jQuery(".chaty-help-header").hide();
                        jQuery(".help-form-footer").hide();
                        jQuery(".chaty-form-response").html("<p class='error-p'><?php printf(esc_html__("There is some problem in sending request. Please send us mail on %1\$s", 'chaty'), "<a href='mailto:contact@premio.io'>contact@premio.io</a>"); ?></p>");
                    }
                }
            });
            return false;
        });
        jQuery(".chaty-help-tooltip").click(function(e){
            e.stopPropagation();
            jQuery("#chaty-help-form").show();
            jQuery(".chaty-help-header").show();
            jQuery(".help-form-footer").show();
            jQuery(".chaty-form-response").html("");
            jQuery(".chaty-help-btn").toggle();
            jQuery(".chaty-help-form").toggleClass("active");
            if(jQuery(".chaty-help-btn .tooltiptext").length) {
                jQuery(".chaty-help-btn .tooltiptext").remove();
                jQuery.ajax({
                    url: "<?php echo esc_url(admin_url('admin-ajax.php')) ?>",
                    data: {
                        nonce: "<?php echo esc_attr(wp_create_nonce("hide_chaty_cta")) ?>",
                        action: "hide_chaty_cta"
                    },
                    type: "post",
                    success: function (responseText) {

                    }
                });
            }

        });
        jQuery(".chaty-help-form").click(function(e){
            e.stopPropagation();
        });
        jQuery("body").click(function(){
            jQuery(".chaty-help-form").removeClass("active");
            if(jQuery(".chaty-help-form").hasClass("active")) {
                jQuery(".chaty-help-btn").show();
            } else {
                jQuery(".chaty-help-btn").hide();
            }
        });
    });
</script>
