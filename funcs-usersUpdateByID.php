<?php
require_once('funcs-afficheDataTable.php');

function usersCheckModifOK(){
  global $pdo;
  $stmt = $pdo->prepare('SELECT * FROM "users" WHERE "user"= :u ');
  $stmt -> execute(array('u'=>$_POST['user']));
  $rows = $stmt->fetchAll();
  
  if (count($rows)>=1){
      return False;
  }
  else{
      return True;
  }

}

function usersUpdateByID($id) {
  global $pdo;
  if (intval($id)>0) {
    
    
    $stmt=$pdo->prepare('UPDATE "users" SET "mail" = :m , "pass" = :p WHERE "id"= :i ;');
    $stmt->execute(array('i'=>$id,"m"=>$_POST['mail'],'p'=>sha1($_POST['pass'])));
      if($_POST['id'] == $id){
      $_SESSION['user'] = $_POST['user'];
    
      }

    cbPrintf('<h2 class="ok">Modification de l\'utilisateur #%s effectuée</h2>',$id);
  } else {
    cbPrintf('<h2 class="err">Modification de l\'utilisateur #%s impossible !!!</h2>',$id);
  }
  usersList();
}
?>
