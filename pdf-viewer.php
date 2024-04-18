<?php
// Überprüfen, ob eine Datei angefordert wurde
if (isset($_GET['file']) && isset($_GET['application_id'])) {
    $file_name = $_GET['file'];
    $application_id = $_GET['application_id'];
    $application = get_application_by_id($application_id);

    if($application && $application->filepath){

        $uploadDir = get_applications_dir();
        // Pfad zur PDF-Datei konstruieren
        $file_path = $uploadDir . $application->filepath .'/'. $file_name;

        // Überprüfen, ob die Datei existiert
        if (file_exists($file_path)) {
            // Dateityp festlegen
            header('Content-Type: ' . mime_content_type($file_path));

            // Datei ausgeben
            readfile($file_path);
            exit;
        } else {
            // Wenn die Datei nicht existiert, Fehlermeldung ausgeben
            http_response_code(404);
            exit('File not found');
        }
    }else{
        // Wenn die Datei nicht existiert, Fehlermeldung ausgeben
        http_response_code(401);
        exit('unauthorized');
    }
    
} else if(isset($_GET['file']) && isset($_GET['review_id'])){
    $file_name = $_GET['file'];
    $review_id = $_GET['review_id'];
    $review = get_review_by_id($review_id);

    if($review && $review->filepath){
        $uploadDir = get_consent_dir();

        $file_path = $uploadDir . $review->filepath .'/'.$file_name;

        // Überprüfen, ob die Datei existiert
        if (file_exists($file_path)) {
            // Dateityp festlegen
            header('Content-Type: ' . mime_content_type($file_path));

            // Datei ausgeben
            readfile($file_path);
            exit;
        } else {
            // Wenn die Datei nicht existiert, Fehlermeldung ausgeben
            http_response_code(404);
            exit('File not found');
        }
    }else{
        // Wenn die Datei nicht existiert, Fehlermeldung ausgeben
        http_response_code(401);
        exit('unauthorized');
    }
}else {
    // Wenn keine Datei angefordert wurde oder die Anwendungs-ID fehlt, Fehlermeldung ausgeben
    http_response_code(401);
    exit('unauthorized');
}
?>
