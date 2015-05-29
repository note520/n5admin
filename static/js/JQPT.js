/*
 * jQuery常用效果集合
 * JQPT 1.0.0
 * author:97873550@qq.com
 * webSite:www.note520.com
 * update:2013-10-22
 */
(function($,win){
    var JQPT= win.JQPT = win.JQPT||{
        global: win,
        version: "dev-1.0.0"
    };
    JQPT.trace = function()
    {
        var logs = Array.prototype.slice.call(arguments);
        if(typeof(console) != "undefined" && typeof(console.log) != "undefined") console.log(logs.join(" "));
    };
    /*默认的全局namespace为JQPT或J（当J没有被占据的情况下）。
     * */
    if(win.J == undefined) win.J = JQPT;
    if(win.trace == undefined)win.trace = JQPT.trace;
})(jQuery,window);

/*******************功能模块*******************/
/*
 * tabs模块
 * update:2013-10-22
 * */
JQPT.tabs=(function($){
    var opts;
    //构造函数
    var _that=function(){
        this.opt= null||{};
        return this;
    }
    var base =function(options){
        var agr={
            conf:{
                bigBox:".mod",
                titCell:".tit a",//导航元素
                mainCell:".cont",//内容元素的父层对象
                evt:"click",//触发方式 || mouseover：鼠标移过触发；|| click：鼠标点击触发；
                currentName:"current",//当前位置自动增加的class名称
                curr : 0,//默认的当前位置索引。0是第一个
                vis:1,//visible
                scrNum:1,//滚动的个数
                vertical:false,//垂直 滚动的方向
                effect:"basic",//效果选择 basic | fade | scroll | scrollLoop | marquee 即 默认切换,渐隐,匹配滚动,循环滚动,无缝滚动
                effectTime:500,//动画时间
                interTime:2500,//自动时间间隔
                auto:false,//是否自动
                autoDir:1,//自动播放方向,-1 | 1
                //方向事件控制
                countShow:".count-box",//统计当前页码
                controlVisible:false,
                prevCell:".prev",//前一个按钮元素
                nextCell:".next"//后一个按钮元素
            }
        }
        opts= $.extend({},agr.conf,options);
        var bigBox=$(opts.bigBox);

        bigBox.each(function(){
            var navObj = $(opts.titCell, $(this));//导航子元素结合
            var navObjSize= navObj.size();
            var contBox = $(opts.mainCell , $(this));//内容元素父层对象
            var contBoxSize=contBox.children().size();//内容子层对象
            var currClass=opts.currentName;//当前class名称
            var evt=opts.evt;//事件选择
            var effect=opts.effect;//自定义效果选择
            var effectTime= opts.effectTime;
            var curr=opts.curr;
            var oldCurr=curr;
            var vis= opts.vis;
            var scrNum=opts.scrNum;
            var vertical=opts.vertical;
            var liW= contBox.children().outerWidth(true);
            var liH= contBox.children().outerHeight(true);
            var controlVisible=opts.controlVisible
            var prevCell=$(opts.prevCell , $(this));
            var nextCell=$(opts.nextCell , $(this));
            var auto=opts.auto;
            var autoDir=opts.autoDir;
            var interTime=opts.interTime;
            var inter=null;
            var countShow=$(opts.countShow, $(this))//统计当前页面
            //当前屏计算
            var currScreen=0;
            var tmpScreen=0;
            var boolPan=false;
            /**初始化**/
            if(contBoxSize<opts.vis) return; //当内容个数少于可视个数，不执行效果
            contBox.children().eq(opts.curr).show().siblings().hide();
            navObj.eq(curr).addClass(currClass).siblings(curr).removeClass(currClass);
            countShow.html((curr+1)+"/"+Math.ceil(contBoxSize/scrNum));
            var init={
                scroll:function(){
                    vertical==false?
                        contBox.wrap('<div class="tempWrap" style="overflow:hidden; position:relative; width:'+vis*liW+'px"></div>')
                            .css({ "position":"relative","overflow":"hidden","padding":"0","margin":"0","width":contBoxSize*liW})
                            .children().css( {"float":"left","display":"inline-block"}):
                        contBox.wrap('<div class="tempWrap" style="overflow:hidden; position:relative; height:'+vis*liH+'px;'+'width:'+vis*liW+'px"></div>')
                            .css({ "position":"relative","overflow":"hidden","padding":"0","margin":"0","height":contBoxSize*liH})
                            .children().css( {"display":"block"});
                },
                scrollLoop:function(){
                    contBox.children().clone().appendTo(contBox).clone().prependTo(contBox);
                    vertical==false?
                        contBox.wrap('<div class="tempWrap" style="overflow:hidden; position:relative; width:'+vis*liW+'px"></div>')
                            .css( { "width":contBoxSize*liW*contBoxSize,"left":-contBoxSize*liW,"position":"relative","overflow":"hidden","padding":"0","margin":"0"})
                            .children().css( {"float":"left","display":"inline-block"}):
                        contBox.wrap('<div class="tempWrap" style="overflow:hidden; position:relative;  height:'+vis*liH+'px;'+'width:'+vis*liW+'px"></div>')
                            .css( {"position":"relative","overflow":"hidden","padding":"0","margin":"0","top":-contBoxSize*liH,"height":contBoxSize*liH*contBoxSize})
                            .children().css( {"display":"block"});
                },
                marquee:function(){
                    contBox.children().clone().appendTo(contBox).clone().prependTo(contBox);
                    vertical==false?
                        contBox.wrap('<div class="tempWrap" style="overflow:hidden; position:relative; width:'+vis*liW+'px"></div>')
                            .css( { "width":contBoxSize*liW*contBoxSize,"left":-contBoxSize*liW,"position":"relative","overflow":"hidden","padding":"0","margin":"0"})
                            .children().css( {"float":"left","display":"inline-block","width":liW}):
                        contBox.wrap('<div class="tempWrap" style="overflow:hidden; position:relative;  height:'+vis*liH+'px;'+'width:'+vis*liW+'px"></div>')
                            .css( {"position":"relative","overflow":"hidden","padding":"0","margin":"0","top":-contBoxSize*liH,"height":contBoxSize*liH*contBoxSize})
                            .children().css( {"display":"block","height":liH});
                }
            };
            if(init[effect]){
                init[effect]();
            }
            /**效果选择**/
            var effectSwitch={
                basic:function(){
                    contBox.children().stop(true,true).eq(curr).show().siblings().hide();
                },
                fade:function(){
                    if ( curr >= navObjSize) { curr = 0; } else if( curr < 0) { curr = navObjSize-1; }
                    contBox.children().stop(true,true).eq(curr).fadeIn(opts.effectTime).siblings().hide();
                },
                scroll:function(){
                    if ( curr >= navObjSize) { curr = 0; } else if( curr < 0) { curr = navObjSize-1; }
                    contBox.stop(true,true).animate(vertical==false?{"left":-curr*scrNum*liW}:{"top":-curr*scrNum*liH},effectTime)
                },
                scrollLoop:function(){
                    var panel=0;
                    var tempNum = curr - oldCurr;//?
                    if( navObjSize>2 && tempNum==-(navObjSize-1) ) tempNum=1;//?
                    if( navObjSize>2 && tempNum==(navObjSize-1) ) tempNum=-1;//?
                    var scrollNum = Math.abs( tempNum*scrNum );//滚动个数绝对值
                    if ( curr >= navObjSize) { curr = 0; } else if( curr < 0) { curr = navObjSize-1; }//如果真实当前索引到末尾则跳转到首，如果是到首位则跳到末尾索引
                    if(tempNum<0 ){//-方向
                        contBox.stop(true,true).animate(vertical==false?{"left":-(contBoxSize-scrollNum )*liW}:{"top":-(contBoxSize-scrollNum )*liH},effectTime,function(){
                            for(var i=0;i<scrollNum;i++){ contBox.children().last().prependTo(contBox); }//末尾添加到前头
                            contBox.css(vertical==false?{"left":-contBoxSize*liW}:{"top":-contBoxSize*liH});//重置到初始位置
                        });
                    }else{//正方向
                        contBox.stop(true,true).animate(vertical==false?{"left":-( contBoxSize + scrollNum)*liW}:{"top":-( contBoxSize + scrollNum)*liH},effectTime,function(){
                            for(var i=0;i<scrollNum;i++){ contBox.children().first().appendTo(contBox); }
                            contBox.css(vertical==false?{"left":-contBoxSize*liW}:{"top":-contBoxSize*liH});
                        });
                    }
                    oldCurr=curr;
                },
                marquee:function(){
                    if ( curr>= 2) { curr=1; } else if( curr<0) { curr = 0; }

                    var tempDir = vertical==false?contBox.css("left").replace("px",""):contBox.css("top").replace("px","");//每次替换滚动值且对应方向进行累加
                    if(curr==0 ){//-方向
                        contBox.animate(vertical==false?{"left":++tempDir}:{"top":++tempDir},0,function(){
                            if( vertical==false?contBox.css("left").replace("px","")>= 0:contBox.css("top").replace("px","")>= 0){//到达首位
                                for(var i=0;i<contBoxSize;i++){ contBox.children().last().prependTo(contBox);}
                                contBox.css(vertical==false?{"left":-contBoxSize*liW}:{"top":-contBoxSize*liW});
                            }
                        });
                    }
                    else{
                        contBox.animate(vertical==false?{"left":--tempDir}:{"top":--tempDir},0,function(){
                            if( vertical==false?contBox.css("left").replace("px","")<= -contBoxSize*liW*2:contBox.css("top").replace("px","")<= -contBoxSize*liH*2){
                                for(var i=0;i<contBoxSize;i++){
                                    contBox.children().first().appendTo(contBox);
                                }
                                contBox.css(vertical==false?{"left":-contBoxSize*liW}:{"top":-contBoxSize*liH});
                            }
                        });
                    }
                    oldCurr=curr;
                }
            };
            /**分屏统计**/
            //当前索引属于的屏数
            var allPanel=Math.ceil(contBoxSize/scrNum);//总屏数
            //总屏分组
            var currPanel=(function(){
                var allArr=[];
                for(var i=0;i<contBoxSize;i++){
                    allArr.push(i+1);
                }
                //每allPanel个字符串一组数据存入
                var start=0;
                var end=scrNum;
                var tmpArr=[];//二维数组[[],[],...]
                for(var j=0;j<allArr.length;j++){
                    if(j%scrNum==0){
                        var arr=allArr.slice(start,end);
                        tmpArr.push(arr);
                        start+=scrNum;
                        end+=scrNum;
                    }
                }
                return tmpArr;
            })();
            var panStatus={
                nextPan:function(){
                    if(tmpScreen==contBoxSize && currScreen==allPanel){
                        currScreen=0;
                        tmpScreen=0;
                    }
                    //判断curr在哪个屏范围
                    tmpScreen+=scrNum;
                    $.each(currPanel,function(key,value){
                        var subArr=value;
                        $.each(subArr,function(subKey,subValue){
                            if(tmpScreen==subValue){
                                currScreen+=1;
                            }
                        });
                    });

                    if(tmpScreen>=contBoxSize){
                        tmpScreen=0;
                        currScreen=0;
                    }
                    countShow.html((currScreen+1)+"/"+allPanel);
                },
                prevPan:function(){
                    if(tmpScreen==0 && currScreen==0){
                        boolPan=true;
                        if(boolPan){
                            currScreen=allPanel;
                            tmpScreen=contBoxSize;
                            boolPan=false;
                        }
                    }
                    //判断curr在哪个屏范围
                    tmpScreen-=scrNum;
                    $.each(currPanel,function(key,value){
                        var subArr=value;
                        $.each(subArr,function(subKey,subValue){
                            if(tmpScreen==subValue){
                                currScreen-=1;
                            }
                        });
                    });
                    countShow.html((currScreen+1)+"/"+allPanel);
                    if(tmpScreen<=0){
                        countShow.html((currScreen)+"/"+allPanel);
                        currScreen=allPanel;
                        tmpScreen=contBoxSize;
                    }
                }
            }
            //切换导航点击对象状态
            var navStatus=function(obj,curr,currClass){
                obj.eq(curr).addClass(currClass).siblings(curr).removeClass(currClass);
               // alert(curr+"---"+currScreen+"--"+tmpScreen)
            }
            /**绑定事件**/
            navObj.live(evt,function(){
                curr=$(this).index();
                effectSwitch[effect]();
                $(this).addClass(currClass).siblings(curr).removeClass(currClass);
            });
            prevCell.live("click",function(){
                curr--;
                effectSwitch[effect]();
                navStatus(navObj,curr,currClass);
                panStatus.prevPan();
            });
            nextCell.live("click",function(){
                curr++;
                effectSwitch[effect]();
                navStatus(navObj,curr,currClass);
                panStatus.nextPan();
            });
            if(controlVisible){
                $(this).hover(function(){
                    nextCell.show();
                    prevCell.show();
                },function(){
                    nextCell.hide();
                    prevCell.hide();
                });
            }
            //自动播放
            if(auto){
                var autoPlay=function(){
                    inter = setInterval(function(){
                        autoDir==1?curr--:curr++;
                        effectSwitch[effect]();
                        navStatus(navObj,curr,currClass);
                    }, interTime);
                }
                //无缝滚动
                if(effect=="marquee"){
                    autoDir==1?curr--:curr++;
                    inter = setInterval(function(){
                        effectSwitch[effect]();
                    }, interTime);

                    contBox.hover(function(){
                        if(auto){clearInterval(inter); }
                    },function(){
                        if(auto){clearInterval(inter);
                            inter = setInterval(effectSwitch[effect],interTime);}
                    });
                }else{
                    autoPlay();
                    $(this).hover(function(){
                        if(auto){
                            clearInterval(inter);
                        }
                    },function(){
                        if(auto){
                            clearInterval(inter);
                            autoPlay();
                        }
                    });
                }
            }
        });

        return this;
    };
    //公共接口
    _that.prototype={
        base:base
    }
    return _that;
})(jQuery);
/*
 * slider模块
 * */
