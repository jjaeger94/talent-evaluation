<div class="container">
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <div class="card mt-5">
                <div class="card-body">
                    <h5 class="card-title">Ihre Bewerbungsstelle</h5>
                    <p class="card-text">Bitte bestätigen Sie die Stelle, um fortzufahren.</p>
                    <div class="row">
                        <div class="col-md-12">
                            <p><strong>Stellenbezeichnung:</strong></p>
                            <p><?php echo esc_html( $job->job_title ); ?></p>
                        </div>
                    </div>
                    <form method="get" action="<?php echo home_url('/test-methode/') ?>">
                        <div class="form-check mb-3">
                            <input class="form-check-input" type="checkbox" value="" id="dataProcessingCheckbox" name="dataProcessingCheckbox" required>
                            <label class="form-check-label" for="dataProcessingCheckbox">
                                Ich stimme der Verarbeitung meiner Daten zu. <a href="/datenschutzerklaerung">Datenschutzerklärung</a>
                            </label>
                        </div>
                        <?php if(isset($aid)): ?>
                            <input type="hidden" name="aid" value="<?php echo esc_attr( $aid ); ?>">
                        <?php else: ?>
                            <input type="hidden" name="jid" value="<?php echo esc_attr( $jid ); ?>">
                        <?php endif; ?>
                        <input type="hidden" name="key" value="<?php echo esc_attr( $key ); ?>">
                        <button type="submit" class="btn btn-primary mt-3">Stelle bestätigen</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
