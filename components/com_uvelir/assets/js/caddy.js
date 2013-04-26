    
    var $ = jQuery;
    
    /**
     * Добавление товара в корзину
     */
    function uvelir_caddy_add(params)
    {
        var del_btn = '#del_'+params.data.item_id;
        var span_count = '#count_span_'+params.data.item_id;
        var li_count = '#count_li_'+params.data.item_id;
        var count = parseInt($(span_count).text());
        count ++;
        $(del_btn).show('slow');
        $(li_count).show('slow');
        $(span_count).text(count);
        
        to_caddy_send_data(params);
    }

    /**
     * Удаление товара из корзину
     */
    function uvelir_caddy_del(params)
    {
        var del_btn = '#del_'+params.data.item_id;
        var span_count = '#count_span_'+params.data.item_id;
        var li_count = '#count_li_'+params.data.item_id;
        var count = parseInt($(span_count).text());
        count --;
        if(count < 1)
        {
            jQuery(del_btn).hide('slow');
            jQuery(li_count).hide('slow');
            count = 0;
        }
        $(span_count).text(count);
        
        to_caddy_send_data(params);
    }


    /**
     * Передача данных на сервер
     */
    function to_caddy_send_data(params)
    {
        $.ajax({
            type: 'POST',
            url: params.action,
            data: params.data,
            success: function(html){
                $('#uvelir_debud').html(html);
                var data = $.parseJSON(html);
                $('#mod_caddy_product_count').text(data[1].count);
                $('#mod_caddy_product_sum').text(data[1].sum);
            }
        });
    }

