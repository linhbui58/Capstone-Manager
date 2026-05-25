    </div><!-- /.app-wrapper -->

    <!-- BOOTSTRAP -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <!-- DATATABLE -->
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

    <!-- SWEET ALERT -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- AOS -->
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>

    <!-- APP JS -->
    <script src="assets/js/app.js"></script>

    <script>

        /*
        |--------------------------------------------------------------------------
        | AOS INIT
        |--------------------------------------------------------------------------
        */
        AOS.init({
            duration: 600,
            once: true,
            easing: 'ease-out-cubic'
        });

        /*
        |--------------------------------------------------------------------------
        | GLOBAL LOADER
        |--------------------------------------------------------------------------
        */
        $(window).on('load', function(){
            $('#global-loader').fadeOut(400);
        });

        /*
        |--------------------------------------------------------------------------
        | DATATABLE INIT (safe — skip tables already initialised individually)
        |--------------------------------------------------------------------------
        */
        $(document).ready(function() {
            $.fn.dataTable.ext.errMode = 'none';

            $('.datatable').each(function() {
                if ($(this).attr('id')) return;
                if ($.fn.DataTable.isDataTable(this)) return;

                $(this).DataTable({
                    retrieve: true,
                    responsive: true,
                    language: {
                        search: "",
                        searchPlaceholder: "Tìm kiếm...",
                        lengthMenu: "Hiện _MENU_ dòng",
                        info: "Hiển thị _START_ đến _END_ trong _TOTAL_ mục",
                        paginate: { next: "Sau", previous: "Trước" }
                    }
                });
            });

            /* AUTO HIDE ALERTS */
            setTimeout(() => {
                $('.alert').fadeOut(500);
            }, 3500);
        });

        /*
        |--------------------------------------------------------------------------
        | DELETE CONFIRM (SweetAlert2 — dark theme)
        |--------------------------------------------------------------------------
        */
        $(document).on('click', '.btn-delete', function(e){
            e.preventDefault();
            let link = $(this).attr('href');

            Swal.fire({
                title: 'Xác nhận xóa?',
                text: "Dữ liệu này sẽ bị xóa vĩnh viễn.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: '<i class="fa fa-trash me-1"></i> Xóa',
                cancelButtonText: 'Hủy',
                background: '#0d1526',
                color: '#e2e8f0',
                confirmButtonColor: '#ef4444',
                cancelButtonColor: 'rgba(255,255,255,0.08)',
                customClass: {
                    popup: 'swal-glass-popup',
                    confirmButton: 'btn btn-danger',
                    cancelButton: 'btn btn-secondary'
                },
                buttonsStyling: false
            }).then((result) => {
                if(result.isConfirmed){
                    window.location.href = link;
                }
            });
        });

        /*
        |--------------------------------------------------------------------------
        | MOBILE SIDEBAR TOGGLE
        |--------------------------------------------------------------------------
        */
        $('#sidebarToggle').on('click', function(){
            $('.sidebar').toggleClass('show');
            $('#sidebar-overlay').toggleClass('active');
        });

        $('#sidebar-overlay').on('click', function(){
            $('.sidebar').removeClass('show');
            $('#sidebar-overlay').removeClass('active');
        });

    </script>

    <!-- SweetAlert2 dark glass popup override -->
    <style>
    .swal-glass-popup {
        border: 1px solid rgba(255,255,255,0.08) !important;
        border-radius: 20px !important;
        backdrop-filter: blur(20px) !important;
        box-shadow: 0 20px 60px rgba(0,0,0,0.6) !important;
    }
    .swal2-title { color: #f8fafc !important; font-family: 'Plus Jakarta Sans', sans-serif !important; }
    .swal2-html-container { color: #94a3b8 !important; font-family: 'Plus Jakarta Sans', sans-serif !important; }
    </style>

</body>
</html>