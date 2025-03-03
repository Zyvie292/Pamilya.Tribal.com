'use strict';

//banner slider
document.addEventListener("DOMContentLoaded", function () {
  const slider = document.querySelector(".slider-container");
  const slides = document.querySelectorAll(".slider-item");
  let index = 0;

  function autoSlide() {
    index++;
    if (index >= slides.length) {
      index = 0; // Reset to first slide
    }
    slider.scrollTo({
      left: slides[index].offsetLeft,
      behavior: "smooth",
    });
  }

  // Auto-slide every 3 seconds
  setInterval(autoSlide, 3000);
});




// modal variables
const modal = document.querySelector('[data-modal]');
const modalCloseBtn = document.querySelector('[data-modal-close]');
const modalCloseOverlay = document.querySelector('[data-modal-overlay]');

// modal function
const modalCloseFunc = function () { modal.classList.add('closed'); }

// modal eventListener
modalCloseOverlay.addEventListener('click', modalCloseFunc);
modalCloseBtn.addEventListener('click', modalCloseFunc);





// mobile menu variables
const mobileMenuOpenBtn = document.querySelectorAll('[data-mobile-menu-open-btn]');
const mobileMenu = document.querySelectorAll('[data-mobile-menu]');
const mobileMenuCloseBtn = document.querySelectorAll('[data-mobile-menu-close-btn]');
const overlay = document.querySelector('[data-overlay]');

for (let i = 0; i < mobileMenuOpenBtn.length; i++) {

  // mobile menu function
  const mobileMenuCloseFunc = function () {
    mobileMenu[i].classList.remove('active');
    overlay.classList.remove('active');
  }

  mobileMenuOpenBtn[i].addEventListener('click', function () {
    mobileMenu[i].classList.add('active');
    overlay.classList.add('active');
  });

  mobileMenuCloseBtn[i].addEventListener('click', mobileMenuCloseFunc);
  overlay.addEventListener('click', mobileMenuCloseFunc);

}


//Horizontal Menu
document.addEventListener("DOMContentLoaded", () => {
  const menuButtons = document.querySelectorAll(".menu-btn");

  menuButtons.forEach((button) => {
    button.addEventListener("click", (e) => {
      e.preventDefault();

      const submenu = button.nextElementSibling;

      // Close other submenus before opening the selected one
      document.querySelectorAll(".submenu").forEach((sub) => {
        if (sub !== submenu) sub.style.display = "none";
      });

      submenu.style.display = submenu.style.display === "block" ? "none" : "block";
    });
  });

  // Close submenus when clicking outside
  document.addEventListener("click", (e) => {
    if (!e.target.closest(".menu-item")) {
      document.querySelectorAll(".submenu").forEach((sub) => (sub.style.display = "none"));
    }
  });
});


// JavaScript for Login & Register Dropdown
const dropdownButton = document.querySelector('.dropdown-button');
const closeButton = document.querySelector('.dropdown-panel-login .close');
const overlay2 = document.querySelector('.overlay-login');
const dropdownPanel = document.querySelector('.dropdown-panel-login');

// Open Dropdown
dropdownButton.addEventListener('click', () => {
    overlay2.style.display = 'block';
    dropdownPanel.style.display = 'block';
});

// Close Dropdown
overlay2.addEventListener('click', closeDropdown);
closeButton.addEventListener('click', closeDropdown);

function closeDropdown() {
    overlay2.style.display = 'none';
    dropdownPanel.style.display = 'none';
}

// Get the overlay and buttons



// Get elements
const overlayLoginForm = document.getElementById('overlay-loginform');
const overlayRegisterForm = document.getElementById('overlay-registerform');
const overlayForgotPassword = document.getElementById('overlay-forgot-password');
const dropdownPanelLogin = document.querySelector('.dropdown-panel-login');

// Open & Close Buttons
const openLoginLink = document.getElementById('open-login');
const closeLoginBtn = document.getElementById('close-btn-login');

