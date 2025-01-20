<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Enseignant Dashboard - Youdemy</title>
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
<body class="font-sans font-normal antialiased bg-gray-100 text-gray-900">
    <div class="flex h-screen bg-gray-100">
        <!-- Sidebar -->
        <aside class="w-64 bg-primary text-white">
            <div class="p-4">
                <h1 class="text-2xl font-bold">Youdemy</h1>
                <p class="text-sm">Enseignant Dashboard</p>
            </div>
            <nav class="mt-6">
                <a href="#" class="block py-2 px-4 hover:bg-secondary">Dashboard</a>
                <a href="#" class="block py-2 px-4 hover:bg-secondary">Mes Cours</a>
                <a href="#" class="block py-2 px-4 hover:bg-secondary">Ajouter un Cours</a>
                <a href="#" class="block py-2 px-4 hover:bg-secondary">Statistiques</a>
            </nav>
        </aside>

        <!-- Main Content -->
        <main class="flex-1 overflow-y-auto">
            <div class="p-6">
                <h2 class="text-3xl font-bold mb-6">Tableau de Bord Enseignant</h2>

                <!-- Quick Stats -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                    <div class="bg-white rounded-lg shadow p-6">
                        <h3 class="text-xl font-semibold mb-2">Total des Cours</h3>
                        <p class="text-3xl font-bold text-primary">12</p>
                    </div>
                    <div class="bg-white rounded-lg shadow p-6">
                        <h3 class="text-xl font-semibold mb-2">Étudiants Inscrits</h3>
                        <p class="text-3xl font-bold text-primary">156</p>
                    </div>
                    <div class="bg-white rounded-lg shadow p-6">
                        <h3 class="text-xl font-semibold mb-2">Revenus ce Mois</h3>
                        <p class="text-3xl font-bold text-primary">1,250 €</p>
                    </div>
                </div>

                <!-- Recent Courses -->
                <div class="bg-white rounded-lg shadow p-6 mb-6">
                    <h3 class="text-xl font-semibold mb-4">Cours Récents</h3>
                    <table class="w-full">
                        <thead>
                            <tr class="text-left">
                                <th class="pb-3">Titre</th>
                                <th class="pb-3">Catégorie</th>
                                <th class="pb-3">Étudiants</th>
                                <th class="pb-3">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="py-2">Introduction à Python</td>
                                <td>Programmation</td>
                                <td>45</td>
                                <td>
                                    <a href="#" class="text-primary hover:underline">Modifier</a>
                                    <a href="#" class="text-red-600 hover:underline ml-2">Supprimer</a>
                                </td>
                            </tr>
                            <tr>
                                <td class="py-2">Design UX/UI Avancé</td>
                                <td>Design</td>
                                <td>32</td>
                                <td>
                                    <a href="#" class="text-primary hover:underline">Modifier</a>
                                    <a href="#" class="text-red-600 hover:underline ml-2">Supprimer</a>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Add New Course -->
                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="text-xl font-semibold mb-4">Ajouter un Nouveau Cours</h3>
                    <form>
                        <div class="mb-4">
                            <label for="title" class="block text-sm font-medium text-gray-700">Titre</label>
                            <input type="text" id="title" name="title" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50">
                        </div>
                        <div class="mb-4">
                            <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                            <textarea id="description" name="description" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50"></textarea>
                        </div>
                        <div class="mb-4">
                            <label for="category" class="block text-sm font-medium text-gray-700">Catégorie</label>
                            <select id="category" name="category" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50">
                                <option>Programmation</option>
                                <option>Design</option>
                                <option>Business</option>
                                <option>Marketing</option>
                            </select>
                        </div>
                        <div class="mb-4">
                            <label for="tags" class="block text-sm font-medium text-gray-700">Tags</label>
                            <input type="text" id="tags" name="tags" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50">
                        </div>
                        <div class="mb-4">
                            <label for="content" class="block text-sm font-medium text-gray-700">Contenu</label>
                            <input type="file" id="content" name="content" class="mt-1 block w-full">
                        </div>
                        <button type="submit" class="bg-primary text-white px-4 py-2 rounded-md hover:bg-secondary">Ajouter le Cours</button>
                    </form>
                </div>
            </div>
        </main>
    </div>
</body>
</html>