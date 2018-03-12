/**
 * @license Copyright (c) 2003-2017, CKSource - Frederico Knabben. All rights reserved.
 * For licensing, see LICENSE.md or http://ckeditor.com/license
 */

CKEDITOR.editorConfig = function( config ) {
	base_path = 'http://localhost/creidsadmin/v3.0/multiauth/public';
	
	config.filebrowserBrowseUrl = base_path + '/js/ckeditor/kcfinder/browse.php?type=files';
	config.filebrowserImageBrowseUrl = base_path + '/js/ckeditor/kcfinder/browse.php?type=images';
	config.filebrowserFlashBrowseUrl = base_path + '/js/ckeditor/kcfinder/browse.php?type=flash';
	config.filebrowserUploadUrl = base_path + '/js/ckeditor/kcfinder/upload.php?type=files';
	config.filebrowserImageUploadUrl = base_path + '/js/ckeditor/kcfinder/upload.php?type=images';
	config.filebrowserFlashUploadUrl = base_path + '/js/ckeditor/kcfinder/upload.php?type=flash';
	config.extraPlugins = 'youtube';
	config.extraPlugins = 'video';
	
	config.toolbar = [
		{ name: 'document', items: [ 'Source', '-', 'Save', 'NewPage', 'Preview', 'Print', '-', 'Templates' ] },
		{ name: 'clipboard', items: [ 'Cut', 'Copy', 'Paste', 'PasteText', 'PasteFromWord', '-', 'Undo', 'Redo' ] },
		{ name: 'editing', items: [ 'Find', 'Replace', '-', 'SelectAll', '-', 'Scayt' ] },
		{ name: 'forms', items: [ 'Form', 'Checkbox', 'Radio', 'TextField', 'Textarea', 'Select', 'Button', 'ImageButton', 'HiddenField', 'Smiley' ] },
		'/',
		{ name: 'basicstyles', items: [ 'Bold', 'Italic', 'Underline', 'Strike', 'Subscript', 'Superscript', '-', 'CopyFormatting', 'RemoveFormat' ] },
		{ name: 'paragraph', items: [ 'NumberedList', 'BulletedList', '-', 'Outdent', 'Indent', '-', 'Blockquote', 'CreateDiv', '-', 'JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock', '-', 'BidiLtr', 'BidiRtl', 'Language' ] },
		{ name: 'links', items: [ 'Link', 'Unlink', 'Anchor' ] },
		{ name: 'insert', items: [ 'Image', 'Youtube', 'Video', 'Flash', 'Table', 'HorizontalRule', 'SpecialChar', 'PageBreak', 'Iframe' ] },
		'/',
		{ name: 'styles', items: [ 'Styles', 'Format', 'Font', 'FontSize' ] },
		{ name: 'colors', items: [ 'TextColor', 'BGColor' ] },
		{ name: 'tools', items: [ 'Maximize', 'ShowBlocks' ] },
		{ name: 'about', items: [ 'About' ] }
	];
};
