document.addEventListener("DOMContentLoaded", function () {
   const settingsBtn = document.getElementById("settingsBtn");
   const settingsCard = document.getElementById("settingsCard");
   const closeSettingsBtn = document.getElementById("closeSettingsBtn");

   // Show the settings card when the button is clicked
   settingsBtn.addEventListener("click", function () {
       settingsCard.classList.remove("hidden");
   });

   // Hide the settings card when the close button is clicked
   closeSettingsBtn.addEventListener("click", function () {
       settingsCard.classList.add("hidden");
   });
});

function loadingprocess(){
    function showLoading() {
      document.getElementById('loading-overlay').style.display = 'block';
    }
    
    function hideLoading() {
      document.getElementById('loading-overlay').style.display = 'none';
    }
    
    // Simulate a delay (you would replace this with actual data fetching)
    function loadData() {
      showLoading();
      setTimeout(function() {
        // Load your content here
        // document.querySelector('.container1').innerHTML = '<p>Your content goes here</p>';
        hideLoading();
      }, 2000); // Simulate a 2-second delay
    }
    
    // Trigger the loading animation when loading content
    loadData();
  }
  loadingprocess()