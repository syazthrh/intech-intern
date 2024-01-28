<?php
// Include the database connection file
include 'dbconnect.php';

// Start the session
session_start();

// Assuming the sender is the logged-in user
$senderUsername = $_SESSION['username'];
$receiverUsername = $_POST['receiver'];

if (isset($_POST['message']) && !empty($_POST['message'])) {
    $messageContent = $_POST['message'];

    // Check if the sender username exists in the supervisor table
    $checkSupervisorQuery = "SELECT username FROM supervisor WHERE username = '$senderUsername'";
    $checkSupervisorResult = mysqli_query($dbconn, $checkSupervisorQuery);

    // Check if the sender username exists in the users table
    $checkUsersQuery = "SELECT username FROM users WHERE username = '$senderUsername'";
    $checkUsersResult = mysqli_query($dbconn, $checkUsersQuery);

    // Check if the sender username exists in either table
    if ($checkSupervisorResult && mysqli_num_rows($checkSupervisorResult) > 0) {
        $senderTable = 'supervisor';
    } elseif ($checkUsersResult && mysqli_num_rows($checkUsersResult) > 0) {
        $senderTable = 'users';
    } else {
        // Return a JSON response indicating that the sender username doesn't exist
        $response = ['success' => false, 'error' => 'Sender username does not exist'];
        echo json_encode($response);
        exit;
    }

    // Insert the new message into the appropriate table
    $insertMessageQuery = "INSERT INTO chat_messages (sender_id, receiver_id, message) 
                           VALUES ('$senderUsername', '$receiverUsername', '$messageContent')";

    $result = mysqli_query($dbconn, $insertMessageQuery);

    if ($result) {
        // Return a JSON response with the new message details
        $response = [
            'success' => true,
            'message' => [
                'sender' => $senderUsername,
                'receiver' => $receiverUsername,
                'content' => $messageContent,
                'timestamp' => date('Y-m-d H:i:s')
            ]
        ];

        echo json_encode($response);
        exit;
    } else {
        // Return a JSON response indicating failure
        $response = ['success' => false, 'error' => 'Failed to send the message'];
        echo json_encode($response);
        exit;
    }
} else {
    // Return a JSON response indicating missing message content
    $response = ['success' => false, 'error' => 'Message content is required'];
    echo json_encode($response);
    exit;
}
?>
