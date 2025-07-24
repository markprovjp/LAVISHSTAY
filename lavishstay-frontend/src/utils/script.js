const chatBody = document.querySelector(".chat-body");
const messageInput = document.querySelector(".message-input");
const sendMessageButton = document.querySelector("#send-message");
const fileInput = document.querySelector("#file-input");
const fileUploadWrapper = document.querySelector(".file-upload-wrapper");
const fileCancelButton = document.querySelector("#file-cancel");
const chatbotToggler = document.querySelector("#chatbot-toggler");
const closeChatbot = document.querySelector("#close-chatbot");


// Api setup
const API_KEY = "AIzaSyDknGaSdnOzlm3bhuJqvSl79eOb68SA3Bw"; // LINK LẤY API KEY: https://aistudio.google.com/apikey
const API_URL = `https://generativelanguage.googleapis.com/v1beta/models/gemini-2.0-flash:generateContent?key=${API_KEY}`;

const userData = {
    message: null,
    file: {
        data: null,
        mime_type: null
    }
};

// const chatHistory = [
//     {
//         role: "model",
//         parts: [{ text: `Đinh Duy Vinh (2005), chàng sinh viên đến từ Quảng Ngãi, hiện đang theo học tại Đại học Duy Tân, Đà Nẵng, là một người trẻ đam mê công nghệ và lập trình. Từ thuở nhỏ, Vinh đã có niềm đam mê mãnh liệt với các thiết bị điện tử và luôn muốn tìm hiểu mọi thứ xung quanh. Chính sự tò mò này đã đưa anh đến với thế giới lập trình ngay từ những năm cấp 3, đặc biệt là trong thời gian giãn cách xã hội do dịch COVID-19. Với thời gian rảnh rỗi, Vinh bắt đầu tự học lập trình web, và rồi từ những dự án nhỏ ban đầu, anh đã phát triển được những sản phẩm hữu ích cho cộng đồng.
// Những dự án mà Vinh thực hiện không chỉ đơn giản là những sản phẩm công nghệ mà còn là minh chứng cho sự sáng tạo và khả năng giải quyết vấn đề của anh. Anh đã tự tay xây dựng một loạt các dự án đa dạng như hệ thống quản lý sinh viên, web game giải trí, website chống lừa đảo, trang web tải ảnh từ Imgur, công cụ tạo mã QR code, dự báo thời tiết trực tuyến, và cả extension Chrome giúp đánh giá nhanh giảng viên của trường Đại học Duy Tân. Không dừng lại ở đó, Vinh còn đắm chìm vào việc khai thác API từ các mạng xã hội như Instagram, Facebook, TikTok và Zalo để lấy thông tin người dùng. Anh cũng đã thử sức với việc tạo module iOS để crack ứng dụng Locket, phát triển API tải video từ TikTok, tạo web chuyển đổi 2FA, và không thể không nhắc đến các bot Telegram mà Vinh viết để tự động hóa các tác vụ một cách hiệu quả.
// Vinh không chỉ giỏi trong việc phát triển các dự án công nghệ mà còn luôn mong muốn chia sẻ những gì mình học được với cộng đồng. Kênh YouTube của anh (YouTube: @duyvinh09) là nơi anh chia sẻ những mẹo, thủ thuật và tiện ích cực kỳ hữu ích mà anh đã tự tìm ra, giúp đỡ mọi người trong hành trình học hỏi công nghệ. Ngoài YouTube, Vinh cũng kết nối và chia sẻ kiến thức qua các nền tảng khác như GitHub (GitHub: duyvinh09) và Facebook (Facebook: duyvinh09), nơi anh luôn sẵn sàng giao lưu, học hỏi từ cộng đồng và giúp đỡ những người có chung niềm đam mê. Đặc biệt, Vinh còn sở hữu một nhóm chat trên Telegram, nơi anh và các bạn có thể trao đổi kiến thức, cùng nhau phát triển và học hỏi từ những người đi trước.
// Với một portfolio đầy ấn tượng tại duyvinh09.github.io và dinhduyvinh.eu.org, Vinh không ngừng khẳng định khả năng của mình qua mỗi dự án. Anh là một chàng trai luôn nỗ lực học hỏi, phát triển và sẵn sàng chia sẻ với cộng đồng những gì anh biết. Với tinh thần sáng tạo không ngừng nghỉ và sự nhiệt huyết trong từng dự án, Đinh Duy Vinh chắc chắn sẽ còn đạt được nhiều thành công và tiếp tục là nguồn cảm hứng cho thế hệ trẻ đam mê công nghệ.` }],
//     },
// ];

