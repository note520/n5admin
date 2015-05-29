<?php   if(!defined('DEDEINC')) exit('dedecms');
/**
 * 管理员后台基本函数
 *
 * @version        $Id:inc_fun_funAdmin.php 1 13:58 2010年7月5日Z tianya $
 * @package        DedeCMS.Libraries
 * @copyright      Copyright (c) 2007 - 2012, DesDev, Inc.
 * @license        http://help.dedecms.com/usersguide/license.html
 * @link           http://www.dedecms.com
 */

/**
 *  获取拼音信息
 *
 * @access    public
 * @param     string  $str  字符串
 * @param     int  $ishead  是否为首字母
 * @param     int  $isclose  解析后是否释放资源
 * @return    string
 */
function SpGetPinyin($str, $ishead=0, $isclose=1)
{
    global $pinyins;
    $restr = '';
    $str = trim($str);
    $slen = strlen($str);
    if($slen < 2)
    {
        return $str;
    }
    if(count($pinyins) == 0)
    {
        $fp = fopen(DEDEINC.'/data/pinyin.dat', 'r');
        while(!feof($fp))
        {
            $line = trim(fgets($fp));
            $pinyins[$line[0].$line[1]] = substr($line, 3, strlen($line)-3);
        }
        fclose($fp);
    }
    for($i=0; $i<$slen; $i++)
    {
        if(ord($str[$i])>0x80)
        {
            $c = $str[$i].$str[$i+1];
            $i++;
            if(isset($pinyins[$c]))
            {
                if($ishead==0)
                {
                    $restr .= $pinyins[$c];
                }
                else
                {
                    $restr .= $pinyins[$c][0];
                }
            }else
            {
                $restr .= "_";
            }
        }else if( preg_match("/[a-z0-9]/i", $str[$i]) )
        {
            $restr .= $str[$i];
        }
        else
        {
            $restr .= "_";
        }
    }
    if($isclose==0)
    {
        unset($pinyins);
    }
    return $restr;
}


/**
 *  创建目录
 *
 * @access    public
 * @param     string  $spath 目录名称
 * @return    string
 */
function SpCreateDir($spath)
{
    global $cfg_dir_purview,$cfg_basedir,$cfg_ftp_mkdir,$isSafeMode;
    if($spath=='')
    {
        return true;
    }
    $flink = false;
    $truepath = $cfg_basedir;
    $truepath = str_replace("\\","/",$truepath);
    $spaths = explode("/",$spath);
    $spath = "";
    foreach($spaths as $spath)
    {
        if($spath=="")
        {
            continue;
        }
        $spath = trim($spath);
        $truepath .= "/".$spath;
        if(!is_dir($truepath) || !is_writeable($truepath))
        {
            if(!is_dir($truepath))
            {
                $isok = MkdirAll($truepath,$cfg_dir_purview);
            }
            else
            {
                $isok = ChmodAll($truepath,$cfg_dir_purview);
            }
            if(!$isok)
            {
                echo "创建或修改目录：".$truepath." 失败！<br>";
                CloseFtp();
                return false;
            }
        }
    }
    CloseFtp();
    return true;
}

function jsScript($js)
{
	$out = "<script type=\"text/javascript\">";
	$out .= "//<![CDATA[\n";
	$out .= $js;
	$out .= "\n//]]>";
	$out .= "</script>\n";

	return $out;
}

/**
 *  获取编辑器
 *
 * @access    public
 * @param     string  $fname 表单名称
 * @param     string  $fvalue 表单值
 * @param     string  $nheight 内容高度
 * @param     string  $etype 编辑器类型
 * @param     string  $gtype 获取值类型
 * @param     string  $isfullpage 是否全屏
 * @return    string
 */
