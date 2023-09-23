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
