<?php
    $title=$_wlabel_user->wlabel_result->title;
    $image=$_wlabel_user->wlabel_data->image;
    $color=$_wlabel_user->wlabel_data->color;
?>
<div class="menu" style="background-color:<?php echo $color; ?>;" data-url="<?php echo plugin_dir_url(__DIR__); ?>">
    <?php
        if($image!=''){
            echo '<img class="image" src="'.$image.'" alt='.$title.';>';
        }

    ?>
    <div class="item" onclick="WhiteLabel_panel_menu('detail');">Montos</div>
    <div class="item" onclick="WhiteLabel_panel_menu('client');">Clientes</div>
    <div class="item" onclick="WhiteLabel_panel_menu('client-booking');">Reservas por Clientes</div>
    <div class="item" onclick="WhiteLabel_panel_menu('booking');">Reservas</div>
</div>
