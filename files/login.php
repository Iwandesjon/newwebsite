<?php
$content = new TemplatePower('template/login.html');
$content->prepare();
$error = false;
$passerror = false;

if (isset($_SESSION['groepid']))
{
    header ("Location: index.php?pid=6");
}

    if(isset($_POST['submit']))
    {
        if(!empty($_POST['gebruikersnaam']) AND !empty($_POST['wachtwoord']))
        {
            $Wachtwoord = sha1($_POST['wachtwoord']);
            // controle van gebruikersnaam + wachtwoord
            $check = $mysql->prepare("SELECT COUNT(*) FROM leden
                                    WHERE gebruikersnaam = :gebruikersnaam 
                                    AND wachtwoord = :wachtwoord");
            $check->bindParam(":gebruikersnaam", $_POST['gebruikersnaam']);
            $check->bindParam(":wachtwoord", $Wachtwoord);
            $check->execute();
            $aantal = $check->fetchColumn();
            
            if($aantal == 1)
            {
                $persoon = $mysql->prepare("SELECT * FROM leden
                                            WHERE gebruikersnaam = :gebruikersnaam
                                            AND wachtwoord = :wachtwoord");
                $persoon->bindParam(":gebruikersnaam", $_POST['gebruikersnaam']);
                // $wachtwoord = sha1($_POST['wachtwoord']);
                $persoon->bindParam(":wachtwoord", $Wachtwoord);
                $persoon->execute();
                $persooninfo = $persoon->fetch(PDO::FETCH_ASSOC);
                
                $_SESSION['ledenid'] = $persooninfo['ledenid'];
                $_SESSION['groepid'] = $persooninfo['groep_groepid'];

                
                $ledenid = $_SESSION['ledenid'];
                
                $ophalen = $mysql->prepare("SELECT * FROM leden WHERE ledenid = :ledenid");
                $ophalen->bindParam(":ledenid", $ledenid);
                $ophalen->execute();
                $claninfo = $ophalen->fetch(PDO::FETCH_ASSOC);
                
                $_SESSION['clan_clanid'] = $claninfo['clan_clanid'];
                $_SESSION['clan_functie_clan_functieid'] = $claninfo['clan_functie_clan_functieid'];
                var_dump($_SESSION);

            }
            else
            {
                $content->newBlock("LOGINFORM");
                $content->assign("ERROR", "Uw gebruikersnaam en/of wachtwoord is onjuist!");
                
            }
        }
        else
        {
            $content->newBlock("LOGINFORM");
            $content->assign("ERROR", "U bent uw Gebruikersnaam en/of wachtwoord vergeten in te vullen");
            
        }
    }
    else
    {
        // formulier
        $content->newBlock("LOGINFORM");
        
    }
?>