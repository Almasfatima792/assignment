<?php
$user_data = $this->utils->get_option('user_data', []);

?>
<div class="ekit-admin-fields-container">
    <div class="ekit-admin-fields-container-fieldset-- xx">
        <div class="panel-group attr-accordion" id="accordion" role="tablist" aria-multiselectable="true">
            <div class="attr-panel ekit_accordion_card ekit-admin-card-shadow">
                <div class="attr-panel-heading" role="tab" id="facebook_data_headeing">
                    <a class="attr-btn" role="button" data-attr-toggle="collapse" data-parent="#accordion" href="#facebook_control_data"
                        aria-expanded="true" aria-controls="facebook_control_data">
                        <?php esc_html_e('Facebook User Data', 'elementskit'); ?>
                    </a>
                </div>
                <div id="facebook_control_data" class="attr-panel-collapse attr-collapse attr-in" role="tabpanel"
                    aria-labelledby="facebook_data_headeing">
                    <div class="attr-panel-body">
                        <?php
                            $this->utils->input([
                                'type' => 'text',
                                'name' => 'user_data[facebook][page_id]',
                                'label' => esc_html__('Page ID', 'elementskit'),
                                'placeholder' => 'GameOfThrones',
                                'value' => (!isset($user_data['facebook']['page_id'])) ? '' : ($user_data['facebook']['page_id'])
                            ]);
                        ?>
                        <?php
                            $this->utils->input([
                                'type' => 'text',
                                'name' => 'user_data[facebook][token]',
                                'label' => esc_html__('Access Token', 'elementskit'),
                                'placeholder' => 'S8LGISx9wBAOx5oUnxpOceDbyD01DYNNUwoz8jTHpm2ZB9RmK6jKwm',
                                'value' => (!isset($user_data['facebook']['token'])) ? '' : ($user_data['facebook']['token'])
                            ]);
                        ?>
						<a href="https://token.wpmet.com/index.php?provider=facebook" target="_blank"> <?php echo esc_html__('Get Access Token', 'elementskit');?> </a>
                    </div>
                </div>
            </div>
            <div class="attr-panel ekit_accordion_card ekit-admin-card-shadow">
                <div class="attr-panel-heading" role="tab" id="twetter_data_headeing">
                    <a class="attr-btn attr-collapsed" role="button" data-attr-toggle="collapse" data-parent="#accordion"
                        href="#twitter_data_control" aria-expanded="false" aria-controls="twitter_data_control">
                        <?php esc_html_e('Twitter User Data', 'elementskit'); ?>
                    </a>
                </div>
                <div id="twitter_data_control" class="attr-panel-collapse attr-collapse" role="tabpanel"
                    aria-labelledby="twetter_data_headeing">
                    <div class="attr-panel-body">
                        <?php
                            $this->utils->input([
                                'type' => 'text',
                                'name' => 'user_data[twitter][name]',
                                'label' => esc_html__('Twitter Username', 'elementskit'),
                                'placeholder' => 'gameofthrones',
                                'value' => (!isset($user_data['twitter']['name'])) ? '' : ($user_data['twitter']['name'])

                            ]);
                        ?>
                        <?php
                            $this->utils->input([
                                'type' => 'text',
                                'name' => 'user_data[twitter][access_token]',
                                'label' => esc_html__('Access Token', 'elementskit'),
                                'placeholder' => '97174059-g10REWwVvI0Mk02DhoXbqpEhh4zQg6SBIP2k8',
                                // 'info' => esc_html__('Yuour', 'elementskit')
                                'value' => (!isset($user_data['twitter']['access_token'])) ? '' : ($user_data['twitter']['access_token'])
                            ]);
                        ?>                      
						<a href="https://token.wpmet.com/index.php?provider=twitter" target="_blank"> <?php echo esc_html__('Get Access Token', 'elementskit');?> </a>
                    </div>
                </div>
            </div>
            <div class="attr-panel ekit_accordion_card ekit-admin-card-shadow">
                <div class="attr-panel-heading" role="tab" id="mail_chimp_data_headeing">
                    <a class="attr-btn attr-collapsed" role="button" data-attr-toggle="collapse" data-parent="#accordion"
                        href="#mail_chimp_data_control" aria-expanded="false"
                        aria-controls="mail_chimp_data_control">
                        <?php esc_html_e('Mail Chimp Data', 'elementskit'); ?>
                    </a>
                </div>
                <div id="mail_chimp_data_control" class="attr-panel-collapse attr-collapse" role="tabpanel"
                    aria-labelledby="mail_chimp_data_headeing">
                    <div class="attr-panel-body">
                        <?php
                              $this->utils->input([
                                  'type' => 'text',
                                  'name' => 'user_data[mail_chimp][token]',
                                  'label' => esc_html__('Token', 'elementskit'),
                                  'placeholder' => '24550c8cb06076751a80274a52878-us20',
                                  'value' => (!isset($user_data['mail_chimp']['token'])) ? '' : ($user_data['mail_chimp']['token'])
                              ]);
                          ?>
                      
                    </div>
                </div>
            </div>
            <div class="attr-panel ekit_accordion_card ekit-admin-card-shadow">
                <div class="attr-panel-heading" role="tab" id="instagram_data_headeing">
                    <a class="attr-btn attr-collapsed" role="button" data-attr-toggle="collapse" data-parent="#accordion"
                        href="#instagram_data_control" aria-expanded="false" aria-controls="instagram_data_control">
                        <?php esc_html_e('Instragram User Data', 'elementskit'); ?>
                    </a>
                </div>
                <div id="instagram_data_control" class="attr-panel-collapse attr-collapse" role="tabpanel"
                    aria-labelledby="instagram_data_headeing">
                    <div class="attr-panel-body">
                        <?php
                            $this->utils->input([
                                'type' => 'text',
                                'name' => 'user_data[instragram][user_id]',
                                'label' => esc_html__('User ID', 'elementskit'),
                                'placeholder' => '236745672995',
                                'value' => (!isset($user_data['instragram']['user_id'])) ? '' : ($user_data['instragram']['user_id'])
                            ]);
                        ?>
                        <?php
                            $this->utils->input([
                                'type' => 'text',
                                'name' => 'user_data[instragram][token]',
                                'label' => esc_html__('Access Token', 'elementskit'),
                                'placeholder' => '2367672995.1677ed0.deaerer7a14501d04cd9982c7a0d23c716dd',
                                'value' => (!isset($user_data['instragram']['token'])) ? '' : ($user_data['instragram']['token'])
                            ]);
                        ?>
						<a href="https://token.wpmet.com/index.php?provider=instagram" target="_blank"> <?php echo esc_html__('Get Access Token', 'elementskit');?> </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>