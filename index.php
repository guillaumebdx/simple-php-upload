<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Upload de plusieurs fichiers à la fois</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css"
          integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u"
          crossorigin="anonymous">

</head>
<body>
<?php
if (isset($_POST['delete'])) {
    $fileToDelete = $_POST['fileToDelete'];
    unlink($fileToDelete);
    echo "l'image a bien été supprimée";
}



if (isset($_POST['submit'])) {

    if (count($_FILES['upload']['name']) > 0) {
        for ($i = 0; $i < count($_FILES['upload']['name']); $i++) {
            $tmpFilePath = $_FILES['upload']['tmp_name'][$i];
            if ($tmpFilePath != "") {
                $shortname = $_FILES['upload']['name'][$i];
                $size = $_FILES['upload']['size'][$i];
                //$type = $_FILES['upload']['type'][$i];
                //infoFichier va récupérer les information du fichier dans un tableau associatif, on va récupérer la clé extension dans ce tableau
                $infoFichier = pathinfo($_FILES['upload']['name'][$i]);
                $extension = $infoFichier['extension'];
                $extensionsAllowed = array('jpg', 'gif', 'png');
                //on vérifie la taille avant de lancer l'upload
                if ($size <= 1000000) {
                    //maintenant on vérifie l'extension
                    if (in_array($extension, $extensionsAllowed)) {

                        $filePath = "uploaded/" . date('d-m-Y-H-i-s') . '-' . $_FILES['upload']['name'][$i];
                        //Upload the file into the temp dir
                        if (move_uploaded_file($tmpFilePath, $filePath)) {
                           // $files[] = $shortname;
                            $filePaths[] = $filePath;
                        }
                    } else {
                        echo "Votre fichier doit être un gif, png ou jpg";
                    }

                } else {
                    echo "Votre fichier est trop volumineux, il doit faire moins de 1 Mo";
                }

            }

        }
    }

    if (is_array(@$filePaths)) {
        echo "<form method=\"post\" target=\"\"> <div class=\"row\">";

        foreach ($filePaths as $file) {

            echo "<div class=\"col-xs-6 col-md-3\">
        <a href=\"$file\" class=\"thumbnail\">
            <img src='$file' alt=\"$file\">
        </a>
        <input type=\"hidden\" value=\"$file\" name=\"fileToDelete\">
        <input type=\"submit\" name=\"delete\" value=\"Supprimer cette image du serveur\">
    </div>";
        }
        echo "</div></form>";
    }
}
?>


<form action="" enctype="multipart/form-data" method="post">

    <div>
        <label for='upload'>Sélectionner un ou plusieurs fichiers :</label>
        <input id='upload' name="upload[]" type="file" multiple="multiple"/>
    </div>

    <p><input type="submit" name="submit" value="Envoyer"></p>

</form>

</body>
</html>