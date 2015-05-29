/*******************************************************************************
* KindEditor - WYSIWYG HTML Editor for Internet
* Copyright (C) 2006-2011 kindsoft.net
* 
* 站长图库 - http://www.zztuku.com
*
* @author Roddy <fmamcn@vip.qq.com>
* @site http://www.kindsoft.net/
* @licence http://www.kindsoft.net/license.php
*******************************************************************************/




function runCode(obj) {
        var winname = window.open('', "_blank", '');
        winname.document.open('text/html', 'replace');
winname.opener = null // 防止代码对论谈页面修改
        winname.document.write(obj.value);
        winname.document.close();
}
function saveCode(obj) {
        var winname = window.open('', '_blank', 'top=10000');
        winname.document.open('text/html', 'replace');
        winname.document.write(obj.value);
        winname.document.execCommand('saveas','','code.htm');
        winname.close();
}
function copycode(obj) {
if(obj.style.display != 'none') {
   var rng = document.body.createTextRange();
   rng.moveToElementText(obj);
   rng.scrollIntoView();
   rng.select();
   rng.execCommand("Copy");
   rng.collapse(false);
   alert('代码已复制到剪切板');
}
}