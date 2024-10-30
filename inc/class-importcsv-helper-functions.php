<?php
/**
 * Import CSV Items
 *
 * @package import-order-items-from-csv
 * @since   3.2.0
 */

defined( 'ABSPATH' ) || exit;

class Importcsv_Helper_Functions {

	public function __construct() {
    add_action( 'post_edit_form_tag' , array( $this, 'post_edit_form_tag' ) );
    add_action('wp_ajax_csvimport', array( $this, 'my_ajax_csvimport_handler' ));
    add_action('save_post', array($this, 'renew_save_again'), 10, 3);
    add_action( 'admin_enqueue_scripts', array($this, 'enqueue_scripts') );
    add_action( 'admin_enqueue_scripts', array($this, 'enqueue_styles') );
  }

  public function wc_get_product_id_by_variation_sku($sku) {
    $args = array(
    'post_type'  => 'product_variation',
    'meta_query' => array(
        array(
            'key'   => '_sku',
            'value' => $sku,
        )
    )
);
$posts = get_posts( $args);
if(isset($posts) && is_array($posts )){
  foreach ( $posts as $post ) {
      return $post->ID;
  }
}
    

  }
	

	public function post_edit_form_tag( ) {
	   echo ' enctype="multipart/form-data"';
	}

	

public function enqueue_scripts()
{
    wp_enqueue_script( 'jquery-ui-dialog' );
}
public function enqueue_styles()
{
    
     wp_enqueue_style( 'jquery-ui-theme-smoothness', plugins_url( 'assets/jquery-ui.css', dirname(__FILE__) ) );
}

public function my_ajax_csvimport_handler(){


if (isset($_FILES['csvfile']) && is_uploaded_file($_FILES['csvfile']['tmp_name'])) {
  // READ FILE CONTENTS
  $csv = file_get_contents($_FILES['csvfile']['tmp_name']);
  
   $lines = explode("\n", $csv);
  //remove the first element from the array
  //$head = str_getcsv(array_shift($lines));
  $htmlford = '<div class="csvresouter">';
  $htmlford .= '<table class="widefat fixed" cellspacing="0">';
  $htmlford .= '<thead>
    <tr>
            <th class="manage-column" scope="col" style="width:55px;"><strong>'.__('Row #','itemswcorders').'</strong></th>
            <th class="manage-column" scope="col"><strong>'.__('SKU','itemswcorders').'</strong></th>
            <th class="manage-column" scope="col"><strong>'.__('PRICE','itemswcorders').'</strong></th>
            <th class="manage-column" scope="col"><strong>'.__('QTY','itemswcorders').'</strong></th>
            <th class="manage-column" scope="col"><strong>'.__('STATUS','itemswcorders').'</strong></th>
    </tr>
    </thead>';
  $htmlford .= '<tbody>';
  $rowcount = 1;
  foreach ($lines as $line) {
  
    $getsku = explode(",", $line);
    if(isset($getsku[0]) && !empty($getsku[0])){
      if($this->wc_get_product_id_by_variation_sku($getsku[0])){
         $pID = $this->wc_get_product_id_by_variation_sku($getsku[0]);
      }
    else{
      $pID = wc_get_product_id_by_sku( $getsku[0] );
    }
  
      if($pID){
        
        $fieldsarr = explode(',', $line);
        if($rowcount % 2){
          $htmlford .= '<tr class=""><td>'.$rowcount.'</td><td>'.$fieldsarr[0].'</td><td>'.get_woocommerce_currency_symbol().$fieldsarr[1].'</td><td>'.$fieldsarr[2].'</td><td><font color="green">'.__('Added','itemswcorders').'</font></td></tr>';
        }else{
          $htmlford .= '<tr class="alternate"><td>'.$rowcount.'</td><td>'.$fieldsarr[0].'</td><td>'.get_woocommerce_currency_symbol().$fieldsarr[1].'</td><td>'.$fieldsarr[2].'</td><td><font color="green">'.__('Added','itemswcorders').'</font></td></tr>';
        }
        
      }else{
        $fieldsarr = explode(',', $line);
        if($rowcount % 2){
          $htmlford .= '<tr class=""><td>'.$rowcount.'</td><td>'.$fieldsarr[0].'</td><td>'.get_woocommerce_currency_symbol().$fieldsarr[1].'</td><td>'.$fieldsarr[2].'</td><td><font color="red">'.__('Skipped','itemswcorders').'</font></td></tr>';
        }else{
          $htmlford .= '<tr class="alternate"><td>'.$rowcount.'</td><td>'.$fieldsarr[0].'</td><td>'.get_woocommerce_currency_symbol().$fieldsarr[1].'</td><td>'.$fieldsarr[2].'</td><td><font color="red">'.__('Skipped','itemswcorders').'</font></td></tr>';
        }
        
        
      }
    }

    $rowcount++;
  }


  }
  $htmlford .= '</tbody>';
  $htmlford .= '</table>';
  $htmlford .= '</div>';
  $htmlford .= '<p><button style="
    margin-top: 12px; 
" type="button" onclick="document.post.submit();" class="button generate-items">'.__('Save Items','itemswcorders').'</button><button style="
    margin-top: 12px; margin-left:10px; color:red; 
" type="button" id="closesummerydialog" class="button generate-items">'.__('Cancel','itemswcorders').'</button></p>';

  echo $htmlford;
    //Don't forget to always exit in the ajax function.
     wp_die();
}

// resubmit renew order handler

public function renew_save_again($post_id, $post, $update){

    $slug = 'shop_order';

    $order = wc_get_order( $post_id );

    if(is_admin()){
            // If this isn't a 'woocommercer order' post, don't update it.
            if ( $slug != $post->post_type ) {
                    return;
            }
            if(isset($_POST['renew_order']) && $_POST['renew_order']){
                    $filename = sanitize_text_field($_POST['uploadcsv']);

                    // Check the type of file. We'll use this as the 'post_mime_type'.
                    $filetype = wp_check_filetype( $filename, null );

                    // Get the path to the upload directory.
                    $wp_upload_dir = wp_upload_dir();

                    // Prepare an array of post data for the attachment.
                    $attachment = array(
                      'guid'           => $wp_upload_dir['url'] . '/' . $filename, 
                      'post_mime_type' => $filetype['type'],
                      'post_title'     => preg_replace( '/\.[^.]+$/', '', $filename ),
                      'post_content'   => '',
                      'post_status'    => 'inherit'
                    );

                    // Insert the attachment.
                    $attach_id = wp_insert_attachment( $attachment, $filename, $post_id );

                    // Make sure that this file is included, as wp_generate_attachment_metadata() depends on it.
                   require_once( ABSPATH . 'wp-admin/includes/image.php' );
                    require_once( ABSPATH . 'wp-admin/includes/file.php' );
                    require_once( ABSPATH . 'wp-admin/includes/media.php' );

                    $target_dir = wp_upload_dir();
                    $target_file = $target_dir . sanitize_text_field(basename($_FILES["uploadcsv"]["name"]));
                     if (move_uploaded_file($_FILES["uploadcsv"]["tmp_name"], $target_file)) {
                        echo "The file ". esc_html__(basename( $_FILES["uploadcsv"]["name"]),'itemswcorders'). " has been uploaded.";
                    } else {
                        echo esc_html__("Sorry, there was an error uploading your file.","itemswcorders");
                    }
                   
              
                   if ( $file = fopen( $target_file , r ) ) {
                     //echo "File opened.<br />";
                    $line = array();

                    $i = 0;

                      //CSV: one line is one record and the cells/fields are seperated by ";"
                      //so $dsatz is an two dimensional array saving the records like this: $dsatz[number of record][number of cell]
                  while ( $line[$i] = fgets ($file, 4096) ) {
                    
                    $missingsku = array();
                    $addedarray = array();
                      $dsatz[$i] = array();
                      $rowArr = explode(",", $line[$i]);
                     $sku = trim($rowArr[0]);
                     $costprice = $rowArr[1];
                     $qty = $rowArr[2];
                    


                    $total = $costprice*$qty;

                    $variationsArray = [
                          'subtotal'     => $costprice, // e.g. 32.95
                          'total'        => $total, // e.g. 32.95
                      ];
                        
                      if($this->wc_get_product_id_by_variation_sku($sku)){
                         $pID = $this->wc_get_product_id_by_variation_sku($sku);
                          $varProduct = new WC_Product_Variation($pID);
                            $order->add_product($varProduct, $qty, $variationsArray);
                            $order->calculate_totals();

                      }
                    else{
                      if(wc_get_product_id_by_sku( $sku )){
                        $pID = wc_get_product_id_by_sku( $sku );
                       $varProduct = get_product($pID);
                            $order->add_product($varProduct, $qty, $variationsArray);
                            $order->calculate_totals();
                      }
                      
                    }

                       
                    
                    

                      $i++;
                  }
                  
                
                   }
                    
                }
    }
}


}

return new Importcsv_Helper_Functions;