<?php if(!isset($_GET['add']) || $_GET['add'] == false): ?>
<button class="btn btn-primary mb-3" type="button" data-bs-toggle="collapse" data-bs-target="#jobInfoCollapse" aria-expanded="false" aria-controls="jobInfoCollapse">
    Job Infos bearbeiten
</button>
<button class="btn btn-primary mb-3" type="button" data-bs-toggle="collapse" data-bs-target="#requirementCollapse" aria-expanded="false" aria-controls="requirementCollapse">
    Anforderung hinzufügen/bearbeiten
</button>
<button class="btn btn-primary mb-3" type="button" data-bs-toggle="collapse" data-bs-target="#tableCollapse" aria-expanded="false" aria-controls="tableCollapse">
Liste anzeigen
</button>
<div class="collapse" id="jobInfoCollapse">
    <div class="card card-body">
    <?php include TE_DIR.'forms/job-form.php';?>
    </div>
</div>
<div class="collapse" id="requirementCollapse">
    <div class="card card-body">
        <?php include TE_DIR.'filters/requirements-list.php'; ?>
    </div>
</div>
<div class="collapse" id="tableCollapse">
    <div class="card card-body">
        <?php include TE_DIR.'tables/talents-job-table-template.php';?>
    </div>
</div>
 <?php    
else:
include TE_DIR.'forms/job-form.php';
endif;
?>