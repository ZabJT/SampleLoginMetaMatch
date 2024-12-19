const primaryHeader = document.querySelector(".primary-header")
const navToggle = document.querySelector(".mobile-nav-toggle");
const primaryNav = document.querySelector(".primary-nav");

let nextButton = document.getElementById('next');
let prevButton = document.getElementById('prev');
let carousel = document.querySelector('.carousel');
let listHTML = document.querySelector('.carousel .list');

navToggle.addEventListener('click', () => {
    primaryNav.hasAttribute("data-visible") 
        ? navToggle.setAttribute("aria-expanded", false) 
        : navToggle.setAttribute("aria-expanded", true);
    primaryNav.toggleAttribute("data-visible");
    primaryHeader.toggleAttribute("data-overlay");
})



nextButton.onclick = function(){
    showSlider('next');
}

prevButton.onclick = function(){
    showSlider('prev');
}

let unAcceptClick;
const showSlider = (type) => {
    nextButton.style.pointerEvents = 'none';
    prevButton.style.pointerEvents = 'none';

    carousel.classList.remove('prev', 'next');
    let items = document.querySelectorAll('.carousel .list .item'); // Use querySelectorAll
        if (type === 'next') {
            listHTML.appendChild(items[0]);
            carousel.classList.add('next');
        }
        else {
            let positionLast = items.length - 1;
            listHTML.prepend(items[positionLast]);
            carousel.classList.add('prev');
        }
        clearTimeout(unAcceptClick);
        unAcceptClick = setTimeout(() => {
            nextButton.style.pointerEvents = 'auto';
        prevButton.style.pointerEvents = 'auto';
        }, 2000 );
}

document.addEventListener('DOMContentLoaded', function() {
    const cursor = document.querySelector('.cursor');

    document.addEventListener('mousemove', function(e) {
        cursor.style.top = e.pageY + 'px';
        cursor.style.left = e.pageX + 'px';
    });

    const revealElements = document.querySelectorAll('.reveal');

    revealElements.forEach(element => {
        element.addEventListener('mouseover', function() {
            cursor.classList.add('reveal-grow');
        });
        element.addEventListener('mouseout', function() {
            cursor.classList.remove('reveal-grow');
        });
    });

    window.addEventListener('scroll', function() {
        revealElements.forEach(element => {
            var revealTop = element.getBoundingClientRect().top;
            var revealPoint = 150;
            var windowHeight = window.innerHeight;

            if (revealTop < windowHeight - revealPoint) {
                element.classList.add('active');
            } else {
                element.classList.remove('active');
            }
        });
    });

    let currentIndex = 0;
const items = document.querySelectorAll('.slider-item');
const totalItems = items.length;

// Function to show the next item
function showNextItem() {
    // Remove active class from current item
    items[currentIndex].classList.remove('slider-item-active');
    
    // Update index to the next item
    currentIndex = (currentIndex + 1) % totalItems;
    
    // Add active class to the next item
    items[currentIndex].classList.add('slider-item-active');
}

// Show the first item initially
items[currentIndex].classList.add('slider-item-active');

// Change item every 3 seconds (3000 milliseconds)
setInterval(showNextItem, 2000);

})


document.addEventListener("DOMContentLoaded", () => {
    // Mobile Navigation Toggle
    const navToggle = document.querySelector(".mobile-nav-toggle");
    const primaryNav = document.querySelector(".primary-nav");
    const primaryHeader = document.querySelector(".primary-header");

    if (navToggle && primaryNav && primaryHeader) {
        navToggle.addEventListener("click", (event) => {
            const isVisible = primaryNav.hasAttribute("data-visible");
            navToggle.setAttribute("aria-expanded", !isVisible);
            primaryNav.toggleAttribute("data-visible");
            primaryHeader.toggleAttribute("data-overlay");
        });
    }

    // Profile Panel Toggle
    const profileIcon = document.getElementById("profileIcon");
    const profilePanel = document.getElementById("profilePanel");

    if (profileIcon && profilePanel) {
        profileIcon.addEventListener("click", (event) => {
            event.stopPropagation(); // Prevent the event from bubbling
            profilePanel.classList.toggle("active");
        });

        document.addEventListener("click", (event) => {
            if (!profilePanel.contains(event.target) && event.target !== profileIcon) {
                profilePanel.classList.remove("active");
            }
        });
    } else {
        console.error("Profile icon or panel not found in the DOM.");
    }

});

