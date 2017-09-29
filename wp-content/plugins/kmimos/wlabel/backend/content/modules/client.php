<?php
$kmimos_load=dirname(dirname(dirname(dirname(dirname(dirname(dirname(dirname(__FILE__)))))))).'/wp-load.php';
if(file_exists($kmimos_load)){
    include_once($kmimos_load);
}

global $wpdb;
$wlabel=$_wlabel_user->wlabel;
$WLresult=$_wlabel_user->wlabel_result;
$_wlabel_user->wlabel_Options('client');
$_wlabel_user->wLabel_Filter(array('tddate','tdcheck'));
$_wlabel_user->wlabel_Export('client','CLIENTES','table');
?>


<div class="module_title">
    USUARIOS POR DIA
</div>

<div class="section">
    <div class="tables">
    <table cellspacing="0" cellpadding="0">
        <thead>
        <tr>
            <th>Titulo</th>
            <?php
                $day_init=strtotime(date('m/d/Y',$WLresult->time));
                $day_last=strtotime(date('m/d/Y',time()));
                $day_more=(24*60*60);

                for($day=$day_init; $day<=$day_last ; $day=$day+$day_more){
                    echo '<th class="day tdshow" data-check="day" data-month="'.date('n',$day).'" data-year="'.date('Y',$day).'">'.date('d/m/Y',$day).'</th>';//date('d',$day).'--'.
                    if(date('t',$day)==date('d',$day) || $day_last==$day){
                        echo '<th class="month tdshow" data-check="month" data-month="'.date('n',$day).'" data-year="'.date('Y',$day).'">'.date('F/Y',$day).'</th>';
                        if(date('m',$day)=='12' || $day_last==$day){
                            echo '<th class="year tdshow" data-check="year" data-month="'.date('n',$day).'" data-year="'.date('Y',$day).'">'.date('Y',$day).'</th>';
                        }
                    }
                }
            echo '<th class="total tdshow" data-check="total">TOTAL</th>';
            ?>

        </tr>
        </thead>
        <tbody>



<?php
//USER
$sql = "
    SELECT DISTINCT
      users.ID,
      users.user_login as login,
      users.user_registered as date

    FROM
      wp_users AS users
      LEFT JOIN wp_usermeta AS usermeta ON (usermeta.user_id=users.ID AND usermeta.meta_key='_wlabel')
      LEFT JOIN wp_posts AS posts ON (posts.post_author=users.ID)
      LEFT JOIN wp_postmeta AS postmeta ON (postmeta.post_id=posts.post_parent AND postmeta.meta_key='_wlabel')

    WHERE
      (
        usermeta.meta_value = '{$wlabel}'
        OR
        (postmeta.meta_value = '{$wlabel}'  AND posts.post_type = 'wc_booking' AND NOT posts.post_status LIKE '%cart%')
      )

    ORDER BY
      users.ID DESC
";
/*
*/

//var_dump($sql);
//var_dump($wpdb);
$users = $wpdb->get_results($sql);
//var_dump(count($users));
//var_dump($users);
//var_dump($users[0]);


$BUILDusers = array();
foreach($users as $key => $user){
    //var_dump($user);
    $ID=$user->ID;
    $date=strtotime($user->date);
    $login=$user->login;

   $BUILDusers[$ID] = array();
   $BUILDusers[$ID]['user'] = $ID;
   $BUILDusers[$ID]['login'] = $login;
   $BUILDusers[$ID]['date'] = $date;
   //$BUILDusers[$ID][''] = ;
}


//USER ONLY REGISTER
$sql = "
        SELECT DISTINCT
          users.ID,
          users.user_login as login,
          users.user_registered as date

        FROM
          wp_users AS users
          LEFT JOIN wp_usermeta AS usermeta ON (usermeta.user_id=users.ID AND usermeta.meta_key='_wlabel')

        WHERE
          usermeta.meta_value = '{$wlabel}'

        ORDER BY
          users.ID DESC
    ";
/*
 */

//var_dump($sql);
//var_dump($wpdb);
$users_register = $wpdb->get_results($sql);
//var_dump(count($users_register));
//var_dump($users);
//var_dump($users[0]);


$BUILDusers_register = array();
foreach($users_register as $key => $user){
    //var_dump($user);
    $ID=$user->ID;
    $date=strtotime($user->date);
    $login=$user->login;

    $BUILDusers_register[$ID] = array();
    $BUILDusers_register[$ID]['user'] = $ID;
    $BUILDusers_register[$ID]['login'] = $login;
    $BUILDusers_register[$ID]['date'] = $date;
    //$BUILDusers[$ID][''] = ;
}


