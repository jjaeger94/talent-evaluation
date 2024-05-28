<table class="table table-striped">
<thead>
    <tr>
        <th>Kriterium</th>
        <th><a href="<?php echo esc_url(home_url('/job-details/?id=' . $job->ID)); ?>"><?php echo $job->job_title; ?></a></th>
        <th><a href="<?php echo esc_url(home_url('/talent-details/?id=' . $talent->ID)); ?>"><?php echo $talent->prename . ' ' . $talent->surname; ?></a></th>
    </tr>
</thead>
<tbody>
    <tr>
        <td>Schulabschluss</td>
        <td><?php echo get_study_degree($job->school); ?></td>
        <td><?php echo get_study_degree($talent->school); ?></td>
    </tr>
    <tr>
        <td>Verfügbarkeit</td>
        <td><?php echo get_availability_string($job->availability); ?></td>
        <td><?php echo get_availability_string($talent->availability); ?></td>
    </tr>
    <tr>
        <td>Home Office</td>
        <td><?php echo isset($job->home_office) && $job->home_office ? 'Angeboten' : 'Verboten'; ?></td>
        <td><?php echo isset($talent->home_office) && $talent->home_office ? 'Verlangt' : 'Egal'; ?></td>
    </tr>
    <tr>
        <td>Führerschein</td>
        <td><?php echo isset($job->license) && $job->license ? 'Benötigt' : 'Egal'; ?></td>
        <td><?php echo isset($talent->license) && $talent->license ? 'Vorhanden' : 'Nicht vorhanden'; ?></td>
    </tr>
    <tr>
        <td>Entfernung</td>
        <td><?php echo getDistanceBetween($talent->post_code, $job->post_code);?></td>
        <td><?php echo get_mobility_label($talent->mobility); ?></td>
    </tr>
    <?php for ($i = 0; $i < max(count($apprenticeships), count(isset($grouped_requirements[1]) ? $grouped_requirements[1] : [])); $i++): ?>
    <tr>
        <td>Ausbildung</td>
        <td><?php echo isset($grouped_requirements[1][$i]) ? get_apprenticeship_field($grouped_requirements[1][$i]->field) : '';?></td>
        <td><?php echo isset($apprenticeships[$i]) ? get_apprenticeship_field($apprenticeships[$i]->field) .': '. $apprenticeships[$i]->designation : '';?></td>
    </tr>
    <?php endfor; ?>
    <?php for ($i = 0; $i < max(count($studies), count(isset($grouped_requirements[2]) ? $grouped_requirements[2] : [])); $i++): ?>
    <tr>
        <td>Studium</td>
        <td><?php echo isset($grouped_requirements[2][$i]) ? get_study_degree($grouped_requirements[2][$i]->degree) . ': ' . get_study_field($grouped_requirements[2][$i]->field) : '';?></td>
        <td><?php echo isset($studies[$i]) ? get_study_degree($studies[$i]->degree) . ': ' . get_study_field($studies[$i]->field).': '. $studies[$i]->designation : '';?></td>
    </tr>
    <?php endfor; ?>
    <?php for ($i = 0; $i < max(count($experiences), count(isset($grouped_requirements[3]) ? $grouped_requirements[3] : [])); $i++): ?>
    <tr>
        <td>Berufserfahrung</td>
        <td><?php echo isset($grouped_requirements[3][$i]) ? get_experience_field($grouped_requirements[3][$i]->field) : '';?></td>
        <td><?php echo isset($experiences[$i]) ? get_experience_field($experiences[$i]->field) .': '. $experiences[$i]->position : '';?></td>
    </tr>
    <?php endfor; ?>
</tbody>
</table>