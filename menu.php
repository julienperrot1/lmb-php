<?php

$RACINE = "";
print ("<SCRIPT type=\"text/javascript\">");
print ("RACINE = '" . $RACINE . "';");
print ("</SCRIPT>");

include_once ($RACINE . 'utils/Bdd.php');
include_once ($RACINE . 'utils/load_session.php');
include_once ($RACINE . 'config.php');

	
?>

<SCRIPT src="ajax/ajax.js" type="text/javascript"></SCRIPT>
<SCRIPT src="ajax/menu.js" type="text/javascript"></SCRIPT>

<HTML>
	<META http-equiv="content-type" content="text/html; charset=utf-8" />

	<HEAD>
		<TITLE>MONOBASKET - Le site de gestion et de statistique du monobasket</TITLE>
		<LINK rel="stylesheet" type="text/css" href="styles/global.css">
	</HEAD>

	<BODY>
		<DIV class="menu">
			<TABLE>
				<TR>
					<?php
				
						print ("<TD><DIV onmouseover=\"this.style.cursor='pointer'\" onclick=\"window.location.href = 'index.php';\">Accueil</DIV></TD>");			
						print ("<TD><DIV onmouseover=\"this.style.cursor='pointer'\" onclick=\"window.location.href = 'joueurs.php';\">Les joueurs</DIV></TD>");			
						print ("<TD><DIV onmouseover=\"this.style.cursor='pointer'\" onclick=\"window.location.href = 'equipes.php';\">Les équipes</DIV></TD>");	
						print ("<TD><DIV onmouseover=\"this.style.cursor='pointer'\" onclick=\"window.location.href = 'ligue.php?id=8';\">LMB 2016-17</DIV></TD>");
						print ("<TD><DIV onmouseover=\"this.style.cursor='pointer'\" onclick=\"window.location.href = 'ligues.php';\">Les autres ligues</DIV></TD>");	
						print ("<TD><DIV onmouseover=\"this.style.cursor='pointer'\" onclick=\"window.location.href = 'aide.php';\">Aide d'utilisation</DIV></TD>");	
						
					?>
				</TR>
			</TABLE>
		</DIV>
		
		<DIV class="menuLogin" id="menu.login">
		</DIV>
		

		<SCRIPT type="text/javascript">chargeLogin();</SCRIPT>