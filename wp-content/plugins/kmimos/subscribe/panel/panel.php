<?php

$paged = 0;
if(isset($_GET['paged'])){
    $paged = $_GET['paged'];
}

$date = getdate();
$desde = '';//date("Y-m-01", $date[0] );
$hasta = '';//date("Y-m-d", $date[0]);
$WHERE = array();//date("Y-m-d", $date[0]);

if(	!empty($_POST['desde']) && !empty($_POST['hasta']) ){
    $desde = $_POST['desde'];
    $hasta = $_POST['hasta'];
    $query_desde = strtotime($desde);
    $query_hasta = strtotime($hasta);
    $WHERE['time'] = array();
    $WHERE['time']['query'] = "(time >= $query_desde AND time <= $query_hasta)";
    $WHERE['time']['condition'] = "AND";
}

$query_where='';
if(count($WHERE)>0){
    $query_where=' WHERE ';
    $iwhere=0;
    foreach($WHERE as $key => $item){
        if(isset($item['condition'])){
            if($iwhere>0){
                $query_where.=$item['condition'].' ';
            }
        }
        $query_where.=$item['query'];
        $iwhere++;
    }

}
$limit = 1;
$row = $limit*$paged;
$table = $_subscribe->table;
$total = $_subscribe->result("SELECT * FROM $table $query_where ORDER BY id DESC");
$result = $_subscribe->result("SELECT * FROM $table $query_where ORDER BY id DESC");// LIMIT $row, $limit
//var_dump($result);

?>
<style type="text/css">
    .subscribe{position:relative; padding:20px;}
    .subscribe .title{padding: 20px 0; font-size: 30px;}
    .subscribe .navigate{padding:20px 0;}
    .subscribe .navigate .icon{padding:20px; font-size: 20px;}

    .subscribe table{}
    .subscribe table tr{}
    .subscribe table td{padding: 10px; border: 1px solid #CCC;}
    .subscribe table thead{background: #CCC;}
</style>
<div class="subscribe">
    <div class="title">
        <h1>USUARIOS SUSCRITOS</h1>
    </div>

    <!-- Filtros -->
    <div class="filters row text-right">
        <div class="col-sm-12">
            <form class="form-inline" action="/wp-admin/admin.php?page=subscribe" method="POST">
                <label>Filtrar:</label>
                <div class="form-group">
                    <div class="input-group">
                        <div class="input-group-addon">Desde</div>
                        <input type="date" class="form-control" name="desde" value="<?php echo $desde; ?>">
                    </div>
                </div>
                <div class="form-group">
                    <div class="input-group">
                        <div class="input-group-addon">Hasta</div>
                        <input type="date" class="form-control" name="hasta" value="<?php echo $hasta ?>">
                    </div>
                </div>
                <button type="submit" class="btn btn-success"><i class="fa fa-search"></i> Buscar</button>
            </form>
            <hr>
        </div>
    </div>

<?php

/*//Navigate
$paged_link = (isset($_SERVER['HTTPS']) ? "https" : "http")."://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
$paged_total=count($total)/$limit;
$paged_prev=$paged;
if($paged>0){
    $paged_prev=$paged-1;
}

$paged_next=$paged;
if(($paged+1)<$paged_total){
    $paged_next=$paged+1;
}

if(count($total)>count($result)){
    echo '<div class="navigate">';
    echo '<a href="'.$paged_link.'&paged='.$paged_prev.'"><i class="icon fa fa-angle-left" aria-hidden="true"></i></a>';
    echo 'Pagina '.($paged+1).' de '.$paged_total;
    echo '<a href="'.$paged_link.'&paged='.$paged_next.'"><i class="icon fa fa-angle-right" aria-hidden="true"></i></a>';
    echo '</div>';

}
*/
if(count($result)>0){
    ?>
    <table id="tblusers" class="table table-striped table-bordered dt-responsive table-hover table-responsive nowrap datatable-buttons"
           cellspacing="0" width="100%">
        <thead>
            <tr>
                <td>ID</td>
                <td>Email</td>
                <td>Origen</td>
                <td>Fecha</td>
            </tr>
        </thead>
        <tbody>

    <?php
    foreach($result as $row){
    ?>

            <tr>
                <td><?php echo $row->id; ?></td>
                <td><?php echo $row->email; ?></td>
                <td><?php echo $row->source; ?></td>
                <td><?php echo date('d/m/Y',$row->time); ?></td>
            </tr>
    <?php
    }
    ?>
        </tbody>
    </table>

    <?php


}else{
    '<div class="message">No hay personas regstradas</div>';
}

?>
</div>

