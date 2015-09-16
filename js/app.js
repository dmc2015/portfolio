// Foundation JavaScript
// Documentation can be found at: http://foundation.zurb.com/docs
$(document).foundation();

$(document).ready(function() {
  if (window.location.hash === '#blog-page'){
    blogOpen();
    console.log('blog opened via direct link or bookmark');
  }
  // else{
  //   blogClose();
  //   console.log('default close of blog')
  // };

});


var blogOpen = function(){
  console.log("Blog start");
  //assigns blog and other docs to variables

  //var mainBody = document.querySelector('.main');
  var blogPage = document.querySelector('#blog-page');
  var main = document.querySelector('.main');
  var googleAd = document.querySelector('ins.adsbygoogle');

  //hides main page and shows blog page

  //shows blogs
  blogPage.classList.add('blog-show');
  blogPage.classList.remove('blog-hide');

  googleAd.classList.add('show-ad');
  googleAd.classList.remove('hide-ad');

  //hides remaining content
  main.style.display = "none";

  console.log('Blog Page :' + blogPage);
  //console.log('Main Page :' + main);
  //console.log('Main Page Inner HTML :' + mainInner);
};



var blogClose = function() {
  var blogPage = document.querySelector('#blog-page');
  var main = document.querySelector('.main');
  var googleAd = document.querySelector('ins.adsbygoogle');


  blogPage.classList.add('blog-hide');
  blogPage.classList.remove('blog-show');

  googleAd.classList.add('hide-ad');
  googleAd.classList.remove('show-ad');

  main.style.display = "initial";

  console.log('blog exit attempted');
  console.log(main);
};


//
// // "https://www.codementor.io/session/9704206780#blog-page"
//
// $(function() {
//   if (window.location.hash == 'blog-page') {
//     showBlogPage();
//   }
// });
//
// function showBlogPage() {
//   // ....
// }
