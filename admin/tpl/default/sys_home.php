<style tyle="text/css">
.sys-home-mod{padding:20px;}
.server-list li b{margin-right:10px;}
.server-list li p{margin:5px 0;}
</style>
<div class="sys-home-mod">
	<ul class="server-list">
		<li>
			<h3>环境配置信息:</h3>
            <p><b>PHP版本:</b><?php echo PHP_VERSION;?></p>
            <p><b>服务器端信息</b> <?PHP echo $_SERVER['SERVER_SOFTWARE']; ?></p>
            <p><b>服务器操作系统：</b> <?PHP echo PHP_OS; ?></p>
            <p><b>运行环境：</b> <?php echo $_SERVER['SERVER_SOFTWARE'];?></p>
            <p><b>mysql版本：</b><?php echo mysql_get_server_info();?></p>
            <p><b>上传限制：</b><?php $max_upload = ini_get("file_uploads") ? ini_get("upload_max_filesize") : "Disabled"; echo $max_upload;?></p>
            <p><b>服务器时间：</b><?php echo date("Y-m-d H:i:s",time());?></p>
         </li>
	</ul>
</div>