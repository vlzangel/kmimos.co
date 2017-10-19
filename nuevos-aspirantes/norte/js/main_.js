$(window).load(function() {
	
	//$('#section-1').height( $(window).height() - 1  );
	$('#section-6').height( $(window).height() - 1  );
	
});

//$(window).resize(function(){
//	$('iframe').width( ($(window).width() * 97) / 100 );
//	$('iframe').height( ($(window).height() * 97) / 100 );
// $('iframe').contentDocument.location.reload(true);
//});


$('#subscribe').on('click', function(){
  _subscribe();
});

$('#frm-temp').on('submit', function(e){
  e.preventDefault(e);
  _subscribe();
});

function _subscribe(){

    if( !$('#terminos').prop('checked') || $('#email').val() == "" ){

      if( !$('#terminos').prop('checked') ){
        $('#msg').html('Debe aceptar los terminos y condiciones');
      }
      if( $('#email').val() == "" ){
        $('#msg').html('Debe completar los campos');
      }
      return;
    }

    $('#loading').removeClass('hidden');
    $('#msg').html('Validando datos...');
    $.ajax( "/landing/list-subscriber.php?source=kmimos-mx-cuidadores-norte&email="+$('#email').val()+"&phone="+$('#phone').val() )
    .done(function(data) { 
      if(data == 1){ 
        $('#loading').addClass('hidden');
        $('#msg').html('Gracias por ser parte de Kmimos, completa tu registro.');
        $('#frm-redirect').submit();
      }else if(data == 2){
        $('#msg').html('Formato de email incorrecto.');
      }else{
        $('#msg').html('No pudimos completar su solicitud, intente nuevamente');
      }
    })
    .fail(function() {
      $('#msg').html('No pudimos completar su solicitud, intente nuevamente');
      $('#loading').addClass('hidden');
    });  

}
