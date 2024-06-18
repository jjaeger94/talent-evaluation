<?php
/**
 * Template for new job email
 *
 * Variables:
 * - $talent: Objekt, das die Talent-Daten enthält
 * - $subject : Betreff
 */
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title><?php echo esc_html($subject); ?></title>
</head>
<body>
    <p><?php echo esc_html($talent->prename) . ' ' . esc_html($talent->surname); ?> möchte ein Erstgespräch!</p>
    <p>ID: <?php echo esc_html($talent->ID)?></p>
    <p>Hinzugefügt: <?php echo esc_html($talent->added)?></p>
    <p>Email: <?php echo esc_html($talent->email)?></p>
    <p>Telefon: <?php echo esc_html($talent->mobile)?></p>
    <br>
    <p>Viele Grüße</p>
    <p>Dein Team Convii</p>
</body>
</html>
