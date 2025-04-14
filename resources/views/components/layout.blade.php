<!DOCTYPE html>
<html lang="it">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ $documentTitle }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
</head>
<body>
    <x-nav />
    <div class="min-vh-100">
        {{ $slot }}
    </div>
    <x-footer />
    <x-modal />
    {{-- SCRIPT FONTAWESOME --}}
    <script src="https://kit.fontawesome.com/cfb9a37921.js" crossorigin="anonymous"></script>
    {{-- ! SCRIPT IUBENDA SERVER PERSONALE --}}
    {{-- <script type="text/javascript">
        var _iub = _iub || [];
        _iub.csConfiguration = {
            "siteId": 3767111,
            "cookiePolicyId": 11435994,
            "lang": "it"
        };
    </script>
    <script type="text/javascript" src="https://cs.iubenda.com/autoblocking/3767111.js"></script>
    <script type="text/javascript" src="//cdn.iubenda.com/cs/iubenda_cs.js" charset="UTF-8" async></script> --}}
    {{-- ! SCRIPT IUBENDA SERVER CUSTORINO --}}
    <script type="text/javascript">
        var _iub = _iub || [];
        _iub.csConfiguration = {
            "siteId": 3770174,
            "cookiePolicyId": 42749205,
            "lang": "it"
        };
    </script>
    <script type="text/javascript" src="https://cs.iubenda.com/autoblocking/3770174.js"></script>
    <script type="text/javascript" src="//cdn.iubenda.com/cs/iubenda_cs.js" charset="UTF-8" async></script>
</body>

</html>
