<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="{{ asset('favicon.png') }}" type="image/x-icon">
    <title>404 Not Found</title>
    <style>
        body {
            margin: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background: linear-gradient(135deg, #f3f4f6, #e5e7eb);
            font-family: 'Arial', sans-serif;
            overflow: hidden;
        }
        .container {
            text-align: center;
            animation: fadeIn 1.5s ease-in-out;
        }
        h1 {
            font-size: 4em;
            color: #ef4444;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.1);
            animation: bounce 1s infinite alternate;
        }
        p {
            font-size: 1.5em;
            color: #1f2937;
            margin-top: 10px;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        @keyframes bounce {
            from { transform: translateY(0); }
            to { transform: translateY(-10px); }
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>404</h1>
        <p>Oops! The page you're looking maybe broken.</p>
    </div>
</body>
</html> 