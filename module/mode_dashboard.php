<?php
// mode_dashboard.php

require "config/config.php";

function getUserCount() {
    return getData("SELECT COUNT(user_id) as count FROM tb_user")[0]['count'];
}

function getSupplierCount() {
    return getData("SELECT COUNT(id_supplier) as count FROM tb_supplier")[0]['count'];
}

function getCustomerCount() {
    return getData("SELECT COUNT(id_customer) as count FROM tb_customer")[0]['count'];
}

function getBahanCount() {
    return getData("SELECT COUNT(id_bahan) as count FROM tb_bahan")[0]['count'];
}

?>
