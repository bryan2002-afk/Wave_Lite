const profileBtn = document.querySelector('.profile-btn');
const dropdownMenu = document.querySelector('.dropdown-menu');

const arrowIcon = profileBtn.querySelector('.arrow');

profileBtn.addEventListener('click', () => {
    dropdownMenu.classList.toggle('active');
    arrowIcon.classList.toggle('rotate');
})

window.addEventListener('click', (e) => {
    const clickedOutside =
    !profileBtn.contains(e.target) &&
    !dropdownMenu.contains(e.target);

    if (clickedOutside) {
        dropdownMenu.classList.remove('active');
        arrowIcon.classList.remove('rotate');
    }
})
