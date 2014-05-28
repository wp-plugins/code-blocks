<?
/**
 * Plugin Name: Code Blocks
 * Plugin URI: http://subinsb.com/posting-code-blocks-on-wordpress
 * Description: Add Code Blocks To Your posts and pages in the Visual Editor or Text Editor.
 * Version: 0.3
 * Author: Subin Siby
 * Author URI: http://subinsb.com
 * License: GPLv3
*/
function CBS_adminMenu() {
 add_submenu_page('edit.php', __('Code Blocks'), __('Code Blocks'), 'manage_options', 'CBS_admin', 'CBS_optPage');
}
add_action('admin_menu', 'CBS_adminMenu');
function CBSmakeItPretty($s){
 $s=str_replace('\"','"',$s);
 $s=str_replace("\'",'"',$s);
 return $s;
}
function CBS_optPage(){
 if(isset($_POST['submit'])){
  $isV=isset($_POST['visual']) ? "":"off";
  update_option("CBS_InVisual",$isV);
  $isTx=isset($_POST['text']) ? "":"off";
  update_option("CBS_InText",$isTx);
  update_option("CBS_StartCode",$_POST['before']);
  update_option("CBS_EndCode",$_POST['after']);
  $isPt=isset($_POST['ptag']) ? "":"off";
  update_option("CBS_PTag",$isPt);
  if(!file_put_contents(WP_PLUGIN_DIR. '/code-blocks/editor-style.css', $_POST['css']) && $_POST['css']!=""){
   echo '<div id="message" class="error"><p>Failed To Save Custom CSS. Make Sure you have Write permission in <b>wp-contents/plugins</b> folder.</p></div>';
  }else{
   echo '<div id="message" class="updated"><p>Saved Settings</p></div>';
  }
 }
 $startCode=get_option("CBS_StartCode")=="" ? "<pre><code>":get_option("CBS_StartCode");
 $endCode=get_option("CBS_EndCode")=="" ? "</code></pre>":get_option("CBS_EndCode");
 $vchecked=get_option("CBS_InVisual")=="" ? "checked='checked'":"";
 $tchecked=get_option("CBS_InText")=="" ? "checked='checked'":"";
 $pchecked=get_option("CBS_PTag")=="" ? "checked='checked'":"";
?>
 <h2>Configure Code Blocks</h2>
 <div id="poststuff">
  <div id="post-body" class="metabox-holder columns-2">
   <div id="postbox-container-1" class="postbox-container">
    <div class="postbox">
     <div class="inside">
     <h2>Donate</h2>
     <p>I'm 14 and I need donations to create more plugins.</p>
     <p>Please consider a donation for the improvement of this plugin and for future awesome plugins.</p>
      <form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top">
       <input type="hidden" name="cmd" value="_s-xclick">
       <input type="hidden" name="hosted_button_id" value="ZYQWUZ2B8ZXXA">
       <input type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_donateCC_LG.gif" border="0" name="submit" alt="PayPal – The safer, easier way to pay online.">
       <img alt="" border="0" src="https://www.paypalobjects.com/en_GB/i/scr/pixel.gif" width="1" height="1">
      </form>
     </div>
    </div>
   </div>
   <div id="postbox-container-2" class="postbox-container">
    <form action="" method="POST">
     <div style="font-size:16px;line-height:2em;" class="postbox">
      <div class="inside">
       <h3 class="hndle"><span>Display</span></h3><br/>
       <input type="checkbox" <?echo $vchecked;?> name="visual"/>Enable Code Block Button in Visual Editor<br/><br/>
       <input type="checkbox" <?echo $tchecked;?> name="text"/>Enable Code Block Button in Text Editor
      </div>
     </div>
     <div style="font-size:16px;margin-top:15px;line-height:2em;" class="postbox">
      <div class="inside">
       <h3 class="hndle"><span>Content</span></h3>
       <div style="font-size: 15px;margin: 10px;font-weight: bold;">Starting Code</div>
       <textarea name="before" style="display:block;"><?echo CBSmakeItPretty($startCode);?></textarea>
       <div style="font-size: 15px;margin: 10px;font-weight: bold;">End Code</div>
       <textarea name="after"><?echo CBSmakeItPretty($endCode);?></textarea>
       <style>
       .postbox .inside textarea{resize: vertical;width: 100%;height:100px;}
       </style>
      </div>
     </div>
     <div style="font-size:16px;margin-top:15px;line-height:2em;" class="postbox">
      <div class="inside">
       <h3 class="hndle"><span>Custom CSS</span></h3><br/>
       <div>You can add any custom CSS that will be applied on the <b>Visual</b> Editor. (Visualization)</div><br/>
       <textarea name="css"><?echo file_get_contents(WP_PLUGIN_DIR. '/code-blocks/editor-style.css');?></textarea>
      </div>
     </div>
     <div style="font-size:16px;margin-top:15px;line-height:2em;" class="postbox">
      <div class="inside">
       <h3 class="hndle"><span>Other Settings</span></h3><br/>
       <input type="checkbox" <?echo $pchecked;?> name="ptag"/>Remove Paragraph <b>&lt;p&gt;</b> tags from code. (Recommended)<br/>
       <p>
        When you wrap a code in post to a code block, the Paragraph (<b>&lt;p&gt;</b>) tags will enter in to the code block. Do you want to remove it ?
       </p>
       <p>Example :</p>
       <blockquote>&lt;code&gt;Other Code&lt;p&gt;Codes In Paragraphs&lt;/p&gt;&lt;/code&gt;</blockquote>
       <p>to :</p>
       <blockquote>&lt;code&gt;Other Code&lt;br/&gt;Codes In Paragraphs&lt;/code&gt;</blockquote>
      </div>
     </div>
     <span style="display:inline-block;vertical-align:middle;">The Icon of Code Me button is&nbsp;&nbsp;&nbsp;</span>
     <img style="display:inline-block;vertical-align:middle;" src="<?echo plugins_url().'/code-blocks/tool-icon.png';?>"/>
     <br/><button name="submit">Save Settings</button>
    </form>
   </div>
  </div>
 </div>
<? 
}
/* Visual Editor */
add_action('admin_init', 'CBS_add_button');
function CBS_add_button() {
 if(current_user_can('edit_posts') && current_user_can('edit_pages') && get_user_option('rich_editing') == 'true' && get_option("CBS_InVisual")!="off"){
  add_filter('mce_external_plugins', 'CBS_add_plugin');
  add_filter('mce_buttons', 'CBS_register_button');
 }
}
function CBS_register_button( $buttons ) {
 array_push($buttons, "CBSbutton");
 return $buttons;
}
function CBS_add_plugin( $plugin_array ) {	
 $url = plugins_url()."/code-blocks";
 $startCode=get_option("CBS_StartCode")=="" ? "<pre><code>":get_option("CBS_StartCode");
 $endCode=get_option("CBS_EndCode")=="" ? "</code></pre>":get_option("CBS_EndCode");
 $plugin_array["CBSbutton"] = $url.'/button.php?start='.urlencode(CBSmakeItPretty($startCode)).'&end='.urlencode(CBSmakeItPretty($endCode))."&nop=".get_option("CBS_PTag");
 return $plugin_array;
}
/* HTML Text Editor */
function CBS_teButton(){ 	  
 //Remove Linebreaks
 $startCode=get_option("CBS_StartCode")=="" ? "<pre><code>":get_option("CBS_StartCode");
 $endCode=get_option("CBS_EndCode")=="" ? "</code></pre>":get_option("CBS_EndCode");
 $right_tag = CBSmakeItPretty(str_replace("\r\n","",$endCode));
 $left_tag = CBSmakeItPretty(str_replace("\r\n","",$startCode));
 if(get_option("CBS_InText")!="off" && get_current_screen()->base!="" && get_current_screen()->base=="post"){
  $content  = '<script type="text/javascript">';
  $content .= "if(typeof QTags != 'undefined'){QTags.addButton( 'CBSbutton', 'Code Block', '".$left_tag."', '".$right_tag."' );}";
  $content .= "</script>";  
  echo $content;
 }	  
}
/* Text Editor functions */
function CBS_init(){
 if (current_user_can('edit_posts') && current_user_can('edit_pages')) {
  add_action('admin_print_footer_scripts',  'CBS_teButton');
 }
}
add_action("admin_init", "CBS_init");
function CBS_custom_css($wp) {	
 $url = plugins_url()."/code-blocks";
 $wp .= ',' . $url.'/editor-style.css';
 return $wp;
}
add_filter('mce_css','CBS_custom_css');
?>