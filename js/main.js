// JavaScript Document
$(document).ready(function () {
	(function (jQuery) {
		jQuery.fn.clickoutside = function (callback) {
			var outside = 1, self = $(this);
			self.cb = callback;
			this.click(function () {
				outside = 0;
			});
			$(document).click(function () {
				outside && self.cb();
				outside = 1;
			});
			return $(this);
		}
	})(jQuery);
	(function (jQuery) {
		// Browser supports HTML5 multiple file?
		var multipleSupport = typeof $('<input/>')[0].multiple !== 'undefined',
			isIE = /msie/i.test(navigator.userAgent);
		jQuery.fn.customFile = function () {
			return this.not(".customfile").each(function () {
				var $file = $(this).addClass('customfile');
				var $wrap = $('<div class="customfile-wrap">'),
					$input = $('<input type="text" class="customfile-filename" />'),
					$button = $('<button type="button" class="customfile-upload">Browse</button>');
				$label = $('<label class="customfile-upload" for="' + $file[0].id + '">Browse</label>');
				$file.css({ position: 'absolute', left: '-9999px' });
				//$wrap.insertAfter( $file ).append( $file, $input, ( isIE ? $label : $button ) );
				$wrap.insertAfter($file).append($file, $input, $button);
				$input.css('width', '100% !important').css('width', '-=91px'); //69px for button + 22px for padding+border
				$file.attr('tabIndex', - 1);
				$button.attr('tabIndex', - 1);
				$button.click(function () {
					$file.focus().click(); // Open dialog
				});
				$file.change(function () {
					var files = [], fileArr, filename;
					if (multipleSupport) {
						fileArr = $file[0].files;
						for (var i = 0, len = fileArr.length; i < len; i++) {
							files.push(fileArr[i].name);
						}
						filename = files.join(', ');
					} else {
						filename = $file.val().split('').pop();
					}
					$input.val(filename).attr('title', filename).focus();
				});
				$input.on({
					blur: function () { $file.trigger('blur'); },
					keydown: function (e) {
						if (e.which === 13) { // Enter
							if (!isIE) { $file.trigger('click'); }
						} else if (e.which === 8 || e.which === 46) { // Backspace & Del
							// On some browsers the value is read - only
							// with this trick we remove the old input and add
							// a clean clone with all the original events attached
							$file.replaceWith($file = $file.clone(true));
							$file.trigger('change');
							$input.val('');
						} else if (e.which === 9) { // TAB
							return;
						} else { // All other keys
							return false;
						}
					}
				});
			});
		};
	}(jQuery));
	$(window).scroll(function () {
		if ($(this).scrollTop() > 200) {
			$('.backtotop').fadeIn(500);
		} else {
			$('.backtotop').fadeOut(500);
		}
	});
	$('.backtotop').click(function (event) {
		event.preventDefault();
		$('html, body').animate({ scrollTop: 0 }, 500);
		return false;
	});
	$(function () {
		$('td pre code').each(function () {
			eval($(this).text());
		});
	});
	/**
	 * Nav and Links
	 */
	activate_links();
	$(".faq_answer").slideUp();
	$(".faq_question").click(function () {
		$(this).next(".faq_answer").slideToggle(300).siblings(".faq_answer").slideUp(200);
		$(this).toggleClass('faq_active').siblings(".faq_question").removeClass('faq_active');
	});
	$(".result_foot").click(function () {
		$(this).hide().prev(".more").removeClass('hidden');
	});
	$(".dashboard_icon,.dashboard_label").hover(
		function () {
			$(this).siblings().addClass('hovered');
		}, function () {
			$(this).siblings().removeClass('hovered');
		});
	$(".topic_detail").slideUp();
	$(".topic_title").click(function () {
		if ($(this).children(".icon_plusminus").hasClass('icon_shift')) {
			$(".icon_plusminus").removeClass('icon_shift');
		} else {
			$(".icon_plusminus").removeClass('icon_shift');
			$(this).children(".icon_plusminus").addClass('icon_shift');
		}
		$(this).next(".topic_detail").slideToggle(300).siblings(".topic_detail").slideUp(200);
	});
	$(".folder").click(function (e) {
		if ($(this).children(".folder_arrow_box").hasClass('arrow_right')) {
			$(".folder_data").slideUp(200);
			$(".folder_arrow_box").removeClass('arrow_down').addClass('arrow_right');
			$(this).children(".folder_arrow_box").removeClass('arrow_right').addClass('arrow_down');
			$(this).next(".folder_data").removeClass('hidden').slideDown(300);
		} else {
			$(this).children(".folder_arrow_box").removeClass('arrow_down').addClass('arrow_right');
			$(this).next(".folder_data").slideUp(200);
		}
	});
	$(".ctab").click(function (e) {
		$(".ctab").removeClass('active');
		$(this).addClass('active');
		var tabview = $(this).attr('id').replace('cnav_', '');
		if (tabview == 'chapter') {
			window.location.assign('?page=account&view=uploads&ctab=course&form=chapter');

		}
		else {
			$.ajax({
				type: "POST",
				url: "index.php",
				data: { process: "upload", proc_type: "change tab", tabview: tabview }
			}).done(function (html) {
				console.log(html);
				$("#ctab_content").html(html);
				activate_content();
				activate_links();
			});
		}

	});
	$(".result_nav").click(function (e) {
		$(".result_nav").removeClass('active');
		$(this).addClass('active');
		var content_id = $(this).attr('id').replace('rnav_', 'res_');
		$(".result_section").fadeOut(200);
		$("#" + content_id).fadeIn(300);
	});
	activate_content();
	/**
	 * Tooltips
	 */

	var timer;
	/*$(".bmenu_drop").hide();
	$(".bmenu").hover(function() {
        var drop_name = $(this).attr('id') + '_drop';
		$("#" + drop_name).delay(500).slideDown().siblings(".bmenu_drop").delay(500).slideUp();
	}, function(){
		
	});*/
	/**
	 * Account panel
	 **/
	$("#account_panel").removeClass('hidden').hide();
	$(".account_drop").hover(function () {
		$("#account_panel").slideDown(300);
	});
	$("#header").hover(function () {
		$("#account_panel").slideUp(300);
	});
	/**
	 * Browse departments panel
	 */
	if ($('#browse_panel').length > 0) {
		$("#browse_panel").css('top', $("#browse_drop").position().top + $("#browse_drop").height());
		$(".bpanel_contain").each(function () {
			var listWidth = 1 + Math.max.apply(null, $(this).children(".bpanel_listing").map(function () {
				return $(this).outerWidth(true);
			}).get());
			var spaceWidth = ($(this).width() - (3 * listWidth)) / 2;
			$(this).children(".bpanel_listing").css('width', listWidth);
			$(this).children(".bpanel_space").css('width', spaceWidth);
		});
		$("#browse_drop div").hover(
			function () {
				$(this).siblings().addClass('hovered');
				$("#browse_panel").removeClass('invisible');
			}, function () {
				$(this).siblings().removeClass('hovered');
				$("#browse_panel").addClass('invisible');
			});
		$("#browse_panel").hover(
			function () {
				$(this).removeClass('invisible');
				$("#browse_drop div").addClass('hovered');
			}, function () {
				$("#browse_drop div").removeClass('hovered');
				$(this).addClass('invisible');
			});
	}

	/**
	 * Form scripts
	 */
	$("[id^=search_txt]").each(function () {
		var id_ext = $(this).attr('id').replace('search_txt', '');
		var value = $(this).val();
		$(this).click(function () {
			$(this).val('');
			$("#search" + id_ext).clickoutside(function () {
				$("#search_txt" + id_ext).val(value);
			});
		}).keypress(function (event) {
			if (event.which == 13) {
				submitSearch(id_ext);
			}
		});
	});
	$("[class^=i_search]").each(function () {
		var id_ext = $(this).parent("[id^=search]").attr('id').replace('search', '');
		$(this).click(function (event) {
			submitSearch(id_ext);
		});
	});
	$("#fakepwd").on('focus', function () { $(this).addClass('hidden').siblings("#pwd").removeClass('hidden').focus(); });
	$("#recover_form").validate({ errorElement: 'div' });
	$("#profile_form").validate({ errorElement: 'div' });
	$("#pwd_form").validate({
		errorElement: 'div',
		rules: {
			pwd_cnew: { equalTo: "#pwd_new" }
		}
	});
	$("#reg_form").validate({
		errorElement: 'div',
		rules: {
			conf_email: { equalTo: "#email" },
			conf_pwd: { equalTo: "#pwd" }
		}
	});
	$(".i_search").click(function (event) {
		submitSearch();
	});
	$("#search_txt").keypress(function (event) {
		if (event.which == 13) {
			submitSearch();
		}
	});
	$("#contact_form").validate({ errorElement: 'div' });
	$(".add_form_link").click(function (e) {
		$("#body_fade").addClass('active').click(function (e) {
			$(this).removeClass('active');
			$("#add_form_holder").addClass('hidden').removeClass('form_popup');
		});
		$("#add_form_holder").toggleClass('form_popup').toggleClass('hidden');
	});
	$(".resource > .tools > .js_edit").click(function (e) {
		var resource = $(this).parents(".resource");
		var form = resource.siblings(".resource_edit");
		form.show().find(".js_cancel").click(function (e) {
			form.hide(200);
		});
		form.children("form").submit(function () {
			var doc_id = $(this).children("input[name=docID]").val();
			var resource = $(this).parent().siblings("#doc" + doc_id);
			var valueName = $(this).children("input[name=name]").val();
			var valueLink = $(this).children("input[name=link]").val();
			resource.children(".subhead").text(valueName);
			resource.children("a").attr('href', valueLink).text(valueLink);
			$(this).parent(".resource_edit").hide(200);
			return false;
		});
		form.find("input[name=docID]").val(resource.attr('id').replace(/[^0-9]/g, ''));
		form.find("input[name=name]").val(resource.children(".subhead").text());
		form.find("input[name=link]").focus().val(resource.children("a").attr('href'));
	});
	/**
	 * Course scripts
	 */
	$("#active_vid img").click(function () {
		$(this).addClass('hidden').parent().css('height', '535');
	});
	$(".progress_fill").css('width', 45);
	$(".icon_box").hover(
		function () {
			$(this).siblings(".label_box").addClass('hovered');
		}, function () {
			$(this).siblings(".label_box").removeClass('hovered');
		});
	$("#active_vim iframe").each(function () {
		var playerID = $(this).attr('id');
		var vim_data = $("#vim_data").text().trim().split('::');
		$f(playerID).addEvent('ready', function () {
			// Add event listeners
			$f(playerID).addEvent('play', onPlay);
			$f(playerID).addEvent('pause', onPause);
			$f(playerID).addEvent('playProgress', onPlayProgress)
			// Fire an API method
			$f(playerID).api('setColor', '0066B3');
			var i = 0;
			while (vim_data[i]) {
				var vim_property = vim_data[i].split(':');
				switch (vim_property[0]) {
					case 'seek':
						if (vim_property[1] > 0) $f(playerID).api('seekTo', vim_property[1]);
						break;
					default:
					//alert( 'vim_value-d-' + vim_property[1] );
				}
				i++;
			}
			$("#status").text('Ready.');
		});
	});
	/*$(".vid_preview").hover(function(){
			$(this).children(".btn_fav,.btn_watch").removeClass('invisible');
		},function(){
			$(this).children(".btn_fav,.btn_watch").addClass('invisible');
		});*/
	$(".btn_collapse > .vim_text").hide();

	/**
	 * On Load Events
	 */
	$(".fitvid").on('load', function () {
		var w = $(this).parent().width();
		var dim = $(this).attr('id').split('x');
		var h = w / dim[0] * dim[1];
		$(this).css('width', w).css('height', h);
	});
	(function ($) {
		$(window).load(function () {
			$(".scrolldiv").mCustomScrollbar({
				advanced: {
					autoScrollOnFocus: true
				}
			});
		});
	})(jQuery);
	/**
	 * Lightbox
	 */
	$("a.lightbox").lightBox({
		overlayBgColor: '#ecedef',
		overlayOpacity: .8,
		imageLoading: 'img/lightbox/lightbox-ico-loading.gif',
		imageBtnClose: 'img/lightbox/lightbox-btn-close.gif',
		imageBtnPrev: 'img/lightbox/lightbox-btn-prev.gif',
		imageBtnNext: 'img/lightbox/lightbox-btn-next.gif',
		containerResizeSpeed: 350
	});
	/**
	 * Billing
	 */
	$("#payment_active").find(".js_delete").click(function (e) {
		$(this).parent(".comment_option").prepend('<a class="js_active">Make Active</a> | ');
		$('#payment_other').append($(this).parents('.payment_method'));
	});
	$("#payment_other").find(".js_delete").click(function (e) {
		$(this).parents('.payment_method').remove();
	});
	$("#payment_other").find(".js_active").click(function (e) {
		var current_html = $('#payment_active').find(".payment_card").html();
		var new_html = $(this).parent().siblings(".payment_card").html();
		$('#payment_active').find(".payment_card").html(new_html);
		$(this).parent().siblings(".payment_card").html(current_html);
	});
	/**
	 * Contributors
	 */
	/**
	 * Commenting
	 */
	//$("#comment_panel").hide();
	$(".btn_comments").click(function () {
		$("#message_panel").slideUp(200);
		if ($("#comment_panel").hasClass('hidden')) {
			$("#comment_panel").hide().removeClass('hidden');
		}
		$("#comment_panel").slideToggle(400);
	});
	$("form#comment_post").submit(function () {
		var comment_box = $(this).parent().siblings(".comment_left");
		var profile_source = $(this).children("input[name=profile_source]").val();
		var name = $(this).children("input[name=full_name]").val();
		var process = $(this).children(".btn_comment").val();
		var msg = $(this).children(".comment").val();
		$(this).children(".comment").val('');
		$.ajax({
			type: "POST",
			url: "index.php",
			data: { process: process, profile: profile_source, name: name, msg: msg }
		}).done(function (html) {
			comment_box.prepend(html);
			//.animate({ scrollTop: comment_box.prop("scrollHeight") }, 2000);
		});
		return false;
	});
	activate_comments();
	$(".line_links").find(".js_reply").click(function (event) {
		event.stopPropagation();
		$("form#reply").remove();
		var form = $("#reply_form").children(".reply_hold").clone();
		form.removeClass('reply_hold').attr('id', 'reply');
		var comment_box = $(this).parents(".line_links").parent();
		comment_box.after(form);
		comment_box.siblings("#reply").children(".comment").focus();
		$("form#reply").submit(function () {
			var profile_source = $(this).children("input[name=profile_source]").val();
			var name = $(this).children("input[name=full_name]").val();
			var process = $(this).children(".btn_comment").val();
			var msg = $(this).children(".comment").val();
			$(this).remove();
			$.ajax({
				type: "POST",
				url: "index.php",
				data: { process: process, profile: profile_source, name: name, msg: msg }
			});
			return false;
		});
	});

	/**
	 * Messaging
	 */
	//$("#message_panel").hide();
	$(".btn_messages").click(function () {
		$("#comment_panel").slideUp(200);
		if ($("#message_panel").hasClass('hidden')) {
			$("#message_panel").hide().removeClass('hidden');
		}
		$("#message_panel").slideToggle(400);
	});
	$(".msg_rec_box").click(function () {
		if (!$(this).hasClass('rec_active')) {
			$(this).addClass('rec_active');
			$(this).siblings(".msg_rec_box").removeClass('rec_active').children(".msg_point").remove();
			$(this).prepend('<div class="msg_point fright"></div>');
			var convo_id = $(this).attr('id').replace('user', 'convo');
			$("[id^=convo_]").hide();
			$("#" + convo_id).removeClass('hidden').show();
		}
	});
	$(".share_rec_box").click(function () {
		$(this).removeClass('share_rec_box').addClass('rec_selected fleft').insertBefore("#rec_before");
	});
	$(".msg_new, .rec_add").hover(function () {
		$(this).children("[class^=plus]").addClass('hovered');
	}, function () {
		$(this).children("[class^=plus]").removeClass('hovered');
	});
	$(".rec_add").click(function (e) {
		$("#body_fade").addClass('active').click(function (e) {
			$(this).removeClass('active');
			$(".form_popup").hide();
		});
		$(".form_popup").show();
		$("#recip_box.initialize").mCustomScrollbar({
			advanced: {
				autoScrollOnFocus: true
			}
		}).removeClass('initialize');
		$("#recip_btn").click(function (e) {
			$("#body_fade").removeClass('active');
			$("#recip_box").find(".selected").each(function (index, element) {
				$(element).removeClass('selected');
				var content = $(element).clone();
				content.removeClass('recipient top_ad').addClass('rec_selected');
				content.children("img").addClass('fleft');
				content.children(".plus_sm").remove();
				content.children(".name").removeClass('name').addClass('rec_name fleft');
				$("#rec_before").before(content);
				$(element).addClass('added').children(".plus_sm").remove();
			});
			$(".form_popup").hide();
		});
	});
	$(".recipient").click(function (e) {
		$(this).toggleClass('selected');
	});
	$(".rec_to > .delete").click(function (e) {
		$(this).parent('.rec_to').remove();
	});
	$("form#share").submit(function (e) {
		$(this).siblings("#recipients").find(".share_rec_box").removeClass('fleft').removeClass('rec_margin').insertAfter("#suggest");
		$(this).siblings(".browse_head").append('<span class="blue"> --- Video shared successfully!</span>');
		return false;
	});
	$("form.convo_post").submit(function () {
		var convo_box = $(this).siblings(".convo_box");
		var process = $(this).children("#message_post").val();
		var profile_source = $(this).children("input[name=profile_source]").val();
		var msg = $(this).children("textarea").val();
		var vtitle = $(this).children("input[name=video_title]").val();
		var vsource = $(this).children("input[name=video_source]").val();
		var vinclude = 'n';
		if ($(this).children("input[name=videolink]").is(":checked")) { vinclude = 'y'; }
		$(this).children("textarea").val('');
		$.ajax({
			type: "POST",
			url: "index.php",
			data: { process: process, profile: profile_source, msg: msg, vid_title: vtitle, vid_source: vsource, vid_include: vinclude }
		}).done(function (html) {
			convo_box.append(html).animate({ scrollTop: convo_box.prop("scrollHeight") }, 2000);
		});
		return false;
	});
	$("#mediatype_selector").find(".selectOption").click(function () {
		var type = $(this).attr('value');
		var video = $("#choose_video");
		var image = $("#choose_image");
		switch (type) {
			case 'image':
				video.addClass('hidden');
				image.removeClass('hidden');
				break;
			case 'video':
				image.addClass('hidden');
				video.removeClass('hidden');
				break;
			default:
				image.addClass('hidden');
				video.addClass('hidden');
		}
	});
	$("#type_selector").find(".selectOption").click(function () {
		var type = $(this).attr('value');
		var text = $("#answer_text");
		var truefalse = $("#answer_truefalse");
		var multiple = $("#answer_multiple");
		switch (type) {
			case 'text':
				truefalse.addClass('hidden');
				multiple.addClass('hidden');
				text.removeClass('hidden');
				break;
			case 'truefalse':
				text.addClass('hidden');
				multiple.addClass('hidden');
				truefalse.removeClass('hidden');
				break;
			case 'multiple':
				text.addClass('hidden');
				truefalse.addClass('hidden');
				multiple.removeClass('hidden');
				break;
			default:
				text.addClass('hidden');
				truefalse.addClass('hidden');
				multiple.addClass('hidden');
		}
	});
});

