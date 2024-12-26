let cache = [];
const pushMessage = document.getElementById('pushMessage');

function scroll(idName) {
  const scrollContainer = document.getElementById(idName);
  scrollContainer.scrollTop = scrollContainer.scrollHeight;
}

document.getElementById('sendAssistant').addEventListener('click', function () {
  if (!cache.length) {
    pushMessage.innerHTML = '';
  }
  
  const inputPrompt = document.getElementById('messageAssistant').value;

  pushMessage.innerHTML += `
  <div class="message-user d-flex justify-content-end my-2 text-end p-2 rounded bg-light w-auto ms-auto" style="max-width: 50%;">
      ${inputPrompt}
  </div>
  `;
  pushMessage.innerHTML += `
  <div class="message-ai d-flex gap-1 align-items-center p-2 rounded my-2 bg-light w-75" style="text-align: justify;">
      <div class="spinner-grow spinner-grow-sm text-dark"></div> <div class="text-secondary" style="font-size: 0.8rem;">Thinking...</div>
  </div>
  `;
  scroll("pushMessage");
  Promise.all([
    fetchStream(inputPrompt),
  ])
  .then (()=> {
      console.log("Pengiriman berhasil");
  })
  .catch(error => {
      console.error('Ada masalah dengan pengiriman:', error);
  });
});

function fetchStream(inputPrompt) {
  return fetch('/api/wekker_assistant', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/x-www-form-urlencoded',
      'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
    },
    body: `teksnya=${encodeURIComponent(inputPrompt)}`,
  })
  .then(response => handleStreamResponse(response))
  .catch(error => console.error('Error in stream 2:', error));
}

function handleStreamResponse(response){
  const pushMessageAI = document.querySelectorAll('.message-ai');
  const lastElement = pushMessageAI[pushMessageAI.length - 1];
  const reader = response.body.getReader();
  const decoder = new TextDecoder('utf-8');
  let first = true;
  async function read() {
    while (true) {
      const { done, value } = await reader.read();
      if (done) break;

      const chunk = decoder.decode(value, { stream: true }).replace(/\*/g, "").replace(/###/g, "");

      if (first){
        lastElement.innerHTML = '';
      };
      for (let char of chunk) {
          lastElement.innerHTML += char === '\n' ? '<br>' : char;
          cache.push(char);
          await new Promise(resolve => setTimeout(resolve, 1));
          first = false;
          scroll("pushMessage");
      }
    }
  }
  read();
};

document.addEventListener('DOMContentLoaded', async () => {
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


