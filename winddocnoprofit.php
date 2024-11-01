<?php

/**
 * Plugin Name:       WindDoc No-Profit
 * Plugin URI:        https://www.winddocnoprofit.com/elenco-soci-associazione/
 * Description:       Visualizza la lista dei soci attivi della tua Associazione
 * Version:           2.2
 * Requires at least: 6.1.1
 * Requires PHP:      5.2.4
 * Author:            GMV Software
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       winddocnoprofit
 */

global $winddocnoprofit_db_version;
$winddocnoprofit_db_version = '2.0';
function winddocnoprofit_install() {

}
register_activation_hook( __FILE__, 'winddocnoprofit_install' );

function winddocnoprofit_update_db_check() {

    global $winddocnoprofit_db_version;

    if ( get_site_option( 'winddocnoprofit_db_version' ) != $winddocnoprofit_db_version ) {


    }
}

function winddocnoprofit_page(){
  add_shortcode( 'winddocnoprofit-lista-soci', 'winddocnoprofit_lista_soci' );
  add_shortcode( 'winddocnoprofit-lista-eventi', 'winddocnoprofit_lista_eventi' );
}



function winddocnoprofit_ajax_getpostsfordatatables() {

  header("Content-Type: application/json");
  $atts = $_POST;
  $columns = array();

  if(isset($atts["socio_nome"]) && $atts["socio_nome"]=="1"){
    $columns[] = 'Nome';
  }
  if(isset($atts["socio_cognome"]) && $atts["socio_cognome"]=="1"){
    $columns[] = 'Cognome';
  }
  if(isset($atts["socio_codice_fiscale"]) && $atts["socio_codice_fiscale"]=="1"){
    $columns[] = 'Codice_Fiscale';
  }
  if(isset($atts["socio_numero_tessera"]) && $atts["socio_numero_tessera"]=="1"){
    $columns[] = 'Numero_Tessera';
  }

  $GET_length = sanitize_text_field($_GET['length']);
  $GET_start = sanitize_text_field($_GET['start']);
  $GET_order_dir = sanitize_text_field($_GET['order'][0]['dir']);
  $GET_order_column = sanitize_text_field($_GET['order'][0]['column']);
  $GET_draw =  sanitize_text_field($_GET['draw']);

  $args = array(
      'post_type' => 'movie',
      'post_status' => 'publish',
      'posts_per_page' => $GET_length,
      'offset' => $GET_start,
      'order' => $GET_order_dir,
    );

  if ($GET_order_column == 0) {
    $args['orderby'] = $columns[$GET_order_column];
  } elseif ($GET_order_column == 1 || $GET_order_column == 2) {
    $args['orderby'] = 'meta_value_num';
    $args['meta_key'] = $columns[$GET_order_column];
  }

  $WindDocNoProfitTalker = new WindDocNoProfitTalker();

  $finish = true;
  if(get_option("WD_WINDDOC_TOKEN")==""){
    $finish = false;
  }
  $soci = array();
  $pagina = 1;
  $start =  sanitize_text_field($_POST["start"]);
  $length =  sanitize_text_field($_POST["length"]);
  $search_val =  sanitize_text_field($_POST["search"]["value"]);


  if(isset($start) && $start>0){
    $pagina = ($start/$length)+1;
  }

  $q = "";
  if($search_val!=""){
    $q = $search_val;
  }

  $socio = array();
  if(isset($atts["tipo"]) && $atts["tipo"]=="1"){
    $ret = $WindDocNoProfitTalker->listaLibroSoci($pagina,$q,$length);
  }else{
    $ret = $WindDocNoProfitTalker->listaSoci($pagina,$q,$length);
  }

  $pagina++;


  $data = array();

  foreach ($ret["lista"] as $key => $value){
    $i = 0;


    if(isset($atts["socio_nome"]) && $atts["socio_nome"]=="1"){
      $data[$key][$i] = $value["contatto_nome"];
      $i++;
    }
    if(isset($atts["socio_cognome"]) && $atts["socio_cognome"]=="1"){
      $data[$key][$i] = $value["contatto_cognome"];
      $i++;
    }
    if(isset($atts["socio_carica"]) && $atts["socio_carica"]=="1"){
      $data[$key][$i] = $value["carica_socio_nome"];
      $i++;
    }

    if(isset($atts["socio_codice_fiscale"]) && $atts["socio_codice_fiscale"]=="1"){
      $data[$key][$i] = $value["contatto_codice_fiscale"];
      $i++;
    }
    if(isset($atts["socio_numero_tessera"]) && $atts["socio_numero_tessera"]=="1"){
      $data[$key][$i] = $value["numero_tessera"];
      $i++;
    }

    if(isset($atts["socio_telefono"]) && $atts["socio_telefono"]=="1"){
      $data[$key][$i] = $value["contatto_telefono"];
      $i++;
    }

    if(isset($atts["socio_data_nascita"]) && $atts["socio_data_nascita"]=="1"){
      $data[$key][$i] = $value["contatto_data_nascita"];
      $i++;
    }
  }
  
  $max = $ret["numero_pagine"]*$length;

  if(count($ret["lista"])<$length){
    $max = count($ret["lista"]);
  }
  $json_data = array(
     "draw" => intval($GET_draw),
     "recordsTotal" => $max,
     "recordsFiltered" => $max,
     "data" => $data
   );

   echo json_encode($json_data);
   wp_die();

}
function winddocnoprofit_lista_soci( $atts, $content = null, $code = '' ) {


	if ( 'winddocnoprofit-lista-soci' == $code ) {
		

		$numero_soci_per_pagina = get_option("WDNP_WINDDOC_NUM_LIST");
    if($numero_soci_per_pagina==""){
      $numero_soci_per_pagina = 10;
    }

    wp_enqueue_script( 'jquery' );
    wp_enqueue_script( 'dataTables_js', plugins_url( '/js/jquery.dataTables.js', __FILE__ ));
    wp_enqueue_style( 'dataTables_css', plugins_url( '/js/jquery.dataTables.min.css', __FILE__ ));
    $html = '

    <div style="max-width:1200px;">
    <table id="table_id" class="display">
      <thead>
        <tr>';
        if(isset($atts["socio_nome"]) && $atts["socio_nome"]=="1"){
          $html.='<th>Nome</th>';
        }
        if(isset($atts["socio_cognome"]) && $atts["socio_cognome"]=="1"){
          $html.='<th>Cognome</th>';
        }
        if(isset($atts["socio_carica"]) && $atts["socio_carica"]=="1"){
          $html.='<th>Carica</th>';
        }
        if(isset($atts["socio_codice_fiscale"]) && $atts["socio_codice_fiscale"]=="1"){
          $html.='<th>Codice Fiscale</th>';
        }
        if(isset($atts["socio_numero_tessera"]) && $atts["socio_numero_tessera"]=="1"){
          $html.='<th>Numero Tessera</th>';
        }

        if(isset($atts["socio_telefono"]) && $atts["socio_telefono"]=="1"){
          $html.='<th>Telefono</th>';
        }

        if(isset($atts["socio_data_nascita"]) && $atts["socio_data_nascita"]=="1"){
          $html.='<th>Data Nascita</th>';
        }





        $html.='</tr>
      </thead>

    </table>
    </div>
    </div>

    <script type="text/javascript">
    var datatablesajax = "'.admin_url('admin-ajax.php').'";
    jQuery( document ).ready(function() {
    jQuery(\'#table_id\').DataTable({
    				 "bProcessing": true,
             "serverSide": true,
             "ordering": false,
             language : {
                      "decimal":        "",
                      "emptyTable":     "Nessun socio trovato",
                      "info":           "Visualizza _START_ di _END_ di _TOTAL_ soci",
                      "infoEmpty":      "Visualizza 0 di 0 di 0 soci",
                      "infoFiltered":   "(filtered from _MAX_ total entries)",
                      "infoPostFix":    "",
                      "thousands":      ",",
                      "lengthMenu":     "Visualizza _MENU_ Soci",
                      "loadingRecords": "Caricamento...",
                      "processing":     "Caricamento...",
                      "search":         "Cerca:",
                      "zeroRecords":    "Nessun Socio trovato",
                      "paginate": {
                          "first":      "Primo",
                          "last":       "Ultimo",
                          "next":       "Prossimo",
                          "previous":   "Precedente"
                      }
                  },
             pageLength : '.$numero_soci_per_pagina.',
             "ajax":{
                url: datatablesajax + \'?action=getpostsfordatatables\',
                type: "post",  // type of method  , by default would be get
                data:'.json_encode($atts).',
                error: function(){  // error handling code
                  jQuery("#employee_grid_processing").css("display","none");
                }
              }
            });
    });
    </script>
    ';



    return $html;


	}
  return "";
}





