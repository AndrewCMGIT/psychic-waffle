function getBasket() {
  return JSON.parse(localStorage.getItem("basket")) || [];
}

function saveBasket(basket) {
  localStorage.setItem("basket", JSON.stringify(basket));
}

function addToBasket(id, name, price) {
  let basket = getBasket();

  const exists = basket.find(item => item.id === id);

  if (!exists) {
    basket.push({ id, name, price });
  }

  saveBasket(basket);
  updateBasketCount();
}

function updateBasketCount() {
  const basket = getBasket();
  const counter = document.getElementById("basket-count");

  if (counter) {
    counter.textContent = basket.length;
  }
}

document.addEventListener("DOMContentLoaded", () => {

  document.querySelectorAll(".reserve-btn").forEach(button => {
    button.addEventListener("click", () => {

      const id = button.dataset.id;
      const name = button.dataset.name;
      const price = Number(button.dataset.price);

      addToBasket(id, name, price);

    });
  });

});

function displayBasket() {
  const basket = getBasket();

  const container = document.getElementById("basket-container");
  const totalElement = document.getElementById("basket-total-price");

  if (!container || !totalElement) return;

  container.innerHTML = "";

  if (basket.length === 0) {
    container.innerHTML = `<div class="empty-basket">Your basket is currently empty.</div>`;
    totalElement.textContent = "£0";
    return;
  }

  let total = 0;

  basket.forEach((car, index) => {
    total += car.price;

    const item = document.createElement("div");
    item.classList.add("basket-item");

    item.innerHTML = `
      <div class="basket-info">
        <span class="basket-title">${car.name}</span>
        <span class="basket-price">£${car.price.toLocaleString()}</span>
      </div>
      <button class="remove-btn" onclick="removeFromBasket(${index})">
        Remove
      </button>
    `;

    container.appendChild(item);
  });

  totalElement.textContent = "£" + total.toLocaleString();
}

function removeFromBasket(index) {
  let basket = getBasket();

  basket.splice(index, 1);

  saveBasket(basket);
  displayBasket();
  updateBasketCount();
}

updateBasketCount();
displayBasket();

const checkoutForm = document.getElementById("checkout-form");
const basketDataInput = document.getElementById("basket-data");

if (checkoutForm && basketDataInput) {
  checkoutForm.addEventListener("submit", function (event) {
    const basket = getBasket();

    if (basket.length === 0) {
      event.preventDefault();
      alert("Your basket is empty.");
      return;
    }

    basketDataInput.value = JSON.stringify(basket);
  });
}