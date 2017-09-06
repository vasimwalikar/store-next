<?php
session_start();

    include_once('includes/includes.php');
    $getOrderStatus = StoreNextMasterDAO::getOrderStatusFn();
    ?>

    <div id="page-content-wrapper">
        <div class="container">
            <div class="order_content">
                <div class="product_details">
                    <!--                    <div class="header">-->
                    <!--                        Order Details-->
                    <!--                    </div>-->
                    <hr />
                    <?php
                    $i=0;
                    foreach($getOrderStatus as $status) {
                        $i++;
                        $process1 = 'processing'.$i;
                        $process2 = 'approved'.$i;
                        $process3 = 'shipping'.$i;
                        $process4 = 'delivery'.$i;
                        $process5 = 'cancel'.$i;
                        $transname = 'processing'.$i.'trans';
                        $transname1 = 'approved'.$i.'trans';
                        $transname2 = 'shipping'.$i.'trans';
                        $transname3 = 'delivery'.$i.'trans';
                        $transname4 = 'cancel'.$i.'trans';
                        ?>
                        <div class="row">
                            <div class="col-sm-12 col-md-12 col-xs-12">
                                <div class="product_header">
                                    <div class="row trans_header">
                                        <div class="col-sm-5 col-xs-5 col-md-4 transid">
                                            <span class="details1"> Order ID: </span>
                                            <a href="order_transaction.php?transactionid=<?php echo $status['transaction_id']; ?>">
                                                <input type="button" class="btn btn-default <?php echo $transname.' '.$transname1.' '.$transname2.' '.$transname3.' '.$transname4;  ?>" value="<?php echo $status['transaction_id']; ?>"></a>
                                        </div>
                                        <div class="col-sm-4 col-md-4 col-xs-4 col-sm-offset-2">
                                            <h4 class="seller">Product: <span class="details"><?php echo $status['product'] ?></span></h4>
                                        </div>

                                    </div>
                                    <div class="row">
                                        <div class="col-sm-1 col-xs-1 col-md-1 col-md-offset-6 col-sm-offset-6 col-xs-offset-6 trans_process">
                                            Ordered
                                        </div>
                                        <div class="col-sm-1 col-xs-1 col-md-1 trans_process">
                                            Approved
                                        </div>
                                        <div class="col-sm-1 col-xs-1 col-md-1 trans_process">
                                            Shipping
                                        </div>
                                        <div class="col-sm-1 col-xs-1 col-md-1 trans_process">
                                            Delivery
                                        </div>
                                        <div class="col-sm-1 col-xs-1 col-md-1 trans_process">
                                            Cancel
                                        </div>
                                    </div>
                                    <br />
                                    <div class="row">
                                        <div class="col-sm-1 col-md-1 col-xs-1 col-md-offset-1 col-sm-offset-1 col-xs-offset-1 phone_image">
                                            <img src="<?php echo $status['image']; ?>" width="40px" height="50px"/>

                                            <!--                                    Product Name: --><?php //echo $status['product'] ?>
                                            <!--                                    Product ID: --><?php //echo $status['productid'] ?>
                                        </div>
                                        <div class="col-sm-4 col-xs-4 col-md-4">
                                            <!--                                    <h6>Product Name: <span class="details"><?php echo $status['product'] ?></span></h6>-->
                                            <h6>Product ID: <span class="details2"><?php echo $status['productid'] ?></span></h6>
                                            <h6>Retailer Name: <span class="details2"><?php echo $status['location_name'] ?></span></h6>
                                            <h6>Seller Mobile: <span class="details2"><?php echo $status['store_name'] ?></span></h6>
                                        </div>
                                        <div class="col-sm-1 col-xs-1 col-md-1  trans_process1">
                                            <a href="#" class="processing" id="<?php echo $process1; ?>">
                                                <div  class="<?php if($status['processing_status'] == 'done') { echo "circle"; } else { echo "circle1"; } ?>">
                                                </div>
                                            </a>
                                        </div>
                                        <div class="col-sm-1 col-xs-1 col-md-1  trans_process1">
                                            <a href="#" class="approved" id="<?php echo $process2; ?>">
                                                <div class="<?php if($status['approved_status'] == 'done') { echo "circle"; } else { echo "circle1"; } ?>">
                                                </div>
                                            </a>
                                        </div>
                                        <div class="col-sm-1 col-xs-1 col-md-1  trans_process1">
                                            <a href="#" class="shipping" id="<?php echo $process3; ?>">
                                                <div class="<?php if($status['shipping_status'] == 'done') { echo "circle"; } else { echo "circle1"; } ?>">
                                                </div>
                                            </a>
                                        </div>
                                        <div class="col-sm-1 col-xs-1 col-md-1  trans_process1">
                                            <a href="#" class="delivery" id="<?php echo $process4; ?>">
                                                <div class="<?php if($status['delivery_status'] == 'done') { echo "circle"; } else { echo "circle1"; } ?>">
                                                </div>
                                            </a>
                                        </div>
                                        <div class="col-sm-1 col-xs-1 col-md-1  trans_process1">
                                            <a href="#" class="cancel" id="<?php echo $process5; ?>">
                                                <div class="circle1">
                                                </div>
                                            </a>
                                        </div>
                                    </div>

                                    <br />
                                </div>
                            </div>
                        </div>
                        <br />
                    <?php } ?>

                    <br />
                </div>
            </div>
        </div>
    </div>
    <script type="application/javascript" src="assets/js/bootbox.js"></script>
    <script type="application/javascript">
        $("a").click(function(event) {
            var status = $(this).attr('id');
            var status_toupdate = $(this).attr('class');
//            alert(status_toupdate)
            var cur_status = $('#' + status).children().attr('class');
//            alert(cur_status)
            var trans = status + 'trans';
            var transid = $('.' + trans).val();
//            alert(transid);
            if(status_toupdate == 'approved' && cur_status == 'circle1'){
                window.location = "order_transaction.php?transactionid="+transid;
            }else if(status_toupdate == 'cancel'){
                bootbox.confirm("Sure want to cancel?", function (result) {
                    if (result == true) {
                        var formData = new FormData();
                        formData.append('update_status1', '');
                        formData.append('transid', transid);
                        formData.append('status', status_toupdate);
                        $.ajax({
                            url: 'api.php',
                            type: 'post',
                            data: formData,
                            contentType: false,
                            cache: false,
                            processData: false,
                            success: function (data, textstatus, jqXHR) {
                                if (data == 'success') {
                                    location.reload();
                                }
                            }
                        });
                    }
                });
            }
            else {
                if (cur_status == 'circle') {
                    bootbox.alert("Status already been updated", function () {
                    });
                } else if (cur_status == 'circle1') {
                    bootbox.confirm("Update status to next stage?", function (result) {
                        if (result == true) {
                            var formData = new FormData();
                            formData.append('update_status1', '');
                            formData.append('transid', transid);
                            formData.append('status', status_toupdate);
                            $.ajax({
                                url: 'api.php',
                                type: 'post',
                                data: formData,
                                contentType: false,
                                cache: false,
                                processData: false,
                                success: function (data, textstatus, jqXHR) {
                                    if (data == 'success') {
                                        $('#' + status).children().removeClass('circle1');
                                        $('#' + status).children().addClass('circle');
                                        $.toast({
                                            heading: 'SUCCESS',
                                            text: 'Order Status Updated',
                                            position: 'bottom-right',
                                            icon: 'success'
                                        });
                                    }
                                }
                            });
                        }
                    });
                }
            }
        });

        $('li:contains("Order")').addClass('active');
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

