<?php
// Include the database connection file
include 'dbconnect.php';

// Start the session
session_start();

// Assuming the supervisor is logged in
$supervisorUsername = $_SESSION['username'];

// Assuming a student is selected
if (isset($_GET['username'])) {
    $studentUsername = $_GET['username'];

    // Fetch student details for display
    $studentDetailsQuery = "SELECT full_name FROM users WHERE username = '$studentUsername'";
    $studentDetailsResult = mysqli_query($dbconn, $studentDetailsQuery);
    $studentDetails = mysqli_fetch_assoc($studentDetailsResult);

    if ($studentDetails) {
        // Display chat box for the supervisor to contact the student
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
                    <div class='nav-item'><a href='welcomesupervisor.php' id='home'>Home</a></div>
                    <div class="nav-item"><a href="login.php" id="home">LOG OUT</a></div>
                </div>
            </header>

            <div class='page-title'>
                <h1>Contact Student: <?php echo $studentDetails['full_name']; ?></h1>
            </div>
            
            <div class='chat-container'>
                <div class='chat-box'>
                    <?php
                    // Fetch and display messages
                    $messagesQuery = "SELECT * FROM chat_messages 
                                    WHERE (sender_id = '$supervisorUsername' AND receiver_id = '$studentUsername') 
                                        OR (sender_id = '$studentUsername' AND receiver_id = '$supervisorUsername')
                                    ORDER BY timestamp ASC";
                    $messagesResult = mysqli_query($dbconn, $messagesQuery);

                    echo "<div style='height: 300px; overflow-y: auto; border: 1px solid #ccc; padding: 10px;'>";
                    while ($message = mysqli_fetch_assoc($messagesResult)) {
                        $sender = $message['sender_id'];
                        $receiver = $message['receiver_id'];
                        $messageContent = $message['message'];
                        echo "<p><strong>$sender:</strong> $messageContent</p>";
                    }
                    echo "</div>";

                    // Display form for sending a new message
                    echo "<form method='post' action='sendmessage.php'>";
                    echo "<input type='hidden' name='sender' value='$supervisorUsername'>";
                    echo "<input type='hidden' name='receiver' value='$studentUsername'>";
                    echo "<label for='message'>Your Message:</label>";
                    echo "<input type='text' name='message' id='message' required>";
                    echo "<button type='button' onclick='sendMessage()'>Send</button>";
                    echo "</form>";
                    ?>
                </div>

                <!-- JavaScript to handle sending messages and updating the chat box -->
                <script>
                    function sendMessage() {
                        var messageInput = document.getElementById('message');
                        var messageContent = messageInput.value;

                        // Send message using AJAX
                        var xhr = new XMLHttpRequest();
                        xhr.open('POST', 'sendmessage.php', true);
                        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                        xhr.onreadystatechange = function() {
                            if (xhr.readyState == 4 && xhr.status == 200) {
                                var response = JSON.parse(xhr.responseText);
                                if (response.success) {
                                    // Message sent successfully, update chat box
                                    var chatBox = document.querySelector('div[style*="height: 300px;"]');
                                    var sender = '<?php echo $supervisorUsername; ?>';
                                    var content = response.message.content;
                                    var newMessage = '<p><strong>' + sender + ':</strong> ' + content + '</p>';
                                    chatBox.innerHTML += newMessage;

                                    // Clear the message input
                                    messageInput.value = '';
                                } else {
                                    // Handle error in sending message
                                    alert('Failed to send the message');
                                }
                            }
                        };
                        xhr.send('receiver=<?php echo $studentUsername; ?>&message=' + encodeURIComponent(messageContent));
                    }
                </script>
            </div>
        </body>
        </html>
        <?php
    } else {
        // Inform the supervisor that the student does not exist
        echo "<h1>Student Not Found</h1>";
        echo "<p>The student does not exist.</p>";
    }
} else {
    // Inform the supervisor that no student is selected
    echo "<h1>No Student Selected</h1>";
    echo "<p>Please select a student to chat with.</p>";
}
?>
