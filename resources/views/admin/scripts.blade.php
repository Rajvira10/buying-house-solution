<!-- JAVASCRIPT -->
<script src="{{ asset('public/admin-assets/libs/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('public/admin-assets/libs/simplebar/simplebar.min.js') }}"></script>
<script src="{{ asset('public/admin-assets/libs/node-waves/waves.min.js') }}"></script>
<script src="{{ asset('public/admin-assets/libs/feather-icons/feather.min.js') }}"></script>
<script src="{{ asset('public/admin-assets/js/pages/plugins/lord-icon-2.1.0.js') }}"></script>
<script src="{{ asset('public/admin-assets/js/plugins.js') }}"></script>



<!-- apexcharts -->
<script src="{{ asset('public/admin-assets/libs/apexcharts/apexcharts.min.js') }}"></script>

<!-- Vector map-->
<script src="{{ asset('public/admin-assets/libs/jsvectormap/js/jsvectormap.min.js') }}"></script>
<script src="{{ asset('public/admin-assets/libs/jsvectormap/maps/world-merc.js') }}"></script>

<!--Swiper slider js-->
<script src="{{ asset('public/admin-assets/libs/swiper/swiper-bundle.min.js') }}"></script>

<!-- Dashboard init -->
<script src="{{ asset('public/admin-assets/js/pages/dashboard-ecommerce.init.js') }}"></script>

{{-- Choices js --}}
<script src="{{ asset('public/admin-assets/libs/choices.js/public/assets/scripts/choices.min.js') }}"></script>

{{-- Flatpickr js --}}
<script src="{{ asset('public/admin-assets/libs/flatpickr/flatpickr.min.js') }}"></script>

<!-- Sweet Alerts js -->
<script src="{{ asset('public/admin-assets/libs/sweetalert2/sweetalert2.min.js') }}"></script>
<script src='https://unpkg.com/sweetalert2@7.17.0/dist/sweetalert2.all.js'></script>

<!-- Sweet alert init js-->
<script src="{{ asset('public/admin-assets/js/pages/sweetalerts.init.js') }}"></script>

<!-- notifications init -->
<script src="{{ asset('public/admin-assets/js/pages/notifications.init.js') }}"></script>

<!-- particles js -->
<script src="{{ asset('public/admin-assets/libs/particles.js/particles.js') }}"></script>
<!-- particles app js -->
<script src="{{ asset('public/admin-assets/js/pages/particles.app.js') }}"></script>
<!-- password-addon init -->
<script src="{{ asset('public/admin-assets/js/pages/password-addon.init.js') }}"></script>
{{-- --------------------------------------- --}}
<!--DATATABLE js-->

<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js" defer>
</script>
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"
    defer></script>
<script src="https://cdn.datatables.net/responsive/2.2.9/js/dataTables.responsive.min.js" defer></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js" defer></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.bootstrap5.min.js" defer></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js" defer></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js" defer></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js" defer></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.html5.min.js" defer></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.print.min.js" defer></script>
<script src='https://cdn.datatables.net/buttons/2.4.2/js/buttons.colVis.min.js' defer></script>

<!-- PRISM JS plugin -->
<script src="{{ asset('public/admin-assets/libs/prismjs/prism.js') }}"></script>
<script src="{{ asset('public/admin-assets/js/pages/form-validation.init.js') }}"></script>

{{-- Toasfify Js --}}
<script type='text/javascript' src='https://cdn.jsdelivr.net/npm/toastify-js'></script>

<!--select2-->
<script src="{{ asset('public/admin-assets/js/pages/select2.init.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<!-- App js -->
<script src="{{ asset('public/admin-assets/js/app.js') }}"></script>


<!--SELECTR-->
<script src="https://cdn.jsdelivr.net/gh/mobius1/selectr@latest/dist/selectr.min.js" defer type="text/javascript">
</script>

<!-- Summernote js -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.20/summernote.min.js"
    integrity="sha512-6rE6Bx6fCBpRXG/FWpQmvguMWDLWMQjPycXMr35Zx/HRD9nwySZswkkLksgyQcvrpYMx0FELLJVBvWFtubZhDQ=="
    crossorigin="anonymous" referrerpolicy="no-referrer"></script>

{{-- --------------------------------------- --}}

{{-- Custom Toaster Function --}}
<script>
    function toaster(message, class_name) {
        Toastify({
            newWindow: !0,
            text: message,
            position: "right",
            className: "bg-" + class_name,
            stopOnFocus: !0,
            duration: "4000"
        }).showToast();
    }
</script>


<script>
    // Jquery - selecting all rows 
    $('#allCheckBtn').change(function() {
        if ($(this).prop('checked')) {
            $('tbody tr td input[type="checkbox"]').each(function() {
                $(this).prop('checked', true);
            });
        } else {
            $('tbody tr td input[type="checkbox"]').each(function() {
                $(this).prop('checked', false);
            });
        }
    });
    // --end--   Jquery - selecting all rows 
</script>


