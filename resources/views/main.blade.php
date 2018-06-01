<!DOCTYPE html>
<html lang="en">
<!-- Partials usually start with an underscore-->
  <head>
    @include('partials._head')
  </head>

  <body>

    @include('partials._nav')

    <div class="container">

      @include('partials._messages')


        <!-- Blade start with an @ sign-->
        <!-- this is a layout -->
      @yield('content')

      @include('partials._footer')

    </div>


    @include('partials._javascript')

    @yield('scripts')
  </body>

</html>