function activate_links() {
	$(".btn_active").click(function (event) { event.stopPropagation(); });
	$("[class*=link_]").each(function (index, linkElement) {
		var linkTarget = '#test';
		var linkOpen = 'same';
		var classList = $(this).attr('class').split(/\s+/);
		$.each(classList, function (index2, classString) {
			if (classString.substr(0, 5) == 'link_') {
				var linkType = classString.replace('link_', '');
				switch (linkType) {
					case 'about': linkTarget = 'index.php?page=about'; break;
					case 'admin': linkTarget = 'index.php?page=account&view=admin'; break;
					case 'users': linkTarget = 'index.php?page=account&view=users'; break;
					case 'albumvid':
						var id = $(linkElement).attr('id');
						var doc_id = id.replace(/[^0-9]/g, '');
						linkTarget = 'index.php?page=album&id=' + doc_id;
						break;
					case 'audio': linkTarget = 'index.php?view=audio'; break;
					case 'billing': linkTarget = 'index.php?page=account&view=billing'; break;
					case 'comment_panel':
						var id = $(linkElement).parents(".link_albumvid").attr('id');
						var doc_id = id.replace(/[^0-9]/g, '');
						linkTarget = 'index.php?page=album&id=' + doc_id + '&show=comments#comment_panel';
						break;
					case 'comments': linkTarget = 'index.php?page=account&view=comments'; break;
					case 'contact': linkTarget = 'index.php?page=help#contact'; break;
					case 'content': linkTarget = 'download_content.php?fn=' + $(linkElement).children(".doc-path").text(); break;
					case 'content_direct': linkTarget = 'getfile/' + $(linkElement).parent().siblings(".doc-path").text().replace('mediacenter/content/', ''); break;
					case 'contributors': linkTarget = 'index.php?view=contributors'; break;
					case 'course':
						var id = $(linkElement).attr('id');
						var course_id = id.replace(/[^0-9]/g, '');
						linkTarget = 'index.php?page=topic&id=' + course_id;
						break;
					case 'courses': linkTarget = 'index.php?view=topics'; break;
					case 'dbupdate': linkTarget = 'db_update.php'; linkOpen = 'new'; break;
					case 'documents': linkTarget = 'index.php?view=documents'; break;
					//case 'favorites': linkTarget = 'index.php?page=account&view=favorites'; break;
					case 'forum': linkTarget = 'forum'; linkOpen = 'new'; break;
					case 'help': linkTarget = 'index.php?page=help'; break;
					case 'history': linkTarget = 'index.php?page=account&view=history'; break;
					case 'folders': linkTarget = 'index.php?page=account&view=folders'; break;
					case 'quiz_history': linkTarget = 'index.php?page=account&view=quiz_history'; break;
					case 'watchlist': linkTarget = 'index.php?page=account&view=watchlist'; break;
					case 'favorites': linkTarget = 'index.php?page=account&view=favorites'; break;
					case 'home': linkTarget = 'index.php?sect=false'; break;
					case 'login': linkTarget = 'index.php?page=login'; break;
					case 'logoff': linkTarget = 'logoff.php'; break;
					case 'main_contributors': linkTarget = 'index.php?sect=false&view=contributors'; break;
					case 'main_courses': linkTarget = 'index.php?sect=false&view=topics'; break;
					case 'messages': linkTarget = 'index.php?page=account&view=messages'; break;
					case 'myaccount': linkTarget = 'index.php?page=account'; break;
					case 'mycontent':
						var id = $(linkElement).attr('id');
						var doc_id = id.replace(/[^0-9]/g, '');
						linkTarget = 'index.php?page=album&id=' + doc_id;
						break;
					case 'password': linkTarget = 'index.php?page=account#pwd_form'; break;
					case 'pictures': linkTarget = 'index.php?view=pictures'; break;
					case 'presentations': linkTarget = 'index.php?view=presentations'; break;
					case 'recover': linkTarget = 'index.php?page=login&view=recover'; break;
					/* case 'register': linkTarget = 'index.php?page=login&view=register'; break; */
					case 'register': linkTarget = 'index.php?page=login&view=register-type'; break;
					case 'resources': linkTarget = 'index.php?view=resources'; break;
					case 'section':
						var id = $(linkElement).attr('id');
						var sect_id = id.replace(/[^0-9]/g, '');
						linkTarget = 'index.php?sect=' + sect_id + '&page=section';
						break;
					case 'store': linkTarget = 'store'; linkOpen = 'new'; break;
					case 'tab':
						var args = $(linkElement).children(".tab_path").text().split('::');
						var argItems = new Array();
						$.each(args, function (index, arrayItem) {
							var fieldValue = arrayItem.split(':');
							argItems[index] = fieldValue[0] + '=' + fieldValue[1];
						});
						var argString = argItems.join('&');
						linkTarget = 'index.php?' + argString;
						break;
					case 'terms': linkTarget = 'index.php?page=terms'; break;
					case 'uploads': linkTarget = 'index.php?page=account&view=uploads&ctab=course&form=chapter'; break;
					case 'userpanel': linkTarget = ''; break;
					case 'videos': linkTarget = 'index.php?view=videos'; break;
					//case 'watchlist': linkTarget = 'index.php?page=account&view=watchlist'; break;
					default: linkTarget = '#' + linkType;
				}
			}
		});
		if ($(this).prop('tagName') === 'A') {
			$(this).attr('href', linkTarget);
			if (linkOpen == 'new') {
				$(this).attr('target', '_blank');
			}
		} else {
			if (linkOpen == 'new') {
				$(this).click(function () { window.open(linkTarget) });
			} else {
				$(this).click(function () { window.location.assign(linkTarget); });
			}
		}
	});
}

