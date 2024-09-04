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
    Convii ist ein Unternehmen, das talentierte Menschen wie dich mit Firmen verbindet, die auf der Suche nach Persönlichkeiten mit herausragenden sozialen Fähigkeiten sind.
    </p>
    <p>
        Wir freuen uns sehr, dass du dich bei uns angemeldet hast. Leider haben wir dich bisher telefonisch nicht erreichen können. Wir würden dich gerne besser kennenlernen. Antworte einfach auf diese E-Mail oder buche direkt einen Termin mit uns.
    </p>
    <p>
        <a href="https://outlook.office365.com/book/Convii@gj-glassart.com/">Jetzt Termin vereinbaren</a>
    </p>
    <p>
        Unabhängig davon kannst Du Dich auch bereits über den folgenden Link registrieren:
    </p>
    <p>
        <a href="<?php echo home_url('/membership-join/membership-registration') . '?member_id=' . $new_member['member_id'] . '&code=' . $new_member['reg_code']; ?>">Hier registrieren</a>
    </p>
    
    <?php include TE_DIR.'mails/signatur.htm'; ?>
</body>
</html>
