/**
 * ueRunCode运行代码插件基于百度UE编辑器
 * 978873550@qq.com
 * www.note520.com
 * 2014-07-09
 * 使用本插件请保留版权
 **/
(function(win){
        var fn={
            getId:function(str){
                return document.getElementById(str);
            },
            getName:function(str){
                return document.getElementsByName(str);
            },
            addEvent:function(obj,eType,fn,param,bool){
                var capTrue=!!bool;
                var ieEvent="on"+eType;
                if(obj.addEventListener){
                    obj.addEventListener(eType,fn,capTrue);//标准方法
                    return true;
                }else if(obj.attachEvent){
                    var r=obj.attachEvent(ieEvent,function(){
                        fn.apply(obj,arguments);//解决this指向问题，但删除存在一定的问题
                    });//针对ie
                    return r;
                }else{
                    obj[ieEvent] = fn;
                }
            },
            creatButton:function(str,i){
                var buttonNode=document.createElement("input");
                buttonNode.type="button";
                buttonNode.value=str;
                buttonNode.setAttribute("class","run-code-btn");
                buttonNode.name="mod_runCode_"+i;
                return buttonNode;
            },
            runCode:function(code,width,height){
                var w=width||800;
                var h=height||600;
                var win=window.open('','',"width="+w+",height="+h);
                var newDoc=win.document.open("text/html","replace");
                var txt=code;
                newDoc.write(txt);
                newDoc.close();
            },
            html:function (str) {
                return str ? str.replace(/&((g|l|quo)t|amp|#39|nbsp);/g, function (m) {
                    return {
                        '&lt;':'<',
                        '&amp;':'&',
                        '&quot;':'"',
                        '&gt;':'>',
                        '&#39;':"'",
                        '&nbsp;':' '
                    }[m]
                }) : '';
            }
        }
        var init={
            start:function(){
                var textAreaArr=fn.getName("runCode");
                for(var i=0;i<textAreaArr.length;i++){
                    var preCode=textAreaArr[i];
                    //创建外包容div
                    var wrap=document.createElement("div");
                    wrap.setAttribute("name","runCodeMod");
                    wrap.setAttribute("class","run-code-mod");
                    preCode.parentNode.insertBefore(wrap,preCode);
                    wrap.appendChild(preCode);
                    //创建button
                    var runButton=fn.creatButton("运行代码",i);
                    wrap.appendChild(runButton);
                    //获取文本
                    var text=fn.html(preCode.innerHTML);
                    preCode.innerHTML=null;
                    //创建textArea
                    var textArea=document.createElement("textarea");
                    textArea.name="codeTxt";
                    textArea.value=text;
                    preCode.appendChild(textArea);
                    //绑定事件
                    var runObj=preCode.nextSibling;
                    fn.addEvent(runObj,"click",function(){
                        var txt = this.previousSibling.firstChild.value;
                        fn.runCode(txt);
                    });
                }
                return this;
            }
     }
    var that={
       	 start:function(){
       		init.start();
       	 }
    };
    win.ueRunCode=that;
    return that;
})(window);