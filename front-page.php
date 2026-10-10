<?php
/**
 * Главная страница Revive Agency.
 *
 * Все секции свёрстаны прямо здесь, тексты приходят из ACF-полей
 * и переключаются по языку через Polylang.
 *
 * @package Revive
 */

/*
 * Load every font family used by this template in one request. Enqueueing
 * before get_header() lets WordPress print the stylesheet in <head>; the old
 * CSS @import and the <link> inside the page body caused an extra render-
 * blocking request chain.
 */
wp_enqueue_style(
	'revive-front-fonts',
	'https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=JetBrains+Mono:wght@400;500&family=Manrope:wght@400;500;600;700;800&display=swap',
	array(),
	null
);

/*
 * Decide the hero motion mode in <head>, before the header and first frame
 * can paint. The full branded scene runs once per browser session; subsequent
 * page views use the compact cut. CSS remains a no-JS fallback.
 */
if ( ! function_exists( 'revive_loader_bootstrap' ) ) {
	/**
	 * Решение о режиме сцены принимается в <head>, до первого кадра.
	 * Полная сцена играет один раз за сессию браузера; дальше — короткий срез.
	 * Без JS класс не появится и сцены не будет вовсе.
	 */
	function revive_loader_bootstrap() {
		?>
		<style id="revive-loader-ground">html{background:#03032d}</style>
		<script id="revive-loader-bootstrap">
		(function (d, w) {
			'use strict';
			var root = d.documentElement;
			/* Полная сцена с лейблом REVIVE AGENCY играет на КАЖДОМ заходе.
			   Вернуть режим «полная сцена один раз за сессию, дальше короткий срез»:
			   поставить RV_LOADER_EVERY_VISIT = false. */
			var RV_LOADER_EVERY_VISIT = false; /* полная заставка — раз за сессию, дальше короткий срез */
			var returning = false;
			if (!RV_LOADER_EVERY_VISIT) {
				try { returning = w.sessionStorage.getItem('rvLoaderSeen.v12') === '1'; } catch (e) {}
			}
			root.classList.add('rv-l', 'rv-l-boot');
			root.classList.add(returning ? 'rv-l-return' : 'rv-l-first');
			try {
				if (w.matchMedia('(prefers-reduced-motion: reduce)').matches) {
					root.classList.add('rv-l-calm');
				}
			} catch (e) {}

			/* Аварийный выход, независимый от основного скрипта сцены: что бы
			   ни случилось ниже по странице — через 7 секунд сайт открыт. */
			w.setTimeout(function () {
				if (root.classList.contains('rv-l-done')) return;
				var dead = ['rv-l', 'rv-l-boot', 'rv-l-first', 'rv-l-return', 'rv-l-go', 'rv-l-swap'];
				for (var i = 0; i < dead.length; i++) root.classList.remove(dead[i]);
				root.classList.add('rv-l-calm');
				var s = d.querySelector('.rv-l-scene');
				if (s && s.parentNode) s.parentNode.removeChild(s);
			}, 7000);
		})(document, window);
		</script>
		<?php
	}
}
add_action( 'wp_head', 'revive_loader_bootstrap', 1 );

/*
 * Стабильная высота экрана для телефона (iOS Safari / Chrome Android).
 * При свайпе браузер прячет и показывает адресную строку — меняются svh/vh
 * и window.innerHeight, блоки меняют высоту, видео в hero «прыгает».
 * Здесь высота замеряется ОДИН раз (до первого кадра) и пересчитывается
 * только при изменении ширины (поворот экрана). Все vh/svh в CSS темы
 * идут через --rv-lvh / --rv-svh, скрипты — через window.RVVH().
 * На ПК с мышью значения обновляются при любом ресайзе окна, как раньше.
 */
if ( ! function_exists( 'revive_viewport_lock' ) ) {
	function revive_viewport_lock() {
		?>
		<script id="rv-viewport-lock" data-no-optimize="1" data-no-defer="1" data-cfasync="false">
		(function (w, d) {
			'use strict';
			/* v2. Встроенный браузер Telegram / Instagram и Safari, открытый по
			   ссылке из мессенджера, запускают страницу ещё во время анимации
			   открытия окна: высота в этот момент 0 или промежуточная. Поэтому
			   высота не замораживается сразу: пока человек не коснулся экрана,
			   любое изменение размера принимается (окно «устаканивается», это
			   видно только под заставкой). С первого касания / прокрутки высота
			   замораживается; дальше её меняет только поворот (смена ширины). */
			var r = d.documentElement;
			var touch = !!(w.matchMedia && w.matchMedia('(hover: none), (pointer: coarse)').matches);
			var lastW = 0, vh = 0, settled = false, locked = false;
			function probe(u) {
				try {
					var e = d.createElement('div');
					e.style.cssText = 'position:absolute;top:0;left:-9999px;width:1px;visibility:hidden;pointer-events:none;height:100' + u;
					r.appendChild(e);
					var h = e.getBoundingClientRect().height;
					r.removeChild(e);
					return h;
				} catch (err) { return 0; }
			}
			function lock() {
				var ih = w.innerHeight || r.clientHeight || 0;
				var iw = w.innerWidth || r.clientWidth || 0;
				if (ih < 120 || iw < 120) return false;          /* окно ещё не получило размер */
				var s = probe('svh') || ih;
				var l = probe('lvh') || probe('vh') || ih;
				if (s < 120 || l < 120) return false;
				vh = ih; lastW = iw; locked = true;
				r.style.setProperty('--rv-svh', (s / 100) + 'px');
				r.style.setProperty('--rv-lvh', (l / 100) + 'px');
				try { w.dispatchEvent(new Event('rv:vh-lock')); } catch (err) {}
				return true;
			}
			function settle() {
				if (settled) return;
				if (!locked) lock();
				if (locked) settled = true;
			}
			lock();
			/* пока размер не получен — пробуем на каждом кадре (до ~3 с) */
			var tries = 0;
			(function retry() {
				if (locked || tries++ > 180) return;
				w.requestAnimationFrame(function () { if (!lock()) retry(); });
			})();
			w.RVVH = function () { return (touch && vh) ? vh : (w.innerHeight || r.clientHeight); };
			function onResize() {
				var iw = w.innerWidth || r.clientWidth || 0;
				if (!touch || !settled || !locked || Math.abs(iw - lastW) > 1) lock();
			}
			w.addEventListener('resize', onResize, { passive: true, rvRaw: true });
			if (w.visualViewport) {
				/* WebView без своего resize: размер окна приходит только сюда */
				w.visualViewport.addEventListener('resize', function () { if (!settled || !locked) lock(); }, { passive: true });
			}
			d.addEventListener('DOMContentLoaded', function () { if (!settled) lock(); }, { rvRaw: true });
			w.addEventListener('load', function () { if (!settled) lock(); }, { rvRaw: true });
			w.addEventListener('orientationchange', function () { w.setTimeout(lock, 350); }, { rvRaw: true });
			w.addEventListener('pageshow', function (e) { if (e.persisted) { settled = false; lock(); } }, { rvRaw: true });
			/* первое касание / колесо / клавиша — с этого момента высота заморожена */
			d.addEventListener('touchstart', settle, { passive: true, capture: true });
			d.addEventListener('pointerdown', settle, { passive: true, capture: true });
			w.addEventListener('wheel', settle, { passive: true, capture: true });
			w.addEventListener('keydown', settle, { capture: true });
			w.addEventListener('scroll', function () { if ((w.pageYOffset || r.scrollTop) > 4) settle(); }, { passive: true, capture: true });
		})(window, document);
		</script>
		<?php
	}
}
add_action( 'wp_head', 'revive_viewport_lock', 0 );

get_header();
?>
<script id="rv-motion-conductor" data-no-optimize="1" data-no-defer="1" data-cfasync="false">
/* REVIVE motion conductor v1.0
   Один дирижёр движения на всю страницу.

   Зачем: до этого каждый блок вешал свой scroll-слушатель, свой rAF и свой
   IntersectionObserver со своими порогами. Отсюда — рваный ритм появлений и
   десятки обработчиков на один скролл. Дирижёр решает три вещи:

   1) ОДИН такт. Все scroll/resize-обработчики страницы собираются в один
      passive-слушатель и вызываются внутри одного requestAnimationFrame.
      Код блоков не меняется: перехватывается сам addEventListener.
   2) ОДИН порог появления. Опции «reveal»-наблюдателей приводятся к канону
      (threshold .08, rootMargin 0 0 -10%), поэтому блоки открываются в один
      и тот же момент прокрутки.
   3) СТРАХОВКА. Ни один элемент не может остаться в состоянии is-pending:
      сторож досматривает страницу и открывает всё, что попало в экран.

   Публичный API: RVMotion.onScroll / onResize / onTick / reveal / sweep.
*/
(function (win, doc) {
	'use strict';
	if (win.RVMotion) { return; }

	var root = doc.documentElement;
	var reduceQuery = win.matchMedia ? win.matchMedia('(prefers-reduced-motion: reduce)') : null;
	var reduced = !!(reduceQuery && reduceQuery.matches);

	/* ─────────────────────────────────────────────────────────────
	   1. ЕДИНЫЙ ТАКТ
	   ───────────────────────────────────────────────────────────── */

	var scrollFns = [];
	var resizeFns = [];
	var tickFns = [];
	var frame = 0;
	var lastScrollEvent = null;
	var lastResizeEvent = null;

	function invoke(fn, event) {
		try {
			if (typeof fn === 'function') { fn.call(win, event); }
			else if (fn && typeof fn.handleEvent === 'function') { fn.handleEvent(event); }
		} catch (error) {
			/* Падение одного блока не должно останавливать движение всей страницы. */
			if (win.console && win.console.warn) { win.console.warn('[rv-motion]', error); }
		}
	}

	function runFrame() {
		frame = 0;
		var scrollEvent = lastScrollEvent;
		var resizeEvent = lastResizeEvent;
		lastScrollEvent = null;
		lastResizeEvent = null;
		var i;
		if (scrollEvent) {
			for (i = 0; i < scrollFns.length; i++) { invoke(scrollFns[i], scrollEvent); }
		}
		if (resizeEvent) {
			for (i = 0; i < resizeFns.length; i++) { invoke(resizeFns[i], resizeEvent); }
		}
		for (i = 0; i < tickFns.length; i++) { invoke(tickFns[i], scrollEvent || resizeEvent); }
		sweep(false);
	}

	function request() {
		if (!frame) { frame = win.requestAnimationFrame(runFrame); }
	}

	function push(list, fn) {
		if (!fn || list.indexOf(fn) !== -1) { return; }
		list.push(fn);
	}

	function drop(list, fn) {
		var index = list.indexOf(fn);
		if (index !== -1) { list.splice(index, 1); }
	}

	/* Перехват регистрации. Всё, что не является простым passive-слушателем
	   скролла или ресайза (capture, once, AbortSignal, opts.rvRaw), уходит
	   в родной механизм без изменений. */
	var nativeAdd = win.addEventListener;
	var nativeRemove = win.removeEventListener;
	var docAdd = doc.addEventListener;
	var docRemove = doc.removeEventListener;

	function routable(type, fn, opts) {
		if (type !== 'scroll' && type !== 'resize') { return false; }
		if (!fn) { return false; }
		if (typeof fn !== 'function' && typeof fn.handleEvent !== 'function') { return false; }
		if (opts === true) { return false; }
		if (opts && typeof opts === 'object' && (opts.capture || opts.once || opts.signal || opts.rvRaw)) { return false; }
		return true;
	}

	function listFor(type) { return type === 'scroll' ? scrollFns : resizeFns; }

	win.addEventListener = function (type, fn, opts) {
		if (routable(type, fn, opts)) { push(listFor(type), fn); return; }
		return nativeAdd.call(win, type, fn, opts);
	};
	win.removeEventListener = function (type, fn, opts) {
		if (routable(type, fn, opts)) { drop(listFor(type), fn); return; }
		return nativeRemove.call(win, type, fn, opts);
	};
	doc.addEventListener = function (type, fn, opts) {
		if (routable(type, fn, opts)) { push(listFor(type), fn); return; }
		return docAdd.call(doc, type, fn, opts);
	};
	doc.removeEventListener = function (type, fn, opts) {
		if (routable(type, fn, opts)) { drop(listFor(type), fn); return; }
		return docRemove.call(doc, type, fn, opts);
	};

	nativeAdd.call(win, 'scroll', function (event) {
		lastScrollEvent = event || { type: 'scroll' };
		request();
	}, { passive: true });

	/* Телефон: при первой прокрутке браузер прячет адресную строку, и окно
	   меняет высоту — это «resize». Раньше на него все блоки заново мерили
	   себя в одном кадре (рывок на первом свайпе). На touch-устройствах
	   такие изменения только высоты не рассылаются: ширина та же — раскладка
	   та же. Поворот экрана и реальное изменение ширины проходят как раньше. */
	var touchUI = !!(win.matchMedia && win.matchMedia('(hover: none), (pointer: coarse)').matches);
	var lastW = win.innerWidth, lastH = win.innerHeight;
	nativeAdd.call(win, 'resize', function (event) {
		var w = win.innerWidth, h = win.innerHeight;
		var heightOnly = w === lastW && Math.abs(h - lastH) < 220;
		lastW = w; lastH = h;
		if (touchUI && heightOnly) { return; }
		lastResizeEvent = event || { type: 'resize' };
		lastScrollEvent = lastScrollEvent || { type: 'scroll' };
		request();
	}, { passive: true });

	/* ─────────────────────────────────────────────────────────────
	   2. ЕДИНЫЙ ПОРОГ ПОЯВЛЕНИЯ
	   ───────────────────────────────────────────────────────────── */

	/* На телефоне блок начинает проявляться, как только показался край, —
	   без паузы с пустым местом и «выскакивания» на середине экрана. */
	var touchReveal = !!(win.matchMedia && win.matchMedia('(hover: none), (pointer: coarse)').matches);
	var CANON_THRESHOLD = touchReveal ? 0.01 : 0.08;
	var CANON_MARGIN = touchReveal ? '0px 0px -4% 0px' : '0px 0px -10% 0px';
	var NativeObserver = win.IntersectionObserver;

	if (NativeObserver) {
		/* Канон применяется только к «reveal»-наблюдателям: отрицательный
		   rootMargin либо одиночный небольшой порог. Наблюдатели предзагрузки
		   (положительный rootMargin, threshold 0), наблюдатели паузы фоновых
		   петель ('12% 0px 12% 0px') и всё без опций остаются как есть. */
		var isRevealOptions = function (opts) {
			if (!opts) { return false; }
			if (opts.root) { return false; }
			var margin = typeof opts.rootMargin === 'string' ? opts.rootMargin : '';
			var threshold = opts.threshold;
			if (threshold && typeof threshold.length === 'number') { return false; }
			if (margin && margin.indexOf('-') === -1) { return false; }
			if (margin && margin.indexOf('-') !== -1) { return true; }
			return typeof threshold === 'number' && threshold > 0 && threshold <= 0.35;
		};

		var PatchedObserver = function (callback, opts) {
			if (isRevealOptions(opts)) {
				opts = { threshold: CANON_THRESHOLD, rootMargin: CANON_MARGIN };
			}
			return new NativeObserver(callback, opts);
		};
		PatchedObserver.prototype = NativeObserver.prototype;
		win.IntersectionObserver = PatchedObserver;
	}

	/* ─────────────────────────────────────────────────────────────
	   3. СТРАХОВКА ПОЯВЛЕНИЙ
	   ───────────────────────────────────────────────────────────── */

	var PENDING = '.is-pending, [data-rv-reveal]:not(.is-in)';
	var lastSweep = 0;

	function show(el) {
		if (el.classList.contains('is-pending')) {
			el.classList.remove('is-pending');
			el.classList.add('is-visible');
		}
		el.classList.add('is-in');
	}

	/* force = открыть всё, не глядя на положение (печать, reduced-motion,
	   уход со страницы, достигнут низ документа). */
	function sweep(force) {
		if (!force) {
			var now = win.performance && win.performance.now ? win.performance.now() : Date.now();
			if (now - lastSweep < 120) { return; }
			lastSweep = now;
			if (!doc.querySelector(PENDING)) { return; }
		}
		var nodes = doc.querySelectorAll(PENDING);
		if (!nodes.length) { return; }
		var viewport = win.innerHeight || root.clientHeight || 0;
		var atBottom = (win.pageYOffset + viewport) >= (doc.documentElement.scrollHeight - 240);
		for (var i = 0; i < nodes.length; i++) {
			var el = nodes[i];
			var rect = el.getBoundingClientRect();
			/* Открываем всё, что уже вошло в экран или осталось выше него.
			   Высокие панели (выше вьюпорта) раньше могли не набрать порог
			   и залипали невидимыми — здесь это исключено. */
			if (force || atBottom || rect.top < viewport * 0.92) { show(el); }
		}
	}

	/* Сторож: перепроверка в моменты, когда IntersectionObserver может
	   не сработать — прыжок по якорю, восстановление вкладки, печать,
	   мгновенная прокрутка колесом до нужной секции. */
	function guard() { sweep(false); }

	nativeAdd.call(win, 'load', function () { sweep(false); win.setTimeout(guard, 400); }, { rvRaw: true });
	nativeAdd.call(win, 'hashchange', guard, { rvRaw: true });
	nativeAdd.call(win, 'pageshow', guard, { rvRaw: true });
	nativeAdd.call(win, 'scrollend', guard, { passive: true, rvRaw: true });
	nativeAdd.call(win, 'beforeprint', function () { sweep(true); }, { rvRaw: true });
	docAdd.call(doc, 'visibilitychange', function () { if (!doc.hidden) { guard(); } }, { rvRaw: true });
	docAdd.call(doc, 'keydown', function (event) {
		if (event.key === 'End' || event.key === 'PageDown' || event.key === ' ') { win.setTimeout(guard, 60); }
	}, { passive: true, rvRaw: true });

	if (reduced) { sweep(true); }
	if (reduceQuery && typeof reduceQuery.addEventListener === 'function') {
		reduceQuery.addEventListener('change', function () {
			reduced = reduceQuery.matches;
			if (reduced) { sweep(true); }
		});
	}

	/* ─────────────────────────────────────────────────────────────
	   4. ЕДИНАЯ СИСТЕМА ПОЯВЛЕНИЙ ДЛЯ НОВЫХ БЛОКОВ
	   Разметка: <div data-rv-reveal> … <li data-rv-item> … </div>
	   Стагер внутри группы берётся из --rv-stagger, порядок — из --rv-i.
	   ───────────────────────────────────────────────────────────── */

	var revealObserver = null;

	function indexItems(group) {
		var items = group.querySelectorAll('[data-rv-item]');
		for (var i = 0; i < items.length; i++) {
			if (!items[i].style.getPropertyValue('--rv-i')) {
				items[i].style.setProperty('--rv-i', String(i));
			}
		}
	}

	function register(scope) {
		var groups = (scope || doc).querySelectorAll('[data-rv-reveal]');
		for (var i = 0; i < groups.length; i++) {
			var group = groups[i];
			if (group.dataset.rvBound === '1') { continue; }
			group.dataset.rvBound = '1';
			indexItems(group);
			if (reduced || !revealObserver) { show(group); continue; }
			revealObserver.observe(group);
		}
	}

	if (NativeObserver && !reduced) {
		revealObserver = new NativeObserver(function (entries) {
			for (var i = 0; i < entries.length; i++) {
				if (!entries[i].isIntersecting) { continue; }
				show(entries[i].target);
				revealObserver.unobserve(entries[i].target);
			}
		}, { threshold: CANON_THRESHOLD, rootMargin: CANON_MARGIN });
	}

	root.classList.add('rv-conductor');

	function boot() {
		register(doc);
		sweep(false);
	}

	if (doc.readyState === 'loading') {
		docAdd.call(doc, 'DOMContentLoaded', boot, { rvRaw: true });
	} else {
		boot();
	}

	/* ─────────────────────────────────────────────────────────────
	   5. ПАУЗА АНИМАЦИЙ ВНЕ ЭКРАНА (телефон и планшет)
	   На странице ~300 бесконечных анимаций (частицы, блики, пульсы,
	   орбиты). Раньше они шли все сразу, даже в блоках далеко за экраном,
	   и каждый кадр пересчитывал стили всей страницы — отсюда рывки при
	   прокрутке. Теперь анимации блока играют, только пока блок рядом
	   с экраном (±35% высоты), и продолжаются с того же места.
	   ───────────────────────────────────────────────────────────── */
	function cullBoot() {
		var mq = win.matchMedia && win.matchMedia('(hover: none), (pointer: coarse), (max-width: 1024px)');
		if (!NativeObserver || !mq || !mq.matches || typeof Element.prototype.getAnimations !== 'function') { return; }
		var blocks = Array.prototype.filter.call(doc.querySelectorAll('section'), function (s) {
			return !(s.parentElement && s.parentElement.closest('section'));
		});
		var paused = new WeakMap();
		/* Бесконечные блики и пульсы, которые браузер рисует покадрово на основном
		   потоке (box-shadow, background-position, left). На телефоне выключаем —
		   элементы остаются в исходном виде, как до старта блика. */
		var HEAVY = /^(btnShineBg|rvAuBtn|rvAuditPulse|rvBuildScan|rvGlobalGoldShine|rvHxBeam|rvStxPing|rvcBtnShine|rvcPulse)$/;
		function tame(el) {
			if (!touchUI) { return; }
			var anims = el.getAnimations({ subtree: true });
			for (var t = 0; t < anims.length; t++) {
				if (anims[t].animationName && HEAVY.test(anims[t].animationName)) { try { anims[t].cancel(); } catch (e) {} }
			}
		}
		for (var tb = 0; tb < blocks.length; tb++) { tame(blocks[tb]); }
		/* блики, которые включаются позже (появление блока, побуквенные слова) */
		if (touchUI) docAdd.call(doc, 'animationstart', function (event) {
			if (!HEAVY.test(event.animationName || '')) { return; }
			var anims = event.target.getAnimations ? event.target.getAnimations() : [];
			for (var q = 0; q < anims.length; q++) {
				if (anims[q].animationName === event.animationName) { try { anims[q].cancel(); } catch (e) {} }
			}
		}, { capture: true, passive: true });
		var cull = new NativeObserver(function (entries) {
			for (var i = 0; i < entries.length; i++) {
				var el = entries[i].target, list, k;
				if (entries[i].isIntersecting) {
					tame(el);
					list = paused.get(el);
					if (list) { for (k = 0; k < list.length; k++) { try { if (list[k].playState === 'paused') { list[k].play(); } } catch (e) {} } }
					paused.delete(el);
				} else {
					list = [];
					var anims = el.getAnimations({ subtree: true });
					for (k = 0; k < anims.length; k++) {
						if (anims[k].playState === 'running') { try { anims[k].pause(); list.push(anims[k]); } catch (e) {} }
					}
					paused.set(el, list);
				}
			}
		}, { rootMargin: '35% 0px 35% 0px', threshold: 0 });
		for (var b = 0; b < blocks.length; b++) { cull.observe(blocks[b]); }
	}
	nativeAdd.call(win, 'load', function () { win.setTimeout(cullBoot, 300); }, { rvRaw: true });

	win.RVMotion = {
		version: '1.0',
		onScroll: function (fn) { push(scrollFns, fn); return fn; },
		offScroll: function (fn) { drop(scrollFns, fn); },
		onResize: function (fn) { push(resizeFns, fn); return fn; },
		offResize: function (fn) { drop(resizeFns, fn); },
		onTick: function (fn) { push(tickFns, fn); return fn; },
		offTick: function (fn) { drop(tickFns, fn); },
		reveal: show,
		register: register,
		sweep: sweep,
		request: request,
		get reduced() { return reduced; },
		stats: function () {
			return { scroll: scrollFns.length, resize: resizeFns.length, tick: tickFns.length };
		}
	};
}(window, document));
</script>
<div class="rv-l-scene" role="presentation" aria-hidden="true">
  <div class="rv-l__ink">
    <span class="rv-l__glow rv-l__glow--blue"></span>
    <span class="rv-l__glow rv-l__glow--gold"></span>
    <span class="rv-l__meta rv-l__meta--tl">Strategy / Design / Technology</span>
    <span class="rv-l__meta rv-l__meta--tr">Est. 2014</span>
    <span class="rv-l__meta rv-l__meta--bl">Kyiv &mdash; Orange County</span>
    <span class="rv-l__num">000</span>
    <div class="rv-l__rail"><i></i></div>
  </div>
  <div class="rv-l__lockup"></div>
</div>
<?php
/* ===== HERO v10 | минимализм: предложение + кнопка + «120+» =====
   Видео фона то же (hero2.mp4 + постер). Заставка rv-l-scene ищет
   .hero[data-hero-motion="8.0"], .hero__title и .hero__title-mask —
   классы сохранены: надпись REVIVE AGENCY теперь фирменная подпись
   внизу экрана, а главный заголовок H1 — предложение клиенту.
   Счётчик «120+» по-прежнему анимирует скрипт по классу .hero__stats-num.
   Тексты правятся в массиве ниже. */
$rv_hx_en = function_exists( 'pll_current_language' ) && 'en' === pll_current_language();
$rv_hx = $rv_hx_en ? array(
	'h1'      => 'Websites and ads <em>that attract customers</em>',
	'cta'     => 'Discuss a project',
	'num'     => '120+',
	'numL'    => 'brands trusted us with their growth',
) : array(
	'h1'      => 'Сайти та реклама, <em>що залучають клієнтів</em>',
	'cta'     => 'Обговорити проєкт',
	'num'     => '120+',
	'numL'    => 'брендів довірили нам свій розвиток',
);
$rv_hx_poster = get_stylesheet_directory_uri() . '/hero-poster.jpg';
?>
<section class="hero rv-hx" data-hero-motion="8.0" data-hero-state="booting" aria-labelledby="rv-hx-title">
  <div class="hero__bg" aria-hidden="true" style="--hero-poster:url('<?php echo esc_url( $rv_hx_poster ); ?>')">
    <video class="hero__video" autoplay muted loop playsinline preload="auto" poster="<?php echo esc_url( $rv_hx_poster ); ?>">
      <source src="https://www.revive-agency.com/wp-content/uploads/2026/08/hero2.mp4" type="video/mp4">
    </video>
  </div>
  <div class="rv-hero-glow" aria-hidden="true"></div>
  <div class="rv-hx__shade" aria-hidden="true"></div>

  <div class="rv-hx__main">
    <h1 class="rv-hx__h1" id="rv-hx-title" data-hx style="--hd:0"><?php echo wp_kses( $rv_hx['h1'], array( 'em' => array() ) ); ?></h1>
    <div class="rv-hx__ctas" data-hx style="--hd:1">
      <a class="rv-hx__btn rv-hx__btn--gold" href="#win1"><span class="rv-hx__btn-t"><?php echo esc_html( $rv_hx['cta'] ); ?></span><span class="rv-hx__btn-ic" aria-hidden="true"><svg viewBox="0 0 16 16"><path d="M4.5 11.5l7-7M6 4.5h5.5V10"/></svg></span></a>
    </div>
  </div>

  <div class="rv-hx__sign">
    <div class="rv-hx__trust" data-hx style="--hd:2">
      <p class="rv-hx__brands"><b class="hero__stats-num"><?php echo esc_html( $rv_hx['num'] ); ?></b><span><?php echo esc_html( $rv_hx['numL'] ); ?></span></p>
    </div>
    <span class="rv-hx__spark" aria-hidden="true"></span>
    <div class="hero__title" aria-hidden="true">
      <span class="hero__title-mask" aria-hidden="true">
        <span class="hero__title-word orange" data-word="REVIVE">
          <span class="hero__letter" style="--rv-i:0">R</span><span class="hero__letter" style="--rv-i:1">E</span><span class="hero__letter" style="--rv-i:2">V</span><span class="hero__letter" style="--rv-i:3">I</span><span class="hero__letter" style="--rv-i:4">V</span><span class="hero__letter" style="--rv-i:5">E</span>
        </span>
      </span>
      <span class="hero__title-mask" aria-hidden="true">
        <span class="hero__title-word hero__title-word--agency" data-word="AGENCY">
          <span class="hero__letter" style="--rv-i:6">A</span><span class="hero__letter" style="--rv-i:7">G</span><span class="hero__letter" style="--rv-i:8">E</span><span class="hero__letter" style="--rv-i:9">N</span><span class="hero__letter" style="--rv-i:10">C</span><span class="hero__letter" style="--rv-i:11">Y</span>
        </span>
      </span>
    </div>
  </div>

<style id="rv-hx-css">
/* Всё под .hero.rv-hx — перебивает накопленные правила старого hero в style.css */
.hero.rv-hx{--gold:#F5C542;--ink:#030032;--ease:cubic-bezier(.22,1,.36,1);--px:clamp(20px,4vw,64px);
  height:auto!important;min-height:calc(calc(var(--rv-lvh,1vh)*100) - 88px);min-height:calc(calc(var(--rv-svh,1svh)*100) - 88px);
  display:flex!important;flex-direction:column!important;justify-content:flex-start!important;gap:0;
  /* снизу — место под плавающую кнопку-стрелку, чтобы она не легла на надпись */
  padding:clamp(22px,calc(var(--rv-lvh,1vh)*3.6),40px) var(--px) calc(clamp(20px,2.2vw,34px) + 70px)!important;
  overflow:hidden;color:#fff;font-family:'Inter',Arial,sans-serif}
.hero.rv-hx *,.hero.rv-hx *::before,.hero.rv-hx *::after{box-sizing:border-box}
.hero.rv-hx p,.hero.rv-hx h1,.hero.rv-hx ul{margin:0;padding:0}
.hero.rv-hx ul{list-style:none}
.hero.rv-hx i,.hero.rv-hx em{font-style:normal}
.hero.rv-hx a{text-decoration:none}

/* затемнение только там, где текст: слева и снизу — видео остаётся живым */
.hero.rv-hx .rv-hx__shade{position:absolute!important;inset:0;z-index:0!important;pointer-events:none;
  background:
    linear-gradient(90deg,rgba(3,0,50,.82) 0%,rgba(3,0,50,.55) 40%,rgba(3,0,50,.12) 78%,rgba(3,0,50,.2) 100%),
    linear-gradient(180deg,rgba(3,0,50,.45) 0%,transparent 20%,transparent 55%,rgba(3,0,50,.92) 100%)}

/* — главное: предложение и кнопка — */
.hero.rv-hx .rv-hx__main{flex:1;display:flex;flex-direction:column;justify-content:center;align-items:flex-start;padding:clamp(28px,calc(var(--rv-lvh,1vh)*5),64px) 0 clamp(24px,calc(var(--rv-lvh,1vh)*4),48px)}
.rv-hx__h1{font-family:'Manrope',Arial,sans-serif!important;font-weight:800;font-size:clamp(30px,3.4vw,58px)!important;line-height:1.06!important;letter-spacing:-.03em;
  text-transform:uppercase;color:#fff}
.rv-hx__h1 em{display:block;color:transparent;
  background:linear-gradient(100deg,#F5D488 0%,#F5C542 35%,#fff3c4 50%,#F5C542 65%,#DFAE3F 100%);background-size:220% 100%;background-position:100% 0;
  -webkit-background-clip:text;background-clip:text;-webkit-text-fill-color:transparent;
  animation:rvHxShine 2.6s 2.4s var(--ease) 1 both}
.rv-hx__ctas{display:flex;flex-wrap:wrap;gap:12px;margin-top:clamp(28px,calc(var(--rv-lvh,1vh)*4.6),48px)}
.rv-hx__btn{display:inline-flex;align-items:center;justify-content:center;gap:10px;min-height:58px;padding:0 32px;border-radius:999px;white-space:nowrap;
  font-family:'Inter',Arial,sans-serif;font-weight:800;font-size:.84rem;letter-spacing:.08em;text-transform:uppercase;
  transition:box-shadow .3s var(--ease),filter .3s ease,border-color .3s ease,background .3s ease,color .3s ease}
.rv-hx__btn--gold{position:relative;overflow:hidden;isolation:isolate;color:var(--ink)!important;background:linear-gradient(135deg,#F5D488 0%,#F5C542 48%,#DFAE3F 100%);border:1px solid rgba(245,197,66,.6);box-shadow:0 12px 34px rgba(245,197,66,.26)}
.rv-hx__btn--gold::after{content:"";position:absolute;inset:-35% auto -35% -65%;width:42%;pointer-events:none;transform:translateX(-150%) skewX(-22deg);background:linear-gradient(90deg,transparent,rgba(255,255,255,.58),transparent);animation:rvHxBtnGlint 5.8s ease-in-out 2s infinite}
.rv-hx__btn--gold:hover,.rv-hx__btn--gold:focus-visible{box-shadow:0 0 0 1px rgba(245,197,66,.45),0 14px 38px rgba(245,197,66,.34),0 0 48px 6px rgba(245,197,66,.2);filter:brightness(1.05)}
.rv-hx__btn:focus-visible{outline:2px solid rgba(245,197,66,.8);outline-offset:3px}
/* Ободок кнопки плавно расходится: ПК и телефон */
.rv-hx__btn--gold{animation:rvHxHalo 3.2s ease-out 2.6s infinite}
/* ПК: убрана жёлтая подсветка за курсором в hero */
.hero.rv-hx > .rv-hero-glow{display:none!important}

/* — строка доверия над подписью — */
.hero.rv-hx .rv-hx__sign{position:relative}
.rv-hx__trust{display:flex;align-items:center;gap:22px;margin-bottom:clamp(10px,calc(var(--rv-lvh,1vh)*1.6),16px)}
.rv-hx__brands{display:flex;align-items:center;gap:14px;flex:none}
.hero.rv-hx .hero__stats-num{display:block;min-width:2.7ch;font-family:'Manrope',Arial,sans-serif;font-weight:800;font-size:clamp(26px,2vw,32px);line-height:1;letter-spacing:-.03em;color:var(--gold);font-variant-numeric:tabular-nums;text-shadow:0 0 24px rgba(245,197,66,.35)}
.rv-hx__brands span{font-family:'JetBrains Mono',monospace;font-size:.66rem;letter-spacing:.14em;text-transform:uppercase;color:#fff}

/* — фирменная подпись на всю ширину: ширина слова ≈ 7.55 кегля (замер Manrope 800) — */
.hero.rv-hx .hero__title{margin:0!important;font-size:min(calc((100vw - 2 * min(4vw, 64px)) / 7.62), calc(var(--rv-lvh,1vh)*26))!important;line-height:.9!important;justify-content:flex-start!important;width:100%;gap:.13em;letter-spacing:-.015em!important}
/* flex-start, а не space-between: клон из заставки садится с обычным пробелом,
   и после подмены AGENCY больше не отъезжает вправо */
.hero.rv-hx .hero__title::after{display:none}
/* AGENCY — приглушённая «тень», REVIVE — золото: подпись не спорит с H1.
   Правило без .rv-hx — его же получает клон надписи в заставке. */
.hero__title-word--agency .hero__letter{color:rgba(255,255,255,.18)!important;-webkit-text-fill-color:rgba(255,255,255,.18)!important}
.hero__title-word.orange .hero__letter{color:rgba(245,197,66,.78)!important;-webkit-text-fill-color:rgba(245,197,66,.78)!important}
/* БЛИК v2 — один сплошной луч проходит через всю надпись REVIVE AGENCY.
   Раньше блик был у каждой буквы свой и его глушило правило html.rv-l-calm
   (animation:none!important) из style.css — поэтому на сайте его не было видно.
   Теперь: у всех букв общий градиент шириной 3×надписи, скрипт ниже отдаёт
   ширину надписи (--tw) и смещение каждой буквы (--x) — луч идёт единым
   фронтом. На финише — искра (.rv-hx__spark) в том же ритме. Клон в заставке не затрагивается. */
.hero.rv-hx .hero__title{--tw:1000px}
.hero.rv-hx .hero__title-word .hero__letter{background-repeat:no-repeat;background-size:calc(var(--tw) * 3) 100%;
  background-position:calc(var(--tw) * -1.75 - var(--x,0px)) 0;-webkit-background-clip:text;background-clip:text;-webkit-text-fill-color:transparent!important}
.hero.rv-hx .hero__title-word.orange .hero__letter{background-image:linear-gradient(100deg,rgba(245,197,66,.78) 0%,rgba(245,197,66,.78) 45%,#FFE08A 47.8%,#FFFBEA 49.4%,#fff 50%,#FFFBEA 50.6%,#FFE08A 52.2%,rgba(245,197,66,.78) 55%,rgba(245,197,66,.78) 100%)}
.hero.rv-hx .hero__title-word--agency .hero__letter{background-image:linear-gradient(100deg,rgba(255,255,255,.18) 0%,rgba(255,255,255,.18) 45%,rgba(255,255,255,.5) 47.8%,rgba(255,255,255,.92) 49.4%,#fff 50%,rgba(255,255,255,.92) 50.6%,rgba(255,255,255,.5) 52.2%,rgba(255,255,255,.18) 55%,rgba(255,255,255,.18) 100%)}
/* специфичность выше, чем у html.rv-l-calm … .hero__letter в style.css */
html .hero.rv-hx[data-hero-motion] .hero__title-mask .hero__title-word .hero__letter{animation:rvHxBeam 7s cubic-bezier(.5,.05,.25,1) 1.4s infinite!important}
.hero.rv-hx .rv-hx__spark{position:absolute;right:calc(var(--sr,0px) - 17px);bottom:calc(var(--th,62%) * .8);z-index:4;width:34px;height:34px;pointer-events:none;opacity:0;
  background:radial-gradient(circle,#fff 0 9%,rgba(255,236,170,.55) 16%,transparent 34%),
    linear-gradient(90deg,transparent 0 46%,#fff 50%,transparent 54%) 50% 50%/100% 10% no-repeat,
    linear-gradient(0deg,transparent 0 46%,#fff 50%,transparent 54%) 50% 50%/10% 100% no-repeat;
  filter:drop-shadow(0 0 6px rgba(255,236,170,.95));animation:rvHxSpark 7s ease-out 1.4s infinite}
/* Повторный заход (заставка раз за сессию): буквы стартуют скрытыми и поднимаются
   анимацией rvLoadWordUp из style.css. Блик не должен её перебивать — иначе надпись
   так и остаётся невидимой. Поэтому здесь обе анимации сразу. */
html.rv-l-return.rv-l-go .hero.rv-hx[data-hero-motion] .hero__title-mask .hero__title-word .hero__letter{
  animation:rvLoadWordUp var(--rv-dur-base,.6s) .04s var(--rv-l-ease,cubic-bezier(.22,1,.36,1)) both,rvHxBeam 7s cubic-bezier(.5,.05,.25,1) 1.4s infinite!important}
html.rv-l-return.rv-l-go .hero.rv-hx[data-hero-motion] .hero__title-mask:last-child .hero__title-word .hero__letter{animation-delay:.12s,1.4s!important}
html.rv-l:not(.rv-l-done) .hero.rv-hx[data-hero-motion] .hero__title-mask .hero__title-word .hero__letter,
html.rv-l:not(.rv-l-done) .hero.rv-hx .rv-hx__spark{animation-play-state:paused!important}
@keyframes rvHxBeam{
  0%{background-position:calc(var(--tw) * -1.75 - var(--x,0px)) 0}
  24%,100%{background-position:calc(var(--tw) * -.25 - var(--x,0px)) 0}
}
@keyframes rvHxSpark{
  0%,19%{opacity:0;transform:scale(.2) rotate(0deg)}
  22%{opacity:1;transform:scale(1.15) rotate(45deg)}
  30%,100%{opacity:0;transform:scale(.3) rotate(90deg)}
}

/* — появление: ждёт старта заставки, без неё играет сразу — */
.rv-hx [data-hx]{opacity:0;transform:translate3d(0,22px,0);animation:rvHxIn .9s var(--ease) both;animation-delay:calc(.25s + var(--hd,0) * .09s)}
html.rv-l:not(.rv-l-go) .rv-hx [data-hx]{animation-play-state:paused}
html.rv-l-go .rv-hx [data-hx]{animation-delay:calc(.35s + var(--hd,0) * .09s)}
@keyframes rvHxIn{to{opacity:1;transform:none}}
@keyframes rvHxShine{from{background-position:100% 0}to{background-position:0 0}}
@keyframes rvHxLetterGlint{
  0%,7%{background-position:100% 0;filter:brightness(1)}
  16%{background-position:50% 0;filter:brightness(1.35) drop-shadow(0 0 .12em var(--rv-letter-glow))}
  25%,100%{background-position:0 0;filter:brightness(1)}
}
@keyframes rvHxBtnGlint{0%,12%{transform:translateX(-150%) skewX(-22deg)}34%,100%{transform:translateX(650%) skewX(-22deg)}}
@keyframes rvHxHalo{0%{box-shadow:0 10px 26px rgba(245,197,66,.28),0 0 0 0 rgba(245,197,66,.5)}70%,100%{box-shadow:0 10px 26px rgba(245,197,66,.28),0 0 0 14px rgba(245,197,66,0)}}
/* иконка в кнопке — только на телефоне */
.rv-hx__btn-ic{display:none}

/* — ноутбук с невысоким экраном — */
@media(min-width:1025px) and (max-height:760px){
  .rv-hx__h1{font-size:clamp(30px,3vw,48px)!important}
  .rv-hx__btn{min-height:52px}
}
/* — телефон — */
@media(max-width:700px){
  .hero.rv-hx{min-height:calc(calc(var(--rv-svh,1svh)*100) - 72px);padding:18px 20px calc(82px + env(safe-area-inset-bottom,0px))!important}
  .hero.rv-hx .rv-hx__shade{background:linear-gradient(180deg,rgba(3,0,50,.62) 0%,rgba(3,0,50,.48) 45%,rgba(3,0,50,.94) 100%)}
  .hero.rv-hx .rv-hx__main{padding:36px 0 30px}
  /* ровно две строки: самая длинная строка («THAT ATTRACT CUSTOMERS») ≈ 13.1 кегля */
  .rv-hx__h1{font-size:max(20px,min(calc((100vw - 40px) / 13.4),30px))!important;line-height:1.1!important}
  .rv-hx__h1 em{white-space:nowrap}
  /* кнопка: компактная «пилюля» с тёмной иконкой-стрелкой, дышащим ореолом и бликом */
  .rv-hx__ctas{display:flex;width:auto;gap:10px;margin-top:22px}
  .rv-hx__btn{width:auto;min-height:48px;gap:14px;padding:0 5px 0 22px;font-size:.72rem;letter-spacing:.11em}
  .rv-hx__btn--gold{box-shadow:0 10px 26px rgba(245,197,66,.28);animation:rvHxHalo 3.2s ease-out 2.6s infinite;-webkit-tap-highlight-color:transparent;transition:transform .25s var(--ease),box-shadow .3s var(--ease)}
  .rv-hx__btn--gold:active{transform:scale(.96)}
  .rv-hx__btn-ic{display:grid;place-items:center;width:38px;height:38px;flex:none;border-radius:50%;background:var(--ink);box-shadow:inset 0 0 0 1px rgba(255,255,255,.08)}
  .rv-hx__btn-ic svg{width:15px;height:15px;fill:none;stroke:var(--gold);stroke-width:1.8;stroke-linecap:round;stroke-linejoin:round;transition:transform .35s var(--ease)}
  .rv-hx__btn--gold:active .rv-hx__btn-ic svg{transform:translate(2px,-2px)}
  .rv-hx__brands span{font-size:.6rem;letter-spacing:.1em;line-height:1.45}
  .hero.rv-hx .hero__title{font-size:calc((100vw - 40px) / 7.62)!important}
}
@media(prefers-reduced-motion:reduce){
  .rv-hx [data-hx]{opacity:1;transform:none;animation:none}
  .rv-hx__h1 em{animation:none;background-position:50% 0}
  html .hero.rv-hx[data-hero-motion] .hero__title-mask .hero__title-word .hero__letter{animation:none!important}
  .hero.rv-hx .rv-hx__spark{display:none}
  .rv-hx__btn--gold{animation:none!important}
  .rv-hx__btn--gold::after{animation:none;display:none}
}
</style>
<script id="rv-hx-beam">
/* Геометрия для блика: ширина надписи и смещение каждой буквы.
   offsetLeft не учитывает transform — входная анимация букв замер не сбивает. */
(function(){
  'use strict';
  var sign  = document.querySelector('.hero.rv-hx .rv-hx__sign');
  var title = sign && sign.querySelector('.hero__title');
  if (!title) return;
  var letters = title.querySelectorAll('.hero__letter');
  function absLeft(el){ var x = 0; while (el){ x += el.offsetLeft; el = el.offsetParent; } return x; }
  function measure(){
    var w = title.offsetWidth;
    if (!w) return;
    var t0 = absLeft(title);
    title.style.setProperty('--tw', w + 'px');
    for (var i = 0; i < letters.length; i++){
      letters[i].style.setProperty('--x', (absLeft(letters[i]) - t0) + 'px');
    }
    var last = letters[letters.length - 1];
    if (last){
      var right = absLeft(last) - absLeft(sign) + last.offsetWidth;
      sign.style.setProperty('--sr', Math.max(0, sign.offsetWidth - right) + 'px');
    }
    sign.style.setProperty('--th', title.offsetHeight + 'px');
  }
  measure();
  if (window.ResizeObserver) new ResizeObserver(measure).observe(title);
  else window.addEventListener('resize', measure);
  if (document.fonts && document.fonts.ready) document.fonts.ready.then(measure);
  window.addEventListener('load', measure);
  document.addEventListener('rv:loader-done', measure);
})();
</script>
</section>

<style id="rv-hx-vfit-css">
/* Телефон / планшет: видео hero без object-fit:cover и без transform.
   Горизонтальное видео 16:9 на вертикальном экране cover растягивает в ~3 раза;
   пока Safari / Telegram прячет или показывает панель, iOS пересчитывает cover
   от промежуточного размера и видео на секунду «раздувается» в 2–3 раза.
   Здесь видео получает точные размеры в px (тот же кадр и масштаб, что был),
   поэтому пересчитывать браузеру нечего. ПК не затрагивается. */
@media (hover:none),(pointer:coarse){
  .hero[data-hero-motion="8.0"] .hero__bg{transform:none!important;will-change:auto!important;animation:none!important;overflow:hidden}
  html.rv-hero-js .hero[data-hero-motion="8.0"] .hero__video,
  .hero[data-hero-motion="8.0"] .hero__video{transform:none!important;transition:opacity .6s ease!important}
  .hero[data-hero-motion="8.0"].rv-vfit .hero__video{position:absolute!important;max-width:none!important;max-height:none!important;object-fit:fill!important;object-position:50% 50%!important}
}
</style>
<script id="rv-hx-vfit" data-no-optimize="1" data-no-defer="1" data-cfasync="false">
/* Телефон / планшет: hero и видео в нём получают размеры в px один раз.
   iOS 26 (Safari и браузер Telegram) при скрытии/показе панели меняет не только
   высоту экрана, но и env(safe-area-inset-bottom) — hero, а за ним видео,
   меняли размер. Теперь высота hero замеряется в спокойном состоянии и
   фиксируется; меняется только при смене ширины (поворот) — сигнал rv:vh-lock
   от скрипта фиксации высоты экрана в <head>. */
(function () {
  'use strict';
  if (!(window.matchMedia && window.matchMedia('(hover: none), (pointer: coarse)').matches)) return;
  var hero = document.querySelector('.hero[data-hero-motion="8.0"]');
  var bg = hero && hero.querySelector('.hero__bg');
  var v = bg && bg.querySelector('.hero__video');
  if (!hero || !bg || !v) return;
  var SCALE = 1.045;            /* прежний масштаб слоя фона — кадр не меняется */
  var heroW = 0, heroH = 0, fitKey = '';

  function fit() {
    var W = heroW || hero.clientWidth, H = heroH || hero.clientHeight;
    if (!W || !H) return;
    var ar = (v.videoWidth && v.videoHeight) ? v.videoWidth / v.videoHeight : 16 / 9;
    var key = W + 'x' + H + ':' + ar.toFixed(4);
    if (key === fitKey) return;
    fitKey = key;
    var w, h;
    if (W / H > ar) { w = W; h = W / ar; } else { h = H; w = H * ar; }
    w = Math.ceil(w * SCALE); h = Math.ceil(h * SCALE);
    bg.style.setProperty('height', H + 'px', 'important');
    bg.style.setProperty('bottom', 'auto', 'important');
    v.style.width = w + 'px';
    v.style.height = h + 'px';
    v.style.left = Math.round((W - w) / 2) + 'px';
    v.style.top = Math.round((H - h) * 0.45) + 'px';
    hero.classList.add('rv-vfit');
  }

  function lockHero() {
    /* снять прошлую фиксацию, замерить естественную высоту, зафиксировать */
    hero.style.removeProperty('height');
    hero.style.removeProperty('min-height');
    hero.style.removeProperty('max-height');
    var W = hero.clientWidth, H = hero.offsetHeight;
    if (!W || H < 200) return;
    heroW = W; heroH = H;
    hero.style.setProperty('height', H + 'px', 'important');
    hero.style.setProperty('min-height', H + 'px', 'important');
    hero.style.setProperty('max-height', H + 'px', 'important');
    fit();
  }

  lockHero();
  window.addEventListener('rv:vh-lock', lockHero, { rvRaw: true });
  v.addEventListener('loadedmetadata', fit);
  window.addEventListener('load', lockHero, { rvRaw: true });
  document.addEventListener('rv:loader-done', lockHero);
  if (document.fonts && document.fonts.ready) document.fonts.ready.then(lockHero);

  /* Диагностика: открыть страницу с ?rvdebug=1 — в углу живые размеры. */
  if (/[?&]rvdebug=1/.test(location.search)) {
    var box = document.createElement('div');
    box.style.cssText = 'position:fixed;left:6px;top:50%;z-index:2147483647;font:11px/1.35 monospace;color:#0f0;background:rgba(0,0,0,.78);padding:6px 8px;border-radius:6px;pointer-events:none;white-space:pre';
    document.body.appendChild(box);
    var probe = document.createElement('div');
    probe.style.cssText = 'position:fixed;bottom:0;left:0;width:1px;height:env(safe-area-inset-bottom,0px);visibility:hidden;pointer-events:none';
    document.body.appendChild(probe);
    var maxV = 0, minV = 1e9;
    (function tick() {
      var vr = v.getBoundingClientRect();
      var vw = Math.round(vr.width);
      maxV = Math.max(maxV, vw); minV = Math.min(minV, vw);
      var cs = getComputedStyle(document.documentElement);
      box.textContent =
        'innerH ' + window.innerHeight + '  vv ' + (window.visualViewport ? Math.round(window.visualViewport.height) : '-') +
        '\nsvh ' + cs.getPropertyValue('--rv-svh') + '  lvh ' + cs.getPropertyValue('--rv-lvh') +
        '\nhero ' + hero.offsetHeight + '  bg ' + bg.offsetHeight +
        '\nvideo ' + vw + 'x' + Math.round(vr.height) + '  min ' + minV + ' max ' + maxV +
        '\nsafeB ' + probe.offsetHeight + '  y ' + Math.round(window.pageYOffset);
      requestAnimationFrame(tick);
    })();
  }
})();
</script>

<script id="revive-loader-stage">
(function () {
  'use strict';

  var root  = document.documentElement;
  var scene = document.querySelector('.rv-l-scene');
  var hero  = document.querySelector('.hero[data-hero-motion="8.0"]');
  var title = hero ? hero.querySelector('.hero__title') : null;

  /* Любая нештатная ситуация — страница просто открывается без сцены. */
  function abort() {
    root.classList.remove('rv-l', 'rv-l-boot', 'rv-l-first', 'rv-l-return', 'rv-l-go');
    root.classList.add('rv-l-calm');
    if (scene && scene.parentNode) scene.parentNode.removeChild(scene);
  }

  function stamp() {
    return (window.performance && window.performance.now) ? window.performance.now() : +new Date();
  }
  function remember() {
    try { window.sessionStorage.setItem('rvLoaderSeen.v12', '1'); } catch (e) {}
  }

  if (!scene || !hero || !title) { abort(); return; }

  var returning = root.classList.contains('rv-l-return');
  if (root.classList.contains('rv-l-calm')) { remember(); abort(); return; }

  var video    = hero.querySelector('.hero__video');
  var ink      = scene.querySelector('.rv-l__ink');
  var lockup   = scene.querySelector('.rv-l__lockup');
  var railFill = scene.querySelector('.rv-l__rail i');
  var num      = scene.querySelector('.rv-l__num');

  var MORPH_MS = 940;                      /* полёт лейбла на своё место   */
  var HOLD_MS  = returning ? 0 : 600;      /* выдержка от появления лейбла */
  var MAX_MS   = returning ? 420 : 1400;   /* потолок ожидания на медленной сети */

  var clone = null, cloneDone = false, shownAt = 0;
  var committed = false, finished = false, raf = 0, rz = 0;
  var startedAt = stamp();

  /* ------------------------------------------------------------------
     ПРОГРЕСС. Настоящий: шрифты + первый декодированный кадр видео +
     минимальная выдержка сцены. Показанное значение догоняет цель
     с затуханием и никогда не идёт назад.
     ------------------------------------------------------------------ */
  var W   = { fonts: .30, media: .45, hold: .25 };
  var got = { fonts: 0, media: 0, hold: 0 };
  var shown = 0;

  function pad(v) { return v < 10 ? '00' + v : (v < 100 ? '0' + v : '' + v); }
  function targetPct() { return (got.fonts * W.fonts + got.media * W.media + got.hold * W.hold) * 100; }
  function ready() {
    return got.fonts === 1 && got.media === 1 && got.hold === 1 && (returning || cloneDone);
  }

  function frame() {
    if (shownAt) got.hold = HOLD_MS ? Math.min(1, (stamp() - shownAt) / HOLD_MS) : 1;

    var t = targetPct();
    if (shown < t) shown = Math.min(t, shown + Math.max((t - shown) * .12, .35));

    if (num) num.textContent = pad(Math.min(99, Math.floor(shown)));
    if (railFill) railFill.style.transform = 'scaleX(' + Math.max(shown / 100, .02).toFixed(4) + ')';

    if (!committed && (ready() || stamp() - startedAt > MAX_MS)) { commit(); return; }
    raf = window.requestAnimationFrame(frame);
  }

  /* ------------------------------------------------------------------
     ГОТОВНОСТЬ ШРИФТОВ. До неё лейбл не строим: иначе морф считался бы
     по метрикам подменного шрифта и посадка уехала бы на пиксели.
     ------------------------------------------------------------------ */
  function fontsIn() {
    if (got.fonts === 1) return;
    got.fonts = 1;
    buildLockup();
  }
  (function () {
    if (!document.fonts || !document.fonts.load) { fontsIn(); return; }
    try {
      /* Ждём ровно тот начерк, которым набран заголовок, а не весь набор
         семейств страницы: document.fonts.ready на этой теме резолвится
         на полсекунды позже, чем нужно сцене. */
      var cs = window.getComputedStyle(title);
      var spec = cs.fontStyle + ' ' + cs.fontWeight + ' ' + cs.fontSize + ' ' + cs.fontFamily;
      document.fonts.load(spec, 'REVIVE AGENCY').then(fontsIn, fontsIn);
    } catch (e) {
      fontsIn();
    }
    window.setTimeout(fontsIn, 650);
  })();

  /* ------------------------------------------------------------------
     ГОТОВНОСТЬ ВИДЕО. Ждём именно декодированный кадр, а не событие
     загрузки: видео должно проявиться под уходящими чернилами.
     ------------------------------------------------------------------ */
  function mediaStep(v) {
    if (v > got.media) got.media = v;
    if (got.media === 1) root.classList.add('rv-l-media');
  }
  function firstFrame() {
    if (got.media === 1 || !video) return;
    if ('requestVideoFrameCallback' in video) {
      mediaStep(.8);
      try { video.requestVideoFrameCallback(function () { mediaStep(1); }); }
      catch (e) { mediaStep(1); }
    } else {
      mediaStep(1);
    }
  }
  if (!video) {
    mediaStep(1);
  } else {
    video.addEventListener('loadedmetadata', function () { mediaStep(.5); }, { once: true });
    video.addEventListener('loadeddata', firstFrame, { once: true });
    video.addEventListener('canplay', firstFrame, { once: true });
    video.addEventListener('error', function () { mediaStep(1); }, { once: true });
    if (video.readyState >= 2) firstFrame();
    try {
      var p = video.play();
      if (p && typeof p.catch === 'function') p.catch(function () {});
    } catch (e) {}
    window.setTimeout(function () { mediaStep(1); }, returning ? 350 : 1000);
  }

  /* ------------------------------------------------------------------
     ЛЕЙБЛ. Это клон настоящего <h1> с теми же классами и снятыми
     computed-метриками — поэтому посадка получается пиксель-в-пиксель.
     ------------------------------------------------------------------ */
  var COPY = ['fontFamily','fontSize','fontWeight','fontStyle','letterSpacing','lineHeight',
              'color','textTransform','columnGap','rowGap','alignItems','whiteSpace'];

  function buildLockup() {
    if (returning || cloneDone || finished) { cloneDone = true; if (!shownAt) shownAt = stamp(); return; }
    try {
      clone = title.cloneNode(true);
      clone.removeAttribute('id');
      clone.setAttribute('aria-hidden', 'true');

      var cs = window.getComputedStyle(title);
      for (var i = 0; i < COPY.length; i++) {
        try { clone.style[COPY[i]] = cs[COPY[i]]; } catch (e) {}
      }
      clone.style.margin = '0';

      lockup.appendChild(clone);
      place();
      lockup.classList.add('is-ready');
    } catch (e) {
      clone = null;
      if (lockup) lockup.style.display = 'none';
    }
    cloneDone = true;
    shownAt = stamp();
  }

  function visualBox(el) {
    var ms = el.querySelectorAll('.hero__title-mask'), b = null, i, r;
    for (i = 0; i < ms.length; i++) {
      r = ms[i].getBoundingClientRect();
      if (!r.width && !r.height) continue;
      if (!b) b = { left: r.left, top: r.top, right: r.right, bottom: r.bottom };
      else {
        b.left   = Math.min(b.left, r.left);
        b.top    = Math.min(b.top, r.top);
        b.right  = Math.max(b.right, r.right);
        b.bottom = Math.max(b.bottom, r.bottom);
      }
    }
    if (!b) { r = el.getBoundingClientRect(); return { left: r.left, top: r.top, width: r.width, height: r.height }; }
    b.width = b.right - b.left;
    b.height = b.bottom - b.top;
    return b;
  }

  function place() {
    if (!clone) return;
    lockup.style.transition = 'none';
    lockup.style.transform  = 'none';

    var cr = title.getBoundingClientRect();
    lockup.style.left   = cr.left + 'px';
    lockup.style.top    = cr.top + 'px';
    lockup.style.width  = cr.width + 'px';
    lockup.style.height = cr.height + 'px';

    var vb = visualBox(clone);
    var vw = window.innerWidth  || root.clientWidth;
    var vh = (window.RVVH ? window.RVVH() : window.innerHeight) || root.clientHeight;
    if (!vb.width || !vb.height) return;

    var k  = Math.min(1, Math.min(vw * .72, 520) / vb.width, (vh * .24) / vb.height);
    var gx = (vw - vb.width  * k) / 2;
    var gy = vh * .455 - (vb.height * k) / 2;
    var dx = gx - (cr.left + (vb.left - cr.left) * k);
    var dy = gy - (cr.top  + (vb.top  - cr.top)  * k);

    lockup.style.transform =
      'translate3d(' + dx.toFixed(2) + 'px,' + dy.toFixed(2) + 'px,0) scale(' + k.toFixed(4) + ')';
  }

  window.addEventListener('resize', function () {
    if (committed || !clone) return;
    if (rz) window.cancelAnimationFrame(rz);
    rz = window.requestAnimationFrame(place);
  });

  /* ------------------------------------------------------------------
     СТАРТ СЦЕНЫ: счётчик добивается до 100, линия становится кромкой
     уходящих чернил, лейбл летит на своё место в hero.
     ------------------------------------------------------------------ */
  function commit() {
    if (committed) return;
    committed = true;
    if (raf) window.cancelAnimationFrame(raf);
    remember();

    /* Даже если кадр видео так и не подтвердился — под уходящими чернилами
       должно быть видео, а не постер. */
    root.classList.add('rv-l-media');
    hero.setAttribute('data-hero-state', 'playing');

    if (num) num.textContent = '100';
    if (railFill) {
      railFill.style.transition = 'transform .3s cubic-bezier(.16,1,.3,1)';
      railFill.style.transform  = 'scaleX(1)';
    }

    /* Заголовок мог уехать за экран восстановлением скролла — тогда без морфа. */
    var morph = !!clone;
    if (morph) {
      var cr = title.getBoundingClientRect();
      var vh = (window.RVVH ? window.RVVH() : window.innerHeight) || root.clientHeight;
      if (cr.width < 40 || cr.bottom < 40 || cr.top > vh - 20) morph = false;
    }

    root.classList.remove('rv-l-boot');
    root.classList.add('rv-l-go');

    if (morph) {
      place();
      void lockup.offsetWidth;
      window.requestAnimationFrame(function () {
        lockup.style.transition = 'transform ' + MORPH_MS + 'ms cubic-bezier(.16,1,.3,1)';
        lockup.style.transform  = 'translate3d(0,0,0) scale(1)';
      });
    } else if (clone) {
      lockup.style.transition = 'opacity .22s ease-out';
      lockup.style.opacity    = '0';
    }

    try {
      document.dispatchEvent(new CustomEvent('rv:hero-start', { detail: { returning: returning } }));
    } catch (e) {}

    window.setTimeout(finish, morph ? MORPH_MS + 90 : (returning ? 540 : 420));
  }

  /* Подмена клона настоящим заголовком — в одном кадре, поэтому не видна. */
  function finish() {
    if (finished) return;
    finished = true;
    root.classList.add('rv-l-swap');
    hero.setAttribute('data-hero-state', 'complete');
    if (lockup) { lockup.style.transition = 'none'; lockup.style.opacity = '0'; }
    window.requestAnimationFrame(function () {
      if (scene && scene.parentNode) scene.parentNode.removeChild(scene);
      root.classList.add('rv-l-done');
      try { document.dispatchEvent(new CustomEvent('rv:loader-done')); } catch (e) {}
    });
  }

  /* Страховки: сцена не может зависнуть ни при каком раскладе. */
  window.setTimeout(function () { if (!committed) commit(); }, MAX_MS + 400);
  window.setTimeout(finish, MAX_MS + MORPH_MS + 900);

  if (returning) { shownAt = startedAt; got.hold = 1; }
  raf = window.requestAnimationFrame(frame);
})();
</script>


<!--<section class="intro">
  
  <video class="intro__bg"
         src="<?php echo get_field('video'); ?>"
         autoplay muted loop playsinline aria-hidden="true"></video>

  <div class="intro__text">
    <div class="line">we listen</div>
    <div class="line">we imagine</div>
    <div class="line"><span class="accent">we create</span></div>
    <div class="line">beautiful things</div>
  </div>
</section>-->

<!-- ===== SERVICES | прайс-индекс + витрина | v8.0 ===== -->
<?php
$rv_svx_en = ! function_exists( 'pll_current_language' ) || 'en' === pll_current_language();

/* Иконки услуг — один набор линейных SVG, цвет задаётся через currentColor. */
$rv_svx_icons = array(
	'code'   => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><polyline points="16 18 22 12 16 6"/><polyline points="8 6 2 12 8 18"/><line x1="13.5" y1="4" x2="10.5" y2="20"/></svg>',
	'store'  => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><path d="M3 9l1.7-5h14.6L21 9"/><path d="M4 10v10h16V10"/><path d="M9 20v-6h6v6"/><path d="M3 9c0 1.5 1 2.5 2.4 2.5S8 10.5 8 9c0 1.5 1 2.5 2.5 2.5S13 10.5 13 9c0 1.5 1 2.5 2.5 2.5S18 10.5 18 9c0 1.5 1 2.5 1.5 2.5"/></svg>',
	'mobile' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><rect x="6.5" y="2" width="11" height="20" rx="2.4"/><line x1="10" y1="5" x2="14" y2="5"/><circle cx="12" cy="18.5" r=".8" fill="currentColor"/></svg>',
	'bot'    => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><path d="M7 6.5h10a3 3 0 0 1 3 3v6a3 3 0 0 1-3 3h-5l-4 3v-3H7a3 3 0 0 1-3-3v-6a3 3 0 0 1 3-3z"/><path d="M12 3v3.5"/><circle cx="12" cy="2.6" r=".9"/><circle cx="9" cy="12.3" r="1" fill="currentColor"/><circle cx="15" cy="12.3" r="1" fill="currentColor"/></svg>',
	'seo'    => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><circle cx="10.5" cy="10.5" r="6.5"/><line x1="21" y1="21" x2="15.3" y2="15.3"/><polyline points="7 12 9.2 9.8 11 11.4 14 8.2"/></svg>',
	'target' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="9"/><circle cx="12" cy="12" r="5"/><circle cx="12" cy="12" r="1.2" fill="currentColor"/><line x1="12" y1="1.5" x2="12" y2="4"/><line x1="12" y1="20" x2="12" y2="22.5"/><line x1="1.5" y1="12" x2="4" y2="12"/><line x1="20" y1="12" x2="22.5" y2="12"/></svg>',
	'mega'   => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><path d="M3 11v3l4 1 2 5h2l-1.4-4.6L18 18V4L7 8H3z"/><path d="M21 9.5a3 3 0 0 1 0 5"/></svg>',
	'pen'    => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><path d="M12 19l7-7 3 3-7 7-3-3z"/><path d="M18 13l-1.5-7.5L2 2l3.5 14.5L13 18l5-5z"/><path d="M2 2l7.586 7.586"/><circle cx="11" cy="11" r="2"/></svg>',
);

/* Общие данные: цена, иконка, цвет свечения, направление (Build/Grow/Scale). */
$rv_svx_meta = array(
	'webdev'    => array( 'price' => 1500, 'icon' => 'code',   'glow' => '#F5C542', 'glow2' => '#7E5C0E', 'group' => 'build' ),
	'ecommerce' => array( 'price' => 2000, 'icon' => 'store',  'glow' => '#52E0B9', 'glow2' => '#0B625D', 'group' => 'build' ),
	'mobileapp' => array( 'price' => 2500, 'icon' => 'mobile', 'glow' => '#A99AFF', 'glow2' => '#4534A2', 'group' => 'build' ),
	'uiux'      => array( 'price' => 500,  'icon' => 'pen',    'glow' => '#DFE6F2', 'glow2' => '#4A5578', 'group' => 'build' ),
	'seo'       => array( 'price' => 500,  'icon' => 'seo',    'glow' => '#7AA8E8', 'glow2' => '#16345F', 'group' => 'grow'  ),
	'gads'      => array( 'price' => 400,  'icon' => 'target', 'glow' => '#EFCE75', 'glow2' => '#8A5F0D', 'group' => 'grow'  ),
	'smm'       => array( 'price' => 500,  'icon' => 'mega',   'glow' => '#B9A4F0', 'glow2' => '#3A2A6E', 'group' => 'grow'  ),
	'chatbots'  => array( 'price' => 500,  'icon' => 'bot',    'glow' => '#65DEFF', 'glow2' => '#176BA8', 'group' => 'scale' ),
);

/* Посилання з рядка послуги на її сторінку (UA / EN). EN-адресу беремо з перекладу
   UA-сторінки в Polylang; якщо переклад не пов'язаний — запасний EN-слаг нижче. */
$rv_svx_urls = array();
if ( $rv_svx_en ) {
	$rv_shop_uk = get_page_by_path( 'rozrobka-internet-magazinu-pid-klyuch', OBJECT, 'page' );
	$rv_shop_en = ( $rv_shop_uk && function_exists( 'pll_get_post' ) ) ? (int) pll_get_post( $rv_shop_uk->ID, 'en' ) : 0;
	$rv_svx_urls['ecommerce'] = $rv_shop_en ? get_permalink( $rv_shop_en ) : home_url( '/turnkey-online-store-development/' );
	$rv_svx_urls['webdev']    = home_url( '/web-development/' );
	$rv_svx_urls['mobileapp'] = home_url( '/mobile-app-development/' );
	$rv_svx_urls['uiux']     = home_url( '/ui-ux-design/' );
	$rv_svx_urls['seo']      = home_url( '/seo-services/' );
	$rv_svx_urls['gads']     = home_url( '/google-ads-management/' );
	$rv_svx_urls['smm']      = home_url( '/social-media-advertising/' );
	$rv_svx_urls['chatbots'] = home_url( '/chatbot-development/' );
} else {
	$rv_svx_urls['ecommerce'] = home_url( '/uk/rozrobka-internet-magazinu-pid-klyuch/' );
	$rv_svx_urls['webdev']    = home_url( '/uk/rozrobka-sajtiv/' );
	$rv_svx_urls['mobileapp'] = home_url( '/uk/rozrobka-mobilnih-zastosunkiv/' );
	$rv_svx_urls['uiux']     = home_url( '/uk/ui-ux-dizajn/' );
	$rv_svx_urls['seo']      = home_url( '/uk/seo-prosuvannya/' );
	$rv_svx_urls['gads']     = home_url( '/uk/reklama-google-ads/' );
	$rv_svx_urls['smm']      = home_url( '/uk/targetovana-reklama/' );
	$rv_svx_urls['chatbots'] = home_url( '/uk/rozrobka-chat-botiv/' );
}

$rv_svx_text = $rv_svx_en ? array(
	'webdev'    => array( 'Website Development', 'Fast, scalable websites — from landing pages to complex web platforms, built to convert and grow.', array( 'Landing pages', 'Corporate sites', 'Web platforms' ) ),
	'ecommerce' => array( 'Online Stores', 'High-converting online stores — from product catalogs to full-scale e-commerce platforms, built to sell and scale.', array( 'Catalog & cart', 'Payments & delivery', 'CRM integrations' ) ),
	'mobileapp' => array( 'Mobile Apps', 'Fast, intuitive mobile apps for iOS and Android — designed around your business, users, and growth.', array( 'iOS', 'Android', 'UX prototype' ) ),
	'uiux'      => array( 'UI/UX Design', 'Interfaces users love — research, wireframes and pixel-perfect design systems that feel effortless.', array( 'Research', 'Prototypes', 'Design system' ) ),
	'seo'       => array( 'SEO', 'Technical audits, content and link strategy that push you to the top of organic search — and keep you there.', array( 'Technical audit', 'Content', 'Link building' ) ),
	'gads'      => array( 'Google Ads', 'Search, display and performance campaigns tuned for maximum ROI — every click accounted for.', array( 'Search', 'Performance Max', 'Analytics' ) ),
	'smm'       => array( 'Social Media Ads', 'Scroll-stopping creatives and precise targeting across Meta, TikTok and beyond — ads people actually engage with.', array( 'Meta', 'TikTok', 'Creatives' ) ),
	'chatbots'  => array( 'Chatbots & Telegram Bots', 'Smart AI chatbots and Telegram bots — automate sales, support, communication, and everyday business tasks.', array( 'AI assistants', 'Telegram bots', 'Automation' ) ),
) : array(
	'webdev'    => array( 'Розробка сайтів', 'Швидкі та масштабовані сайти — від лендингів до складних вебплатформ, створених для заявок і зростання.', array( 'Лендінги', 'Корпоративні сайти', 'Вебплатформи' ) ),
	'ecommerce' => array( 'Інтернет-магазини', 'Інтернет-магазини, що продають — від каталогів товарів до повноцінних e-commerce платформ, готових до масштабування.', array( 'Каталог і кошик', 'Оплата й доставка', 'Інтеграція з CRM' ) ),
	'mobileapp' => array( 'Мобільні застосунки', 'Швидкі та зручні застосунки для iOS та Android — створені під ваш бізнес, користувачів і майбутнє зростання.', array( 'iOS', 'Android', 'UX-прототип' ) ),
	'uiux'      => array( 'UI/UX дизайн', 'Інтерфейси, які подобаються користувачам: дослідження, прототипи та дизайн-системи до пікселя.', array( 'Дослідження', 'Прототипи', 'Дизайн-система' ) ),
	'seo'       => array( 'SEO-просування', 'Технічний аудит, контент і стратегія посилань, які виводять вас у топ органічної видачі — і утримують там.', array( 'Технічний аудит', 'Контент', 'Посилання' ) ),
	'gads'      => array( 'Реклама Google Ads', 'Пошукові, медійні та performance-кампанії, налаштовані на максимальну окупність — кожен клік під контролем.', array( 'Пошук', 'Performance Max', 'Аналітика' ) ),
	'smm'       => array( 'Таргетована реклама', 'Креативи, які зупиняють скрол, і точний таргетинг у Meta, TikTok та інших — реклама, з якою справді взаємодіють.', array( 'Meta', 'TikTok', 'Креативи' ) ),
	'chatbots'  => array( 'Чат-боти та Telegram-боти', 'Розумні AI чат-боти та Telegram-боти — автоматизуємо продажі, підтримку, комунікацію та щоденні бізнес-процеси.', array( 'AI-асистенти', 'Telegram-боти', 'Автоматизація' ) ),
);

$rv_svx_ui = $rv_svx_en ? array(
	'chip'   => 'Services & pricing',
	'title'  => 'Services priced <em>upfront</em>',
	'intro'  => 'Eight services — from websites to ads and automation. Starting prices come from real projects; the exact figure is fixed in the contract.',
	'calc'   => 'Estimate my budget',
	'talk'   => 'Discuss a project',
	'from'   => 'from',
	'order'  => 'Discuss this',
	'more'   => 'Learn more',
	'list'   => 'Service list',
	'groups' => array( 'build' => 'Build', 'grow' => 'Grow', 'scale' => 'Scale' ),
) : array(
	'chip'   => 'Послуги та ціни',
	'title'  => 'Послуги з ціною <em>до старту</em>',
	'intro'  => 'Вісім напрямів — від сайту до реклами й автоматизації. Ціни «від» — з реальних проєктів, точну суму фіксуємо в договорі.',
	'calc'   => 'Розрахувати бюджет',
	'talk'   => 'Обговорити проєкт',
	'from'   => 'від',
	'order'  => 'Обговорити',
	'more'   => 'Детальніше',
	'list'   => 'Перелік послуг',
	'groups' => array( 'build' => 'Build', 'grow' => 'Grow', 'scale' => 'Scale' ),
);

$rv_svx = array();
foreach ( $rv_svx_meta as $rv_k => $rv_m ) {
	$rv_svx[] = array(
		'key'   => $rv_k,
		'name'  => $rv_svx_text[ $rv_k ][0],
		'desc'  => $rv_svx_text[ $rv_k ][1],
		'tags'  => $rv_svx_text[ $rv_k ][2],
		'price' => '$' . number_format( $rv_m['price'], 0, '.', ' ' ),
		'icon'  => $rv_m['icon'],
		'glow'  => $rv_m['glow'],
		'glow2' => $rv_m['glow2'],
		'group' => $rv_m['group'],
		'url'   => isset( $rv_svx_urls[ $rv_k ] ) ? $rv_svx_urls[ $rv_k ] : '',
	);
}
$rv_svx_total = str_pad( count( $rv_svx ), 2, '0', STR_PAD_LEFT );
$rv_svx_first = $rv_svx[0];
?>
<section class="rv-svx" id="services" aria-labelledby="rv-svx-title" data-rv-svx>
<span class="rv-section-anchor" id="rv-services" aria-hidden="true"></span>
<div class="rv-svx__bg" aria-hidden="true"></div>

<div class="rv-svx__wrap">
	<header class="rv-svx__head">
		<div>
			<p class="rv-svx__chip"><i></i><?php echo esc_html( $rv_svx_ui['chip'] ); ?><b><?php echo esc_html( $rv_svx_total ); ?></b></p>
			<h2 class="rv-svx__title" id="rv-svx-title"><?php echo wp_kses( $rv_svx_ui['title'], array( 'em' => array() ) ); ?></h2>
		</div>
		<div class="rv-svx__intro">
			<p><?php echo esc_html( $rv_svx_ui['intro'] ); ?></p>
			<div class="rv-svx__ctas">
				<a class="rv-svx__btn rv-svx__btn--gold" href="#rv-quiz"><?php echo esc_html( $rv_svx_ui['calc'] ); ?> <span aria-hidden="true">↗</span></a>
				<a class="rv-svx__btn rv-svx__btn--ghost" href="#win1"><?php echo esc_html( $rv_svx_ui['talk'] ); ?></a>
			</div>
		</div>
	</header>

	<div class="rv-svx__body">
		<ol class="rv-svx__list" aria-label="<?php echo esc_attr( $rv_svx_ui['list'] ); ?>">
			<?php foreach ( $rv_svx as $rv_i => $rv_s ) :
				$rv_idx = str_pad( $rv_i + 1, 2, '0', STR_PAD_LEFT );
				$rv_on  = 0 === $rv_i;
			?>
			<li class="rv-svx__item<?php echo $rv_on ? ' is-active' : ''; ?><?php echo $rv_s['url'] ? ' has-url' : ''; ?>" style="--g:<?php echo esc_attr( $rv_s['glow'] ); ?>;--g2:<?php echo esc_attr( $rv_s['glow2'] ); ?>" data-i="<?php echo (int) $rv_i; ?>">
				<button type="button" class="rv-svx__row" aria-expanded="false" aria-controls="rv-svx-d-<?php echo esc_attr( $rv_s['key'] ); ?>">
					<span class="rv-svx__idx"><?php echo esc_html( $rv_idx ); ?></span>
					<span class="rv-svx__name"><?php echo esc_html( $rv_s['name'] ); ?></span>
					<span class="rv-svx__grp rv-svx__grp--<?php echo esc_attr( $rv_s['group'] ); ?>"><?php echo esc_html( $rv_svx_ui['groups'][ $rv_s['group'] ] ); ?></span>
					<span class="rv-svx__price"><small><?php echo esc_html( $rv_svx_ui['from'] ); ?></small> <?php echo esc_html( $rv_s['price'] ); ?></span>
					<span class="rv-svx__arrow" aria-hidden="true"><svg viewBox="0 0 24 24"><path d="M7 17L17 7M9 7h8v8"/></svg></span>
					<span class="rv-svx__prog" aria-hidden="true"></span>
				</button>
				<div class="rv-svx__detail" id="rv-svx-d-<?php echo esc_attr( $rv_s['key'] ); ?>">
					<div class="rv-svx__detail-in">
						<div class="rv-svx__mvis" aria-hidden="true">
							<span class="rv-svx__mring"></span>
							<span class="rv-svx__morbit"><i></i><i></i><i></i></span>
							<span class="rv-svx__morb"><?php echo $rv_svx_icons[ $rv_s['icon'] ]; // SVG из массива выше ?></span>
						</div>
						<p><?php echo esc_html( $rv_s['desc'] ); ?></p>
						<ul class="rv-svx__tags"><?php foreach ( $rv_s['tags'] as $rv_t ) : ?><li><?php echo esc_html( $rv_t ); ?></li><?php endforeach; ?></ul>
						<a class="rv-svx__mini" href="<?php echo $rv_s['url'] ? esc_url( $rv_s['url'] ) : '#win1'; ?>"><?php echo esc_html( $rv_s['url'] ? $rv_svx_ui['more'] : $rv_svx_ui['order'] ); ?> <span aria-hidden="true">↗</span></a>
					</div>
				</div>
			</li>
			<?php endforeach; ?>
		</ol>

		<aside class="rv-svx__stage" aria-hidden="true" style="--g:<?php echo esc_attr( $rv_svx_first['glow'] ); ?>;--g2:<?php echo esc_attr( $rv_svx_first['glow2'] ); ?>">
			<div class="rv-svx__card">
				<div class="rv-svx__bar"><i></i><i></i><i></i><span data-svx="url">revive-agency.com/services/<?php echo esc_html( $rv_svx_first['key'] ); ?></span></div>
				<div class="rv-svx__visual">
					<span class="rv-svx__ring"></span>
					<span class="rv-svx__orbit"><i></i><i></i><i></i></span>
					<span class="rv-svx__orb" data-svx="icon"><?php echo $rv_svx_icons[ $rv_svx_first['icon'] ]; // SVG из массива выше ?></span>
				</div>
				<div class="rv-svx__meta">
					<span data-svx="grp" class="rv-svx__grp rv-svx__grp--<?php echo esc_attr( $rv_svx_first['group'] ); ?>"><?php echo esc_html( $rv_svx_ui['groups'][ $rv_svx_first['group'] ] ); ?></span>
					<span class="rv-svx__count"><b data-svx="idx">01</b> / <?php echo esc_html( $rv_svx_total ); ?></span>
				</div>
				<h3 class="rv-svx__sname" data-svx="name"><?php echo esc_html( $rv_svx_first['name'] ); ?></h3>
				<p class="rv-svx__sdesc" data-svx="desc"><?php echo esc_html( $rv_svx_first['desc'] ); ?></p>
				<ul class="rv-svx__tags" data-svx="tags"><?php foreach ( $rv_svx_first['tags'] as $rv_t ) : ?><li><?php echo esc_html( $rv_t ); ?></li><?php endforeach; ?></ul>
				<div class="rv-svx__foot">
					<p class="rv-svx__big"><small><?php echo esc_html( $rv_svx_ui['from'] ); ?></small><span data-svx="price"><?php echo esc_html( $rv_svx_first['price'] ); ?></span></p>
					<a class="rv-svx__btn rv-svx__btn--gold" href="<?php echo $rv_svx_first['url'] ? esc_url( $rv_svx_first['url'] ) : '#win1'; ?>" tabindex="-1" data-svx-cta><span data-svx-cta-t><?php echo esc_html( $rv_svx_first['url'] ? $rv_svx_ui['more'] : $rv_svx_ui['order'] ); ?></span> <span>↗</span></a>
				</div>
			</div>
		</aside>
	</div>
</div>

<style>
.rv-svx{--gold:#F5C542;--ink:#030032;--line:rgba(255,255,255,.1);--ease:cubic-bezier(.22,1,.36,1);
  position:relative;isolation:isolate;overflow:hidden;padding:clamp(52px,calc(var(--rv-lvh,1vh)*7.5),84px) 0!important;
  background:#030032;color:#fff;font-family:'Inter',Arial,sans-serif}
.rv-svx *,.rv-svx *::before,.rv-svx *::after{box-sizing:border-box}
.rv-svx p,.rv-svx ul,.rv-svx ol,.rv-svx h2,.rv-svx h3{margin:0;padding:0}
.rv-svx ul,.rv-svx ol{list-style:none}
.rv-svx i,.rv-svx em{font-style:normal}
.rv-svx button{font:inherit;color:inherit;background:none;border:0;border-radius:0;padding:0;margin:0;-webkit-appearance:none;appearance:none;cursor:pointer;text-align:left}
.rv-svx a{text-decoration:none}
.rv-svx__bg{position:absolute;inset:0;z-index:-1;pointer-events:none;
  background:
   radial-gradient(40% 50% at 78% 55%,color-mix(in srgb,var(--svx-g,#F5C542) 16%,transparent) 0%,transparent 70%),
   radial-gradient(45% 45% at 8% 10%,rgba(100,108,255,.14) 0%,transparent 70%),
   linear-gradient(rgba(255,255,255,.03) 1px,transparent 1px) 0 0/64px 64px,
   linear-gradient(90deg,rgba(255,255,255,.03) 1px,transparent 1px) 0 0/64px 64px;
  -webkit-mask-image:linear-gradient(180deg,transparent 0%,#000 14%,#000 86%,transparent 100%);
          mask-image:linear-gradient(180deg,transparent 0%,#000 14%,#000 86%,transparent 100%);
  transition:background .8s var(--ease)}
.rv-svx__wrap{width:100%;max-width:1320px;margin:0 auto;padding:0 clamp(20px,4vw,56px)}

/* — шапка блока — */
.rv-svx__head{display:grid;grid-template-columns:minmax(0,1fr) minmax(0,1fr);gap:24px 64px;align-items:end;margin-bottom:clamp(24px,calc(var(--rv-lvh,1vh)*3.6),40px)}
.rv-svx__chip{display:inline-flex;align-items:center;gap:.7rem;padding:.45rem 1rem;border-radius:100px;border:1px solid rgba(255,255,255,.14);background:rgba(255,255,255,.05);
  font-family:'JetBrains Mono',monospace;font-size:.68rem;letter-spacing:.12em;text-transform:uppercase;color:#fff}
.rv-svx__chip i{width:7px;height:7px;border-radius:50%;background:var(--gold);box-shadow:0 0 12px var(--gold)}
.rv-svx__chip b{font-weight:500;color:var(--gold)}
.rv-svx__title{margin-top:16px!important;font-family:'Manrope',Arial,sans-serif;font-weight:800;font-size:clamp(34px,4vw,58px);line-height:1.05;letter-spacing:-.03em;text-transform:uppercase;color:#fff}
.rv-svx__title em{display:block;font-style:normal;color:transparent;-webkit-text-stroke:1.5px var(--gold);padding-right:2px}
.rv-svx__intro p{font-size:clamp(15px,1.1vw,17px);line-height:1.6;color:#fff;max-width:46ch}
.rv-svx__ctas{display:flex;flex-wrap:wrap;gap:12px;margin-top:18px}
.rv-svx__btn{display:inline-flex;align-items:center;justify-content:center;gap:10px;min-height:50px;padding:0 24px;border-radius:999px;
  font-family:'Inter',Arial,sans-serif;font-weight:800;font-size:.84rem;letter-spacing:.06em;text-transform:uppercase;white-space:nowrap;
  transition:box-shadow .3s var(--ease),filter .3s ease,border-color .3s ease,background .3s ease,color .3s ease}
.rv-svx__btn--gold{color:var(--ink);background:linear-gradient(135deg,#F5D488 0%,#F5C542 48%,#DFAE3F 100%);border:1px solid rgba(245,197,66,.58);box-shadow:0 10px 30px rgba(245,197,66,.24)}
.rv-svx__btn--gold:hover,.rv-svx__btn--gold:focus-visible{box-shadow:0 0 0 1px rgba(245,197,66,.4),0 12px 34px rgba(245,197,66,.3),0 0 42px 4px rgba(245,197,66,.22);filter:brightness(1.04)}
.rv-svx__btn--gold span{display:inline-block;transition:transform .28s var(--ease)}
.rv-svx__btn--gold:hover span{transform:translate(2px,-2px)}
.rv-svx__btn--ghost{color:#fff;border:1px solid rgba(255,255,255,.22);background:rgba(255,255,255,.03)}
.rv-svx__btn--ghost:hover,.rv-svx__btn--ghost:focus-visible{border-color:rgba(245,197,66,.7);color:var(--gold)}

/* — список + витрина — */
.rv-svx__body{display:grid;grid-template-columns:minmax(0,1.4fr) minmax(0,.85fr);gap:clamp(28px,4vw,64px);align-items:start}
.rv-svx__list{border-top:1px solid var(--line);counter-reset:none}
.rv-svx__item{position:relative;border-bottom:1px solid var(--line)}
.rv-svx .rv-svx__row{position:relative;display:grid;grid-template-columns:48px minmax(0,1fr) auto auto 38px;align-items:center;gap:18px;width:100%;
  min-height:64px;padding:9px 14px 9px 24px;overflow:hidden;border-radius:18px;transition:background .45s var(--ease)}
.rv-svx__row::before{content:"";position:absolute;inset:0;z-index:-1;border-radius:inherit;
  background:linear-gradient(90deg,color-mix(in srgb,var(--g) 18%,transparent) 0%,rgba(255,255,255,.02) 60%,transparent 100%);
  transform:scaleX(0);transform-origin:left;transition:transform .6s var(--ease)}
.rv-svx__row::after{content:"";position:absolute;left:8px;top:16px;bottom:18px;width:3px;border-radius:3px;background:var(--g);box-shadow:0 0 14px var(--g);
  transform:scaleY(0);transition:transform .45s var(--ease)}
.rv-svx__item.is-active .rv-svx__row::before,.rv-svx__row:hover::before{transform:scaleX(1)}
.rv-svx__item.is-active .rv-svx__row::after{transform:scaleY(1)}
.rv-svx__idx{font-family:'JetBrains Mono',monospace;font-size:.78rem;letter-spacing:.08em;color:#fff;transition:color .3s ease}
.rv-svx__item.is-active .rv-svx__idx{color:var(--gold)}
.rv-svx__name{font-family:'Manrope',Arial,sans-serif;font-weight:700;font-size:clamp(17px,1.45vw,22px);line-height:1.15;letter-spacing:-.02em;color:#fff;
  transition:transform .5s var(--ease),color .3s ease}
.rv-svx__row:hover .rv-svx__name,.rv-svx__item.is-active .rv-svx__name{transform:translateX(10px)}
.rv-svx__grp{justify-self:end;display:inline-flex;align-items:center;gap:6px;padding:5px 10px;border-radius:99px;border:1px solid currentColor;
  font-family:'JetBrains Mono',monospace;font-size:.62rem;letter-spacing:.12em;text-transform:uppercase;line-height:1;white-space:nowrap}
.rv-svx__grp::before{content:"";width:5px;height:5px;border-radius:50%;background:currentColor}
.rv-svx__grp--build{color:#B9AEFF}.rv-svx__grp--grow{color:#F5C542}.rv-svx__grp--scale{color:#65DEFF}
.rv-svx__price{justify-self:end;font-family:'Manrope',Arial,sans-serif;font-weight:800;font-size:clamp(16px,1.3vw,20px);letter-spacing:-.01em;color:#fff;white-space:nowrap;
  font-variant-numeric:tabular-nums;transition:color .3s ease,text-shadow .3s ease}
.rv-svx__price small{font-family:'JetBrains Mono',monospace;font-weight:400;font-size:.62rem;letter-spacing:.1em;text-transform:uppercase;margin-right:4px}
.rv-svx__item.is-active .rv-svx__price{color:var(--gold);text-shadow:0 0 24px rgba(245,197,66,.35)}
.rv-svx__arrow{display:grid;place-items:center;width:38px;height:38px;border-radius:50%;border:1px solid rgba(255,255,255,.2);transition:all .4s var(--ease)}
.rv-svx__arrow svg{width:18px;height:18px;fill:none;stroke:#fff;stroke-width:1.8;stroke-linecap:round;stroke-linejoin:round;transition:transform .4s var(--ease),stroke .3s ease}
.rv-svx__row:hover .rv-svx__arrow,.rv-svx__item.is-active .rv-svx__arrow{background:var(--gold);border-color:var(--gold);box-shadow:0 0 22px rgba(245,197,66,.4)}
.rv-svx__row:hover .rv-svx__arrow svg,.rv-svx__item.is-active .rv-svx__arrow svg{stroke:var(--ink);transform:rotate(45deg)}
.rv-svx__prog{position:absolute;left:24px;right:14px;bottom:0;height:2px;background:var(--gold);transform:scaleX(0);transform-origin:left;opacity:0}
.rv-svx__item.is-active.is-auto .rv-svx__prog{opacity:.9;animation:rvSvxProg var(--svx-dur,5200ms) linear forwards}
@keyframes rvSvxProg{to{transform:scaleX(1)}}
.rv-svx__row:focus-visible{outline:2px solid rgba(245,197,66,.75);outline-offset:-2px}

/* описание внутри строки: на десктопе его показывает витрина */
.rv-svx__detail{display:grid;grid-template-rows:0fr;transition:grid-template-rows .5s var(--ease)}
.rv-svx__detail-in{overflow:hidden;min-height:0;padding:0 18px 0 88px}
.rv-svx__detail-in p{font-size:1rem;line-height:1.6;color:#fff;max-width:56ch}
.rv-svx__tags{display:flex;flex-wrap:wrap;gap:8px}
.rv-svx__detail .rv-svx__tags{margin-top:14px}
.rv-svx__tags li{padding:7px 12px;border-radius:99px;border:1px solid rgba(255,255,255,.16);background:rgba(255,255,255,.04);
  font-family:'JetBrains Mono',monospace;font-size:.66rem;letter-spacing:.08em;text-transform:uppercase;color:#fff}
.rv-svx__mini{display:inline-flex;gap:8px;margin:16px 0 22px;font-family:'JetBrains Mono',monospace;font-size:.74rem;letter-spacing:.1em;text-transform:uppercase;
  color:var(--gold);border-bottom:1px solid rgba(245,197,66,.5);padding-bottom:3px}
@media(min-width:1025px){.rv-svx__detail{display:none}}

/* витрина */
.rv-svx__stage{position:sticky;top:96px}
.rv-svx__card{position:relative;overflow:hidden;border-radius:28px;padding:0 0 22px;
  background:linear-gradient(160deg,rgba(255,255,255,.08) 0%,rgba(255,255,255,.025) 60%);
  border:1px solid rgba(255,255,255,.14);box-shadow:0 40px 90px rgba(0,0,0,.45),inset 0 1px 0 rgba(255,255,255,.08);
  -webkit-backdrop-filter:blur(14px);backdrop-filter:blur(14px)}
.rv-svx__card::before{content:"";position:absolute;inset:-40% -20% auto;height:80%;pointer-events:none;
  background:radial-gradient(50% 50% at 50% 50%,color-mix(in srgb,var(--g) 30%,transparent),transparent 70%);transition:background .7s var(--ease)}
.rv-svx__bar{position:relative;display:flex;align-items:center;gap:7px;padding:13px 20px;border-bottom:1px solid rgba(255,255,255,.1)}
.rv-svx__bar i{width:9px;height:9px;border-radius:50%;background:rgba(255,255,255,.25)}
.rv-svx__bar i:first-child{background:var(--gold)}
.rv-svx__bar span{margin-left:auto;font-family:'JetBrains Mono',monospace;font-size:.66rem;letter-spacing:.04em;color:#fff;opacity:.85}
.rv-svx__visual{position:relative;display:grid;place-items:center;height:176px}
.rv-svx__ring{position:absolute;width:150px;height:150px;border-radius:50%;
  background:conic-gradient(from 0deg,transparent 0 25%,var(--g) 45%,transparent 55% 100%);
  -webkit-mask:radial-gradient(farthest-side,transparent calc(100% - 2px),#000 calc(100% - 1px));
          mask:radial-gradient(farthest-side,transparent calc(100% - 2px),#000 calc(100% - 1px));
  animation:rvSvxSpin 6s linear infinite;filter:drop-shadow(0 0 8px var(--g))}
.rv-svx__orbit{position:absolute;width:122px;height:122px;border-radius:50%;border:1px dashed rgba(255,255,255,.18);animation:rvSvxSpin 18s linear infinite reverse}
.rv-svx__orbit i{position:absolute;width:8px;height:8px;border-radius:50%;background:#fff;box-shadow:0 0 10px #fff}
.rv-svx__orbit i:nth-child(1){top:-4px;left:calc(50% - 4px)}
.rv-svx__orbit i:nth-child(2){bottom:18px;right:4px;width:6px;height:6px;background:var(--g);box-shadow:0 0 10px var(--g)}
.rv-svx__orbit i:nth-child(3){bottom:22px;left:6px;width:5px;height:5px;opacity:.6}
.rv-svx__orb{position:relative;display:grid;place-items:center;width:88px;height:88px;border-radius:26px;color:#fff;
  background:linear-gradient(145deg,var(--g),var(--g2));box-shadow:0 24px 60px color-mix(in srgb,var(--g) 40%,transparent),inset 0 1px 0 rgba(255,255,255,.4),inset 0 -12px 24px rgba(0,0,0,.18);
  transition:background .6s var(--ease),box-shadow .6s var(--ease)}
.rv-svx__orb svg{width:42px;height:42px;filter:drop-shadow(0 2px 6px rgba(0,0,0,.25))}
.rv-svx__orb.is-swap{animation:rvSvxSwap .7s cubic-bezier(.34,1.56,.64,1)}
.rv-svx__meta,.rv-svx__sname,.rv-svx__sdesc,.rv-svx__card>.rv-svx__tags,.rv-svx__foot{position:relative;margin-left:24px!important;margin-right:24px!important}
.rv-svx__meta{display:flex;justify-content:space-between;align-items:center}
.rv-svx__count{font-family:'JetBrains Mono',monospace;font-size:.72rem;letter-spacing:.1em;color:#fff}
.rv-svx__count b{font-weight:500;color:var(--gold)}
.rv-svx__sname{margin-top:12px!important;font-family:'Manrope',Arial,sans-serif;font-weight:800;font-size:clamp(22px,1.9vw,30px);line-height:1.05;letter-spacing:-.03em;color:#fff}
.rv-svx__sdesc{margin-top:8px!important;font-size:.92rem;line-height:1.55;color:#fff;min-height:4.65em}
.rv-svx__card>.rv-svx__tags{margin-top:14px!important}
.rv-svx__foot{display:flex;align-items:center;justify-content:space-between;gap:16px;margin-top:18px!important;padding-top:16px;border-top:1px solid rgba(255,255,255,.1)}
.rv-svx__big{display:flex;align-items:baseline;gap:8px;font-family:'Manrope',Arial,sans-serif;font-weight:800;font-size:clamp(28px,2.5vw,40px);line-height:1;letter-spacing:-.03em;color:var(--gold);
  text-shadow:0 0 30px rgba(245,197,66,.3);font-variant-numeric:tabular-nums}
.rv-svx__big small{font-family:'JetBrains Mono',monospace;font-weight:400;font-size:.72rem;letter-spacing:.1em;text-transform:uppercase;color:#fff;text-shadow:none}
.rv-svx__foot .rv-svx__btn{min-height:46px;padding:0 20px}
.rv-svx [data-svx].is-out{opacity:0;transform:translateY(10px)}
.rv-svx [data-svx]{transition:opacity .3s ease,transform .45s var(--ease)}
@keyframes rvSvxSpin{to{transform:rotate(360deg)}}
@keyframes rvSvxSwap{0%{transform:scale(1) rotate(0)}40%{transform:scale(.78) rotate(-14deg)}100%{transform:scale(1) rotate(0)}}

/* — телефон: иконка услуги в раскрытой строке — как витрина на ПК — */
.rv-svx__mvis{display:none}
@media(max-width:1024px){
  .rv-svx .rv-svx__detail-in{padding:0}
  .rv-svx__mvis{position:relative;display:grid;place-items:center;height:176px;margin:6px 0 16px;border-radius:24px;overflow:hidden;isolation:isolate;
    background:linear-gradient(160deg,rgba(255,255,255,.08) 0%,rgba(255,255,255,.025) 60%);
    border:1px solid rgba(255,255,255,.14);box-shadow:inset 0 1px 0 rgba(255,255,255,.08)}
  .rv-svx__mvis::before{content:"";position:absolute;inset:-40% -20% auto;height:90%;z-index:-1;pointer-events:none;
    background:radial-gradient(50% 50% at 50% 50%,color-mix(in srgb,var(--g) 30%,transparent),transparent 70%)}
  .rv-svx__mring{position:absolute;width:150px;height:150px;border-radius:50%;
    background:conic-gradient(from 0deg,transparent 0 25%,var(--g) 45%,transparent 55% 100%);
    -webkit-mask:radial-gradient(farthest-side,transparent calc(100% - 2px),#000 calc(100% - 1px));
            mask:radial-gradient(farthest-side,transparent calc(100% - 2px),#000 calc(100% - 1px));
    filter:drop-shadow(0 0 8px var(--g));animation:rvSvxSpin 6s linear infinite}
  .rv-svx__morbit{position:absolute;width:122px;height:122px;border-radius:50%;border:1px dashed rgba(255,255,255,.18);animation:rvSvxSpin 18s linear infinite reverse}
  .rv-svx__morbit i{position:absolute;width:8px;height:8px;border-radius:50%;background:#fff;box-shadow:0 0 10px #fff}
  .rv-svx__morbit i:nth-child(1){top:-4px;left:calc(50% - 4px)}
  .rv-svx__morbit i:nth-child(2){bottom:18px;right:4px;width:6px;height:6px;background:var(--g);box-shadow:0 0 10px var(--g)}
  .rv-svx__morbit i:nth-child(3){bottom:22px;left:6px;width:5px;height:5px;opacity:.6}
  .rv-svx__morb{position:relative;display:grid;place-items:center;width:88px;height:88px;border-radius:26px;color:#fff;
    background:linear-gradient(145deg,var(--g),var(--g2));
    box-shadow:0 24px 60px color-mix(in srgb,var(--g) 40%,transparent),inset 0 1px 0 rgba(255,255,255,.4),inset 0 -12px 24px rgba(0,0,0,.18)}
  .rv-svx__morb svg{width:42px;height:42px;filter:drop-shadow(0 2px 6px rgba(0,0,0,.25))}
}

/* появление при скролле */
.rv-svx.is-ready .rv-svx__head>*,.rv-svx.is-ready .rv-svx__item,.rv-svx.is-ready .rv-svx__stage{opacity:0;transform:translateY(28px)}
.rv-svx.is-in .rv-svx__head>*,.rv-svx.is-in .rv-svx__item,.rv-svx.is-in .rv-svx__stage{opacity:1;transform:none;
  transition:opacity .8s var(--ease),transform .8s var(--ease);transition-delay:calc(var(--d,0) * 60ms)}

/* — планшет и телефон: аккордеон, витрина скрыта — */
@media(max-width:1024px){
  .rv-svx__head{grid-template-columns:1fr;gap:20px}
  .rv-svx__body{grid-template-columns:1fr}
  .rv-svx__stage{display:none}
  .rv-svx .rv-svx__row{grid-template-columns:34px minmax(0,1fr) auto 34px;gap:12px;min-height:58px;padding:8px 8px 8px 18px}
  .rv-svx__row::after{left:5px;top:14px;bottom:14px}
  .rv-svx__grp{display:none}
  .rv-svx__arrow{width:34px;height:34px}
  .rv-svx__arrow svg{transition:transform .4s var(--ease)}
  .rv-svx__item.is-open .rv-svx__arrow svg{transform:rotate(135deg)}
  .rv-svx__item.has-url .rv-svx__arrow svg,.rv-svx__item.has-url.is-open .rv-svx__arrow svg{transform:none}
  .rv-svx__row:hover .rv-svx__name,.rv-svx__item.is-active .rv-svx__name{transform:none}
  .rv-svx__item.is-open .rv-svx__detail{grid-template-rows:1fr}
  .rv-svx__detail-in{padding:0 10px 0 64px}
  .rv-svx__item .rv-svx__prog{display:none}
}
@media(max-width:767px){
  .rv-svx{padding:44px 0 40px!important}
  /* без цветной подсветки справа — остаются только сетка и синий свет слева сверху */
  .rv-svx__bg{background:
   radial-gradient(45% 45% at 8% 10%,rgba(100,108,255,.14) 0%,transparent 70%),
   linear-gradient(rgba(255,255,255,.03) 1px,transparent 1px) 0 0/64px 64px,
   linear-gradient(90deg,rgba(255,255,255,.03) 1px,transparent 1px) 0 0/64px 64px}
  .rv-svx__head{gap:12px;margin-bottom:18px}
  /* открытая услуга без подсветки: ни фона строки, ни цветного пятна в карточке, полоска слева без свечения */
  .rv-svx__row::before,.rv-svx__mvis::before{display:none}
  .rv-svx__row::after{box-shadow:none}
  .rv-svx__ctas{display:none}
  .rv-svx__intro p{font-size:.92rem;line-height:1.5}
  .rv-svx__mini{margin:12px 0 16px}
}
@media(max-width:600px){
  .rv-svx__title{font-size:clamp(28px,8.4vw,34px)}
  .rv-svx .rv-svx__row{grid-template-columns:28px minmax(0,1fr) auto;gap:10px}
  .rv-svx__arrow{display:none}
  /* рядок із власною сторінкою: стрілка лишається і веде на сторінку */
  .rv-svx .rv-svx__item.has-url .rv-svx__row{grid-template-columns:28px minmax(0,1fr) auto 30px}
  .rv-svx__item.has-url .rv-svx__arrow{display:grid;width:30px;height:30px}
  .rv-svx__name{font-size:16.5px}
  .rv-svx__price{font-size:15px}
  .rv-svx__price small{display:block;margin:0 0 2px;text-align:right}
  .rv-svx__detail-in{padding:0 4px 0 56px}
}
@media(prefers-reduced-motion:reduce){
  .rv-svx *,.rv-svx *::before,.rv-svx *::after{animation:none!important;transition:none!important}
  .rv-svx.is-ready .rv-svx__head>*,.rv-svx.is-ready .rv-svx__item,.rv-svx.is-ready .rv-svx__stage{opacity:1;transform:none}
}
</style>

<script>
(function(){
  'use strict';
  var root = document.querySelector('[data-rv-svx]');
  if (!root) return;
  var DATA  = <?php
	$rv_svx_js = array();
	foreach ( $rv_svx as $rv_i => $rv_s ) {
		$rv_svx_js[] = array(
			'key'   => $rv_s['key'],
			'name'  => $rv_s['name'],
			'desc'  => $rv_s['desc'],
			'tags'  => $rv_s['tags'],
			'price' => $rv_s['price'],
			'glow'  => $rv_s['glow'],
			'glow2' => $rv_s['glow2'],
			'group' => $rv_s['group'],
			'glab'  => $rv_svx_ui['groups'][ $rv_s['group'] ],
			'icon'  => $rv_svx_icons[ $rv_s['icon'] ],
			'url'   => $rv_s['url'],
		);
	}
	echo wp_json_encode( $rv_svx_js, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES );
  ?>;
  var items  = [].slice.call(root.querySelectorAll('.rv-svx__item'));
  var stage  = root.querySelector('.rv-svx__stage');
  var q      = function(k){ return root.querySelector('[data-svx="' + k + '"]'); };
  var reduce = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
  var wide   = window.matchMedia('(min-width: 1025px)');
  var DUR = 5200, cur = 0, timer = null, inView = false, hold = false, swapT = null;

  /* — витрина: плавная подмена содержимого — */
  function paint(i){
    var s = DATA[i];
    var pad = function(n){ return (n < 10 ? '0' : '') + n; };
    stage.style.setProperty('--g', s.glow);
    stage.style.setProperty('--g2', s.glow2);
    root.style.setProperty('--svx-g', s.glow);
    q('url').textContent  = 'revive-agency.com/services/' + s.key;
    q('idx').textContent  = pad(i + 1);
    q('name').textContent = s.name;
    q('desc').textContent = s.desc;
    q('price').textContent = s.price;
    var g = q('grp'); g.textContent = s.glab; g.className = 'rv-svx__grp rv-svx__grp--' + s.group;
    var t = q('tags'); t.innerHTML = '';
    s.tags.forEach(function(x){ var li = document.createElement('li'); li.textContent = x; t.appendChild(li); });
    q('icon').innerHTML = s.icon;
    var cta = root.querySelector('[data-svx-cta]');
    if (cta) {
      cta.setAttribute('href', s.url || '#win1');
      cta.querySelector('[data-svx-cta-t]').textContent = s.url ? <?php echo wp_json_encode( $rv_svx_ui['more'], JSON_UNESCAPED_UNICODE ); ?> : <?php echo wp_json_encode( $rv_svx_ui['order'], JSON_UNESCAPED_UNICODE ); ?>;
    }
  }
  function select(i, fromUser){
    if (i === cur && fromUser !== 'init') { if (wide.matches) restart(); return; }
    items[cur].classList.remove('is-active','is-auto');
    cur = i;
    items[cur].classList.add('is-active');
    if (!stage || !wide.matches) return;
    var fading = root.querySelectorAll('.rv-svx__stage [data-svx]');
    if (reduce) { paint(i); restart(); return; }
    Array.prototype.forEach.call(fading, function(el){ el.classList.add('is-out'); });
    clearTimeout(swapT);
    swapT = setTimeout(function(){
      paint(i);
      var orb = q('icon'); orb.classList.remove('is-swap'); void orb.offsetWidth; orb.classList.add('is-swap');
      Array.prototype.forEach.call(fading, function(el, n){
        setTimeout(function(){ el.classList.remove('is-out'); }, n * 35);
      });
    }, 220);
    restart();
  }

  /* — автосмена на десктопе: 5.2 с на услугу, пауза при наведении/фокусе — */
  function stop(){ clearTimeout(timer); timer = null; items.forEach(function(it){ it.classList.remove('is-auto'); }); }
  function restart(){
    stop();
    if (reduce || !inView || hold || !wide.matches || document.hidden) return;
    var it = items[cur];
    it.classList.remove('is-auto'); void it.offsetWidth; it.classList.add('is-auto');
    root.style.setProperty('--svx-dur', DUR + 'ms');
    timer = setTimeout(function(){ select((cur + 1) % items.length); }, DUR);
  }

  items.forEach(function(it, i){
    var btn = it.querySelector('.rv-svx__row');
    btn.addEventListener('click', function(e){
      /* телефон/планшет: стрілка рядка з власною сторінкою веде на неї */
      if (!wide.matches && DATA[i].url && e.target.closest('.rv-svx__arrow')) { window.location.href = DATA[i].url; return; }
      /* десктоп: рядок уже виділено наведенням — клік веде на сторінку послуги, якщо вона є */
      if (wide.matches) { if (DATA[i].url) { window.location.href = DATA[i].url; return; } select(i, true); return; }
      /* телефон: аккордеон, открыта одна строка */
      var open = !it.classList.contains('is-open');
      items.forEach(function(o){ o.classList.remove('is-open'); o.querySelector('.rv-svx__row').setAttribute('aria-expanded','false'); });
      if (open) { it.classList.add('is-open'); btn.setAttribute('aria-expanded','true'); }
      select(i, true);
    });
    btn.addEventListener('mouseenter', function(){ if (wide.matches) select(i, true); });
  });
  var list = root.querySelector('.rv-svx__body');
  list.addEventListener('mouseenter', function(){ hold = true; stop(); });
  list.addEventListener('mouseleave', function(){ hold = false; restart(); });
  root.addEventListener('focusin', function(){ hold = true; stop(); });
  root.addEventListener('focusout', function(){ hold = false; restart(); });
  document.addEventListener('visibilitychange', restart);
  if (wide.addEventListener) wide.addEventListener('change', restart);

  /* — появление по скроллу + запуск автосмены — */
  root.querySelectorAll('.rv-svx__head>*').forEach(function(el, n){ el.style.setProperty('--d', n); });
  items.forEach(function(el, n){ el.style.setProperty('--d', n + 2); });
  if (stage) stage.style.setProperty('--d', 3);
  if (!reduce && 'IntersectionObserver' in window) {
    root.classList.add('is-ready');
    new IntersectionObserver(function(es){
      inView = es[0].isIntersecting;
      if (inView) root.classList.add('is-in');
      restart();
    }, { threshold: .15 }).observe(root);
  } else {
    inView = true;
    root.classList.add('is-in');
  }
})();
</script>
</section>
<!-- ===== /SERVICES BLOCK ===== -->

<?php /* Бегущая строка отключена: повторяла заголовок блока «We build / We grow / We scale».
   Чтобы вернуть — заменить false на true. */ if ( false ) : ?>
<!-- ===== RV MARQUEE v3: две перекрещенные ленты-«скотча» ===== -->
<?php
$rv_mq_en = function_exists('pll_current_language') && 'en' === pll_current_language();
$rv_mq_a  = array('WE BUILD', 'WE GROW', 'WE SCALE', 'REVIVE YOUR BUSINESS');
$rv_mq_b  = $rv_mq_en
	? array('Websites', 'E-commerce', 'Mobile apps', 'Google Ads', 'SEO', 'CRM', 'AI assistants', 'Automation')
	: array('Сайти', 'Інтернет-магазини', 'Застосунки', 'Google Ads', 'SEO', 'CRM', 'AI-асистенти', 'Автоматизація');
?>
<div class="rv-marquee" id="rv-marquee" aria-hidden="true"
     data-a="<?php echo esc_attr( wp_json_encode( $rv_mq_a ) ); ?>"
     data-b="<?php echo esc_attr( wp_json_encode( $rv_mq_b ) ); ?>">
<style>
/* Две сплошные ленты крест-накрест: золотая сверху, тёмная стеклянная под ней.
   У каждой свой непрозрачный фон — ничего не просвечивает и не двоится,
   пересечение читается как приём. Края блока растворяются маской. */
.rv-marquee{ --ha:clamp(52px,5.4vw,78px); --hb:clamp(42px,4.2vw,60px);
  position:relative; background:#030032; padding:clamp(64px,7.5vw,112px) 0; overflow:hidden; isolation:isolate;
  -webkit-mask-image:linear-gradient(90deg,transparent 0,#000 7%,#000 93%,transparent 100%);
          mask-image:linear-gradient(90deg,transparent 0,#000 7%,#000 93%,transparent 100%); }
.rv-marquee::before{ content:""; position:absolute; inset:0; z-index:-1; pointer-events:none;
  background:radial-gradient(ellipse 42% 70% at 50% 50%, rgba(245,197,66,.14) 0%, transparent 70%); }
.rv-marquee__tilt{ position:relative; display:flex; align-items:center; margin:0 -120px; overflow:hidden; }
.rv-marquee__row{ position:relative; z-index:1; display:flex; width:max-content; will-change:transform; }
.rv-marquee__row span{ display:flex; align-items:center; white-space:nowrap; }

/* лента A — золото, чернильный текст, блик пробегает по ленте */
.rv-marquee__tilt--a{ z-index:2; height:var(--ha); transform:rotate(-3deg);
  background:linear-gradient(90deg,#C99A2E 0%,#F5C542 18%,#FCE7A2 50%,#F5C542 82%,#C99A2E 100%);
  box-shadow:0 22px 60px rgba(0,0,0,.55), 0 0 70px rgba(245,197,66,.22), inset 0 1px 0 rgba(255,255,255,.55), inset 0 -1px 0 rgba(120,80,0,.35); }
.rv-marquee__tilt--a::after{ content:""; position:absolute; z-index:0; top:0; bottom:0; left:0; width:22%; pointer-events:none;
  background:linear-gradient(100deg,transparent,rgba(255,255,255,.65),transparent);
  transform:translate3d(-120%,0,0) skewX(-20deg); animation:rvMqSheen 6.5s cubic-bezier(.45,0,.2,1) 1.2s infinite; }
.rv-marquee__row--a span{ font-family:'Manrope','Montserrat',Arial,sans-serif; font-weight:800;
  font-size:clamp(20px,2.3vw,34px); letter-spacing:-.01em; text-transform:uppercase; color:#030032; }
.rv-marquee__row--a i{ font-style:normal; display:inline-block; padding:0 .9em; font-size:.62em; color:#030032;
  animation:rvStarSpin 7s linear infinite; }

/* лента B — тёмное стекло с золотой кромкой, моношрифт */
.rv-marquee__tilt--b{ z-index:1; height:var(--hb); transform:rotate(3deg);
  margin-top:calc((var(--ha) + var(--hb)) / -2);
  background:linear-gradient(180deg,#0E1052,#070837);
  border-top:1px solid rgba(245,197,66,.35); border-bottom:1px solid rgba(245,197,66,.35);
  box-shadow:0 18px 50px rgba(0,0,0,.5); }
.rv-marquee__row--b span{ font-family:'JetBrains Mono',monospace; font-weight:500;
  font-size:clamp(12px,1.15vw,17px); letter-spacing:.2em; text-transform:uppercase; color:#fff; }
.rv-marquee__row--b i{ font-style:normal; display:inline-block; padding:0 1.6em; color:#F5C542; font-size:.9em; }

@keyframes rvStarSpin{ to{ transform:rotate(360deg); } }
@keyframes rvMqSheen{ 0%{ transform:translate3d(-120%,0,0) skewX(-20deg); } 45%,100%{ transform:translate3d(560%,0,0) skewX(-20deg); } }
@media(max-width:768px){
  .rv-marquee{ --ha:48px; --hb:38px; padding:54px 0; }
  .rv-marquee__tilt--a{ transform:rotate(-6deg); }
  .rv-marquee__tilt--b{ transform:rotate(6deg); }
  .rv-marquee__row--a span{ font-size:19px; }
  .rv-marquee__row--b span{ font-size:11px; letter-spacing:.16em; }
}
@media (prefers-reduced-motion: reduce){
  .rv-marquee__row--a i,.rv-marquee__tilt--a::after{ animation:none; }
}
</style>
<div class="rv-marquee__tilt rv-marquee__tilt--a"><div class="rv-marquee__row rv-marquee__row--a" data-dir="-1" data-speed="1.1"></div></div>
<div class="rv-marquee__tilt rv-marquee__tilt--b"><div class="rv-marquee__row rv-marquee__row--b" data-dir="1" data-speed=".75"></div></div>
<script>
(function(){
'use strict';
var root=document.getElementById('rv-marquee');
if(!root) return;
function words(attr, sep){
  var list=[]; try{ list=JSON.parse(root.getAttribute(attr)||'[]'); }catch(e){}
  var html=list.map(function(w){ var s=document.createElement('b'); s.textContent=w; return s.innerHTML; }).join('<i>'+sep+'</i>')+'<i>'+sep+'</i>';
  return '<span>'+html+'</span>';
}
var rowA=root.querySelector('.rv-marquee__row--a'), rowB=root.querySelector('.rv-marquee__row--b');
var a=words('data-a','&#10022;'), b=words('data-b','&#9670;');
rowA.innerHTML=a+a+a; rowB.innerHTML=b+b+b;                     /* запас, чтобы лента не кончалась на широких экранах */
var reduced=window.matchMedia('(prefers-reduced-motion: reduce)').matches;
if(reduced) return;
var items=[rowA,rowB].map(function(r){ return {el:r, x:0, dir:+r.dataset.dir, speed:+r.dataset.speed, unit:0}; });
function measure(){ items.forEach(function(it){ var s=it.el.firstElementChild; it.unit=s?s.offsetWidth:0; }); }
var mqWidth=innerWidth;
measure();
if(document.fonts&&document.fonts.ready) document.fonts.ready.then(measure);
addEventListener('resize',function(){ if(innerWidth===mqWidth) return; mqWidth=innerWidth; measure(); },{passive:true});
/* скорость дышит от скролла: вниз — ленты разгоняются, вверх — едут назад */
var lastY=pageYOffset, boost=0, active=false, raf=0;
addEventListener('scroll',function(){ if(!active){ lastY=pageYOffset; return; } boost+=(pageYOffset-lastY)*.06; lastY=pageYOffset; },{passive:true});
function tick(){
  if(!active||document.hidden){ raf=0; return; }
  boost*=.92;
  var v=Math.max(-6,Math.min(6,boost));
  items.forEach(function(it){
    if(!it.unit) return;
    it.x+=(it.speed+v*it.dir*-1)*it.dir;
    it.x=((it.x%it.unit)+it.unit)%it.unit;                     /* бесшовно: сдвиг на ширину одного куска */
    it.el.style.transform='translate3d('+(-it.x)+'px,0,0)';
  });
  raf=requestAnimationFrame(tick);
}
function start(){ if(active&&!document.hidden&&!raf) raf=requestAnimationFrame(tick); }
function stop(){ if(raf){ cancelAnimationFrame(raf); raf=0; } }
if('IntersectionObserver' in window){
  new IntersectionObserver(function(es){ active=es[0].isIntersecting; if(active){ lastY=pageYOffset; start(); } else stop(); },{threshold:0,rootMargin:'160px 0px'}).observe(root);
}else{ active=true; start(); }
document.addEventListener('visibilitychange',function(){ if(document.hidden) stop(); else start(); });
})();
</script>
</div>
<!-- ===== /RV MARQUEE ===== -->
<?php endif; ?>

<!-- ===== WHAT WE DO / BUILD · GROW · SCALE ===== -->
<?php
$rv_wwd_en = ! function_exists('pll_current_language') || pll_current_language() === 'en';

/*
 * Verified values already present in the portfolio data on this homepage.
 * Keep campaign proof in one editable config instead of scattering figures
 * through the markup. Do not add unverified figures here.
 */
$rv_growth_metrics = array(
	array(
		'value'    => '+260%',
		'label_en' => 'Enquiries',
		'label_ua' => 'Звернень',
	),
	array(
		'value'    => '3.1×',
		'label_en' => 'ROAS',
		'label_ua' => 'ROAS',
	),
	array(
		'value'    => '−42%',
		'label_en' => 'Cost per lead',
		'label_ua' => 'Вартість ліда',
	),
);

$rv_build_services = $rv_wwd_en
	? array('Corporate websites', 'Ecommerce', 'Landing pages', 'Web applications', 'Mobile apps', 'UI/UX design')
	: array('Корпоративні сайти', 'Інтернет-магазини', 'Лендінги', 'Вебзастосунки', 'Мобільні застосунки', 'UI/UX дизайн');
$rv_grow_services = $rv_wwd_en
	? array('Google Ads', 'Meta Ads', 'SEO', 'Analytics', 'Conversion optimization')
	: array('Google Ads', 'Meta Ads', 'SEO', 'Аналітика', 'Оптимізація конверсії');
$rv_scale_services = $rv_wwd_en
	? array('CRM', 'Lead management', 'Sales automation', 'Integrations', 'AI assistants', 'Chatbots')
	: array('CRM', 'Управління лідами', 'Автоматизація продажів', 'Інтеграції', 'AI-асистенти', 'Чат-боти');
?>
<section class="rv-wwd<?php echo $rv_wwd_en ? '' : ' rv-wwd--ua'; ?>" id="rv-wwd" data-motion-version="5.2" aria-labelledby="rv-wwd-title">
	<div class="rv-wwd-story-rail" aria-hidden="true">
		<span>01</span><i></i><b></b><span>03</span>
	</div>
	<header class="rv-wwd__intro rv-wwd-reveal">
		<p class="rv-wwd__eyebrow"><?php echo $rv_wwd_en ? 'What we do' : 'Що ми робимо'; ?></p>
		<h2 class="rv-wwd__intro-title" id="rv-wwd-title">
			<?php if ($rv_wwd_en) : ?>
				<span class="rv-wwd__statement rv-wwd__statement--build" data-text="We build.">We build.</span>
				<span class="rv-wwd__statement rv-wwd__statement--grow" data-text="We grow.">We grow.</span>
				<em class="rv-wwd__statement rv-wwd__statement--scale" data-text="We scale.">We scale.</em>
			<?php else : ?>
				<span class="rv-wwd__statement rv-wwd__statement--build" data-text="Створюємо.">Створюємо.</span>
				<span class="rv-wwd__statement rv-wwd__statement--grow" data-text="Просуваємо.">Просуваємо.</span>
				<em class="rv-wwd__statement rv-wwd__statement--scale" data-text="Масштабуємо.">Масштабуємо.</em>
			<?php endif; ?>
		</h2>
		<p class="rv-wwd__intro-copy">
			<?php echo $rv_wwd_en
				? 'From digital products to customer acquisition and automation — we build systems that help businesses grow.'
				: 'Від цифрових продуктів до залучення клієнтів та автоматизації — створюємо системи, що допомагають бізнесу зростати.'; ?>
		</p>
	</header>

	<span class="rv-dock-mark" data-dock-mark="build" aria-hidden="true"></span>
	<section class="rv-wwd-panel rv-wwd-panel--build rv-wwd-reveal" id="build" data-chapter="01 / BUILD" data-stack-card="01" aria-labelledby="rv-build-title">
		<div class="rv-wwd-atmosphere" aria-hidden="true"><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span></div>
		<div class="rv-wwd-panel__copy">
			<p class="rv-wwd__eyebrow"><span>01</span> / <?php echo $rv_wwd_en ? 'Build' : 'Створюємо'; ?></p>
			<h2 class="rv-wwd-panel__title" id="rv-build-title">
				<?php echo $rv_wwd_en ? 'Websites &amp;<br><em>Digital Products</em>' : 'Сайти та<br><em>цифрові продукти</em>'; ?>
			</h2>
			<p class="rv-wwd-panel__description">
				<?php echo $rv_wwd_en
					? 'We design and build digital products that turn attention into action and visitors into customers.'
					: 'Проєктуємо та розробляємо цифрові продукти, які перетворюють увагу на дію, а відвідувачів — на клієнтів.'; ?>
			</p>
			<div class="rv-wwd-chips" aria-label="<?php echo $rv_wwd_en ? 'Build services' : 'Послуги зі створення'; ?>">
				<?php foreach ($rv_build_services as $rv_service) : ?>
					<span role="button" tabindex="0" aria-pressed="false"><?php echo esc_html($rv_service); ?></span>
				<?php endforeach; ?>
			</div>
			<div class="rv-wwd-price">
				<span><?php echo $rv_wwd_en ? 'Projects from' : 'Проєкти від'; ?></span>
				<strong>$1,500</strong>
			</div>
			<div class="rv-wwd-actions">
				<a class="rv-wwd-btn rv-wwd-btn--primary" href="#win1"><?php echo $rv_wwd_en ? 'Start a project' : 'Розпочати проєкт'; ?> <span>↗︎</span></a>
				<a class="rv-wwd-btn rv-wwd-btn--text" href="#rv-cine"><?php echo $rv_wwd_en ? 'View our work' : 'Дивитися роботи'; ?> <span>→</span></a>
			</div>
		</div>

		<figure class="rv-build-visual" aria-label="<?php echo $rv_wwd_en ? 'Animated digital product interface' : 'Анімований інтерфейс цифрового продукту'; ?>">
			<div class="rv-build-orbit rv-build-orbit--one" aria-hidden="true"></div>
			<div class="rv-build-orbit rv-build-orbit--two" aria-hidden="true"></div>
			<div class="rv-build-browser">
				<div class="rv-build-browser__bar"><i></i><i></i><i></i><span>revive-agency.com</span></div>
				<div class="rv-build-browser__screen">
					<div class="rv-build-browser__nav"><strong>REVIVE <span>AGENCY</span></strong><i></i><i></i><b>START ↗︎</b></div>
					<div class="rv-build-browser__hero">
						<p>DIGITAL PRODUCT / 2026</p>
						<h3><?php echo $rv_wwd_en ? 'MAKE BRANDS<br><em>MOVE.</em>' : 'БРЕНДИ, ЩО<br><em>РУХАЮТЬСЯ.</em>'; ?></h3>
						<span><?php echo $rv_wwd_en ? 'Strategy · Design · Development' : 'Стратегія · Дизайн · Розробка'; ?></span>
					</div>
					<div class="rv-build-browser__cards"><i></i><i></i><i></i></div>
					<div class="rv-build-cursor" aria-hidden="true"><i></i></div>
				</div>
			</div>
			<div class="rv-build-phone" aria-hidden="true">
				<div class="rv-build-phone__sensor"></div>
				<div class="rv-build-phone__brand">REVIVE <span>AGENCY</span></div>
				<p><?php echo $rv_wwd_en ? 'Experience<br>in motion.' : 'Досвід<br>у русі.'; ?></p>
				<div class="rv-build-phone__orb"></div>
				<div class="rv-build-phone__button">EXPLORE ↗︎</div>
			</div>
			<figcaption class="screen-reader-text">
				<span><?php echo $rv_wwd_en ? 'Interactive product prototype' : 'Інтерактивний прототип продукту'; ?></span>
				<strong>REVIVE DIGITAL SYSTEM</strong>
				<small><?php echo $rv_wwd_en ? 'Strategy · UX/UI · Development' : 'Стратегія · UX/UI · Розробка'; ?></small>
			</figcaption>
		</figure>
	</section>

	<span class="rv-dock-mark" data-dock-mark="grow" aria-hidden="true"></span>
	<section class="rv-wwd-panel rv-wwd-panel--grow rv-wwd-reveal" id="grow" data-chapter="02 / GROW" data-stack-card="02" aria-labelledby="rv-grow-title">
		<div class="rv-wwd-atmosphere" aria-hidden="true"><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span></div>
		<div class="rv-grow-dashboard" aria-label="<?php echo $rv_wwd_en ? 'Selected campaign performance dashboard' : 'Дашборд результатів окремих кампаній'; ?>">
			<div class="rv-grow-metrics">
				<?php foreach ($rv_growth_metrics as $rv_metric) : ?>
					<div><strong><?php echo esc_html($rv_metric['value']); ?></strong><span><?php echo esc_html($rv_wwd_en ? $rv_metric['label_en'] : $rv_metric['label_ua']); ?></span></div>
				<?php endforeach; ?>
			</div>
			<div class="rv-grow-chart">
				<div class="rv-grow-chart__grid" aria-hidden="true"></div>
				<svg viewBox="0 0 680 260" role="img" aria-label="<?php echo $rv_wwd_en ? 'Campaign performance rising over time' : 'Зростання ефективності кампанії з часом'; ?>">
					<defs><linearGradient id="rvGrowFill" x1="0" y1="0" x2="0" y2="1"><stop offset="0" stop-color="#f5c542" stop-opacity=".24"/><stop offset="1" stop-color="#f5c542" stop-opacity="0"/></linearGradient></defs>
					<path class="rv-grow-chart__fill" d="M0 229 C80 224 95 204 150 202 S240 215 285 165 S380 164 420 120 S515 125 558 77 S630 61 680 24 L680 260 L0 260 Z"/>
					<path class="rv-grow-chart__line" pathLength="1" d="M0 229 C80 224 95 204 150 202 S240 215 285 165 S380 164 420 120 S515 125 558 77 S630 61 680 24"/>
					<circle class="rv-grow-chart__point" cx="680" cy="24" r="7"/>
					<circle class="rv-grow-chart__runner rv-grow-chart__runner--one" r="5" aria-hidden="true">
						<animateMotion dur="4.8s" repeatCount="indefinite" path="M0 229 C80 224 95 204 150 202 S240 215 285 165 S380 164 420 120 S515 125 558 77 S630 61 680 24"/>
					</circle>
					<circle class="rv-grow-chart__runner rv-grow-chart__runner--two" r="3" aria-hidden="true">
						<animateMotion begin="-2.4s" dur="4.8s" repeatCount="indefinite" path="M0 229 C80 224 95 204 150 202 S240 215 285 165 S380 164 420 120 S515 125 558 77 S630 61 680 24"/>
					</circle>
				</svg>
			</div>
			<div class="rv-grow-dashboard__foot"><span><?php echo $rv_wwd_en ? 'Selected campaign results' : 'Результати окремих кампаній'; ?></span></div>
		</div>

		<div class="rv-wwd-panel__copy">
			<p class="rv-wwd__eyebrow"><span>02</span> / <?php echo $rv_wwd_en ? 'Grow' : 'Просуваємо'; ?></p>
			<h2 class="rv-wwd-panel__title" id="rv-grow-title"><?php echo $rv_wwd_en ? 'Marketing &amp;<br><em>Growth</em>' : 'Маркетинг та<br><em>зростання</em>'; ?></h2>
			<p class="rv-wwd-panel__description">
				<?php echo $rv_wwd_en
					? 'We turn traffic into measurable growth through performance marketing, search and conversion optimization.'
					: 'Перетворюємо трафік на вимірюване зростання за допомогою performance-маркетингу, пошуку та оптимізації конверсії.'; ?>
			</p>
			<div class="rv-wwd-chips">
				<?php foreach ($rv_grow_services as $rv_service) : ?><span role="button" tabindex="0" aria-pressed="false"><?php echo esc_html($rv_service); ?></span><?php endforeach; ?>
			</div>
			<div class="rv-wwd-actions">
				<a class="rv-wwd-btn rv-wwd-btn--primary" href="#win1"><?php echo $rv_wwd_en ? 'Grow your business' : 'Зростати разом з нами'; ?> <span>↗︎</span></a>
				<a class="rv-wwd-btn rv-wwd-btn--text" href="#rv-cine"><?php echo $rv_wwd_en ? 'View marketing cases' : 'Дивитися маркетингові кейси'; ?> <span>→</span></a>
			</div>
		</div>
	</section>

	<span class="rv-dock-mark" data-dock-mark="scale" aria-hidden="true"></span>
	<section class="rv-wwd-panel rv-wwd-panel--scale rv-wwd-reveal" id="scale" data-chapter="03 / SCALE" data-stack-card="03" aria-labelledby="rv-scale-title">
		<div class="rv-wwd-atmosphere" aria-hidden="true"><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span></div>
		<div class="rv-wwd-panel__copy">
			<p class="rv-wwd__eyebrow"><span>03</span> / <?php echo $rv_wwd_en ? 'Scale' : 'Масштабуємо'; ?></p>
			<h2 class="rv-wwd-panel__title" id="rv-scale-title"><?php echo $rv_wwd_en ? 'CRM, AI &amp;<br><em>Automation</em>' : 'CRM, AI та<br><em>автоматизація</em>'; ?></h2>
			<p class="rv-wwd-panel__description">
				<?php echo $rv_wwd_en
					? 'We connect marketing, sales and operations into one intelligent system built to scale.'
					: 'Об’єднуємо маркетинг, продажі та операційні процеси в єдину розумну систему, готову до масштабування.'; ?>
			</p>
			<div class="rv-wwd-chips">
				<?php foreach ($rv_scale_services as $rv_service) : ?><span role="button" tabindex="0" aria-pressed="false"><?php echo esc_html($rv_service); ?></span><?php endforeach; ?>
			</div>
			<div class="rv-wwd-actions">
				<a class="rv-wwd-btn rv-wwd-btn--primary" href="#win1"><?php echo $rv_wwd_en ? 'Automate your business' : 'Автоматизувати бізнес'; ?> <span>↗︎</span></a>
				<a class="rv-wwd-btn rv-wwd-btn--text" href="#scale"><?php echo $rv_wwd_en ? 'Explore automation' : 'Детальніше про автоматизацію'; ?> <span>→</span></a>
			</div>
		</div>

		<div class="rv-scale-flow" aria-label="<?php echo $rv_wwd_en ? 'Traffic to automated sales flow' : 'Шлях від трафіку до автоматизованих продажів'; ?>">
			<svg class="rv-scale-flow__lines" viewBox="0 0 720 560" preserveAspectRatio="none" aria-hidden="true">
				<path pathLength="1" d="M78 92 H210 Q245 92 245 128 V184 H350"/>
				<path pathLength="1" d="M78 184 H350"/>
				<path pathLength="1" d="M78 276 H210 Q245 276 245 238 V184 H350"/>
				<path pathLength="1" d="M410 184 V282"/>
				<path pathLength="1" d="M410 338 V402 H260"/>
				<path pathLength="1" d="M410 338 V402 H560"/>
				<path pathLength="1" d="M260 458 V500 H410"/>
				<path pathLength="1" d="M560 458 V500 H410"/>
				<g class="rv-scale-flow__packets" aria-hidden="true">
					<circle class="rv-scale-flow__packet rv-scale-flow__packet--gold" r="5">
						<animateMotion dur="3.6s" repeatCount="indefinite" path="M78 92 H210 Q245 92 245 128 V184 H350"/>
					</circle>
					<circle class="rv-scale-flow__packet rv-scale-flow__packet--blue" r="4">
						<animateMotion begin="-1.2s" dur="3.6s" repeatCount="indefinite" path="M78 184 H350"/>
					</circle>
					<circle class="rv-scale-flow__packet rv-scale-flow__packet--green" r="4">
						<animateMotion begin="-2.4s" dur="3.6s" repeatCount="indefinite" path="M78 276 H210 Q245 276 245 238 V184 H350"/>
					</circle>
					<circle class="rv-scale-flow__packet rv-scale-flow__packet--gold" r="5">
						<animateMotion begin="-.7s" dur="3.2s" repeatCount="indefinite" path="M410 184 V282"/>
					</circle>
					<circle class="rv-scale-flow__packet rv-scale-flow__packet--green" r="5">
						<animateMotion begin="-1.5s" dur="3.8s" repeatCount="indefinite" path="M410 338 V402 H560 V458 V500 H410"/>
					</circle>
				</g>
			</svg>
			<div class="rv-scale-node rv-scale-node--source rv-scale-node--ads">Google Ads</div>
			<div class="rv-scale-node rv-scale-node--source rv-scale-node--meta">Meta Ads</div>
			<div class="rv-scale-node rv-scale-node--source rv-scale-node--seo">SEO</div>
			<div class="rv-scale-node rv-scale-node--website"><i></i>Website</div>
			<div class="rv-scale-node rv-scale-node--crm"><small>02</small>CRM<strong><?php echo $rv_wwd_en ? 'Lead captured' : 'Лід отримано'; ?></strong></div>
			<div class="rv-scale-node rv-scale-node--ai">AI</div>
			<div class="rv-scale-node rv-scale-node--auto"><?php echo $rv_wwd_en ? 'Automation' : 'Автоматизація'; ?></div>
			<div class="rv-scale-node rv-scale-node--sales"><span>●</span><?php echo $rv_wwd_en ? 'Sales' : 'Продажі'; ?></div>
			<div class="rv-scale-flow__dot" aria-hidden="true"></div>
		</div>
	</section>
</section>
<style id="rv-wow-css">
/* ======================================================================
   WHAT WE DO — WOW LAYER v1 (BUILD / GROW / SCALE), ПК и телефон.
   Надстройка над существующей анимацией: ничего не переписывает.
   Используются только отдельные свойства (translate / scale / rotate /
   clip-path) и собственные слои, которые скрипт ниже вставляет в карточки,
   поэтому «плавание», наклон за курсором и рисование линий из style.css
   продолжают работать. Сцена запускается один раз, когда панель в кадре.
   ====================================================================== */
.rv-wow-ready{--wow-ease:cubic-bezier(.16,1,.3,1);--wow-spring:cubic-bezier(.34,1.45,.64,1)}

/* ── 1. Кинематографичное раскрытие карточек ─────────────────────────── */
.rv-wow-ready .rv-build-browser,
.rv-wow-ready .rv-grow-dashboard,
.rv-wow-ready .rv-scale-flow{clip-path:inset(9% 7% 9% 7% round 40px)}
.rv-wow-ready .rv-wwd-panel.rv-wow .rv-build-browser,
.rv-wow-ready .rv-wwd-panel.rv-wow .rv-grow-dashboard,
.rv-wow-ready .rv-wwd-panel.rv-wow .rv-scale-flow{
  clip-path:inset(-18% -18% -18% -18% round 0);
  transition:clip-path 1.25s var(--wow-ease) .08s}

/* блик-«вспышка», проходит по карточке при раскрытии и потом раз в ~9 с */
.rv-wow-sweep{position:absolute;inset:0;z-index:6;pointer-events:none;overflow:hidden;border-radius:inherit}
.rv-wow-sweep::before{content:"";position:absolute;top:-30%;bottom:-30%;left:0;width:34%;
  background:linear-gradient(100deg,transparent,rgba(255,255,255,.035) 30%,rgba(255,244,210,.16) 50%,rgba(255,255,255,.035) 70%,transparent);
  transform:translate3d(-130%,0,0) skewX(-16deg);opacity:0}
.rv-wow .rv-wow-sweep::before{animation:rvWowSweep 9s var(--wow-ease) .55s infinite}
@keyframes rvWowSweep{0%{transform:translate3d(-130%,0,0) skewX(-16deg);opacity:0}3%{opacity:1}16%{opacity:1}20%,100%{transform:translate3d(420%,0,0) skewX(-16deg);opacity:0}}

/* свет за курсором (только ПК с мышью) */
.rv-wow-spot{position:absolute;inset:0;z-index:5;pointer-events:none;border-radius:inherit;opacity:0;transition:opacity .5s ease;
  background:radial-gradient(420px circle at var(--sx,50%) var(--sy,50%),rgba(245,197,66,.10),rgba(100,108,255,.06) 35%,transparent 65%);mix-blend-mode:screen}
.rv-wow-hover .rv-wow-spot{opacity:1}

/* ── 2. BUILD: телефон «влетает», заголовок в макете проявляется строками ── */
.rv-wow-ready .rv-build-phone{translate:90px 70px;rotate:16deg;scale:.86;
  transition:translate 1.35s var(--wow-ease) .35s,rotate 1.35s var(--wow-ease) .35s,scale 1.35s var(--wow-spring) .35s}
.rv-wow-ready .rv-wow .rv-build-phone{translate:0 0;rotate:0deg;scale:1}
.rv-wow-ready .rv-build-browser__nav,
.rv-wow-ready .rv-build-browser__hero p,
.rv-wow-ready .rv-build-browser__hero h3,
.rv-wow-ready .rv-build-browser__hero span{opacity:0;translate:0 22px;clip-path:inset(0 0 100% 0);
  transition:opacity .8s ease,translate 1s var(--wow-ease),clip-path 1s var(--wow-ease)}
.rv-wow-ready .rv-wow .rv-build-browser__nav{opacity:1;translate:0 0;clip-path:inset(-20% -20% -20% -20%);transition-delay:.45s}
.rv-wow-ready .rv-wow .rv-build-browser__hero p{opacity:1;translate:0 0;clip-path:inset(-20% -20% -20% -20%);transition-delay:.6s}
.rv-wow-ready .rv-wow .rv-build-browser__hero h3{opacity:1;translate:0 0;clip-path:inset(-20% -20% -20% -20%);transition-delay:.72s}
.rv-wow-ready .rv-wow .rv-build-browser__hero span{opacity:1;translate:0 0;clip-path:inset(-20% -20% -20% -20%);transition-delay:.9s}
.rv-wow-ready .rv-build-browser__cards{scale:1 0;transform-origin:50% 100%;transition:scale 1s var(--wow-spring) 1s}
.rv-wow-ready .rv-wow .rv-build-browser__cards{scale:1 1}
.rv-wow-ready .rv-build-phone p,.rv-wow-ready .rv-build-phone__button{opacity:0;translate:0 14px;transition:opacity .7s ease 1.05s,translate .9s var(--wow-ease) 1.05s}
.rv-wow-ready .rv-wow .rv-build-phone p,.rv-wow-ready .rv-wow .rv-build-phone__button{opacity:1;translate:0 0}
.rv-wow-ready .rv-wow .rv-build-phone__button{transition-delay:1.2s}
/* глубина за курсором: телефон и орбиты смещаются сильнее окна */
.rv-wow-ready .rv-wow .rv-build-orbit{translate:calc(var(--wx,0) * 26px) calc(var(--wy,0) * 20px);transition:translate .9s var(--wow-ease)}
.rv-wow-ready .rv-wow.rv-wow-done .rv-build-phone{translate:calc(var(--wx,0) * 18px) calc(var(--wy,0) * 14px);transition:translate .9s var(--wow-ease),rotate 1.35s var(--wow-ease),scale 1.35s var(--wow-spring)}

/* ── 3. GROW: сетка «сканируется», луч идёт вместе с линией, финиш — ударная волна ── */
.rv-wow-ready .rv-grow-chart__grid{clip-path:inset(0 100% 0 0);transition:clip-path 1.6s var(--wow-ease) .3s}
.rv-wow-ready .rv-wow .rv-grow-chart__grid{clip-path:inset(0 0 0 0)}
.rv-wow-ready .rv-grow-chart__fill{opacity:0;transition:opacity 1.2s ease 1.3s}
.rv-wow-ready .rv-wow .rv-grow-chart__fill{opacity:1}
.rv-wow-scan{position:absolute;top:0;bottom:0;left:0;width:90px;z-index:3;pointer-events:none;opacity:0;
  background:linear-gradient(90deg,transparent,rgba(245,197,66,.10) 60%,rgba(255,236,170,.5) 97%,transparent);transform:translate3d(-100px,0,0)}
.rv-wow .rv-wow-scan{animation:rvWowScan 1.45s cubic-bezier(.45,0,.2,1) .36s both}
@keyframes rvWowScan{0%{opacity:0;transform:translate3d(-100px,0,0)}12%{opacity:1}88%{opacity:1}100%{opacity:0;transform:translate3d(var(--scan-w,700px),0,0)}}
.rv-wow-ring{fill:none;stroke:#F5C542;stroke-width:2;opacity:0}
/* подчёркивание метрик рисуется слева направо */
.rv-wow-ready .rv-grow-metrics div{position:relative}
.rv-wow-ready .rv-grow-metrics div::after{content:"";position:absolute;left:0;right:20px;bottom:0;height:2px;border-radius:2px;
  background:linear-gradient(90deg,#F5C542,rgba(245,197,66,0));transform:scaleX(0);transform-origin:left;transition:transform 1.1s var(--wow-ease)}
.rv-wow-ready .rv-grow-metrics div:first-child::after{left:0}
.rv-wow-ready .rv-wow .rv-grow-metrics div::after{transform:scaleX(1)}
.rv-wow-ready .rv-wow .rv-grow-metrics div:nth-child(1)::after{transition-delay:.7s}
.rv-wow-ready .rv-wow .rv-grow-metrics div:nth-child(2)::after{transition-delay:.85s}
.rv-wow-ready .rv-wow .rv-grow-metrics div:nth-child(3)::after{transition-delay:1s}

/* ── 4. SCALE: узлы загораются по ходу потока, CRM «принимает» лид ── */
@media(min-width:601px){
  .rv-wow-ready .rv-scale-node{opacity:0;scale:.5;
    transition:transform var(--rv-dur-base,.6s) var(--rv-motion-spring,ease),border-color .3s ease,box-shadow .3s ease,color .3s ease,
      opacity .5s ease var(--wd,0s),scale .9s var(--wow-spring) var(--wd,0s)}
  .rv-wow-ready .rv-wow .rv-scale-node{opacity:1;scale:1}
  .rv-scale-node--ads{--wd:.25s}.rv-scale-node--meta{--wd:.35s}.rv-scale-node--seo{--wd:.45s}
  .rv-scale-node--website{--wd:.75s}.rv-scale-node--crm{--wd:1s}
  .rv-scale-node--ai{--wd:1.3s}.rv-scale-node--auto{--wd:1.4s}.rv-scale-node--sales{--wd:1.7s}
}
.rv-wow-burst{position:absolute;inset:-2px;border-radius:inherit;pointer-events:none;border:1.5px solid rgba(245,197,66,.8);opacity:0}
.rv-wow .rv-wow-burst{animation:rvWowBurst 3.6s cubic-bezier(.2,.8,.2,1) 1.35s infinite}
.rv-wow .rv-wow-burst+.rv-wow-burst{animation-delay:1.6s}
@keyframes rvWowBurst{0%{opacity:.9;transform:scale(1)}40%,100%{opacity:0;transform:scale(1.45)}}
.rv-wow-ready .rv-scale-node--crm{transition:transform var(--rv-dur-base,.6s) var(--rv-motion-spring,ease),border-color .3s ease,color .3s ease,
  opacity .5s ease var(--wd,0s),scale .9s var(--wow-spring) var(--wd,0s),box-shadow 1.2s ease 1.3s}
.rv-wow-ready .rv-wow .rv-scale-node--crm{box-shadow:0 12px 28px rgba(0,0,16,.24),0 0 46px rgba(245,197,66,.28)}
.rv-scale-node--sales{overflow:hidden}
.rv-wow-shine{position:absolute;top:0;bottom:0;left:0;width:40%;pointer-events:none;
  background:linear-gradient(100deg,transparent,rgba(245,197,66,.55),transparent);transform:translate3d(-120%,0,0) skewX(-18deg)}
.rv-wow .rv-wow-shine{animation:rvWowShine 3.6s ease-in-out 2.1s infinite}
@keyframes rvWowShine{0%{transform:translate3d(-120%,0,0) skewX(-18deg)}35%,100%{transform:translate3d(320%,0,0) skewX(-18deg)}}

/* логотип в макетах: полное имя вместо «R⁄» */
.rv-build-browser__nav strong{font-size:clamp(11px,1.15vw,17px)!important;font-weight:800;letter-spacing:-.01em;white-space:nowrap}
.rv-build-phone__brand{font-size:clamp(7px,.72vw,11px)!important;font-weight:800;letter-spacing:.01em;white-space:nowrap}
.rv-build-phone__brand span{color:#F5C542}
@media(max-width:700px){.rv-build-browser__nav strong{font-size:10px!important}.rv-build-phone__brand{font-size:7px!important}}
@media(prefers-reduced-motion:reduce){
  .rv-wow-ready *{transition:none!important}
  .rv-wow-sweep,.rv-wow-scan,.rv-wow-burst,.rv-wow-shine,.rv-wow-spot{display:none!important}
}
</style>
<script id="rv-wow-js">
(function(){
  'use strict';
  var root = document.getElementById('rv-wwd');
  if (!root || !('IntersectionObserver' in window)) return;
  if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) return;
  var panels = [].slice.call(root.querySelectorAll('.rv-wwd-panel'));
  if (!panels.length) return;

  function add(host, cls, tag){ if (!host) return null; var el = document.createElement(tag || 'span'); el.className = cls; el.setAttribute('aria-hidden','true'); host.appendChild(el); return el; }
  var SVGNS = 'http://www.w3.org/2000/svg';

  /* слои эффектов */
  ['.rv-build-browser','.rv-grow-dashboard','.rv-scale-flow'].forEach(function(sel){
    var el = root.querySelector(sel);
    add(el, 'rv-wow-sweep'); add(el, 'rv-wow-spot');
  });
  var chart = root.querySelector('.rv-grow-chart');
  var scan = add(chart, 'rv-wow-scan');
  var crm = root.querySelector('.rv-scale-node--crm');
  add(crm, 'rv-wow-burst'); add(crm, 'rv-wow-burst');
  add(root.querySelector('.rv-scale-node--sales'), 'rv-wow-shine');

  /* ударная волна на финише графика — два кольца SVG */
  var svg = chart && chart.querySelector('svg'), rings = [];
  if (svg) {
    for (var k = 0; k < 2; k++) {
      var c = document.createElementNS(SVGNS, 'circle');
      c.setAttribute('class','rv-wow-ring'); c.setAttribute('cx','680'); c.setAttribute('cy','24'); c.setAttribute('r','7');
      var a1 = document.createElementNS(SVGNS, 'animate');
      a1.setAttribute('attributeName','r'); a1.setAttribute('values','7;34'); a1.setAttribute('dur','2.4s');
      a1.setAttribute('begin','indefinite'); a1.setAttribute('repeatCount','indefinite');
      var a2 = document.createElementNS(SVGNS, 'animate');
      a2.setAttribute('attributeName','opacity'); a2.setAttribute('values','.8;0'); a2.setAttribute('dur','2.4s');
      a2.setAttribute('begin','indefinite'); a2.setAttribute('repeatCount','indefinite');
      c.appendChild(a1); c.appendChild(a2); svg.appendChild(c); rings.push([a1, a2]);
    }
  }
  function sizeScan(){ if (scan && chart) chart.style.setProperty('--scan-w', (chart.offsetWidth + 20) + 'px'); }
  sizeScan(); window.addEventListener('resize', sizeScan, { passive: true });

  root.classList.add('rv-wow-ready');

  function fire(p){
    if (p.classList.contains('rv-wow')) return;
    p.classList.add('rv-wow');
    setTimeout(function(){ p.classList.add('rv-wow-done'); }, 1800);
    if (p.classList.contains('rv-wwd-panel--grow')) {
      rings.forEach(function(r, n){
        setTimeout(function(){ try { r[0].beginElement(); r[1].beginElement(); } catch(e){} }, 1750 + n * 1200);
      });
    }
  }
  var io = new IntersectionObserver(function(es){
    es.forEach(function(e){ if (e.isIntersecting) { fire(e.target); io.unobserve(e.target); } });
  }, { threshold: .22 });
  panels.forEach(function(p){ io.observe(p); });
  /* страховка: панель уже в кадре при загрузке / переходе по якорю */
  setTimeout(function(){
    panels.forEach(function(p){ var r = p.getBoundingClientRect(); if (r.top < innerHeight * .85 && r.bottom > 0) fire(p); });
  }, 400);

  /* ПК: свет за курсором и глубина сцены BUILD */
  if (!window.matchMedia('(hover: hover) and (pointer: fine)').matches) return;
  panels.forEach(function(p){
    var card = p.querySelector('.rv-build-browser, .rv-grow-dashboard, .rv-scale-flow');
    var raf = 0, ev = null;
    p.addEventListener('pointermove', function(e){
      ev = e; if (raf) return;
      raf = requestAnimationFrame(function(){
        raf = 0;
        var pr = p.getBoundingClientRect();
        p.style.setProperty('--wx', ((ev.clientX - pr.left) / pr.width - .5).toFixed(3));
        p.style.setProperty('--wy', ((ev.clientY - pr.top) / pr.height - .5).toFixed(3));
        if (card) {
          var r = card.getBoundingClientRect();
          card.style.setProperty('--sx', (ev.clientX - r.left) + 'px');
          card.style.setProperty('--sy', (ev.clientY - r.top) + 'px');
          card.classList.toggle('rv-wow-hover', ev.clientX > r.left && ev.clientX < r.right && ev.clientY > r.top && ev.clientY < r.bottom);
        }
      });
    }, { passive: true });
    p.addEventListener('pointerleave', function(){
      p.style.setProperty('--wx', 0); p.style.setProperty('--wy', 0);
      if (card) card.classList.remove('rv-wow-hover');
    });
  });
})();
</script>
<style id="rv-wow2-css">
/* ======================================================================
   WHAT WE DO — WOW LAYER v2 (BUILD / GROW / SCALE), ПК и телефон.
   Надстройка над v1 и style.css: старые правила не переписывает.
   Все новые элементы вставляет скрипт ниже (без JS их нет), циклы —
   только transform / opacity и идут, пока глава в кадре (.is-active).
   BUILD — чертёж «рендерится» сканером, телефон включается, вокруг
           макета всплывают плашки с глубиной за курсором.
   GROW  — линия графика рисуется с огненной головой и шлейфом,
           по пути загораются точки-вехи с колоннами, на финише бейдж.
   SCALE — живой маршрут лида: импульс бежит от рекламы до продаж,
           узлы вспыхивают по прибытии, CRM «+1 лід», продажа — салют.
   ====================================================================== */
.rv-w2{--w2-ease:cubic-bezier(.16,1,.3,1);--w2-spring:cubic-bezier(.34,1.56,.64,1)}
.rv-w2 .rv-wwd-panel:not(.is-active) .rv-w2-loop,
.rv-w2 .rv-wwd-panel:not(.is-active) .rv-w2-loop::before,
.rv-w2 .rv-wwd-panel:not(.is-active) .rv-w2-loop::after{animation-play-state:paused!important}

/* ── 0. Световой шов: при входе в главу по верхней кромке проходит луч ── */
.rv-w2-seam{position:absolute;left:0;right:0;top:0;height:1px;z-index:9;pointer-events:none;overflow:hidden}
.rv-w2-seam::before{content:"";position:absolute;top:0;left:0;width:38%;height:100%;opacity:0;
  background:linear-gradient(90deg,transparent,rgba(245,197,66,.95),transparent);transform:translate3d(-110%,0,0)}
.rv-w2 .rv-wow > .rv-w2-seam::before{animation:rvW2Seam 1.7s var(--w2-ease) .05s both}
@keyframes rvW2Seam{0%{transform:translate3d(-110%,0,0);opacity:0}12%{opacity:1}100%{transform:translate3d(290%,0,0);opacity:0}}

/* ── 1. BUILD ─────────────────────────────────────────────────────────── */
/* чертёж: золотая сетка + каркасы блоков, «стирается» сканером сверху вниз */
.rv-w2-bp{position:absolute;inset:0;z-index:5;pointer-events:none;
  background:linear-gradient(rgba(245,197,66,.11) 1px,transparent 1px) 0 0/22px 22px,
             linear-gradient(90deg,rgba(245,197,66,.11) 1px,transparent 1px) 0 0/22px 22px,
             linear-gradient(145deg,rgba(9,12,52,.97),rgba(4,5,30,.98))}
.rv-w2-bp i{position:absolute;border:1px dashed rgba(245,197,66,.55);border-radius:6px}
.rv-w2-bp i::after{content:attr(data-l);position:absolute;top:5px;left:7px;color:rgba(245,197,66,.8);
  font:600 7px/1 var(--font-mono,'JetBrains Mono',monospace);letter-spacing:.14em}
.rv-w2-bp i:nth-child(1){top:5%;left:4%;right:4%;height:9%}
.rv-w2-bp i:nth-child(2){top:24%;left:4%;width:56%;height:44%}
.rv-w2-bp i:nth-child(3){right:5%;bottom:7%;width:40%;height:24%}
.rv-w2 .rv-wow .rv-w2-bp{animation:rvW2Unclip 1.25s cubic-bezier(.55,0,.3,1) .32s both}
@keyframes rvW2Unclip{from{clip-path:inset(0 0 0 0)}to{clip-path:inset(100% 0 0 0);visibility:hidden}}

/* лазер-сканер, который «рендерит» экран */
.rv-w2-scan{position:absolute;left:0;right:0;top:0;height:100%;z-index:6;pointer-events:none;opacity:0;transform:translate3d(0,-2%,0)}
.rv-w2-scan::before{content:"";position:absolute;left:0;right:0;top:0;height:2px;
  background:linear-gradient(90deg,transparent,#ffe9a6 18%,#F5C542 50%,#ffe9a6 82%,transparent);
  box-shadow:0 0 18px 3px rgba(245,197,66,.55)}
.rv-w2-scan::after{content:"";position:absolute;left:0;right:0;top:-80px;height:80px;
  background:linear-gradient(180deg,transparent,rgba(245,197,66,.16))}
.rv-w2 .rv-wow .rv-w2-scan{animation:rvW2Scan 1.25s cubic-bezier(.55,0,.3,1) .32s both}
@keyframes rvW2Scan{0%{transform:translate3d(0,-2%,0);opacity:0}6%{opacity:1}92%{opacity:1}100%{transform:translate3d(0,100%,0);opacity:0}}

/* кнопка START в макете: кольцо «клика» */
.rv-w2 .rv-build-browser__nav b{position:relative}
.rv-w2-ping{position:absolute;inset:-1px;border-radius:999px;border:1px solid rgba(245,197,66,.9);pointer-events:none;opacity:0}
.rv-w2 .rv-wow-done .rv-w2-ping{animation:rvW2Ping 3s cubic-bezier(.2,.7,.2,1) .2s infinite}
@keyframes rvW2Ping{0%{opacity:.95;transform:scale(1)}55%,100%{opacity:0;transform:scale(1.55,2.1)}}

/* телефон «включается» при посадке */
.rv-w2-flash{position:absolute;inset:0;z-index:6;border-radius:inherit;pointer-events:none;opacity:0;
  background:radial-gradient(120% 70% at 50% 34%,rgba(255,255,255,.6),rgba(160,170,255,.2) 45%,transparent 75%)}
.rv-w2 .rv-wow .rv-w2-flash{animation:rvW2Flash 1.1s ease-out 1.3s both}
@keyframes rvW2Flash{0%{opacity:0}16%{opacity:1}100%{opacity:0}}

/* плавающие плашки вокруг макета: глубина за курсором (ПК) + парение */
.rv-w2-chip{position:absolute;z-index:7;pointer-events:none;opacity:0;scale:.5;
  translate:calc(var(--wx,0) * var(--d,20px)) calc(var(--wy,0) * var(--d,20px));
  transition:opacity .6s ease var(--cd,1.3s),scale 1.1s var(--w2-spring) var(--cd,1.3s),translate .9s var(--w2-ease)}
.rv-w2 .rv-wow .rv-w2-chip{opacity:1;scale:1}
.rv-w2-chip__in{display:flex;align-items:center;gap:8px;padding:9px 14px;border:1px solid rgba(245,197,66,.34);border-radius:999px;
  background:linear-gradient(145deg,rgba(30,35,88,.96),rgba(9,12,38,.96));
  box-shadow:0 18px 40px rgba(0,0,20,.5),inset 0 1px 0 rgba(255,255,255,.09);
  color:#f5f4ef;font:600 10px/1 var(--font-mono,'JetBrains Mono',monospace);letter-spacing:.1em;text-transform:uppercase;white-space:nowrap;
  animation:rvW2Float var(--fd,6s) ease-in-out infinite alternate}
.rv-w2-chip__in i{position:relative;width:7px;height:7px;border-radius:50%;background:var(--c,#F5C542);box-shadow:0 0 10px var(--c,#F5C542)}
.rv-w2-chip__in i::after{content:"";position:absolute;inset:-4px;border-radius:50%;border:1px solid var(--c,#F5C542);opacity:0;animation:rvW2Dot 2.2s ease-out infinite}
@keyframes rvW2Float{from{transform:translate3d(0,-7px,0) rotate(-2deg)}to{transform:translate3d(0,7px,0) rotate(1.5deg)}}
@keyframes rvW2Dot{0%{opacity:.9;transform:scale(.6)}70%,100%{opacity:0;transform:scale(1.8)}}
.rv-w2-chip--a{left:3%;top:clamp(2px,1.2vw,20px);--d:34px;--cd:1.25s;--fd:5.6s}
.rv-w2-chip--b{right:16%;top:clamp(0px,.4vw,8px);--d:-26px;--cd:1.45s;--fd:6.4s;--c:#8c91ff}
.rv-w2-chip--c{left:-1%;bottom:7%;--d:22px;--cd:1.65s;--fd:7.2s;--c:#35c99e}
@media (max-width:700px){
  .rv-w2-chip__in{gap:6px;padding:6px 9px;font-size:7.5px}
  .rv-w2-chip__in i{width:5px;height:5px}
  .rv-w2-chip--a{left:auto;right:36%;top:-6px}
  .rv-w2-chip--b{right:0;top:auto;bottom:-6px}
  .rv-w2-chip--c{left:2%;bottom:-6px}
  .rv-w2-bp i::after{font-size:5px;top:3px;left:4px}
}

/* ── 2. GROW ──────────────────────────────────────────────────────────── */
/* линия рисуется ровнее и дольше — под неё идёт голова-комета */
.rv-w2 .rv-wwd-panel--grow.is-visible .rv-grow-chart__line{animation-duration:1.9s;animation-delay:.35s;animation-timing-function:cubic-bezier(.55,.06,.25,1)}
.rv-w2 .rv-wwd-panel--grow.is-visible .rv-grow-chart__point{animation-delay:2.15s}
.rv-w2-comet,.rv-w2-trail,.rv-w2-mile,.rv-w2-badge{pointer-events:none}
.rv-w2-trail{fill:none;stroke-linecap:round;opacity:0}
.rv-w2-trail--wide{stroke:rgba(245,197,66,.28);stroke-width:12}
.rv-w2-trail--core{stroke:#fff3c4;stroke-width:3.5}
.rv-w2-mile__dot{fill:#0b0e33;stroke:#F5C542;stroke-width:2.5}
.rv-w2-mile__ring{fill:none;stroke:#F5C542;stroke-width:1.5;opacity:0}
.rv-w2-mile__pillar{stroke:rgba(245,197,66,.32);stroke-width:1.5;stroke-dasharray:3 5}
.rv-w2-mile__label{fill:#f5f4ef;font:700 15px/1 var(--font-mono,'JetBrains Mono',monospace);letter-spacing:.04em;opacity:0}
.rv-w2-badge rect{fill:#F5C542}
.rv-w2-badge text{fill:#07082a;font:800 17px/1 var(--font-display,'Manrope',Arial,sans-serif);letter-spacing:-.01em}
@media (max-width:767px){.rv-w2-mile__label{display:none}}
.rv-w2 .rv-grow-metrics strong{transition:text-shadow .8s ease}
.rv-w2 .rv-grow-metrics strong.is-counted{text-shadow:0 0 30px rgba(245,197,66,.55)}

/* ── 3. SCALE ─────────────────────────────────────────────────────────── */
.rv-w2-orb{position:absolute;left:0;top:0;z-index:1;width:14px;height:14px;margin:-7px 0 0 -7px;border-radius:50%;pointer-events:none;opacity:0;
  background:radial-gradient(circle,#fff 0 22%,#ffe39a 38%,rgba(245,197,66,.55) 58%,rgba(245,197,66,0) 72%);will-change:transform,opacity}
.rv-w2-orb::before{content:"";position:absolute;inset:-14px;border-radius:50%;background:radial-gradient(circle,rgba(245,197,66,.42),rgba(245,197,66,0) 68%)}
.rv-w2-orb--ghost{width:10px;height:10px;margin:-5px 0 0 -5px}
.rv-w2-orb--ghost::before{inset:-6px;opacity:.5}
.rv-w2-orb--ghost.rv-w2-orb--g2{width:7px;height:7px;margin:-3.5px 0 0 -3.5px}
.rv-w2-orb--blue{background:radial-gradient(circle,#fff 0 22%,#c9ccff 38%,rgba(116,123,255,.6) 58%,rgba(116,123,255,0) 72%)}
.rv-w2-orb--blue::before{background:radial-gradient(circle,rgba(116,123,255,.45),rgba(116,123,255,0) 68%)}
.rv-w2-orb--green{background:radial-gradient(circle,#fff 0 22%,#b8f3df 38%,rgba(53,201,158,.6) 58%,rgba(53,201,158,0) 72%)}
.rv-w2-orb--green::before{background:radial-gradient(circle,rgba(53,201,158,.45),rgba(53,201,158,0) 68%)}
.rv-w2-hit{position:absolute;left:0;top:0;z-index:5;pointer-events:none;opacity:0;border:1.5px solid var(--hc,#F5C542);box-shadow:0 0 18px var(--hc,#F5C542)}
.rv-w2-glow{position:absolute;left:0;top:0;z-index:1;pointer-events:none;opacity:0;border-radius:50%;
  background:radial-gradient(closest-side,var(--hc,#F5C542),transparent);mix-blend-mode:screen}
.rv-w2-pop{position:absolute;left:0;top:0;z-index:6;pointer-events:none;opacity:0;padding:5px 9px;border-radius:999px;white-space:nowrap;
  background:#F5C542;color:#07082a;font:800 10px/1 var(--font-mono,'JetBrains Mono',monospace);letter-spacing:.08em;text-transform:uppercase;
  box-shadow:0 10px 26px rgba(245,197,66,.35)}
.rv-w2-pop--green{background:#35c99e;box-shadow:0 10px 26px rgba(53,201,158,.38)}
.rv-w2-spark{position:absolute;left:0;top:0;z-index:6;width:7px;height:7px;margin:-3.5px 0 0 -3.5px;border-radius:50%;pointer-events:none;opacity:0;background:var(--sc,#F5C542);box-shadow:0 0 8px var(--sc,#F5C542)}
.rv-w2 .rv-scale-node.rv-w2-on{border-color:var(--hc,rgba(245,197,66,.9))!important}
@media (max-width:600px){.rv-w2-orb{z-index:2}.rv-w2-pop{font-size:8.5px;padding:4px 8px}}

/* ── Блики слева направо отключены (по просьбе): общий блик по карточкам
   BUILD / GROW / SCALE (v1) и блик по графику GROW (style.css) ── */
.rv-wwd .rv-wow-sweep{display:none!important}
.rv-wwd .rv-grow-chart::after{animation:none!important;opacity:0!important}

/* ── GROW, телефон: вторая (синяя) точка бежит по графику, как на ПК ── */
@media (max-width:600px){
  .rv-wwd .rv-grow-chart__runner--two{display:inline;stroke-width:4}
  .rv-wwd .rv-grow-chart__runner--one{stroke-width:3}
}

@media (prefers-reduced-motion:reduce){
  .rv-w2-seam,.rv-w2-bp,.rv-w2-scan,.rv-w2-ping,.rv-w2-flash,.rv-w2-glare,.rv-w2-chip,.rv-w2-orb,.rv-w2-hit,.rv-w2-glow,.rv-w2-pop,.rv-w2-spark,
  .rv-w2-comet,.rv-w2-trail,.rv-w2-mile,.rv-w2-badge{display:none!important}
}
</style>
<script id="rv-wow2-js">
(function(){
  'use strict';
  var root = document.getElementById('rv-wwd');
  if (!root || !('IntersectionObserver' in window) || !('MutationObserver' in window)) return;
  if (window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches) return;
  var SVGNS = 'http://www.w3.org/2000/svg';
  var UA = /^uk|^ua/i.test(document.documentElement.lang || '') || root.classList.contains('rv-wwd--ua');
  var touch = !(window.matchMedia && window.matchMedia('(hover: hover) and (pointer: fine)').matches);

  function el(tag, cls, host, html){ var n = document.createElement(tag); if (cls) n.className = cls; n.setAttribute('aria-hidden','true'); if (html) n.innerHTML = html; if (host) host.appendChild(n); return n; }
  function sv(tag, attrs, host){ var n = document.createElementNS(SVGNS, tag); for (var k in attrs) n.setAttribute(k, attrs[k]); if (host) host.appendChild(n); return n; }
  /* срабатывает, когда у элемента появляется класс (или сразу, если уже есть) */
  function onClass(node, cls, fn){
    if (!node) return;
    if (node.classList.contains(cls)) { fn(); return; }
    var mo = new MutationObserver(function(){ if (node.classList.contains(cls)) { mo.disconnect(); fn(); } });
    mo.observe(node, { attributes: true, attributeFilter: ['class'] });
  }
  var canAnimate = typeof Element.prototype.animate === 'function';

  var pBuild = root.querySelector('.rv-wwd-panel--build');
  var pGrow  = root.querySelector('.rv-wwd-panel--grow');
  var pScale = root.querySelector('.rv-wwd-panel--scale');
  [pBuild, pGrow, pScale].forEach(function(p){ if (p) el('span', 'rv-w2-seam', p); });

  /* ───────────── BUILD ───────────── */
  (function(){
    if (!pBuild) return;
    var screen = pBuild.querySelector('.rv-build-browser__screen');
    if (screen) {
      el('div', 'rv-w2-bp', screen, '<i data-l="NAV"></i><i data-l="HERO"></i><i data-l="GRID"></i>');
      el('div', 'rv-w2-scan', screen);
    }
    var start = pBuild.querySelector('.rv-build-browser__nav b');
    if (start) el('span', 'rv-w2-ping', start);
    var phone = pBuild.querySelector('.rv-build-phone');
    if (phone) el('span', 'rv-w2-flash', phone);
    var visual = pBuild.querySelector('.rv-build-visual');
    if (visual) {
      var chips = [
        ['a', 'UX / UI'],
        ['b', 'Mobile-first'],
        ['c', UA ? 'Запуск' : 'Launch']
      ];
      chips.forEach(function(c){
        el('span', 'rv-w2-chip rv-w2-chip--' + c[0], visual, '<span class="rv-w2-chip__in rv-w2-loop"><i></i>' + c[1] + '</span>');
      });
    }
    function dropBp(){ var bp = pBuild.querySelector('.rv-w2-bp'), sc = pBuild.querySelector('.rv-w2-scan'); if (bp) bp.remove(); if (sc) sc.remove(); }
    /* страховка: если сцена v1 не стартовала, чертёж не должен закрывать макет */
    onClass(pBuild, 'is-visible', function(){ setTimeout(function(){ if (!pBuild.classList.contains('rv-wow')) dropBp(); }, 4500); });
    /* после завершения чертёж больше не нужен */
    onClass(pBuild, 'rv-wow', function(){
      setTimeout(dropBp, 1800);
    });
  })();

  /* ───────────── GROW ───────────── */
  (function(){
    if (!pGrow) return;
    var svg = pGrow.querySelector('.rv-grow-chart svg');
    var line = pGrow.querySelector('.rv-grow-chart__line');
    if (!svg || !line) return;
    var D = line.getAttribute('d');
    var DUR = 1.9, DELAY = 0.35, SPL = '.55 .06 .25 1';

    /* кривая анимации линии: прогресс → время (для синхронизации вех) */
    function bez(a, b, t){ return 3*a*t*(1-t)*(1-t) + 3*b*t*t*(1-t) + t*t*t; }
    function timeAt(f){
      var lo = 0, hi = 1;
      for (var i = 0; i < 30; i++) { var m = (lo + hi) / 2; if (bez(.06, 1, m) < f) lo = m; else hi = m; }
      return bez(.55, .25, (lo + hi) / 2);
    }

    /* хвост-шлейф: два штриха, которые едут вместе с краем линии */
    function trail(cls, len){
      var t = sv('path', { 'class': 'rv-w2-trail ' + cls, d: D, pathLength: '1', 'stroke-dasharray': len + ' 3', 'stroke-dashoffset': len }, svg);
      sv('animate', { attributeName: 'stroke-dashoffset', values: len + ';' + (len - 1), dur: DUR + 's', begin: 'indefinite', fill: 'freeze', calcMode: 'spline', keyTimes: '0;1', keySplines: SPL }, t);
      sv('animate', { attributeName: 'opacity', values: '0;1;1;0', keyTimes: '0;.05;.88;1', dur: DUR + 's', begin: 'indefinite', fill: 'freeze' }, t);
      return t;
    }
    var trails = [trail('rv-w2-trail--wide', .2), trail('rv-w2-trail--core', .07)];

    /* голова-комета */
    var defs = svg.querySelector('defs') || sv('defs', {}, svg);
    var rg = sv('radialGradient', { id: 'rvW2Halo' }, defs);
    sv('stop', { offset: '0', 'stop-color': '#fff6d2', 'stop-opacity': '1' }, rg);
    sv('stop', { offset: '.35', 'stop-color': '#F5C542', 'stop-opacity': '.55' }, rg);
    sv('stop', { offset: '1', 'stop-color': '#F5C542', 'stop-opacity': '0' }, rg);
    var comet = sv('g', { 'class': 'rv-w2-comet', opacity: '0' }, svg);
    sv('circle', { r: '22', fill: 'url(#rvW2Halo)' }, comet);
    sv('circle', { r: '5', fill: '#fffbe9' }, comet);
    sv('animateMotion', { path: D, dur: DUR + 's', begin: 'indefinite', fill: 'freeze', calcMode: 'spline', keyPoints: '0;1', keyTimes: '0;1', keySplines: SPL }, comet);
    sv('animate', { attributeName: 'opacity', values: '0;1;1;0', keyTimes: '0;.04;.9;1', dur: DUR + 's', begin: 'indefinite', fill: 'freeze' }, comet);

    /* точки-вехи вдоль линии */
    var probe = sv('path', { d: D, fill: 'none', stroke: 'none' }, svg);
    var total = probe.getTotalLength ? probe.getTotalLength() : 0;
    var metric = pGrow.querySelector('.rv-grow-metrics strong');
    var finalTxt = metric ? (metric.textContent || '').trim() : '';
    var m = finalTxt.replace('−', '-').match(/[-+]?\d+(?:[.,]\d+)?/);
    var finalVal = m ? Math.abs(parseFloat(m[0].replace(',', '.'))) : 0;
    var pct = /%/.test(finalTxt);
    var miles = [];
    if (total) {
      [.24, .46, .66, .84].forEach(function(f){
        var pt = probe.getPointAtLength(total * f);
        var g = sv('g', { 'class': 'rv-w2-mile' }, svg);
        var pil = sv('line', { 'class': 'rv-w2-mile__pillar', x1: pt.x, x2: pt.x, y1: 262, y2: 262 }, g);
        sv('animate', { attributeName: 'y2', values: '262;' + (pt.y + 8).toFixed(1), dur: '.7s', begin: 'indefinite', fill: 'freeze', calcMode: 'spline', keyTimes: '0;1', keySplines: '.16 1 .3 1' }, pil);
        var ring = sv('circle', { 'class': 'rv-w2-mile__ring', cx: pt.x, cy: pt.y, r: 5 }, g);
        sv('animate', { attributeName: 'r', values: '5;24', dur: '.9s', begin: 'indefinite', fill: 'freeze' }, ring);
        sv('animate', { attributeName: 'opacity', values: '.9;0', dur: '.9s', begin: 'indefinite', fill: 'freeze' }, ring);
        var dot = sv('circle', { 'class': 'rv-w2-mile__dot', cx: pt.x, cy: pt.y, r: 0 }, g);
        sv('animate', { attributeName: 'r', values: '0;8;5', keyTimes: '0;.55;1', dur: '.55s', begin: 'indefinite', fill: 'freeze' }, dot);
        if (finalVal && pct) {
          var v = Math.max(1, Math.round(finalVal * (229 - pt.y) / (229 - 24) / 5) * 5);
          var lab = sv('text', { 'class': 'rv-w2-mile__label', x: pt.x, y: (pt.y - 18).toFixed(1), 'text-anchor': 'middle' }, g);
          lab.textContent = '+' + v + '%';
          sv('animate', { attributeName: 'opacity', values: '0;.85', dur: '.5s', begin: 'indefinite', fill: 'freeze' }, lab);
        }
        miles.push({ t: timeAt(f), g: g });
      });
    }
    probe.remove();

    /* финиш: бейдж с итоговым результатом */
    var badge = null;
    if (finalTxt) {
      badge = sv('g', { 'class': 'rv-w2-badge', transform: 'translate(560 6)', opacity: '0' }, svg);
      var inner = sv('g', {}, badge);
      var w = 40 + finalTxt.length * 11;
      sv('rect', { x: -w / 2, y: -17, width: w, height: 34, rx: 17 }, inner);
      var tx = sv('text', { x: 0, y: 6, 'text-anchor': 'middle' }, inner);
      tx.textContent = '▲ ' + finalTxt;
      sv('animateTransform', { attributeName: 'transform', type: 'scale', values: '.4;1.12;1', keyTimes: '0;.6;1', dur: '.6s', begin: 'indefinite', fill: 'freeze' }, inner);
      sv('animate', { attributeName: 'opacity', values: '0;1', dur: '.3s', begin: 'indefinite', fill: 'freeze' }, badge);
      sv('animateTransform', { attributeName: 'transform', type: 'translate', values: '560 6;560 0;560 6', dur: '3.4s', begin: 'indefinite', repeatCount: 'indefinite', additive: 'replace' }, badge);
    }

    function fire(node){ var a = node.querySelectorAll('animate,animateMotion,animateTransform'); for (var i = 0; i < a.length; i++) { try { a[i].beginElement(); } catch (e) {} } }
    /* старт строго по факту начала рисования линии (animationstart),
       чтобы голова кометы шла ровно по краю линии на любом устройстве */
    var done = false;
    function go(){
      if (done) return; done = true;
      fire(comet); trails.forEach(fire);
      miles.forEach(function(mi){ setTimeout(function(){ fire(mi.g); }, mi.t * DUR * 1000); });
      if (badge) setTimeout(function(){ fire(badge); }, DUR * 1000 + 120);
    }
    line.addEventListener('animationstart', function(e){ if (e.animationName === 'rvGrowDraw') go(); });
    onClass(pGrow, 'is-visible', function(){ setTimeout(go, (DELAY + 2.4) * 1000); });
  })();

  /* ───────────── SCALE ───────────── */
  (function(){
    if (!pScale || !canAnimate) return;
    var flow = pScale.querySelector('.rv-scale-flow');
    if (!flow) return;
    var linesSvg = flow.querySelector('.rv-scale-flow__lines');
    var paths = linesSvg ? linesSvg.querySelectorAll(':scope > path') : [];
    var N = {
      ads: flow.querySelector('.rv-scale-node--ads'), meta: flow.querySelector('.rv-scale-node--meta'), seo: flow.querySelector('.rv-scale-node--seo'),
      web: flow.querySelector('.rv-scale-node--website'), crm: flow.querySelector('.rv-scale-node--crm'),
      ai: flow.querySelector('.rv-scale-node--ai'), auto: flow.querySelector('.rv-scale-node--auto'), sales: flow.querySelector('.rv-scale-node--sales')
    };
    if (!N.web || !N.crm || !N.ai || !N.auto || !N.sales) return;
    var COL = { ads: '#F5C542', meta: '#8c91ff', seo: '#35c99e', web: '#8c91ff', crm: '#F5C542', ai: '#8c91ff', auto: '#35c99e', sales: '#35c99e' };

    function orbSet(mod){
      var s = { main: el('span', 'rv-w2-orb' + (mod ? ' rv-w2-orb--' + mod : ''), flow), ghosts: [] };
      var n = touch ? 1 : 2;
      for (var i = 0; i < n; i++) s.ghosts.push(el('span', 'rv-w2-orb rv-w2-orb--ghost rv-w2-orb--g' + (i + 1) + (mod ? ' rv-w2-orb--' + mod : ''), flow));
      return s;
    }
    var orbA = orbSet(''), orbB = orbSet('green');
    var fx = {};
    ['ads','meta','seo','web','crm','ai','auto','sales'].forEach(function(k){
      if (!N[k]) return;
      fx[k] = { hit: el('span', 'rv-w2-hit', flow), glow: el('span', 'rv-w2-glow', flow) };
      fx[k].hit.style.setProperty('--hc', COL[k]); fx[k].glow.style.setProperty('--hc', COL[k]);
    });
    var popLead = el('span', 'rv-w2-pop', flow); popLead.textContent = UA ? '+1 лід' : '+1 lead';
    var popSale = el('span', 'rv-w2-pop rv-w2-pop--green', flow); popSale.textContent = UA ? '+1 продаж ✓' : '+1 sale ✓';
    var sparks = [];
    var SC = ['#F5C542', '#35c99e', '#fff6d2', '#8c91ff'];
    for (var s = 0; s < (touch ? 8 : 12); s++) { var sp = el('span', 'rv-w2-spark', flow); sp.style.setProperty('--sc', SC[s % SC.length]); sparks.push(sp); }

    var live = [], gen = 0, running = false, inView = false, armed = false, cycle = 0;
    function track(a){ live.push(a); a.onfinish = a.oncancel = function(){ var i = live.indexOf(a); if (i > -1) live.splice(i, 1); }; return a; }
    function center(n){ return { x: n.offsetLeft, y: n.offsetTop, w: n.offsetWidth, h: n.offsetHeight }; }
    function visible(n){ return n && n.offsetParent !== null && getComputedStyle(n).display !== 'none'; }
    var useSvg = function(){ return linesSvg && paths.length >= 8 && getComputedStyle(linesSvg).display !== 'none' && visible(N.ads); };

    /* точки маршрута: на ПК — по настоящим линиям схемы, на телефоне — по узлам */
    function samplePath(p, steps){
      var W = flow.clientWidth, H = flow.clientHeight, vb = linesSvg.viewBox.baseVal;
      var sx = W / (vb && vb.width ? vb.width : 720), sy = H / (vb && vb.height ? vb.height : 560);
      var clone = sv('path', { d: p.getAttribute('d'), fill: 'none', stroke: 'none' }, linesSvg);
      var L = clone.getTotalLength(), out = [];
      for (var i = 0; i <= steps; i++) { var q = clone.getPointAtLength(L * i / steps); out.push({ x: q.x * sx, y: q.y * sy }); }
      clone.remove(); return out;
    }
    function elbow(a, b){ var my = (a.y + b.y) / 2; return [a, { x: a.x, y: my }, { x: b.x, y: my }, b]; }
    function route(srcKey){
      var c = { web: center(N.web), crm: center(N.crm), ai: center(N.ai), auto: center(N.auto), sales: center(N.sales) };
      if (useSvg()) {
        var si = { ads: 0, meta: 1, seo: 2 }[srcKey];
        return { src: srcKey, s1: samplePath(paths[si], 24), s2: samplePath(paths[3], 10),
          s3a: samplePath(paths[4], 16), s3b: samplePath(paths[5], 16), s4a: samplePath(paths[6], 14), s4b: samplePath(paths[7], 14) };
      }
      var top = { x: flow.clientWidth / 2, y: flow.clientHeight * .13 + 14 };
      return { src: null, s1: [top, { x: c.web.x, y: c.web.y }], s2: [{ x: c.web.x, y: c.web.y }, { x: c.crm.x, y: c.crm.y }],
        s3a: elbow(c.crm, c.ai), s3b: elbow(c.crm, c.auto), s4a: elbow(c.ai, c.sales), s4b: elbow(c.auto, c.sales) };
    }
    /* равномерная скорость по ломаной: смещения кадров = доля пройденного пути */
    function frames(pts, sc){
      var d = [0];
      for (var i = 1; i < pts.length; i++) d.push(d[i - 1] + Math.hypot(pts[i].x - pts[i - 1].x, pts[i].y - pts[i - 1].y));
      var L = d[d.length - 1] || 1, kf = [];
      for (var j = 0; j < pts.length; j++) {
        var o = d[j] / L, op = Math.min(1, o / .1, (1 - o) / .12);
        kf.push({ offset: o, opacity: Math.max(0, op), transform: 'translate3d(' + pts[j].x.toFixed(1) + 'px,' + pts[j].y.toFixed(1) + 'px,0) scale(' + sc + ')' });
      }
      return { kf: kf, len: L };
    }
    function travel(set, pts){
      var f = frames(pts, 1), dur = Math.max(420, Math.min(1150, f.len / (touch ? .32 : .5)));
      var a = track(set.main.animate(f.kf, { duration: dur, easing: 'cubic-bezier(.45,.05,.35,1)', fill: 'forwards' }));
      set.ghosts.forEach(function(g, i){ track(g.animate(frames(pts, .85 - i * .15).kf, { duration: dur, delay: 55 * (i + 1), easing: 'cubic-bezier(.45,.05,.35,1)', fill: 'forwards' })); });
      return new Promise(function(res){ a.addEventListener('finish', function(){ res(); }); });
    }
    function wait(ms){ return new Promise(function(res){ setTimeout(res, ms); }); }
    function place(node, c, w, h){ node.style.width = w + 'px'; node.style.height = h + 'px'; node.style.left = (c.x - w / 2) + 'px'; node.style.top = (c.y - h / 2) + 'px'; }
    function hit(key){
      var n = N[key], f = fx[key]; if (!n || !f || !visible(n)) return;
      var c = center(n), br = getComputedStyle(n).borderRadius;
      place(f.hit, c, c.w, c.h); f.hit.style.borderRadius = br;
      place(f.glow, c, c.w * 1.9, c.h * 2.6);
      track(f.hit.animate([{ opacity: .95, transform: 'scale(1)' }, { opacity: 0, transform: 'scale(1.32,1.7)' }], { duration: 800, easing: 'cubic-bezier(.2,.7,.2,1)' }));
      track(f.glow.animate([{ opacity: 0, transform: 'scale(.6)' }, { opacity: .55, transform: 'scale(1)', offset: .3 }, { opacity: 0, transform: 'scale(1.15)' }], { duration: 1000, easing: 'ease-out' }));
      n.style.setProperty('--hc', COL[key]); n.classList.add('rv-w2-on');
      setTimeout(function(){ n.classList.remove('rv-w2-on'); }, 650);
    }
    function pop(p, c, rise){
      p.style.left = c.x + 'px'; p.style.top = (c.y - c.h / 2) + 'px';
      track(p.animate([
        { opacity: 0, transform: 'translate(-50%,-30%) scale(.6)' },
        { opacity: 1, transform: 'translate(-50%,-110%) scale(1.06)', offset: .22 },
        { opacity: 1, transform: 'translate(-50%,-130%) scale(1)', offset: .7 },
        { opacity: 0, transform: 'translate(-50%,-' + (130 + rise) + '%) scale(.96)' }
      ], { duration: 1500, easing: 'cubic-bezier(.16,1,.3,1)' }));
    }
    function burst(c){
      sparks.forEach(function(sp, i){
        var ang = (i / sparks.length) * Math.PI * 2 + Math.random() * .4, r = (touch ? 34 : 46) + Math.random() * 26;
        var x = Math.cos(ang) * r, y = Math.sin(ang) * r * .7;
        sp.style.left = c.x + 'px'; sp.style.top = c.y + 'px';
        track(sp.animate([
          { opacity: 1, transform: 'translate3d(0,0,0) scale(1)' },
          { opacity: 0, transform: 'translate3d(' + x.toFixed(1) + 'px,' + y.toFixed(1) + 'px,0) scale(.2)' }
        ], { duration: 780 + Math.random() * 300, easing: 'cubic-bezier(.16,1,.3,1)' }));
      });
    }

    function journey(){
      var my = ++gen;
      var srcs = ['ads', 'meta', 'seo'], src = srcs[cycle++ % 3];
      var R = route(src);
      var alive = function(){ return running && my === gen; };
      if (R.src) hit(R.src);
      travel(orbA, R.s1).then(function(){ if (!alive()) return; hit('web'); return wait(160); })
      .then(function(){ if (!alive()) return; return travel(orbA, R.s2); })
      .then(function(){ if (!alive()) return; hit('crm'); pop(popLead, center(N.crm), 40); return wait(380); })
      .then(function(){ if (!alive()) return; return Promise.all([travel(orbA, R.s3a), travel(orbB, R.s3b)]); })
      .then(function(){ if (!alive()) return; hit('ai'); hit('auto'); return wait(220); })
      .then(function(){ if (!alive()) return; return Promise.all([travel(orbA, R.s4a), travel(orbB, R.s4b)]); })
      .then(function(){ if (!alive()) return; var c = center(N.sales); hit('sales'); burst(c); pop(popSale, c, 50); return wait(touch ? 1700 : 1300); })
      .then(function(){ if (alive()) journey(); });
    }
    function startLoop(delay){
      if (running) return; running = true;
      var my = ++gen;
      setTimeout(function(){ if (running && my === gen) journey(); }, delay);
    }
    function stopLoop(){
      running = false; gen++;
      live.slice().forEach(function(a){ try { a.cancel(); } catch (e) {} });
      live.length = 0;
      for (var k in N) if (N[k]) N[k].classList.remove('rv-w2-on');
    }
    function sync(){ if (armed && inView && !document.hidden) startLoop(cycle ? 500 : 2100); else stopLoop(); }
    onClass(pScale, 'rv-wow', function(){ armed = true; sync(); });
    new IntersectionObserver(function(es){ inView = es[0].isIntersecting; sync(); }, { threshold: .2 }).observe(flow);
    document.addEventListener('visibilitychange', sync);
    var rz = 0;
    window.addEventListener('resize', function(){ clearTimeout(rz); rz = setTimeout(function(){ if (running) { stopLoop(); sync(); } }, 250); }, { passive: true });
  })();

  root.classList.add('rv-w2');
})();
</script>
<?php
/* ======================================================================
   REVIVE CHAPTER DOCK v1.0 — нижняя панель глав BUILD / GROW / SCALE.
   Идея из devils.inc (CAPITAL / APPS / GATE): у каждой главы свой цвет,
   активная вкладка заливается им, тонкая линия сверху показывает путь
   по всей истории. Без JS панели нет, якоря #build/#grow/#scale работают
   как обычно. Цвета глав — переменные --rv-ch-* в конце style.css.
   ====================================================================== */
$rv_dock_en = ! function_exists('pll_current_language') || pll_current_language() === 'en';
$rv_dock_chapters = array(
	array('id' => 'build', 'num' => '01', 'label' => 'Build', 'sub' => $rv_dock_en ? 'Websites & products'   : 'Сайти та продукти'),
	array('id' => 'grow',  'num' => '02', 'label' => 'Grow',  'sub' => $rv_dock_en ? 'Marketing & growth'    : 'Маркетинг і зростання'),
	array('id' => 'scale', 'num' => '03', 'label' => 'Scale', 'sub' => $rv_dock_en ? 'CRM, AI & automation'  : 'CRM, AI, автоматизація'),
);
?>
<nav class="rv-dock" id="rv-dock" aria-label="<?php echo $rv_dock_en ? 'Service chapters' : 'Розділи послуг'; ?>" data-rv-dock>
	<div class="rv-dock__frame">
		<span class="rv-dock__progress" aria-hidden="true"><i></i></span>
		<?php foreach ($rv_dock_chapters as $rv_chapter) : ?>
			<a class="rv-dock__tab rv-dock__tab--<?php echo esc_attr($rv_chapter['id']); ?>" href="#<?php echo esc_attr($rv_chapter['id']); ?>" data-dock-tab="<?php echo esc_attr($rv_chapter['id']); ?>">
				<span class="rv-dock__idx"><?php echo esc_html($rv_chapter['num']); ?></span>
				<span class="rv-dock__label"><b data-scramble><?php echo esc_html(strtoupper($rv_chapter['label'])); ?></b></span>
				<span class="rv-dock__sub"><?php echo esc_html($rv_chapter['sub']); ?></span>
				<span class="rv-dock__meter" aria-hidden="true"><i></i></span>
			</a>
		<?php endforeach; ?>
	</div>
</nav>
<script>
/* Появление глав BUILD / GROW / SCALE.
   Порог задаёт дирижёр (RVMotion), поэтому момент открытия совпадает с
   остальными блоками страницы. Панель выше вьюпорта раньше могла не набрать
   порог 14% и оставалась невидимой — теперь есть три страховки:
   стартовый показ всего, что уже в кадре, сторож дирижёра на каждом такте
   прокрутки и мгновенный показ при прыжке по якорю. */
(function () {
	var items = Array.prototype.slice.call(document.querySelectorAll('.rv-wwd-reveal'));
	if (!items.length) return;

	var motion = window.RVMotion || null;
	var reduced = window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;

	function show(item) {
		item.classList.remove('is-pending');
		item.classList.add('is-visible');
	}

	if (reduced || !('IntersectionObserver' in window)) {
		items.forEach(show);
		return;
	}

	var viewport = (window.RVVH ? window.RVVH() : window.innerHeight) || document.documentElement.clientHeight;
	items.forEach(function (item) {
		/* Всё, что уже в кадре или выше него (переход по якорю, F5 на середине
		   страницы, восстановление позиции прокрутки), открывается сразу. */
		if (item.getBoundingClientRect().top < viewport * 0.92) { show(item); return; }
		item.classList.add('is-pending');
	});

	var pending = items.filter(function (item) { return item.classList.contains('is-pending'); });
	if (!pending.length) return;

	var observer = new IntersectionObserver(function (entries) {
		entries.forEach(function (entry) {
			if (!entry.isIntersecting) return;
			show(entry.target);
			observer.unobserve(entry.target);
		});
	}, { threshold: 0.08, rootMargin: '0px 0px -10% 0px' });
	pending.forEach(function (item) { observer.observe(item); });

	/* Последний рубеж: если наблюдатель по любой причине не сработал,
	   через 2.5 с открываем всё, что физически видно. */
	window.setTimeout(function () {
		if (motion) { motion.sweep(false); return; }
		var vh = (window.RVVH ? window.RVVH() : window.innerHeight) || document.documentElement.clientHeight;
		pending.forEach(function (item) {
			if (item.getBoundingClientRect().top < vh) show(item);
		});
	}, 2500);
}());
</script>
<script>
/* REVIVE intro choreography v5.2
   WE BUILD / Створюємо assembles from a restrained particle field on every
   viewport; mobile uses a smaller particle budget for the same visual story. */
(function () {
	'use strict';
	var root = document.getElementById('rv-wwd');
	if (!root) return;
	var intro = root.querySelector('.rv-wwd__intro');
	var title = root.querySelector('.rv-wwd__intro-title');
	var build = root.querySelector('.rv-wwd__statement--build');
	if (!intro || !title || !build) return;

	var reduced = window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;
	if (reduced || !window.requestAnimationFrame) return;

	var canvas = document.createElement('canvas');
	canvas.className = 'rv-wwd-particle-canvas';
	canvas.setAttribute('aria-hidden', 'true');
	title.appendChild(canvas);
	intro.classList.add('rv-particles-enabled');

	var ctx = canvas.getContext && canvas.getContext('2d');
	if (!ctx) {
		intro.classList.remove('rv-particles-enabled');
		canvas.remove();
		return;
	}

	var particles = [];
	var played = false;
	var raf = 0;
	var cssWidth = 0;
	var cssHeight = 0;
	var dpr = 1;

	function rand(min, max) { return min + Math.random() * (max - min); }
	function easeOutQuart(t) { return 1 - Math.pow(1 - t, 4); }

	function syncCanvas() {
		if (played) return;
		var titleRect = title.getBoundingClientRect();
		var buildRect = build.getBoundingClientRect();
		if (!titleRect.width || !buildRect.width || !buildRect.height) return;
		var compact = window.innerWidth <= 700;
		var padX = compact ? 10 : 18;
		var padY = compact ? 8 : 12;
		cssWidth = Math.ceil(buildRect.width + padX * 2);
		cssHeight = Math.ceil(buildRect.height + padY * 2);
		canvas.style.left = Math.round(buildRect.left - titleRect.left - padX) + 'px';
		canvas.style.top = Math.round(buildRect.top - titleRect.top - padY) + 'px';
		canvas.style.width = cssWidth + 'px';
		canvas.style.height = cssHeight + 'px';
		dpr = Math.min(window.devicePixelRatio || 1, 2);
		canvas.width = Math.max(1, Math.round(cssWidth * dpr));
		canvas.height = Math.max(1, Math.round(cssHeight * dpr));
		ctx.setTransform(dpr, 0, 0, dpr, 0, 0);
	}

	function buildParticleMap() {
		syncCanvas();
		if (!cssWidth || !cssHeight) return false;

		var off = document.createElement('canvas');
		off.width = Math.max(1, Math.round(cssWidth * dpr));
		off.height = Math.max(1, Math.round(cssHeight * dpr));
		var octx = off.getContext('2d');
		if (!octx) return false;
		octx.setTransform(dpr, 0, 0, dpr, 0, 0);

		var cs = window.getComputedStyle(build);
		var fontSize = parseFloat(cs.fontSize) || 72;
		var weight = cs.fontWeight || '800';
		var family = cs.fontFamily || 'Arial, sans-serif';
		octx.font = weight + ' ' + fontSize + 'px ' + family;
		octx.textBaseline = 'alphabetic';
		octx.textAlign = 'left';
		if ('letterSpacing' in octx) octx.letterSpacing = cs.letterSpacing;
		octx.fillStyle = '#fff';
		var text = build.getAttribute('data-text') || (build.textContent || '').trim();
		var compact = window.innerWidth <= 700;
		var padX = compact ? 10 : 18;
		var padY = compact ? 8 : 12;
		/* Manrope (раньше Archivo): cap-height около .75em над базовой линией. This
		   baseline keeps the particle word aligned with the live DOM text. */
		var baseline = padY + fontSize * .79;
		octx.fillText(text, padX, baseline);

		var image;
		try { image = octx.getImageData(0, 0, off.width, off.height); }
		catch (e) { return false; }
		var data = image.data;
		/* The 39px mobile word needs denser sampling than the desktop title.
		   At step 7 it often produced fewer than 80 points and incorrectly
		   triggered the static fallback before the first visible frame. */
		var step = compact ? 5 : (window.innerWidth > 1500 ? 7 : 6);
		var points = [];
		for (var y = 0; y < off.height; y += Math.round(step * dpr)) {
			for (var x = 0; x < off.width; x += Math.round(step * dpr)) {
				var alpha = data[(y * off.width + x) * 4 + 3];
				if (alpha > 85) points.push({ x:x / dpr, y:y / dpr });
			}
		}
		/* Keep the effect premium and GPU/CPU friendly on large screens. */
		var maxParticles = compact ? 420 : 1050;
		if (points.length > maxParticles) {
			var stride = points.length / maxParticles;
			points = points.filter(function (_, i) { return Math.floor(i / stride) !== Math.floor((i - 1) / stride); }).slice(0, maxParticles);
		}
		particles = points.map(function (p, i) {
			var angle = rand(-Math.PI, Math.PI);
			var radius = rand(55, Math.min(230, cssWidth * .34));
			return {
				tx:p.x, ty:p.y,
				sx:p.x + Math.cos(angle) * radius + rand(-35, 35),
				sy:p.y + Math.sin(angle) * radius * .52 + rand(-28, 28),
				delay:rand(0, .24),
				phase:rand(0, Math.PI * 2),
				amp:rand(4, 18),
				size:rand(.75, 1.55),
				gold:(i % 11 === 0 || Math.random() < .055)
			};
		});
		return particles.length > (compact ? 24 : 80);
	}

	function drawFrame(now, start) {
		var elapsed = now - start;
		var duration = window.innerWidth <= 700 ? 980 : 1180;
		var raw = Math.min(1, elapsed / duration);
		ctx.clearRect(0, 0, cssWidth, cssHeight);

		/* One soft magnetic halo; no per-particle shadows, so the scene stays fast. */
		if (raw > .16 && raw < .94) {
			var haloX = cssWidth * (.22 + raw * .56);
			var halo = ctx.createRadialGradient(haloX, cssHeight * .52, 0, haloX, cssHeight * .52, Math.max(70, cssHeight * 1.15));
			halo.addColorStop(0, 'rgba(245,197,66,' + (0.055 * Math.sin(raw * Math.PI)).toFixed(3) + ')');
			halo.addColorStop(1, 'rgba(245,197,66,0)');
			ctx.fillStyle = halo;
			ctx.fillRect(0, 0, cssWidth, cssHeight);
		}

		particles.forEach(function (p) {
			var local = Math.max(0, Math.min(1, (raw - p.delay) / Math.max(.001, 1 - p.delay)));
			if (local <= 0) return;
			var e = easeOutQuart(local);
			var swirl = Math.sin((1 - e) * Math.PI * 1.35 + p.phase) * p.amp * (1 - e);
			var x = p.sx + (p.tx - p.sx) * e + swirl;
			var y = p.sy + (p.ty - p.sy) * e + Math.cos(p.phase + e * 4) * p.amp * .28 * (1 - e);
			var alpha = Math.min(1, local * 3.2) * (raw > .91 ? Math.max(0, (1 - raw) / .09) : 1);
			ctx.globalAlpha = alpha;
			ctx.fillStyle = p.gold ? '#F5C542' : '#F7F6F1';
			var s = p.size * (0.72 + e * .38);
			ctx.fillRect(x - s * .5, y - s * .5, s, s);
		});
		ctx.globalAlpha = 1;

		if (raw >= .72 && !intro.classList.contains('rv-build-text-revealing')) {
			intro.classList.add('rv-build-text-revealing');
		}
		if (raw < 1) {
			raf = window.requestAnimationFrame(function (t) { drawFrame(t, start); });
		} else {
			intro.classList.add('rv-build-text-revealed');
			canvas.classList.add('is-finished');
			window.setTimeout(function () { if (canvas.parentNode) canvas.parentNode.removeChild(canvas); }, 420);
		}
	}

	function play() {
		if (played) return;
		played = true;
		if (!buildParticleMap()) {
			intro.classList.remove('rv-particles-enabled');
			if (canvas.parentNode) canvas.parentNode.removeChild(canvas);
			return;
		}
		intro.classList.add('rv-particles-playing');
		window.requestAnimationFrame(function (now) { drawFrame(now, now); });
	}

	function arm() {
		syncCanvas();
		if (!('IntersectionObserver' in window)) { play(); return; }
		var io = new IntersectionObserver(function (entries) {
			entries.forEach(function (entry) {
				if (!entry.isIntersecting) return;
				io.disconnect();
				play();
			});
		}, { threshold:.14, rootMargin:'0px 0px -7% 0px' });
		io.observe(intro);
	}

	window.addEventListener('resize', function () {
		if (!played) syncCanvas();
	}, { passive:true });

	if (document.fonts && document.fonts.ready) {
		document.fonts.ready.then(arm, arm);
	} else {
		arm();
	}
}());
</script>
<script>
(function () {
	'use strict';
	var root = document.getElementById('rv-wwd');
	if (!root) return;

	var reduced = window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;
	var panels = root.querySelectorAll('.rv-wwd-panel');

	/* Pause expensive ambient motion outside the viewport. */
	if (reduced || !('IntersectionObserver' in window)) {
		Array.prototype.forEach.call(panels, function (panel) { panel.classList.add('is-active'); });
	} else {
		var activeObserver = new IntersectionObserver(function (entries) {
			entries.forEach(function (entry) {
				entry.target.classList.toggle('is-active', entry.isIntersecting);
				var motionSvgs = entry.target.querySelectorAll('svg');
				Array.prototype.forEach.call(motionSvgs, function (svg) {
					if (entry.isIntersecting && typeof svg.unpauseAnimations === 'function') svg.unpauseAnimations();
					if (!entry.isIntersecting && typeof svg.pauseAnimations === 'function') svg.pauseAnimations();
				});
				if (entry.isIntersecting && entry.target.classList.contains('rv-wwd-panel--grow')) {
					animateGrowthMetrics(entry.target);
				}
			});
		}, { threshold: 0.08, rootMargin: '12% 0px 12% 0px' });
		Array.prototype.forEach.call(panels, function (panel) { activeObserver.observe(panel); });
	}

	function animateGrowthMetrics(panel) {
		if (reduced || panel.dataset.metricsAnimated === '1') return;
		panel.dataset.metricsAnimated = '1';
		var metrics = panel.querySelectorAll('.rv-grow-metrics strong');
		Array.prototype.forEach.call(metrics, function (metric, index) {
			var original = (metric.textContent || '').trim();
			var normalized = original.replace('−', '-').replace(',', '.');
			var match = normalized.match(/[-+]?\d+(?:\.\d+)?/);
			if (!match) return;
			var target = Math.abs(parseFloat(match[0]));
			var decimals = match[0].indexOf('.') === -1 ? 0 : match[0].split('.')[1].length;
			var prefix = original.charAt(0) === '+' ? '+' : (original.charAt(0) === '−' || original.charAt(0) === '-' ? '−' : '');
			var suffix = original.replace(/^[+−-]?\d+(?:[.,]\d+)?/, '');
			var start = 0;
			var duration = 900 + index * 140;
			metric.classList.add('is-counting');
			function tick(now) {
				if (!start) start = now;
				var progress = Math.min(1, (now - start) / duration);
				var eased = 1 - Math.pow(1 - progress, 4);
				metric.textContent = prefix + (target * eased).toFixed(decimals) + suffix;
				if (progress < 1) window.requestAnimationFrame(tick);
				else {
					metric.textContent = original;
					metric.classList.remove('is-counting');
					metric.classList.add('is-counted');
					window.setTimeout(function () { metric.classList.remove('is-counted'); }, 720);
				}
			}
			window.setTimeout(function () { window.requestAnimationFrame(tick); }, index * 90);
		});
	}

	/* Scroll-scrub gives the three chapters one continuous cinematic arc.
	   Only CSS variables are updated; layout itself never animates. */
	var storyFrame = 0;
	/* Телефон и планшет (touch): сдвиги здесь 8–14px, а стоили 10–40 мс на кадр —
	   переменные на панелях пересчитывают стили всего блока и перезапускают
	   CSS-анимации, в которых они участвуют. На touch — только класс активности,
	   без покадровых переменных. Композиция та же, прокрутка ровная. */
	var storyLite = !(window.matchMedia && window.matchMedia('(hover: hover) and (pointer: fine)').matches) || window.innerWidth <= 767;
	var storyActive = null;
	function updateStoryMotion() {
		storyFrame = 0;
		var viewport = (window.RVVH ? window.RVVH() : window.innerHeight) || document.documentElement.clientHeight;
		var rootRect = root.getBoundingClientRect();
		if (storyLite) {
			var on = rootRect.top < viewport * .72 && rootRect.bottom > viewport * .28;
			if (on !== storyActive) { storyActive = on; root.classList.toggle('is-story-active', on); }
			return;
		}
		var firstPanel = panels[0];
		var lastPanel = panels[panels.length - 1];
		if (!firstPanel || !lastPanel) return;
		var firstRect = firstPanel.getBoundingClientRect();
		var lastRect = lastPanel.getBoundingClientRect();
		var storyStart = firstRect.top + window.pageYOffset;
		var storyEnd = lastRect.bottom + window.pageYOffset;
		var storyPoint = window.pageYOffset + viewport * .5;
		var progress = Math.max(0, Math.min(1, (storyPoint - storyStart) / Math.max(1, storyEnd - storyStart)));
		root.style.setProperty('--rv-story-marker', (progress * 112).toFixed(2) + 'px');
		root.classList.toggle('is-story-active', rootRect.top < viewport * .72 && rootRect.bottom > viewport * .28);

		var mobile = window.innerWidth <= 600;
		Array.prototype.forEach.call(panels, function (panel) {
			var rect = panel.getBoundingClientRect();
			var distance = (rect.top + rect.height * .5 - viewport * .5) / viewport;
			var clamped = Math.max(-1, Math.min(1, distance));
			var shift = clamped * (mobile ? -8 : -24);
			panel.style.setProperty('--rv-scroll-y', shift.toFixed(2) + 'px');
			panel.style.setProperty('--rv-chapter-shift', (clamped * (mobile ? 14 : 44)).toFixed(2) + 'px');
		});
	}
	function requestStoryMotion() {
		if (!storyFrame) storyFrame = window.requestAnimationFrame(updateStoryMotion);
	}
	if (!reduced) {
		window.addEventListener('scroll', requestStoryMotion, { passive: true });
		window.addEventListener('resize', requestStoryMotion, { passive: true });
		updateStoryMotion();
	}

	/* Fine-pointer devices get a GPU-only light/parallax layer.
	   Touch layouts keep their clean composition and never depend on hover. */
	var finePointer = window.matchMedia && window.matchMedia('(hover: hover) and (pointer: fine)').matches;
	if (reduced || !finePointer) return;

	function bindLight(target, vars) {
		if (!target) return;
		var frame = 0;
		var next = null;

		function render() {
			frame = 0;
			if (!next) return;
			target.style.setProperty(vars.x, next.x + '%');
			target.style.setProperty(vars.y, next.y + '%');
			if (vars.tiltX) target.style.setProperty(vars.tiltX, next.tiltX + 'deg');
			if (vars.tiltY) target.style.setProperty(vars.tiltY, next.tiltY + 'deg');
			next = null;
		}

		target.addEventListener('pointerenter', function () {
			target.dataset.pointer = '1';
		});
		target.addEventListener('pointermove', function (event) {
			var rect = target.getBoundingClientRect();
			var x = Math.max(0, Math.min(100, ((event.clientX - rect.left) / rect.width) * 100));
			var y = Math.max(0, Math.min(100, ((event.clientY - rect.top) / rect.height) * 100));
			next = {
				x: x.toFixed(2),
				y: y.toFixed(2),
				tiltX: ((50 - y) * .055).toFixed(2),
				tiltY: ((x - 50) * .075).toFixed(2)
			};
			if (!frame) frame = window.requestAnimationFrame(render);
		}, { passive: true });
		target.addEventListener('pointerleave', function () {
			target.dataset.pointer = '0';
			next = { x: String(vars.resetX || 72), y: String(vars.resetY || 38), tiltX: '0', tiltY: '0' };
			if (!frame) frame = window.requestAnimationFrame(render);
		});
	}

	bindLight(root.querySelector('.rv-build-visual'), {
		x: '--rv-glow-x', y: '--rv-glow-y', tiltX: '--rv-tilt-x', tiltY: '--rv-tilt-y'
	});
	bindLight(root.querySelector('.rv-grow-dashboard'), {
		x: '--rv-glow-x', y: '--rv-glow-y', tiltX: '--rv-grow-tilt-x', tiltY: '--rv-grow-tilt-y', resetX: 86, resetY: 12
	});
	bindLight(root.querySelector('.rv-scale-flow'), {
		x: '--rv-flow-glow-x', y: '--rv-flow-glow-y', tiltX: '--rv-scale-tilt-x', tiltY: '--rv-scale-tilt-y', resetX: 56, resetY: 55
	});
}());
</script>
<script id="revive-wwd-sticky-stack">
/* Progressive enhancement for the Build / Grow / Scale story.
   Each incoming chapter pins over the previous one; the covered chapter
   recedes by only 2.2% and loses 11% brightness, with no blur or rotation. */
(function () {
	'use strict';
	var root = document.getElementById('rv-wwd');
	if (!root || !window.matchMedia || !window.requestAnimationFrame) return;

	var panels = Array.prototype.slice.call(root.querySelectorAll('[data-stack-card]'));
	if (panels.length < 2) return;

	var reducedQuery = window.matchMedia('(prefers-reduced-motion: reduce)');
	var desktopQuery = window.matchMedia('(min-width: 1024px) and (min-height: 760px)');
	var frame = 0;
	var enabled = false;

	function resetPanels() {
		panels.forEach(function (panel) {
			panel.style.removeProperty('--rv-stack-scale');
			panel.style.removeProperty('--rv-stack-brightness');
			panel.style.removeProperty('--rv-stack-lift');
			panel.classList.remove('is-stacked');
		});
	}

	function renderStack() {
		frame = 0;
		if (!enabled) return;

		var viewport = (window.RVVH ? window.RVVH() : window.innerHeight) || document.documentElement.clientHeight;
		var stackTop = parseFloat(window.getComputedStyle(root).getPropertyValue('--rv-stack-top')) || 92;
		var start = viewport * .88;
		var stackStep = 14;

		panels.forEach(function (panel, index) {
			var progress = 0;
			if (index < panels.length - 1) {
				var nextTop = panels[index + 1].getBoundingClientRect().top;
				var finish = stackTop + ((index + 1) * stackStep) + 44;
				progress = Math.max(0, Math.min(1, (start - nextTop) / Math.max(1, start - finish)));
			}

			panel.style.setProperty('--rv-stack-scale', (1 - progress * .022).toFixed(4));
			panel.style.setProperty('--rv-stack-brightness', (1 - progress * .11).toFixed(3));
			panel.style.setProperty('--rv-stack-lift', (-progress * 4).toFixed(2) + 'px');
			panel.classList.toggle('is-stacked', progress > .04);
		});
	}

	function requestStack() {
		if (enabled && !frame) frame = window.requestAnimationFrame(renderStack);
	}

	function syncMode() {
		/* Sticky stacking is intentionally desktop-only. On portrait phones the
		   nearly viewport-height cards can overlap when the browser chrome changes
		   the visual viewport during scroll. Mobile keeps the normal document flow. */
		enabled = desktopQuery.matches && !reducedQuery.matches;
		root.classList.toggle('rv-stack-ready', enabled);
		root.classList.remove('rv-stack-mobile');
		if (!enabled) resetPanels();
		requestStack();
	}

	window.addEventListener('scroll', requestStack, { passive: true });
	window.addEventListener('resize', requestStack, { passive: true });
	if (typeof desktopQuery.addEventListener === 'function') {
		desktopQuery.addEventListener('change', syncMode);
		reducedQuery.addEventListener('change', syncMode);
	} else {
		desktopQuery.addListener(syncMode);
		reducedQuery.addListener(syncMode);
	}

	syncMode();
}());
</script>
<script id="revive-chapter-dock">
/* REVIVE chapter dock v1.0
   Нижняя панель глав BUILD / GROW / SCALE.
   · Позиции глав берутся из невидимых меток .rv-dock-mark перед каждой
     панелью: на ПК панели «липкие», и их собственный rect врёт.
   · Измерение — только при ресайзе/изменении высоты страницы; на скролле
     одна арифметика по pageYOffset, без чтения layout.
   · Скролл по клику идёт через RVScroll (Lenis + фирменная кривая),
     без него — обычный smooth. */
(function () {
	'use strict';
	var wwd = document.getElementById('rv-wwd');
	var dock = document.getElementById('rv-dock');
	if (!wwd || !dock || !window.requestAnimationFrame) return;

	var doc = document.documentElement;
	var reduced = window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;

	/* fixed-элемент не должен жить внутри секций с transform/overflow */
	document.body.appendChild(dock);

	var chapters = Array.prototype.slice.call(dock.querySelectorAll('[data-dock-tab]')).map(function (tab) {
		var id = tab.getAttribute('data-dock-tab');
		return {
			id: id,
			tab: tab,
			panel: document.getElementById(id),
			mark: wwd.querySelector('[data-dock-mark="' + id + '"]'),
			meter: tab.querySelector('.rv-dock__meter i'),
			label: tab.querySelector('[data-scramble]'),
			text: (tab.querySelector('[data-scramble]') || {}).textContent || '',
			p: -1
		};
	}).filter(function (c) { return c.panel && c.mark; });
	if (chapters.length < 2) return;

	var wash = document.createElement('div');
	wash.className = 'rv-dock-wash';
	wash.setAttribute('aria-hidden', 'true');
	document.body.appendChild(wash);

	var counter = dock.querySelector('[data-dock-current]');
	var starts = [];
	var end = 0;
	var visible = false;
	var current = -1;
	var total = -1;

	doc.classList.add('rv-dock-ready');

	function clamp(v) { return v < 0 ? 0 : (v > 1 ? 1 : v); }

	function measure() {
		var y = window.pageYOffset || doc.scrollTop || 0;
		starts = chapters.map(function (c) { return c.mark.getBoundingClientRect().top + y; });
		end = wwd.getBoundingClientRect().bottom + y;
	}

	/* «Расшифровка» подписи вкладки, как моно-подписи у Devils. */
	var GLYPHS = 'ABCDEFGHKMNPRSTUVXYZ0123456789';
	function scramble(chapter) {
		var el = chapter.label;
		var text = chapter.text;
		if (!el || !text) return;
		if (el._rvScramble) window.cancelAnimationFrame(el._rvScramble);
		if (reduced) { el.textContent = text; return; }
		var started = 0;
		var duration = 520;
		function step(now) {
			if (!started) started = now;
			var p = clamp((now - started) / duration);
			var out = '';
			for (var i = 0; i < text.length; i++) {
				var settle = (i + 1) / text.length;
				out += (p >= settle * .92) ? text.charAt(i) : GLYPHS.charAt((Math.random() * GLYPHS.length) | 0);
			}
			el.textContent = out;
			if (p < 1) el._rvScramble = window.requestAnimationFrame(step);
			else { el.textContent = text; el._rvScramble = 0; }
		}
		el._rvScramble = window.requestAnimationFrame(step);
	}

	function setCurrent(index) {
		if (index === current) return;
		current = index;
		chapters.forEach(function (c, i) {
			var on = i === index;
			c.tab.classList.toggle('is-current', on);
			if (on) c.tab.setAttribute('aria-current', 'step');
			else c.tab.removeAttribute('aria-current');
		});
		var accent = window.getComputedStyle(chapters[index].tab).getPropertyValue('--tab-accent').trim();
		if (accent) {
			dock.style.setProperty('--rv-ch-accent', accent);
			wash.style.setProperty('--rv-ch-accent', accent);
		}
		if (counter) counter.textContent = chapters[index].tab.querySelector('.rv-dock__idx').textContent;
		if (visible) scramble(chapters[index]);
	}

	function render() {
		if (!starts.length) measure();
		var y = window.pageYOffset || doc.scrollTop || 0;
		var vh = (window.RVVH ? window.RVVH() : window.innerHeight) || doc.clientHeight;
		var probe = y + vh * .55;
		var storyEnd = Math.max(starts[starts.length - 1] + 1, end - vh * .45);

		var show = (y + vh * .9 > starts[0]) && (y + vh * .86 < end);
		if (show !== visible) {
			visible = show;
			dock.classList.toggle('is-on', show);
			wash.classList.toggle('is-on', show);
			dock.setAttribute('aria-hidden', show ? 'false' : 'true');
			if (show && current >= 0) scramble(chapters[current]);
		}

		var index = 0;
		for (var i = 0; i < starts.length; i++) { if (probe >= starts[i]) index = i; }
		setCurrent(index);

		for (var j = 0; j < chapters.length; j++) {
			var s = starts[j];
			var e = j < starts.length - 1 ? starts[j + 1] : storyEnd;
			var p = Math.round(clamp((probe - s) / Math.max(1, e - s)) * 1000) / 1000;
			if (p !== chapters[j].p) {
				chapters[j].p = p;
				chapters[j].meter.style.transform = 'scaleX(' + p + ')';
			}
		}

		var t = Math.round(clamp((probe - starts[0]) / Math.max(1, storyEnd - starts[0])) * 1000) / 1000;
		if (t !== total) {
			total = t;
			dock.style.setProperty('--rv-dock-total', String(t));
		}
	}

	function remeasure() { measure(); render(); }

	/* клик по вкладке: RVScroll учитывает липкие панели и фирменную кривую */
	chapters.forEach(function (c) {
		c.tab.addEventListener('click', function (event) {
			if (event.metaKey || event.ctrlKey || event.shiftKey || event.altKey) return;
			event.preventDefault();
			var done = false;
			if (window.RVScroll && typeof window.RVScroll.to === 'function') {
				done = window.RVScroll.to(c.panel);
			}
			if (!done) {
				var cs = window.getComputedStyle(c.panel);
				var offset = cs.position === 'sticky' ? (parseFloat(cs.top) || 0) : 0;
				var yTarget = c.mark.getBoundingClientRect().top + window.pageYOffset - offset;
				window.scrollTo({ top: Math.max(0, yTarget), left: 0, behavior: reduced ? 'auto' : 'smooth' });
			}
			if (window.history && history.replaceState) history.replaceState(null, '', '#' + c.id);
		});
	});

	/* модальное окно на :target (#win1) перекрывается панелью — прячем её */
	function syncModal() { doc.classList.toggle('rv-modal-open', window.location.hash === '#win1'); }
	window.addEventListener('hashchange', syncModal);
	syncModal();

	if (window.RVMotion) {
		window.RVMotion.onScroll(render);
		window.RVMotion.onResize(remeasure);
	} else {
		var frame = 0;
		var request = function () { if (!frame) frame = window.requestAnimationFrame(function () { frame = 0; render(); }); };
		window.addEventListener('scroll', request, { passive: true });
		window.addEventListener('resize', function () { measure(); request(); }, { passive: true });
	}

	/* высота страницы меняется от картинок, шрифтов, аккордеонов выше */
	if ('ResizeObserver' in window) {
		var roFrame = 0;
		new ResizeObserver(function () {
			if (roFrame) return;
			roFrame = window.requestAnimationFrame(function () { roFrame = 0; remeasure(); });
		}).observe(document.body);
	}
	window.addEventListener('load', remeasure);
	if (document.fonts && document.fonts.ready) document.fonts.ready.then(remeasure, function () {});

	remeasure();
}());
</script>
<!-- ===== /WHAT WE DO ===== -->

<script>
(function () {
	'use strict';
	var chips = document.querySelectorAll('.rv-wwd-chips span[role="button"]');
	if (!chips.length) return;

	function toggleChip(chip) {
		var active = !chip.classList.contains('is-active');
		chip.classList.toggle('is-active', active);
		chip.setAttribute('aria-pressed', active ? 'true' : 'false');
	}

	Array.prototype.forEach.call(chips, function (chip) {
		chip.addEventListener('click', function () { toggleChip(chip); });
		chip.addEventListener('keydown', function (event) {
			if (event.key !== 'Enter' && event.key !== ' ') return;
			event.preventDefault();
			toggleChip(chip);
		});
	});
}());
</script>



<!-- ===== RV AUDIT: экспресс-проверка сайта через Google PageSpeed ===== -->
<section class="rv-audit" id="audit">
<span class="rv-section-anchor" id="rv-audit" aria-hidden="true"></span>
<style>
.rv-audit{ --font-label:'JetBrains Mono',monospace; --rv-h:'Manrope','Montserrat',Arial,sans-serif;
  --rv-gold:#F5C542; --ok:#5BD98A; --warn:#F5C542; --bad:#FF6B6B;
  position:relative; background:#030032; color:#fff; padding:110px 0 120px; overflow:hidden; }
.rv-audit::before{ content:""; position:absolute; inset:0; pointer-events:none;
  background:radial-gradient(ellipse 46% 52% at 16% 12%, rgba(245,197,66,.08) 0%, transparent 68%); }
.rv-audit__wrap{ position:relative; max-width:none; padding:0 clamp(20px,4vw,64px);
  display:grid; grid-template-columns:minmax(300px,.72fr) minmax(0,1.6fr); gap:clamp(34px,4vw,80px);
  align-items:start; }
.rv-audit__chip{ display:inline-flex; align-items:center; gap:.7rem; background:rgba(255,255,255,.05);
  border:1px solid rgba(255,255,255,.12); border-radius:100px; padding:.5rem 1.1rem;
  font-family:var(--font-label); font-size:.66rem; letter-spacing:.1em; text-transform:uppercase;
  color:#fff; }
.rv-audit__chip i{ font-style:normal; width:7px; height:7px; border-radius:50%; background:var(--rv-gold);
  box-shadow:0 0 12px var(--rv-gold); }
.rv-audit__title{ font-family:var(--rv-h); font-weight:800; letter-spacing:-.03em; text-transform:uppercase;
  font-size:clamp(34px,4vw,58px); line-height:1.05; margin:22px 0 24px; color:#fff; -webkit-text-fill-color:#fff; }
.rv-audit__title em{ font-style:normal; color:transparent; -webkit-text-fill-color:transparent;
  -webkit-text-stroke:1.5px var(--rv-gold); padding-right:2px; }
.rv-audit__lead{ font-size:1rem; line-height:1.65; color:#fff; margin:0; max-width:44ch; }
.rv-audit__facts{ list-style:none; margin:26px 0 0; padding:0; display:flex; flex-direction:column; gap:10px; }
.rv-audit__facts li{ display:flex; gap:12px; font-size:.92rem; line-height:1.5; color:#fff; }
.rv-audit__facts li::before{ content:"—"; color:var(--rv-gold); }
/* карточка */
.rv-audit__card{ position:relative; border:1px solid rgba(255,255,255,.12); border-radius:28px;
  background:rgba(255,255,255,.045); padding:clamp(24px,2.4vw,40px); display:flex; flex-direction:column;
  min-height:clamp(340px,26vw,420px); overflow:hidden; }
.rv-audit__card::before{ content:""; position:absolute; inset:0; pointer-events:none; border-radius:inherit;
  background:radial-gradient(120% 80% at 88% 0%, rgba(245,197,66,.09) 0%, transparent 62%); }
.rv-audit__card > *{ position:relative; }
/* тонкая золотая дуга по верхней кромке — тот же приём, что у карточек кейсов */
.rv-audit__card::after{ content:""; position:absolute; left:12%; right:12%; top:0; height:1px;
  background:linear-gradient(90deg,transparent,rgba(245,197,66,.55),transparent); }
.rv-audit__form{ display:flex; gap:12px; flex-wrap:wrap; }
.rv-audit input.rv-audit__input{ flex:1 1 260px; min-height:66px; padding:0 28px; border-radius:999px;
  border:1px solid rgba(255,255,255,.16); background:rgba(255,255,255,.05); color:#fff;
  font-family:inherit; font-size:1.02rem; -webkit-appearance:none; appearance:none;
  transition:border-color .3s ease, background .3s ease, box-shadow .3s ease; }
.rv-audit input.rv-audit__input::placeholder{ color:rgba(255,255,255,.38); }
.rv-audit input.rv-audit__input:focus{ outline:none; border-color:rgba(245,197,66,.6); background:rgba(255,255,255,.08);
  box-shadow:0 0 0 4px rgba(245,197,66,.1); }
.rv-audit__go{ flex:none; min-height:64px; padding:0 34px; border:0; border-radius:999px; cursor:pointer;
  font-family:inherit; font-weight:800; font-size:1rem; letter-spacing:.06em; text-transform:uppercase;
  color:#030032; background:linear-gradient(135deg,#F5D488 0%,#F5C542 45%,#DFAE3F 100%);
  box-shadow:0 10px 30px rgba(245,197,66,.28); transition:transform .3s ease, box-shadow .3s ease; }
.rv-audit__go:hover{ transform:translateY(-2px); box-shadow:0 14px 36px rgba(245,197,66,.42); }
.rv-audit__go[disabled]{ opacity:.55; cursor:default; transform:none; }
.rv-audit__hint{ margin:16px 0 0; font-size:.86rem; line-height:1.5; color:#fff; }
/* состояние «ещё не проверяли»: показываем, что именно будем мерить,
   иначе карточка до первого клика — пустой прямоугольник */
.rv-audit__idle{ margin-top:auto; padding-top:26px; }
.rv-audit.is-load .rv-audit__idle, .rv-audit.is-done .rv-audit__idle{ display:none; }
.rv-audit__prev{ display:flex; flex-wrap:wrap; gap:clamp(18px,3vw,44px); }
.rv-audit__prev div{ display:flex; align-items:center; gap:14px; }
.rv-audit__prev svg{ width:64px; height:64px; flex:none; }
.rv-audit__prev circle{ fill:none; stroke:rgba(255,255,255,.16); stroke-width:5; stroke-dasharray:5 7; }
.rv-audit__prev b{ position:absolute; font-family:var(--rv-h); font-weight:800; font-size:1.05rem;
  color:rgba(255,255,255,.28); }
.rv-audit__prevbox{ position:relative; display:grid; place-items:center; }
.rv-audit__prev span{ font-family:var(--font-label); font-size:.68rem; letter-spacing:.1em; text-transform:uppercase;
  color:#fff; line-height:1.5; max-width:9ch; }
.rv-audit__what{ margin:24px 0 0; padding-top:20px; border-top:1px solid rgba(255,255,255,.1);
  font-size:.88rem; line-height:1.6; color:#fff; }
.rv-audit__err{ margin:16px 0 0; font-size:.92rem; line-height:1.5; color:#FFB4B4; display:none; }
.rv-audit.is-err .rv-audit__err{ display:block; }
/* загрузка */
.rv-audit__load{ display:none; margin-top:28px; }
.rv-audit.is-load .rv-audit__load{ display:block; }
.rv-audit.is-load .rv-audit__go{ opacity:.55; }
.rv-audit__bar{ height:3px; border-radius:2px; background:rgba(255,255,255,.12); overflow:hidden; }
.rv-audit__bar i{ display:block; height:100%; width:0; border-radius:2px; background:var(--rv-gold);
  box-shadow:0 0 14px rgba(245,197,66,.5); transition:width .8s linear; }
.rv-audit__status{ margin-top:14px; font-family:var(--font-label); font-size:.72rem; letter-spacing:.08em;
  text-transform:uppercase; color:#fff; }
/* результат */
.rv-audit__res{ display:none; margin-top:30px; }
.rv-audit.is-done .rv-audit__res{ display:block; animation:rvAuditIn .5s cubic-bezier(.22,1,.36,1) both; }
@keyframes rvAuditIn{ from{ opacity:0; transform:translateY(14px); } to{ opacity:1; transform:none; } }
.rv-audit__site{ font-family:var(--font-label); font-size:.7rem; letter-spacing:.1em; text-transform:uppercase;
  color:#fff; margin-bottom:18px; word-break:break-all; }
.rv-audit__dials{ display:flex; flex-wrap:wrap; gap:clamp(18px,3vw,44px); }
.rv-audit__dial{ display:flex; align-items:center; gap:14px; }
.rv-audit__dial svg{ width:74px; height:74px; transform:rotate(-90deg); flex:none; }
.rv-audit__dial circle{ fill:none; stroke-width:5; }
.rv-audit__dial .bg{ stroke:rgba(255,255,255,.12); }
.rv-audit__dial .fg{ stroke-linecap:round; stroke-dasharray:201; stroke-dashoffset:201;
  transition:stroke-dashoffset 1.1s cubic-bezier(.22,1,.36,1); }
.rv-audit__dial b{ position:absolute; font-family:var(--rv-h); font-weight:800; font-size:1.15rem; }
.rv-audit__dialbox{ position:relative; display:grid; place-items:center; }
.rv-audit__dial span{ font-family:var(--font-label); font-size:.68rem; letter-spacing:.1em; text-transform:uppercase;
  color:#fff; line-height:1.5; max-width:9ch; }
.rv-audit__list{ list-style:none; margin:28px 0 0; padding:22px 0 0; border-top:1px solid rgba(255,255,255,.12);
  display:flex; flex-direction:column; gap:14px; }
.rv-audit__list li{ display:grid; grid-template-columns:22px minmax(0,1fr) auto; gap:14px; align-items:baseline;
  font-size:.96rem; line-height:1.5; }
.rv-audit__list i{ font-style:normal; font-weight:800; }
.rv-audit__list u{ text-decoration:none; display:block; font-size:.84rem; color:#fff; margin-top:2px; }
.rv-audit__list b{ font-family:var(--font-label); font-weight:400; font-size:.78rem; letter-spacing:.06em;
  color:#fff; white-space:nowrap; }
.rv-audit__verdict{ margin:26px 0 0; padding:20px 22px; border-radius:18px; background:rgba(245,197,66,.08);
  border:1px solid rgba(245,197,66,.24); font-size:.98rem; line-height:1.6; }
.rv-audit__act{ display:flex; flex-wrap:wrap; align-items:center; gap:16px; margin-top:24px; }
.rv-audit__cta{ display:inline-flex; align-items:center; justify-content:center; gap:12px; min-height:64px;
  padding:0 34px; border-radius:999px; font-weight:800; font-size:1rem; letter-spacing:.06em;
  text-transform:uppercase; text-decoration:none; color:#030032;
  background:linear-gradient(135deg,#F5D488 0%,#F5C542 45%,#DFAE3F 100%);
  box-shadow:0 10px 30px rgba(245,197,66,.28); transition:transform .3s ease, box-shadow .3s ease; }
.rv-audit__cta:hover{ transform:translateY(-2px); box-shadow:0 14px 36px rgba(245,197,66,.42); }
.rv-audit__again{ background:none; border:0; cursor:pointer; padding:6px 0; font-family:var(--font-label);
  font-size:.7rem; letter-spacing:.1em; text-transform:uppercase; color:#fff; }
.rv-audit__again:hover{ color:var(--rv-gold); }
.rv-audit__src{ margin:18px 0 0; font-size:.8rem; color:#fff; }
@media(max-width:1024px){
  .rv-audit{ padding:80px 0 90px; }
  .rv-audit__wrap{ grid-template-columns:1fr; gap:34px; }
}
@media(max-width:700px){
  /* блок должен помещаться в экран телефона: режем отступы и превью */
  .rv-audit{ padding:34px 0 38px; }
  .rv-audit__wrap{ gap:22px; }
  .rv-audit__title{ margin:14px 0 14px; }
  .rv-audit__lead{ font-size:.94rem; line-height:1.5; }
  .rv-audit__facts{ margin-top:16px; gap:7px; }
  .rv-audit__facts li{ font-size:.86rem; line-height:1.45; }
  .rv-audit__go{ width:100%; min-height:60px; font-size:.92rem; }
  .rv-audit input.rv-audit__input{ min-height:58px; flex:1 1 100%; }
  .rv-audit__cta{ width:auto; min-height:60px; font-size:.92rem; padding:0 28px; }
  .rv-audit__card{ min-height:0; padding:20px 18px; }
  .rv-audit__hint{ margin-top:12px; font-size:.8rem; }
  .rv-audit__idle{ padding-top:18px; }
  /* три превью должны встать в один ряд на 360px */
  .rv-audit__prev{ gap:10px; justify-content:space-between; }
  .rv-audit__prev div{ gap:8px; }
  .rv-audit__prev svg{ width:44px; height:44px; }
  .rv-audit__prev b{ font-size:.9rem; }
  .rv-audit__prev span{ font-size:.54rem; letter-spacing:.06em; max-width:8ch; }
  .rv-audit__what{ margin-top:16px; padding-top:14px; font-size:.82rem; line-height:1.5; }
  .rv-audit__dials{ gap:16px; }
  .rv-audit__dial svg{ width:60px; height:60px; }
  .rv-audit__list{ margin-top:20px; padding-top:16px; gap:11px; }
  .rv-audit__src{ font-size:.75rem; }
}
@media(prefers-reduced-motion:reduce){
  .rv-audit.is-done .rv-audit__res{ animation:none; }
  .rv-audit__dial .fg, .rv-audit__bar i{ transition:none; }
}

/* ======================================================================
   ЭКСПРЕСС-АУДИТ — ФИНИШНАЯ ОТДЕЛКА
   ----------------------------------------------------------------------
   Разметка и логика проверки не тронуты, меняется только оформление.
   1. Возвращена тональная иерархия. Глобальное правило в g777.css красит
      все подписи блока в чистый белый — из-за него плейсхолдер
      «yoursite.com» читался как уже введённый адрес, а мономодные
      подписи спорили с основным текстом. Держим контраст выше 7:1,
      но разводим уровни.
   2. Карточка получила вид прибора: сетка-подложка, две подписанные
      строки (что меряем / полный чеклист) и живые кольца ожидания
      вместо пунктирных заготовок.
   3. Колонки выровнены по высоте — пустота внутри карточки уходит
      в подписанную зону, а не висит воздухом.
   Заголовок намеренно оставлен на Manrope: на нём же сидят PROCESS,
   QUIZ, FAQ и CINE — это вторая гарнитура сайта, не случайность.
   ====================================================================== */

/* --- 1. Левая колонка -------------------------------------------------- */
.rv-audit .rv-audit__wrap{ align-items:stretch; }

.rv-audit .rv-audit__chip{
  padding:.55rem 1.15rem;
  letter-spacing:.14em;
  color:#fff!important;
  -webkit-text-fill-color:rgba(255,255,255,.74)!important;
  background:rgba(255,255,255,.04);
  border-color:rgba(255,255,255,.1);
}
.rv-audit .rv-audit__chip i{ animation:rvAuditPulse 2.8s ease-in-out infinite; }
@keyframes rvAuditPulse{
  0%,100%{ box-shadow:0 0 0 0 rgba(245,197,66,.5), 0 0 12px var(--rv-gold); }
  55%    { box-shadow:0 0 0 5px rgba(245,197,66,0), 0 0 12px var(--rv-gold); }
}

.rv-audit .rv-audit__lead{ color:#fff; }

/* Факты — как строки спецификации: моноиндекс и волосяные линии. */
.rv-audit .rv-audit__facts{ counter-reset:rvaf; gap:0; margin-top:28px; }
.rv-audit .rv-audit__facts li{
  counter-increment:rvaf;
  gap:16px;
  align-items:baseline;
  padding:11px 0;
  border-top:1px solid rgba(255,255,255,.08);
  color:#fff;
}
.rv-audit .rv-audit__facts li:last-child{ border-bottom:1px solid rgba(255,255,255,.08); }
.rv-audit .rv-audit__facts li::before{
  content:"0" counter(rvaf);
  flex:none;
  font-family:var(--font-label);
  font-size:.64rem;
  letter-spacing:.1em;
  color:var(--rv-gold);
  opacity:.85;
}

/* --- 2. Карточка ------------------------------------------------------- */
.rv-audit .rv-audit__card{
  border-color:rgba(255,255,255,.1);
  background:linear-gradient(180deg,rgba(255,255,255,.055),rgba(255,255,255,.026));
  transition:border-color .4s ease, box-shadow .4s ease;
}
/* Техническая сетка под содержимым — тот же приём, что в секциях сайта. */
.rv-audit .rv-audit__card::before{
  background:
    radial-gradient(120% 80% at 88% 0%, rgba(245,197,66,.10) 0%, transparent 62%),
    linear-gradient(rgba(140,150,255,.05) 1px, transparent 1px),
    linear-gradient(90deg, rgba(140,150,255,.05) 1px, transparent 1px);
  background-size:auto, 72px 72px, 72px 72px;
  -webkit-mask-image:linear-gradient(180deg,#000 0%,#000 44%,transparent 92%);
          mask-image:linear-gradient(180deg,#000 0%,#000 44%,transparent 92%);
}
.rv-audit .rv-audit__card::after{
  left:8%; right:8%;
  background:linear-gradient(90deg,transparent,rgba(245,197,66,.62) 50%,transparent);
}
/* Фокус в поле подсвечивает всю карточку — сигнал «прибор включён». */
.rv-audit .rv-audit__card:focus-within{
  border-color:rgba(245,197,66,.26);
  box-shadow:0 0 0 1px rgba(245,197,66,.07), 0 30px 80px rgba(0,0,20,.32);
}

/* --- 3. Поле и подсказка ---------------------------------------------- */
.rv-audit .rv-audit__form input.rv-audit__input{
  min-height:68px;
  background:rgba(255,255,255,.04);
  border-color:rgba(255,255,255,.13);
}
.rv-audit .rv-audit__form input.rv-audit__input::placeholder{
  font-family:var(--font-label);
  font-size:.92rem;
  letter-spacing:.04em;
  color:rgba(255,255,255,.42)!important;
  -webkit-text-fill-color:rgba(255,255,255,.42)!important;
  opacity:1;
}
.rv-audit .rv-audit__form input.rv-audit__input:focus{
  border-color:rgba(245,197,66,.55);
  box-shadow:0 0 0 4px rgba(245,197,66,.12), inset 0 0 24px rgba(245,197,66,.05);
}
.rv-audit .rv-audit__hint{
  font-size:.84rem;
  color:#fff!important;
  -webkit-text-fill-color:rgba(255,255,255,.64)!important;
}

/* --- 4. Ожидание: подписанные строки вместо пустоты -------------------- */
.rv-audit .rv-audit__idle{
  border-top:1px solid rgba(255,255,255,.09);
  padding-top:0;
}
.rv-audit .rv-audit__idle::before{
  content:"<?php echo (function_exists('pll_current_language') && pll_current_language() == 'en') ? 'What we measure' : 'Що ми міряємо'; ?>";
  display:block;
  padding:16px 0 22px;
  font-family:var(--font-label);
  font-size:.6rem;
  font-weight:500;
  letter-spacing:.18em;
  text-transform:uppercase;
  color:#fff;
}
.rv-audit .rv-audit__what::before{
  content:"<?php echo (function_exists('pll_current_language') && pll_current_language() == 'en') ? 'Full checklist' : 'Повний чекліст'; ?>";
  display:block;
  margin-bottom:12px;
  font-family:var(--font-label);
  font-size:.6rem;
  font-weight:500;
  letter-spacing:.18em;
  text-transform:uppercase;
  color:rgba(245,197,66,.72);
}
.rv-audit .rv-audit__what{
  color:#fff!important;
  -webkit-text-fill-color:rgba(255,255,255,.74)!important;
}

/* Кольца ожидания: тонкий контур, метка на 12 часах и очень медленное
   вращение пунктира — состояние «наготове», а не «не загрузилось». */
.rv-audit .rv-audit__prev div{ gap:16px; }
.rv-audit .rv-audit__prevbox::before{
  content:"";
  position:absolute;
  inset:-7px;
  border:1px solid rgba(245,197,66,.16);
  border-radius:50%;
}
.rv-audit .rv-audit__prevbox::after{
  content:"";
  position:absolute;
  top:-9px;
  left:50%;
  width:1px;
  height:7px;
  background:var(--rv-gold);
  opacity:.5;
  transform:translateX(-50%);
}
.rv-audit .rv-audit__prev circle{
  stroke:rgba(245,197,66,.34);
  stroke-width:2;
  stroke-dasharray:2 9;
  transform-box:fill-box;
  transform-origin:center;
  animation:rvAuditScan 26s linear infinite;
}
.rv-audit .rv-audit__prev div:nth-child(2) circle{ animation-duration:32s; animation-direction:reverse; }
.rv-audit .rv-audit__prev div:nth-child(3) circle{ animation-duration:38s; }
@keyframes rvAuditScan{ to{ transform:rotate(360deg); } }
.rv-audit .rv-audit__prev b{
  font-size:1.1rem;
  color:rgba(255,255,255,.36)!important;
  -webkit-text-fill-color:rgba(255,255,255,.36)!important;
}
.rv-audit .rv-audit__prev span{
  letter-spacing:.12em;
  color:#fff!important;
  -webkit-text-fill-color:rgba(255,255,255,.64)!important;
}

/* --- 5. Служебные подписи результата ----------------------------------- */
.rv-audit .rv-audit__status,
.rv-audit .rv-audit__site,
.rv-audit .rv-audit__src,
.rv-audit .rv-audit__list u,
.rv-audit .rv-audit__dial span,
.rv-audit .rv-audit__again{
  color:#fff!important;
  -webkit-text-fill-color:rgba(255,255,255,.64)!important;
}

/* --- 6. Клавиатурная навигация ---------------------------------------- */
.rv-audit :is(button,a,input):focus-visible{
  outline:2px solid rgba(245,197,66,.75);
  outline-offset:3px;
}

@media(max-width:700px){
  .rv-audit .rv-audit__facts{ margin-top:18px; }
  .rv-audit .rv-audit__facts li{ padding:10px 0; gap:12px; }
  .rv-audit .rv-audit__form input.rv-audit__input{ min-height:58px; }
  .rv-audit .rv-audit__idle::before{ padding:13px 0 16px; font-size:.56rem; letter-spacing:.14em; }
  .rv-audit .rv-audit__what::before{ font-size:.56rem; letter-spacing:.14em; }
  .rv-audit .rv-audit__prev div{ gap:10px; }
  .rv-audit .rv-audit__prevbox::before{ inset:-5px; }
  .rv-audit .rv-audit__prevbox::after{ top:-7px; height:5px; }
}

@media(prefers-reduced-motion:reduce){
  .rv-audit .rv-audit__prev circle,
  .rv-audit .rv-audit__chip i{ animation:none; }
}

/* ======================================================================
   ЭКСПРЕСС-АУДИТ v3 — «сканер»: консоль с живым статусом, радар в карточке,
   луч сканирования во время проверки, кольца-«калибровка» в ожидании.
   Логика проверки (PageSpeed) не тронута.
   ====================================================================== */
.rv-audit{ --ok:#5BD98A; }
.rv-audit::after{ content:""; position:absolute; inset:0; pointer-events:none;
  background:
    radial-gradient(ellipse 40% 50% at 78% 50%, rgba(91,217,138,.07) 0%, transparent 70%),
    linear-gradient(rgba(140,150,255,.035) 1px,transparent 1px) 0 0/80px 80px,
    linear-gradient(90deg,rgba(140,150,255,.035) 1px,transparent 1px) 0 0/80px 80px;
  -webkit-mask-image:radial-gradient(ellipse 70% 80% at 60% 50%,#000,transparent 80%);
          mask-image:radial-gradient(ellipse 70% 80% at 60% 50%,#000,transparent 80%); }
.rv-audit .rv-audit__card{ background:linear-gradient(160deg,rgba(20,24,72,.92),rgba(7,8,40,.96));
  border-color:rgba(255,255,255,.12); box-shadow:0 40px 100px rgba(0,0,20,.45), inset 0 1px 0 rgba(255,255,255,.06); }

/* консоль */
.rv-audit .rv-audit__console{ display:flex; align-items:center; gap:7px; margin:calc(-1 * clamp(24px,2.4vw,40px)) calc(-1 * clamp(24px,2.4vw,40px)) 26px;
  padding:14px clamp(20px,2.2vw,32px); border-bottom:1px solid rgba(255,255,255,.08); background:rgba(255,255,255,.025);
  font-family:var(--font-label); font-size:.64rem; letter-spacing:.12em; text-transform:uppercase; }
.rv-audit__console > i{ width:8px; height:8px; border-radius:50%; background:rgba(255,255,255,.18); }
.rv-audit__console > i:first-child{ background:var(--rv-gold); box-shadow:0 0 10px rgba(245,197,66,.6); }
.rv-audit__console span{ margin-left:10px; color:rgba(255,255,255,.62); }
.rv-audit__console b{ margin-left:auto; display:inline-flex; align-items:center; gap:8px; font-weight:500; color:var(--ok); }
.rv-audit__console b::before{ content:""; width:7px; height:7px; border-radius:50%; background:currentColor; box-shadow:0 0 10px currentColor; animation:rvAuBlink 1.6s ease-in-out infinite; }
.rv-audit__console em{ font-style:normal; display:none; }
.rv-audit__console .st-ready{ display:inline; }
.rv-audit.is-load .rv-audit__console b{ color:var(--rv-gold); }
.rv-audit.is-load .rv-audit__console .st-ready,.rv-audit.is-done .rv-audit__console .st-ready{ display:none; }
.rv-audit.is-load .rv-audit__console .st-scan{ display:inline; }
.rv-audit.is-done:not(.is-load) .rv-audit__console .st-done{ display:inline; }
@keyframes rvAuBlink{ 50%{ opacity:.3; } }

/* радар в правом верхнем углу карточки */
.rv-audit .rv-audit__card > .rv-audit__radar{ position:absolute; z-index:0; right:-110px; top:-40px; width:360px; height:360px; border-radius:50%; pointer-events:none;
  background:radial-gradient(circle,transparent 0 21%,rgba(91,217,138,.10) 21.3% 21.8%,transparent 22% 42%,rgba(91,217,138,.08) 42.3% 42.8%,transparent 43% 63%,rgba(91,217,138,.06) 63.3% 63.8%,transparent 64%);
  opacity:.9; }
.rv-audit__radar i{ position:absolute; inset:0; border-radius:50%;
  background:conic-gradient(from 0deg,rgba(91,217,138,.22),rgba(91,217,138,0) 18%,transparent 100%);
  animation:rvAuSpin 6s linear infinite; }
.rv-audit.is-load .rv-audit__radar i{ animation-duration:1.4s; background:conic-gradient(from 0deg,rgba(245,197,66,.3),rgba(245,197,66,0) 20%,transparent 100%); }
@keyframes rvAuSpin{ to{ transform:rotate(360deg); } }
.rv-audit .rv-audit__card > *:not(.rv-audit__radar):not(.rv-audit__beam){ z-index:1; }

/* поле: префикс https:// и «лазер» по нижней кромке */
.rv-audit .rv-audit__form{ position:relative; }
.rv-audit .rv-audit__card .rv-audit__form > .rv-audit__pre{ position:absolute; z-index:2; left:26px; top:34px; transform:translateY(-50%); pointer-events:none;
  font-family:var(--font-label); font-size:.92rem; letter-spacing:.04em; color:rgba(245,197,66,.8); }
.rv-audit .rv-audit__form input.rv-audit__input{ padding-left:100px; }
.rv-audit .rv-audit__form::after{ content:""; position:absolute; left:30px; bottom:0; height:1px; width:90px; pointer-events:none;
  background:linear-gradient(90deg,transparent,var(--rv-gold),transparent); opacity:.8; animation:rvAuLaser 3.8s ease-in-out infinite; }
.rv-audit .rv-audit__card:focus-within .rv-audit__form::after{ opacity:0; }
@keyframes rvAuLaser{ 0%,100%{ transform:translateX(0); } 50%{ transform:translateX(min(34vw,420px)); } }
.rv-audit .rv-audit__go{ position:relative; overflow:hidden; }
.rv-audit .rv-audit__go::after{ content:""; position:absolute; top:0; bottom:0; left:-60%; width:45%; pointer-events:none;
  background:linear-gradient(100deg,transparent,rgba(255,255,255,.7),transparent); transform:skewX(-20deg);
  animation:rvAuBtn 5s ease-in-out 1.5s infinite; }
@keyframes rvAuBtn{ 0%{ left:-60%; } 30%,100%{ left:130%; } }

/* ожидание: кольца «калибруются» — по кольцу бежит золотой сектор */
.rv-audit .rv-audit__prevbox::before{ inset:-6px; border:0;
  background:conic-gradient(from 0deg,transparent 0 72%,rgba(245,197,66,.75) 100%);
  -webkit-mask:radial-gradient(farthest-side,transparent calc(100% - 2px),#000 calc(100% - 1.5px));
          mask:radial-gradient(farthest-side,transparent calc(100% - 2px),#000 calc(100% - 1.5px));
  animation:rvAuSpin 2.6s linear infinite; }
.rv-audit .rv-audit__prev div:nth-child(2) .rv-audit__prevbox::before{ animation-delay:-.9s; animation-duration:3.1s; }
.rv-audit .rv-audit__prev div:nth-child(3) .rv-audit__prevbox::before{ animation-delay:-1.7s; animation-duration:3.6s; }

/* проверка: луч сканирования идёт по карточке сверху вниз */
.rv-audit .rv-audit__card > .rv-audit__beam{ position:absolute; z-index:3; left:0; right:0; top:0; height:120px; pointer-events:none; opacity:0;
  background:linear-gradient(180deg,transparent,rgba(245,197,66,.10) 70%,rgba(255,236,170,.55) 99%,transparent); }
.rv-audit.is-load .rv-audit__beam{ opacity:1; animation:rvAuBeam 2.2s cubic-bezier(.45,0,.2,1) infinite; }
@keyframes rvAuBeam{ 0%{ transform:translateY(-120px); } 100%{ transform:translateY(var(--au-h,480px)); } }
.rv-audit.is-load .rv-audit__bar i{ background:linear-gradient(90deg,#F5C542,#FCE7A2,#F5C542); background-size:200% 100%; animation:rvAuBar 1.2s linear infinite; }
@keyframes rvAuBar{ to{ background-position:-200% 0; } }

/* результат: кольца светятся, строки проявляются по очереди */
.rv-audit .rv-audit__dial .fg{ filter:drop-shadow(0 0 6px currentColor); }
.rv-audit.is-done .rv-audit__dial{ animation:rvAuPop .7s cubic-bezier(.34,1.5,.64,1) both; }
.rv-audit.is-done .rv-audit__dial:nth-child(2){ animation-delay:.1s; } .rv-audit.is-done .rv-audit__dial:nth-child(3){ animation-delay:.2s; }
.rv-audit.is-done .rv-audit__list li{ animation:rvAuUp .55s cubic-bezier(.22,1,.36,1) both; }
.rv-audit.is-done .rv-audit__list li:nth-child(1){ animation-delay:.35s; } .rv-audit.is-done .rv-audit__list li:nth-child(2){ animation-delay:.42s; }
.rv-audit.is-done .rv-audit__list li:nth-child(3){ animation-delay:.49s; } .rv-audit.is-done .rv-audit__list li:nth-child(4){ animation-delay:.56s; }
.rv-audit.is-done .rv-audit__list li:nth-child(5){ animation-delay:.63s; } .rv-audit.is-done .rv-audit__list li:nth-child(n+6){ animation-delay:.7s; }
@keyframes rvAuPop{ from{ opacity:0; transform:scale(.7); } to{ opacity:1; transform:none; } }
@keyframes rvAuUp{ from{ opacity:0; transform:translateY(10px); } to{ opacity:1; transform:none; } }

/* появление блока: карточка «включается» */
.rv-au-js .rv-audit__wrap > div:first-child > *{ opacity:0; transform:translateY(24px); transition:opacity .8s ease, transform .9s cubic-bezier(.16,1,.3,1); }
.rv-au-js .rv-audit__card{ opacity:0; transform:translateY(40px) scale(.97); transition:opacity .9s ease .15s, transform 1.1s cubic-bezier(.16,1,.3,1) .15s, border-color .4s ease, box-shadow .4s ease; }
.rv-au-js .rv-audit__card::after{ transform:scaleX(0); transition:transform 1.4s cubic-bezier(.16,1,.3,1) .6s; }
.rv-au-js.rv-au-in .rv-audit__wrap > div:first-child > *{ opacity:1; transform:none; }
.rv-au-js.rv-au-in .rv-audit__wrap > div:first-child > *:nth-child(2){ transition-delay:.08s; }
.rv-au-js.rv-au-in .rv-audit__wrap > div:first-child > *:nth-child(3){ transition-delay:.16s; }
.rv-au-js.rv-au-in .rv-audit__wrap > div:first-child > *:nth-child(4){ transition-delay:.24s; }
.rv-au-js.rv-au-in .rv-audit__card{ opacity:1; transform:none; }
.rv-au-js.rv-au-in .rv-audit__card::after{ transform:scaleX(1); }

@media(max-width:700px){
  .rv-audit .rv-audit__console{ margin:-20px -18px 18px; padding:12px 16px; font-size:.56rem; }
  .rv-audit__console span{ margin-left:6px; }
  .rv-audit .rv-audit__card .rv-audit__form > .rv-audit__pre{ top:29px; left:20px; font-size:.82rem; }
  .rv-audit .rv-audit__form input.rv-audit__input{ padding-left:86px; }
  .rv-audit .rv-audit__card > .rv-audit__radar{ width:220px; height:220px; right:-100px; top:-70px; opacity:.55; }
  @keyframes rvAuLaser{ 0%,100%{ transform:translateX(0); } 50%{ transform:translateX(52vw); } }
}
@media(prefers-reduced-motion:reduce){
  .rv-audit__radar i,.rv-audit .rv-audit__prevbox::before,.rv-audit .rv-audit__form::after,.rv-audit .rv-audit__go::after,
  .rv-audit__console b::before,.rv-audit.is-load .rv-audit__beam{ animation:none!important; }
  .rv-au-js .rv-audit__wrap > div:first-child > *,.rv-au-js .rv-audit__card{ opacity:1; transform:none; }
}
</style>
<?php
$rv_au_en = (function_exists('pll_current_language') && pll_current_language() == 'en');
/* Ключ Google PageSpeed Insights.
   Без ключа API тоже отвечает, но режет частоту запросов с одного адреса.
   Получить: console.cloud.google.com -> APIs -> PageSpeed Insights API -> Credentials.
   Вставьте строку между кавычками — больше ничего менять не нужно. */
$rv_psi_key = 'AIzaSyCC_avHgttFT38SJe9uca0KANQK6gJ2UyM';
?>
<div class="rv-audit__wrap">
	<div>
		<div class="rv-audit__chip"><i></i><?php echo $rv_au_en ? 'Free audit' : 'Експрес-аудит'; ?></div>
		<h2 class="rv-audit__title"><?php echo $rv_au_en ? 'Check <em>your site</em>' : 'Перевірте <em>свій сайт</em>'; ?></h2>
		<p class="rv-audit__lead"><?php echo $rv_au_en
			? 'Enter the address — in about twenty seconds you will see speed, the mobile version and the SEO basics.'
			: 'Введіть адресу — приблизно за двадцять секунд побачите швидкість, мобільну версію та SEO-базу.'; ?></p>
		<ul class="rv-audit__facts">
			<li><?php echo $rv_au_en ? 'Data straight from Google PageSpeed Insights' : 'Дані напряму з Google PageSpeed Insights'; ?></li>
			<li><?php echo $rv_au_en ? 'Measured the way a phone on 4G sees your site' : 'Міряємо так, як бачить ваш сайт телефон на 4G'; ?></li>
			<li><?php echo $rv_au_en ? 'No sign-up: the result appears right on this page' : 'Без реєстрації: результат з’являється прямо тут'; ?></li>
		</ul>
	</div>

	<div class="rv-audit__card">
		<span class="rv-audit__radar" aria-hidden="true"><i></i></span>
		<span class="rv-audit__beam" aria-hidden="true"></span>
		<div class="rv-audit__console" aria-hidden="true">
			<i></i><i></i><i></i>
			<span>revive / site-scanner</span>
			<b><em class="st-ready">Ready</em><em class="st-scan">Scanning</em><em class="st-done">Done</em></b>
		</div>
		<div class="rv-audit__form">
			<span class="rv-audit__pre" aria-hidden="true">https://</span>
			<input class="rv-audit__input" id="rv-audit-url" type="url" inputmode="url" autocomplete="url"
			       placeholder="<?php echo $rv_au_en ? 'yoursite.com' : 'vashsite.com'; ?>"
			       aria-label="<?php echo $rv_au_en ? 'Website address' : 'Адреса сайту'; ?>">
			<button type="button" class="rv-audit__go" id="rv-audit-go"><?php echo $rv_au_en ? 'Check' : 'Перевірити'; ?></button>
		</div>
		<p class="rv-audit__hint"><?php echo $rv_au_en
			? 'The check runs on Google servers and takes 15–30 seconds. We do not store the address.'
			: 'Перевірка йде на серверах Google і триває 15–30 секунд. Адресу ми не зберігаємо.'; ?></p>
		<p class="rv-audit__err" id="rv-audit-err"></p>

		<div class="rv-audit__idle">
			<div class="rv-audit__prev">
				<?php foreach (($rv_au_en ? array('Speed','SEO','Tech basics') : array('Швидкість','SEO','Технічна база')) as $rv_a_l) : ?>
				<div><div class="rv-audit__prevbox">
					<svg viewBox="0 0 74 74" aria-hidden="true"><circle cx="37" cy="37" r="32"></circle></svg>
					<b>&mdash;</b>
				</div><span><?php echo esc_html($rv_a_l); ?></span></div>
				<?php endforeach; ?>
			</div>
			<p class="rv-audit__what"><?php echo $rv_au_en
				? 'We check: how fast the main content appears, page weight, the mobile version, HTTPS, the page title and description, and whether search engines can index it.'
				: 'Перевіряємо: як швидко з’являється основний вміст, вагу сторінки, мобільну версію, HTTPS, заголовок і опис сторінки та чи відкрита вона для пошуковиків.'; ?></p>
		</div>

		<div class="rv-audit__load">
			<div class="rv-audit__bar"><i id="rv-audit-bar"></i></div>
			<div class="rv-audit__status" id="rv-audit-status"></div>
		</div>

		<div class="rv-audit__res" id="rv-audit-res">
			<div class="rv-audit__site" id="rv-audit-site"></div>
			<div class="rv-audit__dials" id="rv-audit-dials"></div>
			<ul class="rv-audit__list" id="rv-audit-list"></ul>
			<div class="rv-audit__verdict" id="rv-audit-verdict"></div>
			<div class="rv-audit__act">
				<button type="button" class="rv-audit__again" id="rv-audit-again"><?php echo $rv_au_en ? 'Check another site' : 'Перевірити інший сайт'; ?></button>
			</div>
			<p class="rv-audit__src"><?php echo $rv_au_en
				? 'Source: Google PageSpeed Insights, mobile strategy. A single page, not the whole site.'
				: 'Джерело: Google PageSpeed Insights, мобільна стратегія. Одна сторінка, не весь сайт.'; ?></p>
		</div>
	</div>
</div>
<script>
/* появление блока аудита и высота хода луча сканирования */
(function(){
  'use strict';
  var root = document.getElementById('audit');
  if (!root) return;
  var card = root.querySelector('.rv-audit__card');
  function h(){ if (card) card.style.setProperty('--au-h', (card.offsetHeight + 40) + 'px'); }
  h(); window.addEventListener('resize', h, { passive:true });
  if (window.ResizeObserver && card) new ResizeObserver(h).observe(card);
  if (window.matchMedia('(prefers-reduced-motion: reduce)').matches || !('IntersectionObserver' in window)) return;
  root.classList.add('rv-au-js');
  var io = new IntersectionObserver(function(es){
    if (!es[0].isIntersecting) return;
    io.disconnect(); root.classList.add('rv-au-in');
  }, { threshold:.18 });
  io.observe(root);
  setTimeout(function(){ var r = root.getBoundingClientRect(); if (r.top < innerHeight && r.bottom > 0) root.classList.add('rv-au-in'); }, 500);
})();
</script>
<script>
(function(){
  'use strict';
  var root = document.getElementById('audit');
  if (!root) return;
  var EN  = <?php echo $rv_au_en ? 'true' : 'false'; ?>;
  var KEY = <?php echo wp_json_encode($rv_psi_key); ?>;

  var T = EN ? {
    steps:['Loading the page…','Measuring speed on 4G…','Checking the mobile version…','Looking at the SEO basics…','Almost there…'],
    dials:[['Speed','performance'],['SEO','seo'],['Tech basics','best-practices']],
    bad:'Enter a site address, for example yoursite.com',
    fail:'Google could not open this page. Check the address, or the site may be blocking external checks.',
    slow:'The check is taking longer than usual. Try again in a minute.',
    checks:{ lcp:'Main content appears', weight:'Page weight', title:'Page title', desc:'Meta description',
             view:'Mobile version', https:'Secure connection (HTTPS)', crawl:'Open to search engines' },
    hints:{ lcp:'Above 2.5 s people start leaving before they see anything.',
            weight:'Over 3 MB is expensive traffic and a slow first screen on mobile.',
            title:'Without it search engines invent the title of your snippet themselves.',
            desc:'The line under the link in search results — it decides whether people click.',
            view:'Without it the phone shows the desktop layout scaled down.',
            https:'Without a certificate the browser marks the site as “not secure”.',
            crawl:'A page closed from indexing simply does not exist for Google.' },
    ok:'ok', warn:'weak', err:'problem',
    v:[ 'The site is in good shape: the basics hold up. The growth points are in content and conversion, not in the code.',
        'The middle ground: it works, but the speed and the basics are losing you part of the traffic. Most of it can be fixed without a redesign.',
        'There are serious problems: at this speed a large share of visitors leave before the first screen appears. This is worth dealing with first.' ]
  } : {
    steps:['Завантажуємо сторінку…','Міряємо швидкість на 4G…','Перевіряємо мобільну версію…','Дивимось SEO-базу…','Майже готово…'],
    dials:[['Швидкість','performance'],['SEO','seo'],['Технічна база','best-practices']],
    bad:'Введіть адресу сайту, наприклад vashsite.com',
    fail:'Google не зміг відкрити цю сторінку. Перевірте адресу — або сайт закритий від зовнішніх перевірок.',
    slow:'Перевірка триває довше за звичайне. Спробуйте ще раз за хвилину.',
    checks:{ lcp:'Основний вміст з’являється', weight:'Вага сторінки', title:'Заголовок сторінки', desc:'Опис для пошуку',
             view:'Мобільна версія', https:'Захищене з’єднання (HTTPS)', crawl:'Відкрито для пошуковиків' },
    hints:{ lcp:'Понад 2,5 с — і люди йдуть, не дочекавшись першого екрана.',
            weight:'Більше 3 МБ — це дорогий трафік і повільний перший екран на телефоні.',
            title:'Без нього пошуковик сам вигадує заголовок вашого сніпета.',
            desc:'Рядок під посиланням у видачі — від нього залежить, чи клікнуть.',
            view:'Без нього телефон показує десктопну версію, зменшену вдвічі.',
            https:'Без сертифіката браузер позначає сайт як «не захищений».',
            crawl:'Сторінка, закрита від індексації, для Google просто не існує.' },
    ok:'норма', warn:'слабко', err:'проблема',
    v:[ 'Сайт у нормі: база тримається. Точки зростання — у контенті й конверсії, а не в коді.',
        'Середина: працює, але швидкість і база забирають частину трафіку. Більшість правиться без редизайну.',
        'Є серйозні проблеми: на такій швидкості значна частина відвідувачів іде ще до першого екрана. З цього варто починати.' ]
  };

  var elUrl = document.getElementById('rv-audit-url'), elGo = document.getElementById('rv-audit-go'),
      elErr = document.getElementById('rv-audit-err'), elBar = document.getElementById('rv-audit-bar'),
      elStat = document.getElementById('rv-audit-status'), elDials = document.getElementById('rv-audit-dials'),
      elList = document.getElementById('rv-audit-list'), elVerd = document.getElementById('rv-audit-verdict'),
      elSite = document.getElementById('rv-audit-site');
  var timerBar = null, timerStat = null, running = false, summary = '';

  function norm(v){
    v = (v || '').trim();
    if (!v) return '';
    if (!/^https?:\/\//i.test(v)) v = 'https://' + v;
    /* грубая проверка: домен с точкой и без пробелов */
    if (!/^https?:\/\/[^\s\/]+\.[^\s\/]{2,}/i.test(v)) return '';
    return v;
  }
  function color(score){ return score >= 90 ? 'var(--ok)' : score >= 50 ? 'var(--warn)' : 'var(--bad)'; }
  function mark(state){ return state === 'ok' ? '✓' : state === 'warn' ? '!' : '✕'; }
  function markColor(state){ return state === 'ok' ? 'var(--ok)' : state === 'warn' ? 'var(--warn)' : 'var(--bad)'; }

  function start(){
    var url = norm(elUrl.value);
    if (!url){ root.classList.add('is-err'); elErr.textContent = T.bad; return; }
    if (running) return;
    running = true;
    root.classList.remove('is-err','is-done');
    root.classList.add('is-load');
    elGo.disabled = true;

    /* прогресс честный только по времени: API не отдаёт стадии,
       поэтому шкала идёт к 90% за 25 секунд и добирает на ответе */
    var p = 0, i = 0;
    elBar.style.width = '4%';
    elStat.textContent = T.steps[0];
    timerBar = setInterval(function(){ p = Math.min(90, p + 3.5); elBar.style.width = p + '%'; }, 900);
    timerStat = setInterval(function(){ i = (i + 1) % T.steps.length; elStat.textContent = T.steps[i]; }, 4200);

    var api = 'https://www.googleapis.com/pagespeedonline/v5/runPagespeed?url=' + encodeURIComponent(url) +
              '&strategy=mobile&category=performance&category=seo&category=best-practices' +
              (KEY ? '&key=' + encodeURIComponent(KEY) : '');

    var done = false;
    var guard = setTimeout(function(){ if (!done){ done = true; stop(); fail(T.slow); } }, 60000);

    fetch(api).then(function(r){ return r.json(); }).then(function(d){
      if (done) return; done = true; clearTimeout(guard);
      if (!d || !d.lighthouseResult){ stop(); fail(T.fail); return; }
      show(url, d.lighthouseResult);
    }).catch(function(){
      if (done) return; done = true; clearTimeout(guard);
      stop(); fail(T.fail);
    });
  }
  function stop(){
    clearInterval(timerBar); clearInterval(timerStat);
    root.classList.remove('is-load');
    elGo.disabled = false; running = false;
  }
  function fail(msg){ root.classList.add('is-err'); elErr.textContent = msg; }

  function show(url, lh){
    elBar.style.width = '100%';
    stop();
    var cats = lh.categories || {}, aud = lh.audits || {};
    var scores = {};
    T.dials.forEach(function(d){ scores[d[1]] = Math.round(((cats[d[1]] || {}).score || 0) * 100); });

    /* циферблаты */
    elDials.innerHTML = '';
    T.dials.forEach(function(d){
      var v = scores[d[1]];
      var box = document.createElement('div');
      box.className = 'rv-audit__dial';
      box.innerHTML = '<div class="rv-audit__dialbox">' +
        '<svg viewBox="0 0 74 74"><circle class="bg" cx="37" cy="37" r="32"></circle>' +
        '<circle class="fg" cx="37" cy="37" r="32" style="stroke:' + color(v) + '"></circle></svg>' +
        '<b style="color:' + color(v) + '">' + v + '</b></div><span>' + d[0] + '</span>';
      elDials.appendChild(box);
      var fg = box.querySelector('.fg');
      setTimeout(function(){ fg.style.strokeDashoffset = String(201 - 201 * v / 100); }, 60);
    });

    /* проверки: цифры из аудитов Lighthouse */
    var lcp = (aud['largest-contentful-paint'] || {}).numericValue;
    var bytes = (aud['total-byte-weight'] || {}).numericValue;
    var rows = [];
    if (lcp != null){
      var sec = lcp / 1000;
      rows.push(['lcp', sec < 2.5 ? 'ok' : sec < 4 ? 'warn' : 'err', sec.toFixed(1) + ' ' + (EN ? 's' : 'с')]);
    }
    if (bytes != null){
      var mb = bytes / 1048576;
      rows.push(['weight', mb < 2 ? 'ok' : mb < 3.5 ? 'warn' : 'err', mb.toFixed(1) + ' ' + (EN ? 'MB' : 'МБ')]);
    }
    function pass(id, key){
      var a = aud[id]; if (!a) return;
      var s = a.score === 1 ? 'ok' : a.score === 0 ? 'err' : 'warn';
      rows.push([key, s, s === 'ok' ? T.ok : s === 'warn' ? T.warn : T.err]);
    }
    pass('document-title','title');
    pass('meta-description','desc');
    pass('viewport','view');
    pass('is-on-https','https');
    pass('is-crawlable','crawl');

    elList.innerHTML = '';
    rows.forEach(function(r){
      var li = document.createElement('li');
      li.innerHTML = '<i style="color:' + markColor(r[1]) + '">' + mark(r[1]) + '</i>' +
                     '<div>' + T.checks[r[0]] + '<u>' + T.hints[r[0]] + '</u></div>' +
                     '<b>' + r[2] + '</b>';
      elList.appendChild(li);
    });

    var perf = scores.performance;
    elVerd.textContent = perf >= 85 ? T.v[0] : perf >= 50 ? T.v[1] : T.v[2];
    elSite.textContent = url.replace(/^https?:\/\//,'').replace(/\/$/,'');
    root.classList.add('is-done');

    summary = (EN ? 'Express audit: ' : 'Експрес-аудит: ') + elSite.textContent +
      ' — ' + T.dials.map(function(d){ return d[0] + ' ' + scores[d[1]]; }).join(', ') +
      (lcp != null ? ', LCP ' + (lcp/1000).toFixed(1) + 's' : '');
  }

  document.getElementById('rv-audit-again').addEventListener('click', function(){
    root.classList.remove('is-done','is-err');
    elUrl.value = ''; elUrl.focus();
  });
  elGo.addEventListener('click', start);
  elUrl.addEventListener('keydown', function(e){ if (e.key === 'Enter'){ e.preventDefault(); start(); } });
})();
</script>
</section>
<!-- ===== /RV AUDIT ===== -->


<?php /* ============================================================
   LEGACY SERVICES 01–06 ARE TEMPORARILY HIDDEN.
   Their markup and ACF bindings are preserved for an easy rollback.
   ============================================================ */ ?>
<?php if ( false ) : ?>
<section class="s5" id="s5">
	<div class="s5__abs-img"><img class="s5__web-image" src="https://www.revive-agency.com/wp-content/uploads/2026/08/web777.png" alt="<?php echo pll_current_language()=='en' ? 'Responsive websites and mobile applications by Revive Agency' : 'Адаптивні сайти та мобільні застосунки від Revive Agency'; ?>" width="800" height="716" loading="lazy" decoding="async"></div>
	<div class="wrapper">
		<div class="s5__inner">
			<?php the_field('infa_sekczii_5'); ?>
			<!--<div class="s5__text-title">Разработка<br> сайтов,<br> мобильных<br> приложений</div>
			<div class="s5__text">Landing page, сайт-визитка, интернет-магазин</div>
			<div class="s5__text">Полный цикл созданий мобильных приложений<br> для IOS и Android</div>-->
	   </div>
		
	</div>
</section>
<section class="s3" id="s3">
	<div class="s3__abs">
		<div class="s3__abs-wrapper">
		<div class="s3__abs-inner">
				<img class="g1 s3__seo-image" src="https://www.revive-agency.com/wp-content/uploads/2026/08/seo7777.png" alt="<?php echo pll_current_language()=='en' ? 'SEO promotion and website audit' : 'SEO-просування та аудит сайту'; ?>" width="800" height="716" loading="lazy" decoding="async">
		</div>
		</div>
	</div>
	<div class="wrapper">
		<!--<div class="s3__text-title">Seo продвижение</div>
		<div class="s3__text">Детальный результат по каждому ключу</div>
		<div class="s3__img-box">
			<p><img src="https://www.ice-agency.com.ua/wp-content/uploads/2020/05/seo.png" width="60%" height="60%" /></p>
		</div>
		<div class="s3__text-title" id="s33">Контекстная реклама</div>
	<div class="s3__link">
				<a href=""><img src="/wp-content/uploads/2020/05/google-an.png"></a>
				<a href=""><img src="/wp-content/uploads/2020/05/google-ad.png"></a>
	</div>
		<div class="s3__text">Сертифицированы партнерами</div>-->
		<?php the_field('infa_3'); ?>
	</div>
</section>
<section class="s4" id="s4">
	<div class="wrapper">
		<div class="s4__inner">
			<div class="s4__left">
				<?php the_field('infa_sekczii_4'); ?>
			<!--<div class="s4__text-title">Таргетированная<br> реклама</div>
			<div class="s4__link">
				<a href=""><img src="/wp-content/uploads/2020/05/logo-insta.png"></a>
				<a href=""><img src="/wp-content/uploads/2020/05/logo-facebook.png"></a>
			</div>
	        <div class="s4__text">Точечно найдем ваших самых<br> горячих клиентов!</div>-->
	    </div>
		
		<div class="s4__right anim">
			<img class="s4__target-image"
			     src="https://www.revive-agency.com/wp-content/uploads/2026/08/soc.png"
			     alt="" width="800" height="716" aria-hidden="true" loading="lazy" decoding="async">
		</div>
			</div>
	</div>
</section>
<!-- CRM: сначала содержание, затем иллюстрация. На ПК GIF остаётся справа
     за счёт absolute-позиционирования, на телефоне естественно идёт ниже текста. -->
<section class="s3 crm" id="s3-crm">
	<div class="wrapper">
		<div class="s3__text">
			<?php the_field('infa_realizaczii_crm'); ?>
		</div>
		<div class="s3__abs">
			<div class="s3__abs-wrapper">
				<div class="s3__abs-inner">
					<img class="g1 s3__crm-image" src="https://www.revive-agency.com/wp-content/uploads/2026/08/crm111.png" alt="<?php echo pll_current_language()=='en' ? 'CRM integration and sales automation' : 'Інтеграція CRM та автоматизація продажів'; ?>" width="800" height="716" loading="lazy" decoding="async">
				</div>
			</div>
		</div>
	</div>
</section>
<!--   -->

<?php /* Блок скидки (section.s6, «GET A DISCOUNT FOR SERVICES») удалён.
         Смысл цифры не считывался, а его кнопка была четвёртым одинаковым
         CTA на странице. Ссылка #s6 в меню, если она есть, теперь ведёт
         в пустоту — стоит проверить настройки меню. */ ?>
<section class="s8" id="s8">
	<div class="s8__ads-img">
		<img class="s8_img s8__uiux-image"
		     src="https://www.revive-agency.com/wp-content/uploads/2026/08/design7.png"
		     alt="<?php echo pll_current_language()=='en' ? 'UI and UX design across desktop and mobile devices' : 'UI та UX дизайн для комп’ютерів і мобільних пристроїв'; ?>"
		     width="800" height="716" loading="lazy" decoding="async">
	</div>
	<div class="wrapper">
		<?php the_field('infa_sekczii_8'); ?>
	<!--<div class="s8__text-title">Работа с блогерами</div>
	<div class="s8__link">
		<a href=""><img src="/wp-content/uploads/2020/05/logo-insta.png"></a>
		<a href=""><img src="/wp-content/uploads/2020/05/logo-key.png"></a>
		<a href=""><img src="/wp-content/uploads/2020/05/logo3.png"></a>
		<a href=""><img src="/wp-content/uploads/2020/05/logo-tube.png"></a>
	</div>
	<div class="s8__text">Интеграции, обзоры,<br> прероллы вашего продукта</div>-->
		
	</div>
</section>
<?php endif; ?>
<!-- ===== RV TRUST SCENE v1.0: клиенты и партнёрства (идея из devils.inc) ===== -->
<?php
/*
 * Сцена «Партнерства» вместо старых бегущих лент логотипов (section.s9).
 * Логотипы берутся из тех же ACF-полей, что и раньше (internet_magaziny,
 * biznes_sajty): добавили клиента в админке — карточка появится сама.
 *
 * Откат на старый блок: $rv_trust_scene = false.
 */
$rv_trust_scene = true;

/*
 * ЖИВЫЕ ФОТО. Сюда — реальные фото команды, офиса, созвонов, выступлений
 * (JPG/WebP ~1200px по длинной стороне). Они встанут в ближние слоты сцены
 * чёрно-белыми и оживут цветом при наведении. Пока массив пуст — сцена
 * собирается только из логотипов. Не больше 6 фото.
 *
 * array('src' => 'https://www.revive-agency.com/wp-content/uploads/2026/09/team-1.webp', 'alt' => 'Команда Revive Agency'),
 */
$rv_trust_photos = array();

if ($rv_trust_scene) :
	$rv_trust_en = ! function_exists('pll_current_language') || pll_current_language() === 'en';

	/* логотипы из ACF: <a href><img src alt></a>, дубликаты убираем по src */
	$rv_trust_logos = array();
	$rv_trust_seen = array();
	foreach (array('internet_magaziny', 'biznes_sajty') as $rv_trust_field) {
		$rv_trust_html = function_exists('get_field') ? (string) get_field($rv_trust_field) : '';
		if ($rv_trust_html === '') continue;
		if (! preg_match_all('~(?:<a\b([^>]*)>\s*)?(<img\b[^>]*>)~i', $rv_trust_html, $rv_trust_matches, PREG_SET_ORDER)) continue;
		foreach ($rv_trust_matches as $rv_trust_match) {
			if (! preg_match('~\bsrc=["\']([^"\']+)["\']~i', $rv_trust_match[2], $rv_trust_src)) continue;
			$rv_trust_key = strtolower($rv_trust_src[1]);
			if (isset($rv_trust_seen[$rv_trust_key])) continue;
			$rv_trust_seen[$rv_trust_key] = true;
			$rv_trust_alt = preg_match('~\balt=["\']([^"\']*)["\']~i', $rv_trust_match[2], $rv_trust_a) ? html_entity_decode($rv_trust_a[1], ENT_QUOTES) : '';
			$rv_trust_href = (! empty($rv_trust_match[1]) && preg_match('~\bhref=["\']([^"\']+)["\']~i', $rv_trust_match[1], $rv_trust_h)) ? html_entity_decode($rv_trust_h[1], ENT_QUOTES) : '';
			$rv_trust_logos[] = array('type' => 'logo', 'src' => $rv_trust_src[1], 'alt' => $rv_trust_alt, 'href' => $rv_trust_href);
		}
	}

	/*
	 * Слоты сцены: x%, y%, глубина (1 — дальний, за словом; 3 — ближний),
	 * базовая ширина карточки в px на экране 1440. Мобильная раскладка —
	 * отдельные 8 слотов, остальные карточки на телефоне скрыты.
	 */
	$rv_trust_slots = array(
		/* верхняя зона: два ряда над бегущей строкой */
		/* ширины карточек выровнены: раньше 146–226 px, мелкие «дальние»
		   терялись; теперь 172–208 px, разница видна, но не бросается в глаза */
		array( 7, 11, 2, 192), array(19, 28, 1, 172), array(32, 11, 3, 208), array(45, 28, 1, 172),
		array(57, 11, 2, 192), array(69, 28, 1, 172), array(81, 11, 3, 208), array(93, 28, 1, 172),
		/* нижняя зона: по бокам от круглой кнопки */
		array( 9, 78, 3, 208), array(23, 91, 1, 172), array(34, 74, 2, 192),
		array(66, 74, 2, 192), array(77, 91, 1, 172), array(92, 78, 3, 208),
	);
	/* телефон: бегущей строки нет — 14 логотипов рядами по 3,
	   в середине ряд из двух по бокам от круглой кнопки */
	$rv_trust_mobile = array(
		array(17,  9, 98), array(50, 12, 98), array(83,  9, 98),
		array(17, 29, 98), array(50, 31, 98), array(83, 29, 98),
		array(14, 49, 92), array(86, 49, 92),
		array(17, 69, 98), array(50, 71, 98), array(83, 69, 98),
		array(17, 89, 98), array(50, 91, 98), array(83, 89, 98),
	);
	/* полоса слова (≈38–62% высоты сцены) всегда свободна от карточек;
	   фото занимают ближние слоты, логотипы — оставшиеся по порядку */
	$rv_trust_photo_slots = array(2, 6, 8, 13, 0, 4);

	$rv_trust_items = array();
	$rv_trust_used = array();
	foreach (array_slice($rv_trust_photos, 0, 6) as $rv_trust_i => $rv_trust_photo) {
		if (empty($rv_trust_photo['src'])) continue;
		$rv_trust_slot = $rv_trust_photo_slots[$rv_trust_i];
		$rv_trust_used[$rv_trust_slot] = true;
		$rv_trust_items[] = array_merge(array('type' => 'photo', 'alt' => '', 'href' => ''), $rv_trust_photo, array('slot' => $rv_trust_slot));
	}
	$rv_trust_free = array();
	foreach ($rv_trust_slots as $rv_trust_i => $rv_trust_s) {
		if (! isset($rv_trust_used[$rv_trust_i])) $rv_trust_free[] = $rv_trust_i;
	}
	foreach ($rv_trust_logos as $rv_trust_logo) {
		if (! $rv_trust_free) break;
		$rv_trust_logo['slot'] = array_shift($rv_trust_free);
		$rv_trust_items[] = $rv_trust_logo;
	}
?>
<section class="rv-trust" id="s9" data-rv-trust aria-labelledby="rv-trust-title">
	<header class="rv-trust__head" data-rv-reveal>
		<div>
			<p class="rv-trust__eyebrow" data-rv-item><i aria-hidden="true"></i>05 / <?php echo $rv_trust_en ? 'Selected partnerships' : 'Вибрані партнерства'; ?></p>
			<h2 class="rv-trust__title" id="rv-trust-title"><?php echo $rv_trust_en ? 'Selected <em>clients</em>' : 'Вибрані <em>клієнти</em>'; ?></h2>
		</div>
		<p class="rv-trust__lead" data-rv-item>
			<small><?php echo $rv_trust_en ? 'Trusted worldwide' : 'Нам довіряють'; ?></small>
			<?php echo $rv_trust_en ? 'Brands we&rsquo;ve helped move forward — from Kyiv to California.' : 'Бренди, яким ми допомогли рухатися вперед — від Києва до Каліфорнії.'; ?>
		</p>
	</header>

	<div class="rv-trust__stage">
		<div class="rv-trust__word" aria-hidden="true">
			<div class="rv-trust__track">
				<div class="rv-trust__group">
					<span>Partnerships</span><i></i><span class="is-outline">Trust</span><i></i><span>Growth</span><i></i>
				</div>
			</div>
		</div>

		<?php if ($rv_trust_items) : ?>
		<ul class="rv-trust__cloud" aria-label="<?php echo $rv_trust_en ? 'Clients' : 'Клієнти'; ?>">
			<?php foreach ($rv_trust_items as $rv_trust_n => $rv_trust_item) :
				$rv_trust_s = $rv_trust_slots[$rv_trust_item['slot']];
				$rv_trust_m = isset($rv_trust_mobile[$rv_trust_n]) ? $rv_trust_mobile[$rv_trust_n] : null;
				$rv_trust_w = $rv_trust_item['type'] === 'photo' ? round($rv_trust_s[3] * 1.18) : $rv_trust_s[3];
				$rv_trust_style = sprintf('--x:%d%%;--y:%d%%;--d:%d;--w:%d;--f:%.1fs;--fd:-%.1fs', $rv_trust_s[0], $rv_trust_s[1], $rv_trust_s[2], $rv_trust_w, 6 + ($rv_trust_n % 4) * .9, fmod($rv_trust_n * 1.3, 7));
				if ($rv_trust_m) $rv_trust_style .= sprintf(';--mx:%d%%;--my:%d%%;--mw:%d', $rv_trust_m[0], $rv_trust_m[1], $rv_trust_m[2]);
				$rv_trust_tag = $rv_trust_item['href'] !== '' ? 'a' : 'div';
			?>
			<li class="rv-trust__item<?php echo $rv_trust_m ? '' : ' is-desktop-only'; ?>" data-depth="<?php echo (int) $rv_trust_s[2]; ?>" style="<?php echo esc_attr($rv_trust_style); ?>">
				<<?php echo $rv_trust_tag; ?> class="rv-trust__card rv-trust__card--<?php echo esc_attr($rv_trust_item['type']); ?>"<?php if ($rv_trust_tag === 'a') : ?> href="<?php echo esc_url($rv_trust_item['href']); ?>" target="_blank" rel="nofollow noopener"<?php endif; ?>>
					<img src="<?php echo esc_url($rv_trust_item['src']); ?>" alt="<?php echo esc_attr($rv_trust_item['alt']); ?>" loading="lazy" decoding="async"<?php echo $rv_trust_item['type'] === 'logo' ? ' width="150" height="150"' : ''; ?>>
					<?php if ($rv_trust_item['type'] === 'logo' && $rv_trust_item['alt'] !== '') : ?>
						<span class="rv-trust__name" aria-hidden="true"><?php echo esc_html($rv_trust_item['alt']); ?><?php echo $rv_trust_tag === 'a' ? ' ↗︎' : ''; ?></span>
					<?php endif; ?>
				</<?php echo $rv_trust_tag; ?>>
			</li>
			<?php endforeach; ?>
		</ul>
		<?php endif; ?>

		<a class="rv-trust__cta" href="#win1">
			<svg class="rv-trust__ring" viewBox="0 0 200 200" aria-hidden="true" focusable="false">
				<defs><path id="rvTrustRing" d="M100,100 m-86,0 a86,86 0 1,1 172,0 a86,86 0 1,1 -172,0"/></defs>
				<text><textPath href="#rvTrustRing" textLength="538" lengthAdjust="spacing">Start a project · Revive Agency · Start a project · Revive Agency ·</textPath></text>
			</svg>
			<span class="rv-trust__cta-core">
				<span><?php echo $rv_trust_en ? 'Become<br>a client' : 'Стати<br>клієнтом'; ?></span>
				<em aria-hidden="true">↗︎</em>
			</span>
		</a>
	</div>
</section>
<script id="rv-trust-scene">
/* REVIVE trust scene v1.0
   · слово-лента реагирует на скорость и направление скролла;
   · карточки разнесены по трём слоям глубины: параллакс от скролла и курсора
     (только ПК), лёгкое «дыхание» на CSS;
   · CTA-кружок притягивается к курсору;
   · всё стоит на паузе, пока сцена вне экрана. */
(function () {
	'use strict';
	var section = document.querySelector('[data-rv-trust]');
	if (!section || !window.requestAnimationFrame) return;
	var stage = section.querySelector('.rv-trust__stage');
	var track = section.querySelector('.rv-trust__track');
	var group = section.querySelector('.rv-trust__group');
	var cta = section.querySelector('.rv-trust__cta');
	if (!stage || !track || !group) return;

	var reduced = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
	var fine = window.matchMedia('(hover: hover) and (pointer: fine)').matches;
	var doc = document.documentElement;

	/* бесшовная петля: группа повторяется, пока лента не станет шире 2 экранов */
	function fill() {
		var copies = track.querySelectorAll('.rv-trust__group');
		for (var i = copies.length - 1; i >= 1; i--) track.removeChild(copies[i]);
		var width = group.getBoundingClientRect().width || 1;
		var need = Math.max(2, Math.ceil((window.innerWidth * 2) / width) + 1);
		for (var n = 1; n < need; n++) {
			var clone = group.cloneNode(true);
			clone.setAttribute('aria-hidden', 'true');
			track.appendChild(clone);
		}
		return width;
	}

	var unit = fill();
	if (reduced) { section.classList.add('is-static'); return; }

	var live = false;
	var raf = 0;
	var last = 0;
	var x = 0;
	var dir = -1;
	var boost = 0;
	var lastY = window.pageYOffset;
	var stageTop = 0;
	var stageH = 1;
	var px = 0, py = 0, tpx = 0, tpy = 0, sp = 0;
	var written = { px: null, py: null, sp: null };

	/* на телефоне строка скрыта CSS — тогда и цикл ленты не крутится */
	var word = section.querySelector('.rv-trust__word');
	var wordOn = true;
	function measure() {
		wordOn = !!word && window.getComputedStyle(word).display !== 'none';
		var r = stage.getBoundingClientRect();
		stageTop = r.top + window.pageYOffset;
		stageH = r.height || 1;
	}

	function write(name, value) {
		var v = Math.round(value * 1000) / 1000;
		if (written[name] === v) return;
		written[name] = v;
		stage.style.setProperty('--' + name, String(v));
	}

	function frame(now) {
		raf = 0;
		if (!live) return;
		var dt = last ? Math.min(.05, (now - last) / 1000) : .016;
		last = now;

		var y = window.pageYOffset;
		var dy = y - lastY;
		lastY = y;
		var v = (window.RVScroll && window.RVScroll.lenis) ? window.RVScroll.velocity() : dy;
		if (Math.abs(v) > .35) dir = v > 0 ? -1 : 1;
		boost += (Math.min(Math.abs(v), 70) * 11 - boost) * .085;

		if (wordOn) {
			x += dir * (64 + boost) * dt;
			if (x <= -unit) x += unit;
			else if (x > 0) x -= unit;
			track.style.transform = 'translate3d(' + x.toFixed(2) + 'px,0,0)';
		}

		if (fine) {
			px += (tpx - px) * .06;
			py += (tpy - py) * .06;
			var vh = (window.RVVH ? window.RVVH() : window.innerHeight) || doc.clientHeight;
			sp = Math.max(-1, Math.min(1, ((y + vh / 2) - (stageTop + stageH / 2)) / vh));
			write('px', px);
			write('py', py);
			write('sp', sp);
		}
		if (wordOn || fine) raf = window.requestAnimationFrame(frame);
	}

	function play() { if (!raf && live && !document.hidden) { last = 0; raf = window.requestAnimationFrame(frame); } }
	function pause() { if (raf) { window.cancelAnimationFrame(raf); raf = 0; } }

	if ('IntersectionObserver' in window) {
		new IntersectionObserver(function (entries) {
			live = entries[0].isIntersecting;
			section.classList.toggle('is-live', live);
			if (live) { measure(); play(); } else pause();
		}, { rootMargin: '160px 0px 160px 0px' }).observe(section);
	} else {
		live = true;
		section.classList.add('is-live');
		play();
	}
	document.addEventListener('visibilitychange', function () { if (document.hidden) pause(); else play(); });

	var lastWidth = window.innerWidth;
	window.addEventListener('resize', function () {
		measure();
		if (window.innerWidth !== lastWidth) { lastWidth = window.innerWidth; unit = fill(); }
	}, { passive: true });
	window.addEventListener('load', function () { measure(); unit = fill(); });
	if (document.fonts && document.fonts.ready) document.fonts.ready.then(function () { unit = fill(); measure(); }, function () {});

	if (!fine) return;

	stage.addEventListener('pointermove', function (event) {
		var r = stage.getBoundingClientRect();
		tpx = ((event.clientX - r.left) / r.width - .5) * 2;
		tpy = ((event.clientY - r.top) / r.height - .5) * 2;
	}, { passive: true });
	stage.addEventListener('pointerleave', function () { tpx = 0; tpy = 0; });

	if (cta) {
		var ctaFrame = 0;
		var next = null;
		var paint = function () {
			ctaFrame = 0;
			if (!next) return;
			cta.style.setProperty('--mx', next[0] + 'px');
			cta.style.setProperty('--my', next[1] + 'px');
		};
		cta.addEventListener('pointermove', function (event) {
			var r = cta.getBoundingClientRect();
			var dx = event.clientX - (r.left + r.width / 2);
			var dy = event.clientY - (r.top + r.height / 2);
			next = [Math.max(-22, Math.min(22, dx * .3)).toFixed(1), Math.max(-22, Math.min(22, dy * .3)).toFixed(1)];
			if (!ctaFrame) ctaFrame = window.requestAnimationFrame(paint);
		}, { passive: true });
		cta.addEventListener('pointerleave', function () {
			next = ['0', '0'];
			if (!ctaFrame) ctaFrame = window.requestAnimationFrame(paint);
		});
	}
}());
</script>
<style id="rv-trust-fix">
/* ===== КЛИЕНТЫ: правки v2 =====
   1) Логотипы одного визуального веса: скрипт ниже обрезает прозрачные поля
      PNG и задаёт ширину по формуле «равная площадь» — широкие знаки ниже,
      компактные крупнее. До его работы действует прежнее правило.
   2) Дальние карточки больше не тусклые: логотипы на всех — в полную яркость,
      рамка и подложка у дальних подтянуты к остальным.
   3) Бейдж «Стати клієнтом» крупнее: около 220 px на ПК вместо 168 px. */
.rv-trust__card--logo img.is-fit{aspect-ratio:auto;height:auto;max-width:none;max-height:none}
.rv-trust .rv-trust__card img,
.rv-trust__item[data-depth="1"] .rv-trust__card img{opacity:1}
.rv-trust__item[data-depth="1"] .rv-trust__card{
  border-color:rgba(245,244,239,.12);
  background:linear-gradient(155deg,rgba(255,255,255,.07),rgba(255,255,255,.012) 62%),rgba(9,10,54,.94);
  box-shadow:0 26px 52px rgba(0,0,18,.34),inset 0 1px 0 rgba(255,255,255,.07)}
.rv-trust [data-rv-fitting] .rv-trust__card--logo img{opacity:0}

.rv-trust .rv-trust__cta{width:clamp(136px,min(15vw,calc(var(--rv-lvh,1vh)*22)),220px)}
.rv-trust .rv-trust__cta-core{font-size:13px;gap:10px}
.rv-trust .rv-trust__cta-core em{font-size:22px}
.rv-trust .rv-trust__ring text{font-size:10.5px}
@media (max-width:767px){
  .rv-trust .rv-trust__cta{width:clamp(108px,calc(var(--rv-lvh,1vh)*14),118px)}
  .rv-trust .rv-trust__cta-core{font-size:11px}
  .rv-trust .rv-trust__ring text{font-size:11px}
}
</style>
<script id="rv-trust-fix-js">
(function(){
  'use strict';
  var root = document.querySelector('[data-rv-trust]');
  if (!root) return;
  var cloud = root.querySelector('.rv-trust__cloud');
  if (!cloud) return;
  var imgs = [].slice.call(cloud.querySelectorAll('.rv-trust__card--logo img'));
  if (!imgs.length) return;

  /* Обрезка прозрачных полей: возвращает картинку без полей и её пропорции */
  function trim(src, cb){
    var im = new Image();
    im.decoding = 'async';
    im.onload = function(){
      try {
        var w = im.naturalWidth, h = im.naturalHeight;
        var c = document.createElement('canvas'); c.width = w; c.height = h;
        var x = c.getContext('2d'); x.drawImage(im, 0, 0);
        var d = x.getImageData(0, 0, w, h).data, minx = w, miny = h, maxx = -1, maxy = -1;
        for (var y = 0; y < h; y++) for (var i = 0; i < w; i++) {
          if (d[(y * w + i) * 4 + 3] > 16) {
            if (i < minx) minx = i; if (i > maxx) maxx = i;
            if (y < miny) miny = y; if (y > maxy) maxy = y;
          }
        }
        if (maxx < 0) { cb(null); return; }
        var cw = maxx - minx + 1, ch = maxy - miny + 1;
        var o = document.createElement('canvas'); o.width = cw; o.height = ch;
        o.getContext('2d').drawImage(c, minx, miny, cw, ch, 0, 0, cw, ch);
        cb({ url: o.toDataURL('image/png'), ar: cw / ch });
      } catch (e) { cb(null); }
    };
    im.onerror = function(){ cb(null); };
    im.src = src;
  }

  /* Ширина в % от карточки (3:2) по формуле равной площади:
     w = K·√ar, но не шире 72% и не выше 46% высоты карточки. */
  var K = 27;
  function widthFor(ar){
    ar = Math.max(.3, Math.min(ar, 10));
    return Math.min(K * Math.sqrt(ar), 72, 46 * ar / 1.5);
  }

  var started = false;
  function run(){
    if (started) return;
    started = true;
    cloud.setAttribute('data-rv-fitting', '');
    var left = imgs.length;
    function done(){ if (--left <= 0) cloud.removeAttribute('data-rv-fitting'); }
    imgs.forEach(function(img){
      trim(img.getAttribute('src'), function(r){
        if (r) {
          img.removeAttribute('loading');
          img.src = r.url;
          img.style.width = widthFor(r.ar).toFixed(2) + '%';
          img.classList.add('is-fit');
        }
        done();
      });
    });
    /* страховка: логотипы не останутся скрытыми, даже если что-то не загрузилось */
    setTimeout(function(){ cloud.removeAttribute('data-rv-fitting'); }, 3000);
  }

  if ('IntersectionObserver' in window) {
    var io = new IntersectionObserver(function(es){
      if (!es[0].isIntersecting) return;
      io.disconnect(); run();
    }, { rootMargin: '600px 0px' });
    io.observe(root);
  } else {
    run();
  }
})();
</script>
<?php endif; ?>
<!-- ===== /RV TRUST SCENE ===== -->
<?php /* Старый блок клиентов (бегущие ленты). Выводится только при $rv_trust_scene = false. */ ?>
<?php if ( empty( $rv_trust_scene ) ) : ?>
<section class="s9" id="s9">
	<div class="s9__ads__img">
<img class="s9__img1" src="/wp-content/uploads/2020/05/head.png" alt="" aria-hidden="true" loading="lazy" decoding="async">
		<img class="s9__img2" src="https://www.revive-agency.com/wp-content/uploads/2020/05/cap.png" alt="" aria-hidden="true" loading="lazy" decoding="async">
		<img class="s9__img3" src="/wp-content/uploads/2020/05/gif-mail.gif" alt="" aria-hidden="true" loading="lazy" decoding="async">
	</div>
	<div class="wrapper">
		<?php
		/* Заголовок задаётся здесь, как и подзаголовок ниже.
		   В поле ACF zagolovok_sekczii_9 лежит старый текст в теге <center>:
		   из-за него заголовок центрировался и терял золотой контур на хвосте
		   (скрипт шаблона не размечает <em>, если внутри есть теги).
		   Вернуть управление из админки — заменить строку с $rv_s9_title на:
		   $rv_s9_title = trim(wp_kses((string) get_field('zagolovok_sekczii_9'), array('br' => array()))); */
		$rv_s9_en    = (function_exists('pll_current_language') && pll_current_language() == 'en');
		$rv_s9_title = $rv_s9_en ? 'SELECTED CLIENTS' : 'ВИБРАНІ КЛІЄНТИ';
		?>
		<div class="s9__text-title"><?php echo $rv_s9_title; ?></div>
		<?php the_field('infa_sekczii_9'); ?>
		<?php
		$rv_clients_first_row = function_exists('get_field')
			? trim((string) get_field('internet_magaziny'))
			: '';
		$rv_clients_second_row = function_exists('get_field')
			? trim((string) get_field('biznes_sajty'))
			: '';
		?>
	<ul class="s9__list">
<p><span style="color: #ffffff;"><strong>
	
	<?php if (pll_current_language() == 'en') { ?>
		Brands we've helped move forward.
	<?php } else { ?>
		Бренди, яким ми допомогли рухатися вперед.
	<?php } ?>
	</strong></span></p>
	<?php if ($rv_clients_first_row !== '') : ?>
		<div class="ticker ticker--rtl" data-ticker-direction="right-to-left">
			<div class="ticker-track" data-ticker-speed="60" style="animation-direction: normal;">
				<div class="logos"><?php echo $rv_clients_first_row; ?></div>
			</div>
		</div>
	<?php endif; ?>
	<?php if ($rv_clients_second_row !== '') : ?>
		<div class="ticker ticker--ltr" data-ticker-direction="left-to-right">
			<div class="ticker-track" data-ticker-speed="60" style="animation-direction: reverse;">
				<div class="logos"><?php echo $rv_clients_second_row; ?></div>
			</div>
		</div>
	<?php endif; ?>
		</ul>
	</div>
	<div class="mobil-view s9__mobil-img mobil-no">
		<div class="s9_abs-inner">
		<img class="s9__img1" src="/wp-content/uploads/2020/05/head.png" alt="" aria-hidden="true" loading="lazy" decoding="async">
		<img class="s9__img2" src="/wp-content/uploads/2020/05/cap.png" alt="" aria-hidden="true" loading="lazy" decoding="async">
		<img class="s9__img3" src="/wp-content/uploads/2020/05/gif-mail.gif" alt="" aria-hidden="true" loading="lazy" decoding="async">
		</div>
	</div>
	
</section>
<?php endif; ?>
<script>
/* ============================================================
   OUR CLIENTS — бегущие ленты логотипов
   1-я полоса: справа налево.  2-я полоса: слева направо.

   Скорость задаётся в ПИКСЕЛЯХ В СЕКУНДУ (data-ticker-speed),
   поэтому она одинакова на ПК и на мобильных при любой ширине ленты.
   (CSS-анимация translateX(-50%) так не умеет: при фиксированной
   длительности узкая лента едет медленнее широкой.)

   Скрипт полностью берёт движение на себя и глушит CSS-анимацию
   инлайном с important — так старый инициализатор темы не может
   перебить направление рядов.
   ============================================================ */
(function () {
  'use strict';

  var DEFAULT_SPEED = 60; // px/сек — «прежняя» скорость ленты

  function boot() {
    var tickers = document.querySelectorAll('section.s9 .ticker, .s9 .ticker');
    if (!tickers.length) return;

    document.documentElement.classList.add('rv-ticker-js');

    var mq = window.matchMedia || function () { return { matches: false }; };
    var reduced  = mq('(prefers-reduced-motion: reduce)').matches;
    var canHover = mq('(hover:hover) and (pointer:fine)').matches;
    var rows = [];

    function silence(row) {
      // инлайн + important перебивает и таблицу стилей, и скрипт темы
      row.track.style.setProperty('animation', 'none', 'important');
      row.track.style.setProperty('will-change', 'transform');
    }
    function measure(row) {
      row.half = row.track.scrollWidth / 2;
    }
    function paint(row) {
      row.track.style.transform = 'translate3d(' + row.x.toFixed(2) + 'px,0,0)';
    }

    Array.prototype.forEach.call(tickers, function (ticker, index) {
      var track = ticker.querySelector('.ticker-track');
      if (!track) return;

      var copies = track.querySelectorAll('.logos');
      if (!copies.length) return;

      // нормализуем содержимое: ровно две копии для бесшовной петли,
      // даже если старый скрипт темы уже что-то клонировал
      for (var i = copies.length - 1; i >= 1; i--) {
        copies[i].parentNode.removeChild(copies[i]);
      }
      var clone = track.querySelector('.logos').cloneNode(true);
      clone.setAttribute('aria-hidden', 'true');
      track.appendChild(clone);

      // направление: data-атрибут → класс → порядок полосы
      var attr = ticker.getAttribute('data-ticker-direction');
      var toRight = attr ? (attr === 'left-to-right')
                  : ticker.classList.contains('ticker--ltr') ? true
                  : (index === 1);

      var speed = parseFloat(track.getAttribute('data-ticker-speed'));
      if (!isFinite(speed) || speed <= 0) speed = DEFAULT_SPEED;

      var row = {
        track: track, ticker: ticker,
        dir: toRight ? 1 : -1,
        speed: speed, half: 0, x: 0, hover: false
      };

      silence(row);
      measure(row);
      row.x = toRight ? -row.half : 0; // правой ленте нужен запас слева
      paint(row);
      rows.push(row);

      if (canHover) {
        ticker.addEventListener('mouseenter', function () { row.hover = true; });
        ticker.addEventListener('mouseleave', function () { row.hover = false; });
      }
    });

    if (!rows.length) return;

    // пересчёт после догрузки картинок — до неё scrollWidth занижен
    window.addEventListener('load', function () {
      rows.forEach(function (r) {
        silence(r); measure(r);
        if (r.x < -r.half) r.x = -r.half;
        paint(r);
      });
    });

    if (reduced) return; // ленты остаются статичными

    var raf = 0, last = 0, visible = true;

    function frame(now) {
      if (!last) last = now;
      var dt = (now - last) / 1000;
      last = now;
      if (dt > 0.05) dt = 0.05; // после фонового таба не «прыгаем»

      for (var i = 0; i < rows.length; i++) {
        var r = rows[i];
        if (r.hover || !r.half) continue;
        r.x += r.dir * r.speed * dt;
        if (r.x <= -r.half) r.x += r.half;
        else if (r.x >= 0) r.x -= r.half;
        paint(r);
      }
      raf = requestAnimationFrame(frame);
    }
    function start() {
      if (!raf && visible && !document.hidden) { last = 0; raf = requestAnimationFrame(frame); }
    }
    function stop() {
      if (raf) { cancelAnimationFrame(raf); raf = 0; }
    }

    document.addEventListener('visibilitychange', function () {
      if (document.hidden) stop(); else start();
    });

    if ('IntersectionObserver' in window) {
      var target = rows[0].ticker.closest ? (rows[0].ticker.closest('section') || rows[0].ticker) : rows[0].ticker;
      new IntersectionObserver(function (entries) {
        visible = entries.some(function (e) { return e.isIntersecting; });
        if (visible) start(); else stop();
      }, { rootMargin: '200px 0px' }).observe(target);
    }

    // пересчёт только при смене ШИРИНЫ: на мобильных скрытие адресной
    // строки меняет высоту и не должно дёргать ленту
    var lastWidth = window.innerWidth;
    window.addEventListener('resize', function () {
      if (window.innerWidth === lastWidth) return;
      lastWidth = window.innerWidth;
      rows.forEach(function (r) {
        silence(r); measure(r);
        if (r.x < -r.half) r.x = -r.half;
        paint(r);
      });
    }, { passive: true });

    start();
  }

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', boot);
  } else {
    boot();
  }
})();
</script>
<?php
/* ===== ЦИФРЫ | бенто + барабанные счётчики | v8.0 ===== */
$rv_stx_en = ( function_exists( 'pll_current_language' ) && 'en' === pll_current_language() );
$rv_stx = $rv_stx_en ? array(
	'chip'   => 'Numbers, not promises',
	'title'  => 'Revive <em>in numbers</em>',
	'status' => 'Results / since 2014',
	'intro'  => 'Proven by results. Trusted by ambitious brands worldwide.',
	'items'  => array(
		array( 'value' => 200, 'suffix' => '+', 'signal' => 'Delivered',    'label' => 'Completed projects',  'note' => 'Websites, stores, apps and campaigns — each dot is one project.' ),
		array( 'value' => 12,  'suffix' => '',  'signal' => 'Experience',   'label' => 'Years of experience', 'note' => 'From the first landing page to AI assistants.' ),
		array( 'value' => 21,  'suffix' => '',  'signal' => 'Global reach', 'label' => 'Countries worldwide', 'note' => 'Offices in Kyiv and Irvine, clients from Europe to the US.' ),
		array( 'value' => 95,  'suffix' => '%', 'signal' => 'Retention',    'label' => 'Returning clients',   'note' => '19 of 20 clients come back with their next project.' ),
	),
	'now'    => 'now',
	'swipe'  => 'Swipe',
	'hq'     => 'Kyiv · Irvine',
	'foot'   => array( 'Trusted by ambitious brands', 'Kyiv · Irvine / Est. 2014' ),
) : array(
	'chip'   => 'Цифри, а не обіцянки',
	'title'  => 'Revive <em>у цифрах</em>',
	'status' => 'Результати / з 2014 року',
	'intro'  => 'Доведено результатами. Нам довіряють амбітні бренди в усьому світі.',
	'items'  => array(
		array( 'value' => 200, 'suffix' => '+', 'signal' => 'Реалізовано', 'label' => 'Завершених проєктів',   'note' => 'Сайти, магазини, застосунки й кампанії — кожна точка це проєкт.' ),
		array( 'value' => 12,  'suffix' => '',  'signal' => 'Досвід',      'label' => 'Років досвіду',         'note' => 'Від першого лендингу до AI-асистентів.' ),
		array( 'value' => 21,  'suffix' => '',  'signal' => 'Географія',   'label' => 'Країна світу',          'note' => 'Офіси в Києві та Ірвайні, клієнти від Європи до США.' ),
		array( 'value' => 95,  'suffix' => '%', 'signal' => 'Повернення',  'label' => 'Клієнтів повертаються', 'note' => '19 з 20 клієнтів приходять із наступним проєктом.' ),
	),
	'now'    => 'зараз',
	'swipe'  => 'Гортайте',
	'hq'     => 'Київ · Ірвайн',
	'foot'   => array( 'Нам довіряють амбітні бренди', 'Kyiv · Irvine / Est. 2014' ),
);

/* Барабан: каждая цифра — колонка 0–9, прокрученная на полный оборот плюс нужная цифра. */
if ( ! function_exists( 'rv_stx_odometer' ) ) {
function rv_stx_odometer( $value ) {
	$out = '';
	foreach ( str_split( (string) $value ) as $d ) {
		$out .= '<span class="rv-stx__d" style="--n:' . (int) $d . '"><span>';
		for ( $k = 0; $k < 20; $k++ ) {
			$out .= '<i>' . ( $k % 10 ) . '</i>';
		}
		$out .= '</span></span>';
	}
	return $out;
}
}
$rv_stx_year_now = 2026;
?>
<section class="rv-stx" id="stats" aria-labelledby="rv-stx-title" data-rv-stx>
	<div class="rv-stx__bg" aria-hidden="true"></div>
	<div class="rv-stx__wrap">
		<header class="rv-stx__head">
			<div>
				<p class="rv-stx__chip"><i></i><?php echo esc_html( $rv_stx['chip'] ); ?></p>
				<h2 class="rv-stx__title" id="rv-stx-title"><?php echo wp_kses( $rv_stx['title'], array( 'em' => array() ) ); ?></h2>
			</div>
			<div class="rv-stx__intro">
				<p class="rv-stx__status"><i aria-hidden="true"></i><?php echo esc_html( $rv_stx['status'] ); ?></p>
				<p><?php echo esc_html( $rv_stx['intro'] ); ?></p>
			</div>
		</header>

		<div class="rv-stx__nav" aria-hidden="true">
			<span class="rv-stx__count"><b data-stx-cur>01</b><span>/ <?php echo esc_html( str_pad( count( $rv_stx['items'] ), 2, '0', STR_PAD_LEFT ) ); ?></span></span>
			<span class="rv-stx__bar"><i></i></span>
			<button type="button" class="rv-stx__hint" data-stx-next tabindex="-1"><?php echo esc_html( $rv_stx['swipe'] ); ?> <svg viewBox="0 0 24 24"><path d="M5 12h14M13 6l6 6-6 6"/></svg></button>
		</div>

		<div class="rv-stx__grid" role="list" data-stx-track>
			<?php foreach ( $rv_stx['items'] as $rv_i => $rv_it ) :
				$rv_n = $rv_i + 1;
			?>
			<article class="rv-stx__tile rv-stx__tile--<?php echo (int) $rv_n; ?>" role="listitem">
				<span class="rv-stx__spot" aria-hidden="true"></span>
				<div class="rv-stx__meta" aria-hidden="true">
					<span><b><?php echo esc_html( str_pad( $rv_n, 2, '0', STR_PAD_LEFT ) ); ?></b> / <?php echo esc_html( $rv_it['signal'] ); ?></span>
					<i></i>
				</div>
				<p class="rv-stx__sr"><?php echo esc_html( $rv_it['value'] . $rv_it['suffix'] . ' — ' . $rv_it['label'] ); ?></p>
				<div class="rv-stx__num" aria-hidden="true">
					<span class="rv-stx__odo"><?php echo rv_stx_odometer( $rv_it['value'] ); // разметка собрана выше из цифр ?></span><?php if ( '' !== $rv_it['suffix'] ) : ?><sup><?php echo esc_html( $rv_it['suffix'] ); ?></sup><?php endif; ?>
				</div>
				<h3 class="rv-stx__label"><?php echo esc_html( $rv_it['label'] ); ?></h3>
				<p class="rv-stx__note"><?php echo esc_html( $rv_it['note'] ); ?></p>

				<div class="rv-stx__viz" aria-hidden="true">
				<?php if ( 1 === $rv_n ) : ?>
					<div class="rv-stx__dots"><?php for ( $k = 0; $k < 200; $k++ ) { echo '<i style="--k:' . (int) $k . '"></i>'; } ?></div>
				<?php elseif ( 2 === $rv_n ) : ?>
					<div class="rv-stx__time">
						<div class="rv-stx__track"><span class="rv-stx__fill"></span>
							<?php for ( $y = 0; $y <= 12; $y++ ) { echo '<i style="--y:' . (int) $y . '"' . ( 0 === $y % 4 ? ' class="big"' : '' ) . '></i>'; } ?>
							<b class="rv-stx__now"></b>
						</div>
						<div class="rv-stx__years"><span>2014</span><span>2018</span><span>2022</span><span><?php echo (int) $rv_stx_year_now; ?> · <?php echo esc_html( $rv_stx['now'] ); ?></span></div>
					</div>
				<?php elseif ( 3 === $rv_n ) : ?>
					<div class="rv-stx__radar">
						<span class="rv-stx__sweep"></span>
						<span class="rv-stx__rings"></span>
						<?php
						/* 21 точка по трём орбитам — детерминированно, без случайностей */
						for ( $k = 0; $k < 21; $k++ ) {
							$rv_r   = array( 30, 40, 48 )[ $k % 3 ];
							$rv_a   = deg2rad( $k * 137.508 );
							$rv_x   = 50 + $rv_r * cos( $rv_a );
							$rv_y   = 50 + $rv_r * sin( $rv_a );
							printf( '<i style="left:%.2f%%;top:%.2f%%;--k:%d"></i>', $rv_x, $rv_y, $k );
						}
						?>
						<b class="rv-stx__hq"><?php echo esc_html( $rv_stx['hq'] ); ?></b>
					</div>
				<?php else : ?>
					<div class="rv-stx__people">
						<?php $rv_on = (int) round( $rv_it['value'] / 5 );
						for ( $k = 0; $k < 20; $k++ ) {
							echo '<i class="' . ( $k < $rv_on ? 'on' : 'off' ) . '" style="--k:' . (int) $k . '"><svg viewBox="0 0 24 24"><circle cx="12" cy="7" r="4"/><path d="M4 21c0-4.4 3.6-8 8-8s8 3.6 8 8z"/></svg></i>';
						} ?>
					</div>
				<?php endif; ?>
				</div>
			</article>
			<?php endforeach; ?>
		</div>

		<footer class="rv-stx__foot" aria-hidden="true">
			<span><?php echo esc_html( $rv_stx['foot'][0] ); ?></span><i></i><span><?php echo esc_html( $rv_stx['foot'][1] ); ?></span>
		</footer>
	</div>

<style>
.rv-stx{--gold:#F5C542;--ease:cubic-bezier(.22,1,.36,1);--roll:cubic-bezier(.16,1,.3,1);
  position:relative;isolation:isolate;overflow:hidden;padding:clamp(52px,calc(var(--rv-lvh,1vh)*7.5),84px) 0!important;background:#030032;color:#fff;font-family:'Inter',Arial,sans-serif}
.rv-stx *,.rv-stx *::before,.rv-stx *::after{box-sizing:border-box}
.rv-stx p,.rv-stx h2,.rv-stx h3{margin:0;padding:0}
.rv-stx i,.rv-stx em{font-style:normal}
.rv-stx button{font:inherit;color:inherit;background:none;border:0;border-radius:0;padding:0;margin:0;-webkit-appearance:none;appearance:none;cursor:pointer}
.rv-stx__bg{position:absolute;inset:0;z-index:-1;pointer-events:none;
  background:radial-gradient(45% 50% at 85% 20%,rgba(245,197,66,.10),transparent 70%),radial-gradient(50% 55% at 10% 85%,rgba(100,108,255,.16),transparent 72%)}
.rv-stx__wrap{max-width:none;margin:0 auto;padding:0 clamp(20px,4vw,64px)} /* по ширине контейнера, как «Аудит», «Процес», «Кейси», FAQ */

/* — шапка: заголовок в одну строку, размер как у «Вибрані клієнти» — */
.rv-stx__head{display:grid;grid-template-columns:minmax(0,1fr) minmax(0,.62fr);gap:18px 56px;align-items:end;margin-bottom:clamp(22px,calc(var(--rv-lvh,1vh)*3.4),38px)}
.rv-stx__chip{display:inline-flex;align-items:center;gap:.7rem;padding:.45rem 1rem;border-radius:100px;border:1px solid rgba(255,255,255,.14);background:rgba(255,255,255,.05);
  font-family:'JetBrains Mono',monospace;font-size:.66rem;letter-spacing:.12em;text-transform:uppercase;color:#fff}
.rv-stx__chip i{width:7px;height:7px;border-radius:50%;background:var(--gold);box-shadow:0 0 12px var(--gold)}
.rv-stx__title{margin-top:16px!important;font-family:'Manrope',Arial,sans-serif;font-weight:800;font-size:clamp(34px,4vw,58px);line-height:1.05;letter-spacing:-.03em;
  text-transform:uppercase;white-space:nowrap;color:#fff}
.rv-stx__title em{font-style:normal;color:transparent;-webkit-text-stroke:1.5px var(--gold);padding-right:2px}
.rv-stx__status{display:flex;align-items:center;gap:10px;margin-bottom:10px!important;font-family:'JetBrains Mono',monospace;font-size:.68rem;letter-spacing:.12em;text-transform:uppercase;color:var(--gold)}
.rv-stx__status i{width:7px;height:7px;border-radius:50%;background:#5BD98A;box-shadow:0 0 10px #5BD98A;animation:rvStxLive 2s ease-in-out infinite}
.rv-stx__intro>p:last-child{font-size:clamp(15px,1.1vw,17px);line-height:1.55;color:#fff;max-width:40ch}

/* — четыре плитки в ряд — */
.rv-stx__grid{display:grid;grid-template-columns:repeat(4,minmax(0,1fr));gap:14px}
.rv-stx__tile{position:relative;overflow:hidden;isolation:isolate;display:flex;flex-direction:column;padding:22px 22px 24px;border-radius:26px;
  background:linear-gradient(160deg,rgba(255,255,255,.06),rgba(255,255,255,.015) 70%);border:1px solid rgba(255,255,255,.1);
  box-shadow:inset 0 1px 0 rgba(255,255,255,.06);transition:border-color .4s ease}
.rv-stx__tile::after{content:"";position:absolute;left:24px;right:24px;top:0;height:1px;background:linear-gradient(90deg,transparent,var(--gold),transparent);
  transform:scaleX(0);transition:transform 1.2s var(--ease) .2s}
.rv-stx.is-in .rv-stx__tile::after{transform:scaleX(1)}
.rv-stx__tile:hover{border-color:rgba(245,197,66,.35)}
.rv-stx__spot{position:absolute;inset:0;z-index:-1;pointer-events:none;opacity:0;transition:opacity .4s ease;
  background:radial-gradient(300px circle at var(--mx,50%) var(--my,50%),rgba(245,197,66,.12),transparent 60%)}
.rv-stx__tile:hover .rv-stx__spot{opacity:1}
.rv-stx__meta{display:flex;justify-content:space-between;align-items:center;font-family:'JetBrains Mono',monospace;font-size:.64rem;letter-spacing:.12em;text-transform:uppercase;color:#fff}
.rv-stx__meta b{font-weight:500;color:var(--gold)}
.rv-stx__meta i{width:6px;height:6px;border-radius:50%;background:var(--gold);box-shadow:0 0 10px var(--gold)}
.rv-stx__sr{position:absolute;width:1px;height:1px;overflow:hidden;clip:rect(0 0 0 0);white-space:nowrap}

/* барабан */
.rv-stx__num{display:flex;align-items:flex-start;margin-top:16px;font-family:'Manrope',Arial,sans-serif;font-weight:800;font-size:clamp(54px,5.2vw,86px);line-height:1;letter-spacing:-.05em;color:#fff;
  font-variant-numeric:tabular-nums;font-feature-settings:"tnum" 1}
.rv-stx__odo{display:inline-flex}
.rv-stx__d{display:inline-block;height:1em;overflow:hidden;
  -webkit-mask-image:linear-gradient(180deg,transparent 0,#000 12%,#000 88%,transparent 100%);mask-image:linear-gradient(180deg,transparent 0,#000 12%,#000 88%,transparent 100%)}
.rv-stx__d>span{display:flex;flex-direction:column;transform:translateY(calc((var(--n) + 10) * -1em))}
.rv-stx__d i{display:block;height:1em;line-height:1;font-style:normal;text-align:center}
.rv-stx.is-ready .rv-stx__d>span{transform:translateY(0)}
.rv-stx.is-ready.is-in .rv-stx__d>span{transform:translateY(calc((var(--n) + 10) * -1em));transition:transform 2.1s var(--roll);transition-delay:calc(var(--t,0) * 1ms)}
.rv-stx__num sup{position:static;top:auto;vertical-align:top;margin-left:.04em;font-size:.5em;line-height:1.1;color:var(--gold);text-shadow:0 0 30px rgba(245,197,66,.35)}
.rv-stx__label{margin-top:8px!important;font-family:'Manrope',Arial,sans-serif;font-weight:700;font-size:clamp(16px,1.25vw,19px);letter-spacing:-.01em;color:#fff}
.rv-stx__note{margin-top:6px!important;font-size:.86rem;line-height:1.45;color:#fff}
.rv-stx__viz{margin-top:auto;padding-top:18px}

/* 1 — 200 точек */
.rv-stx__dots{display:grid;grid-template-columns:repeat(20,1fr);gap:4px}
.rv-stx__dots i{aspect-ratio:1;border-radius:50%;background:rgba(255,255,255,.12)}
.rv-stx.is-in .rv-stx__dots i{background:var(--gold);box-shadow:0 0 5px rgba(245,197,66,.5);
  transition:background .3s ease,box-shadow .3s ease;transition-delay:calc(var(--k) * 8ms + 200ms)}
.rv-stx.is-in .rv-stx__dots i:nth-child(n+193){animation:rvStxBlink 1.6s ease-in-out infinite;animation-delay:calc(var(--k) * 40ms)}

/* 2 — таймлайн */
.rv-stx__track{position:relative;height:40px}
.rv-stx__track::before{content:"";position:absolute;left:0;right:0;top:19px;height:2px;background:rgba(255,255,255,.14);border-radius:2px}
.rv-stx__fill{position:absolute;left:0;right:0;top:19px;height:2px;border-radius:2px;background:linear-gradient(90deg,rgba(245,197,66,.3),var(--gold));box-shadow:0 0 12px rgba(245,197,66,.6);
  transform:scaleX(0);transform-origin:left;transition:transform 2s var(--roll) .3s}
.rv-stx.is-in .rv-stx__fill{transform:scaleX(1)}
.rv-stx__track i{position:absolute;top:14px;left:calc(var(--y) * 100% / 12);width:2px;height:12px;margin-left:-1px;background:rgba(255,255,255,.35);border-radius:2px}
.rv-stx__track i.big{top:10px;height:20px;background:#fff}
.rv-stx__now{position:absolute;top:12px;right:-7px;width:14px;height:14px;border-radius:50%;background:var(--gold);box-shadow:0 0 0 5px rgba(245,197,66,.18),0 0 16px var(--gold);
  opacity:0;transform:scale(.4);transition:opacity .4s ease 2.1s,transform .6s cubic-bezier(.34,1.56,.64,1) 2.1s}
.rv-stx.is-in .rv-stx__now{opacity:1;transform:scale(1);animation:rvStxPing 2.4s ease-out 2.6s infinite}
.rv-stx__years{display:flex;justify-content:space-between;margin-top:4px;font-family:'JetBrains Mono',monospace;font-size:.6rem;letter-spacing:.06em;color:#fff}
.rv-stx__years span:last-child{color:var(--gold)}

/* 3 — радар */
.rv-stx__tile--3 .rv-stx__viz{display:flex;justify-content:center}
.rv-stx__radar{position:relative;width:min(100%,150px);aspect-ratio:1;border-radius:50%}
.rv-stx__rings{position:absolute;inset:0;border-radius:50%;
  background:radial-gradient(circle,transparent 0 29%,rgba(255,255,255,.12) 29.5% 30%,transparent 30.5% 39%,rgba(255,255,255,.1) 39.5% 40%,transparent 40.5% 47.5%,rgba(255,255,255,.08) 48% 48.5%,transparent 49%)}
.rv-stx__sweep{position:absolute;inset:2%;border-radius:50%;background:conic-gradient(from 0deg,rgba(245,197,66,.28),rgba(245,197,66,0) 22%,transparent 100%);animation:rvStxSpin 5s linear infinite}
.rv-stx__radar i{position:absolute;width:6px;height:6px;margin:-3px 0 0 -3px;border-radius:50%;background:rgba(255,255,255,.25);transform:scale(.4);opacity:0}
.rv-stx.is-in .rv-stx__radar i{opacity:1;transform:scale(1);background:var(--gold);box-shadow:0 0 8px var(--gold);
  transition:opacity .4s ease,transform .5s cubic-bezier(.34,1.56,.64,1);transition-delay:calc(var(--k) * 70ms + 300ms)}
.rv-stx__hq{position:absolute;left:50%;top:50%;transform:translate(-50%,-50%);white-space:nowrap;font-family:'JetBrains Mono',monospace;font-weight:500;font-size:.5rem;letter-spacing:.08em;text-transform:uppercase;color:var(--gold)}

/* 4 — 19 из 20 */
.rv-stx__people{display:grid;grid-template-columns:repeat(10,minmax(0,1fr));gap:8px 5px}
.rv-stx__people i{display:block;aspect-ratio:1}
.rv-stx__people svg{width:100%;height:100%;fill:rgba(255,255,255,.14);transition:fill .4s ease,filter .4s ease,transform .5s cubic-bezier(.34,1.56,.64,1)}
.rv-stx.is-in .rv-stx__people i.on svg{fill:var(--gold);filter:drop-shadow(0 0 5px rgba(245,197,66,.5));transform:translateY(-2px);transition-delay:calc(var(--k) * 70ms + 300ms)}
.rv-stx__people i.off svg{fill:none;stroke:rgba(255,255,255,.45);stroke-width:1.2;stroke-dasharray:3 2}

.rv-stx__foot{display:flex;align-items:center;gap:18px;margin-top:20px;font-family:'JetBrains Mono',monospace;font-size:.64rem;letter-spacing:.12em;text-transform:uppercase;color:#fff}
.rv-stx__foot i{flex:1;height:1px;background:linear-gradient(90deg,rgba(255,255,255,.25),rgba(245,197,66,.6),rgba(255,255,255,.25))}

/* навигация карусели — только на телефоне */
.rv-stx__nav{display:none}

/* появление */
.rv-stx.is-ready .rv-stx__head>*,.rv-stx.is-ready .rv-stx__tile{opacity:0;transform:translateY(30px)}
.rv-stx.is-in .rv-stx__head>*,.rv-stx.is-in .rv-stx__tile{opacity:1;transform:none;transition:opacity .9s var(--ease),transform .9s var(--ease),border-color .4s ease;transition-delay:calc(var(--d,0) * 90ms)}

@keyframes rvStxSpin{to{transform:rotate(360deg)}}
@keyframes rvStxLive{50%{opacity:.35}}
@keyframes rvStxBlink{50%{opacity:.35}}
@keyframes rvStxPing{0%{box-shadow:0 0 0 0 rgba(245,197,66,.45),0 0 16px var(--gold)}100%{box-shadow:0 0 0 16px rgba(245,197,66,0),0 0 16px var(--gold)}}
@keyframes rvStxNudge{0%,100%{transform:translateX(0)}50%{transform:translateX(4px)}}

/* планшет: 2 × 2 */
@media(max-width:1100px) and (min-width:761px){
  .rv-stx__head{grid-template-columns:1fr}
  .rv-stx__grid{grid-template-columns:repeat(2,minmax(0,1fr))}
}

/* телефон: карусель, листается пальцем */
@media(max-width:760px){
  .rv-stx{padding:48px 0 44px!important}
  .rv-stx__head{grid-template-columns:1fr;gap:12px;margin-bottom:20px}
  .rv-stx__title{font-size:clamp(26px,8.2vw,34px)}
  .rv-stx__status{display:none}
  .rv-stx__nav{display:flex;align-items:center;gap:16px;margin-bottom:14px}
  .rv-stx__count{display:flex;align-items:center;gap:6px;font-family:'JetBrains Mono',monospace;font-size:.8rem;letter-spacing:.08em;color:#fff}
  .rv-stx__count b{position:relative;display:grid;place-items:center;width:40px;height:40px;border-radius:50%;border:1px solid rgba(245,197,66,.55);font-weight:500;color:#fff}
  .rv-stx__count b::before{content:"";position:absolute;top:5px;left:50%;width:5px;height:5px;margin-left:-2.5px;border-radius:50%;background:var(--gold);box-shadow:0 0 8px var(--gold)}
  .rv-stx__count>span{opacity:.75}
  .rv-stx__bar{position:relative;flex:1;max-width:110px;height:2px;border-radius:2px;background:rgba(255,255,255,.14);overflow:hidden}
  .rv-stx__bar i{position:absolute;inset:0;background:var(--gold);transform-origin:left;transform:scaleX(var(--p,.25));transition:transform .45s var(--ease)}
  .rv-stx .rv-stx__hint{display:inline-flex;align-items:center;gap:8px;margin-left:auto;font-family:'JetBrains Mono',monospace;font-size:.7rem;letter-spacing:.16em;text-transform:uppercase;color:#fff}
  .rv-stx__hint svg{width:16px;height:16px;fill:none;stroke:var(--gold);stroke-width:2;stroke-linecap:round;stroke-linejoin:round;animation:rvStxNudge 1.6s ease-in-out infinite}
  .rv-stx__grid{display:flex;gap:12px;overflow-x:auto;overflow-y:hidden;scroll-snap-type:x mandatory;scroll-padding-left:20px;
    margin:0 -20px;padding:4px 20px 6px;-webkit-overflow-scrolling:touch;overscroll-behavior-x:contain;scrollbar-width:none}
  .rv-stx__grid::-webkit-scrollbar{display:none}
  .rv-stx__grid::after{content:"";flex:0 0 8px}
  .rv-stx__tile{flex:0 0 84%;scroll-snap-align:start;min-height:420px}
  .rv-stx__num{font-size:clamp(60px,18vw,84px)}
  .rv-stx__radar{width:170px}
  .rv-stx__hq{font-size:.56rem}
  .rv-stx__foot{margin-top:18px}
  .rv-stx__foot span:first-child{display:none}
}
@media(prefers-reduced-motion:reduce){
  .rv-stx *,.rv-stx *::before,.rv-stx *::after{animation:none!important;transition:none!important}
  .rv-stx.is-ready .rv-stx__head>*,.rv-stx.is-ready .rv-stx__tile{opacity:1;transform:none}
}
</style>

<script>
(function(){
  'use strict';
  var root = document.querySelector('[data-rv-stx]');
  if (!root) return;
  var reduce = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

  /* задержки: плитки по очереди, цифры внутри числа — справа налево чуть позже */
  root.querySelectorAll('.rv-stx__head>*').forEach(function(el, n){ el.style.setProperty('--d', n); });
  root.querySelectorAll('.rv-stx__tile').forEach(function(tile, n){
    tile.style.setProperty('--d', n + 1);
    var ds = tile.querySelectorAll('.rv-stx__d');
    ds.forEach(function(d, k){ d.style.setProperty('--t', 250 + n * 140 + (ds.length - 1 - k) * 120); });
  });

  if (!reduce && 'IntersectionObserver' in window) {
    root.classList.add('is-ready');
    var io = new IntersectionObserver(function(es){
      if (!es[0].isIntersecting) return;
      io.disconnect();
      /* кадр задержки, чтобы стартовое положение барабана успело отрисоваться */
      requestAnimationFrame(function(){ requestAnimationFrame(function(){ root.classList.add('is-in'); }); });
    }, { threshold: .2 });
    io.observe(root);
  } else {
    root.classList.add('is-in');
  }

  /* телефон: счётчик «01 / 04», полоска прогресса и кнопка «Гортайте» */
  var track = root.querySelector('[data-stx-track]');
  var tiles = [].slice.call(root.querySelectorAll('.rv-stx__tile'));
  var curEl = root.querySelector('[data-stx-cur]');
  var bar   = root.querySelector('.rv-stx__bar i');
  var next  = root.querySelector('[data-stx-next]');
  function current(){
    var x = track.scrollLeft, best = 0, d = Infinity;
    tiles.forEach(function(t, n){
      var dd = Math.abs((t.offsetLeft - tiles[0].offsetLeft) - x);
      if (dd < d) { d = dd; best = n; }
    });
    if (x > 0 && x + track.clientWidth >= track.scrollWidth - 4) best = tiles.length - 1;
    return best;
  }
  var sraf = 0;
  function sync(){
    sraf = 0;
    var i = current();
    if (curEl) curEl.textContent = (i < 9 ? '0' : '') + (i + 1);
    if (bar) bar.style.setProperty('--p', ((i + 1) / tiles.length).toFixed(3));
  }
  if (track && tiles.length) {
    track.addEventListener('scroll', function(){ if (!sraf) sraf = requestAnimationFrame(sync); }, { passive: true });
    sync();
    if (next) next.addEventListener('click', function(){
      var n = (current() + 1) % tiles.length;
      track.scrollTo({ left: tiles[n].offsetLeft - tiles[0].offsetLeft, behavior: reduce ? 'auto' : 'smooth' });
    });
  }

  /* подсветка за курсором внутри плитки */
  if (!reduce && window.matchMedia('(hover: hover) and (pointer: fine)').matches) {
    root.querySelectorAll('.rv-stx__tile').forEach(function(tile){
      var raf = 0;
      tile.addEventListener('pointermove', function(e){
        if (raf) return;
        raf = requestAnimationFrame(function(){
          raf = 0;
          var r = tile.getBoundingClientRect();
          tile.style.setProperty('--mx', (e.clientX - r.left) + 'px');
          tile.style.setProperty('--my', (e.clientY - r.top) + 'px');
        });
      }, { passive: true });
    });
  }
})();
</script>
</section>

<!-- ===== RV PROCESS: как мы работаем, 6 шагов ===== -->
<section class="rv-proc" id="rv-proc">
<style>
.rv-proc{ --font-label:'JetBrains Mono',monospace; --rv-h:'Manrope','Montserrat',Arial,sans-serif;
  --rv-gold:#F5C542; position:relative; background:#030032; color:#fff; padding:110px 0 120px; overflow:hidden; }
.rv-proc::before{ content:""; position:absolute; inset:0; pointer-events:none;
  background:radial-gradient(ellipse 46% 52% at 78% 12%, rgba(245,197,66,.08) 0%, transparent 68%); }
/* сетка сайта: во всю ширину с одним полем по краям — как .wrapper и остальные секции */
.rv-proc__wrap{ position:relative; max-width:none; margin:0; padding:0 clamp(20px,4vw,64px); }
.rv-proc__chip{ display:inline-flex; align-items:center; gap:.7rem; background:rgba(255,255,255,.05);
  border:1px solid rgba(255,255,255,.12); border-radius:100px; padding:.5rem 1.1rem;
  font-family:var(--font-label); font-size:.66rem; letter-spacing:.1em; text-transform:uppercase;
  color:#fff; }
.rv-proc__chip i{ font-style:normal; width:7px; height:7px; border-radius:50%; background:var(--rv-gold);
  box-shadow:0 0 12px var(--rv-gold); }
.rv-proc__head{ display:grid; grid-template-columns:minmax(0,1.15fr) minmax(0,.85fr); gap:clamp(30px,4vw,80px);
  align-items:end; margin-bottom:clamp(40px,4.4vw,72px); }
.rv-proc__title{ font-family:var(--rv-h); font-weight:800; letter-spacing:-.03em; text-transform:uppercase;
  font-size:clamp(34px,4vw,58px); line-height:1.05; color:#fff; margin:22px 0 0; }
.rv-proc__title em{ font-style:normal; color:transparent; -webkit-text-fill-color:transparent;
  -webkit-text-stroke:1.5px var(--rv-gold); padding-right:2px; }
.rv-proc__lead{ font-size:1.02rem; line-height:1.65; color:#fff; margin:0; max-width:52ch; }
/* сетка шагов */
.rv-proc__grid{ display:grid; grid-template-columns:repeat(3,minmax(0,1fr)); gap:clamp(16px,1.6vw,26px); }
.rv-proc__step{ position:relative; display:flex; flex-direction:column; gap:12px;
  padding:clamp(26px,2.2vw,38px) clamp(22px,2vw,34px) clamp(24px,2vw,34px);
  border:1px solid rgba(255,255,255,.12); border-radius:22px; background:rgba(255,255,255,.045);
  overflow:hidden; transition:border-color .35s ease, background .35s ease, transform .35s cubic-bezier(.22,1,.36,1); }
.rv-proc__step::after{ content:attr(data-no); position:absolute; right:14px; bottom:-18px; pointer-events:none;
  font-family:var(--rv-h); font-weight:800; font-size:96px; line-height:1; letter-spacing:-.05em;
  color:rgba(255,255,255,.045); }
.rv-proc__step:hover{ border-color:rgba(245,197,66,.42); background:rgba(255,255,255,.07); transform:translateY(-4px); }
.rv-proc__no{ display:inline-flex; align-items:center; gap:10px; font-family:var(--font-label);
  font-size:.68rem; letter-spacing:.16em; color:var(--rv-gold); }
.rv-proc__no b{ font-weight:500; }
.rv-proc__no span{ flex:1; height:1px; background:linear-gradient(90deg,rgba(245,197,66,.5),transparent); }
.rv-proc__name{ font-family:var(--rv-h); font-weight:800; font-size:1.24rem; line-height:1.2; letter-spacing:-.01em;
  color:#fff; margin:0; }
.rv-proc__time{ font-family:var(--font-label); font-size:.66rem; letter-spacing:.12em; text-transform:uppercase;
  color:#fff; }
/* на широком мониторе колонка становится шире 600px — длину строки держим сами */
.rv-proc__text{ font-size:.95rem; line-height:1.6; color:#fff; margin:0; max-width:46ch; }
/* обёртка нужна только телефону: на десктопе она «прозрачна»,
   и текст с плашкой «на виході» остаются прямыми детьми карточки —
   иначе margin-top:auto перестанет прижимать плашку к низу */
.rv-proc__body, .rv-proc__body > div{ display:contents; }
.rv-proc__chev{ display:none; }
.rv-proc__out{ margin-top:auto; padding-top:14px; border-top:1px solid rgba(255,255,255,.1);
  font-size:.88rem; line-height:1.5; color:#fff; max-width:52ch; }
.rv-proc__out b{ display:block; font-family:var(--font-label); font-weight:400; font-size:.62rem;
  letter-spacing:.16em; text-transform:uppercase; color:var(--rv-gold); margin-bottom:6px; }
/* подвал: цифра договорённостей + кнопка */
.rv-proc__foot{ display:flex; align-items:center; justify-content:space-between; gap:24px; flex-wrap:wrap;
  margin-top:44px; padding-top:28px; border-top:1px solid rgba(255,255,255,.1); }
.rv-proc__note{ font-family:var(--font-label); font-size:.72rem; letter-spacing:.08em; line-height:1.7;
  color:#fff; text-transform:uppercase; margin:0; }
/* метрика как у остальных кнопок сайта: капс, 800, высота 64-70, трекинг .06em */
.rv-proc__cta{ display:inline-flex; align-items:center; justify-content:center; gap:12px;
  min-height:64px; padding:0 40px; border-radius:999px;
  font-weight:800; font-size:1rem; letter-spacing:.06em; text-transform:uppercase;
  text-decoration:none; color:#030032;
  background:linear-gradient(135deg,#F5D488 0%,#F5C542 45%,#DFAE3F 100%);
  box-shadow:0 10px 30px rgba(245,197,66,.28); transition:transform .3s ease, box-shadow .3s ease; }
.rv-proc__cta:hover{ transform:translateY(-2px); box-shadow:0 14px 36px rgba(245,197,66,.42); }
/* появление */
.rv-proc__step{ opacity:0; transform:translateY(26px); }
.rv-proc.is-in .rv-proc__step{ opacity:1; transform:none;
  transition:opacity .7s cubic-bezier(.22,1,.36,1), transform .7s cubic-bezier(.22,1,.36,1); }
.rv-proc.is-in .rv-proc__step:nth-child(2){ transition-delay:.08s; }
.rv-proc.is-in .rv-proc__step:nth-child(3){ transition-delay:.16s; }
.rv-proc.is-in .rv-proc__step:nth-child(4){ transition-delay:.24s; }
.rv-proc.is-in .rv-proc__step:nth-child(5){ transition-delay:.32s; }
.rv-proc.is-in .rv-proc__step:nth-child(6){ transition-delay:.4s; }
@media(max-width:1024px){
  .rv-proc{ padding:80px 0 90px; }
  .rv-proc__head{ grid-template-columns:1fr; gap:18px; margin-bottom:40px; }
  .rv-proc__grid{ grid-template-columns:repeat(2,minmax(0,1fr)); gap:16px; }
}
@media(max-width:700px){
  .rv-proc{ padding:34px 0 40px; }
  .rv-proc__head{ margin-bottom:26px; gap:12px; }
  .rv-proc__title{ margin-top:14px; }
  .rv-proc__lead{ font-size:.94rem; line-height:1.5; }
  .rv-proc__name{ font-size:1.05rem; }
  .rv-proc__text{ font-size:.9rem; line-height:1.5; }
  .rv-proc__out{ font-size:.84rem; line-height:1.45; padding-top:10px; }
  .rv-proc__step{ gap:8px; }
  .rv-proc__no{ font-size:.62rem; }
  /* на телефоне — таймлайн: линия слева и точки, шаги идут вертикально */
  .rv-proc__grid{ grid-template-columns:1fr; gap:0; position:relative; padding-left:34px; }
  .rv-proc__grid::before{ content:""; position:absolute; left:11px; top:10px; bottom:10px; width:1px;
    background:linear-gradient(180deg,rgba(245,197,66,.65),rgba(245,197,66,.12)); }
  /* overflow:hidden из базового правила обрезал бы точку — она вынесена за границу шага */
  .rv-proc__step{ border:0; background:none; border-radius:0; padding:0 0 20px; overflow:visible; }
  .rv-proc__step::after{ display:none; }
  /* точка ровно по центру линии: линия на 11px от края сетки, шаг сдвинут на 34px */
  .rv-proc__step::before{ content:""; position:absolute; left:-27px; top:5px; width:11px; height:11px;
    border-radius:50%; background:var(--rv-gold);
    box-shadow:0 0 0 4px rgba(3,0,50,1), 0 0 0 6px rgba(245,197,66,.18), 0 0 14px rgba(245,197,66,.5); }
  .rv-proc__step:hover{ transform:none; background:none; border-color:transparent; }
  .rv-proc__no span{ display:none; }
  .rv-proc__name{ font-size:1.12rem; }
  .rv-proc__out{ padding-top:12px; }
  .rv-proc__foot{ margin-top:12px; }
  /* ШЕСТЬ ШАГОВ В ОДИН ЭКРАН.
     Скелет — номер, название, срок — виден весь сразу; описание и
     «на виході» раскрываются по тапу, открыт всегда один шаг. */
  .rv-proc__step{ cursor:pointer; padding-bottom:14px; }
  .rv-proc__body{ display:grid; grid-template-rows:0fr;
    transition:grid-template-rows .32s cubic-bezier(.22,1,.36,1); }
  .rv-proc__body > div{ display:block; overflow:hidden; }
  .rv-proc__step.is-open .rv-proc__body{ grid-template-rows:1fr; }
  .rv-proc__text{ padding-top:8px; }
  .rv-proc__out{ margin-top:10px; }
  .rv-proc__no{ position:relative; padding-right:26px; }
  .rv-proc__chev{ display:block; position:absolute; right:2px; top:50%; width:11px; height:11px;
    margin-top:-6px; border-right:1.5px solid var(--rv-gold); border-bottom:1.5px solid var(--rv-gold);
    transform:rotate(45deg); transition:transform .3s cubic-bezier(.22,1,.36,1); }
  .rv-proc__step.is-open .rv-proc__chev{ transform:rotate(-135deg); margin-top:-2px; }
  /* на телефоне — по содержимому: во всю ширину кнопка выглядела растянутой */
  .rv-proc__cta{ width:auto; min-height:66px; font-size:.92rem; padding:0 30px; }
}
@media(prefers-reduced-motion:reduce){
  .rv-proc__step{ opacity:1!important; transform:none!important; transition:none!important; }
}
</style>
<?php
$rv_pr_en = (function_exists('pll_current_language') && pll_current_language() == 'en');
$rv_steps = $rv_pr_en ? array(
  array('Brief & audit','1–2 days','We dig into your business, goals and competitors — and review analytics and your current site if you have one.','A task map and a clear view of what will actually bring you leads.'),
  array('Estimate & plan','1–2 days','We lock the scope, deadlines and price. You see everything the project includes before it starts.','A proposal broken down into stages and costs.'),
  array('Prototype','2–4 days','We build the page structure and screen logic — what goes where and why — and agree on it before design begins.','A clickable prototype of every key screen.'),
  array('Design','4–8 days','We design the interface around your brand: desktop and mobile layouts, button, form and error states.','Layouts ready to be built, with no open questions.'),
  array('Build & content','2–3 weeks','We code it, connect the CMS, fill in content, wire up forms, analytics and page speed.','The site on a staging domain — you click through it before anyone else does.'),
  array('Launch & support','ongoing','We move it to your domain, test on real devices and train your team on the admin panel. Then updates, improvements and growth.','A live site and a team that stays on call.'),
) : array(
  array('Бріф і аудит','1–2 дні','Занурюємось у ваш бізнес, цілі та конкурентів. Дивимось аналітику й поточний сайт, якщо він уже є.','Карта задач і розуміння, що саме приводитиме заявки.'),
  array('Кошторис і план','1–2 дні','Фіксуємо обсяг робіт, терміни та вартість. Ви бачите все, що входить у проєкт, ще до старту.','Пропозиція з розбивкою по етапах і цінах.'),
  array('Прототип','2–4 дні','Збираємо структуру сторінок і логіку екранів — де що стоїть і навіщо. Узгоджуємо до того, як почнеться дизайн.','Клікабельний прототип усіх ключових екранів.'),
  array('Дизайн','4–8 днів','Малюємо інтерфейс під ваш бренд: макети для десктопа й телефона, стани кнопок, форм і помилок.','Макети, готові до розробки, без відкритих питань.'),
  array('Розробка й наповнення','2–3 тижні','Верстаємо, підключаємо CMS, наповнюємо контентом, налаштовуємо форми, аналітику та швидкість.','Сайт на тестовому домені — ви клікаєте його першими.'),
  array('Запуск і підтримка','далі щомісяця','Переносимо на ваш домен, перевіряємо на реальних пристроях, навчаємо команду працювати з адмінкою. Далі — оновлення, доробки й зростання.','Робочий сайт і команда, яка лишається на зв’язку.'),
);
?>
<div class="rv-proc__wrap">
	<div class="rv-proc__head">
		<div>
			<div class="rv-proc__chip"><i></i><?php echo $rv_pr_en ? 'Process' : 'Процес'; ?></div>
			<h2 class="rv-proc__title"><?php echo $rv_pr_en ? 'How we <em>work</em>' : 'Як ми <em>працюємо</em>'; ?></h2>
		</div>
		<p class="rv-proc__lead"><?php echo $rv_pr_en
			? 'Six stages, fixed in the contract. After every one of them you get something you can open, click and check — not a status update.'
			: 'Шість етапів, зафіксованих у договорі. Після кожного ви отримуєте те, що можна відкрити, клікнути й перевірити, — а не статус «у роботі».'; ?></p>
	</div>

	<div class="rv-proc__grid">
		<?php foreach ($rv_steps as $rv_i => $rv_s) : $rv_n = str_pad($rv_i + 1, 2, '0', STR_PAD_LEFT); ?>
		<article class="rv-proc__step" data-no="<?php echo esc_attr($rv_n); ?>">
			<div class="rv-proc__no"><b><?php echo esc_html($rv_n); ?></b><span></span><i class="rv-proc__chev" aria-hidden="true"></i></div>
			<h3 class="rv-proc__name"><?php echo esc_html($rv_s[0]); ?></h3>
			<div class="rv-proc__time"><?php echo esc_html($rv_s[1]); ?></div>
			<div class="rv-proc__body"><div>
				<p class="rv-proc__text"><?php echo esc_html($rv_s[2]); ?></p>
				<div class="rv-proc__out"><b><?php echo $rv_pr_en ? 'You get' : 'На виході'; ?></b><?php echo esc_html($rv_s[3]); ?></div>
			</div></div>
		</article>
		<?php endforeach; ?>
	</div>

	<div class="rv-proc__foot">
		<p class="rv-proc__note"><?php echo $rv_pr_en
			? '&mdash; Contract, fixed deadlines, payment by stages'
			: '&mdash; Договір, фіксовані терміни, оплата за етапами'; ?></p>
		<a class="rv-proc__cta" href="#win1"><?php echo $rv_pr_en ? 'Discuss a project' : 'Обговорити проєкт'; ?> <span>↗︎</span></a>
	</div>
</div>
<script>
(function(){
  var el = document.getElementById('rv-proc');
  if (!el) return;

  /* ---- телефон: шаги раскрываются по тапу ---- */
  var steps = [].slice.call(el.querySelectorAll('.rv-proc__step'));
  var mq = window.matchMedia('(max-width: 700px)');
  function open(st){
    steps.forEach(function(s){ s.classList.remove('is-open'); s.setAttribute('aria-expanded','false'); });
    if (st){ st.classList.add('is-open'); st.setAttribute('aria-expanded','true'); }
  }
  function sync(){
    if (mq.matches){
      steps.forEach(function(s){ s.setAttribute('role','button'); s.setAttribute('tabindex','0'); });
      if (!el.querySelector('.rv-proc__step.is-open')) open(steps[0]);
    } else {
      /* на десктопе раскрывать нечего: убираем и роль, и состояние */
      steps.forEach(function(s){ s.removeAttribute('role'); s.removeAttribute('tabindex');
        s.removeAttribute('aria-expanded'); s.classList.remove('is-open'); });
    }
  }
  steps.forEach(function(st){
    st.addEventListener('click', function(){
      if (!mq.matches) return;
      open(st.classList.contains('is-open') ? null : st);
    });
    st.addEventListener('keydown', function(e){
      if (!mq.matches) return;
      if (e.key === 'Enter' || e.key === ' '){ e.preventDefault();
        open(st.classList.contains('is-open') ? null : st); }
    });
  });
  sync();
  if (mq.addEventListener) mq.addEventListener('change', sync);
  else if (mq.addListener) mq.addListener(sync);

  if (!('IntersectionObserver' in window)){ el.classList.add('is-in'); return; }
  var io = new IntersectionObserver(function(es){
    if (!es[0].isIntersecting) return;
    el.classList.add('is-in'); io.disconnect();
  }, { threshold:.12 });
  io.observe(el);
})();
</script>
</section>
<!-- ===== /RV PROCESS ===== -->

<!-- ===== RV CINE v1: портфолио — «кейс-кинотеатр».
     Полная замена секции <section class="rv-works" id="rv-works"> … </section>.
     Все стили и скрипт внутри: в style5.css ничего дописывать не нужно. ===== -->
<section class="rv-cine" id="rv-cine">
<style>
.rv-cine{
  --font-label:'JetBrains Mono',monospace;
  --rv-h:'Manrope','Montserrat',Arial,sans-serif;
  --rv-gold:#F5C542; --rv-gold-lt:#FCEEB8; --rv-ink:#030032;
  --rv-ease:cubic-bezier(.22,1,.36,1);
  --rv-pad:clamp(20px,4vw,64px);
  --c1:#2a2170; --c2:#050338;      /* цвета текущего кейса, меняются из JS */
  --t:0;                            /* -.5 … .5 — положение внутри кейса, для параллакса */
  position:relative; background:var(--rv-ink); padding:0; color:#fff;
}
.rv-cine *{ box-sizing:border-box; }
.rv-cine button{ font-family:inherit; line-height:1; border:0; background:none; padding:0; color:inherit;
  -webkit-appearance:none; appearance:none; cursor:pointer; }

/* ── сцена ─────────────────────────────────────────────────────────────── */
.rv-cine__pin{ position:sticky; top:0; height:calc(var(--rv-lvh,1vh)*100); height:calc(var(--rv-svh,1svh)*100); overflow:hidden;
  display:flex; flex-direction:column; padding:5.2vmin var(--rv-pad) 4vmin; }

/* заревo: два пятна цвета кейса, перетекают вместе со сменой карточки */
.rv-cine__aura{ position:absolute; inset:-15%; z-index:0; pointer-events:none; }
.rv-cine__aura i{ position:absolute; display:block; border-radius:50%; filter:blur(90px);
  transition:background 1.1s ease, transform 1.1s var(--rv-ease); }
.rv-cine__aura i:nth-child(1){ width:58%; height:70%; left:42%; top:-18%;
  background:var(--c1); opacity:.42; transform:translate3d(calc(var(--t) * -70px),calc(var(--t) * 40px),0); }
.rv-cine__aura i:nth-child(2){ width:52%; height:62%; left:-12%; top:44%;
  background:var(--c2); opacity:.55; transform:translate3d(calc(var(--t) * 60px),calc(var(--t) * -34px),0); }
.rv-cine__aura::after{ content:""; position:absolute; inset:0;
  background:radial-gradient(130% 100% at 50% 46%, transparent 42%, rgba(3,0,50,.72) 100%); }

/* зерно — фактура премиальной печати, статичное */
.rv-cine__grain{ position:absolute; inset:0; z-index:6; pointer-events:none; opacity:.045; mix-blend-mode:overlay;
  background-image:url("data:image/svg+xml;charset=utf8,%3Csvg xmlns='http://www.w3.org/2000/svg' width='160' height='160'%3E%3Cfilter id='n'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='.85' numOctaves='3'/%3E%3C/filter%3E%3Crect width='160' height='160' filter='url(%23n)'/%3E%3C/svg%3E"); }

/* имя клиента гигантским водяным знаком на фоне */
.rv-cine__ghost{ position:absolute; z-index:0; left:50%; top:52%; pointer-events:none; white-space:nowrap;
  font-family:var(--rv-h); font-weight:800; letter-spacing:-.04em; line-height:.8;
  font-size:clamp(140px,23vw,420px); color:rgba(255,255,255,.032);
  transform:translate3d(calc(-50% + var(--t) * 90px),-50%,0); transition:opacity .45s ease; }

/* ── шапка ─────────────────────────────────────────────────────────────── */
.rv-cine__head{ position:relative; z-index:4; flex:none; display:flex; justify-content:space-between;
  align-items:flex-start; gap:24px; flex-wrap:wrap; }
.rv-cine__chip{ display:inline-flex; align-items:center; gap:.7rem; background:rgba(255,255,255,.05);
  border:1px solid rgba(255,255,255,.12); border-radius:100px; padding:.5rem 1.1rem;
  font-family:var(--font-label); font-size:.66rem; letter-spacing:.1em; text-transform:uppercase;
  color:#fff; backdrop-filter:blur(10px); }
.rv-cine__chip i{ font-style:normal; width:7px; height:7px; border-radius:50%; background:var(--rv-gold);
  box-shadow:0 0 12px var(--rv-gold); animation:rvcPulse 2.6s ease-in-out infinite; }
@keyframes rvcPulse{ 0%,100%{ opacity:1; box-shadow:0 0 12px var(--rv-gold); } 50%{ opacity:.4; box-shadow:0 0 4px var(--rv-gold); } }
.rv-cine__title{ font-family:var(--rv-h); font-weight:800; letter-spacing:-.03em; text-transform:uppercase;
  font-size:var(--fs-h2, clamp(28px,4.2vw,54px)); line-height:1.02; margin:16px 0 0; }
.rv-cine__title em{ font-style:normal; color:transparent; -webkit-text-stroke:1.5px var(--rv-gold);
  background:linear-gradient(110deg,transparent 42%,rgba(252,238,184,.9) 50%,transparent 58%);
  background-size:230% 100%; -webkit-background-clip:text; background-clip:text;
  animation:rvcShine 6s ease-in-out 1.2s infinite; }
@keyframes rvcShine{ 0%{ background-position:130% 0; } 34%{ background-position:-70% 0; } 100%{ background-position:-70% 0; } }
.rv-cine__hint{ display:flex; align-items:center; gap:10px; font-family:var(--font-label);
  font-size:.68rem; letter-spacing:.1em; text-transform:uppercase; color:#fff; padding-top:6px; }
.rv-cine__hint b{ color:var(--rv-gold); font-weight:700; font-variant-numeric:tabular-nums; }
.rv-cine__hint i{ font-style:normal; display:inline-block; animation:rvcNudge 1.8s ease-in-out infinite; }
@keyframes rvcNudge{ 0%,100%{ transform:translateY(0); } 50%{ transform:translateY(5px); } }

/* ── вертикальный индекс кейсов слева ─────────────────────────────────── */
.rv-cine__rail{ position:absolute; z-index:5; left:var(--rv-pad); top:50%; transform:translateY(-50%);
  display:flex; flex-direction:column; gap:2px; padding-left:14px; }
.rv-cine__rail::before{ content:""; position:absolute; left:0; top:4px; bottom:4px; width:1px;
  background:rgba(255,255,255,.14); }
.rv-cine__rail-prog{ position:absolute; left:-1px; top:4px; width:3px; border-radius:2px; height:0;
  background:linear-gradient(180deg,rgba(245,197,66,.35),var(--rv-gold));
  box-shadow:0 0 12px rgba(245,197,66,.6); transition:height .5s var(--rv-ease); }
.rv-cine__rail button{ display:flex; align-items:center; gap:10px; padding:4px 0; text-align:left;
  font-family:var(--font-label); font-size:.66rem; letter-spacing:.08em; color:rgba(255,255,255,.34);
  transition:color .35s ease, transform .45s var(--rv-ease); }
.rv-cine__rail button span{ max-width:0; overflow:hidden; white-space:nowrap; opacity:0;
  text-transform:uppercase; transition:max-width .5s var(--rv-ease), opacity .35s ease; }
.rv-cine__rail button:hover{ color:#fff; }
.rv-cine__rail button.is-on{ color:var(--rv-gold); transform:translateX(6px); }
.rv-cine__rail button.is-on span{ max-width:130px; opacity:1; }

/* ── две колонки: текст / устройство ──────────────────────────────────── */
.rv-cine__stage{ position:relative; z-index:3; flex:1; min-height:0; display:grid;
  grid-template-columns:minmax(0,.92fr) minmax(0,1.08fr); align-items:center;
  gap:4vmin; padding-left:clamp(80px,13vw,220px); }

.rv-cine__meta{ display:flex; flex-wrap:wrap; align-items:center; gap:1.1vmin;
  font-family:var(--font-label); font-size:clamp(11px,1.45vmin,14px); letter-spacing:.08em;
  text-transform:uppercase; color:#fff; margin-bottom:2.4vmin; }
.rv-cine__meta s{ opacity:.4; text-decoration:none; }
.rv-cine__meta b{ color:var(--rv-gold); font-weight:400; }

/* имя кейса — побуквенный влёт */
.rv-cine__name{ font-family:var(--rv-h); font-weight:800; letter-spacing:-.035em; line-height:.92;
  font-size:clamp(40px,6.4vw,104px); margin:0; perspective:600px; }
.rv-cine__name w{ display:inline-block; white-space:nowrap; }
.rv-cine__name i{ display:inline-block; font-style:normal; opacity:0; will-change:transform,opacity;
  transform:translateY(.55em) rotateX(-65deg); filter:blur(10px);
  transition:opacity .5s var(--rv-ease), transform .6s var(--rv-ease), filter .5s var(--rv-ease);
  transition-delay:calc(var(--d) * 28ms); }
.rv-cine__name.is-on i{ opacity:1; transform:none; filter:blur(0); }
.rv-cine__name.is-out{ opacity:0; transform:translateY(-22px); filter:blur(9px);
  transition:opacity .26s ease, transform .26s ease, filter .26s ease; }

/* цифра результата */
.rv-cine__res{ display:flex; align-items:baseline; gap:1rem; margin-top:3.4vmin; }
.rv-cine__num{ font-family:var(--rv-h); font-weight:800; letter-spacing:-.03em;
  font-size:clamp(30px,4.6vmin,56px); color:var(--rv-gold); font-variant-numeric:tabular-nums;
  text-shadow:0 0 34px rgba(245,197,66,.4); position:relative; }
.rv-cine__num::after{ content:""; position:absolute; left:0; right:0; bottom:-.35em; height:2px; border-radius:2px;
  background:linear-gradient(90deg,var(--rv-gold),transparent); transform:scaleX(0); transform-origin:left;
  transition:transform .9s var(--rv-ease) .25s; }
.rv-cine__num.is-on::after{ transform:scaleX(1); }

.rv-cine__label{ font-family:var(--font-label); font-size:clamp(11px,1.5vmin,14px); letter-spacing:.08em;
  text-transform:uppercase; color:#fff; max-width:16em; }

.rv-cine__act{ display:flex; align-items:center; gap:20px; margin-top:4.6vmin; flex-wrap:wrap; }
.rv-cine__cta{ position:relative; overflow:hidden; text-decoration:none; display:inline-flex; align-items:center; gap:12px;
  font-family:var(--rv-h); font-weight:700; font-size:.92rem; letter-spacing:.06em; text-transform:uppercase;
  color:var(--rv-ink); background:linear-gradient(135deg,#F5D488 0%,#F5C542 45%,#DFAE3F 100%);
  border-radius:999px; padding:18px 36px; box-shadow:0 8px 30px rgba(245,197,66,.35); transition:.4s all; }
.rv-cine__cta:hover{ background:#fff; transform:translateY(-2px); box-shadow:0 12px 36px rgba(245,197,66,.5); }
.rv-cine__cta::after{ content:""; position:absolute; top:0; left:-80%; width:60%; height:100%;
  background:linear-gradient(105deg,transparent 20%,rgba(255,255,255,.75) 50%,transparent 80%);
  transform:skewX(-20deg); pointer-events:none; animation:rvcBtnShine 4.5s ease-in-out 2.2s infinite; }
@keyframes rvcBtnShine{ 0%{ left:-80%; } 28%{ left:130%; } 100%{ left:130%; } }
.rv-cine__arrows{ display:flex; gap:10px; }
.rv-cine__arrows button{ width:46px; height:46px; border-radius:50%; border:1px solid rgba(255,255,255,.18);
  background:rgba(255,255,255,.05); font-size:1rem; transition:.3s; }
.rv-cine__arrows button small{ display:none; }
.rv-cine__arrows button:hover{ background:var(--rv-gold); border-color:var(--rv-gold); color:var(--rv-ink); }
.rv-cine__arrows button[disabled]{ opacity:.28; pointer-events:none; }

/* ── правая колонка: номер кейса + окно браузера ──────────────────────── */
.rv-cine__right{ position:relative; display:flex; justify-content:center; align-items:center;
  height:100%; perspective:1500px; }
.rv-cine__no{ position:absolute; right:0; top:2%; z-index:1; text-align:right; pointer-events:none;
  padding-right:3px;   /* обводка -webkit-text-stroke рисуется за границей блока */
  font-family:var(--rv-h); font-weight:800; line-height:.8; color:transparent;
  -webkit-text-stroke:1.5px rgba(245,197,66,.5); font-size:clamp(64px,11vmin,132px);
  transform:translate3d(calc(var(--t) * 34px),calc(var(--t) * -18px),0); }
.rv-cine__no small{ display:block; font-family:var(--font-label); font-size:.62rem; letter-spacing:.22em;
  color:#fff; -webkit-text-stroke:0; margin-bottom:8px; }
.rv-cine__no em{ font-style:normal; display:inline-block; }

.rv-cine__device{ position:relative; z-index:2; width:min(100%,720px); aspect-ratio:16/10;
  border-radius:2vmin; overflow:hidden; background:rgba(255,255,255,.06);
  border:1px solid rgba(255,255,255,.16); backdrop-filter:blur(18px);
  box-shadow:0 44px 90px rgba(0,0,0,.55), inset 0 0 0 1px rgba(255,255,255,.05);
  transform-style:preserve-3d; will-change:transform;
  transform:translate3d(0,calc(var(--t) * -42px),0)
            rotateY(calc(var(--mx,0) * 12deg)) rotateX(calc(var(--my,0) * -8deg)); }
.rv-cine__device.is-swap{ animation:rvcPop .85s cubic-bezier(.34,1.56,.64,1); }
@keyframes rvcPop{ 0%{ transform:scale(.94) rotateY(-18deg); filter:blur(12px); opacity:.35; }
  60%{ filter:blur(0); opacity:1; } 100%{ transform:none; } }
.rv-cine__bar{ display:flex; align-items:center; gap:.5rem; padding:.85rem 1.1rem;
  border-bottom:1px solid rgba(255,255,255,.1); background:rgba(255,255,255,.05); }
.rv-cine__bar s{ width:9px; height:9px; border-radius:50%; background:rgba(255,255,255,.24);
  text-decoration:none; flex:none; }
.rv-cine__bar s:first-of-type{ background:var(--rv-gold); }
.rv-cine__bar span{ margin-left:auto; font-family:var(--font-label); font-size:.7rem;
  color:#fff; letter-spacing:.02em; }
.rv-cine__screen{ position:relative; height:calc(100% - 44px); overflow:hidden; }

/* ── макеты «живых» экранов ───────────────────────────────────────────── */
.rv-mock{ position:absolute; inset:0; display:none; padding:3.4% 4%; font-family:var(--font-label); }
.rv-mock.is-on{ display:block; }
.rv-mock b, .rv-mock i, .rv-mock u, .rv-mock em, .rv-mock s{ font-style:normal; text-decoration:none; }

/* SEO: выдача, где наша строка поднимается с 8-й позиции на 1-ю */
.rv-seo__q{ display:flex; align-items:center; gap:8px; height:11%; border-radius:99px; padding:0 3%;
  background:rgba(255,255,255,.1); border:1px solid rgba(255,255,255,.14);
  font-size:clamp(9px,1.25vmin,13px); color:#fff; overflow:hidden; }
.rv-seo__q u{ width:1.1em; height:1.1em; flex:none; border-radius:50%; border:1.5px solid rgba(255,255,255,.45); }
.rv-seo__q span{ overflow:hidden; white-space:nowrap; border-right:1.5px solid var(--rv-gold); }
.rv-mock.is-play .rv-seo__q span{ animation:rvcType 1.3s steps(22) both, rvcCaret .7s step-end 5; }
@keyframes rvcType{ from{ max-width:0; } to{ max-width:22ch; } }
@keyframes rvcCaret{ 50%{ border-color:transparent; } }
.rv-seo__list{ position:relative; margin-top:4%; height:82%; }
.rv-seo__row{ position:absolute; left:0; right:0; height:15%; display:flex; flex-direction:column;
  justify-content:center; gap:5px; padding-left:10%; border-radius:6px;
  transition:top .85s var(--rv-ease), opacity .5s ease, background .5s ease; opacity:.55; }
.rv-seo__row u{ display:block; height:5px; border-radius:3px; background:rgba(255,255,255,.28); width:58%; }
.rv-seo__row u+u{ height:4px; width:80%; background:rgba(255,255,255,.14); }
.rv-seo__nums{ position:absolute; left:0; top:0; bottom:0; width:8%; }
.rv-seo__nums s{ position:absolute; left:0; right:0; height:15%; display:flex; align-items:center;
  justify-content:center; font-size:clamp(8px,1.05vmin,11px); color:#fff; }
.rv-seo__nums s:nth-child(1){ top:0%; color:var(--rv-gold); }
.rv-seo__nums s:nth-child(2){ top:17%; } .rv-seo__nums s:nth-child(3){ top:34%; }
.rv-seo__nums s:nth-child(4){ top:51%; } .rv-seo__nums s:nth-child(5){ top:68%; }
.rv-seo__nums s:nth-child(6){ top:85%; }
.rv-seo__row--we{ opacity:1; background:rgba(245,197,66,.09); box-shadow:inset 0 0 0 1px rgba(245,197,66,.3); }
.rv-seo__row--we u{ background:var(--rv-gold); width:66%; }
.rv-seo__row--we u+u{ background:rgba(245,197,66,.35); }
.rv-seo__row--we em{ position:absolute; right:2%; top:50%; transform:translateY(-50%);
  font-size:clamp(8px,1.1vmin,12px); color:var(--rv-gold); letter-spacing:.06em; }
.rv-seo__row:nth-child(1){ top:0%; }  .rv-seo__row:nth-child(2){ top:17%; }
.rv-seo__row:nth-child(3){ top:34%; } .rv-seo__row:nth-child(4){ top:51%; }
.rv-seo__row:nth-child(5){ top:68%; } .rv-seo__row:nth-child(6){ top:85%; }
.rv-mock.is-play .rv-seo__row--we{ animation:rvcSeoRise 3.4s var(--rv-ease) 1.2s both; }
/* Подъём строки «8 → 1» — через transform, а не top: раньше каждый кадр
   трёхсекундной анимации пересчитывал раскладку карточки (рывки в «Обрані роботи»).
   Шаг ряда в списке = 17% высоты списка = 113.333% высоты строки (строка — 15%).
   Исходные места строк — те же, что были в начале старой анимации. */
.rv-seo__list .rv-seo__row--a{ top:0%; }
.rv-seo__list .rv-seo__row--b{ top:17%; }
.rv-seo__list .rv-seo__row--we{ top:85%; }
@keyframes rvcSeoRise{ 0%{ transform:translate3d(0,0,0); } 30%{ transform:translate3d(0,-226.667%,0); } 60%{ transform:translate3d(0,-453.333%,0); } 100%{ transform:translate3d(0,-566.667%,0); } }
.rv-mock.is-play .rv-seo__row--a{ animation:rvcSeoDown1 3.4s var(--rv-ease) 1.2s both; }
@keyframes rvcSeoDown1{ 0%,60%{ transform:translate3d(0,0,0); } 100%{ transform:translate3d(0,113.333%,0); } }
.rv-mock.is-play .rv-seo__row--b{ animation:rvcSeoDown2 3.4s var(--rv-ease) 1.2s both; }
@keyframes rvcSeoDown2{ 0%,60%{ transform:translate3d(0,0,0); } 100%{ transform:translate3d(0,113.333%,0); } }

/* ADS: панель, где цена лида падает */
.rv-ads__top{ display:flex; justify-content:space-between; align-items:flex-start; }
.rv-ads__ttl{ font-size:clamp(8px,1.1vmin,11px); letter-spacing:.16em; text-transform:uppercase;
  color:#fff; }
.rv-ads__val{ --odo-d:.55s; font-size:clamp(20px,3.4vmin,40px); margin-top:6px; }
.rv-ads__tag{ display:inline-flex; align-items:center; gap:6px; border-radius:99px; padding:5px 11px;
  background:rgba(245,197,66,.12); border:1px solid rgba(245,197,66,.3);
  font-size:clamp(8px,1.05vmin,11px); color:var(--rv-gold); letter-spacing:.06em; }
.rv-ads__chart{ position:absolute; left:4%; right:4%; bottom:6%; height:56%;
  display:flex; align-items:flex-end; gap:2.4%; }
.rv-ads__chart i{ flex:1; border-radius:4px 4px 2px 2px; transform-origin:bottom; transform:scaleY(0);
  background:linear-gradient(180deg,rgba(255,255,255,.34),rgba(255,255,255,.08)); }
.rv-ads__chart i:last-child{ background:linear-gradient(180deg,var(--rv-gold),rgba(245,197,66,.18));
  box-shadow:0 0 24px rgba(245,197,66,.35); }
.rv-mock.is-play .rv-ads__chart i{ animation:rvcGrow .8s var(--rv-ease) both; }
@keyframes rvcGrow{ from{ transform:scaleY(0); } to{ transform:scaleY(1); } }
/* по умолчанию — падение: так читается снижение стоимости лида */
.rv-ads__chart i:nth-child(1){ height:96%; animation-delay:.25s; }
.rv-ads__chart i:nth-child(2){ height:88%; animation-delay:.35s; }
.rv-ads__chart i:nth-child(3){ height:74%; animation-delay:.45s; }
.rv-ads__chart i:nth-child(4){ height:79%; animation-delay:.55s; }
.rv-ads__chart i:nth-child(5){ height:58%; animation-delay:.65s; }
.rv-ads__chart i:nth-child(6){ height:46%; animation-delay:.75s; }
.rv-ads__chart i:nth-child(7){ height:33%; animation-delay:.85s; }
/* .is-up — метрика со знаком «+»: рост звонков, заявок, выручки */
.rv-ads.is-up .rv-ads__chart i:nth-child(1){ height:33%; }
.rv-ads.is-up .rv-ads__chart i:nth-child(2){ height:46%; }
.rv-ads.is-up .rv-ads__chart i:nth-child(3){ height:58%; }
.rv-ads.is-up .rv-ads__chart i:nth-child(4){ height:52%; }
.rv-ads.is-up .rv-ads__chart i:nth-child(5){ height:74%; }
.rv-ads.is-up .rv-ads__chart i:nth-child(6){ height:88%; }
.rv-ads.is-up .rv-ads__chart i:nth-child(7){ height:96%; }
.rv-ads__line{ position:absolute; left:4%; right:4%; bottom:6%; height:56%; pointer-events:none; }
.rv-ads__line svg{ width:100%; height:100%; overflow:visible; }
.rv-ads__line path{ fill:none; stroke:var(--rv-gold); stroke-width:2; stroke-linecap:round;
  stroke-linejoin:round; stroke-dasharray:600; stroke-dashoffset:600;
  filter:drop-shadow(0 0 8px rgba(245,197,66,.7)); }
.rv-ads__line .up{ display:none; }
.rv-ads.is-up .rv-ads__line .dn{ display:none; }
.rv-ads.is-up .rv-ads__line .up{ display:block; }
.rv-mock.is-play .rv-ads__line path{ animation:rvcDraw 1.6s var(--rv-ease) .9s both; }
@keyframes rvcDraw{ to{ stroke-dashoffset:0; } }

/* WEB: страница собирается из блоков, затем ползёт скор */
.rv-web{ flex-direction:column; gap:3%; }
.rv-mock.rv-web.is-on{ display:flex; }
.rv-web i{ display:block; border-radius:5px; background:rgba(255,255,255,.13); opacity:0;
  transform:translateY(16px) scale(.98); }
.rv-mock.rv-web.is-play i{ animation:rvcBuild .6s var(--rv-ease) both; }
@keyframes rvcBuild{ to{ opacity:1; transform:none; } }
.rv-web > i:nth-child(1){ height:9%; animation-delay:.15s; background:rgba(255,255,255,.2); }
.rv-web > i:nth-child(2){ height:30%; animation-delay:.3s;
  background:linear-gradient(120deg,rgba(245,197,66,.5),rgba(245,197,66,.12)); }
.rv-web__row{ display:flex; gap:3%; height:26%; }
.rv-web__row i{ flex:1; height:100%; }
.rv-web__row i:nth-child(1){ animation-delay:.45s; }
.rv-web__row i:nth-child(2){ animation-delay:.55s; }
.rv-web__row i:nth-child(3){ animation-delay:.65s; }
.rv-web__foot{ height:12%; animation-delay:.8s; }
/* плашка аналитики: результат кейса барабаном + растущий график */
.rv-web__stat{ position:absolute; right:4%; bottom:5%; width:clamp(150px,46%,300px);
  padding:3.2% 0 0; border-radius:14px; overflow:hidden; opacity:0;
  background:linear-gradient(160deg,rgba(11,8,58,.94),rgba(6,4,42,.9));
  border:1px solid rgba(245,197,66,.32);
  box-shadow:0 24px 60px rgba(0,0,0,.55), 0 0 44px rgba(245,197,66,.14);
  transform:translateY(18px) scale(.96); }
.rv-mock.rv-web.is-play .rv-web__stat{ animation:rvcStatIn .8s var(--rv-ease) .85s both; }
@keyframes rvcStatIn{ to{ opacity:1; transform:none; } }
.rv-web__stat u{ display:block; padding:0 4.5%; font-size:clamp(7px,.95vmin,10px); letter-spacing:.16em;
  text-transform:uppercase; color:#fff; margin-bottom:.5em; }

/* .rv-odo — цифра-барабан: недигитные символы статичны, каждая цифра крутит 0-9.
   Общий компонент: используется и в макете Web, и в макете Google Ads. */
.rv-odo{ display:flex; align-items:flex-end; gap:.04em; font-family:var(--rv-h); font-weight:800;
  line-height:1; letter-spacing:-.02em; color:var(--rv-gold);
  text-shadow:0 0 26px rgba(245,197,66,.45); }
.rv-odo s{ text-decoration:none; }
.rv-odo i{ display:block; height:1em; overflow:hidden; }
.rv-odo i em{ display:block; will-change:transform; }
.rv-odo i b{ display:block; height:1em; line-height:1; font-weight:inherit; text-align:center;
  font-variant-numeric:tabular-nums; }
.rv-mock.is-play .rv-odo i em{
  transform:translateY(calc(var(--d) * -1em));
  transition:transform 1.25s cubic-bezier(.16,1,.3,1);
  transition-delay:calc(var(--odo-d,1.15s) + var(--k) * .1s); }
/* стрелка направления рядом с цифрой */
.rv-odo span{ font-size:.5em; color:var(--rv-gold); opacity:0; margin-left:.15em; }
.rv-mock.is-play .rv-odo span{ animation:rvcArrow .7s var(--rv-ease) calc(var(--odo-d,1.15s) + .95s) both; }
@keyframes rvcArrow{ from{ opacity:0; transform:translate(-6px,6px); } to{ opacity:1; transform:none; } }

.rv-web__val{ padding:0 4.5%; font-size:clamp(17px,3.1vmin,34px); }

/* растущий график под цифрой */
.rv-web__spark{ display:block; width:100%; height:38%; margin-top:6%; overflow:visible; }
.rv-web__spark .ln{ fill:none; stroke:var(--rv-gold); stroke-width:2.2; stroke-linecap:round;
  stroke-linejoin:round; stroke-dasharray:220; stroke-dashoffset:220;
  filter:drop-shadow(0 0 7px rgba(245,197,66,.75)); }
.rv-web__spark .ar{ fill:url(#rvcSparkFill); opacity:0; }
.rv-mock.rv-web.is-play .rv-web__spark .ln{ animation:rvcSparkDraw 1.5s var(--rv-ease) 1.05s both; }
.rv-mock.rv-web.is-play .rv-web__spark .ar{ animation:rvcSparkFade .9s ease 1.6s both; }
@keyframes rvcSparkDraw{ to{ stroke-dashoffset:0; } }
@keyframes rvcSparkFade{ to{ opacity:1; } }

/* TARGET: лента креативов, охват и реакции растут */
.rv-tgt{ gap:3.5%; height:100%; }
.rv-mock.rv-tgt.is-on{ display:flex; }
.rv-tgt__feed{ flex:1.25; display:flex; flex-direction:column; gap:5%; }
.rv-tgt__post{ flex:1; border-radius:8px; background:rgba(255,255,255,.07);
  border:1px solid rgba(255,255,255,.1); display:flex; align-items:center; gap:10px; padding:0 3.5%;
  opacity:0; transform:translateX(24px); }
.rv-mock.is-play .rv-tgt__post{ animation:rvcSlide .6s var(--rv-ease) both; }
@keyframes rvcSlide{ to{ opacity:1; transform:none; } }
.rv-tgt__post:nth-child(1){ animation-delay:.2s; }
.rv-tgt__post:nth-child(2){ animation-delay:.35s; }
.rv-tgt__post:nth-child(3){ animation-delay:.5s; }
.rv-tgt__post u{ width:13%; aspect-ratio:1; border-radius:6px; flex:none;
  background:linear-gradient(140deg,rgba(245,197,66,.55),rgba(245,197,66,.12)); }
.rv-tgt__post em{ flex:1; display:flex; flex-direction:column; gap:5px; }
.rv-tgt__post em s{ display:block; height:5px; border-radius:3px; background:rgba(255,255,255,.26); width:70%; }
.rv-tgt__post em s+s{ width:45%; height:4px; background:rgba(255,255,255,.13); }
.rv-tgt__post b{ font-size:clamp(8px,1.05vmin,11px); color:var(--rv-gold); letter-spacing:.04em; }
.rv-mock.is-play .rv-tgt__post b{ animation:rvcBeat 1.6s ease-in-out 1s infinite; }
@keyframes rvcBeat{ 0%,100%{ transform:scale(1); } 40%{ transform:scale(1.16); } }
.rv-tgt__side{ flex:.75; display:flex; flex-direction:column; justify-content:center; gap:8%;
  border-left:1px solid rgba(255,255,255,.1); padding-left:5%; }
.rv-tgt__kpi u{ display:block; font-size:clamp(8px,1.05vmin,11px); letter-spacing:.14em;
  text-transform:uppercase; color:#fff; margin-bottom:6px; }
.rv-tgt__kpi b{ font-family:var(--rv-h); font-weight:800; font-size:clamp(15px,2.4vmin,28px); color:#fff; }
.rv-tgt__kpi:last-child b{ color:var(--rv-gold); }
.rv-tgt__dots{ display:flex; flex-wrap:wrap; gap:5px; margin-top:4%; }
.rv-tgt__dots i{ width:7px; height:7px; border-radius:50%; background:rgba(255,255,255,.16); opacity:0; }
.rv-mock.is-play .rv-tgt__dots i{ animation:rvcDot .4s ease both; }
.rv-tgt__dots i:nth-child(-n+7){ background:var(--rv-gold); }
@keyframes rvcDot{ to{ opacity:1; } }

/* ── финальный экран-CTA ──────────────────────────────────────────────── */
.rv-cine__end{ position:absolute; inset:0; z-index:5; display:flex; flex-direction:column;
  align-items:center; justify-content:center; text-align:center; gap:34px; padding:0 var(--rv-pad);
  background:radial-gradient(ellipse 62% 62% at 50% 45%,rgba(29,27,107,.9) 0%,rgba(3,0,50,.99) 68%),var(--rv-ink);
  opacity:0; visibility:hidden; transform:scale(1.06); transition:opacity .6s ease, transform .8s var(--rv-ease), visibility .6s; }
.rv-cine.is-end .rv-cine__end{ opacity:1; visibility:visible; transform:none; }
/* когда выехал финальный экран, сцена кейса гаснет — иначе просвечивает сквозь */
.rv-cine.is-end .rv-cine__head,
.rv-cine.is-end .rv-cine__stage,
.rv-cine.is-end .rv-cine__foot,
.rv-cine.is-end .rv-cine__ghost{ opacity:0; filter:blur(12px);
  transition:opacity .45s ease, filter .45s ease; }
.rv-cine.is-end .rv-cine__rail{ opacity:0; filter:blur(12px);
  transform:translateY(-50%); transition:opacity .45s ease, filter .45s ease; }
.rv-cine__end h3{ font-family:var(--rv-h); font-weight:800; letter-spacing:-.03em; text-transform:uppercase;
  line-height:.98; font-size:clamp(34px,6vw,88px); margin:0; }
.rv-cine__end p{ font-family:var(--font-label); font-size:.74rem; letter-spacing:.12em;
  text-transform:uppercase; color:#fff; margin:0; }

/* ── появление секции ─────────────────────────────────────────────────── */
.rv-cine__head, .rv-cine__rail, .rv-cine__stage, .rv-cine__foot{
  opacity:0; transform:translateY(34px); filter:blur(7px);
  transition:opacity .9s var(--rv-ease), transform .9s var(--rv-ease), filter .9s var(--rv-ease); }
.rv-cine__rail{ transform:translateY(calc(-50% + 34px)); }
.rv-cine.is-in .rv-cine__head,
.rv-cine.is-in .rv-cine__stage,
.rv-cine.is-in .rv-cine__foot{ opacity:1; transform:none; filter:blur(0); }
.rv-cine.is-in .rv-cine__rail{ opacity:1; transform:translateY(-50%); filter:blur(0); transition-delay:.24s; }
.rv-cine.is-in .rv-cine__stage{ transition-delay:.14s; }
.rv-cine.is-in .rv-cine__foot{ transition-delay:.3s; }

/* ── подвал: прогресс ─────────────────────────────────────────────────── */
.rv-cine__foot{ position:relative; z-index:4; flex:none; display:flex; align-items:center; gap:20px;
  margin-top:3vmin; }
.rv-cine__prog{ flex:1; height:3px; border-radius:2px; background:rgba(255,255,255,.13); }
.rv-cine__prog i{ position:relative; display:block; height:100%; width:0; border-radius:2px;
  background:linear-gradient(90deg,rgba(245,197,66,.35),var(--rv-gold));
  box-shadow:0 0 14px rgba(245,197,66,.5); }
.rv-cine__prog i::after{ content:""; position:absolute; right:-3px; top:50%; width:7px; height:7px;
  margin-top:-3.5px; border-radius:50%; background:var(--rv-gold); box-shadow:0 0 12px var(--rv-gold); }
.rv-cine__tape{ font-family:var(--font-label); font-size:.64rem; letter-spacing:.14em;
  text-transform:uppercase; color:#fff; white-space:nowrap; }

/* ── подсказка «гортайте вниз» ────────────────────────────────────────────
   На телефоне хинт из шапки скрыт, и сцена выглядит статичной картинкой.
   Мобильная подсказка появляется сразу после фиксации первого кейса и
   исчезает после первого перехода: направление понятно, интерфейс чистый. */
.rv-cine__scroll-hint{ display:none; position:absolute; left:50%; z-index:4;
  bottom:calc(env(safe-area-inset-bottom,0px) + 40px); transform:translateX(-50%);
  flex-direction:column; align-items:center; gap:10px; pointer-events:none;
  font-family:var(--font-label); font-size:.62rem; letter-spacing:.2em; text-transform:uppercase;
  color:#fff; text-shadow:0 2px 14px rgba(3,0,50,.8);
  opacity:0; transition:opacity .28s var(--rv-ease); }
/* кружок делает подсказку заметной на любом фоне кейса */
.rv-cine__scroll-hint i{ position:relative; display:grid; place-items:center;
  width:44px; height:44px; border-radius:50%;
  border:1px solid rgba(245,197,66,.5); background:rgba(3,0,50,.42);
  box-shadow:0 10px 26px rgba(0,0,0,.4), inset 0 0 18px rgba(245,197,66,.08);
  animation:rvcHintDrift 1.9s ease-in-out infinite; }
/* расходящееся кольцо — движение видно даже боковым зрением */
.rv-cine__scroll-hint i::after{ content:""; position:absolute; inset:-1px; border-radius:50%;
  border:1px solid rgba(245,197,66,.55); animation:rvcHintRing 1.9s ease-out infinite; }
.rv-cine__scroll-hint svg{ display:block; width:22px; height:22px; }
.rv-cine__scroll-hint svg path{ stroke:var(--rv-gold); stroke-width:1.4; }
/* показывает и гасит скрипт: видна, пока сцена закреплена и идёт первый кейс */
.rv-cine__scroll-hint.is-live{ opacity:1; }
.rv-cine__scroll-hint.is-off,
.rv-cine.is-end .rv-cine__scroll-hint,
.rv-cine.is-static .rv-cine__scroll-hint{ opacity:0!important; }
@keyframes rvcHintDrift{
  0%,100%{ transform:translateY(-3px); }
  50%    { transform:translateY(6px); }
}
@keyframes rvcHintRing{
  0%  { transform:scale(1);    opacity:.55; }
  70% { transform:scale(1.55); opacity:0; }
  100%{ transform:scale(1.55); opacity:0; }
}
/* ── адаптив ──────────────────────────────────────────────────────────── */
@media(max-width:1100px){
  .rv-cine__stage{ grid-template-columns:1fr; align-content:center; gap:3vmin; padding-left:0; }
  .rv-cine__rail{ display:none; }
  .rv-cine__no{ top:auto; bottom:calc(100% + 10px); font-size:clamp(48px,13vw,90px); }
  .rv-cine__device{ width:min(100%,540px); margin:0 auto; }
  .rv-cine__name{ font-size:clamp(38px,9vw,64px); }
  .rv-cine__ghost{ font-size:38vw; opacity:.7; }
}
@media(max-width:700px){
  .rv-cine__pin{ padding:22px 20px 22px; touch-action:pan-y; }
  .rv-cine__hint{ display:none; }
  .rv-cine__right{ order:-1; }
  .rv-cine__device{ backdrop-filter:none; aspect-ratio:16/11; }
  .rv-cine__res{ margin-top:20px; } .rv-cine__act{ margin-top:24px; }
  .rv-cine__cta{ padding:15px 24px; font-size:.78rem; }
  .rv-cine__act{ gap:12px; flex-wrap:nowrap; }
  .rv-cine__arrows button{ width:42px; height:42px; }
  .rv-cine__arrows #rvcNext{ display:flex; flex-direction:column; align-items:center;
    justify-content:center; gap:1px; }
  .rv-cine__arrows #rvcNext small{ display:block; font-family:var(--font-label);
    font-size:.43rem; line-height:1; letter-spacing:.08em; text-transform:uppercase; }
  .rv-cine__arrows #rvcNext span{ line-height:.8; }

  /* Мобильный прогресс должен читаться как навигация, а не как декор. */
  .rv-cine__foot{ gap:12px; margin-top:12px; }
  .rv-cine__tape{ font-size:.68rem; letter-spacing:.1em; }
  .rv-cine__prog{ height:4px; background:rgba(255,255,255,.2); }
  .rv-cine__prog i::after{ right:-4px; width:9px; height:9px; margin-top:-4.5px; }

  /* Горизонтальная плашка занимает меньше высоты и не перекрывает CTA. */
  .rv-cine__scroll-hint{ display:flex; bottom:calc(env(safe-area-inset-bottom,0px) + 48px);
    flex-direction:row; gap:9px; padding:8px 10px 8px 14px; border-radius:999px;
    border:1px solid rgba(245,197,66,.38); background:rgba(3,0,50,.82);
    box-shadow:0 12px 30px rgba(0,0,0,.36), inset 0 0 20px rgba(245,197,66,.06);
    font-size:.58rem; letter-spacing:.12em; white-space:nowrap; }
  .rv-cine__scroll-hint i{ width:30px; height:30px; }
  .rv-cine__scroll-hint svg{ width:18px; height:18px; }
}
/* если sticky заблокирован предком с overflow — сцена просто стоит на месте,
   кейсы листаются стрелками */
.rv-cine.is-static{ height:auto!important; }
.rv-cine.is-static .rv-cine__pin{ position:relative; height:auto; min-height:640px; padding-top:60px; padding-bottom:60px; }

@media (prefers-reduced-motion: reduce){
  .rv-cine *, .rv-cine *::before, .rv-cine *::after{
    animation:none!important; transition:none!important; }
  .rv-cine__head, .rv-cine__rail, .rv-cine__stage, .rv-cine__foot{ opacity:1; filter:none; }
  .rv-cine__rail{ transform:translateY(-50%); }
  .rv-cine__head, .rv-cine__stage, .rv-cine__foot{ transform:none; }
  .rv-cine__name i{ opacity:1; transform:none; filter:none; }
  .rv-cine__ghost{ display:none; }
}
</style><?php
$rv_c_en = (function_exists('pll_current_language') && pll_current_language() == 'en');

/* Кейсы. k — тип «живого» экрана: seo | ads | web | target.
   g1/g2 — цвета зарева сцены. r — цифра результата (анимируется счётчиком). */
$rv_cases = array(
  array('name'=>'Ukrzoloto','url'=>'ukrzoloto.ua','year'=>'2018','k'=>'seo',
        'g1'=>'#2a2170','g2'=>'#050338','r'=>'+480%',
        'd_en'=>'Web · SEO','d_uk'=>'Web · SEO',
        'l_en'=>'organic traffic','l_uk'=>'органічного трафіку'),
  array('name'=>'Dyenergy','url'=>'dyenergy.eu','year'=>'2022','k'=>'ads',
        'g1'=>'#123a6b','g2'=>'#040a2e','r'=>'-42%',
        'd_en'=>'Google Ads · SEO','d_uk'=>'Google Ads · SEO',
        'l_en'=>'cost per lead','l_uk'=>'вартість ліда'),
  array('name'=>'Hekaya','url'=>'hekayajewellery.com','year'=>'2024','k'=>'target',
        'g1'=>'#4d3d10','g2'=>'#0d0a2c','r'=>'x3.1',
        'd_en'=>'Web · Target','d_uk'=>'Web · Таргет',
        'l_en'=>'ROAS','l_uk'=>'ROAS'),
  array('name'=>'Balitecture','url'=>'balitecture.com','year'=>'2025','k'=>'web',
        'g1'=>'#123f4d','g2'=>'#040a2e','r'=>'+260%',
        'd_en'=>'Web · Target','d_uk'=>'Web · Таргет',
        'l_en'=>'enquiries','l_uk'=>'звернень'),
  array('name'=>'Nikola service','url'=>'nikola-service.com','year'=>'2023','k'=>'web',
        'g1'=>'#2b2160','g2'=>'#050338','r'=>'+355%',
        'd_en'=>'Web · Google Ads','d_uk'=>'Web · Google Ads',
        'l_en'=>'calls from site','l_uk'=>'дзвінків із сайту'),
  array('name'=>'Fliesendekor','url'=>'fliesendekor.com','year'=>'2026','k'=>'seo',
        'g1'=>'#3a1b5e','g2'=>'#0a0630','r'=>'TOP-5',
        'd_en'=>'SEO · Google Ads','d_uk'=>'SEO · Google Ads',
        'l_en'=>'core keywords','l_uk'=>'за основними ключами'),
  array('name'=>'Horizon consulting','url'=>'horizon-consulting.com','year'=>'2025','k'=>'target',
        'g1'=>'#1a3f5e','g2'=>'#040a2e','r'=>'-51%',
        'd_en'=>'Target · Google Ads','d_uk'=>'Таргет · Google Ads',
        'l_en'=>'cost per applicant','l_uk'=>'ціна кандидата'),
  array('name'=>'Gotech HVAC','url'=>'gotech-hvac.com','year'=>'2025','k'=>'seo',
        'g1'=>'#1d1b6b','g2'=>'#030032','r'=>'+310%',
        'd_en'=>'Web · SEO','d_uk'=>'Web · SEO',
        'l_en'=>'organic traffic','l_uk'=>'органічного трафіку'),
  array('name'=>'Fastkava','url'=>'fastkava.com.pl','year'=>'2026','k'=>'seo',
        'g1'=>'#5e3a1b','g2'=>'#0d0730','r'=>'+420%',
        'd_en'=>'SEO · Web','d_uk'=>'SEO · Web',
        'l_en'=>'organic traffic','l_uk'=>'органічного трафіку'),
  array('name'=>'BrightFlow','url'=>'brightflow.com','year'=>'2026','k'=>'ads',
        'g1'=>'#2b2160','g2'=>'#050338','r'=>'+295%',
        'd_en'=>'Web · Google Ads','d_uk'=>'Web · Google Ads',
        'l_en'=>'calls from site','l_uk'=>'дзвінків із сайту'),
);
$rv_c_total = str_pad(count($rv_cases), 2, '0', STR_PAD_LEFT);

/* данные для скрипта: один JSON вместо десяти наборов DOM-узлов */
$rv_c_json = array();
foreach ($rv_cases as $rv_c) {
  $rv_c_json[] = array(
    'n' => $rv_c['name'], 'u' => $rv_c['url'], 'y' => $rv_c['year'], 'k' => $rv_c['k'],
    'g1' => $rv_c['g1'], 'g2' => $rv_c['g2'], 'r' => $rv_c['r'],
    'd' => $rv_c_en ? $rv_c['d_en'] : $rv_c['d_uk'],
    'l' => $rv_c_en ? $rv_c['l_en'] : $rv_c['l_uk'],
  );
}
?>
<div class="rv-cine__pin">
	<div class="rv-cine__aura" aria-hidden="true"><i></i><i></i></div>
	<span class="rv-cine__grain" aria-hidden="true"></span>
	<div class="rv-cine__ghost" id="rvcGhost" aria-hidden="true"></div>

	<div class="rv-cine__head">
		<div>
			<div class="rv-cine__chip"><i></i><?php echo $rv_c_en ? 'Portfolio' : 'Портфоліо'; ?></div>
			<h2 class="rv-cine__title"><?php echo $rv_c_en ? 'Selected <em>works</em>' : 'Обрані <em>роботи</em>'; ?></h2>
		</div>
		<div class="rv-cine__hint">
			<b id="rvcCur">01</b> / <?php echo esc_html($rv_c_total); ?> &mdash;
			<?php echo $rv_c_en ? 'scroll' : 'гортайте'; ?> <i>&darr;</i></div>
	</div>

	<nav class="rv-cine__rail" id="rvcRail" aria-label="<?php echo $rv_c_en ? 'Cases' : 'Кейси'; ?>">
		<span class="rv-cine__rail-prog" id="rvcRailProg" aria-hidden="true"></span>
		<?php foreach ($rv_cases as $rv_c_i => $rv_c) : ?>
		<button type="button" data-i="<?php echo esc_attr($rv_c_i); ?>">
			<?php echo esc_html(str_pad($rv_c_i + 1, 2, '0', STR_PAD_LEFT)); ?>
			<span><?php echo esc_html($rv_c['name']); ?></span>
		</button>
		<?php endforeach; ?>
	</nav>

	<div class="rv-cine__stage">
		<div class="rv-cine__left">
			<div class="rv-cine__meta" id="rvcMeta"></div>
			<h3 class="rv-cine__name" id="rvcName"></h3>
			<div class="rv-cine__res">
				<b class="rv-cine__num" id="rvcNum">&nbsp;</b>
				<span class="rv-cine__label" id="rvcLabel"></span>
			</div>
			<div class="rv-cine__act">
				<a class="rv-cine__cta" href="#win1"><?php echo $rv_c_en ? 'Discuss a project' : 'Обговорити проєкт'; ?> <span>↗︎</span></a>
				<div class="rv-cine__arrows">
					<button type="button" id="rvcPrev" aria-label="<?php echo $rv_c_en ? 'Previous case' : 'Попередній кейс'; ?>">&uarr;</button>
					<button type="button" id="rvcNext" aria-label="<?php echo $rv_c_en ? 'Next case' : 'Наступний кейс'; ?>"><small aria-hidden="true"><?php echo $rv_c_en ? 'Next' : 'Далі'; ?></small><span aria-hidden="true">&darr;</span></button>
				</div>
			</div>
		</div>

		<div class="rv-cine__right">
			<div class="rv-cine__no" aria-hidden="true"><small>CASE</small><em id="rvcNo">01</em></div>
			<div class="rv-cine__device" id="rvcDevice">
				<div class="rv-cine__bar"><s></s><s></s><s></s><span id="rvcUrl"></span></div>
				<div class="rv-cine__screen">

					<!-- SEO -->
					<div class="rv-mock rv-seo" data-k="seo">
						<div class="rv-seo__q"><u></u><span id="rvcQuery">google.com</span></div>
						<div class="rv-seo__list">
							<div class="rv-seo__nums" aria-hidden="true"><s>1</s><s>2</s><s>3</s><s>4</s><s>5</s><s>6</s></div>
							<div class="rv-seo__row rv-seo__row--a"><u></u><u></u></div>
							<div class="rv-seo__row rv-seo__row--b"><u></u><u></u></div>
							<div class="rv-seo__row"><u></u><u></u></div>
							<div class="rv-seo__row"><u></u><u></u></div>
							<div class="rv-seo__row"><u></u><u></u></div>
							<div class="rv-seo__row rv-seo__row--we"><u></u><u></u><em id="rvcSeoTag">&uarr; 8 &rarr; 1</em></div>
						</div>
					</div>

					<!-- ADS -->
					<div class="rv-mock rv-ads" data-k="ads">
						<div class="rv-ads__top">
							<div>
								<div class="rv-ads__ttl" id="rvcAdsTtl"></div>
								<div class="rv-ads__val rv-odo" id="rvcAdsVal"></div>
							</div>
							<div class="rv-ads__tag" id="rvcAdsTag"></div>
						</div>
						<div class="rv-ads__chart"><i></i><i></i><i></i><i></i><i></i><i></i><i></i></div>
						<div class="rv-ads__line" aria-hidden="true">
							<svg viewBox="0 0 100 40" preserveAspectRatio="none">
								<path class="dn" d="M2,4 L16,8 L30,15 L44,12 L58,22 L72,28 L86,34 L98,36"></path>
								<path class="up" d="M2,36 L16,32 L30,25 L44,28 L58,18 L72,11 L86,6 L98,3"></path>
							</svg>
						</div>
					</div>

					<!-- WEB -->
					<div class="rv-mock rv-web" data-k="web">
						<i></i><i></i>
						<div class="rv-web__row"><i></i><i></i><i></i></div>
						<i class="rv-web__foot"></i>
						<div class="rv-web__stat">
							<u id="rvcWebCap">result</u>
							<div class="rv-web__val rv-odo" id="rvcWebVal"></div>
							<svg class="rv-web__spark" viewBox="0 0 100 40" preserveAspectRatio="none" aria-hidden="true">
								<defs><linearGradient id="rvcSparkFill" x1="0" y1="0" x2="0" y2="1">
									<stop offset="0%" stop-color="#F5C542" stop-opacity=".45"/>
									<stop offset="100%" stop-color="#F5C542" stop-opacity="0"/>
								</linearGradient></defs>
								<path class="ar" d="M0,35 L15,31 L30,33 L45,23 L60,19 L75,11 L88,7 L100,2 L100,40 L0,40 Z"></path>
								<path class="ln" d="M0,35 L15,31 L30,33 L45,23 L60,19 L75,11 L88,7 L100,2"></path>
							</svg>
						</div>
					</div>

					<!-- TARGET -->
					<div class="rv-mock rv-tgt" data-k="target">
						<div class="rv-tgt__feed">
							<div class="rv-tgt__post"><u></u><em><s></s><s></s></em><b>&hearts; 1.2k</b></div>
							<div class="rv-tgt__post"><u></u><em><s></s><s></s></em><b>&hearts; 940</b></div>
							<div class="rv-tgt__post"><u></u><em><s></s><s></s></em><b>&hearts; 2.4k</b></div>
						</div>
						<div class="rv-tgt__side">
							<div class="rv-tgt__kpi"><u><?php echo $rv_c_en ? 'Reach' : 'Охоплення'; ?></u><b id="rvcTgtReach">1 240 000</b></div>
							<div class="rv-tgt__kpi"><u>ROAS</u><b id="rvcTgtRoas">x3.1</b>
								<div class="rv-tgt__dots" aria-hidden="true">
									<i></i><i></i><i></i><i></i><i></i><i></i><i></i>
									<i></i><i></i><i></i><i></i><i></i><i></i><i></i>
								</div>
							</div>
						</div>
					</div>

				</div>
			</div>
		</div>
	</div>

	<div class="rv-cine__foot">
		<span class="rv-cine__tape" id="rvcTape">&mdash;&mdash; case study</span>
		<div class="rv-cine__prog"><i id="rvcProg"></i></div>
	</div>

	<div class="rv-cine__scroll-hint" id="rvcScrollHint" aria-hidden="true">
		<span><?php echo $rv_c_en ? 'swipe down' : 'гортайте вниз'; ?></span>
		<i><svg viewBox="0 0 24 24" fill="none" stroke-linecap="round" stroke-linejoin="round"><path d="M12 3.5v14"/><path d="M6 12l6 6 6-6"/></svg></i>
	</div>

	<div class="rv-cine__end">
		<p><?php echo $rv_c_en ? 'Next in line' : 'Наступний у черзі'; ?></p>
		<h3><?php echo $rv_c_en ? 'Your project<br>is next' : 'Ваш проєкт<br>наступний'; ?></h3>
		<a class="rv-cine__cta" href="#win1"><?php echo $rv_c_en ? 'Start now' : 'Почати зараз'; ?> <span>↗︎</span></a>
	</div>
</div>

<?php
/* ===== МОБИЛЬНАЯ ВЕРСИЯ (≤700px): карусель кейсов, как «Revive у цифрах» =====
   Липкая сцена-«кинотеатр» на телефоне скрыта; вместо неё — горизонтальная
   карусель карточек. В каждой карточке свой «живой» экран (SEO / Ads / Web /
   Target) — те же макеты и анимации графиков, что на ПК; анимация
   проигрывается, когда карточка становится активной. Данные — из $rv_cases. */
if ( ! function_exists( 'rv_cm_odo' ) ) {
	/* '+480%' → барабан: каждая цифра — лента 0-9, остальное — статичные символы */
	function rv_cm_odo( $raw ) {
		$out = ''; $k = 0; $raw = (string) $raw;
		$len = strlen( $raw );
		for ( $i = 0; $i < $len; $i++ ) {
			$c = $raw[ $i ];
			if ( ctype_digit( $c ) ) {
				$reel = '';
				for ( $n = 0; $n <= 10 + (int) $c; $n++ ) { $reel .= '<b>' . ( $n % 10 ) . '</b>'; }
				$out .= '<i style="--d:' . ( 10 + (int) $c ) . ';--k:' . ( $k++ ) . '"><em>' . $reel . '</em></i>';
			} else {
				$out .= '<s>' . esc_html( $c ) . '</s>';
			}
		}
		return $out . '<span>' . ( '-' === substr( $raw, 0, 1 ) ? '&#8600;' : '&#8599;' ) . '</span>';
	}
}
?>
<div class="rv-cm" data-rv-cm>
	<svg class="rv-cm__defs" width="0" height="0" aria-hidden="true" focusable="false"><defs>
		<linearGradient id="rvcmSparkFill" x1="0" y1="0" x2="0" y2="1"><stop offset="0%" stop-color="#F5C542" stop-opacity=".45"/><stop offset="100%" stop-color="#F5C542" stop-opacity="0"/></linearGradient>
	</defs></svg>

	<div class="rv-cm__head">
		<div class="rv-cine__chip"><i></i><?php echo $rv_c_en ? 'Portfolio' : 'Портфоліо'; ?></div>
		<h2 class="rv-cine__title"><?php echo $rv_c_en ? 'Selected <em>works</em>' : 'Обрані <em>роботи</em>'; ?></h2>
	</div>

	<div class="rv-cm__nav" aria-hidden="true">
		<span class="rv-cm__count"><b data-cm-cur>01</b><span>/ <?php echo esc_html( $rv_c_total ); ?></span></span>
		<span class="rv-cm__bar"><i></i></span>
		<button type="button" class="rv-cm__hint" data-cm-next tabindex="-1"><?php echo $rv_c_en ? 'Swipe' : 'Гортайте'; ?> <svg viewBox="0 0 24 24"><path d="M5 12h14M13 6l6 6-6 6"/></svg></button>
	</div>

	<div class="rv-cm__track" role="list" data-cm-track>
	<?php foreach ( $rv_cases as $rv_c_i => $rv_c ) :
		$rv_cm_d  = $rv_c_en ? $rv_c['d_en'] : $rv_c['d_uk'];
		$rv_cm_l  = $rv_c_en ? $rv_c['l_en'] : $rv_c['l_uk'];
		$rv_cm_up = '-' !== substr( $rv_c['r'], 0, 1 );
	?>
		<article class="rv-cm__card" role="listitem" style="--c1:<?php echo esc_attr( $rv_c['g1'] ); ?>">
			<div class="rv-cm__meta">
				<span><b><?php echo esc_html( str_pad( $rv_c_i + 1, 2, '0', STR_PAD_LEFT ) ); ?></b> / <?php echo esc_html( $rv_c['year'] ); ?></span>
				<span class="rv-cm__dir"><?php echo esc_html( $rv_cm_d ); ?></span>
			</div>

			<div class="rv-cm__device" aria-hidden="true">
				<div class="rv-cine__bar"><s></s><s></s><s></s><span><?php echo esc_html( $rv_c['url'] ); ?></span></div>
				<div class="rv-cm__screen">
				<?php if ( 'seo' === $rv_c['k'] ) : ?>
					<div class="rv-mock rv-seo is-on">
						<div class="rv-seo__q"><u></u><span><?php echo esc_html( strtok( $rv_c['url'], '.' ) ); ?></span></div>
						<div class="rv-seo__list">
							<div class="rv-seo__nums"><s>1</s><s>2</s><s>3</s><s>4</s><s>5</s><s>6</s></div>
							<div class="rv-seo__row rv-seo__row--a"><u></u><u></u></div>
							<div class="rv-seo__row rv-seo__row--b"><u></u><u></u></div>
							<div class="rv-seo__row"><u></u><u></u></div>
							<div class="rv-seo__row"><u></u><u></u></div>
							<div class="rv-seo__row"><u></u><u></u></div>
							<div class="rv-seo__row rv-seo__row--we"><u></u><u></u><em>&uarr; 8 &rarr; 1</em></div>
						</div>
					</div>
				<?php elseif ( 'ads' === $rv_c['k'] ) : ?>
					<div class="rv-mock rv-ads is-on<?php echo $rv_cm_up ? ' is-up' : ''; ?>">
						<div class="rv-ads__top">
							<div>
								<div class="rv-ads__ttl"><?php echo esc_html( $rv_cm_l ); ?></div>
								<div class="rv-ads__val rv-odo"><?php echo rv_cm_odo( $rv_c['r'] ); ?></div>
							</div>
							<div class="rv-ads__tag"><?php echo esc_html( $rv_cm_d ); ?></div>
						</div>
						<div class="rv-ads__chart"><i></i><i></i><i></i><i></i><i></i><i></i><i></i></div>
						<div class="rv-ads__line">
							<svg viewBox="0 0 100 40" preserveAspectRatio="none">
								<path class="dn" d="M2,4 L16,8 L30,15 L44,12 L58,22 L72,28 L86,34 L98,36"></path>
								<path class="up" d="M2,36 L16,32 L30,25 L44,28 L58,18 L72,11 L86,6 L98,3"></path>
							</svg>
						</div>
					</div>
				<?php elseif ( 'web' === $rv_c['k'] ) : ?>
					<div class="rv-mock rv-web is-on">
						<i></i><i></i>
						<div class="rv-web__row"><i></i><i></i><i></i></div>
						<i class="rv-web__foot"></i>
						<div class="rv-web__stat">
							<u><?php echo esc_html( $rv_cm_l ); ?></u>
							<div class="rv-web__val rv-odo"><?php echo rv_cm_odo( $rv_c['r'] ); ?></div>
							<svg class="rv-web__spark" viewBox="0 0 100 40" preserveAspectRatio="none">
								<path class="ar" d="M0,35 L15,31 L30,33 L45,23 L60,19 L75,11 L88,7 L100,2 L100,40 L0,40 Z"></path>
								<path class="ln" d="M0,35 L15,31 L30,33 L45,23 L60,19 L75,11 L88,7 L100,2"></path>
							</svg>
						</div>
					</div>
				<?php else : ?>
					<div class="rv-mock rv-tgt is-on">
						<div class="rv-tgt__feed">
							<div class="rv-tgt__post"><u></u><em><s></s><s></s></em><b>&hearts; 1.2k</b></div>
							<div class="rv-tgt__post"><u></u><em><s></s><s></s></em><b>&hearts; 940</b></div>
							<div class="rv-tgt__post"><u></u><em><s></s><s></s></em><b>&hearts; 2.4k</b></div>
						</div>
						<div class="rv-tgt__side">
							<div class="rv-tgt__kpi"><u><?php echo $rv_c_en ? 'Reach' : 'Охоплення'; ?></u><b>1 240 000</b></div>
							<div class="rv-tgt__kpi"><u><?php echo esc_html( $rv_cm_l ); ?></u><b><?php echo esc_html( $rv_c['r'] ); ?></b>
								<div class="rv-tgt__dots"><i></i><i></i><i></i><i></i><i></i><i></i><i></i><i></i><i></i><i></i><i></i><i></i><i></i><i></i></div>
							</div>
						</div>
					</div>
				<?php endif; ?>
				</div>
			</div>

			<h3 class="rv-cm__name"><?php echo esc_html( $rv_c['name'] ); ?></h3>
			<div class="rv-cm__res">
				<b class="rv-cm__num rv-odo" aria-label="<?php echo esc_attr( $rv_c['r'] ); ?>"><?php echo rv_cm_odo( $rv_c['r'] ); ?></b>
				<span class="rv-cm__label"><?php echo esc_html( $rv_cm_l ); ?></span>
			</div>
		</article>
	<?php endforeach; ?>
	</div>

	<a class="rv-cine__cta rv-cm__cta" href="#win1"><?php echo $rv_c_en ? 'Discuss a project' : 'Обговорити проєкт'; ?> <span>↗︎</span></a>
</div>

<style>
.rv-cm{display:none}
@media(max-width:700px){
  /* липкая сцена на телефоне не нужна — только карусель */
  .rv-cine__pin{display:none!important}
  .rv-cine{height:auto!important}
  .rv-cm{display:block;position:relative;overflow:hidden;padding:48px 20px 44px;
    background:radial-gradient(60% 40% at 90% 10%,rgba(245,197,66,.08),transparent 70%),radial-gradient(60% 45% at 5% 90%,rgba(100,108,255,.14),transparent 72%)}
  .rv-cm__defs{position:absolute;width:0;height:0;overflow:hidden}
  .rv-cm__head{margin-bottom:18px}
  .rv-cm .rv-cine__title{font-size:clamp(26px,8.2vw,34px);margin-top:14px}

  /* навигация: как в «Revive у цифрах» */
  .rv-cm__nav{display:flex;align-items:center;gap:16px;margin-bottom:14px}
  .rv-cm__count{display:flex;align-items:center;gap:6px;font-family:var(--font-label);font-size:.8rem;letter-spacing:.08em;color:#fff}
  .rv-cm__count b{position:relative;display:grid;place-items:center;width:40px;height:40px;border-radius:50%;border:1px solid rgba(245,197,66,.55);font-weight:500;color:#fff}
  .rv-cm__count b::before{content:"";position:absolute;top:5px;left:50%;width:5px;height:5px;margin-left:-2.5px;border-radius:50%;background:var(--rv-gold);box-shadow:0 0 8px var(--rv-gold)}
  .rv-cm__count>span{opacity:.75}
  .rv-cm__bar{position:relative;flex:1;max-width:110px;height:2px;border-radius:2px;background:rgba(255,255,255,.14);overflow:hidden}
  .rv-cm__bar i{position:absolute;inset:0;background:var(--rv-gold);transform-origin:left;transform:scaleX(var(--p,.1));transition:transform .45s var(--rv-ease)}
  .rv-cm .rv-cm__hint{display:inline-flex;align-items:center;gap:8px;margin-left:auto;font-family:var(--font-label);font-size:.7rem;letter-spacing:.16em;text-transform:uppercase;color:#fff;line-height:1}
  .rv-cm__hint svg{width:16px;height:16px;fill:none;stroke:var(--rv-gold);stroke-width:2;stroke-linecap:round;stroke-linejoin:round;animation:rvCmNudge 1.6s ease-in-out infinite}

  /* лента карточек */
  .rv-cm__track{display:flex;gap:12px;overflow-x:auto;overflow-y:hidden;scroll-snap-type:x mandatory;scroll-padding-left:20px;
    margin:0 -20px;padding:4px 20px 6px;-webkit-overflow-scrolling:touch;overscroll-behavior-x:contain;scrollbar-width:none}
  .rv-cm__track::-webkit-scrollbar{display:none}
  .rv-cm__track::after{content:"";flex:0 0 8px}
  .rv-cm__card{position:relative;overflow:hidden;isolation:isolate;contain:layout paint;flex:0 0 86%;scroll-snap-align:start;scroll-snap-stop:always;display:flex;flex-direction:column;
    padding:18px 16px 20px;border-radius:26px;border:1px solid rgba(255,255,255,.1);
    background:radial-gradient(90% 55% at 80% 0%,color-mix(in srgb,var(--c1) 70%,transparent),transparent 72%),linear-gradient(160deg,rgba(255,255,255,.06),rgba(255,255,255,.015) 70%);
    box-shadow:inset 0 1px 0 rgba(255,255,255,.06);transition:border-color .4s ease}
  .rv-cm__card::after{content:"";position:absolute;left:24px;right:24px;top:0;height:1px;background:linear-gradient(90deg,transparent,var(--rv-gold),transparent);transform:scaleX(0);transition:transform 1s var(--rv-ease) .1s}
  .rv-cm__card.is-play{border-color:rgba(245,197,66,.3)}
  .rv-cm__card.is-play::after{transform:scaleX(1)}
  .rv-cm__meta{display:flex;justify-content:space-between;align-items:center;gap:10px;font-family:var(--font-label);font-size:.62rem;letter-spacing:.12em;text-transform:uppercase;color:#fff}
  .rv-cm__meta b{font-weight:500;color:var(--rv-gold)}
  .rv-cm__dir{opacity:.8;white-space:nowrap;overflow:hidden;text-overflow:ellipsis}

  /* окно браузера с «живым» экраном */
  .rv-cm__device{position:relative;backface-visibility:hidden;display:flex;flex-direction:column;margin-top:14px;aspect-ratio:16/11;border-radius:16px;overflow:hidden;
    background:rgba(255,255,255,.06);border:1px solid rgba(255,255,255,.16);box-shadow:0 24px 50px rgba(0,0,0,.45),inset 0 0 0 1px rgba(255,255,255,.05)}
  .rv-cm .rv-cine__bar{flex:none;padding:.7rem .9rem}
  .rv-cm .rv-cine__bar span{font-size:.62rem}
  .rv-cm__screen{position:relative;flex:1;min-height:0;overflow:hidden}
  .rv-cm .rv-web__spark .ar{fill:url(#rvcmSparkFill)}
  /* карточка не активна — макет в стартовом кадре; активна — анимация идёт.
     Правила .rv-mock.is-play из стилей ПК срабатывают через класс на макете. */

  /* имя и цифра результата */
  .rv-cm__name{margin:16px 0 0;font-family:var(--rv-h);font-weight:800;font-size:clamp(28px,8.6vw,36px);line-height:1;letter-spacing:-.035em;color:#fff}
  .rv-cm__res{display:flex;align-items:baseline;gap:12px}
  .rv-cm__num{--odo-d:.3s;font-size:clamp(30px,9vw,40px);text-shadow:none}
  /* свечение цифры — статичный слой, а не filter: фильтр перерисовывался бы каждый кадр барабана */
  .rv-cm__res{position:relative}
  .rv-cm__res::before{content:"";position:absolute;left:-14px;top:50%;width:160px;height:70px;margin-top:-35px;z-index:-1;pointer-events:none;background:radial-gradient(closest-side,rgba(245,197,66,.16),transparent)}
  .rv-cm__res{margin-top:auto;padding-top:10px}
  .rv-cm__card.is-play .rv-cm__num i em{transform:translateY(calc(var(--d) * -1em));transition:transform 1.25s cubic-bezier(.16,1,.3,1);transition-delay:calc(var(--odo-d) + var(--k) * .1s)}
  .rv-cm__card.is-play .rv-cm__num span{animation:rvcArrow .7s var(--rv-ease) calc(var(--odo-d) + .95s) both}
  .rv-cm__label{font-family:var(--font-label);font-size:.66rem;letter-spacing:.1em;text-transform:uppercase;color:#fff;line-height:1.35}

  .rv-cm__cta.rv-cine__cta{display:flex;justify-content:center;margin-top:18px;padding:16px 24px;font-size:.8rem}
  /* <i>/<em> по умолчанию курсив — из-за наклона цифры барабана обрезались по краю ячейки */
  .rv-cm i,.rv-cm b,.rv-cm em,.rv-cm s{font-style:normal}
  .rv-cm__num i{padding:0 .02em}

  /* ── появление блока: шапка, навигация, карточки веером справа, кнопка ── */
  .rv-cm--js .rv-cm__head,.rv-cm--js .rv-cm__nav,.rv-cm--js .rv-cm__cta{opacity:0;transform:translate3d(0,26px,0)}
  .rv-cm--js .rv-cm__card{opacity:0;transform:translate3d(36px,0,0) scale(.97)}
  .rv-cm--js.is-in .rv-cm__head{animation:rvCmUp .8s var(--rv-ease) both}
  .rv-cm--js.is-in .rv-cm__nav{animation:rvCmUp .8s var(--rv-ease) .1s both}
  .rv-cm--js.is-in .rv-cm__card{animation:rvCmCard .85s var(--rv-ease) calc(.12s + var(--ci,0) * .09s) both}
  .rv-cm--js.is-in .rv-cm__cta{animation:rvCmUp .8s var(--rv-ease) .5s both}

  /* ── карточка стала активной: окно «выпрыгивает», текст собирается снизу ── */
  .rv-cm__card.is-play .rv-cm__meta{animation:rvCmUp .7s var(--rv-ease) both}
  .rv-cm__card.is-play .rv-cm__device{animation:rvCmDev .8s var(--rv-ease) both}
  .rv-cm__card.is-play .rv-cm__name{animation:rvCmUp .8s var(--rv-ease) .12s both}
  .rv-cm__card.is-play .rv-cm__label{animation:rvCmUp .8s var(--rv-ease) .24s both}
}
@keyframes rvCmUp{from{opacity:0;transform:translate3d(0,16px,0)}to{opacity:1;transform:translate3d(0,0,0)}}
@keyframes rvCmCard{from{opacity:0;transform:translate3d(36px,0,0) scale(.97)}to{opacity:1;transform:translate3d(0,0,0) scale(1)}}
@keyframes rvCmDev{from{opacity:.4;transform:translate3d(0,14px,0) scale(.965)}to{opacity:1;transform:translate3d(0,0,0) scale(1)}}
@keyframes rvCmNudge{0%,100%{transform:translateX(0)}50%{transform:translateX(4px)}}
@media(max-width:700px) and (prefers-reduced-motion:reduce){
  .rv-cm .rv-odo i em{transform:translateY(calc(var(--d) * -1em))!important}
  .rv-cm__num{filter:none}
  .rv-cm .rv-odo span{opacity:1!important}
  .rv-cm *{animation:none!important}
}
</style>
<script>
/* Карусель кейсов на телефоне: счётчик, прогресс, «Гортайте» и запуск анимации
   графика в карточке, которая вошла в кадр. Каждая карточка «оживает» один раз
   и дальше остаётся в финальном состоянии — без сбросов и рывков при листании. */
(function(){
  'use strict';
  var root = document.querySelector('[data-rv-cm]');
  if (!root) return;
  var track = root.querySelector('[data-cm-track]');
  var cards = [].slice.call(root.querySelectorAll('.rv-cm__card'));
  var curEl = root.querySelector('[data-cm-cur]');
  var bar   = root.querySelector('.rv-cm__bar i');
  var next  = root.querySelector('[data-cm-next]');
  var reduce = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
  if (!track || !cards.length) return;

  cards.forEach(function(c, n){ c.style.setProperty('--ci', Math.min(n, 3)); });

  function play(c){
    if (c.classList.contains('is-play')) return;
    c.classList.add('is-play');
    var m = c.querySelector('.rv-mock'); if (m) m.classList.add('is-play');
  }

  /* счётчик и полоска — только при реальном смещении, в одном кадре */
  var lastI = -1, raf = 0, step = 0;
  function measureStep(){ step = cards.length > 1 ? (cards[1].offsetLeft - cards[0].offsetLeft) : track.clientWidth; }
  function paint(){
    raf = 0;
    if (!step) measureStep();
    var i = Math.round(track.scrollLeft / (step || 1));
    if (track.scrollLeft + track.clientWidth >= track.scrollWidth - 4) i = cards.length - 1;
    i = Math.max(0, Math.min(cards.length - 1, i));
    if (i === lastI) return;
    lastI = i;
    if (curEl) curEl.textContent = (i < 9 ? '0' : '') + (i + 1);
    if (bar) bar.style.setProperty('--p', ((i + 1) / cards.length).toFixed(3));
  }
  track.addEventListener('scroll', function(){ if (!raf) raf = requestAnimationFrame(paint); }, { passive: true });
  window.addEventListener('resize', function(){ step = 0; paint(); }, { passive: true });
  paint();

  if (next) next.addEventListener('click', function(){
    if (!step) measureStep();
    var n = (lastI + 1) % cards.length;
    track.scrollTo({ left: n * step, behavior: reduce ? 'auto' : 'smooth' });
  });

  if (reduce || !('IntersectionObserver' in window)) { cards.forEach(play); return; }

  root.classList.add('rv-cm--js');
  var seen = false, visible = [];

  /* карточка на 60% в кадре — запускаем её анимацию (ещё во время доводки свайпа) */
  var cio = new IntersectionObserver(function(es){
    es.forEach(function(e){
      var c = e.target, k = visible.indexOf(c);
      if (e.isIntersecting) { if (k < 0) visible.push(c); if (seen) play(c); }
      else if (k >= 0) visible.splice(k, 1);
    });
  }, { root: track, threshold: .6 });
  cards.forEach(function(c){ cio.observe(c); });

  /* весь блок показался — влёт карточек, затем «оживает» первая */
  var io = new IntersectionObserver(function(es){
    if (!es[0].isIntersecting) return;
    io.disconnect();
    root.classList.add('is-in');
    setTimeout(function(){ seen = true; visible.forEach(play); }, 600);
  }, { threshold: .2 });
  io.observe(root);
})();
</script>

<script>
(function(){
'use strict';
var sec = document.getElementById('rv-cine');
if(!sec) return;

var mobile = window.matchMedia('(max-width:700px)');
var CASES = <?php echo wp_json_encode($rv_c_json); ?>;
var N = CASES.length;                 /* кейсы */
var TRAVEL = N + 0.5;                 /* шагов пути: N-1 между кейсами + 1.5 на финальный экран */
var STEP_VH_DESKTOP = 0.62;
var STEP_VH_MOBILE  = 0.52;           /* короче путь пальцем, десктоп остаётся без изменений */
function stepVH(){ return mobile.matches ? STEP_VH_MOBILE : STEP_VH_DESKTOP; }

var pin      = sec.querySelector('.rv-cine__pin');
var ghost    = document.getElementById('rvcGhost');
var railBtns = [].slice.call(sec.querySelectorAll('.rv-cine__rail button'));
var railProg = document.getElementById('rvcRailProg');
var elMeta   = document.getElementById('rvcMeta');
var elName   = document.getElementById('rvcName');
var elNum    = document.getElementById('rvcNum');
var elLabel  = document.getElementById('rvcLabel');
var elNo     = document.getElementById('rvcNo');
var elUrl    = document.getElementById('rvcUrl');
var elCur    = document.getElementById('rvcCur');
var elProg   = document.getElementById('rvcProg');
var elTape   = document.getElementById('rvcTape');
var device   = document.getElementById('rvcDevice');
var mocks    = [].slice.call(pin.querySelectorAll('.rv-mock'));   /* только сцена ПК: у мобильной карусели свои макеты */
var elQuery  = document.getElementById('rvcQuery');
var elSeoTag = document.getElementById('rvcSeoTag');
var elAdsVal = document.getElementById('rvcAdsVal');
var elAdsTag = document.getElementById('rvcAdsTag');
var elAdsTtl = document.getElementById('rvcAdsTtl');
var elTgtRoas= document.getElementById('rvcTgtRoas');
var elWebVal = document.getElementById('rvcWebVal');
var elWebCap = document.getElementById('rvcWebCap');
var btnPrev  = document.getElementById('rvcPrev');
var btnNext  = document.getElementById('rvcNext');
var hint     = document.getElementById('rvcScrollHint');
var hintOff  = false;
function killHint(){
  if(hintOff || !hint) return;
  hintOff = true; hint.classList.remove('is-live'); hint.classList.add('is-off');
}
/* На мобильном показываем направление сразу при фиксации первого кейса.
   После перехода к кейсу 02 механика уже усвоена — подсказка не возвращается. */
function hintTick(caseIndex){
  if(!hint) return;
  var show = false;
  if(!hintOff && mobile.matches && !staticMode && !sec.classList.contains('is-end')){
    var rr = sec.getBoundingClientRect();
    var pinned = rr.top <= 8 && rr.bottom >= sceneHeight * 0.9;
    show = pinned && caseIndex === 0;
  }
  hint.classList.toggle('is-live', show);
}

var reduced = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
/* Высоту закреплённой сцены берём из её CSS-высоты (calc(var(--rv-svh,1svh)*100)), а не из
   динамического window.innerHeight. На iOS/Android innerHeight меняется,
   когда скрывается адресная строка, и раньше сдвигал всю страницу. */
var sceneHeight = Math.max(pin.clientHeight, 1), measuredViewportWidth = 0;
var idx = -1, seek = -1, ticking = false, staticMode = false, numRAF = 0;
var pad = function(n){ return ('0' + n).slice(-2); };

/* sticky молча ломается, если у любого предка overflow не visible */
function stickyBlocked(){
  for(var el = sec.parentElement; el && el !== document.documentElement; el = el.parentElement){
    var o = getComputedStyle(el);
    if(/(hidden|auto|scroll|clip)/.test(o.overflow + o.overflowX + o.overflowY)) return true;
  }
  return false;
}

/* ── счётчик цифры: '+480%' → префикс '+', число 480, суффикс '%' ────── */
function rollNumber(raw){
  cancelAnimationFrame(numRAF);
  var m = String(raw).match(/^([^\d]*)(\d+(?:[.,]\d+)?)(.*)$/);
  if(!m || reduced){ elNum.textContent = raw; return; }
  var pre = m[1], post = m[3], txt = m[2].replace(',', '.');
  var target = parseFloat(txt);
  var dec = (txt.split('.')[1] || '').length;
  var t0 = 0, DUR = 1100;
  numRAF = requestAnimationFrame(function loop(ts){
    if(!t0) t0 = ts;
    var p = Math.min(1, (ts - t0) / DUR);
    var v = target * (1 - Math.pow(1 - p, 3));
    elNum.textContent = pre + v.toFixed(dec) + post;
    if(p < 1) numRAF = requestAnimationFrame(loop);
  });
}

/* ── цифра-барабан для макета Web: каждая цифра прокручивает полный круг 0-9
      и останавливается на нужной, соседние — со сдвигом по времени ───────── */
function buildOdometer(host, raw){
  if(!host) return;
  host.innerHTML = '';
  var chars = String(raw).split(''), k = 0;
  for(var i = 0; i < chars.length; i++){
    var c = chars[i];
    if(c >= '0' && c <= '9'){
      var cell = document.createElement('i');
      var reel = document.createElement('em');
      for(var n = 0; n <= 10 + (+c); n++){
        var b = document.createElement('b');
        b.textContent = String(n % 10);
        reel.appendChild(b);
      }
      cell.style.setProperty('--d', 10 + (+c));   /* оборот + приземление */
      cell.style.setProperty('--k', k++);          /* сдвиг соседних цифр */
      cell.appendChild(reel);
      host.appendChild(cell);
    }else{
      var st = document.createElement('s');
      st.textContent = c;
      host.appendChild(st);
    }
  }
  var arrow = document.createElement('span');
  arrow.textContent = (String(raw).charAt(0) === '-') ? '\u2198' : '\u2197';
  host.appendChild(arrow);
}

/* ── побуквенный влёт имени ───────────────────────────────────────────── */
function setName(text){
  elName.classList.add('is-out');
  setTimeout(function(){
    elName.classList.remove('is-on');
    elName.innerHTML = '';
    var words = String(text).split(' '), d = 0;
    for(var w = 0; w < words.length; w++){
      var wrap = document.createElement('w');      /* слово целиком: не рвётся переносом */
      for(var i = 0; i < words[w].length; i++){
        var s = document.createElement('i');
        s.style.setProperty('--d', d++);
        s.textContent = words[w].charAt(i);
        wrap.appendChild(s);
      }
      elName.appendChild(wrap);
      if(w < words.length - 1) elName.appendChild(document.createTextNode(' '));
    }
    elName.classList.remove('is-out');
    requestAnimationFrame(function(){
      requestAnimationFrame(function(){ elName.classList.add('is-on'); });
    });
  }, reduced ? 0 : 220);
}

/* ── перерисовка сцены под кейс i ─────────────────────────────────────── */
function render(i){
  var c = CASES[i];
  if(!c) return;

  sec.style.setProperty('--c1', c.g1);
  sec.style.setProperty('--c2', c.g2);

  elMeta.innerHTML = '<b>' + c.u + '</b><s>&middot;</s><span>' + c.y + '</span><s>&middot;</s><span>' + c.d + '</span>';
  elUrl.textContent = c.u;
  elLabel.textContent = c.l;
  elNo.textContent = pad(i + 1);
  elCur.textContent = pad(i + 1);
  ghost.textContent = c.n;
  elTape.textContent = '—— case ' + pad(i + 1) + ' / ' + pad(N);

  setName(c.n);

  elNum.classList.remove('is-on');
  void elNum.offsetWidth;
  rollNumber(c.r);
  elNum.classList.add('is-on');

  /* подписи внутри макетов подстраиваются под кейс.
     Строго до is-play: узлы, вставленные в уже «играющий» макет, получили бы
     конечное состояние сразу, без перехода — барабан цифр не прокрутился бы. */
  if(elQuery) elQuery.textContent = c.u.split('.')[0];
  if(elSeoTag) elSeoTag.innerHTML = '&uarr; 8 &rarr; 1';
  if(elAdsTtl) elAdsTtl.textContent = c.l;
  if(elAdsTag) elAdsTag.textContent = c.d;
  if(c.k === 'ads'){
    /* знак метрики решает, куда идёт график: «-42% вартість ліда» — вниз,
       «+295% дзвінків» — вверх. Иначе картинка спорит с подписью. */
    var adsMock = pin.querySelector('.rv-mock.rv-ads');
    if(adsMock) adsMock.classList.toggle('is-up', c.r.charAt(0) !== '-');
    buildOdometer(elAdsVal, c.r);
  }
  if(elTgtRoas) elTgtRoas.textContent = c.r;
  if(elWebCap) elWebCap.textContent = c.l;
  if(c.k === 'web') buildOdometer(elWebVal, c.r);

  /* живой экран нужного типа: перезапуск анимаций через снятие класса */
  for(var m = 0; m < mocks.length; m++){
    var on = mocks[m].getAttribute('data-k') === c.k;
    mocks[m].classList.toggle('is-on', on);
    mocks[m].classList.remove('is-play');
  }
  var act = pin.querySelector('.rv-mock.is-on');
  if(act && !reduced){ void act.offsetWidth; act.classList.add('is-play'); }
  else if(act){ act.classList.add('is-play'); }

  if(!reduced){ device.classList.remove('is-swap'); void device.offsetWidth; device.classList.add('is-swap'); }

  for(var b = 0; b < railBtns.length; b++) railBtns[b].classList.toggle('is-on', b === i);
  if(railProg && railBtns.length){
    var host = railBtns[i] || railBtns[0];
    railProg.style.height = (host.offsetTop + host.offsetHeight - 4) + 'px';
  }
  if(btnPrev) btnPrev.disabled = (i === 0);
}

/* ── скролл ───────────────────────────────────────────────────────────── */
function measure(){
  if(window.innerWidth === measuredViewportWidth) return;
  measuredViewportWidth = window.innerWidth;
  /* телефон: вместо липкой сцены карусель .rv-cm — длинный путь прокрутки не нужен */
  if(mobile.matches){ staticMode = true; sec.classList.remove('is-static'); sec.style.height = ''; return; }
  staticMode = stickyBlocked();
  sec.classList.toggle('is-static', staticMode);
  if(staticMode){ sec.style.height = ''; if(idx < 0) go(0); return; }
  sceneHeight = Math.max(pin.clientHeight, 1);
  sec.style.height = (sceneHeight * (1 + TRAVEL * stepVH())) + 'px';
  update();
}

function update(){
  if(staticMode) return;
  var r = sec.getBoundingClientRect();
  var total = sec.offsetHeight - sceneHeight;
  if(total <= 0) return;
  var p = Math.min(1, Math.max(0, -r.top / total));
  var pos = p * TRAVEL;                     /* 0 … N+0.5; слот N — финальный экран */
  var slot = Math.max(0, Math.min(N, Math.floor(pos + 0.5)));
  var i = Math.min(N - 1, slot);
  var t = Math.max(-0.5, Math.min(0.5, pos - slot));

  sec.style.setProperty('--t', t.toFixed(3));
  elProg.style.width = (p * 100) + '%';
  sec.classList.toggle('is-end', slot >= N);
  if(i > 0 && idx === 0) killHint();        /* первый переход объяснил механику */

  if(i !== idx){ idx = i; render(i); }
  hintTick(i);
  if(seek === idx) seek = -1;              /* цель прокрутки достигнута */
}

function go(i){
  i = Math.max(0, Math.min(N - 1, i));
  killHint();                                /* листает стрелками — подсказка тоже не нужна */
  seek = i;                                  /* два быстрых клика подряд не «слипаются» */
  if(staticMode){ seek = -1; idx = i; render(i); return; }
  var total = sec.offsetHeight - sceneHeight;
  var top = sec.getBoundingClientRect().top + (window.pageYOffset || document.documentElement.scrollTop);
  window.scrollTo({ top: top + (i / TRAVEL) * total, behavior: reduced ? 'auto' : 'smooth' });
}

addEventListener('scroll', function(){
  if(ticking || staticMode) return;
  ticking = true;
  requestAnimationFrame(function(){ update(); ticking = false; });
}, { passive:true });
addEventListener('resize', measure, {passive:true});

for(var b = 0; b < railBtns.length; b++){
  (function(btn){
    btn.addEventListener('click', function(){ go(parseInt(btn.getAttribute('data-i'), 10)); });
  })(railBtns[b]);
}
function here(){ return seek >= 0 ? seek : idx; }
if(btnPrev) btnPrev.addEventListener('click', function(){ go(here() - 1); });
if(btnNext) btnNext.addEventListener('click', function(){ go(here() + 1); });

/* лёгкий 3D-отклик окна на мышь: максимум 12°, читается как глубина */
if(!reduced && window.matchMedia('(hover: hover)').matches){
  pin.addEventListener('pointermove', function(e){
    var r = pin.getBoundingClientRect();
    device.style.setProperty('--mx', ((e.clientX - r.left) / r.width - .5).toFixed(3));
    device.style.setProperty('--my', ((e.clientY - r.top) / r.height - .5).toFixed(3));
  });
  pin.addEventListener('pointerleave', function(){
    device.style.setProperty('--mx', 0); device.style.setProperty('--my', 0);
  });
}

/* появление секции */
if('IntersectionObserver' in window){
  new IntersectionObserver(function(es, o){
    if(!es[0].isIntersecting) return;
    sec.classList.add('is-in'); o.disconnect();
  }, { threshold:.05 }).observe(sec);
}else{ sec.classList.add('is-in'); }

render(0); idx = 0;
if(hint) setTimeout(function(){ hintTick(idx); }, 250);
if(document.readyState === 'complete') measure();
else addEventListener('load', measure);
measure();
})();
</script>
</section>
<!-- ===== /RV CINE ===== -->

<!-- ===== RV QUIZ: калькулятор бюджета, 4 вопроса ===== -->
<section class="rv-quiz" id="rv-quiz">
<style>
.rv-quiz{ --font-label:'JetBrains Mono',monospace; --rv-h:'Manrope','Montserrat',Arial,sans-serif;
  --rv-gold:#F5C542; position:relative; background:#030032; color:#fff; padding:110px 0 120px; overflow:hidden; }
.rv-quiz::before{ content:""; position:absolute; inset:0; pointer-events:none;
  background:radial-gradient(ellipse 44% 54% at 82% 16%, rgba(245,197,66,.09) 0%, transparent 68%); }
.rv-quiz__wrap{ position:relative; max-width:none; padding:0 clamp(20px,4vw,64px);
  display:grid; grid-template-columns:minmax(300px,.72fr) minmax(0,1.6fr); gap:clamp(34px,4vw,80px);
  align-items:start; }
.rv-quiz__chip{ display:inline-flex; align-items:center; gap:.7rem; background:rgba(255,255,255,.05);
  border:1px solid rgba(255,255,255,.12); border-radius:100px; padding:.5rem 1.1rem;
  font-family:var(--font-label); font-size:.66rem; letter-spacing:.1em; text-transform:uppercase;
  color:#fff; }
.rv-quiz__chip i{ font-style:normal; width:7px; height:7px; border-radius:50%; background:var(--rv-gold);
  box-shadow:0 0 12px var(--rv-gold); }
.rv-quiz__title{ font-family:var(--rv-h); font-weight:800; letter-spacing:-.03em; text-transform:uppercase;
  font-size:clamp(34px,4vw,58px); line-height:1.05; margin:22px 0 24px; color:#fff; -webkit-text-fill-color:#fff; }
.rv-quiz__title em{ font-style:normal; color:transparent; -webkit-text-fill-color:transparent;
  -webkit-text-stroke:1.5px var(--rv-gold); padding-right:2px; }
.rv-quiz__lead{ font-size:1rem; line-height:1.65; color:#fff; margin:0; max-width:44ch; }
.rv-quiz__facts{ list-style:none; margin:26px 0 0; padding:0; display:flex; flex-direction:column; gap:10px; }
.rv-quiz__facts li{ display:flex; gap:12px; align-items:flex-start; font-size:.92rem; line-height:1.5;
  color:#fff; }
.rv-quiz__facts li::before{ content:"—"; color:var(--rv-gold); }
/* карточка */
/* высота фиксированная: иначе карточка прыгает между шагами
   (2 варианта ответа против 4) и страница дёргается под пальцем */
.rv-quiz__card{ border:1px solid rgba(255,255,255,.12); border-radius:24px; background:rgba(255,255,255,.045);
  padding:clamp(24px,2.4vw,40px); display:flex; flex-direction:column;
  min-height:clamp(360px,30vw,470px); }
.rv-quiz__form{ flex:1; }
.rv-quiz__bar{ display:flex; align-items:center; gap:16px; margin-bottom:26px; }
.rv-quiz__track{ flex:1; height:3px; border-radius:2px; background:rgba(255,255,255,.12); overflow:hidden; }
.rv-quiz__track i{ display:block; height:100%; width:25%; border-radius:2px; background:var(--rv-gold);
  box-shadow:0 0 14px rgba(245,197,66,.5); transition:width .45s cubic-bezier(.22,1,.36,1); }
.rv-quiz__step{ font-family:var(--font-label); font-size:.7rem; letter-spacing:.1em; color:#fff;
  white-space:nowrap; }
.rv-quiz__step b{ color:var(--rv-gold); font-weight:500; }
.rv-quiz__q{ font-family:var(--rv-h); font-weight:800; font-size:clamp(1.15rem,1.7vw,1.6rem); line-height:1.25;
  letter-spacing:-.02em; margin:0 0 20px; }
.rv-quiz__opts{ display:grid; grid-template-columns:repeat(2,minmax(0,1fr)); gap:12px; }
.rv-quiz__opt{ display:flex; align-items:center; gap:14px; min-height:64px; padding:14px 20px; text-align:left;
  border:1px solid rgba(255,255,255,.14); border-radius:16px; background:rgba(255,255,255,.04); color:#fff;
  font-family:inherit; font-size:.98rem; line-height:1.35; cursor:pointer; -webkit-appearance:none; appearance:none;
  transition:border-color .25s ease, background .25s ease, transform .25s ease; }
.rv-quiz__opt span{ flex:none; width:22px; height:22px; border-radius:50%; border:1px solid rgba(245,197,66,.55);
  position:relative; }
.rv-quiz__opt span::after{ content:""; position:absolute; inset:5px; border-radius:50%; background:var(--rv-gold);
  transform:scale(0); transition:transform .25s cubic-bezier(.34,1.56,.64,1); }
.rv-quiz__opt:hover{ border-color:rgba(245,197,66,.5); background:rgba(255,255,255,.07); }
.rv-quiz__opt.is-on{ border-color:var(--rv-gold); background:rgba(245,197,66,.1); }
.rv-quiz__opt.is-on span::after{ transform:scale(1); }
.rv-quiz__back{ margin-top:20px; background:none; border:0; padding:6px 0; cursor:pointer;
  font-family:var(--font-label); font-size:.7rem; letter-spacing:.1em; text-transform:uppercase;
  color:#fff; transition:color .25s ease; }
.rv-quiz__back:hover{ color:var(--rv-gold); }
.rv-quiz__back[hidden]{ display:none; }
/* состояния карточки: расчёт -> результат -> бриф -> готово */
.rv-quiz__res, .rv-quiz__brief, .rv-quiz__final{ display:none; }
.rv-quiz.is-done .rv-quiz__form,
.rv-quiz.is-brief .rv-quiz__form,
.rv-quiz.is-final .rv-quiz__form{ display:none; }
.rv-quiz.is-done .rv-quiz__res,
.rv-quiz.is-brief .rv-quiz__brief,
.rv-quiz.is-final .rv-quiz__final{ display:block; animation:rvQuizIn .5s cubic-bezier(.22,1,.36,1) both; }
/* поля контактов на последнем шаге брифа */
.rv-quiz__fields{ display:flex; flex-direction:column; gap:12px; }
.rv-quiz__fields[hidden]{ display:none; }
.rv-quiz input.rv-quiz__in{ min-height:60px; padding:0 20px; border-radius:16px; border:1px solid rgba(255,255,255,.16);
  background:rgba(255,255,255,.05); color:#fff; font-family:inherit; font-size:1rem;
  -webkit-appearance:none; appearance:none; margin:0; box-sizing:border-box; }
.rv-quiz input.rv-quiz__in::placeholder{ color:rgba(255,255,255,.38); }
.rv-quiz input.rv-quiz__in:focus{ outline:none; border-color:rgba(245,197,66,.6); background:rgba(255,255,255,.08); }
.rv-quiz__err{ margin:2px 0 0; font-size:.88rem; color:#FFB4B4; min-height:1.2em; }
.rv-quiz__send{ align-self:flex-start; min-height:60px; padding:0 34px; border:0; border-radius:999px;
  cursor:pointer; font-family:inherit; font-weight:800; font-size:.98rem; letter-spacing:.06em;
  text-transform:uppercase; color:#030032;
  background:linear-gradient(135deg,#F5D488 0%,#F5C542 45%,#DFAE3F 100%);
  box-shadow:0 10px 30px rgba(245,197,66,.28); transition:transform .3s ease, box-shadow .3s ease; }
.rv-quiz__send:hover{ transform:translateY(-2px); box-shadow:0 14px 36px rgba(245,197,66,.42); }
.rv-quiz__sum{ margin:16px 0 0; font-size:.94rem; line-height:1.6; color:#fff; }
.rv-quiz__consent{ margin:2px 0 0; font-size:.8rem; line-height:1.5; color:#fff; }
.rv-quiz__send[disabled]{ opacity:.6; cursor:default; transform:none; }
/* статус отправки: «Надсилаємо…» */
.rv-quiz__status{ margin:10px 0 0; font-family:var(--font-label); font-size:.72rem; letter-spacing:.1em;
  text-transform:uppercase; color:var(--rv-gold); min-height:1.2em; }
/* галочка на экране благодарности */
.rv-quiz__ok{ width:56px; height:56px; border-radius:50%; display:grid; place-items:center;
  border:1px solid rgba(91,217,138,.5); background:rgba(91,217,138,.1); margin-bottom:18px; }
.rv-quiz__ok svg{ width:26px; height:26px; stroke:#5BD98A; stroke-width:2; }
.rv-quiz__thanks{ font-family:var(--rv-h); font-weight:800; letter-spacing:-.02em;
  font-size:clamp(1.3rem,2.2vw,1.9rem); line-height:1.25; color:#fff; }
/* форма внизу подсвечивается, когда мы подставили в неё данные */
.s11__form.rv-prefilled{ animation:rvFormPing 1.6s ease-out 2; border-radius:20px; }
@keyframes rvFormPing{ 0%{ box-shadow:0 0 0 0 rgba(245,197,66,.34); } 100%{ box-shadow:0 0 0 22px rgba(245,197,66,0); } }
@media(prefers-reduced-motion:reduce){ .s11__form.rv-prefilled{ animation:none; } }
@keyframes rvQuizIn{ from{ opacity:0; transform:translateY(14px); } to{ opacity:1; transform:none; } }
.rv-quiz__label{ font-family:var(--font-label); font-size:.66rem; letter-spacing:.16em; text-transform:uppercase;
  color:#fff; margin-bottom:10px; }
.rv-quiz__price{ font-family:var(--rv-h); font-weight:800; letter-spacing:-.03em; line-height:1;
  font-size:clamp(2.2rem,4.4vw,3.6rem); color:var(--rv-gold); }
.rv-quiz__meta{ display:flex; flex-wrap:wrap; gap:12px 32px; margin-top:22px; padding-top:22px;
  border-top:1px solid rgba(255,255,255,.12); }
.rv-quiz__meta div{ min-width:150px; }
.rv-quiz__meta b{ display:block; font-family:var(--font-label); font-weight:400; font-size:.62rem;
  letter-spacing:.16em; text-transform:uppercase; color:#fff; margin-bottom:6px; }
.rv-quiz__meta span{ font-size:1.02rem; color:#fff; }
.rv-quiz__inc{ display:flex; flex-wrap:wrap; gap:8px; margin-top:22px; }
.rv-quiz__inc i{ font-style:normal; font-family:var(--font-label); font-size:.66rem; letter-spacing:.08em;
  text-transform:uppercase; color:#fff; border:1px solid rgba(255,255,255,.16);
  border-radius:100px; padding:.45rem .9rem; }
.rv-quiz__note{ font-size:.86rem; line-height:1.55; color:#fff; margin:22px 0 0; }
.rv-quiz__act{ display:flex; flex-wrap:wrap; align-items:center; gap:16px; margin-top:26px; }
.rv-quiz__cta{ display:inline-flex; align-items:center; justify-content:center; gap:12px; min-height:64px;
  padding:0 40px; border-radius:999px; font-weight:800; font-size:1rem; letter-spacing:.06em;
  text-transform:uppercase; text-decoration:none; color:#030032;
  background:linear-gradient(135deg,#F5D488 0%,#F5C542 45%,#DFAE3F 100%);
  box-shadow:0 10px 30px rgba(245,197,66,.28); transition:transform .3s ease, box-shadow .3s ease; }
.rv-quiz__cta:hover{ transform:translateY(-2px); box-shadow:0 14px 36px rgba(245,197,66,.42); }
.rv-quiz__again{ background:none; border:0; cursor:pointer; padding:6px 0;
  font-family:var(--font-label); font-size:.7rem; letter-spacing:.1em; text-transform:uppercase;
  color:#fff; transition:color .25s ease; }
.rv-quiz__again:hover{ color:var(--rv-gold); }
@media(max-width:1024px){
  .rv-quiz{ padding:80px 0 90px; }
  .rv-quiz__wrap{ grid-template-columns:1fr; gap:34px; }
}
@media(max-width:700px){
  .rv-quiz{ padding:34px 0 38px; }
  .rv-quiz__wrap{ gap:22px; }
  .rv-quiz__title{ margin:14px 0 14px; }
  .rv-quiz__lead{ font-size:.94rem; line-height:1.5; }
  .rv-quiz__facts{ margin-top:16px; gap:7px; }
  .rv-quiz__facts li{ font-size:.86rem; line-height:1.45; }
  .rv-quiz__card{ padding:20px 18px; }
  .rv-quiz__bar{ margin-bottom:18px; }
  .rv-quiz__q{ margin-bottom:14px; }
  .rv-quiz__opts{ grid-template-columns:1fr; gap:10px; }
  .rv-quiz__opt{ min-height:56px; padding:12px 16px; }
  /* на телефоне вариантов 3 или 4 — держим общую высоту, чтобы карточка
     не подпрыгивала между вопросами прямо под пальцем */
  .rv-quiz__card{ min-height:396px; }
  .rv-quiz__cta{ width:auto; min-height:66px; font-size:.92rem; padding:0 30px; }
  .rv-quiz__act{ gap:14px; }
  .rv-quiz__again{ text-align:left; }
}
@media(prefers-reduced-motion:reduce){
  .rv-quiz.is-done .rv-quiz__res{ animation:none; }
  .rv-quiz__track i, .rv-quiz__opt, .rv-quiz__opt span::after{ transition:none; }
}

/* ======================================================================
   КАЛЬКУЛЯТОР v3 — «конфигуратор»: расчёт наживо в шапке карточки,
   сегментный прогресс, карточки-ответы с иконками и стрелкой,
   свечение карточки растёт с каждым шагом, итог докручивается.
   ====================================================================== */
.rv-quiz::after{ content:""; position:absolute; inset:0; pointer-events:none;
  background:linear-gradient(rgba(245,197,66,.03) 1px,transparent 1px) 0 0/80px 80px,linear-gradient(90deg,rgba(245,197,66,.03) 1px,transparent 1px) 0 0/80px 80px;
  -webkit-mask-image:radial-gradient(ellipse 60% 70% at 65% 50%,#000,transparent 80%); mask-image:radial-gradient(ellipse 60% 70% at 65% 50%,#000,transparent 80%); }
.rv-quiz__wrap > div:first-child{ position:relative; isolation:isolate; }
.rv-quiz__wrap > div:first-child::before{ content:"$"; position:absolute; z-index:-1; left:-2%; top:-6%; pointer-events:none;
  font-family:var(--rv-h); font-weight:800; font-size:clamp(220px,24vw,380px); line-height:1; color:transparent;
  -webkit-text-stroke:1px rgba(245,197,66,.13); transform:rotate(-8deg); animation:rvQzFloat 9s ease-in-out infinite alternate; }
@keyframes rvQzFloat{ to{ transform:rotate(-2deg) translateY(-14px); } }
/* факты — строки спецификации, как в аудите */
.rv-quiz .rv-quiz__facts{ counter-reset:rvqf; gap:0; margin-top:28px; }
.rv-quiz .rv-quiz__facts li{ counter-increment:rvqf; gap:16px; align-items:baseline; padding:11px 0; border-top:1px solid rgba(255,255,255,.08); }
.rv-quiz .rv-quiz__facts li:last-child{ border-bottom:1px solid rgba(255,255,255,.08); }
.rv-quiz .rv-quiz__facts li::before{ content:"0" counter(rvqf); flex:none; font-family:var(--font-label); font-size:.64rem; letter-spacing:.1em; color:var(--rv-gold); }

/* карточка: свечение усиливается от шага к шагу (--qp: .25 … 1) */
.rv-quiz .rv-quiz__card{ position:relative; overflow:hidden; isolation:isolate; border-radius:28px; border-color:rgba(255,255,255,.12);
  background:linear-gradient(160deg,rgba(22,24,74,.94),rgba(7,8,40,.97));
  box-shadow:0 40px 100px rgba(0,0,20,.45), inset 0 1px 0 rgba(255,255,255,.06); }
.rv-quiz .rv-quiz__card::before{ content:""; position:absolute; z-index:-1; right:-20%; top:-35%; width:70%; aspect-ratio:1; border-radius:50%; pointer-events:none;
  background:radial-gradient(circle,rgba(245,197,66,.22),transparent 65%); opacity:calc(.25 + var(--qp,.25) * .75); transition:opacity .8s ease; }
.rv-quiz .rv-quiz__card::after{ content:""; position:absolute; left:10%; right:10%; top:0; height:1px; pointer-events:none;
  background:linear-gradient(90deg,transparent,rgba(245,197,66,.7),transparent); }

/* расчёт наживо */
.rv-quiz__live{ display:flex; align-items:center; justify-content:space-between; gap:14px; flex-wrap:wrap;
  margin:calc(-1 * clamp(24px,2.4vw,40px)) calc(-1 * clamp(24px,2.4vw,40px)) 26px; padding:16px clamp(20px,2.2vw,32px);
  border-bottom:1px solid rgba(255,255,255,.08); background:rgba(255,255,255,.025); }
.rv-quiz__livel{ display:inline-flex; align-items:center; gap:9px; font-family:var(--font-label); font-size:.62rem; letter-spacing:.16em; text-transform:uppercase; color:rgba(255,255,255,.62); }
.rv-quiz__livel::before{ content:""; width:7px; height:7px; border-radius:50%; background:var(--rv-gold); box-shadow:0 0 10px var(--rv-gold); animation:rvQzBlink 1.8s ease-in-out infinite; }
.rv-quiz__livev{ font-family:var(--font-label); font-weight:500; font-size:.74rem; letter-spacing:.08em; text-transform:uppercase; color:rgba(255,255,255,.5); }
.rv-quiz__livev.is-num{ font-family:var(--rv-h); font-weight:800; font-size:clamp(1.1rem,1.6vw,1.45rem); letter-spacing:-.02em; text-transform:none; color:var(--rv-gold);
  text-shadow:0 0 24px rgba(245,197,66,.35); font-variant-numeric:tabular-nums; }
.rv-quiz__livev.is-bump{ animation:rvQzBump .5s cubic-bezier(.34,1.6,.64,1); }
.rv-quiz.is-done .rv-quiz__live,.rv-quiz.is-brief .rv-quiz__live,.rv-quiz.is-final .rv-quiz__live{ display:none; }
@keyframes rvQzBlink{ 50%{ opacity:.3; } }
@keyframes rvQzBump{ 0%{ transform:scale(1); } 40%{ transform:scale(1.08); } 100%{ transform:scale(1); } }

/* сегментный прогресс: 4 шага — 4 отрезка */
.rv-quiz .rv-quiz__track{ height:4px; background:rgba(255,255,255,.12); border-radius:0;
  -webkit-mask-image:repeating-linear-gradient(90deg,#000 0 calc(25% - 5px),transparent calc(25% - 5px) 25%);
          mask-image:repeating-linear-gradient(90deg,#000 0 calc(25% - 5px),transparent calc(25% - 5px) 25%); }
.rv-quiz .rv-quiz__track i{ border-radius:0; background:linear-gradient(90deg,#DFAE3F,#F5C542,#FCE7A2); box-shadow:0 0 16px rgba(245,197,66,.6); }

/* вопрос и ответы въезжают при каждом шаге */
.rv-quiz__q.is-anim{ animation:rvQzQ .55s cubic-bezier(.16,1,.3,1) both; }
@keyframes rvQzQ{ from{ opacity:0; transform:translateY(12px); } to{ opacity:1; transform:none; } }
.rv-quiz .rv-quiz__opt{ position:relative; overflow:hidden; isolation:isolate; min-height:70px; padding:14px 48px 14px 18px; border-radius:18px;
  background:linear-gradient(160deg,rgba(255,255,255,.06),rgba(255,255,255,.02)); border-color:rgba(255,255,255,.13);
  animation:rvQzOpt .55s cubic-bezier(.16,1,.3,1) both; animation-delay:calc(.06s + var(--i,0) * .06s);
  transition:border-color .3s ease, background .3s ease, transform .35s cubic-bezier(.16,1,.3,1), box-shadow .35s ease; }
@keyframes rvQzOpt{ from{ opacity:0; transform:translateY(16px) scale(.98); } to{ opacity:1; transform:none; } }
.rv-quiz .rv-quiz__opt strong{ font-weight:600; }
.rv-quiz .rv-quiz__opt::before{ content:""; position:absolute; inset:0; z-index:-1; opacity:0; transition:opacity .35s ease;
  background:radial-gradient(120% 140% at 0% 50%,rgba(245,197,66,.16),transparent 60%); }
.rv-quiz .rv-quiz__opt::after{ content:"\2192"; position:absolute; right:18px; top:50%; font-size:1.05rem; color:var(--rv-gold);
  opacity:0; transform:translate(-8px,-50%); transition:opacity .3s ease, transform .35s cubic-bezier(.16,1,.3,1); }
.rv-quiz .rv-quiz__opt:hover{ border-color:rgba(245,197,66,.55); transform:translateY(-2px); box-shadow:0 14px 34px rgba(0,0,0,.3), 0 0 0 1px rgba(245,197,66,.12); }
.rv-quiz .rv-quiz__opt:hover::before,.rv-quiz .rv-quiz__opt.is-on::before{ opacity:1; }
.rv-quiz .rv-quiz__opt:hover::after,.rv-quiz .rv-quiz__opt.is-on::after{ opacity:1; transform:translate(0,-50%); }
.rv-quiz .rv-quiz__opt:active{ transform:scale(.98); }
.rv-quiz__ico{ flex:none; display:grid; place-items:center; width:40px; height:40px; border-radius:12px;
  background:linear-gradient(145deg,rgba(245,197,66,.22),rgba(245,197,66,.06)); border:1px solid rgba(245,197,66,.35); }
.rv-quiz__ico svg{ width:20px; height:20px; fill:none; stroke:var(--rv-gold); stroke-width:1.7; stroke-linecap:round; stroke-linejoin:round; }
.rv-quiz .rv-quiz__opt:hover .rv-quiz__ico{ background:var(--rv-gold); }
.rv-quiz .rv-quiz__opt:hover .rv-quiz__ico svg{ stroke:#030032; }

/* итог: цифра докручивается, «что входить» появляется по одному */
.rv-quiz .rv-quiz__price{ font-variant-numeric:tabular-nums; text-shadow:0 0 40px rgba(245,197,66,.35); }
.rv-quiz.is-done .rv-quiz__inc i{ animation:rvQzOpt .5s cubic-bezier(.16,1,.3,1) both; }
.rv-quiz.is-done .rv-quiz__inc i:nth-child(1){ animation-delay:.5s; } .rv-quiz.is-done .rv-quiz__inc i:nth-child(2){ animation-delay:.57s; }
.rv-quiz.is-done .rv-quiz__inc i:nth-child(3){ animation-delay:.64s; } .rv-quiz.is-done .rv-quiz__inc i:nth-child(4){ animation-delay:.71s; }
.rv-quiz.is-done .rv-quiz__inc i:nth-child(5){ animation-delay:.78s; }
.rv-quiz .rv-quiz__inc i{ border-color:rgba(245,197,66,.3); background:rgba(245,197,66,.06); }

@media(max-width:700px){
  .rv-quiz__live{ margin:-20px -18px 18px; padding:12px 16px; }
  .rv-quiz__wrap > div:first-child::before{ font-size:200px; left:auto; right:-4%; top:-10%; }
  .rv-quiz .rv-quiz__opt{ min-height:60px; padding:12px 44px 12px 14px; }
  .rv-quiz__ico{ width:36px; height:36px; }
  /* на телефоне касание вместо ховера: стрелка видна сразу, но приглушённо */
  .rv-quiz .rv-quiz__opt::after{ opacity:.45; transform:translate(0,-50%); }
}
@media(prefers-reduced-motion:reduce){
  .rv-quiz .rv-quiz__opt,.rv-quiz__q.is-anim,.rv-quiz__livev.is-bump,.rv-quiz.is-done .rv-quiz__inc i,
  .rv-quiz__wrap > div:first-child::before,.rv-quiz__livel::before{ animation:none!important; }
}
</style>
<?php $rv_qz_en = (function_exists('pll_current_language') && pll_current_language() == 'en'); ?>
<div class="rv-quiz__wrap">
	<div>
		<div class="rv-quiz__chip"><i></i><?php echo $rv_qz_en ? 'Estimate' : 'Калькулятор'; ?></div>
		<h2 class="rv-quiz__title"><?php echo $rv_qz_en ? 'Estimate your <em>budget</em>' : 'Порахуйте <em>бюджет</em>'; ?></h2>
		<p class="rv-quiz__lead"><?php echo $rv_qz_en
			? 'Four questions — and you see the price range and the timeline right on this page.'
			: 'Чотири питання — і ви бачите вилку цін та строк прямо тут, на сторінці.'; ?></p>
		<ul class="rv-quiz__facts">
			<li><?php echo $rv_qz_en ? 'No sign-up and no “leave your number, we’ll call back”' : 'Без реєстрації та без «залиште номер, ми передзвонимо»'; ?></li>
			<li><?php echo $rv_qz_en ? 'The range is based on our real projects of the last two years' : 'Вилка порахована на наших реальних проєктах за два роки'; ?></li>
			<li><?php echo $rv_qz_en ? 'The exact figure is fixed after the brief, in the contract' : 'Точна цифра фіксується після брифу, у договорі'; ?></li>
		</ul>
	</div>

	<div class="rv-quiz__card">
		<div class="rv-quiz__live" aria-live="polite">
			<span class="rv-quiz__livel"><?php echo $rv_qz_en ? 'Your estimate' : 'Ваш розрахунок'; ?></span>
			<b class="rv-quiz__livev" id="rv-quiz-live"><?php echo $rv_qz_en ? 'Pick a project type' : 'Оберіть тип проєкту'; ?></b>
		</div>
		<div class="rv-quiz__form" id="rv-quiz-form">
			<div class="rv-quiz__bar">
				<div class="rv-quiz__track"><i id="rv-quiz-prog"></i></div>
				<div class="rv-quiz__step"><b id="rv-quiz-cur">1</b> / 4</div>
			</div>
			<h3 class="rv-quiz__q" id="rv-quiz-q"></h3>
			<div class="rv-quiz__opts" id="rv-quiz-opts" role="group"></div>
			<button type="button" class="rv-quiz__back" id="rv-quiz-back" hidden><?php echo $rv_qz_en ? '&larr; Back' : '&larr; Назад'; ?></button>
		</div>

		<div class="rv-quiz__res" id="rv-quiz-res">
			<div class="rv-quiz__label" id="rv-quiz-reslabel"></div>
			<div class="rv-quiz__price" id="rv-quiz-price"></div>
			<div class="rv-quiz__meta">
				<div><b><?php echo $rv_qz_en ? 'Timeline' : 'Термін'; ?></b><span id="rv-quiz-time"></span></div>
				<div><b><?php echo $rv_qz_en ? 'Your choice' : 'Ваш вибір'; ?></b><span id="rv-quiz-sum"></span></div>
			</div>
			<div class="rv-quiz__inc" id="rv-quiz-inc"></div>
			<p class="rv-quiz__note"><?php echo $rv_qz_en
				? 'This is a range, not an invoice: the final number depends on details we clarify in the brief. It never moves once the contract is signed.'
				: 'Це вилка, а не рахунок: підсумкова цифра залежить від деталей, які уточнюємо на брифі. Після підписання договору вона вже не змінюється.'; ?></p>
			<div class="rv-quiz__act">
				<button type="button" class="rv-quiz__cta" id="rv-quiz-tobrief"><?php echo $rv_qz_en ? 'Send a request' : 'Залишити заявку'; ?> <span>↗︎</span></button>
				<button type="button" class="rv-quiz__again" id="rv-quiz-again"><?php echo $rv_qz_en ? 'Recalculate' : 'Перерахувати'; ?></button>
			</div>
		</div>

		<!-- бриф: три вопроса поверх расчёта -->
		<div class="rv-quiz__brief" id="rv-quiz-brief">
			<div class="rv-quiz__bar">
				<div class="rv-quiz__track"><i id="rv-brief-prog"></i></div>
				<div class="rv-quiz__step"><?php echo $rv_qz_en ? 'Brief' : 'Бриф'; ?> <b id="rv-brief-cur">1</b> / 3</div>
			</div>
			<h3 class="rv-quiz__q" id="rv-brief-q"></h3>
			<div class="rv-quiz__opts" id="rv-brief-opts" role="group"></div>
			<div class="rv-quiz__fields" id="rv-brief-fields" hidden>
				<input class="rv-quiz__in" id="rv-brief-name" type="text" autocomplete="name"
				       placeholder="<?php echo $rv_qz_en ? 'Your name' : 'Ваше ім’я'; ?>"
				       aria-label="<?php echo $rv_qz_en ? 'Your name' : 'Ваше ім’я'; ?>">
				<input class="rv-quiz__in" id="rv-brief-phone" type="tel" autocomplete="tel"
				       placeholder="<?php echo $rv_qz_en ? 'Phone' : 'Телефон'; ?>"
				       aria-label="<?php echo $rv_qz_en ? 'Phone' : 'Телефон'; ?>">
				<input class="rv-quiz__in" id="rv-brief-email" type="email" autocomplete="email"
				       placeholder="<?php echo $rv_qz_en ? 'Email (optional)' : 'Email (необов’язково)'; ?>"
				       aria-label="Email">
				<p class="rv-quiz__err" id="rv-brief-err"></p>
				<button type="button" class="rv-quiz__send" id="rv-brief-send"><?php echo $rv_qz_en ? 'Send the brief' : 'Надіслати бриф'; ?></button>
				<p class="rv-quiz__status" id="rv-brief-status"></p>
				<p class="rv-quiz__consent"><?php echo $rv_qz_en
					? 'By pressing the button you agree to the processing of your data.'
					: 'Натискаючи кнопку, ви погоджуєтесь на обробку ваших даних.'; ?></p>
			</div>
			<button type="button" class="rv-quiz__back" id="rv-brief-back">&larr; <?php echo $rv_qz_en ? 'Back' : 'Назад'; ?></button>
		</div>

		<!-- заявка ушла: подтверждение приходит от самой CF7 -->
		<div class="rv-quiz__final" id="rv-quiz-final">
			<div class="rv-quiz__ok" aria-hidden="true">
				<svg viewBox="0 0 24 24" fill="none" stroke-linecap="round" stroke-linejoin="round"><path d="M4 12.5l5.2 5.2L20 7"/></svg>
			</div>
			<div class="rv-quiz__thanks" id="rv-final-thanks"></div>
			<p class="rv-quiz__sum" id="rv-final-sum"></p>
			<p class="rv-quiz__note"><?php echo $rv_qz_en
				? 'We reply the same day — by phone or email, whichever you prefer.'
				: 'Відповідаємо того ж дня — телефоном або на email, як вам зручніше.'; ?></p>
			<div class="rv-quiz__act">
				<button type="button" class="rv-quiz__again" id="rv-final-again"><?php echo $rv_qz_en ? 'Start over' : 'Почати спочатку'; ?></button>
			</div>
		</div>
	</div>
</div>
<script>
(function(){
  'use strict';
  var root = document.getElementById('rv-quiz');
  if (!root) return;
  var EN = <?php echo $rv_qz_en ? 'true' : 'false'; ?>;

  /* Модель считает вилку от стартовых цен калькулятора (−30% к прежним
     $1500 / $3000 / $2500 / $500): сайт от $1050, магазин от $2100,
     застосунок от $1750, просування от $350/міс.
     Дальше — множители за объём, за дизайн и за срочность. */
  var T = EN ? {
    q1:'What do you need?', q2:'How big is it?', q2p:'How many channels?',
    q3:'Design', q3p:'What do you already have?', q4:'When do you need it?',
    types:[['site','Website',1050],['shop','Online store',2100],['app','Mobile app',1750],['promo','Ads & SEO',350]],
    scale:{ build:[['Landing, 1–3 screens',1],['Up to 10 pages',1.5],['10–50 pages',2.2],['50+ or a big catalogue',3.2]],
            promo:[['One channel',1],['Two channels',1.8],['SEO + ads + social',2.6]] },
    design:{ build:[['Template tuned to your brand',.9],['Custom design',1.15],['Custom design + motion',1.4]],
             promo:[['No website yet',1.3],['Website, needs traffic',1],['Website and analytics in place',.9]] },
    time:[['Flexible',1],['Within 1–2 months',1.1],['Urgent',1.3]],
    resB:'Estimated budget', resP:'Estimated budget per month',
    bq:['What do you already have?','When do we start?','Where should we reply?'],
    b1:['No website yet','A website that needs replacing','A website that needs improvements'],
    b2:['Ready to start now','Within a month','Still exploring the market'],
    bErrName:'Please tell us your name', bErrCt:'Enter a phone number',
    bErrMail:'This email looks incorrect',
    bSending:'Sending…', bSent:'Thank you! Your brief has been sent.',
    bFail:'We couldn\u2019t send your request. Please try again.',
    bDone:'Your brief is ready',
    weeks:'weeks', months:'first results in 1–3 months',
    inc:{ site:['Prototype','Custom design','Adaptive layout','CMS','Analytics'],
          shop:['Catalogue and cart','Payments and delivery','Admin panel','Adaptive layout','Analytics'],
          app:['UX research','iOS and Android','Backend and API','Store publishing'],
          promo:['Audit','Semantics and strategy','Campaign setup','Monthly reporting'] }
  } : {
    q1:'Що потрібно?', q2:'Який масштаб?', q2p:'Скільки напрямків?',
    q3:'Дизайн', q3p:'Що вже є?', q4:'Коли потрібно?',
    types:[['site','Сайт',1050],['shop','Інтернет-магазин',2100],['app','Мобільний застосунок',1750],['promo','Реклама і просування',350]],
    scale:{ build:[['Лендинг, 1–3 екрани',1],['До 10 сторінок',1.5],['10–50 сторінок',2.2],['50+ або великий каталог',3.2]],
            promo:[['Один канал',1],['Два канали',1.8],['SEO + реклама + соцмережі',2.6]] },
    design:{ build:[['Шаблон під ваш бренд',.9],['Індивідуальний дизайн',1.15],['Індивідуальний + анімації',1.4]],
             promo:[['Сайту ще немає',1.3],['Сайт є, потрібен трафік',1],['Є сайт і аналітика',.9]] },
    time:[['Гнучко',1],['За 1–2 місяці',1.1],['Терміново',1.3]],
    resB:'Орієнтовний бюджет', resP:'Орієнтовний бюджет на місяць',
    bq:['Що вже є?','Коли починаємо?','Куди відповісти?'],
    b1:['Сайту ще немає','Є сайт, потрібен новий','Є сайт, потрібні доробки'],
    b2:['Готові починати зараз','Протягом місяця','Поки вивчаю ринок'],
    bErrName:'Напишіть, будь ласка, ім’я', bErrCt:'Вкажіть номер телефону',
    bErrMail:'Схоже, в email помилка',
    bSending:'Надсилаємо…', bSent:'Дякуємо! Бриф надіслано.',
    bFail:'Не вдалося надіслати заявку. Спробуйте ще раз.',
    bDone:'Бриф готовий',
    weeks:'тижнів', months:'перші результати за 1–3 місяці',
    inc:{ site:['Прототип','Індивідуальний дизайн','Адаптив','CMS','Аналітика'],
          shop:['Каталог і кошик','Оплата й доставка','Адмінка','Адаптив','Аналітика'],
          app:['UX-дослідження','iOS та Android','Бекенд і API','Публікація в сторах'],
          promo:['Аудит','Семантика і стратегія','Налаштування кампаній','Щомісячний звіт'] }
  };

  var WEEKS = { site:[2,4,7,11], shop:[6,8,11,15], app:[8,12,18,24] };
  var step = 0, answers = [null,null,null,null];
  var priceText = '', timeText = '';
  var elQ = document.getElementById('rv-quiz-q'), elOpts = document.getElementById('rv-quiz-opts'),
      elProg = document.getElementById('rv-quiz-prog'), elCur = document.getElementById('rv-quiz-cur'),
      elBack = document.getElementById('rv-quiz-back');

  function isPromo(){ return answers[0] && answers[0].key === 'promo'; }
  function questions(){
    var promo = isPromo();
    return [
      { q:T.q1, opts:T.types.map(function(t){ return { label:t[1], k:1, key:t[0], base:t[2] }; }) },
      { q:promo ? T.q2p : T.q2, opts:(promo ? T.scale.promo : T.scale.build).map(function(o){ return { label:o[0], k:o[1] }; }) },
      { q:promo ? T.q3p : T.q3, opts:(promo ? T.design.promo : T.design.build).map(function(o){ return { label:o[0], k:o[1] }; }) },
      { q:T.q4, opts:T.time.map(function(o){ return { label:o[0], k:o[1] }; }) }
    ];
  }
  function render(){
    var Q = questions()[step];
    elQ.textContent = Q.q;
    elCur.textContent = step + 1;
    elProg.style.width = ((step + 1) / 4 * 100) + '%';
    elBack.hidden = step === 0;
    elOpts.innerHTML = '';
    root.style.setProperty('--qp', ((step + 1) / 4).toFixed(2));
    elQ.classList.remove('is-anim'); void elQ.offsetWidth; elQ.classList.add('is-anim');
    Q.opts.forEach(function(o, i){
      var b = document.createElement('button');
      b.type = 'button';
      b.className = 'rv-quiz__opt' + (answers[step] && answers[step].label === o.label ? ' is-on' : '');
      b.style.setProperty('--i', i);
      b.innerHTML = (o.key && ICONS[o.key] ? '<em class="rv-quiz__ico" aria-hidden="true">' + ICONS[o.key] + '</em>' : '<span aria-hidden="true"></span>') + '<strong>' + o.label + '</strong>';
      b.addEventListener('click', function(){
        answers[step] = o;
        /* смена типа проекта меняет сами вопросы — сбрасываем то, что ниже */
        if (step === 0) { answers[1] = answers[2] = null; }
        if (step < 3) { step++; render(); } else { finish(); }
      });
      elOpts.appendChild(b);
    });
    live();
  }
  elBack.addEventListener('click', function(){ if (step > 0) { step--; render(); } });

  /* иконки типов проекта на первом шаге */
  var ICONS = {
    site:'<svg viewBox="0 0 24 24"><rect x="3" y="4" width="18" height="16" rx="2.5"/><path d="M3 9h18M7 6.5h.01M10 6.5h.01"/></svg>',
    shop:'<svg viewBox="0 0 24 24"><path d="M5 8h14l-1.2 11.2a2 2 0 0 1-2 1.8H8.2a2 2 0 0 1-2-1.8z"/><path d="M9 10V7a3 3 0 0 1 6 0v3"/></svg>',
    app:'<svg viewBox="0 0 24 24"><rect x="6.5" y="2.5" width="11" height="19" rx="2.5"/><path d="M11 18.5h2"/></svg>',
    promo:'<svg viewBox="0 0 24 24"><path d="M4 17l5-5 4 3 7-8"/><path d="M15 7h5v5"/></svg>'
  };

  /* Расчёт наживо: та же формула, что в finish(), по уже выбранным ответам.
     Цифра плавно «докручивается» к новому значению после каждого клика. */
  var elLive = document.getElementById('rv-quiz-live'), liveLo = 0, liveHi = 0, liveRAF = 0;
  var reducedQ = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
  /* Цена на шагах не показывается — только в итоге (finish). В шапке карточки
     подсказка: сначала «оберіть тип», дальше — сколько вопросов осталось. */
  function live(){
    if (!elLive) return;
    elLive.classList.remove('is-num', 'is-bump');
    if (!answers[0]) { elLive.textContent = EN ? 'Pick a project type' : 'Оберіть тип проєкту'; return; }
    var left = 4 - step;
    elLive.textContent = EN
      ? (left > 1 ? left + ' questions left — then the estimate' : 'Last question — then the estimate')
      : (left > 1 ? 'Ще ' + left + ' питання — і побачите вилку' : 'Останнє питання — і побачите вилку');
  }
  function tween(a0, b0, a1, b1, out, dur){
    cancelAnimationFrame(liveRAF);
    if (reducedQ) { out(a1, b1); return; }
    var t0 = 0; dur = dur || 700;
    liveRAF = requestAnimationFrame(function f(ts){
      if (!t0) t0 = ts;
      var p = Math.min(1, (ts - t0) / dur), e = 1 - Math.pow(1 - p, 3);
      out(round(a0 + (a1 - a0) * e), round(b0 + (b1 - b0) * e));
      if (p < 1) liveRAF = requestAnimationFrame(f);
    });
  }

  function round(n){ return Math.round(n / 100) * 100; }
  function money(n){ return '$' + String(n).replace(/\B(?=(\d{3})+(?!\d))/g, ' '); }

  /* Калибровка вилки. Множители за объём и дизайн давали завышенный верх,
     поэтому итог опускаем на 25%. Одна цифра — чтобы поправить вилку по всему
     калькулятору можно было в одном месте. */
  var CALIBR = .75;
  /* Магазины и приложения по нашей вилке выходили дороже рынка — им отдельный
     минус 20%. Стартовая цена услуги при этом не трогается: в блоке услуг и в
     FAQ заявлено «від $2000» и «від $2500», и нижняя граница держится по ним. */
  var TYPEK = { shop:.64, app:.64 };   /* -20%, а затем ещё -20% */
  /* Та же калибровка для сроков: -20%. Ниже двух недель не опускаемся —
     столько занимает даже самый простой проект с согласованиями. */
  var TCALIBR = .8;

  function finish(){
    var base = answers[0].base, key = answers[0].key;
    var low  = round(base * answers[1].k * answers[2].k * answers[3].k * CALIBR * (TYPEK[key] || 1));
    /* ниже стартовой цены услуги не опускаемся: на сайте и в FAQ заявлено
       «сайти від $1500», калькулятор не должен этому противоречить */
    if (low < base) low = base;
    var high = round(low * 1.45);
    document.getElementById('rv-quiz-reslabel').textContent = key === 'promo' ? T.resP : T.resB;
    var elPrice = document.getElementById('rv-quiz-price');
    elPrice.textContent = money(low) + ' – ' + money(high);
    if (!reducedQ) { var pr = 0; var t1 = 0; (function f(ts){ if (!t1) t1 = ts; var p = Math.min(1, (ts - t1) / 1100), e = 1 - Math.pow(1 - p, 4);
      elPrice.textContent = money(round(low * e)) + ' – ' + money(round(high * e)); if (p < 1) pr = requestAnimationFrame(f); })(performance.now()); }

    var time;
    if (key === 'promo') { time = T.months; }
    else {
      var idx = [1, 1.5, 2.2, 3.2].indexOf(answers[1].k); if (idx < 0) idx = 1;
      var w = Math.max(2, Math.round((WEEKS[key][idx] || 6) * TCALIBR));
      if (answers[3].k > 1.2) w = Math.max(2, Math.round(w * .8));
      time = w + '–' + (w + 2) + ' ' + T.weeks;
    }
    document.getElementById('rv-quiz-time').textContent = time;
    document.getElementById('rv-quiz-sum').textContent = answers[0].label + ' · ' + answers[1].label;

    var inc = document.getElementById('rv-quiz-inc');
    inc.innerHTML = '';
    (T.inc[key] || []).forEach(function(t){ var i = document.createElement('i'); i.textContent = t; inc.appendChild(i); });

    root.classList.add('is-done');

    /* Заявка должна приходить с расчётом: если в форме есть поле для сообщения,
       кладём в него итог расчёта. Формы может не быть — тогда просто молчим. */
    priceText = money(low) + ' – ' + money(high) + (key === 'promo' ? (EN ? ' / month' : ' / міс') : '');
    timeText  = time;
  }
  function reset(){
    step = 0; answers = [null,null,null,null];
    bStep = 0; bAns = [null,null];
    sending = false; if (bSend){ bSend.disabled = false; }
    if (bStatus) bStatus.textContent = ''; if (bErr) bErr.textContent = '';
    root.classList.remove('is-done','is-brief','is-final');
    render();
  }
  document.getElementById('rv-quiz-again').addEventListener('click', reset);
  document.getElementById('rv-final-again').addEventListener('click', reset);

  /* ================= БРИФ: три вопроса поверх расчёта =================
     Дальше идти некуда — форма уже внизу страницы. Поэтому бриф не шлёт
     ничего сам, а подставляет ответы в форму сайта: человек видит, что
     именно уйдёт, и жмёт «Надіслати». Никаких скрытых отправок. */
  var bStep = 0, bAns = [null,null], sending = false;
  var bQ = document.getElementById('rv-brief-q'), bOpts = document.getElementById('rv-brief-opts'),
      bFields = document.getElementById('rv-brief-fields'), bProg = document.getElementById('rv-brief-prog'),
      bCur = document.getElementById('rv-brief-cur'), bBack = document.getElementById('rv-brief-back'),
      bName = document.getElementById('rv-brief-name'), bPhone = document.getElementById('rv-brief-phone'),
      bMail = document.getElementById('rv-brief-email'), bErr = document.getElementById('rv-brief-err'),
      bStatus = document.getElementById('rv-brief-status'), bSend = document.getElementById('rv-brief-send');
  var LANG = <?php echo $rv_qz_en ? "'EN'" : "'UA'"; ?>;

  function bRender(){
    bQ.textContent = T.bq[bStep];
    bCur.textContent = bStep + 1;
    bProg.style.width = ((bStep + 1) / 3 * 100) + '%';
    bFields.hidden = bStep !== 2;
    bOpts.innerHTML = '';
    if (bStep === 2) return;
    (bStep === 0 ? T.b1 : T.b2).forEach(function(label){
      var b = document.createElement('button');
      b.type = 'button';
      b.className = 'rv-quiz__opt' + (bAns[bStep] === label ? ' is-on' : '');
      b.innerHTML = '<span aria-hidden="true"></span>' + label;
      b.addEventListener('click', function(){ bAns[bStep] = label; bStep++; bRender(); });
      bOpts.appendChild(b);
    });
  }
  document.getElementById('rv-quiz-tobrief').addEventListener('click', function(){
    bStep = 0; bAns = [null,null];
    root.classList.remove('is-done'); root.classList.add('is-brief');
    bRender();
  });
  bBack.addEventListener('click', function(){
    if (bStep === 0){ root.classList.remove('is-brief'); root.classList.add('is-done'); return; }
    bStep--; bRender();
  });

  /* Полный текст брифа: он же уходит в письмо отдельным блоком (rv_brief),
     потому что в самой форме сайта всего два поля. */
  function fullSummary(){
    var L = [];
    L.push((EN ? 'Service' : 'Послуга') + ': ' + answers[0].label);
    L.push((EN ? 'Scope' : 'Масштаб') + ': ' + answers[1].label);
    L.push((EN ? 'Design' : 'Дизайн') + ': ' + answers[2].label);
    L.push((EN ? 'Timing' : 'Терміни') + ': ' + answers[3].label);
    L.push((EN ? 'Estimated budget' : 'Орієнтовний бюджет') + ': ' + priceText);
    L.push((EN ? 'Estimated timeline' : 'Орієнтовний термін') + ': ' + timeText);
    L.push(T.bq[0] + ' ' + bAns[0]);
    L.push(T.bq[1] + ' ' + bAns[1]);
    L.push((EN ? 'Name' : 'Ім’я') + ': ' + bName.value.trim());
    L.push((EN ? 'Phone' : 'Телефон') + ': ' + bPhone.value.trim());
    if (bMail.value.trim()) L.push('Email: ' + bMail.value.trim());
    L.push((EN ? 'Page language' : 'Мова сторінки') + ': ' + LANG);
    return L.join('\n');
  }

  /* Форма сайта одна и уже на нужном языке — её и отправляем.
     Так работают и почтовый шаблон CF7, и антиспам, и reCAPTCHA:
     свой канал отправки для этого поднимать не нужно. */
  function siteForm(){ return document.querySelector('.s11__form form.wpcf7-form') ||
                              document.querySelector('.s11 form.wpcf7-form'); }

  /* Поля у CF7 называются как угодно, поэтому ищем по типу, затем по имени. */
  function pick(form, type, re){
    var el = form.querySelector('input[type="' + type + '"]');
    if (el) return el;
    var all = form.querySelectorAll('input[type="text"], input:not([type])');
    for (var i = 0; i < all.length; i++){
      if (re.test(all[i].name || '') || re.test(all[i].placeholder || '')) return all[i];
    }
    return null;
  }
  function setVal(el, v){
    if (!el || !v) return;
    el.value = v;
    el.dispatchEvent(new Event('input', { bubbles:true }));
    el.dispatchEvent(new Event('change', { bubbles:true }));
  }
  function hidden(form, name, value){
    var el = form.querySelector('input[name="' + name + '"][data-rv="1"]');
    if (!el){
      el = document.createElement('input');
      el.type = 'hidden'; el.name = name; el.setAttribute('data-rv','1');
      form.appendChild(el);
    }
    el.value = value;
  }

  function briefDone(){
    document.getElementById('rv-final-thanks').textContent = T.bSent;
    document.getElementById('rv-final-sum').textContent =
      answers.map(function(a){ return a.label; }).join(' · ') + ' · ' + priceText + ' · ' + timeText;
    root.classList.remove('is-brief'); root.classList.add('is-final');
  }
  function stopSending(msg){
    sending = false; bSend.disabled = false; bStatus.textContent = '';
    if (msg) bErr.textContent = msg;              /* введённые данные остаются на месте */
  }

  bSend.addEventListener('click', function(){
    if (sending) return;                           /* защита от второго нажатия */
    var name = bName.value.trim(), phone = bPhone.value.trim(), mail = bMail.value.trim();
    if (name.length < 2){ bErr.textContent = T.bErrName; bName.focus(); return; }
    if (phone.replace(/\D/g,'').length < 7){ bErr.textContent = T.bErrCt; bPhone.focus(); return; }
    if (mail && !/^[^\s@]+@[^\s@]+\.[^\s@]{2,}$/.test(mail)){ bErr.textContent = T.bErrMail; bMail.focus(); return; }
    bErr.textContent = '';

    var form = siteForm();
    /* формы нет (например, плагин отключён) — про отправку не врём:
       показываем ошибку и ведём человека в блок с контактами */
    if (!form){
      bErr.textContent = T.bFail;
      var box = document.querySelector('.s11');
      if (box) box.scrollIntoView({ behavior:'smooth', block:'start' });
      return;
    }

    sending = true; bSend.disabled = true; bStatus.textContent = T.bSending;

    setVal(pick(form, 'text', /name|ім|имя/i), name);
    setVal(pick(form, 'tel',  /phone|tel|телефон/i), phone);
    if (mail) setVal(pick(form, 'email', /mail|пошт/i), mail);
    var ta = form.querySelector('textarea');
    if (ta && !ta.value.trim()) setVal(ta, fullSummary());

    /* согласие в форме отмечаем сами: кнопка брифа подписана строкой о согласии */
    var acc = form.querySelectorAll('.wpcf7-acceptance input[type="checkbox"]');
    for (var a = 0; a < acc.length; a++){ if (!acc[a].checked){ acc[a].checked = true;
      acc[a].dispatchEvent(new Event('change', { bubbles:true })); } }

    hidden(form, 'rv_brief', fullSummary());
    hidden(form, 'rv_lang', LANG);

    var btn = form.querySelector('input[type="submit"], button[type="submit"], .wpcf7-submit');
    if (!btn){ stopSending(T.bFail); return; }

    /* ответ CF7 приходит событием — только после него показываем «дякуємо» */
    var guard = setTimeout(function(){ off(); stopSending(T.bFail); }, 25000);
    function off(){
      clearTimeout(guard);
      /* служебные поля живут только на время этой отправки: если человек
         потом отправит обычную форму руками, бриф в письмо не подмешается */
      var tmp = form.querySelectorAll('input[data-rv="1"]');
      for (var t = 0; t < tmp.length; t++){ tmp[t].parentNode.removeChild(tmp[t]); }
      form.removeEventListener('wpcf7mailsent', onOk);
      form.removeEventListener('wpcf7invalid', onBad);
      form.removeEventListener('wpcf7spam', onBad);
      form.removeEventListener('wpcf7mailfailed', onBad);
      form.removeEventListener('wpcf7submit', onAny);
    }
    function onOk(){ off(); sending = false; bSend.disabled = false; bStatus.textContent = ''; briefDone(); }
    function onBad(){ off(); stopSending(T.bFail); }
    /* на случай нестандартной сборки CF7: если ни одно из событий выше не
       сработало, ловим общее и смотрим статус в нём */
    function onAny(e){
      var st = e && e.detail && e.detail.status;
      if (st === 'mail_sent'){ onOk(); }
      else if (st){ onBad(); }
    }
    form.addEventListener('wpcf7mailsent', onOk);
    form.addEventListener('wpcf7invalid', onBad);
    form.addEventListener('wpcf7spam', onBad);
    form.addEventListener('wpcf7mailfailed', onBad);
    form.addEventListener('wpcf7submit', onAny);

    btn.click();
  });

  render();
})();
</script>
</section>
<!-- ===== /RV QUIZ ===== -->

<?php /* ============================================================
   БЛОК ОТЗЫВОВ ВЫКЛЮЧЕН.
   Код целиком сохранён ниже и ничего не выводит: условие if(false)
   пропускает и HTML, и стили, и скрипт — они даже не попадают в страницу.
   Чтобы вернуть блок на сайт, замените false на true в строке ниже.
   ============================================================ */ ?>
<?php if ( false ) : ?>
<!-- ===== RV REVIEWS v2: тёмное стекло, автопрогресс, свайп ===== -->
<section class="rv-reviews" id="rv-reviews">
<style>
.rv-reviews{ --font-label:'JetBrains Mono',monospace; --rv-accent-solid:#E8B93A; position:relative; background:#030032; padding:110px 0 120px; overflow:hidden; }
.rv-reviews::before{ content:""; position:absolute; inset:0; pointer-events:none;
  background:radial-gradient(ellipse 40% 55% at 18% 20%, rgba(245, 197, 66,.09) 0%, transparent 70%); }
/* гигантская декоративная кавычка за карточкой */
.rv-reviews::after{ content:"\201C"; position:absolute; right:4%; top:-40px;
  font-family:var(--font-main); font-weight:800; font-size:clamp(220px,30vw,420px);
  line-height:1; color:rgba(245, 197, 66,.07); pointer-events:none; }
.rv-reviews__wrap{ position:relative; max-width:1200px; margin:0 auto; padding:0 15px;
  display:grid; grid-template-columns:minmax(280px,1fr) minmax(0,1.6fr); gap:60px; align-items:center; }
.rv-reviews__chip{ display:inline-flex; align-items:center; gap:.7rem; background:rgba(255,255,255,.05);
  border:1px solid rgba(255,255,255,.12); border-radius:100px; padding:.5rem 1.1rem;
  font-family:var(--font-label); font-size:.66rem; font-weight:400; letter-spacing:.1em;
  text-transform:uppercase; color:#fff; }
.rv-reviews__chip i{ font-style:normal; width:7px; height:7px; border-radius:50%; background:#F5C542; box-shadow:0 0 12px #F5C542; }
.rv-reviews__title{ font-family:'Manrope','Montserrat',Arial,sans-serif; font-weight:800; letter-spacing:-.03em;
  font-size:clamp(34px,4vw,58px); line-height:1.05; color:#fff; text-transform:uppercase; margin:22px 0 34px; }
.rv-reviews__title em{ font-style:normal; color:transparent; -webkit-text-stroke:1.5px #F5C542; }
.rv-reviews__nav{ display:flex; align-items:center; gap:1rem; }
.rv-reviews__arrow{ width:44px; height:44px; border-radius:50%; background:rgba(255,255,255,.05);
  border:1px solid rgba(255,255,255,.15); color:#fff; font-size:1rem; cursor:pointer; transition:.3s;
  -webkit-appearance:none; appearance:none; padding:0; line-height:1; }
.rv-reviews__arrow:hover{ background:#F5C542; border-color:#F5C542; color:#030032; }
.rv-reviews__count{ font-family:var(--font-label); font-size:.72rem; letter-spacing:.08em; color:#fff; }
.rv-reviews__count b{ color:#F5C542; font-weight:700; }
/* сцена: карточки одна поверх другой */
.rv-reviews__stage{ display:grid; }
.rv-review{ grid-area:1/1; background:rgba(255,255,255,.05); border:1px solid rgba(255,255,255,.12);
  border-radius:28px; padding:clamp(28px,4vw,52px); backdrop-filter:blur(14px);
  box-shadow:0 30px 70px rgba(0,0,0,.4);
  opacity:0; transform:translateY(24px); pointer-events:none; transition:opacity .55s ease, transform .55s ease; }
.rv-review.is-active{ opacity:1; transform:none; pointer-events:auto; }
.rv-review__stars{ color:#F5C542; letter-spacing:.25em; font-size:.95rem; margin-bottom:20px; }
.rv-review__text{ color:#fff; font-size:clamp(17px,1.6vw,22px); line-height:1.65; font-weight:500; margin:0 0 28px; }
.rv-review__who{ display:flex; align-items:center; gap:14px; }
.rv-review__ava{ font-family:'Manrope',Arial,sans-serif; width:52px; height:52px; border-radius:50%; flex:none; display:flex; align-items:center; justify-content:center;
  background:linear-gradient(145deg,var(--rv-accent-solid,#E8B93A),#7E5C0E); color:#030032;
  font-family:var(--font-main); font-weight:800; font-size:.95rem; }
.rv-review__author{ font-family:'Manrope','Montserrat',Arial,sans-serif; font-weight:700; color:#fff; }
.rv-review__company{ font-family:var(--font-label); color:#fff; font-size:.78rem; margin-top:4px; }
/* прогресс автоплея: активный сегмент наливается золотом за 6с */
.rv-reviews__bar{ display:flex; gap:8px; margin-top:26px; }
.rv-reviews__seg{ position:relative; flex:1; height:3px; border-radius:2px; background:rgba(255,255,255,.14);
  border:0; padding:0; cursor:pointer; overflow:hidden; -webkit-appearance:none; appearance:none; }
.rv-reviews__seg::after{ content:""; position:absolute; inset:0; background:#F5C542; transform:scaleX(0); transform-origin:left; }
.rv-reviews__seg.is-done::after{ transform:scaleX(1); }
.rv-reviews__seg.is-run::after{ animation:rvSegFill 6s linear forwards; }
@keyframes rvSegFill{ from{ transform:scaleX(0); } to{ transform:scaleX(1); } }
@media(max-width:900px){
  .rv-reviews{ padding:70px 0 80px; }
  .rv-reviews__wrap{ grid-template-columns:1fr; gap:34px; }
  .rv-reviews__title{ margin:18px 0 24px; }
  .rv-reviews::after{ right:-6%; top:-20px; }
  .rv-reviews__stage-box{ overflow:hidden; touch-action:pan-y; }
  .rv-reviews__stage{ touch-action:pan-y; }
  .rv-review{
    transition:opacity .42s cubic-bezier(.16,1,.3,1),
               transform .42s cubic-bezier(.16,1,.3,1);
    will-change:transform, opacity;
  }
  .rv-review.is-dragging{ transition:none !important; }
  .rv-review.is-swipe-neighbour{ visibility:visible; pointer-events:none; }
}
@media (prefers-reduced-motion: reduce){
  .rv-review{ transition:none; }
  .rv-reviews__seg.is-run::after{ animation:none; transform:scaleX(1); }
}
</style>
<?php $en = (pll_current_language() == 'en'); ?>
<div class="rv-reviews__wrap">
	<div class="rv-reviews__head">
		<div class="rv-reviews__chip"><i></i><?php echo $en ? 'Reviews' : 'Відгуки'; ?></div>
		<h2 class="rv-reviews__title"><?php echo $en ? 'What clients <em>say</em>' : 'Що кажуть <em>клієнти</em>'; ?></h2>
		<div class="rv-reviews__nav">
			<button class="rv-reviews__arrow" data-go="-1" aria-label="prev">&larr;</button>
			<span class="rv-reviews__count"><b id="rv-rev-cur">01</b> / 05</span>
			<button class="rv-reviews__arrow" data-go="1" aria-label="next">&rarr;</button>
		</div>
	</div>
	<div class="rv-reviews__stage-box">
	<div class="rv-reviews__stage">
		<article class="rv-review is-active">
			<div class="rv-review__stars">&#9733;&#9733;&#9733;&#9733;&#9733;</div>
			<p class="rv-review__text"><?php echo $en
				? 'Revive rebuilt our online store and conversions grew within the first month. Clear process, fast communication, zero surprises.'
				: 'Revive переробили наш інтернет-магазин — конверсія зросла вже за перший місяць. Зрозумілий процес, швидка комунікація, жодних сюрпризів.'; ?></p>
			<div class="rv-review__who"><span class="rv-review__ava">MC</span><span><span class="rv-review__author">Michael Carter</span><div class="rv-review__company">CEO, Carter Logistics</div></span></div>
		</article>
		<article class="rv-review">
			<div class="rv-review__stars">&#9733;&#9733;&#9733;&#9733;&#9733;</div>
			<p class="rv-review__text"><?php echo $en
				? 'Revive created a modern corporate website that clearly presents our services and strengthens our brand. The project was delivered on time, and the entire process was well organized.'
				: 'Revive створили сучасний корпоративний сайт, який чітко презентує наші послуги та підсилює бренд. Проєкт реалізували вчасно, а весь процес був добре організований.'; ?></p>
			<div class="rv-review__who"><span class="rv-review__ava">JW</span><span><span class="rv-review__author">James Walker</span><div class="rv-review__company"><?php echo $en ? 'Managing Director, Horizon Consulting' : 'Керуючий директор, Horizon Consulting'; ?></div></span></div>
		</article>
		<article class="rv-review">
			<div class="rv-review__stars">&#9733;&#9733;&#9733;&#9733;&#9733;</div>
			<p class="rv-review__text"><?php echo $en
				? 'Revive redesigned our online store, improved its SEO and launched Google Ads campaigns. Organic traffic grew, we started receiving more qualified leads, and the cost per conversion decreased.'
				: 'Revive оновили наш інтернет-магазин, покращили SEO та запустили рекламні кампанії в Google Ads. Органічний трафік зріс, ми почали отримувати більше якісних заявок, а вартість конверсії знизилася.'; ?></p>
			<div class="rv-review__who"><span class="rv-review__ava">OB</span><span><span class="rv-review__author">Olivia Bennett</span><div class="rv-review__company"><?php echo $en ? 'Founder, UrbanNest' : 'Засновниця, UrbanNest'; ?></div></span></div>
		</article>
		<article class="rv-review">
			<div class="rv-review__stars">&#9733;&#9733;&#9733;&#9733;&#9733;</div>
			<p class="rv-review__text"><?php echo $en
				? 'They handled our Google Ads and SEO together — leads doubled while cost per lead went down. We stayed for a third year now.'
				: 'Вони ведуть нам Google Ads і SEO разом — лідів удвічі більше, а вартість ліда знизилась. Працюємо вже третій рік.'; ?></p>
			<div class="rv-review__who"><span class="rv-review__ava">SM</span><span><span class="rv-review__author">Sarah Mitchell</span><div class="rv-review__company">Marketing Director, Nova Interiors</div></span></div>
		</article>
		<article class="rv-review">
			<div class="rv-review__stars">&#9733;&#9733;&#9733;&#9733;&#9733;</div>
			<p class="rv-review__text"><?php echo $en
				? 'The mobile app they built works flawlessly on iOS and Android. Design is exactly what our brand needed.'
				: 'Мобільний застосунок, який вони розробили, бездоганно працює на iOS і Android. Дизайн — саме те, що потрібно нашому бренду.'; ?></p>
			<div class="rv-review__who"><span class="rv-review__ava">DB</span><span><span class="rv-review__author">Daniel Brooks</span><div class="rv-review__company">Founder, BrightFlow</div></span></div>
		</article>
	</div>
	<div class="rv-reviews__bar" id="rv-rev-bar"></div>
	</div>
</div>
<script>
(function(){
'use strict';
var root=document.getElementById('rv-reviews');
var slides=root.querySelectorAll('.rv-review');
var bar=document.getElementById('rv-rev-bar');
var cur=0, timer=null, inView=true;
var reduced=window.matchMedia('(prefers-reduced-motion: reduce)').matches;
var pad=function(n){ return ('0'+(n+1)).slice(-2); };
for(var i=0;i<slides.length;i++){(function(i){
  var b=document.createElement('button'); b.className='rv-reviews__seg'; b.setAttribute('aria-label','review '+(i+1));
  b.addEventListener('click',function(){ go(i); restart(); }); bar.appendChild(b);
})(i);}
var segs=bar.children;
function paint(){
  for(var i=0;i<segs.length;i++){
    segs[i].classList.toggle('is-done', i<cur);
    segs[i].classList.remove('is-run');
  }
  /* перезапуск CSS-анимации налива активного сегмента */
  void segs[cur].offsetWidth;
  segs[cur].classList.add('is-run');
  document.getElementById('rv-rev-cur').textContent=pad(cur);
}
function go(i){
  slides[cur].classList.remove('is-active');
  cur=(i+slides.length)%slides.length;
  slides[cur].classList.add('is-active');
  paint();
}
function restart(){ clearInterval(timer);
  if(reduced) return;
  timer=setInterval(function(){ if(inView&&!document.hidden) go(cur+1); },6000); }
root.querySelectorAll('.rv-reviews__arrow').forEach(function(a){
  a.addEventListener('click',function(){ go(cur + +a.dataset.go); restart(); });
});
/* Мобильный свайп: карточка следует за пальцем, соседняя входит сбоку.
   Вертикальный жест остаётся обычной прокруткой страницы. */
var stage=root.querySelector('.rv-reviews__stage');
var gesture=null, dragRaf=null, swipeAnimating=false;
function wrapIndex(i){ return (i+slides.length)%slides.length; }
function clearSwipeStyles(){
  for(var i=0;i<slides.length;i++){
    slides[i].classList.remove('is-dragging','is-swipe-neighbour');
    slides[i].style.transform='';
    slides[i].style.opacity='';
  }
}
function renderDrag(){
  dragRaf=null;
  if(!gesture || gesture.axis!=='x') return;
  var width=Math.max(stage.clientWidth,1);
  var dx=Math.max(-width*.92,Math.min(width*.92,gesture.dx));
  var dir=dx<0?1:-1;
  var next=wrapIndex(cur+dir);
  var progress=Math.min(Math.abs(dx)/width,1);
  var active=slides[cur], neighbour=slides[next];
  if(gesture.neighbour!==next){
    clearSwipeStyles();
    active.classList.add('is-dragging');
    neighbour.classList.add('is-dragging','is-swipe-neighbour');
    gesture.neighbour=next;
  }
  active.style.transform='translate3d('+dx+'px,0,0) scale('+(1-progress*.025).toFixed(3)+')';
  active.style.opacity=(1-progress*.42).toFixed(3);
  neighbour.style.transform='translate3d('+(dx+dir*width)+'px,0,0) scale('+(0.975+progress*.025).toFixed(3)+')';
  neighbour.style.opacity=(0.34+progress*.66).toFixed(3);
}
stage.addEventListener('touchstart',function(e){
  if(e.touches.length!==1 || reduced || swipeAnimating) return;
  var t=e.touches[0];
  gesture={x:t.clientX,y:t.clientY,dx:0,dy:0,axis:null,neighbour:null,time:Date.now()};
  clearInterval(timer);
},{passive:true});
stage.addEventListener('touchmove',function(e){
  if(!gesture || e.touches.length!==1) return;
  var t=e.touches[0];
  gesture.dx=t.clientX-gesture.x;
  gesture.dy=t.clientY-gesture.y;
  if(!gesture.axis && (Math.abs(gesture.dx)>12 || Math.abs(gesture.dy)>12)){
    /* Обычная диагональная прокрутка не должна превращаться в свайп отзыва. */
    gesture.axis=Math.abs(gesture.dx)>Math.abs(gesture.dy)*1.35?'x':'y';
  }
  if(gesture.axis!=='x') return;
  e.preventDefault();
  if(!dragRaf) dragRaf=requestAnimationFrame(renderDrag);
},{passive:false});
function finishSwipe(cancelled){
  if(!gesture) return;
  if(dragRaf){ cancelAnimationFrame(dragRaf); dragRaf=null; }
  var g=gesture; gesture=null;
  if(g.axis!=='x'){
    clearSwipeStyles();
    restart();
    return;
  }
  var width=Math.max(stage.clientWidth,1);
  var elapsed=Math.max(Date.now()-g.time,1);
  var fast=Math.abs(g.dx)/elapsed>.45;
  var commit=!cancelled && (Math.abs(g.dx)>Math.min(74,width*.20) || (fast&&Math.abs(g.dx)>28));
  var dir=g.dx<0?1:-1;
  var next=wrapIndex(cur+dir);
  var active=slides[cur], neighbour=slides[next];
  active.classList.remove('is-dragging');
  neighbour.classList.remove('is-dragging');
  swipeAnimating=true;
  if(commit){
    active.style.transform='translate3d('+(dir<0?width:-width)+'px,0,0) scale(.975)';
    active.style.opacity='0';
    neighbour.style.transform='translate3d(0,0,0) scale(1)';
    neighbour.style.opacity='1';
    window.setTimeout(function(){
      slides[cur].classList.remove('is-active');
      cur=next;
      slides[cur].classList.add('is-active');
      paint();
      requestAnimationFrame(clearSwipeStyles);
      swipeAnimating=false;
      restart();
    },420);
  }else{
    active.style.transform='translate3d(0,0,0) scale(1)';
    active.style.opacity='1';
    neighbour.style.transform='translate3d('+(dir*width)+'px,0,0) scale(.975)';
    neighbour.style.opacity='0';
    window.setTimeout(function(){
      clearSwipeStyles();
      swipeAnimating=false;
      restart();
    },420);
  }
}
stage.addEventListener('touchend',function(){ finishSwipe(false); },{passive:true});
stage.addEventListener('touchcancel',function(){ finishSwipe(true); },{passive:true});
if(window.matchMedia('(hover: hover)').matches){
  root.addEventListener('mouseenter',function(){ clearInterval(timer); });
  root.addEventListener('mouseleave',restart);
}
if('IntersectionObserver' in window){
  new IntersectionObserver(function(es){ inView=es[0].isIntersecting; },{threshold:0}).observe(root);
}
paint(); restart();
})();
</script>
</section>
<!-- ===== /RV REVIEWS ===== -->
<?php endif; ?>

<!-- ===== RV FAQ: частые вопросы + разметка FAQPage ===== -->
<section class="rv-faq" id="rv-faq">
<style>
.rv-faq{ --font-label:'JetBrains Mono',monospace; --rv-h:'Manrope','Montserrat',Arial,sans-serif;
  --rv-gold:#F5C542; position:relative; background:#030032; color:#fff; padding:110px 0 120px; overflow:hidden; }
.rv-faq::before{ content:""; position:absolute; inset:0; pointer-events:none;
  background:radial-gradient(ellipse 42% 50% at 12% 14%, rgba(245,197,66,.07) 0%, transparent 70%); }
/* сетка сайта: во всю ширину с одним полем по краям — как .wrapper и остальные секции */
.rv-faq__wrap{ position:relative; max-width:none; margin:0; padding:0 clamp(20px,4vw,64px);
  display:grid; grid-template-columns:minmax(300px,.72fr) minmax(0,1.6fr);
  gap:clamp(34px,4vw,80px); align-items:start; }
.rv-faq__chip{ display:inline-flex; align-items:center; gap:.7rem; background:rgba(255,255,255,.05);
  border:1px solid rgba(255,255,255,.12); border-radius:100px; padding:.5rem 1.1rem;
  font-family:var(--font-label); font-size:.66rem; letter-spacing:.1em; text-transform:uppercase;
  color:#fff; }
.rv-faq__chip i{ font-style:normal; width:7px; height:7px; border-radius:50%; background:var(--rv-gold);
  box-shadow:0 0 12px var(--rv-gold); }
/* цвет задаём явно: колонка-обёртка красит текст в rgba(255,255,255,.7),
   и заголовок наследовал от неё серый вместо белого */
.rv-faq__title{ font-family:var(--rv-h); font-weight:800; letter-spacing:-.03em; text-transform:uppercase;
  font-size:clamp(34px,4vw,58px); line-height:1.05; margin:22px 0 26px;
  color:#fff; -webkit-text-fill-color:#fff; }
.rv-faq__title em{ font-style:normal; color:transparent; -webkit-text-fill-color:transparent;
  -webkit-text-stroke:1.5px var(--rv-gold); padding-right:2px; }
.rv-faq__aside{ font-size:1rem; line-height:1.65; color:#fff; max-width:46ch; }
.rv-faq__card{ margin-top:28px; padding:clamp(22px,1.8vw,30px) clamp(22px,1.8vw,28px);
  border:1px solid rgba(255,255,255,.12);
  border-radius:20px; background:rgba(255,255,255,.045); }
.rv-faq__card b{ display:block; font-family:var(--rv-h); font-weight:800; font-size:1.05rem; margin-bottom:8px; color:#fff; }
.rv-faq__card p{ margin:0 0 16px; font-size:.92rem; line-height:1.55; color:#fff; }
.rv-faq__phone{ display:inline-block; font-family:var(--rv-h); font-weight:800; font-size:1.18rem;
  color:var(--rv-gold); text-decoration:none; letter-spacing:-.01em; }
.rv-faq__phone:hover{ text-decoration:underline; }
/* аккордеон */
.rv-faq__list{ display:flex; flex-direction:column; gap:10px; }
.rv-faq__item{ border:1px solid rgba(255,255,255,.12); border-radius:18px; background:rgba(255,255,255,.04);
  transition:border-color .3s ease, background .3s ease; }
.rv-faq__item.is-open{ border-color:rgba(245,197,66,.4); background:rgba(255,255,255,.06); }
.rv-faq__q{ width:100%; display:flex; align-items:flex-start; gap:16px;
  padding:clamp(20px,1.7vw,28px) clamp(24px,2vw,34px);
  font-family:var(--rv-h); font-weight:700; font-size:1.02rem; line-height:1.4; letter-spacing:-.01em;
  text-align:left; color:#fff; background:none; border:0; cursor:pointer;
  -webkit-appearance:none; appearance:none; }
/* текст занимает всю строку, плюс/минус прижат к правому краю карточки */
.rv-faq__q span{ flex:1 1 auto; }
.rv-faq__q i{ flex:none; width:22px; height:22px; margin-top:2px; position:relative; font-style:normal; }
.rv-faq__q i::before, .rv-faq__q i::after{ content:""; position:absolute; left:50%; top:50%; background:var(--rv-gold);
  transform:translate(-50%,-50%); transition:transform .3s cubic-bezier(.22,1,.36,1), opacity .3s ease; }
.rv-faq__q i::before{ width:14px; height:1.5px; }
.rv-faq__q i::after{ width:1.5px; height:14px; }
.rv-faq__item.is-open .rv-faq__q i::after{ transform:translate(-50%,-50%) scaleY(0); opacity:0; }
.rv-faq__a{ display:grid; grid-template-rows:0fr; transition:grid-template-rows .35s cubic-bezier(.22,1,.36,1); }
.rv-faq__item.is-open .rv-faq__a{ grid-template-rows:1fr; }
.rv-faq__a > div{ overflow:hidden; }
.rv-faq__a p{ margin:0; padding:0 clamp(24px,2vw,34px) clamp(24px,2vw,30px); font-size:.96rem; line-height:1.65;
  color:#fff; max-width:92ch; }
.rv-faq__a p + p{ padding-top:12px; }
@media(max-width:1024px){
  .rv-faq{ padding:80px 0 90px; }
  .rv-faq__wrap{ grid-template-columns:1fr; gap:34px; }
  .rv-faq__card{ margin-top:22px; }
}
@media(max-width:700px){
  .rv-faq{ padding:34px 0 38px; }
  .rv-faq__wrap{ gap:20px; }
  .rv-faq__title{ margin:14px 0 14px; }
  .rv-faq__aside{ font-size:.94rem; line-height:1.5; }
  .rv-faq__card{ margin-top:16px; padding:16px 18px; }
  .rv-faq__card p{ margin-bottom:10px; font-size:.86rem; }
  .rv-faq__phone{ font-size:1.08rem; }
  .rv-faq__list{ gap:8px; }
  .rv-faq__q{ padding:14px 16px; font-size:.94rem; gap:10px; }
  .rv-faq__a p{ padding:0 16px 16px; font-size:.9rem; line-height:1.55; }
}
@media(prefers-reduced-motion:reduce){
  .rv-faq__a{ transition:none; }
  .rv-faq__q i::before, .rv-faq__q i::after{ transition:none; }
}
</style>
<?php
$rv_fq_en = (function_exists('pll_current_language') && pll_current_language() == 'en');
$rv_faq = $rv_fq_en ? array(
  array('How much does a website cost?','Scope decides it: landing pages and corporate sites start at $1,500, online stores at $2,000, mobile apps at $2,500. We name the exact figure after the brief and break it down by stage — so the number does not move mid-project.'),
  array('How long does it take?','A landing page takes 2–3 weeks, a corporate site 3–5 weeks, an online store 5–7 weeks and up. Deadlines are fixed in the contract together with the stages.'),
  array('What do you need from me to start?','About an hour for the brief, plus access to your domain and analytics if the site already exists. After that — feedback at three checkpoints: prototype, design and the staging version.'),
  array('Who writes the texts and prepares the images?','We can take it on: copywriting, image selection and basic retouching. If the content is yours, we hand over a structure and examples so nothing gets lost along the way.'),
  array('Will I be able to edit the site myself?','Yes. We build on a CMS with a human admin panel, and after launch we walk your team through it and leave a short written guide.'),
  array('What does support include after launch?','CMS and plugin updates, backups, uptime monitoring, small content edits and consulting. The scope is agreed monthly, so there are no surprises in the invoice.'),
  array('How does payment work — and do you work with clients abroad?','Usually two or three payments tied to stages: start, design, launch. We work under a contract; for clients in the EU and the US we issue invoices. We have offices in Kyiv and Irvine, California, and run projects in English and Ukrainian.'),
) : array(
  array('Скільки коштує сайт?','Усе вирішує обсяг: лендинги та корпоративні сайти стартують від $1500, інтернет-магазини — від $2000, мобільні застосунки — від $2500. Точну цифру називаємо після брифу й розбиваємо по етапах, щоб вона не «пливла» вже в процесі.'),
  array('Скільки часу займає розробка?','Лендинг — 2–3 тижні, корпоративний сайт — 3–5 тижнів, інтернет-магазин — від 5–7 тижнів. Терміни фіксуємо в договорі разом з етапами.'),
  array('Що потрібно від мене на старті?','Приблизно година на бріф і доступи до домену та аналітики, якщо сайт уже існує. Далі — зворотний зв’язок на трьох контрольних точках: прототип, дизайн і тестова версія.'),
  array('Хто готує тексти та зображення?','Можемо взяти на себе: копірайтинг, добір зображень і базову обробку. Якщо контент ваш — передаємо структуру та приклади, щоб нічого не загубилось.'),
  array('Чи зможу я сам редагувати сайт?','Так. Робимо на CMS із людською адмінкою, після запуску проводимо навчання й лишаємо коротку письмову інструкцію.'),
  array('Що входить у підтримку після запуску?','Оновлення CMS і плагінів, резервні копії, моніторинг доступності, дрібні правки контенту та консультації. Обсяг узгоджуємо помісячно — без сюрпризів у рахунку.'),
  array('Як відбувається оплата і чи працюєте ви з клієнтами за кордоном?','Зазвичай двома-трьома платежами за етапами: старт, дизайн, запуск. Працюємо за договором, для клієнтів з ЄС і США виставляємо інвойси. У нас офіси в Києві та Ірвайні (Каліфорнія), ведемо проєкти українською та англійською.'),
);
/* Разметка для поисковиков: тот же список вопросов, что видит человек. */
$rv_faq_ld = array('@context'=>'https://schema.org','@type'=>'FAQPage','mainEntity'=>array());
foreach ($rv_faq as $rv_f) {
  $rv_faq_ld['mainEntity'][] = array(
    '@type'=>'Question','name'=>$rv_f[0],
    'acceptedAnswer'=>array('@type'=>'Answer','text'=>$rv_f[1]),
  );
}
?>
<div class="rv-faq__wrap">
	<div class="rv-faq__aside">
		<div class="rv-faq__chip"><i></i>FAQ</div>
		<h2 class="rv-faq__title"><?php echo $rv_fq_en ? 'Frequent <em>questions</em>' : 'Часті <em>питання</em>'; ?></h2>
		<p style="margin:0;"><?php echo $rv_fq_en
			? 'The seven things clients ask before signing. If your question is not here, ask it directly — we answer the same day.'
			: 'Сім питань, які клієнти ставлять до підписання договору. Якщо вашого тут немає — запитайте напряму, відповідаємо того ж дня.'; ?></p>
		<div class="rv-faq__card">
			<b><?php echo $rv_fq_en ? 'Still not sure?' : 'Не знайшли відповідь?'; ?></b>
			<p><?php echo $rv_fq_en
				? 'A 15-minute call is usually enough to understand the scope, the budget range and the timeline.'
				: 'Зазвичай 15 хвилин розмови вистачає, щоб зрозуміти обсяг, вилку бюджету й терміни.'; ?></p>
			<a class="rv-faq__phone" href="tel:+380678000830">+38 (067) 8000-830</a>
		</div>
	</div>

	<div class="rv-faq__list" id="rv-faq-list">
		<?php foreach ($rv_faq as $rv_i => $rv_f) : ?>
		<div class="rv-faq__item<?php echo $rv_i === 0 ? ' is-open' : ''; ?>">
			<button type="button" class="rv-faq__q" aria-expanded="<?php echo $rv_i === 0 ? 'true' : 'false'; ?>" aria-controls="rv-faq-a<?php echo esc_attr($rv_i); ?>">
				<span><?php echo esc_html($rv_f[0]); ?></span><i aria-hidden="true"></i>
			</button>
			<div class="rv-faq__a" id="rv-faq-a<?php echo esc_attr($rv_i); ?>" role="region">
				<div><p><?php echo esc_html($rv_f[1]); ?></p></div>
			</div>
		</div>
		<?php endforeach; ?>
	</div>
</div>
<script type="application/ld+json"><?php echo wp_json_encode($rv_faq_ld, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES); ?></script>
<script>
(function(){
  var list = document.getElementById('rv-faq-list');
  if (!list) return;
  list.addEventListener('click', function(e){
    var btn = e.target.closest ? e.target.closest('.rv-faq__q') : null;
    if (!btn || !list.contains(btn)) return;
    var item = btn.parentNode, open = item.classList.contains('is-open');
    /* открыт всегда один: длинный список из семи развёрнутых ответов
       на телефоне превращается в простыню на четыре экрана */
    var all = list.querySelectorAll('.rv-faq__item');
    for (var i = 0; i < all.length; i++){
      all[i].classList.remove('is-open');
      all[i].querySelector('.rv-faq__q').setAttribute('aria-expanded','false');
    }
    if (!open){ item.classList.add('is-open'); btn.setAttribute('aria-expanded','true'); }
  });
})();
</script>
</section>
<!-- ===== /RV FAQ ===== -->

<section class="s11" id="s11">
	<!-- Анимированный фон: видео с Землёй остаётся, слои затемнения — из style.css. -->
	<video class="s11__video rv-lazy-video"
	       data-src="https://d8j0ntlcm91z4.cloudfront.net/user_38xzZboKViGWJOttwIXH07lWA1P/hf_20260613_180732_a54afbf6-b30d-470e-861f-669871f09f67.mp4"
	       preload="none" muted loop playsinline aria-hidden="true" tabindex="-1"></video>
	<span class="rv-wave" aria-hidden="true"></span>
<?php
/* ===== КОНТАКТЫ v2 =====
   Слева — заголовок, каналы связи и офисы с местным временем,
   справа — форма в стеклянной карточке. Контакты заданы здесь
   (раньше приходили из ACF-поля infa_sekczii_11): правятся в массиве ниже. */
$rv_ct_en = function_exists( 'pll_current_language' ) && 'en' === pll_current_language();
$rv_ct = $rv_ct_en ? array(
	'chip'    => 'Contact · we reply the same day',
	'title'   => 'Let’s make things <em>move.</em>',
	'lead'    => 'Tell us what you’re building — we’ll take it from there. Or reach us directly:',
	'card'    => 'Discuss your project',
	'online'  => 'Online now',
	'cardsub' => 'Leave your number — a project manager calls you back with first ideas and an estimate.',
	'note'    => 'By sending the form you agree to our <a href="%s">privacy policy</a>.',
	'privacy' => home_url( '/privacy-policy/' ),
	'phoneL'  => 'Phone',
	'tgL'     => 'Telegram',
	'waL'     => 'WhatsApp',
	'offices' => 'Offices',
	'now'     => 'local time',
	'kyiv'    => array( 'Kyiv', 'Novopecherskyi Lane 5, Kyiv, Ukraine' ),
	'irvine'  => array( 'Irvine', '2030 Main Street, Irvine, California 92614, USA' ),
	'form'    => '[contact-form-7 id="10" title="cf7#1"]',
) : array(
	'chip'    => 'Контакти · відповідаємо того ж дня',
	'title'   => 'Створімо щось, <em>що рухає вперед.</em>',
	'lead'    => 'Розкажіть, що плануєте створити — далі ми візьмемо все на себе. Або напишіть напряму:',
	'card'    => 'Обговорімо ваш проєкт',
	'online'  => 'Зараз онлайн',
	'cardsub' => 'Залиште номер — менеджер проєкту передзвонить з першими ідеями та оцінкою.',
	'note'    => 'Надсилаючи форму, ви погоджуєтеся з <a href="%s">політикою конфіденційності</a>.',
	'privacy' => home_url( '/uk/privacy-policy/' ),
	'phoneL'  => 'Телефон',
	'tgL'     => 'Telegram',
	'waL'     => 'WhatsApp',
	'offices' => 'Офіси',
	'now'     => 'місцевий час',
	'kyiv'    => array( 'Київ', 'Новопечерський провулок, 5, Київ, Україна' ),
	'irvine'  => array( 'Ірвайн', '2030 Мейн-стріт, Ірвайн, Каліфорнія 92614, США' ),
	'form'    => '[contact-form-7 id="473" title="cf7#1_ua"]',
);
$rv_ct_channels = array(
	array( 'phone', $rv_ct['phoneL'], '+38 (067) 8000-830', 'tel:+380678000830', false ),
	array( 'tg',    $rv_ct['tgL'],    '@max8ua',            'https://telegram.me/max8ua', true ),
	array( 'wa',    $rv_ct['waL'],    '+38 067 8000 830',   'https://wa.me/380678000830', true ),
);
$rv_ct_icons = array(
	'phone' => '<svg viewBox="0 0 24 24"><path d="M5 4h4l2 5-2.5 1.5a11 11 0 0 0 5 5L15 13l5 2v4a2 2 0 0 1-2 2A16 16 0 0 1 3 6a2 2 0 0 1 2-2"/></svg>',
	'tg'    => '<svg viewBox="0 0 24 24"><path d="M21 4L3 11l6 2.2L18 7l-7 7.5V20l3.3-3.6L18 19z"/></svg>',
	'wa'    => '<svg viewBox="0 0 24 24"><path d="M4 20l1.3-3.9A8.5 8.5 0 1 1 8 18.8z"/><path d="M9 8.5c0 3.5 3 6.5 6.5 6.5l1-1.8-2-1-1 1a5 5 0 0 1-2.7-2.7l1-1-1-2z"/></svg>',
);
?>
	<div class="wrapper">
		<div class="rv-ct">
			<div class="rv-ct__left">
				<p class="rv-ct__chip"><i></i><?php echo esc_html( $rv_ct['chip'] ); ?></p>
				<h2 class="rv-ct__title"><?php echo wp_kses( $rv_ct['title'], array( 'em' => array() ) ); ?></h2>

				<ul class="rv-ct__channels">
					<?php foreach ( $rv_ct_channels as $rv_c ) : ?>
					<li>
						<a class="rv-ct__ch" href="<?php echo esc_url( $rv_c[3] ); ?>"<?php echo $rv_c[4] ? ' target="_blank" rel="noopener"' : ''; ?>>
							<span class="rv-ct__ico" aria-hidden="true"><?php echo $rv_ct_icons[ $rv_c[0] ]; // SVG из массива выше ?></span>
							<span class="rv-ct__chtext"><small><?php echo esc_html( $rv_c[1] ); ?></small><b><?php echo esc_html( $rv_c[2] ); ?></b></span>
							<span class="rv-ct__arrow" aria-hidden="true">↗</span>
						</a>
					</li>
					<?php endforeach; ?>
				</ul>

				<div class="rv-ct__offices" aria-label="<?php echo esc_attr( $rv_ct['offices'] ); ?>">
					<?php foreach ( array( array( $rv_ct['kyiv'], 'Europe/Kiev' ), array( $rv_ct['irvine'], 'America/Los_Angeles' ) ) as $rv_o ) : ?>
					<div class="rv-ct__office">
						<p class="rv-ct__city"><span><?php echo esc_html( $rv_o[0][0] ); ?></span><time data-rv-tz="<?php echo esc_attr( $rv_o[1] ); ?>">—:—</time></p>
						<p class="rv-ct__addr"><?php echo esc_html( $rv_o[0][1] ); ?></p>
						<p class="rv-ct__tz"><?php echo esc_html( $rv_ct['now'] ); ?></p>
					</div>
					<?php endforeach; ?>
				</div>
			</div>

			<div class="rv-ct__right">
				<div class="rv-ct__card">
					<div class="rv-ct__card-head">
						<p class="rv-ct__card-title"><?php echo esc_html( $rv_ct['card'] ); ?></p>
						<p class="rv-ct__online"><i></i><?php echo esc_html( $rv_ct['online'] ); ?></p>
					</div>
					<p class="rv-ct__card-sub"><?php echo esc_html( $rv_ct['cardsub'] ); ?></p>
					<div class="s11__form">
						<?php echo do_shortcode( $rv_ct['form'] ); ?>
					</div>
					<p class="rv-ct__note"><?php echo wp_kses( sprintf( $rv_ct['note'], esc_url( $rv_ct['privacy'] ) ), array( 'a' => array( 'href' => array() ) ) ); ?></p>
				</div>
			</div>
		</div>
	</div>

<style id="rv-ct-css">
section.s11#s11{--gold:#F5C542;--ease:cubic-bezier(.22,1,.36,1);
  display:block!important;min-height:0!important;padding:clamp(72px,calc(var(--rv-lvh,1vh)*10),120px) 0 clamp(90px,calc(var(--rv-lvh,1vh)*16),190px)!important}
#s11 .rv-ct *,#s11 .rv-ct *::before,#s11 .rv-ct *::after{box-sizing:border-box}
#s11 .rv-ct p,#s11 .rv-ct h2,#s11 .rv-ct ul{margin:0;padding:0}
#s11 .rv-ct ul{list-style:none}
#s11 .rv-ct i,#s11 .rv-ct em{font-style:normal}
#s11 > .wrapper{max-width:1320px!important;padding:0 clamp(20px,4vw,56px)!important}
.rv-ct{display:grid;grid-template-columns:minmax(0,1.05fr) minmax(0,.95fr);gap:clamp(32px,5vw,88px);align-items:start;font-family:'Inter',Arial,sans-serif;color:#fff}

/* — левая колонка — */
.rv-ct__chip{display:inline-flex;align-items:center;gap:.7rem;padding:.45rem 1rem;border-radius:100px;border:1px solid rgba(255,255,255,.16);
  background:rgba(3,0,50,.4);-webkit-backdrop-filter:blur(10px);backdrop-filter:blur(10px);
  font-family:'JetBrains Mono',monospace;font-size:.66rem;letter-spacing:.12em;text-transform:uppercase;color:#fff}
.rv-ct__chip i{width:7px;height:7px;border-radius:50%;background:#5BD98A;box-shadow:0 0 10px #5BD98A;animation:rvCtLive 2s ease-in-out infinite}
.rv-ct__title{margin-top:20px!important;font-family:'Manrope',Arial,sans-serif;font-weight:800;font-size:clamp(34px,3.35vw,58px);line-height:1.06;letter-spacing:-.035em;
  text-transform:uppercase;color:#fff}
.rv-ct__title em{display:block;margin-top:.04em;color:var(--gold);white-space:nowrap}
.rv-ct__lead{margin-top:18px!important;max-width:44ch;font-size:clamp(15px,1.15vw,18px);line-height:1.6;color:#fff}

.rv-ct__channels{width:100%;max-width:none;margin-top:26px!important;border-top:1px solid rgba(255,255,255,.14)}
.rv-ct__channels li{border-bottom:1px solid rgba(255,255,255,.14)}
.rv-ct__ch{position:relative;display:grid;grid-template-columns:48px minmax(0,1fr) 40px;align-items:center;gap:16px;padding:14px 6px;color:#fff!important;text-decoration:none!important;overflow:hidden;isolation:isolate}
.rv-ct__ch::before{content:"";position:absolute;inset:0;z-index:-1;background:linear-gradient(90deg,rgba(245,197,66,.14),rgba(245,197,66,0) 70%);transform:scaleX(0);transform-origin:left;transition:transform .5s var(--ease)}
.rv-ct__ch:hover::before,.rv-ct__ch:focus-visible::before{transform:scaleX(1)}
.rv-ct__ch:focus-visible{outline:2px solid rgba(245,197,66,.8);outline-offset:2px}
.rv-ct__ico{display:grid;place-items:center;width:48px;height:48px;border-radius:50%;border:1px solid rgba(245,197,66,.45);background:rgba(245,197,66,.08);transition:background .35s ease,border-color .35s ease}
.rv-ct__ico svg{width:20px;height:20px;fill:none;stroke:var(--gold);stroke-width:1.7;stroke-linecap:round;stroke-linejoin:round;transition:stroke .35s ease}
.rv-ct__ch:hover .rv-ct__ico{background:var(--gold);border-color:var(--gold)}
.rv-ct__ch:hover .rv-ct__ico svg{stroke:#030032}
.rv-ct__chtext small{display:block;font-family:'JetBrains Mono',monospace;font-size:.62rem;letter-spacing:.12em;text-transform:uppercase;color:var(--gold)}
.rv-ct__chtext b{display:block;margin-top:3px;font-family:'Manrope',Arial,sans-serif;font-weight:700;font-size:clamp(18px,1.6vw,24px);letter-spacing:-.01em;color:#fff;white-space:nowrap;overflow:hidden;text-overflow:ellipsis}
.rv-ct__arrow{justify-self:end;display:grid;place-items:center;width:40px;height:40px;border-radius:50%;border:1px solid rgba(255,255,255,.2);font-size:.95rem;color:#fff;transition:transform .4s var(--ease),background .3s ease,color .3s ease,border-color .3s ease}
.rv-ct__ch:hover .rv-ct__arrow{transform:rotate(45deg);background:#fff;color:#030032;border-color:#fff}

.rv-ct__offices{width:100%;display:grid;grid-template-columns:1fr 1fr;gap:12px;margin-top:22px}
.rv-ct__office{padding:16px 18px;border-radius:18px;border:1px solid rgba(255,255,255,.12);background:rgba(3,0,50,.38);-webkit-backdrop-filter:blur(10px);backdrop-filter:blur(10px)}
.rv-ct__city{display:flex;justify-content:space-between;align-items:baseline;gap:10px}
.rv-ct__city span{font-family:'Manrope',Arial,sans-serif;font-weight:800;font-size:1.05rem;text-transform:uppercase;letter-spacing:.02em;color:#fff}
.rv-ct__city time{font-family:'JetBrains Mono',monospace;font-size:.95rem;font-weight:500;color:var(--gold);font-variant-numeric:tabular-nums}
.rv-ct__addr{margin-top:6px!important;font-size:.84rem;line-height:1.45;color:#fff}
.rv-ct__tz{margin-top:8px!important;font-family:'JetBrains Mono',monospace;font-size:.58rem;letter-spacing:.12em;text-transform:uppercase;color:#fff;opacity:.7}

/* — ПК и планшет: каналы связи — три плитки с иконками, как на телефоне.
     Номер под подписью оставлен: на ПК по tel: звонок часто не открывается — номер видно сразу. — */
@media(min-width:768px){
  .rv-ct__channels{display:grid;grid-template-columns:repeat(3,minmax(0,1fr));gap:12px;border-top:0}
  .rv-ct__channels li{border:0}
  .rv-ct__ch{grid-template-columns:1fr;justify-items:center;text-align:center;gap:12px;padding:22px 12px 20px;border-radius:22px;
    border:1px solid rgba(255,255,255,.14);background:rgba(3,0,50,.42);-webkit-backdrop-filter:blur(10px);backdrop-filter:blur(10px);
    transition:border-color .35s ease,transform .45s var(--ease),box-shadow .45s var(--ease)}
  .rv-ct__ch:hover,.rv-ct__ch:focus-visible{border-color:rgba(245,197,66,.55);transform:translateY(-4px);box-shadow:0 18px 40px rgba(0,0,0,.35),0 0 30px rgba(245,197,66,.12)}
  .rv-ct__ch::before{background:radial-gradient(80% 70% at 50% 0%,rgba(245,197,66,.16),transparent 70%);transform:none;opacity:0;transition:opacity .45s ease}
  .rv-ct__ch:hover::before,.rv-ct__ch:focus-visible::before{opacity:1;transform:none}
  .rv-ct__ico{width:54px;height:54px}
  .rv-ct__ico svg{width:22px;height:22px}
  .rv-ct__chtext{min-width:0;width:100%}
  .rv-ct__chtext small{font-size:.6rem}
  .rv-ct__chtext b{margin-top:5px;font-size:clamp(13px,1.05vw,15px);font-weight:600;letter-spacing:0;opacity:.85}
  .rv-ct__arrow{display:none}
}

/* — карточка с формой: по центру высоты левой колонки — */
.rv-ct__right{align-self:center}
.rv-ct__card{position:relative;padding:clamp(24px,2.6vw,36px);border-radius:30px;overflow:hidden;
  background:linear-gradient(160deg,rgba(255,255,255,.1),rgba(255,255,255,.03) 70%);border:1px solid rgba(255,255,255,.16);
  box-shadow:0 40px 90px rgba(0,0,0,.45),inset 0 1px 0 rgba(255,255,255,.12);-webkit-backdrop-filter:blur(20px);backdrop-filter:blur(20px)}
.rv-ct__card::before{content:"";position:absolute;left:30px;right:30px;top:0;height:1px;background:linear-gradient(90deg,transparent,var(--gold),transparent)}
.rv-ct__card::after{content:"";position:absolute;right:-30%;top:-40%;width:70%;height:80%;pointer-events:none;background:radial-gradient(circle,rgba(245,197,66,.14),transparent 65%)}
.rv-ct__card-head{display:flex;flex-direction:column-reverse;align-items:flex-start;gap:14px}
.rv-ct__card-title{font-family:'Manrope',Arial,sans-serif;font-weight:800;font-size:clamp(22px,2vw,30px);line-height:1.1;letter-spacing:-.02em;color:#fff}
.rv-ct__online{display:inline-flex;align-items:center;gap:8px;padding:6px 13px 6px 12px;border-radius:99px;border:1px solid rgba(91,217,138,.35);background:rgba(91,217,138,.08);
  font-family:'JetBrains Mono',monospace;font-size:.6rem;letter-spacing:.12em;text-transform:uppercase;color:#5BD98A}
.rv-ct__online i{width:6px;height:6px;border-radius:50%;background:#5BD98A;box-shadow:0 0 8px #5BD98A;animation:rvCtLive 2s ease-in-out infinite}
.rv-ct__card-sub{margin-top:10px!important;font-size:.95rem;line-height:1.55;color:#fff;max-width:46ch}
#s11 .rv-ct__card .s11__form{position:relative;z-index:1;width:100%!important;max-width:none!important;margin:22px 0 0!important;padding:0!important}
#s11 .rv-ct__card .s11__form .wpcf7-form p{margin:0 0 14px!important}
#s11 .rv-ct__card .s11__form .wpcf7-form-control-wrap{width:100%!important;max-width:none!important;margin:0!important}
#s11 .rv-ct__card .s11__form input.wpcf7-submit{width:100%!important;max-width:none!important;margin:4px 0 0!important}
.rv-ct__note{position:relative;z-index:1;margin-top:14px!important;font-size:.78rem;line-height:1.5;color:#fff;opacity:.85}
.rv-ct__note a{color:var(--gold)!important;text-decoration:none!important;border-bottom:1px solid rgba(245,197,66,.5)}

/* отступы плашек: сильнее сброса #s11 .rv-ct p */
#s11 .rv-ct .rv-ct__chip{padding:.5rem 1.05rem .5rem .85rem}
#s11 .rv-ct .rv-ct__online{padding:6px 13px 6px 11px}

@keyframes rvCtLive{50%{opacity:.35}}

/* — планшет — */
@media(max-width:1024px){
  .rv-ct{grid-template-columns:1fr}
  .rv-ct__right{order:2;align-self:stretch}
  .rv-ct__title{font-size:clamp(34px,5.6vw,58px)}
}
/* — телефон: заголовок → форма → каналы → офисы — */
@media(max-width:767px){
  section.s11#s11{padding:56px 0 calc(var(--rv-lvh,1vh)*30)!important}
  .rv-ct{display:flex;flex-direction:column;gap:0}
  .rv-ct__left{display:contents}
  .rv-ct__chip{order:1;align-self:flex-start}
  .rv-ct__title{order:2;font-size:clamp(28px,8vw,36px)}
  .rv-ct__title em{white-space:normal}
  .rv-ct__lead{order:3;font-size:.95rem}
  .rv-ct__right{order:4;margin-top:22px}
  .rv-ct__channels{order:5}
  .rv-ct__offices{order:6;grid-template-columns:1fr}
  .rv-ct__card{padding:22px 18px;border-radius:24px}
  .rv-ct__ch{grid-template-columns:44px minmax(0,1fr) 36px;gap:12px;padding:12px 2px}
  .rv-ct__ico{width:44px;height:44px}
  .rv-ct__arrow{width:36px;height:36px}
  .rv-ct__chtext b{font-size:18px}
}
/* — телефон, компактно: ~1 экран вместо 2,5 —
   убраны вводный абзац, подзаголовок карточки и офисы (Київ/Ірвайн),
   каналы связи — одной строкой из трёх плиток */
@media(max-width:767px){
  section.s11#s11{padding:40px 0 150px!important} /* снизу — окно, чтобы была видна планета на фоне */
  #s11 .rv-ct .rv-ct__chip{padding:.4rem .9rem .4rem .75rem;font-size:.58rem}
  #s11 .rv-ct .rv-ct__title{margin-top:14px!important;font-size:clamp(26px,7.4vw,32px)}
  .rv-ct__lead,.rv-ct__card-sub,.rv-ct__offices{display:none!important}
  .rv-ct__right{margin-top:32px} /* воздух между заголовком и формой */
  .rv-ct__card{padding:18px 16px 16px;border-radius:22px}
  .rv-ct__card-head{flex-direction:row;align-items:center;justify-content:space-between;gap:10px}
  .rv-ct__card-title{font-size:18px;line-height:1.15}
  #s11 .rv-ct .rv-ct__online{padding:5px 10px 5px 9px;font-size:.52rem;flex:none}
  #s11 .rv-ct__card .s11__form{margin-top:14px!important}
  #s11 .rv-ct__card .s11__form .wpcf7-form p{margin:0 0 10px!important}
  #s11 .rv-ct .rv-ct__note{margin-top:10px!important;font-size:.7rem}
  .rv-ct__channels{display:grid;grid-template-columns:repeat(3,minmax(0,1fr));gap:8px;margin-top:12px!important;border-top:0}
  .rv-ct__channels li{border:0}
  .rv-ct__ch{grid-template-columns:1fr;justify-items:center;gap:6px;padding:10px 4px;border-radius:16px;
    border:1px solid rgba(255,255,255,.14);background:rgba(3,0,50,.42);-webkit-backdrop-filter:blur(10px);backdrop-filter:blur(10px);text-align:center}
  .rv-ct__ico{width:36px;height:36px}
  .rv-ct__ico svg{width:17px;height:17px}
  .rv-ct__chtext small{font-size:.56rem;letter-spacing:.1em;color:#fff}
  .rv-ct__chtext b,.rv-ct__arrow{display:none}
  .rv-ct__ch:active .rv-ct__ico{background:var(--gold);border-color:var(--gold)}
  .rv-ct__ch:active .rv-ct__ico svg{stroke:#030032}
}
@media(prefers-reduced-motion:reduce){.rv-ct__chip i,.rv-ct__online i{animation:none}}
</style>

<script id="rv-ct-js">
/* Местное время офисов — обновляется раз в 20 секунд */
(function(){
  'use strict';
  var els = document.querySelectorAll('#s11 [data-rv-tz]');
  if (!els.length || !window.Intl) return;
  function tick(){
    Array.prototype.forEach.call(els, function(el){
      try {
        el.textContent = new Intl.DateTimeFormat('uk-UA', { hour: '2-digit', minute: '2-digit', hour12: false, timeZone: el.getAttribute('data-rv-tz') }).format(new Date());
      } catch (e) {}
    });
  }
  tick();
  setInterval(tick, 20000);
})();
</script>
</section>
<script>
/* Видео получает src и запускается только при приближении к нижнему блоку. */
(function(){
  'use strict';
  var video = document.querySelector('#s11 .rv-lazy-video[data-src]');
  if (!video) return;

  function loadVideo(){
    if (video.dataset.rvLoaded) return;
    var source = video.getAttribute('data-src');
    if (!source) return;

    video.dataset.rvLoaded = '1';
    video.removeAttribute('data-src');
    video.muted = true;
    video.loop = true;
    video.playsInline = true;
    video.autoplay = true;

    function revealAndPlay(){
      var section = document.getElementById('s11');
      if (section) section.classList.add('rv-video-ready');
      var promise = video.play();
      if (promise && promise.catch) promise.catch(function(){});
    }

    video.addEventListener('canplay', revealAndPlay, { once:true });
    video.src = source;
    video.load();
    if (video.readyState >= 3) revealAndPlay();
  }

  if (!('IntersectionObserver' in window)){
    window.addEventListener('load', loadVideo, { once:true });
    return;
  }

  var observer = new IntersectionObserver(function(entries){
    if (!entries[0].isIntersecting) return;
    loadVideo();
    observer.disconnect();
  }, { rootMargin:'0px 0px 700px 0px', threshold:0 });

  observer.observe(video);
})();
</script>
<script>
/* ============================================================================
   ФУТЕР: сцена отвечает форме
   ----------------------------------------------------------------------------
   Фон перестаёт быть равномерным во времени и получает событие.
     • фокус в поле  → сцена отступает: движение замедляется, яркость падает,
                        внимание уходит на форму;
     • заявка ушла   → снизу вверх один раз проходит волна света.
   Отправку ловим по событию Contact Form 7 (wpcf7mailsent). Если плагин
   почему-то не отдаст событие — есть запасной путь через submit формы.
   ========================================================================= */
(function(){
  'use strict';
  var s11 = document.querySelector('.s11');
  if (!s11) return;

  /* --- фокус: сцена отступает, пока человек печатает --- */
  var fields = s11.querySelectorAll('input, textarea');
  var blurT = null;
  Array.prototype.forEach.call(fields, function(f){
    if (f.type === 'submit') return;
    f.addEventListener('focus', function(){
      clearTimeout(blurT);
      s11.classList.add('is-focus');
    });
    f.addEventListener('blur', function(){
      /* небольшая пауза: переход между полями не должен «моргать» сценой */
      clearTimeout(blurT);
      blurT = setTimeout(function(){
        if (!s11.querySelector(':focus')) s11.classList.remove('is-focus');
      }, 220);
    });
  });

  /* --- отправлено: волна света --- */
  var waveT = null;
  function wave(){
    s11.classList.remove('is-focus');
    s11.classList.remove('is-sent');
    void s11.offsetWidth;              /* перезапуск анимации */
    s11.classList.add('is-sent');
    clearTimeout(waveT);
    waveT = setTimeout(function(){ s11.classList.remove('is-sent'); }, 2200);
  }
  document.addEventListener('wpcf7mailsent', function(e){
    if (s11.contains(e.target) || !document.querySelector('.wpcf7')) wave();
  }, false);

  /* запасной путь, если события CF7 нет */
  var form = s11.querySelector('form');
  if (form) form.addEventListener('submit', function(){ setTimeout(wave, 600); });
})();
</script>

<script>
/* ============================================================================
   ФОН ФУТЕРА — сцена из второго блока
   ----------------------------------------------------------------------------
   Материал и логика взяты из блока услуг один в один:
     • пузырьки .bubble-img с тем же бликом и тем же подъёмом;
     • сферы .orb со стеклом и иконками услуг;
     • листья с тем же контуром.
   Что добавлено: фон темнее, пузырьков в разы больше, а сферы и листья
   расступаются перед курсором — та же математика отталкивания, что
   в блоке услуг (радиус 400px, плавное сглаживание 0.1 за кадр).
   ========================================================================= */
(function(){
  'use strict';
  var scene = document.getElementById('rv-scene');
  if (!scene) return;
  if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) return;

  var s11    = scene.parentNode;
  var farL   = document.getElementById('rv-far');
  var nearL  = document.getElementById('rv-near');
  var bubsL  = document.getElementById('rv-bubs');
  var MOBILE = window.matchMedia('(max-width: 900px)').matches;
  var HOVER  = window.matchMedia('(hover: hover) and (pointer: fine)').matches;

  /* иконки услуг — те же, что в карточках второго блока */
  var ICONS = {
    code: '<polyline points="16 18 22 12 16 6"/><polyline points="8 6 2 12 8 18"/><line x1="13.5" y1="4" x2="10.5" y2="20"/>',
    seo:  '<circle cx="10.5" cy="10.5" r="6.5"/><line x1="21" y1="21" x2="15.3" y2="15.3"/><polyline points="7 12 9.2 9.8 11 11.4 14 8.2"/>',
    tgt:  '<circle cx="12" cy="12" r="9"/><circle cx="12" cy="12" r="5"/><circle cx="12" cy="12" r="1.2" fill="currentColor"/><line x1="12" y1="1.5" x2="12" y2="4.5"/><line x1="12" y1="19.5" x2="12" y2="22.5"/><line x1="1.5" y1="12" x2="4.5" y2="12"/><line x1="19.5" y1="12" x2="22.5" y2="12"/>',
    pen:  '<path d="M12 19l7-7 3 3-7 7-3-3z"/><path d="M18 13l-1.5-7.5L2 2l3.5 14.5L13 18l5-5z"/><path d="M2 2l7.586 7.586"/><circle cx="11" cy="11" r="2"/>'
  };
  function svg(inner, stroke){
    return '<svg viewBox="0 0 24 24" fill="none" stroke="' + stroke + '" stroke-width="1.8" ' +
           'stroke-linecap="round" stroke-linejoin="round">' + inner + '</svg>';
  }
  var LEAF = '<svg viewBox="0 0 24 24" fill="none" stroke="rgba(255,255,255,.45)" stroke-width="1.2" ' +
             'stroke-linecap="round"><path d="M12 2C7 8 4 12 4 16a8 8 0 0 0 16 0c0-4-3-8-8-14z" opacity=".7"/>' +
             '<path d="M12 22V8"/></svg>';

  /* ---- боке: пятна вне фокуса. Их задача — не быть замеченными,
         а создать ощущение, что кадр снят объективом ---- */
  var bokL = document.getElementById('rv-bok');
  var BOKEH = MOBILE
    ? [[190,'18%','68%',0],[130,'70%','22%',1],[240,'46%','88%',0]]
    : [[260,'12%','62%',0],[180,'58%','84%',1],[320,'74%','48%',0],
       [150,'34%','30%',0],[210,'86%','76%',1],[120,'22%','12%',0]];
  BOKEH.forEach(function(b){
    var d = document.createElement('div');
    d.className = 'bokeh' + (b[3] ? ' bokeh--gold' : '');
    d.style.cssText = 'width:' + b[0] + 'px;height:' + b[0] + 'px;top:' + b[1] + ';left:' + b[2] + ';' +
                      'opacity:' + (.5 + Math.random() * .3).toFixed(2);
    bokL.appendChild(d);
  });

  /* ---- сферы ----
     Композиция вместо равномерной россыпи: центр интереса справа,
     там же, где стояла цветочная арка. Плотность падает влево,
     к заголовку. Формат: [размер, top%, left%, иконка, золотая, план] */
  var midL = document.getElementById('rv-mid');
  var PLANES = { far: farL, mid: midL, near: nearL };
  var ORBS = MOBILE ? [
    [104,'16%','74%','code',0,'mid'], [62,'56%','16%','seo',1,'far'],
    [132,'74%','62%','tgt',0,'mid'],  [180,'40%','92%','pen',0,'near']
  ] : [
    /* дальний план — мелкие, приглушённые */
    [78,'22%','52%','seo',0,'far'],  [56,'64%','36%','pen',1,'far'],
    [96,'12%','86%','tgt',0,'far'],  [64,'80%','70%','code',0,'far'],
    /* плоскость фокуса — главные объекты, справа */
    [168,'30%','68%','code',0,'mid'], [112,'62%','82%','seo',0,'mid'],
    [86,'18%','58%','tgt',1,'mid'],   [74,'76%','58%','pen',0,'mid'],
    /* ближний план — крупные, вне фокуса, у краёв кадра */
    [230,'52%','96%','code',0,'near'], [190,'84%','14%','tgt',1,'near']
  ];
  ORBS.forEach(function(o, i){
    var d = document.createElement('div');
    d.className = 'orb' + (o[4] ? ' orb--gold' : '');
    d.innerHTML = svg(ICONS[o[3]], o[4] ? 'rgba(245,197,66,.85)' : 'rgba(255,255,255,.75)');
    d.style.cssText = 'width:' + o[0] + 'px;height:' + o[0] + 'px;top:' + o[1] + ';left:' + o[2] + ';';
    d.dataset.rx = 0; d.dataset.ry = 0; d.dataset.ph = (i * 0.9);
    (PLANES[o[5]] || midL).appendChild(d);
  });

  /* ---- листья ---- */
  var LEAVES = MOBILE ? [[70,'16%','10%'],[92,'72%','78%']]
                      : [[86,'12%','22%'],[132,'44%','88%'],[74,'80%','12%'],[108,'86%','56%']];
  LEAVES.forEach(function(l, i){
    var d = document.createElement('div');
    d.className = 'leaf';
    d.innerHTML = LEAF;
    d.style.cssText = 'width:' + l[0] + 'px;height:' + l[0] + 'px;top:' + l[1] + ';left:' + l[2] + ';' +
                      'opacity:' + (.28 + (i % 2) * .12) + ';';
    d.dataset.rx = 0; d.dataset.ry = 0; d.dataset.ph = (i * 1.7);
    (i === 0 ? midL : i === 1 ? farL : i === 2 ? nearL : farL).appendChild(d);
  });

  /* ---- пузырьки ----
     Важно: сцена не должна начинаться пустой. Пузырёк летит 5–11 секунд
     снизу вверх, поэтому при обычном запуске первые секунды экран пустой —
     именно это и выглядело как «пузырьков нет».
     Решение: при первом показе засеваем поток отрицательной задержкой
     анимации, то есть пузырьки сразу находятся в середине пути. */
  var inView = false, timer = null, seeded = false;
  /* Поток пузырьков = столько же одновременно живущих композиторских слоёв.
     Замер на дросселе показал: они съедают около пятой части кадра при скролле
     по секции. На телефоне поток вдвое реже — визуально это те же пузырьки,
     просто чуть свободнее, а длинных кадров вдвое меньше. */
  var STEP = MOBILE ? 660 : 190;

  /* Пузырёк живёт в обёртке: подъём анимирует CSS на самом пузырьке,
     а отклонение от курсора (или от наклона телефона) задаётся обёртке.
     Две трансформации на разных элементах не конфликтуют — иначе одна
     затирала бы другую, и пузырьки просто не реагировали бы. */
  function bubble(preroll){
    var wrap = document.createElement('div');
    wrap.className = 'bubble-wrap';
    var z = Math.random() * (MOBILE ? 16 : 22) + 6;
    var dur = Math.random() * 6 + 5;
    wrap.style.cssText = 'left:' + (Math.random()*100).toFixed(1) + '%;';

    var b = document.createElement('div');
    b.className = 'bubble-img';
    b.style.cssText = 'width:' + z + 'px;height:' + z + 'px;' +
                      'opacity:' + (Math.random()*.4 + .2).toFixed(2) + ';' +
                      'animation:rvFootUp ' + dur.toFixed(1) + 's linear forwards' +
                      (preroll ? ';animation-delay:-' + (Math.random() * dur * .85).toFixed(1) + 's' : '');
    wrap.appendChild(b);
    bubsL.appendChild(wrap);
    setTimeout(function(){ wrap.remove(); }, (dur + 2) * 1000);
  }
  function spawn(){ if (inView && !document.hidden) bubble(false); }
  function seed(){
    if (seeded) return;
    seeded = true;
    var n = MOBILE ? 8 : 30;
    for (var i = 0; i < n; i++) bubble(true);
  }

  /* ---- курсор: сферы и листья расступаются, слои идут параллаксом ---- */
  var movers = scene.querySelectorAll('.orb, .leaf');
  var layers = scene.querySelectorAll('.rv-scene__layer[data-depth]');
  var mouse = { px: -1e4, py: -1e4, nx: 0, ny: 0 }, cur = { x: 0, y: 0 };
  var raf = null;

  function loop(){
    raf = null;
    if (!inView || document.hidden) return;
    var t = Date.now() * .001;

    /* прогресс прохода секции через экран: -1 (внизу) … 1 (вверху).
       Планы смещаются с разной скоростью — это второй источник глубины,
       он работает даже без мыши, в том числе на телефоне. */
    var rr = s11.getBoundingClientRect();
    var prog = 1 - (rr.top + rr.height / 2) / ((window.RVVH ? window.RVVH() : window.innerHeight) / 2 + rr.height / 2);
    prog = Math.max(-1, Math.min(1, prog));

    cur.x += (mouse.nx - cur.x) * .06;
    cur.y += (mouse.ny - cur.y) * .06;
    for (var k = 0; k < layers.length; k++){
      var L = layers[k];
      var dep = +L.dataset.depth || 0;
      var scr = +L.dataset.scroll || 0;
      L.style.transform = 'translate(' + (cur.x * dep).toFixed(1) + 'px,' +
                          (cur.y * dep + prog * scr).toFixed(1) + 'px)';
    }

    for (var i = 0; i < movers.length; i++){
      var el = movers[i];
      /* МОБАЙЛ: только покачивание. getBoundingClientRect вперемешку с записью
         transform заставляет браузер пересчитывать раскладку по нескольку раз
         за кадр — на iOS это и есть рывки при скролле. Курсора здесь всё равно
         нет, отталкиваться не от чего. */
      if (MOBILE){
        el.style.transform = 'translate3d(0,' +
          (Math.sin(t * .6 + (+el.dataset.ph || 0)) * 12).toFixed(1) + 'px,0)';
        continue;
      }
      var r = el.getBoundingClientRect();
      var dx = mouse.px - r.left - r.width / 2;
      var dy = mouse.py - r.top  - r.height / 2;
      var d  = Math.max(1, Math.hypot(dx, dy));
      var tx = 0, ty = 0;
      if (d < 400){                                  /* радиус как во втором блоке */
        var f = (400 - d) / 400;
        tx = dx / d * f * -70;
        ty = dy / d * f * -70;
      }
      var rx = +el.dataset.rx || 0, ry = +el.dataset.ry || 0;
      rx += (tx - rx) * .1;                          /* сглаживание как во втором блоке */
      ry += (ty - ry) * .1;
      el.dataset.rx = rx; el.dataset.ry = ry;
      var ph = +el.dataset.ph || 0;
      el.style.transform = 'translate(' + rx.toFixed(1) + 'px,' +
                           (ry + Math.sin(t * .6 + ph) * 12).toFixed(1) + 'px)';
    }

    /* Пузырьки тоже расступаются — от курсора на десктопе и от точки,
       которую задаёт наклон телефона. Радиус меньше, чем у сфер:
       мелкие объекты должны реагировать деликатнее. */
    if (!MOBILE && mouse.px > -1e3){
      var wraps = bubsL.children;
      for (var w = 0; w < wraps.length; w++){
        var el2 = wraps[w], kid = el2.firstChild;
        if (!kid) continue;

        /* Позицию берём у самого пузырька: обёртка стоит внизу и не двигается,
           подъём живёт на дочернем элементе. Ранее применённое смещение
           вычитаем, иначе оно попадёт обратно во вход и пузырёк уползёт. */
        var r2  = kid.getBoundingClientRect();
        var cx  = r2.left + r2.width  / 2 - (el2._ox || 0);
        var cy  = r2.top  + r2.height / 2 - (el2._oy || 0);
        var ddx = cx - mouse.px;
        var ddy = cy - mouse.py;
        var dd  = Math.hypot(ddx, ddy);

        if (dd < 160 && dd > 1){
          var ff = (160 - dd) / 160;
          el2._ox = ddx / dd * ff * 28;
          el2._oy = ddy / dd * ff * 20;
          el2.style.transform = 'translate(' + el2._ox.toFixed(1) + 'px,' +
                                               el2._oy.toFixed(1) + 'px)';
        } else if (el2._ox || el2._oy){
          el2._ox = el2._oy = 0;
          el2.style.transform = '';
        }
      }
    }
    raf = requestAnimationFrame(loop);
  }
  function start(){ if (!raf) raf = requestAnimationFrame(loop); }

  if (HOVER){
    s11.addEventListener('mousemove', function(e){
      var r = s11.getBoundingClientRect();
      mouse.px = e.clientX; mouse.py = e.clientY;
      mouse.nx = (e.clientX - r.left) / r.width  - .5;
      mouse.ny = (e.clientY - r.top)  / r.height - .5;
    });
    s11.addEventListener('mouseleave', function(){
      mouse.px = mouse.py = -1e4; mouse.nx = mouse.ny = 0;
    });
  }

  if ('IntersectionObserver' in window){
    new IntersectionObserver(function(es){
      inView = es[0].isIntersecting;
      if (inView){ start(); }            /* seed()/spawn сняты: пузырьки скрыты стилями */
      else if (timer){ clearInterval(timer); timer = null; }
    }, { threshold: 0, rootMargin: '200px 0px' }).observe(s11);
  } else { inView = true; start(); }   /* то же для браузеров без IntersectionObserver */


  /* ---- мобильный: наклон устройства вместо курсора ----
     На телефоне курсора нет, и сцена не реагировала ни на что.

     Ключевой момент: сферы и пузырьки расступаются от точки mouse.px/py.
     Если задать только смещение планов (mouse.nx/ny), поедет фон, а сами
     пузырьки останутся на месте. Поэтому наклон работает как ВИРТУАЛЬНЫЙ
     КУРСОР: точка ходит по секции вслед за наклоном, и сцена отзывается
     ровно так же, как на мышь.

     В iOS с 13-й версии на акселерометр нужно разрешение — спрашиваем его
     по первому касанию экрана, без модальных окон. */
  /* ===== РЕАКЦИЯ НА НАКЛОН ТЕЛЕФОНА — ВЫКЛЮЧЕНА =====
     iOS спрашивает разрешение на доступ к данным движения и ориентации,
     и системное окно вылезает поверх первого экрана. Для маркетингового
     сайта это выглядит подозрительно и отпугивает, а даёт всего лишь
     лёгкий сдвиг фоновых слоёв. Планы по-прежнему двигаются от скролла,
     глубина сцены сохраняется.
     Чтобы вернуть наклон, поставьте TILT = true. */
  var TILT = false;

  if (TILT && MOBILE && window.DeviceOrientationEvent){
    var tilt = { x: 0, y: 0 };
    function onTilt(e){
      if (e.gamma == null || e.beta == null) return;
      tilt.x = Math.max(-1, Math.min(1, e.gamma / 30));        /* влево-вправо */
      tilt.y = Math.max(-1, Math.min(1, (e.beta - 45) / 30));  /* вперёд-назад */

      mouse.nx = tilt.x * .5;                                  /* планы */
      mouse.ny = tilt.y * .5;
      /* «Виртуальный курсор» убран намеренно: он включал расступание сфер и
         пузырьков, а это чтение геометрии каждого элемента на каждом кадре.
         Наклон по-прежнему двигает планы — глубина сохраняется, цена нулевая. */
    }
    function enableTilt(){
      window.addEventListener('deviceorientation', onTilt, { passive: true });
      start();
    }
    if (typeof DeviceOrientationEvent.requestPermission === 'function'){
      document.addEventListener('touchend', function once(){
        document.removeEventListener('touchend', once);
        DeviceOrientationEvent.requestPermission()
          .then(function(state){ if (state === 'granted') enableTilt(); })
          .catch(function(){});
      }, { once: true, passive: true });
    } else {
      enableTilt();
    }
  }

  document.addEventListener('visibilitychange', function(){ if (!document.hidden && inView) start(); });
})();

/* =========================================================================
   ФУТЕР: БЛИК ПО НАДПИСИ REVIVE — ОДИН ПРОХОД ВМЕСТО ВЕЧНОГО ЦИКЛА
   -------------------------------------------------------------------------
   Надпись набрана градиентом с background-clip:text, а блик — это движение
   background-position. Такое не умеет композитор: каждый кадр перерисовывается
   вся площадь текста, а она во весь экран. На телефоне цикл крутился всегда,
   в том числе пока пользователь скроллит, — отсюда рывки внизу страницы.
   Теперь на узких экранах блик проходит один раз, когда футер появился в кадре.
   ========================================================================= */
(function(){
  'use strict';
  function init(){
    var brand = document.querySelector('.footer-brand');
    if (!brand) return;
    if (!window.matchMedia('(max-width: 1024px)').matches) return;
    if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) return;
    if (!('IntersectionObserver' in window)){ brand.classList.add('is-shine'); return; }
    var io = new IntersectionObserver(function(es){
      if (!es[0].isIntersecting) return;
      brand.classList.add('is-shine');
      io.disconnect();
    }, { threshold: .25 });
    io.observe(brand);
  }
  if (document.readyState === 'loading') document.addEventListener('DOMContentLoaded', init);
  else init();
})();
</script>

	<?php
	/* Стандартный цикл WordPress убран намеренно.
	   Он выводил содержимое страницы «Главная» — старый текст про ICE Agency,
	   который был спрятан правилом main{display:none}, но индексировался
	   поисковиками на английской версии сайта. Вся вёрстка главной — выше. */
	?>
<script>
  
  /* Слоганы второго блока: растут при приближении к центру экрана
     и уменьшаются при удалении от него */
  const lines = document.querySelectorAll('.intro__text .line');

  function updateLines(){
    const center = (window.RVVH ? window.RVVH() : window.innerHeight) / 2;
    lines.forEach(el => {
      const r = el.getBoundingClientRect();
      const d = Math.abs(r.top + r.height / 2 - center) / center; // 0 в центре, 1 у края
      const t = Math.max(0, 1 - d);                               // 1 в центре, 0 у края
      el.style.transform = `scale(${(0.72 + 0.38 * t).toFixed(3)})`; // 0.72 … 1.10
      el.style.opacity   = (0.12 + 0.88 * t).toFixed(3);
    });
  }

  let raf = null;
  function onScroll(){
    if (raf) return;
    raf = requestAnimationFrame(() => { updateLines(); raf = null; });
  }
  window.addEventListener('scroll', onScroll, { passive:true });
  window.addEventListener('resize', onScroll);
  updateLines();
</script>
<script>
/* Hero: cinematic background motion + счётчик 120+ */
(function () {
  'use strict';

  var reduced = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
  var fineDesktop = window.matchMedia(
    '(min-width: 768px) and (hover: hover) and (pointer: fine)'
  ).matches;

  var hero  = document.querySelector('.hero');
  var bg    = document.querySelector('.hero__bg');
  var glow  = document.querySelector('.rv-hero-glow');

  /* Параллакс и «глубина» фона — только на ПК с мышью. На телефоне видео
     стоит на месте: покадровый масштаб видеослоя и включение/выключение
     will-change при выходе из hero давали рывок на первом экране. */
  if (hero && bg && !reduced && fineDesktop) {
    var tx = 0, ty = 0, cx = 0, cy = 0;
    var lightTX = hero.clientWidth * 0.5;
    var lightTY = hero.clientHeight * 0.5;
    var lightX = lightTX, lightY = lightTY, lightActive = false;
    var baseScale = fineDesktop ? 1.075 : 1.045;
    var targetScale = baseScale, currentScale = baseScale;
    var targetScrollY = 0, currentScrollY = 0;
    var raf = 0, scrollDirty = true, inView = true;

    hero.classList.add('rv-hero-depth-ready');

    function clamp(value, min, max) {
      return Math.max(min, Math.min(max, value));
    }

    function scheduleHeroFrame() {
      if (!raf) raf = window.requestAnimationFrame(renderHeroFrame);
    }

    function renderHeroFrame() {
      raf = 0;

      if (scrollDirty) {
        var rect = hero.getBoundingClientRect();
        var progress = clamp(-rect.top / Math.max(rect.height, 1), 0, 1);
        targetScale = baseScale + progress * (fineDesktop ? 0.035 : 0.022);
        targetScrollY = -progress * (fineDesktop ? 10 : 7);
        scrollDirty = false;
      }

      cx += (tx - cx) * 0.075;
      cy += (ty - cy) * 0.075;
      lightX += (lightTX - lightX) * 0.11;
      lightY += (lightTY - lightY) * 0.11;
      currentScale += (targetScale - currentScale) * 0.075;
      currentScrollY += (targetScrollY - currentScrollY) * 0.075;

      bg.style.setProperty('--hero-photo-x', (-cx * 10).toFixed(2) + 'px');
      bg.style.setProperty('--hero-photo-y', (-cy * 8 + currentScrollY).toFixed(2) + 'px');
      bg.style.setProperty('--hero-photo-scale', currentScale.toFixed(4));
      if (glow) {
        glow.style.transform = 'translate3d(' + lightX.toFixed(1) + 'px,' +
          lightY.toFixed(1) + 'px,0) translate(-50%,-50%)';
      }

      if (
        Math.abs(tx - cx) > 0.001 ||
        Math.abs(ty - cy) > 0.001 ||
        Math.abs(lightTX - lightX) > 0.1 ||
        Math.abs(lightTY - lightY) > 0.1 ||
        Math.abs(targetScale - currentScale) > 0.0005 ||
        Math.abs(targetScrollY - currentScrollY) > 0.05
      ) {
        scheduleHeroFrame();
      }
    }

    function trackHeroPointer(e) {
      var rect = hero.getBoundingClientRect();
      tx = clamp(((e.clientX - rect.left) / rect.width) * 2 - 1, -1, 1);
      ty = clamp(((e.clientY - rect.top) / rect.height) * 2 - 1, -1, 1);
      lightTX = clamp(e.clientX - rect.left, 0, rect.width);
      lightTY = clamp(e.clientY - rect.top, 0, rect.height);
      lightActive = true;
      hero.classList.add('is-hero-lit');
      scheduleHeroFrame();
    }

    if (fineDesktop) {
      hero.addEventListener('pointerenter', trackHeroPointer, { passive: true });
      hero.addEventListener('pointermove', trackHeroPointer, { passive: true });

      hero.addEventListener('pointerleave', function () {
        tx = 0;
        ty = 0;
        lightActive = false;
        lightTX = hero.clientWidth * 0.5;
        lightTY = hero.clientHeight * 0.5;
        hero.classList.remove('is-hero-lit');
        scheduleHeroFrame();
      });
    }

    window.addEventListener('scroll', function () {
      if (!inView) return;
      scrollDirty = true;
      scheduleHeroFrame();
    }, { passive: true });

    window.addEventListener('resize', function () {
      scrollDirty = true;
      if (!lightActive) {
        lightTX = hero.clientWidth * 0.5;
        lightTY = hero.clientHeight * 0.5;
      }
      scheduleHeroFrame();
    });

    if ('IntersectionObserver' in window) {
      new IntersectionObserver(function (entries) {
        inView = entries[0].isIntersecting;
        if (inView) {
          hero.classList.add('rv-hero-depth-ready');
          scrollDirty = true;
          scheduleHeroFrame();
        } else {
          hero.classList.remove('rv-hero-depth-ready');
        }
      }, { rootMargin: '10% 0px' }).observe(hero);
    }

    scheduleHeroFrame();
  }

  var num = document.querySelector('.hero__stats-num');
  if (num && !reduced) {
    var raw    = num.textContent;
    var target = parseInt(raw, 10) || 120;
    var suffix = raw.replace(/[0-9\s]/g, '');
    num.textContent = '0' + suffix;

    var counterDelay = document.documentElement.classList.contains('rv-hero-returning') ? 320 : 1080;
    setTimeout(function () {
      var t0 = null;
      function step(ts) {
        if (!t0) t0 = ts;
        var p = Math.min((ts - t0) / 1300, 1);
        var eased = 1 - Math.pow(1 - p, 3);
        num.textContent = Math.round(eased * target) + suffix;
        if (p < 1) requestAnimationFrame(step);
      }
      requestAnimationFrame(step);
    }, counterDelay);
  }
})();
</script>

<!-- ============================================================
     МИКРОАНИМАЦИИ: построчные заголовки, кастомный курсор,
     liquid-кнопки. Длительности и кривые — из токенов --rv-* .
     ============================================================ -->
<script>
(function(){
  var reduced = matchMedia('(prefers-reduced-motion: reduce)').matches;
  var fine    = matchMedia('(hover: hover) and (pointer: fine)').matches;
  var G       = window.gsap || null;

  /* ==========================================================
     1. ЗАГОЛОВКИ ПОСТРОЧНО
     ========================================================== */
  var TITLES = ['.s2__text-title','.s3__text-title','.s4__text-title',
                '.s5__text-title','.s8__text-title','.s9__text-title',
                '.s11__text-title','.main-title','.rv-reviews__title'];
  var BR = '⁣BR⁣';

  function splitLines(el){
    if (el.dataset.rvSplit) return;
    var ok = [].every.call(el.childNodes, function(n){
      if (n.nodeType === 3) return true;
      if (n.nodeType !== 1) return false;
      if (n.tagName === 'BR') return true;
      /* <em> с золотым контуром переживает разбивку, если внутри одно слово */
      return n.tagName === 'EM' && !/\s/.test(n.textContent);
    });
    if (!ok || !el.textContent.trim()) return;

    if (!el.dataset.rvHtml) el.dataset.rvHtml = el.innerHTML;

    var words = el.dataset.rvHtml
                  .replace(/<br\s*\/?>/gi, ' ' + BR + ' ')
                  .split(/\s+/).filter(Boolean);
    el.innerHTML = words.map(function(w){
      return w === BR ? '<br>' : '<i>' + w + '</i>';
    }).join(' ');

    var lines = [], cur = null, top = null;
    [].forEach.call(el.querySelectorAll('i'), function(i){
      var t = i.offsetTop;
      if (cur === null || Math.abs(t - top) > 4){ cur = []; lines.push(cur); top = t; }
      cur.push(i.innerHTML);
    });
    if (!lines.length){ el.innerHTML = el.dataset.rvHtml; return; }

    el.innerHTML = lines.map(function(l){
      return '<span class="rv-line"><span>' + l.join(' ') + '</span></span>';
    }).join('');
    el.classList.add('rv-split');
    el.dataset.rvSplit = '1';
  }

  /* Разбивка на строки — только для широких экранов.
     На телефоне маска прячет заголовок целиком, и до срабатывания
     наблюдателя блок грузится с пустой дырой на 120-170px.
     Смотрится как сломанная вёрстка, поэтому там оставляем
     обычный текст без анимации. */
  var RV_NARROW = matchMedia('(max-width: 1024px)').matches;

  /* ==========================================================
     1b. ЗОЛОТОЙ КОНТУР НА ПОСЛЕДНЕМ СЛОВЕ
     ----------------------------------------------------------
     Единый приём для всех заголовков — как «ОБРАНІ роботи»
     в портфолио: слово-хвост становится контурным золотом.
     Тексты приходят из ACF, поэтому размечаем их здесь, а не
     руками в админке. Заголовок, где <em> проставлен вручную,
     не трогаем — ручная разметка главнее.
     ========================================================== */
  var OUTLINE = TITLES.concat(['.s6__text-title','.s7__text-title']);

  function outlineLast(el){
    if (el.dataset.rvOut || el.querySelector('em')) { el.dataset.rvOut = '1'; return; }
    /* только простое содержимое: текст и переносы */
    var ok = [].every.call(el.childNodes, function(n){
      return n.nodeType === 3 || (n.nodeType === 1 && n.tagName === 'BR');
    });
    if (!ok) return;

    var html = el.innerHTML.replace(/(\s|&nbsp;|<br\s*\/?>)+$/i, '');
    /* на узких экранах короткие слова не должны висеть в конце строки:
       «реклама в / соцмережах» -> «реклама / в соцмережах» */
    if (matchMedia('(max-width: 1024px)').matches){
      html = html.replace(/(^|[\s>])([A-Za-zА-Яа-яЇїІіЄєҐґ]{1,2})[ \t]+(?=[^\s<])/g, '$1$2\u00A0');
    }
    var m = html.match(/([^\s<>&;]{2,})$/);           /* последнее слово */
    if (!m) return;
    /* заголовок из одного слова оставляем как есть — иначе он весь контурный.
       Проверяем именно видимый текст: перед словом обычно стоят отступы вёрстки */
    var head = html.slice(0, m.index);
    if (!head.replace(/<[^>]*>/g, '').trim()) return;

    /* именно <em> без атрибутов: разбивка на строки режет разметку по пробелам,
       и тег с атрибутом внутри неё развалился бы пополам */
    el.dataset.rvHtml = head + '<em>' + m[1] + '</em>';
    el.innerHTML = el.dataset.rvHtml;
    el.dataset.rvOut = '1';
  }

  [].forEach.call(document.querySelectorAll(OUTLINE.join(',')), outlineLast);

  function initTitles(){
    if (RV_NARROW) return;

    var els = document.querySelectorAll(TITLES.join(','));
    if (!els.length) return;
    [].forEach.call(els, splitLines);

    if (reduced || !('IntersectionObserver' in window)){
      [].forEach.call(els, function(e){ e.classList.add('rv-in'); });
      return;
    }
    var io = new IntersectionObserver(function(ent){
      ent.forEach(function(e){
        if (e.isIntersecting){ e.target.classList.add('rv-in'); io.unobserve(e.target); }
      });
    }, { threshold: .15, rootMargin: '0px 0px -8% 0px' });
    [].forEach.call(els, function(e){ io.observe(e); });

    /* Страховка. Если наблюдатель по любой причине не сработал,
       заголовок обязан появиться сам — текст никогда не должен
       остаться навсегда спрятанным под маской. */
    setTimeout(function(){
      [].forEach.call(els, function(e){
        var r = e.getBoundingClientRect();
        if (r.top < innerHeight && r.bottom > 0) e.classList.add('rv-in');
      });
    }, 1500);
  }

  var rt, rvLastW = innerWidth;
  addEventListener('resize', function(){
    /* На телефоне resize прилетает каждый раз, когда прячется или
       выезжает адресная строка, — то есть постоянно во время скролла.
       Пересобирать строки нужно только если реально сменилась ширина,
       иначе заголовки дёргаются прямо под пальцем. */
    if (innerWidth === rvLastW) return;
    rvLastW = innerWidth;
    clearTimeout(rt);
    rt = setTimeout(function(){
      [].forEach.call(document.querySelectorAll('.rv-split'), function(e){
        e.innerHTML = e.dataset.rvHtml;
        e.classList.remove('rv-split','rv-in');
        delete e.dataset.rvSplit;
      });
      initTitles();
      [].forEach.call(document.querySelectorAll('.rv-split'), function(e){ e.classList.add('rv-in'); });
    }, 250);
  });

  /* ==========================================================
     2. МАГНИТНОЕ СМЕЩЕНИЕ КНОПОК
     Кастомный курсор удалён — остались только сами кнопки,
     которые слегка тянутся за указателем.
     ========================================================== */
  var MAG_SEL = '.s6__link, .hero__buttons a, input.wpcf7-submit, .nav-arrow';

  function initMagnetic(){
    if (!fine || reduced) return;
    [].forEach.call(document.querySelectorAll(MAG_SEL), function(b){
      var R = 90, K = .26;
      b.addEventListener('mousemove', function(e){
        var r  = b.getBoundingClientRect(),
            ox = e.clientX - (r.left + r.width  / 2),
            oy = e.clientY - (r.top  + r.height / 2);
        if (Math.hypot(ox, oy) > R + Math.max(r.width, r.height) / 2) return reset();
        if (G) G.to(b, { x: ox * K, y: oy * K, duration: .25, ease: 'power3.out', overwrite: 'auto' });
        else   b.style.transform = 'translate(' + ox * K + 'px,' + oy * K + 'px)';
      });
      function reset(){
        if (G) G.to(b, { x: 0, y: 0, duration: .5, ease: 'elastic.out(1,.5)', overwrite: 'auto' });
        else   b.style.transform = '';
      }
      b.addEventListener('mouseleave', reset);
    });
  }

  /* ==========================================================
     4. LIQUID-КНОПКИ — GSAP timeline: mask + scale + rotation
     ========================================================== */
  var BTN_SEL = '.s6__link, .hero__buttons a, input.wpcf7-submit';

  function wrapButton(b){
    if (b.dataset.rvBtn) return null;
    /* <input> обернуть нельзя — для него делаем только glow */
    if (b.tagName === 'INPUT'){ b.classList.add('rv-btn'); b.dataset.rvBtn = '1'; return b; }

    var fill  = document.createElement('span'); fill.className  = 'rv-btn__fill';
    var label = document.createElement('span'); label.className = 'rv-btn__label';
    while (b.firstChild) label.appendChild(b.firstChild);
    b.appendChild(fill);
    b.appendChild(label);
    b.classList.add('rv-btn');
    b.dataset.rvBtn = '1';
    return b;
  }

  function initLiquid(){
    /* на тач-устройствах mouseenter приходит от тапа, а mouseleave
       может не прийти вовсе — кнопка залипает увеличенной и залитой */
    if (reduced || !fine) return;
    [].forEach.call(document.querySelectorAll(BTN_SEL), function(b){
      if (!wrapButton(b)) return;

      var fill  = b.querySelector('.rv-btn__fill'),
          label = b.querySelector('.rv-btn__label'),
          ls0   = label ? (getComputedStyle(label).letterSpacing === 'normal'
                            ? '0px' : getComputedStyle(label).letterSpacing) : '0px',
          tl    = null;

      function pt(e){
        var r = b.getBoundingClientRect();
        return {
          x: ((e.clientX - r.left) / r.width  * 100).toFixed(1) + '%',
          y: ((e.clientY - r.top ) / r.height * 100).toFixed(1) + '%'
        };
      }

      b.addEventListener('mouseenter', function(e){
        var p = pt(e);
        b.style.setProperty('--rv-x', p.x);
        b.style.setProperty('--rv-y', p.y);
        if (!G) return;

        tl && tl.kill();
        tl = G.timeline({ defaults: { ease: 'power3.out' } })
          .to(b, { scale: 1.045, rotate: -.7, duration: .25 }, 0)
          .to(b, { boxShadow: '0 20px 50px rgba(245,197,66,.45), 0 0 0 1px rgba(245,197,66,.35)',
                   duration: .25 }, 0);
        if (fill) tl.fromTo(fill,
                  { clipPath: 'circle(0% at ' + p.x + ' ' + p.y + ')' },
                  { clipPath: 'circle(150% at ' + p.x + ' ' + p.y + ')', duration: .55 }, 0);
        if (label) tl.to(label, { y: -3, letterSpacing: '.16em', duration: .25 }, 0);
      });

      b.addEventListener('mouseleave', function(e){
        var p = pt(e);   /* схлопывается туда, где курсор вышел */
        if (!G) return;
        tl && tl.kill();
        tl = G.timeline({ defaults: { ease: 'power2.inOut' } })
          .to(b, { scale: 1, rotate: 0, duration: .35 }, 0)
          .to(b, { boxShadow: '0 0 0 rgba(245,197,66,0)', duration: .35 }, 0);
        if (fill)  tl.to(fill,  { clipPath: 'circle(0% at ' + p.x + ' ' + p.y + ')', duration: .45 }, 0);
        if (label) tl.to(label, { y: 0, letterSpacing: ls0, duration: .3 }, 0);
      });
    });
  }

  function boot(){
    /* ждём шрифты: до их загрузки метрики текста другие и переносы
       строк посчитаются не там, где окажутся в итоге */
    if (document.fonts && document.fonts.ready) {
      document.fonts.ready.then(initTitles);
    } else {
      initTitles();
    }
    initMagnetic();
    initLiquid();
  }
  if (document.readyState === 'loading') document.addEventListener('DOMContentLoaded', boot);
  else boot();
})();
</script>
<script>
/* Единое премиальное появление контента. Анимируются контейнеры, а не сами
   PNG/GIF: так вход не спорит с их постоянным плаванием и слайдерами. */
(function(){
  'use strict';

  /* ACF/визуальный редактор иногда сохраняет пустые <p> и &nbsp;.
     Они не несут контента, но создают заметные вертикальные провалы. */
  [].forEach.call(document.querySelectorAll(
    '.s3 p, .s4 p, .s5 p, .s8 p, .s9 p, .s10 p, .s11 p'
  ), function(p){
    var text = (p.textContent || '').replace(/\u00a0/g, '').trim();
    var useful = p.querySelector('img, picture, video, iframe, svg, a, button, input, textarea, select');
    if (!text && !useful) p.remove();
  });

  var groups = [
    { root: '.s5', items: ['.s5__inner', '.s5__abs-img'] },
    { root: 'section.s3:not(.crm)', items: ['.wrapper > *', '.s3__abs'] },
    { root: '.s4', items: ['.s4__left', '.s4__right'] },
    { root: 'section.s3.crm', items: ['.s3__text', '.s3__abs'] },
    { root: '.s8', items: ['.wrapper > *', '.s8__ads-img'] },
    { root: '.s9', items: ['.s9__text-title', '.s9__list'] },
    /* .rv-numbers управляет своим каскадом и счётчиками самостоятельно. */
    /* .rv-cine ведёт своё появление сам (класс is-in), общий reveal ему не нужен */
    { root: '.rv-reviews', items: ['.rv-reviews__head', '.rv-reviews__stage-box'] },
    { root: '.s10', items: ['.s10__text', '.s10__img2'] },
    { root: '.s11', items: ['.s11__text', '.s11__form'] }
  ];
  var sections = [];
  var elements = [];

  groups.forEach(function(group){
    var root = document.querySelector(group.root);
    if (!root) return;
    sections.push(root);
    var index = 0;
    group.items.forEach(function(selector){
      [].forEach.call(root.querySelectorAll(selector), function(el){
        if (elements.indexOf(el) !== -1) return;
        el.classList.add('rv-premium-reveal');
        el.style.setProperty('--rv-reveal-delay', Math.min(index * 90, 270) + 'ms');
        elements.push(el);
        index += 1;
      });
    });
  });

  if (!elements.length) return;
  var reduced = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
  if (reduced || !('IntersectionObserver' in window)){
    sections.forEach(function(section){ section.classList.add('rv-seen'); });
    elements.forEach(function(el){ el.classList.add('rv-premium-in'); });
    return;
  }

  document.documentElement.classList.add('rv-motion-ready');
  var io = new IntersectionObserver(function(entries){
    entries.forEach(function(entry){
      if (!entry.isIntersecting) return;
      entry.target.classList.add('rv-premium-in');
      var section = entry.target.closest('section');
      if (section) section.classList.add('rv-seen');
      io.unobserve(entry.target);
    });
  }, { threshold: .1, rootMargin: '0px 0px -8% 0px' });
  elements.forEach(function(el){ io.observe(el); });

  /* Страховка от пустого контента при нестандартном браузере/вебвью. */
  setTimeout(function(){
    elements.forEach(function(el){
      var rect = el.getBoundingClientRect();
      if (rect.top < innerHeight && rect.bottom > 0){
        el.classList.add('rv-premium-in');
        var section = el.closest('section');
        if (section) section.classList.add('rv-seen');
      }
    });
  }, 1400);
})();
</script>

<!-- Постоянная scroll-стрелка: вниз в начале страницы, вверх у самого низа. -->
<button class="rv-to-top rv-scroll-cue is-visible" id="rv-to-top" type="button"
        aria-label="<?php echo pll_current_language() == 'en' ? 'Scroll to the bottom' : 'Прокрутити донизу'; ?>"
        title="<?php echo pll_current_language() == 'en' ? 'Scroll to the bottom' : 'Прокрутити донизу'; ?>"
        aria-hidden="false" tabindex="0">
    <svg viewBox="0 0 24 24" aria-hidden="true" focusable="false">
        <path d="M12 19V5M6.5 10.5 12 5l5.5 5.5"/>
    </svg>
</button>
<script>
(function(){
  'use strict';
  var button = document.getElementById('rv-to-top');
  if (!button) return;

  var reduced = window.matchMedia &&
                window.matchMedia('(prefers-reduced-motion: reduce)').matches;
  var ticking = false;
	var isEnglish = (document.documentElement.lang || '').toLowerCase().indexOf('en') === 0;

	function isAtBottom(){
		var docHeight = Math.max(document.documentElement.scrollHeight, document.body.scrollHeight);
		return window.pageYOffset + (window.RVVH ? window.RVVH() : window.innerHeight) >= docHeight - Math.max(96, (window.RVVH ? window.RVVH() : window.innerHeight) * .16);
	}

  function update(){
    ticking = false;
		var atBottom = isAtBottom();
		var downLabel = isEnglish ? 'Scroll to the bottom' : 'Прокрутити донизу';
		var upLabel = isEnglish ? 'Back to top' : 'Повернутися нагору';
		button.classList.add('is-visible');
		button.classList.toggle('rv-scroll-cue', !atBottom);
		button.classList.toggle('rv-scroll-end', atBottom);
		button.setAttribute('aria-hidden', 'false');
		button.setAttribute('aria-label', atBottom ? upLabel : downLabel);
		button.setAttribute('title', atBottom ? upLabel : downLabel);
		button.tabIndex = 0;
  }

  window.addEventListener('scroll', function(){
    if (ticking) return;
    ticking = true;
    window.requestAnimationFrame(update);
  }, { passive:true });
  window.addEventListener('resize', function(){ update(); }, { passive:true });

  button.addEventListener('click', function(){
		if (!button.classList.contains('rv-scroll-end')) {
			var docHeight = Math.max(document.documentElement.scrollHeight, document.body.scrollHeight);
			window.scrollTo({ top:docHeight, left:0, behavior:reduced ? 'auto' : 'smooth' });
			return;
		}
		window.scrollTo({ top:0, left:0, behavior:reduced ? 'auto' : 'smooth' });
  });

  update();
})();
</script>

<!-- Спокойные микроанимации интерфейса: метки, якорное меню и поля форм. -->
<script>
(function(){
  'use strict';

  var reduced = window.matchMedia &&
                window.matchMedia('(prefers-reduced-motion: reduce)').matches;

  /* Индикаторы аудита анимируются именно при появлении в viewport,
     а не в момент загрузки страницы, пока секция ещё далеко ниже экрана. */
  var auditPreview = document.querySelector('.rv-audit');
  if (auditPreview) {
    if (reduced || !('IntersectionObserver' in window)) {
      auditPreview.classList.add('is-preview-visible');
    } else {
      var auditPreviewObserver = new IntersectionObserver(function(entries){
        entries.forEach(function(entry){
          if (!entry.isIntersecting) return;
          entry.target.classList.add('is-preview-visible');
          auditPreviewObserver.unobserve(entry.target);
        });
      }, { threshold:.2, rootMargin:'0px 0px -8% 0px' });
      auditPreviewObserver.observe(auditPreview);
    }
  }

  /* Метка секции появляется один раз; постоянного пульса намеренно нет. */
  var chips = document.querySelectorAll(
    '.rv-audit__chip,.rv-proc__chip,.rv-cine__chip,' +
    '.rv-quiz__chip,.rv-reviews__chip,.rv-faq__chip'
  );
  Array.prototype.forEach.call(chips, function(chip){
    chip.classList.add('rv-micro-chip');
  });

  if (reduced || !('IntersectionObserver' in window)) {
    Array.prototype.forEach.call(chips, function(chip){ chip.classList.add('rv-micro-in'); });
  } else {
    document.documentElement.classList.add('rv-micro-ready');
    var chipObserver = new IntersectionObserver(function(entries){
      entries.forEach(function(entry){
        if (!entry.isIntersecting) return;
        entry.target.classList.add('rv-micro-in');
        chipObserver.unobserve(entry.target);
      });
    }, { threshold:.35, rootMargin:'0px 0px -8% 0px' });
    Array.prototype.forEach.call(chips, function(chip){ chipObserver.observe(chip); });
    window.setTimeout(function(){
      Array.prototype.forEach.call(chips, function(chip){
        var rect = chip.getBoundingClientRect();
        if (rect.top < (window.RVVH ? window.RVVH() : window.innerHeight) && rect.bottom > 0) chip.classList.add('rv-micro-in');
      });
    }, 1400);
  }

  /* Текущий смысловой раздел отмечается в основном и мобильном меню. */
  var navIds = ['services','build','grow','scale','audit','rv-cine','s11'];
  var sections = navIds.map(function(id){ return document.getElementById(id); }).filter(Boolean);
  var navLinks = Array.prototype.filter.call(document.querySelectorAll(
    '.main-navigation a[href*="#"],#rmp-menu-wrap-542 a.rmp-menu-item-link[href*="#"]'
  ), function(link){
    try {
      var hash = new URL(link.href, window.location.href).hash.slice(1);
      if (navIds.indexOf(hash) === -1) return false;
      link.dataset.rvNavTarget = hash;
      link.classList.add('rv-nav-track');
      return true;
    } catch (e) { return false; }
  });
  var navTicking = false;

  function setCurrentNav(id){
    navLinks.forEach(function(link){
      var current = link.dataset.rvNavTarget === id;
      link.classList.toggle('rv-nav-current', current);
      if (current) link.setAttribute('aria-current','location');
      else link.removeAttribute('aria-current');
    });
  }

  function updateCurrentNav(){
    navTicking = false;
    if (!sections.length || !navLinks.length) return;
    var probe = Math.max(110, (window.RVVH ? window.RVVH() : window.innerHeight) * .38);
    var current = sections[0];
    sections.forEach(function(section){
      var rect = section.getBoundingClientRect();
      if (rect.top <= probe) current = section;
    });
    setCurrentNav(current.id);
  }

  function collectLateNavLinks(){
    var candidates = document.querySelectorAll(
      '.main-navigation a[href*="#"],#rmp-menu-wrap-542 a.rmp-menu-item-link[href*="#"]'
    );
    Array.prototype.forEach.call(candidates, function(link){
      if (navLinks.indexOf(link) !== -1) return;
      try {
        var hash = new URL(link.href, window.location.href).hash.slice(1);
        if (navIds.indexOf(hash) === -1) return;
        link.dataset.rvNavTarget = hash;
        link.classList.add('rv-nav-track');
        link.addEventListener('click', function(){ setCurrentNav(link.dataset.rvNavTarget); });
        navLinks.push(link);
      } catch (e) {}
    });
    updateCurrentNav();
  }

  if (sections.length) {
    window.addEventListener('scroll', function(){
      if (navTicking) return;
      navTicking = true;
      window.requestAnimationFrame(updateCurrentNav);
    }, { passive:true });
    window.addEventListener('resize', updateCurrentNav, { passive:true });
    navLinks.forEach(function(link){
      link.addEventListener('click', function(){ setCurrentNav(link.dataset.rvNavTarget); });
    });
    updateCurrentNav();
    document.addEventListener('DOMContentLoaded', collectLateNavLinks, { once:true });
    window.setTimeout(collectLateNavLinks, 900);
  }

  /* Золотая линия фокуса и галочка после корректного заполнения. */
  var fields = document.querySelectorAll(
    '.rv-audit__input,.rv-quiz__in,' +
    '.s11 input:not([type="submit"]):not([type="button"]):not([type="hidden"]):not([type="checkbox"]):not([type="radio"]),' +
    '.s11 textarea'
  );
  function updateField(field){
    var value = (field.value || '').trim();
    var valid = value !== '' && field.checkValidity() && field.getAttribute('aria-invalid') !== 'true';
    field.classList.toggle('rv-field-valid', valid);
  }
  Array.prototype.forEach.call(fields, function(field){
    field.classList.add('rv-micro-field');
    field.addEventListener('input', function(){ updateField(field); });
    field.addEventListener('change', function(){ updateField(field); });
    field.addEventListener('blur', function(){ updateField(field); });
    updateField(field);
  });
})();
</script>


<!-- ===== REVIVE: desktop hybrid navigation ===== -->
<script>
(function(){
  'use strict';

  var mq = window.matchMedia('(min-width: 993px)');
  var body = document.body;
  var header = document.getElementById('masthead');
  if (!body || !header) return;

  var inner = header.querySelector('.header__inner');
  if (!inner || document.querySelector('.rv-desktop-actions')) return;

  var isEn = (document.documentElement.lang || '').toLowerCase().indexOf('en') === 0;
  var labels = isEn ? {
    work: 'Work', services: 'Services', start: "Let's start", menu: 'Open menu', close: 'Close',
    brand: 'Revive Agency / Navigation', eyebrow: 'Digital agency',
    copy: 'Design, development and marketing systems built to move brands forward.',
    signature: 'Design · Development · Marketing',
    items: [
      ['Services & Pricing','#services'], ['Build','#build'], ['Grow','#grow'], ['Scale','#scale'],
      ['Free Audit','#audit'], ['Portfolio','#rv-cine'], ['Contact','#s11']
    ]
  } : {
    work: 'Роботи', services: 'Послуги', start: 'Почати', menu: 'Відкрити меню', close: 'Закрити',
    brand: 'Revive Agency / Навігація', eyebrow: 'Digital agency',
    copy: 'Дизайн, розробка та маркетингові системи, що рухають бренди вперед.',
    signature: 'Дизайн · Розробка · Маркетинг',
    items: [
      ['Послуги та ціни','#services'], ['Створюємо','#build'], ['Просуваємо','#grow'], ['Масштабуємо','#scale'],
      ['Безкоштовний аудит','#audit'], ['Портфоліо','#rv-cine'], ['Контакти','#s11']
    ]
  };

  function make(tag, cls, attrs){
    var el = document.createElement(tag);
    if (cls) el.className = cls;
    if (attrs) Object.keys(attrs).forEach(function(k){
      if (k === 'text') el.textContent = attrs[k];
      else el.setAttribute(k, attrs[k]);
    });
    return el;
  }

  var lang = inner.querySelector('ul.ice-lang');
  var actions = make('div','rv-desktop-actions');
  var work = make('a','rv-desktop-navlink', {href:'#rv-cine', text:labels.work});
  work.dataset.hybridTarget = 'rv-cine';
  var services = make('a','rv-desktop-navlink', {href:'#services', text:labels.services});
  services.dataset.hybridTarget = 'services';
  var cta = make('a','rv-desktop-cta', {href:'#s11', text:labels.start});

  /* Compact desktop language switcher. Intentionally text-only: the CTA is
     already a pill and the burger is already a circle, so a third framed
     control would make the header visually busy. */
  var desktopLang = make('div','rv-desktop-lang', {
    'aria-label': isEn ? 'Language switcher' : 'Перемикач мови'
  });
  if (lang) {
    var languageLinks = lang.querySelectorAll('a');
    Array.prototype.forEach.call(languageLinks, function(source, index){
      var clone = source.cloneNode(false);
      clone.removeAttribute('class');
      var li = source.closest('li');
      var hreflang = (source.getAttribute('hreflang') || (li && li.getAttribute('lang')) || '').toLowerCase();
      var text = (source.textContent || '').trim().toLowerCase();
      var href = (source.getAttribute('href') || '').toLowerCase();
      var isUa = /(^|[-_])(uk|ua)([-_]|$)/.test(hreflang) || text === 'uk' || text === 'ua' || href.indexOf('/uk/') !== -1;
      clone.textContent = isUa ? 'UA' : 'EN';
      if (li && li.classList.contains('current-lang')) {
        clone.classList.add('is-current');
        clone.setAttribute('aria-current','page');
      }
      desktopLang.appendChild(clone);
      if (index < languageLinks.length - 1) {
        desktopLang.appendChild(make('span','rv-desktop-lang__sep',{text:'/','aria-hidden':'true'}));
      }
    });
  }

  var burger = make('button','rv-desktop-burger', {
    type:'button', 'aria-label':labels.menu, 'aria-expanded':'false', 'aria-controls':'rv-desktop-menu'
  });
  burger.appendChild(document.createElement('span'));

  actions.appendChild(services);
  actions.appendChild(work);
  actions.appendChild(cta);
  if (desktopLang.children.length) actions.appendChild(desktopLang);
  actions.appendChild(burger);

  inner.insertBefore(actions, lang || null);

  var overlay = make('div','rv-desktop-menu', {id:'rv-desktop-menu', 'aria-hidden':'true'});
  var top = make('div','rv-desktop-menu__top');
  top.appendChild(make('div','rv-desktop-menu__brand',{text:labels.brand}));
  var close = make('button','rv-desktop-menu__close',{type:'button','aria-label':labels.close});
  close.appendChild(document.createTextNode(labels.close));
  var closeIcon = document.createElement('i'); closeIcon.setAttribute('aria-hidden','true'); close.appendChild(closeIcon);
  top.appendChild(close);

  var menuBody = make('div','rv-desktop-menu__body');
  var nav = make('nav','rv-desktop-menu__nav',{'aria-label':isEn ? 'Main navigation' : 'Головна навігація'});
  labels.items.forEach(function(item, index){
    var a = make('a','rv-desktop-menu__item',{href:item[1]});
    a.appendChild(make('span','rv-desktop-menu__index',{text:String(index + 1).padStart(2,'0')}));
    a.appendChild(make('span','rv-desktop-menu__label',{text:item[0]}));
    a.appendChild(make('span','rv-desktop-menu__arrow',{text:'↗︎','aria-hidden':'true'}));
    nav.appendChild(a);
  });
  menuBody.appendChild(nav);

  var aside = make('aside','rv-desktop-menu__aside');
  aside.appendChild(make('p','rv-desktop-menu__eyebrow',{text:labels.eyebrow}));
  aside.appendChild(make('p','rv-desktop-menu__aside-copy',{text:labels.copy}));
  menuBody.appendChild(aside);

  var foot = make('div','rv-desktop-menu__foot');
  var langBox = make('div','rv-desktop-menu__lang');
  if (lang) {
    Array.prototype.forEach.call(lang.querySelectorAll('a'), function(source){
      var clone = source.cloneNode(false);
      clone.removeAttribute('class');
      var itemText = (source.textContent || source.getAttribute('title') || '').trim();
      var img = source.querySelector('img');
      var alt = img ? (img.getAttribute('alt') || '') : '';
      var token = (itemText || alt || source.getAttribute('hreflang') || '').trim();
      if (!token) token = source.href.indexOf('/uk') !== -1 ? 'UA' : 'EN';
      clone.textContent = token.toUpperCase().slice(0,3);
      if (source.closest('.current-lang')) clone.classList.add('is-current');
      langBox.appendChild(clone);
    });
  }
  foot.appendChild(langBox);
  foot.appendChild(make('div','rv-desktop-menu__signature',{text:labels.signature}));

  overlay.appendChild(top);
  overlay.appendChild(menuBody);
  overlay.appendChild(foot);
  document.body.appendChild(overlay);
  body.classList.add('rv-hybrid-ready');

  var lastFocus = null;
  function focusables(){
    return Array.prototype.slice.call(overlay.querySelectorAll('a[href],button:not([disabled])'));
  }
  function openMenu(){
    if (!mq.matches) return;
    lastFocus = document.activeElement;
    body.classList.add('rv-desktop-menu-open');
    burger.setAttribute('aria-expanded','true');
    burger.setAttribute('aria-label',labels.close);
    overlay.setAttribute('aria-hidden','false');
    window.setTimeout(function(){ close.focus(); }, 50);
  }
  function closeMenu(restore){
    body.classList.remove('rv-desktop-menu-open');
    burger.setAttribute('aria-expanded','false');
    burger.setAttribute('aria-label',labels.menu);
    overlay.setAttribute('aria-hidden','true');
    if (restore && lastFocus && typeof lastFocus.focus === 'function') lastFocus.focus();
  }

  burger.addEventListener('click', function(){
    if (body.classList.contains('rv-desktop-menu-open')) closeMenu(true); else openMenu();
  });
  close.addEventListener('click', function(){ closeMenu(true); });
  overlay.addEventListener('click', function(e){
    var link = e.target.closest('a[href]');
    if (link && link.getAttribute('href').charAt(0) === '#') closeMenu(false);
  });

  document.addEventListener('keydown', function(e){
    if (!body.classList.contains('rv-desktop-menu-open')) return;
    if (e.key === 'Escape') { e.preventDefault(); closeMenu(true); return; }
    if (e.key !== 'Tab') return;
    var f = focusables();
    if (!f.length) return;
    var first = f[0], last = f[f.length - 1];
    if (e.shiftKey && document.activeElement === first) { e.preventDefault(); last.focus(); }
    else if (!e.shiftKey && document.activeElement === last) { e.preventDefault(); first.focus(); }
  });

  function updateHybridActive(){
    if (!mq.matches) return;
    var probe = Math.max(110, (window.RVVH ? window.RVVH() : window.innerHeight) * .34);
    var current = '';
    ['services','build','grow','scale','audit','rv-cine','s11'].forEach(function(id){
      var section = document.getElementById(id);
      if (section && section.getBoundingClientRect().top <= probe) current = id;
    });
    services.classList.toggle('is-active', ['services','build','grow','scale','audit'].indexOf(current) !== -1);
    work.classList.toggle('is-active', current === 'rv-cine');
  }

  var ticking = false;
  window.addEventListener('scroll', function(){
    if (ticking) return;
    ticking = true;
    window.requestAnimationFrame(function(){ ticking = false; updateHybridActive(); });
  }, {passive:true});
  window.addEventListener('resize', updateHybridActive, {passive:true});
  updateHybridActive();

  function mqChange(e){ if (!e.matches) closeMenu(false); }
  if (mq.addEventListener) mq.addEventListener('change', mqChange);
  else if (mq.addListener) mq.addListener(mqChange);
})();
</script>


<script id="rv-title-reveal">
/* REVIVE title reveal v1.0 — единое появление заголовков секций.
   Каждое слово заворачивается в маску; слова одной строки выезжают снизу
   вместе, строки идут с шагом 90 мс по кривой --rv-ease-out. Слово-акцент
   (<em>, золотой контур) после подъёма «прорисовывается» слева направо.
   · Разметка заголовка (<em>, <span>, <br>) сохраняется — режутся только
     текстовые узлы, поэтому блики и контуры остаются на своих местах.
   · Каждое слово — inline-block с vertical-align:top: высота заголовка
     не меняется, сдвига вёрстки нет.
   · Без JS и при prefers-reduced-motion заголовки просто видны.
   · Страховка: всё, что оказалось в экране, раскрывается на следующем
     кадре скролла, даже если IntersectionObserver не сработал. */
(function (win, doc) {
	'use strict';
	if (win.RVTitles) return;
	if (!doc.querySelectorAll || !win.requestAnimationFrame) return;
	if (win.matchMedia && win.matchMedia('(prefers-reduced-motion: reduce)').matches) return;

	var SELECTOR = [
		'.rv-wwd-panel__title',
		'.rv-audit__title',
		'.rv-trust__title',
		'.stats__title',
		'.rv-proc__title',
		'.rv-cine__title',
		'.rv-quiz__title',
		'.rv-faq__title',
		'.s11__text-title--mobile'
	].join(',');
	var LINE_STEP = 90;          /* мс между строками */
	var WS = /([ \t\n\r\f]+)/;   /* неразрывный пробел остаётся внутри слова */

	var root = doc.documentElement;
	var pending = [];

	function visible(el) {
		return !!(el.offsetWidth || el.offsetHeight || el.getClientRects().length);
	}

	/* текстовые узлы -> <span.rv-tw><span.rv-tw__i>слово</span></span> */
	function wrapWords(node) {
		var child = node.firstChild;
		while (child) {
			var next = child.nextSibling;
			if (child.nodeType === 3) {
				var parts = child.nodeValue.split(WS);
				if (parts.length > 1 || parts[0].trim()) {
					var frag = doc.createDocumentFragment();
					for (var i = 0; i < parts.length; i++) {
						var part = parts[i];
						if (!part) continue;
						if (WS.test(part) && !part.replace(/[ \t\n\r\f]/g, '')) {
							frag.appendChild(doc.createTextNode(' '));
							continue;
						}
						var mask = doc.createElement('span');
						mask.className = 'rv-tw';
						var inner = doc.createElement('span');
						inner.className = 'rv-tw__i';
						inner.textContent = part;
						mask.appendChild(inner);
						frag.appendChild(mask);
					}
					node.replaceChild(frag, child);
				}
			} else if (child.nodeType === 1 && child.tagName !== 'BR' && !child.classList.contains('rv-tw')) {
				wrapWords(child);
			}
			child = next;
		}
	}

	/* номер строки для каждого слова — по фактической позиции в вёрстке */
	function indexLines(title) {
		var words = title.querySelectorAll('.rv-tw');
		var size = parseFloat(win.getComputedStyle(title).fontSize) || 40;
		var tops = [];
		for (var i = 0; i < words.length; i++) {
			var top = words[i].getBoundingClientRect().top;
			var line = -1;
			for (var t = 0; t < tops.length; t++) {
				if (Math.abs(tops[t] - top) < size * .45) { line = t; break; }
			}
			if (line === -1) { tops.push(top); line = tops.length - 1; }
			words[i].style.setProperty('--rv-tl', String(line));
			words[i].classList.toggle('rv-tw--accent', !!words[i].closest('em'));
		}
	}

	function split(title) {
		if (title.hasAttribute('data-rv-tr') || title.classList.contains('rv-split')) return false;
		if (!visible(title) || !title.textContent.trim()) return false;
		title.setAttribute('data-rv-tr', '1');
		wrapWords(title);
		indexLines(title);
		title.classList.add('rv-tr');
		return true;
	}

	function reveal(title) {
		if (title.classList.contains('rv-tr--in')) return;
		title.classList.add('rv-tr--in');
		var at = pending.indexOf(title);
		if (at !== -1) pending.splice(at, 1);
		if (observer) observer.unobserve(title);
	}

	var observer = 'IntersectionObserver' in win ? new IntersectionObserver(function (entries) {
		for (var i = 0; i < entries.length; i++) {
			if (entries[i].isIntersecting) reveal(entries[i].target);
		}
	}, { threshold: 0, rootMargin: '0px 0px -5% 0px' }) : null;

	/* страховка на каждом кадре скролла: всё, что уже в экране или выше */
	function guard() {
		if (!pending.length) return;
		var vh = win.innerHeight || root.clientHeight;
		for (var i = pending.length - 1; i >= 0; i--) {
			var r = pending[i].getBoundingClientRect();
			if (r.top < vh * .96 && (r.width || r.height)) reveal(pending[i]);
		}
	}

	function scan() {
		var titles = doc.querySelectorAll(SELECTOR);
		for (var i = 0; i < titles.length; i++) {
			if (!split(titles[i])) continue;
			pending.push(titles[i]);
			if (observer) observer.observe(titles[i]);
		}
		if (!observer) pending.slice().forEach(reveal);
		/* два кадра, чтобы стартовое (скрытое) состояние успело отрисоваться */
		win.requestAnimationFrame(function () { win.requestAnimationFrame(guard); });
	}

	/* строки пересчитываются, пока заголовок ещё не раскрыт */
	function relayout() {
		for (var i = 0; i < pending.length; i++) indexLines(pending[i]);
		scan();
	}

	root.classList.add('rv-tr-ready');
	scan();

	if (win.RVMotion) win.RVMotion.onScroll(guard);
	else win.addEventListener('scroll', guard, { passive: true });

	var lastWidth = win.innerWidth;
	win.addEventListener('resize', function () {
		if (win.innerWidth === lastWidth) return;   /* адресная строка телефона */
		lastWidth = win.innerWidth;
		relayout();
	}, { passive: true });
	if (doc.fonts && doc.fonts.ready) doc.fonts.ready.then(relayout, function () {});
	win.addEventListener('load', function () { relayout(); guard(); });

	win.RVTitles = { scan: scan, reveal: reveal };
}(window, document));
</script>

<script id="rv-smooth-scroll" data-no-optimize="1" data-no-defer="1" data-cfasync="false">
/* REVIVE smooth scroll + easing tokens v1.0
   1) RVEase — JS читает те же кривые, что и CSS (--rv-ease-out / -io / -amb
      из MOTION TOKENS в style.css). Одна правка в CSS меняет движение везде.
   2) RVScroll.to(target) — единый скролл страницы: якоря, вкладки глав,
      «наверх», стрелки кейсов. Учитывает липкие панели BUILD/GROW/SCALE.
   3) Lenis — инерционный скролл колесом, только ПК с мышью/тачпадом и без
      prefers-reduced-motion. На телефонах — родной скролл (быстрее и
      привычнее). Грузится после window.load и не влияет на LCP.
      Меню, модалки и прокручиваемые блоки внутри страницы скроллятся
      нативно (prevent + allowNestedScroll). */
(function (win, doc) {
	'use strict';
	if (win.RVScroll) return;

	var root = doc.documentElement;
	var LENIS_SRC = 'https://cdn.jsdelivr.net/npm/lenis@1.3.26/dist/lenis.min.js';

	/* ── 1. Кривые ───────────────────────────────────────────────────── */
	function bezier(x1, y1, x2, y2) {
		var cx = 3 * x1, bx = 3 * (x2 - x1) - cx, ax = 1 - cx - bx;
		var cy = 3 * y1, by = 3 * (y2 - y1) - cy, ay = 1 - cy - by;
		function sx(t) { return ((ax * t + bx) * t + cx) * t; }
		function sy(t) { return ((ay * t + by) * t + cy) * t; }
		function dx(t) { return (3 * ax * t + 2 * bx) * t + cx; }
		return function (x) {
			if (x <= 0) return 0;
			if (x >= 1) return 1;
			var t = x, i;
			for (i = 0; i < 8; i++) {
				var err = sx(t) - x;
				if (Math.abs(err) < 1e-5) return sy(t);
				var d = dx(t);
				if (Math.abs(d) < 1e-6) break;
				t -= err / d;
			}
			var lo = 0, hi = 1;
			t = x;
			for (i = 0; i < 30 && hi - lo > 1e-6; i++) {
				var v = sx(t);
				if (Math.abs(v - x) < 1e-5) break;
				if (x > v) lo = t; else hi = t;
				t = (lo + hi) / 2;
			}
			return sy(t);
		};
	}

	function token(name, fallback) {
		var raw = '';
		try { raw = win.getComputedStyle(root).getPropertyValue(name); } catch (e) {}
		var m = raw && raw.match(/cubic-bezier\(([^)]+)\)/);
		var p = m ? m[1].split(',').map(parseFloat) : fallback;
		if (p.length !== 4 || p.some(isNaN)) p = fallback;
		return bezier(p[0], p[1], p[2], p[3]);
	}

	var ease = {
		out: token('--rv-ease-out', [.22, 1, .36, 1]),
		io: token('--rv-ease-io', [.76, 0, .24, 1]),
		amb: token('--rv-ease-amb', [.45, 0, .55, 1]),
		bezier: bezier
	};
	win.RVEase = ease;

	/* ── 2. Единый скролл ────────────────────────────────────────────── */
	var reducedQuery = win.matchMedia ? win.matchMedia('(prefers-reduced-motion: reduce)') : { matches: false };
	var fineQuery = win.matchMedia ? win.matchMedia('(hover: hover) and (pointer: fine)') : { matches: false };
	var nativeScrollTo = win.scrollTo;
	var nativeIntoView = win.Element && Element.prototype.scrollIntoView;
	var lenis = null;

	function scrollY() { return win.pageYOffset || root.scrollTop || 0; }
	function maxY() { return Math.max(0, Math.max(root.scrollHeight, doc.body ? doc.body.scrollHeight : 0) - win.innerHeight); }

	/* Цель в пикселях документа. Липкая панель (BUILD/GROW/SCALE на ПК)
	   меряется по метке перед ней: rect самой панели в залипании врёт. */
	function resolve(target) {
		if (typeof target === 'number') return Math.max(0, Math.min(maxY(), target));
		var el = typeof target === 'string' ? doc.getElementById(target.replace(/^#/, '')) : target;
		if (!el || !el.getBoundingClientRect) return null;
		var cs = win.getComputedStyle(el);
		var y;
		if (cs.position === 'sticky') {
			var mark = el.previousElementSibling;
			if (!mark || !mark.hasAttribute('data-dock-mark')) return null;
			y = mark.getBoundingClientRect().top + scrollY() - (parseFloat(cs.top) || 0);
		} else {
			y = el.getBoundingClientRect().top + scrollY() - (parseFloat(cs.scrollMarginTop) || 0);
		}
		return Math.max(0, Math.min(maxY(), Math.round(y)));
	}

	function durationFor(distance) {
		return Math.max(.85, Math.min(1.9, .6 + Math.abs(distance) / 3200));
	}

	function to(target, options) {
		options = options || {};
		var y = resolve(target);
		if (y === null) return false;
		var done = function () {
			if (win.RVMotion) win.RVMotion.sweep(false);
			if (typeof options.onComplete === 'function') options.onComplete();
		};
		if (lenis) {
			lenis.scrollTo(y, {
				duration: durationFor(y - scrollY()),
				easing: ease.io,
				force: true,
				onComplete: done
			});
		} else {
			nativeScrollTo.call(win, { top: y, left: 0, behavior: reducedQuery.matches ? 'auto' : 'smooth' });
			win.setTimeout(done, 900);
		}
		return true;
	}

	win.RVScroll = {
		version: '1.0',
		to: to,
		resolve: resolve,
		velocity: function () { return lenis ? lenis.velocity : 0; },
		get lenis() { return lenis; },
		stop: function () { if (lenis) lenis.stop(); },
		start: function () { if (lenis) lenis.start(); }
	};

	/* ── 3. Lenis ────────────────────────────────────────────────────── */
	var lockAt = 0;
	var lockValue = false;
	function locked() {
		var now = Date.now();
		if (now - lockAt < 100) return lockValue;
		lockAt = now;
		var body = doc.body;
		lockValue = !!body && (
			body.classList.contains('rv-desktop-menu-open') ||
			win.getComputedStyle(body).overflowY === 'hidden' ||
			win.getComputedStyle(root).overflowY === 'hidden'
		);
		return lockValue;
	}

	function start() {
		if (lenis || !win.Lenis || !fineQuery.matches || reducedQuery.matches) return;

		/* Дирижёр (RVMotion) перехватывает window.addEventListener('scroll')
		   и откладывает вызов на кадр. Lenis нужен родной слушатель, иначе
		   его синхронизация с нативной прокруткой опаздывает на кадр. */
		var patched = Object.prototype.hasOwnProperty.call(win, 'addEventListener') ? win.addEventListener : null;
		if (patched) { try { delete win.addEventListener; } catch (e) { patched = null; } }
		try {
			lenis = new win.Lenis({
				lerp: .095,
				wheelMultiplier: 1,
				smoothWheel: true,
				syncTouch: false,
				autoRaf: true,
				allowNestedScroll: true,
				stopInertiaOnNavigate: true,
				prevent: function (node) {
					return locked() || (node.classList && (node.classList.contains('rv-desktop-menu') || node.classList.contains('overlay') || node.classList.contains('popuper')));
				}
			});
		} catch (error) {
			lenis = null;
			if (win.console && win.console.warn) win.console.warn('[rv-scroll]', error);
		} finally {
			if (patched) win.addEventListener = patched;
		}
		if (!lenis) return;
		root.classList.add('rv-smooth');

		/* Старые вызовы smooth-скролла (кнопка «наверх», стрелки кейсов,
		   квиз) идут той же кривой, что и всё остальное. */
		win.scrollTo = function (a) {
			if (lenis && a && typeof a === 'object' && a.behavior === 'smooth' && typeof a.top === 'number' && !locked()) {
				to(a.top);
				return;
			}
			return nativeScrollTo.apply(win, arguments);
		};
		if (nativeIntoView) {
			Element.prototype.scrollIntoView = function (arg) {
				if (lenis && arg && typeof arg === 'object' && arg.behavior === 'smooth' && (!arg.block || arg.block === 'start') && !arg.inline && !locked()) {
					if (to(this)) return;
				}
				return nativeIntoView.apply(this, arguments);
			};
		}
	}

	function stopSmooth() {
		if (!lenis) return;
		lenis.destroy();
		lenis = null;
		root.classList.remove('rv-smooth');
		win.scrollTo = nativeScrollTo;
		if (nativeIntoView) Element.prototype.scrollIntoView = nativeIntoView;
	}

	/* Якоря на этой же странице — одной кривой. Модалка #win1 (:target),
	   скрытые и fixed-цели остаются браузеру. */
	doc.addEventListener('click', function (event) {
		if (!lenis || event.defaultPrevented || event.button !== 0 || event.metaKey || event.ctrlKey || event.shiftKey || event.altKey) return;
		var link = event.target && event.target.closest ? event.target.closest('a[href*="#"]') : null;
		if (!link || link.target === '_blank') return;
		var url;
		try { url = new URL(link.href, win.location.href); } catch (e) { return; }
		if (url.origin !== win.location.origin || url.pathname !== win.location.pathname || url.search !== win.location.search) return;
		if (!url.hash || url.hash === '#') return;
		var id = decodeURIComponent(url.hash.slice(1));
		var el = doc.getElementById(id);
		if (!el) return;
		var cs = win.getComputedStyle(el);
		if (cs.position === 'fixed' || cs.display === 'none') return;
		if (resolve(el) === null) return;
		event.preventDefault();
		win.requestAnimationFrame(function () {
			to(el);
			if (win.history && history.pushState) history.pushState(null, '', '#' + id);
		});
	});

	function load() {
		if (!fineQuery.matches || reducedQuery.matches) return;
		if (win.Lenis) { start(); return; }
		var script = doc.createElement('script');
		script.src = LENIS_SRC;
		script.async = true;
		script.onload = start;
		doc.head.appendChild(script);
	}

	function onPreferenceChange() {
		if (reducedQuery.matches || !fineQuery.matches) stopSmooth();
		else load();
	}
	if (typeof reducedQuery.addEventListener === 'function') {
		reducedQuery.addEventListener('change', onPreferenceChange);
		fineQuery.addEventListener('change', onPreferenceChange);
	}

	if (doc.readyState === 'complete') load();
	else win.addEventListener('load', load, { once: true });
}(window, document));
</script>

<style id="rv-final-polish-v5">
/* Final cascade guard: section-specific inline CSS is declared throughout the
   template, so the critical density/CTA rules repeat here once, at the end. */
:root{--rv-motion-ease:cubic-bezier(.22,1,.36,1);--rv-cta-radius:999px;--rv-cta-gold-a:#F5D488;--rv-cta-gold-b:#F5C542;--rv-cta-gold-c:#DFAE3F;--rv-cta-ink:#030032;--rv-cta-shadow:0 10px 30px rgba(245,197,66,.26);--rv-cta-shadow-hover:0 0 0 1px rgba(245,197,66,.38),0 12px 34px rgba(245,197,66,.30),0 0 42px 4px rgba(245,197,66,.24)}
.hero__buttons a,.hero .btn--primary,.rv-wwd-btn--primary,.rv-cine__cta,.rv-proc__cta,.rv-quiz__cta,.rv-quiz__send,.rv-audit__go,.rv-audit__cta,a.s6__link,.s1__link-inner a.s6__link,.s11 input.wpcf7-submit,.rv-desktop-cta{border-radius:var(--rv-cta-radius)!important;border:1px solid rgba(245,197,66,.58)!important;background:linear-gradient(135deg,var(--rv-cta-gold-a) 0%,var(--rv-cta-gold-b) 48%,var(--rv-cta-gold-c) 100%)!important;color:var(--rv-cta-ink)!important;-webkit-text-fill-color:var(--rv-cta-ink)!important;box-shadow:var(--rv-cta-shadow)!important;transform:none!important;transition:box-shadow .32s var(--rv-motion-ease),filter .32s ease,border-color .32s ease!important}
.hero__buttons a:hover,.hero__buttons a:focus-visible,.hero .btn--primary:hover,.hero .btn--primary:focus-visible,.rv-wwd-btn--primary:hover,.rv-wwd-btn--primary:focus-visible,.rv-cine__cta:hover,.rv-cine__cta:focus-visible,.rv-proc__cta:hover,.rv-proc__cta:focus-visible,.rv-quiz__cta:hover,.rv-quiz__cta:focus-visible,.rv-quiz__send:hover,.rv-quiz__send:focus-visible,.rv-audit__go:hover,.rv-audit__go:focus-visible,.rv-audit__cta:hover,.rv-audit__cta:focus-visible,a.s6__link:hover,a.s6__link:focus-visible,.s11 input.wpcf7-submit:hover,.s11 input.wpcf7-submit:focus-visible,.rv-desktop-cta:hover,.rv-desktop-cta:focus-visible{background:linear-gradient(135deg,var(--rv-cta-gold-a) 0%,var(--rv-cta-gold-b) 48%,var(--rv-cta-gold-c) 100%)!important;color:var(--rv-cta-ink)!important;-webkit-text-fill-color:var(--rv-cta-ink)!important;border-color:rgba(245,197,66,.9)!important;box-shadow:var(--rv-cta-shadow-hover)!important;filter:brightness(1.035);transform:none!important}
.hero__buttons a .arrow,.rv-wwd-btn--primary>span,.rv-cine__cta>span,.rv-proc__cta>span,.rv-quiz__cta>span,.rv-audit__cta>span,.rv-desktop-cta>span{display:inline-block;line-height:1;transform:translate(0,0)!important;transition:transform .28s var(--rv-motion-ease)!important}
.hero__buttons a:hover .arrow,.hero__buttons a:focus-visible .arrow,.rv-wwd-btn--primary:hover>span,.rv-wwd-btn--primary:focus-visible>span,.rv-cine__cta:hover>span,.rv-cine__cta:focus-visible>span,.rv-proc__cta:hover>span,.rv-proc__cta:focus-visible>span,.rv-quiz__cta:hover>span,.rv-quiz__cta:focus-visible>span,.rv-audit__cta:hover>span,.rv-audit__cta:focus-visible>span,.rv-desktop-cta:hover>span,.rv-desktop-cta:focus-visible>span{transform:translate(2px,-2px)!important}
.rv-wwd-btn--text{opacity:.76;border-bottom-color:rgba(245,244,239,.22);transition:opacity .28s var(--rv-motion-ease),border-color .28s ease,color .28s ease!important}.rv-wwd-btn--text:hover,.rv-wwd-btn--text:focus-visible{opacity:1;color:var(--rv-wwd-gold,#F5C542);border-bottom-color:rgba(245,197,66,.58)}
@media(min-width:993px){.rv-wwd__intro-copy{max-width:32ch;line-height:1.55;color:#fff}.rv-wwd-panel__description{max-width:40ch;line-height:1.56;color:#fff;margin-bottom:28px}.rv-wwd-chips{margin-bottom:32px}.rv-proc__lead{max-width:42ch;line-height:1.56;color:#fff}.rv-proc__text{max-width:39ch;line-height:1.54;color:#fff}.rv-proc__out{max-width:43ch;line-height:1.46;color:#fff}.rv-audit__lead,.rv-quiz__lead,.rv-faq__aside{max-width:38ch;line-height:1.56;color:#fff}.rv-audit__facts,.rv-quiz__facts{max-width:39ch;margin-top:22px;gap:8px}.rv-audit__facts li,.rv-quiz__facts li{font-size:.89rem;line-height:1.46;color:#fff}.rv-audit__hint{max-width:48ch;color:#fff}.rv-review__text{max-width:44ch;line-height:1.56;color:#fff;margin-bottom:24px}.rv-faq__a p{max-width:64ch;line-height:1.56;color:#fff}}
.rv-services .card,.rv-services .showcase,.rv-services .nav-arrow,.rv-wwd-panel,.rv-wwd-chips span,.rv-proc__step,.rv-audit__card,.rv-quiz__card,.rv-review,.rv-faq__item{transition-timing-function:var(--rv-motion-ease)!important}
@media(prefers-reduced-motion:reduce){.hero__buttons a .arrow,.rv-wwd-btn--primary>span,.rv-cine__cta>span,.rv-proc__cta>span,.rv-quiz__cta>span,.rv-audit__cta>span,.rv-desktop-cta>span{transition:none!important;transform:none!important}}
</style>

<?php

get_footer();