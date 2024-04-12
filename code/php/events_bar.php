<!DOCTYPE html>
<html>

<head>
  <title>Advertising Bar</title>
  <style>
    .ad-container {
      width: 100%;
      min-height: 30px;
      background-color: black;
      color: white;
      overflow: hidden;
      display: flex;
      justify-content: center;
      align-items: center;
      font-family: "Alata";
      font-size: 0.75em;
      margin-bottom: 3px;
    }

    body {
      width: 100%;
      margin-bottom: 10px;
      margin-top: 10px;
      clear: right;
    }

    .ad-content {
      display: inline;
    }

    .ad-message {
      animation: fade-out 2s ease-out forwards 3s;
    }

    @keyframes fade-out {
      from {
        opacity: 1;
      }

      to {
        opacity: 0;
      }
    }
  </style>
</head>

<body>
  <div class="ad-container">
    <div class="ad-content" id="adContent"></div>
  </div>

  <script>
    const adContent = document.getElementById('adContent');
    const messages = [
      "Summer Sale! Up to 50% off on Amazom.ca!",
      "New Arrivals - Iphones & Macs.",
      "Amazon Prime is free for a month!"
    ];
    let currentIndex = 0;

    function updateAdMessage() {
      adContent.textContent = messages[currentIndex];
      currentIndex = (currentIndex + 1) % messages.length;

      // Reset the animation
      adContent.classList.remove('ad-message');
      void adContent.offsetWidth;
      adContent.classList.add('ad-message');
    }

    // Initial message setup
    updateAdMessage();

    // Increased interval for longer message display
    setInterval(updateAdMessage, 5000);  
  </script>
</body>

</html>