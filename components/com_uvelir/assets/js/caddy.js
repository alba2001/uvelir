    
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

jQuery(function($){
    $(document).ready(function(){
        $('.caddy_item_count').change(function(e){
            var item = this;
            var count = parseInt($(item).val());
            if(!count || count < 1)
            {
                count = 0;
            }
            var id = $(this).attr('rel');
            var prise = $('#caddy_item_price_'+id).text();
            var sum = prise*count;
            // Изменяем сумму товара
            $('#caddy_item_sum_'+id).text(sum);
            $(item).val(count);
            
            // Меняем сумму корзины
            var item_sums = $('.caddy_item_sum');
            var total = 0;
            $.each(item_sums, function(i,item_sum){
                sum = parseInt($(item_sum).text());
                total += sum;
            });
            // Всего стоимость товаров в корзине
            $('#caddy_total_sum').text(total);
            
            // Отправляем изменения в корзине на серер
            var form  = $('#caddy_show');
            $.ajax({
                type: 'POST',
                url: $(form).attr('action'),
                data: $(form).serialize(),
                success: function(html){
//                    $('#uvelir_debud').html(html);
                    var data = $.parseJSON(html);
            console.log(data);
                    $('#mod_caddy_product_count').text(data[1].count);
                    $('#mod_caddy_product_sum').text(data[1].sum);
                }
            });
            
        });
    });
});