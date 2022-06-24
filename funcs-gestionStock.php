<?php

function adminGestionStock(){
   
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
      cbPrintf('<th colspan="3">action</th>');
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
    afficheTD("<a href='index.php?action=adminGestionStockManage&id=".$id."'>Modifier</a>");
    afficheTD("<a href=".$_SERVER['PHP_SELF']."?action=updateRelease&hardId=".$id.">Reset</a>");
    afficheTD("<a href='index.php?action=adminGestionStockDel&id=".$id."'>Supprimer</a>");

    
    printf("</tr>\n");
  }
  printf("</table>\n");
  $stmt->closeCursor();
}
}

function adminGestionStockManage(){ // Form modif

  global $pdo;

  $id = $_GET['id']; 
  $stmt=$pdo->prepare('SELECT * FROM "journal" WHERE id= :i ');
  $stmt->execute(array('i'=>$id));
  $stmt = $stmt->fetch();
  cbPrintf('<h2>équipement #%s</h2>',$id);
  cbPrintf('<form action="%s?action=adminGestionStockManageUpdate&id='.$id.'" method="post" accept-charset="utf8">',$_SERVER['PHP_SELF']);
  cbPrintf('<table class="update">');
  $inputI=sprintf('<input type="text" name="id" value="%s" readonly="readonly"/>',$id);
  $inputH=sprintf('<input type="text" name="hardware" value="%s"/>',$stmt['hardware']);
  $inputR=sprintf('<input type="text" name="reference" value="%s"/>',$stmt['reference']);
  $inputS=sprintf('<input type="text" name="state" value="%s"/>',$stmt['state']);
  $inputWo=sprintf('<input type="text" name="by_who" value="%s"/>',$stmt['by_who']);
  $inputWe=sprintf('
  <select name="where" >
  <option value ="'.$stmt['where'].'">'.$stmt['where'].'</option>
  <option value="ST01">ST01</option>
  <option value="SI01">SI01</option>
  <option value="SI02">SI02</option>
  <option value="AD01">AD01</option>
  <option value="AD02">AD02</option>
  <option value="CO01">CO01</option>
</select>'
);
  $inputDr=sprintf('<input type="text" name="date_rendering" value="%s"/>',$stmt['date_rendering']);

  afficheTRTHTD('id :',$inputI);
  afficheTRTHTD('Type :',$inputH);
  afficheTRTHTD('Référence :',$inputR);
  afficheTRTHTD('Etat :',$inputS);
  afficheTRTHTD('Prit par qui :',$inputWo);
  afficheTRTHTD('Où se trouve t\'il :',$inputWe);
  afficheTRTHTD('Date de rendu :',$inputDr);
  afficheTRTD('<input type="submit" value="Mise à jour"/>','colspan="2"');
  cbPrintf('</table>');
  cbPrintf('</form>');
}

function adminGestionStockManageUpdate(){ // Met a jour dans la base de donnée si la référence 
  global $pdo;

  $sql = 'UPDATE "journal" SET "hardware" = :h , "reference" = :r , "state" = :s ,"by_who"=:wo ,"where"=:we,"date_rendering"=:dr WHERE "id"= :i ;';

  $stmt = $pdo -> prepare($sql);
  //printf("i"=>$_POST['id'],"u"=>$_POST['hardware'],"r"=>$_POST['reference']."s".$_POST['state']."wo".$_POST['by_who']."we".$_POST['where']."dr".$_POST['date_rendering']));
  $stmt -> execute(array("i"=>$_POST['id'],"h"=>$_POST['hardware'],"r"=>$_POST['reference'],"s"=>$_POST['state'],"wo"=>$_POST['by_who'],"we"=>$_POST['where'],"dr"=>$_POST['date_rendering']));

}

function adminGestionStockAdd(){



  cbPrintf('<form action="%s?action=adminGestionStockAddBase" method="post" accept-charset="utf8">',$_SERVER['PHP_SELF']);
    cbPrintf('<table class="update">');

    $hardware=sprintf('<input type="text" name="hardware" value=""/>');
    $reference=sprintf('<input type="text" name="reference" value=""/>');

    afficheTRTHTD('Type :',$hardware);
    afficheTRTHTD('Réference :',$reference);
    afficheTRTD('<input type="submit" value="Ajouter"/>','colspan="2"');
    cbPrintf('</table>');
    cbPrintf('</form>');

}

function refExist($ref){ // Renvois True si la référnce existe | False si n'existe pas
  global $pdo;

  $sql = 'SELECT * FROM "journal" WHERE "reference"=:r';
  $stmt = $pdo -> prepare($sql);
  $stmt -> execute(array("r"=>$_POST['reference']));

  if(count($stmt -> fetch())==1){
    return True;
  }
  else{
    return False;
  }
}

function adminGestionStockAddBase(){
  global $pdo;

  $sql = 'INSERT INTO "journal" ("hardware","reference","state","where") VALUES (:h,:r,\'free\',\'ST01\')';
  $stmt = $pdo -> prepare($sql);
  $stmt -> execute(array("h"=>$_POST['hardware'],"r"=>$_POST['reference']));
}

