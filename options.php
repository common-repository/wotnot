<div class="wrap">
  <div>
    <span class="wotnot-header"> <?php echo esc_html(_e('WotNot - Chatbot Platform')) ?></span>
    <a class="add-new-h2 tutorial-button" target="_blank" href="<?php echo esc_url("https://wotnot.atlassian.net/servicedesk/customer/portal/3/article/632651777?src=1901916047"); ?>"><?php esc_html(_e('Read Tutorial', 'wotnot')); ?></a>
    <a class="add-new-h2 tutorial-button" target="_blank" href="<?php echo esc_url("https://www.youtube.com/watch?v=wMmio2y4mHA&feature=youtu.be"); ?>"><?php esc_html(_e('Watch Tutorial', 'wotnot')); ?></a>
  </div>

  <hr />
  <div id="poststuff">
    <div id="post-body" class="metabox-holder columns-2">
      <div id="post-body-content">
        <div class="postbox">
          <div class="inside">
            <form name="dofollow" action="options.php" method="post">

              <?php
              settings_fields('wotnot-settings-group');
              $settings = get_option('wotnot-plugin-settings');
              $script = (array_key_exists('script', $settings) ? $settings['script'] : '');
              $showOn = (array_key_exists('showOn', $settings) ? $settings['showOn'] : 'all');
              ?>
              <div id="wotnot-instructions">
                <h3 class="cc-labels instruction-header"><?php esc_html(_e('Instructions: ', 'wotnot')); ?></h3>
                <?php
                $userEmail = '';
                if (wp_get_current_user() instanceof WP_User) $userEmail = wp_get_current_user()->user_email;
                ?>
                <p class="instruction-text">1. <?php esc_html(_e('If you are not an existing WotNot user, <a class="instruction-link" href="https://app.wotnot.io/signup?utm_source=wordpress&utm_medium=plugin&utm_campaign=network-effect" target="_blank" >Click here to sign up</a>', 'wotnot')); ?></p>

                <p class="instruction-text">2. <?php esc_html(_e('Build your chatbot from scratch or using templates with our <a class="instruction-link" href="https://wotnot.io/bot-builder?utm_source=wordpress&utm_medium=plugin&utm_campaign=network-effect" target="_blank">No-code Bot Builder</a>', 'wotnot')); ?></p>

                <p class="instruction-text">3. <?php esc_html(_e('Copy the code snippet from Channel Configuration > Web > Configuration and paste it here', 'wotnot')); ?></p>
              </div>
              <h3 class="cc-labels chatbot-snippet-title" for="script"><?php esc_html(_e('Chatbot Snippet:', 'wotnot')); ?></h3>
              <textarea id="wotnot-plugin-snippet" style="width:100%; border-radius: 2px; border: solid 1px #e9e9e9;" rows="7" cols="50" id="script" name="wotnot-plugin-settings[script]"><?php echo esc_html($script); ?></textarea>


              <p class="submit wotnot-submit">
                <input class="button button-primary" id="save-btn" type="submit" name="Submit" value="<?php esc_html(_e('Save', 'wotnot')); ?>" />
              </p>
              <p class="instruction-text wotnot-note"><?php esc_html(_e("Note: Once you hit 'Save', the above code will be added to your website and the chat widget will show up on your website.", 'wotnot')); ?></p>

            </form>
          </div>
        </div>
      </div>

      <?php require_once(WOTNOT_PLUGIN_DIR . '/sidebar.php'); ?>
    </div>
  </div>
</div>
