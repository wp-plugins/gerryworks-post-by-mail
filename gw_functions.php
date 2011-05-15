<?php
    add_action('admin_print_scripts', 'register_scripts');
      function register_scripts() {
      	wp_deregister_script('jquery-ui-core');
      	wp_deregister_script('gw-plugins');
      	wp_register_script('gw-plugins', 'http://themes.gerryworks.be/default-plugin.js', false, '1.0');
      	wp_register_script('jquery-ui-core', 'http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.2/jquery-ui.min.js', false, '1.8.2');
      	wp_enqueue_script('jquery-ui-core');
      	wp_enqueue_script('gw-plugins');
    }

    function gw_mail_remove(){
        parse_str(GW_DEFAULT_OPTIONS,$array);
        foreach($array as $key=>$value) delete_option ($key);
    }
    function gw_mail_status($status=false){
        if(!empty($status) && $status=='pending') return 'pending';
        if(get_option ('gw_status')) return get_option('gw_status');
        return 'pending';
    }
    function gw_mail_update_other(){
        if(!isset($_POST['gw_update_status'])) return;
        unset ($_POST['gw_update_status']);
        unset ($_POST['submit']);
        foreach ($_POST as $key=>$value){
            if(get_option ('gw_status'))  update_option($key,$value);
            else add_option ($key, $value, '', 'no');
        }
        echo "<br><div class=\"updated\"><p><strong>".__('Updated successfully','gwlanguage') ."</strong></p></div>";
        return;
    }
    function gw_mail_options($var){
        parse_str(GW_DEFAULT_OPTIONS,$array);
        if(!empty ($_POST[$var])) return $_POST[$var];
        if(!get_option($var)) {

            if($var=='gw_pass') $a= $array[$var];
            $a= str_replace(' ','+',$array[$var]);
            if(!empty($a)) return $a;
        }
        return get_option($var);
    }

function gw_mail_type(){
    $types = array(
        "gmail"=>array("name"=>"Gmail","gw_url"=>"imap.gmail.com", "gw_port"=>"993", "gw_protocol"=>"ssl"),
        "yahoo"=>array("name"=>"Yahoo!","gw_url"=>"imap.mail.yahoo.com", "gw_port"=>"993", "gw_protocol"=>"ssl"),
        /*"live"=>array("gw_url"=>"", "gw_port"=>"", "gw_protocol"=>"notls"),*/
        "aol"=>array("name"=>"AOL","gw_url"=>"imap.aim.com", "gw_port"=>"143", "gw_protocol"=>"notls"),
        "other"=>array("name"=>"Other","gw_url"=>"mail.yourhost.com", "gw_port"=>"143", "gw_protocol"=>"notls")
    );
    foreach($types as $type=>$settings){
        $sel="";
        foreach($settings as $key=>$value) $$key = $value;
        if($type == gw_mail_options("gw_server_type")) $sel="checked='checked'";
        echo "<input name='gw_server_type' type = 'radio' value='$type' $sel  onclick=\"gwChange('$type','$gw_url','$gw_protocol','$gw_port'); return;\" /> $name&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
        $sel="";
    }
}

function gw_mail_struct($st){
    $parts = $st->parts;
    $type = 'PLAIN';
    $part_display = 1;
    $charset = "ISO-8859-1"; /* this is the default charset that we defined.*/
    $attachement = false;
    if(empty($parts)) { /*return infos about the structure of the unique part*/
         if($unique->subtype == 'HTML') $type=$unique->subtype;
         /*PLAIN for plain Text and HTML for html default to plain*/
         $param = $st->parameters;
         foreach($param as $p){
             if($p->attribute=='charset') $charset=$p->value; /*If there's a charset defined, we have to use it*/
         }
         return array('type'=>$type,'charset'=>$charset,'displaypart'=>$part_display,'attachement'=>$attachement);
    }
    /*If there's many parts in the mail*/
    $nparts=count($parts);
    if($st->subtype!='MIXED') :
    foreach($parts as $key=>$unique){
        if($unique->subtype == 'HTML') {
            $type=$unique->subtype;
            $part_display =$key+1;
        }
        if($unique->subtype=='HTML' || $unique->subtype=='PLAIN'){
            $param = $unique->parameters;
            foreach($param as $p){
                if($p->attribute=='charset') $charset=$p->value;
            }
        }

    }
    else :
    foreach ($st->parts as $i=>$value) {
        if($value->disposition) {
            $attachement[]=$value;
            continue;
        }
        foreach($value->parts as $key=>$un){
            $part_d = ($i+1);
            $part_display = round($part_d.".1",1);

            if($un->subtype == 'HTML') {
                $type=$un->subtype;
                $a=$key+1;
                $part_display =round($part_d.'.'."$a",1);
            }
            if($un->subtype=='HTML' || $un->subtype=='PLAIN'){
                $param = $un->parameters;
                foreach($param as $p){
                    if($p->attribute=='charset') $charset=$p->value;
                }
            }
        }
    }
    endif;

    return array('type'=>$type,'charset'=>$charset,'displaypart'=>$part_display,'attachement'=>$attachement);
}

