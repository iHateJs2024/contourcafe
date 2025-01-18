//? Redirect To The Same Page when Reload To Clear The URL
window.addEventListener("load", function () {
  // Check if the navigation type is reload
  if (performance.getEntriesByType("navigation")[0].type === "reload") {
    // Redirect to another page
    window.location.href = "admin-index.php"; // Replace with the target URL
  }
});
