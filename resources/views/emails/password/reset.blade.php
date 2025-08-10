<!DOCTYPE html>
<html lang="it">

<head>
    <meta charset="UTF-8">
    <title>Reimposta Password</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            color: #333333;
            margin: 0;
            padding: 20px;
        }

        .container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            padding: 30px 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        h1 {
            color: #007bff;
            font-size: 24px;
        }

        p {
            font-size: 16px;
            line-height: 1.5;
        }

        a.button {
            color: #ffffff !important;
            background-color: #007bff;
            padding: 12px 24px;
            text-decoration: none;
            border-radius: 5px;
            display: inline-block;
            margin-top: 20px;
            font-weight: bold;
        }

        a.button:hover {
            opacity: 0.9;
        }

        .footer {
            margin-top: 30px;
            font-size: 12px;
            color: #888;
            text-align: center;
        }
    </style>
</head>

<body>
    <div class="container" role="presentation">
        <h1>Reimposta la tua Password</h1>
        <p>Hai ricevuto questa email perché è stata richiesta una reimpostazione della password per il tuo account.</p>
        <p>Clicca sul pulsante qui sotto per procedere:</p>
        <a href="{{ $link }}" class="button" target="_blank" rel="noopener noreferrer">Reimposta la Password</a>
        <p>Se non hai effettuato questa richiesta, puoi ignorare questa email.</p>

        <div class="footer">
            &copy; {{ date('Y') }} CusTorino – Tutti i diritti riservati.
        </div>
    </div>
</body>

</html>
