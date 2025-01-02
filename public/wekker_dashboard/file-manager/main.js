document.addEventListener('DOMContentLoaded', async () => {
  let activeContent;
  const projectList = document.getElementById('projectList');
  let showFiles = document.getElementById('showFiles');
  const btnProject = document.getElementById('btnProject');

  const getList = await fetch(`/get-list-projects`, {
    method: 'GET',
    headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
    }
  });

  response = await getList.json();
  console.log(response);
  response.forEach(respon => {
    projectList.innerHTML += `
    <li class="list-group-item folder-item" data-path="/${respon.uuid}" data-name="${respon.name}">
        <i class="bi bi-folder-fill folder-icon"></i> ${respon.name}
    </li>
    `

    const fileGrid = `fileGrid_${respon.uuid}`;
    const fileList = `fileList_${respon.uuid}`;

    showFiles.innerHTML += `
    <div id="content_${respon.uuid}" class="all-content">
        <!-- File Grid -->
        <div class="row row-cols-2 row-cols-md-4 g-4 file-grid" id="${fileGrid}">
        
        </div>

        <!-- File List (Hidden by Default) -->
        <div class="list-group file-list" id="${fileList}" style="display: none;">
            
        </div>
    </div>
    `
    const fileGridnya = document.getElementById(fileGrid);
    const fileListnya = document.getElementById(fileList);
    
    const idContent = `content_${respon.uuid}`;

    const showContent = document.getElementById(idContent);
    console.log(showContent);
    if (showContent) showContent.style.display = 'none';

    if (respon.html){
      fileGridnya.innerHTML += `
      <div class="col">
        <div class="card p-3 d-flex align-items-center">
          <ion-icon class="fs-1 py-2 text-success" name="logo-html5"></ion-icon>
          <p class="mt-2 mb-0">index.html</p>
        </div>
      </div>
      `;
      fileListnya.innerHTML += `
      <a href="#" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
          <div class="d-flex align-items-center gap-2">
              <ion-icon class="fs-4 text-success" name="logo-html5"></ion-icon>
              <span>index.html</span>
          </div>
          <span>10 KB</span>
      </a>
      `;
    }
    if (respon.css){
      fileGridnya.innerHTML += `
      <div class="col">
        <div class="card p-3 d-flex align-items-center">
          <ion-icon class="fs-1 py-2 text-primary" name="logo-css3"></ion-icon>
          <p class="mt-2 mb-0">style.css</p>
        </div>
      </div>
      `;
      fileListnya.innerHTML += `
      <a href="#" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
          <div class="d-flex align-items-center gap-2">
              <ion-icon class="fs-4 text-primary" name="logo-html5"></ion-icon>
              <span>style.css</span>
          </div>
          <span>105 KB</span>
      </a>
      `;
    }
    if (respon.javascript){
      fileGridnya.innerHTML += `
      <div class="col">
        <div class="card p-3 d-flex align-items-center">
          <ion-icon class="fs-1 py-2 text-warning" name="logo-javascript"></ion-icon>
          <p class="mt-2 mb-0">script.js</p>
        </div>
      </div>
      `;
      fileListnya.innerHTML += `
      <a href="#" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
          <div class="d-flex align-items-center gap-2">
              <ion-icon class="fs-4 text-warning" name="logo-html5"></ion-icon>
              <span>script.js</span>
          </div>
          <span>13 KB</span>
      </a>
      `;
    }
  });

  projectList.addEventListener('click', (e) => {
    if (e.target.classList.contains('folder-item')) {
      const folderName = e.target.dataset.name;
      const pathName = e.target.dataset.path.split('/').pop();
      console.log(`Folder clicked: ${folderName}, Path name: ${pathName}`);
      updateBreadcrumb(folderName);
      loadFolderContent(pathName);
    }
  });

  function updateBreadcrumb(folderName) {
    let breadcrumb = document.getElementById('breadcrumb');
    if (breadcrumb) {
      console.log('MASUK',breadcrumb);
    }
    breadcrumb.innerHTML = `
      <li class="breadcrumb-item"><a href="#">root</a></li>
      <li class="breadcrumb-item active" aria-current="page">${folderName}</li>`;
  }

  function loadFolderContent(pathName) {
    const idContent = `content_${pathName}`;
    const showContent = document.getElementById(idContent);
    const allContent = document.querySelectorAll('.all-content');
    allContent.forEach(content => {
      content.style.display = 'none';
    });

    if (showContent) {
      console.log(`Element found: ${idContent}`);
      showContent.style.display = 'block';
      activeContent = idContent;
      console.log(window.getComputedStyle(showContent).display);

    } else {
      console.log(`Element NOT found: ${idContent}`);
      console.error(`Element not found for ID: ${idContent}`);
    }
  }

  const toggleViewButton = document.getElementById('toggleView');

  toggleViewButton.addEventListener('click', () => {
      const getActiveContent = document.getElementById(activeContent);
      const listShowing = getActiveContent.querySelector('.file-list');
      const gridShowing = getActiveContent.querySelector('.file-grid');
      let isgridShowing = !gridShowing.classList.contains('d-none');
      if (isgridShowing) {
          listShowing.style.display = "block";
          gridShowing.classList.add('d-none');
          toggleViewButton.innerHTML = `<ion-icon class="p-0 m-0" name="grid-outline" style="font-size: 2.2rem; border: none;"></ion-icon>`;
      } else {
          listShowing.style.display = "none";
          gridShowing.classList.remove('d-none');
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

  handleClickBehavior();
  window.addEventListener("resize", handleClickBehavior);
});