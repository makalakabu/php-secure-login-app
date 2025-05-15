<?php
require '../config.php'; // Load the database connection

try {
    //extract from database all of the information
    $stmt = $connection->prepare("
        SELECT e.id, e.object_name, e.description, e.img_path, u.name AS username, u.notelp, u.email, e.contact_method
        FROM evaluation e
        JOIN user u ON e.user_id = u.id
        ORDER BY e.id DESC
    ");
    $stmt->execute();

    $evaluations = $stmt->fetchAll(PDO::FETCH_ASSOC); 

    // Output the rows dynamically in the table
    foreach ($evaluations as $evaluation) {
        echo '<tr>';
        echo '<td>' . htmlspecialchars($evaluation['id']) . '</td>';
        echo '<td>' . htmlspecialchars($evaluation['object_name']) . '</td>';
        echo '<td>' . htmlspecialchars($evaluation['description']) . '</td>';
        echo '<td><img src="' . htmlspecialchars($evaluation['img_path']) . '" alt="Object Image" width="100"></td>';
        echo '<td>' . htmlspecialchars($evaluation['username']) . '</td>';
        
        // Display contact method based on the value
        if ($evaluation['contact_method'] === 'telephone-method') {
            echo '<td>Telephone: ' . htmlspecialchars($evaluation['notelp']) . '</td>';
        } elseif ($evaluation['contact_method'] === 'email-method') {
            echo '<td>Email: ' . htmlspecialchars($evaluation['email']) . '</td>';
        } else {
            echo '<td>' . htmlspecialchars($evaluation['contact_method']) . '</td>'; // Fallback for other methods
        }
        
        echo '</tr>';
    }
} catch (PDOException $e) {
    // Display an error message if the query fails
    echo '<tr><td colspan="6" class="text-danger text-center">Error: ' . htmlspecialchars($e->getMessage()) . '</td></tr>';
}
?>
