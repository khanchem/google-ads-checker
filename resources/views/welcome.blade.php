<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AdSense Checker</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
   .loader {
  border: 16px solid #f3f3f3; /* Light grey */
  border-top: 16px solid #3498db; /* Blue */
  border-radius: 50%;
  width: 120px;
  height: 120px;
  animation: spin 2s linear infinite;
  

  position: fixed;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
}

@keyframes spin {
  0% { transform: rotate(0deg); }
  100% { transform: rotate(360deg); }
}
   </style>
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body>
    <div class="container mt-5">
        <h1>AdSense Checker</h1>
        <form id="adsenseForm" enctype="multipart/form-data">
            <div class="input-group mb-3">
                <input type="file" class="form-control" id="excelFile" name="excelFile" accept=".xls,.xlsx" required>
                <div class="input-group-append">
                    <button class="btn btn-primary" type="submit">Check AdSense</button>
                </div>
            </div>
        </form>
        <div id="results" class="mt-3"></div>
    </div>
 <div style="display: none" class="loader"></div>
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>

    <script>
        $(document).ready(function() {
            const csrfToken = $('meta[name="csrf-token"]').attr('content');

            $('#adsenseForm').submit(function(e) {
                e.preventDefault();
                const formData = new FormData(this);
        $('.loader').show();
                // Send an AJAX request with the CSRF token and the uploaded file
                $.ajax({
                    type: 'POST',
                    url: '/public/url-checker', // Replace with your actual backend endpoint
                    headers: {
                        'X-CSRF-TOKEN': csrfToken, // Include the CSRF token in the headers
                    },
                    
                    data: formData,
                    contentType: false, // Required for file uploads
                    processData: false, // Required for file uploads
                     
                    success: function(response) {
                        displayResults(response);
                        $('.loader').hide();
                    },
                    error: function(xhr, status, error) {
                         $('.loader').hide();
                        console.error('Error:', error);
                            displayResults(response);
                       
                    }
                    
                });
            });

            function displayResults(data) {
                const resultsContainer = $('#results');
                resultsContainer.empty();

                if (data.noAdSenseUrls && data.noAdSenseUrls.length > 0) {
                    resultsContainer.append('<h2>URLs with  AdSense:</h2>');
                    const urlsList = $('<ul>');
                    data.noAdSenseUrls.forEach(function(url) {
                        urlsList.append(`<li>${url}</li>`);
                    });
                    resultsContainer.append(urlsList);
                } else {
                    resultsContainer.append('<p>No URLs with  AdSense found.</p>');
                }
            }
        });
    </script>
     <script type="text/javascript">
 $(document).ready(function() {

$('#buton-click').click(function() {

  // Show the loader
  $('.loader').show();

  // Prevent the form from submitting normally
 
});
});

       </script>
</body>
</html>
