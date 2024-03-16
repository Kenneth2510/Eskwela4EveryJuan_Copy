<!DOCTYPE html>
<html lang="en">
<head>
  @include('partials.header')
</head>
<body class="min-h-full bg-mainwhitebg font-poppins">
    <x-message />
    <section class="flex flex-row w-full h-auto bg-mainwhitebg">
      <header class="absolute top-0 left-0 z-40 w-1/3 px-4 py-4 bg-mainwhitebg">
        <a href="/">
          <span class="self-center font-semibold font-semibbold whitespace-nowrap md:text-2xl text-darthmouthgreen">
            Eskwela4EveryJuan
          </span>
        </a>
      </header>
      @yield('content')
    </section>


@include('partials.footer')