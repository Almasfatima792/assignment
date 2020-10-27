<style>
    .software-licensing p {
        background: #fff;
    }
    .software-licensing {
        background: #fff;
        -webkit-border-radius: 10px;
        -moz-border-radius: 10px;
        border-radius: 10px;
        font-family: 'Poppins', sans-serif;
    }
    .sticky-form-field {
        padding: 20px 20px 10px 20px;
    }
    .sticky-form-field label {
        display: block;
        padding: 0 0 5px 0;
    }
    .sticky-form-field input {
        height: 30px;
        width: 300px;
        -webkit-border-radius: 3px;
        -moz-border-radius: 3px;
        border-radius: 3px;
    }
    .sticky-form-buttons {
        padding: 10px 20px 20px 20px;
    }
    button.sticky-activate-key {
        background: #00c67c;
        border: none;
        color: #fff;
        padding: 5px 20px;
        font-family: 'Poppins', sans-serif;
        font-size: 12px;
        -webkit-border-radius: 3px;
        -moz-border-radius: 3px;
        border-radius: 3px;
    }
    button.sticky-activate-key:hover {
        background: #009661;
    }
    button.sticky-deactivate-key {
        background: #969696;
        border: none;
        color: #fff;
        padding: 5px 20px;
        font-family: 'Poppins', sans-serif;
        font-size: 12px;
        margin-right: 0px;
        -webkit-border-radius: 3px;
        -moz-border-radius: 3px;
        border-radius: 3px;
    }
    button.sticky-deactivate-key:hover {
        background: #585858;
    }
    .software-licensing-footer {
        padding: 15px;
        background: #e1daf6;
        color: #7559c0;
        font-weight: bold;
        -webkit-border-bottom-right-radius: 10px;
        -webkit-border-bottom-left-radius: 10px;
        -moz-border-radius-bottomright: 10px;
        -moz-border-radius-bottomleft: 10px;
        border-bottom-right-radius: 10px;
        border-bottom-left-radius: 10px;
    }
    .sticky-element-content {
        margin: 20px 0 0 0;
    }
    .mystickyelements-header-title {
        padding: 15px 20px;
        background-color: #f9fcfc;
        -webkit-border-top-left-radius: 10px;
        -webkit-border-top-right-radius: 10px;
        -moz-border-radius-topleft: 10px;
        -moz-border-radius-topright: 10px;
        border-top-left-radius: 10px;
        border-top-right-radius: 10px;
    }
    .mystickyelements-header-title h3 {
        margin: 0;
        padding: 0;
    }
</style>
<div class="wrap">
    <div id="sticky-element-activate-key" class="sticky-element-content">
        <?php
        $is_active = 0;
        $is_expired = 0;
        $license_key = "";
        $expire_on = "";
        if(!empty($license_data) && isset($license_data['license'])) {
            if($license_data['license'] == "valid") {
                $is_active = 1;
            } else if($license_data['license'] == "expired") {
                $is_expired = 1;
            }
			if ( isset($license_data['expires'])) {
				$expire_on = $license_data['expires'];
			}
            $license_key = get_option("sticky_element_license_key");
        }
        ?>
        <div class="software-licensing">
            <div class="mystickyelements-header-title">
                <h3>Software Licensing</h3>
            </div>
            <div class="sticky-form-field">
                <label for="sticky-license_key"><?php _e('License Key', 'mystickyelements'); ?></label>
                <input type="text" value="<?php echo $license_key ?>" id="sticky-license_key">
            </div>
            <div class="sticky-form-buttons">
                <button type="button" class="sticky-activate-key"><?php _e('Activate License', 'mystickyelements'); ?></button>
                <button style="display: <?php echo ($is_active || $is_expired)?"inline-block":"none" ?>" type="button" class="sticky-deactivate-key"><?php _e('Deactivate License', 'mystickyelements'); ?></button>
            </div>
            <input type="hidden" id="sticky_ajax_url" value="<?php echo admin_url("admin-ajax.php") ?>">
            <div class="software-licensing-footer">
                <?php if($is_active == 1) {
                    echo "Your License key will expire on ".date("d F, Y", strtotime($expire_on)).".";
                } else if($is_expired == 1) {
                    $url = "https://go.premio.io/checkout/?edd_license_key=".$license_key."&download_id=".PRO_MY_STICKY_ELEMENT_ID;
                    echo "Your License key has been expired on ".date("d F, Y", strtotime($expire_on)).". <a target='_blank' href='".$url."'>Click here</a> to renew";
                } else {
                    echo "Activate your License key to use All Pro features";
                }?>
            </div>
        </div>
    </div>
</div>
<script>
    jQuery(document).ready(function(){
        jQuery(".sticky-activate-key").click(function(){
            licenseKey = jQuery.trim(jQuery("#sticky-license_key").val());
            AJAX_URL = jQuery("#sticky_ajax_url").val();
            if(licenseKey == "") {
                alert("Please enter your license Key");
            } else {
                jQuery(".software-licensing button").attr("disabled", true);
                jQuery.ajax({
                    url: AJAX_URL,
                    data: "license_key=" + licenseKey + "&action=sticky_element_activate_key&nonce=<?php echo wp_create_nonce('sticky_element_activate_key_nonce') ?>",
                    method: 'post',
                    success: function (res) {
                        jQuery(".software-licensing button").attr("disabled", false);
                        res = jQuery.parseJSON(res);
                        if(res.status == 1) {
                            jQuery(".sticky-deactivate-key").show();
                            setTimeout(function(){
                                window.location.reload();
                            }, 1500);
                        } else {
                            jQuery(".sticky-deactivate-key").hide();
                        }
                        jQuery(".software-licensing-footer").html(res.message);
                    }
                });
            }
        });
        jQuery(".sticky-deactivate-key").click(function(){
            licenseKey = jQuery.trim(jQuery("#sticky-license_key").val());
            AJAX_URL = jQuery("#sticky_ajax_url").val();
            jQuery(".software-licensing button").attr("disabled", true);
            jQuery.ajax({
                url: AJAX_URL,
                data: "license_key=" + licenseKey + "&action=sticky_element_deactivate_key&nonce=<?php echo wp_create_nonce('sticky_element_deactivate_key_nonce') ?>",
                method: 'post',
                success: function (res) {
                    jQuery(".software-licensing button").attr("disabled", false);
                    res = jQuery.parseJSON(res);
                    if(res.status == 1) {
                        jQuery(".sticky-deactivate-key").hide();
                        jQuery("#sticky-license_key").val("");
                        setTimeout(function(){
                            window.location.reload();
                        }, 1500);
                    }
                    jQuery(".software-licensing-footer").html(res.message);
                }
            });
        });
    });
</script>