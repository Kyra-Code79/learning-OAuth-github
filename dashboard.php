<?php
session_start();  // Start the session to access user data

// Check if the user is logged in
if (!isset($_SESSION['access_token'])) {
    echo "Access token is missing.";
    exit();
}

$access_token = $_SESSION['access_token'];

if (!isset($_SESSION['user'])) {
    header("Location: callback.php");
    exit();
}

$user = $_SESSION['user'];  // Retrieve user data from session

// Function to make an authenticated API request
function fetchGitHubData($url, $access_token) {
    $options = [
        'http' => [
            'method' => 'GET',
            'header' => [
                "User-Agent: PHP",
                "Authorization: token $access_token"
            ]
        ]
    ];
    $context = stream_context_create($options);
    $data = file_get_contents($url, false, $context);
    return json_decode($data, true);
}

// Fetch repositories
$repos_url = "https://api.github.com/user/repos?sort=updated&per_page=10"; // Get 10 most recently updated repos
$repos = fetchGitHubData($repos_url, $access_token);

// Fetch followers
$followers_url = "https://api.github.com/users/{$user['login']}/followers?per_page=10";
$followers = fetchGitHubData($followers_url, $access_token);

// Fetch following
$following_url = "https://api.github.com/users/{$user['login']}/following?per_page=10";
$following = fetchGitHubData($following_url, $access_token);

// Fetch user stats
$user_url = "https://api.github.com/users/{$user['login']}";
$user_details = fetchGitHubData($user_url, $access_token);

