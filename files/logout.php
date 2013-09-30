<?php
$content = new TemplatePower('template/logout.html');
$content->prepare();
$error = false;
$passerror = false;

if (!isset($_SESSION['groepid']))
{
    header ("Location: index.php?pid=5");
}

if (!empty($_SESSION['ledenid']))
{
    
    if(isset($_POST['Uitloggen']))
    {
        
        $_SESSION['ledenid'] == "";
        $_SESSION['groepid'] == "";
        
        unset($_SESSION['ledenid']);
        unset($_SESSION['groepid']);
        
        header("Location: index.php?pid=10");
    }
    else
    {
    
    }
}
?>



