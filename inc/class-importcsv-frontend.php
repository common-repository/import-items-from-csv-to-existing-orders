<?php
/**
 * Import CSV Items
 *
 * @package import-order-items-from-csv
 * @since   1.0
 */

defined( 'ABSPATH' ) || exit;

class Importcsv_Frontend {
	public function __construct() {

   add_action( 'woocommerce_order_item_add_action_buttons', array( $this, 'action_woocommerce_order_item_add_action_buttons' ), 10, 1);
  }
	// add new button for woocommerce

// define the woocommerce_order_item_add_action_buttons callback
function action_woocommerce_order_item_add_action_buttons( $order )
{

    echo '<button type="button" id="showcsvform" class="button generate-items">' . __( 'CSV Import', 'itemswcorders' ) . '</button>';
    // indicate its taopix order generator button

    echo '
  <style>
.csvresouter{
    overflow-y: auto;
	overflow-x:hidden;
    height:350px;
}
#importcsvloading {
    background-color: #2c3e50;
    position: fixed;
    height: 100%;
    width: 100%;
    left: 0;
    z-index:55;
    display:none;
    top: 0;
}
.sk-fading-circle {
    margin: 0 auto;
    top:30%;
    width: 100px;
    height: 100px;
    position: relative;
}
.sk-fading-circle .sk-circle {
    width: 100%;
    height: 100%;
    position: absolute;
    left: 0;
    top: 0;
}
.sk-fading-circle .sk-circle:before {
    content: "";
    display: block;
    margin: 0 auto;
    width: 15%;
    height: 15%;
    background-color: #fff;
    border-radius: 100%;
    -webkit-animation: sk-circleFadeDelay 1.2s infinite ease-in-out both;
    animation: sk-circleFadeDelay 1.2s infinite ease-in-out both;
}
.sk-fading-circle .sk-circle2 {
    -webkit-transform: rotate(30deg);
    -ms-transform: rotate(30deg);
    transform: rotate(30deg);
}
.sk-fading-circle .sk-circle3 {
    -webkit-transform: rotate(60deg);
    -ms-transform: rotate(60deg);
    transform: rotate(60deg);
}
.sk-fading-circle .sk-circle4 {
    -webkit-transform: rotate(90deg);
    -ms-transform: rotate(90deg);
    transform: rotate(90deg);
}
.sk-fading-circle .sk-circle5 {
    -webkit-transform: rotate(120deg);
    -ms-transform: rotate(120deg);
    transform: rotate(120deg);
}
.sk-fading-circle .sk-circle6 {
    -webkit-transform: rotate(150deg);
    -ms-transform: rotate(150deg);
    transform: rotate(150deg);
}
.sk-fading-circle .sk-circle7 {
    -webkit-transform: rotate(180deg);
    -ms-transform: rotate(180deg);
    transform: rotate(180deg);
}
.sk-fading-circle .sk-circle8 {
    -webkit-transform: rotate(210deg);
    -ms-transform: rotate(210deg);
    transform: rotate(210deg);
}
.sk-fading-circle .sk-circle9 {
    -webkit-transform: rotate(240deg);
    -ms-transform: rotate(240deg);
    transform: rotate(240deg);
}
.sk-fading-circle .sk-circle10 {
    -webkit-transform: rotate(270deg);
    -ms-transform: rotate(270deg);
    transform: rotate(270deg);
}
.sk-fading-circle .sk-circle11 {
    -webkit-transform: rotate(300deg);
    -ms-transform: rotate(300deg);
    transform: rotate(300deg);
}
.sk-fading-circle .sk-circle12 {
    -webkit-transform: rotate(330deg);
    -ms-transform: rotate(330deg);
    transform: rotate(330deg);
}
.sk-fading-circle .sk-circle2:before {
    -webkit-animation-delay: -1.1s;
    animation-delay: -1.1s;
}
.sk-fading-circle .sk-circle3:before {
    -webkit-animation-delay: -1s;
    animation-delay: -1s;
}
.sk-fading-circle .sk-circle4:before {
    -webkit-animation-delay: -0.9s;
    animation-delay: -0.9s;
}
.sk-fading-circle .sk-circle5:before {
    -webkit-animation-delay: -0.8s;
    animation-delay: -0.8s;
}
.sk-fading-circle .sk-circle6:before {
    -webkit-animation-delay: -0.7s;
    animation-delay: -0.7s;
}
.sk-fading-circle .sk-circle7:before {
    -webkit-animation-delay: -0.6s;
    animation-delay: -0.6s;
}
.sk-fading-circle .sk-circle8:before {
    -webkit-animation-delay: -0.5s;
    animation-delay: -0.5s;
}
.sk-fading-circle .sk-circle9:before {
    -webkit-animation-delay: -0.4s;
    animation-delay: -0.4s;
}
.sk-fading-circle .sk-circle10:before {
    -webkit-animation-delay: -0.3s;
    animation-delay: -0.3s;
}
.sk-fading-circle .sk-circle11:before {
    -webkit-animation-delay: -0.2s;
    animation-delay: -0.2s;
}
.sk-fading-circle .sk-circle12:before {
    -webkit-animation-delay: -0.1s;
    animation-delay: -0.1s;
}
@-webkit-keyframes sk-circleFadeDelay {
    0%, 39%, 100% {
        opacity: 0;
    }
    40% {
        opacity: 1;
    }
}
@keyframes sk-circleFadeDelay {
    0%, 39%, 100% {
        opacity: 0;
    }
    40% {
        opacity: 1;
    }
}
#csvfilediv{
display:none;
float: left;
    border: 1px solid #ddd;
    padding: 0 4px;
    width: 192px;
}
#closecsvdiv{
  font-size: 18px;
    color: red;
    cursor:pointer;
}
  </style>
  <div id="dialogcsvres" title="'.esc_attr( 'CSV Import: Confirm Import', 'itemswcorders' ).'" style="display:none;">
      </p>
  </div>
  <div id="importcsvloading">
  <div class="sk-fading-circle">
  <div class="sk-circle1 sk-circle"></div>
  <div class="sk-circle2 sk-circle"></div>
  <div class="sk-circle3 sk-circle"></div>
    <div class="sk-circle4 sk-circle"></div>
    <div class="sk-circle5 sk-circle"></div>
    <div class="sk-circle6 sk-circle"></div>
    <div class="sk-circle7 sk-circle"></div>
    <div class="sk-circle8 sk-circle"></div>
    <div class="sk-circle9 sk-circle"></div>
    <div class="sk-circle10 sk-circle"></div>
    <div class="sk-circle11 sk-circle"></div>
    <div class="sk-circle12 sk-circle"></div>
  </div>
  </div>
   <div id="csvfilediv">
   <p>
