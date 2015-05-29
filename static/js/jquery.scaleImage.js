/*
解决了平时使用时要在图片显示出来后才能进行缩放时撑大布局的问题
///参数设置：
scaling     是否等比例自动缩放
width       图片最大高
height      图片最大宽
loadpic     加载中的图片路径
*/
jQuery.fn.ScaleImage=function(scaling,width,height,srcUrl){
	return this.each(function(){
		var t=$(this);
		var src=$(this).attr(srcUrl);
		//console.log(src);
		var img=new Image();
		img.src=src;
		//自动缩放图片
		var autoScaling=function(){
			if(scaling){
				if(img.width>0 && img.height>0){ 
			        if(img.width/img.height>=width/height){ 
			            if(img.width>width){ 
			                t.width(width); 
			                t.height((img.height*width)/img.width); 
			            }else{ 
			                t.width(img.width); 
			                t.height(img.height); 
			            } 
			        } 
			        else{ 
			            if(img.height>height){ 
			                t.height(height); 
			                t.width((img.width*height)/img.height); 
			            }else{ 
			                t.width(img.width); 
			                t.height(img.height); 
			            } 
			        } 
			    } 
			}	
		}
		//处理ff下会自动读取缓存图片
//		if(img.complete){
//		    //alert("getToCache!");
//			autoScaling();
//		    return;
//		}
		$(img).load(function(){
			autoScaling();
		});
	});
}