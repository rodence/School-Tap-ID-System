<?php
include 'includes/db_conn.php';
// signup.php


if ((isset($_GET['type']))) {
    if ($_GET["type"] == "get_subjects") {
        $user_id = $_GET["user_id"];
        $rows = array();
        $result = mysqli_query($connection, "SELECT * FROM user_subject where user_id = '" . $user_id . "' ");
        while ($row = mysqli_fetch_assoc($result)) {

            $rows[] = array(
                "user_id" => $row["user_id"],
                "subject" => $row["subject"],

            );
        }

        // encode array as JSON
        echo  $json = json_encode($rows);
    }


    if ($_GET['type'] == "user_list") {


        $rows = array();

        // fetch data from database
        $result = mysqli_query($connection, "SELECT * FROM users");
        while ($row = mysqli_fetch_assoc($result)) {

            if ($row["is_active"] == 1) {
                $is_ac = '<td><span class="badge badge-success">Active</span></td>';
            } else {
                $is_ac =  '<td><span class="badge badge-danger">In-active</span></td>';
            }


            $action_btn = "<button onclick='window.open(" . '"' . "signup.php?id=" . $row["user_id"] . '"' . ")' class='btn btn-warning btn-sm'>EDIT</button>";

            $action_btn .= "<button style='margin:5px;' onclick='change_status(" . '"' . $row['user_id'] . '"' . ")' class='btn btn-info btn-sm'>Change Status</button>";
            $action_btn .= "<button style='margin:5px;' onclick='delete_account(" . '"' . $row['user_id'] . '"' . ")' class='btn btn-danger btn-sm'>Delete</button>";





            $rows[] = array(
                "user_id" => $row["user_id"],
                "first_name" => $row["first_name"],
                "last_name" => $row["last_name"],
                "email" => $row["email"],
                "type" => $row["registered_as"],
                "is_active" => $is_ac,
                "action" => $action_btn
            );
        }

        // encode array as JSON
        echo  $json = json_encode($rows);
    }


    if ($_GET["type"] == "change_status") {
        $user_id =  $_GET["user_id"];

        $sql = "UPDATE users set is_active=IF(is_active='1', 0, 1)  where user_id = '" . $user_id . "'";
        $query = mysqli_query($connection, $sql);
        if ($query) {
            echo json_encode("Success");
        } else {
            echo json_encode("Failed");
        }
    }

    if ($_GET["type"] == "delete_account") {
        $user_id =  $_GET["user_id"];
        $sql = "DELETE FROM users WHERE user_id = '$user_id'";
        $query = mysqli_query($connection, $sql);

        if ($query) {
            // Delete records from other tables that match the user_id
            $sql_attendance = "DELETE FROM attendance WHERE student_id = '$user_id'";
            $query_attendance = mysqli_query($connection, $sql_attendance);

            $sql_attendance_subject = "DELETE FROM attendance_subject WHERE user_id = '$user_id'";
            $query_attendance_subject = mysqli_query($connection, $sql_attendance_subject);

            $sql_profile_picture = "DELETE FROM profile_picture WHERE user_id = '$user_id'";
            $query_profile_picture = mysqli_query($connection, $sql_profile_picture);

            $sql_user_subject = "DELETE FROM user_subject WHERE user_id = '$user_id'";
            $query_user_subject = mysqli_query($connection, $sql_user_subject);

            // Check if all queries were successful
            if ($query_attendance && $query_attendance_subject && $query_profile_picture && $query_user_subject) {
                echo json_encode("Account and associated records deleted");
            } else {
                echo json_encode("Failed to delete all associated records");
            }
        } else {
            echo json_encode("Failed to delete the account");
        }
    }


    if ($_GET["type"] == "daily_tap") {
        $from = $_GET["from"];
        $to = $_GET["to"];

        $array = array();
        $interval = new DateInterval('P1D');

        $realEnd = new DateTime(date("Y-m-d", strtotime($to)));
        $realEnd->add($interval);

        $period = new DatePeriod(new DateTime(date("Y-m-d", strtotime($from))), $interval, $realEnd);

        foreach ($period as $date) {
            $array[] = $date->format('Y-m-d');
        }


        $rows = array();


        foreach ($array as $date) {
            $sql = "SELECT COUNT(DISTINCT student_id) AS count FROM attendance WHERE date = '" . $date . "'";
            $query = mysqli_query($connection, $sql);
            while ($row = mysqli_fetch_assoc($query)) {
                $rows[] = array(
                    "date" => $date,
                    "count" => $row["count"],
                );
            }
        }


        // encode array as JSON
        echo  $json = json_encode($rows);
    }


    function getBetweenDates($startDate, $endDate)
    {
        $array = array();
        $interval = new DateInterval('P1D');

        $realEnd = new DateTime($endDate);
        $realEnd->add($interval);

        $period = new DatePeriod(new DateTime($startDate), $interval, $realEnd);

        foreach ($period as $date) {
            $array[] = $date->format('Y-m-d');
        }

        return $array;
    }
}
