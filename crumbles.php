<script>
    // Initialize Select2 Elements
    $('.productidedit').select2();
    

    // unit seperation by comma function (1,000,000)
    function separatebycomma(input) {
        var input = String(input);
        var position = 1;
        var input_len = input.length;
        var val = 0;
        var counter = 1;
        var fixed = 3;
        var counts = 1;
        var reader = 0;

        var s_input = "";

        while (position < input_len) {
            for (var x = 1; x <= 3; x++) {
                if (reader < input_len) {
                    val = input_len - counts;
                    s_input = input[val] + s_input;
                    counts++;
                }
                reader++;
            }
            if (reader < input_len) {
                if (input[val] != ".") {
                    s_input = "," + s_input;
                }
            }
            position = counter * fixed;
            counter++;
        }
        return s_input;
    }

    function removeCommas(text) {
        // Use a regular expression to globally replace commas with an empty string
        return text.replace(/,/g, '');
    }


    // Get subtotal, discount, tax, net_total, paid_amt, due of all the items in the order table
    function calculate(dis, paid, net_total) {
        var subtotal = 0;
        var discount = dis;
        var tax = 0;
        var tax_percent = 0.05;
        // net_total is the rounded up total
        // var net_total = 0; 
        var paid_amt = paid;
        var due = 0;

        $(".total").each(function() {
            var clean_total = parseFloat($(this).val().replace(/,/g, '')) || 0;
            subtotal += clean_total;
            tax += clean_total;
            net_total = (tax * tax_percent) + subtotal;
        })

        if (due != NaN) {
            due = net_total - discount;
            net_total = due;
        } else {
            due = 0;
        }
        due = net_total - paid_amt;
        // format subtotal with comma
        var frm_subtotal = separatebycomma(subtotal.toFixed(2));
        var frm_tax_total = separatebycomma((tax * tax_percent).toFixed(2));
        var frm_net_total = separatebycomma((net_total).toFixed(2));
        var frm_due = separatebycomma((due).toFixed(2));

        function NegativeNumberWithCommas(due) {
            // Check if the due is negative
            if (due < 0) {
                // Use the toLocaleString() method with appropriate options
                return due.toLocaleString(undefined, {
                    style: 'decimal',
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2
                });
            } else {
                // If the due is not negative, simply format it with commas
                return due.toLocaleString();
            }
        }
        // frm_due = separatebyhyphen((frm_due));

        $("#subtotal").val(frm_subtotal);
        $("#txttax").val(frm_tax_total);
        $("#txtnetttotal").val(frm_net_total);
        $("#txtdiscount").val(discount);
        $("#txtdue").val(NegativeNumberWithCommas(due));

    }
    // Calculate function ends here


    $(document).ready(function(){
        
        $(".productidedit").on('change', function() {
            var tr = $(this).parent().parent();
            var productid = this.value;

            var paid = removeCommas($('#txtpaid').val());
            var net_total = removeCommas($('#txtnetttotal').val());

            function getProduct_ajax (){
                $.ajax({
                    url: "getproduct.php",
                    method: "get",
                    data: {
                        id: productid
                    },
                    success: function(data) {
                        tr.find(".stock").val(data["productstock"]);
                        tr.find(".price").val(data["salesprice"]);
                        tr.find(".qty").val(1);
                        tr.find(".pname").val(data["productname"]); 

                        // tr.find(".total").val(data["salesprice"]);

                        sales_p = data["salesprice"];

                        var qty = tr.find(".qty").val();
                        var price = tr.find(".price").val();
                        var stock = tr.find(".stock").val();
                        var frm_total_1 = separatebycomma(qty * price);
                        tr.find(".total").val(frm_total_1);
                        calculate(0, paid, net_total);


                        // Get the input element by name
                        var qty_input = document.querySelector('input[name="qty[]"]');

                        // stock = qty_input.getAttribute('max');
                        // Get the maximum value using getAttribute
                        qty_input.setAttribute('max', stock);
                        var qty_maxValue = qty_input.getAttribute('max');
                        // Log the maximum value
                    }
                });
            }
        }); 



        var cus_id = 1;
        var sales_p;
        var qty_input_error = "";
        var total = 0;

        $(".btnaddedit").on('click', function(){

            var html = '';
            html += '<tr>';
            html += '<td><input type="text" class="form-control productid productnum" readonly value="<?php echo $cus_id;?>" style="text-align: center;"></td>';
            html += '<td><input type="hidden" name="productname[]" class="form-control pname" readonly></td>'
            html += '<td><select type="text" name="productid[]" class="form-control productidedit"><option value="">Select value</option><?php echo fill_products($pdo, '');?></select></td>'
            html += '<td><input type="text" name="stock[]" class="form-control stock" readonly style="text-align: center;"></td>';
            html += '<td><div class="input-group" id=""><div class="input-group-prepend"> <span class = "input-group-text" > <i class ="fa fa-dollar"> </i></span ></div><input type="text" name="price[]" class="form-control price" readonly ></div></td>';
            html += '<td><input type="number" min="1" max="" name="qty[]" class="form-control qty qty-error-not" style="text-align: center;" value="" required ></td>';
            html += '<td><div class="input-group" id=""><div class="input-group-prepend"> <span class = "input-group-text" > <i class ="fa fa-dollar"> </i></span ></div><input type="text" name="total[]" class="form-control total" value="0" readonly ></div></td>';
            html += '<td><button type="button" class="btn btn-block btn-danger btn-xs btnremove" name="" style="width:fit-content; padding: 7px 15px;"><i class="nav-icon fas fa-minus"></i></button></td>';
            html += '</tr>';


            var html_mob = '';
            html_mob += '<tr>';
            html_mob += '<td><input type="text" class="form-control productid productnum" readonly value="<?php echo $cus_id?>" style="text-align: center;"></td>';
            html_mob += '<td><input type="hidden" name="productname[]" class="form-control pname" readonly></td>'
            html_mob += '<td><select type="text" name="productid[]" class="form-control productidedit"><option value="">Select value</option><?php echo fill_products($pdo, '');?></select></td>'
            html_mob += '<td><input type="text" name="stock[]" class="form-control stock" readonly style="text-align: center;"></td>';
            html_mob += '<td><div class="input-group" id=""><input type="text" name="price[]" class="form-control price" style="text-align:center;" readonly ></div></td>';
            html_mob += '<td><input type="number" min="1" max="" name="qty[]" class="form-control qty qty-error-not" style="text-align: center;" value="" required ></td>';
            html_mob += '<td><div class="input-group" id=""><input type="text" name="total[]" class="form-control total" style="text-align:center;" value="0" readonly ></div></td>';
            html_mob += '<td><button type="button" class="btn btn-block btn-danger btn-xs btnremove" name="" style="width:fit-content; padding: 7px 15px;"><i class="nav-icon fas fa-minus"></i></button></td>';
            html_mob += '</tr>';

            
            var screenwidth = $(window).width();
            if (screenwidth < 768){
                $('#productbody_mobile').append(html_mob);
            } else{
                $('#productbody_desktop').append(html);
                }

            // Increment product id for the next row
            // Initialize Select2 Elements

            
            $('.productidedit').select2();

            $(".productidedit").on('change', function() {
            var tr = $(this).parent().parent();
            var productid = this.value;

            var paid = removeCommas($('#txtpaid').val());
            var net_total = removeCommas($('#txtnetttotal').val());

                $.ajax({
                    url: "getproduct.php",
                    method: "get",
                    data: {
                        id: productid
                    },
                    success: function(data) {
                        tr.find(".stock").val(data["productstock"]);
                        tr.find(".price").val(data["salesprice"]);
                        tr.find(".qty").val(1);
                        tr.find(".pname").val(data["productname"]); 

                        // tr.find(".total").val(data["salesprice"]);

                        sales_p = data["salesprice"];

                        var qty = tr.find(".qty").val();
                        var price = tr.find(".price").val();
                        var stock = tr.find(".stock").val();
                        var frm_total_1 = separatebycomma(qty * price);
                        tr.find(".total").val(frm_total_1);
                        calculate(0, paid, net_total);


                        // Get the input element by name
                        var qty_input = document.querySelector('input[name="qty[]"]');

                        // stock = qty_input.getAttribute('max');
                        // Get the maximum value using getAttribute
                        qty_input.setAttribute('max', stock);
                        var qty_maxValue = qty_input.getAttribute('max');
                        // Log the maximum value
                    }
                });
            }); 
        })

        $(document).on('input', '.qty', function() {
    
            // Get the quantity input value
            $('#txtpaid').val("")
            var tr = $(this).parent().parent();
            var quantityValue = parseFloat($(this).val()) || 0;
            var stock = parseFloat(tr.find(".stock").val()) || 0;
            var paid = removeCommas($('#txtpaid').val());
            var net_total = removeCommas($('#txtnetttotal').val());
            // calculate(0, 0, 0);
    
            // Check if quantity is greater than stock
            if (quantityValue >= stock) {
                $(this).addClass("is-invalid");
                <?php
                echo '
                
                    Swal.fire({
                    position: "top-end",
                    icon: "warning",
                    title: "Stock Limit Reached",
                    button: "OK",
                    timer: 1500
                    })
                
                ';
    
                ?>
            } else {
                $(this).removeClass("is-invalid");
            }
    
            var salesprice = parseFloat(tr.find(".price").val()) || 0;
    
            total = salesprice * quantityValue;
    
            // Format the total value with commas for display
            frm_total = separatebycomma(total);
            tr.find(".total").val(frm_total);
            calculate(0, paid, net_total);
        });
    
    
    
        // Removing a row
        var id_list;
        $(document).on('click', '.btnremove', function() {
            var paid = removeCommas($('#txtpaid').val());
            var net_total = removeCommas($('#txtnetttotal').val());
            $(this).closest('tr').remove();
            $('#txtpaid').val("");
    
            // Recalculate product IDs for remaining rows
            $('.productnum').each(function(index) {
                calculate(0, paid, net_total);
    
                $(this).val(index + 1);
    
                // total count of the id_list remaining
                id_list = $(this).val();
    
            });
    
    
            // reset the cus_id value 
            cus_id = Number(id_list) + 1;
        });

        
        // On discount input
        $(document).on('input', '#txtdiscount', function() {
            var discount = removeCommas($(this).val());
            var total = removeCommas($("#txtnetttotal").val());
            var paid = removeCommas($("#txtpaid").val());
            calculate(discount, paid, total);
        })

        $(document).on('input', '#txtpaid', function(){
        var rawpaid = $(this).val();
        var paid = removeCommas(rawpaid);
        var discount = $('#txtdiscount').val();
        calculate(discount, paid,);
        // Remove existing commas
        var inputValue = event.target.value.replace(/,/g, '');

        
        // Add commas for formatting
        var formattedValue = inputValue.replace(/\B(?=(\d{3})+(?!\d))/g, ',');
        
        // Update the input field with the formatted value
        event.target.value = formattedValue;
   })

    });
</script>