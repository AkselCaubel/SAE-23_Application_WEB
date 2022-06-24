<?php
require_once('funcs-afficheDataTable.php');
function usersList() {
  global $pdo;
  $stmt=$pdo->prepare('SELECT * FROM "users" ORDER BY "user"');
  if ($stmt->execute()) {
    printf("<table>\n");
    $entete=false;
    while ($row=$stmt->fetch()) {
      if(!$entete) {// Affiche l'entete 1 seule fois
        $entete=true;
        printf("<tr>");
        afficheTH('id');
        afficheTH('user');
        afficheTH('pass');
        afficheTH('mail');
        cbPrintf('<th colspan="2">action</th>');
        printf("</tr>\n");
      }
      printf("<tr>");
      $id=cbGetValue($row,'id');
      $user=cbGetValue($row,'user');
      $pass=str_repeat("* ",strlen(cbGetValue($row,'pass')));
      $mail=cbGetValue($row,'mail');
      afficheTD($id);
      afficheTD($user);
      afficheTD($pass);
      afficheTD($mail);
      afficheTD("<a href='index.php?action=manage&id=".$id."'>Modifier</a>");
      afficheTD("<a href='index.php?action=adminMadageDelete&id=".$id."'>Supprimer</a>");

      printf("</tr>\n");
    }
    printf("</table>\n");
    $stmt->closeCursor();
  }
}
?>
