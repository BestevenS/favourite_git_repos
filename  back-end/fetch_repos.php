<?php
// Συμπεριλαμβάνουμε τα headers από το headers.php
include 'headers.php';
include 'config.php';  // Ή χρησιμοποιούμε μεταβλητή περιβάλλοντος αν το token είναι αποθηκευμένο εκεί

if (isset($_POST['username'])) {
    $username = $_POST['username'];
    $url = "https://api.github.com/users/{$username}/repos";  // URL του GitHub API

    // Προετοιμασία του cURL request
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0');  // User-agent για το GitHub API
    
    // Προσθέτουμε το Authorization header για το token
    $headers = [
        "Authorization: token $github_token"  // Χρησιμοποιούμε το token για authorization
    ];
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

    // Εκτέλεση του cURL request
    $response = curl_exec($ch);
    
    // Έλεγχος για σφάλματα cURL
    if ($response === false) {
        echo "cURL Error: " . curl_error($ch);
    } else {
        // Εκτύπωση της απόκρισης από το GitHub API
        header('Content-Type: application/json');  // Ορίζει την απόκριση ως JSON
        echo $response;  // Επιστρέφουμε την απόκριση του GitHub API
    }

    curl_close($ch);
}
?>