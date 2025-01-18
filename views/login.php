<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Login - Youdemy</title>
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
                'sans' : ['Roboto Condensed', 'sans-serif']
              }
            }
          }
        }
    </script>
</head>
<body class="font-sans font-normal antialiased bg-gray-50">
    <!-- Header Section -->
    <div class="h-24 w-full bg-primary">
        <div class="container mx-auto h-full flex items-center justify-between px-6">
            <a href="index.php?action=home" class="text-xl font-extrabold italic tracking-tighter text-white uppercase">Youdemy</a>
            <a href="index.php?action=home" class="text-white hover:text-gray-200">Back to Home</a>
        </div>
    </div>

    <!-- Login Form Section -->
    <div class="container mx-auto px-6 py-12">
        <div class="max-w-md mx-auto bg-white rounded-lg shadow-lg overflow-hidden">
            <div class="bg-primary py-8 px-6">
                <h2 class="text-2xl font-bold text-white text-center">Welcome Back!</h2>
                <p class="text-blue-100 text-center mt-2">Login to access your courses</p>
            </div>
            <form class="py-8 px-6 space-y-6">
                <div>
                    <label class="block text-gray-700 text-sm font-bold mb-2">Email Address</label>
                    <input type="email" class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:outline-none focus:border-primary" required>
                </div>
                <div>
                    <label class="block text-gray-700 text-sm font-bold mb-2">Password</label>
                    <input type="password" class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:outline-none focus:border-primary" required>
                </div>
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <input type="checkbox" class="h-4 w-4 text-primary border-gray-300 rounded">
                        <label class="ml-2 text-sm text-gray-600">Remember me</label>
                    </div>
                </div>
                <button type="submit" class="w-full bg-primary text-white py-3 rounded-lg hover:bg-secondary transition-colors">
                    Login
                </button>
                <div class="text-center mt-4">
                    <span class="text-gray-600 text-sm">Don't have an account?</span>
                    <a href="index.php?action=register" class="text-primary hover:text-secondary text-sm ml-1">Register now</a>
                </div>
            </form>
        </div>
    </div>

    <!-- Footer -->
    <div class="bg-gray-900 text-white mt-12">
        <div class="container mx-auto px-6 py-6 text-center">
            <p>&copy; 2025 Youdemy. All rights reserved.</p>
        </div>
    </div>
</body>
</html>