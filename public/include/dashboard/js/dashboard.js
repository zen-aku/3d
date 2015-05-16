(function ($) {

    var Dashboard = {
        initFunction: function () {
            $('#main-navigation').metisMenu();

            $(window).bind("load resize", function () {
                if ($(this).width() < 768) {
                    $('div.sidebar-collapse').addClass('collapse');
                } else {
                    $('div.sidebar-collapse').removeClass('collapse');
                }
            });

            $('.clear-cache').each(function () {
                $(this).bind('click', function (event) {
                    event.preventDefault();
                    $.ajax('/application/index/clear-cache', {
                        dataType: 'json',
                        data: {
                            key: $(this).attr('data-cache-key')
                        },
                        method: 'POST',
                        beforeSend: function () {
                            //show loader
                            $('.page-wrapper').append($('<div/>', {'class': 'loader-cube'}));
                        },
                        success: function (response) {
                            if (response.status === 'success') {
                                window.location.reload();
                            }
                        },
                        complete: function () {
                            //hide loader
                            $('.page-wrapper .loader-cube').remove();
                        }
                    });
                });
            });

            //navigation toggler
            $('.navbar-hide').bind('click', function (event) {
                event.preventDefault();
                $('.sidebar-collapse, .navbar-hide').hide();
                $('.navbar-side').addClass('minimized');
                $('.page-wrapper').addClass('full-width');
                setTimeout(function () {
                    $('.navbar-side').bind('mouseover', function () {
                        $('.page-wrapper').removeClass('full-width');
                        $('.navbar-side').removeClass('minimized');
                        setTimeout(function() {
                            $('.sidebar-collapse, .navbar-hide').show();
                        }, 500);
                        $(this).unbind('mouseover');
                    });
                }, 700);
            });
        },
        initialization: function () {
            Dashboard.initFunction();

        }
    };

    $(document).ready(function () {
        Dashboard.initFunction();
    });

}(jQuery));
