<?php
$content = new TemplatePower('template/Clanjoinen.html');
$content->prepare();
$error = false;
$passerror = false;



  $ledenid = $_SESSION['ledenid'];
                
                $ophalen = $mysql->prepare("SELECT * FROM leden WHERE ledenid = :ledenid");
                $ophalen->bindParam(":ledenid", $ledenid);
                $ophalen->execute();
                $claninfo = $ophalen->fetch(PDO::FETCH_ASSOC);
                

                
 
                
                
 

if(!isset($_SESSION['groepid']))
{
    $content->newBlock("INGELOGD");
    $content->assign("ERRORTEXT1", "Je bent niet ingelogd.");
    $content->assign("ERRORTEXT2", "Je bent niet ingelogd.");
    $content->assign("ERRORTEXT3", "Je bent niet ingelogd.");
}
else
{

if(isset($_GET['actie']))
{
    $actie = $_GET['actie'];
}
else
{
    $actie = NULL;
}



switch($actie)
    
{
    case "aanmaken":

        $clannaamx = $_POST['clannaam'];
        $clannaamx_check = $mysql->prepare("SELECT * FROM clan WHERE clannaam = :clannaam");
        $clannaamx_check->bindParam(':clannaam', $clannaamx);
        $clannaamx_check->execute();

        if($clannaamx_check->rowCount()>0)
        {
            $content->newBlock("MELDINGCLAN");
            $content->assign("SUCCESTEXT" , "Clannaam is al in gebruik!");
        }

        else
        {
            if(isset($_POST['clanaanmaken']))
            {
                if(!empty($_POST['clannaam']))
                {
                    $insert = $mysql->prepare("INSERT INTO clan SET clanid = :clanid, clannaam = :clannaam");
                    $insert->bindParam(":clanid", $_SESSION['clanid']);
                    $insert->bindParam(":clannaam", $_POST['clannaam']);
                    $insert->execute();

                    $clanclanid = $mysql->lastInsertId();

                    $update = $mysql->prepare(" UPDATE leden SET clan_functie_clan_functieid = 2, groep_groepid = 4, clan_clanid = :clan_clanid WHERE ledenid = :ledenid ");
                    $update->bindParam(":clan_clanid", $clanclanid);
                    $update->bindParam(":ledenid", $_SESSION['ledenid']);
                    $update->execute();

                    $_SESSION['clan_clanid'] = $clanclanid;
                    $_SESSION['clan_functie_clan_functieid'] = 2;

                    $content->newBlock("SUCCES");
                    $content->assign("SUCCESTEXT", "U hebt succesvol een clan geregistreerd!");
                    echo "lol";
                }
                else
                {
                    print "Je hebt geen naam ingevuld";
                }
            }
        }

        break;

    case "pending":
    if($_SESSION['clan_functie_clan_functieid'] == 2 OR $_SESSION['clan_functie_clan_functieid'] == 1)
    {
       $content->newBlock("MELDINGCLAN");
       $content->assign("SUCCESTEXT" , "Je zit al in een clan, verlaat deze om een nieuwe te joinen!"); 
    }
    else
    {

        $pending = $mysql->prepare("UPDATE leden SET clan_clanid = :clan_clanid, groep_groepid = 3, clanaccept_clanacceptid = 3 WHERE ledenid = :ledenid");
        $pending->bindParam(":ledenid", $_SESSION['ledenid']);
        $pending->bindParam(":clan_clanid", $_GET['clan_clanid']);
        $pending->execute();

        $_SESSION['clan_clanid'] = NULL;
        $_SESSION['clan_functie_clan_functieid'] = NULL;
        
        $content->newBlock("MELDINGCLAN");
        $content->assign("SUCCESTEXT" , "Je aanvraag is verstuurd, wacht op antwoord!");

        echo "ok.";
    }
    
    break;

    
    case "verlaten":
    if($_SESSION['clan_functie_clan_functieid'] >= 1)
    {
       $verlaten1 = $mysql->prepare("UPDATE leden SET clan_clanid = NULL, clan_functie_clan_functieid = NULL, groep_groepid = 2 WHERE clan_clanid = :clan_clanid");
       $verlaten1->bindParam(":clan_clanid", $_SESSION['clan_clanid']);
       $verlaten1->execute();

        $_SESSION['clan_clanid'] = NULL;
        $_SESSION['clan_functie_clan_functieid'] = NULL;
       
      $content->newBlock("MELDINGCLAN");
      $content->assign("SUCCESTEXT", "U heeft de clan succesvol verlaten!");
        
    }
    else
    {
        
    }
    break;
    case "clanevent":
    if($_SESSION['clan_functie_clan_functieid'] == 2)
    {
        $clanevent = $mysql->prepare("INSERT INTO  events SET clan_clanid = :clan_clanid, eventaccept_eventacceptid = 1, eventresultaataccept_evenresultaatacceptid = 1");
        $clanevent->bindParam(":clan_clanid", $_SESSION['clan_clanid']);
        $clanevent->execute();

        $content->newBlock("MELDINGCLAN");
        $content->assign("SUCCESTEXT", "Clan uitdaging verzonden!");
    }


default:
    if($_SESSION['clan_functie_clan_functieid'] == 0)
    {
        $content->newBlock("FORMULIER");
    }
        
        if($_SESSION['clan_clanid'] == 0)
        {
          $content->newBlock("INGELOGD");
          $content->assign("ERRORTEXT1", "Clans");
          $content->assign("ERRORTEXT2", "Hier kan je een clanmaken of joinen.");
          $content->assign("ERRORTEXT3", "Clanlijst");
    
        $sql = "SELECT * FROM clan";

        $sqlvar = $mysql->prepare($sql);

        $results = $sqlvar->execute();



        while($row = $sqlvar->fetch(PDO::FETCH_ASSOC))
        {
            $username = $row['clannaam'];

            $content->newBlock("CLANS");
            $content->assign("ClanId", $row['clanid']);
            $content->assign("Clannaam", $row['clannaam']);

        }
    }
    else
    {
        if ($_SESSION['clan_functie_clan_functieid'] == 2)
        {
          
          $content->newBlock("INGELOGD1");
          $content->assign("ERRORTEXT1", "Clans");
          $content->assign("ERRORTEXT2", "Hier ziet u het overzicht van de andere clans.");
          $content->assign("ERRORTEXT3", "Clanlijst");
          $content->assign("ClanId", $_SESSION['clan_clanid']);
          
          $sql = "SELECT * FROM clan";
    
        $sqlvar = $mysql->prepare($sql);
    
        $results = $sqlvar->execute();
        
        
        
        while($row = $sqlvar->fetch(PDO::FETCH_ASSOC))
        {
            $username = $row['clannaam'];
            
            $content->newBlock("CLANS1");
            $content->assign("ClanId", $row['clanid']);
            $content->assign("Clannaam", $row['clannaam']);
             
        }
          
          }
          else {
          $content->newBlock("INGELOGD1");
          $content->assign("ERRORTEXT1", "Clans");
          $content->assign("ERRORTEXT2", "Hier ziet u het overzicht van de andere clans.");
          $content->assign("ERRORTEXT3", "Clanlijst");
          }
          
        if($_SESSION['clan_functie_clan_functieid'] == 0)
        {
          
          $sql = "SELECT * FROM clan";
    
        $sqlvar = $mysql->prepare($sql);
    
        $results = $sqlvar->execute();
        
        
        
        while($row = $sqlvar->fetch(PDO::FETCH_ASSOC))
        {
            $username = $row['clannaam'];
            
            $content->newBlock("CLANS");
            $content->assign("ClanId", $row['clanid']);
            $content->assign("Clannaam", $row['clannaam']);
             
        }
    }
    

    
    
    
    
}

}

}
var_dump($_SESSION);
?>