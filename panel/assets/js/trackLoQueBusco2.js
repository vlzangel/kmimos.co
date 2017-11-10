<script>

mixpanel.identify();
var distinct_ID = mixpanel.get_distinct_id();
document.getElementById('pf-search-button-manual').addEventListener("click", ClickBuscar);

function ClickBuscar() {


p=document.getElementsByClassName('boton_portada boton_servicio activo');



for (i = 0; i< p.length; i++) {
var tt = p[i].getElementsByTagName('input');
var id = "#" + jQuery (tt).attr('id');
console.log(id);
if( jQuery (id).prop('checked'))    {

  console.log('chequiao')
  var nombre = jQuery (tt).attr('value')
  mixpanel.people.set({ nombre: "si" });
                                    }
                              }
  var estadoss = document.getElementById("estado_cuidador");
  var municipioss = document.getElementById("municipio_cache");
  mixpanel.people.set({ 'estadoBuscado' : estadoss });
  mixpanel.people.set({ 'municipioBuscado' : municipioss });
  var FechadeBusqueda = new Date();
  mixpanel.people.set({ 'UltimaFechaDeBusqueda' : FechadeBusqueda });




                                                                               }
</script>
