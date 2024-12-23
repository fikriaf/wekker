const projectList = document.getElementById('projectList');
const fileGrid = document.getElementById('fileGrid');
const breadcrumb = document.getElementById('breadcrumb');

projectList.addEventListener('click', (e) => {
  if (e.target.classList.contains('folder-item')) {
    const folderName = e.target.dataset.path.split('/').pop();
    updateBreadcrumb(folderName);
    loadFolderContent(folderName);
  }
});

function updateBreadcrumb(folderName) {
  breadcrumb.innerHTML = `
    <li class="breadcrumb-item"><a href="#">Root</a></li>
    <li class="breadcrumb-item active" aria-current="page">${folderName}</li>
  `;
}

function loadFolderContent(folderName) {
  fileGrid.innerHTML = `
    <div class="col">
      <div class="card p-3">
        <i class="bi bi-folder-fill folder-icon fs-1"></i>
        <p class="mt-2 mb-0">src</p>
      </div>
    </div>
    <div class="col">
      <div class="card p-3">
        <i class="bi bi-file-earmark fs-1"></i>
        <p class="mt-2 mb-0">index.html</p>
      </div>
    </div>
  `;
}

const toggleViewButton = document.getElementById('toggleView');
const fileGridnya = document.getElementById('fileGrid');
const fileList = document.getElementById('fileList');

toggleViewButton.addEventListener('click', () => {
    const isGridView = !fileGrid.classList.contains('d-none');
    if (isGridView) {
        // Tampilkan List View
        fileGrid.classList.add('d-none');
        fileList.classList.remove('d-none');
        toggleViewButton.innerHTML = `<ion-icon class="p-0 m-0" name="grid-outline" style="font-size: 2.2rem; border: none;"></ion-icon>`;
    } else {
        // Tampilkan Grid View
        fileList.classList.add('d-none');
        fileGrid.classList.remove('d-none');
        toggleViewButton.innerHTML = `<ion-icon class="p-0 m-0" name="list-outline" style="font-size: 2.2rem; border: none;"></ion-icon>`;
    }
});


// Menu Toggle
let toggle = document.querySelector(".toggle");
let closeToggle = document.querySelector(".close-toggle");
let navigation = document.querySelector(".navigation");
let main = document.querySelector(".main");

function handleClickBehavior() {
  if (window.innerWidth < 768) {
    // Untuk resolusi kecil
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
    // Untuk resolusi besar
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

// Jalankan awal
handleClickBehavior();

// Tambahkan event listener saat ukuran layar berubah
window.addEventListener("resize", handleClickBehavior);

