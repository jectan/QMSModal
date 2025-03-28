let sidebar1= document.querySelector(".sidebar1");
let closeBtn = document.querySelector("#btn");
let searchBtn = document.querySelector(".bx-search");

/* closeBtn.addEventListener("click", ()=>{
  sidebar1.classList.toggle("open");
  menuBtnChange();//calling the function(optional)
});

searchBtn.addEventListener("click", ()=>{ // Sidebar open when you click on the search iocn
  sidebar1.classList.toggle("open");
  menuBtnChange(); //calling the function(optional)
}); */

// following are the code to change sidebar button(optional)
function menuBtnChange() {
 if(sidebar1.classList.contains("open")){
   closeBtn.classList.replace("bx-menu", "bx-menu-alt-right");//replacing the icons class
 }else {
   closeBtn.classList.replace("bx-menu-alt-right","bx-menu");//replacing the icons class
 }
}

document.addEventListener("DOMContentLoaded", function () {
  let toggleButton = document.getElementById("manageLibrariesToggle");
  let libraryMenu = document.getElementById("libraryMenu");

  if (toggleButton && libraryMenu) {
      toggleButton.addEventListener("click", function (event) {
          event.preventDefault(); // Prevents the page from reloading if clicked

          libraryMenu.classList.toggle("show");
          toggleButton.classList.toggle("active"); 
      });
  }
});
