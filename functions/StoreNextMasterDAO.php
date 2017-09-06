<?php
class StoreNextMasterDAO extends AbstractDAO {

    public static function checkLogin($un,$pw_new){
        $query="SELECT * FROM location_details where mobile_number=:un and password=:pw";
        $result=self::fetchQuery($query,array('un'=>$un,'pw'=>$pw_new));
        return $result;
    }
    public static function getOrderStatus($userid){
        $query = "SELECT transaction_id,image,store_id,GROUP_CONCAT(order_status,'-',log_time) as state FROM
                  (SELECT pd.phone_id,image,store_id,os.transaction_id,order_status,os.log_time FROM product_details pd JOIN consumer_shipping_details csd ON
                  pd.phone_id=csd.phone_id JOIN order_status os ON csd.transaction_id=os.transaction_id  WHERE store_id=:userid) a GROUP BY transaction_id";
        $result = self::fetchQuery($query,array('userid'=>$userid));
        return $result;
    }
    public static function getOrderStatusNew($userid){
        $query = "SELECT csd.transaction_id,product,s_user_name,s_mobile,productid,store_id,processing_status,processing_log_time,approved_status,approved_log_time,shipping_status,
                    shipping_log_time,delivery_status,delivery_log_time,store_name FROM order_status_new osn JOIN consumer_shipping_details csd
                    ON osn.transaction_id=csd.transaction_id WHERE store_id=:storeid AND (final_status!='completed' AND final_status!='cancelled' OR final_status IS NULL OR final_status='') order by csd.log_time desc";
        $result = self::fetchQuery($query,array('storeid'=>$userid));
        return $result;
    }
    public static function getOrderStatusFn(){
        $query = "SELECT location_name,csd.transaction_id,product,image,productid,store_id,processing_status,processing_log_time,approved_status,approved_log_time,shipping_status,
shipping_log_time,delivery_status,delivery_log_time,store_name FROM consumer_shipping_details csd JOIN order_status_new osn ON csd.transaction_id=osn.transaction_id JOIN product_details pd ON
csd.phone_id=pd.phone_id JOIN location_details ld ON csd.store_id=ld.id WHERE shipping_status IS NULL AND delivery_status IS NULL AND processing_status IS NOT NULL AND approved_status IS NOT NULL
AND (final_status IS NULL OR final_status='')";
        $result = self::fetchQuery($query);
        return $result;
    }
    public  static function getPendingOrder($userid){
        $query = "SELECT transaction_id FROM consumer_shipping_details WHERE store_id=:userid AND transaction_id NOT IN
                  (SELECT transaction_id FROM order_status_new WHERE delivery_status='done' or final_status='completed')";
        $result = self::fetchQuery($query,array('userid'=>$userid));
        return $result;
    }
    public static function getLastStatus($transaction){
        $query = "select processing_status,approved_status,shipping_status,delivery_status from order_status_new where transaction_id=:transaction order by id desc limit 1";
        $result = self::fetchQuery($query,array('transaction'=>$transaction));
        return $result;
    }
    public static function updateStatus($transaction,$status,$status_new){
//        $query = "insert into order_status (order_status,transaction_id) values (:status,:transaction)";
        $query = "update order_status_new set $status_new='done',$status=NOW() where transaction_id=:transaction";
        $result = self::updateQuery($query,array('transaction'=>$transaction));
        return $result;
    }
    public static function updateStatusForDelivery($transaction, $status, $status_new){
//        $query = "insert into order_status (order_status,transaction_id) values (:status,:transaction)";
        $query = "update order_status_new set $status_new='done',$status=NOW(),final_status='completed' where transaction_id=:transaction";
        $result = self::updateQuery($query,array('transaction'=>$transaction));
        return $result;
    }
    public static function getOrderIdStatus($transaction){
        $query = "SELECT store_order_id FROM consumer_shipping_details WHERE transaction_id=:transaction";
        $result = self::fetchQuery($query,array('transaction'=>$transaction));
        return $result[0]['store_order_id'];
    }
    public static function updateOrderId($transaction,$orderid){
        $query = "update consumer_shipping_details set store_order_id=:orderid WHERE transaction_id=:transaction";
        $result = self::updateQuery($query,array('transaction'=>$transaction,'orderid'=>$orderid));
        return $result;
    }
    public static function gettransactiondetail($transactionid){
        $query = "SELECT lat,lng,store_name,seller,productid FROM consumer_shipping_details WHERE transaction_id=:transactionid";
        $result = self::fetchQuery($query,array('transactionid'=>$transactionid));
        return $result;
    }
    public static function updateSeller($transid,$store){
        $query = "update consumer_shipping_details set store_name=:seller where transaction_id=:transactionid";
        $result = self::updateQuery($query,array('transactionid'=>$transid,'seller'=>$store));
        return $result;
    }
    public static function getStoreDetails($transid){
        $query = "SELECT mobile_number,email,address,location_name,state,city,pincode FROM location_details ld JOIN consumer_shipping_details csd ON ld.id=csd.store_id WHERE transaction_id=:transid";
        $result = self::fetchQuery($query,array('transid'=>$transid));
        return $result;
    }
    public static function getSellerDetails($store){
        $query = "SELECT mobile_number,email,store_address FROM sangeetha_store_details WHERE store_name=:store";
        $result = self::fetchQuery($query,array('store'=>$store));
        return $result;
    }
    public static function getSellerDetailsRefurbish($store){
        $query = "SELECT phone_number mobile_number,email,address store_address FROM user_details WHERE username=:store limit 1";
        $result = self::fetchQuery($query,array('store'=>$store));
        return $result;
    }
    public static function getConsumerDetails($transid){
        $query = "select s_user_name,s_mobile,s_email,delivery_type,product,productid from consumer_shipping_details where transaction_id=:transid";
        $result = self::fetchQuery($query,array('transid'=>$transid));
        return $result;
    }
    public static function getSellerOrderDetails($transid,$store){
        $query = "insert into sangeetha_order_status (transaction_id,store_id) values (:transaction,:store)";
        $result = self::insertQuery($query,array('transaction'=>$transid,'store'=>$store));
        return $result;
    }
    public static function getStoreIdForSang($transid){
        $query = "SELECT store_id FROM consumer_shipping_details WHERE transaction_id=:transid";
        $result = self::fetchQuery($query,array('transid'=>$transid));
        return $result[0]['store_id'];
    }
    public static function getUserName($transid){
        $query = "select s_user_name,s_mobile,s_email,product from consumer_shipping_details where transaction_id=:transid";
        $result = self::fetchQuery($query,array('transid'=>$transid));
        return $result;
    }
    public static function updateOrderStatusApproved($transid){
        $query = "update order_status_new set approved_status='done',approved_log_time=NOW() where transaction_id=:transid";
        $result = self::updateQuery($query,array('transid'=>$transid));
        return $result;
    }
    public static function updateOrderStatusDelivery($transid){
        $query = "update order_status_new set delivery_status='done',delivery_log_time=NOW(),final_status='completed' where transaction_id=:transid";
        $result = self::updateQuery($query,array('transid'=>$transid));
        return $result;
    }
    public static function updateOrderStatusShipping($transid){
        $query = "update order_status_new set shipping_status='done',shipping_log_time=NOW() where transaction_id=:transid";
        $result = self::updateQuery($query,array('transid'=>$transid));
        return $result;
    }
    public static function getSellerDetailsNew($transid){
        $query = "SELECT mobile_number,email FROM sangeetha_store_details ssd JOIN consumer_shipping_details csd ON ssd.store_name=csd.store_name WHERE transaction_id=:transid";
        $result = self::fetchQuery($query,array('transid'=>$transid));
        return $result;
    }
    public static function updateOrderStatusCancel($transid){
        $query = "update order_status_new set final_status='cancelled' where transaction_id=:transid";
        $result = self::updateQuery($query,array('transid'=>$transid));
        return $result;
    }
}
?>
