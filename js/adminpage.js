// DOM Elements
const tableBody = document.getElementById("cpuTableBody");
const gpuTableBody = document.getElementById("gpuTableBody");
const ramTableBody = document.getElementById("ramTableBody");

const selectAllCheckboxes = document.querySelectorAll(".select-all-checkbox");
const addBtn = document.getElementById("addBtn");
const editBtn = document.getElementById("editBtn");
const deleteBtn = document.getElementById("deleteBtn");
const addEditModal = document.getElementById("addEditModal");
const deleteModal = document.getElementById("deleteModal");
const cancelBtn = document.getElementById("cancelBtn");
const confirmDeleteBtn = document.getElementById("confirmDeleteBtn");
const cancelDeleteBtn = document.getElementById("cancelDeleteBtn");
const crudForm = document.getElementById("crudForm");
const modalTitle = document.getElementById("modalTitle");

// Product Data Arrays
let cpuProducts = [];
let gpuProducts = [];
let ramProducts = [];
let selectedProductIds = []; // Track selected product IDs

// Populate Table with Product Data
function populateTable(products, tableBody) {
  tableBody.innerHTML = "";
  products.forEach((product) => {
    const row = document.createElement("tr");
    row.innerHTML = `
      <td><input type="checkbox" name="selectProduct" value="${product.id}"></td>
      <td>${product.id}</td>
      <td>${product.name}</td>
      <td>${product.img}</td>
      <td>${product.specification}</td>
      <td>${product.price}</td>
      <td>${product.releaseDate}</td>
    `;
    tableBody.appendChild(row);
  });
}

// Handle Add/Edit Form Submission
crudForm.addEventListener("submit", (event) => {
  event.preventDefault();
  const productId = document.getElementById("productId").value || "";
  const newProduct = {
    id: productId || Date.now(), // Temporary ID for frontend
    name: document.getElementById("productName").value,
    img: document.getElementById("productImage").value,
    specification: document.getElementById("productSpecification").value,
    price: document.getElementById("productPrice").value,
    releaseDate: document.getElementById("releaseDate").value,
  };

  if (modalTitle.textContent === "Add Product") {
    cpuProducts.push(newProduct);
  } else {
    const index = cpuProducts.findIndex((product) => product.id == productId);
    if (index !== -1) cpuProducts[index] = newProduct;
  }

  populateTable(cpuProducts, tableBody);
  addEditModal.classList.remove("active");
});

// Confirm Delete
confirmDeleteBtn.addEventListener("click", () => {
  cpuProducts = cpuProducts.filter(product => !selectedProductIds.includes(product.id));
  gpuProducts = gpuProducts.filter(product => !selectedProductIds.includes(product.id));
  ramProducts = ramProducts.filter(product => !selectedProductIds.includes(product.id));

  populateTable(cpuProducts, tableBody);
  populateTable(gpuProducts, gpuTableBody);
  populateTable(ramProducts, ramTableBody);

  deleteModal.classList.remove("active");
});

// Select All Checkbox Handling
selectAllCheckboxes.forEach(checkbox => {
  checkbox.addEventListener("change", (event) => {
    const tableId = event.target.closest("table").id;
    const checkboxes = document.querySelectorAll(`#${tableId} input[name="selectProduct"]`);
    checkboxes.forEach(cb => cb.checked = event.target.checked);
    updateSelectedProductIds(checkboxes);
  });
});

// Update Selected Product IDs
function updateSelectedProductIds(checkboxes) {
  selectedProductIds = Array.from(checkboxes)
    .filter(cb => cb.checked)
    .map(cb => parseInt(cb.value));
  deleteBtn.disabled = selectedProductIds.length === 0;
}

// Handle Navigation Links
document.querySelectorAll(".nav-links a").forEach(link => {
  link.addEventListener("click", (event) => {
    const category = event.target.dataset.category;
    document.querySelectorAll("table").forEach(table => {
      table.style.display = table.id === `${category}Table` ? "table" : "none";
    });
  });
});

// Add Button Click
addBtn.addEventListener("click", () => {
  modalTitle.textContent = "Add Product";
  crudForm.reset();
  addEditModal.classList.add("active");
});

// Edit Button Click
editBtn.addEventListener("click", () => {
  if (selectedProductIds.length === 1) {
    const selectedProduct = cpuProducts.find(p => p.id === selectedProductIds[0]);
    if (selectedProduct) {
      modalTitle.textContent = "Edit Product";
      document.getElementById("productId").value = selectedProduct.id;
      document.getElementById("productName").value = selectedProduct.name;
      document.getElementById("productImage").value = selectedProduct.img;
      document.getElementById("productSpecification").value = selectedProduct.specification;
      document.getElementById("productPrice").value = selectedProduct.price;
      document.getElementById("releaseDate").value = selectedProduct.releaseDate;
      addEditModal.classList.add("active");
    }
  } else {
    alert("Please select exactly one product to edit.");
  }
});

// Delete Button Click
deleteBtn.addEventListener("click", () => {
  if (selectedProductIds.length > 0) {
    deleteModal.classList.add("active");
  } else {
    alert("Please select at least one product to delete.");
  }
});

// Cancel Buttons
cancelBtn.addEventListener("click", () => addEditModal.classList.remove("active"));
cancelDeleteBtn.addEventListener("click", () => deleteModal.classList.remove("active"));

// Sorting Functionality
function applySort() {
  var sortValue = document.getElementById('sort').value;
  var currentUrl = new URL(window.location.href);
  currentUrl.searchParams.set('sort', sortValue);  // Update the sort parameter
  window.location.href = currentUrl.toString();  // Redirect to the updated URL
}


// Add New Button: Toggle Add/Edit Form Visibility


// Close the modal when the user clicks on the close button
document.getElementById("closeModal").onclick = function() {
  document.getElementById("addEditModal").classList.remove("active");
}
