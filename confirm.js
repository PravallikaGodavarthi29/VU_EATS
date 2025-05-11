function confirmOrder() {
  let name = document.getElementById("name").value.trim();
  let phone = document.getElementById("phone").value.trim();
  let orderType = document.getElementById("order-type").value;
  let totalAmount = parseFloat(document.getElementById("total").textContent); // Get total amount

  // Validation
  if (!name || !phone) {
    alert("Please enter all details before proceeding.");
    return;
  }

  if (!/^[a-zA-Z\s]+$/.test(name)) {
    alert("Please enter a valid name.");
    return;
  }

  if (!/^\d{10}$/.test(phone)) {
    alert("Please enter a valid 10-digit phone number.");
    return;
  }

  if (isNaN(totalAmount) || totalAmount <= 0) {
    alert("Invalid total amount. Please check your order.");
    return;
  }

  // Store order details in localStorage
  localStorage.setItem(
    "orderDetails",
    JSON.stringify({ name, phone, orderType, totalAmount })
  );

  // Redirect to payment page
  window.location.href = "payment.html";
}
