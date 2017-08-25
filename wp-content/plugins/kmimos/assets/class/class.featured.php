<?php
class kmimos_featured_class extends WP_Widget {
	public function __construct(){
		$options = array(
			"classname"=>"featured_css",
			"description"=>"Cuidadores destacados"
		);
		$this->WP_Widget('featured_id','Kmimos Cuidadores Destacados',$options);
//        parent::__construct( false, __( 'My New Widget Title', 'textdomain' ) );
	}
	public function form($instance){
		$defaults = array(
			"titulo"=>"Cuidadores Destacados"
		);
		$instance = wp_parse_args((array)$instance, $defaults);
		$titulo = esc_attr($instance["titulo"]);
?>
		<p>Título: <input type="text" name="<?php echo $this->get_field_name("titulo");?>" value="<?php echo $titulo;?>" class="widefat" /></p>
<?php
	}
	public function update($new_instance, $old_instance){
		$instance = $old_instance;
		$instance['titulo'] = strip_tags($new_instance['titulo']);
        return $instance;
	}
	public function widget($args, $instance){
		extract($args);
		$titulo = apply_filters('widget_title',$instance['titulo']);
		echo $before_widget;
		echo $before_title.$titulo.$after_title;
        $pais = 'mx';
        
        $ubicaciones = $_GET['ubicacion_cuidador'];
        if(!$ubicaciones) $meta = array('relation' => 'OR', array('key'=>'featured_petsitter','value'=>'1','compare' => '='));
        else {
            $by_locations = array('relation' => 'OR');
            foreach($ubicaciones as $location){
                $by_locations[]=array('key'=>'location_petsitter','value'=>$location,'compare' => 'LIKE');
            }
            $meta = array('relation' => 'AND', array('key'=>'featured_petsitter','value'=>'1','compare' => '='),$by_locations);
        }
//print_r($ubicaciones);
        $params = array(
            'posts_per_page' => 5,
            'orderby' => 'rand',
            'meta_query' => $meta,
            'post_type' => 'petsitters',
            'post_status' => 'publish'
        );

        $cuidadores = get_posts($params);
//print_r($params);
        
?>
<div class="pfwidgetinner">
    <ul class="pf-widget-itemlist">
<?php
        foreach($cuidadores as $cuidador){
            $desde = get_post_meta( $cuidador->ID, 'precio_desde', true);
            $location = get_post_meta( $cuidador->ID, 'location_petsitter', true);
            $mpo = get_term_by('slug', $location, 'pointfinderlocations');
            $edo = get_term_by('slug', substr($location,0,5), 'pointfinderlocations');
//print_r($mpo);
?>
        <li class="clearfix widget-featured">
            <a href="<?php echo get_permalink($cuidador->ID); ?>" title="">
                <?php echo get_the_post_thumbnail($cuidador->ID, 'thumbnail', array( 'class' => 'alignleft img40x40' )); ?>
                <div class="title"><?php echo get_the_title ($cuidador->ID); ?></div>
                <div class="price">Hospedaje desde $<?php echo number_format($desde, 2, '.', ','); ?>/noche</div>
                <sup><?php echo $mpo->name; ?> - <?php echo $edo->name; ?></sup>
            </a>
        </li>
<?php
        }
?>
    </ul>
</div>
<?php 
		echo $after_widget;
	}
}
?>