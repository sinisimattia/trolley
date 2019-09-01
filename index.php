<?php
	//session_start();

	$total = 0;

	function getMemos( $link, $status ){
		$query = "SELECT * FROM memos WHERE stato = ".$status." ORDER BY scadenza";

		$result = mysqli_query($link, $query);

		if ( $result ) {
			$count = 0;

			while ( $row = mysqli_fetch_array($result) ){
				$titolo = $row["titolo"];
				$scadenza = date( "d/M/Y", $row["scadenza"]);
				$descrizione = $row["descrizione"];

				echo '<div value="'.$row["id"].'" class="group round item draggable" draggable="true"><a class="title">'.$titolo.'</a><br><a class="info">entro il '.$scadenza.'</a><br><a class="desc">'.$descrizione.'</a>';

				switch ( $status ){
					case (0):
						echo'<div id="actions"><button class="button act_start" value="'.$row["id"].'">Inizia</button><button class="button act_remove" value="'.$row["id"].'">Rimuovi</button></div>';
					break;

					case (1):
						echo'<div id="actions"><button class="button act_complete" value="'.$row["id"].'">Completa</button><button class="button act_interrupt" value="'.$row["id"].'">Interrompi</button></div>';
					break;

					case (2):
						echo'<div id="actions"><button class="button act_remove" value="'.$row["id"].'">Rimuovi</button></div>';
					break;
				}

				echo '</div>';

				$count++;
			}

			if ( !$count ) echo "<a class='min-txt'>Nulla...</a>";
		}

		else
			echo '<a style="color:Red">Errore durante la connessione al database.</a>';
	}

	$dbserver = "localhost";
	$dbname = "2do";
	$dbuser = "root";
	$dbpassword = "";

	$link = mysqli_connect($dbserver, $dbuser, $dbpassword);
	mysqli_select_db($link, $dbname);

	mysqli_set_charset($link, "utf-8");
?>
<!DOCTYPE html>

<html lang="it-IT">

<head>
	<title>Trolley</title>
	<link href="img/calendar.png" rel="icon">

	<link href="style/css/simple.css" rel="stylesheet">
	<meta charset="UTF-8">

	<script src="scripts/jQuery.js" rel="text/javascript"></script>
	<script src="scripts/jUI/jquery-ui.js" rel="text/javascript"></script>
	<script src="scripts/plan.js" rel="text/javascript" id="plan-script"></script>
</head>

<body>
	<noscript>
		<div class="group">
			<a>Questo sito utilizza degli script per funzionare, disattiva il blocco o cambia browser.</a>
		</div>
	</noscript>

	<div id="header" class="group">
		<a class="title" href="/">Trolley</a>
		<br>
		<a><?php echo date("[ D d/M/Y ]") ?></a>
	</div>

	<div class="group round" id="new-memo-wrapper">
		<form id="new-memo" method="POST">
			<p>Aggiungi un promemoria</p>

			<input type="text" id="title" name="title" placeholder="Titolo" required>
			<br>
			<textarea id="content" name="content" placeholder="Contenuto"></textarea>
			<br>
			<input type="date" id="deadline" name="deadline" placeholder="Scadenza">
			<br>
			<button class="button" type="submit">Aggiungi</button>
		</form>
	</div>

	<hr>

	<div id="main">
		<div id="doing-wrapper" class="min-group list round" value="1">
			<div id="doing">
				<a>In corso</a>

				<hr style="border: 0.5px solid orange">

				<?php getMemos( $link, 1 ); ?>
			</div>
		</div>

		<div id="to-do-wrapper" class="min-group list round" value="0">
			<div id="to-do">
				<a>Da fare</a>

				<hr style="border: 0.5px solid red">

				<?php getMemos( $link, 0 ); ?>
			</div>
		</div>

		<div id="done-wrapper" class="min-group list round" value="2">
			<div id="done">
				<a>Fatto</a>

				<hr style="border: 0.5px solid green">

				<?php getMemos( $link, 2 ); ?>
			</div>
		</div>
	</div>
</body>
<?php mysqli_close($link) ?>
</html>