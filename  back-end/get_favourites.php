<?php
include 'headers.php';
include 'config.php';  // Συμπεριλαμβάνουμε το αρχείο config για το GitHub token

// Σύνδεση με τη MySQL
$host = '127.0.0.1';
$dbname = 'github_repos';
$username = 'root';
$password = '';
$conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);

// Λήψη όλων των αποθηκευμένων repo_urls
$stmt = $conn->prepare("SELECT repo_url FROM favourites");
$stmt->execute();
$favourites = $stmt->fetchAll(PDO::FETCH_COLUMN);

$repos_data = [];

// Κάνουμε κλήση στο GitHub API
foreach ($favourites as $repo_url) {
    $api_url = str_replace('github.com', 'api.github.com/repos', $repo_url);
    $options = [
        "http" => [
            "header" => "User-Agent: My-App\r\nAuthorization: token $github_token\r\n"
        ]
    ];
    $context = stream_context_create($options);
    $repo_data = file_get_contents($api_url, false, $context);

    if ($repo_data) {
        $repos_data[] = json_decode($repo_data, true);
    }
}

header('Content-Type: application/json');
echo json_encode($repos_data);
?>