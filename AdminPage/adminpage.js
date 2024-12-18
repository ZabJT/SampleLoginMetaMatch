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

let cpuProducts = [];
let gpuProducts = [];
let ramProducts = [];

let selectedProductIds = []; // Track selected product IDs

// Populate table with product data
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

// Add/Edit product
crudForm.addEventListener("submit", (event) => {
  event.preventDefault();
  const formData = new FormData();
  const productId = document.getElementById("productId").value || "";
  formData.append("id", productId);
  formData.append("productname", document.getElementById("productName").value);
  formData.append("img_dir", document.getElementById("productImage").value);
  formData.append("specs", document.getElementById("productSpecification").value);
  formData.append("price", document.getElementById("productPrice").value);
  formData.append("dor", document.getElementById("releaseDate").value);

  if (modalTitle.textContent === "Add Product") {
    const newProduct = {
      id: Date.now(), // Temporary ID for frontend
      name: document.getElementById("productName").value,
      img: document.getElementById("productImage").value,
      specification: document.getElementById("productSpecification").value,
      price: document.getElementById("productPrice").value,
      releaseDate: document.getElementById("releaseDate").value
    };

    cpuProducts.push(newProduct);
    populateTable(cpuProducts, tableBody); 
  }

  addEditModal.classList.remove("active");
});

confirmDeleteBtn.addEventListener("click", () => {
  cpuProducts = cpuProducts.filter(product => !selectedProductIds.includes(product.id));
  gpuProducts = gpuProducts.filter(product => !selectedProductIds.includes(product.id));
  ramProducts = ramProducts.filter(product => !selectedProductIds.includes(product.id));

  populateTable(cpuProducts, tableBody);
  populateTable(gpuProducts, gpuTableBody);
  populateTable(ramProducts, ramTableBody);

  deleteModal.classList.remove("active");
});

selectAllCheckboxes.forEach(checkbox => {
  checkbox.addEventListener("change", (event) => {
    const category = event.target.closest("table").id;
    const checkboxes = document.querySelectorAll(`#${category} input[name="selectProduct"]`);
    checkboxes.forEach(cb => cb.checked = event.target.checked);
    updateSelectedProductIds(checkboxes);
  });
});

document.querySelectorAll("table").forEach(table => {
  table.addEventListener("change", (event) => {
    if (event.target.name === "selectProduct") {
      const checkboxes = table.querySelectorAll('input[name="selectProduct"]');
      updateSelectedProductIds(checkboxes);
    }
  });
});

function updateSelectedProductIds(checkboxes) {
  selectedProductIds = Array.from(checkboxes)
    .filter(cb => cb.checked)
    .map(cb => parseInt(cb.value));

  
  deleteBtn.disabled = selectedProductIds.length === 0;
}

document.querySelectorAll(".nav-links a").forEach(link => {
  link.addEventListener("click", (event) => {
    const category = event.target.dataset.category;
    document.querySelectorAll("table").forEach(table => {
      table.style.display = table.id === `${category}Table` ? "table" : "none";
    });
  });
});

//Add
addBtn.addEventListener("click", () => {
  modalTitle.textContent = "Add Product";
  crudForm.reset();
  addEditModal.classList.add("active");
});

//Edit
editBtn.addEventListener("click", () => {
  if (selectedProductIds.length === 1) {
    //Find Selected Product
    const selectedProductId = selectedProductIds[0];
    const selectedProduct = [...cpuProducts, ...gpuProducts, ...ramProducts].find(p => p.id === selectedProductId);
    
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
  }
});

//Delete Selected Product/s
deleteBtn.addEventListener("click", () => {
  deleteModal.classList.add("active");
});

//Cancel DeleteBtn
cancelDeleteBtn.addEventListener("click", () => {
  deleteModal.classList.remove("active");
});

//cancel Add/EditBtn
cancelBtn.addEventListener("click", () => {
  addEditModal.classList.remove("active");
});
