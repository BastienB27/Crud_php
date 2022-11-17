<?php
/* Verifiez si le paramettre id existe */
if(isset($_GET["id"]) && !empty(trim($_GET["id"]))){
    
    require_once "connect.php";
    
    /* Preparer la requete */
    $sql = "SELECT * FROM livre WHERE id = ?";
    
    if($stmt = mysqli_prepare($link, $sql)){
        
        mysqli_stmt_bind_param($stmt, "i", $param_id);
        
        
        $param_id = trim($_GET["id"]);
        
        
        if(mysqli_stmt_execute($stmt)){
            $result = mysqli_stmt_get_result($stmt);
    
            if(mysqli_num_rows($result) == 1){
                /* recuperer le livre */
                $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
                
                /* recuperer les champs */
                $titre = $row["titre"];
                $auteur = $row["auteur"];
                $date = $row["date"];
            } else{
                /* Si pas de id correct retourne la page d'erreur */
                header("location: error.php");
                exit();
            }
            
        } else{
            echo "Oops! une erreur est survenue.";
        }
    }
     
    
    mysqli_stmt_close($stmt);
    
    
    mysqli_close($link);
} else{
    /* Si pas de id correct retourne la page d'erreur */
    header("location: error.php");
    exit();
}
?>


<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Information</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <style>
        .wrapper{
            width: 700px;
            margin: 0 auto;
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <h1 class="mt-5 mb-3">Informations de ce livre</h1>
                    <div class="form-group">
                        <label>Titre</label>
                        <p><b><?php echo $row["titre"]; ?></b></p>
                    </div>
                    <div class="form-group">
                        <label>Auteur</label>
                        <p><b><?php echo $row["auteur"]; ?></b></p>
                    </div>
                    <div class="form-group">
                        <label>Date</label>
                        <p><b><?php echo $row["date"]; ?></b> ans</p>
                    </div>
                    <p><a href="list.php" class="btn btn-primary">Retour</a></p>
                </div>
            </div>        
        </div>
    </div>
</body>
</html>