<input name="uploadcsv" id="uploadcsv" type="file" accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel" style="
">
</p>
  
  <input type="hidden" value="1" name="renew_order" />
  
 
</div>';


echo "<script>
(function ($) {   
  $(document).ready(function(){
     $('#showcsvform').click(function(e){
      $('#csvfilediv').fadeIn(400);
      });

      $('#closecsvdiv').click(function(e){
      $('#csvfilediv').fadeOut(400);
      });
       $(document).on('click','#closesummerydialog',function(){
         $('#uploadcsv').val('');
        $('#dialogcsvres').dialog('close');
        });
        
        $('div#dialogcsvres').on('dialogclose', function(event) {
             $('#uploadcsv').val('');
         });

        $('#uploadcsv').change(function(e){
        var formData = new FormData();
            var file = this.files[0];
            formData.append('action', 'csvimport');
            formData.append('order_id', '".$order->get_id()."');
            formData.append('csvfile', file);
    
            $('#importcsvloading').show();
        jQuery.ajax({
            url: ajaxurl,
            type: 'POST',
            data: formData, 
            cache: false,
            processData: false, 
            contentType: false,     
            success: function(data) {
              $( '#dialogcsvres' ).html(data);
                      $( '#dialogcsvres' ).dialog({
            dialogClass: 'importcsv-dialog',
            width: '650',
            modal: true
            });
              
            },
            complete: function(){
           $('#importcsvloading').hide();
          }
        });
               
            

           
            
        });
    });
  
})(jQuery);
</script>
";

}

}


return new Importcsv_Frontend;