function adminGestionStockDel(){
  global $pdo;

    $sql = 'DELETE FROM "journal" WHERE "id"=:i';
    $stmt = $pdo -> prepare($sql);
    $stmt -> execute(array('i'=>$_GET['id']));
}


function keep(){

    global $pdo;

    $sql = 'SELECT * FROM "journal" WHERE "state"=:s ';
    $stmt = $pdo -> query($sql);
    $stmt -> execute(array("s"=>"free"));

    //$rows = $stmp->fetchAll();

    printf("<table>\n");
    $entete=false;
    while ($row=$stmt->fetch()) {
      if(!$entete) {// Affiche l'entete 1 seule fois
        $entete=true;
        printf("<tr>");

        afficheTH('type');
        afficheTH('reference');
        afficheTH('état');
        afficheTH('Ou se trouve t-il');
        afficheTH('Action');
        printf("</tr>\n");
      }
      printf("<tr>");
      $id=cbGetValue($row,'id');
      $hardware=cbGetValue($row,'hardware');
      $reference=cbGetValue($row,'reference');
      $state=cbGetValue($row,'state');
      $where=cbGetValue($row,'where');
      $keep='<a href='.$_SERVER['PHP_SELF'].'?action=infoKeep&hardId='.$id.'>Prendre</a>';


      afficheTD($hardware);
      afficheTD($reference);
      afficheTD($state);
      afficheTD($where);
      afficheTD($keep);

      
      printf("</tr>\n");
    }
    printf("</table>\n");


    printf('Aucun autre matériel a prendre');

    $stmt->closeCursor();
}

function infoKeep($salle=""){
  include("script-date.html");

  cbPrintf('<form action="%s?action=updateKeep&hardId='.cbGetValue($_REQUEST,'hardId').'" method="post" accept-charset="utf8">',$_SERVER['PHP_SELF']);
  cbPrintf('<table class="update">');
  $inputW=sprintf('<div id="dateTable"><input name="date_rendering" type="text" id="datepicker"></div>');
  $selectS=sprintf('
  <select name="room" >
  <option value="ST01">ST01</option>
  <option value="SI01">SI01</option>
  <option value="SI02">SI02</option>
  <option value="AD01">AD01</option>
  <option value="AD02">AD02</option>
  <option value="CO01">CO01</option>
</select>
');

  afficheTRTHTD('Date de rendu :',$inputW);
  afficheTRTHTD('Pour salle :',$selectS);

  afficheTRTD('<input type="submit" value="Confirmer"/>','colspan="2"');
  cbPrintf('</table>');
  cbPrintf('</form>');
}

function updateKeep($hardId){

    global $pdo;
    

    $dateTake = date('d/m/y');
    $rawDr = $_POST['date_rendering']; // Date de rendu format US
    
    $dateRendering = substr($rawDr,3,2).'/'.substr($rawDr,0,2).'/'.substr($rawDr,6,4); // Date de rendu format FR
    
    $sql = 'UPDATE "journal" SET "by_who" = :u , "state"= :s , "date_take"=:dt , "date_rendering" = :dr ,"where"=:we WHERE "id"= :i ;';
    $stmt = $pdo -> prepare($sql);
    $stmt -> execute(array('u'=>$_SESSION['user'],'s'=>'taken','dt'=>$dateTake,"dr"=>$dateRendering,'i'=>$hardId,"we"=>$_POST['room']));
    $stmt->closeCursor();

}


function release(){

    global $pdo;

    $sql = 'SELECT * FROM "journal" WHERE "by_who"=:u ';
    $stmt = $pdo -> query($sql);
    $stmt -> execute(array("u"=>$_SESSION['user']));

    //$rows = $stmp->fetchAll();

    printf("<table>\n");
    $entete=false;
    while ($row=$stmt->fetch()) {
      if(!$entete) {// Affiche l'entete 1 seule fois
        $entete=true;
        printf("<tr>");

        afficheTH('type');
        afficheTH('reference');
        afficheTH('état');
        afficheTH('Ou se trouve t-il');
        afficheTH('Action');
        printf("</tr>\n");
      }
      printf("<tr>");
      $id=cbGetValue($row,'id');
      $hardware=cbGetValue($row,'hardware');
      $reference=cbGetValue($row,'reference');
      $state=cbGetValue($row,'state');
      $where=cbGetValue($row,'where');
      $keep='<a href='.$_SERVER['PHP_SELF'].'?action=updateRelease&hardId='.$id.'>Rendre</a>';


      afficheTD($hardware);
      afficheTD($reference);
      afficheTD($state);
      afficheTD($where);
      afficheTD($keep);

      
      printf("</tr>\n");
    }
    printf("</table>\n");

    $rows = $stmt->fetchAll();
    
    cbPrintf('Aucun autre matériel a rendre');
    
    

}

function updateRelease($hardId){
    global $pdo;
    
    $sql = 'UPDATE "journal" SET "by_who" = :u , "state"= :s , "date_take"=:dt ,"date_rendering"=:dr , "where" = :w WHERE "id"= :i ;';
    
    $stmt = $pdo -> query($sql);
    $stmt -> execute(array('u'=>null,'s'=>'free','dt'=>null,'dr'=>null,'w'=>'ST01','i'=>$hardId));
    $stmt->closeCursor();
}

?>