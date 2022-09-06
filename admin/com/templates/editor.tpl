<script src="includes/js/jquery/plugins/jquery.plugin.js"></script>
<script src="includes/js/imodal/imodal.js"></script>
<!-- <script src="includes/js/jquery/plugins/jquery.iModal.js"></script> -->
<script src="includes/js/jquery/plugins/jquery.tableSelector.js"></script>
<script src="includes/js/jquery/plugins/jquery.window.js"></script>
<script src="includes/js/jquery/plugins/jquery.window-extensions.js"></script>
<link rel="stylesheet" href="includes/js/imodal/imodal.css" type="text/css" media="screen" />


<script>
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
	      inline_styles : true,
		  selector: '#{$editor.ElementId}',
		  width : "{$editor.Width}",
		  height : "{$editor.Height}",
		  theme: 'silver',
		  resize: 'both',
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
		  loggedInUserID : '{$editor.LoggedInUserId}',
		  relative_urls: false,
	      document_base_url : "{$editor.AppUrl}",
		  plugins: [
			'advlist autolink lists link image charmap print preview hr anchor pagebreak',
			'searchreplace wordcount visualblocks visualchars code fullscreen fullpage',
			'insertdatetime media nonbreaking save table directionality emoticons',
			'template paste textpattern imagetools codesample table toc noneditable',
			'help quickbars responsive_filemanager'
		  ],
		  convert_urls : false,
		  paste_as_text: false,
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
          /*automatic_uploads: true,
		  file_picker_types: 'image media',
		  file_picker_callback: function (cb, value, meta) {
			var input = document.createElement('input');
			input.setAttribute('type', 'file');
			input.setAttribute('accept', 'image/*');
			input.onchange = function () {
			  var file = this.files[0];
			  var reader = new FileReader();
			  reader.onload = function () {
				var id = 'blobid' + (new Date()).getTime();
				var blobCache =  tinymce.activeEditor.editorUpload.blobCache;
				var base64 = reader.result.split(',')[1];
				var blobInfo = blobCache.create(id, file, base64);
				blobCache.add(blobInfo);
				cb(blobInfo.blobUri(), { title: file.name });
			  };
			  reader.readAsDataURL(file);
			};
			input.click();
		  },*/
		  image_advtab: true,
		  file_picker_types: 'image media',
		  external_filemanager_path: "{$editor.AppUrl}/admin/includes/js/tiny_mce/plugins/responsive_filemanager/filemanager/",
		  filemanager_title: "Image Manager" ,
		  external_plugins: { 
			"filemanager" : "{$editor.AppUrl}/admin/includes/js/tiny_mce/plugins/responsive_filemanager/filemanager/plugin.min.js",
		  },
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