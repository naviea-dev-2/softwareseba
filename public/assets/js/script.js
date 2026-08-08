
    //Menu active
    var href = location.href;
    var elem = '.left-navbar li a[href="' + href + '"]';

    $("ul.left-navbar li").parent().removeClass("active");
    $("ul.left-navbar li a").parent().removeClass("active");

    var parentClase = $(elem).parents(".dropdown");
    if (parentClase.length) {
        $(parentClase).addClass("active");
        $(elem).parent().addClass("active");
    } else {
        $(elem).addClass("active");
    }

