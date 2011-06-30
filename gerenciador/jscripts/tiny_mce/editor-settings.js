// JavaScript Document
	tinyMCE.init({
	mode : "textareas",
	//language : "pt_br",
	theme : "advanced",
	editor_selector : "mceAdvanced",
	
	plugins : "paste,preview,save,visualchars,nonbreaking,xhtmlxtras",
	
	//theme_advanced_buttons1 : "bold,italic,underline,bullist,numlist,undo,redo,link,unlink,|,cut,copy,paste,pastetext,pasteword,|,image,|,preview,|,",
	
	theme_advanced_buttons1 : "bold,italic,underline,bullist,numlist,undo,redo,link,unlink,|,cut,copy,paste,pastetext,pasteword,|,image,|,preview,visualchars,nonbreaking,code",
	
	// Salvar rascunho | Negrito | Itálico | underline | undo | redo | link | tirar link | cortar | copiar | colar | visual control characters | Insert non-breaking space | HTML
	
	theme_advanced_buttons2 : "",
	theme_advanced_buttons3 : "",
	theme_advanced_toolbar_location : "top",
	theme_advanced_toolbar_align : "left",
	theme_advanced_statusbar_location : ""
	//theme_advanced_resizing : true
});
	tinyMCE.init({
	mode : "textareas",
	theme : "advanced",
	editor_selector : "mceSimple",
	theme_advanced_buttons1 : "bold,italic,underline,separator,undo,redo,separator,link,unlink",
	theme_advanced_buttons2 : "",
	theme_advanced_buttons3 : "",
	theme_advanced_toolbar_location : "top",
	theme_advanced_toolbar_align : "left"
});