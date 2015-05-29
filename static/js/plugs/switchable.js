var AutoPlay = new Class({
	options: {
		autoplay: true,
		interval: 3000,
		pauseOnHover: true //鼠标悬停停止播放
	},
	_autoInit: function(fn) {
		if (!this.options.autoplay) return;
		this.autofn = fn || $empty;
		this.autoEvent().startAutoplay();
	},
	autoEvent: function() { //悬停时停止播放
		if (this.options.pauseOnHover && this.container) {
			this.container.addEvents({
				'mouseenter': this.stopAutoplay.bind(this),
				'mouseleave': function() {
					this.startAutoplay();
				}.bind(this)
			});
		}
		return this;
	},
	startAutoplay: function() {
		this.paused = false;
		this.autoTimer = function() {
			if (this.paused) return;
			this.autofn();
		}.periodical(this.options.interval, this);
	},
	stopAutoplay: function() {
		if (this.autoTimer) {
			$clear(this.autoTimer);
			this.autoTimer = undefined;
		}
		this.paused = true;
	}
});

var LazyLoad = new Class({ //延时加载基类
	Implements: [Options, Events],
	options: {
		img: 'img-lazyload',
		//存图象地址的属性
		textarea: 'textarea-lazyload',
		//textarea的class
		lazyDataType: 'textarea',
		//延时类型
		execScript: true,
		//是否执行脚本
		islazyload: true,
		//是否执行延时操作
		lazyEventType: 'beforeSwitch' //要接触延时的事件
	},
	loadCustomLazyData: function(containers, type) {
		var area, imgs, area_cls = this.options.textarea,
		img_data = this.options.img;
		if (!this.options.islazyload) return;
		$splat(containers).each(function(container) {
			switch (type) {
			case 'img':
				imgs = container.nodeName === 'IMG' ? [container] : container.getElements('img');
				imgs.each(function(img) {
					this.loadImgSrc(img, img_data);
				},
				this);
				break;
			default:
				area = container.getElement('textarea');
				if (area && area.hasClass(area_cls)) this.loadAreaData(area);
				break;
			}
		},
		this);
	},
	loadImgSrc: function(img, flag, cb) {
		flag = flag || this.options.img;
		var dataSrc = img.getProperty(flag);
		img.removeProperty(flag);
		if (dataSrc && img.src != dataSrc) {
			var pic = new Image();
			pic.onload = function() {
				pic.onload = null;
				img.src = dataSrc;
				cb && cb();
			}
			pic.src = dataSrc;
			//	new Asset.image(dataSrc,{onload:function(image){
			//		img.src = dataSrc;
			//	}.bind(this)});
		}
	},
	loadAreaData: function(area, cb) {
		//area.setStyle('display','none').className='';
		var content = new Element('div').inject(area, 'before');
		this.stripScripts(area.value, content);
		area.destroy();
		cb && cb();
	},
	isAllDone: function() {
		var type = this.options.lazyDataType,
		flag = this.options[type],
		elems,
		i,
		len,
		isImgSrc = type === 'img';
		if (type) {
			elems = this.container.getElements(type);
			for (i = 0, len = elems.length; i < len; i++) {
				if (isImgSrc ? elems[i].get(flag) : elems[i].hasClass(flag)) return false;
			}
		}
		return true;
	},
	stripScripts: function(v, content) {
		var scripts = '';
		var text = v.replace(/<script[^>]*>([\s\S]*?)<\/script>/gi, function() {
			scripts += arguments[1] + '\n';
			return '';
		});
		content.set('html', this.options.execScript ? text: v);
		this.options.execScript && $exec(scripts);
	},
	_lazyloadInit: function(panel) {
		var loadLazyData = function() {
			var containers = $type(panel) == 'function' ? panel(arguments) : panel;
			this.loadCustomLazyData(containers, this.options.lazyDataType);
			if (this.isAllDone()) {
				this.removeEvent(this.options.lazyEventType, arguments.callee);
			}
		};
		this.addEvent(this.options.lazyEventType, loadLazyData.bind(this));
	}
});

