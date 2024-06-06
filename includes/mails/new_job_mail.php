<?php
/**
 * Template for new job email
 *
 * Variables:
 * - $talent: Objekt, das die Talent-Daten enthält
 * - $count: Anzahl der neuen Stellen
 */
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Neue Stellen</title>
</head>
<body>
    <h1>Hallo <?php echo esc_html($talent->prename); ?>,</h1>
    <p>wir haben <?php echo esc_html($count); ?> neue Stellen, die zu deinem Profil passen.</p>
    <p>Besuche unsere <a href="<?php echo esc_url(home_url('/matching')); ?>">Website</a>, um mehr zu erfahren.</p>
    <p>Viele Grüße</p>
    <p>Dein Team Convii</p>
</body>
</html>
