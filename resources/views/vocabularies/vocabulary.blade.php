@php
  $isAdmin = request()->is('*-admin*') || request()->is('admin*');
@endphp

<!-- Header -->
@include("layouts/header")

@include("layouts/sidebar")

<!-- ======== main-wrapper start =========== -->
<main class="main-wrapper" style="{{ $isAdmin ? 'margin-left: 300px;' : 'margin-left: 0px !important; width: 100% !important;' }}">
  @if($isAdmin)
    @yield('content_voca')
  @else
    <div class="container-xl py-4">
      @yield('content_voca')
    </div>
  @endif
</main>
<!-- ======== main-wrapper end =========== -->
<!-- Footer -->
@include("layouts/footer")