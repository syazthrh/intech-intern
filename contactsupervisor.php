<?php
// Include the database connection file
include 'dbconnect.php';

// Start the session
session_start();

// Assuming student is logged in
$studentUsername = $_SESSION['username'];

// Fetch the supervisor for the student
$supervisorQuery = "SELECT s.usupervisor_id, u.full_name AS supervisor_name
                    FROM student_internships s
                    INNER JOIN supervisor u ON s.usupervisor_id = u.username
                    WHERE s.username = '$studentUsername'";
$supervisorResult = mysqli_query($dbconn, $supervisorQuery);

// Assuming a supervisor is found
if ($supervisorDetails = mysqli_fetch_assoc($supervisorResult)) {
    $supervisorUsername = $supervisorDetails['usupervisor_id'];
    $supervisorName = $supervisorDetails['supervisor_name'];

    // Display chat box for the student to contact the supervisor
    ?>
    <!DOCTYPE html>
    <html lang='en'>

    <head>
        <meta charset='UTF-8'>
        <meta name='viewport' content='width=device-width, initial-scale=1.0'>
        <title>Contact Supervisor</title>
        <link rel='stylesheet' type='text/css' href='style.css'>
    </head>

    <body>
        <header>
            <div id='logo'>INTECH INTERN</div>
            <div class='nav-links'>
                <div class="nav-item"><a href="welcomeconfirm.php" id="home">Home</a></div>
                <div class="nav-item"><a href="progressreport.php">Progress Report</a></div>
                <div class="nav-item"><a href="contactsupervisor.php">Contact Supervisor</a></div>
                <div class="nav-item"><a href="login.php">Log Out</a></div>
            </div>
        </header>

        <div class='page-title'>
            <h1>Contact Supervisor: <?php echo $supervisorName; ?></h1>
        </div>

        <div class='chat-container'>
            <div class='chat-box'>
                <?php
                // Fetch and display previous messages
                $messagesQuery = "SELECT * FROM chat_messages 
                                  WHERE (sender_id = '$studentUsername' AND receiver_id = '$supervisorUsername') 
                                     OR (sender_id = '$supervisorUsername' AND receiver_id = '$studentUsername')
                                  ORDER BY timestamp ASC";
                $messagesResult = mysqli_query($dbconn, $messagesQuery);

                while ($message = mysqli_fetch_assoc($messagesResult)) {
                    $sender = $message['sender_id'];
                    $receiver = $message['receiver_id'];
                    $messageContent = $message['message'];
                    echo "<p><strong>$sender:</strong> $messageContent</p>";
                }
                ?>
            </div>

            <!-- Form for sending a new message -->
            <form method='post' action='sendmessage.php' name='messageForm'>
                <input type='hidden' name='sender' value='<?php echo $studentUsername; ?>'>
                <input type='hidden' name='receiver' value='<?php echo $supervisorUsername; ?>'>
                <input type='text' name='message' class='message-input' placeholder='Your Message' required>
                <button type='submit' class='send-button'>Send</button>
            </form>
        </div>

        <script>
    function sendMessage() {
        var messageInput = document.querySelector('.message-input');
        var messageContent = messageInput.value;
        var chatBox = document.querySelector('.chat-box');

        // Display the sent message in the chat box
        var sender = '<?php echo $studentUsername; ?>';
        var newMessage = '<p><strong>' + sender + ':</strong> ' + messageContent + '</p>';
        chatBox.innerHTML += newMessage;

        // Clear the message input
        messageInput.value = '';

        // Manually scroll the chat box to the bottom to show the latest message
        chatBox.scrollTop = chatBox.scrollHeight;

        // Disable input temporarily for animation
        messageInput.disabled = true;

        // Animate the message input width
        setTimeout(function () {
            messageInput.style.width = '0';
            setTimeout(function () {
                // Re-enable input after animation
                messageInput.disabled = false;
                messageInput.style.width = '70%'; // Reset the width
            }, 300);
        }, 300);

        // Delayed form submission to let the animation complete
        setTimeout(function () {
            document.forms['messageForm'].submit();
        }, 600);
    }
</script>

    </body>

    </html>
    <?php
} else {
    // Display an error message if supervisor details are not found
    echo "Supervisor details not found.";
}
?>
