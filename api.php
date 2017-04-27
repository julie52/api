<?php
  ini_set('display_errors', 1);
  header('Content-Type: application/json');
  header('Access-Control-Allow-Origin: *');
  header('Access-Control-Allow-Headers: Origin, Content-Type, Authorization, X-Auth-Token');
  header('Access-Control-Allow-Methods: GET, POST, PUT, PATCH, DELETE, HEAD, OPTIONS');
  $serveur = "localhost";
  $user = "blondeau";
  $password ="ugZV5rd7tvq05fvT";
  $name_bdd ="blondeau";
  $method = $_SERVER['REQUEST_METHOD'];
  $input = file_get_contents('php://input');
  $input = json_decode($input);

    try{
      $connexion= new PDO('mysql:host='.$serveur.';dbname='.$name_bdd.';charset=utf8', $user, $password);
      $connexion-> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      }
    catch(Exception $e){
    die('Erreur :'.$e->getmessage());
    }

    if ($method == 'GET') {
      $req= $connexion->prepare("SELECT * FROM projets ");
      $req->execute();
      $resultat = $req->fetchAll(PDO::FETCH_ASSOC);
      echo (json_encode($resultat));
      while($row = $req->fetch()){
      echo $row["nom_projet"]."<br>";
  }
} else if ($method == 'POST') {

      $nom_du_projet = $input->infos->nom_du_projet;
      $techno_utilises = $input->infos->techno_utilises;
      $description = $input->infos->description;

      $req = $connexion->prepare('INSERT INTO projets (nom_du_projet, techno_utilises, description) VALUES(?,?,?)');
      $req->execute(array($nom_du_projet,$techno_utilises,$description));
      }
  else if($method == 'DELETE') {
    $nom_projet = $input->infos->nom_du_projet;
    $req= $connexion->query("DELETE FROM projets WHERE nom_du_projet='$nom_du_projet'");
  }
  if($method == 'GET'){

    if(isset($_GET['contact'])){

        if( !empty ($_GET['mail']) && !empty($_GET['sujet']) && !empty($_GET['message'])){

            $expediteur= $_GET['mail'];
            $message= $_GET['message'];
            $sujet= $_GET['sujet'];
            $mon_mail ="julie.blondeau.fr@gmail.com";

            $headers  = 'MIME-Version: 1.0'."\r\n";
            $headers .= 'From:'.$expediteur."\r\n";
            $headers .= 'Content-type: text/html;charset=UTF-8'."\r\n";
            $headers.='Content-Transfer-Encoding: 8bit';


            echo (json_encode($expediteur));
            $envoi = mail($mon_mail,$sujet,$message,$headers);
            if($envoi){
                    echo (json_encode('Votre message a bien été envoyé.'));
            }   else{
                    echo (json_encode('votre message n\'a pas pu être envoyé.'));
                }
        }

  
?>