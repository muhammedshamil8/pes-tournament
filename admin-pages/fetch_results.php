<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', '1');
require "connect_db.php";

?>
 <script>
      function search2() {
            const matchId = document.getElementById('searchInput').value.toLowerCase();
            const tableRows = document.querySelectorAll('#matchTableBody tr');

            tableRows.forEach(row => {
                const matchIdCell = row.querySelector('td:first-child');  // Updated selector to target the first cell
                if (matchIdCell) {
                    const matchIdText = matchIdCell.textContent || matchIdCell.innerText;

                    if (matchIdText.toLowerCase().includes(matchId)) {
                        row.style.display = '';
                    } else {
                        row.style.display = 'none';
                    }
                }
            });
          }
          function fetchResultCardDetails(searchCriteria) {
    return fetch(`manage_result.php?searchCriteria=${searchCriteria}`)
        .then(response => {
            if (!response.ok) {
                throw new Error('Error fetching result card details');
            }
            return response.json();
        })
        .then(data => {
            console.log('Response data:', data); // Log the response data
            return data;
        })
        .catch(error => {
            console.error('Error:', error);
            // Handle the error as needed
        });
}


function displayCardDetails(cardDetails) {
    const cardsContainer = document.querySelector('#cardsContainer');
    cardsContainer.innerHTML = '';

    cardDetails.forEach(card => {
        const cardDiv = document.createElement('div');
        cardDiv.classList.add('card');

        const cardContent = `
            <p>Name: ${card.user_id}</p>
            <img src="${card.result_image}" alt="Image Alt Text" />
            <p>Score: ${card.team1_score} - ${card.team2_score}</p>
            <p>Message: ${card.message}</p>
            <p>Match Active: ${card.match_active === 1 ? 'Yes' : 'No'}</p>
        `;

        cardDiv.innerHTML = cardContent;
        cardsContainer.appendChild(cardDiv);
    });
}

function search() {
    const matchId = document.getElementById('searchInput').value;
    fetchResultCardDetails(matchId)
        .then(response => {
            if (response.ok) {
                return response.json();
            } else {
                console.error('Error:', response.status, response.statusText);
                // Handle the error as needed
            }
        })
        .then(cardDetails => {
            displayCardDetails(cardDetails);
        })
        .catch(error => {
            console.error('Error:', error);
            // Handle the error as needed
        });
}

function showAllCards() {
    const cards = document.querySelectorAll('.card');
    cards.forEach(card => {
        card.style.display = 'block';
    });
}

    </script>