<?php
include_once( "include/class.TemplatePower.inc.php" );
$mysql = new PDO('mysql:host=localhost;dbname=clandbnew' , 'root' , 'Kippenpoot1234' , array(     PDO::ATTR_PERSISTENT => true ) );

$header = new TemplatePower( "template/header.html" );
$header->prepare();

if(isset($_GET['pid']))
{
    $_GET['pid'] = $_GET['pid'];
}
else
{
    $_GET['pid'] = NULL;
}

for($i = 1; $i <= 13; $i++)
{
    if($_GET['pid'] == $i)
    {
        $menunaam = "menu".$i;
        $header->assign( $menunaam, "class='current'");
    }
    
}

if (!(isset($_SESSION['ledenid'])))
{
    $header->newBlock("GUESTNAV");
    if ($_GET['pid'] == 8)
    {
        $header->assign( "menu8", "class='current'");
    }
    elseif($_GET['pid'] == 5)
    {
        $header->assign( "menu5", "class='current'");
    }
}
elseif($_SESSION['groepid'] == 2)
{
    $header->newBlock("MEMBERNAV");
}
elseif($_SESSION['groepid'] == 1)
{
    $header->newBlock("MEMBERNAV");
    $header->newBlock("ADMINNAV");
}
elseif($_SESSION['groepid'] == 4)
{
    $header->newBlock("CLANLEIDERNAV");
}
elseif($_SESSION['groepid'] == 3)
{
    $header->newBlock("CLANMEMBERNAV");
}

?>