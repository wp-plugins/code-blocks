<?
header("Content-type: application/x-javascript");
?>
(function() {
 var start = '<?echo urldecode($_GET['start']);?>';
 var end = '<?echo urldecode($_GET['end']);?>';
 tinymce.create('tinymce.plugins.CBSbutton', {
  init : function(ed, url) {
   ed.addButton('CBSbutton', {
    title : 'Code Block',
    image : url+'/tool-icon.png',
    onclick : function() {
     var selection=ed.selection.getContent();
     <?
     if($_GET['nop']==""){
     ?>
     var selection=selection.replace(/\<p\>/g, "<br/>");
     var selection=selection.replace(/\<\/p\>/g, "");
     <?
     }
     ?>
     if(selection==""){
      tinymce.activeEditor.execCommand('mceInsertContent', false, start+"Code Here"+end);
     }else{
      ed.selection.setContent(start + selection + end);
      console.log(start + selection + end);
     }
    }
   });
  }
 });
 tinymce.PluginManager.add('CBSbutton', tinymce.plugins.CBSbutton);
})();