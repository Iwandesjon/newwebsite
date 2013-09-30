<?php
$content = new TemplatePower('template/aanmelden.html');
$content->prepare();
$error = false;
$passerror = false;

if (isset($_SESSION['groepid']))
{
    header ("Location: index.php?pid=10");
}

if (isset($_POST['Registreer']))
{
    $mysql = new PDO('mysql:host=localhost;dbname=clandbnew', 'root', 'Kippenpoot1234');
    $mysql->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    $Gebruikersnaam = $_POST['Gebruikersnaam'];
    $Voornaam = $_POST['Voornaam'];
    $Achternaam = $_POST['Achternaam'];
    $Wachtwoord = sha1($_POST['Wachtwoord']);
    $Email = $_POST['Email'];
    
    $Gebruikersnaam = $_POST['Gebruikersnaam'];
    $Email = $_POST['Email'];
    $Gebruikersnaam_check = $mysql->prepare("SELECT * FROM leden WHERE Gebruikersnaam = :gebruikersnaam");
    $Email_check = $mysql->prepare("SELECT * FROM leden WHERE Email = :email");
    $Gebruikersnaam_check->bindParam(':gebruikersnaam', $Gebruikersnaam);
    $Email_check->bindParam(':email', $Email);
    $Gebruikersnaam_check->execute();
    $Email_check->execute();
        if($Gebruikersnaam_check->rowCount()>0)
        {
            $content->newBlock("MELDING");
            $content->assign("BERICHT" , "Gebruikersnaam is al in gebruik!");
        }
        elseif($Email_check->rowCount()>0)
        {
            $content->newBlock("MELDING");
            $content->assign("BERICHT" , "Email is al in gebruik!");
        }
        else 
        {
            if($_POST['Wachtwoord'] == $_POST['Herhaalwachtwoord'])
            {
                $passerror = false;
            }
            elseif($_POST['Wachtwoord'] !== $_POST['Herhaalwachtwoord'])
            {
                $passerror = true;
            }
            if($passerror == true)
            {
                $content->newBlock("MELDING");
                $content->assign("BERICHT" , "Wachtwoorden komen niet overeen!");
            }
            else
            {  
                if( strlen($Wachtwoord) < 8 ) 
                {
                    $content->newBlock("MELDING");
                    $content->assign("BERICHT" , "Uw wachtwoord moet minimaal uit 8 karakters bestaan!");
                }
                else
                {
                    
                    if($_POST['Gebruikersnaam'] == "")
                    {
                        $error = true;
                    }
                    elseif($_POST['Voornaam'] == "")
                    {
                        $error = true;
                    }
                    elseif($_POST['Achternaam'] == "")
                    {
                        $error = true;
                    }
                    elseif($_POST['Wachtwoord'] == "")
                    {
                        $error = true;
                    }
                    elseif($_POST['Herhaalwachtwoord'] == "")
                    {
                        $error = true;
                    }
                    elseif($_POST['Email'] == "")
                    {
                        $error = true;
                    }
                    if($error == true)
                    {
                        $content->newBlock("MELDING");
                        $content->assign("BERICHT" , "U heeft &eacute;&eacute;n of meerdere velden niet ingevuld!");
                    }
                    else
                    {
                        try 
                        {          
                        $sql = " 
                                INSERT INTO leden (`ledenid` , `gebruikersnaam` , `voornaam` , `achternaam` , `wachtwoord` , `email` , `groep_groepid` )
                                VALUES ( NULL , :gebruikersnaam , :voornaam , :achternaam , :wachtwoord , :email , 2)                              
                                ";


                                $stmt = $mysql->prepare($sql);
                                $Wachtwoord = sha1($_POST['Wachtwoord']);
                                $stmt->bindParam(':gebruikersnaam', $_POST['Gebruikersnaam'], PDO::PARAM_STR);
                                $stmt->bindParam(':voornaam', $_POST['Voornaam'], PDO::PARAM_STR);
                                $stmt->bindParam(':achternaam', $_POST['Achternaam'], PDO::PARAM_STR);
                                $stmt->bindParam(':wachtwoord', $Wachtwoord, PDO::PARAM_STR);
                                $stmt->bindParam(':email', $_POST['Email'], PDO::PARAM_STR);
                                
                                $stmt->execute();
                                $content->newBlock("MELDING");
                                $content->assign("BERICHT" , "Uw account is succesvol aangemaakt! " );
                                
                                }
                    
                            catch(PDOException $e) 
                            { 
                                $content->newBlock("MELDING");
                                $content->assign("BERICHT"   , "<pre>Regel: ".$e->getLine()."<br>Bestand: ".$e->getFile()."<br>Foutmelding: ".$e->getMessage());
                            }
                        }
                    }
                }
            }
}

else    {
        $content->newBlock("FORM");
        }
?>