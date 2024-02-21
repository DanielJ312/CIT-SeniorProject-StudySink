document.addEventListener("DOMContentLoaded", function() {
    let cards = document.querySelectorAll('.cardContainer');
    let currentCardIndex = 0;

    // Initialize the counter
    const totalCardsElement = document.getElementById('totalCards');
    const currentCardIndexElement = document.getElementById('currentCardIndex');
    totalCardsElement.textContent = cards.length;
    currentCardIndexElement.textContent = currentCardIndex + 1;

    // Initially hide all cards except the first one
    cards.forEach((card, index) => {
        card.style.display = index === 0 ? 'block' : 'none';
    });

    // Add flipping functionality to each card
    cards.forEach(card => {
        card.addEventListener('click', () => {
            card.classList.toggle('is-flipped');
        });
    });

    // Handle Back and Next button clicks
    handleNavigationButtons();

    // Handle shuffling
    const shuffleButton = document.getElementById('shuffleButton');
    shuffleButton.addEventListener('click', shuffleCards);

    function shuffleCards() {
        // Shuffle the cards array
        for (let i = cards.length - 1; i > 0; i--) {
            let j = Math.floor(Math.random() * (i + 1));
            [cards[i], cards[j]] = [cards[j], cards[i]];
        }

        // Append shuffled cards to the DOM
        const cardsContainer = document.querySelector('.cards');
        cardsContainer.innerHTML = ''; // Clear existing cards
        Array.from(cards).forEach(card => {
            cardsContainer.appendChild(card); // Append shuffled card
            card.style.display = 'none'; // Hide all cards
        });

        // Reset current card index and display the first card
        currentCardIndex = 0;
        cards[currentCardIndex].style.display = 'block';
        currentCardIndexElement.textContent = currentCardIndex + 1;
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
        cards[currentCardIndex].style.display = 'none'; // Hide current card
        cards[currentCardIndex].classList.remove('is-flipped'); // Reset flip state
        currentCardIndex += offset;
        cards[currentCardIndex].style.display = 'block'; // Show new card
        currentCardIndexElement.textContent = currentCardIndex + 1;
    }
});
