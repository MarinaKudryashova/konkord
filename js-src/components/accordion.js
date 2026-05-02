document.addEventListener('DOMContentLoaded', () => {
	const accordions = document.querySelectorAll('.accordion__item');

  const close = function() {
    accordions.forEach(el => {
      if (el.classList.contains('is-open')) {
        el.classList.remove('is-open');
        el.querySelector('.accordion__control').setAttribute('aria-expanded', false);
        el.querySelector('.accordion__content').setAttribute('aria-hidden', true);
        el.querySelector('.accordion__content').style.maxHeight = null;
      }
    });
  }

  const open = function(current) {
    current.classList.add('is-open');
    const control = current.querySelector('.accordion__control');
    const content = current.querySelector('.accordion__content');
    control.setAttribute('aria-expanded', true);
    content.setAttribute('aria-hidden', false);
    content.style.maxHeight = content.scrollHeight + 'px';
  }

	accordions.forEach(el => {
		el.addEventListener('click', (e) => {
      const current = e.currentTarget;

      if(current.classList.contains('is-open')) {
        close();
      } else {
        close();
        open(current);
      }
    });
	});
});