var DataLazyLoad = new Class({
	Extends: LazyLoad,
	options: {
		threshold: null,
		//获取要延时的窗口距离
		syncEl: null,
		//images ,areas
		config: {
			mod: 'manual',
			//延时模式
			diff: 'default',
			//默认取为两屏窗口的延时元素
			placeholder: 'none' //默认延时图片地址
		}
	},
	initialize: function(options, containers) {
		this.containers = $splat($(containers) || document);
		if (!this.containers) return;
		this.setOptions(options);
		this.lazyinit();
	},
	lazyinit: function() {
		this.threshold = this.getThreshold();
		this.filterItems().getItemsLength();
		this.initLoadEvent();
	},
	filterItems: function() {
		var containers = this.containers,
		imgs, areas, i, len, img, area, lazyImgs = [],
		lazyAreas = [];
		containers.each(function(n) {
			lazyImgs = lazyImgs.combine(n.getElements('img').filter(this.filterImg, this));
			lazyAreas = lazyAreas.combine(n.getElements('textarea').filter(this.filterArea, this));
		},
		this);
		this.images = lazyImgs;
		this.areas = lazyAreas;
		return this;
	},
	filterImg: function(img) {
		var img_data = this.options.img,
		dataSrc = img.getAttribute(img_data),
		threshold = this.threshold,
		placeholder = this.options.config.placeholder,
		isManualMod = this.options.config.mod === 'manual';
		if (isManualMod) {
			if (dataSrc) {
				if (placeholder !== 'none') img.src = placeholder;
				return true;
			}
		} else {
			if ($(img).getOffsets().y > threshold && ! dataSrc) {
				img.set(img_data, img.src);
				placeholder !== 'none' ? img.src = placeholder: img.removeAttribute('src');
				return true;
			}
		}
	},
	filterArea: function(area) {
		return area.hasClass(this.options.textarea);
	},
	initLoadEvent: function() {
		var timer, self = this,
		win = window;
		win.addEvent('domready', this.loadItems.bind(this));

		if (!this.getItemsLength()) return;
		win.addEvents({
			'scroll': loader,
			'resize': resizeloader
		});

		function resizeloader() {
			self.threshold = self.getThreshold();
			loader();
		};
		function loader() {
			if (timer) return;
			timer = function() {
				self.loadItems.call(self);
				if (!self.getItemsLength()) win.removeEvents({
					'scroll': loader,
					'resize': resizeloader
				})
				timer = null;
			}.delay(100);
		}
	},
	loadItems: function() {
		var syncEl = this.options.syncEl;
		if (syncEl) return this.loadsync(this[syncEl], syncEl);

		this.initItems(this.images.concat(this.areas));
		this.fireEvent('callback');
	},
	initItems: function(items) {
		var scrollTop = window.getScroll().y,
		threshold = this.threshold + scrollTop,
		obj = {
			images: [],
			areas: []
		},
		fnObj = {
			'areas': 'loadAreaData',
			'images': 'loadImgSrc'
		};

		items.each(function(el) {
			var isArea = el.tagName == 'TEXTAREA' ? 'areas': 'images',
			ele = el;
			if (isArea) el = $(el.getStyle('display') == 'none' ? el.parentNode: el);

			if (el.getOffsets().y <= threshold) {
				this[fnObj[isArea]](ele);
			} else {
				obj[isArea].push(ele);
			}
		},
		this);

		this.images = obj.images;
		this.areas = obj.areas;
	},
	loadsync: function(els, tag) {
		if (!els.length) return;
		var scrollTop = window.getScroll().y,
		threshold = this.threshold + scrollTop,
		isArea = tag == 'areas';

		if (this.options.trigger) $(trigger).addEvent('click', this.show.bind(this));
		else this.show();
		els = els.filter(function(el) {
			if (isArea) el = el.getStyle('display') == 'none' ? el.parentNode: el;
			return el.getOffsets().y <= threshold;
		});

		if (!els.length) return;
		var el = els.shift(),
		callback = this.loadsync.bind(this, [this[tag].erase(el), tag]);

		if (isArea) return this.loadAreaData(el, callback);
		this.loadImgSrc(el, false, callback);
	},
	getThreshold: function() {
		if (this.options.threshold) return this.options.threshold;
		var diff = this.options.config.diff,
		vh = window.getSize().y;
		if (diff === 'default') return 1 * vh;
		return vh + diff;
	},
	getItemsLength: function() {
		return this.images.length + this.areas.length;
	}
});