function activate_content() {
	$("#tab_content.initialize").removeClass('initialize').each(function (index, element) {
		var data_id = $(this).attr('data-id');
		$(this).html($("#" + data_id + "_data").html());
	});
	$("input[type=file]").customFile();
	enableSelectBoxes();
	/* $("input:text").not("#search_txt, .maintain").click(function(){ $(this).val(''); });
	$("textarea").click(function(e) {
        if( $(this).val().indexOf('begin typing') >= 0 ) {
			$(this).val('');
		}
    }); */
	$(".vid_preview > img, .vim_preview > img").hover(function () {
		$(this).siblings(".subhead").addClass('color1_dk');
	}, function () {
		$(this).siblings(".subhead").removeClass('color1_dk');
	});
	$(".doc_image").hover(function () {
		$(this).next(".doc_title").addClass('color1_dk');
	}, function () {
		$(this).next(".doc_title").removeClass('color1_dk');
	});
	$(".watched > img, .watched [class^=browse_head]").hover(function () {
		if ($(this).parents(".apanel")) {
			$(this).siblings("[class^=browse_head]").addClass('color1_lt');
		} else {
			$(this).siblings("[class^=browse_head]").addClass('color1_dk');
		}
	}, function () {
		$(this).siblings("[class^=browse_head]").removeClass('color1_dk').removeClass('color1_lt');
	});
	$(".tab").click(function (e) {
		var active_content = $("#tab_content").html();
		var active_data_id = $("#tab_content").attr('data-id');
		var active_target = active_data_id + '_data';
		$("#" + active_target).html(active_content);
		$(this).addClass('active').siblings(".tab").removeClass('active');
		var id = $(this).attr('id');
		var data_id = id + '_data';
		var data = $("#" + data_id).html();
		$("#tab_content").html(data).attr('data-id', id);
		activate_content();
		activate_links();
	});

	$(".btn_watch, .btn_fav, .btn_feature").on("click", function () {
		var id = $(this).attr('id');
		var doc_id = id.replace(/[^0-9]/g, '');
		if ($(this).hasClass('btn_fav') || $(this).hasClass('btn_fav_main')) {
			if ($(this).hasClass('link_favorites')) {
				var call_target = 'favorite_delete';
				$(this).removeClass('btn_active').removeClass('link_favorites');
				$(this).attr('title', 'Add to Favorites');
			}
			else {
				var call_target = 'favorite_add';
				$(this).addClass('btn_active').addClass('link_favorites');
				$(this).attr('title', 'Remove from Favorites');
			}

			var new_val = parseInt($("#count_favorites").val()) + 1;
			$("#count_favorites").val(new_val);
		} else if ($(this).hasClass('btn_watch') || $(this).hasClass('btn_watch_main')) {
			if ($(this).hasClass('link_watchlist')) {
				var call_target = 'watchlist_delete';
				$(this).removeClass('btn_active').removeClass('link_watchlist');
				$(this).attr('title', 'Add to Watchlist');
			}
			else {
				var call_target = 'watchlist_add';
				$(this).addClass('btn_active').addClass('link_watchlist');
				$(this).attr('title', 'Remove from Watchlist');
			}

			var new_val = parseInt($("#count_watchlist").text()) + 1;
			$("#count_watchlist").text(new_val);
		} else if ($(this).hasClass('btn_feature')) {
			if ($(this).hasClass('btn_active')) {
				var call_target = 'featured_remove';
				$(this).removeClass('btn_active').html('<span class="c-tooltiptext">Make Featured</span>');
			}
			else {
				var call_target = 'featured_add';
				$(this).addClass('btn_active').html('<span class="c-tooltiptext">Featured Topic</span>');
			}

		}
		$.ajax({
			type: "POST",
			url: "index.php",
			data: { process: "db_call", call: call_target, doc: doc_id }
		});
		//activate_links();
		event.stopPropagation();
	});




	/* $("[class^=btn_fav],[class^=btn_watch],[class^=btn_feature]").hover(function(){
			$(this).children('.tt_img').removeClass('hidden');
			$(this).not('.btn_active, .btn_main_active').click(function(event){
				var id = $(this).attr('id');
				var doc_id = id.replace( /[^0-9]/g, '' );
				if( $(this).hasClass('btn_fav') || $(this).hasClass('btn_fav_main') ) {
					if ($(this).hasClass('link_favorites')){
						var call_target = 'favorite_delete';
						$(this).removeClass('btn_active').removeClass('link_favorites');
					}
					else{
						var call_target = 'favorite_add';
						$(this).addClass('btn_active').addClass('link_favorites');
					}
					
					var new_val = parseInt($("#count_favorites").val()) + 1;
					$("#count_favorites").val(new_val);
				} else if( $(this).hasClass('btn_watch') || $(this).hasClass('btn_watch_main') ) {
					if ($(this).hasClass('link_watchlist')) {
						var call_target = 'watchlist_delete';
						$(this).removeClass('btn_active').removeClass('link_watchlist');
					}
					else {
						var call_target = 'watchlist_add';
						$(this).addClass('btn_active').addClass('link_watchlist');
					}
					
					var new_val = parseInt($("#count_watchlist").text()) + 1;
					$("#count_watchlist").text(new_val);
				} else if( $(this).hasClass('btn_feature') ) {
					var call_target = 'featured_add';
					$(this).addClass('btn_active').addClass('link_featured');
				}
				$.ajax({
					type: "POST",
					url: "index.php",
					data: { process: "db_call", call: call_target, doc: doc_id }
				});
				//activate_links();
				event.stopPropagation();
			});
			
		},function(){
			$(this).children(".tt_img").addClass('hidden');
		}); */
	$(".btn_mp4,.btn_wmv").hover(function () {
		var tooltip = '<div class="tooltip">';
		if ($(this).hasClass('btn_mp4')) {
			tooltip += 'Download MP4';
		} else if ($(this).hasClass('btn_wmv')) {
			tooltip += 'Download WMV';
		}
		tooltip += '</div>';
		$(this).append(tooltip);
	}, function () {
		$(this).children(".tooltip").remove();
	}).click(function (event) {
		event.stopPropagation();
		var filename = $(this).siblings(".download_name").text();
		if ($(this).hasClass('btn_mp4')) {
			var ext = '.mp4';
		} else if ($(this).hasClass('btn_wmv')) {
			var ext = '.wmv';
		}
		var target = 'videos/' + filename + ext;
		window.open(target);
	});
	$(".fav_delete,.watch_delete,.feature_remove").click(function (event) {
		event.stopPropagation();
		var id = $(this).attr('id');
		var doc_id = id.replace(/[^0-9]/g, '');
		if ($(this).hasClass('fav_delete')) {
			$(this).parents(".ctable_row").hide();
			var call_target = 'favorite_delete';
			var new_val = parseInt($("#count_favorites").text()) - 1;
			$("#count_favorites").text(new_val);
		} else if ($(this).hasClass('watch_delete')) {
			$(this).parents(".watchlist_item").hide();
			var call_target = 'watchlist_delete';
			var new_val = parseInt($("#count_watchlist").text()) - 1;
			$("#count_watchlist").text(new_val);
		} else if ($(this).hasClass('feature_remove')) {
			$(this).removeClass('btn_active');
			var call_target = 'featured_remove';
		}
		$.ajax({
			type: "POST",
			url: "index.php",
			data: { process: "db_call", call: call_target, doc: doc_id }
		});

	});
	/**
	 * Resources
	 */
	$(".tools > .js_delete").click(function (e) {
		var resource = $(this).parents(".resource");
		if (!resource.hasClass('top_ad')) {
			resource.nextAll(".resource.top_ad:first").removeClass('top_ad');
		}
		resource.remove();
	});
	/**
	 * Contributors
	 */
	$(".follow").click(function (e) {
		e.stopPropagation();
		$(this).siblings(".follow_check").toggleClass('hidden');
		if ($(this).text() == 'FOLLOW' && $(this).parent().hasClass('contributor_lg')) {
			$(this).text('UNFOLLOW');
		} else {
			$(this).text('FOLLOW');
		}
	});
	/* $("[class^=contributor_box]").click(function(e) {
			var profile = $(this).html().replace(/contributor_sm/g,'contributor_lg').replace(/contributor /g,'contributor_lg');
			$("#contributors_all").addClass('hidden');
			$("#backtolist").removeClass('hidden');
			$("#contributors_single").removeClass('hidden').children(".contributor_box_lg").html(profile);
			activate_content();
		});
	$("#backtolist").click(function(e) {
			$("#backtolist").addClass('hidden');
			$("#contributors_single").addClass('hidden');
			$("#contributors_all").removeClass('hidden');
			activate_content();
		}); */
	/**
	 * Uploads
	 */
	$(".js_publish").click(function (e) {
		var publish = $(this);
		var status = $(this).siblings(".status");
		var proc_type = $(this).text();
		var data = $(this).parents(".subsection").attr('data-id').split(':');
		$.ajax({
			type: "POST",
			url: "index.php",
			data: { process: 'upload', proc_type: proc_type, type: data[0], id: data[1] }
		}).done(function (html) {
			if (publish.text() == 'Published') {
				publish.text('Unpublished');
			} else {
				publish.text('Published');
			}
			status.toggleClass('crs_green crs_red');
		});
	});
	$(".js_rename").click(function (e) {
		e.stopPropagation();
		var target = $(this).parent().siblings(".js_rename_target");
		var name_holder = target.children("a");
		var name_form = target.children("input");
		name_holder.addClass('hidden');
		name_form.removeClass('hidden').focus().click(function (e) { e.stopPropagation(); }).change(function () {
			name_form.addClass('hidden');
			name_holder.text($(this).val().toUpperCase()).removeClass('hidden');
		});
	});
	$(".line_links > .js_delete").click(function (e) {
		e.stopPropagation();
		$(this).parent().hide().parents(".js_delete_target").css('opacity', '.3');
	});
	$(".subsection > .js_delete").click(function (e) {

		var c = window.confirm("Do you really want to delete this?");
		if (c) {
			$(this).parent().hide().nextAll(".subsection").toggleClass('zebra');
			var id = $(this).parent().data('key');
			var type = $(this).parent().data('type');
			console.log(type);
			if (type == 'Quizzes') {
				process = 'DELETE QUIZ';
				proc_type = 'delete quiz';
			}
			else {
				process = 'DELETE COURSE';
				proc_type = 'delete course';
			}
			$.ajax({
				url: 'index.php',
				method: 'post',
				dataType: 'json',
				data: {
					process: process,
					proc_type: proc_type,
					id: id
				},
				success: function (data) {
					console.log(data);
					if (data.success) {
						$(".message-box").html('<div class="success-message">' + data.message + '</div>');
					}
					else {
						$(".message-box").html('<div class="error-message">' + data.message + '</div>');
					}
				}
			});
		}
	});
	$(".subsection > .js_edit").click(function (e) {
		var id = $(this).parents(".subsection").attr('data-id').split(':');
		var doc_type = id[0];
		switch (doc_type) {
			case 'Quizzes':
				window.location.assign('index.php?page=account&action=edit&type=quiz&id=' + id[1])
				break;
			case 'Courses':
				window.location.assign('index.php?page=account&action=edit&type=topic&id=' + id[1])
				break;
			default:
				break;
		}

	});
	$(".pagenext, .pageprev, .pagelinks a").click(function (e) {
		var pagenum = $(this).attr('data-id');
		var tab = $("#tab_content").attr('data-id');
		var doctype = $("#" + tab).text();
		$.ajax({
			type: "POST",
			url: "index.php",
			data: { process: 'upload', proc_type: 'change page', doctype: doctype, pagenum: pagenum }
		}).done(function (html) {
			$("#tab_content").html(html);
			activate_content();
		});
	});
}

