        <!-- Main Footer -->
        <footer class="main-footer">
            <!-- To the right -->
            <div class="float-right d-none d-sm-inline">
                Inventory POS system V.1.0
            </div>
            <!-- Default to the left -->
            <strong>Copyright &copy; <a href="#myPortfolio">Olaniyan Segun</a>.</strong> All rights reserved.
        </footer>

        <!-- SCRIPT FOR DATE PICKER -->
        <!-- Select2 -->
        <script src="plugins/select2/js/select2.full.min.js"></script>
        <!-- InputMask -->
        <script src="plugins/moment/moment.min.js"></script>
        <script src="plugins/inputmask/jquery.inputmask.min.js"></script>
        <!-- date-range-picker -->
        <script src="plugins/daterangepicker/daterangepicker.js"></script>
        <!-- Tempusdominus Bootstrap 4 -->
        <script src="plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js"></script>
        <!-- BS-Stepper -->
        <script src="plugins/bs-stepper/js/bs-stepper.min.js"></script>
        <!-- dropzonejs -->
        <script src="plugins/dropzone/min/dropzone.min.js"></script>
        <!-- Page specific script -->
        <script>
            $(function() {
                //Initialize Select2 Elements
                $('.select2').select2()

                //Initialize Select2 Elements
                $('.select2bs4').select2({
                    theme: 'bootstrap4'
                })

                //Date picker
                $('#reservationdate').datetimepicker({
                    format: 'YYYY-MM-DD',
                    // data_date_format : "mm-dd-yyyy"
                    // locale: {
                    //     format: 'dd-mm-YYYY',
                    // }
                });

                $('#reservationdate2').datetimepicker({
                    format: 'YYYY-MM-DD',
                    // data_date_format : "mm-dd-yyyy"
                    // locale: {
                    //     format: 'dd-mm-YYYY',
                    // }
                });
                // $('#reservationdate').datepicker();

                //Date and time picker
                $('#reservationdatetime').datetimepicker({
                    icons: {
                        time: 'far fa-clock'
                    }
                });

                //Date range picker
                $('#reservation').daterangepicker()
                //Date range picker with time picker
                $('#reservationtime').daterangepicker({
                    timePicker: true,
                    timePickerIncrement: 30,
                    locale: {
                        format: 'MM-DD-YYYY hh:mm A'
                    }
                })
                //Date range as a button
                $('#daterange-btn').daterangepicker({
                        ranges: {
                            'Today': [moment(), moment()],
                            'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                            'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                            'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                            'This Month': [moment().startOf('month'), moment().endOf('month')],
                            'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
                        },
                        startDate: moment().subtract(29, 'days'),
                        endDate: moment()
                    },
                    function(start, end) {
                        $('#reportrange span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'))
                    }
                )

                //Timepicker
                $('#timepicker').datetimepicker({
                    format: 'LT'
                })


            })
        </script>
        <!-- /.SCRIPT FOR DATE PICKER -->

        </div>
        <!-- ./wrapper -->

        <!-- REQUIRED SCRIPTS -->

        </body>

        </html>