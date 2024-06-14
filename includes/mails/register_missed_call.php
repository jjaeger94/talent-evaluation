<?php
/**
 * Template for register with missed call
 *
 * Variables:
 * - $talent: Objekt, das die Talent-Daten enthält
 * - $new_member: Member Objekt das vom swmp plugin erzeugt wurde
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
        Leider konnten wir Dich bisher nicht erreichen. Wir würden gerne mit Dir sprechen, um mehr über Dich zu erfahren. Wenn Du das möchtest, antworte einfach auf diese E-Mail.
    </p>
    <p>
        Unabhängig davon kannst Du Dich auch bereits über den folgenden Link registrieren:
    </p>
    <p>
        <a href="<?php echo home_url('/membership-join/membership-registration') . '?member_id=' . $new_member['member_id'] . '&code=' . $new_member['reg_code']; ?>">Hier registrieren</a>
    </p>
    
    <p>Dein Team Convii</p>
</body>
</html>
