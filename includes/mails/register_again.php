<?php
/**
 * Template for register with missed call
 *
 * Variables:
 * - $talent: Objekt, das die Talent-Daten enthält
 * - $link: Registrierungslink der vom swmp plugin erzeugt wurde
 */
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Willkommen bei Convii</title>
</head>
<body>
    <h1>Hallo <?php echo esc_html($talent->prename); ?>,</h1>
    
    <p>
        Convii ist eine Firma, die Talente wie Dich mit Unternehmen verbindet. Diese Unternehmen suchen Leute für Jobs, bei denen soziale Fähigkeiten wichtig sind.
    </p>
    <p>
        Vor einiger Zeit hast Du Dich bei uns registriert, aber die Registrierung ist noch nicht abgeschlossen. Um tolle Jobs in Deiner Nähe zu finden, schließe bitte die Registrierung ab.
    </p>
    <p>
        Du kannst Deine Registrierung über den folgenden Link abschließen:
    </p>
    <p>
        <a href="<?php echo $link; ?>">Hier Registrierung abschließen</a>
    </p>
    
    <p>Dein Team Convii</p>
</body>
</html>
