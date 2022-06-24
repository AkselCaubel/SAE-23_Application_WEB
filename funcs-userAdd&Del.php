<?php

include("html-debut.php");


######################################### 
#               MANAGE USER             #
######################################### 

function usersAdd(){
    global $pdo;

    $error = $_GET['error'];
    for($i=0;$i<count($error);$i++){
        cbPrintf('<h3 style="color:red;">%s</h3>',$error);
    }
    $error=null;

    $_POST['user'] = "";
    $_POST['pass'] = "";
    $_POST['mail'] = "";
    $_POST['verifPass']="";

    $_POST['submit']=false;
    cbPrintf('<form action="%s?action=adminUserAddBase&submit=true" method="post" accept-charset="utf8">',$_SERVER['PHP_SELF']);
    cbPrintf('<table class="update">');

    $user=sprintf('<input type="text" name="user" value="%s"/>',$_GET['user']);
    $pass=sprintf('<input type="password" name="pass" value=""/>');
    $verifPass=sprintf('<input type="password" name="verifPass" value=""/>');
    $mail=sprintf('<input type="mail" name="mail" value="%s"/>',$_GET['mail']);

    afficheTRTHTD('Utilisateur :',$user);
    afficheTRTHTD('Mot de passe :',$pass);
    afficheTRTHTD('Confirmation du mot de passe :',$verifPass);
    afficheTRTHTD('Adresse :',$mail);
    afficheTRTD('<input type="submit" value="Ajouter"/>','colspan="2"');
    cbPrintf('</table>');
    cbPrintf('</form>');
}

function userAddDataBase(){
    global $pdo;


    
    
    $error = array();
    
    $ok = 0;
    
    
    
    if(!$_POST['user']){
        array_push($error,"Merci de rentrer un nom d'utilisateur");
        
        $ok-=1;
    }

    elseif(!usersCheckModifOK($_POST['user'])){
        array_push($error,"Ce nom d'utilisateur est déjà prit ");
    }
    
    if(!$_POST['mail']){
        array_push($error,"Merci de rentrer un mail");
 
    
        $ok -=1;
    };

    if(strcmp($_POST['pass'],$_POST['verifPass'])==0 && $_POST['pass']!=""){

        $ok +=1;
        
    }
    else{
        array_push($error,"Les mots de passes ne correspondent pas");
        
    }
    
    $user = $_POST['user'];
    $mail = $_POST['mail'];
    
    
    if($ok!=1){
    
        
        $submit=$_GET['submit'];
        
        if ($submit=='true'){  // test sur submit pour ne pas subir une redirection direct

            $_POST['error']=$error;
            $form = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'].'?action=adminManageAdd&submit=false&error='.$error[0].'&user='.$user.'&mail='.$mail;
            header('Location: '.$form);
            exit(1);

        }
    
    }

    else{
        $sql = 'INSERT INTO "users" (user,pass,mail) VALUES (:u,:p,:m);';
        $stmt = $pdo->prepare($sql);
        $stmt -> execute(array('u'=>$user,'p'=>sha1($_POST['pass']),'m'=>$mail));
    }


}

function userDelDataBase(){
    global $pdo;

    $sql = 'DELETE FROM "users" WHERE "id"=:i';
    $stmt = $pdo -> prepare($sql);
    $stmt -> execute(array('i'=>$_GET['id']));
}


?>