function winddocnoprofit_lista_eventi( $atts, $content = null, $code = '' ) {

  
  $html = "";
  if ( 'winddocnoprofit-lista-eventi' == $code ) {
    //Lista Eventi
    $WindDocNoProfitTalker = new WindDocNoProfitTalker();

    $show = "all";
    $numero = 10;
    $order = "data_desc";
    if(isset($atts["show"]) && $atts["show"]!=""){
      $show = $atts["show"];
    }
    if(isset($atts["numero"]) && $atts["numero"]!=""){
      $numero = $atts["numero"];
    }
    if(isset($atts["order"]) && $atts["order"]!=""){
      $order = $atts["order"];
    }
    
    $lista_eventi = $WindDocNoProfitTalker->listaEventi($show,$numero,$order);
    
    ob_start();
    include_once( plugin_dir_path( __FILE__ ) . 'template/lista_eventi.php' );
    $html = ob_get_clean();
	}
  return $html;
}


add_action( 'plugins_loaded', 'winddocnoprofit_update_db_check' );
add_action( 'plugins_loaded', 'winddocnoprofit_page', 10, 0 );
require_once 'src/WDNP_Settings.php';
require_once 'src/WDNP_Helper.php';



add_action( 'wp_ajax_getpostsfordatatables', 'winddocnoprofit_ajax_getpostsfordatatables' );
add_action( 'wp_ajax_nopriv_getpostsfordatatables', 'winddocnoprofit_ajax_getpostsfordatatables' );

$winddocnoprofit_settings = new WDNP_Settings();

if (is_admin()) {

}
