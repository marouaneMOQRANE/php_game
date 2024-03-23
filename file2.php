<?php


##################### redirection vers select.php si l'utilisatuer essaye d'acceder directement vers file2.php ################################################################
session_start();
if (!isset($_POST["nom"]) || !isset($_POST["numTentatives"])) {
   
    header("Location: select.php");
}
###############################################################################################################################





########################### fonction pour gener un nombre aleatoire ##############################################################
function NombreAleatoire() {
    $numbers = range(0, 9); //genere une sequence de 0123456789
    shuffle($numbers);   //change les positions des nombre de la sequence aleatoirement   
    $NombreAle = '';
    for ($i = 0; $i < 4; $i++) {
        $NombreAle .= $numbers[$i]; //on va affecter les 4 nombres aleatoire de la sequence au variable $NombreAle .
    }
    $_SESSION["NombreAle"] = $NombreAle; //Stockage du nombre aléatoire dans la session
}
###########################################################################################################################################################################################





############################# fonction pour comparer le nombre aleatoire avec le user input ########################################################################################################################
function ComparerNombres($NombreAle, $userInput) {
    $mort = 0;
    $blesse = 0;
    for ($i = 0; $i < 4; $i++) {
        if ($NombreAle[$i] == $userInput[$i]) {  //si il ont la meme valeur incremente mort 
            $mort++;
        } elseif (strpos($NombreAle, $userInput[$i]) !== false) { //si il ont la meme valeur maos pas la meme position incremente blsee
            $blesse++;
        }
    }
    $result=[$mort, $blesse];
    return $result;
}
########################################################################################################################################################################################




########################################################################################################################################################################################
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $_SESSION["nom"] = $_POST["nom"]; // Stockage du nom dans la session

    if (!isset($_SESSION["TentativesRestantes"])) {
        $_SESSION["TentativesRestantes"] = $_POST["numTentatives"]; // Initialisation du nombre de tentatives
        $_SESSION["TentativePourChecked"] = $_POST["numTentatives"]; // on va utiliser cette session dans select.php  pour garder la dernier difficulté saisie par l'utilisatuer car l'autre session $_SESSION["TentativesRestantes"]  va étre vider si l'utilisatur gagne ou perd  
        NombreAleatoire(); // Génération du nombre aléatoire
        $_SESSION["TentativesHistory"] = array(); // Initialisation de l'historique des tentatives 
    }

    $NombreAle = $_SESSION["NombreAle"];
    //echo $NombreAle;   // si vous vouler tester 
    if (isset($_POST['userInput'])){  //teste la saise de l'ultilisateur si le nombre est de 4 digit different
        $userInput = $_POST["userInput"];
        if (!is_numeric($userInput) || strlen($userInput) != 4 || count(array_unique(str_split($userInput))) != 4) { 
            echo "<script>alert('Veuillez entrer un nombre valide de 4 chiffres sans répétition.');</script>"; // affiche un Alerte JavaScript si la saisie est fausse
        }else {
            $result = ComparerNombres($NombreAle, $userInput); //compare la saise d'utilisateur avec le nombre aleatoire
            array_push($_SESSION["TentativesHistory"], [$userInput, $result]); // Enregistrement de la tentative  et le resultat de comparaison mort / blesse dans l'historique
            if ($result[0] == 4) {   // si on a 4 morts on va reinitialiser les données de ses 3 sessions
               unset($_SESSION['NombreAle']);
               unset($_SESSION['TentativesRestantes']);
               unset($_SESSION['TentativesHistory']);// Réinitialisation des données de la session
            }else {
                    $_SESSION["TentativesRestantes"]--; // si y'as pas 4 morts  on va decmentater le nombre de tentatives restantes
        }
    }
        
    }
}
########################################################################################################################################################################################
?>







