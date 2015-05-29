/*
 * author:sky@note520.com
 * webSite:www.note520.com
 * update:2014-10-22
 */
(function(window, document){
    var TM=TM||{};
    TM.dom={
        getId:function(str){
            return document.getElementById(str)
        }
    }
    TM.events=function(){

        function addEvent(obj,eType,fn,bool){
            var capTrue=!!bool;
            var ieEvent="on"+eType;
            if(obj.attachEvent){
                obj.attachEvent(ieEvent,fn);
            }else{
                obj.addEventListener(eType,fn,capTrue);
            }
        }

        function removeEvent(obj,eType,fn,bool){
            var capTrue=!!bool;
            var ieEvent="on"+eType;
            if(obj.attachEvent){
                obj.detachEvent(ieEvent,fn);
            }else{
                obj.removeEventListener(eType,fn,capTrue);
            }
        }

        return{
            add:addEvent,
            remove:removeEvent
        }
    }();
    //导航条
    var NavBar=function(TM){
        var that=function(){
            this._default={
                modId:"nav",
                evtType:"mouseover",
                evtEle:"li",
                subEle:"dl"
            };
            this.init();
        };
        that.prototype={
            init:function(){
                var mod=TM.dom.getId(this._default.modId);
                console.log(mod.childNodes[1]);
            }
        }
        return that;
    }(TM);

    //调用
    window.onload=function(){
        var nav=new NavBar();
    }
})(window, document);