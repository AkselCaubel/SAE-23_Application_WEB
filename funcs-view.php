<?php 


########################################################## 
# Affichage par défaut de notre site
##########################################################

function menu(){
  
}

###################################################################################
# Affichage de la DB sous forme de schéma de l'entreprise ( JS ) NON OPERATIONNEL
################################################################################## 

function schemaDB(){
  global $pdo;

  $where = $_GET['where'];

  $sql = 'SELECT * FROM "journal" WHERE "state"=:s AND "where"=:we';
  $stmt = $pdo -> query($sql);
  $stmt -> execute(array("s"=>"taken","we"=>$where));
  echo ($stmt)?$stmt:'{"error":"NOT FOUND !"}';
  exit(0);
}

##########################################################
# Affichage de la DB sous forme de schéma de l'entreprise
##########################################################

function schemaPhp(){
  global $pdo;

  $sql = 'SELECT DISTINCT "where" FROM "journal"';
  $stmt = $pdo -> prepare($sql);
  $stmt -> execute();
  $rows = $stmt->fetchAll();
  $box = '🗃';

  for ($i=0;$i<count($rows);$i++){

    $sql2 = 'SELECT count("id") FROM "journal" WHERE "where"=:we;';
    $stmt2 = $pdo -> prepare($sql2);
    $stmt2 -> execute(array('we'=>$rows[$i]['where']));
    $len = intval($stmt2->fetchAll());
    //cbPrintf("len = ".intval($len));
    //var_dump($rows);
    $where=$rows[$i]['where'];
    cbPrintf('

      <div id='.$where.' style="color:white;text-align:center;height:50px;width:50px;">
      <span>'.str_repeat($box,$len).'</span>
      <p style = "margin:auto">'.$where.'</p>
      </div>
    ');

  }
}

###########################################################
# Affichage de la DB sous forme d'un tableau minimaliste
########################################################### 

function grid (){

    global $pdo;

    $stmt=$pdo->prepare('SELECT * FROM "journal" ORDER BY "date_rending","where","by_who" ASC ');
  if ($stmt->execute()) {
    printf("<table>\n");
    $entete=false;
    while ($row=$stmt->fetch()) {
      if(!$entete) {// Affiche l'entete 1 seule fois
        $entete=true;
        printf("<tr>");
        afficheTH('id');
        afficheTH('type');
        afficheTH('reference');
        afficheTH('état');
        afficheTH('Ou se trouve t-il');
        afficheTH('Pris par');
        afficheTH('Date de prise');
        afficheTH('Date de rendu');
        printf("</tr>\n");
      }
      printf("<tr>");
      $id=cbGetValue($row,'id');
      $hardware=cbGetValue($row,'hardware');
      $reference=cbGetValue($row,'reference');
      $state=cbGetValue($row,'state');
      $where=cbGetValue($row,'where');
      $byWho=cbGetValue($row,'by_who');
      $dateTake=cbGetValue($row,'date_take');
      $dateRending=cbGetValue($row,'date_rendering');

      afficheTD($id);
      afficheTD($hardware);
      afficheTD($reference);
      afficheTD($state);
      afficheTD($where);
      afficheTD($byWho);
      afficheTD($dateTake);
      afficheTD($dateRending);
      
      printf("</tr>\n");
    }
    printf("</table>\n");
    $stmt->closeCursor();
  }
}

###########################################################
# Affichage du pannel utilisateur personnel
########################################################### 

function selfUserView($id){

cbPrintf("<h2 class='subTitle'>Bonjour ".$_SESSION['user']."</h2>");
cbPrintf("<a href='%s?action=manage&id=%d'>Modifier mon profil</a></br>",$_SERVER['PHP_SELF'],$id);
cbPrintf('<a href="%s?logout=true">Se déconnecter</a><br/>',$_SERVER['PHP_SELF']);
}

###################################################################
# Affichage des informations Utilisateur global & statistique Site
###################################################################

function controlPannel(){

	cbPrintf("<h3 class='subTitle'><u><b>Centre de Controle</b></u></h3></br>");

 cbPrintf("<a href='%s?action=adminManage'>Gestion des utilisateurs</a></br>",$_SERVER['PHP_SELF']);
 cbPrintf("<a href='%s?action=adminGestionStock'>Gestion des stocks</a></br>",$_SERVER['PHP_SELF']);

}

?>


