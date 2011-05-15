<div class="wrap">
    <div id="icon-tools" class="icon32"><br /></div>
    <?php    echo "<h2>" . __( 'GERRYWORKS Post by Mail settings','gwlanguage') . "</h2>"; ?>
    <?php gw_admin_update();?>
    <?php gw_mail_update_other();?>
    <div class="postbox-container" style="width: 100%;">
        <div id="server" class="meta-box-sortables ui-sortable">
        <div id="dashboard_right_now" class="postbox ">
    <form action="#server"  method="post">
        <input type="hidden" name="gw_sent">
        <table class="form-table">
            <tbody>
                <tr valign="top">
                    <th scope="row">
                    <b><?php _e('Server Settings','gwlanguage')?></b>
                    </th>
                    <td></td>
                </tr>
               <tr valign="top">
                    <th scope="row"> <label for="gw_server_type"><?php _e('Your host','gwlanguage')?></label></th>
                    <td><?php gw_mail_type();?>
			<br>
                        <small><em><?php _e('Select your mail host','gwlanguage');?></em></small>
                    </td>
                </tr>
               <tr valign="top">
                    <th scope="row"> <label for="gw_url"><?php _e('Your Imap Server','gwlanguage')?></label></th>
                    <td><input type="text" class="regular-text"value="<?php echo gw_mail_options('gw_url')?>" name="gw_url" id="gw_url"/><br>
                        <small><em><?php _e('Set to <b>localhost</b> by default. It can be mail.yourdomain.com, imap.youdomain.com','gwlanguage');?></em></small>
                    </td>
                </tr>
               <tr valign="top">
                    <th scope="row"> <label for="gw_protocol"><?php _e('Server protocol','gwlanguage')?></label></th>
                    <td><input type="text" class="regular-text"value="<?php echo gw_mail_options('gw_protocol')?>" name="gw_protocol" id="gw_protocol"/><br>
                        <small><em><?php _e('Sets to <b>notls</b> by default, can be also <b>ssl, tls or notls</b>','gwlanguage');?></em></small>
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row"> <label for="gw_port"><?php _e('Server port','gwlanguage')?></label></th>
                    <td><input type="text" class="regular-text"value="<?php echo gw_mail_options('gw_port')?>" name="gw_port" id="gw_port"/><br>
                    <small><em><?php _e('Enter your imap server port. <b>143</b> by default','gwlanguage');?></em></small></td>
                </tr>
                <tr valign="top">
                    <th scope="row"> <label for="gw_login"><?php _e('Your login','gwlanguage')?></label></th>
                    <td><input type="text" class="regular-text"value="<?php echo gw_mail_options('gw_login')?>" name="gw_login" id="gw_login"/><br>
                        <small><em><?php _e('Enter here the login you use to access your e-mail account box','gwlanguage');?></em></small></td>
                </tr>
                <tr valign="top">
                    <th scope="row"> <label for="gw_pass"><?php _e('Your password','gwlanguage')?></label></th>
                    <td><input type="password" class="regular-text" value="<?php echo gw_mail_options('gw_pass')?>" name="gw_pass" id="gw_pass"/><br>
                        <small><em><?php _e('Enter here the password related to your login e-mail account','gwlanguage');?></em>
                            </small></td>
                </tr>
				<tr valign="top">
					<th scope="row"> <label for="gw_addiframe"><?php _e('Use iFrame to check post','gwlanguage')?></label></th>
					<td><input type="radio" class="" value="1" name="gw_addiframe" id="gw_addiframe" <?php if(gw_mail_options("gw_addiframe")==1) echo "checked='checked'";?> /> Yes&nbsp;&nbsp;&nbsp;&nbsp;<input 
type="radio" class="" value="2" name="gw_addiframe" id="gw_addiframe" <?php if(gw_mail_options('gw_addiframe')==2) echo "checked='checked'";?> /> No<br>
						<small><em><?php _e('In order to check for mails, each time a page of your blog is loaded.','gwlanguage');?></em>
						</small></td>
				</tr>
				<tr valign="top">
                    <th scope="row"> <input type="submit" class="button-primary" name="Submit" value="<?php _e('Update settings','gwlanguage')?>" /></th>
                    <td></td>
                </tr>

            </tbody>
        </table>
    </form>
        </div>
        </div>
    </div>
        <div class="postbox-container" style="width: 100%;">
            <div id="other" class="meta-box-sortables ui-sortable">
                <div id="" class="postbox ">
      <form action="#other" method="post" >
          <input type="hidden" name="gw_update_status"/>
          <table class="form-table">
             <tbody>
                 <tr valign="top">
                    <th scope="row">
                    <b>Other Settings</b>
                    </th>
                    <td></td>
                </tr>

                <tr valign="top">
                    <th scope="row"> <label for="gw_status"><?php _e('Status of posted mail','gwlanguage')?></label></th>
                    <td>
                        <input type="radio" name="gw_status" value="pending" <? if (gw_mail_status() == "pending") { echo 'checked="yes"'; } ?>/> <?php _e('Pending','gwlanguage')?><br />
			<input type="radio" name="gw_status" value="publish" <? if (gw_mail_status() == "publish") { echo 'checked="yes"'; } ?>/> <?php _e('Published','gwlanguage')?><br />
                        <small><em><?php _e('Select the status to give to post sent to the mail adress by unknown or contributors users.','gwlanguage');?></em></small></td>
                </tr>
                <tr valign="top">
                    <th scope="row"> <label for="gw_w"><?php _e('Video Width','gwlanguage')?></label></th>
                    <td><input type="text" class="regular-text"value="<?php echo gw_mail_options('gw_w')?>" name="gw_w" id="gw_w"/><br>
                        <small><em><?php _e('Enter here the width to use for embed video','gwlanguage');?></em></small></td>
                </tr>
                <tr valign="top">
                    <th scope="row"> <label for="gw_h"><?php _e('Video Height','gwlanguage')?></label></th>
                    <td><input type="text" class="regular-text"value="<?php echo gw_mail_options('gw_h')?>" name="gw_h" id="gw_h"/><br>
                        <small><em><?php _e('Enter here the height to use for embed video','gwlanguage');?></em></small></td>
                </tr>
                <tr valign="top">
                    <th scope="row"> <label for="gw_status"><?php _e('Convert Links','gwlanguage')?></label></th>
                    <td>
                        <input type="radio" name="gw_convertlinks" value="1" <? if (get_option('gw_convertlinks')) { echo 'checked="yes"'; } ?>/> <?php _e('Allways convert url','gwlanguage')?><br />
			<input type="radio" name="gw_convertlinks" value="0" <? if (!get_option('gw_convertlinks')) { echo 'checked="yes"'; } ?>/> <?php _e('Never convert url','gwlanguage')?><br />
                        <small><em><?php _e('Select if you want to convert url into links or not. Set to never convert url by default.','gwlanguage');?></em></small></td>
                </tr>

                <tr valign="top">
                    <th scope="row"> <input type="submit" class="button-primary" name="submit" value="<?php _e('Update settings','gwlanguage')?>" /></th>
                    <td></td>
                </tr>

            </tbody>
        </table>
    </form>

                </div>
            </div>
        </div>
</div>