<?php
$kmimos_load=dirname(dirname(dirname(dirname(dirname(dirname(dirname(dirname(__FILE__)))))))).'/wp-load.php';
if(file_exists($kmimos_load)){
    include_once($kmimos_load);
}

global $wpdb;
$wlabel=$_wlabel_user->wlabel;
$WLresult=$_wlabel_user->wlabel_result;
$_wlabel_user->wLabel_Filter(array('tddate','tdcheck'));
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
    SELECT
      users.ID,
      users.user_login as login,
      users.user_registered as date,
      usermeta.meta_value as label

    FROM
      wp_users AS users
      LEFT JOIN wp_usermeta AS usermeta ON (usermeta.user_id=users.ID AND usermeta.meta_key='_wlabel')
      LEFT JOIN wp_posts AS posts ON (posts.post_author=users.ID)
      LEFT JOIN wp_postmeta AS postmeta ON (postmeta.post_id=posts.post_parent AND postmeta.meta_key='_wlabel')

    WHERE
      (
        usermeta.meta_value = '{$wlabel}'
        OR
        (postmeta.meta_value = '{$wlabel}' AND posts.post_type = 'wc_booking' AND NOT posts.post_status LIKE '%cart%')
      )

    GROUP BY
      users.ID

    ORDER BY
      users.ID DESC
";
/*,
      posts.ID
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
    $date=$user->date;
    $login=$user->login;
    $label=$user->label;

    $sql = "
        SELECT
          posts.ID as pid,
          posts.post_date as pdate

        FROM
          wp_posts AS posts
          LEFT JOIN wp_postmeta AS postmeta ON (postmeta.post_id=posts.post_parent)

        WHERE
          posts.post_author = '{$ID}'
          AND
          (posts.post_type = 'wc_booking' AND NOT posts.post_status LIKE '%cart%')

        ORDER BY
          posts.ID DESC
      ";

    if($label!=$wlabel){
        $sql = "
        SELECT
          posts.ID as pid,
          posts.post_date as pdate

        FROM
          wp_posts AS posts
          LEFT JOIN wp_postmeta AS postmeta ON (postmeta.post_id=posts.post_parent AND postmeta.meta_key='_wlabel')

        WHERE
          posts.post_author = '{$ID}'
          AND
          (postmeta.meta_value = '{$wlabel}'  AND posts.post_type = 'wc_booking' AND NOT posts.post_status LIKE '%cart%')

        ORDER BY
          posts.ID DESC
      ";

    }


    $posts = $wpdb->get_results($sql);
    //var_dump($posts);
    //$pdate=strtotime($user->pdate);
    //$pid=strtotime($user->pid);

    //CUSTOMER
    $_metas_customer = get_user_meta($ID);
    $_customer_name = $_metas_customer['first_name'][0] . " " . $_metas_customer['last_name'][0];

    $BUILDusers[$ID] = array();
    $BUILDusers[$ID]['user'] = $ID;
    $BUILDusers[$ID]['name'] = $_customer_name;
    $BUILDusers[$ID]['login'] = $login;
    $BUILDusers[$ID]['label'] = $label;
    $BUILDusers[$ID]['date'] = strtotime($date);
    $BUILDusers[$ID]['posts'] = array();
/**/
    if(count($posts)>0){
        foreach($posts as $post){
            $BUILDusers[$ID]['posts'][$post->pid] = array('id'=>$post->pid,'date'=>strtotime($post->pdate));
        }
    }

}

//var_dump($BUILDusers);

//CANTIDAD DE RESERVAS POR USUARIO
foreach($BUILDusers as $user){
    echo '<tr>';
     echo '<th class="title">'.$user['name'].'</th>';//.' '.$user['label']
        $day_init=strtotime(date('m/d/Y',$WLresult->time));
        $day_last=strtotime(date('m/d/Y',time()));
        $day_more=(24*60*60);

        $amount_day=0;
        $amount_month=0;
        $amount_year=0;
        $amount_total=0;

        for($day=$day_init; $day<=$day_last ; $day=$day+$day_more){

            foreach($user['posts'] as $puser){
                if(strtotime(date('m/d/Y',$puser['date']))==strtotime(date('m/d/Y',$day))){
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
}


 ?>
        </tbody>
    </table>
    </div>
</div>

