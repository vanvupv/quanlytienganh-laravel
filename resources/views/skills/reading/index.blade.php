<!-- Header -->
@include("layouts/header")

@include("layouts/sidebar")

<!-- ======== main-wrapper start =========== -->
<main class="main-wrapper" style="margin-left: 300px;">
  <!-- ========== section start ========== -->
  @yield('content_reading')
  <!-- ========== section end ========== -->
</main>
<!-- ======== main-wrapper end =========== -->
<!-- Footer -->
@include("layouts/footer")
