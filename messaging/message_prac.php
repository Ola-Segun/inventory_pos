<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- jQuery -->
    <script src="plugins/jquery/jquery.min.js"></script>
    <title>Document</title>
</head>
<body>
    <?php 
    // include_once 'adminheader.php';
    include_once 'connectdb.php';
    session_start();


    function fill_user($pdo)
{
    $output = '';
    $select = $pdo->prepare("select username from tbl_user order by username asc");
    $select->execute();

    $result = $select->fetchAll();

    foreach ($result as $row) {
        $output .= '<option value="' . $row["id"] . '">' . $row["username"] . '</option>';
    }

    return $output;
}
    
    $username = $_POST['username']
    ?>
    <div class="success_msg">

    </div>
    <input type="text" name="username" class="username" placeholder="your name">
    <br>
    <hr>
    <input type="text" name="message" id="message" placeholder="Enter Message">
    <input type="submit" value="Send" class="sendmessage">


    <form action="">
        <!-- <td>
            <select type="text" name="productid[]" class="form-control productid">
                <option value="">Select value</option>
                <?php echo fill_user($pdo);?>
            </select>
        </td> -->




    </form>



<script>
    $(".sendmessage").on('click', function() {
        var tr = $(this).parent().parent();
        var productid = this.value;
        var name = $('.username').val();
        $('#message').val(name);
        // var message = $("#message")
        

        $.ajax({
            url: "send_message.php",
            method: "post",
            // data: {
            //     id: productid
            // },
            success: function(data) {
                // tr.find(".stock").val(data["productstock"]);

            }
        });
    });
</script>


</body>
</html>