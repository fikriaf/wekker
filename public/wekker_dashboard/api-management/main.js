
let toggle = document.querySelector(".toggle");
let closeToggle = document.querySelector(".close-toggle");
let navigation = document.querySelector(".navigation");
let main = document.querySelector(".main");

function handleClickBehavior() {
  if (window.innerWidth < 768) {
    closeToggle.onclick = function () {
      closeToggle.style.display = "none";
      navigation.classList.toggle("active");
      closeToggle.classList.remove("show");
      setTimeout (() => {
        toggle.style.display = "flex";
      }, 400);
    }
    toggle.onclick = function () {
      toggle.style.display = "none";
      navigation.classList.toggle("active");
      main.classList.remove("buka");
      setTimeout (() => {
        closeToggle.style.display = "flex";
      }, 330);
    }

  } else {
    closeToggle.onclick = function () {
      toggle.style.display = "flex";
      closeToggle.classList.add("active");
      closeToggle.style.display = "none";
      navigation.classList.toggle("active");
      navigation.classList.add("active");
      main.classList.add("active");
    }
    toggle.onclick = function () {
      toggle.style.display = "none";
      closeToggle.classList.remove("active");
      closeToggle.style.display = "flex";
      navigation.classList.toggle("active");
      navigation.classList.remove("active");
      main.classList.toggle("active");
    }
  }
}

handleClickBehavior();

window.addEventListener("resize", handleClickBehavior);

function copyApiKey() {
  const apiKey = document.getElementById('valueApiKey').textContent;
  navigator.clipboard.writeText(apiKey).then(() => {
    alert("API Key copied to clipboard!");
  });
}