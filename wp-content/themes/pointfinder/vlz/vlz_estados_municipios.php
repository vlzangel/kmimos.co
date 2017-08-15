<?php

$municipio[1][1] = "ARANJUEZ";
$municipio[1][2] = "SANTA CRUZ";
$municipio[1][3] = "MANRIQUE";
$municipio[1][4] = "CASTILLA";
$municipio[1][5] = "DOCE DE OCTUBRE";
$municipio[1][6] = "ROBLEDO";
$municipio[1][7] = "VILLA HERMOSA";
$municipio[1][8] = "BUENOS AIRES";
$municipio[1][9] = "LA CANDELARIA";
$municipio[1][10] = "LAURELES-ESTADIO";
$municipio[1][11] = "LA AMERICA";
$municipio[1][12] = "SAN JAVIER";
$municipio[1][13] = "EL POBLADO";
$municipio[1][14] = "GUAYABAL";
$municipio[1][15] = "BELEN";
$municipio[2][16] = "NOR-OCCIDENTE (COMUNAS 1-2-3-9)";
$municipio[2][17] = "NOR-ORIENTE (COMUNAS 4-5-6-7-8)";
$municipio[2][18] = "DISTRITO AGUA BLANCA (COMUNAS 13-14-15-21)";
$municipio[2][19] = "ORIENTE (COMUNAS 11-12-16)";
$municipio[2][20] = "SUR (COMUNAS 10-17-18-19-20-22)";
$municipio[3][21] = "USAQUEN";
$municipio[3][22] = "CHAPINERO";
$municipio[3][23] = "SANTA FE";
$municipio[3][24] = "SAN CRISTOBAL";
$municipio[3][25] = "USME";
$municipio[3][26] = "TUNJUELITO";
$municipio[3][27] = "BOSA";
$municipio[3][28] = "KENNEDY";
$municipio[3][29] = "FONTIBON";
$municipio[3][30] = "ENGATIVA";
$municipio[3][31] = "SUBA";
$municipio[3][32] = "BARRIOS UNIDOS";
$municipio[3][33] = "TEUSAQUILLO";
$municipio[3][34] = "LOS MARTIRES";
$municipio[3][35] = "ANTONIO NARINO";
$municipio[3][36] = "PUENTE ARANDA";
$municipio[3][37] = "RAFAEL URIBE";
$municipio[3][38] = "CIUDAD BOLIVAR";
$municipio[3][39] = "CERCAN&Iacute;AS DE BOGOTA";
$municipio[4][40] = "RIOMAR";
$municipio[4][41] = "NORTE-CENTRO HISTORICO";
$municipio[4][42] = "SUR OCCIDENTE";
$municipio[4][43] = "METROPOLITANA";
$municipio[4][44] = "SUR ORIENTE";
$municipio[5][45] = "LOCALIDAD HISTORICA Y DEL CARIBE NORTE (COMUNAS 1-2-3-8-9-10)";
$municipio[5][46] = "LOCALIDAD DE LA VIRGEN Y TURISTICA (COMUNAS 4-5-6-7)";
$municipio[5][47] = "LOCALIDAD INDUSTRIAL DE LA BAHIA (COMUNAS 11-12-13-14-15)";


if(isset($_POST['estado'])){
      $datos = $municipio[$_POST['estado']];
      $municipios = '';
      foreach ( $datos as $id => $name) {
            $municipios .= '<option value="'.$id.'">'.$name.'</option>';
      }
      echo $municipios;
}