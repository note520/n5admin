/*
 * jQuery TM
 * tm 1.0.0
 * author:sky@note520.com
 * webSite:www.note520.com
 * update:2013-10-22
 */
'use strict';
(function(win,undefined){
    /**
     * TM是全局对象。
     * @name TM
     * @class TM是TM框架的全局对象，也是框架内部所有类的全局命名空间。在全局T未被占用的情况下，也可以使用其缩写T
     */
    var TM=win.TM=win.TM||{
        global: win,
        version: "dev-1.0.0"
    };
    /**
     * 简单的log方法
     */
    TM.trace = function()
    {
        var logs = Array.prototype.slice.call(arguments);
       if(typeof(console)&&typeof(console.log)){
           console.log(logs.join(" "))
       }
    };

    var emptyConstructor = function() {};
    /**
     * TM框架的继承方法。
     * @param {Function} childClass 子类。
     * @param {Function} parentClass 父类。
     */
    TM.inherit = function(childClass, parentClass)
    {
        emptyConstructor.prototype = parentClass.prototype;
        childClass.superClass = parentClass.prototype;
        childClass.prototype = new emptyConstructor();
        childClass.prototype.constructor = childClass;
        //TM.merge(childClass.prototype, parentClass.prototype);
    };

    /**
     * 把props参数指定的属性或方法复制到obj对象上。
     * @param {Object} obj Object对象。
     * @param {Object} props 包含要复制到obj对象上的属性或方法的对象。
     * @param {Boolean} strict 指定是否采用严格模式复制。默认为false。
     * @return {Object} 复制后的obj对象。
     */
    TM.merge = function(obj, props, strict)
    {
        for(var key in props)
        {
            if(!strict || obj.hasOwnProperty(key) || obj[key] !== undefined) obj[key] = props[key];
        }
        return obj;
    };

    /**
     * 改变func函数的作用域scope，即this的指向。
     * @param {Function} func 要改变函数作用域的函数。
     * @param {Object} self 指定func函数的作用对象。
     * @return {Function} 一个作用域为参数self的功能与func相同的新函数。
     */
    TM.delegate = function(func, self)
    {
        var context = self || win;
        if (arguments.length > 2)
        {
            var args = Array.prototype.slice.call(arguments, 2);
            return function()
            {
                var newArgs = Array.prototype.concat.apply(args, arguments);
                return func.apply(context, newArgs);
            };
        }else
        {
            return function() {return func.apply(context, arguments);};
        }
    };
    //当T没有被占据的情况下
    win.T=!win.T && TM;
    win.trace=!win.trace && TM.trace;
})(this);
/**
 * Fx常用效果
 *
 */
(function(win,$){
    var Fx=TM.Fx={};
    //添加效果
    Fx.addEffect=function(name, fn) {
        Fx[name] = fn;
    };
})(this,jQuery);
/**
 * tabs模块 预定swtich思想
 */
