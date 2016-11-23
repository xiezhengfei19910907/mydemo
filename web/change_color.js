$('.prod-info-styles li .pis-color-box .pis-color-a .pis-color').click(function () {
    var goodsID = $('input:hidden[ name = goods_id ]')[0].value;
    if (goodsID != '66283') {
        return false;
    }

    var color = $(this).attr('data-kvalue');

    $('#thumblist li a').removeClass('zoomThumbActive');

    $('#thumblist li a img').each(function (index, element) {
        if (((color == undefined) && ($(element).attr('color') == '')) || ($(element).attr('kcolor') == color)) {
            $(element).click();

            var itemList = $('.carousel').find("ul");
            itemList.find("li:lt(" + index + ")").appendTo(itemList);
        }
    });
});