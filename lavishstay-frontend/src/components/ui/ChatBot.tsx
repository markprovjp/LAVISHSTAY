import { useState, useEffect, useRef } from 'react';
import { Input, Avatar, Button, FloatButton, Flex, Skeleton, Typography, Popover, Tag, InputRef } from 'antd';
import { useSelector } from 'react-redux';
import { RootState } from '../../store';
// MODIFIED: Import new service functions
import { getHotelContext, logChatMessage } from '../../services/chatService';
import { MessageOutlined, SendOutlined, UserOutlined, CloseOutlined } from '@ant-design/icons';
import { AnimatePresence, motion } from 'framer-motion';

const { Text } = Typography;

// --- Type Definitions ---
interface Message {
    from: 'user' | 'bot';
    text: string;
}

// --- Constants ---
const quickQuestions = [
    "Giờ nhận phòng và trả phòng?",
    "Khách sạn có những tiện nghi gì?",
    "Chính sách hủy phòng như thế nào?",
    "Các loại phòng và giá cả?",
];
const CHAT_HISTORY_KEY = 'lavishstay_chat_history';

// --- Sub-components ---
const MessageBubble = ({ msg }: { msg: Message }) => {
    const isUser = msg.from === 'user';
    const { isAuthenticated, user } = useSelector((state: RootState) => state.auth);

    return (
        <Flex gap="small" align="flex-start" justify={isUser ? 'flex-end' : 'flex-start'} className="mb-4">
            {!isUser && <Avatar src="/images/chatbot-img.png" size="large" />}
            <div
                className={`px-4 py-2 rounded-2xl max-w-[85%] text-sm shadow-sm ${isUser ? 'bg-[var(--color-primary)] text-white rounded-br-none' : 'bg-[var(--color-bg-container)] border border-[var(--color-border)] rounded-bl-none'}`}
            >
                {/* Use pre-wrap to respect newlines from the AI response */}
                <Text style={{ color: isUser ? 'white' : 'var(--color-text-base)', whiteSpace: 'pre-wrap' }}>{msg.text}</Text>
            </div>
            {isUser && (
                <Avatar
                    src={isAuthenticated ? user?.avatar : undefined}
                    icon={!isAuthenticated || !user?.avatar ? <UserOutlined /> : undefined}
                    size="large"
                    className="bg-gray-200 text-gray-600"
                />
            )}
        </Flex>
    );
};

const TypingIndicator = () => (
    <Flex gap="small" align="flex-start" justify="flex-start" className="mb-4">
        <Avatar src="/images/chatbot-img.png" size="large" />
        <div className="px-4 py-2 rounded-2xl bg-[var(--color-bg-container)] border border-[var(--color-border)] w-4/5">
            <Skeleton active paragraph={{ rows: 1, width: '100%' }} title={false} />
        </div>
    </Flex>
);