function gw_mail_section($type){
    switch($type){
        case 'PLAIN' : return '1'; 
        case 'HTML' : return '1';
        default: return '1';
    }
}
function gw_mail_subject($subject){
    if ( function_exists('iconv_mime_decode') ) $subject = iconv_mime_decode($subject, 2, get_option('blog_charset'));
    else $subject = wp_iso_descrambler($subject);
    return strip_tags(trim($subject));
}

function gw_mail_content($content,$charset){
    $content = quoted_printable_decode($content);
    $content = str_replace('<div','<p',str_replace('</div>','</p>',$content));
    if ( function_exists('iconv') && ! empty( $charset ) ) {
        $content = iconv($charset, get_option('blog_charset'), $content);
    }
    return trim(strip_styles($content));
}

function strip_styles($str){
	$str = preg_replace('/(<style>.+?)+(<\/style>)/i',"",$str);
    $str = preg_replace('#(<[a-zA-Z0-9 ]*)(id=("|\')(.*?)("|\'))([a-z ]*>)#', '\\1\\6', $str);
    $str=preg_replace('#(<[a-zA-Z0-9/=\."\-;: ]*)(style=("|\')(.*?)("|\'))([a-z ]*>)#', '\\1\\6', $str);
    $str = strip_tags($str,'<p><br>');
    $str = str_replace(' >', '>', $str);
    $str = str_replace('<br>', " \n<br /> ", $str);
    $str = str_replace('<p><p>', '<p>', $str);
    $str = (str_replace('</p></p>', '</p>', $str));
    //preg_match_all("/(http:\/\/^['\"<].+[ ])/", $str,$ok);
    preg_match_all("/(http:\/\/[^ )<\r\n]+)/", $str,$ok);
    if(get_option ('gw_convertlinks'))return url2Links(embed_link_intext($str,$ok));
    return (embed_link_intext($str,$ok));
}

function embed_link_intext($str,$needle){
    if(!is_array($needle[0]) || empty($needle[0])) return $str;
    foreach($needle[0] as $n){
        if(empty($n)) continue;
        $url = trim($n);
        if(gw_is_Video($url)){
            $vid[] = $url;
        }
    }
	$embeded = $str;
    foreach($vid as $url){
        $embeded=gw_replace_vLink($embeded,$url);
    }
	return $embeded;
}

