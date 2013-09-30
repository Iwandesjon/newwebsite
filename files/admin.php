<?php
$content = new TemplatePower('template/admin.html');
$content->prepare();
$error = false;
$passerror = false;

if (isset($_GET['actie']))
{
    $actie = $_GET['actie'];
}
else {
    $actie = "";
}

if (!isset($_SESSION['groepid']) || ($_SESSION['groepid'] != 1))
{
    header ("Location: index.php?pid=10");
}
 
if (isset ($_POST['Zoeken']))
{
    try {
        
        $Zoeken = "%".$_POST['Zoeken']."%";
        $persoon = $mysql->prepare(" SELECT gebruikersnaam, ledenid FROM leden WHERE gebruikersnaam LIKE :Username ");
        $persoon->bindParam(":Username", $Zoeken); 
        $persoon->execute();
        
        }
        
        catch(PDOException $e) 
        
        {
            
            $content->newBlock("MELDING");
            $content->assign("BERICHT"   , "<pre>Regel: ".$e->getLine()."<br>Bestand: ".$e->getFile()."<br>Foutmelding: ".$e->getMessage());
        
        } 
            
        while ($row = $persoon->fetch(PDO::FETCH_ASSOC)) 
        {
        
            $content->newBlock("LEDEN");
            $content->assign("Leden", $row['gebruikersnaam']);
            $content->assign("LidId", $row['ledenid']);
            
        } 
}

elseif($actie == "verwijderen")
{
    $verwijderen = $mysql->prepare (" DELETE FROM leden WHERE ledenid = :ledenid ");
    $verwijderen->bindParam(":ledenid", $_GET['id']);
    $verwijderen->execute();
}

elseif ($actie == "bewerken")
{
    
    if(isset($_POST['Bewerken']))
    {

    try {        
        $bewerken = $mysql->prepare (" UPDATE leden
                                    SET voornaam = :voornaam, achternaam = :achternaam, gebruikersnaam = :gebruikersnaam, email = :email 
                                    WHERE ledenid = :ledenid ");
        $bewerken->bindParam(":ledenid", $_GET['id']);
        $bewerken->bindParam(":voornaam", $_POST['Voornaam']);
        $bewerken->bindParam(":achternaam", $_POST['Achternaam']);
        $bewerken->bindParam(":gebruikersnaam", $_POST['Gebruikersnaam']);
        $bewerken->bindParam(":email", $_POST['Email']);
        $bewerken->execute();
        }
    
    catch(PDOException $e) {
        $template->newBlock("MELDING");
        $template->assign("BERICHT"   , "<pre>Regel: ".$e->getLine()."<br>Bestand: ".$e->getFile()."<br>Foutmelding: ".$e->getMessage());
        }    
    }
    
    $update = $mysql->prepare ("SELECT voornaam, achternaam, gebruikersnaam, email FROM leden WHERE ledenid = :ledenid ");
    $update->bindParam(":ledenid", $_GET['id']);
    $update->execute();
    
    $resultaat = $update->fetch(PDO::FETCH_ASSOC);
    
    $content->newBlock("BEWERKEN");
    $content->assign("LidId", $_GET['id']);
    $content->assign("voornaam", $resultaat['voornaam']);
    $content->assign("achternaam", $resultaat['achternaam']);
    $content->assign("gebruikersnaam", $resultaat['gebruikersnaam']);
    $content->assign("email", $resultaat['email']);


}
else
{
   echo "Je kan niks doen";
}
?>