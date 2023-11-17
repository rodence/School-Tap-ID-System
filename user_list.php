<?php

session_start();
if ($_SESSION['logged_as'] != 'Admin') {
    header("Location: index.php");
    exit();
}

include 'includes/header.php';
include 'includes/db_conn.php';
// signup.php
include 'includes/navbar.php';
?>

<br>

<br>
<br>
<br>
<div class="container">
    <div class="card">
        <div class="title">
            <h1> User List </h1>
        </div>
        <div class="content">
            <div class="col-xl-12 col-sm-12 col-12 table-responsive-sm">
                <button onclick="window.open('signup.php')" class="btn btn-success btn-sm">New User</button>
            </div>
            <br>
            <div class="col-xl-12 col-sm-12 col-12 table-responsive-sm">
                <table class="table table-striped table-bordered table-hover" id="tbl_user_list">
                    <thead>
                        <tr>
                            <th>User Id</th>
                            <th>First Name</th>
                            <th>Last Name</th>
                            <th>Email</th>
                            <th>Type</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody id="tbl_user_body">
                    </tbody>
                </table>

            </div>
        </div>

    </div>



</div>

<script>
    load_user_list();

    function load_user_list() {


        $.ajax({
            url: 'data_processor.php',
            type: 'GET',
            dataType: 'json',
            data: {
                "type": "user_list"
            },
            success: function(data) {
                $('#tbl_user_list').DataTable({
                    "bDestroy": true,
                    data: data,

                    columns: [{
                            'data': "user_id"
                        },
                        {
                            'data': "first_name"
                        },
                        {
                            'data': "last_name"
                        },
                        {
                            'data': "email"
                        },
                        {
                            'data': "type"
                        },
                        {
                            'data': "is_active"
                        },
                        {
                            'data': "action"
                        }

                    ],
                    initComplete: function() {
                        console.log('DataTables initialization completed');
                    }
                });
            }
        });


    }

    function edit_user(user_id) {
        alert(user_id);



    }

    function change_status(user_id) {
        var yesno = confirm("Change Status");

        if (yesno) {
            $.ajax({
                method: 'GET',
                url: 'data_processor.php',
                data: {
                    type: "change_status",
                    user_id: user_id
                },
                dataType: 'json',
                success: function(source) {
                    alert(source);
                    load_user_list();
                }

            });

        }



    }

    function delete_account(user_id) {
        var yesno = confirm("Delete Account?");

        if (yesno) {
            $.ajax({
                method: 'GET',
                url: 'data_processor.php',
                data: {
                    type: "delete_account",
                    user_id: user_id
                },
                dataType: 'json',
                success: function(source) {
                    alert(source);
                    load_user_list();
                }

            });

        }



    }
</script>