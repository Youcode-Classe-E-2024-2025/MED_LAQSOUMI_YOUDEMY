<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Youdemy</title>
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
                <p class="text-sm">Admin Dashboard</p>
            </div>
            <nav class="mt-6">
                <a href="#" class="block py-2 px-4 hover:bg-secondary">Dashboard</a>
                <a href="#" class="block py-2 px-4 hover:bg-secondary">Utilisateurs</a>
                <a href="#" class="block py-2 px-4 hover:bg-secondary">Cours</a>
                <a href="#" class="block py-2 px-4 hover:bg-secondary">Catégories</a>
                <a href="#" class="block py-2 px-4 hover:bg-secondary">Tags</a>
                <a href="#" class="block py-2 px-4 hover:bg-secondary">Statistiques</a>
            </nav>
        </aside>

        <!-- Main Content -->
        <main class="flex-1 overflow-y-auto">
            <div class="p-6">
                <h2 class="text-3xl font-bold mb-6">Tableau de Bord Administrateur</h2>

                <!-- Quick Stats -->
                <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
                    <div class="bg-white rounded-lg shadow p-6">
                        <h3 class="text-xl font-semibold mb-2">Total des Cours</h3>
                        <p class="text-3xl font-bold text-primary">250</p>
                    </div>
                    <div class="bg-white rounded-lg shadow p-6">
                        <h3 class="text-xl font-semibold mb-2">Utilisateurs</h3>
                        <p class="text-3xl font-bold text-primary">1,500</p>
                    </div>
                    <div class="bg-white rounded-lg shadow p-6">
                        <h3 class="text-xl font-semibold mb-2">Enseignants</h3>
                        <p class="text-3xl font-bold text-primary">75</p>
                    </div>
                    <div class="bg-white rounded-lg shadow p-6">
                        <h3 class="text-xl font-semibold mb-2">Catégories</h3>
                        <p class="text-3xl font-bold text-primary">12</p>
                    </div>
                </div>

                <!-- Validation des Comptes Enseignants -->
                <div class="bg-white rounded-lg shadow p-6 mb-6">
                    <h3 class="text-xl font-semibold mb-4">Validation des Comptes Enseignants</h3>
                    <table class="w-full">
                        <thead>
                            <tr class="text-left">
                                <th class="pb-3">Nom</th>
                                <th class="pb-3">Email</th>
                                <th class="pb-3">Date d'Inscription</th>
                                <th class="pb-3">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="py-2">Jean Dupont</td>
                                <td>jean.dupont@email.com</td>
                                <td>2023-05-15</td>
                                <td>
                                    <button class="bg-green-500 text-white px-2 py-1 rounded-md hover:bg-green-600">Valider</button>
                                    <button class="bg-red-500 text-white px-2 py-1 rounded-md hover:bg-red-600 ml-2">Refuser</button>
                                </td>
                            </tr>
                            <tr>
                                <td class="py-2">Marie Martin</td>
                                <td>marie.martin@email.com</td>
                                <td>2023-05-16</td>
                                <td>
                                    <button class="bg-green-500 text-white px-2 py-1 rounded-md hover:bg-green-600">Valider</button>
                                    <button class="bg-red-500 text-white px-2 py-1 rounded-md hover:bg-red-600 ml-2">Refuser</button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Gestion des Tags -->
                <div class="bg-white rounded-lg shadow p-6 mb-6">
                    <h3 class="text-xl font-semibold mb-4">Gestion des Tags</h3>
                    <form class="mb-4">
                        <label for="tags" class="block text-sm font-medium text-gray-700 mb-2">Ajouter des Tags (séparés par des virgules)</label>
                        <div class="flex">
                            <input type="text" id="tags" name="tags" class="flex-grow rounded-l-md border-gray-300 shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50">
                            <button type="submit" class="bg-primary text-white px-4 py-2 rounded-r-md hover:bg-secondary">Ajouter</button>
                        </div>
                    </form>
                    <div class="flex flex-wrap gap-2">
                        <span class="bg-gray-200 text-gray-700 px-2 py-1 rounded-full text-sm">Python</span>
                        <span class="bg-gray-200 text-gray-700 px-2 py-1 rounded-full text-sm">JavaScript</span>
                        <span class="bg-gray-200 text-gray-700 px-2 py-1 rounded-full text-sm">UX/UI</span>
                        <span class="bg-gray-200 text-gray-700 px-2 py-1 rounded-full text-sm">Design</span>
                        <span class="bg-gray-200 text-gray-700 px-2 py-1 rounded-full text-sm">Marketing</span>
                    </div>
                </div>

                <!-- Statistiques Globales -->
                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="text-xl font-semibold mb-4">Statistiques Globales</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <h4 class="text-lg font-semibold mb-2">Répartition par Catégorie</h4>
                            <ul class="space-y-2">
                                <li>Programmation: 35%</li>
                                <li>Design: 25%</li>
                                <li>Business: 20%</li>
                                <li>Marketing: 15%</li>
                                <li>Autres: 5%</li>
                            </ul>
                        </div>
                        <div>
                            <h4 class="text-lg font-semibold mb-2">Top 3 Enseignants</h4>
                            <ol class="list-decimal list-inside">
                                <li>Jean Dupont (25 cours)</li>
                                <li>Marie Martin (18 cours)</li>
                                <li>Pierre Durand (15 cours)</li>
                            </ol>
                        </div>
                    </div>
                    <div class="mt-6">
                        <h4 class="text-lg font-semibold mb-2">Cours le Plus Populaire</h4>
                        <p>"Introduction à Python" par Jean Dupont (500 étudiants inscrits)</p>
                    </div>
                </div>
            </div>
        </main>
    </div>
</body>
</html>