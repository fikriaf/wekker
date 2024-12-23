let lastSessionData = { html: '', css: '', js: '' };


document.addEventListener("DOMContentLoaded", async () => {
  const uuid = window.location.pathname.split('/').pop();

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
  }


  document.getElementById('submitPrompt').addEventListener('click', async function (e) {
    e.preventDefault();

    document.getElementById("loadingGenerate").style.display = 'flex';
    
    const inputPrompt = document.getElementById('inputPrompt').value;

    try {
      const response = await fetch('http://localhost:8000/api/wekker_req.php', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `prompt=${encodeURIComponent(inputPrompt)}&api_key=123`,
      });

      if (!response.ok) {
        console.log('Server response not OK');
      }

      const data = await response.json();
      console.log("Full response data:", data);

      if (data) {
          try {
            if (data.html.code) {
              document.querySelectorAll(".btn-code-nav").forEach(btn => btn.classList.remove("active"));
              document.querySelectorAll(".codenya").forEach(codenya => codenya.classList.remove("active"));
              document.querySelector(".btn-html").classList.add('active');
              document.querySelector(".code-html").classList.add('active');
              const codeHTML = document.getElementById('codeHTML');
              await typeTextEffect(data.html.code, codeHTML, 1);
              Prism.highlightElement(codeHTML);
              saveSyntaxToLocal('html', data.html.code);
            }
          } catch (error) {
              console.error("No Code CSS");
          }
          try {
              if (data.css.code) {
                  document.querySelectorAll(".btn-code-nav").forEach(btn => btn.classList.remove("active"));
                  document.querySelectorAll(".codenya").forEach(codenya => codenya.classList.remove("active"));
                  document.querySelector(".btn-css").classList.add('active');
                  document.querySelector(".code-css").classList.add('active');
                  const codeCSS = document.getElementById('codeCSS');
                  await typeTextEffect(data.css.code, codeCSS, 1);
                  Prism.highlightElement(codeCSS);
                  saveSyntaxToLocal('css', data.css.code);
              }
          } catch (error) {
              console.error("No Code CSS");
          }
          try {
              if (data.javascript.code) {
                  document.querySelectorAll(".btn-code-nav").forEach(btn => btn.classList.remove("active"));
                  document.querySelectorAll(".codenya").forEach(codenya => codenya.classList.remove("active"));
                  document.querySelector(".btn-js").classList.add('active');
                  document.querySelector(".code-js").classList.add('active');
                  const codeJS = document.getElementById('codeJS');
                  await typeTextEffect(data.javascript.code, codeJS, 1);
                  Prism.highlightElement(codeJS);
                  saveSyntaxToLocal('js', data.javascript.code);
              }
          } catch (error) {
              console.log("No Code JS");
          }
      } else {
        console.log("Respons JSON tidak valid atau kunci 'html' tidak ditemukan.");
      }
    } catch (error) {
      console.error("Error fetching data:", error);
      console.log('Terjadi kesalahan saat memuat data.');
    }
    document.getElementById("loadingGenerate").style.display = 'none';
  });


      
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
    const editCodeHTML = document.getElementById('codeHTML').textContent;
    const editCodeCSS = document.getElementById('codeCSS').textContent;
    const editCodeJS = document.getElementById('codeJS').textContent;

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
        console.log("____________",requestData);
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