var Tabs = new Class({
	Implements: [AutoPlay, LazyLoad],
	options: {
		onLoad: $empty,
		onInit: $empty,
		//初始化时调用
		onBeforeSwitch: $empty,
		//tab切换前调用
		onSwitch: $empty,
		//切换时调用
		eventType: 'mouse',
		//事件类型click和mouse
		hasTriggers: true,
		//是否有触点选择
		triggersBox: '.switchable-triggerBox',
		//触点的父元素
		triggers: '.switchable-trigger',
		//触点class
		panels: '.switchable-panel',
		//显示面板class
		content: '.switchable-content',
		//显示区域class
		activeIndex: 0,
		//默认显示的元素 索引
		activeClass: 'active',
		//当前触点元素class
		steps: 1,
		//一次显示一个panel
		delay: 100,
		//mouse延时触发
		haslrbtn: false,
		//是否支持前后按钮
		prev: '.prev',
		next: '.next',
		autoplay: false,
		disableCls: null
	},
	initialize: function(container, options) {
		this.container = $(container);
		if (!this.container) return;
		this.setOptions(options);
		this.activeIndex = this.options.activeIndex;
		this.init();
	},
	init: function() {
		this.fireEvent('load');
		this.getMarkup();
		this.triggersEvent().extendPlugins();
		if (this.options.hasTriggers && this.triggers[this.activeIndex]) this.triggers[this.activeIndex].addClass(this.options.activeClass);
		if (this.options.islazyload) this.fireEvent('beforeSwitch', {
			toIndex: this.activeIndex
		});
		this.fireEvent('init');
	},
	extendPlugins: function() {
		var options = this.options;
		if (options.autoplay) this._autoInit(this.autofn.bind(this));
		if (options.islazyload) this._lazyloadInit(this.getLazyPanel.bind(this));
		Tabs.plugins.each(function(plugin) {
			if (plugin.init) plugin.init.call(this);
		},
		this);
	},
	autofn: function() {
		var index = this.activeIndex < this.length - 1 ? this.activeIndex + 1: 0;
		this.switchTo(index, 'FORWARD');
	},
	getMarkup: function() {
		var container = this.container,
		options = this.options;

		if (options.hasTriggers)

		var triggersBox = $(options.triggersBox) || container.getElements(options.triggersBox);

        if(triggersBox.length>1){
            this.triggers={};
            triggersBox.each(function(i,k){
                this.triggers[k] = i?i.getChildren():container.getElements(options.triggers);
            }.bind(this)); 
        }else{
            this.triggers = triggersBox[0] ? triggersBox[0].getChildren() : container.getElements(options.triggers);
        }

		panels = this.panels = container.getElements(options.panels);

		this.content = $(options.content) || container.getElement(options.content) ? container.getElement(options.content) : panels[0] ? panels[0].getParent() : [];

		this.content = $splat(this.content);

		if (!panels.length && this.content.length) this.panels = this.content[0].getChildren();

		if (!this.panels.length) return this;
		this.length = this.panels.length / options.steps;
	},
	triggersEvent: function() {
		var options = this.options,
		triggers = this.triggers;
        if($type(triggers) === 'object'){
            if (options.hasTriggers) Object.each(triggers,function(triggerArr){
                triggerArr.each(function(trigger,index){
                    trigger.addEvent('click', function(e) {
                        if (!this.triggerIsValid(index)) return;
                        this.cancelTimer().switchTo(index);
                    }.bind(this));

                    if (options.eventType === 'mouse') trigger.addEvents({
                        'mouseenter': function(e) {
                            if (!this.triggerIsValid(index)) return;
                            this.switchTimer = this.switchTo.delay(options.delay, this, index);
                        }.bind(this),
                        'mouseleave': this.cancelTimer.bind(this)
                    });
                },this); 
            },this);
        }else{
            if (options.hasTriggers) triggers.each(function(trigger, index) {
                trigger.addEvent('click', function(e) {
                    if (!this.triggerIsValid(index)) return;
                    this.cancelTimer().switchTo(index);
                }.bind(this));

                if (options.eventType === 'mouse') trigger.addEvents({
                    'mouseenter': function(e) {
                        if (!this.triggerIsValid(index)) return;
                        this.switchTimer = this.switchTo.delay(options.delay, this, index);
                    }.bind(this),
                    'mouseleave': this.cancelTimer.bind(this)
                });
            },
            this);
        }
		if (options.haslrbtn) this.lrbtn();
		return this;
	},
	lrbtn: function() { //前后按钮事件
		['prev', 'next'].each(function(d) {
			this[d + 'btn'] = this.container.getElement(this.options[d]).addEvent('click', function(e) {
				if (!$(e.target).hasClass(this.options.disableCls)) this[d]();
			}.bind(this));
		},
		this);
		this.disabledBtn();
	},
	disabledBtn: function() {
		var disableCls = this.options.disableCls;
		if (disableCls) {
			this.addEvent('switch', function(ev) {
				var i = ev.currentIndex,
				disableBtn = (i === 0) ? this['prevbtn'] : (i === Math.ceil(this.length) - 1) ? this['nextbtn'] : undefined;
				this['nextbtn'].removeClass(disableCls);
				this['prevbtn'].removeClass(disableCls);
				if (disableBtn) disableBtn.addClass(disableCls);
			}.bind(this));
		}
	},
	triggerIsValid: function(index) {
		return this.activeIndex !== index;
	},
	cancelTimer: function() {
		if (this.switchTimer) {
			$clear(this.switchTimer);
			this.switchTimer = undefined;
		}
		return this;
	},
	switchTo: function(index, direction) {
		var options = this.options,
		triggers = this.triggers,
		panels = this.panels,
		activeIndex = this.activeIndex,
		steps = options.steps,
		fromIndex = activeIndex * steps,
		toIndex = index * steps;


		if (!this.triggerIsValid(index)) return this;

		this.fireEvent('beforeSwitch', {
			toIndex: index
		});

        if($type(triggers)==='object'){
        if (options.hasTriggers){ 
            Object.each(triggers,function(triggerArr){
                this.switchTrigger(activeIndex > - 1 ? triggerArr[activeIndex] : null, triggerArr[index]);
            },this);
        }
        }else{
		if (options.hasTriggers) this.switchTrigger(activeIndex > - 1 ? triggers[activeIndex] : null, triggers[index]);
        }
		if (direction === undefined) direction = index > activeIndex ? 'FORWARD': 'BACKWARD';

		this.switchView(
		panels.slice(fromIndex, fromIndex + steps), panels.slice(toIndex, toIndex + steps), index, direction);

		this.activeIndex = index;

		return this.fireEvent('switch', {
			currentIndex: index
		});
	},
	switchTrigger: function(fromTrigger, toTrigger, index) {
		var activeClass = this.options.activeClass;
		if (fromTrigger) fromTrigger.removeClass(activeClass);
		toTrigger.addClass(activeClass);
	},
	switchView: function(fromPanels, toPanels, index, direction) {
		fromPanels[0].setStyle('display', 'none');
		toPanels[0].setStyle('display', '');
	},
	prev: function() {
		var activeIndex = this.activeIndex;
		this.switchTo(activeIndex > 0 ? activeIndex - 1: this.length - 1, 'BACKWARD');
	},
	next: function() {
		var activeIndex = this.activeIndex;
		this.switchTo(activeIndex < this.length - 1 ? activeIndex + 1: 0, 'FORWARD');
	},
	getLazyPanel: function(args) {
		var steps = this.options.steps,
		from = args[0].toIndex * steps,
		to = from + steps;
		return this.panels.slice(from, to);
	}
});

