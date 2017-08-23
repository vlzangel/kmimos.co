<?php
global $current_user;
global $wpdb;

if($current_user->ID != 1 && $current_user->ID != 22) echo('No está autorizado a entrar en esta página');
else {
$direccion = (($_SERVER['HTTPS'])?'https://':'http://').$_SERVER['HTTP_HOST'];
$ubica=array(
    array('ciudad'=>'Cdad. México', 'lat'=>19.4141721, 'lng'=> -99.1431542),
    array('ciudad'=>'Guadalajara', 'lat'=>20.6715532, 'lng'=> -103.3598659),
    array('ciudad'=>'Monterrey', 'lat'=>25.6490376, 'lng'=> -100.4431801),
    array('ciudad'=>'Panamá', 'lat'=>8.9774212, 'lng'=> -79.5228142),
    array('ciudad'=>'Buenos Aires', 'lat'=>-34.6190137, 'lng'=> -58.4331013),
);    
$disdef = 20;               // Km a la redonda x defecto
$pppdef = 20;               // Post per page x defecto

?>
<style>
.wrapper {
  background: white;
  margin: auto;
  padding: 1em;
}
h1 {
  text-align: center;
}
ul.tabs {
  list-style-type: none;
  margin: 0;
  padding: 0;
}
ul.tabs li {
  border: gray solid 1px;
  border-bottom: none;
  float: left;
  margin: 0 .25em 0 0;
  padding: .25em .5em;
}
ul.tabs li a {
  color: gray;
  font-weight: bold;
  text-decoration: none;
}
ul.tabs li.active {
  background: gray;
}
ul.tabs li.active a {
  color: white;
}
.clr {
  clear: both;
}
article {
  border-top: gray solid 1px;
  padding: 0 1em;
}
.trama {
    width: 100%;
    height: 250px;
}
.servicio {
    width: 150px;
    float: left;
}
.servicio label {
    width: 120px;
}
.servicio input {
    vertical-align: top;
}
fieldset {
    width: 195px;
    float: left;
    border-radius: 5px;
    border: 1px solid silver;
    margin: 5px;
    padding: 5px;
}
fieldset input[type=number] {
    width: 80px;
    text-align: right;
}
.grupo_rango {
    float: left;
    margin: 5px;
}
legend {
    font-size: 1.1em;
    text-align: center;
    width: 90% !important;
    margin-bottom: 0;
}
</style>
<form action="" id="pruebas" method="post">
<div id="contenedor">
<section class="wrapper">
    <h1>Panel de Pruebas</h1>
    <ul class="tabs">
        <li><a href="#tab0">init</a></li>
        <li><a href="#tab1">get-location</a></li>
        <li><a href="#tab2">get-petsitters</a></li>
        <li><a href="#tab3">get-petsitter</a></li>
    </ul>
    <div class="clr"></div>
    <section class="block">
        <article id="tab0">
            <h2>get-init</h2>
            <button class ="enviarConsulta" data-type="init">Enviar</button>
            <hr>
        </article>
        <article id="tab1">
            <h2>get-location</h2>
            <label for="geocodigo">Geocódigo</label>
            <input type="text" id="geocodigo" style="width: 60px;"><button class ="enviarConsulta" data-type="geocodigo">Enviar</button>
            <hr>
        </article>
        <article id="tab2">
            <h2>get-petsitters</h2>
            <h3>Ubicación del Usuario</h3>
            <label for="latitud">Latitud</label>
            <input type="longitud" id="latitud" style="width: 120px; text-align: right;" value="<?php echo $latdef; ?>">
            <label for="longitud">Longitud</label>
            <input type="longitud" id="longitud" style="width: 120px; text-align: right;" value="<?php echo $lngdef; ?>">
            <label for="distancia">Distancia</label>
            <input type="number" id="distancia" style="width: 60px; text-align: right;" value="<?php echo $disdef; ?>"><span>Km</span><br>
            <h3>Ubicaciones Predeterminadas</h3>            
            <button class ="miUbicacion">Mi Ubicación</button>
<?php
foreach($ubica as $ubi){ ?>
            <button class ="ubicacionDef" data-lat="<?php echo $ubi['lat']; ?>" data-lng="<?php echo $ubi['lng']; ?>"><?php echo $ubi['ciudad']; ?></button>
<?php
} ?>
            <h3>Ubicación del Cuidador</h3>
            <label for="geocodigo_cuidador">Geocódigo</label>
            <input type="text" id="geocodigo_cuidador" style="width: 60px;">
            <h3>Filtrado por Rangos</h3>
            <fieldset>
                <legend>Rango de Precios</legend>
                <div class="grupo_rango">
                <label for="price_from">Mínimo:</label><br>
                <input id="price_from" type="number">
                </div>
                <div class="grupo_rango">
                <label for="price_to">Máximo:</label><br>
                <input id="price_to" type="number">
                </div>
            </fieldset>
            <fieldset>
                <legend>Rango de Experienca</legend>
                <div class="grupo_rango">
                <label for="exp_from">Mínima:</label><br>
                <input id="exp_from" type="number">
                </div>
                <div class="grupo_rango">
                <label for="exp_to">Máxima:</label><br>
                <input id="exp_to" type="number">
                </div>
            </fieldset>
            <fieldset>
                <legend>Rango de Valoración</legend>
                <div class="grupo_rango">
                <label for="rate_from">Mínima:</label><br>
                <input id="rate_from" type="number">
                </div>
                <div class="grupo_rango">
                <label for="rate_to">Máxima:</label><br>
                <input id="rate_to" type="number">
                </div>
            </fieldset>
            <fieldset>
                <legend>Rango Posicionamiento</legend>
                <div class="grupo_rango">
                <label for="rank_from">Mínimo:</label><br>
                <input id="rank_from" type="number">
                </div>
                <div class="grupo_rango">
                <label for="rank_to">Máximo:</label><br>
                <input id="rank_to" type="number">
                </div>
            </fieldset>
            <div class="clr"></div>
            <h3>Servicios</h3>
            <div class="servicio"><input type="checkbox" id="s01" data-value="hospedaje">
            <label for="s01">Hospadaje</label></div>
            <div class="servicio"><input type="checkbox" id="s02" data-value="guarderia">
            <label for="s02">Guardería</label></div>
            <div class="servicio"><input type="checkbox" id="s03" data-value="paseos">
            <label for="s03">Paseos</label></div>
            <div class="servicio"><input type="checkbox" id="s04" data-value="adiestramiento">
            <label for="s04">Adiestramiento</label></div>
            <div class="servicio"><input type="checkbox" id="s05" data-value="peluqueria">
            <label for="s05">Peluqueria</label></div>
            <div class="servicio"><input type="checkbox" id="s06" data-value="bano">
            <label for="s06">Baño</label></div>
            <div class="servicio"><input type="checkbox" id="s07" data-value="transporte">
            <label for="s07">Trasp. Sencilla</label></div>
            <div class="servicio"><input type="checkbox" id="s08" data-value="transporte2">
            <label for="s08">Trasp. Redonda</label></div>
            <div class="servicio"><input type="checkbox" id="s09" data-value="veterinario">
            <label for="s09">Vis. Veterinario</label></div>
            <div class="servicio"><input type="checkbox" id="s10" data-value="peluqueria2">
            <label for="s05">Peluqueria(adic.)</label></div>
            <div class="servicio"><input type="checkbox" id="s11" data-value="bano2">
            <label for="s06">Baño (adicional)</label></div>
            <div class="servicio"><input type="checkbox" id="s12" data-value="limpieza">
            <label for="s10">Limpieza Dental</label></div>
            <div class="servicio"><input type="checkbox" id="s13" data-value="acupuntura">
            <label for="s11">Acupuntura</label></div>
            <div class="clr"></div>
            <h3> Cuidadores que Acepten Mascotas</h3>
            <div class="servicio"><input type="checkbox" id="t01" data-value="s">
            <label for="t01">Pequeñas</label></div>
            <div class="servicio"><input type="checkbox" id="t02" data-value="m">
            <label for="t02">Medianas</label></div>
            <div class="servicio"><input type="checkbox" id="t03" data-value="l">
            <label for="t03">Grandes</label></div>
            <div class="servicio"><input type="checkbox" id="t04" data-value="x">
            <label for="t04">Gigantes</label></div>
            <div class="clr"></div>
            <h3>Cuidadores que Tengan Mascotas</h3>
            <div class="servicio"><input type="checkbox" id="m01" data-value="s">
            <label for="m01">Pequeñas</label></div>
            <div class="servicio"><input type="checkbox" id="m02" data-value="m">
            <label for="m02">Medianas</label></div>
            <div class="servicio"><input type="checkbox" id="m03" data-value="l">
            <label for="m03">Grandes</label></div>
            <div class="servicio"><input type="checkbox" id="m04" data-value="x">
            <label for="m04">Gigantes</label></div>
            <div class="clr"></div>
            <h3> Cuidadores que Acepten Conductas</h3>
            <div class="servicio"><input type="checkbox" id="c01" data-value="s">
            <label for="c01">Sociables</label></div>
            <div class="servicio"><input type="checkbox" id="c02" data-value="n">
            <label for="c02">No Sociables</label></div>
            <div class="servicio"><input type="checkbox" id="c03" data-value="m">
            <label for="c03">Agresivas c/mascotas</label></div>
            <div class="servicio"><input type="checkbox" id="c04" data-value="h">
            <label for="c04">Agresivas c/humanos</label></div>
            <div class="clr"></div>
            <h3>Ordenamiento</h3>
            <label for="orden">Ordenar por:</label><select id="orden">
                <option value="distance_asc">Distancia ascentente: primero los más cercanos</option>
                <option value="distance_desc">Distancia descentente: primero los más lejanos</option>
                <option value="price_asc">Precio ascentente: primero los más económicos</option>
                <option value="price_desc">Precio descentente: primero los más costosos</option>
                <option value="experience_asc">Experiencia ascentente: primero los menos expertos</option>
                <option value="experience_desc">Experiencia descentente: primero los más expertos</option>
                <option value="name_asc">Nombre ascentente: inicial del nombre de la A a la Z</option>
                <option value="name_desc">Nombre descentente: inicial del nombre de la Z a la A</option>
                <option value="rate_desc">Valoración descentente: primero los de mayor valoración</option>
                <option value="rate_asc">Valoración ascentente: primero los de menor valoración</option>
            </select>
            <h3>Paginación</h3>
            <label for="desde">Desde</label>
            <input type="number" id="desde" style="width: 60px; text-align: right;" value="0">
            <label for="ppp">Largo</label>
            <input type="number" id="ppp" style="width: 60px; text-align: right;" value="<?php echo $pppdef; ?>">
            <button class ="enviarConsulta" data-type="ubicacion">Enviar</button>
            <hr>
            <div id="res_ubicacion"></div>
        </article>
        <article id="tab3">
            <h2>get-petsitter</h2>
            <label for="id_cuidador">Id del Cuidador</label>
            <input type="number" id="id_cuidador" style="width: 60px; text-align: right;"><button class ="enviarConsulta" data-type="id">Enviar</button>
            <label for="codigo_cuidador">Código del Cuidador</label>
            <input type="number" id="codigo_cuidador" style="width: 60px; text-align: right;"><button class ="enviarConsulta" data-type="code">Enviar</button>
            <hr>
            <fieldset id="fieldset">
                <legend>Descripción del Cuidador</legend>
                <label for="nombre">Nombres:</label><input id="nombre">
                <label for="apellidos">Apellidos:</label><input id="apellidos">
            </fieldset>
        </article>
    </section>
</section>
<hr>
<label for="nombre">Trama recibida:</label><br><textarea id="trama_recibida" class="trama"></textarea>
</div>
</form>
<script>
jQuery.noConflict(); 
jQuery(document).ready(document).ready(function() {
    var url = window.location.href.split("#");
    console.log('posicion='+url[1]+'. Largo='+url.length);
    var posicion ='cuidadores';
    if(url.length==1) jQuery(location).attr('href','#'+posicion);
	var urlSever = '<?php echo $direccion; ?>/wp-content/plugins/kmimos/app-server.php';
/*
*   Recibe los datos del cuidador enviando el código del mismo
*/
    jQuery(".enviarConsulta").on('click', function(e){
        e.preventDefault();
        var type = jQuery(this).attr('data-type');
        switch(type){
        case 'init':
            var params = {action: 'init' };
            break;
        case 'code':
            var code = jQuery("#codigo_cuidador").val();
            var params = {action: 'get-petsitter', code: code };
            break;
        case 'id':
            var id = jQuery("#id_cuidador").val();
            var params = {action: 'get-petsitter', id: id };
            break;
        case 'geocodigo':
            var geocodigo = jQuery("#geocodigo").val();
            var params = {action: 'get-location', location: geocodigo };
            break;
        case 'ubicacion':
            jQuery("#res_ubicacion").html('<img src="/wp-content/plugins/kmimos/assets/images/sf-spinner.gif"><span> Cargando...</span>');
            var lat = jQuery("#latitud").val();
            var lng = jQuery("#longitud").val();
            var dist = jQuery("#distancia").val();
            var loc = jQuery("#geocodigo_cuidador").val();
            var from = jQuery("#desde").val();
            var size = jQuery("#ppp").val();
            var order = jQuery("#orden").val();
            var price_from = jQuery("#price_from").val();
            var price_to = jQuery("#price_to").val();
            var exp_from = jQuery("#exp_from").val();
            var exp_to = jQuery("#exp_to").val();
            var rate_from = jQuery("#rate_from").val();
            var rate_to = jQuery("#rate_to").val();
            var rank_from = jQuery("#rank_from").val();
            var rank_to = jQuery("#rank_to").val();
            var serv = '';
            for(i=1;i<=14;i++){
                if(jQuery("#s"+("0"+i).slice(-2)).is(':checked')) {
                    if(serv != '') serv = serv+",";
                    serv = serv + jQuery("#s"+("0"+i).slice(-2)).attr('data-value');
                }
            }
//            serv = "'" + serv + "'";
            var acepta = '';
            for(i=1;i<=4;i++){
                if(jQuery("#t"+("0"+i).slice(-2)).is(':checked')) {
                    if(acepta != '') acepta = acepta+",";
                    acepta = acepta + jQuery("#t"+("0"+i).slice(-2)).attr('data-value');
                }
            }
//            acepta = "'" + acepta + "'";
            var tiene = '';
            for(i=1;i<=4;i++){
                if(jQuery("#m"+("0"+i).slice(-2)).is(':checked')) {
                    if(tiene != '') tiene = tiene+",";
                    tiene = tiene + jQuery("#m"+("0"+i).slice(-2)).attr('data-value');
                }
            }
//            tiene = "'" + tiene + "'";
            var conducta = '';
            for(i=1;i<=4;i++){
                if(jQuery("#c"+("0"+i).slice(-2)).is(':checked')) {
                    if(conducta != '') conducta = conducta+",";
                    conducta = conducta + jQuery("#c"+("0"+i).slice(-2)).attr('data-value');
                }
            }
//            conducta = "'" + conducta + "'";
            var params = {action: 'get-petsitters', lat: lat, lng: lng, dist: dist, loc: loc, serv: serv, from: from, size: size, price_from: price_from, price_to: price_to, exp_from: exp_from, exp_to: exp_to, rate_from: rate_from, rate_to: rate_to, rank_from: rank_from, rank_to: rank_to, acepta: acepta, tiene: tiene, conducta: conducta, order: order };
            break;
        }
        jQuery.post(urlSever, params,function(){
// Acción a realizar al momento de enviar la solicitud
            jQuery("#trama_recibida").html('');
        }, "json")
        .success(function(data){
            jQuery("#trama_recibida").html('');
            jQuery.each(data, function(key,value){
// Acción a realizar por cada dato recibido
                jQuery("#trama_recibida").append(key+'=>'+JSON.stringify(value)+'\n');
                switch(type){
                case 'code':
                case 'id':
                    break;
                case 'geocodigo':
                    break;
                case 'ubicacion':
                    if(key==0) jQuery("#res_ubicacion").html('<strong>TOTAL: '+value['total']+' cuidadores</strong> - Precio por servicio de '+value['precios']+'<br><ul>');
                    else jQuery("#res_ubicacion").append(ficha_cuidador(key, value));
                    break;
                }
            });
// Acción a realizar al finalizar la recepción de datos
            console.log('Datos del cuidador recibidos');
            switch(type){
            case 'code':
            case 'id':
                break;
            case 'geocodigo':
                break;
            case 'ubicacion':
                jQuery("#res_ubicacion").append('</ul>');
                break;
            }
        });
    });
    
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(coordenadas);
    }
    
    jQuery(".miUbicacion").on('click', function(e){
        e.preventDefault();
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(coordenadas);
        }
    });

    jQuery(".ubicacionDef").on('click', function(e){
        e.preventDefault();
        jQuery("#latitud").val(jQuery(this).attr('data-lat'));
        jQuery("#longitud").val(jQuery(this).attr('data-lng'));
    });
    
    jQuery('ul.tabs li:first').addClass('active');
    jQuery('.block article').hide();
    jQuery('.block article:first').show();
    jQuery('ul.tabs li').on('click',function(){
        jQuery("#trama_recibida").html('');
        jQuery('ul.tabs li').removeClass('active');
        jQuery(this).addClass('active')
        jQuery('.block article').hide();
        var activeTab = jQuery(this).find('a').attr('href');
        jQuery(activeTab).show();
        return false;
    });
    
    function ficha_cuidador(key, value){
        var dist = parseFloat(value['distance']);
        return '<li>ID: '+value['ID']+', Nombre: '+value['name']+', Distancia: '+dist.toFixed(3)+'Km, Desde: '+value['price']+'</li>';
    }
});
function coordenadas(position){
    if(position.coords.latitude!='' && position.coords.longitude!='') {
        document.getElementById("latitud").value=position.coords.latitude;
        document.getElementById("longitud").value=position.coords.longitude;        
    }
    else {
        var mensaje = 'No es posible leer su ubicación,\nverifique si su GPS está encendido\ny vuelva a recargar la página.'+$("#latitud").val()+','+$("#longitud").val();
/*        $("#selector_locacion").removeClass("hide");
        $("#selector_coordenadas").addClass("hide");
        $("#selector_tipo").addClass("hide");*/
        alert(mensaje);        
    }
}
</script>
<?php
} 
/* --- FIN DE LA APLICACIÓN --- */
?>