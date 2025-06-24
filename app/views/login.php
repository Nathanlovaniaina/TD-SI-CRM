<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Connexion</title>
    <style>
        :root {
            --primary-color: #1b3e6f;
            --accent-color: #4169e1;
            --text-light: #ffffff;
            --background: #f5f5f5;
            --shadow: 0 2px 8px rgba(0,0,0,0.1);
        }

        body {
            margin: 0;
            font-family: "Segoe UI", system-ui, -apple-system, sans-serif;
            background-color: var(--background);
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .login-container {
            background: white;
            padding: 2rem;
            border-radius: 8px;
            box-shadow: var(--shadow);
            width: 100%;
            max-width: 400px;
        }

        .login-container h2 {
            margin-top: 0;
            color: var(--primary-color);
            text-align: center;
        }

        .form-group {
            margin-bottom: 1.25rem;
        }

        label {
            display: block;
            margin-bottom: 0.5rem;
            color: #333;
        }

        input[type="text"],
        input[type="password"] {
            width: 100%;
            padding: 0.75rem;
            border: 1px solid #ccc;
            border-radius: 6px;
        }

        .login-btn {
            background-color: var(--accent-color);
            color: white;
            padding: 0.75rem;
            border: none;
            border-radius: 6px;
            width: 100%;
            font-weight: bold;
            cursor: pointer;
        }

        .login-btn:hover {
            background-color: var(--primary-color);
        }

        .login-footer {
            text-align: center;
            margin-top: 1rem;
            font-size: 0.9rem;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h2>Connexion</h2>
        <form method="post" action="login">
            <div class="form-group">
                <label for="username">Identifiant ou email</label>
                <input type="text" id="username" name="username" required>
            </div>

            <div class="form-group">
                <label for="password">Mot de passe</label>
                <input type="password" id="password" name="password" required>
            </div>

            <button type="submit" class="login-btn">Se connecter</button>
        </form>
        <div class="login-footer">
            Pas encore de compte ? Contactez l'administrateur.
        </div>
    </div>
</body>
</html>
