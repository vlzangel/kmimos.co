jQuery(function($){

  var num_select_max = 1; // Numero de items seleccionar por referencia
  var num_ref_max = 5; // Numero maximo de referencia asignadas por participantes

  $('#referencia').on('click', function(){
    if( !$('#referencia').hasClass('disabled') ){
      var itemSelect = $('[name="opt[]"]:checked').size(); 
console.log(itemSelect+" > 0 && "+itemSelect+" <= "+num_ref_max);           
      if( itemSelect > 0 && itemSelect <= num_ref_max ){
        $('#modalRef').modal('toggle');
      }else{
        alert('Debe seleccionar '+num_select_max+' referidos');
      }
    }
  });

  $('#saveRef').on('click', function(){

    if( $('#ref').val() != ""){
        var list = [];
        var count = $('[name="opt[]"]:checked').size();
        $('[name="opt[]"]:checked').each(function(){
          list.push($(this).val());
        });

        if( count > 0 && count <= num_ref_max ){

            var datos = {
              'ref': $('#ref').val(),
              'fec': $('#fecha').val(),
              'list': list,
            };

            $.ajax({
              url: "/landing/saveRef_landing_referidos.php",
              type : 'POST',
              data: datos,
              success: function (xdata) {
                var r = $.parseJSON(xdata);
                if(r['sts'] == 1){
                  $('#loading').addClass('hidden');
                  $('#msg').html('Referencia registrada satisfactoriamente!');
                  $("#modalRef").modal('hide');
                  $.each(r['data'], function(i,v){
                    $('[id="'+v+'"]').find('[data-target="check"]').html('<i class="fa fa-2x fa-check" aria-hidden="true"></i>');
                    $('[id="'+v+'"]').find('[data-target="ref"]').html(r['ref']);
                  });
                  countRefNotEmpty();
                }else{
                  $('#loading').addClass('hidden');
                  $('#msg').removeClass('hidden');
                  $('#msg').html('No pudimos completar su solicitud, intente nuevamente');
                }
              },beforeSend: function () {
                $('#loading').removeClass('hidden');
                $('#msg').html('Enviando...');
              },
              complete: function () {
              },
              fail: function(){
                $('#msg').html('No pudimos completar su solicitud, intente nuevamente');
              }
            });
        }else{
          $('#msg').html('Debe seleccionar solo '+num_select_max+' referidos.');
          $('#loading').addClass('hidden');
        }
    }
  });

  function countRefNotEmpty(){
    var disabled = $('[data-target="ref"]:not(:empty)').size(); 
    if( disabled >= num_ref_max ){
      $("#referencia").addClass('disabled');
    }
  }
});