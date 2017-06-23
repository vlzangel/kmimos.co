<?php
    $user_id = $current_user->ID;
    $count = ($params['count']!='')? $params['count']: 0;
    $this->FieldOutput .= '<div class="lista_pendientes" data-user="'.$user_id.'"><ul>';
    if($count>0) {
        $issues = explode(",",$params['list']);
        $this->FieldOutput .= '<div class="inside">';
        $this->FieldOutput .= '<ul>';
        foreach($issues as $issue){
            $this->FieldOutput .= '<li><a href="'.$params['detail_url'].$issue.'">'. get_the_title($issue).'</a></li>';
        }
        $this->FieldOutput .= '</ul>';
        $this->FieldOutput .= '<br><p>Total: '.count($issues).' asuntos pendientes</p>';
        $this->FieldOutput .= '</div>';
    }else{
        $this->FieldOutput .=  '<p>No tienes ningún asunto pendiente</p>';
    }
    $this->FieldOutput .=  '</ul><br></div>';
?>