
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

    // Ensure custom select works without conflicts
    const elSelectCustom = document.getElementsByClassName("js-selectCustom")[0];
    const elSelectCustomValue = elSelectCustom?.children[0];
    const elSelectCustomOptions = elSelectCustom?.children[1];
    let selectedSortValue = elSelectCustomValue?.getAttribute("data-value");

    if (elSelectCustom && elSelectCustomValue && elSelectCustomOptions) {
        Array.from(elSelectCustomOptions.children).forEach((elOption) => {
            elOption.addEventListener("click", (e) => {
                elSelectCustomValue.textContent = e.target.textContent;
                selectedSortValue = e.target.getAttribute("data-value");
                elSelectCustomValue.setAttribute("data-value", selectedSortValue);
                elSelectCustom.classList.remove("isActive");
            });
        });

        elSelectCustomValue.addEventListener("click", () => {
            elSelectCustom.classList.toggle("isActive");
        });

        document.addEventListener("click", (e) => {
            if (!elSelectCustom.contains(e.target)) {
                elSelectCustom.classList.remove("isActive");
            }
        });
    }
});