const chatHistory = [
    {
        role: "model",
        parts: [{ text: "Bạn là một trợ lý AI hữu ích. Luôn luôn trả lời bằng tiếng Việt." }],
    },
];

const initialInputHeight = messageInput.scrollHeight;

// Create message element with dynamic classes and return it
const createMessageElement = (content, ...classes) => {
    const div = document.createElement("div");
    div.classList.add("message", ...classes);
    div.innerHTML = content;
    return div;
};

// Generate bot response using API
const generateBotResponse = async (incomingMessageDiv) => {
    const messageElement = incomingMessageDiv.querySelector(".message-text");
    
    // chatHistory.push({
    //     role: "user",
    //     parts: [{ text: `Using the details provided above, please address this query: ${userData.message}` }, ...(userData.file.data ? [{ inline_data: userData.file }] : [])],
    // });

    chatHistory.push({
        role: "user",
        parts: [{ text: userData.message }, ...(userData.file.data ? [{ inline_data: userData.file }] : [])],
    });
    
    // API request options
    const requestOptions = {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({
            contents: chatHistory
        })
    }

    try {
        // Fetch bot response from API
        const response = await fetch(API_URL, requestOptions);
        const data = await response.json();
        if (!response.ok) throw new Error(data.error.message);

        // Extract and display bot's response text
        const apiResponseText = data.candidates[0].content.parts[0].text.replace(/\*\*(.*?)\*\*/g, "$1").trim();
        messageElement.innerText = apiResponseText;
        chatHistory.push({
            role: "model",
            parts: [{ text: apiResponseText }]
        });
    } catch (error) {
        messageElement.innerText = error.message;
        messageElement.style.color = "#ff0000";
    } finally {
        userData.file = {};
        incomingMessageDiv.classList.remove("thinking");
        chatBody.scrollTo({ behavior: "smooth", top: chatBody.scrollHeight });
    }
};

// Handle outgoing user message
const handleOutgoingMessage = (e) => {
    e.preventDefault();
    userData.message = messageInput.value.trim();
    messageInput.value = "";
    fileUploadWrapper.classList.remove("file-uploaded");
    messageInput.dispatchEvent(new Event("input"));

    // Create and display user message
    const messageContent = `<div class="message-text"></div>
                            ${userData.file.data ? `<img src="data:${userData.file.mime_type};base64,${userData.file.data}" class="attachment" />` : ""}`;

    const outgoingMessageDiv = createMessageElement(messageContent, "user-message");
    outgoingMessageDiv.querySelector(".message-text").innerText = userData.message;
    chatBody.appendChild(outgoingMessageDiv);
    chatBody.scrollTop = chatBody.scrollHeight;

    // Simulate bot response with thinking indicator after a delay
    setTimeout(() => {
        const messageContent = `<svg class="bot-avatar" xmlns="http://www.w3.org/2000/svg" width="50" height="50" viewBox="0 0 1024 1024">
                    <path d="M738.3 287.6H285.7c-59 0-106.8 47.8-106.8 106.8v303.1c0 59 47.8 106.8 106.8 106.8h81.5v111.1c0 .7.8 1.1 1.4.7l166.9-110.6 41.8-.8h117.4l43.6-.4c59 0 106.8-47.8 106.8-106.8V394.5c0-59-47.8-106.9-106.8-106.9zM351.7 448.2c0-29.5 23.9-53.5 53.5-53.5s53.5 23.9 53.5 53.5-23.9 53.5-53.5 53.5-53.5-23.9-53.5-53.5zm157.9 267.1c-67.8 0-123.8-47.5-132.3-109h264.6c-8.6 61.5-64.5 109-132.3 109zm110-213.7c-29.5 0-53.5-23.9-53.5-53.5s23.9-53.5 53.5-53.5 53.5 23.9 53.5 53.5-23.9 53.5-53.5 53.5zM867.2 644.5V453.1h26.5c19.4 0 35.1 15.7 35.1 35.1v121.1c0 19.4-15.7 35.1-35.1 35.1h-26.5zM95.2 609.4V488.2c0-19.4 15.7-35.1 35.1-35.1h26.5v191.3h-26.5c-19.4 0-35.1-15.7-35.1-35.1zM561.5 149.6c0 23.4-15.6 43.3-36.9 49.7v44.9h-30v-44.9c-21.4-6.5-36.9-26.3-36.9-49.7 0-28.6 23.3-51.9 51.9-51.9s51.9 23.3 51.9 51.9z"></path>
                </svg>
                <div class="message-text">
                    <div class="thinking-indicator">
                        <div class="dot"></div>
                        <div class="dot"></div>
                        <div class="dot"></div>
                    </div>
                </div>`;

        const incomingMessageDiv = createMessageElement(messageContent, "bot-message", "thinking");
        chatBody.appendChild(incomingMessageDiv);
        chatBody.scrollTo({ behavior: "smooth", top: chatBody.scrollHeight });
        generateBotResponse(incomingMessageDiv);
    }, 600);
};

