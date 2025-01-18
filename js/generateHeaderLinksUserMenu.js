function generateHeaderLinksUserMenu() {
  document.getElementById("ul-header").innerHTML = headerLinksDataUserMenu
    .map((headerLink) => {
      let { id, linkName, link, img } = headerLink;
      return `
        <li>
          <a class="${id}-link" href="${link}">
            ${linkName}
            <img class="header-link-image" src="${img}" alt="">
          </a>
        </li>
      `;
    })
    .join("");
}

//! Run this function when reload page
generateHeaderLinksUserMenu();
