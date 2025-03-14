
  // Hide Alert Messages After 3 Seconds
  document.addEventListener("DOMContentLoaded", function() {
    // Check if there are any alert boxes (success or error)
    const alertBox = document.querySelector(".alert-box");
    
    if (alertBox) {
      // Auto-hide the alert box after 5 seconds
      setTimeout(function() {
        alertBox.style.display = "none";
      }, 5000);

      // Allow the user to close the alert box manually
      const closeBtn = document.createElement("button");
      closeBtn.innerHTML = "×"; // Close icon
      closeBtn.style.position = "absolute";
      closeBtn.style.top = "5px";
      closeBtn.style.right = "10px";
      closeBtn.style.background = "transparent";
      closeBtn.style.border = "none";
      closeBtn.style.fontSize = "20px";
      closeBtn.style.cursor = "pointer";
      
      alertBox.style.position = "relative"; // Ensure the close button is positioned correctly
      alertBox.appendChild(closeBtn);
      
      closeBtn.addEventListener("click", function() {
        alertBox.style.display = "none"; // Hide the alert when the close button is clicked
      });
    }
  });

