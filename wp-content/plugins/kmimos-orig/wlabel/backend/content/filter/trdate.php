<div class="filter">
    <div class="type date" data-type="trdate">
        <div class="month line">
            <label>Mes</label>
            <select name="month">
                <option value="">Seleccionar ...</option>
                <?php
                for($month=1; $month<12 ; $month++){
                    echo '<option value="'.$month.'">'.date('F',strtotime(''.$month.'/1/'.date('Y',time()))).'</option>';
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
                    echo '<option value="'.$year_option.'">'.$year_option.'</option>';
                }
                ?>
            </select>
        </div>
    </div>
</div>