//POSTS
$sql = "
        SELECT DISTINCT
          posts.ID,
          posts.post_author as author

        FROM
          wp_posts AS posts
          LEFT JOIN wp_postmeta AS postmeta ON (postmeta.post_id=posts.post_parent AND postmeta.meta_key='_wlabel')

        WHERE
          postmeta.meta_value = '{$wlabel}'
          AND
          posts.post_type = 'wc_booking'
          AND NOT
          posts.post_status LIKE '%cart%'

        ORDER BY
          posts.ID DESC
    ";

//var_dump($sql);
//var_dump($wpdb);
$posts = $wpdb->get_results($sql);
//var_dump(count($posts));
//var_dump($posts);


$BUILDposts = array();
foreach($posts as $key => $post){
    //var_dump($user);
    $ID=$post->ID;
    $author=$post->author;

    $BUILDposts[$author] = array();
    $BUILDposts[$author]['post'] = $ID;
    $BUILDposts[$author]['author'] = $author;
    //$BUILDusers[$ID][''] = ;
}




//CANTIDAD DE USUARIOS
echo '<tr>';
 echo '<th class="title">Cantidad de usuarios totales utilizando el Wlabel(registrados con wlabel '.$wlabel.' y reservando con wlabel)</th>';
    $day_init=strtotime(date('m/d/Y',$WLresult->time));
    $day_last=strtotime(date('m/d/Y',time()));
    $day_more=(24*60*60);

    $amount_day=0;
    $amount_month=0;
    $amount_year=0;
    $amount_total=0;

    for($day=$day_init; $day<=$day_last ; $day=$day+$day_more){

        foreach($BUILDusers as $user){
            if(strtotime(date('m/d/Y',$user['date']))==strtotime(date('m/d/Y',$day))){
                $amount_user=0;
                //if($user['status']!='cancelled'){}
                    $amount_user=1;

                $amount_user=(round($amount_user*100)/100);
                $amount_day=$amount_day+$amount_user;
                $amount_month=$amount_month+$amount_user;
                $amount_year=$amount_year+$amount_user;
                $amount_total=$amount_total+$amount_user;
            }
        }


        echo '<td class="day tdshow" data-check="day" data-month="'.date('n',$day).'" data-year="'.date('Y',$day).'">'.$amount_day.'</td>';
        $amount_day=0;

        if(date('t',$day)==date('d',$day) || $day_last==$day){
            echo '<td class="month tdshow" data-check="month" data-month="'.date('n',$day).'" data-year="'.date('Y',$day).'">'.$amount_month.'</td>';
            $amount_month=0;

            if(date('m',$day)=='12' || $day_last==$day){
                echo '<th class="year tdshow" data-check="year" data-month="'.date('n',$day).'" data-year="'.date('Y',$day).'">'.$amount_year.'</th>';
                $amount_year=0;
            }
        }
    }
echo '<th class="total tdshow" data-check="total">'.$amount_total.'</th>';
echo '</tr>';


//CANTIDAD DE USUARIOS REGISTRADOS
echo '<tr>';
echo '<th class="title">Usuarios registrados con el Wlabel</th>';
$day_init=strtotime(date('m/d/Y',$WLresult->time));
$day_last=strtotime(date('m/d/Y',time()));
$day_more=(24*60*60);

$amount_day=0;
$amount_month=0;
$amount_year=0;
$amount_total=0;

for($day=$day_init; $day<=$day_last ; $day=$day+$day_more){

    foreach($BUILDusers_register as $user){
        if(strtotime(date('m/d/Y',$user['date']))==strtotime(date('m/d/Y',$day))){
            $amount_user=0;
            //if(!array_key_exists($user['user'],$BUILDposts)){}
                $amount_user=1;

            $amount_user=(round($amount_user*100)/100);
            $amount_day=$amount_day+$amount_user;
            $amount_month=$amount_month+$amount_user;
            $amount_year=$amount_year+$amount_user;
            $amount_total=$amount_total+$amount_user;
        }
    }


    echo '<td class="day tdshow" data-check="day" data-month="'.date('n',$day).'" data-year="'.date('Y',$day).'">'.$amount_day.'</td>';
    $amount_day=0;

    if(date('t',$day)==date('d',$day) || $day_last==$day){
        echo '<td class="month tdshow" data-check="month" data-month="'.date('n',$day).'" data-year="'.date('Y',$day).'">'.$amount_month.'</td>';
        $amount_month=0;

        if(date('m',$day)=='12' || $day_last==$day){
            echo '<th class="year tdshow" data-check="year" data-month="'.date('n',$day).'" data-year="'.date('Y',$day).'">'.$amount_year.'</th>';
            $amount_year=0;
        }
    }
}
echo '<th class="total tdshow" data-check="total">'.$amount_total.'</th>';
echo '</tr>';
//




