<?php

function notifyAsArray($notifications) {
	$new = array();
	if (isset($notifications) && trim($notifications) !== '') {
		$not = explode(",", $notifications);
		foreach ($not as $n) {
			list($code, $date, $state) = explode(" ", $n);
			array_push($new, array("code" => $code, "date" => $date, "state" => $state));
		}
	}
	return $new;
}

function randomString($length = 5) {
	$str = "";
	$characters = array_merge(range('A', 'Z'), range('a', 'z'), range('0', '9'));
	$max = count($characters) - 1;
	for ($i = 0; $i < $length; $i++) {
		$rand = mt_rand(0, $max);
		$str .= $characters[$rand];
	}
	return $str;
}

function getArray($res) {
	$array = array();
	if ($res->num_rows > 0) {
		while ($row = $res->fetch_assoc()) {
			if (count($row) > 1) {
				array_push($array, $row);
			} else {
				sort($row);
				array_push($array, $row[0]);
			}
		}
	}
	return $array;
}

function cartAsString($cart) {
	$cartString = '';
	foreach ($cart as $cartline) {
		$line = $cartline["code"] . ' ' . $cartline["qt"];
		if (strcmp($cartString, '') === 0) {
			$cartString = $line;
		} else {
			$cartString = $cartString . ',' . $line;
		}
	}
	return $cartString;
}

function cartAsArray($cart) {
	$newCart = array();
	if (isset($cart) && trim($cart) !== '') {
		$lines = explode(",", $cart);
		foreach ($lines as $line) {
			list($code, $qt) = explode(" ", $line);
			array_push($newCart, array("code" => $code, "qt" => $qt));
		}
	}
	return $newCart;
}

function sec_session_start() {
	$session_name = 'sec_session_id'; // Imposta un nome di sessione
	$secure = false; // Imposta il parametro a true se vuoi usare il protocollo 'https'.
	$httponly = true; // Questo impedirà ad un javascript di essere in grado di accedere all'id di sessione.
	ini_set('session.use_only_cookies', 1); // Forza la sessione ad utilizzare solo i cookie.
	$cookieParams = session_get_cookie_params(); // Legge i parametri correnti relativi ai cookie.
	session_set_cookie_params($cookieParams["lifetime"], $cookieParams["path"], $cookieParams["domain"], $secure, $httponly);
	session_name($session_name); // Imposta il nome di sessione con quello prescelto all'inizio della funzione.
	session_start(); // Avvia la sessione php.
	session_regenerate_id(); // Rigenera la sessione e cancella quella creata in precedenza.
}

function checkbrute($username, $conn) {
	// Recupero il timestamp
	$now = time();
	// Vengono analizzati tutti i tentativi di login a partire dagli ultimi 30 minuti.
	$valid_attempts = $now - (30 * 60);
	if ($stmt = $conn->prepare("SELECT time FROM log WHERE email = ? AND time > '$valid_attempts'")) {
		$stmt->bind_param('s', $username);
		// Eseguo la query creata.
		$stmt->execute();
		$stmt->store_result();
		// Verifico l'esistenza di più di 5 tentativi di login falliti.
		if ($stmt->num_rows > 5) {
			return true;
		} else {
			return false;
		}
	}
}

function sendMail($nameSender, $mailSender, $mailReceiver, $subject, $message) {
	$header = "MIME-Version: 1.0\r\n";
	$header .= "Content-type: text/html; charset=iso-8859-1\r\n";
	$header .= "From: " . $nameSender . " <" . $mailSender . "> \r\n";
	$header .= "Reply-to: [email]" . $mailSender . "[/email]\r\n";
	$oggetto = $subject;
	$destinatario = $mailReceiver;

	$messaggio =
		'<html>

  <head>
  <title>' . $subject . '</title>
  </head>

  <body>'
		. $message . '
  </body>
  </html>';

	mail($mailReceiver, $oggetto, $messaggio, $header);
}

