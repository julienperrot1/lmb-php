<?php

include_once 'menu.php';

include_once ($RACINE . 'modele/Equipe.php');

?>

<SCRIPT src="ajax/equipe.creamodi.js" type="text/javascript"></SCRIPT>

<DIV class="corps">
	<DIV class="pleineLargeur">
		<DIV class="titre">
			Création / Edition d'une équipe
		</DIV>
	</DIV>
	
	<DIV class="pleineLargeur">
		<DIV class="texte">
			<?php
			
			$equipe = null;
			$equipe_nom = "";
			$equipe_couleur_base = "000000";
			$equipe_photo = null;
			if (isset($_GET["id"]))
			{
				$equipe = Equipe::recup($_GET["id"]);
				$equipe_nom = $equipe->get("nom");
				$equipe_couleur_base = $equipe->get("couleur_base");
				$equipe_photo = $equipe->get("photo");
			}
			
			if (isset($utilisateur_en_cours) && $utilisateur_en_cours->get("droits") >= 3)
			{
				print ("Nom de l'équipe : ");
				print ("<INPUT class=\"champTexte\" id=\"equipe.creamodi.nom\" type=\"text\" maxlength=\"255\" size=\"50\" value=\"" . $equipe_nom . "\">");	
				print ("<BR/>");

				print ("Couleur de base (format RRVVBB) : ");
				print ("<INPUT class=\"champTexte\" id=\"equipe.creamodi.couleur_base\" type=\"text\" maxlength=\"6\" size=\"10\" value=\"" . $equipe_couleur_base . "\" oninput=\"coloreChamp();\">");
				foreach ($COULEURS_BASE as $couleur)
				{
					print ("<DIV class=\"champ_a_cliquer_neutre\" style=\"min-width: 10px; min-height: 10px; background-color: #" . $couleur . ";\" onmouseover=\"this.style.cursor='pointer'\" onclick=\"changeCouleur('" . $couleur . "')\"></DIV>");
				}
				print ("<BR/>");
				
				print ("Photo : ");
				if ($equipe_photo && $equipe_photo != "")
				{
					print ("<IMG id=\"equipe.creamodi.photo\" class=\"image_grande\" src=\"" . $IMAGE_UPLOAD_DIR . "/" . $equipe_photo . "\"></IMG>");
				}
				else
				{
					print ("<IMG id=\"equipe.creamodi.photo\" class=\"image_grande\" src=\"images/equipe_default.jpg\"></IMG>");
				}
				print ("<INPUT id=\"equipe.creamodi.photo.fichier\" type=\"hidden\" value=\"" . $equipe_photo . "\">");
				
				?>
				
				<FORM action="utils/upload_photo.php" method="post" enctype="multipart/form-data" target="equipe.upload.iframe" onsubmit="demarreUpload();">
					<P id="equipe.upload.loading" hidden="true"><IMG src="images/loading.gif"/></P>
					<P id="equipe.upload.formulaire">
						<INPUT name="fichier" type="file" size="30"/>
						<INPUT type="submit" name="equipe.upload.formulaire.valide" value="Charger la photo" />
					 </P>

					 <IFRAME id="equipe.upload.iframe" name="equipe.upload.iframe" src="#" style="width:0;height:0;border:0px solid #fff;"></IFRAME>
				 </FORM>
				
				
				<?php
				
				print ("<BR/>");
				
				if ($equipe)
				{
					print ("<INPUT id=\"equipe.creamodi.id\" type=\"hidden\" value=\"" . $equipe->get("id") . "\">");
					print ("<DIV class=\"champ_a_cliquer\" onmouseover=\"this.style.cursor='pointer'\" onclick=\"modifieEquipe();\">Enregistrer les modifications</DIV>");	
				}
				else
				{
					print ("<DIV class=\"champ_a_cliquer\" onmouseover=\"this.style.cursor='pointer'\" onclick=\"creeEquipe();\">Créer l'équipe</DIV>");	
				}
				
				print ("<DIV class=\"champ_a_cliquer\" onmouseover=\"this.style.cursor='pointer'\" onclick=\"retourEquipes();\">Annuler</DIV>");	
								
				print ("<DIV id=\"equipe.creamodi.message\" class=\"texte\"></DIV>");
			}
			else
			{
				if ($equipe)
				{
					print ("<DIV id=\"equipe.creamodi.message\" class=\"messageErreur\">Vous n'avez pas les droits pour éditer une équipe</DIV>");
				}
				else
				{
					print ("<DIV id=\"equipe.creamodi.message\" class=\"messageErreur\">Vous n'avez pas les droits pour créer une nouvelle équipe</DIV>");
				}
				
				print ("<DIV class=\"champ_a_cliquer\" onmouseover=\"this.style.cursor='pointer'\" onclick=\"retourEquipes();\">Annuler</DIV>");	
			}
			
			?>
		</DIV>
	</DIV>
	
<?php

include_once 'pied_de_page.php';

?>

<!-- Chargement des diverses parties de la page -->
<SCRIPT type="text/javascript">coloreChamp();</SCRIPT>
