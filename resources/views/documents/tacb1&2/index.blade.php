<!-- Header -->
@include("layouts/header")

<!-- ======== main-wrapper start =========== -->
<main class="main-wrapper" style="margin-left: 0px; ">
  <!-- ========== section start ========== -->
  @yield('content_document')
  <!-- ========== section end ========== -->
</main>
<!-- ======== main-wrapper end =========== -->
<!-- Footer -->
@include("layouts/footer")