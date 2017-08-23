<?php
    //PRODUCT COLUMN
    add_filter('manage_product_posts_columns', 'products_columns_head');
    add_action('manage_product_posts_custom_column', 'products_columns_content', 10, 2);

    // ADD NEW COLUMN
    function products_columns_head($defaults){
        $columns=array();
        $defaults['dashboard_panel'] = 'Panel de Ajustes';
        //$columns[]=$defaults;
        return $defaults;
    }

    // SHOW THE FEATURED IMAGE
    function products_columns_content($column_name, $post_ID) {

        if ($column_name == 'dashboard_panel') {
            global $wpdb;
            $query="SELECT post_author FROM wp_posts WHERE ID='{$post_ID}' AND post_type='product'";
            $result=$wpdb->get_var($query);
            $link=site_url().'?i='.md5($result);
            echo '<a href="'.$link.'">Editar</a>';
        }
    }


?>