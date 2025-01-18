function generateMobileHeaderLinksAdmin() {
  document.getElementById("js-ul__mobile").innerHTML =
    mobileHeaderLinksDataAdmin
      .map((headerLink) => {
        let { id, linkName, link, img } = headerLink;
        return `
        <li>
          <a class="${id}-link" href="${link}">
            ${linkName}
            <img class="header-link-image" src="${img}" alt="">
            </a>
            <span class="${id}-span-text"></span>
        </li>
      `;
      })
      .join("");
}
generateMobileHeaderLinksAdmin();
