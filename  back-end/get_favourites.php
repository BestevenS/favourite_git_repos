<?php
header("Access-Control-Allow-Origin: http://localhost:8000");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

// Σύνδεση με τη MySQL
$host = '127.0.0.1';
$dbname = 'github_repos';
$username = 'root';
$password = '';
$conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);

// Λήψη όλων των αποθηκευμένων repo_urls
$stmt = $conn->prepare("SELECT repo_url FROM favourites");
$stmt->execute();
$favourites = $stmt->fetchAll(PDO::FETCH_COLUMN);  // Παίρνουμε τα URLs των favourites

$repos_data = [];  // Αποθηκεύει τα δεδομένα από το GitHub API για κάθε repo

// Για κάθε αποθηκευμένο repo_url κάνουμε κλήση στο GitHub API
foreach ($favourites as $repo_url) {
    // Μετατροπή του GitHub URL σε API URL
    $api_url = str_replace('github.com', 'api.github.com/repos', $repo_url);
    $options = [
        "http" => [
            "header" => "User-Agent: My-App\r\n"  // GitHub απαιτεί User-Agent header
        ]
    ];
    $context = stream_context_create($options);
    $repo_data = file_get_contents($api_url, false, $context);

    // Αποθήκευση των δεδομένων του repo στο array
    $repos_data[] = json_decode($repo_data, true);
}

// Επιστροφή των δεδομένων σε μορφή JSON
header('Content-Type: application/json');
echo json_encode($repos_data);
?>