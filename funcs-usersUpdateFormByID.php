<?php
require_once('funcs-afficheDataTable.php');
function usersUpdateFormByID($id) {
  global $pdo;
  $stmt=$pdo->prepare('SELECT * FROM "users" WHERE id= :i ');
  $stmt->execute(array('i'=>$id));
  $stmt = $stmt->fetch();
  cbPrintf('<h2>Utilisateur #%s</h2>',$id);
  cbPrintf('<form action="%s?action=manageUpdate&id='.$id.'" method="post" accept-charset="utf8">',$_SERVER['PHP_SELF']);
  cbPrintf('<table class="update">');
  $inputN=sprintf('<input type="text" name="id" value="%s" readonly="readonly"/>',$id);
  $inputU=sprintf('<input type="text" name="user" value="%s"/>',$stmt['user']);
  $inputM=sprintf('<input type="password" name="pass" value=""/>');
  $inputA=sprintf('<input type="text" name="mail" value="%s"/>',$stmt['mail']);
  afficheTRTHTD('Numéro :',$inputN);
  afficheTRTHTD('Utilisateur :',$inputU);
  afficheTRTHTD('Mot de passe :',$inputM);
  afficheTRTHTD('Adresse :',$inputA);
  afficheTRTD('<input type="submit" value="Mise à jour"/>','colspan="2"');
  cbPrintf('</table>');
  cbPrintf('</form>');

}
?>
