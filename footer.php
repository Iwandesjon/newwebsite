<?php
$footer = new TemplatePower( "template/footer.html" );
$footer->prepare();

$sql = "SELECT gebruikersnaam FROM leden ORDER BY ledenid DESC LIMIT 0, 4";

$stmt = $mysql->prepare($sql);

$stmt->execute();

while($row = $stmt->fetch(PDO::FETCH_ASSOC))
{
    $footer->newBlock("LAATSTELEDEN");
    $footer->assign("LAATSTELID" , $row['gebruikersnaam']."<br>");
}

$header->printToScreen();
$content->printToScreen();
$footer->printToScreen();
?>