/*function get_account_bar( usr_id, usr_name ) {
	$.get( "inc/modules/account.php",
		{ usr_id: usr_id, usr_name: usr_name },
		function(data) {
			$("#account").html(data);
			activate_links();
		});
}*/

function submitSearch() {
	var search_txt = encodeURIComponent($("#search_txt").val());
	window.location.assign("index.php?page=results&search=" + search_txt);
}

function onPlay(playerID) {
	$(".btn_collapse > .vim_text").hide();
	$(".btn_collapse").animate({ width: "0px", marginRight: "0px" }, { queue: false, duration: 450 });
	/*$("#" + playerID).parent().parent(".contain").addClass('widecontain').removeClass('contain');*/
	var w = $("#" + playerID).parent().width();
	var dim = playerID.split('x');
	if (w < dim[0]) {
		var h = w / dim[0] * dim[1];
	} else {
		var h = 720;
	}
	$("#" + playerID).css('width', w).css('height', h).parent().css('height', h + 40);
	var doc_id = $("#active_doc").text();
	$.ajax({
		type: "POST",
		url: "index.php",
		data: { process: "db_call", call: "history_add", doc: doc_id }
	});
}
function onPause(playerID) {
	$(".btn_collapse").animate({ width: "177px", marginLeft: "49px" }, { queue: false, duration: 450 }).delay(500).children('.vim_text').show();
	$(".fspace_vim").animate({ width: "49px" }, { queue: false, duration: 150 });
	$(".btn_collapse").click(function () {
		$("#" + playerID).css('height', 330).parent().css('height', 370);
		$(".btn_collapse > .vim_text").hide();
		$(".btn_collapse").animate({ width: "0px", marginLeft: "0px" }, { queue: false, duration: 450 });
		$(".fspace_vim").animate({ width: "58px" }, { queue: false, duration: 150 });
	});
	var doc_id = $("#active_doc").text();
	$f(playerID).api('getCurrentTime', function (value, playerID) {
		$.ajax({
			type: "POST",
			url: "index.php",
			data: { process: "db_call", call: "history_update", doc: doc_id, progress: value }
		});
	});

}
function onPlayProgress(data, playerID) {

}

