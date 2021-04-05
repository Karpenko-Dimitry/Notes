<!doctype html>
<html lang="{{ app()->getLocale() }}">
    @include('partials.head')
    <body>
    <div class="container">
        @include('partials.navigation')
        @yield('jumbotron')
    </div>
    <main role="main" class="container">
        <div class="row">
            @yield('content')
            @yield('side-bar')
        </div>
    </main>

    @include('partials.footer')
    </body>
</html>
