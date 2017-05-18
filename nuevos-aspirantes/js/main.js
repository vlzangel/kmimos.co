$(window).load(function() {
	
	$('#section-1').height( $(window).height() - 1  );
	$('#section-6').height( $(window).height() - 1  );
	
});


$('#subscribe').on('click', function(){
  _subscribe();
});

$('#frm-temp').on('submit', function(e){
  e.preventDefault(e);
  _subscribe();
});

function _subscribe(){

//  if( $('#email').val().match(emailRegex) ) {
    $('#loading').removeClass('hidden');
    $('#msg').html('Enviando...');
    $.ajax( "/landing/list-subscriber.php?source=kmimos-co-cuidadores&email="+$('#email').val()+"&phone="+$('#phone').val() )
    .done(function(data) {
      if(data == 1){ 
        $('#loading').addClass('hidden');
        $('#msg').html('Gracias por ser parte de Kmimos, completa tu registro.');
        $('#frm-redirect').submit();
      }else{
        $('#msg').html('No pudimos completar su solicitud, intente nuevamente');
      }
    })
    .fail(function() {
      $('#msg').html('No pudimos completar su solicitud, intente nuevamente');
      $('#loading').addClass('hidden');
    });  
//  }
}
