<div class="chat-bar fixed-top" style="z-index: 1000;">
    <div class="container">
        <div class="row align-items-center">
            <!-- Bild von Dieter -->
            <div class="col-2">
                <img src="<?php echo $game->image_url; ?>" class="img-fluid rounded-circle" style="width: 50px;">
            </div>
            <!-- Name von Dieter -->
            <div class="col-8 text-center">
                <h4><?php echo $game->title; ?></h4>
            </div>
            <!-- Icon mit Fragezeichen -->
            <div class="col-2 text-right">
                <div class="menu-button" id="help-chat-open">
                    <i class="fa-regular fa-circle-question"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal für Tipps -->
<div class="modal fade" id="tipModal" tabindex="-1" role="dialog" aria-labelledby="tipModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="tipModalLabel"><?php echo isset($game->info_title) ? $game->info_title : 'Tipps';?></h5>
                <button class="btn-close" id="help-btn-close" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <?php echo isset($game->info_msg) ? $game->info_msg : 'Tipps' ?>
                <?php include_once 'product-view.php'; ?>
            </div>
        </div>
    </div>
</div>
<script>
jQuery(document).ready(function($) {
    $('#help-chat-open').click(function() {
        $('#tipModal').modal('show');
    });
    $('#help-btn-close').click(function() {
        $('#tipModal').modal('hide');
    });
});
</script>
