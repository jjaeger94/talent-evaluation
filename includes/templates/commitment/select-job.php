<!-- select-job.php -->
<div class="container">
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <div class="card mt-5">
                <div class="card-body">
                    <h5 class="card-title">Bitte wählen Sie Ihre Bewerbungsstelle</h5>
                    <p class="card-text">Bitte wählen Sie aus der untenstehenden Dropdown-Liste die Stelle aus, auf die Sie sich beworben haben, und bestätigen Sie Ihre Auswahl mit einem Klick auf 'Stelle bestätigen'.</p>
                    <form method="get" action="<?php echo home_url('/test-methode/') ?>">
                    <div class="form-group row">
                            <div class="col-md-8">
                                <select class="form-select" id="jid" name="jid">
                                    <?php foreach ( $jobs as $job ) : ?>
                                        <option data-hash="<?php echo esc_attr( $job->hash ); ?>" value="<?php echo esc_attr( $job->ID ); ?>" <?php selected( $jid, $job->ID ); ?>><?php echo esc_html( $job->job_title ); ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-md-4 align-self-end">
                                <button type="submit" class="btn btn-primary">Stelle bestätigen</button>
                            </div>
                        </div>
                        <!-- Verstecktes Feld für den Schlüssel -->
                        <input type="hidden" name="key" id="job_hash">
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
jQuery(document).ready(function($) {
    $('#jid').change(function() {
        var selectedHash = $(this).find('option:selected').data('hash');
        $('#job_hash').val(selectedHash);
    }).change();
});
</script>
