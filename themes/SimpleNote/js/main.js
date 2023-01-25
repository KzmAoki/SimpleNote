window.addEventListener('DOMContentLoaded', function(e){
  [].forEach.call(document.querySelectorAll('[data-href]'),function(x){
    x.addEventListener('click',function(e){
      location.href=x.dataset["href"];
    });
  });
});
