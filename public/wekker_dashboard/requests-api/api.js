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

  const session_hash = Math.random().toString(36).substring(2, 13);
  const urlJoin = "https://qwen-qwen2-5-coder-artifacts.hf.space/gradio_api/queue/join?__theme=system";
  const urlData = "https://qwen-qwen2-5-coder-artifacts.hf.space/gradio_api/queue/data?session_hash=" + session_hash;

  const uaText = await fetch('/ua.txt').then(res => res.text());
  const uaList = uaText
    .split('\n')
    .map(s => s.trim())
    .filter(s => s);

  const randomUA = uaList[Math.floor(Math.random() * uaList.length)];

  const headers = {
    "Content-Type": "application/json",
    "User-Agent": randomUA,
    "Origin": "https://qwen-qwen2-5-coder-artifacts.hf.space",
    "Referer": "https://qwen-qwen2-5-coder-artifacts.hf.space/?__theme=system"
  };

  const upPrompt = {
    data: [
`You are a professional web developer who creates modern, professional, and production-ready UI components.

REQUIREMENTS:
- Output is only HTML (start with normal full), CSS, and JS. And must be SEPARATED.
- DO NOT include explanations, comments, or extra text.
- All designs must be balanced, intentional, and visually polished.
- You can add external styling/script library to make it look good.
- You MUST style every element. No default, raw, or unstyled HTML is allowed. Everything must look designed, clean, and consistent.
- HTML must be semantic and accessible. Always include imports if external styles or scripts are used.
- CSS must handle complex and complete visual styling.
- JavaScript must be clean, modular, and also handle interaction behavior.

Maintain perfect consistency in spacing, typography, and interactivity.`
    ],
    event_data: null,
    fn_index: 2,
    trigger_id: 25,
    session_hash: session_hash
  };
  
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

    const response = await fetch('/api/wekker_requests_generate', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/x-www-form-urlencoded',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
      },
      body: `prompt=${encodeURIComponent(inputPrompt)}&api_key=${encodeURIComponent(value.textContent)}&materials=${encodeURIComponent(selectedItems.textContent)}&ua=${encodeURIComponent(randomUA)}&hash=${encodeURIComponent(session_hash)}`,
    });

    // if (!response.ok || !response.body) {
    //   console.error("Server response error");
    //   return;
    // }

    const reader = response.body.getReader();
    const decoder = new TextDecoder("utf-8");

    let fullText = '';
    let parsedJSON = null;

    const codeHTML = document.getElementById("codeHTML");
    const codeCSS = document.getElementById("codeCSS");
    const codeJS = document.getElementById("codeJS");

    codeHTML.textContent = '';
    codeCSS.textContent = '';
    codeJS.textContent = '';

    let tickBuffer = '';
    let currentLang = '';
    let isCapturing = false;
    let bufferPerLang = '';
    let codeBuffers = { html: '', css: '', js: '' };
    let stopParsing = false;
    
    while (!stopParsing) {
      const { value, done } = await reader.read();
      if (done) break;

      const chunk = decoder.decode(value, { stream: true });

      for (let i = 0; i < chunk.length; i++) {
        const char = chunk[i];

        // Tambah ke tickBuffer (max 3 char)
        tickBuffer = (tickBuffer + char).slice(-3);

        // Awal blok kode (``` deteksi 3 char)
        if (!isCapturing && tickBuffer === '```') {
          isCapturing = true;
          currentLang = '';
          bufferPerLang = '';
          tickBuffer = '';
          continue;
        }

        // Tangkap nama bahasa setelah ```
        if (isCapturing && !currentLang) {
          document.getElementById("loadingGenerate").style.display = 'none';
          if (char === '\n') {
            currentLang = bufferPerLang.trim().toLowerCase();
            bufferPerLang = '';

            if (['html', 'css', 'js', 'javascript'].includes(currentLang)) {
              const langKey = currentLang === 'javascript' ? 'js' : currentLang;
              
              document.querySelectorAll(".btn-code-nav").forEach(btn => btn.classList.remove("active"));
              document.querySelectorAll(".codenya").forEach(codenya => codenya.classList.remove("active"));
              document.querySelector(".btn-"+ langKey.toLocaleLowerCase())?.classList.add('active');
              document.querySelector(".code-"+ langKey.toLocaleLowerCase())?.classList.add('active');
            }

            continue;
          }
          bufferPerLang += char;
          continue;
        }

        // Deteksi akhir blok kode
        if (isCapturing && tickBuffer === '```') {
          isCapturing = false;
          tickBuffer = '';

          if (['html', 'css', 'js', 'javascript'].includes(currentLang)) {
            
            const langKey = currentLang === 'javascript' ? 'js' : currentLang;
            
            bufferPerLang = bufferPerLang.slice(0, -3);
            
            codeBuffers[langKey] += bufferPerLang;

            const target = document.getElementById("code" + langKey.toUpperCase());

            if (target) {
              target.textContent = bufferPerLang;
              Prism.highlightElement(target);
              saveSyntaxToLocal(langKey, bufferPerLang);
              target.scrollTop = target.scrollHeight;
            }

            if (langKey === 'js') {
              stopParsing = true;
              break;
            }

          }

          bufferPerLang = '';
          currentLang = '';
          continue;
        }


        // Jika sedang menangkap isi blok
        if (isCapturing) {
          if (tickBuffer === '```') {
            continue;
          }
          bufferPerLang += char;
          
          if (['html', 'css', 'js', 'javascript'].includes(currentLang)) {
            const langKey = currentLang === 'javascript' ? 'js' : currentLang;
            const target = document.getElementById("code" + langKey.toUpperCase());

            if (target) {
              target.textContent += char;
              target.scrollTop = target.scrollHeight;
            }
          }
        }
      }


      fullText += chunk;

      // if (extracted.html) {
      //   // Real-time tampil di HTML (sementara)
      //   document.querySelectorAll(".btn-code-nav").forEach(btn => btn.classList.remove("active"));
      //   document.querySelectorAll(".codenya").forEach(codenya => codenya.classList.remove("active"));
      //   document.querySelector(".btn-html").classList.add('active');
      //   document.querySelector(".code-html").classList.add('active');
      //   codeHTML.textContent = extracted.html;
      //   codeHTML.scrollTop = codeHTML.scrollHeight;
      //   // Prism.highlightElement(codeHTML);
      // }
      // if (extracted.css) {
      //   document.querySelectorAll(".btn-code-nav").forEach(btn => btn.classList.remove("active"));
      //   document.querySelectorAll(".codenya").forEach(codenya => codenya.classList.remove("active"));
      //   document.querySelector(".btn-css").classList.add('active');
      //   document.querySelector(".code-css").classList.add('active');
      //   codeCSS.textContent = extracted.css;
      //   codeHTML.scrollTop = codeHTML.scrollHeight;
      //   // Prism.highlightElement(codeCSS);
      // }
      // if (extracted.js) {
      //   document.querySelectorAll(".btn-code-nav").forEach(btn => btn.classList.remove("active"));
      //   document.querySelectorAll(".codenya").forEach(codenya => codenya.classList.remove("active"));
      //   document.querySelector(".btn-js").classList.add('active');
      //   document.querySelector(".code-js").classList.add('active');
      //   codeJS.textContent = extracted.js;
      //   codeHTML.scrollTop = codeHTML.scrollHeight;
      //   // Prism.highlightElement(codeJS);
      // }

      

      // Cek apakah sudah muncul hasil JSON final
      const start = fullText.indexOf('[[[PARSED_START]]]');
      const end = fullText.indexOf('[[[PARSED_END]]]');

      if (start !== -1 && end !== -1 && end > start) {
        const jsonRaw = fullText.substring(start + 18, end);
        try {
          parsedJSON = JSON.parse(jsonRaw);
        } catch (e) {
          console.error("Gagal parse JSON akhir", e);
        }
        break;
      }
    }

    // Gantikan isi dengan hasil akhir
    // if (parsedJSON) {
    //   if (parsedJSON.html?.code) {
    //     document.querySelectorAll(".btn-code-nav").forEach(btn => btn.classList.remove("active"));
    //     document.querySelectorAll(".codenya").forEach(codenya => codenya.classList.remove("active"));
    //     document.querySelector(".btn-html").classList.add('active');
    //     document.querySelector(".code-html").classList.add('active');
    //     codeHTML.textContent = parsedJSON.html.code;
    //     // await typeTextEffect(parsedJSON.html.code, codeHTML, 1);
    //     Prism.highlightElement(codeHTML);
    //     saveSyntaxToLocal('html', parsedJSON.html.code);
    //   }
    //   if (parsedJSON.css?.code) {
    //     document.querySelectorAll(".btn-code-nav").forEach(btn => btn.classList.remove("active"));
    //     document.querySelectorAll(".codenya").forEach(codenya => codenya.classList.remove("active"));
    //     document.querySelector(".btn-css").classList.add('active');
    //     document.querySelector(".code-css").classList.add('active');
    //     codeCSS.textContent = parsedJSON.css.code;
    //     // await typeTextEffect(parsedJSON.css.code, codeCSS, 1);
    //     Prism.highlightElement(codeCSS);
    //     saveSyntaxToLocal('css', parsedJSON.css.code);
    //   }
    //   if (parsedJSON.javascript?.code) {
    //     document.querySelectorAll(".btn-code-nav").forEach(btn => btn.classList.remove("active"));
    //     document.querySelectorAll(".codenya").forEach(codenya => codenya.classList.remove("active"));
    //     document.querySelector(".btn-js").classList.add('active');
    //     document.querySelector(".code-js").classList.add('active');
    //     codeJS.textContent = parsedJSON.javascript.code;
    //     // await typeTextEffect(parsedJSON.javascript.code, codeJS, 1);
    //     Prism.highlightElement(codeJS);
    //     saveSyntaxToLocal('js', parsedJSON.javascript.code);
    //   }
    // }

    
  });
  
  // submitPrompt.addEventListener('click', async function (e) {
  //   e.preventDefault();

  //   document.getElementById("loadingGenerate").style.display = 'flex';
    
  //   const inputPrompt = valuePrompt.value;

  //   try {
  //     const response = await fetch('/api/wekker_requests_generate', {
  //       method: 'POST',
  //       headers: {
  //         'Content-Type': 'application/x-www-form-urlencoded',
  //         'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
  //       },
  //       body: `prompt=${encodeURIComponent(inputPrompt)}&api_key=${encodeURIComponent(value.textContent)}&materials=${encodeURIComponent(selectedItems.textContent)}`,
  //     });

  //     if (!response.ok) {
  //       console.log('Server response not OK');
  //     }

  //     const data = await response.json();
      
  //     if (data) {
  //         try {
  //           if (data.html.code) {
  //             document.querySelectorAll(".btn-code-nav").forEach(btn => btn.classList.remove("active"));
  //             document.querySelectorAll(".codenya").forEach(codenya => codenya.classList.remove("active"));
  //             document.querySelector(".btn-html").classList.add('active');
  //             document.querySelector(".code-html").classList.add('active');
  //             const codeHTML = document.getElementById('codeHTML');
  //             await typeTextEffect(data.html.code??null, codeHTML, 1);
  //             Prism.highlightElement(codeHTML);
  //             saveSyntaxToLocal('html', data.html.code??null);
  //           }
  //         } catch (error) {
  //             console.error("No Code HTML");
  //             await typeTextEffect('', document.getElementById('codeHTML'), 1);
  //         };
  //         try {
  //             if (data.css.code) {
  //                 document.querySelectorAll(".btn-code-nav").forEach(btn => btn.classList.remove("active"));
  //                 document.querySelectorAll(".codenya").forEach(codenya => codenya.classList.remove("active"));
  //                 document.querySelector(".btn-css").classList.add('active');
  //                 document.querySelector(".code-css").classList.add('active');
  //                 const codeCSS = document.getElementById('codeCSS');
  //                 await typeTextEffect(data.css.code??null, codeCSS, 1);
  //                 Prism.highlightElement(codeCSS);
  //                 saveSyntaxToLocal('css', data.css.code??null);
  //             }
  //         } catch (error) {
  //             console.error("No Code CSS");
  //             await typeTextEffect('', document.getElementById('codeCSS'), 1);
  //         };
  //         try {
  //             if (data.javascript.code) {
  //                 document.querySelectorAll(".btn-code-nav").forEach(btn => btn.classList.remove("active"));
  //                 document.querySelectorAll(".codenya").forEach(codenya => codenya.classList.remove("active"));
  //                 document.querySelector(".btn-js").classList.add('active');
  //                 document.querySelector(".code-js").classList.add('active');
  //                 const codeJS = document.getElementById('codeJS');
  //                 await typeTextEffect(data.javascript.code??null, codeJS, 1);
  //                 Prism.highlightElement(codeJS);
  //                 saveSyntaxToLocal('js', data.css.javascript??null);
  //             }
  //         } catch (error) {
  //             console.log("No Code JS");
  //             await typeTextEffect('', document.getElementById('codeJS'), 1);
  //         };
  //     } else {
  //       console.log("Respons JSON tidak valid atau kunci 'html' tidak ditemukan.");
  //     };
  //   } catch (error) {
  //     console.error("Error fetching data:", error);
  //     console.log('Sorry, nothing response code from server. Please input prompt correctly !!!');
  //   }
  //   document.getElementById("loadingGenerate").style.display = 'none';
  //   if (promptbegin) {
  //       console.log('Cookie ditemukan! Nilai cookie: ' + promptbegin);
  //       valuePrompt.value = "";
  //       valuePrompt.textContent = "";
  //       deleteCookie('promptbegin');
  //   }
  // });

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
      await typeTextOptimized(responseDB.html, document.getElementById('codeHTML'));
      Prism.highlightElement(codeHTML);
      document.querySelectorAll(".btn-code-nav").forEach(btn => btn.classList.remove("active"));
      document.querySelectorAll(".codenya").forEach(codenya => codenya.classList.remove("active"));
      document.querySelector(".btn-css").classList.add('active');
      document.querySelector(".code-css").classList.add('active');
      await typeTextOptimized(responseDB.css, document.getElementById('codeCSS'));
      Prism.highlightElement(codeCSS);
      document.querySelectorAll(".btn-code-nav").forEach(btn => btn.classList.remove("active"));
      document.querySelectorAll(".codenya").forEach(codenya => codenya.classList.remove("active"));
      document.querySelector(".btn-js").classList.add('active');
      document.querySelector(".code-js").classList.add('active');
      await typeTextOptimized(responseDB.javascript, document.getElementById('codeJS'));
      Prism.highlightElement(codeJS);
    } catch (error) {
      console.error("Error during typeTextEffect execution:", error);
    }
  };
  

  function typeTextOptimized(text, targetElement, batchSize = 20, delay = 1) {
    return new Promise(resolve => {
      let index = 0;
      targetElement.textContent = "";

      const interval = setInterval(() => {
        if (index < text.length) {
          targetElement.textContent += text.slice(index, index + batchSize);
          index += batchSize;
          targetElement.scrollTop = targetElement.scrollHeight;
        } else {
          clearInterval(interval);
          resolve();
        }
      }, delay);
    });
  }

  function typeTextEffect(text, targetElement, delay) {
    return new Promise(resolve => {
        let index = 0;
        targetElement.textContent = "";

        const interval = setInterval(() => {
          if (index < text.length) {
              targetElement.textContent += text[index];
              index++;

              // Auto scroll ke bawah setiap karakter
              targetElement.scrollTop = targetElement.scrollHeight;
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

  // Kirim prompt
  await fetch(urlJoin, {
    method: 'POST',
    headers,
    body: JSON.stringify(upPrompt)
  });

  // Tunggu sedikit sebelum GET (opsional delay 300ms)
  await new Promise(res => setTimeout(res, 300));

  // Ambil hasil queue
  await fetch(urlData, {
    method: 'GET',
    headers
  });

  setInterval(saveCode, 10000);
});
