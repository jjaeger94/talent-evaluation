<button class="btn btn-primary mb-3" data-bs-toggle="collapse" data-bs-target="#controlCollapse" aria-expanded="true" aria-controls="controlCollapse">
Aktionen
</button>
<button class="btn btn-primary mb-3" data-bs-toggle="collapse" data-bs-target="#tableCollapse" aria-expanded="false" aria-controls="tableCollapse">
Tabelle
</button>
<div class="collapse" id="controlCollapse">
    <?php include TE_DIR.'controls/compare.php'; ?>
</div>
<div class="collapse" id="tableCollapse">
    <div class="card card-body">
        <?php include TE_DIR.'tables/compare-table-template.php'; ?>
    </div>
</div>