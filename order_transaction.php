<?php
session_start();
use Elasticsearch\ClientBuilder;

require_once '../vendor/autoload.php';
if (isset($_GET['storeid'])) {
    $flag = 1;
    $seller = '';
    $userid = $_GET['storeid'];
    $store_array = array();
    $transactionid = $_GET['transactionid'];
    include_once('includes/includes.php');
    $gettransactiondetail = StoreNextMasterDAO::gettransactiondetail($transactionid);
    foreach ($gettransactiondetail as $data) {
        $lat = $data['lat'];
        $lng = $data['lng'];
        $seller = $data['seller'];
        $store_name = $data['store_name'];
        $productid = $data['productid'];
    }
//    echo $seller.'--'.$store_name;
    mysql_connect("db1.gobuzz.mobi","root","d6@Nbu33!$");
    mysql_select_db('refurbish');
//    mysql_connect("localhost", "root", "root");
//    mysql_select_db('refurbish');
    if ($seller == 'sangeetha') {
        if (isset($store_name) && !empty($store_name)) {

            $flag = 0;

        } else {
            $client = ClientBuilder::create()->build();
            $count = 10;
            // $hid = '';
            $final_data = array();

            if (isset($_REQUEST['lat']))
                $lat = $_REQUEST['lat'];
            if (isset($_REQUEST['lng']))
                $lng = $_REQUEST['lng'];
            if (isset($_REQUEST['count']))
                $count = $_REQUEST['count'];

            function sortByDistance($a, $b)
            {
                return $a['distance'] - $b['distance'];
            }

            function aasort(&$array, $key)
            {
                $sorter = array();
                $ret = array();
                reset($array);
                foreach ($array as $ii => $va) {
                    $sorter[$ii] = $va[$key];
                }
                asort($sorter);
                foreach ($sorter as $ii => $va) {
                    $ret[$ii] = $array[$ii];
                }
                $array = $ret;
            }

            function haversineGreatCircleDistance($latitudeFrom, $longitudeFrom, $latitudeTo, $longitudeTo, $earthRadius = 6371000)
            {
                // convert from degrees to radians
                $latFrom = deg2rad($latitudeFrom);
                $lonFrom = deg2rad($longitudeFrom);
                $latTo = deg2rad($latitudeTo);
                $lonTo = deg2rad($longitudeTo);

                $latDelta = $latTo - $latFrom;
                $lonDelta = $lonTo - $lonFrom;

                $angle = 2 * asin(sqrt(pow(sin($latDelta / 2), 2) +
                        cos($latFrom) * cos($latTo) * pow(sin($lonDelta / 2), 2)));
                return $angle * $earthRadius;
            }

            $query = "select store_name,store_address,mobile_number,email,city,lat,lng FROM sangeetha_store_details";
            $result = mysql_query($query);
            if (mysql_num_rows($result) > 0) {
                while ($row = mysql_fetch_assoc($result)) {
                    $distance = haversineGreatCircleDistance((double)$lat, (double)$lng, (double)$row['lat'], (double)$row['lng'], 6371);
                    $row['distance'] = "" . $distance . "";
                    array_push($final_data, $row);
                }
            }

            if (isset($final_data)) {
                // uasort($final_data,'sortByDistance');
                aasort($final_data, 'distance');
                $final_store_array = array();
                $store_array = array();
                foreach ($final_data as $data) {
                    //        print_r($data);
                    $store = $data['store_name'];
                    $json = '{
                        "query": {
                            "match": {
                                "StoreName": {
                                     "query": "' . $store . '"
                                                }
                                    }
                                    }
                        }';
                    //        echo $json;
                    $params['index'] = 'sangeetha_store';
                    $params['body'] = $json;
                    $results = $client->search($params);
                    $results = json_encode($results);
                    $es_data = json_decode($results, true);

                    $json1 = '{
                        "query": {
                            "match": {
                            "productid": {
                                 "query": ' . $productid . '
                                            }
                                }
                        }
                    }';

                    $params1['index'] = 'sangeetha';
                    $params1['body'] = $json1;
                    $results1 = $client->search($params1);
//                    print_r($results1);
                    $product = $results1['hits']['hits'][0]['_source']['model'];
//                    echo $product;die;
                    if (isset($es_data['hits']['hits'][0]['_source'])) {
                        $es_data_new = $es_data['hits']['hits'][0]['_source'];
                        if (array_key_exists($product, $es_data_new)) {
                            $avail = $es_data_new[$product];
                            if ($avail > 0) {
                                $final_store_array['store'] = $es_data_new['StoreName'];
                                $final_store_array['address'] = $es_data_new['store_address'];
                                $final_store_array['mobile'] = $es_data_new['mobile'];
                                $final_store_array['email'] = $es_data_new['email'];
                                $final_store_array['city'] = $es_data_new['city'];
                                array_push($store_array, $final_store_array);
                            }
                        }
                    } else {
                        //                       echo "Product Not Available";
                    }
                }
            } else {
                echo "No Nearest Location";
            }
//                    print_r($store_array);
        }
        $store_array = array_slice($store_array, 0, 1);