// Handle Enter key press for sending messages
messageInput.addEventListener("keydown", (e) => {
    const userMessage = e.target.value.trim();
    if (e.key === "Enter" && userMessage && !e.shiftKey && window.innerWidth > 768) {
        handleOutgoingMessage(e);
    }
});

messageInput.addEventListener("input", (e) => {
    messageInput.style.height = `${initialInputHeight}px`;
    messageInput.style.height = `${messageInput.scrollHeight}px`;
    document.querySelector(".chat-form").style.boderRadius = messageInput.scrollHeight > initialInputHeight ? "15px" : "32px";
});

// Handle file input change event
fileInput.addEventListener("change", (e) => {
    const file = e.target.files[0];
    if (!file) return;
    const reader = new FileReader();
    reader.onload = (e) => {
        fileUploadWrapper.querySelector("img").src = e.target.result;
        fileUploadWrapper.classList.add("file-uploaded");
        const base64String = e.target.result.split(",")[1];

        // Store file data in userData
        userData.file = {
            data: base64String,
            mime_type: file.type
        };
        
        fileInput.value = ""; 
    };

    reader.readAsDataURL(file);
});

fileCancelButton.addEventListener("click", (e) => {
    userData.file = {};
    fileUploadWrapper.classList.remove("file-uploaded");
});

const picker = new EmojiMart.Picker({
    theme: "light",
    showSkinTones: "none",
    previewPosition: "none",
    onEmojiSelect: (emoji) => {
        const { selectionStart: start, selectionEnd: end } = messageInput;
        messageInput.setRangeText(emoji.native, start, end, "end");
        messageInput.focus();
    },
    onClickOutside: (e) => {
        if (e.target.id === "emoji-picker") {
            document.body.classList.toggle("show-emoji-picker");
        } else {
            document.body.classList.remove("show-emoji-picker");
        }
    },
});

document.querySelector(".chat-form").appendChild(picker);

fileInput.addEventListener("change", async (e) => {
    const file = e.target.files[0];
    if (!file) return;
    const validImageTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
    if (!validImageTypes.includes(file.type)) {
        await Swal.fire({
            icon: 'error',
            title: 'Lỗi',
            text: 'Chỉ chấp nhận file ảnh (JPEG, PNG, GIF, WEBP)',
            confirmButtonText: 'OK'
        });
        resetFileInput();
        return;
    }
    const reader = new FileReader();
    reader.onload = (e) => {
        fileUploadWrapper.querySelector("img").src = e.target.result;
        fileUploadWrapper.classList.add("file-uploaded");
        const base64String = e.target.result.split(",")[1];
        userData.file = {
            data: base64String,
            mime_type: file.type
        };
    };
    reader.readAsDataURL(file);
});

function resetFileInput() {
    fileInput.value = "";
    fileUploadWrapper.classList.remove("file-uploaded");
    fileUploadWrapper.querySelector("img").src = "#";
    userData.file = { data: null, mime_type: null };
    document.querySelector(".chat-form").reset();
}

sendMessageButton.addEventListener("click", (e) => handleOutgoingMessage(e));
document.querySelector("#file-upload").addEventListener("click", (e) => fileInput.click());
chatbotToggler.addEventListener("click", () => document.body.classList.toggle("show-chatbot"));
closeChatbot.addEventListener("click", () => document.body.classList.remove("show-chatbot"));