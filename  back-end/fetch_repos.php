<?php
header("Access-Control-Allow-Origin: http://localhost:8000");  // Επέτρεψε αιτήματα από το front-end
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");    // Επέτρεψε τα POST και GET αιτήματα
header("Access-Control-Allow-Headers: Content-Type");          // Επέτρεψε headers όπως το Content-Type

if (isset($_POST['username'])) {
    $username = $_POST['username'];
    $url = "https://api.github.com/users/{$username}/repos";  // URL του GitHub API
    
    // Χρησιμοποίησε cURL για να καλέσεις το API
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0');  // User-agent για το GitHub API
    $response = curl_exec($ch);
    
    // Εμφάνιση οποιωνδήποτε cURL σφαλμάτων
    if ($response === false) {
        echo "cURL Error: " . curl_error($ch);
    } else {
        // Εκτύπωση της πλήρους απόκρισης από το GitHub API
        header('Content-Type: application/json');  // Ορίζει την απόκριση ως JSON
        echo $response;  // Τύπωσε την πλήρη απόκριση
    }
    
    curl_close($ch);
}
?>