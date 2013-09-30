<?php
$content = new TemplatePower('template/leden.html');
$content->prepare();
$error = false;
$passerror = false;

try
{

    $mysql = new PDO('mysql:host=localhost;dbname=clandbnew','root','');
    $mysql->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); 
        
    $sql = "SELECT `ledenid`, `gebruikersnaam` , `voornaam` , `groep_groepid` FROM leden";
             
    $stmt = $mysql->prepare($sql); 
              
    $stmt->execute(); 
             
    while($row = $stmt->fetch(PDO::FETCH_ASSOC)) 
    { 
        $content->newBlock("LEDEN");
        $content->assign("GEBRUIKERSNAAM" , $row['gebruikersnaam']."<br>");;
        $content->assign("RANK" , $row['groep_groepid']."<br>");;
        $content->assign("NAAM" , $row['voornaam']."<br>");
        $content->assign("LidId", $row['ledenid']);
    }
}
            
    catch(PDOException $e) 
    { 
        $content->newBlock("LEDEN");
        $content->assign("" , "<pre>Regel: ".$e->getLine()."<br>Bestand: ".$e->getFile()."<br>Foutmelding: ".$e->getMessage());
    }

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
    case 'sendMessage':
       $content->newBlock("BERICHTEN");
       $content->assign("LidId", $_GET['id']);

       if (isset($_POST['submitMessage']))
       {
           date_default_timezone_set('UTC');
           $tijd = time();

           $sendmessagequery = "INSERT INTO berichten ( onderwerp, bericht, berichtdatum)
                                VALUES ( :onderwerp, :bericht, :berichtdatum)";
           $sendmessage = $mysql->prepare($sendmessagequery);
           $sendmessage->bindParam(":onderwerp", $_POST['onderwerp']);
           $sendmessage->bindParam(":bericht", $_POST['bericht']);
           $sendmessage->bindParam(":berichtdatum", $tijd);
           $sendmessage->execute();

           $messageid = $mysql->lastInsertId($sendmessagequery);

           $messageissend = $mysql->prepare("INSERT INTO gebruikers_has_berichten (gebruikers_gid, berichten_berichtid, berichtstatus_berichtstatusid)
                                             VALUES (:gebruikers_gid, :berichten_berichtid, 1)");
           $messageissend->bindParam(":gebruikers_gid", $_GET['id']);
           $messageissend->bindParam(":berichten_berichtid", $messageid);
           $messageissend->execute();

           $content->newBlock("ERROR");
           $content->assign("ERRORTEXT", "Je bericht is verzonden!");
       }

    break;
}

?>