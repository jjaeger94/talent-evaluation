<?php

if(!isset($_GET['add']) || $_GET['add'] == false):
global $wpdb;
$jobs_table = $wpdb->prefix . 'te_jobs';
$matching_table = $wpdb->prefix . 'te_matching';
$customers_table = $wpdb->prefix . 'te_customers';
$jobs = $wpdb->get_results($wpdb->prepare("
SELECT j.*,
c.company_name,
(
    SELECT COUNT(*)
    FROM {$matching_table} m
    WHERE m.job_id = j.ID
    AND m.value = 2
) AS positive_matching_count
FROM {$jobs_table} j
JOIN {$customers_table} c ON j.customer_id = c.ID
WHERE customer_id = %d
",$id));
?>
<button class="btn btn-primary mb-3" data-bs-toggle="collapse" data-bs-target="#editCustomerCollapse" aria-expanded="true" aria-controls="editCustomerCollapse">
    Kunden bearbeiten
</button>
<button class="btn btn-primary mb-3" data-bs-toggle="collapse" data-bs-target="#jobsTableCollapse" aria-expanded="false" aria-controls="jobsTableCollapse">
    Jobs anzeigen
</button>
<div class="collapse" id="jobsTableCollapse">
    <div class="card card-body">
        <?php include TE_DIR.'tables/jobs-table-template.php';?>
    </div>
</div>
<div class="collapse show" id="editCustomerCollapse">
    <div class="card card-body">
        <?php include TE_DIR.'forms/customer-form.php';?>
    </div>
</div>
<?php else : ?>
    <?php include TE_DIR.'forms/customer-form.php'; ?>
<?php endif; ?>