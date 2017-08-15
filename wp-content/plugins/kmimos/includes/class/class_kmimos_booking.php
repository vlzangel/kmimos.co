<?php

class Class_Kmimos_Booking{

    var $args;

    function __construct($args=array()){
        global $wpdb;
        $this->args = $args;
        $this->wpdb = $wpdb;

        ///Booking meta
        $this->order = 0;
        $this->meta_order = array();

        $this->booking = 0;
        $this->meta_booking = array();

        //Product
        $this->product = array();
        $this->meta_product = array();

        //USER
        $this->user_client = 0;
        $this->user_caregiver = 0;

        $this->user_meta_client = array();
        $this->user_meta_caregiver = array();
    }

    function Booking_Details($IDbooking=0){
        if($IDbooking!=0) {
            $table_posts=$this->wpdb->posts;
            $query="SELECT * FROM $table_posts WHERE post_type = 'wc_booking' AND post_parent = '$IDbooking'";
            $reserva = $this->wpdb->get_row($query);

            if(!is_null($reserva)){
                $this->order = $IDbooking;
                $this->meta_order = get_post_meta($IDbooking);

                $this->booking = $reserva->ID;
                $this->meta_booking = get_post_meta($reserva->ID);
                $this->Booking_Get_Details_User($IDbooking);
            }
        }
    }

    //BOOKING USERS
    function Booking_Get_Details_User($IDbooking=0){
        if(count($this->meta_order)==0){
           $this->Booking_Details($IDbooking);
        }

        if(count($this->meta_product)==0){
            $this->Booking_Product($IDbooking);
        }

        $this->user_client = $this->meta_order["_customer_user"][0];
        $this->user_meta_client = get_user_meta($this->user_client);

        $this->user_caregiver=$this->product->post_author;
        $this->user_meta_caregiver = get_user_meta($this->user_caregiver);
    }

    function Booking_Get_Details_Client($IDbooking=0){
        if(count($this->meta_order)==0){
            $this->Booking_Get_Details_User($IDbooking);
        }
        return $this->user_meta_client;
    }

    function Booking_Get_Details_Caregiver($IDbooking=0){
        if(count($this->meta_order)==0){
            $this->Booking_Get_Details_User($IDbooking);
        }
        return $this->user_meta_caregiver;
    }

    function Booking_Product($IDbooking=0){
        if(count($this->meta_booking)==0){
            $this->Booking_Details($IDbooking);
        }

        $table_posts=$this->wpdb->posts;
        $IDproduct=$this->meta_booking['_booking_product_id'][0];
        $query="SELECT * FROM $table_posts WHERE ID = '$IDproduct'";
        $this->product = $this->wpdb->get_row($query);

        if(!is_null($this->product)){
            $this->meta_product = get_post_meta($this->product->ID);
            $this->Booking_Get_Details_User($IDbooking);
        }
    }
}

$kmimos_load=dirname(dirname(dirname(dirname(dirname(dirname(__FILE__)))))).'/wp-load.php';
if(file_exists($kmimos_load)){
    include_once($kmimos_load);
}

$_kmimos_booking = new Class_Kmimos_Booking();


