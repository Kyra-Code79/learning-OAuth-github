<?php 
require 'config.php';
$github_auth_url = "https://github.com/login/oauth/authorize?client_id=" . $clientId . "&redirect_uri=" . urlencode(GITHUB_REDIRECT_URL) . "&scope=user,repo";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GitHub Dashboard | Login</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
</head>

<body class="bg-gray-50 min-h-screen flex flex-col">
    <!-- Header -->
    <header class="bg-gray-800 text-white py-4">
        <div class="container mx-auto px-6 flex items-center justify-center">
            <div class="flex items-center space-x-2">
                <i class="fab fa-github text-3xl"></i>
                <h1 class="text-2xl font-bold">GitHub Dashboard</h1>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="flex-grow flex items-center justify-center py-12 px-4">
        <div class="max-w-md w-full space-y-8">
            <!-- Card -->
            <div class="bg-white shadow-xl rounded-lg overflow-hidden">
                <!-- Card Header -->
                <div class="bg-gray-100 px-6 py-4 border-b border-gray-200">
                    <h2 class="text-xl font-semibold text-gray-800 text-center">Welcome to GitHub Dashboard</h2>
                </div>
                
                <!-- Card Body -->
                <div class="p-6">
                    <!-- Login Options -->
                    <div class="space-y-6">
                        <!-- GitHub Login Button -->
                        <a href="<?= $github_auth_url ?>" 
                           class="flex items-center justify-center w-full bg-gray-800 hover:bg-gray-700 text-white py-3 px-4 rounded-md transition duration-200 ease-in-out transform hover:-translate-y-1">
                            <i class="fab fa-github text-xl mr-3"></i>
                            <span class="font-medium">Continue with GitHub</span>
                        </a>

                        <!-- Divider -->
                        <div class="relative">
                            <div class="absolute inset-0 flex items-center">
                                <div class="w-full border-t border-gray-300"></div>
                            </div>
                            <div class="relative flex justify-center text-sm">
                                <span class="px-2 bg-white text-gray-500">Or login with credentials</span>
                            </div>
                        </div>
                        
                        <!-- Traditional Login Form -->
                        <form action="login.php" method="POST" class="space-y-4">
                            <div>
                                <label for="username" class="block text-sm font-medium text-gray-700 mb-1">Username</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <i class="fas fa-user text-gray-400"></i>
                                    </div>
                                    <input type="text" id="username" name="username" required 
                                           class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                </div>
                            </div>
                            
                            <div>
                                <label for="password" class="block text-sm font-medium text-gray-700 mb-1">
                                    Password
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <i class="fas fa-lock text-gray-400"></i>
                                    </div>
                                    <input type="password" id="password" name="password" required 
                                           class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                </div>
                            </div>
                            
                            <div class="flex items-center justify-between">
                                <div class="flex items-center">
                                    <input id="remember_me" name="remember_me" type="checkbox" 
                                           class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                    <label for="remember_me" class="ml-2 block text-sm text-gray-700">
                                        Remember me
                                    </label>
                                </div>
                                
                                <div class="text-sm">
                                    <a href="#" class="font-medium text-blue-600 hover:text-blue-500">
                                        Forgot password?
                                    </a>
                                </div>
                            </div>
                            
                            <button type="submit" 
                                    class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                Sign in
                            </button>
                        </form>
                    </div>
                </div>
                
                <!-- Card Footer -->
                <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 text-center">
                    <p class="text-sm text-gray-600">Don't have an account? 
                        <a href="#" class="font-medium text-blue-600 hover:text-blue-500">Sign up</a>
                    </p>
                </div>
            </div>
            
            <!-- Features -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-center">
                <div class="bg-white p-4 rounded-lg shadow">
                    <div class="flex items-center justify-center w-12 h-12 mx-auto bg-blue-100 rounded-full mb-3">
                        <i class="fas fa-code text-blue-600"></i>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900">Repository Access</h3>
                    <p class="mt-2 text-sm text-gray-500">Access all your public and private repositories</p>
                </div>
                
                <div class="bg-white p-4 rounded-lg shadow">
                    <div class="flex items-center justify-center w-12 h-12 mx-auto bg-green-100 rounded-full mb-3">
                        <i class="fas fa-chart-bar text-green-600"></i>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900">Analytics</h3>
                    <p class="mt-2 text-sm text-gray-500">Track your progress and contributions</p>
                </div>
                
                <div class="bg-white p-4 rounded-lg shadow">
                    <div class="flex items-center justify-center w-12 h-12 mx-auto bg-purple-100 rounded-full mb-3">
                        <i class="fas fa-users text-purple-600"></i>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900">Network</h3>
                    <p class="mt-2 text-sm text-gray-500">Connect with your followers and those you follow</p>
                </div>
            </div>
        </div>
    </main>
    
    <!-- Footer -->
    <footer class="bg-gray-100 py-4">
        <div class="container mx-auto px-6 text-center text-gray-500 text-sm">
            <p>&copy; <?= date('Y') ?> GitHub Dashboard | Not affiliated with GitHub, Inc.</p>
            <p class="mt-1">
                <a href="#" class="text-gray-600 hover:text-gray-800 mx-2">Privacy Policy</a>
                <a href="#" class="text-gray-600 hover:text-gray-800 mx-2">Terms of Service</a>
                <a href="#" class="text-gray-600 hover:text-gray-800 mx-2">Contact</a>
            </p>
        </div>
    </footer>
</body>
</html>