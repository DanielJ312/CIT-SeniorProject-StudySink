document.addEventListener("DOMContentLoaded", function() {
    const cards = document.querySelectorAll('.cardContainer');
    let currentCardIndex = 0;

    // Initialize the counter
    const totalCardsElement = document.getElementById('totalCards');
    const currentCardIndexElement = document.getElementById('currentCardIndex');
    totalCardsElement.textContent = cards.length;
    currentCardIndexElement.textContent = currentCardIndex + 1;

    // Initially hide all cards except the first one
    cards.forEach((card, index) => {
        card.style.display = index === 0 ? 'block' : 'none';
        console.log(`Card ${index} display:`, card.style.display); // Debugging
    });

    // Add flipping functionality to each card
    cards.forEach(card => {
        card.addEventListener('click', () => {
            card.classList.toggle('is-flipped');
        });
    });

    // Handle Back button click
    const backButton = document.getElementById('backButton');
    backButton.addEventListener('click', function() {
        if (currentCardIndex > 0) {
            cards[currentCardIndex].style.display = 'none'; // Hide current card
            currentCardIndex--;
            cards[currentCardIndex].style.display = 'block'; // Show previous card
            cards[currentCardIndex].classList.remove('is-flipped'); // Reset flip state
            currentCardIndexElement.textContent = currentCardIndex + 1;
        }
    });

    // Handle Next button click
    const nextButton = document.getElementById('nextButton');
    nextButton.addEventListener('click', function() {
        if (currentCardIndex < cards.length - 1) {
            cards[currentCardIndex].style.display = 'none'; // Hide current card
            currentCardIndex++;
            cards[currentCardIndex].style.display = 'block'; // Show next card
            cards[currentCardIndex].classList.remove('is-flipped'); // Reset flip state
            currentCardIndexElement.textContent = currentCardIndex + 1;
        }
    });

    // Handle shuffling
    const shuffleButton = document.getElementById('shuffleButton');
    shuffleButton.addEventListener('click', shuffleCards);

    function shuffleCards() {
        // Reset current card index and flip state
        cards[currentCardIndex].style.display = 'none';
        cards[currentCardIndex].classList.remove('is-flipped');
        currentCardIndex = 0;

        // Shuffle logic
        const cardsArray = Array.from(cards);
        for (let i = cardsArray.length - 1; i > 0; i--) {
            let j = Math.floor(Math.random() * (i + 1));
            [cardsArray[i].style.order, cardsArray[j].style.order] = [cardsArray[j].style.order, cardsArray[i].style.order];
        }

        // Ensure the first card is displayed after shuffling
        cards[0].style.display = 'block';
        currentCardIndexElement.textContent = 1;
    }
});
