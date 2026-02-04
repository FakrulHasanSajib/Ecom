// script.js

const trails = document.querySelectorAll('.trail');
let mouseX = 0, mouseY = 0;

// Initialize trail positions
const trailPositions = Array.from({ length: trails.length }, () => ({ x: 0, y: 0 }));

document.addEventListener('mousemove', (e) => {
  mouseX = e.clientX;
  mouseY = e.clientY;
});

// Animate the trails
function animateTrails() {
  for (let i = trails.length - 1; i > 0; i--) {
    trailPositions[i].x = trailPositions[i - 1].x;
    trailPositions[i].y = trailPositions[i - 1].y;
  }

  trailPositions[0].x = mouseX;
  trailPositions[0].y = mouseY;

  trails.forEach((trail, index) => {
    trail.style.transform = `translate(${trailPositions[index].x}px, ${trailPositions[index].y}px)`;
  });

  requestAnimationFrame(animateTrails);
}

animateTrails();

// Select all input fields
const inputs = document.querySelectorAll('.colorful-input');

// Array of colors for the gradient
const colors = [
  ['#FF5733', '#FFC300', '#33FFCE'],
  ['#33FFCE', '#DAF7A6', '#FF33A6'],
  ['#FFC300', '#DAF7A6', '#33FFCE']
];

// Function to generate random gradients
function getRandomGradient() {
  const colorSet = colors[Math.floor(Math.random() * colors.length)];
  return `linear-gradient(135deg, ${colorSet[0]}, ${colorSet[1]}, ${colorSet[2]})`;
}

// Add event listeners for mouseover and focus
inputs.forEach(input => {
  input.addEventListener('mouseover', () => {
    input.style.background = getRandomGradient();
    input.style.boxShadow = '0px 6px 20px rgba(0, 0, 0, 0.3)';
  });

  input.addEventListener('focus', () => {
    input.style.background = getRandomGradient();
    input.style.boxShadow = '0px 8px 25px rgba(0, 0, 0, 0.5)';
  });

  input.addEventListener('mouseout', () => {
    input.style.background = ''; // Reset background
    input.style.boxShadow = '';  // Reset box shadow
  });

  input.addEventListener('blur', () => {
    input.style.background = ''; // Reset background
    input.style.boxShadow = '';  // Reset box shadow
  });
});

