/*
 * "Planning Script"
 * Mattia Sinisi - https://snisni.it/about/me
 *
 * w/ jQuery & jQuery UI- https://jQuery.org
 */

$(function(){
	$(document).ready(function(){ // Quando il documento si è caricato...
		setDrag(); // Imposto oggetti trascinabili
	});

	function setDrag(){ // Dichiarazione di oggetti interagibili
		$(".draggable").draggable(); // Trascinabile

		$(".list").droppable({ // Area di rilascio, quando questo avviene...
			revert: true, // Se ci trascino sopra un'oggetto non accettabile lo riporta indietro
			revertDuration: 500, // Tempo (ms) in cui fa tornare indietro gli oggetti trascinati
			accept: ".item", // Cosa accettare
			over: function( event, UI ){ // Quando ci passo sopra...
				$(this).css({ // Modifica stile dell'area di rilascio
					"transition": "border 0.2s",
					"border": "5px solid var(--color-b)",
					"margin": "-5px"
				});
			},
			out: function( event, UI ){ // Quando smetto di trascinare qualcosa sopra...
				this.style = ""; // Ripristina lo stile
			},
			drop: function( event, UI ){ // Quando rilascio qualcosa di accettabile...
				this.style = ""; // Ripristina lo stile

				var id = UI.draggable.attr("value"); // Salvo l'attributo "value" dell'oggetto trascinato
				var stato = Number($(this).attr("value")); // Salvo l'attributo "value" dell'area di rilascio
				change( stato, id ); // Effettuo il cambio di stato
			}
		});
	}

	var change = function( stato, id ){ // Cambio di stato
		if ( id == undefined ) var id = this.value; // Se l'ID non è specificato utilizzo l'attributo "value" dell'oggetto chiamante
		if ( typeof stato == "object" ) stato = stato.data; // Se lo stato è passato come oggetto lo ridefinisco come un suo parametro

		var request = "opr=change_status&stato=" + stato + "&id=" + id; // Dati della da fare allo script PHP
		var scriptUrl = "/scripts/plan.php"; // URL dello script PHP da utilizzare
		
		$.post( scriptUrl, request, function(data){ // Richiesta allo script PHP - dopo eseguo la seguente funzione...
			console.log("Success! Here's what you got: " + data); // Stampo nella console la risposta del server
			update(); // Aggiorno le liste
		}, "text" ); // Tipo di dato di ritorno dal server
	}
		
	function update(){ // Aggiornamento liste
		// Quando finisco di aggiornare una lista aggiorno la successiva, alla fine re-imposto gli oggetti interagibili
		$("#to-do-wrapper").load( document.URL + " #to-do", function( data ){
			$("#doing-wrapper").load( document.URL + " #doing", function( data ){
				$("#done-wrapper").load( document.URL + " #done", function( data ){
					setDrag();
				});
			});
		});
	}

	// Eventi legati ai bottoni di ogni promemoria - chiamo la funzione "change" senza specificare l'ID

	$("#main").on("click", ".act_start", 1, change);

	$("#main").on("click", ".act_interrupt", 0, change);

	$("#main").on("click", ".act_complete", 2, change);

	$("#main").on("click", ".act_remove", -1, change);

	$("#new-memo").on("submit", function(){ // Aggiunta di un promemoria
		var title = encodeURIComponent( $("#title").val() ); // Titolo codificato per la trasmissione
		var content = encodeURIComponent( $("#content").val() ); // Descrizione codificata per la trasmissione
		var date = (new Date( $("#deadline").val() ).getTime()) / 1000; // Data inserita diviso mille, gli ultimi 3 valori non servono e superano il massimo di memoria della casella di tipo "INT" di SQL

		var request = "opr=add&title=" + title + "&content=" + content + "&date=" + date; // Dati della da fare allo script PHP
		var scriptUrl = "/scripts/plan.php"; // URL dello script PHP da utilizzare
		
		$.post( scriptUrl, request, function(data){ // Richiesta allo script PHP - dopo eseguo la seguente funzione...
			console.log("Success! Here's what you got: " + data); // Stampo nella console la risposta del server
			update(); // Aggiorno le liste
			$(this).find('').val(''); // Svuoto tutti gli input di questo form
		}, "text" ); // Tipo di dato di ritorno dal server

		return false; // Non ricarico all'invio del form
	});
});