//        echo json_encode($store_array);
    }elseif($seller == 'refurbish'){
        if (isset($store_name) && !empty($store_name)) {
            $flag = 0;
        } else {
            $store_array = array();

            $query = "SELECT username store,address,phone_number mobile,email,city FROM user_details ud JOIN stock_details sd ON ud.id=sd.seller_id WHERE stock_id='$productid'";
            $result = mysql_query($query);
            if (mysql_num_rows($result) > 0) {
                while ($row = mysql_fetch_assoc($result)) {
                    $final_store_array = $row;
                    array_push($store_array, $final_store_array);
                }
            }
//            echo json_encode($store_array);
        }
    }

    ?>

    <div id="page-content-wrapper">
        <div class="container">
            <div class="order_content">
                <div class="product_details">
                    <div class="header">
                        Order Transaction Details
                    </div>
                    <hr/>
                    <div class="row">
                        <?php if ($flag == 0) { ?>
                            <h3>Phone already been booked from seller</h3>
                        <?php } else { ?>
                            <table class="table table-striped">
                                <thead>
                                <th>Store Name</th>
                                <th>Store Address</th>
                                <th>Mobile Number</th>
                                <th>City</th>
                                <th>Book</th>
                                </thead>
                                <tbody>
                                <?php foreach ($store_array as $store) { ?>
                                    <tr>
                                        <td><?php echo $store['store']; ?></td>
                                        <td><?php echo $store['address']; ?></td>
                                        <td><?php echo $store['mobile']; ?></td>
                                        <td><?php echo $store['city']; ?></td>
                                        <td hidden><?php echo $transactionid; ?></td>
                                        <td>
                                            <a href="order_book.php?transactionid=<?php echo $transactionid; ?>&store=<?php echo $store['store']; ?>&seller_type=<?php echo $seller; ?>&storeid=<?php echo $userid; ?>"
                                               class="btn btn-default">BLOCK</a></td>
                                    </tr>
                                <?php } ?>
                                </tbody>
                            </table>
                        <?php } ?>
                    </div>
                    <br/>
                </div>
            </div>
        </div>
    </div>
    </div>
    <script type="application/javascript">
        $('li:contains("Order")').addClass('active');
        $("#menu-toggle").click(function (e) {
            e.preventDefault();
            $("#wrapper").toggleClass("toggled");
        });
        $("#menu-toggle-2").click(function (e) {
            e.preventDefault();
            $("#wrapper").toggleClass("toggled-2");
            $('#menu ul').hide();
        });

        function initMenu() {
            $('#menu ul').hide();
            $('#menu ul').children('.current').parent().show();
            //$('#menu ul:first').show();
            $('#menu li a').click(
                function () {
                    var checkElement = $(this).next();
                    if ((checkElement.is('ul')) && (checkElement.is(':visible'))) {
                        return false;
                    }
                    if ((checkElement.is('ul')) && (!checkElement.is(':visible'))) {
                        $('#menu ul:visible').slideUp('normal');
                        checkElement.slideDown('normal');
                        return false;
                    }
                }
            );
        }
        $(document).ready(function () {
            initMenu();
        });
    </script>
    <?php
}

?>
