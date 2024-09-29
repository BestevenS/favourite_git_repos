<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GitHub Repo Search</title>
    <link rel="stylesheet" href="css/styles.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="js/app.js" defer></script>
</head>
<body>
    <h1>GitHub Repo Search</h1>

    <!-- Tab Interface -->
    <div class="tabs">
        <button data-tab="search-tab">Search Repos</button>
        <button data-tab="favourites-tab">Favourites</button>
    </div>

    <!-- Search Tab Content -->
    <div id="search-tab" class="tab-content active">
        <form id="github-form">
            <label for="github-url">GitHub Profile URL:</label>
            <input type="text" id="github-url" placeholder="Enter GitHub profile URL" required>
            <button type="submit">Search</button>
        </form>
        <div id="repos-list"></div>
    </div>

    <!-- Favourites Tab Content -->
    <div id="favourites-tab" class="tab-content">
        <h2>Favourite Repositories</h2>
        <div id="favourites-list">
            <!-- Αγαπημένα θα εμφανίζονται εδώ -->
        </div>
    </div>
</body>
</html>