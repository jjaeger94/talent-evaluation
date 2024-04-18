<p>Hallo <?php echo $application->prename; ?>,</p>
<p>Schön das du dich auf die Stelle <?php echo $job->job_title; ?> beworben hast.</p>
<p>Wir von Commit IQ überprüfen für <?php echo get_user_meta($job->user_id, 'company', true); ?> einige deiner Bewerberdaten und benötigen dein Einverständnis.</p>
<p>Bitte klicke auf den unten stehenden Link, um uns dein Einverständnis zu geben und das Formular auszufüllen:</p>
<p><a href="<?php echo esc_url(add_query_arg(array('id' => $application->ID, 'key' => $review->filepath), home_url('/consent'))); ?>">Einverständniserklärung ausfüllen</a></p>
<p>Viele Grüße,<br>Das Team von Commit IQ</p>