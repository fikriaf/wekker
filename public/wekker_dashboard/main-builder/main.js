let lastSessionDatas = { html: '', css: '', js: '' };
document.addEventListener('DOMContentLoaded', async function() {

  const createProject = document.getElementById('createProject');
  createProject.addEventListener('click', () => {
    localStorage.setItem('html', null);
    localStorage.setItem('css', null);
    localStorage.setItem('js', null);
  })

  const uuid = window.location.pathname.split('/').pop();
  const dropdownlistProject = document.getElementById('dropdownlistProject');
  const dropdownProject = document.getElementById('dropdownProject');
  const rootManager = document.getElementById('rootManager');

  dropdownlistProject.innerHTML = '';
  const getListPro = await fetch(`/get-list-projects`, {
    method: 'GET',
    headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
    }
  });

  const hasils = await getListPro.json();
  hasils.forEach(hasil => {
    if (hasil.uuid === uuid){
      dropdownProject.textContent = hasil.name;
      rootManager.textContent = hasil.name;
    }
    listProjectelemen = `
    <a href="/dashboard/main-builder/${hasil.uuid}" class="dropdown-item">
        <div class="d-flex w-100 align-items-center row">
          <div class="col-12 col-md-5 d-flex justify-content-start align-items-center gap-2">
              <ion-icon name="terminal"></ion-icon>${hasil.name}
          </div>
          <div class="col-12 col-md-6 d-flex justify-content-end text-secondary">
              Last Updated: ${hasil.updated_at}
          </div>
          <button class="col-12 col-md-1 btn btn-sm btn-danger delete-project" data-uuid="${hasil.uuid}">Delete</button>
        </div>
    </a>`

    dropdownlistProject.innerHTML += listProjectelemen;
  });

  // -----------------------------------------------------------------------------------------------------

  document.getElementById('dropdownlistProject').addEventListener('click', async (event) => {
    if (event.target.classList.contains('delete-project')) {
      const uuid = event.target.getAttribute('data-uuid');

      if (confirm('Are you sure you want to delete this project?')) {
        const response = await fetch(`/delete-project/${uuid}`, {
          method: 'DELETE',
          headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
          },
        });

        if (response.ok) {
          event.target.closest('.dropdown-item').remove();
        } else {
          alert('Failed to delete project.');
        }
      }
    }
  });

  // -----------------------------------------------------------------------------------------------------

  let shareLink = document.getElementById('shareLink')
  document.getElementById('btnShareLink').addEventListener('click', async () => {
    const urlfull = window.location.href;
    shareLink.value = urlfull;
  });

  const btnShare = document.getElementById('btnShare');
  btnShare.addEventListener('click', function () {
    navigator.clipboard.writeText(shareLink).then(() => {
      btnShare.textContent = '';
      btnShare.innerHTML = `<ion-icon name="checkmark-done"></ion-icon>`;
      setTimeout(() => {
        btnShare.innerHTML = '';
        btnShare.textContent = 'Copy';
      }, 3000);
    });
  });

  // -----------------------------------------------------------------------------------------------------
  spinner = document.getElementById('SpinnerBorder')
  spinner.style.display = 'block';
  document.getElementById('btnOpenFile').addEventListener('click', async () => {
    let listProject = document.getElementById('listProject');
    listProject.innerHTML = '';
    
    const getList = await fetch(`/get-list-projects`, {
      method: 'GET',
      headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
      }
    });

    const projects = await getList.json();
    spinner.style.display = 'none';
    projects.forEach(project => {
      const childList = `
        <a href="/dashboard/main-builder/${project.uuid}" class="list-group-item list-group-item-action">
          <div class="d-flex w-100 row">
            <div class="col d-flex justify-content-start align-items-center gap-2">
              <ion-icon name="terminal"></ion-icon>${project.name}
            </div>
            <div class="col d-flex justify-content-end text-secondary">
              ${project.updated_at}
            </div>
          </div>
        </a>
      `;
      
      // Append the new project link to the list
      listProject.innerHTML += childList;
    });

  });


  async function getSessionData() {
      try {
          let response = {
              html: localStorage.getItem('html'),
              css: localStorage.getItem('css'),
              js: localStorage.getItem('js')
          };

          return await response;
      } catch (error) {
          console.error('Error saat mendapatkan data session:', error);
          return null;
      }
  }

  async function checkAndRefreshIframe() {
      const sessionData = await getSessionData();
      // console.log("session data", sessionData);
      if (
          sessionData &&
          (sessionData.html !== lastSessionDatas.html ||
              sessionData.css !== lastSessionDatas.css ||
              sessionData.js !== lastSessionDatas.js)
      ) {
          refreshIframe(sessionData);
          lastSessionDatas = sessionData;
      }
  }

  function refreshIframe(sessionData) {
    const iframe = document.getElementById('previewFrame');
    // const bodyHTML = sessionData.html.match(/<body.*?>([\s\S]*?)<\/body>/i);
    const htmlContent = `
        <html>
            <head>
                <style>${sessionData.css}</style>
            </head>
            <body>
                ${sessionData.html}
                <script>${sessionData.js}</script>
            </body>
        </html>
    `;
    
    const blob = new Blob([htmlContent], { type: 'text/html' });
    const blobUrl = URL.createObjectURL(blob);
    iframe.src = blobUrl;
  }

  setInterval(checkAndRefreshIframe, 1000);


  // Menu Toggle
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




  // Ambil elemen indukan-preview dan iframe
  const preview = document.querySelector(".preview")
  const indukanPreview = document.querySelector(".indukan-preview");
  const iframe = indukanPreview.querySelector("iframe");
  const indikasi = document.querySelector(".footer-preview")

  let slider = document.getElementById('resolutionSlider');
  let tooltip = document.getElementById('tooltip');

  if (window.innerWidth < 768) {
    slider.value = '412';
    tooltip.textContent = slider.value + 'p';
    tooltip.style.left = 0;
  }
  slider.addEventListener('input', () => {
    const resolution = slider.value + 'p';
    tooltip.textContent = resolution;

    const percent = (slider.value - slider.min) / (slider.max - slider.min) * 100;
    tooltip.style.left = `calc(${percent}% + ${(1 - (2 * percent) / 100).toFixed(2)}rem)`;

    adjustIframeScale();
  });

  function adjustIframeScale() {
    const indukanWidth = indukanPreview.offsetWidth - 4;
    const indukanHeight = indukanPreview.offsetHeight - 80;
    const iframeOriginalWidth = parseInt(slider.value, 10);
    let iframeOriginalHeight;
    let scaleRatioWidth;
    let scaleRatioHeight;

    preview.style.display = "block";
    iframe.style.transformOrigin = "left top";

    if (iframeOriginalWidth === 1236) {
        indikasi.textContent = "On Preview - Desktop";
        iframeOriginalHeight = 658;
        scaleRatioWidth = indukanWidth / iframeOriginalWidth;
        scaleRatioHeight = indukanHeight / iframeOriginalHeight;
    } else if (iframeOriginalWidth === 824) {
      indikasi.textContent = "On Preview - Tablet";
        iframeOriginalHeight = 439;
        scaleRatioWidth = indukanWidth / iframeOriginalWidth;
        scaleRatioHeight = indukanHeight / iframeOriginalHeight;
    } else if (iframeOriginalWidth === 412) {
      indikasi.textContent = "On Preview - Mobile";
        iframeOriginalHeight = 844;
        preview.style.display = "flex";
        iframe.style.transformOrigin = "center top";
        scaleRatioHeight = indukanHeight / iframeOriginalHeight;
    }
    
    iframe.style.width = `${iframeOriginalWidth}px`;
    iframe.style.height = `${iframeOriginalHeight}px`;

    // Terapkan skala menggunakan transform
    if (iframeOriginalWidth === 412) {
      iframe.style.transform = `scale(${scaleRatioHeight})`;
    } else {
      iframe.style.transform = `scale(${scaleRatioWidth}, ${scaleRatioHeight})`;
    }
    // iframe.style.transformOrigin = 'top left';
  }

  const resizeObserver = new ResizeObserver(() => {
    adjustIframeScale();
  });
  resizeObserver.observe(indukanPreview);



  const checkboxes = document.querySelectorAll('.dropdown-checkbox');
  const output = document.getElementById('selectedItems');
  output.textContent = "None";
  checkboxes.forEach(checkbox => {
      checkbox.addEventListener('change', () => {
          const selected = Array.from(checkboxes)
              .filter(cb => cb.checked)
              .map(cb => cb.value)
              .join(';');

          output.textContent = selected || "None";
      });
  });

  document.querySelector('.label-hover').addEventListener('change', function() {
    if (this.checked) {
      document.querySelector('.footbar').style.bottom = "0";
      document.querySelector('.fa-angle-double-up').style.transform = "rotate(180deg)"
    } else {
      document.querySelector('.footbar').style.bottom = "-27.9rem";
      document.querySelector('.fa-angle-double-up').style.transform = "rotate(360deg)"
    }
  });



  const buttonHTML = document.querySelector(".btn-html");
  const buttonCSS = document.querySelector(".btn-css");
  const buttonJS = document.querySelector(".btn-js");

  function removeActive() {
    document.querySelectorAll(".btn-code-nav").forEach(btn => btn.classList.remove("active"));
    document.querySelectorAll(".codenya").forEach(codenya => codenya.classList.remove("active"));
  }

  buttonHTML.addEventListener("click", function () {
    removeActive();
    buttonHTML.classList.add("active");
    document.querySelector(".code-html").classList.add("active")
  });

  buttonCSS.addEventListener("click", function () {
    removeActive();
    buttonCSS.classList.add("active");
    document.querySelector(".code-css").classList.add("active")
  });

  buttonJS.addEventListener("click", function () {
    removeActive();
    buttonJS.classList.add("active");
    document.querySelector(".code-js").classList.add("active")
  });

  const editableCodes = document.querySelectorAll("#codeHTML, #codeCSS, #codeJS");

  let cachedText = '';

  function debounce(func, delay) {
      let timeout;
      return function (...args) {
          clearTimeout(timeout);
          timeout = setTimeout(() => func.apply(this, args), delay);
      };
  }

  function saveCursorPosition(element) {
      const selection = window.getSelection();
      const range = selection.getRangeAt(0);
      const preCaretRange = range.cloneRange();
      preCaretRange.selectNodeContents(element);
      preCaretRange.setEnd(range.endContainer, range.endOffset);
      return preCaretRange.toString().length;
  }

  function restoreCursorPosition(element, cursorPosition) {
      const selection = window.getSelection();
      const range = document.createRange();
      let charIndex = 0;
      const nodeStack = [element];
      let foundStart = false;

      while (nodeStack.length > 0) {
          const node = nodeStack.pop();
          if (node.nodeType === Node.TEXT_NODE) {
              const nextCharIndex = charIndex + node.textContent.length;
              if (!foundStart && cursorPosition >= charIndex && cursorPosition <= nextCharIndex) {
                  range.setStart(node, cursorPosition - charIndex);
                  range.collapse(true);
                  foundStart = true;
              }
              charIndex = nextCharIndex;
          } else {
              let i = node.childNodes.length;
              while (i--) nodeStack.push(node.childNodes[i]);
          }
      }
      selection.removeAllRanges();
      selection.addRange(range);
  }

  function highlightCode() {
      editableCodes.forEach(editableCode => {
          const currentText = editableCode.textContent;
          if (currentText === cachedText) return;
          cachedText = currentText;

          const cursorPosition = saveCursorPosition(editableCode);
          Prism.highlightElement(editableCode);
          restoreCursorPosition(editableCode, cursorPosition);
      });
  }

  const debouncedHighlight = debounce(highlightCode, 1000);

  editableCodes.forEach(editableCode => {
      editableCode.addEventListener("keydown", (e) => {
          if (e.key === "Tab") {
              e.preventDefault();
              const selection = window.getSelection();
              const range = selection.getRangeAt(0);

              const tabNode = document.createTextNode("    ");
              range.insertNode(tabNode);

              range.setStartAfter(tabNode);
              range.setEndAfter(tabNode);
              selection.removeAllRanges();
              selection.addRange(range);
              debouncedHighlight();
          }

          if (e.key === "Enter") {
              e.preventDefault();
              const selection = window.getSelection();
              const range = selection.getRangeAt(0);
              const textBeforeCursor = range.startContainer.textContent.slice(0, range.startOffset);
              const matchIndent = textBeforeCursor.match(/^\s+/);
              const indent = matchIndent ? matchIndent[0] : "";
              const newLineNode = document.createTextNode("\n" + indent);
              range.insertNode(newLineNode);

              range.setStartAfter(newLineNode);
              range.setEndAfter(newLineNode);
              selection.removeAllRanges();
              selection.addRange(range);
              debouncedHighlight();
          }

          if (e.key === "Backspace") {
              const selection = window.getSelection();
              const range = selection.getRangeAt(0);

              const node = range.startContainer;
              const offset = range.startOffset;
              const textContent = node.textContent;

              const lineStart = textContent.lastIndexOf("\n", offset - 1) + 1;
              const lineText = textContent.slice(lineStart, offset);

              if (lineText.endsWith("    ")) {
                  const updatedText =
                      textContent.slice(0, offset - 4) + textContent.slice(offset);

                  node.textContent = updatedText;

                  const newCursorPosition = offset - 4;
                  range.setStart(node, newCursorPosition);
                  range.setEnd(node, newCursorPosition);
                  selection.removeAllRanges();
                  selection.addRange(range);

                  e.preventDefault();
              }
          }
      });

      editableCode.addEventListener("input", () => {
          debouncedHighlight();
      });

      Prism.highlightElement(editableCode);
  });


  document.getElementById('toggleSections').addEventListener('click', function() {
      const sections = document.getElementById('sections');
      const chevron = document.getElementById("chevronDown");
      sections.style.display = (sections.style.display === 'none' || sections.style.display === '') ? 'block' : 'none';
      chevron.style.transform = (sections.style.display === 'none' || sections.style.display === '') ? "rotate(360deg)" : "rotate(180deg)";
  });

  function setupClipboardButton(buttonId, valueId) {
    const button = document.getElementById(buttonId);
    const value = document.getElementById(valueId).textContent;
  
    button.addEventListener('click', function () {
        navigator.clipboard.writeText(value).then(() => {
            button.innerHTML = `<ion-icon name="checkmark-done"></ion-icon>`;
            setTimeout(() => {
                button.innerHTML = `<ion-icon name="copy-outline"></ion-icon>`;
            }, 3000);
        });
    });
  }
  
  setupClipboardButton('btnApiKey', 'valueApiKey');
  setupClipboardButton('btnApiKeyMobile', 'valueApiKeyMobile');

});

