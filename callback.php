<?php
require 'config.php';

session_start();  // Start the session at the top of the script

if (isset($_GET['code'])) {
    $code = $_GET['code'];

    // Exchange code for access token
    $token_url = "https://github.com/login/oauth/access_token";
    $post_data = [
        'client_id' => $clientId,
        'client_secret' => $clientSecret,
        'code' => $code
    ];

    $options = [
        'http' => [
            'header' => "Accept: application/json\r\n" . 
                        "Content-Type: application/x-www-form-urlencoded\r\n", // Specify content type
            'method' => 'POST',
            'content' => http_build_query($post_data),
        ]
    ];
    $context = stream_context_create($options);
    $response = file_get_contents($token_url, false, $context);
    $token_data = json_decode($response, true);

    if (isset($token_data['access_token'])) {
        $access_token = $token_data['access_token'];

        // Store access token in session
        $_SESSION['access_token'] = $access_token;  // Store token in session

        // Fetch user info
        $user_url = "https://api.github.com/user";
        $opts = [
            'http' => [
                'header' => "User-Agent: PHP\r\nAuthorization: token $access_token\r\n"
            ]
        ];
        $context = stream_context_create($opts);
        $user_data = file_get_contents($user_url, false, $context);
        $user = json_decode($user_data, true);

        // Store user data in session
        $_SESSION['user'] = $user;

        // Redirect to dashboard.php
        header("Location: dashboard.php");
        exit();
    } else {
        echo "Error getting access token.";
    }
} else {
    echo "No code received.";
}
?>
