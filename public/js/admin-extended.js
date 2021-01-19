(function ($) {
    var AdminExtended = {
        init: function () {
            if (location.href.indexOf('admin/annuity') > 0) {
                $('.add').click(function (e) {
                    setTimeout(function () {
                        for (let i = 0; i < $('.item_index').length; i++) {
                            $($('.item_index')[i]).html(i + 1);
                        }
                    }, 100);
                });
            }
        },
    };
    window.AdminExtended = AdminExtended;
})(jQuery);

$(document).ready(function () {
    AdminExtended.init();
});
