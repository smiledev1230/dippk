// JavaScript Document
$(document).ready(function() {
	//SideNav
	$(".crs_sidenav > .title").click(function(e) {
		var data = $(this).next(".data");
        data.slideToggle(300);
		fill_progress_bar();
		data.siblings(".data").slideUp(200);
    });
	$(".crs_icon_plus, .crs_icon_minus").click(function(e) {
        $(this).parent().siblings(".data").slideToggle(300);
		$(this).toggleClass('crs_icon_plus crs_icon_minus');
    });
	activate_crslinks();
	fill_progress_bar();
	$("#lsn_scroll").mCustomScrollbar({
		callbacks:{
			onTotalScroll:function(){
				$("[id*=chp_next-]").addClass('active');
			},
    		onTotalScrollOffset:200
		}	
	});
	$(".chp_vim iframe").each(function(index, element) {
		var w = $(this).parent().width();
		var dim = $(this).attr('id').split('x');
		if( w < dim[0] ) {
			var h = w / dim[0] * dim[1];
		} else {
			var h = 365.625;
		}
		$(this).css('width',w).css('height',h); 
    });
	activate_lesson_links();
	$(".expand").click(function(e) {
		if( $(this).text() == 'expand >>>' ) {
			$(this).text('<<< collapse');
		} else {
			$(this).text('expand >>>');
		}
        $("#main > .crs_right").toggle();
		$("#main > .crs_left").toggleClass('wide');
		$(".pancontainer").toggleClass('wide');
		size_pancontainer();
	});
	activate_editors();
});

function fill_progress_bar() {
	$(".crs_progress").each(function(index, element) {
           var target_width = $(this).children(".crs_progress_text").text();
		   $(this).children(".crs_progress_fill").css('width',0).animate( {width: target_width}, 1000 ); 
        });
}

function activate_crslinks() {
	$("[class*=link_crs_]").each(function(index, linkElement) {
		var root = 'link_crs_';
    	var linkTarget = '#test';
		var linkOpen = 'same';
		var classList = $(this).attr('class').split(/\s+/);
		$.each( classList, function(index2, classString){
			if( classString.substr(0,root.length) == root ) {
				var linkType = classString.replace(root,'');
				switch( linkType ) {
					case 'chapter':
						var chapter = $(linkElement).data('chapter');
						var lesson = $(linkElement).data('lesson');
						linkTarget = 'index.php?page=topic&view=chapter&part=' + chapter + '&chapter=' + lesson;
						//linkTarget = 'index.php?page=course&view=lesson&chapter=' + chapter;
						break;
					case 'edit':
						var course = $(linkElement).attr('data-id').replace( /[^0-9]/g, '' );
						linkTarget = 'index.php?page=editor&view=topic&id='+course;
						break;
					case 'editc':
						var ids = $(linkElement).attr('data-id').split(':');
						var course = ids[0].replace( /[^0-9]/g, '' );
						var chapter = ids[1].replace( /[^0-9]/g, '' );
						linkTarget = 'index.php?page=editor&view=course&id='+course+'&chapter=' + chapter;
						break;
					case 'editq':
						var ids = $(linkElement).attr('id').split(':');
						var quiz = ids[0].replace( /[^0-9]/g, '' );
						var question = ids[1].replace( /[^0-9]/g, '' );
						linkTarget = 'index.php?page=editor&view=quiz&id='+quiz+'&question=' + question;
						break;
					case 'lesson':
						var lsn = false;
						if( $(linkElement).attr('id') !== undefined ) {
							lsn = $(linkElement).attr('id').replace( /[^0-9]/g, '' );
						}
						linkTarget = 'index.php?page=topic&view=chapter';
						if( lsn ) linkTarget += '&chapter=' + lsn;
						break;
					case 'quiz':
						var quiz = $(linkElement).attr('id').replace( /[^0-9]/g, '' );
						linkTarget = 'index.php?page=course&view=lesson&quiz=' + quiz;
						break;
					case 'results': linkTarget = 'index.php?page=topic&view=results'; break;
					case 'welcome': linkTarget = 'index.php?page=topic&view=welcome'; break;
					default: linkTarget = '#' + linkType;
				}
			}
		});
		if( $(this).prop('tagName') === 'A' ) {
			$(this).attr('href', linkTarget);
			if( linkOpen == 'new' ) {
				$(this).attr('target', '_blank');
			}
		} else {
			if( linkOpen == 'new' ) {
				$(this).click(function(){ window.open( linkTarget ) });
			} else {
				$(this).click(function(){ window.location.assign( linkTarget ); });
			}
		}
    });
}

$(document).on('click','.complete_topic',function(){
	course = $(this).data('course');
	lesson = $(this).data('lesson');
	chapter = $(this).data('chapter');
	$this = $(this);
	$.ajax({
		url: 'index.php',
		method: 'post',
		dataType: 'json',
		beforeSend: function () {
			$(this).html('Finishing...');
		},
		complete: function () {
			$(this).html('Finish Topic');
		},
		data: {
			process: 'COMPLETE COURSE',
			proc_type: 'complete course',
			course: course,
			lesson: lesson,
			chapter: chapter,
		},
		success: function (data) {
			if(data.success){
				window.location.assign('index.php?page=topic&view=results');
			}
		},
		error: function (err) {
			console.log(err);
		}
	});
})
function activate_lesson_links() {
	$(".lsn_pagenav_btn").click(function(event) {
		var page = $(this).attr('data-id');
		var path = $("#path").text();
		var ext = $("#extension").text();
		var panwidth = '';
		if( $(".pancontainer").hasClass('wide') ) panwidth = 'wide';
		$.ajax({
			type: "POST",
			url: "courseware/loaders/multi.php",
			data: { ajax: true, page: page, path: path, extension: ext }
			}).done(function(html){
				$("#lsn_container").html(html);
				$(".pancontainer").addClass(panwidth);
				$("#img_primary").on('load',function(){
					init_pancontainer();
					activate_lesson_links();
				});
			});
	});
}

function activate_editors() {
	$("[class*=crs_edit_]").click(function(e) {
        var id = $(this).attr('class').replace('crs_edit_','');
		$("#crs_actv_"+id).toggleClass('hidden');
		$("#crs_edit_"+id).toggleClass('hidden');
	});
}