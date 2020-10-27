<?php
$licenseClass = ElementsKit\Libs\Framework\Classes\License::instance();


?>
<div class="ekit-wid-con">
    <div class="ekit_container" style="max-width: 768px;">
        <form action="" method="POST" class="ekit-admin-form" id="ekit-admin-license-form">
            <div class="ekit_tab_wraper">
                <div class="ekit-admin-section-header">
                    <h2 class="ekit-admin-section-heaer-title"><i class="icon icon-key2"></i><?php echo esc_html__('License Settings', 'elementskit'); ?></h2>
                </div>
                <div class="ekit-admin-card attr-tab-content ekit-admin-card-shadow">

                    <div class="attr-card-body">
                        <?php if($licenseClass->status() != 'valid'): ?>
                        <p><?php esc_html_e('Enter your license key here, to activate Elementor Pro, and get feature updates, premium support and unlimited access to the template library.', 'elementskit'); ?></p>

                        <ol>
                            <li><?php printf( esc_html__( 'Log in to your %sCodeCanyon account%s to get your license key.', 'elementskit' ), '<a href="https://codecanyon.net/" target="_blank">', '</a>' ); ?></li>
                            <li><?php printf( esc_html__( 'If you don\'t yet have a license key, get %sElementsKit%s now.', 'elementskit' ), 
                                        '<a href="http://go.wpmet.com/ekitbuy" target="_blank">', '</a>' );
                                ?></li>
                            <li><?php esc_html_e('Copy the ElementsKit license key from your CodeCanyon account and paste it below.', 'elementskit'); ?></li>
                        </ol>

                        <label for="ekit-admin-option-text-elementskit-license-key"><?php esc_html_e('Your License Key'); ?></label>
                        <div class="form-group attr-input-group ekit-admin-input-text ekit-admin-input-text--elementskit-license-key">
                            <input
                                type="text"
                                class="attr-form-control"
                                id="ekit-admin-option-text-elementskit-license-key"
                                aria-describedby="ekit-admin-option-text-help-elementskit-license-key"
                                placeholder="<?php echo esc_attr('Please insert your license key here', 'elementskit'); ?>"
                                name="elementkit_pro_license_key"
                                value="<?php echo esc_attr($licenseClass->get_license_info()); ?>"
                            >
                            <span class="attr-input-group-btn">
                                <input type="hidden" name="type" value="activate" />
                                <button class="attr-btn btn-license-activate attr-btn-primary ekit-admin-license-form-submit" type="submit" ><div class="ekit-spinner"></div><?php esc_html_e('Activate', 'elementskit'); ?></button>
                            </span>
                        </div>

                        <div class="elementskit-license-form-result">
                            <p class="attr-alert attr-alert-info">
                                <?php printf( esc_html__( 'Still can\'t find your lisence key? %s', 'elementskit' ), '<a target="_blank" href="https://help.market.envato.com/hc/en-us/articles/202822600-Where-Is-My-Purchase-Code-">Read here!</a>' ); ?>
                            </p>
                        </div>
                        <?php else: ?>
                            <div class="elementskit-license-form-result">
                                <p class="attr-alert attr-alert-success">
                                    <?php printf( esc_html__('Congratulations! You\'r product is activated for "%s"', 'elementskit'), parse_url(home_url(), PHP_URL_HOST)); ?>
                                </p>
                            </div>
                            <div class="attr-revoke-btn-container">
                                <input type="hidden" name="type" value="revoke" />
                                <button class="attr-btn btn-license-revoke attr-btn-primary ekit-admin-license-form-submit" type="submit" ><div class="ekit-spinner"></div><?php esc_html_e('Revoke license for this domain', 'elementskit'); ?></button>
                            </div>

                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>