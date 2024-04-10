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
                    <th>Kriterien</th>
                    <th>Vollständigkeit</th>
                    <th>Background Screening</th>
                    <th>Commitment Test</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach ($candidates as $candidate) : ?>
                <tr>
                    <td class="align-middle">
                        <strong><a href="<?php echo esc_url(home_url('/kandidaten-details?id=' . $candidate->ID)); ?>"><?php echo esc_html($candidate->prename . ' ' . $candidate->surname); ?></a></strong><br>
                        <?php echo date('d.m.Y', strtotime($candidate->added)); ?> <!-- Bewerbungsdatum anzeigen -->
                    </td>
                    <td class="align-middle"><?php echo esc_html($candidate->job_title); ?></td>
                    <td class="align-middle">
                        <?php if (!$candidate->review_id) : ?>
                            <!-- Keine Anzeige für -1 -->
                        <?php elseif ($candidate->review->criteria == 0) : ?>
                            <div class="circle gray"></div>
                        <?php elseif ($candidate->review->criteria == 1) : ?>
                            <div class="circle red"></div>
                        <?php elseif ($candidate->review->criteria == 2) : ?>
                            <div class="circle yellow"></div>
                        <?php elseif ($candidate->review->criteria == 3) : ?>
                            <div class="circle green"></div>
                        <?php endif; ?>
                    </td>
                    <td class="align-middle">
                        <?php if (!$candidate->review_id) : ?>
                            <!-- Keine Anzeige für -1 -->
                        <?php elseif ($candidate->review->completeness == 0) : ?>
                            <div class="circle gray"></div>
                        <?php elseif ($candidate->review->completeness == 1) : ?>
                            <div class="circle red"></div>
                        <?php elseif ($candidate->review->completeness == 2) : ?>
                            <div class="circle yellow"></div>
                        <?php elseif ($candidate->review->completeness == 3) : ?>
                            <div class="circle green"></div>
                        <?php endif; ?>
                    </td>
                    <td class="align-middle">
                        <?php if (!$candidate->review_id) : ?>
                            <!-- Keine Anzeige für -1 -->
                        <?php elseif ($candidate->review->screening == 0) : ?>
                            <div class="circle gray"></div>
                        <?php elseif ($candidate->review->screening == 1) : ?>
                            <div class="circle red"></div>
                        <?php elseif ($candidate->review->screening == 2) : ?>
                            <div class="circle yellow"></div>
                        <?php elseif ($candidate->review->screening == 3) : ?>
                            <div class="circle green"></div>
                        <?php endif; ?>
                    </td>
                    <td class="align-middle">
                        <?php if (!$candidate->review_id) : ?>
                        <?php elseif ($candidate->review->commitment == -1) : ?>
                            In Prüfung
                        <?php else : ?>
                            <?php echo esc_html($candidate->review->commitment) . ' / 10'; ?>
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
