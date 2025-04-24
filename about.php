<?php
session_start();
require 'dbConnect.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us - Survey Portal</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        function toggleDarkMode() {
            document.documentElement.classList.toggle('dark');
            localStorage.setItem('theme', document.documentElement.classList.contains('dark') ? 'dark' : 'light');
        }

        window.onload = () => {
            if (localStorage.getItem('theme') === 'dark') {
                document.documentElement.classList.add('dark');
            }
        };
    </script>
</head>
<body class="bg-gray-100 dark:bg-gray-900 min-h-screen flex flex-col">
    <!-- Header -->
    <header class="bg-white dark:bg-gray-800 shadow-md">
        <div class="container mx-auto px-4 py-4 flex justify-between items-center">
            <h1 class="text-2xl font-bold text-gray-800 dark:text-white">Survey Portal</h1>
            <div class="flex items-center space-x-4">
                <a href="home.php" class="text-gray-600 dark:text-gray-300 hover:text-gray-800 dark:hover:text-white">Home</a>
                <button onclick="toggleDarkMode()" class="text-gray-600 dark:text-gray-300">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21.752 15.002A9.718 9.718 0 0112 21.75 9.75 9.75 0 0112 2.25c.35 0 .694.018 1.032.053a0.75 0.75 0 01.268 1.415 7.5 7.5 0 1010.49 10.49 0.75 0.75 0 01-1.415.268c-.035.338-.053.682-.053 1.032z" />
                    </svg>
                </button>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="flex-grow container mx-auto px-4 py-8">
        <div class="max-w-4xl mx-auto">
            <h2 class="text-3xl font-bold text-gray-800 dark:text-white mb-8">About Survey Portal</h2>
            
            <div class="space-y-8">
                <!-- Mission Section -->
                <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-md">
                    <h3 class="text-xl font-semibold text-gray-800 dark:text-white mb-4">Our Mission</h3>
                    <p class="text-gray-600 dark:text-gray-300">
                        At Survey Portal, our mission is to provide a simple, powerful, and secure platform for creating and managing surveys. We believe that gathering feedback and insights should be accessible to everyone, from small businesses to large organizations.
                    </p>
                </div>

                <!-- Features Section -->
                <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-md">
                    <h3 class="text-xl font-semibold text-gray-800 dark:text-white mb-4">What We Offer</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="flex items-start space-x-3">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 text-blue-500 mt-1">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <div>
                                <h4 class="font-medium text-gray-800 dark:text-white">Easy Survey Creation</h4>
                                <p class="text-gray-600 dark:text-gray-300">Create professional surveys in minutes with our intuitive interface.</p>
                            </div>
                        </div>
                        <div class="flex items-start space-x-3">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 text-blue-500 mt-1">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 013 19.875v-6.75zM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V8.625zM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V4.125z" />
                            </svg>
                            <div>
                                <h4 class="font-medium text-gray-800 dark:text-white">Analytics & Insights</h4>
                                <p class="text-gray-600 dark:text-gray-300">Get detailed analytics and insights from your survey responses.</p>
                            </div>
                        </div>
                        <div class="flex items-start space-x-3">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 text-blue-500 mt-1">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <div>
                                <h4 class="font-medium text-gray-800 dark:text-white">Secure & Private</h4>
                                <p class="text-gray-600 dark:text-gray-300">Your data is protected with enterprise-grade security measures.</p>
                            </div>
                        </div>
                        <div class="flex items-start space-x-3">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 text-blue-500 mt-1">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <div>
                                <h4 class="font-medium text-gray-800 dark:text-white">Responsive Design</h4>
                                <p class="text-gray-600 dark:text-gray-300">Surveys look great on any device, from desktop to mobile.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Team Section -->
                <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-md">
                    <h3 class="text-xl font-semibold text-gray-800 dark:text-white mb-4">Our Team</h3>
                    <p class="text-gray-600 dark:text-gray-300 mb-4">
                        We are a dedicated team of developers, designers, and data enthusiasts committed to making survey creation and analysis as simple and effective as possible.
                    </p>
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                        <div class="text-center">
                            <div class="w-24 h-24 bg-gray-200 dark:bg-gray-700 rounded-full mx-auto mb-4"></div>
                            <h4 class="font-medium text-gray-800 dark:text-white">Ashish Kumar Dash</h4>
                            
                        </div>
                        <div class="text-center">
                            <div class="w-24 h-24 bg-gray-200 dark:bg-gray-700 rounded-full mx-auto mb-4"></div>
                            <h4 class="font-medium text-gray-800 dark:text-white">Shivansu Bisht</h4>
                     
                        </div>
                        <div class="text-center">
                            <div class="w-24 h-24 bg-gray-200 dark:bg-gray-700 rounded-full mx-auto mb-4"></div>
                            <h4 class="font-medium text-gray-800 dark:text-white">Shivam </h4>
                          
                        </div>
                        <div class="text-center">
                            <div class="w-24 h-24 bg-gray-200 dark:bg-gray-700 rounded-full mx-auto mb-4"></div>
                            <h4 class="font-medium text-gray-800 dark:text-white">Ayush Kamboj</h4>
                          
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="bg-gray-800 dark:bg-gray-900 text-white py-8">
        <div class="container mx-auto px-4">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div>
                    <h3 class="text-lg font-semibold mb-4">Survey Portal</h3>
                    <p class="text-gray-400">Create and share surveys with ease. Get valuable insights from your audience.</p>
                </div>
                <div>
                    <h3 class="text-lg font-semibold mb-4">Quick Links</h3>
                    <ul class="space-y-2">
                        <li><a href="home.php" class="text-gray-400 hover:text-white">Home</a></li>
                        <li><a href="about.php" class="text-gray-400 hover:text-white">About Us</a></li>
                        <li><a href="contact.php" class="text-gray-400 hover:text-white">Contact Us</a></li>
                    </ul>
                </div>
                <div>
                    <h3 class="text-lg font-semibold mb-4">Connect With Us</h3>
                    <div class="flex space-x-4">
                        <a href="#" class="text-gray-400 hover:text-white">
                            <i class="fab fa-facebook-f"></i>
                        </a>
                        <a href="#" class="text-gray-400 hover:text-white">
                            <i class="fab fa-twitter"></i>
                        </a>
                        <a href="#" class="text-gray-400 hover:text-white">
                            <i class="fab fa-linkedin-in"></i>
                        </a>
                        <a href="#" class="text-gray-400 hover:text-white">
                            <i class="fab fa-instagram"></i>
                        </a>
                    </div>
                </div>
            </div>
            <div class="border-t border-gray-700 mt-8 pt-8 text-center text-gray-400">
                <p>&copy; <?= date('Y') ?> Survey Portal. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/js/all.min.js"></script>
</body>
</html> 