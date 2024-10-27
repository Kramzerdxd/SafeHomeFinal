<?php 
session_start();

$_SESSION['username'];
$_SESSION['password'];
if (!isset($_SESSION['username'])) {
	header("location: login.php");
} 
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Sensor Logs</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

    <link href="https://fonts.googleapis.com/css?family=League+Spartan:300,300i,400,400i,600,600i,700,700i|Raleway:300,300i,400,400i,500,500i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <style>
       table tr td:last-child{
            width: 120px;
        }
        .table-container {
            width: 100%;
            max-height: 530px; 
            overflow: auto; 
        }

        .table-header-gas{
            background-color: #a44039 !important; 
            color: #fff;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.4);
            border-radius: 5px; 
            position: sticky;
            top: 0;
            z-index: 1;
            width: 20%;
        }

      

        .table-header-smoke{
            background-color: #454545 !important; 
            color: #fff;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.4);
            border-radius: 5px; 
            position: sticky;
            top: 0;
            z-index: 1;
            width: 20%;
        }

        .table-header-water{
            background-color: #12355B !important; 
            color: #fff;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.4);
            border-radius: 5px; 
            position: sticky;
            top: 0;
            z-index: 1; 
            width: 20%;
        }

        .table-header-timestamp{
            background-color: #2D3142 !important; 
            color: #fff;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.4);
            border-radius: 5px; 
            position: sticky;
            top: 0;
            z-index: 1;
            width: 40%;
        }

        body{
            font-family: "Poppins", sans-serif;
            color: #444444;            
        }
        #searchInput {
            width:280px;
            border-width:thin;
            border-radius: 5px; 
            padding: 5px 5px 5px 10px;
        }

        #sortSelect {
            width:280px; 
            border-width:thin; 
            border-radius: 5px;
            padding: 5px 5px 5px 10px;
        }   
  
        #filterByDateButton,
        .btn-get-started {
            background-color: #0294DB; 
            color: white; 
            border: none; 
            border-radius: 5px; padding: 5px 10px 5px 10px;
            
        }
 
@media screen and (max-width: 576px) {

.table-header-gas,
.table-header-smoke,
.table-header-water,
.table-header-timestamp{
    width: auto;
    }

    #searchInput {
        width: 100%;
        height: auto;
    }

    #sortSelect {
        margin-top: 8px;
        width: 100%;
        height: auto;
    }

#dateFilterInput {
        margin-top: 8px;
        width: 41%;
        height: auto;
    }

    #filterByDateButton{
        margin-top: 8px;
        width: 31%;
        height: auto;  
    }

    .btn-get-started{
        margin-top: 8px;
        width: 25%;
        height: auto;
    }

.table-container {
    max-height: 895px; 
}

.wrapper i {
    display: none;
}


}
    </style>
    <script>
        $(document).ready(function(){
            $('[data-toggle="tooltip"]').tooltip();   
        });
    </script>
</head>
<body>
<?php require_once 'header.php'; ?>
<section id="hero" class="d-flex flex-column justify-content-center align-items-center">
    <div class="wrapper text-center text-xl-center" style="width: 100%; max-width: 995px; padding: 11px;">
            <div class="row">
                <div class="col-md-12"><br>
                    <div class="mt-5 mb-2 justify-content-center clearfix">
                        <h2 class="pull-left" style="color:#2E2E2E; font-weight: bold; padding: 5px; padding-bottom: 1px; text-align: justify;">Sensor Logs</h2>
    </div>
    <div class="mt-1 mb-3 clearfix" style="position: relative;">
                        <input type="text" id="searchInput" placeholder="Search for records...">
                        <i class="fa fa-search" style="cursor: pointer; color:#69707a; position: absolute; top: 50%; right: 700px; transform: translateY(-50%); cursor: pointer; position: absolute;"></i>

    <select id="sortSelect">
        <option style="color:black;" disabled selected>Sort by</option>
        <option value="all">Show All</option>
        <option value="timestamp_asc">Sort by Timestamp (Ascending)</option>
        <option value="timestamp_desc">Sort by Timestamp (Descending)</option>
    </select>
    <input type="date" id="dateFilterInput" placeholder="Select a date" style="background: #f5f7f9; border-width:thin; border-radius: 5px; padding: 5px 5px 5px 10px;">
