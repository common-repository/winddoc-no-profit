<?php

class WDNP_Helper{

  public $root_login = 'https://app.winddoc.com/oauth-login.php?token_app=a91a6268232934e63f8f33ef54e62c5243a4d69a9983e505282ce224e9d0d165';
  public $root = 'https://app.winddoc.com';

}




class WindDocNoProfitTalker
{
    private $url;
    private $soapclient;
    private $sessionId;
    private $token_app = "a91a6268232934e63f8f33ef54e62c5243a4d69a9983e505282ce224e9d0d165";

    function __construct(){

      $this->url = 'https://app.winddoc.com/v1/api_json.php';
      /*$this->soapclient = new SoapClient(null,
                                         array('location' => $this->url,
                                         'uri'            => $this->url,
                                         'soap_version'   => SOAP_1_1,
                                         'trace'          => 1,
                                         'exceptions'     => 0
                                        ));*/
    }



    public function dettaglioSocio($id="1"){
           
        $args = array();
        $args["id"] = $id;
        $ret = $this->__call("associazioni_soci_dettaglio", $args);
        return $ret;
      
    }



  public function listaSoci($pagina="1",$query="",$length=10){


      $args = array();
      $args["query"] = $query;
      $args["pagina"] = $pagina;
      $args["order"] = "";
      $args["length"] = $length;
      $ret = $this->__call("associazioni_soci_listaCerca", $args);

      return $ret;
    
  }



  public function listaLibroSoci($pagina="1",$query="",$length=10){


    $args = array();
    $args["query"] = $query;
    $args["pagina"] = $pagina;
    $args["order"] = "";
    $args["length"] = $length;
    $ret = $this->__call("associazioni_soci_listaCercaSV", $args);
    
    return $ret;
  
}

  public function listaEventi($show="",$numero=10,$order="data_desc"){
   

      $args = array();
      $args["query"] = "";
      $args["pagina"] = "1";      
      $args["order"] = "";
      if($order=="data_desc"){
        $args["order"] = "data_evento desc";
      }
      if($order=="data_asc"){
        $args["order"] = "data_evento asc";
      }
      $args["limit_list"] = $numero;
      $args["query"] = "";
      if($show=="active"){
        $args["query"] = "data_evento > DATE_SUB(NOW(), INTERVAL 5 DAY)";
      }
      if($show=="not_active"){
        $args["query"] = "data_evento < NOW()";
      }
      $ret = $this->__call("associazioni_eventi_lista", $args);
      return $ret;
    
  }







	public function __call($method, $args=array()){

    $form_params = array("method"=>$method,
              "request"=>array(
                  "token_key"=>array("token"=>get_option('WDNP_WINDDOC_TOKEN'),"token_app"=>$this->token_app),
                  )
            );
    foreach($args as $k=>$v){
      $form_params["request"][$k]=$v;
    }
    $curl = curl_init();
    curl_setopt_array($curl, [
        CURLOPT_URL => $this->url ,
        CURLOPT_POST => true,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_FAILONERROR => true,
        CURLOPT_POSTFIELDS => http_build_query($form_params),
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,    
        CURLOPT_HTTPHEADER => [
            "Accept: application/json"
        ],
    ]);

    $response = curl_exec($curl);
    $err = curl_error($curl);
    curl_close($curl);
    return json_decode($response,true);
/*
	   //return call_user_func_array(array($this->soapclient, $method), $args);
     $response = $this->soapclient->__soapCall($method, $args);

     if(is_object($response) && get_class($response)=="SoapFault"){
       return array();
     }
     return $response;
*/

  }



}
