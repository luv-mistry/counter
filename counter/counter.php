<?php
/*
 * Plugin Name: Counter
 */

 if ( ! defined( 'ABSPATH' ) ) { 
	exit; // Exit if accessed directly
}

if (! function_exists('is_plugin_active')) {
	include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
}


if ( !is_plugin_active( 'sales-countdown-timer-for-woocommerce/sales-countdown-timer-for-woocommerce.php') ) {

	function my_admin_notice() {

		// Deactivate the plugin
		deactivate_plugins(__FILE__);
		$error_message = __('This plugin requires FME Addons: Sales Countdown Timer For Woocommerce plugin to be installed and active!', 'woocommerce');
		echo esc_attr( $error_message );
		wp_die();
	}
	add_action( 'admin_notices', 'my_admin_notice' );
}

function counter_display (){
    
    $strdate = '';
    $enddate = '';
    $allrulesfmesn = get_option('allrulesfmesn');
	if ('' == $allrulesfmesn) {
		$allrulesfmesn = array();
	}

    foreach ($allrulesfmesn as $key => $value){
        $start = strtotime($value['start_date']);
        $end = strtotime($value['end_date']);
        $status = $value['status'];
        if ($status == 'Enable'){
            if ($strdate == '' && $enddate == '' ) {
                $strdate = $start;
                $enddate = $end;
            }  
            else {
                if ($strdate > $start  ){
                    $strdate = $start;

                }
                if ($enddate < $end  ){
                    $enddate = $end;

                }
            }
        }
    }

    ?>
    <script>
        jQuery(document).ready(function(){
            console.log(typeof '<?php echo date('y/m/d', $enddate); ?>');
            var countDownDate = new Date('<?php echo date('Y-m-d', $enddate); ?>').setHours(0)+86400000;;
            console.log(countDownDate);
					var x = setInterval(function() {
						var now = new Date().getTime();
                        

						var distance = countDownDate - now;

                        console.log(distance);


						var days = Math.floor(distance / (1000 * 60 * 60 * 24));
						var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
						var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
						var seconds = Math.floor((distance % (1000 * 60)) / 1000);

                       // console.log(days);
                        document.getElementById("day").innerHTML = days;
                        document.getElementById("hours").innerHTML = hours;
                        document.getElementById("minutes").innerHTML = minutes ;
                        document.getElementById("seconds").innerHTML =seconds ;

						

						if (distance < 0) {
							clearInterval(x);
							document.getElementById("countdown").innerHTML = "EXPIRED";
						}
					}, 1000);
        })

        


    </script>
    

    
    
    <?php

     if ($strdate > strtotime(date("Y/m/d"))){
         return '';
     }
     else {

        $countdown = '<div id="countdown">' ;
        $countdown .= '<span id="day"></span><span> Days </span>' ;
        $countdown .= '<span id="hours"></span><span> Hrs </span>' ;
        $countdown .= '<span id="minutes"></span><span> Min </span>' ;
        $countdown .= '<span id="seconds"></span><span> Sec </span>' ;
        $countdown .= '</div>' ;


         return $countdown ;
     }


}
add_shortcode( 'counter', 'counter_display' );





















?>