Tabs.plugins = [];

Tabs.Effects = {
	none: function(fromEls, toEls) {
		fromEls[0].setStyle('display', 'none');
		toEls[0].setStyle('display', 'block');
	},
	fade: function(fromEls, toEls) {
		if (fromEls.length !== 1) {
			throw new Error('fade effect only supports steps == 1.');
		}
		var fromEl = fromEls[0],
		toEl = toEls[0];

		if (this.anim) this.anim.cancel();
		this.anim = new Fx.Tween(fromEl, {
			duration: this.options.duration,
			onStart: function() {
				toEl.setStyle('opacity', 1);
			},
			onCancel: function() {
				this.element.setStyles({
					'opacity': 0
				});
				this.fireEvent('complete');
			},
			onComplete: function() {
				toEl.setStyle('zIndex', 9);
				fromEl.setStyle('zIndex', 1);
				this.anim = undefined;
			}.bind(this)
		}).start('opacity', 1, 0);
	},
	scroll: function(fromEls, toEls, index, direction) {
		var self = this,
		options = this.options,
		activeIndex = this.activeIndex,
		isX = options.effect === 'scrollx',
		len = this.length,
		content = this.content[0],
		viewDiff = this.viewSize[isX ? 0: 1],
		steps = options.steps,
		panels = this.panels,
		prop = isX ? 'left': 'top',
		diff = - viewDiff * index,
		from,
		isCritical,
		isBackward = direction !== 'FORWARD';

		isCritical = (isBackward && activeIndex === 0 && index === len - 1) || (!isBackward && activeIndex === len - 1 && index === 0);

		if (isCritical) {
			diff = position.call(this, true);
		}

		fromp = content.getStyle(prop).toInt();

		fromp = isNaN(fromp) ? 0: fromp;

		if (this.anim) this.anim.cancel();

		this.anim = new Fx.Tween(content, {
			duration: this.options.duration,
			onComplete: function() {
				if (isCritical) position.call(self);
				this.anim = undefined;
			}.bind(this)
		}).start(prop, fromp, diff);

		function position(reset) {
			var start = isBackward ? len - 1: 0,
			from = start * steps,
			to = (start + 1) * steps,
			i;

			for (i = from; i < to; i++) {
				var l = (isBackward ? - 1: 1) * viewDiff * len;
				panels[i].setStyle('position', reset ? 'relative': '').setStyle(prop, reset ? l: '');
			}
			if (reset) return isBackward ? viewDiff: - viewDiff * len;
			return content.setStyle(prop, isBackward ? - viewDiff * (len - 1) : '');
		}
	}
};

