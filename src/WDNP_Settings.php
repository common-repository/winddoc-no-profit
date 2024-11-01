<?php

class WDNP_Settings {

  public $param = array("WDNP_WINDDOC_TOKEN",
                        "WDNP_WINDDOC_NUM_LIST",

                        "WDNP_WINDDOC_SHOW_NOME",
                        "WDNP_WINDDOC_SHOW_COGNOME",
                        "WDNP_WINDDOC_SHOW_NUMEROTESSERA",


                        "WDNP_WINDDOC_CARIA",
                        "WDNP_WINDDOC_SHOW_CODICEFISCALE",
                        "WDNP_WINDDOC_SHOW_DATA_NASCITA",
                        "WDNP_WINDDOC_SHOW_TELEFONO",
                        "WDNP_WINDDOC_TIPO",


                        "WDNP_WINDDOC_EVENTI_SHOW_ONLY_ACTIVE",


                        //contatto_data_nascita
//contatto_telefono
//carica_socio_nome

                      );






  public function __construct() {
	   // Aggiungo la pagina al menu di amministrazione
     add_action( 'admin_menu', array( &$this, 'setupAdminMenus' ) );
	}

  public function setupAdminMenus() {
		add_menu_page( 'WindDoc No-Profit', 'WindDoc No-Profit', 'manage_options','winddocnoprofit_settings', array( &$this, 'settingsPage' ) , "https://app.winddoc.com/theme/default/images/logo_xs.png",56 );
    
	}

