<?php

session_start();
if ($_SESSION['logged_as'] != 'Admin') {
    header("Location: index.php");
    exit();
}


include 'includes/header.php';
include 'includes/db_conn.php';
include 'includes/navbar.php';


$students = "SELECT * from users where is_active = 1 and registered_as like 'STUDENT' ";
$student_query = mysqli_query($connection, $students);
$student_count = mysqli_num_rows($student_query);

$teachers = "SELECT * from users where is_active = 1 and registered_as like 'TEACHER' ";
$teacher_query = mysqli_query($connection, $teachers);
$teacher_count = mysqli_num_rows($teacher_query);


$count_ins = 0;
$students_inside = "SELECT count(student_id) as count_log from attendance where student_id like '01-%' and date='" . date('Y-m-d') . "'  group by student_id,date ";
$student_query_inside = mysqli_query($connection, $students_inside);
while ($row = mysqli_fetch_assoc($student_query_inside)) {
    if (($row["count_log"] % 2) == 1) {
        $count_ins++;
    }
}

$count_ins_t = 0;
$teachers_inside = "SELECT count(student_id) as count_log from attendance where student_id like '02-%' and date='" . date('Y-m-d') . "'  group by student_id,date ";
$teachers_query_inside = mysqli_query($connection, $teachers_inside);
while ($row = mysqli_fetch_assoc($teachers_query_inside)) {
    if (($row["count_log"] % 2) == 1) {
        $count_ins_t++;
    }
}



?>










<br>
<br>
<br>
<br>
<div class="container">
    <div class="row d-flex justify-content-center">

        <div class="col-md-5 border border-success ">
            <div class="card">
                <div class="title badge badge-success"> STUDENT COUNT</div>
            </div>
            <div class="content" style="text-align:center;">
                <h1> <?php echo $student_count; ?> Students</h1>
            </div>
        </div>

        <div class="col-md-5 border border-success ml-1">
            <div class="card">
                <div class="title badge badge-success"> TEACHER COUNT</div>
            </div>
            <div class="content" style="text-align:center;">
                <h1> <?php echo $teacher_count; ?> Teachers</h1>
            </div>
        </div>

    </div>

    <div class="row d-flex justify-content-center">

        <div class="col-md-5 border border-success mt-2">
            <div class="card">
                <div class="title badge badge-success"> NO OF STUDENTS INSIDE THE CAMPUS TODAY</div>
            </div>
            <div class="content" style="text-align:center;">
                <h1> <?php echo $count_ins; ?> Students</h1>
            </div>
        </div>


        <div class="col-md-5 border border-success mt-2 ml-1">
            <div class="card">
                <div class="title badge badge-success"> NO OF TEACHERS INSIDE THE CAMPUS TODAY</div>
            </div>
            <div class="content" style="text-align:center;">
                <h1> <?php echo $count_ins_t; ?> Teachers</h1>
            </div>
        </div>

    </div>

    <br>





    <div class="row d-flex justify-content-center">
        <div class="col-md-10 border border-success mt-2">
            <label for="from_date">From</label>
            <input type="date" id="from_date" value="<?php echo date('Y-m-01'); ?>">

            <label for="to_date">To</label>
            <input type="date" id="to_date" value="<?php echo date('Y-m-d'); ?>">

            <button class="btn btn-success btn-sm" onclick="load_data();">Load Graph</button>
        </div>



    </div>




    <div class="row d-flex justify-content-center">


        <div class="col-md-10 border border-success mt-2 ">
            <figure class="highcharts-figure">
                <div id="container"></div>
                <p class="highcharts-description">

                </p>
            </figure>
        </div>


    </div>




</div>


<script>
    load_data();

    function load_data() {
        var from = $("#from_date").val();
        var to = $("#to_date").val();

        $.ajax({
            url: 'data_processor.php',
            type: 'GET',
            dataType: 'json',
            data: {
                "type": "daily_tap",
                "from": from,
                "to": to
            },
            success: function(data) {
                var date_arr = [];
                var tap_arr = [];


                $.each(data, function(k, v) {
                    date_arr[k] = v["date"];
                    var count = v["count"];
                    tap_arr[k] = parseInt(count);

                });

                load_line(date_arr, tap_arr);

            }
        });

    }




    function load_line(date_arr, tap_arr) {
        // alert(tap_arr[0]);
        Highcharts.chart('container', {

            title: {
                text: 'Daily Average Logged',
                align: 'left'
            },



            yAxis: {
                title: {
                    text: 'Number of users'
                }
            },

            xAxis: {
                categories: date_arr
            },

            legend: {
                layout: 'vertical',
                align: 'right',
                verticalAlign: 'middle'
            },

            plotOptions: {
                series: {
                    label: {
                        connectorAllowed: false
                    },
                    data: date_arr
                }
            },

            series: [{
                name: "QR Scanned",
                data: tap_arr
            }],

            responsive: {
                rules: [{
                    condition: {
                        maxWidth: 500
                    },
                    chartOptions: {
                        legend: {
                            layout: 'horizontal',
                            align: 'center',
                            verticalAlign: 'bottom'
                        }
                    }
                }]
            }

        });
    }
</script>