(function(win,$){
    //构造函数
    var tabs=TM.tabs=function(options){
        this.opt=options||{};
        return this;
    };
    //私有方法构造
    var _tabs=function(root,conf){
        //变量声明
        var tit = $(conf.tit, root),//导航子元素结合
        titSize= tit.size(),
        cont= $(conf.cont , root),//内容元素父层对象
        contSize=cont.children().size(),//内容子层对象
        currClass=conf.currentClass,//当前class名称
        evt=conf.evt,//事件选择
        effect=conf.effect,//自定义效果选择
        effectTime= conf.effectTime,
        curr=conf.curr,
        oldCurr=curr,
        vis= conf.vis,
        scrNum=conf.scrNum,
        vertical=conf.vertical,
        liW= cont.children().outerWidth(true),
        liH= cont.children().outerHeight(true),
        controlVisible=conf.controlVisible,
        prevBt=$(conf.prevBt , root),
        nextBt=$(conf.nextBt , root),
        auto=conf.auto,
        autoDir=conf.autoDir,
        interTime=conf.interTime,
        inter=null,
        countShow=$(conf.countShow,root),//统计当前页面
        //当前屏计算
        currScreen=0,
        tmpScreen=0,
        boolPan=false,
        easing=conf.easing,
        speed=conf.speed,
        callback=conf.callback,
        _this=this;

        if(tit.length==0){
            trace("please config the right of Dom selector: config.tit!");
            return;
        };

        if(contSize<vis) return; //当内容个数少于可视个数，不执行效果
        /**初始化UI**/
        cont.children().eq(curr).show().siblings().hide();
        tit.eq(curr).addClass(currClass).siblings(curr).removeClass(currClass);
        countShow.html((curr+1)+"/"+Math.ceil(contSize/scrNum));
        if(controlVisible){
			nextBt.hide();
            prevBt.hide();

            root.hover(function(){
                nextBt.show();
                prevBt.show();
            },function(){
                nextBt.hide();
                prevBt.hide();
            });
        }
        this._css={
            "scroll":function(){
                //==bug==liW 如果cont子节点不指定宽度则outerWidth会计算错误
                vertical==false?
                    cont.wrap('<div class="tempWrap" style="overflow:hidden; position:relative; width:'+vis*liW+'px"></div>')
                        .css({ "position":"relative","overflow":"hidden","padding":"0","margin":"0","width":contSize*liW})
                        .children().css( {"float":"left","display":"inline-block"}):
                    cont.wrap('<div class="tempWrap" style="overflow:hidden; position:relative; height:'+vis*liH+'px;'+'width:'+vis*liW+'px"></div>')
                        .css({ "position":"relative","overflow":"hidden","padding":"0","margin":"0","height":contSize*liH})
                        .children().css( {"display":"block"});
            },
            "scrollLoop":function(){
                cont.children().clone().appendTo(cont).clone().prependTo(cont);
                vertical==false?
                    cont.wrap('<div class="tempWrap" style="overflow:hidden; position:relative; width:'+vis*liW+'px\"></div>')
                        .css( { "width":contSize*liW*contSize,"left":-contSize*liW,"position":"relative","overflow":"hidden","padding":"0","margin":"0"})
                        .children().css( {"float":"left","display":"inline-block"}):
                    cont.wrap('<div class="tempWrap" style="overflow:hidden; position:relative;  height:'+vis*liH+'px;'+'width:'+vis*liW+'px\"></div>')
                        .css( {"position":"relative","overflow":"hidden","padding":"0","margin":"0","top":-contSize*liH,"height":contSize*liH*contSize})
                        .children().css( {"display":"block"});
            }
        };
        /**按需初始化css**/
        if(this._css[conf.effect]){
            this._css[conf.effect]();
        };
        //效果选择
        this._effect={
            base:function(){
                if(!easing){
                    cont.children().stop(true,true).eq(curr).show().siblings().hide();
                }else{
                    cont.children().stop(true,true).eq(curr).show(speed,easing).siblings().hide(speed,easing);
                }
                if(callback){
                    callback.call(this,oldCurr,root);
                }
            },
            fade:function(){
                if ( curr >= titSize) { curr = 0; } else if( curr < 0) { curr = titSize-1; }
                cont.children().stop(true,true).eq(curr).fadeIn(speed,easing).siblings().hide();
                callback.call(this,oldCurr,root);
            },
            scroll:function(){
                if ( curr >= titSize) { curr = 0; } else if( curr < 0) { curr = titSize-1; }
                cont.stop(true,true).animate(vertical==false?{"left":-curr*scrNum*liW}:{"top":-curr*scrNum*liH},effectTime,easing)
                if(callback){
                    callback.call(this,oldCurr,root);
                }
            },
            scrollLoop:function(){
                var panel=0;
                var tempNum = curr - oldCurr;//?
                if( titSize>2 && tempNum==-(titSize-1) ) tempNum=1;//?
                if( titSize>2 && tempNum==(titSize-1) ) tempNum=-1;//?
                var scrollNum = Math.abs( tempNum*scrNum );//滚动个数绝对值
                if ( curr >= titSize) { curr = 0; } else if( curr < 0) { curr = titSize-1; }//如果真实当前索引到末尾则跳转到首，如果是到首位则跳到末尾索引
                if(tempNum<0 ){//-方向
                    cont.stop(true,true).animate(vertical==false?{"left":-(contSize-scrollNum )*liW}:{"top":-(contSize-scrollNum )*liH},effectTime,easing,function(){
                        for(var i=0;i<scrollNum;i++){ cont.children().last().prependTo(cont); }//末尾添加到前头
                        cont.css(vertical==false?{"left":-contSize*liW}:{"top":-contSize*liH});//重置到初始位置
                    });
                }else{//正方向
                    cont.stop(true,true).animate(vertical==false?{"left":-( contSize + scrollNum)*liW}:{"top":-( contSize + scrollNum)*liH},effectTime,easing,function(){
                        for(var i=0;i<scrollNum;i++){ cont.children().first().appendTo(cont); }
                        cont.css(vertical==false?{"left":-contSize*liW}:{"top":-contSize*liH});
                    });
                }
                oldCurr=curr;
                if(callback){
                    callback.call(this,oldCurr,root);
                }
            }
        };
        /**分屏统计**/
        //当前索引属于的屏数
        var allPanel=Math.ceil(contSize/scrNum);//总屏数
        //总屏分组
        var currPanel=(function(){
            var allArr=[];
            for(var i=0;i<contSize;i++){
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
        //此方法需要优化
        var panStatus={
            nextPan:function(){
                if(tmpScreen==contSize && currScreen==allPanel){
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

                if(tmpScreen>=contSize){
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
                        tmpScreen=contSize;
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
                    tmpScreen=contSize;
                }
            }
        };
        //切换导航点击对象状态
        var navStatus=function(obj,curr,currClass){
            obj.eq(curr).addClass(currClass).siblings(curr).removeClass(currClass);
            // alert(curr+"---"+currScreen+"--"+tmpScreen)
        };
        /**绑定事件**/
        tit.on(evt,function(){
            curr=$(this).index();
            $(this).addClass(currClass).siblings(curr).removeClass(currClass);
            _this._effect[effect]();
        });
        prevBt.on(conf.controlEvt,function(){
            curr--;
            _this._effect[effect]();
            navStatus(tit,curr,currClass);
            panStatus.prevPan();
        });
        nextBt.on(conf.controlEvt,function(){
            curr++;
            _this._effect[effect]();
            navStatus(tit,curr,currClass);
            panStatus.nextPan();
        });
        //自动播放
        if(auto){
            var autoPlay=function(){
                inter = setInterval(function(){
                    autoDir==1?curr--:curr++;
                    _this._effect[effect]();
                    navStatus(tit,curr,currClass);
                }, interTime);
            };
            autoPlay();
            root.hover(function(){
                clearInterval(inter);
            },function(){
                clearInterval(inter);
                autoPlay();
            });
        }
    };
    //公共方法
    tabs.prototype.init=function(options){
		 //参数
		var config={
			mod:".tabs-mod",
			tit:".tab-tit ul li",//导航元素
			cont:".tab-cont",//内容元素的父层对象
			evt:"click",//触发方式 || mouseover：鼠标移过触发；|| click：鼠标点击触发；
			currentClass:"current",//当前位置自动增加的class名称
			curr : 0,//默认的当前位置索引。0是第一个
			vis:1,//visible
			scrNum:1,//滚动的个数
			vertical:false,//垂直 滚动的方向
			effect:"base",//效果选择 base | fade | scroll | scrollLoop  即 默认切换,渐隐,匹配滚动,循环滚动
			effectTime:500,//动画时间
			easing:null,//缓冲动画系数
			speed:"normal",//动画速度 "slow","normal", "fast",or 毫秒数值
			interTime:2500,//自动时间间隔
			auto:false,//是否自动
			autoDir:1,//自动播放方向,-1 | 1
			//方向事件控制
			countShow:".count-box",//统计当前页码
			controlVisible:false,
			controlEvt:"click",
			callback:null,
			prevBt:".prev",//前一个按钮元素
			nextBt:".next"//后一个按钮元素
		};
        //合并参数
        $.extend(this.opt,config,options);
        var mod=$(this.opt.mod),_this=this;
        if(mod.length==0){
            trace("mod selector is no exist !");
            return;
        };
        mod.each(function(){
           new _tabs($(this),_this.opt);
        });
        return mod;
    };
})(this,jQuery);
/*
 * slider模块 scrollLite补位思想
 * */
(function($, window){
    //构造函数
    var slider=TM.slider=function(options){
        this.opt=options||{};
        return this;
    };
    //滚动lite:基于jCarouselLite 首尾复制添加重构dom结构，curr进行逻辑获取，动画执行 curr*li的宽度
    var _scrollLite=function(root,settings){
        var controlVisible=settings.controlVisible,
        leftBtn=$(settings.leftBtn,root),//前一页按钮
        rightBtn=$(settings.rightBtn,root),//后一页按钮
        disableClass=settings.disableClass,
        view=$(settings.view,root),//可视区域
        viewNum=settings.visible,//可视个数
        scrollObj=$(settings.scrollObj,root),//滚动层
        scrNum=settings.scrollNum,//一次滚动的个数
        vertical=settings.vertical,
        navItem=$(settings.navItem,root),
        navCurr=settings.navCurr,
        countShow=$(settings.countShow,root),
        navBool=settings.navBool,
        autoPlay=settings.auto,
        inter,
        interTime=settings.interTime,
        tli=scrollObj.children(),//原始子对象
        speed=settings.speed,
        curr=0, //真实当前索引
        liNum=scrollObj.children().size(),//真实li个数
        imgText=$(settings.imgText,root),

        running = false,//执行状态
        nli,//复制后的新子对象
        nliW,//新子对象宽度
        nliH,//新子对象高度
        nCurr,//新子对象当前索引号0开始
        nliNum,//新子对象个数
        nliSize,
        callBack=settings.callBack,//动画回调函数
        easing=settings.easing,//动画缓冲系数
        _this=this;
        //复制首尾dom结构变化，以及css初始化
        this.newDom={
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
        this.newDom.htmlInit().cssInit();
        //执行效果
        this.effectLoop={
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
                    leftBtn.on("click",function() {
                        if(!running){
                            curr-=scrNum;
                        }
                        _this.effectLoop.scrollGo();
                    });
                };
                if(rightBtn){
                    rightBtn.on("click",function() {
                        if(!running){
                            curr+=scrNum;
                        }
                        _this.effectLoop.scrollGo();
                    });
                }
                return this;
            },
            //tab滚动
            navItem:function(){
                if(navBool){
                    var navLg=navItem.length;
                    navItem.on(settings.navEvt,function(){
                        $(this).addClass(navCurr).siblings().removeClass(navCurr);
                        curr=$(this).index();
                        _this.effectLoop.scrollGo();
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
                    _this.effectLoop.scrollGo();
                }, interTime);
            },
            autoDo:function(){
                if(autoPlay){
                    _this.effectLoop.autoControl();
                };
                scrollObj.hover(function(){
                    if(autoPlay){
                        clearInterval(inter);
                    }
                },function(){
                    if(autoPlay){
                        clearInterval(inter);
                        _this.effectLoop.autoControl();
                    }
                });

                return this;
            }
        };
        this.effectLoop.evtBind(leftBtn,rightBtn).navItem().autoDo();
        //控制显示
        root.hover(function(){
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
    };
    slider.prototype.scrollLite=function(options){
        var conf={
            mod:".scorll-box",//选择器
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
        },_this=this;
        _this.settings=$.extend(this.opt,conf,options);
        var mod=$(_this.settings.mod);
        mod.each(function(){
            new _scrollLite($(this),_this.settings);
        });
        return this;
    };
    //相册幻灯片
    var _gallery=function(root,settings){
        var effect=settings.effect,
        showObj=$(settings.bigShow,root),
        bH=showObj.height(),
        overBox=$(settings.overBox,root),
        overClose=$(settings.overClose,root),

        navItem=$(settings.navItem,root),
        lg=navItem.size(),
        navW=navItem.outerWidth(true),
        navH=navItem.outerHeight(false),
        navCurr=settings.navCurr,
        navVisible=settings.navVisible,//小图多少为一组
        page=1,//小图滚动前的计数条件
        num=0,//小图索引以及鼠标点击次数
        imgSrc,
        recordRead=[],
        leftBtn=$(settings.leftBtn,root),
        rightBtn=$(settings.rightBtn,root),

        imgArr=[],//lightBox所需的图片容器
        lightBool=settings.lightBool,
        lightBoxOverlay=$(settings.lightBoxOverlay),
        lightBoxShow=$(settings.lightBoxShow),
        lightBoxCloseWarp=$(settings.lightBoxCloseWarp),
        lbPrev=$(settings.lbPrev),
        lbNext=$(settings.lbNext),
        lightBoxImgWarp=$(settings.lightBoxImgWarp),
        lbTit=$(settings.lbTit),//图片标题
        lbMiss=settings.lbMiss,//灯箱位置存在误差
        _this=this;
        /**初始化css**/
        this.init={
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
            }
        };
        this.init[effect]();
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
                leftBtn.on("click",function(){
                    if(num<=0){
                        return false;
                        //num=lg-1;
                    }else{
                        num--;
                    }
                    run[effect](false);
                });

                rightBtn.on("click",function(){
                    if(num>=lg-1){
                        return false;
                        //num=0;
                    }else{
                        num++;
                    }
                    run[effect](true);
                });

                navItem.on("click",function(){
                    var index=$(this).index();
                    $(this).addClass(navCurr).siblings().removeClass(navCurr);
                    num=index;
                    run[effect]();
                });

                overClose.on("click",function(){
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
            this.init.lightBox();
            evt.lightBox();
        };
    };
    slider.prototype.gallery=function(options){
        var conf={
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
            };
        var settings=this.settings=$.extend(this.opt,conf,options);
        var mod=$(settings.selectElem);
        mod.each(function(){
            new _gallery($(this),settings);
        });
        return this;
    };
})(jQuery,this);