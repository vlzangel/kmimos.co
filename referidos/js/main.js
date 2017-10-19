

$('#frm').on('submit', function(e){
  e.preventDefault(e);
  _registerLanding();
});

$('#send').on('click', function(e){
  _registerLanding();
});

$("input").change(function(){
  if( $(this).val() != "" ){
    $('msg').html("");
  }
});

function _registerLanding(){

  if( $('#email').val() == "" || $('#name').val() == "" ){
    $('#msg').html('Debe completar los datos');
    return;
  }

  $('#loading').removeClass('hidden');
  $('#msg').html('Registrando Usuario.');
  $.ajax( "/landing/registro-usuario.php?email="+$('#email').val()+"&name="+$('#name').val()+"&referencia="+$('#referencia').val() )
  .done(function() {
    $('#msg').html('Generando url.');
    $('#frm-redirect').submit();
  })
  .fail(function() {
    $('#msg').html('Registro: No pudimos completar su solicitud, intente nuevamente');
    $('#loading').addClass('hidden');
  });  

  $('#loading').removeClass('hidden');
  $('#msg').html('Enviando...');
  $.ajax( "/landing/list-subscriber.php?source=kmimos-mx-clientes-referidos&email="+$('#email').val() )
  .done(function() {
    $('#loading').addClass('hidden');
    $('#msg').html('Guardando referencia.');

    window.location.href = $('#temp').attr('action')+$('#email').val();
  })
  .fail(function() {
    $('#msg').html('Referencia: No pudimos completar su solicitud, intente nuevamente');
    $('#loading').addClass('hidden');
  });  

}

