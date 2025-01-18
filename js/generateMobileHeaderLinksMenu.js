function generateMobileHeaderLinksMenu() {
  document.getElementById("mobile-ul-header").innerHTML =
    mobilHeaderLinksDataMenu
      .map((headerLink) => {
        let { id, link, svg } = headerLink;
        return `
        <li>
          <a class="${id}-link" href="${link}">
            ${svg}
          </a>
        </li>
      `;
      })
      .join("");
}

//! Run this function when reload page
generateMobileHeaderLinksMenu();
