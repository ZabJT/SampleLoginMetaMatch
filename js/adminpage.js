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
    // Check if the modal is already open
    if (addEditModal.classList.contains("active")) {
      // Close the modal if it's already open
      addEditModal.classList.remove("active");
    } else {
      // Open the modal and populate with selected product details
      if (selectedProductIds.length === 1) {
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
  
          // Open the modal
          addEditModal.classList.add("active");
        }
      } else {
        alert("Please select exactly one product to edit.");
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

function toggleAddCPUForm() {
    var addCPUForm = document.getElementById('addCPUForm');
    if (addCPUForm.style.display === 'none' || addCPUForm.style.display === '') {
      addCPUForm.style.display = 'block';
    } else {
      addCPUForm.style.display = 'none';
    }
  }

  // Toggle visibility of Edit CPU form when edit is clicked
function toggleEditCPUForm(event, editId) {
  event.preventDefault(); // Prevent navigation
  const editForm = document.getElementById("editCPUForm");



  // Toggle visibility
  if (editForm.style.display === "none" || editForm.style.display === "") {
    editForm.style.display = "block";
  } else {
    editForm.style.display = "none";
  }


  // Optionally, update the form action with the selected ID
  const urlParams = new URLSearchParams(window.location.search);
  urlParams.set("edit_id", editId);
  history.replaceState(null, "", `?${urlParams.toString()}`);

}



function applySort() {
    const sort = document.getElementById('sort').value;
    window.location.href = 'admin-dashboard.php?sort=' + sort;
}

    // Check if the modal is already open
    if (addEditModal.classList.contains("active")) {
      // Close the modal if it's already open
      addEditModal.classList.remove("active");
    } else {
      // Open the modal
      modalTitle.textContent = "Edit Product";
      const selectedProduct = cpuProducts.find(product => product.id === selectedProductIds[0]);
      if (selectedProduct) {
        // Populate the form fields with the selected product's data
        document.getElementById("productId").value = selectedProduct.id;
        document.getElementById("productName").value = selectedProduct.name;
        document.getElementById("productImage").value = selectedProduct.img;
        document.getElementById("productSpecification").value = selectedProduct.specification;
        document.getElementById("productPrice").value = selectedProduct.price;
        document.getElementById("releaseDate").value = selectedProduct.releaseDate;

        // Show the modal
        addEditModal.classList.add("active");
      } else {
        alert("Please select a product to edit.");
      }
    }


// Cancel add/edit modal
cancelBtn.addEventListener("click", () => {
  addEditModal.classList.remove("active");
});

// Delete modal
deleteBtn.addEventListener("click", () => {
  if (selectedProductIds.length > 0) {
    deleteModal.classList.add("active");
  } else {
    alert("Please select at least one product to delete.");
  }
});

// Cancel delete modal
cancelDeleteBtn.addEventListener("click", () => {
  deleteModal.classList.remove("active");
});

// Apply sorting
function applySort() {
  const sortValue = document.getElementById("sort").value;
  window.location.href = `admin-dashboard.php?sort=${sortValue}`;
}

// Open the modal
function toggleAddCPUForm() {
  document.getElementById("addCPUFormModal").style.display = "block";
}

// Close the modal
function closeAddCPUForm() {
  document.getElementById("addCPUFormModal").style.display = "none";
}

// When the user clicks anywhere outside the modal, close it
window.onclick = function(event) {
  var modal = document.getElementById("addCPUFormModal");
  if (event.target == modal) {
    closeAddCPUForm();
  }
}


function openModal(modalId) {
  document.getElementById(modalId).style.display = "block";
}

function closeModal(modalId) {
  document.getElementById(modalId).style.display = "none";
}

// Example to populate Edit Form Modal
function openEditModal(cpuId, data) {
  document.getElementById('edit_cpu_id').value = cpuId;
  document.getElementById('edit_cpu_name').value = data.cpu_name;
  document.getElementById('edit_cpu_cores').value = data.cpu_cores;
  document.getElementById('edit_cpu_clock_speed').value = data.cpu_clock_speed;
  document.getElementById('edit_cpu_socket').value = data.cpu_socket;
  document.getElementById('edit_cpu_technology').value = data.cpu_technology;
  document.getElementById('edit_cpu_date').value = data.cpu_date;
  document.getElementById('edit_cpu_price').value = data.cpu_price;
  document.getElementById('edit_cpu_brand').value = data.cpu_brand;

  openModal('editCPUFormModal');
}

