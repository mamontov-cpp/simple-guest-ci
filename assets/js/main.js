/**
 * Показывает постраничку
 * @param {Number} pageCount число страниц
 * @param {Number} currentPage текущая страница
 */
var showPagination = function(pageCount, currentPage)  {
	// 7 определено таким образом - первая + последняя + по две обрамляющие кнопки с одной стороны для текущей страницы
	var result = "", i = 1;
	var row  = $(".pagination-row");
	if (pageCount === 0) {
		row.html("");
	}
	if (pageCount <= 7) {
		for (;i <= pageCount; i++) {
			if (i !== currentPage) {
				result += "<div class=\"pagination-item pagination-item-active\"><a href=\"/ajax/posts_get_page/" + i + "/\">" + i + "</a></div>";
			} else {
				result += "<div class=\"pagination-item pagination-item-current\">" + i + "</div>";
			}
		}
	} else {
		if (currentPage < 5) {
			for (; i <= 6; i++) {
				if (i !== currentPage) {
					result += "<div class=\"pagination-item pagination-item-active\"><a href=\"/ajax/posts_get_page/" + i + "/\">" + i + "</a></div>";
				} else {
					result += "<div class=\"pagination-item pagination-item-current\">" + i + "</div>";
				}
			}
			result += "<div class=\"pagination-item\">...</div>";
			result += "<div class=\"pagination-item pagination-item-active\"><a href=\"/ajax/posts_get_page/" + pageCount + "/\">" + pageCount + "</a></div>";
		} else {
			if (pageCount - currentPage < 4) {
				result += "<div class=\"pagination-item pagination-item-active\"><a href=\"/ajax/posts_get_page/1/\">1</a></div>";
				result += "<div class=\"pagination-item\">...</div>";
				for (i = pageCount - 5; i <= pageCount; i++) {
					if (i !== currentPage) {
						result += "<div class=\"pagination-item pagination-item-active\"><a href=\"/ajax/posts_get_page/" + i + "/\">" + i + "</a></div>";
					} else {
						result += "<div class=\"pagination-item pagination-item-current\">" + i + "</div>";
					}
				}
			} else {
				result += "<div class=\"pagination-item pagination-item-active\"><a href=\"/ajax/posts_get_page/1/\">1</a></div>";
				result += "<div class=\"pagination-item\">...</div>";
				for (i = currentPage - 2; i <= currentPage + 2; i++) {
					if (i !== currentPage) {
						result += "<div class=\"pagination-item pagination-item-active\"><a href=\"/ajax/posts_get_page/" + i + "/\">" + i + "</a></div>";
					} else {
						result += "<div class=\"pagination-item pagination-item-current\">" + i + "</div>";
					}
				}
				result += "<div class=\"pagination-item\">...</div>";
				result += "<div class=\"pagination-item pagination-item-active\"><a href=\"/ajax/posts_get_page/" + pageCount + "/\">" + pageCount + "</a></div>";
			}
		}
	}
	row.html(result);
};
/**
 * Показывает страницу сайта
 * @var {Array}  данные для отображения
 */
var showPage = function(items) {
	var result = "";
	for(var i = 0; i < items.length; i++) {
		result += "<section data-id=\"" + items[i]["id"]  + "\" class=\"main large-block post\">";
		result += "<span class=\"posts-name\"><a href=\"mailto:" + items[i]["user"] + "\">" + items[i]["user"] +"</a></span>";
		result += "<span class=\"posts-date\">" + items[i]["date"] +"</span>";
		// noinspection EqualityComparisonWithCoercionJS
		if (items[i]["user_id"] == window["userId"]) {
			result += "<a href=\"javascript: void(0);\" class=\"delete\"><i class=\"fa fa-trash\"></i></a>";
		}
		result += "<div class=\"posts-text\">" + items[i]["text"] +"</div>";
		result += "</section>";
	}
	$(".content-wrapper").html(result);
};