//CANTIDAD DE USUARIOS RESERVANDO
echo '<tr>';
echo '<th class="title">Usuarios reservando con el Wlabel</th>';
$day_init=strtotime(date('m/d/Y',$WLresult->time));
$day_last=strtotime(date('m/d/Y',time()));
$day_more=(24*60*60);

$amount_day=0;
$amount_month=0;
$amount_year=0;
$amount_total=0;

for($day=$day_init; $day<=$day_last ; $day=$day+$day_more){

    foreach($BUILDusers as $user){
        if(strtotime(date('m/d/Y',$user['date']))==strtotime(date('m/d/Y',$day))){
            $amount_user=0;
            if(array_key_exists($user['user'],$BUILDposts)){
                $amount_user=1;
            }
            $amount_user=(round($amount_user*100)/100);
            $amount_day=$amount_day+$amount_user;
            $amount_month=$amount_month+$amount_user;
            $amount_year=$amount_year+$amount_user;
            $amount_total=$amount_total+$amount_user;
        }
    }


    echo '<td class="day tdshow" data-check="day" data-month="'.date('n',$day).'" data-year="'.date('Y',$day).'">'.$amount_day.'</td>';
    $amount_day=0;

    if(date('t',$day)==date('d',$day) || $day_last==$day){
        echo '<td class="month tdshow" data-check="month" data-month="'.date('n',$day).'" data-year="'.date('Y',$day).'">'.$amount_month.'</td>';
        $amount_month=0;

        if(date('m',$day)=='12' || $day_last==$day){
            echo '<th class="year tdshow" data-check="year" data-month="'.date('n',$day).'" data-year="'.date('Y',$day).'">'.$amount_year.'</th>';
            $amount_year=0;
        }
    }
}
echo '<th class="total tdshow" data-check="total">'.$amount_total.'</th>';
echo '</tr>';



//CANTIDAD DE USUARIOS REGISTRADOS
echo '<tr>';
echo '<th class="title">Usuarios registrados con el wlabel '.$wlabel.' Reservando</th>';
$day_init=strtotime(date('m/d/Y',$WLresult->time));
$day_last=strtotime(date('m/d/Y',time()));
$day_more=(24*60*60);

$amount_day=0;
$amount_month=0;
$amount_year=0;
$amount_total=0;

for($day=$day_init; $day<=$day_last ; $day=$day+$day_more){

    foreach($BUILDusers_register as $user){
        if(strtotime(date('m/d/Y',$user['date']))==strtotime(date('m/d/Y',$day))){
            $amount_user=0;
            if(array_key_exists($user['user'],$BUILDposts)){
                $amount_user=1;
            }
            $amount_user=(round($amount_user*100)/100);
            $amount_day=$amount_day+$amount_user;
            $amount_month=$amount_month+$amount_user;
            $amount_year=$amount_year+$amount_user;
            $amount_total=$amount_total+$amount_user;
        }
    }


    echo '<td class="day tdshow" data-check="day" data-month="'.date('n',$day).'" data-year="'.date('Y',$day).'">'.$amount_day.'</td>';
    $amount_day=0;

    if(date('t',$day)==date('d',$day) || $day_last==$day){
        echo '<td class="month tdshow" data-check="month" data-month="'.date('n',$day).'" data-year="'.date('Y',$day).'">'.$amount_month.'</td>';
        $amount_month=0;

        if(date('m',$day)=='12' || $day_last==$day){
            echo '<th class="year tdshow" data-check="year" data-month="'.date('n',$day).'" data-year="'.date('Y',$day).'">'.$amount_year.'</th>';
            $amount_year=0;
        }
    }
}
echo '<th class="total tdshow" data-check="total">'.$amount_total.'</th>';
echo '</tr>';



 ?>
        </tbody>
    </table>
    </div>
</div>