function enableSelectBoxes() {
	$('div.selectBox').each(function () {
		var box = $(this);
		var selected = $(this).find(".selectedOption");
		var selectedSpan = box.find("span.selected");
		selectedSpan.html(selected.html());
		box.attr('value', selected.attr('value'));
		$(this).children(".selectedBox").click(function () {
			var thisSelect = $(this).siblings(".selectOptions");
			$(".selectOptions").not(thisSelect).slideUp(200);
			$(this).siblings(".selectOptions").slideToggle(300).find(".selectOption").click(function (e) {
				$(this).addClass('selectedOption').siblings().removeClass('selectedOption');
				$(this).parent().hide();
				selectedSpan.html($(this).html());
				selectedSpan.trigger('setNextSelector');
				box.attr('value', $(this).attr('value'));
				var identifier = box.attr('id').replace('_selector', '');
				$("#" + identifier + "_selected").attr('value', $(this).attr('value'));
			});
		});
	});
	$(".trigger").on('setNextSelector', function () {
		console.log("It is triggered");
		var trigger = $(this);
		var proc_types = $(this).attr('data-id').split(' ');
		$.each(proc_types, function (index, process) {
			var identifier = process.replace('get_', '');
			var selected = $(trigger).parents(".selectBox").find(".selectedOption").attr('value');
			var add_option = true;
			if ($("#" + identifier + "_selector").hasClass('no_add')) add_option = false;
			var extra = false;
			switch (identifier) {
				case 'folder':
					if ($("#cat_selector")) extra = $("#cat_selector").attr('value');
					break;
				case 'folderc':
					extra = 'self';
					break;
			}
			$.ajax({
				type: "POST",
				url: "index.php",
				data: { process: "upload", proc_type: process, selected: selected, add_option: add_option, extra: extra }
			}).done(function (html) {
				$("#" + identifier + "_selector").find(".selectOptions").html(html);
				$("#" + identifier + "_selector").find(".selected").html('');
			});
		});
	});
	$(".reveal").on('setNextSelector', function () {
		if ($(this).text().indexOf('ADD NEW') >= 0) {
			var identifier = $(this).parents(".selectBox").attr('id').replace('_selector', '');
			console.log(identifier);
			var name = $("#" + identifier + "_selected").attr('name');
			$("#" + identifier + "_selected").attr({
				value: 'enter new ' + identifier + ' here',
				type: 'text',
				name: 'new' + name
			});
		}
	});
}

