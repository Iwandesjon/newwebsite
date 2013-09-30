<?php

$content = new TemplatePower('template/kalender.html');
$content->prepare();
$error = false;
$passerror = false;

if(!isset($_SESSION['groepid']))
{
    $content->newBlock("NIETINGELOGD");
    $content->assign("ERRORTEXT", "Je bent niet ingelogd, registreer een account of log nu in.");
}
else
{

    $maanden     = array(1 => 'Januari', 2 => 'Februari', 3 => 'Maart', 4 => 'April', 5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Augustus', 9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'December');
    $weekdagen    = array('maandag','dinsdag','woensdag','donderdag','vrijdag','zaterdag','zondag');
    $jaren         = range(2013,2014);
    
    if(isset($_GET['maand']))
    {
        if(array_key_exists($_GET['maand'], $maanden))
        {
            $maand = $_GET['maand'];
        }
        else
        {
            $maand = date('n');
        }
    }
    else
    {
        $maand = date('n');
    }
    
    if(isset($_GET['dag']))
    {
        if(in_array($_GET['dag'], $weekdagen))
        {
            $dag = ($_GET['dag']);
        }
        else
        {
            $dag = 1;
        }
    }
    else
    {
    
        if($maand == date('n'))
        {
            $dag = date('j');
        }
        else
        {
            $dag = 1;
        }
    }
    
    if(isset($_GET['jaar']))
    {
        if(in_array($_GET['jaar'],$jaren))
        {
            $jaar = ($_GET['jaar']);
        }else
        {
            $jaar = date('Y');
        }
    }
    else
    {
        $jaar = date('Y');
    }
    
    $huidigeDag = gmmktime(0,0,0,$maand,$dag,$jaar);
    $dagenInMaand = gmdate("t", $huidigeDag);
    
    $eersteVanMaand = gmmktime(0,0,0,$maand,1,$jaar);
    $eersteDagMaand = gmdate("N", $eersteVanMaand);
    
    $content->newBlock("ROW");
    
    
    for($i = $eersteDagMaand; $i > 1; $i-- )
    {
       $content->newBlock("EERSTEDAG");
       $content->gotoBlock("_ROOT");
    }
    $dagenteller = $eersteDagMaand;
    for($j = 1; $j <= $dagenInMaand; $j++)
    {
        $content->newBlock("DAYS");
        $content->assign("DAG", $j);
        // als dagenteller == 7 => nieuwe rij
        if($dagenteller == 7){
    
            $content->newBlock("ROW");
            $content->gotoBlock("_ROOT");
            $dagenteller = 1;
        }
        else
        {
            $dagenteller++;
        }
    }
    
    if($dagenteller == 1)
    {
    
    }
    else
    {
        for($k = $dagenteller; $k <= 7; $k++)
        {
            $content->newBlock("ROWEND");
            $content->gotoBlock("_ROOT");
        }
    }
    $content->newBlock("VORIGEMAAND");
    if($maand == 1)
    {
        //$template->newBlock(VORIGJAAR);
        //$template->assign(Vorigjaar,"($jaar-1)");
        $content->assign("MAANDTERUG", "12");
        $content->assign("JAARTERUG", ($jaar-1));
    }
    else
    {
        $content->assign("MAANDTERUG", ($maand-1));
        $content->assign("JAARTERUG", $jaar);
    }
    $content->newBlock("VOLGENDEMAAND");
    if($maand == 12)
    {
        $content->assign("VOLGENDEMAAND", "1");
        $content->assign("VOLGENDEJAAR", ($jaar+1));
    }
    else
    {
        $content->assign("VOLGENDEMAAND", ($maand+1));
        $content->assign("VOLGENDEJAAR", $jaar);
    }

}

?>