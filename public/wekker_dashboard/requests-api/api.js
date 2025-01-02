let lastSessionData = { html: '', css: '', js: '' };


document.addEventListener("DOMContentLoaded", async () => {
  const submitPrompt = document.getElementById('submitPrompt');  
  const uuid = window.location.pathname.split('/').pop();
  const elemenEditCodeHTML = document.getElementById('codeHTML');
  const elemenEditCodeCSS = document.getElementById('codeCSS');
  const elemenEditCodeJS = document.getElementById('codeJS');
  const value = document.getElementById("valueApiKey");
  const valuePrompt = document.getElementById('inputPrompt');
  const selectedItems = document.getElementById('selectedItems');

  function getCookie(name) {
    let nameEQ = name + "=";
    let ca = document.cookie.split(';');
    for (let i = 0; i < ca.length; i++) {
        let c = ca[i].trim();
        if (c.indexOf(nameEQ) === 0) {
            return decodeURIComponent(c.substring(nameEQ.length, c.length));
        }
    }
    return null;
  }
  function deleteCookie(name) {
      document.cookie = name + '=; Max-Age=-1; path=/';
  }

  let promptbegin = getCookie('promptbegin');

  if (promptbegin) {
      console.log('Cookie ditemukan! Nilai cookie: ' + promptbegin);
      valuePrompt.value = promptbegin;
      valuePrompt.textContent = promptbegin;
  }

  submitPrompt.addEventListener('click', async function (e) {
    e.preventDefault();

    document.getElementById("loadingGenerate").style.display = 'flex';
    
    const inputPrompt = valuePrompt.value;

    console.log(selectedItems);
    try {
      const response = await fetch('/api/wekker_requests_generate', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/x-www-form-urlencoded',
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: `prompt=${encodeURIComponent(inputPrompt)}&api_key=${encodeURIComponent(value.textContent)}&materials=${encodeURIComponent(selectedItems.textContent)}`,
      });

      if (!response.ok) {
        console.log('Server response not OK');
      }

      const data = await response.json();
      
      if (data) {
          try {
            if (data.html.code) {
              document.querySelectorAll(".btn-code-nav").forEach(btn => btn.classList.remove("active"));
              document.querySelectorAll(".codenya").forEach(codenya => codenya.classList.remove("active"));
              document.querySelector(".btn-html").classList.add('active');
              document.querySelector(".code-html").classList.add('active');
              const codeHTML = document.getElementById('codeHTML');
              await typeTextEffect(data.html.code??null, codeHTML, 1);
              Prism.highlightElement(codeHTML);
              saveSyntaxToLocal('html', data.html.code??null);
            }
          } catch (error) {
              console.error("No Code CSS");
              saveSyntaxToLocal('html', data.html.code??null);
          };
          try {
              if (data.css.code) {
                  document.querySelectorAll(".btn-code-nav").forEach(btn => btn.classList.remove("active"));
                  document.querySelectorAll(".codenya").forEach(codenya => codenya.classList.remove("active"));
                  document.querySelector(".btn-css").classList.add('active');
                  document.querySelector(".code-css").classList.add('active');
                  const codeCSS = document.getElementById('codeCSS');
                  await typeTextEffect(data.css.code??null, codeCSS, 1);
                  Prism.highlightElement(codeCSS);
                  saveSyntaxToLocal('css', data.css.code??null);
              }
          } catch (error) {
              console.error("No Code CSS");
              saveSyntaxToLocal('html', data.css.code??null);
          };
          try {
              if (data.javascript.code) {
                  document.querySelectorAll(".btn-code-nav").forEach(btn => btn.classList.remove("active"));
                  document.querySelectorAll(".codenya").forEach(codenya => codenya.classList.remove("active"));
                  document.querySelector(".btn-js").classList.add('active');
                  document.querySelector(".code-js").classList.add('active');
                  const codeJS = document.getElementById('codeJS');
                  await typeTextEffect(data.javascript.code??null, codeJS, 1);
                  Prism.highlightElement(codeJS);
                  saveSyntaxToLocal('js', data.javascript.code??null);
              }
          } catch (error) {
              console.log("No Code JS");
              saveSyntaxToLocal('html', data.javascript.code??null);
          };
      } else {
        console.log("Respons JSON tidak valid atau kunci 'html' tidak ditemukan.");
      };
    } catch (error) {
      console.error("Error fetching data:", error);
      console.log('Sorry, nothing response code from server. Please input prompt correctly !!!');
    }
    document.getElementById("loadingGenerate").style.display = 'none';
    if (promptbegin) {
        console.log('Cookie ditemukan! Nilai cookie: ' + promptbegin);
        valuePrompt.value = "";
        valuePrompt.textContent = "";
        deleteCookie('promptbegin');
    }
  });

  const getFromDB = await fetch(`/dashboard/main-builder/${uuid}/data`, {
      method: 'GET',
      headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
      }
  });
  if (!getFromDB.ok) {
      throw new Error(`HTTP error! Status: ${getFromDB.status}`);
  }
  const responseDB = await getFromDB.json();

  if (responseDB.html || responseDB.css || responseDB.js) {
    try {
      document.querySelectorAll(".btn-code-nav").forEach(btn => btn.classList.remove("active"));
      document.querySelectorAll(".codenya").forEach(codenya => codenya.classList.remove("active"));
      document.querySelector(".btn-html").classList.add('active');
      document.querySelector(".code-html").classList.add('active');
      await typeTextEffect(responseDB.html, document.getElementById('codeHTML'), 1);
      Prism.highlightElement(codeHTML);
      document.querySelectorAll(".btn-code-nav").forEach(btn => btn.classList.remove("active"));
      document.querySelectorAll(".codenya").forEach(codenya => codenya.classList.remove("active"));
      document.querySelector(".btn-css").classList.add('active');
      document.querySelector(".code-css").classList.add('active');
      await typeTextEffect(responseDB.css, document.getElementById('codeCSS'), 1);
      Prism.highlightElement(codeCSS);
      document.querySelectorAll(".btn-code-nav").forEach(btn => btn.classList.remove("active"));
      document.querySelectorAll(".codenya").forEach(codenya => codenya.classList.remove("active"));
      document.querySelector(".btn-js").classList.add('active');
      document.querySelector(".code-js").classList.add('active');
      await typeTextEffect(responseDB.javascript, document.getElementById('codeJS'), 5);
      Prism.highlightElement(codeJS);
    } catch (error) {
      console.error("Error during typeTextEffect execution:", error);
    }
  };
      
  function typeTextEffect(text, targetElement, delay) {
    return new Promise(resolve => {
        let index = 0;
        targetElement.textContent = "";

        const interval = setInterval(() => {
          if (index < text.length) {
              targetElement.textContent += text[index];
              index++;
          } else {
              clearInterval(interval);
              resolve();
          }
        }, delay);
    });
  }

  async function saveSyntaxToLocal(name, content) {
    try {
        localStorage.setItem(name, content);
        // console.log("savetoLocal");
      } catch (error) {
        console.error('Error saat menyimpan syntax ke local storage:', error);
      }
  }

  async function saveCode() {
    let editCodeHTML = elemenEditCodeHTML.textContent;
    let editCodeCSS = elemenEditCodeCSS.textContent;
    let editCodeJS = elemenEditCodeJS.textContent;

    saveSyntaxToLocal('html', editCodeHTML);
    saveSyntaxToLocal('css', editCodeCSS);
    saveSyntaxToLocal('js', editCodeJS);
  
    let sessionData = {
      html: editCodeHTML,
      css: editCodeCSS,
      js: editCodeJS
    };
    
    if (
        sessionData &&
        (sessionData.html !== lastSessionData.html ||
            sessionData.css !== lastSessionData.css ||
            sessionData.js !== lastSessionData.js) && (sessionData.html || sessionData.css || sessionData.js)
    ) {
        lastSessionData = sessionData;

        const requestData = {
          html: editCodeHTML,
          css: editCodeCSS,
          javascript: editCodeJS,
        };

        const uuid = window.location.pathname.split('/').pop();
  
        if (!requestData.html && !requestData.css && !requestData.javascript) {
          console.log('No changes detected, skipping save.');
          return;
        }
        console.log("Memperbarui Database...")
        try {
          const response = await fetch(`/dashboard/main-builder/${uuid}/save-projects`, {
              method: 'POST',
              headers: {
                  'Content-Type': 'application/json',
                  'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
              },
              body: JSON.stringify(requestData)
          });
  
          if (response.ok) {
              console.log('Project Saved Database');
          } else {
              console.error('Error:', response.statusText);
          }
        } catch (error) {
            console.error('Request failed', error);
        }
    }
  }

  setInterval(saveCode, 500);
});
