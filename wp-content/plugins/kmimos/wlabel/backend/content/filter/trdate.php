<div class="filter">
    <div class="type date" data-type="trdate">
        <div class="month line">
            <label>Mes</label>
            <select name="month">
                <option value="">Seleccionar ...</option>
                <?php
                $month_current=date('m',time());
                $LC_month = array("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");
                for($month=1; $month<13 ; $month++){
                    $string = $LC_month[$month-1];

                    $selected='';
                    if($month==$month_current){
                        $selected='selected';
                    }

                    //$string = date('F',strtotime(''.$month.'/1/'.date('Y',time())));
                    echo '<option value="'.$month.'" '.$selected.'>'.$string.'</option>';
                }
                ?>
            </select>
        </div>

        <div class="year line">
            <label>Año</label>
            <select name="year">
                <option value="">Seleccionar ...</option>
                <?php
                $year_date=date('Y',time());
                for($year=0; $year<5; $year++){
                    $year_option=$year_date-$year;

                    $selected='';
                    if($year_option==$year_date){
                        $selected='selected';
                    }

                    echo '<option value="'.$year_option.'" '.$selected.'>'.$year_option.'</option>';
                }
                ?>
            </select>
        </div>
    </div>
</div>