function add_multi_answer() {
	var add_link = $(".multi_add");
	var index = parseInt($(".multi_last").attr('value'), 10) + 1;
	$(".multi_last").removeClass('multi_last');
	var html = '<input type="checkbox" class="multi_last" name="correct_multi_' + index + '" value="' + index + '"><input type="text" class="multi_answer" name="multi_' + index + '">';
	add_link.before(html);
}

function activate_comments() {
	$(".comment_left").find(".js_reply").click(function (e) {
		$("form#reply").remove();
		var form = $("#reply_form").children(".reply_hold").clone();
		form.removeClass('reply_hold').attr('id', 'reply');
		var comment_box = $(this).parents(".comment_sub").parent();
		comment_box.after(form);
		comment_box.siblings("#reply").children(".comment").focus();
		$("form#reply").submit(function () {
			var profile_source = $(this).children("input[name=profile_source]").val();
			var name = $(this).children("input[name=full_name]").val();
			var process = $(this).children(".btn_comment").val();
			var msg = $(this).children(".comment").val();
			$(this).remove();
			$.ajax({
				type: "POST",
				url: "index.php",
				data: { process: process, profile: profile_source, name: name, msg: msg }
			}).done(function (html) {
				comment_box.after(html);
			});
			return false;
		});
	});
	$(".comment_left").find(".js_share").click(function (e) {
		$("form#reply").remove();
		var name = $(this).parent().siblings(".comment_name").html();
		var date = $(this).parent().siblings(".comment_date").html();
		var comment = $(this).parents(".comment_sub").siblings(".comment_box").html();
		var message = 'Check out this comment from ' + name + ' (' + date + '):\n"' + comment + '"\n';
		$("textarea[name=message_text]").html(message);
		$("#comment_panel").slideUp(200);
		if ($("#message_panel").hasClass('hidden')) {
			$("#message_panel").hide().removeClass('hidden');
		}
		$("#message_panel").slideToggle(400);
	});
	$(".comment_left").find(".js_remove").click(function (e) {
		$("form#reply").remove();
		//var response = confirm("Are you sure you want to delete this comment?");
		//if( response == true ) {
		$(this).parents(".comment_sub").parent().remove();
		//}
	});
}

