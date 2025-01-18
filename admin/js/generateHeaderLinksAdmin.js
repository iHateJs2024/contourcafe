function generateHeaderLinksAdmin() {
  document.getElementById("ul-header").innerHTML = headerLinksDataAdmin
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
generateHeaderLinksAdmin();
