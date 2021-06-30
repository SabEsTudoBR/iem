
<script src="includes/js/jquery/plugins/jquery.plugin.js"></script>
<script src="includes/js/imodal/imodal.js"></script>
<!-- <script src="includes/js/jquery/plugins/jquery.iModal.js"></script> -->
<script src="includes/js/jquery/plugins/jquery.tableSelector.js"></script>
<script src="includes/js/jquery/plugins/jquery.window.js"></script>
<script src="includes/js/jquery/plugins/jquery.window-extensions.js"></script>
<link rel="stylesheet" href="includes/js/imodal/imodal.css" type="text/css" media="screen" />


<script>

/*tinyMCE.init({
	// General options
	mode : "exact",
	inline_styles : false,
	editor_selector : "InterspireEditor",
    formats: {
            alignleft: {editor_selector: 'span,em,i,b,strong', block: 'span', styles: {display: 'block', 'text-align':'left'}},
            aligncenter: {editor_selector: 'span,em,i,b,strong', block: 'span', styles: {display: 'block', 'text-align':'center'}},
            alignright: {editor_selector: 'span,em,i,b,strong', block: 'span', styles: {display: 'block', 'text-align':'right'}},
            alignfull: {editor_selector: 'span,em,i,b,strong', block: 'span', styles: {display: 'block', 'text-align':'full'}}
       },
	elements : "{$editor.ElementId}",
	convert_fonts_to_spans : false,
	force_hex_style_colors : false,
	fix_table_elements : true,
	fix_list_elements : true,
	fix_nesting : false,
	forced_root_block : false,
    valid_children : "+body[style]",
	theme : "advanced",
	//---
	// Theme options
	skin : "o2k7",
	skin_variant : "silver",
	theme_advanced_buttons1 : "undo,redo,|,bold,italic,underline,formatselect,fontselect,fontsizeselect,justifyleft,justifycenter,justifyright,justifyfull{$dynamicContentButton}",
	theme_advanced_buttons2 : "cut,copy,paste,pastetext,pasteword,|,cleanup,|,bullist,numlist,|,table,|,outdent,indent,|,interspiresurvey,link,unlink,anchor,image,media,|,forecolor,backcolor,|,|,code,syntaxhl,preview,|,cfbutton",
	theme_advanced_buttons3 : "",
	theme_advanced_buttons4 : "",
	theme_advanced_toolbar_location : "top",
	theme_advanced_toolbar_align : "left",
	theme_advanced_statusbar_location : "bottom",
	theme_advanced_resizing : true,
	relative_urls: false,
	document_base_url : "{$editor.AppUrl}",
	content_css : "{$editor.FullUrl}themes/advanced/skins/o2k7/content.css",
	//---
	plugins : "{$plugins}fullpage,safari,pagebreak,style,layer,table,save,advimage,advlink,media,searchreplace,print,contextmenu,paste,fullscreen,visualchars,nonbreaking,xhtmlxtras,template,inlinepopups,preview",
	external_image_list_url : "{$editor.AppUrl}/admin/index.php?Page=ImageManager&Action=getimageslist&Type={$editor.ImageDirType}&TypeId={$editor.ImageDirTypeId}",
	external_link_list_url  : "{$editor.AppUrl}/admin/index.php?Action=GetPredefinedLinkList",
	convert_urls : false,
	remove_script_host : false,
	cleanup : true,
	cleanup_on_startup : true,
	cleanup_callback : "Application.Modules.TinyMCE.customCleanup",
	verify_html : false,
	width : "{$editor.Width}",
	height : "{$editor.Height}",
	force_p_newlines : false,
	force_br_newlines : true,
	imgPathType : '{$editor.ImageDirType}',
	imgPathTypeId : '{$editor.ImageDirTypeId}',
	template_replace_values : {
	},
	setup : function(ed) {
		// Add a custom field button
		ed.addButton('cfbutton', {
			title : 'Custom Fields',
			image : '{$editor.AppUrl}/admin/images/mce_customfields.gif',
			onclick : function() {
				javascript: ShowCustomFields('html', 'myDevEditControl', '%%PAGE%%'); return false;
			}
		});
		{$dynamicContentPopup}
	}
});*/
if (tinymce.get('{$editor.ElementId}'))
{
	for (var i = tinymce.editors.length - 1 ; i > -1 ; i--)
    {
        tinyMCE.execCommand("mceRemoveEditor", true, tinymce.editors[i].id);
    }
}
setTimeout(function() {
tinymce.init({
		  mode : "exact",
	      inline_styles : false,
		  selector: '#{$editor.ElementId}',
		  width : "{$editor.Width}",
		  height : "{$editor.Height}",
		  theme: 'silver',
		  convert_fonts_to_spans : false,
		  force_hex_style_colors : false,
		  fix_table_elements : true,
		  fix_list_elements : true,
		  fix_nesting : false,
		  forced_root_block : false,
		  force_p_newlines : false,
		  force_br_newlines : true,
		  imgPathType : '{$editor.ImageDirType}',
		  imgPathTypeId : '{$editor.ImageDirTypeId}',
		  relative_urls: false,
	      document_base_url : "{$editor.AppUrl}",
		  plugins: [
			'advlist autolink lists link image charmap print preview hr anchor pagebreak',
			'searchreplace wordcount visualblocks visualchars code fullscreen fullpage',
			'insertdatetime media nonbreaking save table directionality emoticons',
			'template paste textpattern imagetools codesample table toc noneditable',
			'help quickbars'
		  ],
		  convert_urls : false,
		  paste_as_text: true,
		  remove_script_host : false,
		  cleanup : true,
		  cleanup_on_startup : true,
		  cleanup_callback : "Application.Modules.TinyMCE.customCleanup",
		  verify_html : false,
		  external_plugins: {
			"myoldplugin": "{$editor.AppUrl}/admin/includes/js/tiny_mce/plugins/inlinepopups/editor_plugin.js"
		   },
		   menubar : false,
		  toolbar1: 'insertfile undo redo | styleselect | fontselect | fontsizeselect | bold italic underline strikethrough | alignleft aligncenter alignright alignjustify | bullist numlist checklist | outdent indent | cut,copy,paste,pastetext,pasteword | forecolor backcolor | table | charmap emoticons | preview | image media template link anchor codesample | code | cfbutton | dctbutton | slbutton',
		  content_css: [],
		  setup : function(ed) {
			  // Add a custom field button
				ed.ui.registry.addButton('cfbutton', {
					name  : 'Custom Fields',
					text : 'Custom Fields',
					tooltip : 'Custom Fields',
					icon: 'plus',
					image : '{$editor.AppUrl}/admin/images/mce_customfields.gif',
					onAction : function() {
						javascript: ShowCustomFields('html', 'myDevEditControl', '%%PAGE%%'); return false;
					}
				});
				
				ed.ui.registry.addButton('dctbutton', {
					title : 'Dynamic Content Tags',
					text : 'Dynamic Content Tags',
					tooltip : 'Dynamic Content Tags',
					icon: 'plus',
					image : '{$editor.AppUrl}/admin/images/mce_dct_add.gif',
					onAction : function() {
						javascript: ShowDynamicContentTag('html', 'myDevEditControl', '%%PAGE%%'); return false;
					}
				});
				
				ed.ui.registry.addButton('slbutton', {
					title : 'Survey Link',
					text : 'Survey Link',
					tooltip : 'Survey Link',
					icon: 'plus',
					image : '{$editor.AppUrl}/admin/images/mce_dct_add.gif',
					onAction : function() {
						javascript: InsertSurveyLink('HtmlContent'); return false;
					}
				});
		  }
	 });
}, 50);
var UsingWYSIWYG = '%%GLOBAL_UsingWYSIWYG%%';
</script>

<textarea rows="10" id="{$editor.ElementId}" name="{$editor.ElementId}" class="InterspireEditor">
{$editor.HtmlContent}
</textarea>