$(document).on('change', '#section-dropdown', function () {
	var value = $(this).val();
	if (value.length == 0) {
		//empty folder dropdown
		$("#folder-dropdown").empty();
		var content = "<option value=''>Select Section First</option>";
		$("#folder-dropdown").append(content);

		//empty course dropdown
		$("#course-dropdown").empty();
		var content = "<option value=''>Select Topic First</option>";
		$("#course-dropdown").append(content);

		//empty lesson dropdown
		$("#lesson-dropdown").empty();
		var content = "<option value=''>Select Topic First</option>";
		$("#lesson-dropdown").append(content);

		//empty assets dropdown
		$("#assets-dropdown").empty();
		var content = "<option value=''>Select Topic First</option>";
		$("#assets-dropdown").append(content);

		$(".new_section").hide();
		$("#new-section-input").removeAttr('required');
		$("#folder-dropdown").val('');

		$(".new_folder").hide();
		$("#new-folder-input").removeAttr('required');

		return;
	}

	if (value == 'new_section') {
		$(".new_section").show();
		$("#new-section-input").attr('required', 'required');
		$("#folder-dropdown").val('new_folder');

		$(".new_folder").show();
		$("#new-folder-input").attr('required', 'required');
		return;
	}
	else {
		$(".new_section").hide();
		$("#new-section-input").removeAttr('required');
		$("#folder-dropdown").val('');

		$(".new_folder").hide();
		$("#new-folder-input").removeAttr('required');
	}



	$.ajax({
		url: 'ajax.php',
		data: {
			section: value,
			get: 'folders'
		},
		method: 'post',
		dataType: 'json',
		success: function (res) {
			$("#folder-dropdown").empty();
			var content = "<option value=''>Select Folder</option>";
			$("#folder-dropdown").append(content);
			$.each(res, function (key, value) {
				var content = "<option value='" + key + "'>" + value + "</option>";
				$("#folder-dropdown").append(content);
			});

			if ($("#proc_type").val() == 'add course') {
				var content = "<option value='add_new_folder'>ADD NEW FOLDER</option>";
				$("#folder-dropdown").append(content);
			}

		},
		error: function (err) {
			console.log(err);
		}
	})
});

$(document).on('change', '#folder-dropdown', function () {
	var value = $(this).val();
	if (value.length == 0) {

		//empty course dropdown
		$("#course-dropdown").empty();
		var content = "<option value=''>Select Topic First</option>";
		$("#course-dropdown").append(content);

		//empty lesson dropdown
		$("#lesson-dropdown").empty();
		var content = "<option value=''>Select Topic First</option>";
		$("#lesson-dropdown").append(content);

		//empty assets dropdown
		$("#assets-dropdown").empty();
		var content = "<option value=''>Select Topic First</option>";
		$("#assets-dropdown").append(content);

		return;
	}
	if ($("#proc_type").val() == 'add course') {
		if (value == 'add_new_folder') {
			$(".new_folder").show();
			$("#new-folder-input").attr('required', true);
		}
		else {
			$(".new_folder").hide();
			$("#new-folder-input").removeAttr('required', true);
		}
	}

	$.ajax({
		url: 'ajax.php',
		data: {
			folder: value,
			get: 'courses'
		},
		method: 'post',
		dataType: 'json',
		success: function (res) {
			$("#course-dropdown").empty();
			var content = "<option value=''>Select Topic</option>";
			$("#course-dropdown").append(content);
			$.each(res, function (key, value) {
				var content = "<option value='" + key + "'>" + value + "</option>";
				$("#course-dropdown").append(content);
			});
		},
		error: function (err) {
			console.log(err);
		}
	})
})