// Count repositories by language
$language_count = [];
if (!isset($repos['message'])) {
    foreach ($repos as $repo) {
        if (!empty($repo['language'])) {
            if (!isset($language_count[$repo['language']])) {
                $language_count[$repo['language']] = 0;
            }
            $language_count[$repo['language']]++;
        }
    }
    arsort($language_count);
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GitHub Dashboard | <?= htmlspecialchars($user['login']) ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
</head>
<body class="bg-gray-50 text-gray-800">
    <nav class="bg-gray-800 text-white p-4 shadow">
        <div class="container mx-auto flex justify-between items-center">
            <div class="flex items-center space-x-2">
                <i class="fab fa-github text-2xl"></i>
                <span class="font-bold text-xl">GitHub Dashboard</span>
            </div>
            <div class="flex items-center space-x-4">
                <span><?= htmlspecialchars($user['login']) ?></span>
                <a href="logout.php" class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded text-sm transition">Sign Out</a>
            </div>
        </div>
    </nav>
    
    <main class="container mx-auto p-6">
        <!-- Profile Overview -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden mb-8">
            <div class="p-6 md:p-8 flex flex-col md:flex-row gap-6">
                <div class="flex-shrink-0">
                    <img src="<?= $user['avatar_url'] ?>" alt="Profile" class="w-32 h-32 md:w-40 md:h-40 rounded-lg shadow">
                </div>
                <div class="flex-grow">
                    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-4">
                        <div>
                            <h1 class="text-3xl font-bold text-gray-800"><?= htmlspecialchars($user['name'] ?? $user['login']) ?></h1>
                            <p class="text-gray-500 text-lg">@<?= htmlspecialchars($user['login']) ?></p>
                        </div>
                        <a href="<?= htmlspecialchars($user['html_url']) ?>" target="_blank" 
                           class="bg-gray-800 hover:bg-gray-700 text-white px-4 py-2 rounded flex items-center gap-2 transition w-fit">
                            <i class="fas fa-external-link-alt"></i> View GitHub Profile
                        </a>
                    </div>
                    
                    <p class="text-gray-600 mb-6"><?= htmlspecialchars($user['bio'] ?? 'No bio available') ?></p>
                    
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                        <div class="bg-gray-100 p-4 rounded text-center">
                            <div class="text-2xl font-bold"><?= number_format($user_details['public_repos'] ?? 0) ?></div>
                            <div class="text-sm text-gray-500">Repositories</div>
                        </div>
                        <div class="bg-gray-100 p-4 rounded text-center">
                            <div class="text-2xl font-bold"><?= number_format($user_details['followers'] ?? 0) ?></div>
                            <div class="text-sm text-gray-500">Followers</div>
                        </div>
                        <div class="bg-gray-100 p-4 rounded text-center">
                            <div class="text-2xl font-bold"><?= number_format($user_details['following'] ?? 0) ?></div>
                            <div class="text-sm text-gray-500">Following</div>
                        </div>
                        <div class="bg-gray-100 p-4 rounded text-center">
                            <div class="text-2xl font-bold"><?= $user_details['location'] ?? 'N/A' ?></div>
                            <div class="text-sm text-gray-500">Location</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Left column: Repositories -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-lg shadow-md overflow-hidden">
                    <div class="border-b border-gray-200 p-4 flex justify-between items-center">
                        <h2 class="text-xl font-semibold flex items-center gap-2">
                            <i class="far fa-folder text-yellow-500"></i> Recent Repositories
                        </h2>
                        <a href="<?= htmlspecialchars($user['html_url']) ?>?tab=repositories" target="_blank" 
                           class="text-blue-500 hover:underline text-sm">View All</a>
                    </div>
                    <div class="divide-y divide-gray-100">
                        <?php
                        if (isset($repos['message'])) {
                            echo "<div class='p-6 text-red-500'>{$repos['message']}</div>";
                        } else {
                            foreach ($repos as $repo) {
                                $updated_date = new DateTime($repo['updated_at']);
                                $now = new DateTime();
                                $interval = $updated_date->diff($now);
                                
                                if ($interval->days == 0) {
                                    $time_ago = "Today";
                                } elseif ($interval->days == 1) {
                                    $time_ago = "Yesterday";
                                } else {
                                    $time_ago = $interval->days . " days ago";
                                }
                                
                                echo "<div class='p-4 hover:bg-gray-50 transition'>";
                                echo "<div class='flex justify-between items-start mb-2'>";
                                echo "<a href='{$repo['html_url']}' target='_blank' class='text-blue-600 hover:underline font-medium'>{$repo['name']}</a>";
                                echo "<span class='text-xs text-gray-500'>Updated {$time_ago}</span>";
                                echo "</div>";
                                
                                echo "<p class='text-gray-600 text-sm mb-3'>" . htmlspecialchars($repo['description'] ?? 'No description available') . "</p>";
                                
                                echo "<div class='flex items-center gap-4 text-sm'>";
                                if (!empty($repo['language'])) {
                                    echo "<span class='flex items-center gap-1'>";
                                    echo "<span class='w-3 h-3 rounded-full bg-blue-500'></span>";
                                    echo htmlspecialchars($repo['language']);
                                    echo "</span>";
                                }
                                
                                echo "<span class='flex items-center gap-1'>";
                                echo "<i class='far fa-star'></i> " . number_format($repo['stargazers_count']);
                                echo "</span>";
                                
                                echo "<span class='flex items-center gap-1'>";
                                echo "<i class='fas fa-code-branch'></i> " . number_format($repo['forks_count']);
                                echo "</span>";
                                
                                echo "</div>";
                                echo "</div>";
                            }
                        }
                        ?>
                    </div>
                </div>
            </div>
            
            <!-- Right column: Language Stats, Followers, Following -->
            <div class="space-y-8">
                <!-- Language Stats -->
                <div class="bg-white rounded-lg shadow-md overflow-hidden">
                    <div class="border-b border-gray-200 p-4">
                        <h2 class="text-xl font-semibold flex items-center gap-2">
                            <i class="fas fa-code text-green-500"></i> Top Languages
                        </h2>
                    </div>
                    <div class="p-4">
                        <?php
                        if (empty($language_count)) {
                            echo "<p class='text-gray-500'>No language data available</p>";
                        } else {
                            foreach (array_slice($language_count, 0, 5) as $language => $count) {
                                $percentage = round(($count / array_sum($language_count)) * 100);
                                echo "<div class='mb-3'>";
                                echo "<div class='flex justify-between mb-1'>";
                                echo "<span>" . htmlspecialchars($language) . "</span>";
                                echo "<span class='text-gray-500'>{$percentage}%</span>";
                                echo "</div>";
                                echo "<div class='w-full bg-gray-200 rounded-full h-2'>";
                                echo "<div class='bg-blue-500 h-2 rounded-full' style='width: {$percentage}%'></div>";
                                echo "</div>";
                                echo "</div>";
                            }
                        }
                        ?>
                    </div>
                </div>
                
                <!-- Followers -->
                <div class="bg-white rounded-lg shadow-md overflow-hidden">
                    <div class="border-b border-gray-200 p-4 flex justify-between items-center">
                        <h2 class="text-xl font-semibold flex items-center gap-2">
                            <i class="fas fa-users text-purple-500"></i> Followers
                        </h2>
                        <span class="bg-gray-200 text-gray-800 text-xs px-2 py-1 rounded-full">
                            <?= number_format($user_details['followers'] ?? 0) ?>
                        </span>
                    </div>
                    <div class="p-4">
                        <?php
                        if (isset($followers['message'])) {
                            echo "<p class='text-red-500'>{$followers['message']}</p>";
                        } elseif (empty($followers)) {
                            echo "<p class='text-gray-500'>No followers yet</p>";
                        } else {
                            echo "<div class='grid grid-cols-3 gap-2'>";
                            foreach ($followers as $follower) {
                                echo "<a href='{$follower['html_url']}' target='_blank' 
                                     class='flex flex-col items-center p-2 hover:bg-gray-50 rounded transition'>";
                                echo "<img src='{$follower['avatar_url']}' alt='{$follower['login']}' 
                                     class='w-12 h-12 rounded-full mb-1'>";
                                echo "<span class='text-xs text-center overflow-hidden text-ellipsis w-full' 
                                     title='{$follower['login']}'>" . htmlspecialchars($follower['login']) . "</span>";
                                echo "</a>";
                            }
                            echo "</div>";
                            
                            if (count($followers) < ($user_details['followers'] ?? 0)) {
                                echo "<a href='{$user['html_url']}?tab=followers' target='_blank' 
                                     class='block text-center text-blue-500 hover:underline text-sm mt-4'>
                                     View all followers</a>";
                            }
                        }
                        ?>
                    </div>
                </div>
                
                <!-- Following -->
                <div class="bg-white rounded-lg shadow-md overflow-hidden">
                    <div class="border-b border-gray-200 p-4 flex justify-between items-center">
                        <h2 class="text-xl font-semibold flex items-center gap-2">
                            <i class="fas fa-user-plus text-blue-500"></i> Following
                        </h2>
                        <span class="bg-gray-200 text-gray-800 text-xs px-2 py-1 rounded-full">
                            <?= number_format($user_details['following'] ?? 0) ?>
                        </span>
                    </div>
                    <div class="p-4">
                        <?php
                        if (isset($following['message'])) {
                            echo "<p class='text-red-500'>{$following['message']}</p>";
                        } elseif (empty($following)) {
                            echo "<p class='text-gray-500'>Not following anyone yet</p>";
                        } else {
                            echo "<div class='grid grid-cols-3 gap-2'>";
                            foreach ($following as $follow) {
                                echo "<a href='{$follow['html_url']}' target='_blank' 
                                     class='flex flex-col items-center p-2 hover:bg-gray-50 rounded transition'>";
                                echo "<img src='{$follow['avatar_url']}' alt='{$follow['login']}' 
                                     class='w-12 h-12 rounded-full mb-1'>";
                                echo "<span class='text-xs text-center overflow-hidden text-ellipsis w-full' 
                                     title='{$follow['login']}'>" . htmlspecialchars($follow['login']) . "</span>";
                                echo "</a>";
                            }
                            echo "</div>";
                            
                            if (count($following) < ($user_details['following'] ?? 0)) {
                                echo "<a href='{$user['html_url']}?tab=following' target='_blank' 
                                     class='block text-center text-blue-500 hover:underline text-sm mt-4'>
                                     View all following</a>";
                            }
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </main>
    
    <footer class="bg-gray-100 p-6 mt-12">
        <div class="container mx-auto text-center text-gray-500 text-sm">
            &copy; <?= date('Y') ?> GitHub Dashboard | Not affiliated with GitHub, Inc.
        </div>
    </footer>
</body>
</html>