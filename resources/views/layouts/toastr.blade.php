<script src="/bower_components/admin-lte/plugins/toastr/toastr.min.js"></script>
<script src="/bower_components/admin-lte/plugins/sweetalert2/sweetalert2.min.js"></script>

<script>

    //Toastr -->
    // @if(Session::has('success'))
    //     toastr.options = { "closeButton" : true }
    //     toastr.success("{{ session('message') }}");
    // @endif
  
    // @if(Session::has('error'))
    //     toastr.options = { "closeButton" : true }
    //     toastr.error("{{ session('error') }}");
    // @endif
  
    @if(Session::has('info'))
        toastr.options = { "closeButton" : true }
        toastr.info("{{ session('info') }}");
    @endif
  
    @if(Session::has('warning'))
        toastr.options = { "closeButton" : true }
        toastr.warning("{{ session('warning') }}");
    @endif

    //Loadingpage -->
    var sweet_loader = '<div class="sweet_loader"><svg viewBox="0 0 140 140" width="140" height="140"><g class="outline"><path d="m 70 28 a 1 1 0 0 0 0 84 a 1 1 0 0 0 0 -84" stroke="rgba(0,0,0,0.1)" stroke-width="4" fill="none" stroke-linecap="round" stroke-linejoin="round"></path></g><g class="circle"><path d="m 70 28 a 1 1 0 0 0 0 84 a 1 1 0 0 0 0 -84" stroke="#71BBFF" stroke-width="4" fill="none" stroke-linecap="round" stroke-linejoin="round" stroke-dashoffset="200" stroke-dasharray="300"></path></g></svg></div>';

    //SweetAlert2 -->
    @if(Session::has('success'))
    var Toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 3000
      });
      Swal.fire({
        icon: 'success',
        title: '{{ session("success") }}'
      }).then(function() {
        //window.location.reload();
      });
    @endif

    @if(Session::has('error'))
    var Toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 3000
      });
      Swal.fire({
        icon: 'error',
        title: '{{ session("error") }}'
      }).then(function() {
        window.location.reload();
      });
    @endif
</script>