const openRegisterLink = document.getElementById('open-register');
const closeRegisterBtn = document.getElementById('close-btn-register');

const openForgotPasswordLink = document.getElementById('open-password');
const closeForgotPasswordBtn = document.getElementById('close-btn-Forgot-Password');

// Initially Hide Overlays Except Login
overlayLoginForm.style.display = "none";
overlayRegisterForm.style.display = "none";
overlayForgotPassword.style.display = "none";

// Open Login Overlay
openLoginLink.addEventListener("click", (event) => {
    event.preventDefault();
    showOverlay(overlayLoginForm);
    hideOverlay(overlayRegisterForm);
    hideOverlay(overlayForgotPassword);
});

// Close Login
closeLoginBtn.addEventListener("click", () => {
    hideOverlay(overlayLoginForm);
});

// Open Register Overlay
openRegisterLink.addEventListener("click", (event) => {
    event.preventDefault();
    showOverlay(overlayRegisterForm);
    hideOverlay(overlayLoginForm);
    hideOverlay(overlayForgotPassword);
});

// Close Register
closeRegisterBtn.addEventListener("click", () => {
    hideOverlay(overlayRegisterForm);
});

// Open Forgot Password Overlay
openForgotPasswordLink.addEventListener("click", (event) => {
    event.preventDefault();
    showOverlay(overlayForgotPassword);
    hideOverlay(overlayLoginForm);
});

// Close Forgot Password
closeForgotPasswordBtn.addEventListener("click", () => {
    hideOverlay(overlayForgotPassword);
    showOverlay(overlayLoginForm);
});

// Utility Functions
function showOverlay(overlay) {
    overlay.style.display = "flex";  // Use flex to center
}

function hideOverlay(overlay) {
    overlay.style.display = "none";
}


// Notifications
document.addEventListener("DOMContentLoaded", function () {
  const notifButton = document.getElementById("notifButton");
  const notifDropdown = document.getElementById("notifDropdown");

  // Toggle the dropdown visibility
  notifButton.addEventListener("click", (event) => {
    event.stopPropagation(); // Prevent event bubbling
    notifDropdown.classList.toggle("active");
  });

  // Close the dropdown when clicking outside
  document.addEventListener("click", () => {
    notifDropdown.classList.remove("active");
  });

  // Prevent closing the dropdown when clicking inside it
  notifDropdown.addEventListener("click", (event) => {
    event.stopPropagation();
  });

});

// JavaScript to hide the alert after 3 seconds
setTimeout(() => {
  const alertBox = document.getElementById('alert-box');
  if (alertBox) {
      alertBox.style.transition = "opacity 0.5s ease"; // Smooth fading effect
      alertBox.style.opacity = "0";
      setTimeout(() => alertBox.style.display = "none", 500); // Ensure it's removed after fading
  }
}, 3000);



//alert meassage//dashboard
document.addEventListener("DOMContentLoaded", () => {
  const alertBox = document.querySelector(".alert-box-dashboard");
  let lastScrollY = window.scrollY;

  window.addEventListener("scroll", () => {
    if (window.scrollY > lastScrollY) {
      // Scrolling down → Hide alert
      alertBox.style.opacity = "0";
      alertBox.style.top = "-60px"; // Move up
    } else {
      // Scrolling up → Show alert
      alertBox.style.opacity = "1";
      alertBox.style.top = "0";
    }
    lastScrollY = window.scrollY;
  });
});

//Add to cart onclick function
function addToCart(productId) {
  fetch('addtobag.php', {
      method: 'POST',
      headers: {
          'Content-Type': 'application/json',
      },
      body: JSON.stringify({ product_id: productId, quantity: 1 }),
  })
  .then(response => response.json())
  .then(data => {
      if (data.success) {
          alert('Product added to cart!');
      } else {
          alert('Error adding product to cart: ' + data.message);
      }
  })
  .catch(error => console.error('Error:', error));
}

function decodeHtmlEntities(text) {
  let textarea = document.createElement("textarea");
  textarea.innerHTML = text;
  return textarea.value;
}