$(document).on('change', '#course-dropdown', function () {
	var value = $(this).val();
	if (value.length == 0) {


		//empty lesson dropdown
		$("#lesson-dropdown").empty();
		var content = "<option value=''>Select Topic First</option>";
		$("#lesson-dropdown").append(content);

		//empty assets dropdown
		$("#assets-dropdown").empty();
		var content = "<option value=''>Select Topic First</option>";
		$("#assets-dropdown").append(content);

		return;
	}
	$.ajax({
		url: 'ajax.php',
		data: {
			course: value,
			get: 'lessons'
		},
		method: 'post',
		dataType: 'json',
		success: function (res) {
			$("#lesson-dropdown").empty();
			var content = "<option value=''>Select Chapter</option>";
			$("#lesson-dropdown").append(content);
			$.each(res.lessons, function (key, value) {
				var content = "<option value='" + key + "'>" + value + "</option>";
				$("#lesson-dropdown").append(content);
			});
			var content = "<option value='new'>ADD NEW CHAPTER</option>";
			$("#lesson-dropdown").append(content);


			$("#asset-dropdown").empty();
			var content = "<option value=''>Select Asset</option>";
			$("#asset-dropdown").append(content);
			$.each(res.assets, function (key, value) {
				var content = "<option value='" + key + "'>" + value + "</option>";
				$("#asset-dropdown").append(content);
			});
		},
		error: function (err) {
			console.log(err);
		}
	})
})

$(document).on('change', '#lesson-dropdown', function () {
	var value = $(this).val();
	if (value == 'new') {
		$(".new-lesson").show();
		$("#new-lesson-input").attr('required', true);
	}
	else {
		$(".new-lesson").hide();
		$("#new-lesson-input").removeAttr('required');

		//get parts for this chapter or you can say get chapters for this lesoon in old way
		$.ajax({
			url: 'ajax.php',
			data: {
				lesson: value,
				get: 'chapters'
			},
			method: 'post',
			dataType: 'json',
			success: function (res) {
				$("#chapter-dropdown").empty();
				var content = "<option value=''>Select Part</option>";
				$("#chapter-dropdown").append(content);
				$.each(res.chapters, function (key, value) {
					var content = "<option value='" + key + "'>" + value + "</option>";
					$("#chapter-dropdown").append(content);
				});
			},
			error: function (err) {
				console.log(err);
			}
		})
	}
})


$(document).on('change', '#asset-section-dropdown', function () {
	var value = $(this).val();
	if (value.length == 0) {
		//empty folder dropdown
		$("#asset-folder-dropdown").empty();
		var content = "<option value=''>Select Section First</option>";
		$("#asset-folder-dropdown").append(content);

		//empty files dropdown
		$("#files-dropdown").empty();
		var content = "<option value=''>Select Folder First</option>";
		$("#files-dropdown").append(content);
		return;
	}
	$.ajax({
		url: 'ajax.php',
		data: {
			section: value,
			get: 'folders'
		},
		method: 'post',
		dataType: 'json',
		success: function (res) {
			$("#asset-folder-dropdown").empty();
			var content = "<option value=''>Select Folder</option>";
			$("#asset-folder-dropdown").append(content);
			$.each(res, function (key, value) {
				var content = "<option value='" + key + "'>" + value + "</option>";
				$("#asset-folder-dropdown").append(content);
			});
		},
		error: function (err) {
			console.log(err);
		}
	})
});

$(document).on('change', '#asset-folder-dropdown', function () {
	var value = $(this).val();
	if (value.length == 0) {
		//empty files dropdown
		$("#files-dropdown").empty();
		var content = "<option value=''>Select Folder First</option>";
		$("#files-dropdown").append(content);
		return;
	}
	$.ajax({
		url: 'ajax.php',
		data: {
			folder: value,
			get: 'files'
		},
		method: 'post',
		dataType: 'json',
		success: function (res) {
			$("#files-dropdown").empty();
			var content = "<option value=''>Select Folder</option>";
			$("#files-dropdown").append(content);
			$.each(res, function (key, value) {
				var content = "<option value='" + key + "'>" + value + "</option>";
				$("#files-dropdown").append(content);
			});
		},
		error: function (err) {
			console.log(err);
		}
	})
});

$(document).on('change', '#question-type-dropdown', function () {
	var question_type = $(this).val();
	console.log(question_type);
	if (question_type == 'TF') {
		$("#answer_truefalse").removeClass('hidden');
		$("#answer_multiple").addClass('hidden');
	}
	else {
		$("#answer_truefalse").addClass('hidden');
		$("#answer_multiple").removeClass('hidden');
	}
});
$(document).on("click", "#message-contributor-btn", function () {
	$(".message-contributor-container").toggle('slow');
})

$(document).on("click", "#message-contributor-send-btn", function () {
	var message = $("#message-contributor-input").val();
	var receiver_id = $("#receiver_id").val();
	$.ajax({
		url: 'ajax.php',
		data: {
			message: message,
			receiver_id: receiver_id,
			get: 'send message'
		},
		method: 'post',
		dataType: 'json',
		success: function (res) {
			if (res.success) {
				$(".message-contributor-container").hide();
				$(".process-message").addClass('success-message').html(res.message);
			}
		},
		error: function (err) {
			console.log(err);
		}
	})
});

$(document).on("click", ".approve-user", function () {
	var confirm = window.confirm('Do you want to approve this user?');
	if (!confirm) {
		return false;
	}

	var user_id = $(this).data('id');

	$.ajax({
		url: 'ajax.php',
		data: {
			user_id: user_id,
			get: 'approve-user'
		},
		method: 'post',
		dataType: 'json',
		success: function (res) {
			window.location.reload();
		},
		error: function (err) {
			console.log(err);
		}
	})

});

$(document).on("click", ".block-user", function () {
	var confirm = window.confirm('Do you want to block this user?');
	if (!confirm) {
		return false;
	}

	var user_id = $(this).data('id');

	$.ajax({
		url: 'ajax.php',
		data: {
			user_id: user_id,
			get: 'block-user'
		},
		method: 'post',
		dataType: 'json',
		success: function (res) {
			window.location.reload();
		},
		error: function (err) {
			console.log(err);
		}
	})

});

$(document).on("click", ".unblock-user", function () {
	var confirm = window.confirm('Do you want to unblock this user?');
	if (!confirm) {
		return false;
	}

	var user_id = $(this).data('id');

	$.ajax({
		url: 'ajax.php',
		data: {
			user_id: user_id,
			get: 'unblock-user'
		},
		method: 'post',
		dataType: 'json',
		success: function (res) {
			window.location.reload();
		},
		error: function (err) {
			console.log(err);
		}
	})

});
$(document).on("click", ".delete-folder", function () {
	var confirm = window.confirm("Do you really want to delete this?");
	if (confirm) {
		var id = $(this).data('id');
		$this = $(this);
		$.ajax({
			url: 'index.php',
			method: 'post',
			dataType: 'json',
			data: {
				process: 'DELETE FOLDER',
				proc_type: 'delete folder',
				id: id
			},
			success: function (data) {
				if (data.success) {
					$(".message-box").html('<div class="success-message">' + data.message + '</div>');
					$this.parent().parent().remove();
				}
				else {
					$(".message-box").html('<div class="error-message">' + data.message + '</div>');
				}

			},
			error: function (err) {
				console.log(err);
			}

		})
	}

})