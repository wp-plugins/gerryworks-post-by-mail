<?php
/*
Plugin Name: GERRYWORKS Post by Mail
Plugin URI: http://gerryworks.be/2011/04/gw-post-by-mail.jsp
Description: This plugin exists in order to offset some deficiencies of the Wordpress post by mail features. It would be useful for those who want to post on their blog by mail or those who want to allow people to publish post anonymously on their blog by sending emails.
Version: 1.0
Author: Gerry Ntabuhashe
Author URI: http://gerryworks.be/
License: GPL2 or later
*/

/*  Copyright 2011  GERRYWORKS and its OWNER  (email : gerry.ntabuhashe@gerryworks.be)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

if(!defined('GW_MAIL')){
    define('GW_MAIL', true);
    define('GW_PBMDIR',WP_PLUGIN_DIR.'/'.dirname( plugin_basename( __FILE__ ) ));

    require_once GW_PBMDIR.'/gw_functions.php';

    load_plugin_textdomain ( 'gwlanguage', false, dirname( plugin_basename( __FILE__ ) ).'/languages');
    
    add_action( 'wp-mail.php', 'gw_post_mail');
    register_deactivation_hook( __FILE__, 'gw_mail_remove' );

    define('GW_DEFAULT_OPTIONS','gw-default-author=1&gw_login='.get_option('mailserver_login').'&gw_url=localhost&gw_pass='.get_option('mailserver_pass').'&gw_port=143&
		   &gw_server_type='.'other&gw_protocol=notls&gw_addiframe=0');
    define ('GW_DEFAULT_AUTHOR',gw_mail_options("gw-default-author"));
    function gw_post_mail(){
        if ( !defined('WP_MAIL_INTERVAL') )
            define('WP_MAIL_INTERVAL', 1); // 2 minutes

        $last_checked = get_transient('mailserver_last_checked');

        if ( $last_checked ){
             echo (__('Hey slow, try again latter!','gwlanguage'));
             exit;
        }
        set_transient('mailserver_last_checked', true, WP_MAIL_INTERVAL);
        $time_difference = get_option('gmt_offset') * 3600;

        $host= gw_mail_options('gw_url');
        $login = gw_mail_options('gw_login');
        $port = gw_mail_options('gw_port');
        $pass=gw_mail_options('gw_pass');
		$protocol = gw_mail_options('gw_protocol');
        $imb = imap_open('{'."$host:$port"."/imap/".$protocol.'}INBOX', $login, $pass);
        if(!$imb) {
            echo (__('Impossible de se connecter au serveur','gwlanguage'));
            exit;
        }
        $count = imap_num_msg($imb);
        if($count==0){
			imap_close($imb);
            echo ( __('There doesn&#8217;t seem to be any new mail.','gwlanguage') );
            exit;
        }
        for ($i = 1; $i <= $count; ++$i) {
            $dmonths = array('Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec');
            $header = imap_header($imb, $i);
            if(!$header) {
				imap_delete($imb, $i);
				continue; /*If there's no header for this mail, skip */
			}
            /*START>>Defining Date*/
            $date=$header->date;

            if (strpos($date, ',')) {
                $date = trim(substr($date, strpos($date, ',') + 1, strlen($date)));
            }
            $date_arr = explode(' ', $date);
            $date_time = explode(':', $date_arr[3]);

            $ddate_H = $date_time[0];
            $ddate_i = $date_time[1];
            $ddate_s = $date_time[2];

            $ddate_m = $date_arr[1];
            $ddate_d = $date_arr[0];
            $ddate_Y = $date_arr[2];

            for ( $j = 0; $j < 12; $j++ ) {
                if ( $ddate_m == $dmonths[$j] ) {
                    $ddate_m = $j+1;
                }
            }

            $time_zn = intval($date_arr[4]) * 36;
            $ddate_U = gmmktime($ddate_H, $ddate_i, $ddate_s, $ddate_m, $ddate_d, $ddate_Y);
            $ddate_U = $ddate_U - $time_zn;
            $post_date = gmdate('Y-m-d H:i:s', $ddate_U + $time_difference);
            $post_date_gmt = gmdate('Y-m-d H:i:s', $ddate_U);
            /*END>>Defining date*/


            $charset=$header->charset;
            $sujet = gw_sujet(gw_mail_subject($header->subject));
            $post_title = trim($sujet[1]);
            /*START>> Verify if the mail is a spam*/
            if(!gw_not_spam($header)) {
                imap_delete($imb, $i);
                continue;
            }
			$from = $header->from;
            $from =$from[0];
            if(empty($from->mailbox) || empty ($from->host)) {
                imap_delete($imb, $i);continue;
            }
            /*END>> Verify if the mail is a spam*/

            $mail=$from->mailbox."@".$from->host;

            /*START>>Verifying author*/
            $author = sanitize_email($mail);
            if ( is_email($author) ) {
                //echo '<p>' . sprintf(__('Author is %s'), $author) . '</p>';
                $userdata = get_user_by_email($author);
                if ( empty($userdata) ) {
                    $author_found = false;
		} else {
                    $post_author = $userdata->ID;
                    $author_found = true;
		}
            } else {
                $need_moderation = true;
                /*if the mail is invalid, moderation is necessary before publishing
                 * this will help us to avoid, spams posts.
                 */
                $author_found = false;
            }
            if(!$author_found) $post_author = GW_DEFAULT_AUTHOR;
            /*END>>Verifying author informations*/


            /*START>>Getting the post status*/
            if ( $author_found ) {
		$user = new WP_User($post_author);
		$post_status = ( $user->has_cap('publish_posts') ) ? 'publish' : gw_mail_status();
            } elseif($need_moderation){
                $post_status = "pending"; // we set post for moderation
            }else {
		// Author not found in DB, set status to the status defined as default.  Author already set to default.
		$post_status = gw_mail_status();
            }
            /*END>>Getting the post status*/
            
            /*The post category*/
            $post_category = gw_category($sujet[0]);

            $struct = gw_mail_struct(imap_fetchstructure ($imb,$i));
            $charset = $struct['charset'];
            $gw_display = $struct['displaypart'];
            /**
             * fetching the post attachement in order to save it
             */
            //gw_displayattachement($imb,$i,$struct);
            
            /*Get the body of the mail containing text*/
            $post_content = trim(gw_mail_content(imap_fetchbody ($imb , $i, $gw_display),$charset));
            $post_data =  compact('post_content','post_title','post_date','post_date_gmt','post_author','post_category', 'post_status');
            $post_data = add_magic_quotes($post_data);
            $post_ID = wp_insert_post($post_data);
            if ( is_wp_error( $post_ID ) )
					echo "\n" . $post_ID->get_error_message();
            /* We couldn't post, for whatever reason. Better move forward to the next email.*/
            if (empty($post_ID))
                  continue;
            do_action('publish_phone', $post_ID);
            echo "\n" . sprintf(__('Author: %s','gwlanguage'), esc_html($post_author)) . '';
            echo "\n" . sprintf(__('Posted title: %s','gwlanguage'), esc_html($post_title)) . '';
            imap_delete($imb, $i);
        }
        if(imap_expunge($imb)) echo sprintf("\nAll mails deleted",'gwlanguage');
        imap_close($imb);
        exit;
    }

    function gw_mail_post_admin(){
       include_once GW_PBMDIR.'/gw-mail-admin.php';
    }
    function gw_menu_admin() {
	add_options_page("Gerryworks Post by Mail", "Gerryworks Post by Mail", 1, "gw-mail-admin", "gw_mail_post_admin");
    }
    add_action('admin_menu', 'gw_menu_admin');

    function gw_admin_update(){
        if(!isset($_POST['gw_sent'])) return;
       	if(isset($_POST['gw_sent'])) {
            parse_str(GW_DEFAULT_OPTIONS,$array);
            foreach($array as $key=>$value){
                if(!empty($_POST[$key]))
                    $dbss =  str_replace("'",'',$_POST[$key]);
                else
                    $dbss = gw_mail_options($key);
                if(!get_option($key)) add_option($key,$dbss,'','no');
                else update_option($key, $dbss);
            }
            print "<div class=\"updated\"><p><strong>".__('Updated successfully','gwlanguage') ."</strong></p></div>";
	}
    }
    add_action('wp_footer', 'gw_onfooter');
    function gw_onfooter(){     
        if(gw_mail_options('gw_addiframe')==1){
			echo '<script type="text/javascript">';
			echo "document.write('".sprintf('<iframe src="%1$s" style="margin:0px; padding:0px;width:0px;height:0px;" name="mailiframe" width="0" height="0" frameborder="0" scrolling="no" title=""></iframe>',  get_bloginfo('url').'/wp-mail.php')."');";
			echo '</script>';
		}
    }
}