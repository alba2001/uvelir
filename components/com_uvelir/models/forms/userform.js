window.addEvent('domready', function() {
	document.formvalidator.setHandler('fio',
		function (value) {
			regex=/^[^0-9]+$/;
			return regex.test(value);
	});
});
    jQuery(document).ready(function($){
        $.mask.definitions['#']='[9]';  
        $("#jform_phone").mask("+7(#99) 999-99-99");
        
        if($("#jform_user_type_id").val() == 2)
        {
            $('#uslugi-user_add_info').show('slow');
            $('.addinfo').attr('required','required');
        }
        else
        {
            $('#uslugi-user_add_info').hide('slow');
            $('.addinfo').removeAttr('required');
        }
        $("#jform_user_type_id").change(function(){
            if($(this).val() == 2)
            {
                $('#uslugi-user_add_info').show('slow');
                $('.addinfo').attr('required','required');
            }
            else
            {
                $('#uslugi-user_add_info').hide('slow');
                $('.addinfo').removeAttr('required');
            }
        });
        $('#com_uslugi_user_registration').click(function() {
            if ($(this).is(':checked')) 
            {
                $('#uslugi-user_register').show('slow');
                $('.registration').attr('required','required');
            }
            else
            {
                $('#uslugi-user_register').hide('slow');
                $('#error_msg').hide('slow');
                $('.registration').removeAttr('required');
            }
        });
        $('.fio').change(function(){
            var name = '';
            $('.fio').each(function(){
                name = name=='-'?name:name+' '+$(this).val();
            });
            $('#jform_name').val(name);
        });
        $('#jform_email').change(function(){
            var email = $(this).val();
            $('#jform_email1').val(email);
        });
        $('#jform_email1').change(function(){
            var email = $(this).val();
            $('#jform_email2').val(email);
        });
        // Инициируем Имя пользователя
        $('.inputbox').change();
    });

