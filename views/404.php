<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 - Page non trouvée</title>
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Roboto+Condensed:wght@200;300;400;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
          theme: {
            extend: {
              colors: {
                primary: '#2563eb', // Blue color for education theme
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
<body class="font-sans font-normal antialiased bg-gray-50 flex items-center justify-center h-screen flex-col">
    <h1 class="text-4xl font-bold text-primary">404 - Page non trouvée</h1>
    <p class="text-gray-600">Désolé, la page que vous recherchez n'existe pas.</p>
    <a href="index.php?action=home">Retour à l'accueil</a>
</body>
</html>

