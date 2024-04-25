<?php include 'filters/state-filter.php'; ?>
<br>
<?php if ( ! empty( $applications ) ) : ?>
    <div class="table-responsive">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Status</th>
                    <th>Kandidat</th>
                    <?php if ( show_all_features() ) : ?>
                    <th>Kriterien</th>
                    <th>Vollständigkeit</th>
                    <th>Background Screening</th>
                    <?php endif;?>
                    <th>Commitment Test</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ( $applications as $application ) : ?>
                    <tr>
                        <td class="align-middle"><?php echo $application->state; ?></td>
                        <td class="align-middle">
                            <strong><a href="<?php echo esc_url( home_url( '/task-details?id=' . $application->ID ) ); ?>"><?php echo esc_html( $application->prename . ' ' . $application->surname ); ?></a></strong><br>
                            <?php echo date('d.m.Y', strtotime($application->added)); ?> <!-- Bewerbungsdatum anzeigen -->
                        </td>
                        <?php if ( show_all_features() ) : ?>
                        <td class="align-middle text-center">
                            <?php include 'columns/criteria.php'; ?>
                        </td>
                        <td class="align-middle text-center">
                            <?php include 'columns/completeness.php'; ?>
                        </td>
                        <td class="align-middle text-center">
                            <?php include 'columns/screening.php'; ?>
                        </td>
                        <?php endif;?>
                        <td class="align-middle text-center">
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
