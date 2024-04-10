<?php include 'filters/job-filter.php'; ?>
<br>
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
                        <?php include 'columns/criteria.php'; ?>
                    </td>
                    <td class="align-middle">
                        <?php include 'columns/completeness.php'; ?>
                    </td>
                    <td class="align-middle">
                        <?php include 'columns/screening.php'; ?>
                    </td>
                    <td class="align-middle">
                        <?php include 'columns/commitment.php'; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
<?php else : ?>
    <p>Keine Kandidaten gefunden.</p>
<?php endif; ?>
