@include('partials.head')
<body>
<div class="container">
    @include('partials.mails.navigation')
    @yield('jumbotron')
</div>
<main role="main" class="container">
    <div class="row">
        @yield('content')
        @yield('side-bar')
    </div>
</main>
@include('partials.footer')
