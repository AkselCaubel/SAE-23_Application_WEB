<?php
###############################################################################
# Renvoi la valeur de la case $name d'un tableau (ou '' si elle n'existe pas)
###############################################################################
function cbGetValue($array,$name,$default='') {
  if (isset($array[$name])) return $array[$name];
  else return $default;
}
###############################################################################
# Idem pour un tableau de tableau
function cbGetValue2($array,$key,$name,$default='') {
  if (isset($array[$key][$name])) return $array[$key][$name];
  else return $default;
}
###############################################################################
# Affiche les arguments comme avec un printf(), mais toujours avec \n à la fin
# cbPrintf('X=%d',42); est équivalent à printf("X=%d\n",42);
###############################################################################
function cbPrintf() {
  $args=func_get_args();
  $args[0].="\n";
  call_user_func_array('printf',$args);
}
########################################################
# Vérifie l'identité de la personne 
# Si c'est bien elle ou si elle a des droits privilégié
########################################################
function allowTo($id){
  global $pdo;

  $stmp = $pdo -> prepare('SELECT * FROM "users" WHERE "id"= :u ;');
  $stmp -> execute(array("u"=>$id));
  $row = $stmp->fetch();

  if($row['user']==$_SESSION['user'] or ifAdmin($id)){
    return true;
  }
  else{
    return false;
  }
}

######################################### 
#  Vérification Droit Utilisateur
#########################################

function ifAdmin(){
    global $admin;

    if (in_array($_SESSION['id'],$admin,true)){
        return true;
    }
    else {
        return false;
    }
}


#########################################
# Si la personne change son propre user,
# Modification de son user SESSION
#########################################

function ifUserChange($id,$user){
  if($_POST['id'] == $id){
  $_SESSION['user'] = $user;
  }
}

####################################################
## Liste des fonctions à charger
####################################################
require_once('funcs-afficheLoginForm.php');
require_once('funcs-auth.php');
?>