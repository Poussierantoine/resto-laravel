<!doctype html>
<html class="no-js" lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title> @yield('pageTitle') | Les Super Restos</title>
  
  {{-- recuperation du css --}}
  @stack('css')

 {{--  <script src="./js/app.js" type="module" async></script> --}}

 @vite(['resources/js/app.js'])

@livewireStyles

</head>
<body>
  <nav>
      <p id="webSiteName">Les Super Restos</p>
      <ul class="menu">
        <li><a href="/">Home</a></li>
        <li><a href="/restaurants">Restaurants</a></li>
        <li><a href="/contact">Contact</a></li>
      </ul>
    </nav>

    <x-image-title-banner :pageTitle="@yield('pageTitle')" />


  @if (isset($popup))
  @livewire('notification', ['popup' => $popup])
  @endif

  @if (isset($popups))
  @foreach ($popups as $popup)
  @livewire('notification', ['popup' => $popup])
  @endforeach
  @endif
  
  


  <article>
      {{-- recuperation du contenu --}}
    @yield('content')

  </article>

@livewireScripts

</body>
</html>