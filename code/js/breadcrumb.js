$(document).ready(function () {
    function generateBreadcrumb(url) {

        // Split url into array of url components
        const pathParts = url.pathname.split('/').filter(part => part !== "");

        // Remove .php
        for (let i = 0; i < pathParts.length; i++) {
            if (pathParts[i].endsWith(".php")) {
                pathParts[i] = pathParts[i].slice(0, -4);  // Remove the last 4 characters (".php")
            }
        }

        let breadcrumbHtml = '<li class="breadcrumb-item"><a href="../php/main.php">Home</a></li>';

        $.each(pathParts.slice(pathParts.length, pathParts.length - 1), function (index, part) {
            const linkUrl = `/${pathParts.slice(0, index + 1).join('/')}`;

            // Add the previous pages
            breadcrumbHtml += `
          <li class="breadcrumb-item">
            <a href="${linkUrl}">${part}</a>
          </li>
        `;
        });

        // Add the current page 
        breadcrumbHtml += `
        <li class="breadcrumb-item active" aria-current="page">
          ${pathParts[pathParts.length - 1]}
        </li>
      `;
        $('#breadcrumb').html(breadcrumbHtml);
    }

    generateBreadcrumb(window.location);
});
