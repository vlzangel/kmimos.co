<style type="text/css">

    .theme_tite.theme_table_title{
        margin: 20px 0 0 0;
        padding: 10px;
        color: #FFF;
        line-height: 1 !important;
        border: solid 1px #888;
        border-bottom: 0;
        border-radius: 0px 5px 0px 0px;
        display: inline-block;
        background: #59c9a8;
    }

    .theme_table_th{
        color: #FFF;
        text-align: center;
        border-top: 1px solid #888;
        border-right: 1px solid #888;
        background: #59c9a8!important;
    }

    .theme_btn{
        width: 100%;
        margin-bottom: 3px !important;
        padding: 3px 5px;
        float: left;
        color: #FFF !important;
        font-weight: 600;
        text-align: center;
        border-radius: 2px;
        background: #54c8a7;
    }
    .theme_btn.cancelled,
    .theme_btn .cancelled{
        background: #e80000;
    }

</style>

<script type="text/javascript">
    jQuery(document).ready(function(){
        jQuery(document).on('change','select.redirect',function(e){
            var value=jQuery(this).val(); console.log(value);
            if(value!=''){
                if(jQuery(this).find('option[value="'+value+'"]').hasClass('action_confirmed')){

                    var confirmed = confirm("Esta Seguro de cancelar esta reserva?");
                    if (confirmed == true) {
                        //alert("You pressed OK!");
                        window.location.href = value;
                    }else{
                        //alert("You pressed Cancel!");
                    }

                }else{
                    window.location.href = value;
                }
            }
        });
    });
</script>

<?php

if(!function_exists('date_boooking')){
    function date_boooking($date=''){
        $date=strtotime($date);
        $date=date('d/m/Y',$date);
        //$date=substr($date, 6, 2)."/".substr($date, 4, 2)."/".substr($date, 0, 4);
        return $date;
    }
}

if(!function_exists('build_table')){
    function build_table($args=array()){
        $table='';
        foreach($args as $data){
            if(count($data['tr'])>0){
                $table.='<h1 class="vlz_h1 jj_h1 theme_tite theme_table_title">'.$data['title'].'</h1>';
                $table.='<table class="vlz_tabla jj_tabla table table-striped table-responsive">';
                $table.='<tr>';
                foreach($data['th'] as $th){
                    $table.='<th class="theme_table_th '.$th['class'].'">'.$th['data'].'</th>';
                }
                $table.='</tr>';


                foreach($data['tr'] as $tr){
                    $table.='<tr>';
                    foreach($tr as $td){
                        $table.='<td class="'.$td['class'].'">'.$td['data'].'</th>';
                    }
                    $table.='</tr>';
                }

                $table.='</table>';
            }
        }
        return $table;
    }
}

if(!function_exists('build_select')){
    function build_select($args=array()){
        $select='';
        if(count($args)>0){
            $select.='<select class="redirect theme_btn">';
            $select.='<option value="" selected="selected">Seleccionar Acción</option>';
            foreach($args as $option){
                if(array_key_exists('text',$option)){
                    $class='';
                    if(array_key_exists('class',$option)){
                        $class=$option['class'];
                    }
                    $select.='<option class="'.$class.'" value="'.$option['value'].'">'.$option['text'].'</option>';
                }
            }
            $select.='</select>';
        }
        return $select;
    }
}




if(!function_exists('get_metaUser')){
    function get_metaUser($user_id=0, $condicion=''){
        global $wpdb;
        $sql = "
            SELECT u.user_email, m.*
            FROM wp_users as u
                INNER JOIN wp_usermeta as m ON m.user_id = u.ID
            WHERE
                m.user_id = {$user_id}
                {$condicion}
        ";
        $result = $wpdb->get_results($sql);
        //$result = execute($sql);
        return $result;
    }
}

if(!function_exists('GETmetaUSER')){
    function GETmetaUSER($user_id=0){
        $condicion = " AND m.meta_key IN ('first_name', 'last_name', 'user_phone', 'user_mobile')";
        $result = get_metaUser($user_id, $condicion);
        $data = [
            'id' =>'',
            'email' =>'',
            'first_name' =>'',
            'last_name' =>'',
            'user_phone' =>'',
            'user_mobile' =>'',
        ];
        if( !empty($result) ){
            if( $result->num_rows > 0){
                while ($row = $result->fetch_assoc()) {
                    $data['email'] = utf8_encode( $row['user_email'] );
                    $data['id'] = $row['user_id'];
                    $data[$row['meta_key']] = utf8_encode( $row['meta_value'] );
                }
            }
        }
        return $data;
    }
}
?>
