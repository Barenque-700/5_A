
    if(isset($_POST['utente']) && isset($_POST['password']) && isset($_POST['nome']) && isset($_POST['cognome']) && isset($_POST['data'])) {
        $utente = $_POST['utente'];
        $passwordUtente= $_POST['password'];
        $nome= $_POST['nome'];
        $cognome= $_POST['cognome'];
        $data= $_POST['data'];
    try {
        $connessione = new PDO("mysql:host=$host;dbname=$db", $user, $password);
        $sql = "INSERT INTO utenti(Nome, Cognome, NomeUtente, DataNascita, Password) VALUES (?,?,?,?,?)";
        $preparata = $connessione->prepare($sql);
        if ( $preparata->execute([$nome,$cognome,$utente,$data,$passwordUtente]) ) {
            header("Location: Index.php");
            exit;  
        }
        $connessione = null;
    } catch(PDOException $e){
        die("Errore nella gestione del database $db: " . $e->getMessage());
    }
}