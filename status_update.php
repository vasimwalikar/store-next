<?php
session_start();
if(isset($_SESSION['userid'])) {
    $userid = $_SESSION['userid'];
    include_once('includes/includes.php');
    $getPendingOrder = StoreNextMasterDAO::getPendingOrder($userid);
    ?>

    <div id="page-content-wrapper">
        <div class="container">
            <div class="order_content">
                <div class="product_details">
                    <div class="header">
                        Update Order ID
                    </div>
                    <hr />
                    <form name="form1" id="form1">
                        <div class="row">
                            <div class="form-group">
                                <label for="transactionid" class="col-sm-3 control-label text-right">Select Transaction ID*:</label>
                                <div class="col-sm-6">
                                    <select class="col-sm-3 form-control" name="transactionid1" id="transactionid1" required>
                                        <option></option>
                                        <?php foreach($getPendingOrder as $main) {
                                            ?>
                                            <option value="<?php echo $main['transaction_id'] ?>"><?php echo $main['transaction_id'] ?></option>
                                        <?php }  ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group">
                                <label for="transactionid" class="col-sm-3 control-label text-right">Update Store Order ID:</label>
                                <div class="col-sm-6">
                                    <input type="text" name="orderid" id="orderid" class="col-sm-6" readonly>
                                </div>
                            </div>
                            <div class="col-sm-3 note">
                                *Order Id not updated. Please Update.
                            </div>
                        </div>
                        <br>
                        <div class="col-sm-3 col-xs-3 col-md-3 col-md-offset-4 col-xs-offset-4 col-sm-offset-4">
                            <input type="submit" name="submit" value="Update" class="btn btn-default">
                        </div>
                        <br />
                        <hr />
                    </form>
                    <div class="header">
                        Update Status
                    </div>
                    <form name="form" id="form">
                    <div class="row">
                        <div class="form-group">
                            <label for="transactionid" class="col-sm-3 control-label text-right">Select Transaction ID*:</label>
                            <div class="col-sm-6">
                                <select class="col-sm-6 form-control" name="transactionid" id="transactionid" required>
                                    <option></option>
                                    <?php foreach($getPendingOrder as $main) {
                                        ?>
                                        <option value="<?php echo $main['transaction_id'] ?>"><?php echo $main['transaction_id'] ?></option>
                                    <?php }  ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group">
                            <label for="appname" class="col-sm-3 control-label text-right">Current Status*:</label>
                            <div class="col-sm-6">
                                <select class="col-sm-6 form-control" name="prev_status" id="prev_status" disabled>
                                </select>
                            </div>
                        </div>
                    </div>
                        <div class="row">
                            <div class="form-group">
                                <label for="appname" class="col-sm-3 control-label text-right">Next Status*:</label>
                                <div class="col-sm-6">
                                    <select class="col-sm-6 form-control" name="status" id="status" disabled>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <br>
                    <div class="col-sm-3 col-xs-3 col-md-3 col-md-offset-4 col-xs-offset-4 col-sm-offset-4">
                        <input type="submit" name="submit" value="Update" class="btn btn-default">
                    </div>
                    <br />
                </div>
            </div>
        </div>
    </div>
    </div>
    <script type="application/javascript">
        $('#form').submit(function (e) {
            e.preventDefault();
            var transactionid = $('#transactionid').val();
            var status = $('#status').val();
            var formData1 = new FormData();
            formData1.append('update','');
            formData1.append('transid',transactionid);
            formData1.append('status',status);
//            alert(status)
            $.ajax({
                url: 'api.php',
                type: 'post',
                data: formData1,
                contentType: false,
                cache: false,
                processData: false,
                success: function(data, textstatus, jqXHR){
                    if(data == 'success'){
//                        $('#transactionid').empty();
                        $('#prev_status').empty();
                        $('#status').empty();
                        $.toast({
                            heading: 'SUCCESS',
                            text: 'Status Updated',
                            position: 'bottom-right',
                            icon: 'success'
                        });
                    }
                }
            })
        });
        $('#form1').submit(function (e) {
            e.preventDefault();
            var transactionid = $('#transactionid1').val();
            var orderid = $('#orderid').val();
            var formData2 = new FormData();
            formData2.append('update_orderid','');
            formData2.append('transid',transactionid);
            formData2.append('orderid',orderid);
            $.ajax({
                url: 'api.php',
                type: 'post',
                data: formData2,
                contentType: false,
                cache: false,
                processData: false,
                success: function(data, textstatus, jqXHR){
                    if(data == 'success'){
                        $('#transactionid1').empty();
                        $('#orderid').val('');
                        $('.note').css('display','none');
                        $('#orderid').attr('readonly',true);
                        $.toast({
                            heading: 'SUCCESS',
                            text: 'Order ID Updated',
                            position: 'bottom-right',
                            icon: 'success'
                        });
                    }
                }
            })
        });
        $('#transactionid1').change(function(){
//            alert(this.value);
            var trans_val = this.value;
            var formData = new FormData();
            formData.append('get_order_status','');
            formData.append('trans_id',trans_val);
            $.ajax({
                url: 'api.php',
                type: 'post',
                data: formData,
                contentType: false,
                cache: false,
                processData: false,
                success: function(data, textstatus, jqXHR){
                    $('.note').css('display','none');
		//	alert(data);
                    if(data == 'not updated') {
                        $('#orderid').val('');
                        $('#orderid').removeAttr('readonly');
                        $('.note').css('display','block');
                    }else{
			$('#orderid').attr('readonly');
                        $('#orderid').val(data);
                    }
                }
            })
        });
        $('#transactionid').change(function(){
//            alert(this.value);
            var trans_val = this.value;
//            alert(trans_val);
            var formData = new FormData();
            formData.append('get_status','');
            formData.append('trans_id',trans_val);
            $.ajax({
                url: 'api.php',
                type: 'post',
                data: formData,
                contentType: false,
                cache: false,
                processData: false,
                success: function(data, textstatus, jqXHR){
//                    alert(data);
                    $('#prev_status').empty();
                    $('#status').empty();
                    $('#prev_status').append('<option value='+data+'>'+data+'</option>');
                    $('#status').removeAttr('disabled');
                    if(data == 'processing')
                        var state = 'approved';
                    else if(data == 'approved')
                        var state = 'shipping';
                    else if(data == 'shipping')
                        var state = 'delivery';
                    $('#status').append('<option value='+state+'>'+state+'</option>');
                }
            })
        });
        $('li:contains("Status")').addClass('active');
        $("#menu-toggle").click(function(e) {
            e.preventDefault();
            $("#wrapper").toggleClass("toggled");
        });
        $("#menu-toggle-2").click(function(e) {
            e.preventDefault();
            $("#wrapper").toggleClass("toggled-2");
            $('#menu ul').hide();
        });

        function initMenu() {
            $('#menu ul').hide();
            $('#menu ul').children('.current').parent().show();
            //$('#menu ul:first').show();
            $('#menu li a').click(
                function() {
                    var checkElement = $(this).next();
                    if((checkElement.is('ul')) && (checkElement.is(':visible'))) {
                        return false;
                    }
                    if((checkElement.is('ul')) && (!checkElement.is(':visible'))) {
                        $('#menu ul:visible').slideUp('normal');
                        checkElement.slideDown('normal');
                        return false;
                    }
                }
            );
        }
        $(document).ready(function() {initMenu();});
    </script>
    <?php
}else{
    header('Location: index.html');
}

?>
