// Home V2 Smooth Scroll efect
const scrollButton = document.querySelector('#scroll-button');

if (scrollButton) {
    scrollButton.addEventListener('click', function (e) {
        e.preventDefault();
        const target = document.querySelector('#sec-2');
        if (target) {
            target.scrollIntoView({ behavior: 'smooth' });
        }
    });
}