<button id="filterByDateButton">Filter by Date</button>
<a href="download_logs.php" download=""><button class="btn-get-started scrollto"  style = "background-color: #0294DB; color: white; border: none; border-radius: 5px; padding: 5px 10px 5px 10px;">Download</button></a>

    </div>

                    <?php
                    
                    require_once "config.php";
                    
              
                    $sql = "SELECT * FROM sensor_logs ORDER BY timestamp DESC";
                    if($result = mysqli_query($link, $sql)){
                        if(mysqli_num_rows($result) > 0){
                            echo '<div class="table-container">';
                          
                            echo '<table class="table table-bordered table-striped">';
                                echo "<thead>";
                                    echo "<tr>";
                                        echo "<th class='table-header-gas'>Gas</th>";
                                        echo "<th class='table-header-smoke'>Smoke</th>";
                                        echo "<th class='table-header-water'>Water</th>";
                                        echo "<th class='table-header-timestamp'>Timestamp</th>";
                                    echo "</tr>";
                                echo "</thead>";
                                echo "<tbody>";
                                while($row = mysqli_fetch_array($result)){
                                    echo "<tr>";
                                        echo "<td>" . $row['gas_sensor'] . "</td>";
                                        echo "<td>" . $row['smoke_sensor'] . "</td>";
                                        echo "<td>" . $row['water_sensor'] . "</td>";
                                        $timestamp = strtotime($row['timestamp']);
                                        $formattedTimestamp = date('F j, Y h:i:sA', $timestamp);
                                        echo "<td>" . $formattedTimestamp . "</td>";
                                        
                                       
                                        echo "<td class='table-header-timestamp-ymd' style='display: none;'>" . date('Y-m-d', $timestamp) . "</td>";
                                    echo "</tr>";
                                }
                                echo "</tbody>";                            
                            echo "</table>";
                            echo '</div>';
                         
                            mysqli_free_result($result);
                        } else{
                            echo '<div class="alert alert-danger"><em>No records were found.</em></div>';
                        }
                    } else{
                        echo "Oops! Something went wrong. Please try again later.";
                    }
 
                    
                    mysqli_close($link);
                    ?>
                    <div id="noDateRecords" class="alert alert-secondary" style="display: none;">
    <p>No records found for the selected date.</p>
</div>
<div id="noRecordsFound" class="alert alert-secondary" style="display: none;">
    <p>No records found.</p>
</div>
                </div>
            </div>        
    </div>
</section>
    <script>

    $(document).ready(function(){
       
        $("#searchInput").on("keyup", function() {
            var value = $(this).val().toLowerCase();
            $("table tbody tr").filter(function() {
                var text = $(this).text().toLowerCase();
                var rowVisible = text.indexOf(value) > -1; 
                $(this).toggle(rowVisible); 
                return rowVisible; 
            });

           
            if ($("table tbody tr:visible").length === 0) {
                $("#noRecordsFound").show();
            } else {
                $("#noRecordsFound").hide();
            }

    });


    
    $("#sortSelect").on("change", function() {
        var selectedOption = $(this).val();
        var rows = $("table tbody tr").get();

        if (selectedOption === "all") {
            $("table tbody tr").show(); 
            return;
        }

       
        rows.sort(function(a, b) {
            var aValue, bValue;
            if (selectedOption === "timestamp_asc" || selectedOption === "timestamp_desc") {
                aValue = new Date($(a).find("td:eq(3)").text());
                bValue = new Date($(b).find("td:eq(3)").text());
            } else {
                aValue = $(a).find("td:eq(2)").text().toLowerCase();
                bValue = $(b).find("td:eq(2)").text().toLowerCase();
            }

            if (selectedOption === "timestamp_desc" || selectedOption === "water_sensor_desc") {
                return aValue < bValue ? 1 : -1;
            } else {
                return aValue > bValue ? 1 : -1;
            }
        });

        $("table tbody").empty();
        $.each(rows, function(index, row) {
            $("table tbody").append(row);
        });
    });



$("#filterByDateButton").on("click", function () {
    var selectedDate = $("#dateFilterInput").val(); 

    if (selectedDate.trim() === "") {
       
        alert("Please select a date.");
        return;
    }

    var dateFound = false;
    $("table tbody tr").hide(); 
    $("table tbody tr").filter(function () {
    
        var rowTimestampFj = $(this).find("td:eq(3)").text();
        var rowTimestampYMD = $(this).find(".table-header-timestamp-ymd").text();

        
        if (rowTimestampFj === selectedDate || rowTimestampYMD === selectedDate) {
            dateFound = true; 
            return true; 
        } else {
            return false; 
        }
    }).show();

    if (!dateFound) {
        $("#noDateRecords").show();
    } else {
        $("#noDateRecords").hide();
    }
});


    });

</script>

</body>
</html>