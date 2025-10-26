// Enhanced JavaScript for Kagay an View Apartment Management System

// Global variables
let sidebarCollapsed = false;
let currentModal = null;

// Initialize the application
document.addEventListener("DOMContentLoaded", function () {
  initializeApp();
});

// Load Lucide icons
function loadLucideIcons() {
  if (typeof lucide === "undefined") {
    const script = document.createElement("script");
    script.src = "https://cdnjs.cloudflare.com/ajax/libs/lucide/0.263.1/umd/lucide.min.js";
    script.onload = function () {
      lucide.createIcons();
    };
    document.head.appendChild(script);
  } else {
    lucide.createIcons();
  }
}

function initializeApp() {
  // Load and initialize Lucide icons
  loadLucideIcons();

  // Initialize tooltips
  initializeTooltips();

  // Initialize animations
  initializeAnimations();

  // Initialize search functionality
  initializeSearch();

  // Initialize modals
  initializeModals();

  // Initialize notifications
  initializeNotifications();
}

// Sidebar functionality
function toggleSidebar() {
  const sidebar = document.getElementById("sidebar");
  const mainContent = document.querySelector(".main-content");

  sidebarCollapsed = !sidebarCollapsed;

  if (sidebarCollapsed) {
    sidebar.classList.add("collapsed");
    mainContent.style.marginLeft = "80px";
  } else {
    sidebar.classList.remove("collapsed");
    mainContent.style.marginLeft = "280px";
  }

  // Re-initialize icons after sidebar toggle
  setTimeout(() => {
    if (typeof lucide !== "undefined") {
      lucide.createIcons();
    }
  }, 300);
}

// Search functionality
function initializeSearch() {
  const searchInputs = document.querySelectorAll(".search-box input");

  searchInputs.forEach((input) => {
    input.addEventListener("input", function () {
      const searchTerm = this.value.toLowerCase();
      const table = this.closest(".content-area").querySelector("table");

      if (table) {
        filterTable(table, searchTerm);
      }
    });
  });
}

function filterTable(table, searchTerm) {
  const rows = table.querySelectorAll("tbody tr");

  rows.forEach((row) => {
    const text = row.textContent.toLowerCase();
    if (text.includes(searchTerm)) {
      row.style.display = "";
      row.classList.add("search-match");
    } else {
      row.style.display = "none";
      row.classList.remove("search-match");
    }
  });
}

// Modal functionality
function initializeModals() {
  // Close modal when clicking outside
  document.addEventListener("click", function (e) {
    if (e.target.classList.contains("modal")) {
      closeModal();
    }
  });

  // Close modal with Escape key
  document.addEventListener("keydown", function (e) {
    if (e.key === "Escape" && currentModal) {
      closeModal();
    }
  });
}

function openModal(modalId) {
  const modal = document.getElementById(modalId);
  if (modal) {
    modal.style.display = "flex";
    currentModal = modal;
    document.body.style.overflow = "hidden";

    // Focus first input
    const firstInput = modal.querySelector("input, select, textarea");
    if (firstInput) {
      setTimeout(() => firstInput.focus(), 100);
    }
  }
}

function closeModal() {
  if (currentModal) {
    currentModal.style.display = "none";
    currentModal = null;
    document.body.style.overflow = "";
  }
}

// Staff management functions
function showAddStaffForm() {
  openModal("addStaffModal");
}

function viewStaff(id) {
  // Show staff details in a modal or redirect to details page
  showNotification("Viewing staff details for ID: " + id, "info");
}

function editStaff(id) {
  // Show edit form with pre-filled data
  showNotification("Editing staff member ID: " + id, "info");
}

function deleteStaff(id) {
  if (confirm("Are you sure you want to delete this staff member?")) {
    // Show loading state
    const deleteBtn = event.target.closest("button");
    const originalContent = deleteBtn.innerHTML;
    deleteBtn.innerHTML = '<div class="loading"></div>';
    deleteBtn.disabled = true;

    fetch("delete_staff.php", {
      method: "POST",
      headers: {
        "Content-Type": "application/x-www-form-urlencoded",
      },
      body: "id=" + id,
    })
      .then((response) => response.json())
      .then((data) => {
        if (data.success) {
          showNotification("Staff member deleted successfully!", "success");
          // Remove the row from table
          const row = deleteBtn.closest("tr");
          row.style.transition = "all 0.3s ease";
          row.style.opacity = "0";
          row.style.transform = "translateX(-100%)";
          setTimeout(() => row.remove(), 300);
        } else {
          showNotification("Error deleting staff member: " + data.message, "error");
        }
      })
      .catch((error) => {
        showNotification("Error deleting staff member", "error");
      })
      .finally(() => {
        deleteBtn.innerHTML = originalContent;
        deleteBtn.disabled = false;
      });
  }
}

