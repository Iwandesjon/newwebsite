<?php
$content = new TemplatePower('template/clanuitdagen.html');
$content->prepare();

if(isset($_GET['actie']))
{
    $actie = $_GET['actie'];
}
else
{
    $actie = NULL;
}

if ($_SESSION['clan_functie_clan_functieid'] == 2 )
{
    $check_clanevent = $mysql->prepare("SELECT count(*) FROM events WHERE eventaccept_eventacceptid = 1");
    $check_clanevent->execute();

    if($check_clanevent->fetchColumn() > 0)
    {
        $get_clanevent = $mysql->prepare("SELECT events.*, clan.clannaam, clan.clanid FROM events, clan WHERE eventaccept_eventacceptid = 1");

        $get_clanevent->execute();
        $content->newBlock("CLANEVENT");
        while($clanevent = $get_clanevent->fetch(PDO::FETCH_ASSOC))
        {
            $content->assign("Clannaam", $clanevent['clannaam']);
            $content->assign("ClanId", $clanevent['clan_clanid']);
        }

    }
}

switch($actie)
{
    case "accept":
        if ($_SESSION['clan_functie_clan_functieid'] == 2 )
        {
            $acceptevent = $mysql->prepare("UPDATE events SET eventaccept_eventacceptid = 2 WHERE clan_clanid = :clan_clanid");
            $acceptevent->bindParam(":clan_clanid",$_GET['clan_clanid']);
            $acceptevent->execute();
        }
    break;

    case "weiger":
        if ($_SESSION['clan_functie_clan_functieid'] == 2 )
        {
            $weigerevent = $mysql->prepare("DELETE * FROM events WHERE clan_clanid = :clan_clanid");
            $weigerevent->bindParam(":clan_clanid", $_GET['clanid']);
            $weigerevent->execute();
        }
        break;
}
?>