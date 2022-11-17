<?php
/* Inclure le fichier */
require_once "connect.php";
 
/* Definir les variables */
$titre = $auteur = $date = "";
$titre_err = $auteur_err = $date_err = "";
 
/* verifier la valeur id dans le post pour la mise à jour */
if(isset($_POST["id"]) && !empty($_POST["id"])){
    /* recuperation du champ caché */
    $id = $_POST["id"];
    
    /* Validate name */
    $input_titre = trim($_POST["titre"]);
    if(empty($input_titre)){
        $titre_err = "Veillez entrer un titre.";
    } elseif(!filter_var($input_titre, FILTER_VALIDATE_REGEXP, array("options"=>array("regexp"=>"/^[a-zA-Z\s]+$/")))){
        $titre_err = "Veillez entrer un titre valide.";
    } else{
        $titre = $input_titre;
    }
    
    /* Validate auteur */
    $input_auteur = trim($_POST["auteur"]);
    if(empty($input_auteur)){
        $auteur_err = "Veillez entrer un auteur.";     
    } else{
        $auteur = $input_auteur;
    }
    
    /* Validate date */
    $input_date = trim($_POST["date"]);
    if(empty($input_date)){
        $date_err = "Veillez entrer la date.";     
    } elseif(!ctype_digit($input_date)){
        $date_err = "Veillez entrer une valeur positive.";
    } else{
        $date = $input_date;
    }
    
    /* verifier les erreurs avant modification */
    if(empty($titre_err) && empty($auteur_err) && empty($date_err)){
        
        $sql = "UPDATE livre SET nom=?, ecole=?, age=? WHERE id=?";
         
        if($stmt = mysqli_prepare($link, $sql)){
            
            mysqli_stmt_bind_param($stmt, "sssi", $param_titre, $param_auteur, $param_date, $param_id);
            
           
            $param_titre = $titre;
            $param_auteur = $auteur;
            $param_date = $date;
            $param_id = $id;
            
            
            if(mysqli_stmt_execute($stmt)){
                /* enregistremnt modifié, retourne */
                header("location: list.php");
                exit();
            } else{
                echo "Oops! une erreur est survenue.";
            }
        }
         
        
        mysqli_stmt_close($stmt);
    }
    
    
    mysqli_close($link);
} else{
    /* si il existe un paramettre id */
    if(isset($_GET["id"]) && !empty(trim($_GET["id"]))){
        
        $id =  trim($_GET["id"]);
        
       
        $sql = "SELECT * FROM livre WHERE id = ?";


        if($stmt = mysqli_prepare($link, $sql)){
            
            mysqli_stmt_bind_param($stmt, "i", $param_id);
            
            
            $param_id = $id;
            
            
            if(mysqli_stmt_execute($stmt)){
                $result = mysqli_stmt_get_result($stmt);
    
                if(mysqli_num_rows($result) == 1){
                    /* recupere l'enregistremnt */
                    $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
                    
                    /* recupere les champs */
                    $titre = $row["titre"];
                    $auteur = $row["auteur"];
                    $date = $row["date"];
                } else{
                    
                    header("location: error.php");
                    exit();
                }
                
            } else{
                echo "Oops! une erreur est survenue.";
            }
        }
        
        /* Close statement */
        mysqli_stmt_close($stmt);
        
        /* Close connection */
        mysqli_close($link);
    }  else{
        /* pas de id parametter valid, retourne erreur */
        header("location: error.php");
        exit();
    }
}
?>
 
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Modifier le livre</title>
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
                    <h2 class="mt-5">Mise à jour du livre</h2>
                    <p>Modifier les champs et enregistrer</p>
                    <form action="<?php echo htmlspecialchars(basename($_SERVER['REQUEST_URI'])); ?>" method="post">
                        <div class="form-group">
                            <label>Titre</label>
                            <input type="text" name="nom" class="form-control <?php echo (!empty($titre_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $titre; ?>">
                            <span class="invalid-feedback"><?php echo $titre_err;?></span>
                        </div>
                        <div class="form-group">
                            <label>Auteur</label>
                            <textarea name="auteur" class="form-control <?php echo (!empty($auteur_err)) ? 'is-invalid' : ''; ?>"><?php echo $auteur; ?></textarea>
                            <span class="invalid-feedback"><?php echo $auteur_err;?></span>
                        </div>
                        <div class="form-group">
                            <label>Date</label>
                            <input type="number" name="date" class="form-control <?php echo (!empty($date_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $date; ?>">
                            <span class="invalid-feedback"><?php echo $age_err;?></span>
                        </div>
                        <input type="hidden" name="id" value="<?php echo $id; ?>"/>
                        <input type="submit" class="btn btn-primary" value="Enregistrer">
                        <a href="list.php" class="btn btn-secondary ml-2">Annuler</a>
                    </form>
                </div>
            </div>        
        </div>
    </div>
</body>
</html>