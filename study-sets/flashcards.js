document.addEventListener("DOMContentLoaded", function() {
    let cards = document.querySelectorAll('.cardContainer');
    let currentCardIndex = 0;

    const totalCardsElement = document.getElementById('totalCards');
    const currentCardIndexElement = document.getElementById('currentCardIndex');
    totalCardsElement.textContent = cards.length;
    currentCardIndexElement.textContent = currentCardIndex + 1;

    cards.forEach((card, index) => {
        card.style.display = index === 0 ? 'block' : 'none';
    });

    cards.forEach(card => {
        card.addEventListener('click', () => {
            card.classList.toggle('is-flipped');
        });
    });

    handleNavigationButtons();

    const shuffleButton = document.getElementById('shuffleButton');
    shuffleButton.addEventListener('click', shuffleCards);

    function shuffleCards() {
        let cardsArray = Array.from(cards);
        
        for (let i = cardsArray.length - 1; i > 0; i--) {
            let j = Math.floor(Math.random() * (i + 1));
            [cardsArray[i], cardsArray[j]] = [cardsArray[j], cardsArray[i]];
        }
    
        const cardsContainer = document.querySelector('.cards');
        cardsContainer.innerHTML = '';
    
        cardsArray.forEach(card => {
            cardsContainer.appendChild(card);
            card.style.display = 'none';
        });
    
        currentCardIndex = 0;
        cardsArray[currentCardIndex].style.display = 'block';
        currentCardIndexElement.textContent = currentCardIndex + 1;
    
        cards = document.querySelectorAll('.cardContainer');
    }

    function handleNavigationButtons() {
        const backButton = document.getElementById('backButton');
        const nextButton = document.getElementById('nextButton');

        backButton.addEventListener('click', function() {
            if (currentCardIndex > 0) {
                navigateToCard(-1);
            }
        });

        nextButton.addEventListener('click', function() {
            if (currentCardIndex < cards.length - 1) {
                navigateToCard(1);
            }
        });
    }

    function navigateToCard(offset) {
        const oldCard = cards[currentCardIndex];
        const newCardIndex = currentCardIndex + offset;
        const newCard = cards[newCardIndex];

        // Start the fade-out animation
        oldCard.classList.add('fade-out');

        // When the fade-out animation ends, hide the old card, update the index, and fade in the new card
        oldCard.addEventListener('animationend', function handler() {
            oldCard.style.display = 'none'; // Hide the old card
            oldCard.classList.remove('fade-out', 'is-flipped'); // Clean up classes
            
            currentCardIndex = newCardIndex; // Update the index

            // Prepare the new card for animation
            newCard.style.display = 'block';
            newCard.classList.add('fade-in'); // Start the fade-in animation
            newCard.addEventListener('animationend', function fadeinHandler() {
                newCard.classList.remove('fade-in'); // Clean up fade-in class
                newCard.removeEventListener('animationend', fadeinHandler);
            });

            currentCardIndexElement.textContent = currentCardIndex + 1;

            // Remove the event listener to prevent it from triggering more than once
            oldCard.removeEventListener('animationend', handler);
        });
    }
});
