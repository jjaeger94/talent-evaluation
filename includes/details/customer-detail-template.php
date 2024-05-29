<?php

if(!isset($_GET['add']) || $_GET['add'] == false):
global $wpdb;
$jobs_table = $wpdb->prefix . 'te_jobs';
$jobs = $wpdb->get_results($wpdb->prepare(
    "SELECT * FROM $jobs_table WHERE customer_id = %d ORDER BY added DESC",
    $id
));
?>
<button class="btn btn-primary mb-3" type="button" data-bs-toggle="collapse" data-bs-target="#editCustomerCollapse" aria-expanded="true" aria-controls="editCustomerCollapse">
    Kunden bearbeiten
</button>
<button class="btn btn-primary mb-3" type="button" data-bs-toggle="collapse" data-bs-target="#jobsTableCollapse" aria-expanded="false" aria-controls="jobsTableCollapse">
    Jobs anzeigen
</button>
<div class="collapse" id="jobsTableCollapse">
    <div class="card card-body">
        <?php include TE_DIR.'tables/jobs-table-template.php';?>
    </div>
</div>
<div class="collapse" id="editCustomerCollapse">
    <div class="card card-body">
        <?php include TE_DIR.'forms/customer-form.php';?>
    </div>
</div>
<?php else : ?>
    <?php include TE_DIR.'forms/customer-form.php'; ?>
<?php endif; ?>