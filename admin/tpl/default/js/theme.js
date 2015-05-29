$(function () {

  // navbar notification popups
  $(".notification-dropdown").each(function (index, el) {
    var $el = $(el);
    var $dialog = $el.find(".pop-dialog");
    var $trigger = $el.find(".trigger");
    
    $dialog.click(function (e) {
        e.stopPropagation()
    });
    $dialog.find(".close-icon").click(function (e) {
      e.preventDefault();
      $dialog.removeClass("is-visible");
      $trigger.removeClass("active");
    });
    $("body").click(function () {
      $dialog.removeClass("is-visible");
      $trigger.removeClass("active");
    });

    $trigger.click(function (e) {
      e.preventDefault();
      e.stopPropagation();
      
      // hide all other pop-dialogs
      $(".notification-dropdown .pop-dialog").removeClass("is-visible");
      $(".notification-dropdown .trigger").removeClass("active")

      $dialog.toggleClass("is-visible");
      if ($dialog.hasClass("is-visible")) {
        $(this).addClass("active");
      } else {
        $(this).removeClass("active");
      }
    });
  });


  // skin changer
  $(".skins-nav .skin").click(function (e) {
    e.preventDefault();
    if ($(this).hasClass("selected")) {
      return;
    }
    $(".skins-nav .skin").removeClass("selected");
    $(this).addClass("selected");
    
    if (!$("#skin-file").length) {
      $("head").append('<link rel="stylesheet" type="text/css" id="skin-file" href="">');
    }
    var $skin = $("#skin-file");
    if ($(this).attr("data-file")) {
      $skin.attr("href", $(this).data("file"));
    } else {
      $skin.attr("href", "");
    }

  });


  // sidebar menu dropdown toggle
  $("#dashboard-menu .dropdown-toggle").click(function (e) {
    e.preventDefault();
    var $item = $(this).parent();
    $item.toggleClass("active");
    if ($item.hasClass("active")) {
      $item.find(".submenu").slideDown("fast");
    } else {
      $item.find(".submenu").slideUp("fast");
    }
  });


  // mobile side-menu slide toggler
  var $menu = $("#sidebar-nav");
  $("body").click(function () {
    if ($(this).hasClass("menu")) {
      $(this).removeClass("menu");
    }
  });
  $menu.click(function(e) {
    e.stopPropagation();
  });
  $("#menu-toggler").click(function (e) {
    e.stopPropagation();
    $("body").toggleClass("menu");
  });
  $(window).resize(function() { 
    $(this).width() > 769 && $("body.menu").removeClass("menu")
  })


	// build all tooltips from data-attributes
	$("[data-toggle='tooltip']").each(function (index, el) {
		$(el).tooltip({
			placement: $(this).data("placement") || 'top'
		});
	});


  // custom uiDropdown element, example can be seen in user-list.html on the 'Filter users' button
	var uiDropdown = new function() {
  	var self;
  	self = this;
  	this.hideDialog = function($el) {
    		return $el.find(".dialog").hide().removeClass("is-visible");
  	};
  	this.showDialog = function($el) {
    		return $el.find(".dialog").show().addClass("is-visible");
  	};
		return this.initialize = function() {
  		$("html").click(function() {
    		$(".ui-dropdown .head").removeClass("active");
      		return self.hideDialog($(".ui-dropdown"));
    		});
    		$(".ui-dropdown .body").click(function(e) {
      		return e.stopPropagation();
    		});
    		return $(".ui-dropdown").each(function(index, el) {
      		return $(el).click(function(e) {
      			e.stopPropagation();
      			$(el).find(".head").toggleClass("active");
      			if ($(el).find(".head").hasClass("active")) {
        			return self.showDialog($(el));
      			} else {
        			return self.hideDialog($(el));
      			}
      		});
    		});
    	};
  	};

    // instantiate new uiDropdown from above to build the plugins
  	new uiDropdown();
    //全选切换
  	(function(){
  		var host=location.host;
  		var articleObj=$("#deleteAllArticle");
  		var artUrl="http://"+host+"/admin/article/deleteAll";
  		
  		var cateObj=$("#deleteAllCate");
  		var cateUrl="http://"+host+"/deleteArr";
  		var $arr=[];
  		var bool=false;
  		
  		Array.prototype.indexOf = function(val) {

            for (var i = 0; i < this.length; i++) {

                if (this[i] == val) return i;

            }

            return -1;

        };

        Array.prototype.remove = function(val) {

            var index = this.indexOf(val);

            if (index > -1) {

                this.splice(index, 1);

            }

        };
        
  		//全选事件
  		$(".table th input:checkbox").click(function () {
  	  		var $checks = $(this).closest(".table").find("tbody input:checkbox");
  	  		var $checkbox=$(this).closest(".table").find("tbody input:checkbox[name!='allSelected']");
  	  		if ($(this).is(":checked")) {
  	  			$checks.prop("checked", true);
  	  			$checkbox.each(function(){
  	  	  			$arr.push($(this).val());
  	  	  		});
  	  			bool=true;
  	  		} else {
  	  			$checks.prop("checked", false);
  	  			$arr=[];
  	  			bool=false;
  	  		}
  	  		//console.log("all:"+$arr);
  	  	});
  		//单个checkBox事件
  		$("#tableList tbody input:checkbox[name!='allSelected']").click(function(){
  			var _id=$(this).val();
  			if ($(this).is(":checked")) {
  	  			$(this).attr({"checked":true})
  	  			$arr.push(_id);
  	  		} else {
  	  			$(this).attr({"checked":false})
  	  			$arr.remove(_id);
  	  		}
  			//console.log($arr);
  		})
  		 //批量删除
		function moreDel(obj,url,agrId){
			obj.bind("click",function(){
				if(bool){
					//console.log(url,$arr);
	  	  			$.post(url,{arr:$arr},function(data){
	  	  				if(data){
	  	  					window.location.href="http://"+host+"/admin/system";
	  	  				}
	  	  			});
				}
	  		});
		}
		//批量删除栏目
		//moreDel(cateObj,cateUrl,$arr);
		//批量删除文章
		moreDel(articleObj,artUrl,$arr);
  	})();
  	
    // quirk to fix dark skin sidebar menu because of B3 border-box
    if ($("#sidebar-nav").height() > $(".content").height()) {
      $("html").addClass("small");
    }
    
});
