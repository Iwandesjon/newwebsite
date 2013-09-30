<?php
session_start();
include_once('include/class.TemplatePower.inc.php');
include ("header.php");
// include ("content.php");




if(isset($_GET['pid']))
{
    

    // controleren of de geselecteerde pagina in de database staat. In url staat de :pid als GET waarde
    $check = $mysql->prepare('SELECT COUNT(*) FROM pagina WHERE id=:pid');
    $check->bindParam( ":pid" , $_GET['pid']);
    $check->execute();

    if($check->fetchColumn() == 1)
    {
        $var = $mysql->prepare('SELECT * FROM pagina WHERE id=:pid');
        $var->bindParam( ":pid" , $_GET['pid']);
        $var->execute();

        $pagina = $var->fetch(PDO::FETCH_ASSOC);


        include($pagina['map']."/".$pagina['bestand']);


    }
    else
    {
        include("files/home.php");
    }
}
else
{
    include("files/home.php");
    
    $pid = NULL;
    
}

include ("footer.php");
?>
