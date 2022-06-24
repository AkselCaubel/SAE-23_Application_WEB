<?php
###############################################################################
###############################################################################
function auth($user,$pass) {
  global $pdo;
  $stmt=$pdo->prepare('SELECT * FROM "users" WHERE "user"=:u AND "pass"=:p');
  $stmt->execute(array('u'=>$user,'p'=>sha1($pass)));
  $rows=$stmt->fetchAll();
  $stmt->closeCursor();

  if (count($rows)===1) {
    $_SESSION['id'] = $rows[0]['id'];
    return true;
  }
  if ($user!='') {
    
    cbPrintf('<h2 style="color:red;">Mot de passe non valide pour [%s] !!!</h2>',$user);
  }
  return false;
}
?>