(function($) {
	skel.breakpoints({
		xlarge: '(max-width: 1680px)',
		large: '(max-width: 1280px)',
		medium: '(max-width: 980px)',
		small: '(max-width: 736px)',
		xsmall: '(max-width: 480px)',
		xxsmall: '(max-width: 360px)'
	});
	$(function() {
		var	$window = $(window), $body = $('body');
		// Отключить анимации пока страница не пргрузилась
		$body.addClass('is-loading');

		$window.on('load', function() {
			window.setTimeout(function() {
				$body.removeClass('is-loading');
			}, 100);
		});
	});
	$(document).ready(function() {
		// Добавление нового поста
		$("#send-new-post").click(function(event) {
			event.preventDefault();
			var  btn = $("#send-new-post");
			if (btn.hasClass("disabled")) {
				return false;
			}
			btn.addClass("disabled");
			$("#errorMessage").html("");
			$.post(
				"/ajax/posts/add/", {
					"text": $("textarea[name=text]").val(),
					"csrf_test_name": $("input[name=\"csrf_test_name\"]").val(),
					"captcha_word": $("input[name=\"captcha_word\"]").val(),
					"captcha_id": $("input[name=\"captcha_id\"]").val()
				},
				function(o) {
					var data = JSON.parse(o);
					if (data) {
						if ('error' in data) {
							$("#errorMessage").html("<p>" + data['error'] + "</p>");
						} else {
							showPagination(data["pages"], data["current_page"]);
							showPage(data["items"]);
							// Скроллим куски
							$([document.documentElement, document.body]).animate({
								scrollTop: $(".content-wrapper").offset().top
							}, 500);
							$("#errorMessage").html("<p class=\"ok\">Ваш комментарий успешно добавлен!</p>");
						}
						$("#Imageid").replaceWith(data["captcha_image"]);
						$("input[name=\"captcha_id\"]").val(data["captcha_id"]);
						$("input[name=\"csrf_test_name\"]").val(data["csrf_hash"]);
						window["csrf_hash"] = data["csrf_hash"];
					} else {
						$("#errorMessage").html("<p>Не удалось добавить комментарий. Пожалуйста, попробуйте ещё раз.</p>");
					}
					btn.removeClass("disabled");
					$("input[name=\"captcha_word\"]").val("");
				}
			).fail(function() {
				$("#errorMessage").html("<p>Не удалось добавить комментарий. Пожалуйста, попробуйте ещё раз.</p>");
				btn.removeClass("disabled");
			});
			return false;
		});
		window["isLoadingPage"] = false;
		// Работа постранички
		$("body").on("click", ".pagination a", function(event) {
			event.preventDefault();
			if (window["isLoadingPage"]) {
				return false;
			}
			$(".content-wrapper #page-load-errors").remove();
			window["isLoadingPage"] = true;
			var href = $(this).attr("href");
			$.get(href, {}, function(o) {
				var data = JSON.parse(o);
				if (data) {
					showPagination(data["pageCount"], data["page"]);
					showPage(data["items"]);
				} else {
					$(".content-wrapper").prepend("<div id=\"page-load-errors\" class=\"main large-block\"><div class=\"errorMessages\"><p>Не удалось загрузить страницу. Пожалуйста, попробуйте ещё раз</p></div></div>");
				}
				window["isLoadingPage"] = false;
			}).fail(function() {
				$(".content-wrapper").prepend("<div id=\"page-load-errors\" class=\"main large-block\"><div class=\"errorMessages\"><p>Не удалось загрузить страницу. Пожалуйста, попробуйте ещё раз</p></div></div>");
				window["isLoadingPage"] = false;
			});
			return false;
		});
		$("body").on("click", ".content-wrapper .delete", function(event) {
			event.preventDefault();
			if (window["isLoadingPage"]) {
				return false;
			}
			$(".content-wrapper #page-load-errors").remove();
			window["isLoadingPage"] = true;
			var section = $(this).closest("section");
			var id = section.attr("data-id");
			$.post("/ajax/posts/delete/", {
				"csrf_test_name": window["csrf_hash"],
				"id" : id
			}, function(o) {
				var data = JSON.parse(o);
				if (data) {
					if ('error' in data) {
						$(".content-wrapper").prepend("<div id=\"page-load-errors\" class=\"main large-block\"><div class=\"errorMessages\"><p>" + data['error'] + "</p></div></div>");
					} else {
						section.html("<p>Пост удалён</p>")
					}
					$("input[name=\"csrf_test_name\"]").val(data["csrf_hash"]);
					window["csrf_hash"] = data["csrf_hash"];
				} else {
					$(".content-wrapper").prepend("<div id=\"page-load-errors\" class=\"main large-block\"><div class=\"errorMessages\"><p>Не удалось загрузить страницу. Пожалуйста, попробуйте ещё раз</p></div></div>");
				}
				window["isLoadingPage"] = false;
			}).fail(function() {
				$(".content-wrapper").prepend("<div id=\"page-load-errors\" class=\"main large-block\"><div class=\"errorMessages\"><p>Не удалось загрузить страницу. Пожалуйста, попробуйте ещё раз</p></div></div>");
				window["isLoadingPage"] = false;
			});
			return false;
		});
	});
})(jQuery);