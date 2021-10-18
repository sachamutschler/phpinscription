<?php
	session_start();
?>


<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>Forms</title>
	<link href="style.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-+0n0xVW2eSR5OomGNYDnhzAbDsOXxcvSN1TPprVMTNDbiYZCxYbOOl7+AMvyTG2x" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-gtEjrD/SeCtmISkJkNUaaKMoLD0//ElJ19smozuHV6z3Iehds+3Ulb9Bn9Plx0x4" crossorigin="anonymous"></script>

</head>
<body>

	<?php 
	//identifiant base de donnée
	$servername = "localhost";
	$username = "root";
	
//si on clique sur le bouton envoyer
if (isset($_POST['submit'])) {

	//On se connecte à la base de donnée 
	try
	{
		$db = new PDO("mysql:host=$servername;dbname=inscription",$username);
		$db ->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$users = new PDO("mysql:host=$servername;dbname=inscription",$username);
		$users ->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		echo "vous êtes bien connecté à la base de donnée <br>";
	}
	catch(PDOException $e)
	{
		echo "Erreur de la connexion : " .$e->getMessage();
		die();
	}

		$query = $db->prepare("
            INSERT INTO form(email, adresse, telephone)
            VALUES(:email, :adresse, :telephone)");
        $query->bindParam(':email',$email);
        $query->bindParam(':adresse',$adresse);
        $query->bindParam(':telephone',$telephone);

        $query1 = $users->prepare("
            INSERT INTO users(name, password)
            VALUES(:name, :password)");
        $query1->bindParam(':name',$name);
        $query1->bindParam(':password',$password);

		if (!empty($_POST)) {

		if (isset($_POST["name"], $_POST["email"], $_POST["password"], $_POST["adresse"], $_POST["telephone"])
		&& !empty($_POST["name"]) && !empty($_POST["email"]) && !empty($_POST["password"]) && !empty($_POST["adresse"]) && !empty($_POST["telephone"])
		){
			//Formatage PHP (supression des espaces par exemple)
			$name = strip_tags($_POST["name"]);
			$email = strip_tags($_POST["email"]);
			$password = strip_tags($_POST["password"]);
			$adresse = strip_tags($_POST["adresse"]);
			$telephone = strip_tags($_POST["telephone"]);
		}
		else {
			die("Le formulaire est incomplet");	
			}
		}

		$select_login = $db->prepare("SELECT name FROM users WHERE name='$name'");
                        $select_login->execute();

                        $select_email = $db->prepare("SELECT email FROM form WHERE email='$email'");
                        $select_email->execute();

                        if($select_login->rowCount() > 0 || $select_email->rowCount() > 0) {
                            exit('<div class="alert alert-danger" role="alert" style="width: 450px;">
                                    This username or email are already being used
                                </div>');
                        }

                        else{ 

                        	$query = $db->prepare("INSERT INTO form(email, adresse, telephone) VALUES(?, ?, ?)");
                        	$query->bindParam(1, $email);
                        	$query->bindParam(2, $adresse);
                        	$query->bindParam(3, $telephone,PDO::PARAM_INT);

                        	$query->execute();

							$query1 = $users->prepare("INSERT INTO users(name, password) VALUES(?, ?)");
                        	$query1->bindParam(1, $name);
                        	$query1->bindParam(2, $password);

                        	$query1->execute();
							session_start();
                        	echo "vous êtes bien inscrit";
							header("Location: /accueil.php");
							$_SESSION['name'] = $name;
							$_SESSION['email'] = $email;
							$_SESSION['telephone'] = $telephone;
							$_SESSION['adresse'] = $adresse;
                        }
		}
		else{
			echo("Bienvenue sur mon formulaire !");
		}

		
        

	?>
		
		<form action="forms.php" method="post" class="form-example">

	  		<div class="form-example">
	    		<label for="name">Enter your name: </label>
	    		<input type="text" name="name" id="name" class="form-control">
	  		</div><br>

	  		<div class="form-example">
	    		<label for="password">Enter your password: </label>
	    		<input type="password" name="password" id="password" class="form-control">
	  		</div><br>

	  		<div class="form-example">
	    		<label for="email">Enter your email: </label>
	    		<input type="email" name="email" id="email" class="form-control">
	  		</div><br>

	  		<div class="form-example">
	    		<label for="adresse">Enter your street: </label>
	    		<input type="text" name="adresse" id="adresse" class="form-control">
	  		</div><br>

	  		<div class="form-example">
	    		<label for="telephone">Enter your phone number: </label>
		    	<input type="tel" name="telephone" id="telephone" class="form-control">
	  		</div><br>

	  		<div class="form-example">
	    		<input type="submit"name="submit" value="Envoyer">
	  		</div><br>

		</form>

		
</body>
</html>