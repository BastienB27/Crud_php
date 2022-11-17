<?php
/* On insére le fichier connect.php */
require_once "connect.php";

/*  On défini les variables */
$titre = $auteur = $date = "";
$titre_err = $auteur_err = $date_err = "";

if($_SERVER["REQUEST_METHOD"] == "POST"){
    /* Validation Titre */
    $input_titre = trim($_POST["titre"]);
    if(empty($input_titre)){
        $titre_err = "Veillez entrez un Titre.";
    } elseif(!filter_var($input_titre, FILTER_VALIDATE_REGEXP, array("options"=>array("regexp"=>"/^[a-zA-Z\s]+$/")))){
        $titre_err = "Veillez entrez un titre valide.";
    } else{
        $titre = $input_titre;
    }
    
    /* Validation Auteur */
    $input_auteur = trim($_POST["auteur"]);
    if(empty($input_auteur)){
        $auteur_err = "Veillez entrez l'auteur.";     
    } else{
        $auteur = $input_auteur;
    }
    
    /* Validation date */
    $input_date = trim($_POST["date"]);
    if(empty($input_date)){
        $date_err = "Veillez entrez la date.";     
    } elseif(!ctype_digit($input_date)){
        $date_err = "Veillez entrez une date correcte.";
    } else{
        $age = $input_date;
    }

     /* verifiez les erreurs avant enregistrement */
        if(empty($titre_err) && empty($auteur_err) && empty($date_err)){
            /* Prepare an insert statement */
            $sql = "INSERT INTO livre(titre, auteur, date) VALUES (?, ?, ?)";
         
            if($stmt = mysqli_prepare($link, $sql)){
                mysqli_stmt_bind_param($stmt, "ssd", $param_titre, $param_auteur, $param_date);
            
                /* Set parameters */
                $param_titre = $titre;
                $param_auteur = $auteur;
                $param_date = $date;
            
                /* executer la requette */
                if(mysqli_stmt_execute($stmt)){
                    /* opération effectuée, retour */
                    header("location: list.php");
                    exit();
                } else{
                    echo "Oops! une erreur est survenue.";
                }
            }

            /* Close statement */
        mysqli_stmt_close($stmt);
    }
    
    /* Close connection */
    mysqli_close($link);
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Ajoutez un livre</title>
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
                    <h2 class="mt-5">Ajouter un livre</h2>
                    <p>Remplir le formulaire pour enregistrer votre livre dans la base de données !</p>


                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                        <div class="form-group">
                            <label>Titre</label>
                            <input type="text" name="titre" class="form-control <?php echo (!empty($titre_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $titre; ?>">
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
                            <span class="invalid-feedback"><?php echo $date_err;?></span>
                        </div>
                        <input type="submit" class="btn btn-primary" value="Enregistrer">
                        <a href="list.php" class="btn btn-secondary ml-2">Annuler</a>
                    </form>
                </div>
            </div>        
        </div>
    </div>
</body>
</html>