Effects = Tabs.Effects;
Effects['scrollx'] = Effects['scrolly'] = Effects.scroll;

var Switchable = new Class({
	Extends: Tabs,
	options: {
		autoplay: true,
		effect: 'none',
		circular: false,
		//是否开启循环滚动
		duration: 500,
		direction: 'FORWARD',
		//'BACKWARD'	
		viewSize: [] ,//显示区域的大小
		position:'absolute'
	},
	extendPlugins: function() {
		this.parent();
		this.effInit();
	},
	effInit: function() {
		var options = this.options,
		effect = options.effect,
		panels = this.panels,
		content = this.content[0],
		steps = options.steps,
		activeIndex = this.activeIndex,
		len = panels.length;
        var size = panels[0].measure(function(){
            return this.getSize();
        });
		this.viewSize = [options.viewSize[0] || size.x * steps, options.viewSize[1] || size.y * steps];
		if (effect !== 'none') {
			switch (effect) {
			case 'scrollx':
			case 'scrolly':
		 if (options.position === 'absolute'){
            content.setStyle('position','absolute');
            content.getParent().setStyle('position','relative');
            if (effect === 'scrollx') {
              panels.setStyle('float','left');
              content.setStyle('width',this.viewSize[0] * (len/steps));
            }
          }
          if (options.position === 'relative'){
            content.setStyle('position','relative');
            content.getParent().setStyles({'position':'relative','width':this.viewSize[0]});
            if (effect === 'scrollx') {
              panels.setStyles({'float':'left','width':this.viewSize[0]});
              content.setStyle('width',this.viewSize[0] * (len/steps));
            }
          }
				break;
			case 'fade':
				var min = activeIndex * steps,
				max = min + steps - 1,
				isActivePanel;

				panels.each(function(panel, i) {
					isActivePanel = i >= min && i <= max;
					panel.setStyles({
						opacity: isActivePanel ? 1: 0,
						position: 'absolute',
						zIndex: isActivePanel ? 9: 1
					});
				});
				break;
			default:
				break;
			}
		}
	},
	switchView: function(fromEls, toEls, index, direction) {
		var options = this.options,
		effect = options.effect,
		circular = options.circular,
		fn = $type(effect) == 'function' ? effect: Effects[effect];
		if (circular) direction = options.direction;

		if (fn) fn.call(this, fromEls, toEls, index, direction);
	}
});

Switchable.autoRender = function(autoClass, container) {
	var cls = autoClass || '.Auto_Widget',
	clt = $(container || document.body).getElements(cls);
	if (clt.length) clt.each(function(el) {
		var type = el.get('data-widget-type'), config;
		if (type && ('Tabs Switchable DropMenu Slide Accordion DataLazyLoad Popup Countdown'.indexOf(type) > - 1)) {
			try {
				config = el.get('data-widget-config') || {};
				if (type == 'DataLazyLoad') return new window[type](JSON.decode(config), el);
				new window[type](el, JSON.decode(config));
			} catch(e) {}
		}
	});
};

