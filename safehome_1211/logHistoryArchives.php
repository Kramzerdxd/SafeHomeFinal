    <?php 
    session_start();

    $_SESSION['username'];
    $_SESSION['password'];
    if (!isset($_SESSION['username'])) {
        header("location: login.php");
    } 

function archiveOldData($logFilePath, $archiveFilePath, $retentionPeriod) {
    $logContent = file_get_contents($logFilePath);
    $lines = explode("\n", $logContent);

    $archiveContent = '';
    $currentTimestamp = time();

    foreach ($lines as $line) {
        $lineParts = explode("\t", $line);
        if (count($lineParts) >= 1) { 
            $timestamp = strtotime($lineParts[0]);
            if ($currentTimestamp - $timestamp >= $retentionPeriod) {
             
                $archiveContent .= $line . "\n";
            }
        }
    }

   
    file_put_contents($archiveFilePath, $archiveContent, FILE_APPEND);

    
    file_put_contents($logFilePath, $archiveContent, FILE_APPEND);
}


function deleteOldData($logFilePath, $retentionPeriod) {
    $logContent = file_get_contents($logFilePath);
    $lines = explode("\n", $logContent);

    $currentTimestamp = time();

    foreach ($lines as $key => $line) {
        $lineParts = explode("\t", $line);
        if (count($lineParts) >= 1) {
            $timestamp = strtotime($lineParts[0]);
            if ($currentTimestamp - $timestamp >= $retentionPeriod) {
          
                unset($lines[$key]);
            }
        }
    }

   
    file_put_contents($logFilePath, implode("\n", $lines));
}



$mainLogFilePath = 'activity_history.log';
$archiveLogFilePath = 'activity_history_archive.log';


$retentionPeriod = 30 * 24 * 60 * 60; 

if (isset($_POST['deleteOldData'])) {

    if (isset($_POST['timePeriod'])) {
        $selectedTimePeriod = $_POST['timePeriod'];
      
        $retentionPeriod = $selectedTimePeriod * 30 * 24 * 60 * 60;
    }

    archiveOldData($mainLogFilePath, $archiveLogFilePath, $retentionPeriod);
    deleteOldData($archiveLogFilePath, $retentionPeriod);
}


    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Log History Archive</title>
       
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
        <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
      
        <link href="https://fonts.googleapis.com/css?family=League+Spartan:300,300i,400,400i,600,600i,700,700i|Raleway:300,300i,400,400i,500,500i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">
        <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
        <style>

            .table-container {
                max-height: 535px; 
                overflow-y: auto;
                text-align: center; 
            }

            table {
                width: 120px;
                border-collapse: collapse;
                margin: auto; 
            }
            
            table, th, td {
                border: 1px solid black;
            }

            th, td {
                padding: 8px;
                text-align: left;
                color: black;
            }

            .table-header-date{
                background-color: #2D3142  !important; 
                color: #fff;
                text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.4);
                border-radius: 5px; 
                position: sticky;
                top: 0;
                z-index: 1; 
            }

            .table-header-un{
                background-color: #12355B !important; 
                color: #fff;
                text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.4);
                border-radius: 5px; 
                position: sticky;
                top: 0;
                z-index: 1; 
            }

            .table-header-act{
                background-color: #454545 !important; 
                color: #fff;
                text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.4);
                border-radius: 5px; 
                position: sticky;
                top: 0;
                z-index: 1; 

            }

            #searchInput {
                width: 300px; 
                border-width:thin; 
                border-radius: 5px; 
                padding: 5px 5px 5px 10px;
            }

            #sortSelect{
                width:305px; 
                border-width:thin; 
                border-radius: 5px;
                padding: 5px 5px 5px 10px;
        }
            #timePeriodSelect {
                width:200px; 
                border-width:thin; 
                border-radius: 5px;
                padding: 5px 5px 5px 10px;
        }
            #deleteButton:disabled {
                background-color: #808080; 
                color: #ffffff; 
        }
            .btn-get-started {
                background-color: #0294DB; 
                color: white; 
                border: none; 
                border-radius: 5px; 
                padding: 5px 10px 5px 10px;
            
        }

        #form {
            display: flex; 
            align-items: center;
        }


@media screen and (max-width: 576px) {

    #sortSelect, #searchInput {
    width: 100%;
    height: auto;
    margin-bottom: 8px;
  }


  #form {
    flex-direction: column;
    align-items: flex-start;
    margin-bottom: 8px;
  }

  #form #timePeriodSelect, #downloadButton {
    width: 100%;
    height: auto;
  }

.table-header-date,
.table-header-un,
.table-header-act {
    width: auto;
}   

