function generateHeaderLinksMenu() {
  document.getElementById("ul-header").innerHTML = headerLinksDataMenu
    .map((headerLink) => {
      let { id, linkName, link } = headerLink;
      return `
        <li>
          <a class="${id}-link" href="${link}">${linkName}</a>
        </li>
      `;
    })
    .join("");
}

//! Run this function when reload page
generateHeaderLinksMenu();
