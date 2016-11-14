;(function(){
    var $sideBar = $('.bs-docs-sidebar');
    var sideBarTop = $sideBar.offset().top - 60;
    var tops = {};

    $('h1[id],h2[id],h3[id]').each(function(e, element){
        tops[$(element).offset().top] = element.id;
    });

    var getIDByCurrentTop = function(currentTop){
        for (var top in tops) {
            if (currentTop + 50 > top && currentTop - 50 < top) {
                return tops[top];
            }
        }

        return false;
    };

    $(document).scroll(function(){
        var currentTop = $(document).scrollTop();
        var id = getIDByCurrentTop(currentTop);

        if (id) {
            $('ul li').removeClass('active');
            $('ul a[href="#'+id+'"]').parent().addClass('active');
        }

        if (currentTop > sideBarTop) {
            $sideBar.css('position', 'fixed');
        }
        else {
            $sideBar.css('position', 'static');
        }
    });
})();