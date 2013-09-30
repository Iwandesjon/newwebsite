<?php
$content = new TemplatePower('template/externeadminpanel.html');
$content->prepare();
$error = false;
$passerror = false;

if(isset($_GET['actie']))
{
    $actie = $_GET['actie'];
}
else
{
    $actie = NULL;
}

if($_SESSION['groepid'] == 4)
{

    try
    {

        $ledenchecken = $mysql->prepare("SELECT gebruikersnaam FROM leden WHERE clan_clanid = :clan_clanid");
        $ledenchecken->bindParam(":clan_clanid", $_SESSION['clan_clanid']);
        $ledenchecken->execute();


        while($row = $ledenchecken->fetch(PDO::FETCH_ASSOC))
        {
            $content->newBlock("LEDEN");
            $content->assign("GEBRUIKERSNAAM" , $row['gebruikersnaam']."<br>");;

        }
    }

    catch(PDOException $e)
    {
        $content->newBlock("LEDEN");
        $content->assign("" , "<pre>Regel: ".$e->getLine()."<br>Bestand: ".$e->getFile()."<br>Foutmelding: ".$e->getMessage());
        print "bla";
    }

}

switch($actie)
{
case "accepteren":
    if($_SESSION['groepid'] == 4)
    {
        $accepteren = $mysql->prepare("UPDATE leden SET clanaccept_clanacceptid = 1, clan_functie_clan_functieid = 1, groep_groepid = 3 WHERE ledenid = :ledenid");
        $accepteren->bindParam(":ledenid", $_GET['ledenid']);
        $accepteren->execute();


    }

    break;
default:
        if($_SESSION['groepid'] == 4)
        {
            $ophalen1 = $mysql->prepare("SELECT * FROM leden WHERE clan_clanid = :clan_clanid AND clanaccept_clanacceptid = 3");
            $ophalen1->bindParam(":clan_clanid", $_SESSION['clan_clanid']);
            $ophalen1->execute();

            while($info = $ophalen1->fetch())
            {
                $content->newBlock("ACCEPTEREN");
                $content->assign("USERID", $info['ledenid']);
                $content->assign("GEBRUIKERSNAAM", $info['gebruikersnaam']);
            }
        }

}

?>