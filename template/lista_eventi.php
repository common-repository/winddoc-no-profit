
<?php
foreach($lista_eventi["lista"] as $evento){




					
                    
					echo '<article id="'.md5($evento["id_evento"]).'" class="lista_evento">';

                    $iscrizione = true;
                    if($evento["iscrizione_evento"]==1){
                        $iscrizione = false;
                    }
                    if($evento["iscrizione_evento"]==2){
                        if($evento["data_fine_evento"]!=""){
                            if(strtotime($evento["data_fine_evento"])<time()){
                                $iscrizione = false;
                            }
                        }else{
                            if(strtotime($evento["data_evento"])<time()){
                                $iscrizione = false;
                            }
                        }
                    }
                    

                    if(isset($evento["logo"]["link"])){
					    //echo '<div class="featured-image"><a href="'.get_category_link($cat->term_id).'">'.get_the_post_thumbnail($post->ID,"thumb-category").'</a></div>';
                        echo '<div class="">';
                        if($iscrizione){
                            echo '<a target="_blank" href="'.$evento["link_form"].'">';
                        }
                        echo '<img width="1200" height="630" class="attachment-thumb-category size-thumb-category wp-post-image" src="'.$evento["logo"]["link"].'">';
                        if($iscrizione){
                            echo '</a>';
                        }
                        echo '</div>';
                    }
					echo '<div class="featured-art">';
					echo '<div class="autor">';

                    
					

					if($evento["data_fine_evento"]!=""){
					    echo 'Dal '.date('d/m/Y', strtotime($evento["data_evento"]))." al ".date('d/m/Y', strtotime($evento["data_fine_evento"]));					    
                    }else{
                        echo 'Data '.date('d/m/Y', strtotime($evento["data_evento"]));
                    }
                    echo '</div>';
					echo '<h3 class="titolo_articolo">'. $evento["nome"].'</h3>';
					$desc = strip_tags($post->post_content);
					$pos=strpos($desc, ' ', 400);
					echo '<p class="testo_articolo">'.$evento["descrizione"].' </p>';
                    
                    if($iscrizione){
                        echo '<a target="_blank" href="'.$evento["link_form"].'">Iscriviti</a>';
                    }else{
                        echo '<i class="close_event">Iscrizioni Chiuse</i>';
                    }
					//echo '<span class="leggi_resto">'.'<a href="' . get_permalink($post->ID) . '" title="Dettaglio '.esc_attr($post->post_title).'" ><i class="fa fa-arrow-circle-right" aria-hidden="true"></i> Leggi il resto</a>'.'</span>';
					echo '</div>';
					echo '</article>';

}
?>
<style>
.lista_evento {
    border: 1px solid #eee;
    margin-bottom: 30px;
    text-align: left;
}
.lista_evento .featured-art {
    margin: 20px;
}
.lista_evento .autor {
    font-size: 13px;
    margin-bottom: 5px;
}
</style>