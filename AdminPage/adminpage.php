<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Dashboard</title>
  <link rel="stylesheet" href="adminpage.css">
</head>
<body>
  <div class="dashboard">
    <aside class="sidebar">
      <div class="logo">
        <img src="images/LOGO COMPANY.png" alt="Logo">
      </div>
      <nav class="nav-links">
        <a href="#" data-category="cpu">CPU</a>
        <a href="#" data-category="gpu">GPU</a>
        <a href="#" data-category="ram">RAM</a>
      </nav>
    </aside>
    <main class="main-content">
      <div class="crud-header">
        <h1>Dashboard</h1>
        <div class="crud-buttons">
          <button id="addBtn">Add</button>
          <button id="editBtn" disabled>Edit</button>
          <button id="deleteBtn" disabled>Delete</button>
        </div>
      </div>
      <div class="crud-table">
        <table id="cpuTable">
          <thead>
            <tr>
              <th><input type="checkbox" id="selectAllCheckbox" class="select-all-checkbox"></th>
              <th>ID</th>
              <th>Product Name</th>
              <th>IMG_dir</th>
              <th>Specification</th>
              <th>Price</th>
              <th>Date of Release</th>
            </tr>
          </thead>

          <tbody id="cpuTableBody">
            
          </tbody>
        </table>

        <table id="gpuTable" style="display:none;">
          <thead>
            <tr>
              <th><input type="checkbox" class="select-all-checkbox"></th>
              <th>ID</th>
              <th>Product Name</th>
              <th>IMG_dir</th>
              <th>Specification</th>
              <th>Price</th>
              <th>Date of Release</th>
            </tr>
          </thead>

          <tbody id="gpuTableBody">
          
          </tbody>
        </table>

        <table id="ramTable" style="display:none;">
          <thead>
            <tr>
              <th><input type="checkbox" class="select-all-checkbox"></th>
              <th>ID</th>
              <th>Product Name</th>
              <th>IMG_dir</th>
              <th>Specification</th>
              <th>Price</th>
              <th>Date of Release</th>
            </tr>
          </thead>

          <tbody id="ramTableBody">

          </tbody>
        </table>
      </div>
    </main>
  </div>

  <!-- Add/Edit -->
  <div class="modal" id="addEditModal">
    <div class="modal-content">
      <h2 id="modalTitle">Add Product</h2>
      <form id="crudForm">
        <input type="hidden" id="productId">
        <label for="productName">Product Name</label>
        <input type="text" id="productName" required>
        
        <label for="productImage">Product Image Directory</label>
        <input type="text" id="productImage" placeholder="Image URL" required>
        
        <label for="productSpecification">Specification</label>
        <textarea id="productSpecification" rows="5"></textarea>
        
        <label for="productPrice">Price</label>
        <input type="number" id="productPrice" required>
        
        <label for="releaseDate">Date of Release</label>
        <input type="date" id="releaseDate" required>

        <div class="modal-buttons">
          <button type="submit" id="submitBtn">Save</button>
          <button type="button" id="cancelBtn">Cancel</button>
        </div>
      </form>
    </div>
  </div>

  <!-- DELETE CONFIRMATION -->
  <div class="modal" id="deleteModal">
    <div class="modal-content">
      <h2>Confirm Deletion</h2>
      <p>Are you sure you want to delete this product?</p>
      <div class="modal-buttons">
        <button id="confirmDeleteBtn">Delete</button>
        <button id="cancelDeleteBtn">Cancel</button>
      </div>
    </div>
  </div>

  <script src="adminpage.js"></script>
</body>
</html>
