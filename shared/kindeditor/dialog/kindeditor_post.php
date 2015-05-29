<?php

require_once(dirname(__FILE__)."/config.php");
require_once(dirname(__FILE__)."/../image.func.php");

require_once(dirname(__FILE__)."/../json.class.php");

function alert($msg) {
	$json = new Services_JSON();
	echo $json->encode(array('error' => 1, 'message' => $msg));
	exit;
}

$ext_arr = array(
	'image' => array('gif', 'jpg', 'jpeg', 'png', 'bmp'),
	'flash' => array('swf', 'flv'),
	'media' => array('swf', 'flv', 'mp3', 'wav', 'wma', 'wmv', 'mid', 'avi', 'mpg', 'asf', 'rm', 'rmvb'),
	'file' => array('doc', 'docx', 'xls', 'xlsx', 'ppt', 'htm', 'html', 'txt', 'zip', 'rar', 'gz', 'bz2'),
);

$media_type = array(
	'image' => 1,
	'flash' => 2,
	'media' => 3,
	'file' => 4,
);

if(empty($activepath))
{
    $activepath ='';
    $activepath = str_replace('.', '', $activepath);
    $activepath = preg_replace("#\/{1,}#", '/', $activepath);
    if(strlen($activepath) < strlen($cfg_image_dir))
    {
        $activepath = $cfg_image_dir;
    }
}

$ismarkup = isset($ismarkup)? $ismarkup : 0;

$dir_name = empty($_GET['dir']) ? 'image' : trim($_GET['dir']);

if(empty($imgFile))
{
    $imgFile='';
}

if(!is_uploaded_file($imgFile))
{
	alert(gb2utf8("你没有选择上传的文件!".$imgFile));
}
$CKEditorFuncNum = (isset($CKEditorFuncNum))? $CKEditorFuncNum : 1;
$imgfile_name = $_FILES['imgFile']['name'];
$imgfile_name = trim(preg_replace("#[ \r\n\t\*\%\\\/\?><\|\":]{1,}#", '', $imgfile_name));

//检查目录名
$dir_name = empty($_GET['dir']) ? 'image' : trim($_GET['dir']);
if (empty($ext_arr[$dir_name])) {
	alert("目录名不正确。");
}
//获得文件扩展名
$temp_arr = explode(".", $imgfile_name);
$file_ext = array_pop($temp_arr);
$file_ext = trim($file_ext);
$file_ext = strtolower($file_ext);
//检查扩展名
if (in_array($file_ext, $ext_arr[$dir_name]) === false) {
	alert(gb2utf8("上传文件扩展名是不允许的扩展名。\n只允许" . implode(",", $ext_arr[$dir_name]) . "格式。"));
}

$nowtme = time();
$imgfile_type = $_FILES['imgFile']['type'];
$imgfile_type = strtolower(trim($imgfile_type));

$mdir = MyDate($cfg_addon_savetype, $nowtme);
if(!is_dir($cfg_basedir.$activepath."/$mdir"))
{
    MkdirAll($cfg_basedir.$activepath."/$mdir",$cfg_dir_purview);
    CloseFtp();
}
$filename_name = $cuserLogin->getUserID().'-'.dd2char(MyDate("ymdHis", $nowtme).mt_rand(100,999));
$filename = $mdir.'/'.$filename_name;
$fs = explode('.', $imgfile_name);
$filename = $filename.'.'.$fs[count($fs)-1];
$filename_name = $filename_name.'.'.$fs[count($fs)-1];
$fullfilename = $cfg_basedir.$activepath."/".$filename;
move_uploaded_file($imgFile, $fullfilename) or die("上传文件到 $fullfilename 失败！");
// 远程同步到附件服务器
if($cfg_multiserv['rmmedia'] == 1)
{
	if(!$ftp->_is_conn()) $ftp->connect($cfg_multiserv);
    //分析远程文件路径
    $remotefile = str_replace(DEDEROOT, '', $fullfilename);
    $localfile = '../..'.$remotefile;
    //创建远程文件夹
    $remotedir = preg_replace('/[^\/]*\.(jpg|gif|bmp|png)/', '', $remotefile);
    $ftp->rmkdir($remotedir);
    $ftp->upload($localfile, $remotefile);
}

@unlink($imgfile);
if(empty($resize))
{
    $resize = 0;
}
if($resize==1)
{
    if(in_array($imgfile_type, $cfg_photo_typenames))
    {
        ImageResize($fullfilename, $iwidth, $iheight);
    }
}
else
{
    if(in_array($imgfile_type, $cfg_photo_typenames))
    {
        if($ismarkup==1) WaterImg($fullfilename, 'up');
    }
}

$info = '';
$sizes[0] = 0; $sizes[1] = 0;
if($dir_name == 'image')
{
	$sizes = getimagesize($fullfilename, $info);
}

$imgwidthValue = $sizes[0];
$imgheightValue = $sizes[1];
$imgsize = filesize($fullfilename);
$media_type = $media_type[$dir_name];

$inquery = "INSERT INTO `#@__uploads`(arcid,title,url,mediatype,width,height,playtime,filesize,uptime,mid)
  VALUES ('0','$filename','".$activepath."/".$filename."','{$media_type}','$imgwidthValue','$imgheightValue','0','{$imgsize}','{$nowtme}','".$cuserLogin->getUserID()."'); ";
$dsql->ExecuteNoneQuery($inquery);
$fid = $dsql->GetLastID();
AddMyAddon($fid, $activepath.'/'.$filename);
$CKUpload = isset($CKUpload)? $CKUpload : FALSE;

$json = new Services_JSON();
echo $json->encode(array('error' => 0, 'url' => $activepath."/$mdir/".$filename_name));
exit();