    
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
     * Удаление товара из корзины
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
        jQuery.ajax({
            type: 'POST',
            url: params.action,
            data: params.data,
            success: function(html){
//                $('#uvelir_debud').html(html);
                var data = jQuery.parseJSON(html);
                jQuery('#mod_caddy_product_count').text(data[1].count);
                jQuery('#mod_caddy_product_sum').text(data[1].sum);
            }
        });
    }

jQuery(function($){
    $(document).ready(function(){
        
        
        // Удаление товара изкорзины
        $('.com_uvelir-delete').click(function(){
            var id = /^delete_(\d+_\d+)$/.exec($(this).attr('id'))[1];
            $('#caddy_item_count_'+id).val('0');
            $('#caddy_item_sum_'+id).text('0');
            $('#item_row_'+id).hide('slow');
            
            // Изменяем корзину
            change_caddy();
        });
        
        // Увеличение кол-ва товаров в форме корзины
        $('.arow_right').click(function(){
            var span_count = $(this).siblings('input');
            var count = parseInt($(span_count).val());
            count ++;
            $(span_count).val(count);
            $(span_count).change();
        });
        // Уменьшение кол-ва товаров в форме корзины
        $('.arow_left').click(function(){
            var span_count = $(this).siblings('input');
            var count = parseInt($(span_count).val());
            if(count > 1)
            {
                count --;
                $(span_count).val(count);
                $(span_count).change();
            }
            console.log(count);
        });
        
        // Изменение кол-ва товаров в корзине
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
            
            // Изменяем корзину
            change_caddy();
        });
    });

    /**
     *  Внесение изменений в корзину
     */
    function change_caddy()
    {
        // Меняем сумму корзины
        var item_sums = $('.caddy_item_sum');
        var total = 0;
        var sum = 0;
        
        $.each(item_sums, function(i,item_sum){
            sum = parseInt($(item_sum).text());
            total += sum;
        });
        // Всего стоимость товаров в корзине
        $('#caddy_total_sum').text(total);

        // Пересчитваем корзину
        var item_id = 0;
        var item_counts = $('.caddy_item_count');
        var count = '';
        $.each(item_counts, function(i,item_count){
            item_id = $(item_count).attr('rel');
            count += item_id+':'+$(item_count).val()+' ';
        });
console.log(count);
        // Отправляем изменения в корзине на серер
        var form  = $('#step1_form');
        $.ajax({
            type: 'GET',
            url: $(form).attr('action'),
            data: {
                count: count,
                option: 'com_uvelir',
                task: 'caddy.correction'
            },
            success: function(html){
//                    $('#uvelir_debud').html(html);
console.log(html);
                var data = $.parseJSON(html);
console.log(data);
                $('#mod_caddy_product_count').text(data[1].count);
                $('#mod_caddy_product_sum').text(data[1].sum);
            }
        });
        
    }
});