var Accordion = new Class({
	Extends: Tabs,
	options: {
		eventType: 'click',
		activeTriggerCls: 'selected',
		onlyTrigger: false,
		multiple: false,
        effect:false
	},
	triggerIsValid: function(index) {
		if (this.options.onlyTrigger) return true;
		return this.activeIndex !== index || this.options.multiple;
	},
	switchView: function(fromPanels, toPanels, index) {
		var options = this.options,
		panel = toPanels[0];
        this.panelHeight = panel.measure(function(){
            return this.getSize().y;
        });
        if(options.effect){panels.setStyles({'overflow':'hidden'});}
		if (options.onlyTrigger) {
			this.triggers[index].toggleClass(options.activeTriggerCls);
			panel.toggleClass(options.activeTriggerCls);
			return panel.setStyle('display', panel.getStyle('display') == 'none' ? 'block': 'none');
		}
		if (options.multiple) {
            this.triggers[index].toggleClass(options.activeClass);
            if(options.effect){
                if(panel.getStyle('display') == 'none'){
                    panel.setStyles({
                        'display':'',
                        'height':0
                    });
                    new Fx.Tween(panel,{
                         onComplete:function(){
                            this.element.setStyles({
                                'height':this.panelHeight
                            });
                        }
                    }).start('height',0,this.panelHeight)
                }else{
                    panel.setStyle('height',this.panelHeight);
                    new Fx.Tween(panel,{
                         onComplete:function(){
                            this.element.setStyles({
                                'height':this.panelHeight
                            });
                            panel.setStyle('display','none');
                        }
                    }).start('height',this.panelHeight,0)
                }
            }else{
                panel.setStyle('display', panel.getStyle('display') == 'none' ? 'block': 'none'); 
            }
		}else{
            if(options.effect){
                panel.setStyles({
                    'display':'',
                    'height':0
                });
                new Fx.Tween(panel,{
                     onComplete:function(){
                        this.element.setStyles({
                            'height':this.panelHeight
                        });
                    }
                }).start('height',0,this.panelHeight)
                fromPanels[0].setStyle('height',this.panelHeight);
                new Fx.Tween(fromPanels[0],{
                     onComplete:function(){
                        this.element.setStyles({
                            'height':this.panelHeight
                        });
                        fromPanels[0].setStyle('display','none');
                    }
                }).start('height',this.panelHeight,0);
            }else{
                fromPanels[0].setStyle('display', 'none');
                panel.setStyle('display','block');
            }
		}
	}
});

var DropMenu = new Class({
	Implements: [LazyLoad],
	options: {
		onLoad: $empty,
		onShow: $empty,
		onHide: $empty,
		showMode: function(menu) {
			menu.setStyle('display', 'block');
		},
		hideMode: function(menu) {
			menu.setStyle('display', 'none');
		},
		dropClass: 'droping',
		eventType: 'mouse',
		relative: false,
		stopEl: false,
		stopState: false,
		lazyEventType: 'show',
		offset: {
			x: 0,
			y: 20
		}
	},
	initialize: function(el, options) {
		this.element = $(el);
		if (!this.element) return;
		this.setOptions(options);
		var menu = this.options.menu;
		this.menu = $(menu) || $(this.element.get('dropmenu')) || this.element.getParent().getElement('.' + menu);
		if (!this.menu) return;
		this.load().attach()._lazyloadInit(this.menu);
	},
	attach: function() {
		var options = this.options,
		stopState = options.stopState,
		dropClass = options.dropClass,
		eventType = options.eventType;
		if (eventType != 'mouse') {
			this.element.addEvent('click', function(e) {
				if (this.showTimer) $clear(this.showTimer);
				if (stopState) e.stop();
				if (this.status) return;
				this.showTimer = this.show().outMenu.delay(200, this);
			}.bind(this));
		} else {
			$$(this.element, this.menu).addEvents({
				'mouseover': function(e) {
					if (!this.status) this.show();
					if (this.timer) $clear(this.timer);
				}.bind(this),
				'mouseleave': function() {
					if (!this.status) return;
					this.timer = this.hide.delay(200, this);
				}.bind(this)
			});
		}
		this.menu.addEvent('click', function(e) {
			if (options.stopEl) return e.stop();
			return this.hide();
		}.bind(this));
		return this;
	},
	load: function() {
		if (this.options.relative) this.position({
			page: this.element.getPosition(this.options.relative)
		});
		return this.fireEvent('load', [this.element, this]);
	},
	show: function() {
		this.element.addClass(this.options.dropClass);
		this.options.showMode.call(this, this.menu);
		this.status = true;
		return this.fireEvent('show', this.menu);
	},
	hide: function() {
		this.options.hideMode.call(this, this.menu);
		this.element.removeClass(this.options.dropClass);
		this.status = false;
		this.fireEvent('hide', this.menu);
	},
	position: function(event) {
		var options = this.options,
		relative = $(options.relative),
		size = (relative || window).getSize(),
		scroll = (relative || window).getScroll();
		var menu = {
			x: this.menu.offsetWidth,
			y: this.menu.offsetHeight
		};
		var props = {
			x: 'left',
			y: 'top'
		};
		for (var z in props) {
			var pos = event.page[z] + this.options.offset[z];
			if ((pos + menu[z] - scroll[z]) > size[z]) pos = event.page[z] - this.options.offset[z] - menu[z];
			this.menu.setStyle(props[z], pos);
		}
	},
	outMenu: function() {
		var _this = this;
		document.body.addEvent('click', function(e) {
			if (_this.options.stopEl != e.target && _this.menu) {
				_this.hide.call(_this);
				$clear(_this.showTimer);
				this.removeEvent('click', arguments.callee);
			}
		});
	}
});

