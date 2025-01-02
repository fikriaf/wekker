
let toggle = document.querySelector(".toggle");
let closeToggle = document.querySelector(".close-toggle");
let navigation = document.querySelector(".navigation");
let main = document.querySelector(".main");

const nameDB = document.getElementById("nameDB");
const sumTable = document.getElementById("sumTable");
const namaUser = document.getElementById("namaUser");
const storageUsed = document.getElementById("storageUsed");
const endTime = document.getElementById("endTime");
const statusDB = document.getElementById("statusDB");

const panelCreateDB = document.getElementById("panelCreateDB");
const nameDBElement = document.getElementById("nameDB");
const spinner = document.getElementById('SpinnerBorder');
const successCreate = document.getElementById('successCreate');

const infoEmptyDB = document.getElementById('infoEmptyDB');

function hideandopen() {
  successCreate.style.display = 'none';
  spinner.style.display = 'none';

  if (nameDBElement) {
    let checkDB = nameDBElement.textContent.trim();
    if (checkDB === 'my_database') {
      panelCreateDB.style.display = 'flex';
    } else {
      panelCreateDB.style.display = 'none';
    }
  } else {
    console.error('Element #nameDB not found');
  }
}

// Menu Toggle
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

// ----------------------------------------------------------------

async function createDatabase() {
  infoEmptyDB.style.display = 'none';
  spinner.style.display = 'block';
  
  const createDB = await fetch(`/create-database`, {
    method: 'POST',
    headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
    },
    body: JSON.stringify({}),
  }); 

  const response = await createDB.json();

  if (response) {
    setTimeout(()=>{
      getInfoUpdate();
      spinner.style.display = 'none';
      successCreate.style.display = 'block';
      hideandopen();
    }, 1000);
  }
}

async function getInfoUpdate() {
  const getInfoDB = await fetch(`http://127.0.0.1:8000/get-info-database`, {
    method: 'GET',
    headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
    },
  }); 

  const response = await getInfoDB.json();
  
  if (response.success != false) {
    console.log(response);
    nameDB.textContent = response.infoData.dbName;
    hideandopen();
    endTime.textContent = response.infoData.end_time;
    sumTable.textContent = response.tableCount;
    namaUser.textContent = response.namaUser.name;
    storageUsed.textContent = response.storageInfo.total_storage+' byte';
    statusDB.textContent = 'online';
  } else {
    hideandopen();
  }
}

// getInfoUpdate();
handleClickBehavior();
window.addEventListener("resize", handleClickBehavior);
document.getElementById('btnCreateDB').addEventListener('click', createDatabase);




