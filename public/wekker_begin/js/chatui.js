const sendBtn = document.getElementById("send-btn");
const userInput = document.getElementById("user-input");
const chatContainer = document.getElementById("chat-container");

sendBtn.addEventListener("click", () => {
  const userMessage = userInput.value.trim();
  if (userMessage) {
    addChatMessage("User", userMessage);
    userInput.value = ""; // Clear input field

    // Simulate bot response
    setTimeout(() => {
      addChatMessage("ChatGPT", "Ini adalah respons bot untuk: " + userMessage);
    }, 1000);
  }
});

function addChatMessage(sender, message) {
  const messageElement = document.createElement("p");
  messageElement.innerHTML = `<strong>${sender}:</strong> ${message}`;
  messageElement.className = sender === "User" ? "text-primary" : "text-light";
  chatContainer.appendChild(messageElement);

  // Scroll to the bottom
  chatContainer.scrollTop = chatContainer.scrollHeight;
}