var Popup = new Class({
	Implements: [Options, Events],
	options: {
		onLoad: $empty,
		onShow: $empty,
		onHide: $empty,
        isShowPopup:false,
		trigger: 'trigger',
		triggerType: 'mouse',
		align: {
			node: 'trigger',
			points: ['cc', 'cc'],
			offset: [0, 0]
		}
	},
	initialize: function(el, options) {
		this.element = $(el);
		if (!this.element) return;
		this.setOptions(options);
		this.render();
	},
	render: function() {
		var trigger = $(this.options.trigger) || document.getElement(this.options.trigger);
		if (!trigger) return;
		if (this.options.triggerType === 'mouse') {
			trigger.addEvents({
				'mouseover': function(e) {
					this.show();
				}.bind(this),
				'mouseout': function(e) {
					this.hide();
				}.bind(this)
			});
		} else if (this.options.triggerType === 'click') {
			trigger.addEvent('click', function() {
				if (this.element.getStyle('display') === 'none') {
					this.show();
				} else {
					this.hide();
				}
			}.bind(this));
		}
		this._getPosition();
		if(!this.options.isShowPopup)this.hide();
		this.fireEvent('load');
	},
	show: function() {
		this._getPosition();
		$(this.element).setStyle('visibility', 'visible');
		this.fireEvent('show');
	},
	hide: function() {
		$(this.element).setStyle('visibility', 'hidden');
		this.fireEvent('hide');
	},
	_getPosition: function() {
		var trigger = $(this.options.align.node) || document.getElement(this.options.align.node);
		var triggerAlign = this._getXy(this.options.align.points[0]);
		var triggerPos = trigger.getCoordinates();
		var target = [triggerPos.left + triggerAlign[0] * triggerPos.width, triggerPos.top + triggerAlign[1] * triggerPos.height];
		var nodeAlgin = this._getXy(this.options.align.points[1]);
		this.element.setStyles({
			left: target[0] - nodeAlgin[0] * this.element.getCoordinates().width + this.options.align.offset[0],
			top: target[1] - nodeAlgin[1] * this.element.getCoordinates().height + this.options.align.offset[1],
			position: 'absolute'
		});
	},
	_getXy: function(e) {
		var Pos = {
			tl: [0, 0],
			tc: [0.5, 0],
			tr: [1, 0],
			cl: [0, 0.5],
			cc: [0.5, 0.5],
			cr: [1, 0.5],
			bl: [0, 1],
			bc: [0.5, 1],
			br: [1, 1]
		};
		return Pos[e];
	}
});

var Countdown = new Class({
	Implements: [Options, Events],
	options: {
		onLoad: $empty,
		onEnd: $empty,
		endTime: '30000',//详细时间格式月(英文) 日 年 时:分:秒
        startTime:$empty,
		interval: 1000,
		minDigit: 1,
        dataFormat:3,
		isMsecond: false,
		timeRunCls: '.count-run',
		timeEndCls: '.count-end',
		timeUnitCls: {
			'd': '.count-d',
			'h': '.count-h',
			'm': '.count-m',
			's': '.count-s',
			'i': '.count-i'
        },
        isToNowTime:true
	},
	initialize: function(el, options) {
		this.element = $(el);
		if (!this.element) return;
		this.setOptions(options);
		this.attach();
		this.fireEvent('load');
	},
	attach: function() {
        if(!this.options.endTime) return;
		this.startRun(this.formatTime(this.options.endTime), this.options.interval);
	},
	formatTime: function(time) {
        if(!time.test(/\s|\:/g)){
            return parseInt(time) / 1000;
        }else if(this.options.isToNowTime){
            if(!new Date(this.options.endTime)) return;
            return (new Date(this.options.endTime).getTime() / 1000 ) - (new Date().getTime() / 1000);
        }else if(this.options.startTime){
            if(!new Date(this.options.endTime) || !new Date(this.options.startTime)) return;
            return (new Date(this.options.endTime).getTime() / 1000) - (new Date(this.options.startTime).getTime() / 1000);
        }
	},
	addZero: function(num) {
		if (this.options.minDigit === 2 && num.toString().length === 1) return '0' + num;
		return num;
	},
	rendDom: function(time) {
		var d = parseInt(time / 86400);
        if(this.options.dataFormat <=3) d = 0;
		var h = parseInt((time - d * 86400) / 3600);
        if(this.options.dataFormat <=2) h = 0;
		var m = parseInt((time - d * 86400 - h * 3600) / 60);
        if(this.options.dataFormat <=1) m = 0;
		var s = parseInt((time - d * 86400 - h * 3600) % 60);
        if(this.options.dataFormat <=1) s = time;
		var dDom = $(this.options.timeUnitCls.d) || document.getElement(this.options.timeUnitCls.d);
		if (dDom) dDom.set('html', this.addZero(d));
		var hDom = $(this.options.timeUnitCls.h) || document.getElement(this.options.timeUnitCls.h);
		if (hDom) hDom.set('html', this.addZero(h));
		var mDom = $(this.options.timeUnitCls.m) || document.getElement(this.options.timeUnitCls.m);
		if (mDom) mDom.set('html', this.addZero(m));
		var sDom = $(this.options.timeUnitCls.s) || document.getElement(this.options.timeUnitCls.s);
		if (sDom) sDom.set('html', this.addZero(s));
	},
	startRun: function(time, interval) {
		$(this.options.timeEndCls) || document.getElements(this.options.timeEndCls).setStyle('display', 'none');
		$(this.options.timeRunCls) || document.getElements(this.options.timeRunCls).setStyle('display', '');
		this.t = setInterval(function() {
			time--;
			this.rendDom(time);
			if (time === 0) {
				this.endRun();
				clearInterval(this.t);
			}
		}.bind(this), interval);
		if (this.options.isMsecond) {
			var num = 9;
			this.i = setInterval(function() {
				$(this.options.timeUnitCls.i) || document.getElement(this.options.timeUnitCls.i).set('html', num--);
				if (num < 0) num = 9;
			}.bind(this), 100);
		}
	},
	endRun: function() {
		$(this.options.timeRunCls) || document.getElements(this.options.timeRunCls).setStyle('display', 'none');
		$(this.options.timeEndCls) || document.getElements(this.options.timeEndCls).setStyle('display', '');
		this.fireEvent('end');
	}
});

