<?php
// Συμπεριλαμβάνουμε τα headers από το headers.php
include 'headers.php';

// Σύνδεση με τη MySQL βάση δεδομένων
$host = '127.0.0.1';
$dbname = 'github_repos';
$username = 'root';
$password = '';
$conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);

if (isset($_POST['repo_url'])) {
    $repo_url = $_POST['repo_url'];
    
    // Έλεγχος αν το URL υπάρχει ήδη στη βάση δεδομένων
    $stmt = $conn->prepare("SELECT COUNT(*) FROM favourites WHERE repo_url = :repo_url");
    $stmt->bindParam(':repo_url', $repo_url);
    $stmt->execute();
    $count = $stmt->fetchColumn();

    if ($count > 0) {
        // Αν το URL υπάρχει ήδη, διαγράφουμε το row
        $delete_stmt = $conn->prepare("DELETE FROM favourites WHERE repo_url = :repo_url");
        $delete_stmt->bindParam(':repo_url', $repo_url);
        if ($delete_stmt->execute()) {
            echo 'deleted';
        } else {
            echo 'error';
        }
    } else {
        // Αν το URL δεν υπάρχει, το προσθέτουμε στη βάση δεδομένων
        $insert_stmt = $conn->prepare("INSERT INTO favourites (repo_url) VALUES (:repo_url)");
        $insert_stmt->bindParam(':repo_url', $repo_url);
        if ($insert_stmt->execute()) {
            echo 'success';
        } else {
            echo 'error';
        }
    }
}
?>