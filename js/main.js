// Mobile Menu Toggle
const hamburger = document.getElementById('hamburger');
const navMenu = document.getElementById('navMenu');

if (hamburger) {
    hamburger.addEventListener('click', function() {
        navMenu.classList.toggle('active');
        hamburger.classList.toggle('active');
    });
}

// Form Validation
function validateForm(formId) {
    const form = document.getElementById(formId);
    if (!form) return true;
    
    const inputs = form.querySelectorAll('input[required], select[required], textarea[required]');
    let isValid = true;
    
    inputs.forEach(input => {
        if (!input.value.trim()) {
            isValid = false;
            input.style.borderColor = 'var(--danger-color)';
        } else {
            input.style.borderColor = '#ddd';
        }
    });
    
    return isValid;
}

// Email Validation
function validateEmail(email) {
    const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return re.test(email);
}

// Phone Validation
function validatePhone(phone) {
    const re = /^[0-9]{10}$/;
    return re.test(phone.replace(/\s/g, ''));
}

// Calculate Total Price
function calculateTotalPrice() {
    const pickupDate = document.getElementById('pickup_date');
    const returnDate = document.getElementById('return_date');
    const pricePerDay = document.getElementById('price_per_day');
    const totalPrice = document.getElementById('total_price');
    const totalDays = document.getElementById('total_days');
    
    if (pickupDate && returnDate && pricePerDay && pickupDate.value && returnDate.value) {
        const pickup = new Date(pickupDate.value);
        const returnD = new Date(returnDate.value);
        const days = Math.ceil((returnD - pickup) / (1000 * 60 * 60 * 24));
        
        if (days > 0) {
            const price = parseFloat(pricePerDay.value);
            const total = days * price;
            
            if (totalDays) totalDays.textContent = days;
            if (totalPrice) totalPrice.textContent = '₹' + total.toLocaleString();
            
            document.getElementById('total_days_input').value = days;
            document.getElementById('total_price_input').value = total;
        }
    }
}

// File Upload Preview
function previewImage(input, previewId) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById(previewId).src = e.target.result;
            document.getElementById(previewId).style.display = 'block';
        };
        reader.readAsDataURL(input.files[0]);
    }
}

// Confirm Delete
function confirmDelete(message = 'Are you sure you want to delete this?') {
    return confirm(message);
}

// Show Loading
function showLoading(buttonId) {
    const button = document.getElementById(buttonId);
    if (button) {
        button.disabled = true;
        button.innerHTML = '<span class="spinner"></span> Processing...';
    }
}

// Hide Loading
function hideLoading(buttonId, originalText) {
    const button = document.getElementById(buttonId);
    if (button) {
        button.disabled = false;
        button.innerHTML = originalText;
    }
}

// Search Functionality
function searchTable(inputId, tableId) {
    const input = document.getElementById(inputId);
    const table = document.getElementById(tableId);
    
    if (!input || !table) return;
    
    input.addEventListener('keyup', function() {
        const filter = input.value.toUpperCase();
        const rows = table.getElementsByTagName('tr');
        
        for (let i = 1; i < rows.length; i++) {
            const row = rows[i];
            const cells = row.getElementsByTagName('td');
            let found = false;
            
            for (let j = 0; j < cells.length; j++) {
                const cell = cells[j];
                if (cell) {
                    const textValue = cell.textContent || cell.innerText;
                    if (textValue.toUpperCase().indexOf(filter) > -1) {
                        found = true;
                        break;
                    }
                }
            }
            
            row.style.display = found ? '' : 'none';
        }
    });
}

// Filter Cars
function filterCars() {
    const series = document.getElementById('filter_series')?.value.toLowerCase();
    const transmission = document.getElementById('filter_transmission')?.value.toLowerCase();
    const fuelType = document.getElementById('filter_fuel')?.value.toLowerCase();
    const maxPrice = parseFloat(document.getElementById('filter_price')?.value) || Infinity;
    
    const carCards = document.querySelectorAll('.car-card');
    
    carCards.forEach(card => {
        const cardSeries = card.getAttribute('data-series')?.toLowerCase() || '';
        const cardTransmission = card.getAttribute('data-transmission')?.toLowerCase() || '';
        const cardFuel = card.getAttribute('data-fuel')?.toLowerCase() || '';
        const cardPrice = parseFloat(card.getAttribute('data-price')) || 0;
        
        let show = true;
        
        if (series && !cardSeries.includes(series)) show = false;
        if (transmission && transmission !== cardTransmission) show = false;
        if (fuelType && fuelType !== cardFuel) show = false;
        if (cardPrice > maxPrice) show = false;
        
        card.style.display = show ? 'block' : 'none';
    });
}

// Date Validation
function validateDates() {
    const pickupDate = document.getElementById('pickup_date');
    const returnDate = document.getElementById('return_date');
    
    if (pickupDate && returnDate) {
        const today = new Date().toISOString().split('T')[0];
        pickupDate.setAttribute('min', today);
        
        pickupDate.addEventListener('change', function() {
            const selectedDate = new Date(this.value);
            selectedDate.setDate(selectedDate.getDate() + 1);
            const minReturn = selectedDate.toISOString().split('T')[0];
            returnDate.setAttribute('min', minReturn);
            returnDate.value = '';
        });
    }
}

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    // Auto-dismiss alerts after 5 seconds
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(alert => {
        setTimeout(() => {
            alert.style.display = 'none';
        }, 5000);
    });
    
    // Initialize date validation
    validateDates();
    
    // Add event listeners for date fields
    const pickupDate = document.getElementById('pickup_date');
    const returnDate = document.getElementById('return_date');
    
    if (pickupDate) pickupDate.addEventListener('change', calculateTotalPrice);
    if (returnDate) returnDate.addEventListener('change', calculateTotalPrice);
});

// AJAX Helper Function
async function fetchAPI(url, options = {}) {
    try {
        const response = await fetch(url, {
            headers: {
                'Content-Type': 'application/json',
                ...options.headers
            },
            ...options
        });
        
        const data = await response.json();
        return data;
    } catch (error) {
        console.error('API Error:', error);
        return { success: false, message: 'An error occurred' };
    }
}

// Smooth Scroll
document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function(e) {
        e.preventDefault();
        const target = document.querySelector(this.getAttribute('href'));
        if (target) {
            target.scrollIntoView({
                behavior: 'smooth',
                block: 'start'
            });
        }
    });
});