<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jeu de devinette de nombres</title>
<!-- ####################################################### CSS ################################################################### -->

    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f0f0f0;
            text-align: center;
        }

        h1 {
            color: #333;
        }

        form {
            background-color: #fff;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            padding: 20px;
            max-width: 400px;
            margin: 20px auto;
        }

        label {
            display: block;
            margin-bottom: 10px;
            color: #333;
        }

        input[type="text"],
        input[type="submit"] {
            margin-bottom: 10px;
            padding: 8px;
            width: calc(100% - 16px);
            border: 1px solid #ccc;
            border-radius: 3px;
            box-sizing: border-box;
        }

        input[type="submit"] {
            background-color: #4CAF50;
            color: white;
            border: none;
            cursor: pointer;
        }

        input[type="submit"]:hover {
            background-color: #45a049;
        }

        p {
            margin-bottom: 10px;
        }

        .success {
            color: green;
        }

        .failure {
            color: red;
        }

        .button {
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 3px;
            padding: 8px 16px;
            text-decoration: none;
            cursor: pointer;
        }

        .button:hover {
            background-color: #45a049;
        }
    </style>
    <!-- ######################################################################################################################################################################################################################################################## -->
</head>
<body>
    <h1>Jeu de devinette de nombres</h1>

    <?php if (isset($_SESSION["nom"])) {
        echo "<p>Bonjour " . $_SESSION["nom"] . "</p>";  //pour afficher le dernier nom saisie par l'utilisteur   
    } ?>










 <!-- ############################################### AFICHAGE ###################################################################################################################################################################################################### -->
 <?php ############################################################################################################################################################################################
 // dans cette partie on a 3 affichge >> 1) si l'utilistauer gagne ou  2)si l'utilisteur perd ou 3) ou si l'utilisateur est encore dans le jeu ?>





     <!-- dans ce cas l'utilisateur vas gagner si la session de nombre aleatoire $_SESSION["NombreAle"] est vide car dans la ligne '72' on a  vider la session si on a 4 morts  -->

    <?php if (!isset($_SESSION["NombreAle"])) {  ?> <!-- dans la ligne '72' on vider la session si on a 4 morts donc l'utilisateur va gagner  -->
        <p>felicitation vous avez trouver le nombre !!!!! </p>
        <a href="select.php"><button class="button">Rejouer</button></a>
        <?php  //réinitialisation des données de la session car l'utilisateur a gagné
            unset($_SESSION['NombreAle']); 
            unset($_SESSION['TentativesRestantes']);
            unset($_SESSION['TentativesHistory']);
             ?>
        




     <!-- dans ce cas l'utilisateur est encore dans le jeu tanque le nombre de tentative est different de 0  -->
    <?php } else if ($_SESSION["TentativesRestantes"] > 0) { ?> 
        <form method="post" action="file2.php">
            <label for="userInput">Entrez un nombre de 4 chiffres sans répétition :</label>
            <input type="text" id="userInput" name="userInput" maxlength="4" required
                value="<?php if (isset($_POST['userInput'])) echo $_POST['userInput']; ?>">
            <br>
            <input type="hidden" name="nom" value="<?php if (isset($_SESSION['nom'])) echo $_SESSION['nom']; ?>">
            <input type="hidden" name="numTentatives" value="<?php if (isset($_SESSION['TentativePourChecked'])) echo $_SESSION['TentativePourChecked']; ?>">
            <input type="submit" value="Valider">
        </form>
        <p>Tentatives restantes: <?php echo $_SESSION["TentativesRestantes"]; ?></p>
        <p>Historique des tentatives:</p>
      
            <?php foreach ($_SESSION["TentativesHistory"] as $attempt) { ?>
                <?php echo "{$attempt[0]} - {$attempt[1][0]} mort(s) et {$attempt[1][1]} blessé(s)"; ?><br>
            <?php } ?>




            
<!-- dans ce cas l'utilisateur a perdu  car le nombre de tentative est egale a 0 -->
    <?php } else { ?>
        <p>Désolé, vous n'avez pas réussi à deviner le nombre correctement. Le nombre correct était <?php echo $_SESSION["NombreAle"]; ?>.</p>
        <a href="select.php"><button class="button">Rejouer</button></a>
        <?php 
            unset($_SESSION['NombreAle']);
            unset($_SESSION['TentativesRestantes']);
            unset($_SESSION['TentativesHistory']);?>
    <?php } ?>


<!-- ############################################################################################################################ -->

</body>
</html>
