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
        cards[currentCardIndex].style.display = 'none';
        cards[currentCardIndex].classList.remove('is-flipped'); 
        currentCardIndex += offset;
        cards[currentCardIndex].style.display = 'block';
        currentCardIndexElement.textContent = currentCardIndex + 1;
    }
});