// Tenant management functions
function viewTenant(id) {
  window.location.href = "tenant_details.php?id=" + id;
}

function editTenant(id) {
  window.location.href = "edit_tenant.php?id=" + id;
}

function deleteTenant(id) {
  if (confirm("Are you sure you want to delete this tenant?")) {
    const deleteBtn = event.target.closest("button");
    const originalContent = deleteBtn.innerHTML;
    deleteBtn.innerHTML = '<div class="loading"></div>';
    deleteBtn.disabled = true;

    fetch("delete_tenant.php", {
      method: "POST",
      headers: {
        "Content-Type": "application/x-www-form-urlencoded",
      },
      body: "id=" + id,
    })
      .then((response) => response.json())
      .then((data) => {
        if (data.success) {
          showNotification("Tenant deleted successfully!", "success");
          const row = deleteBtn.closest("tr");
          row.style.transition = "all 0.3s ease";
          row.style.opacity = "0";
          row.style.transform = "translateX(-100%)";
          setTimeout(() => row.remove(), 300);
        } else {
          showNotification("Error deleting tenant: " + data.message, "error");
        }
      })
      .catch((error) => {
        showNotification("Error deleting tenant", "error");
      })
      .finally(() => {
        deleteBtn.innerHTML = originalContent;
        deleteBtn.disabled = false;
      });
  }
}

// Filter functions
function filterTenants(status) {
  const rows = document.querySelectorAll("#tenantsTableBody tr");
  const filterButtons = document.querySelectorAll(".filter-btn");

  // Update active filter button
  filterButtons.forEach((btn) => btn.classList.remove("active"));
  event.target.classList.add("active");

  rows.forEach((row) => {
    if (status === "all" || row.dataset.status === status) {
      row.style.display = "";
      row.style.animation = "fadeIn 0.3s ease";
    } else {
      row.style.display = "none";
    }
  });
}

function filterUnits(status) {
  const unitCards = document.querySelectorAll(".unit-card");
  const filterButtons = document.querySelectorAll(".filter-btn");

  // Update active filter button
  filterButtons.forEach((btn) => btn.classList.remove("active"));
  event.target.classList.add("active");

  unitCards.forEach((card) => {
    if (status === "all" || card.dataset.status === status) {
      card.style.display = "";
      card.style.animation = "fadeIn 0.3s ease";
    } else {
      card.style.display = "none";
    }
  });
}

// Renter functions
function openPaymentModal() {
  openModal("paymentModal");
}

function openMaintenanceModal() {
  openModal("maintenanceModal");
}

function openMessageModal() {
  openModal("messageModal");
}

// Form handling
function handleFormSubmit(formId, successMessage) {
  const form = document.getElementById(formId);
  if (form) {
    form.addEventListener("submit", function (e) {
      e.preventDefault();

      const submitBtn = form.querySelector('button[type="submit"]');
      const originalContent = submitBtn.innerHTML;

      // Show loading state
      submitBtn.innerHTML = '<div class="loading"></div>';
      submitBtn.disabled = true;

      // Simulate form submission (replace with actual AJAX call)
      setTimeout(() => {
        showNotification(successMessage, "success");
        closeModal();
        form.reset();

        // Reset button
        submitBtn.innerHTML = originalContent;
        submitBtn.disabled = false;

        // Refresh page or update table
        setTimeout(() => location.reload(), 1000);
      }, 1500);
    });
  }
}

// Notification system
function initializeNotifications() {
  // Create notification container if it doesn't exist
  if (!document.querySelector(".notification-container")) {
    const container = document.createElement("div");
    container.className = "notification-container";
    container.style.cssText = `
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 10000;
            display: flex;
            flex-direction: column;
            gap: 10px;
        `;
    document.body.appendChild(container);
  }
}