(function($, window){
    //构造函数
    var slider=JQPT.slider=function(options){
        this.opt=options||{};
        return this;
    };
    //滚动lite:基于jCarouselLite 首尾复制添加重构dom结构，curr进行逻辑获取，动画执行 curr*li的宽度
    slider.prototype.scrollLite=function(options){
        var agr={
            conf:{
                selectElem:".scorll-box",//选择器
                scrollObj:".img-show ul",//滚动对象集合
                view:".img-show",//可视区域
                circular:true,//是否开启循环滚动
                visible:1,//可视区域个数
                vertical:false,//滚动的方向默认false为水平
                scrollNum:1,//可视区域一次滚动个数
                start: 0,
                speed:400,//动画速度
                auto:true,
                interTime:4000,
                easing:null,
                callBack:null,
                //数字导航切换
                navBool:true,
                navItem:".nav-item ul li",
                navEvt:"click",
                navCurr:"on",
                countShow:".count-box",
                //图片标题添加
                imgText:".maskTitInfo ul li",
                //方向事件控制
                controlVisible:false,
                leftBtn:".prev",
                rightBtn:".next",
                disableClass:"disable"
            }
        }
        var settings=this.settings=$.extend({},agr.conf,options);
        var bigBox=$(settings.selectElem);
        bigBox.each(function(){
            var controlVisible=settings.controlVisible;
            var leftBtn=$(settings.leftBtn,$(this));//前一页按钮
            var rightBtn=$(settings.rightBtn,$(this));//后一页按钮
            var disableClass=settings.disableClass;
            var view=$(settings.view,$(this));//可视区域
            var viewNum=settings.visible//可视个数
            var scrollObj=$(settings.scrollObj,$(this));//滚动层
            var scrNum=settings.scrollNum;//一次滚动的个数
            var vertical=settings.vertical;
            var navItem=$(settings.navItem,$(this));
            var navCurr=settings.navCurr;
            var countShow=$(settings.countShow,$(this))
            var navBool=settings.navBool;
            var autoPlay=settings.auto;
            var inter;
            var interTime=settings.interTime;
            var tli=scrollObj.children();//原始子对象
            var speed=settings.speed;
            var curr=0; //真实当前索引
            var liNum=scrollObj.children().size();//真实li个数
            var imgText=$(settings.imgText,$(this));

            var running = false,//执行状态
                nli,//复制后的新子对象
                nliW,//新子对象宽度
                nliH,//新子对象高度
                nCurr,//新子对象当前索引号0开始
                nliNum,//新子对象个数
                nliSize,
                callBack=settings.callBack,//动画回调函数
                easing=settings.easing;//动画缓冲系数
            //复制首尾dom结构变化，以及css初始化
            var newDom={
                htmlInit:function(){
                    if(settings.circular) {
                        scrollObj.prepend(tli.slice(tli.size()-scrNum-1+1).clone())//取尾部到前头
                            .append(tli.slice(0,scrNum).clone());//取前头到尾部
                        settings.start += viewNum;//从索引号为1开始可视
                    }
                    //新dom结构获取
                    nli=scrollObj.children();
                    nliSize=vertical? nli.outerHeight(true):nli.outerWidth(true);//滚动尺寸
                    nliNum=nli.size();
                    nliW=nli.outerWidth(true);
                    nliH=nli.outerHeight(true);
                    return this;
                },
                cssInit:function(){
                    if(!controlVisible){
                        leftBtn.hide();
                        rightBtn.hide();
                    }
                    view.css(vertical?{position:"relative",overflow:"hidden","z-index":2,height:viewNum*nliSize}:{position:"relative",overflow:"hidden","z-index":2,width:viewNum*nliSize});
                    scrollObj.css(vertical?{position:"relative","z-index":1,overflow:"hidden",height:nliNum*nliSize}:{position:"relative","z-index":1,overflow:"hidden",width:nliNum*nliSize});
                    nli.css(vertical?{"display":"inline-block"}:{"float":"left","display":"inline-block"});
                    if(settings.circular){
                        //改变滚动层位置初始化 3 1 2 3 1
                        scrollObj.css(vertical?{"top":-scrNum*nliH}:{"left":-scrNum*nliW});
                        //nav当前状态
                        navItem.eq(0).addClass(navCurr).siblings().removeClass(navCurr);
                    }else{
                        navItem.eq(curr).addClass(navCurr).siblings().removeClass(navCurr);
                        leftBtn.addClass(disableClass);
                    }
                    //统计
                    countShow.html((curr+1)+"/"+liNum);
                    return this;
                }
            };
            newDom.htmlInit().cssInit();
            //执行效果
            var effectLoop={
                scrollGo:function(){
                    var aniNum;
                    if(!running){
                        if(settings.circular) {
                            if(curr<=-1){
                                curr=liNum-1;
                                scrollObj.css(vertical?{"top": -(curr+2*scrNum)*nliSize+"px"}:{"left": -(curr+2*scrNum)*nliSize+"px"});
                            }else if(curr>liNum-1){
                                curr=0;
                                scrollObj.css(vertical?{"top": -curr*nliSize+"px"}:{"left": -curr*nliSize+"px"});
                            }
                            aniNum=(curr+scrNum);//计算应该滚动的个数
                        }else{
                            if(curr<0){
                                curr=0;
                                return;
                            }else if(curr>liNum-1){
                                curr=liNum-1;
                                return;
                            }else{
                                aniNum=curr;
                            };

                            if(curr<1){
                                leftBtn.addClass(disableClass);
                                rightBtn.removeClass(disableClass);
                            }else if(curr>liNum-2){
                                rightBtn.addClass(disableClass);
                                leftBtn.removeClass(disableClass);
                            }else{
                                rightBtn.removeClass(disableClass);
                                leftBtn.removeClass(disableClass);
                            }
                        };
                        running = true;
                        //执行动画
                        scrollObj.stop(false,true).animate(
                            vertical?{"top":-aniNum*nliSize}:{"left": -aniNum*nliSize}
                            ,speed
                            ,easing,
                            function() {
                                running = false;
                            }
                        );
                        //nav状态
                        navItem.eq(curr).addClass(navCurr).siblings().removeClass(navCurr);
                        //统计
                        countShow.html((curr+1)+"/"+liNum);
                        //图片文本
                        imgText.eq(curr).show().siblings().hide();
                    };
                    return false;
                },
                //事件绑定
                evtBind:function(leftBtn,rightBtn){
                    if(leftBtn){
                        leftBtn.click(function() {
                            if(!running){
                                curr-=scrNum;
                            }
                            effectLoop.scrollGo();
                        });
                    };
                    if(rightBtn){
                        rightBtn.click(function() {
                            if(!running){
                                curr+=scrNum;
                            }
                            effectLoop.scrollGo();
                        });
                    }
                    return this;
                },
                //tab滚动
                navItem:function(){
                    if(navBool){
                        var navLg=navItem.length;
                        navItem.live(settings.navEvt,function(){
                            $(this).addClass(navCurr).siblings().removeClass(navCurr);
                            navIndex=$(this).index();
                            curr=navIndex;
                            effectLoop.scrollGo();
                        });
                    }
                    return this;
                },
                //自动播放
                autoControl:function(){
                    inter = setInterval(function(){
                        if(!running){
                            curr+=scrNum;
                        }
                        effectLoop.scrollGo();
                    }, interTime);
                },
                autoDo:function(){
                    if(autoPlay){
                        effectLoop.autoControl();
                    };
                    scrollObj.hover(function(){
                        if(autoPlay){
                            clearInterval(inter);
                        }
                    },function(){
                        if(autoPlay){
                            clearInterval(inter);
                            effectLoop.autoControl();
                        }
                    });

                    return this;
                }
            };
            effectLoop.evtBind(leftBtn,rightBtn).navItem().autoDo();
            //控制显示
            $(this).hover(function(){
                if(!controlVisible){
                    leftBtn.fadeIn();
                    rightBtn.fadeIn();
                }
            },function(){
                if(!controlVisible){
                    leftBtn.fadeOut();
                    rightBtn.fadeOut();
                }
            });
        });
        return this;
    };
    //相册幻灯片
    slider.prototype.gallery=function(options){
        var agr={
            conf:{
                selectElem:".galleryBox",//选择器
                effect:"fade",
                bigShow:".pic-show",
                speed:400,//动画速度
                auto:true,
                interTime:4000,
                easing:null,
                callBack:null,
                overBox:".over-box",
                overClose:".close-box",
                //数字导航切换
                navBool:true,
                navItem:".small-pic ul li",
                navEvt:"click",
                navCurr:"on",
                navVisible:6,
                countShow:".count-box",
                //方向事件控制
                leftBtn:".prev",
                rightBtn:".next",
                disableClass:"disable",
                //lightBox
                lightBool:true,
                lbTit:".lb-tit",
                lbMiss:6,
                lightBoxOverlay:".lightboxOverlay",
                lightBoxShow:".lightbox",
                lightBoxImgWarp:".lightbox .lb-outerContainer",
                lightBoxCloseWarp:".lb-dataContainer",
                lbPrev:".lb-nav .lb-prev",
                lbNext:".lb-nav .lb-next"
                //lightBoxOuterContainer:"#lightbox .lb-outerContainer"
            }
        };
        var settings=this.settings=$.extend({},agr.conf,options);
        var bigBox=$(settings.selectElem);
        bigBox.each(function(){
            var effect=settings.effect;
            var showObj=$(settings.bigShow,$(this));
            var bH=showObj.height();
            var overBox=$(settings.overBox,$(this));
            var overClose=$(settings.overClose,$(this));

            var navItem=$(settings.navItem,$(this));
            var lg=navItem.size();
            var navW=navItem.outerWidth(true);
            var navH=navItem.outerHeight(false);
            var navCurr=settings.navCurr;
            var navVisible=settings.navVisible;//小图多少为一组
            var page=1;//小图滚动前的计数条件
            var num=0;//小图索引以及鼠标点击次数
            var imgSrc;
            var recordRead=[];
            var leftBtn=$(settings.leftBtn,$(this));
            var rightBtn=$(settings.rightBtn,$(this));

            var imgArr=[];//lightBox所需的图片容器
            var lightBool=settings.lightBool;
            var lightBoxOverlay=$(settings.lightBoxOverlay);
            var lightBoxShow=$(settings.lightBoxShow);
            var lightBoxCloseWarp=$(settings.lightBoxCloseWarp);
            var lbPrev=$(settings.lbPrev);
            var lbNext=$(settings.lbNext);
            var lightBoxImgWarp=$(settings.lightBoxImgWarp);
            var lbTit=$(settings.lbTit);//图片标题
            var lbMiss=settings.lbMiss;//灯箱位置存在误差
            /**初始化css**/
            var init={
                fade:function(){
                    showObj.css({"height":bH,"position":"relative","overflow":"hidden"});
                    showObj.children().filter(function(index){
                        $(this).css({"z-index":index});
                    });
                    navItem.parent().css({"width":lg*navW,"height":navH,"position":"absolute"});
                    navItem.eq(num).addClass(navCurr).siblings().removeClass(navCurr);
                    return this;
                },
                lightBox:function(){
                    navItem.filter(function(){
                        var imgObj={};
                        var imgSrc=$(this).children().attr("src");
                        var imgAlt=$(this).children().attr("alt");
                        var imgTit=$(this).children().attr("title");
                        imgObj.src=imgSrc;
                        imgObj.alt=imgAlt;
                        imgObj.tit=imgTit
                        imgArr.push(imgObj);
                    });
                    return this;
                },
                scroll:function(){

                    return this;
                }
            };
            init[effect]();
            //延时加载

            //预加载处理
            /**
             * 图片头数据加载就绪事件 - 更快获取图片尺寸
             * @version	2011.05.27
             * @author	TangBin
             * @see		http://www.planeart.cn/?p=1121
             * @param	{String}	图片路径
             * @param	{Function}	尺寸就绪
             * @param	{Function}	加载完毕 (可选)
             * @param	{Function}	加载错误 (可选)
             * @example imgReady('http://www.google.com.hk/intl/zh-CN/images/logo_cn.png', function () {
		alert('size ready: width=' + this.width + '; height=' + this.height);
	});
             */
            var imgReady = (function () {
                var list = [], intervalId = null,
                // 用来执行队列
                    tick = function () {
                        var i = 0;
                        for (; i < list.length; i++) {
                            list[i].end ? list.splice(i--, 1) : list[i]();
                        };
                        !list.length && stop();
                    },
                // 停止所有定时器队列
                    stop = function () {
                        clearInterval(intervalId);
                        intervalId = null;
                    };
                return function (url, ready, load, error) {
                    var onready, width, height, newWidth, newHeight,
                        img = new Image();
                    img.src = url;
                    // 如果图片被缓存，则直接返回缓存数据
                    if (img.complete) {
                        ready.call(img);
                        load && load.call(img);
                        return;
                    };
                    width = img.width;
                    height = img.height;
                    // 加载错误后的事件
                    img.onerror = function () {
                        error && error.call(img);
                        onready.end = true;
                        img = img.onload = img.onerror = null;
                    };
                    // 图片尺寸就绪
                    onready = function () {
                        newWidth = img.width;
                        newHeight = img.height;
                        if (newWidth !== width || newHeight !== height ||
                            // 如果图片已经在其他地方加载可使用面积检测
                            newWidth * newHeight > 1024
                            ) {
                            ready.call(img);
                            onready.end = true;
                        };
                    };
                    onready();
                    // 完全加载完毕的事件
                    img.onload = function () {
                        // onload在定时器时间差范围内可能比onready快
                        // 这里进行检查并保证onready优先执行
                        !onready.end && onready();
                        load && load.call(img);
                        // IE gif动画会循环执行onload，置空onload即可
                        img = img.onload = img.onerror = null;
                    };
                    // 加入队列中定期执行
                    if (!onready.end) {
                        list.push(onready);
                        // 无论何时只允许出现一个定时器，减少浏览器性能损耗
                        if (intervalId === null) intervalId = setInterval(tick, 40);
                    };
                };
            })();
            //执行效果
            var run={
                fade:function(dir){
                    //大图区域
                    imgSrc=navItem.eq(num).find("img").attr("src");
                    showObj.children().filter(function(){
                        if($(this).is(":visible")){
                            $(this).stop(true,true).fadeOut().siblings().empty().hide().append("<a href='javascript:void(0);'"+"data-index='"+num+"'><img src='"+imgSrc+"'/></a>").delay(400).fadeIn();
                        }
                    });
                    //小图区域
                    navItem.eq(num).addClass(navCurr).siblings().removeClass(navCurr);
                    if(recordRead.length<lg){
                        recordRead[num]=imgSrc;
                        overBox.hide()
                    }
                    //小图片滚一组控制
                    if(dir){
                        if(num>=navVisible&&num<lg){
                            page++;
                            navItem.parent().stop(true,true).animate({"left":-page*navW});
                        }else if(num>=0&&num<navVisible){
                            page=0;
                            navItem.parent().stop(true,true).animate({"left":-page*navW});
                        }
                    }else{
                        if(num==lg-1){
                            page=lg-navVisible+1;
                            page--;
                            navItem.parent().stop(true,true).animate({"left":-page*navW});
                        }else if(num<navVisible){
                            page=0;
                            navItem.parent().stop(true,true).animate({"left":-page*navW});
                        }
                    }
                    //计算当前索引到达末端
                    if(recordRead.length==lg){
                        //alert("您已经全部阅读完了");
                        overBox.show().delay(1000).animate({"top":"10%"});
                        recordRead=[];
                    }
                    return this;
                },
                overlaySize:function(){
                    var w=$(document).width();
                    var h=$(document).height();
                    lightBoxOverlay.css({"width":w,"height":h});
                    $(window).resize(function() {
                        var w=$(document).width();
                        var h=$(document).height();
                        lightBoxOverlay.css({"width":w,"height":h});
                    });
                    lightBoxOverlay.show();
                },
                //浏览器视窗尺寸变化改变对应的尺寸
                lightBoxShowSize:function(){
                    var top = $(window).scrollTop() + $(window).height() / lbMiss;
                    var left = $(window).scrollLeft();
                    lightBoxShow.css({"top":top,"left":left});

                    $(window).resize(function() {
                        var top = $(this).scrollTop() + $(this).height() / lbMiss;
                        var left = $(this).scrollLeft();
                        lightBoxShow.css({"top":top,"left":left});
                    });

                    lightBoxShow.show();
                }
            };
            //事件绑定
            var evt={
                mouse:function(){
                    leftBtn.live("click",function(){
                        if(num<=0){
                            return false;
                            //num=lg-1;
                        }else{
                            num--;
                        }
                        run[effect](false);
                    });

                    rightBtn.live("click",function(){
                        if(num>=lg-1){
                            return false;
                            //num=0;
                        }else{
                            num++;
                        }
                        run[effect](true);
                    });

                    navItem.bind("click",function(){
                        var index=$(this).index();
                        $(this).addClass(navCurr).siblings().removeClass(navCurr);
                        num=index;
                        run[effect]();
                    });

                    overClose.bind("click",function(){
                        overBox.animate({"top":-1000});
                    });
                    return this;
                },
                //灯箱
                lightBox:function(){
                    showObj.children().click(function(){
                        var index=$(this).children().attr("data-index");
                        num=index;
                        changeShow();
                        return false;
                    });
                    //关闭lightBox
                    var offShow=function(){
                        lightBoxOverlay.hide();
                        lightBoxShow.hide();
                        lightBoxImgWarp.find(".lb-loader").show();
                    };
                    //变换lightBox
                    var changeShow=function(){
                        run.overlaySize();
                        run.lightBoxShowSize();

                        var pad=8;//容器内padding值
                        var srcStr=imgArr[num].src;//获取图片路径
                        var srcTit=imgArr[num].tit;//图片标题

                        imgReady(srcStr,function(){
                            lightBoxImgWarp.find("img").attr({"src":srcStr});

                            lightBoxImgWarp.hide();
                            lightBoxImgWarp.fadeIn().css({"width":this.width+pad,"height":this.height+pad});
                            lightBoxCloseWarp.css({"width":this.width+pad});
                            lightBoxImgWarp.find(".lb-loader").hide();

                            lbTit.css({"width":this.width});
                            lbTit.html("<h4>"+srcTit+"</h4>");
                        })

                    }

                    lightBoxOverlay.click(function(){
                        if($(this).is(":visible")){
                            offShow();
                        }
                        return false;
                    });

                    lightBoxCloseWarp.find(".lb-close").click(function(){
                        offShow();
                        return false;
                    });

                    lbPrev.click(function(){
                        if(num<=0){
                            offShow();
                            return false;
                        }else{
                            num--;
                        }
                        run[effect](false);
                        changeShow();
                    });

                    lbNext.click(function(){
                        if(num>=lg-1){
                            offShow();
                            return false;
                        }else{
                            num++;
                        }
                        run[effect](true);
                        changeShow();
                    });
                }
            }
            evt.mouse();
            if(lightBool){
                init.lightBox();
                evt.lightBox();
            };
        });
        return this;
    };
})(jQuery,this);
/*
 * nav导航模块
 * */
