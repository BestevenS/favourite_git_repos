<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GitHub Repo Search</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        .favourite {
            background-color: #fff8c6; /* Κιτρινωπό χρώμα για τα αποθηκευμένα favourites */
        }
        .tabs {
            margin-top: 20px;
            display: flex;
        }
        .tabs button {
            padding: 10px;
            margin-right: 10px;
            cursor: pointer;
        }
        .tab-content {
            display: none;
        }
        .tab-content.active {
            display: block;
        }
    </style>
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

    <script>
        // Tab functionality
        $('.tabs button').on('click', function() {
            const tabId = $(this).data('tab');
            $('.tab-content').removeClass('active');
            $('#' + tabId).addClass('active');
        });

        let favouritesUrls = [];  // Θα αποθηκεύσουμε εδώ τα URLs των αποθηκευμένων favourites

        // Φόρτωση των αποθηκευμένων favourites κατά την αρχική φόρτωση
        function loadFavourites() {
            $.ajax({
                url: 'http://localhost:8001/get_favourites.php',
                method: 'GET',
                success: function(favourites) {
                    let output = '';

                    if (favourites.length === 0) {
                        output = '<p>No favourites found. Save your favourite repositories by clicking the star!</p>';
                    } else {
                        favourites.forEach(function(fav) {
                            output += `<li>
                                        <strong>Owner:</strong> ${fav.owner.login} <br>
                                        <strong>Repository:</strong> ${fav.name} <br>
                                        <strong>URL:</strong> <a href="${fav.html_url}" target="_blank">${fav.html_url}</a> <br>
                                    </li><hr>`;
                        });
                    }

                    $('#favourites-list').html(output);
                },
                error: function() {
                    console.error('Failed to load favourites.');
                }
            });
        }

        $(document).ready(function() {
            loadFavourites();  // Φόρτωση των favourites κατά την αρχική φόρτωση

            // Αναζήτηση GitHub repositories
            $('#github-form').on('submit', function(e) {
                e.preventDefault();  // Αποφυγή ανανέωσης της σελίδας

                let githubUrl = $('#github-url').val().trim();  // Λήψη του GitHub URL
                let username = githubUrl.split('github.com/')[1];  // Εξαγωγή μόνο του username

                if (!username) {
                    console.error('Invalid GitHub URL');
                    return;
                }

                $.ajax({
                    url: 'http://localhost:8001/fetch_repos.php',  // Το back-end script που καλεί το API
                    method: 'POST',
                    data: { username: username },
                    success: function(response) {
                        let output = '<ul>';

                        response.forEach(repo => {
                            // Έλεγχος αν το repo υπάρχει στη λίστα των favourites
                            const isFavourite = favouritesUrls.includes(repo.html_url);
                            
                            // Αν το repo είναι favourite, προσθέτουμε την κλάση 'favourite'
                            output += `<li class="${isFavourite ? 'favourite' : ''}">
                                        <strong>Name:</strong> ${repo.name} <br>
                                        <strong>Owner:</strong> ${repo.owner.login} <br>  <!-- Διόρθωση εδώ -->
                                        <strong>Description:</strong> ${repo.description || 'No description'} <br>
                                        <strong>URL:</strong> <a href="${repo.html_url}" target="_blank">${repo.html_url}</a> <br>
                                        <button class="star" data-url="${repo.html_url}">★ Save to favourites</button>
                                    </li><hr>`;
                        });

                        output += '</ul>';
                        $('#repos-list').html(output);
                    },
                });
            });

            // AJAX call για την αποθήκευση των favourites
            $(document).on('click', '.star', function() {
                const repoUrl = $(this).data('url');
                const listItem = $(this).closest('li');  // Βρίσκουμε το στοιχείο "li" που περιέχει το repo

                $.ajax({
                    url: 'http://localhost:8001/save_favourite.php',
                    method: 'POST',
                    data: { repo_url: repoUrl },
                    success: function(response) {
                        if (response === 'success') {
                            alert('Saved to favourites');
                            listItem.addClass('favourite');
                            favouritesUrls.push(repoUrl);  // Προσθήκη του νέου favourite στη λίστα
                        } else if (response === 'deleted') {
                            alert('Removed from favourites');
                            listItem.removeClass('favourite');
                            favouritesUrls = favouritesUrls.filter(url => url !== repoUrl);  // Αφαίρεση του favourite από τη λίστα
                        }
                        loadFavourites();  // Ενημέρωση των favourites
                    },
                });
            });
        });
    </script>
</body>
</html>