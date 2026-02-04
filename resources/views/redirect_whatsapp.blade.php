<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Redirecting to WhatsApp...</title>
    <script type="text/javascript">
        window.onload = function() {
            var url = "{{ $url }}"; // The WhatsApp URL passed from the controller
            var newTab = window.open(url, '_blank'); // Open in a new tab
            if (newTab) {
                newTab.focus();
            } else {
                alert('Please allow popups for this site.');
            }
        };
    </script>
</head>
<body>
    <p>Redirecting you to WhatsApp...</p>
</body>
</html>