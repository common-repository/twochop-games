function deletePlay() {
    return confirm('Delete the existing Play button? This is not reversible.');
}

function reinsertPlay() {
    return true;
}

function copyPlay() {
	var txt = document.getElementByID("twochop_id_copy");
	txt.value = txt.value.replace(/^\s+|\s+$/g,""); // trims
	if (txt.value == '')
	{
		alert ('Enter valid Play ID!');
		return false;
	}
	return true;
}

function getshortcodepattern(idtype, id) {
    var re = new RegExp('\\[twochop-public\\s+idtype=\"' + idtype + '\"\\s+id=\"' + id + '\"\\]');
    return re;
}

function getshortcode(idtype, id) {
    var sc = '\\[twochop-public idtype=\"' + idtype + '\"\\ id=\"' + id + '\"\\]';
    return sc;
}

function deleteTcShortcode(idtype, id) {
    var re = getshortcodepattern(idtype, id);

    var win = window.dialogArguments || opener || parent || top;
    var tinyMCE = win.tinyMCE;
    var tinymce = win.tinymce;
    var edCanvas = win.edCanvas;
    var ed;
    if (typeof tinyMCE != 'undefined' && (ed = win.tinyMCE.activeEditor) && !ed.isHidden()) {
        var content = ed.getContent();
        ed.execCommand('mceSetContent', false, content.replace(re, ''));

    } else if (typeof win.edInsertContent == 'function') {
        var content = edCanvas.value;
        edCanvas.value = content.replace(re, '');
    }
}

function insertTcShortcode(idtype, id) {
    var shortcode_public = '[twochop-public idtype=\"' + idtype + '\" id=\"' + id + '\"]';

    var win = window.dialogArguments || opener || parent || top;
    var tinyMCE = win.tinyMCE;
    var tinymce = win.tinymce;
    var edCanvas = win.edCanvas;
    var ed;
    if (typeof tinyMCE != 'undefined' && (ed = win.tinyMCE.activeEditor) && !ed.isHidden()) {
        ed.focus();
        if (tinymce.isIE)
            ed.selection.moveToBookmark(tinymce.EditorManager.activeEditor.windowManager.bookmark);

        ed.selection.collapse(true);

        ed.execCommand('mceInsertContent', false, shortcode_public);
    } else if (typeof win.edInsertContent == 'function') {
        win.edInsertContent(edCanvas, shortcode_public);
    }
}

function checkTcShortcode(idtype, id) {
    var re = getshortcodepattern(idtype, id);

    var win = window.dialogArguments || opener || parent || top;

    var tinyMCE = win.tinyMCE;
    var tinymce = win.tinymce;
    var edCanvas = win.edCanvas;
    var ed;
    if (typeof tinyMCE != 'undefined' && (ed = win.tinyMCE.activeEditor) && !ed.isHidden()) {
        var content = ed.getContent();
        var ifmatch = content.match(re);
        if (ifmatch == null) {
            document.getElementById('cmdActionRi').style.display = '';
        } else {
            document.getElementById('cmdActionRi').style.display = 'none';
        }
    } else if (typeof win.edInsertContent == 'function') {
        var content = edCanvas.value;
        var ifmatch = content.match(re);
        if (ifmatch == null) {
            document.getElementById('cmdActionRi').style.display = '';
        } else {
            document.getElementById('cmdActionRi').style.display = 'none';
        }
    }
}


function getPostInfo() {
    var win = window.dialogArguments || opener || parent || top;

    jtc_post_title = win.document.getElementById('title').value;

    var tinyMCE = win.tinyMCE;
    var tinymce = win.tinymce;
    var edCanvas = win.edCanvas;
    var ed;
    if (typeof tinyMCE != 'undefined' && (ed = win.tinyMCE.activeEditor) && !ed.isHidden()) {
        jtc_post_content = ed.getContent();

    } else if (typeof win.edInsertContent == 'function') {
        jtc_post_content = edCanvas.value;
    }
}


function twochop_form1_submit() {
    // send post information only if user has opted for it
    if (document.getElementById("twochop_ispostinfo").checked) {
        document.getElementById("twochop_post_title").value = escape(jtc_post_title);
        document.getElementById("twochop_post_content").value = escape(jtc_post_content);
    }
    return true;
}

function postform_load() {
    if(jtc_action=="updated")
    {
	    if(jtc_optype =="nw")
	    {
		    insertTcShortcode(jtc_idtype,jtc_id);
	    }
    }
}

function preform_load() {
    document.getElementById("dvHint").innerHTML = 'Hint: Place the text-cursor where you want the TwoChop play button to be inserted.';

    if (jtc_cmdAction == 'delete') {
        deleteTcShortcode(jtc_del_idtype, jtc_del_id);
    } else if (jtc_cmdAction == 'reinsert') {
        insertTcShortcode(jtc_idtype, jtc_id);
    } else if (jtc_cmdAction == 'copy') {
        insertTcShortcode(jtc_idtype, jtc_id);
    }
    if ((jtc_optype == 'ed') || (jtc_optype == 'cp'))  {
        checkTcShortcode(jtc_idtype, jtc_id);
    }

    getPostInfo();
}

function closeDlgWin() {
    var win = window.dialogArguments || opener || parent || top;

    if (typeof (win.tc_closeDialog) == 'function') {
        win.tc_closeDialog();
    }
}

var jtc_optype = '';
var jtc_action = '';
var jtc_cmdAction = '';
var jtc_idtype = '';
var jtc_id = '';
var jtc_del_idtype = '';
var jtc_del_id = '';
var jtc_post_author = '';
var jtc_post_title = '';
var jtc_post_content = '';
var jtc_reserved = '';

