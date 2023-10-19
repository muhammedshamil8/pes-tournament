const navToggle = document.getElementById('nav-toggle');
const navContent = document.querySelector('.nav-content');
const header = document.querySelector('header');
// navToggle.addEventListener('change', () => {
//   navContent.style.display = navToggle.checked ? 'flex' : 'none';
// });


navToggle.addEventListener('change', () => {
    if (navToggle.checked) {
      navContent.style.display = 'flex';
      navContent.classList.remove('hidden');
      header.classList.add('hidden');

    } else {
      navContent.classList.add('hidden');
      header.classList.remove('hidden');

    }
});

const forgetPassMessages = [
  "Forgot your password? Maybe it's on a vacation with your socks, sipping margaritas by the beach. Let's get them both back in action! 🏖️🧦😄",
  "Oops! Forgot your password? No worries, even the best hackers forget their passwords sometimes. Let's get you back in! 🕵️‍♂️😄",
  "You forgot your password? That's like leaving the house without pants. Let's cover that up and get you logged in! 🩳🤭😄",
  "Forgot your password? Did it go for a walk with the neighbor's cat again? Let's call it back home and get you logged in! 🐱🚶‍♂️😄",
  "Password, where art thou? Probably on an adventure with Frodo. Let's bring it back to the Shire and into your account! 🌄🧙‍♂️😄",
  "Oh dear, you've forgotten your password? It must be off having an identity crisis. Let's remind it who it is and get you logged in! 🤖🤷‍♂️😄"
];


let currentIndex = 0;
let timeoutId;

const showForgetPassMessage = () => {
  const customAlertMessage = document.getElementById('custom-alert-message');
  customAlertMessage.innerText = forgetPassMessages[currentIndex];
  currentIndex = (currentIndex + 1) % forgetPassMessages.length;

  const alertBox = document.getElementById('custom-alert');
  alertBox.style.display = 'block';

  if (timeoutId) {
    clearTimeout(timeoutId);
  }

 
  // Add fade-in animation class
  const card = document.querySelector('.card');
  card.classList.remove('fade-in-animation'); 
  void card.offsetWidth; 
  card.classList.add('fade-in-animation'); 


  
  timeoutId = setTimeout(() => {
    closeCustomAlert();
  }, 11000);
};

const closeCustomAlert = () => {
  const alertBox = document.getElementById('custom-alert');
  alertBox.style.display = 'none';
};



// document.querySelector('.forget-pass').addEventListener('click', showForgetPassMessage);

function loadingprocess(){
  function showLoading() {
    document.getElementById('loading-overlay').style.display = 'block';
  }
  
  function hideLoading() {
    document.getElementById('loading-overlay').style.display = 'none';
  }
  
  function loadData() {
    showLoading();
    setTimeout(function() {
      
      hideLoading();
    }, 3150); 
  }
  loadData();
}
loadingprocess();



// function simulateLoading() {
//   let progressBar = document.getElementById('progress-bar');
//   let progressText = document.getElementById('progress-text');
//   let width = 0;
//   let interval = setInterval(frame, 20);

//   function frame() {
//     if (width >= 100) {
//       clearInterval(interval);
//     } else {
//       width++;
//       progressBar.style.width = width + '%';
//       progressText.innerText = width + '%';
//     }
//   }
// }

// document.getElementById('start-loading-btn').addEventListener('click', function () {
//   // Reset the progress bar
//   let progressBar = document.getElementById('progress-bar');
//   progressBar.style.width = '0';
//   document.getElementById('progress-text').innerText = '0%';

//   simulateLoading();
// });

// window.onload = function () {
//   simulateLoading();
// };

// document.getElementById('start-loading-btn').addEventListener('click', function () {
//   simulateLoading();
// });
// ajax function
function loadDoc(url) {
  var xhttp = new XMLHttpRequest();
  xhttp.onreadystatechange = function() {
    if (this.readyState == 4 && this.status == 200) {
      document.getElementById("content").innerHTML = this.responseText;
      // Change the URL without page refresh
      history.pushState(null, null, url);
      // Reinitialize event listeners for navigation toggle
      initNavToggleListeners();
      
// loadpage();

    }
  };
  xhttp.open("GET", url, true);
  xhttp.send();
}
function loadpage(){
  function showLoading() {
    document.getElementById('loading-page').style.display = 'block';
  }
  
  function hideLoading() {
    document.getElementById('loading-page').style.display = 'none';
  }
  
  function loadData() {
    showLoading();
    setTimeout(function() {
      
      hideLoading();
    }, 2000); 
  }
  loadData();
}
function initNavToggleListeners() {
  const navToggle = document.getElementById('nav-toggle');
  const navContent = document.querySelector('.nav-content');
  const header = document.querySelector('header');

  navToggle.addEventListener('change', () => {
    if (navToggle.checked) {
      navContent.style.display = 'flex';
      navContent.classList.remove('hidden');
      header.classList.add('hidden');
    } else {
      navContent.classList.add('hidden');
      header.classList.remove('hidden');
    }
  });
}


