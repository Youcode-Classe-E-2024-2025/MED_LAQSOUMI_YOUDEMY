<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Courses - Youdemy</title>
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Roboto+Condensed:wght@200;300;400;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
          theme: {
            extend: {
              colors: {
                primary: '#2563eb',
                secondary: '#1e40af'
              },
              fontFamily: {
                'sans': ['Roboto Condensed', 'sans-serif']
              }
            }
          }
        }
    </script>
</head>
<body class="font-sans font-normal antialiased bg-gray-50 text-gray-900">
    <!-- Header Section -->
    <div class="h-24 w-full absolute top-0 left-0 bg-primary"></div>
    <div class="h-24 z-20 relative container mx-auto flex items-center justify-between px-6">
        <a href="index.php?action=home" class="text-xl font-extrabold italic tracking-tighter text-white uppercase">Youdemy</a>
        <div x-data="{ mobileMenu: false }" class="text-white text-lg fixed bottom-0 left-0 lg:relative p-6 lg:p-0 w-full lg:w-auto max-w-lg">
            <div x-bind:class="{ 'flex': mobileMenu, 'hidden': !mobileMenu }" class="lg:flex flex-col lg:flex-row items-center justify-center bg-primary lg:bg-transparent pt-6 pb-8 lg:p-0 -mb-6 lg:m-0 rounded-t-3xl shadow-2xl lg:shadow-none">
                <a href="index.php?action=courses" class="my-2 lg:ml-6">Courses</a>
                <a href="index.php?action=login" class="my-2 lg:ml-6">Login</a>
                <a href="index.php?action=register" class="my-2 lg:ml-6">Register</a>
            </div>
        </div>
    </div>

    <!-- Search and Filter Section -->
    <div class="pt-32 pb-12 bg-primary">
        <div class="container mx-auto px-6">
            <div class="max-w-4xl mx-auto">
                <!-- Search Bar -->
                <div class="bg-white rounded-lg p-4 shadow-lg mb-6">
                    <div class="flex flex-col md:flex-row gap-4">
                        <div class="flex-1">
                            <input type="text" placeholder="Search courses..." class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-primary">
                        </div>
                        <button class="bg-primary text-white px-6 py-2 rounded-lg hover:bg-secondary transition-colors">
                            Search
                        </button>
                    </div>
                    
                    <!-- Filters -->
                    <div class="mt-4 flex flex-wrap gap-4">
                        <!-- Categories Dropdown -->
                        <select class="px-4 py-2 border rounded-lg focus:outline-none focus:border-primary">
                            <option value="">All Categories</option>
                            <option value="development">Development</option>
                            <option value="business">Business</option>
                            <option value="design">Design</option>
                            <option value="marketing">Marketing</option>
                        </select>
                        
                        <!-- Popular Tags -->
                        <div class="flex flex-wrap gap-2">
                            <span class="px-3 py-1 bg-gray-100 rounded-full text-sm cursor-pointer hover:bg-primary hover:text-white transition-colors">
                                Web Development
                            </span>
                            <span class="px-3 py-1 bg-gray-100 rounded-full text-sm cursor-pointer hover:bg-primary hover:text-white transition-colors">
                                JavaScript
                            </span>
                            <span class="px-3 py-1 bg-gray-100 rounded-full text-sm cursor-pointer hover:bg-primary hover:text-white transition-colors">
                                Python
                            </span>
                            <span class="px-3 py-1 bg-gray-100 rounded-full text-sm cursor-pointer hover:bg-primary hover:text-white transition-colors">
                                UI/UX
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Course Grid -->
    <div class="container mx-auto px-6 py-12">
        <!-- Results Count and Sort -->
        <div class="flex justify-between items-center mb-8">
            <p class="text-gray-600">Showing 1-9 of 56 courses</p>
            <select class="px-4 py-2 border rounded-lg focus:outline-none focus:border-primary">
                <option value="newest">Newest First</option>
                <option value="popular">Most Popular</option>
                <option value="price-low">Price: Low to High</option>
                <option value="price-high">Price: High to Low</option>
            </select>
        </div>

        <!-- Course Cards Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 mb-12">
            <!-- Course Card -->
            <div class="bg-white rounded-lg overflow-hidden shadow-lg">
                <img src="/api/placeholder/400/250" alt="Course thumbnail" class="w-full h-48 object-cover">
                <div class="p-6">
                    <div class="flex flex-wrap gap-2 mb-2">
                        <span class="px-2 py-1 bg-blue-100 text-primary rounded-full text-xs">Development</span>
                        <span class="px-2 py-1 bg-blue-100 text-primary rounded-full text-xs">Web</span>
                    </div>
                    <h3 class="text-xl font-bold mb-2">Complete Web Development Course</h3>
                    <p class="text-gray-600 mb-4">Learn web development from scratch with practical projects</p>
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <img src="/api/placeholder/40/40" alt="Teacher" class="w-8 h-8 rounded-full mr-2">
                            <span class="text-sm">John Doe</span>
                        </div>
                        <span class="text-primary font-bold">$49.99</span>
                    </div>
                </div>
            </div>
            <!-- Repeat course cards -->
        </div>

        <!-- Pagination -->
        <div class="flex justify-center items-center space-x-2">
            <a href="#" class="px-4 py-2 border rounded-lg hover:bg-primary hover:text-white transition-colors">
                Previous
            </a>
            <a href="#" class="px-4 py-2 border rounded-lg bg-primary text-white">1</a>
            <a href="#" class="px-4 py-2 border rounded-lg hover:bg-primary hover:text-white transition-colors">2</a>
            <a href="#" class="px-4 py-2 border rounded-lg hover:bg-primary hover:text-white transition-colors">3</a>
            <span class="px-4 py-2">...</span>
            <a href="#" class="px-4 py-2 border rounded-lg hover:bg-primary hover:text-white transition-colors">8</a>
            <a href="#" class="px-4 py-2 border rounded-lg hover:bg-primary hover:text-white transition-colors">
                Next
            </a>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-gray-900 text-white">
        <div class="container mx-auto px-6 py-12">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <div>
                    <h3 class="text-xl font-bold mb-4">About Youdemy</h3>
                    <ul class="space-y-2">
                        <li><a href="/about.html" class="hover:text-primary">About Us</a></li>
                    </ul>
                </div>
                <div>
                    <h3 class="text-xl font-bold mb-4">Learn</h3>
                    <ul class="space-y-2">
                        <li><a href="index.php?action=courses" class="hover:text-primary">All Courses</a></li>
                        <li><a href="index.php?action=login" class="hover:text-primary">Login</a></li>
                        <li><a href="index.php?action=register" class="hover:text-primary">Register</a></li>
                    </ul>
                </div>
                <div>
                    <h3 class="text-xl font-bold mb-4">Newsletter</h3>
                    <p class="mb-4">Get the latest updates and offers</p>
                    <form class="flex">
                        <input type="email" placeholder="Enter your email" class="flex-1 p-2 rounded-l text-gray-900">
                        <button class="bg-primary px-4 rounded-r hover:bg-secondary">Subscribe</button>
                    </form>
                </div>
            </div>
        </div>
        <div class="border-t border-gray-800">
            <div class="container mx-auto px-6 py-6 text-center">
                <p>&copy; 2025 Youdemy. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/gh/alpinejs/alpine@v2.8.2/dist/alpine.min.js" defer></script>
</body>
</html>