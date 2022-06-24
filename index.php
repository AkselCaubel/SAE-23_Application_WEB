<?php
$title='404Industries';
include('login.php');
require_once('funcs-usersList.php');
require_once('funcs-usersUpdateFormByID.php');
require_once('funcs-usersUpdateByID.php');
require_once('funcs-userAdd&Del.php');
require_once('funcs-view.php');
require_once('funcs-gestionStock.php');

$admin = array("3","4"); // Id des admin du site

cbPrintf('<h1>%s</h1>',$title);

cbPrintf('</br><a href="?action=schema"><input class="button" type="submit" name="maximal" value="Schéma" /></a>');
cbPrintf('<a href="?action=grid"><input class="button" type="submit" name="minimal" value="Tableau" /></a> </br>');
cbPrintf('</br><a href="?action=keep"><input class="button" type="submit" name="keep" value="Prendre"/></a>');
cbPrintf('<a href="?action=release"><input class="button" type="submit" name="release" value="Rendre" /></a></br>');
$action=cbGetValue($_REQUEST,'action');
if(cbGetValue($_REQUEST,'id')){
  $id = cbGetValue($_REQUEST,'id');
}
else {
  $id = $_SESSION['id'];
}



cbPrintf('<div id="latt-control">');

  cbPrintf('<div id="user-info">');
    selfUserView($id);
  cbPrintf('</div>');

  if (ifAdmin()){
  
    cbPrintf('<div id=control-pannel>');
      controlPannel();
    cbPrintf('</div>');
  }

cbPrintf('</div>');

cbPrintf('<div id="content">');

  switch($action) {
    case 'grid': grid();break; // Page Tableau

    case 'schemaJS':include("script-schema.html");break; // Page plan d'entreprise

    case 'schema':schemaPhp();break;

    case 'manage':if(allowTo($id)){ // Page de modification info personnel
      usersUpdateFormByID($id);break; 
    }
    case 'manageUpdate':if(allowTo($id)){ // Enregistre dans la DB ( Si moi même ou Admin )
    usersUpdateByID($id);break;

    }

    case 'adminGestionStock':if(ifAdmin()){ // Gestion du stock matériel ( Admin Only)
      adminGestionStock();
      cbPrintf('<a href="?action=adminGestionStockAdd"><input class="button" type="submit" name="add" value="Ajouter un équipement" /></a></br>');
      break; 
    }

    case 'adminGestionStockManage':if(ifAdmin()){ // Modification d'un équipement ( Admin Only)
      adminGestionStockManage();break;
    }

    case 'adminGestionStockManageUpdate':if(ifAdmin()){ // Applique la modification de l'équipement dans la DB ( Admin only)
      adminGestionStockManageUpdate();break;
    }

    case 'adminGestionStockAdd':if(ifAdmin()){  // Ajoute un équipement ( Admin Only)
      adminGestionStockAdd();break;
    }

    case 'adminGestionStockAddBase':if(ifAdmin()){
      adminGestionStockAddBase();break;
    }

    case 'adminGestionStockDel':if(ifAdmin()){  // Suprime  un équipement ( Admin Only)
      adminGestionStockDel();break;
    }

    case 'keep':keep();break; // Prendre un équipement

    case 'infoKeep':infoKeep();break; // Information du pret

    case 'updateKeep': // Enregistre le pret dans la DB
      $hardId=cbGetValue($_REQUEST,'hardId');
      updateKeep($hardId);
      break;

    case 'release':release();break; // Voir nos pret avec possibilité de rendu

    case 'updateRelease': // Rend le pret dans la DB
      $hardId=cbGetValue($_REQUEST,'hardId');
      updateRelease($hardId);
      break;

    case 'adminManage':if(ifAdmin()){ // Affichage de modification de tous les user de la DB
      usersList(); 
      cbPrintf('<a href="?action=adminManageAdd&error=&user=&mail=&submit=false"><input class="button" type="submit" name="add" value="Ajouter un utilisateur" /></a></br>');
      break;
    }
    else{
      printf("<p class='err'>Vous n'avez pas autorité sur cette page</p>");break;
    }

    case 'adminManageAdd':if (ifAdmin()){ // Ajouter des users ( Admin Only)
      usersAdd();
    }

    case 'adminUserAddBase':if(ifAdmin()){ // Ajouter l'utilisateur dans la DB si toutes les informations sont ok ( Admin Only)
      userAddDataBase();break;
    }

    case 'adminMadageDelete':if(ifAdmin()){ // Supprime un utilisateur ( Admin Only)
      userDelDataBase();break;
    }

    default: grid();break;
  }

cbPrintf("</div>");

$x= $_SESSION['user'];

include('html-fin.php');
?>
