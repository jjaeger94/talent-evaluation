<form method="get">
    <div class="form-group row">
        <div class="col-md-6">
            <label for="job_id" class="col-form-label">Stelle:</label>
            <select class="form-control" id="job_id" name="job_id">
                <option value="">Alle Stellen</option>
                <?php foreach ( $jobs as $job ) : ?>
                    <option value="<?php echo esc_attr( $job->ID ); ?>" <?php selected( $selected_job, $job->ID ); ?>><?php echo esc_html( $job->job_title ); ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col-md-2 align-self-end">
            <button type="submit" class="btn btn-primary">Filtern</button>
        </div>
    </div>
</form>

<?php if ( ! empty( $candidates ) ) : ?>
    <div class="table-responsive">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Kandidat</th>
                    <th>Stelle</th>
                    <th>Status</th>
                    <th>Ergebnis</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ( $candidates as $candidate ) : ?>
                    <tr>
                        <td class="align-middle">
                            <strong><?php echo esc_html( $candidate->prename . ' ' . $candidate->surname ); ?></strong><br>
                            <?php echo date('d.m.Y', strtotime($candidate->added)); ?> <!-- Bewerbungsdatum anzeigen -->
                        </td>
                        <td class="align-middle"><?php echo esc_html( $candidate->job_title ); ?></td>
                        <td class="align-middle"><?php echo $candidate->reference_id ? 'Erfolgreich bewertet' : 'Kriterien werden überprüft'; ?></td>
                        <td class="align-middle">
                            <?php if ( $candidate->reference_id ) : ?>
                                <img src="<?php echo esc_url( plugin_dir_url( __FILE__ ) . 'images/success.png' ); ?>" alt="Erfolgreich bewertet">
                            <?php else : ?>
                                <a href="<?php echo esc_url( home_url( '/kandidaten-details?id=' . $candidate->ID ) ); ?>">Prüfung läuft...</a>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
<?php else : ?>
    <p>Keine Kandidaten gefunden.</p>
<?php endif; ?>
