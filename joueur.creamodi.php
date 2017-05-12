<?php

include_once 'menu.php';

include_once ($RACINE . 'modele/Joueur.php');

?>

<SCRIPT src="ajax/joueur.creamodi.js" type="text/javascript"></SCRIPT>

<DIV class="corps">
	<DIV class="pleineLargeur">
		<DIV class="titre">
			Création / Edition d'un joueur
		</DIV>
	</DIV>
	
	<DIV class="pleineLargeur">
		<DIV class="texte">
			<?php
			
			$joueur = null;
			$joueur_pseudo = "";
			$joueur_nom = "";
			$joueur_prenom = "";
			$joueur_naissance_jour = "01";
			$joueur_naissance_mois = "01";
			$joueur_naissance_annee = "1901";
			$joueur_sexe = "M";
			$joueur_photo = null;
			$joueur_nom_utilisateur = null;
			$joueur_droits = 0;
			if (isset($_GET["id"]))
			{
				$joueur = Joueur::recup($_GET["id"]);
				$joueur_pseudo = $joueur->get("pseudo");
				$joueur_nom = $joueur->get("nom");
				$joueur_prenom = $joueur->get("prenom");
				$joueur_naissance = explode("-", $joueur->get("naissance"));
				$joueur_naissance_jour = $joueur_naissance[2];
				$joueur_naissance_mois = $joueur_naissance[1];
				$joueur_naissance_annee = $joueur_naissance[0];
				$joueur_sexe = $joueur->get("sexe");
				$joueur_photo = $joueur->get("photo");
				$joueur_nom_utilisateur = $joueur->get("nom_utilisateur");
				$joueur_droits = $joueur->get("droits");;
			}
				
				
			if (isset($utilisateur_en_cours) && ($utilisateur_en_cours->get("droits") >= 3 || (isset($_GET["id"]) && $utilisateur_en_cours->get("droits") >= 1 && $utilisateur_en_cours->get("id") == $_GET["id"])))
			{	
				if ($utilisateur_en_cours->get("droits") >= 3)
				{
					$disabled = "";
				}
				else
				{
					$disabled = " disabled";
				}
				
				print ("Pseudo : ");
				print ("<INPUT class=\"champTexte\" id=\"joueur.creamodi.pseudo\" type=\"text\" maxlength=\"255\" size=\"50\" value=\"" . $joueur_pseudo . "\">");	
				print ("<BR/>");

				print ("Nom : ");
				print ("<INPUT class=\"champTexte\" id=\"joueur.creamodi.nom\" type=\"text\" maxlength=\"255\" size=\"50\" value=\"" . $joueur_nom . "\"" . $disabled . ">");	
				print ("<BR/>");

				print ("Prénom : ");
				print ("<INPUT class=\"champTexte\" id=\"joueur.creamodi.prenom\" type=\"text\" maxlength=\"255\" size=\"50\" value=\"" . $joueur_prenom . "\"" . $disabled . ">");	
				print ("<BR/>");
				
				print ("Nom d'utilisateur : ");
				print ("<INPUT class=\"champTexte\" id=\"joueur.creamodi.nom_utilisateur\" type=\"text\" maxlength=\"255\" size=\"50\" value=\"" . $joueur_nom_utilisateur . "\"" . $disabled . ">");	
				print ("<BR/>");
				
				print ("Modifier le mot de passe : ");
				print ("<INPUT class=\"champTexte\" id=\"joueur.creamodi.mdp\" type=\"password\" maxlength=\"255\" size=\"20\">");	
				print (" Répéter : ");
				print ("<INPUT class=\"champTexte\" id=\"joueur.creamodi.mdp_bis\" type=\"password\" maxlength=\"255\" size=\"20\">");	
				print ("<BR/>");
				
				print ("Droits : ");
				print ("<SELECT id=\"joueur.creamodi.droits\" STYLE=\"width:50%\"" . $disabled . ">");
				foreach($DROITS_DESC as $droits_index => $droits_desc)
				{			
					print ("<OPTION value=\"" . $droits_index . "\"");
					if ($joueur_droits && $joueur_droits == $droits_index)
					{
						print (" selected=\"true\"");
					}
					print (">" . $droits_desc . "</OPTION>");
				}
				print ("</SELECT>");
				print ("<BR/>");
				
				print ("Date de naissance : ");
				print ("<INPUT class=\"champTexte\" id=\"joueur.creamodi.naissance_jour\" type=\"text\" maxlength=\"2\" size=\"1\" value=\"" . $joueur_naissance_jour . "\">");	
				print (" / ");
				print ("<INPUT class=\"champTexte\" id=\"joueur.creamodi.naissance_mois\" type=\"text\" maxlength=\"2\" size=\"1\" value=\"" . $joueur_naissance_mois . "\">");	
				print (" / ");
				print ("<INPUT class=\"champTexte\" id=\"joueur.creamodi.naissance_annee\" type=\"text\" maxlength=\"4\" size=\"2\" value=\"" . $joueur_naissance_annee . "\">");	
				print ("<BR/>");
				
				print ("Sexe : ");
				print ("<INPUT id=\"joueur.creamodi.sexe.M\" name=\"joueur.creamodi.sexe\" type=\"radio\" value=\"M\"");
				if ($joueur_sexe == "M")
				{
					print (" checked");
				}
				print ("> Masculin ");	
				print ("<INPUT id=\"joueur.creamodi.sexe.F\" name=\"joueur.creamodi.sexe\" type=\"radio\" value=\"F\"");
				if ($joueur_sexe == "F")
				{
					print (" checked");
				}
				print ("> Féminin");
				print ("<BR/>");
				
				print ("Photo : ");
				if ($joueur_photo && $joueur_photo != "")
				{
					print ("<IMG id=\"joueur.creamodi.photo\" class=\"image_grande\" src=\"" . $IMAGE_UPLOAD_DIR . "/" . $joueur_photo . "\"></IMG>");
				}
				else
				{
					print ("<IMG id=\"joueur.creamodi.photo\" class=\"image_grande\" src=\"images/joueur_default_" . $joueur_sexe . ".jpg\"></IMG>");
				}
				print ("<INPUT id=\"joueur.creamodi.photo.fichier\" type=\"hidden\" value=\"" . $joueur_photo . "\">");
				
				?>
				
				<FORM action="utils/upload_photo.php" method="post" enctype="multipart/form-data" target="joueur.upload.iframe" onsubmit="demarreUpload();">
					<P id="joueur.upload.loading" hidden="true"><IMG src="images/loading.gif"/></P>
					<P id="joueur.upload.formulaire">
						<INPUT name="fichier" type="file" size="30"/>
						<INPUT type="submit" name="joueur.upload.formulaire.valide" value="Charger la photo" />
					 </P>

					 <IFRAME id="joueur.upload.iframe" name="joueur.upload.iframe" src="#" style="width:0;height:0;border:0px solid #fff;"></IFRAME>
				 </FORM>
				
				
				<?php
				
				print ("<BR/>");
				
				if ($joueur)
				{
					print ("<INPUT id=\"joueur.creamodi.id\" type=\"hidden\" value=\"" . $joueur->get("id") . "\">");
					print ("<DIV class=\"champ_a_cliquer\" onmouseover=\"this.style.cursor='pointer'\" onclick=\"modifieJoueur();\">Enregistrer les modifications</DIV>");	
				}
				else
				{
					print ("<DIV class=\"champ_a_cliquer\" onmouseover=\"this.style.cursor='pointer'\" onclick=\"creeJoueur();\">Créer le joueur</DIV>");	
				}
				
				print ("<DIV id=\"joueur.creamodi.message\" class=\"texte\"></DIV>");
			}
			else
			{
				if ($joueur)
				{
					print ("<DIV id=\"joueur.creamodi.message\" class=\"messageErreur\">Vous n'avez pas les droits pour éditer ce joueur</DIV>");
				}
				else
				{
					print ("<DIV id=\"joueur.creamodi.message\" class=\"messageErreur\">Vous n'avez pas les droits pour créer un nouveau joueur</DIV>");
				}
				
				print ("<DIV class=\"champ_a_cliquer\" onmouseover=\"this.style.cursor='pointer'\" onclick=\"retourJoueurs();\">Annuler</DIV>");	
			}
			
			?>
		</DIV>
	</DIV>
	
<?php

include_once 'pied_de_page.php';

?>