(function($){

    var nav=JQPT.nav=function(options){
        this.opts=options||{};
    }
    //二三级下拉
    nav.prototype.pullDown=function(options){
        var agr={
            conf:{
                selectElem:".nav",//选择器
                navLi:"ul li",
                subNav:".sub",
                threeNav:".three-sub",
                current:"current",
                evt:"hover",
                subIndex:1
            }
        };
        var settings=this.settings= $.extend({},agr.conf,options);
        var bigBox=$(settings.selectElem);
        bigBox.each(function(){
            var navLi=$(settings.navLi,$(this));
            var subNav=$(settings.subNav,$(this));
            var threeNav=$(settings.threeNav,$(this));
            var current=settings.current;
            var subIndex=settings.subIndex;
            var evt=settings.evt;
            //效果
            var effect={
                baseAdd:function(_this){
                    if(_this.hasClass(current)){
                        _this.find(subNav).slideUp();
                        _this.removeClass(current);
                    }else{
                        _this.find(subNav).slideDown();
                        _this.siblings().find(subNav).hide();
                        _this.addClass(current).siblings().removeClass(current);
                        //三级导航
                        var subObj=$(this).find(subNav).children();
                        if(subObj.find(threeNav)){
                            subObj.hover(function(){
                                $(this).find(threeNav).show();
                            },function(){
                                $(this).find(threeNav).hide();
                            });
                        }
                    }
                },
                baseRemove:function(_this){
                    _this.find(subNav).slideUp();
                    _this.removeClass(current);
                }
            }
            //事件类型
            var evtActive={
                rowClick:function(){
                    navLi.live("click",function(){
                        effect.baseAdd($(this));
                    })
                },
                hover:function(){
                    navLi.live(
                        {
                            "mouseover":function(){
                                effect.baseAdd($(this));
                            },
                            "mouseout":function(){
                                effect.baseRemove($(this));
                            }
                        }
                    );
                }
            };
            evtActive[evt]();
        });

        return this;
    }
})(jQuery)
/*******************插件部分*******************/
