// 
function updateNextMatch() {
  // Fetch next match details from your backend and update the HTML
  const nextMatchElement = document.querySelector('.next-match-details');
  nextMatchElement.innerHTML = `
      <p>Next match: 
          <div class='match-info'>
              <div class='match-team-card'><img class='match-team-image' src='${image}' alt='club logo'></div>
              <h4>vs</h4>
              <div class='match-team-card'><img class='match-team-image' src='/images/ac-milan-logo.webp'></div>
          </div>
          <br> on YYYY-MM-DD
      </p>`;
}

// Call this function after a certain interval (e.g., every 5 seconds)
setInterval(updateNextMatch, 5000);

const welcomeParagraph = document.querySelector('.welcome-p');

if (welcomeParagraph) {
  setTimeout(() => {
    welcomeParagraph.style.display = 'none';
  }, 5000);
}

   
    function headerbtn1() {
      const card1 = document.querySelector('.card1');
      const card2 = document.querySelector('.card2');
      const card3 = document.querySelector('.card3');

      card1.style.display = 'block';
      card2.style.display = 'none';
      card3.style.display = 'none';
    }

    function headerbtn2() {
      const card1 = document.querySelector('.card1');
      const card2 = document.querySelector('.card2');
      const card3 = document.querySelector('.card3');

      card1.style.display = 'none';
      card2.style.display = 'block';
      card3.style.display = 'none';
    }

    function headerbtn3() {
      const card1 = document.querySelector('.card1');
      const card2 = document.querySelector('.card2');
      const card3 = document.querySelector('.card3');

      card1.style.display = 'none';
      card2.style.display = 'none';
      card3.style.display = 'block';
    }

