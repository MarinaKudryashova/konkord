const searchOpenBtn = document.querySelector('.searchbar__btn');
const searchForm = document.querySelector('.search');
const searchClose = document.querySelector('.search__close-btn');

if (searchOpenBtn && searchForm && searchClose) {
   searchOpenBtn.addEventListener('click', () => {
      searchForm.classList.add("is-open")
   })
   searchClose.addEventListener('click', () => {
      searchForm.classList.remove("is-open")
   })
}