// --- Main ChatBot Component ---
const ChatBot = () => {
    const [popoverVisible, setPopoverVisible] = useState(false);
    const [inputValue, setInputValue] = useState('');
    const [isLoading, setIsLoading] = useState(false);
    const [showInitialSuggestions, setShowInitialSuggestions] = useState(true);

    const [messages, setMessages] = useState<Message[]>(() => {
        try {
            const savedHistory = localStorage.getItem(CHAT_HISTORY_KEY);
            if (savedHistory) {
                const parsed = JSON.parse(savedHistory);
                if (Array.isArray(parsed) && parsed.length > 1) {
                    setShowInitialSuggestions(false);
                }
                return parsed;
            }
        } catch (error) {
            console.error("Failed to parse chat history from localStorage", error);
        }
        return [{ from: 'bot', text: 'Xin chào! Tôi là trợ lý AI của LavishStay. Tôi có thể giúp gì cho bạn?' }];
    });

    const chatContainerRef = useRef<HTMLDivElement>(null);
    const inputRef = useRef<InputRef>(null);

    useEffect(() => {
        if (chatContainerRef.current) {
            chatContainerRef.current.scrollTo({ top: chatContainerRef.current.scrollHeight, behavior: 'smooth' });
        }
    }, [messages, isLoading]);

    useEffect(() => {
        localStorage.setItem(CHAT_HISTORY_KEY, JSON.stringify(messages));
    }, [messages]);

    useEffect(() => {
        if (!isLoading && popoverVisible && inputRef.current) {
            inputRef.current.focus();
        }
    }, [isLoading, popoverVisible]);

    // --- MODIFIED: Main logic for handling chat messages ---
    const handleSend = async (textToSend?: string) => {
        const userMessageText = (textToSend || inputValue).trim();
        if (!userMessageText) return;

        if (showInitialSuggestions) {
            setShowInitialSuggestions(false);
        }

        const userMessage: Message = { from: 'user', text: userMessageText };
        setMessages(prev => [...prev, userMessage]);
        setInputValue('');
        setIsLoading(true);

        let botResponseText = 'Rất tiếc, đã có lỗi xảy ra. Vui lòng thử lại sau.';

        try {
            // 1. Fetch hotel context from our backend
            const hotelContext = await getHotelContext();

            // 2. Construct the prompt for Gemini
            const prompt = `Dựa vào thông tin khách sạn sau đây: 

---
${hotelContext}
---

Hãy trả lời câu hỏi của khách hàng một cách chi tiết và thân thiện. Câu hỏi: "${userMessageText}"`;

            // 3. Call Gemini API
            const apiKey = import.meta.env.VITE_GEMINI_API_KEY;
            if (!apiKey) {
                throw new Error("VITE_GEMINI_API_KEY is not defined in .env file.");
            }

            // Use the v1 API endpoint and a current model like gemini-1.5-flash
            const apiUrl = `https://generativelanguage.googleapis.com/v1/models/gemini-1.5-flash:generateContent?key=${apiKey}`;

            const requestBody = {
                contents: [{
                    parts: [{ text: prompt }]
                }]
            };

            const response = await fetch(apiUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(requestBody),
            });

            if (!response.ok) {
                const errorBody = await response.json();
                console.error("Gemini API Error:", errorBody);
                throw new Error(`Gemini API request failed: ${errorBody.error?.message || response.statusText}`);
            }

            const responseData = await response.json();
            const geminiResponse = responseData.candidates[0]?.content?.parts[0]?.text;

            if (!geminiResponse) {
                throw new Error("Invalid response structure from Gemini API.");
            }
            
            botResponseText = geminiResponse;

        } catch (err) {
            console.error("Error during chat process:", err);
            // The default error message will be used.
        } finally {
            // 4. Display the bot's response
            const botMessage: Message = { from: 'bot', text: botResponseText };
            setMessages(prev => [...prev, botMessage]);
            setIsLoading(false);

            // 5. Log the conversation to the backend (fire and forget)
            logChatMessage(userMessageText, botResponseText).catch(logErr => {
                console.error("Failed to log chat message:", logErr);
            });
        }
    };

    const chatContent = (
        <div className="w-[450px] h-[70vh] max-h-[600px] flex flex-col bg-[var(--color-bg-base)]">
            <Flex align="center" justify="space-between" className="bg-[var(--color-primary)] text-white p-3 rounded-t-lg shadow-md flex-shrink-0">
                <Flex align="center" gap="middle">
                    <Avatar src="/images/chatbot-img.png" size="default" />
                    <div>
                        <Text strong style={{ color: 'white' }}>LavishStay Assistant</Text>
                        <Flex align="center" gap={4}>
                            <div className="w-2 h-2 bg-green-400 rounded-full" />
                            <Text style={{ color: 'white', opacity: 0.9 }} className="text-xs">Online</Text>
                        </Flex>
                    </div>
                </Flex>
                <Button type="text" icon={<CloseOutlined />} onClick={() => setPopoverVisible(false)} className="text-white/80 hover:text-white" />
            </Flex>

            <div ref={chatContainerRef} className="flex-1 overflow-y-auto p-4">
                {messages.map((msg, idx) => <MessageBubble key={idx} msg={msg} />)}
                <AnimatePresence>
                    {showInitialSuggestions && (
                        <motion.div exit={{ opacity: 0, height: 0, marginBottom: 0 }} transition={{ duration: 0.3 }}>
                            <Flex vertical gap="small" className="mt-4">
                                <Text type="secondary">Hoặc chọn một trong các gợi ý sau:</Text>
                                <Flex gap="small" wrap="wrap">
                                    {quickQuestions.map((q, i) => (
                                        <Tag key={i} onClick={() => handleSend(q)} className="cursor-pointer transition-all hover:scale-105 border-[var(--color-primary)] bg-blue-50 text-[var(--color-primary)]">
                                            {q}
                                        </Tag>
                                    ))}
                                </Flex>
                            </Flex>
                        </motion.div>
                    )}
                </AnimatePresence>
                {isLoading && <TypingIndicator />}
            </div>

            <div className="p-3 bg-[var(--color-bg-container)] border-t border-[var(--color-border)] flex-shrink-0">
                <Flex gap="small">
                    <Input
                        ref={inputRef}
                        placeholder="Nhập câu hỏi..."
                        value={inputValue}
                        onChange={(e) => setInputValue(e.target.value)}
                        onPressEnter={() => handleSend()}
                        disabled={isLoading}
                        size="large"
                    />
                    <Button
                        type="primary"
                        icon={<SendOutlined />}
                        size="large"
                        onClick={() => handleSend()}
                        loading={isLoading}
                        disabled={!inputValue.trim()}
                    />
                </Flex>
            </div>
        </div>
    );

    return (
        <Popover
            content={chatContent}
            trigger="click"
            open={popoverVisible}
            onOpenChange={setPopoverVisible}
            placement="topRight"
            arrow={false}
            overlayStyle={{ padding: 0, margin: 0, borderRadius: '8px', boxShadow: 'var(--box-shadow)' }}
        >
            <FloatButton
                icon={<MessageOutlined />}
                style={{ right: 24, bottom: 100 }}
                badge={{ dot: true }}
            />
        </Popover>
    );
};

export default ChatBot;