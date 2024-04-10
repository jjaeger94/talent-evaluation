<form method="get">
    <div class="form-group row">
        <div class="col-md-6">
            <label for="filter_tasks" class="col-form-label">Stelle:</label>
            <select class="form-control" id="filter_tasks" name="filter_tasks">
                <option value="" <?php echo ($selected_tasks == '') ? 'selected' : ''; ?>>Alle anzeigen</option>
                <option value="new" <?php echo ($selected_tasks == 'new') ? 'selected' : ''; ?>>Neu</option>
                <option value="waiting" <?php echo ($selected_tasks == 'waiting') ? 'selected' : ''; ?>>In Wartestellung</option>
                <option value="in_progress" <?php echo ($selected_tasks == 'in_progress') ? 'selected' : ''; ?>>In Bearbeitung</option>
                <option value="finished" <?php echo ($selected_tasks == 'finished') ? 'selected' : ''; ?>>Fertig</option>
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
                    <th>Status</th>
                    <th>Kandidat</th>
                    <th>Kriterien</th>
                    <th>Vollständigkeit</th>
                    <th>Background Screening</th>
                    <th>Commitment Test</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ( $candidates as $candidate ) : ?>
                    <tr>
                        <td class="align-middle"><?php echo $candidate->state; ?></td>
                        <td class="align-middle">
                            <strong><a href="<?php echo esc_url( home_url( '/bewerbung-details?id=' . $candidate->ID ) ); ?>"><?php echo esc_html( $candidate->prename . ' ' . $candidate->surname ); ?></a></strong><br>
                            <?php echo date('d.m.Y', strtotime($candidate->added)); ?> <!-- Bewerbungsdatum anzeigen -->
                        </td>
                        <td class="align-middle"></td> <!-- Kriterien-Spalte -->
                        <td class="align-middle"></td> <!-- Vollständigkeit-Spalte -->
                        <td class="align-middle"></td> <!-- Background Screening-Spalte -->
                        <td class="align-middle"></td> <!-- Commitment Test-Spalte -->
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
<?php else : ?>
    <p>Keine Kandidaten gefunden.</p>
<?php endif; ?>