function SpGetEditor($fname,$fvalue,$nheight="350",$etype="Basic",$gtype="print",$isfullpage="false",$bbcode=false)
{
    global $cfg_ckeditor_initialized,$photo_markup;
    if(!isset($GLOBALS['cfg_html_editor']))
    {
        $GLOBALS['cfg_html_editor']='fck';
    }
    if($gtype=="")
    {
        $gtype = "print";
    }
    if($GLOBALS['cfg_html_editor']=='fck')
    {
        require_once(DEDEINC.'/FCKeditor/fckeditor.php');
        $fck = new FCKeditor($fname);
        $fck->BasePath        = $GLOBALS['cfg_cmspath'].'/include/FCKeditor/' ;
        $fck->Width        = '100%' ;
        $fck->Height        = $nheight ;
        $fck->ToolbarSet    = $etype ;
        $fck->Config['FullPage'] = $isfullpage;
        if($GLOBALS['cfg_fck_xhtml']=='Y')
        {
            $fck->Config['EnableXHTML'] = 'true';
            $fck->Config['EnableSourceXHTML'] = 'true';
        }
        $fck->Value = $fvalue ;
        if($gtype=="print")
        {
            $fck->Create();
        }
        else
        {
            return $fck->CreateHtml();
        }
    }
    else if($GLOBALS['cfg_html_editor']=='ckeditor')
    {
        require_once(DEDEINC.'/ckeditor/ckeditor.php');
        $CKEditor = new CKEditor();
        $CKEditor->basePath = $GLOBALS['cfg_cmspath'].'/include/ckeditor/' ;
        $config = $events = array();
        $config['extraPlugins'] = 'dedepage,multipic,addon,dewplayer';
		if($bbcode)
		{
			$CKEditor->initialized = true;
			$config['extraPlugins'] .= ',bbcode';
			$config['fontSize_sizes'] = '30/30%;50/50%;100/100%;120/120%;150/150%;200/200%;300/300%';
			$config['disableObjectResizing'] = 'true';
			$config['smiley_path'] = $GLOBALS['cfg_cmspath'].'/images/smiley/';
			// 获取表情信息
			require_once(DEDEDATA.'/smiley.data.php');
			$jsscript = array();
			foreach($GLOBALS['cfg_smileys'] as $key=>$val)
			{
				$config['smiley_images'][] = $val[0];
				$config['smiley_descriptions'][] = $val[3];
				$jsscript[] = '"'.$val[3].'":"'.$key.'"';
			}
			$jsscript = implode(',', $jsscript);
			echo jsScript('CKEDITOR.config.ubb_smiley = {'.$jsscript.'}');
		}

        $GLOBALS['tools'] = empty($toolbar[$etype])? $GLOBALS['tools'] : $toolbar[$etype] ;
        $config['toolbar'] = $GLOBALS['tools'];
        $config['height'] = $nheight;
        $config['skin'] = 'kama';
		if($etype == 'Member')
		{
			$config['filebrowserImageBrowseUrl'] = 'uploads_select.php';
			$config['filebrowserImageUploadUrl'] = 'uploads_add.php?dopost=save&title=editpic';
		}
        if($photo_markup == 1)
        {
        	$config['filebrowserImageUploadUrl'] = '../include/dialog/select_images_post.php?ismarkup=1';
            $config['iswater'] = 'checked';
        } else {
        	$config['filebrowserImageUploadUrl'] = '../include/dialog/select_images_post.php';
            $config['iswater'] = '';
        }
        $CKEditor->returnOutput = TRUE;
        $code = $CKEditor->editor($fname, $fvalue, $config, $events);
        if($gtype=="print")
        {
            echo $code;
        }
        else
        {
            return $code;
        }
    }
	else  if($GLOBALS['cfg_html_editor']=='kindeditor'){
		$fvalue =htmlspecialchars($fvalue);
		$uploadJson = "../include/dialog/kindeditor_post.php";
		$fileManagerJson = "../include/dialog/kindeditor_manager.php";
		$allowFileManager = 'true';
		$extendconfig = '';
		if($etype == 'Member' || $etype == 'MemberLit' || $etype == 'Diy')
		{
			$uploadJson = "";
			$fileManagerJson = "";
			$allowFileManager = 'false';
			$extendconfig = 'allowImageUpload : false,';
			$extendconfig .= 'allowFlashUpload : false,';
			$extendconfig .= 'allowMediaUpload : false,';
			$extendconfig .= 'allowFileUpload : false,';
		}
		
		$items['Member'] = "[
		'source', '|', 'undo', 'redo', '|', 'preview', 'print', 'template', 'code', 'runCode', 'cut', 'copy', 'paste',
		'plainpaste', 'wordpaste', '|', 'justifyleft', 'justifycenter', 'justifyright',
		'justifyfull', 'insertorderedlist', 'insertunorderedlist', 'indent', 'outdent', 'subscript',
		'superscript', 'clearhtml', 'quickformat', 'selectall', '|', 'fullscreen', '/',
		'formatblock', 'fontname', 'fontsize', '|', 'forecolor', 'hilitecolor', 'bold',
		'italic', 'underline', 'strikethrough', 'lineheight', 'removeformat', '|', 'image', 'multiimage',
		'flash', 'media', 'insertfile', 'table', 'hr', 'emoticons', 'baidumap', 'pagebreak',
		'anchor', 'link', 'unlink', '|', 'about']";
		
		$items['Small'] = $items['MemberLit'] = $items['Diy']= "[
		'source', '|', 'fontname', 'fontsize', '|', 'forecolor', 'hilitecolor', 'bold', 'italic', 'underline',
		'removeformat', '|', 'justifyleft', 'justifycenter', 'justifyright', 'insertorderedlist',
		'insertunorderedlist', '|', 'emoticons', 'image', 'multiimage', 'link', 'unlink']";
		
		$itemconfig = '';
		if(isset($items[$etype]))
		{
			$itemconfig = "items :{$items[$etype]},";
		}
		
		$code = <<<EOT
	<link rel="stylesheet" href="/include/kindeditor/themes/default/default.css" />
	<link rel="stylesheet" href="/include/kindeditor/plugins/code/prettify.css" />
	<script src="/include/kindeditor/kindeditor.js"></script>
	<script src="/include/kindeditor/lang/zh_CN.js"></script>
	<script src="/include/kindeditor/plugins/code/prettify.js"></script>
	<script type="text/javascript">
		KindEditor.ready(function(K) {
			var editor1 = K.create('textarea[name="{$fname}"]', {
				cssPath : '../include/plugins/code/prettify.css',
				uploadJson : '$uploadJson',
				fileManagerJson : '$fileManagerJson',
				$extendconfig
				$itemconfig
				allowFileManager : {$allowFileManager}
			});
			prettyPrint();
		});
	</script>
	<textarea name="{$fname}" style="height:{$nheight}px;visibility:hidden;width: 100%;">{$fvalue}</textarea>
