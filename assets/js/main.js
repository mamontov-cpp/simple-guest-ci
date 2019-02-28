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
				result += "<div class=\"pagination-item pagination-item-active\"><a href=\"/ajax/posts/get_page/" + i + "/\">" + i + "</a></div>";
			} else {
				result += "<div class=\"pagination-item pagination-item-current\">" + i + "</div>";
			}
		}
	} else {
		if (i < 5) {
			for (; i <= 6; i++) {
				if (i !== currentPage) {
					result += "<div class=\"pagination-item pagination-item-active\"><a href=\"/ajax/posts/get_page/" + i + "/\">" + i + "</a></div>";
				} else {
					result += "<div class=\"pagination-item pagination-item-current\">" + i + "</div>";
				}
			}
			result += "<div class=\"pagination-item\">...</div>";
			result += "<div class=\"pagination-item pagination-item-active\"><a href=\"/ajax/posts/get_page/" + pageCount + "/\">" + pageCount + "</a></div>";
		} else {
			if (pageCount - currentPage < 4) {
				result += "<div class=\"pagination-item pagination-item-active\"><a href=\"/ajax/posts/get_page/1/\">1</a></div>";
				result += "<div class=\"pagination-item\">...</div>";
				for (i = pageCount - 5; i <= pageCount; i++) {
					if (i !== currentPage) {
						result += "<div class=\"pagination-item pagination-item-active\"><a href=\"/ajax/posts/get_page/" + i + "/\">" + i + "</a></div>";
					} else {
						result += "<div class=\"pagination-item pagination-item-current\">" + i + "</div>";
					}
				}
			} else {
				result += "<div class=\"pagination-item pagination-item-active\"><a href=\"/ajax/posts/get_page/1/\">1</a></div>";
				result += "<div class=\"pagination-item\">...</div>";
				for (i = currentPage - 2; i <= currentPage + 2; i++) {
					if (i !== currentPage) {
						result += "<div class=\"pagination-item pagination-item-active\"><a href=\"/ajax/posts/get_page/" + i + "/\">" + i + "</a></div>";
					} else {
						result += "<div class=\"pagination-item pagination-item-current\">" + i + "</div>";
					}
					result += "<div class=\"pagination-item\">...</div>";
					result += "<div class=\"pagination-item pagination-item-active\"><a href=\"/ajax/posts/get_page/" + pageCount + "/\">" + pageCount + "</a></div>";
				}
			}
		}
	}
	row.html(result);
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
			console.log("OK");
			return false;
		});
	});
})(jQuery);