var Slide = new Class({
	Extends: Switchable,
	options:{
		viewStep:4
	},
	effect:function(fromEls, toEls, index, direction){
		var self = this, options = this.options,
		activeIndex = this.activeIndex,
		isX = options.effect === 'scrollx',
		len = this.length, content = this.content[0],
		viewDiff = this.viewSize[isX ? 0: 1],
		steps = options.steps, panels = this.panels,
		prop = isX ? 'left': 'top', diff = - viewDiff * index, from,
		isCritical, isBackward = direction !== 'FORWARD';

		isCritical = (isBackward && activeIndex === 0 && index === len - 1) || (!isBackward && activeIndex === len - 1 && index === 0);

		if (isCritical) { diff = position.call(this, true); }

		fromp = content.getStyle(prop).toInt();

		fromp = isNaN(fromp) ? 0: fromp;

		if (this.anim) return this;
		
		var  temp= [], viewStep = options.viewStep,viewIndex = viewStep - steps;
		this.anim = new Fx.Tween(content, {
			duration: this.options.duration,
			onStart:function(){
				this.to[0].value=isBackward?viewDiff:-viewDiff;	
				if(!isBackward){
					var l = index,j;
					for(var i = viewStep-1;i>=0;i--){
						l++;
						if(l>len){
							if(!j)j =viewStep - i;
							temp.push(i);
							panels[i].setStyle(prop,j*viewDiff);
						}
					}
					if(!index){
						panels[viewIndex].setStyle(prop,viewDiff);
						panels[0].setStyle(prop,viewDiff);
					}
				}
			},
			onComplete: function() {
				content.setStyle(prop,0);
				panels.each(function(el,i){
					var v = !index? 0 :-viewDiff*index,
						j = index+ viewStep-len;

					if(isBackward && i<j) v = -viewDiff*(index-len);
					if(!isBackward && temp.contains(i)) v = viewDiff*(viewIndex-temp[0]);
					el.setStyle('position','relative').setStyle(prop,v);
				});
				this.anim = undefined;
			}.bind(this)
		}).start(prop, fromp, diff);

		function position(reset) {
			var start = isBackward ? len - 1: 0,
				from = start * steps, to = (start + 1) * steps, i;

			for (i = from; i < to; i++) {
				var l = (isBackward ? - 1: 1) * viewDiff * len;
				panels[i].setStyle('position', reset ? 'relative': '').setStyle(prop, reset ? l: '');
			}
			if (reset) return isBackward ? viewDiff: - viewDiff * len;
			return content.setStyle(prop, isBackward ? - viewDiff * (len - 1) : '');
		}

	},
	switchView: function(fromEls, toEls, index, direction) {
		var options = this.options, circular = options.circular;
		if (circular) direction = options.direction;
        this.effect(fromEls, toEls, index, direction);
	}
});
window.addEvent('domready', Switchable.autoRender.bind(Switchable));

