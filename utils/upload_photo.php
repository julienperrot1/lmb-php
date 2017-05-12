<?php

include_once ('../ajax/ajax_base.php');

$destination = $RACINE . $IMAGE_UPLOAD_DIR . "/" . basename( $_FILES['fichier']['name']);
$result = basename( $_FILES['fichier']['name']);

if (file_exists($destination))
{
    $result = 2;
}
else if(! @move_uploaded_file($_FILES['fichier']['tmp_name'], $destination))
{
	$result = 1;
}

sleep(1);

?>

<script language="javascript" type="text/javascript">window.top.window.termineUpload("<?php echo $result; ?>");</script> 