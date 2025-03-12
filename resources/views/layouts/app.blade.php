<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard')</title>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/boxicons@2.1.2/css/boxicons.min.css">
    <link rel="stylesheet" href="{{ asset('css/SidebarTest.css') }}">
    <link rel="stylesheet" href="{{ asset('lib/fontawesome-free-6.5.2-web/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('lib/bootstrap-icons-1.11.3/font/bootstrap-icons.min.css') }}">
    <link rel="stylesheet" href="{{ asset('lib/bootstrap/css/bootstrap.min.css') }}">
    
    <link rel="stylesheet" href="{{ asset('css/form.css') }}">
    <link href="https://cdn.datatables.net/v/bs5/dt-2.2.2/datatables.min.css" rel="stylesheet">
 

</head>
<body style="background-color: #eef8ff;">
        <!-- Sidebar -->
        @include('partials.sidebar')

        <div class="home-section">
            <!-- Navbar -->
            @include('partials.navbar')

            <div class="scroolAsignSubj h-100 px-4 pt-5 haut-rendbody">
                @yield('content')  <!-- Contenu spécifique à chaque page -->
            </div>
        </div>

        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="{{ asset('js/SidebarTest.js') }}" defer></script>
        <script src="{{ asset('js/bootstrap.min.js') }}" asp-append-version="true"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
        <script src="https://cdn.datatables.net/v/bs5/dt-2.2.2/datatables.min.js"></script>
        <script>
            $(document).ready(function () {
                $('.table').DataTable();
            });
        </script>
</body>
</html>