function showNotification(message, type = "info") {
  const container = document.querySelector(".notification-container");
  const notification = document.createElement("div");

  const icons = {
    success: "check-circle",
    error: "x-circle",
    warning: "alert-triangle",
    info: "info",
  };

  const colors = {
    success: "#10b981",
    error: "#ef4444",
    warning: "#f59e0b",
    info: "#3b82f6",
  };

  notification.className = "notification";
  notification.style.cssText = `
        background: white;
        border: 1px solid ${colors[type]};
        border-left: 4px solid ${colors[type]};
        border-radius: 8px;
        padding: 16px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        display: flex;
        align-items: center;
        gap: 12px;
        min-width: 300px;
        animation: slideInRight 0.3s ease;
    `;

  notification.innerHTML = `
        <i data-lucide="${icons[type]}" style="color: ${colors[type]}; flex-shrink: 0;"></i>
        <span style="flex: 1;">${message}</span>
        <button onclick="this.parentElement.remove()" style="background: none; border: none; cursor: pointer; padding: 4px;">
            <i data-lucide="x" width="16" height="16" style="color: #64748b;"></i>
        </button>
    `;

  container.appendChild(notification);

  // Re-initialize icons
  if (typeof lucide !== "undefined") {
    lucide.createIcons();
  }

  // Auto remove after 5 seconds
  setTimeout(() => {
    if (notification.parentElement) {
      notification.style.animation = "slideOutRight 0.3s ease";
      setTimeout(() => notification.remove(), 300);
    }
  }, 5000);
}

// Animation functions
function initializeAnimations() {
  // Add CSS animations
  const style = document.createElement("style");
  style.textContent = `
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        @keyframes slideInRight {
            from { transform: translateX(100%); opacity: 0; }
            to { transform: translateX(0); opacity: 1; }
        }
        
        @keyframes slideOutRight {
            from { transform: translateX(0); opacity: 1; }
            to { transform: translateX(100%); opacity: 0; }
        }
        
        .search-match {
            background-color: rgba(79, 70, 229, 0.1) !important;
        }
        
        .loading {
            width: 16px;
            height: 16px;
            border: 2px solid #e2e8f0;
            border-top: 2px solid #4f46e5;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }
        
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
    `;
  document.head.appendChild(style);
}

// Tooltip functionality
function initializeTooltips() {
  const tooltipElements = document.querySelectorAll("[data-tooltip]");

  tooltipElements.forEach((element) => {
    element.addEventListener("mouseenter", showTooltip);
    element.addEventListener("mouseleave", hideTooltip);
  });
}

function showTooltip(e) {
  const text = e.target.dataset.tooltip;
  const tooltip = document.createElement("div");
  tooltip.className = "tooltip";
  tooltip.textContent = text;
  tooltip.style.cssText = `
        position: absolute;
        background: #1e293b;
        color: white;
        padding: 8px 12px;
        border-radius: 6px;
        font-size: 12px;
        z-index: 1000;
        pointer-events: none;
        opacity: 0;
        transition: opacity 0.2s;
    `;

  document.body.appendChild(tooltip);

  const rect = e.target.getBoundingClientRect();
  tooltip.style.left = rect.left + rect.width / 2 - tooltip.offsetWidth / 2 + "px";
  tooltip.style.top = rect.top - tooltip.offsetHeight - 8 + "px";

  setTimeout(() => (tooltip.style.opacity = "1"), 10);

  e.target._tooltip = tooltip;
}

function hideTooltip(e) {
  if (e.target._tooltip) {
    e.target._tooltip.remove();
    e.target._tooltip = null;
  }
}

// Utility functions
function formatCurrency(amount) {
  return new Intl.NumberFormat("en-PH", {
    style: "currency",
    currency: "PHP",
  }).format(amount);
}

function formatDate(date) {
  return new Intl.DateTimeFormat("en-US", {
    year: "numeric",
    month: "short",
    day: "numeric",
  }).format(new Date(date));
}

function debounce(func, wait) {
  let timeout;
  return function executedFunction(...args) {
    const later = () => {
      clearTimeout(timeout);
      func(...args);
    };
    clearTimeout(timeout);
    timeout = setTimeout(later, wait);
  };
}

// Export functions for global use
window.toggleSidebar = toggleSidebar;
window.showAddStaffForm = showAddStaffForm;
window.viewStaff = viewStaff;
window.editStaff = editStaff;
window.deleteStaff = deleteStaff;
window.viewTenant = viewTenant;
window.editTenant = editTenant;
window.deleteTenant = deleteTenant;
window.filterTenants = filterTenants;
window.filterUnits = filterUnits;
window.openPaymentModal = openPaymentModal;
window.openMaintenanceModal = openMaintenanceModal;
window.openMessageModal = openMessageModal;
window.openModal = openModal;
window.closeModal = closeModal;
window.showNotification = showNotification;