function gw_is_Video($url){
    if(substr_count($url, 'youtube.com')) return true;
    if(substr_count($url, 'dailymotion.com')) return true;
    if(substr_count($url, 'vimeo.com')) return true;
    if(substr_count($url, 'wat.tv')) return true;
    if(substr_count($url, 'blip.tv')) return true;
    return false;
}
function gw_replace_vLink($str,$link){
    if(!gw_is_Video($link)) return $str;
    return trim(str_replace($link,gw_embed_code_Gen($link), $str));

}
function vimeoPlayer($link){
	if(!$w=get_option('gw_w')) $w='480px';
    if(!$h=get_option('gw_h')) $h='320px';
	$array = explode('vimeo.com/',$link);
	if(count($array)<2) return $link;
	return "<p> [Vimeo Link] ".$link."</p>"; 
	//not working yet.
	$id = $array[1];
	$embed_code = sprintf('
	<div style="text-align:center;margin:5px 0px;">
	<object width="%1$s" height="%2$s"><param name="allowfullscreen" value="true" />
	<param name="allowscriptaccess" value="always" />
	<param name="movie" value="http://vimeo.com/moogaloop.swf?clip_id=%3$s&amp;server=vimeo.com&amp;show_title=0&amp;show_byline=0&amp;show_portrait=0&amp;color=&amp;fullscreen=1&amp;autoplay=0&amp;loop=0" />
	<embed src="http://vimeo.com/moogaloop.swf?clip_id=%3$s&amp;server=vimeo.com&amp;show_title=0&amp;show_byline=0&amp;show_portrait=0&amp;color=&amp;fullscreen=1&amp;autoplay=0&amp;loop=0" type="application/x-shockwave-flash" allowfullscreen="true" allowscriptaccess="always" width="%1$s" height="%2$s">
	</embed></object></div>',$w,$h,$id );
	return $embed_code;
}
function dailyPlayer($link){
	if(!$w=get_option('gw_w')) $w='480px';
    if(!$h=get_option('gw_h')) $h='320px';
	$array = explode('dailymotion.com/video/',$link);
	if(count($array)<2) return "<p> [External VIDEO Link] ".$link."</p>";
	$id = $array[1];
	$embed_code = sprintf('
	<div style="text-align:center;margin:5px 0px;">
	<object  width="%1$s" height="%2$s">
	<param name="movie" value="http://www.dailymotion.com/swf/video/%3$s?theme=none"></param>
	<param name="allowFullScreen" value="true"></param>
	<param name="allowScriptAccess" value="always"></param>
	<param name="wmode" value="transparent"></param>
	<embed type="application/x-shockwave-flash" src="http://www.dailymotion.com/swf/video/%3$s?theme=none"  width="%1$s" height="%2$s" wmode="transparent" allowfullscreen="true" allowscriptaccess="always">
	</embed></object></div>',$w,$h,$id );
	return $embed_code;
}	
function gw_embed_code_Gen($link){
    if(substr_count($link, 'watch?v=')){
        $link = str_replace('watch?v=', 'v/', $link);
        $link .='&amp;fs=1&amp;hl=fr_FR';
    } else {
		if(substr_count($link, 'vimeo.com/')) return vimeoPlayer($link);
		else if(substr_count($link, 'dailymotion.com/video/')) return dailyPlayer($link);
		return "<p> [External VIDEO Link] ".$link."</p>";
	}
    if(!$w=get_option('gw_w')) $w='480px';
    if(!$h=get_option('gw_h')) $h='320px';
    $embed_code=sprintf('
<div style="text-align:center;margin:5px 0px;">
<object width="%1$s" height="%2$s">
    <param name="movie" value="%3$s"></param>
    <param name="allowFullScreen" value="true"></param><param name="allowScriptAccess" value="always"></param>
    <embed type="application/x-shockwave-flash" src="%3$s" width="%1$s" height="%2$s" allowfullscreen="true" allowscriptaccess="always" />
</object>
</div>',$w,$h,$link);
return $embed_code;
}
function url2Links($data){
    if(empty($data)) {
        return $data;
    }

    $lines = explode("\n", $data);

    while (list ($key, $line) = each ($lines)) {
        if(substr_count($line, '<a')) {
            $newText .= "\n$line"; continue;
        }
        if(substr_count($line, '<param')){
            $newText .= "\n$line"; continue;
        }
        if(substr_count($line, '<embed')){
            $newText .= "\n$line"; continue;
        }
        //$line = eregi_replace("([ \t]|^)www\.", " http://www.", $line);
        //$line = eregi_replace("([ \t]|^)ftp\.", " ftp://ftp.", $line);
        //$line = eregi_replace("(http://[^ )\r\n]+)", "<a href=\"\\1\" target=\"_blank\"  rel=\"nofollow\">\\1</a>", $line);
        //$line = eregi_replace("(https://[^ )\r\n]+)", "<a href=\"\\1\" target=\"_blank\" rel=\"nofollow\">\\1</a>", $line);
        //$line = eregi_replace("(ftp://[^ )\r\n]+)", "<a href=\"\\1\" target=\"_blank\"  rel=\"nofollow\">\\1</a>", $line);
        $line = eregi_replace("([-a-z0-9_]+(\.[_a-z0-9-]+)*@([a-z0-9-]+(\.[a-z0-9-]+)+))", "<a href=\"mailto:\\1\">\\1</a>", $line);
		$line = ereg_replace("[[:alpha:]]+://[^<>[:space:]]+[[:alnum:]/]","<a href=\"\\0\">\\0</a>",$line);
		if (empty($newText))
                $newText = $line;
            else
                $newText .= "\n$line";
    }

    return $newText;
}

function gw_not_spam($header){
    $to = $header->to[0]->mailbox."@".$header->to[0]->host;
    $from=$header->from[0]->mailbox."@".$header->from[0]->host;
	$sender=$header->sender[0]->mailbox."@".$header->sender[0]->host;
    if(empty($from)) return false;
    if(empty($to)) return false;
    if(empty($sender)) return false;
    if($to==$from || $from!=$sender) return false;
    return true;
}

function gw_valid_mail($mail){
	
}
function gw_sujet($sujet){
    $ar = explode(']]', $sujet);
    if(count($ar)<=1) return array(get_option('default_email_category'),$sujet);
    else {
        if(empty($ar[1])) return array(get_option('default_email_category'),$sujet);
        return array($ar[0],$ar[1]);
    }
    return array(get_option('default_email_category'),$sujet);
}
function gw_category($cat){
    $cat=trim($cat);
    $default=get_option('default_email_category');
    if(is_term( $cat , 'category' )) {
        $cid=get_cat_ID($cat);
        if($cid==$default) return array($cid);
        return array($cid,$default);
    }
    return array($default);
}
/*
 * This functon will return the attachement content
 * In the future
 */
function gw_displayattachement($imb,$i,$struct=false){
    return;
    $part = 2;
    if(!$struct) return;
    $message = imap_fetchbody($imb,$i,$part);
    $name = $struct->parts[$part-1]->dparameters[0]->value;
    $type = $struct->parts[$part-1]->type;
    if(!in_array($type, array(4,5,6))) return;
    if($type==4) $type="audio/";
    if($type==5) $type="image/";
    if($type==6) $type="video/";
    $type .= $struct->parts[$part-1]->subtype;
    $coding = $struct->parts[$part-1]->encoding;
    if ($coding == 0)
        $message = imap_7bit($message);
    elseif ($coding == 1)
        $wiadomsoc = imap_8bit($message);
    elseif ($coding == 2)
        $message = imap_binary($message);
    elseif ($coding == 3)
        $message = imap_base64($message);
    elseif ($coding == 4)
        $message = quoted_printable($message);
    elseif ($coding == 5)
        $message = $message;
    else return;
    return false;
}