.table-container {
    max-height: 895px; 

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
    <div class="col-md-12">
        <br>
        <div class="mt-5 mb-2 justify-content-center clearfix">
            <h2 class="pull-left" style="color:#2E2E2E; font-weight: bold; padding: 5px; padding-bottom: 1px; text-align: justify;">Activity Logs Archive</h2>
        </div>

        <div class="mt-1 mb-3 clearfix" style="position: relative; display: flex; align-items: center;">
            <input type="text" id="searchInput" placeholder="Search for records..." class="mr-1">
            <i class="fas fa-search" style="cursor: pointer; color:#69707a; position: absolute; right: 700px; top: 50%; transform: translateY(-50%);"></i>
  
            <select id="sortSelect" class="mr-1">
                <option style="color:black;" disabled selected>Sort by</option>
                <option value="all">Show All</option>
                <option value="timestamp_asc">Sort by Timestamp (Ascending)</option>
                <option value="timestamp_desc">Sort by Timestamp (Descending)</option>
            </select>

            <form method="post" action="" id="form">
                <select id="timePeriodSelect" name="timePeriod" onchange="enableDeleteButton()" class="mr-1">
                    <option style="color:black;" disabled selected>Select Time Period</option>
                    <option value="3">3 months old</option>
                    <option value="6">6 months old</option>
                    <option value="9">9 months old</option>
                    <option value="12">1 year and up old</option>
                </select>
                <button class="btn-get-started scrollto" type="submit" name="deleteOldData" id="deleteButton" disabled>Delete</button>
            </form>

            <a href="activity_history_archive.log" download="" class="ml-1">
                <button class="btn-get-started scrollto" id="downloadButton">Download</button>
            </a>
        </div>
    

        <div class="table-container">
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th class='col-md-4 table-header-date'>Date & Time</th>
                    <th class='col-md-4 table-header-un'>Username</th>
                    <th class='col-md-4 table-header-act'>Activity Detail</th>
                </tr>
            </thead>
            <tbody id="logContent" class="table-body">
          
            </tbody>
        </table>
        <div id="noDateRecords" class="alert alert-secondary" style="display: none;">
        <p>No records found for the selected date.</p>
    </div>
    <div id="noRecordsFound" class="alert alert-secondary" style="display: none;">
        <p>No records found.</p>
    </div>
        </div>
        </div>
        </div>        
        </div>
    </section>

<script>

const logContentElement = document.getElementById('logContent');


function fetchAndDisplayLog() {
    fetch('activity_history_archive.log') 
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.text();
        })
        .then(logText => {
            const logLines = logText.split('\n');
            

            const reversedLogLines = logLines.reverse();

            let logTableHTML = '';
            for (const line of reversedLogLines) {
                if (line.trim() !== '') {
                    const [date, username, activityDetail] = line.split('\t');
                    logTableHTML += `
                        <tr>
                            <td>${date}</td>
                            <td>${username}</td>
                            <td>${activityDetail}</td>
                        </tr>
                    `;
                }
            }
            logContentElement.innerHTML = logTableHTML;
        })
        .catch(error => {
            logContentElement.textContent = 'Error fetching log: ' + error.message;
        });
}


fetchAndDisplayLog();

$("#timePeriodSelect").on("change", function() {
    var selectedPeriod = $(this).val();
    var retentionPeriod = selectedPeriod * 30 * 24 * 60 * 60; 

    archiveOldData($mainLogFilePath, $archiveLogFilePath, retentionPeriod);
    deleteOldData($archiveLogFilePath, retentionPeriod);

    fetchAndDisplayLog();
});

function enableDeleteButton() {
        var selectedValue = document.getElementById("timePeriodSelect").value;

        var deleteButton = document.getElementById("deleteButton");
        deleteButton.disabled = selectedValue === "" || selectedValue === "Select Time Period";
    }



    $("#searchInput").on("keyup", function() {
    var value = $(this).val().toLowerCase(); 
    $("table tbody tr").filter(function() {
        var text = $(this).text().toLowerCase();
        var rowVisible = text.indexOf(value) > -1;
        $(this).toggle(rowVisible);
        return rowVisible; 
    });


    $("#noRecordsFound").toggle($("table tbody tr:visible").length === 0);
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

  
    $("table tbody").empty().append(rows);
});

function formatTimestamp(timestamp) {
    const dateParts = timestamp.split('-');
    if (dateParts.length === 3) {
        const [year, month, day] = dateParts;
        return `${month}-${day}-${year.substring(2)}`;
    }
    return timestamp; 
}


$("#filterByDateButton").on("click", function() {
    var selectedDate = $("#dateFilterInput").val().trim();
    if (selectedDate === "") {
        alert("Please select a date.");
        return;
    }

    var dateFound = false;
    $("table tbody").hide();
    $("table tbody").filter(function() {
        var rowTimestamp = $(this).find("td:eq(0)").text();
        var formattedRowTimestamp = formatTimestamp(rowTimestamp);

        if (formattedRowTimestamp === selectedDate) {
            dateFound = true; 
            return true; 
        } else {
            return false; 
        }
    }).show();

  
    $("#noDateRecords").toggle(!dateFound);
});

</script>
</body>
</html>