function login($email, $password, $conn) {
	// Usando statement sql 'prepared' non sarà possibile attuare un attacco di tipo SQL injection.
	if ($stmt = $conn->prepare("SELECT email, password, privileges, shoppingCart, salt FROM user WHERE email = ? LIMIT 1")) {
		$stmt->bind_param('s', $email); // esegue il bind del parametro '$email'.
		$stmt->execute(); // esegue la query appena creata.
		$stmt->store_result();
		$stmt->bind_result($username, $db_password, $privileges, $cart, $salt); // recupera il risultato della query e lo memorizza nelle relative variabili.
		$stmt->fetch();
		$password = hash('sha512', $password . $salt); // codifica la password usando una chiave univoca.
		if ($stmt->num_rows == 1) {
			// se l'utente esiste
			// verifichiamo che non sia disabilitato in seguito all'esecuzione di troppi tentativi di accesso errati.
			if (checkbrute($username, $conn) == true) {
				// Account disabilitato
				// Invia un e-mail all'utente avvisandolo che il suo account è stato disabilitato.
				$msg = '
        <strong>
          <p>Il tuo account è stato bloccato in seguito a troppi tentativi di login errati.</p>
          <p>Per favore per dimostrare che non sei un sistema automatizzato mandaci un email motivazionale per sbloccare l\'account</p>
        </strong>';
				sendMail("InzenirDlaPida", "email", $username, 'Account disabilitato', $msg);
				$_SESSION['error'] = 2;
				return false;
			} else {
				if ($db_password == $password) {
					// Verifica che la password memorizzata nel database corrisponda alla password fornita dall'utente.
					// Password corretta!
					if (is_null($privileges)) {
						$_SESSION['error'] = 4;
						return false;
					} else {
						$user_browser = $_SERVER['HTTP_USER_AGENT']; // Recupero il parametro 'user-agent' relativo all'utente corrente.
						$_SESSION['username'] = $username;
						$_SESSION['login_string'] = hash('sha512', $password . $user_browser);
						$_SESSION['privileges'] = $privileges;
						if (strcmp($privileges, '1') == 0) {
							$_SESSION['targetPage'] = '../admin/order-list-admin.php';
						} else if (strcmp($privileges, '0') == 0) {
							$_SESSION['cart'] = cartAsArray($cart);
							$_SESSION['targetPage'] = '../index.php';
						}
						// Login eseguito con successo.
						return true;
					}
				} else {
					// Password incorretta.
					// Registriamo il tentativo fallito nel database.
					$_SESSION['error'] = 1;
					$now = time();
					$conn->query("INSERT INTO log (email, time) VALUES ('$username', '$now')");
					return false;
				}
			}
		} else {
			// L'utente inserito non esiste.
			return false;
		}
	}
}

function login_check($conn) {
	// Verifica che tutte le variabili di sessione siano impostate correttamente
	if (isset($_SESSION['username'], $_SESSION['login_string'])) {
		$login_string = $_SESSION['login_string'];
		$username = $_SESSION['username'];
		$user_browser = $_SERVER['HTTP_USER_AGENT']; // reperisce la stringa 'user-agent' dell'utente.
		if ($stmt = $conn->prepare("SELECT password FROM user WHERE email = ? LIMIT 1")) {
			$stmt->bind_param('s', $username); // esegue il bind del parametro '$user_id'.
			$stmt->execute(); // Esegue la query creata.
			$stmt->store_result();

			if ($stmt->num_rows == 1) {
				// se l'utente esiste
				$stmt->bind_result($password); // recupera le variabili dal risultato ottenuto.
				$stmt->fetch();
				$login_check = hash('sha512', $password . $user_browser);
				if ($login_check == $login_string) {
					// Login eseguito!!!!
					return true;
				} else {
					//  Login non eseguito
					return false;
				}
			} else {
				// Login non eseguito
				return false;
			}
		} else {
			// Login non eseguito
			return false;
		}
	} else {
		// Login non eseguito
		return false;
	}
}

?>
