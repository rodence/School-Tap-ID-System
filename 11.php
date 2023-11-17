<?php

// Replace with your database credentials
$host = "localhost";
$username = "root";
$password = "";
$dbname = "qr_system";

// Create connection
$connection = mysqli_connect($host, $username, $password, $dbname);

// Check connection
if (!$connection) {
    die("Connection failed: " . mysqli_connect_error());
}


// Set the subject and date parameters
$subject = "ITE 333 - Intelligent System"; // Replace with the desired subject
$date = "2023-03-25"; // Replace with the desired date


// Prepare the SQL query to fetch user attendance information
$sql = "SELECT users.first_name, users.last_name, users.section, attendance_subject.user_id, attendance_subject.date, attendance_subject.teacher_id 
        FROM user_subject 
        INNER JOIN users ON user_subject.user_id = users.user_id 
        LEFT JOIN attendance_subject ON user_subject.user_id = attendance_subject.user_id AND attendance_subject.subject = user_subject.subject AND attendance_subject.date = '$date'
        WHERE user_subject.subject = '$subject' AND users.registered_as = 'STUDENT'
        ORDER BY  users.section ASC, users.last_name ASC, users.first_name ASC ";

// Execute the query and store the result set
$result = mysqli_query($connection, $sql);

// Check if the query was successful
if ($sqlresult) {
    // Display the table
    echo '<div class="table mt">
        <br>
        <table class="table table-bordered" id="example">
            <thead>
                <tr>
                    <th colspan="4">
                        <h3> Class Attendance for ' . $subject . ' (' . $date . ') </h3>
                    </th>
                </tr>
                <tr>
                    <th>No.</th>
                    <th>Student Name</th>
                    <th>Section</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>';

    // Loop through each row of the result set and display the data in the table
    $sno = 0;
    while ($row = mysqli_fetch_assoc($sqlresult)) {
        $sno++;
        $status = $row['date'] ? "Present" : "Absent";
        $name = $row['last_name'] . " " .  $row['first_name'];
        $section = $row['section'];
        echo '<tr>
            <td>' . $sno . '</td>
            <td>' . $name . '</td>
            <td>' . $section . '</td>
            <td>' . $status . '</td>
        </tr>';
    }

    // Close the table
    echo '</tbody></table></div>';
} else {
    // If the query fails, display an error message
    echo "Error: " . mysqli_error($connection);
}

// Close the database connection
mysqli_close($connection);