  public function settingsPage() {


    $WDNP_Helper = new WDNP_Helper();
    $html = "<h1>Impostazioni WindDoc</h1>";
    $update_settings = sanitize_text_field($_POST['update_settings']);
    if($update_settings!=""){
      foreach ($this->param as $value) {
    		  $my_var = sanitize_text_field( $_POST[$value] ); // Valido l’input
    		  update_option( $value, $my_var ); // Salvo l’opzione
      }
      $html.='<div id="message" class="updated"><p><strong>Impostazioni salvate.</strong></p></div>';
    }



  		$html.= '<form method="post" action="">';


        $html.= '

       

        <table class="form-table">
          <tbody>
            <tr>
              <th scope="row">
                <label for="winddocnoprofit_accesso_token">Collega WindDoc No-Profit</label>
              </th>
              <td>
                <button '.(get_option('WDNP_WINDDOC_TOKEN')!="" ? 'style="display:none"' : '').' id="btn_connect_wdnp" title="Collega WindDoc No-Profit" type="button" class="button button-primary" onclick="popitwdNP()" style=""><span><span><span>Collega WindDoc No-Profit</span></span></span></button>
                <span id="span_wdnp_connected" style="background:#D5EED4;display:inline-block;color:#338A2E;border-radius: 12px;border: 2px dashed;padding:8px; font-size:17px;'.(get_option('WDNP_WINDDOC_TOKEN')!="" ? '' : 'display:none').'"><b>WindDoc No-Profit Collegato</b></span>
                <button '.(get_option('WDNP_WINDDOC_TOKEN')!="" ? '' : 'style="display:none"').' id="btn_disconnect_wdnp" title="Scollega WindDoc No-Profit" type="button" class="button button-secondary" onclick="scollegaWDNP()" style=""><span><span><span>Scollega WindDoc No-Profit</span></span></span></button>
                <input type="hidden" name="WDNP_WINDDOC_TOKEN" value="'.get_option('WDNP_WINDDOC_TOKEN').'" id="winddocnoprofit_accesso_token">
                <script type="text/javascript">
                function scollegaWDNP(){
                  document.getElementById("winddocnoprofit_accesso_token").value = "";
                  document.getElementById("span_wdnp_connected").style.display = "none";
                  document.getElementById("btn_disconnect_wdnp").style.display = "none";
                  document.getElementById("btn_connect_wdnp").style.display = "inline-block";
                }
                function popitwdNP() {
                  newwindow=window.open("'.$WDNP_Helper->root_login.'","Login WindDoc","height=400,width=500");
                  if (window.focus) { newwindow.focus(); }
                  return false;
                }
                window.addEventListener("message", receiveMessage, false);
                function receiveMessage(event)
                {

                	if (event.origin == "'.$WDNP_Helper->root.'" || event.origin == "'.$WDNP_Helper->root_login.'"){
                    document.getElementById("winddocnoprofit_accesso_token").value = event.data;
                    document.getElementById("span_wdnp_connected").style.display = "inline";
                    document.getElementById("btn_disconnect_wdnp").style.display = "inline";
                    document.getElementById("btn_connect_wdnp").style.display = "none";
                  }
                }
              </script>


  					 </td>
            <tr>

          <tbody>
        </table>';

    $html.='


      <h2 class="nav-tab-wrapper" id="winddoc-tabs">
      <a class="nav-tab nav-tab-active" onclick="jQuery(\'.tabs-wd\').hide();jQuery(\'#eventi\').show();jQuery(\'#winddoc-tabs a\').removeClass(\'nav-tab-active\');jQuery(this).addClass(\'nav-tab-active\');">Eventi</a>      
      <a class="nav-tab" onclick="jQuery(\'.tabs-wd\').hide();jQuery(\'#lista_soci\').show();jQuery(\'#winddoc-tabs a\').removeClass(\'nav-tab-active\');jQuery(this).addClass(\'nav-tab-active\');">Lista Soci</a>
      
      </h2>
            
      <div id="eventi" class="tabs-wd">
        <div class="inside">
          <p class="description">
          <label for="wpcf7-shortcode">Copia questo shortcode ed incollalo nel tuo articolo, pagina o contenuto di un widget di testo:</label>
          <span class="shortcode wp-ui-highlight"><input type="text" id="wpcf7-shortcode-evento" onfocus="this.select();" readonly="readonly" class="large-text code" value=\'[winddocnoprofit-lista-eventi]\'></span>
          </p>
        </div>

        <table class="form-table">
        <tbody>


          <tr>
            <th scope="row">
              <label for="WDNP_WINDDOC_EVENTI_SHOW">Visualizza gli Eventi</label><br>
            </th>
            <td>
            <select onchange="genera_code_eventi();" name="WDNP_WINDDOC_EVENTI_SHOW" id="WDNP_WINDDOC_EVENTI_SHOW" class=" fixed-width-xl">
                  <option value="all">Tutti</option>
                 <option value="active">Attivi</option>
                 <option value="not_active">Scaduti</option>                 
             </select>
            </td>
          </tr>

          <tr>
            <th scope="row">
              <label for="">Indica quanti eventi vuoi visualizzare</label><br>
            </th>
            <td>
            <input type="text" onkeyup="genera_code_eventi();" value="10" id="WDNP_WINDDOC_EVENTI_NUMERO">
            </td>
          </tr>

          <tr>
            <th scope="row">
              <label for="WDNP_WINDDOC_EVENTI_ORDER">Ordina Eventi</label><br>
            </th>
            <td>
            <select onchange="genera_code_eventi();" id="WDNP_WINDDOC_EVENTI_ORDER" class="fixed-width-xl">
                 <option value="data_asc">Visualizza prima gli eventi più prossimi</option>
                 <option value="data_desc">Visualizza prima gli eventi più venturi</option>
             </select>
            </td>
          </tr>

        </table>
        <script>
        function genera_code_eventi(){
          var code = "[winddocnoprofit-lista-eventi";
  
          code = code + " show=\""+jQuery("#WDNP_WINDDOC_EVENTI_SHOW").val()+"\"";  
          code = code + " numero=\""+jQuery("#WDNP_WINDDOC_EVENTI_NUMERO").val()+"\"";  
          code = code + " order=\""+jQuery("#WDNP_WINDDOC_EVENTI_ORDER").val()+"\"";  
          
          code = code + "]";
          jQuery("#wpcf7-shortcode-evento").val(code);
        }

        genera_code_eventi();
        </script>

      </div>

        <div id="lista_soci" style="display:none;" class="tabs-wd">
        <h2>Lista Soci</h2>

        <div class="inside">
          <p class="description">
          <label for="wpcf7-shortcode">Copia questo shortcode ed incollalo nel tuo articolo, pagina o contenuto di un widget di testo:</label>
          <span class="shortcode wp-ui-highlight"><input type="text" id="wpcf7-shortcode" onfocus="this.select();" readonly="readonly" class="large-text code" value=\'[winddocnoprofit-lista-soci]\'></span>
          </p>
        </div>
        
        <table class="form-table">
          <tbody>


            <tr>
              <th scope="row">
                <label for="WDNP_WINDDOC_NUM_LIST">Numero soci per pagina</label><br>
              </th>
              <td>
              <select onchange="genera_code_soci();" name="WDNP_WINDDOC_NUM_LIST" id="WDNP_WINDDOC_NUM_LIST" class=" fixed-width-xl">
                   <option value="10" '.(get_option('WDNP_WINDDOC_NUM_LIST')==10 ? 'selected' : '').'>10</option>
                   <option value="25" '.(get_option('WDNP_WINDDOC_NUM_LIST')==25 ? 'selected' : '').'>25</option>
                   <option value="50" '.(get_option('WDNP_WINDDOC_NUM_LIST')==50 ? 'selected' : '').'>50</option>
                   <option value="100" '.(get_option('WDNP_WINDDOC_NUM_LIST')==100 ? 'selected' : '').'>100</option>

               </select>
              </td>
            </tr>

            <tr>
              <th scope="row">
                <label for="WDNP_WINDDOC_NUM_LIST">Tipologia Soci</label><br>
              </th>
              <td>
              <select onchange="genera_code_soci();" name="WDNP_WINDDOC_TIPO" id="WDNP_WINDDOC_TIPO" class=" fixed-width-xl">
                   <option value="0" '.(get_option('WDNP_WINDDOC_TIPO')==0 ? 'selected' : '').'>Tutti i Soci</option>
                   <option value="1" '.(get_option('WDNP_WINDDOC_TIPO')==1 ? 'selected' : '').'>Tutti i Soci iscritti al Libro Soci</option>
               </select>
              </td>
            </tr>

            <tr>
              <th scope="row">
                <label for="WDNP_WINDDOC_SHOW_NOME">Visualizza Nome del socio</label><br>
              </th>
              <td>
              <select onchange="genera_code_soci();"  name="WDNP_WINDDOC_SHOW_NOME" id="WDNP_WINDDOC_SHOW_NOME" class=" fixed-width-xl">
                   <option value="0">No</option>
                   <option value="1" '.(get_option('WDNP_WINDDOC_SHOW_NOME')==1 ? 'selected' : '').'>Si</option>
               </select>
              </td>
            </tr>

            <tr>
              <th scope="row">
                <label for="WDNP_WINDDOC_SHOW_COGNOME">Visualizza Cognome del socio</label><br>
              </th>
              <td>
              <select onchange="genera_code_soci();" name="WDNP_WINDDOC_SHOW_COGNOME" id="WDNP_WINDDOC_SHOW_COGNOME" class=" fixed-width-xl">
                   <option value="0">No</option>
                   <option value="1" '.(get_option('WDNP_WINDDOC_SHOW_COGNOME')==1 ? 'selected' : '').'>Si</option>
               </select>
              </td>
            </tr>

            <tr>
              <th scope="row">
                <label for="WDNP_WINDDOC_SHOW_CODICEFISCALE">Visualizza Codice Fiscale del socio</label><br>
              </th>
              <td>
              <select onchange="genera_code_soci();" name="WDNP_WINDDOC_SHOW_CODICEFISCALE" id="WDNP_WINDDOC_SHOW_CODICEFISCALE" class=" fixed-width-xl">
                   <option value="0">No</option>
                   <option value="1" '.(get_option('WDNP_WINDDOC_SHOW_CODICEFISCALE')==1 ? 'selected' : '').'>Si</option>
               </select>
              </td>
            </tr>

            <tr>
              <th scope="row">
                <label for="WDNP_WINDDOC_SHOW_NUMEROTESSERA">Visualizza Numero tessera del socio</label><br>
              </th>
              <td>
              <select onchange="genera_code_soci();" name="WDNP_WINDDOC_SHOW_NUMEROTESSERA" id="WDNP_WINDDOC_SHOW_NUMEROTESSERA" class=" fixed-width-xl">
                   <option value="0">No</option>
                   <option value="1" '.(get_option('WDNP_WINDDOC_SHOW_NUMEROTESSERA')==1 ? 'selected' : '').'>Si</option>
               </select>
              </td>
            </tr>

            <tr>
              <th scope="row">
                <label for="WDNP_WINDDOC_CARIA">Visualizza Carica del socio</label><br>
              </th>
              <td>
              <select onchange="genera_code_soci();" name="WDNP_WINDDOC_CARIA" id="WDNP_WINDDOC_CARIA" class=" fixed-width-xl">
                   <option value="0">No</option>
                   <option value="1" '.(get_option('WDNP_WINDDOC_CARIA')==1 ? 'selected' : '').'>Si</option>
               </select>
              </td>
            </tr>



            <tr>
              <th scope="row">
                <label for="WDNP_WINDDOC_SHOW_DATA_NASCITA">Visualizza Data Nascita del socio</label><br>
              </th>
              <td>
              <select onchange="genera_code_soci();" name="WDNP_WINDDOC_SHOW_DATA_NASCITA" id="WDNP_WINDDOC_SHOW_DATA_NASCITA" class=" fixed-width-xl">
                   <option value="0">No</option>
                   <option value="1" '.(get_option('WDNP_WINDDOC_SHOW_DATA_NASCITA')==1 ? 'selected' : '').'>Si</option>
               </select>
              </td>
            </tr>
            <tr>
              <th scope="row">
                <label for="WDNP_WINDDOC_SHOW_TELEFONO">Visualizza Telefono del socio</label><br>
              </th>
              <td>
              <select onchange="genera_code_soci();" name="WDNP_WINDDOC_SHOW_TELEFONO" id="WDNP_WINDDOC_SHOW_TELEFONO" class=" fixed-width-xl">
                   <option value="0">No</option>
                   <option value="1" '.(get_option('WDNP_WINDDOC_SHOW_TELEFONO')==1 ? 'selected' : '').'>Si</option>
               </select>
              </td>
            </tr>



            <script>
            function genera_code_soci(){
              var code = "[winddocnoprofit-lista-soci";
      
              code = code + " numero_pagine=\""+jQuery("#WDNP_WINDDOC_NUM_LIST").val()+"\"";  
              code = code + " tipo=\""+jQuery("#WDNP_WINDDOC_TIPO").val()+"\"";  

              code = code + " socio_nome=\""+jQuery("#WDNP_WINDDOC_SHOW_NOME").val()+"\"";  
              
              code = code + " socio_cognome=\""+jQuery("#WDNP_WINDDOC_SHOW_COGNOME").val()+"\"";  
              code = code + " socio_codice_fiscale=\""+jQuery("#WDNP_WINDDOC_SHOW_CODICEFISCALE").val()+"\"";  
              code = code + " socio_numero_tessera=\""+jQuery("#WDNP_WINDDOC_SHOW_NUMEROTESSERA").val()+"\"";  
              code = code + " socio_carica=\""+jQuery("#WDNP_WINDDOC_CARIA").val()+"\"";  
              code = code + " socio_data_nascita=\""+jQuery("#WDNP_WINDDOC_SHOW_DATA_NASCITA").val()+"\"";  
              code = code + " socio_telefono=\""+jQuery("#WDNP_WINDDOC_SHOW_TELEFONO").val()+"\"";  
              
              code = code + "]";
              jQuery("#wpcf7-shortcode").val(code);
            }
    
            genera_code_soci();
            </script>




        </table>
      </div>
      

  		<input type="submit" value="Save" class="button button-primary" />
  		<input type="hidden" name="update_settings" value="1" /></p>
  		</form>
      ';

      echo $html;

  }
}
