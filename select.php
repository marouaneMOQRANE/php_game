<?php session_start();?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
     <title>Jeu de devinette de nombres</title>
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
        input[type="text"], input[type="submit"] {
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
            color: #333;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <h1>Sélection du nombre de tentatives</h1>
    <form action="file2.php" method="post">
        <label for="nom">Entrez votre nom :</label><br>
        <input type="text" id="nom" name="nom"  required    value="<?php if(isset($_SESSION["nom"])) echo $_SESSION["nom"];?>"><br><br>
        <p>Choisissez le nombre de tentatives :</p>
        <input type="radio" id="tentaive20" name="numTentatives" value="20" <?php if(isset($_SESSION["TentativePourChecked"])){if($_SESSION["TentativePourChecked"] == 20) echo "checked";}?>>
        <label for="tentaive20">facile(20 tentatives)</label><br>
        <input type="radio" id="tentaive10" name="numTentatives" value="10" <?php if(isset($_SESSION["TentativePourChecked"])){if($_SESSION["TentativePourChecked"] == 10) echo "checked";}?> >
        <label for="tentaive10">moyen(10 tentatives)</label><br>
        <input type="radio" id="tentaive5" name="numTentatives" value="5" <?php if(isset($_SESSION["TentativePourChecked"])){if($_SESSION["TentativePourChecked"] == 5) echo "checked";}?> >
        <label for="tentaive5">Difficile(5 tentatives)</label><br>
        <br>
        <input type="submit" value="Commencer le jeu">
    </form>
</body>
</html>