EOT;
		//echo $reval;
        if($gtype=="print")
        {
            echo $code;
        }
        else
        {
            return $code;
        }
	}
    else { 
        /*
        // ------------------------------------------------------------------------
        // 当前版本,暂时取消dedehtml编辑器的支持
        // ------------------------------------------------------------------------
        require_once(DEDEINC.'/htmledit/dede_editor.php');
        $ded = new DedeEditor($fname);
        $ded->BasePath        = $GLOBALS['cfg_cmspath'].'/include/htmledit/' ;
        $ded->Width        = '100%' ;
        $ded->Height        = $nheight ;
        $ded->ToolbarSet = strtolower($etype);
        $ded->Value = $fvalue ;
        if($gtype=="print")
        {
            $ded->Create();
        }
        else
        {
            return $ded->CreateHtml();
        }
        */
    }
}

/**
 *  获取更新信息
 *
 * @return    void
 */
function SpGetNewInfo()
{
    global $cfg_version,$dsql;
    $nurl = $_SERVER['HTTP_HOST'];
    if( preg_match("#[a-z\-]{1,}\.[a-z]{2,}#i",$nurl) ) {
        $nurl = urlencode($nurl);
    }
    else {
        $nurl = "test";
    }
    $phpv = phpversion();
    $sp_os = PHP_OS;
    $mysql_ver = $dsql->GetVersion();
    $offUrl = "http://www.de"."decms.com/newinfov57.php?version={$cfg_version}&formurl={$nurl}&phpver={$phpv}&os={$sp_os}&mysqlver={$mysql_ver}";
    return $offUrl;
}

?>